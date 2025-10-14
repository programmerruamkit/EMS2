<?php
    session_name("EMS"); session_start();
	// เพิ่มที่ตอนต้นของไฟล์ หลังจาก session_start()
	if (isset($_GET['refresh_cache'])) {
		// ลบไฟล์ cache ทั้งหมด
		$cache_pattern = sys_get_temp_dir() . '/card_data_' . $SESSION_AREA . '_*.json';
		foreach (glob($cache_pattern) as $cache_file) {
			if (file_exists($cache_file)) {
				unlink($cache_file);
			}
		}
	}
    $path='../';
    require($path."include/authen.php"); 
    require($path."include/connect.php");
    require($path."include/head.php");		
    require($path."include/script.php"); 
    $SESSION_AREA=$_SESSION["AD_AREA"];

	// ฟังก์ชันคำนวณข้อมูลการ์ดแบบ Optimized
    function calculateCardDataOptimized($conn, $SESSION_AREA) {
        $card_data = array(
            'due_soon' => 0,     // < 5 วัน
            'approaching' => 0,  // < 15 วัน
            'overdue' => 0       // > 1 วัน
        );
        
        // ดึงข้อมูลอะไหล่ทั้งหมด
        $stmt_spareparts = "SELECT DISTINCT PJSPP_CODENAME, PJSPP_EXPIRE_YEAR FROM [dbo].[PROJECT_SPAREPART] WHERE PJSPP_AREA = ?";
        $params_spareparts = array($SESSION_AREA);
        $query_spareparts = sqlsrv_query($conn, $stmt_spareparts, $params_spareparts);
        
        $sparepart_codes = array();
        while($sparepart = sqlsrv_fetch_array($query_spareparts, SQLSRV_FETCH_ASSOC)) {
            $sparepart_codes[] = $sparepart;
        }
        
        // ดึงข้อมูลรถทั้งหมดในครั้งเดียว
        $sql_vehicles = "SELECT DISTINCT vi.VEHICLEREGISNUMBER, vi.THAINAME
                        FROM vwVEHICLEINFO vi
                        WHERE vi.VEHICLEGROUPDESC = 'Transport' 
                        AND NOT vi.VEHICLEGROUPCODE = 'VG-1403-0755' 
                        AND vi.ACTIVESTATUS = '1'
                        AND AFFCOMPANY IN('RKR','RKS','RKL','RCC','RRC','RATC')";
        
        $query_vehicles = sqlsrv_query($conn, $sql_vehicles);
        $vehicles = array();
        while($vehicle = sqlsrv_fetch_array($query_vehicles, SQLSRV_FETCH_ASSOC)) {
            $vehicles[] = $vehicle;
        }
        
        // วนลูปแยกตาม sparepart เพื่อประมวลผลแบบ batch
        foreach($sparepart_codes as $sparepart) {
            $PJSPP_CODENAME = $sparepart['PJSPP_CODENAME'];
            $PJSPP_EXPIRE_YEAR = $sparepart['PJSPP_EXPIRE_YEAR'];
            
            $valid_codes = array("01","02","03","04","05","06","07","08");
            if (!in_array($PJSPP_CODENAME, $valid_codes)) continue;
            
            $table = "PROJECT_SPAREPART_".$PJSPP_CODENAME;
            $LCD = "PJSPP".$PJSPP_CODENAME."_LCD";
            $VHCRG = "PJSPP".$PJSPP_CODENAME."_VHCRG";
            $VHCRGNM = "PJSPP".$PJSPP_CODENAME."_VHCRGNM";
            $CODENAME = "PJSPP".$PJSPP_CODENAME."_CODENAME";
            $CREATEDATE = "PJSPP".$PJSPP_CODENAME."_CREATEDATE";
            
            // สร้าง WHERE clause สำหรับรถทั้งหมด
            $vehicle_conditions = array();
            $params = array();
            
            foreach($vehicles as $vehicle) {
                $vehicle_conditions[] = "($VHCRG = ? OR $VHCRGNM = ?)";
                $params[] = $vehicle['VEHICLEREGISNUMBER'];
                $params[] = $vehicle['THAINAME'];
            }
            
            if (empty($vehicle_conditions)) continue;
            
            // Query ข้อมูลรถทั้งหมดสำหรับ sparepart นี้ในครั้งเดียว
            $sql_batch = "SELECT 
                            $VHCRG as VEHICLEREGISNUMBER,
                            $VHCRGNM as THAINAME,
                            $LCD as LAST_CHANGE_DATE,
                            ROW_NUMBER() OVER (PARTITION BY $VHCRG, $VHCRGNM ORDER BY $CREATEDATE DESC) as rn
                          FROM [dbo].[$table] 
                          WHERE $CODENAME = '$PJSPP_CODENAME' 
                          AND (" . implode(' OR ', $vehicle_conditions) . ")
                          AND $LCD IS NOT NULL";
            
            $query_batch = sqlsrv_query($conn, $sql_batch, $params);
            
            if ($query_batch) {
                while($row = sqlsrv_fetch_array($query_batch, SQLSRV_FETCH_ASSOC)) {
                    // ใช้เฉพาะข้อมูลล่าสุด (rn = 1)
                    if ($row['rn'] != 1) continue;
                    
                    $lactchangedate = $row['LAST_CHANGE_DATE'];
                    
                    if ($lactchangedate) {
                        // แปลงวันที่
                        if (is_object($lactchangedate)) {
                            $lactchangedate_str = $lactchangedate->format('Y-m-d');
                        } else {
                            $lactchangedate_str = date("Y-m-d", strtotime($lactchangedate));
                        }
                        
                        // คำนวณวันที่ครบกำหนด
                        $expire_date_str = date("Y-m-d", strtotime("+$PJSPP_EXPIRE_YEAR year", strtotime($lactchangedate_str)));
                        
                        // คำนวณจำนวนวันที่เหลือ
                        $current_timestamp = time();
                        $expire_timestamp = strtotime($expire_date_str);
                        $day_diff = floor(($expire_timestamp - $current_timestamp) / (60 * 60 * 24));
                        
                        // จัดหมวดหมู่ตามเงื่อนไข
                        if ($day_diff < 0 && abs($day_diff) > 1) {
                            $card_data['overdue']++;
                        } else if ($day_diff >= 0 && $day_diff < 6) {
                            $card_data['due_soon']++;
                        } else if ($day_diff > 5 && $day_diff < 16) {
                            $card_data['approaching']++;
                        }
                    }
                }
            }
        }
        
        return $card_data;
    }
    
    // ใช้ cache สำหรับข้อมูลการ์ด (cache 30 นาที)
    $cache_file = sys_get_temp_dir() . '/card_data_' . $SESSION_AREA . '_' . date('Y-m-d-H') . '_' . floor(date('i') / 30) . '.json';
    
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < 1800) {
        // ใช้ข้อมูลจาก cache
        $card_data = json_decode(file_get_contents($cache_file), true);
    } else {
        // คำนวณข้อมูลใหม่
        $card_data = calculateCardDataOptimized($conn, $SESSION_AREA);
        // บันทึกลง cache
        file_put_contents($cache_file, json_encode($card_data));
    }
	
	// ดึงข้อมูล PROJECT_SPAREPART แบบง่าย
    $stmt_sparepart = "SELECT DISTINCT * FROM [dbo].[PROJECT_SPAREPART] WHERE PJSPP_AREA = ? ORDER BY PJSPP_ID ASC";
    $params_sparepart = array($SESSION_AREA);
    $query_sparepart = sqlsrv_query($conn, $stmt_sparepart, $params_sparepart);
    $sparepart_data = array();
    while($result_sparepart = sqlsrv_fetch_array($query_sparepart)) {
        $sparepart_data[] = $result_sparepart;
    }
    
    // ดึงข้อมูลรถสำหรับ autocomplete (จำกัด 200 รายการ)
    $wh = "AND ACTIVESTATUS = '1' AND AFFCOMPANY IN('RKR','RKS','RKL','RCC','RRC','RATC')";
    $sql_all_vehicles = "SELECT DISTINCT TOP 200 VEHICLEREGISNUMBER, THAINAME FROM vwVEHICLEINFO 
        WHERE VEHICLEGROUPDESC = 'Transport' AND NOT VEHICLEGROUPCODE = 'VG-1403-0755' $wh 
        ORDER BY VEHICLEREGISNUMBER ASC";
    $query_all_vehicles = sqlsrv_query($conn, $sql_all_vehicles);
    $all_vehicles = array();
    while($result_vehicle = sqlsrv_fetch_array($query_all_vehicles, SQLSRV_FETCH_ASSOC)) {
        $all_vehicles[] = array(
            'value' => $result_vehicle['VEHICLEREGISNUMBER'],
            'label' => $result_vehicle['VEHICLEREGISNUMBER'] . ' - ' . $result_vehicle['THAINAME']
        );
    }
    
    // ตัวแปรสำหรับการค้นหา
    $selected_vehicle = isset($_GET['vehicle_search']) ? $_GET['vehicle_search'] : '';
    $vehicle_data = null;
    
    // กำหนดปีเริ่มต้นและปีสิ้นสุด
    $yearstart = date('Y')-0;
    $yearend = date('Y')+6;
    
    // ค้นหาข้อมูลรถที่เลือก
    if (!empty($selected_vehicle)) {
        $wh = "AND ACTIVESTATUS = '1' AND AFFCOMPANY IN('RKR','RKS','RKL','RCC','RRC','RATC')";
        $sql_vehicleinfo = "SELECT TOP 1 * FROM vwVEHICLEINFO 
            LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
            LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
            WHERE VEHICLEGROUPDESC = 'Transport' AND NOT VEHICLEGROUPCODE = 'VG-1403-0755' $wh 
            AND (VEHICLEREGISNUMBER LIKE ? OR THAINAME LIKE ?)
            ORDER BY 
                CASE WHEN VEHICLEREGISNUMBER = ? THEN 1
                     WHEN THAINAME = ? THEN 2 
                     ELSE 3 END,
                REGISTYPE ASC, VEHICLEREGISNUMBER ASC";
        
        $search_term = '%' . $selected_vehicle . '%';
        $params_search = array($search_term, $search_term, $selected_vehicle, $selected_vehicle);
        $query_vehicleinfo = sqlsrv_query($conn, $sql_vehicleinfo, $params_search);
        $vehicle_data = sqlsrv_fetch_array($query_vehicleinfo, SQLSRV_FETCH_ASSOC);
    }
    
    // ดึงข้อมูล PM Standard แบบ optimized
    $pm_standard_data = array();
    $next_pm_info = null;
    
    if (!empty($selected_vehicle) && $vehicle_data) {
        $sql_pm_standard = "SELECT DISTINCT TOP 50
            A.RPRQ_REGISHEAD,
            A.RPRQ_CARNAMEHEAD,
            A.RPRQ_MILEAGEFINISH,
            A.RPRQ_CARTYPE,
            CONVERT(VARCHAR(10), B.RPATTM_PROCESS, 120) AS RPATTM_PROCESS,
            CONVERT(VARCHAR(4), B.RPATTM_PROCESS, 120) AS YEAR
            FROM REPAIRREQUEST A
            LEFT JOIN REPAIRACTUAL_TIME B ON B.RPRQ_CODE = A.RPRQ_CODE
            WHERE (A.RPRQ_REGISHEAD = ? OR A.RPRQ_CARNAMEHEAD = ?)
            AND A.RPRQ_WORKTYPE = 'PM'
            AND A.RPRQ_STATUSREQUEST = 'ซ่อมเสร็จสิ้น'
            AND CONVERT(VARCHAR(4), B.RPATTM_PROCESS, 120) BETWEEN ? AND ?
            AND B.RPATTM_PROCESS IS NOT NULL
            ORDER BY CONVERT(VARCHAR(10), B.RPATTM_PROCESS, 120) ASC";
        
        $params_pm = array($selected_vehicle, $selected_vehicle, $yearstart, $yearend);
        $query_pm_standard = sqlsrv_query($conn, $sql_pm_standard, $params_pm);
        
        while($result_pm = sqlsrv_fetch_array($query_pm_standard, SQLSRV_FETCH_ASSOC)) {
            $pm_standard_data[] = $result_pm;
        }
        
        // คำนวณ PM ครั้งถัดไป
        if (!empty($pm_standard_data)) {
            $VEHICLEREGISNUMBER = $vehicle_data['VEHICLEREGISNUMBER'];
            $THAINAME = $vehicle_data['THAINAME'];
            $VHCTMG_LINEOFWORK = $vehicle_data['VHCCT_PM'];
            
            $last_pm = end($pm_standard_data);
            $MAXMILEAGENUMBER = $last_pm['RPRQ_MILEAGEFINISH'];

            // ดึงข้อมูลไมล์ปัจจุบัน
            if($selected_vehicle == "AMT" || $selected_vehicle == "RKS" || $selected_vehicle == "RKR" || $selected_vehicle == "RKL"){     
                $sql_mileage = "SELECT TOP 1 MAXMILEAGENUMBER FROM TEMP_MILEAGE WHERE VEHICLEREGISNUMBER = ? ORDER BY CREATEDATE DESC";
                $params_mileage = array($VEHICLEREGISNUMBER);
            } else {
                $explodes = explode('(', $THAINAME);
                $THAINAME_CLEAN = trim($explodes[0]);
                $sql_mileage = "SELECT TOP 1 MAXMILEAGENUMBER FROM TEMP_MILEAGE WHERE THAINAME = ? ORDER BY CREATEDATE DESC";
                $params_mileage = array($THAINAME_CLEAN);
            }
            
            $query_mileage = sqlsrv_query($conn, $sql_mileage, $params_mileage);
            $result_mileage = sqlsrv_fetch_array($query_mileage, SQLSRV_FETCH_ASSOC); 
            
            $CURRENT_MILEAGE = $MAXMILEAGENUMBER; // Default
            if(isset($result_mileage['MAXMILEAGENUMBER'])){
                if($result_mileage['MAXMILEAGENUMBER'] > 1000000){
                    $CURRENT_MILEAGE = $result_mileage['MAXMILEAGENUMBER'] - 1000000;
                } else {
                    $CURRENT_MILEAGE = $result_mileage['MAXMILEAGENUMBER'];
                }
            }

            // คำนวณช่วงไมล์
            $fildsfind = "MLPM_MILEAGE_10K1M";
            if(($CURRENT_MILEAGE >= 1000001) && ($CURRENT_MILEAGE <= 2000000)){
                $fildsfind = "MLPM_MILEAGE_1M2M";
            } else if(($CURRENT_MILEAGE >= 2000001) && ($CURRENT_MILEAGE <= 3000000)){
                $fildsfind = "MLPM_MILEAGE_2M3M";
            }
            
            // หา PM ครั้งถัดไป
            if($VHCTMG_LINEOFWORK) {
                $sql_rankpm = "SELECT TOP 1 * FROM MILEAGESETPM WHERE MLPM_LINEOFWORK = ? AND $fildsfind > ? ORDER BY $fildsfind ASC";
                $params_rankpm = array($VHCTMG_LINEOFWORK, $CURRENT_MILEAGE);
                $query_rankpm = sqlsrv_query($conn, $sql_rankpm, $params_rankpm);
                $result_rankpm = sqlsrv_fetch_array($query_rankpm, SQLSRV_FETCH_ASSOC);
                
                if ($result_rankpm) {
                    $MLPM_MILEAGE = $result_rankpm[$fildsfind];
                    
                    if($MLPM_MILEAGE && isset($vehicle_data['VHCCT_KILOFORDAY']) && $vehicle_data['VHCCT_KILOFORDAY'] > 0) {
                        $remaining_mileage = $MLPM_MILEAGE - $CURRENT_MILEAGE;
                        $estimated_days = ceil($remaining_mileage / $vehicle_data['VHCCT_KILOFORDAY']);
                        $estimated_date = date('Y-m-d', strtotime("+$estimated_days days"));
                        
                        $next_pm_info = array(
                            'pm_name' => $result_rankpm['MLPM_NAME'],
                            'target_mileage' => $MLPM_MILEAGE,
                            'current_mileage' => $CURRENT_MILEAGE,
                            'remaining_mileage' => $remaining_mileage,
                            'estimated_days' => $estimated_days,
                            'estimated_date' => $estimated_date,
                            'km_per_day' => $vehicle_data['VHCCT_KILOFORDAY']
                        );
                    }
                }
            }
        }
    }
?>

<!-- เพิ่ม jQuery UI CSS และ JS -->
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css"> -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- เพิ่ม Loading และ Progress Bar -->
<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.7);
        display: none;
        z-index: 10000;
        justify-content: center;
        align-items: center;
    }
    
    .loading-content {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        min-width: 300px;
    }
    
    .progress-bar {
        width: 100%;
        height: 20px;
        background-color: #f0f0f0;
        border-radius: 10px;
        overflow: hidden;
        margin: 15px 0;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #007bff, #0056b3);
        width: 0%;
        transition: width 0.3s ease;
        border-radius: 10px;
    }
    
    .card-loading {
        position: relative;
        opacity: 0.6;
    }
    
    .card-loading::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.8);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .refresh-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255,255,255,0.9);
        border: 1px solid #ddd;
        border-radius: 3px;
        padding: 5px 8px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
    }
    
    .refresh-btn:hover {
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>

<!-- เพิ่ม CSS สำหรับ maintenance bar -->
<style>
	.maintenance-table {
		width: 100%;
		border-collapse: collapse;
		border: 2px solid #333;
		margin-top: 20px;
	}

	.maintenance-table th,
	.maintenance-table td {
		border: 1px solid #333;
		padding: 8px;
		text-align: left;
		vertical-align: middle;
	}

	.maintenance-type {
		width: 200px;
		font-weight: bold;
		background-color: #f9f9f9;
		border-left: 4px solid #007bff;
	}

	.vehicle-column {
		width: 200px;
		font-weight: bold;
		background-color: #f9f9f9;
	}

	.maintenance-bar {
		position: relative;
		height: 35px;
		margin: 5px 0;
		cursor: pointer;
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 0 12px;
		border-radius: 5px;
		font-size: 11px;
		font-weight: bold;
		box-shadow: 0 2px 4px rgba(0,0,0,0.1);
	}

	.maintenance-bar.normal {
		background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
		color: white;
	}

	.maintenance-bar.warning {
		background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
		color: #333;
	}

	.maintenance-bar.danger {
		background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
		color: white;
	}

	.maintenance-bar.overdue {
		background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
		color: white;
	}

	.maintenance-bar:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 8px rgba(0,0,0,0.2);
		transition: all 0.3s ease;
	}

	.date-text {
		font-size: 9px;
		font-weight: bold;
	}

	.last-date {
		color: inherit;
	}

	.next-date {
		color: inherit;
	}

	.maintenance-row {
		height: auto;
	}

	.sparepart-header {
		text-align: center;
		font-weight: bold;
		background-color: #e9ecef;
		writing-mode: vertical-lr;
		text-orientation: mixed;
		width: 80px;
		min-width: 80px;
	}

	.vehicle-info {
		font-size: 12px;
		line-height: 1.3;
	}

	.days-info {
		position: absolute;
		right: 8px;
		top: 3px;
		font-size: 8px;
		background-color: rgba(0,0,0,0.8);
		color: white;
		padding: 2px 5px;
		border-radius: 3px;
	}

	.search-form {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
    }

    .search-form h4 {
        color: #495057;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .search-input {
        width: 250px;
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
        position: relative;
    }

    .search-btn {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        margin-left: 10px;
        cursor: pointer;
        font-size: 14px;
    }

    .search-btn:hover {
        background-color: #0056b3;
    }
	
    /* Custom Autocomplete Styling */
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        z-index: 9999;
    }

    .ui-autocomplete .ui-menu-item {
        padding: 0;
        margin: 0;
        border-bottom: 1px solid #eee;
    }

    .ui-autocomplete .ui-menu-item:last-child {
        border-bottom: none;
    }

    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper {
        padding: 10px 15px;
        font-size: 14px;
        color: #333;
        text-decoration: none;
        display: block;
    }

    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper:hover,
    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper.ui-state-active {
        background-color: #007bff;
        color: white;
        border: none;
    }

    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper .vehicle-regis {
        font-weight: bold;
        color: #007bff;
    }

    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper:hover .vehicle-regis,
    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper.ui-state-active .vehicle-regis {
        color: white;
    }

    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper .vehicle-name {
        font-size: 12px;
        color: #666;
        margin-left: 10px;
    }

    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper:hover .vehicle-name,
    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper.ui-state-active .vehicle-name {
        color: #e6f3ff;
    }

    /* Loading indicator */
    .search-loading {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }

    .search-input-container {
        position: relative;
        display: inline-block;
    }

    /* เพิ่มไอคอน search */
    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 16px;
    }

    .search-input {
        padding-left: 35px;
    }

	.vehicle-profile {
		background-color: #e3f2fd;
		padding: 20px;
		border-radius: 8px;
		margin-bottom: 20px;
		border: 1px solid #90caf9;
	}

	.vehicle-profile h3 {
		color: #1565c0;
		margin-bottom: 15px;
		font-weight: bold;
	}

	.vehicle-details {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
		gap: 10px;
	}

	.vehicle-detail-item {
		background-color: white;
		padding: 8px 12px;
		border-radius: 4px;
		border-left: 4px solid #2196f3;
		font-size: 14px;
	}

	/* Timeline Table */
	.timeline-table {
		width: 100%;
		border-collapse: collapse;
		margin-top: 20px;
		box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	}

	.timeline-table th,
	.timeline-table td {
		border: 1px solid #333;
		padding: 8px;
		text-align: center;
		vertical-align: middle;
		position: relative;
	}

	.timeline-table th {
		background-color: #b3d9ff;
		font-weight: bold;
		font-size: 14px;
	}

	.maintenance-type-cell {
		background-color: #e3f2fd;
		text-align: left !important;
		padding: 10px;
		font-weight: bold;
		width: 200px;
		vertical-align: top;
	}

	.maintenance-type-cell .sub-text {
		font-size: 12px;
		color: #666;
		font-weight: normal;
		/* margin-top: 5px; */
	}

	.year-column {
		width: 120px;
		background-color: #f5f5f5;
		font-weight: bold;
	}

	.timeline-cell {
		width: 120px;
		height: 40px;
		position: relative;
		padding: 2px;
		background-color: #fafafa;
	}

	.timeline-cell:hover {
		background-color: #f0f0f0;
	}

	/* Spanning Timeline Bar */
	.timeline-bar-container {
		position: absolute;
		top: 0;
		height: 100%;
		z-index: 10;
		pointer-events: none;
	}

	.timeline-bar-span {
		position: absolute;
		height: 30px;
		top: 5px;
		/* border-radius: 15px; */
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 10px;
		font-weight: bold;
		cursor: pointer;
		pointer-events: auto;
		transition: all 0.3s ease;
		box-shadow: 0 2px 4px rgba(0,0,0,0.2);
		border: 1px solid rgba(255,255,255,0.3);
	}

	.timeline-bar-span:hover {
		transform: scale(1.02);
		box-shadow: 0 4px 8px rgba(0,0,0,0.3);
	}

	.timeline-bar-span.normal {
		background-color: #28a745;
		/* background: linear-gradient(135deg, #28a745 0%, #20c997 100%); */
		color: white;
	}

	.timeline-bar-span.warning {
		background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
		color: #333;
	}

	.timeline-bar-span.danger {
		background: linear-gradient(135deg, #dc3545 0%, #c62828 100%);
		color: white;
	}

	.timeline-bar-span.overdue {
		background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
		color: white;
	}

	/* Point markers */
	.timeline-point {
		position: absolute;
		width: 10px;
		height: 10px;
		border-radius: 50%;
		top: 50%;
		transform: translateY(-50%);
		z-index: 15;
		cursor: pointer;
		border: 2px solid white;
		box-shadow: 0 2px 4px rgba(0,0,0,0.2);
	}

	.timeline-point.start {
		background-color: #4CAF50;
		left: 3px;
	}

	.timeline-point.end {
		background-color: #f44336;
		right: 3px;
	}

	.timeline-point:hover {
		transform: translateY(-50%) scale(1.3);
	}

	/* Timeline dates */
	.timeline-date {
		position: absolute;
		font-size: 8px;
		font-weight: bold;
		color: white;
		text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
		z-index: 12;
		white-space: nowrap;
	}

	.timeline-date.start {
		left: 3px;
		top: 2px;
	}

	.timeline-date.end {
		right: 3px;
		top: 2px;
	}

	.no-data {
		text-align: center;
		color: #6c757d;
		font-style: italic;
		padding: 40px;
	}

	.alert {
		padding: 15px;
		margin-bottom: 20px;
		border: 1px solid transparent;
		border-radius: 4px;
	}

	.alert-info {
		color: #0c5460;
		background-color: #d1ecf1;
		border-color: #bee5eb;
	}

	.last-change-cell {
		background-color: #fff3cd;
		border-left: 4px solid #ffc107;
	}

	/* เพิ่ม CSS สำหรับ year indicators */
	.year-indicator {
		transition: all 0.3s ease;
	}

	.year-indicator:hover {
		transform: translateX(-50%) scale(1.2);
		box-shadow: 0 2px 4px rgba(0,0,0,0.3);
	}

	/* ปรับปรุง timeline cells */
	.timeline-cell {
		width: 120px;
		height: 40px;
		position: relative;
		padding: 2px;
		background-color: #fafafa;
	}

	.timeline-cell:hover {
		background-color: #f0f0f0;
	}

	/* เพิ่ม border สำหรับแยกปี */
	.timeline-cell:first-of-type {
		border-left: 2px solid #007bff;
	}

	.timeline-cell:last-of-type {
		border-right: 2px solid #007bff;
	}
	/* เพิ่ม CSS สำหรับ PM Standard */
	.pm-standard-row {
		background-color: #f8f9fa;
		/* border-top: 2px solid #007bff; */
	}

	.pm-standard-row .maintenance-type-cell {
		background-color: #e7f3ff;
		/* border-left: 4px solid #007bff; */
	}

	.pm-bar-span {
		position: absolute;
		height: 28px;
		top: 6px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 0 8px;
		font-size: 9px;
		font-weight: bold;
		cursor: pointer;
		pointer-events: auto;
		transition: all 0.3s ease;
		background: #B22222;
		color: white;
		border-radius: 3px;
		overflow: hidden;
	}

	.pm-bar-span:hover {
		transform: scale(1.02);
		box-shadow: 0 4px 8px rgba(0,0,0,0.3);
	}

	.pm-milestone {
		display: flex;
		flex-direction: column;
		align-items: center;
		font-size: 8px;
		line-height: 1;
		margin: 0 2px;
	}

	.pm-milestone .mileage {
		font-weight: bold;
		color: #FFFFFF;
	}

	.pm-milestone .date {
		font-size: 8px;
		color: #FFFFFF;
	}

	.pm-milestone.next-pm {
		display: flex;
		flex-direction: column;
		align-items: center;
		font-size: 8px;
		line-height: 1;
		margin: 0;
		padding: 2px;
	}

	.pm-milestone.next-pm .mileage {
		font-weight: bold;
		color: #FFD700;
		font-size: 8px;
	}

	.pm-milestone.next-pm .date {
		font-size: 8px;
		color: #FFD700;
	}
</style>

<link rel="stylesheet" href="<?=$path;?>css/dashboard.css" />	
<body> 
	<!-- Loading Overlay -->
	<div class="loading-overlay" id="loadingOverlay">
		<div class="loading-content">
			<h4>กำลังโหลดข้อมูล...</h4>
			<div class="progress-bar">
				<div class="progress-fill" id="progressFill"></div>
			</div>
			<p id="loadingText">กำลังเตรียมข้อมูล...</p>
		</div>
	</div>
    <table width="100%"  height="100%"  border="0" cellpadding="0" cellspacing="0" class="no-border">
        <tr valign="top">
            <td height="1"><?php include ($path."include/navtop.php");?></td>
        </tr>
		<tr valign="top">
			<td>
				<br>
				<div class="row">
					<div class="col-12">
						<div class="row">
							<div class="col-1">&nbsp;</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<div class="col-2">
								<a href="<?=$path?>manage/dashboard_project.php?menu_id=dashboard">							
									<div class="small-box bg-success">
										<div class="inner">
											<h3><h4><font color='white' size='4'>Timeline</font></h4></h3>
											<b><font color='white' size='4'>การบำรุงรักษารถ</font></b>						
										</div>
										<div class="small-box-footer"><b><font color='white' size='3'>จัดการ</font></b></div>
									</div>
								</a>
							</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;					
							<div class="col-2">
								<div style="position: relative;">
									<a href="dashboard_project_view.php?type=due_soon&menu_id=dashboard">
										<div class="small-box bg-info">
											<div class="inner">
												<h3><font color='white'><?php echo $card_data['due_soon']; ?></font></h3>
												<b><font color='white' size='4'>ถึงกำหนดเปลี่ยน</font></b>
												<b><font color='white' size='1'> < 5 วัน</font></b>
											</div>
											<div class="small-box-footer"><b><font color='white' size='3'>จัดการ</font></b></div>
										</div>
									</a>
									<button class="refresh-btn" onclick="refreshCardData()" title="รีเฟรชข้อมูล">
										🔄 รีเฟรช
									</button>
								</div>
							</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<div class="col-2"> 
								<a href="dashboard_project_view.php?type=approaching&menu_id=dashboard">	
									<div class="small-box bg-warning">
										<div class="inner">
											<h3><font color='white'><?php echo $card_data['approaching']; ?></font></h3>
											<b><font color='white' size='4'>ใกล้ถึงกำหนด</font></b>
											<b><font color='white' size='1'> < 15 วัน</font></b>
										</div>
										<div class="small-box-footer"><b><font color='white' size='3'>จัดการ</font></b></div>
									</div>
								</a>
							</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<div class="col-2">
								<a href="dashboard_project_view.php?type=overdue&menu_id=dashboard">	
									<div class="small-box bg-danger">
										<div class="inner">
											<h3><font color='white'><?php echo $card_data['overdue']; ?></font></h3>
											<b><font color='white' size='4'>เกินกำหนด</font></b>
											<b><font color='white' size='1'> > 1 วัน</font></b>
										</div>
										<div class="small-box-footer"><b><font color='white' size='3'>จัดการ</font></b></div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" id="main">
							<tr>
								<td width="86%" valign="top" id="td_detail">
									<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
										<tr class="TOP">
											<td class="LEFT"></td>
											<td class="CENTER">
												<table width="100%" border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td width="25" valign="middle" class=""><img src="../images/88.png" width="48" height="48"></td>
														<td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;Timeline การบำรุงรักษารถ</h3></td>
														<td width="617" align="right" valign="bottom" class="" nowrap></td>
													</tr>
												</table>
											</td>
											<td class="RIGHT"></td>
										</tr>
										<tr class="CENTER">
											<td class="LEFT"></td>
											<td class="CENTER" align="center">
												
												<!-- ฟอร์มค้นหาพร้อม Autocomplete -->
												<div class="search-form">
													<h4>🔍 ค้นหารถ</h4>
													<form method="GET" action="" id="searchForm">
														<input type="hidden" name="menu_id" value="dashboard">
														<div class="search-input-container">
															<i class="search-icon">🔍</i>
															<input type="text" 
																	name="vehicle_search" 
																	id="vehicle_search" 
																	class="search-input" 
																	placeholder="ป้อนทะเบียนรถ หรือ ชื่อรถ..." 
																	value="<?php echo htmlspecialchars($selected_vehicle); ?>"
																	autocomplete="off">
															<div class="search-loading">
																<i class="fa fa-spinner fa-spin"></i>
															</div>
														</div>
														<!-- <button type="submit" class="search-btn"><i class="icon-search"></i> ค้นหา</button> -->
														<?php if (!empty($selected_vehicle)): ?>
															&emsp;
															<a href="<?php echo $path; ?>manage/dashboard_project.php?menu_id=dashboard">
																<button class="bg-color-red font-white" type="button" style="background-color: #dc3545;">❌ ล้างการค้นหา</button>
															</a>
														<?php endif; ?>
													</form>
												</div>

												<?php if (!empty($selected_vehicle)): ?>
													<?php if ($vehicle_data): ?>
														<!-- ส่วนแสดงข้อมูลรถและ Timeline เหมือนเดิม... -->
														<div class="vehicle-profile">
															<h3>🚗 ข้อมูลรถ</h3>
															<div class="vehicle-details">
																<div class="vehicle-detail-item">
																	<strong>ทะเบียน:</strong> <?php echo $vehicle_data['VEHICLEREGISNUMBER']; ?>
																</div>
																<div class="vehicle-detail-item">
																	<strong>ชื่อรถ:</strong> <?php echo $vehicle_data['THAINAME']; ?>
																</div>
																<div class="vehicle-detail-item">
																	<strong>ประเภท:</strong> <?php echo $vehicle_data['REGISTYPE']; ?>
																</div>
																<div class="vehicle-detail-item">
																	<strong>บริษัท:</strong> <?php echo $vehicle_data['AFFCOMPANY']; ?>
																</div>
															</div>
														</div>
														<table width="100%" border="0" cellpadding="0" cellspacing="0">
															<tr>
																<td width="617" align="right" valign="bottom" class="" nowrap>
																	<div class="toolbar">
																			<?php date_default_timezone_set('Asia/Bangkok'); ?>
																		<span style="font-size: 20px; margin-right: 20px;">
																			Date: <?php echo date("d/m/Y"); ?>
																		</span>
																	</div>
																</td>
															</tr>
														</table>
														<!-- Timeline การบำรุงรักษา -->
														<table class="timeline-table">
															<thead>
																<tr>
																	<th align="center" >ประเภทการบำรุงรักษา</th>
																	<th >วันที่เปลี่ยนล่าสุด</th>
																	<th >วันที่ครบกำหนด</th>
																	<th class="year-column" id="last-change-header">ครั้งล่าสุด</th>														
																	<?php
																	for($year = $yearstart; $year <= $yearend; $year++): ?>
																		<th class="year-column"><?php echo $year; ?></th>
																	<?php endfor; ?>
																	<th>จำนวนวันที่เหลือ</th>
																</tr>
															</thead>
															<tbody>
																<!-- แถว PM Standard -->
																<tr class="timeline-row pm-standard-row" data-row="pm">
																	<td class="maintenance-type-cell">
																		<strong>PM Standard</strong>
																		<div class="sub-text">การบำรุงรักษาตามกำหนด</div>
																	</td>
																	<td>
																		<?php 
																		if (!empty($pm_standard_data)) {
																			$last_pm = end($pm_standard_data);
																			$last_pm_date = date('d/m/Y', strtotime($last_pm['RPATTM_PROCESS']));
																			echo $last_pm_date;
																		} else {
																			echo "-";
																		}
																		?>
																	</td>
																	<td>
																		<?php 
																		if($next_pm_info) {
																			echo date('d/m/Y', strtotime($next_pm_info['estimated_date']));
																		} else {
																			echo "-";
																		}
																		?>
																	</td>
																	<td class="timeline-cell" id="last-change-pm"></td>
																	
																	<?php for($year = $yearstart; $year <= $yearend; $year++): ?>
																		<td class="timeline-cell" id="cell-pm-<?php echo $year; ?>">
																			<!-- Empty cell for PM bar -->
																		</td>
																	<?php endfor; ?>
																	
																	<td style="font-weight: bold; color: <?php 
																		if($next_pm_info) {
																			if($next_pm_info['estimated_days'] <= 30) {
																				echo '#ffc107'; // Warning
																			} else if($next_pm_info['estimated_days'] <= 7) {
																				echo '#dc3545'; // Danger
																			} else {
																				echo '#28a745'; // Normal
																			}
																		} else {
																			echo '#6c757d'; // Gray
																		}
																	?>;">
																		<?php 
																		if($next_pm_info) {
																			if($next_pm_info['estimated_days'] > 0) {
																				echo "เหลือ " . $next_pm_info['estimated_days'] . " วัน";
																			} else {
																				echo "เกิน " . abs($next_pm_info['estimated_days']) . " วัน";
																			}
																		} else {
																			echo "-";
																		}
																		?>
																	</td>
																	
																	<!-- Script สำหรับสร้าง PM bar -->
																	<?php if (!empty($pm_standard_data)): ?>
																	<script>
																		document.addEventListener('DOMContentLoaded', function() {
																			setTimeout(function() {
																				createPMStandardBar(
																					<?php echo json_encode($pm_standard_data); ?>,
																					'<?php echo $vehicle_data['VEHICLEREGISNUMBER']; ?>',
																					'<?php echo $vehicle_data['THAINAME']; ?>',
																					<?php echo $yearstart; ?>,
																					<?php echo $yearend; ?>,
																					<?php echo json_encode($next_pm_info); ?>
																				);
																			}, 100);
																		});
																	</script>
																	<?php endif; ?>
																</tr>

																<!-- แถวอะไหล่ต่างๆ เหมือนเดิม -->
																<?php foreach($sparepart_data as $index => $sparepart): ?>
																<tr class="timeline-row" data-row="<?php echo $index; ?>">
																<td class="maintenance-type-cell">
																	<strong><?php echo $sparepart['PJSPP_NAME']; ?></strong>
																	<div class="sub-text">อายุการใช้งาน: <?php echo $sparepart['PJSPP_EXPIRE_YEAR']; ?> ปี</div>
																</td>

																	<?php
																		// ใช้สูตรจาก request_repair_sparepart_form.php
																		$PJSPP_CODENAME = $sparepart['PJSPP_CODENAME'];
																		$PJSPP_EXPIRE_YEAR = $sparepart['PJSPP_EXPIRE_YEAR'];
																		$VEHICLEREGISNUMBER = $vehicle_data['VEHICLEREGISNUMBER'];
																		$THAINAME = $vehicle_data['THAINAME'];
																		
																		$lactchangedate_display = "-";
																		$PJSPP_EXPIRE_DATE = "-";
																		$days_text = "-";
																		$day_diff = 0;
																		$has_data = false;
																		$lactchangedate = null;
																		$expire_date_str = null;
																		
																		$valid_codes = ["01","02","03","04","05","06","07","08"];
																		if (in_array($PJSPP_CODENAME, $valid_codes)) {
																			$table = "PROJECT_SPAREPART_".$PJSPP_CODENAME;
																			$CODENAME = "PJSPP".$PJSPP_CODENAME."_CODENAME"; 
																			$VHCRG = "PJSPP".$PJSPP_CODENAME."_VHCRG"; 
																			$VHCRGNM = "PJSPP".$PJSPP_CODENAME."_VHCRGNM"; 
																			$CREATEDATE = "PJSPP".$PJSPP_CODENAME."_CREATEDATE"; 
																			$LCD = "PJSPP".$PJSPP_CODENAME."_LCD";
																			$REMARK = "PJSPP".$PJSPP_CODENAME."_REMARK";
																			
																			// ดึงข้อมูลจากตาราง PROJECT_SPAREPART_CODENAME
																			$sql_sparepart = "SELECT TOP 1 * FROM [dbo].[$table] WHERE $CODENAME = '$PJSPP_CODENAME' AND ($VHCRG = '$VEHICLEREGISNUMBER' OR $VHCRGNM = '$THAINAME') ORDER BY $CREATEDATE DESC";
																			$query_sparepart_detail = sqlsrv_query($conn, $sql_sparepart);
																			$result_sparepart_detail = sqlsrv_fetch_array($query_sparepart_detail, SQLSRV_FETCH_ASSOC);

																			if($result_sparepart_detail && isset($result_sparepart_detail[$LCD]) && $result_sparepart_detail[$LCD] != null){
																				$lactchangedate = $result_sparepart_detail[$LCD];
																				$lactchangedate_display = date("d/m/Y", strtotime($lactchangedate));
																				
																				// วันที่ครบกำหนด
																				$expire_date_str = date("Y-m-d", strtotime("+$PJSPP_EXPIRE_YEAR year", strtotime($lactchangedate)));
																				$PJSPP_EXPIRE_DATE = date("d/m/Y", strtotime($expire_date_str));
																				
																				// คำนวณจำนวนวันระหว่างวันนี้กับวันครบกำหนด
																				$date1 = date_create(date("Y-m-d"));
																				$date2 = date_create($expire_date_str);
																				$diff = date_diff($date1, $date2);
																				$day_diff = (int)$diff->format("%R%a");
																				
																				if ($day_diff < 0) {
																					$days_text = "เกิน " . abs($day_diff) . " วัน";
																				} else {
																					$days_text = "เหลือ " . $day_diff . " วัน";
																				}
																				
																				$has_data = true;
											
																				$remarkdisplay = $result_sparepart_detail['PJSPP'.$PJSPP_CODENAME.'_REMARK']; 
																			}
																		}
																	?>
																	
																	<td><?php echo $lactchangedate_display; ?></td>
																	<td><?php echo $PJSPP_EXPIRE_DATE; ?></td>
																	<td class="timeline-cell" id="last-change-<?php echo $index; ?>"></td>
																	
																	<?php // แสดงแต่ละปี $yearstart-$yearend
																	for($year = $yearstart; $year <= $yearend; $year++): ?>
																		<td class="timeline-cell" id="cell-<?php echo $index; ?>-<?php echo $year; ?>">
																			<!-- Empty cell for spanning bar -->
																		</td>
																	<?php endfor; ?>
																	
																	<td style="font-weight: bold; color: <?php echo ($day_diff < 0 ? '#dc3545' : ($day_diff <= 30 ? '#ffc107' : '#28a745')); ?> !important;">
																		<?php echo $days_text; ?>
																	</td>
																	
																	<?php if ($has_data): ?>
																	<script>
																		// สร้าง spanning bar สำหรับแถวนี้
																		document.addEventListener('DOMContentLoaded', function() {
																			setTimeout(function() {
																				createSpanningBar(
																					<?php echo $index; ?>,
																					'<?php echo $sparepart['PJSPP_NAME']; ?>',
																					'<?php echo $lactchangedate_display; ?>',
																					'<?php echo $PJSPP_EXPIRE_DATE; ?>',
																					'<?php echo $VEHICLEREGISNUMBER; ?>',
																					'<?php echo $THAINAME; ?>',
																					<?php echo $day_diff; ?>,
																					<?php echo $yearstart; ?>,
																					<?php echo $yearend; ?>,
																					'<?php echo $remarkdisplay; ?>'
																				);
																			}, 100);
																		});
																	</script>
																	<?php endif; ?>
																</tr>
																<?php endforeach; ?>
															</tbody>
														</table>                                            
													<?php else: ?>
														<div class="alert alert-info">
															<strong>ไม่พบข้อมูล!</strong> ไม่พบรถที่ค้นหา "<?php echo htmlspecialchars($selected_vehicle); ?>"
														</div>
													<?php endif; ?>
												<?php else: ?>
													<div class="no-data">
														<h4>🔍 กรุณาค้นหารถที่ต้องการดู Timeline การบำรุงรักษา</h4>
														<p>เริ่มพิมพ์ทะเบียนรถ หรือ ชื่อรถ ในช่องค้นหาด้านบน</p>
														<p><small>💡 ระบบจะแสดงรายการรถที่ตรงกันขณะที่คุณพิมพ์</small></p>
													</div>
												<?php endif; ?>
												
											</td>
											<td class="RIGHT"></td>
										</tr>
										<tr class="BOTTOM">
											<td class="LEFT">&nbsp;</td>
											<td class="CENTER">&nbsp;		
												<center>
													<input type="button" class="button_gray" value="รีเฟรช" onclick="javascript:window.location.reload();">
												</center>
											</td>
											<td class="RIGHT">&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</td>
		</tr>			
        <tr valign="bottom">
            <td class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br" height="5">&nbsp;</td>
        </tr>
    </table>

	<script>
		// ฟังก์ชัน Loading
		function showLoading(text) {
			document.getElementById('loadingOverlay').style.display = 'flex';
			document.getElementById('loadingText').textContent = text || 'กำลังโหลดข้อมูล...';
			document.getElementById('progressFill').style.width = '0%';
		}

		function updateProgress(percent, text) {
			document.getElementById('progressFill').style.width = percent + '%';
			if (text) document.getElementById('loadingText').textContent = text;
		}

		function hideLoading() {
			document.getElementById('loadingOverlay').style.display = 'none';
		}

		// ฟังก์ชัน Refresh ข้อมูลการ์ด
		function refreshCardData() {
			showLoading('กำลังรีเฟรชข้อมูล...');
			
			// จำลองการลบ cache
			updateProgress(30, 'กำลังล้างข้อมูลเก่า...');
			
			setTimeout(function() {
				updateProgress(60, 'กำลังคำนวณข้อมูลใหม่...');
				
				// เพิ่ม parameter เพื่อบังคับให้คำนวณใหม่
				const url = new URL(window.location);
				url.searchParams.set('refresh_cache', '1');
				url.searchParams.set('t', Date.now());
				
				setTimeout(function() {
					updateProgress(100, 'เสร็จสิ้น!');
					window.location.href = url.toString();
				}, 500);
			}, 500);
		}

		// Auto-hide loading เมื่อโหลดเสร็จ
		document.addEventListener('DOMContentLoaded', function() {
			hideLoading();
		});

		// เพิ่ม event สำหรับแสดง loading เมื่อ submit form
		document.addEventListener('DOMContentLoaded', function() {
			const searchForm = document.getElementById('searchForm');
			if (searchForm) {
				searchForm.addEventListener('submit', function() {
					showLoading('กำลังค้นหาข้อมูลรถ...');
				});
			}
		});
	</script>

    <script>
        // ข้อมูลรถสำหรับ autocomplete
        var vehicleData = <?php echo json_encode($all_vehicles); ?>;
        
        // เก็บข้อมูล bars ที่สร้างแล้ว
        var createdBars = [];
        var pmBar = null;
		
		$(document).ready(function() {
			// Initialize autocomplete
			$("#vehicle_search").autocomplete({
				source: function(request, response) {
					// Show loading indicator
					$('.search-loading').show();
					
					// Filter data based on user input
					var filtered = vehicleData.filter(function(item) {
						return item.label.toLowerCase().indexOf(request.term.toLowerCase()) !== -1;
					});
					
					// Limit results to 10 items
					filtered = filtered.slice(0, 10);
					
					// Hide loading indicator
					setTimeout(function() {
						$('.search-loading').hide();
						response(filtered);
					}, 200);
				},
				minLength: 1,
				delay: 300,
				select: function(event, ui) {
					// Set the selected value and submit form
					$(this).val(ui.item.value);
					$('#searchForm').submit();
					return false;
				},
				focus: function(event, ui) {
					// Prevent value change on focus
					return false;
				}
			}).data("ui-autocomplete")._renderItem = function(ul, item) {
				// Custom rendering for autocomplete items
				var parts = item.label.split(' - ');
				var vehicleRegis = parts[0];
				var vehicleName = parts[1] || '';
				
				return $("<li>")
					.append("<div class='ui-menu-item-wrapper'>" +
						"<span class='vehicle-regis'>" + vehicleRegis + "</span>" +
						"<span class='vehicle-name'>" + vehicleName + "</span>" +
						"</div>")
					.appendTo(ul);
			};
			
			// Handle form submission
			$('#searchForm').on('submit', function(e) {
				var searchValue = $('#vehicle_search').val().trim();
				if (searchValue === '') {
					e.preventDefault();
					alert('กรุณาป้อนทะเบียนรถ หรือ ชื่อรถ');
					return false;
				}
			});
			
			// Handle Enter key
			$('#vehicle_search').on('keypress', function(e) {
				if (e.which === 13) {
					$('#searchForm').submit();
				}
			});
			
			// เพิ่ม event listener สำหรับ resize
			$(window).on('resize', function() {
				repositionAllBars();
			});
			
			// เพิ่ม mutation observer สำหรับ table changes
			if (document.querySelector('.timeline-table')) {
				var observer = new MutationObserver(function(mutations) {
					mutations.forEach(function(mutation) {
						if (mutation.type === 'childList' || mutation.type === 'attributes') {
							setTimeout(repositionAllBars, 100);
						}
					});
				});
				
				observer.observe(document.querySelector('.timeline-table'), {
					childList: true,
					subtree: true,
					attributes: true,
					attributeFilter: ['style', 'class']
				});
			}
		});
        
        // ฟังก์ชันจัดตำแหน่ง bars ใหม่
        function repositionAllBars() {
            // จัดตำแหน่ง maintenance bars
            createdBars.forEach(function(barInfo) {
                updateBarPosition(barInfo);
            });
            
            // จัดตำแหน่ง PM bar
            if (pmBar) {
                updatePMBarPosition(pmBar);
            }
        }
        
        // ฟังก์ชันอัพเดตตำแหน่ง maintenance bar
        function updateBarPosition(barInfo) {
            const { rowIndex, startYear, endYear, barContainer } = barInfo;
            
            const startCell = document.getElementById(`last-change-${rowIndex}`);
            let actualEndCell = null;
            
            if (endYear >= <?php echo $yearstart; ?> && endYear <= <?php echo $yearend; ?>) {
                actualEndCell = document.getElementById(`cell-${rowIndex}-${endYear}`);
            } else if (endYear < <?php echo $yearstart; ?>) {
                actualEndCell = startCell;
            } else {
                actualEndCell = document.getElementById(`cell-${rowIndex}-<?php echo $yearend; ?>`);
            }
            
            if (!startCell || !actualEndCell || !barContainer) return;
            
            const startCellRect = startCell.getBoundingClientRect();
            const endCellRect = actualEndCell.getBoundingClientRect();
            const tableRect = startCell.closest('table').getBoundingClientRect();
            
            const left = startCellRect.left - tableRect.left;
            const width = endCellRect.right - startCellRect.left;
            
            barContainer.style.left = left + 'px';
            barContainer.style.width = width + 'px';
        }
        
        // ฟังก์ชันอัพเดตตำแหน่ง PM bar
        function updatePMBarPosition(pmBarInfo) {
            const { barContainer, yearStart, yearEnd } = pmBarInfo;
            
            const startCell = document.getElementById('last-change-pm');
            const endCell = document.getElementById(`cell-pm-${yearEnd}`);
            
            if (!startCell || !endCell || !barContainer) return;
            
            const startCellRect = startCell.getBoundingClientRect();
            const endCellRect = endCell.getBoundingClientRect();
            const tableRect = startCell.closest('table').getBoundingClientRect();
            
            const left = startCellRect.left - tableRect.left;
            const width = endCellRect.right - startCellRect.left;
            
            barContainer.style.left = left + 'px';
            barContainer.style.width = width + 'px';
        }

        function createSpanningBar(rowIndex, maintenanceType, lastDate, nextDate, vehicleRegis, vehicleName, daysDiff, yearStart, yearEnd, remark) {
			// หาตำแหน่งของ cell เริ่มต้น (คอลัมน์ "ครั้งล่าสุด")
			const startCell = document.getElementById(`last-change-${rowIndex}`);
			
			if (!startCell) return;
			
			// คำนวณปีเริ่มต้นและสิ้นสุดจากวันที่
			const startDateParts = lastDate.split('/');
			const endDateParts = nextDate.split('/');
			
			if (startDateParts.length !== 3 || endDateParts.length !== 3) return;
			
			const startYear = parseInt(startDateParts[2]);
			const endYear = parseInt(endDateParts[2]);
			
			// หาตำแหน่งของคอลัมน์ปีที่ต้องการ
			let actualStartCell = startCell;
			let actualEndCell = null;
			
			// ตรวจสอบว่าปีสิ้นสุดอยู่ในช่วง yearstart-yearEnd หรือไม่
			if (endYear >= yearStart && endYear <= yearEnd) {
				// ถ้าปีสิ้นสุดอยู่ในช่วง ให้ใช้คอลัมน์ปีนั้น
				actualEndCell = document.getElementById(`cell-${rowIndex}-${endYear}`);
			} else if (endYear < yearStart) {
				// ถ้าปีสิ้นสุดเป็นก่อนปี yearStart ให้แสดงเฉพาะคอลัมน์ "ครั้งล่าสุด"
				actualEndCell = startCell;
			} else {
				// ถ้าปีสิ้นสุดเกิน yearEnd ให้ใช้คอลัมน์ yearEnd
				actualEndCell = document.getElementById(`cell-${rowIndex}-${yearEnd}`);
			}
			
			// ถ้าไม่มี cell ที่ต้องการ ให้ใช้ค่าเริ่มต้น
			if (!actualStartCell) actualStartCell = startCell;
			if (!actualEndCell) actualEndCell = startCell;
			
			if (!actualStartCell || !actualEndCell) return;
			
			// คำนวณตำแหน่งและขนาดของ bar
			const startCellRect = actualStartCell.getBoundingClientRect();
			const endCellRect = actualEndCell.getBoundingClientRect();
			const tableRect = actualStartCell.closest('table').getBoundingClientRect();
			
			const left = startCellRect.left - tableRect.left;
			const width = endCellRect.right - startCellRect.left;
			
			// กำหนดสีตามสถานะ
			let barClass = 'normal';
			if (daysDiff < 0) {
				barClass = 'danger';
			} else if (daysDiff <= 30) {
				barClass = 'warning';
			} else if (daysDiff <= 90) {
				barClass = 'normal';
			}
			
			// สร้าง spanning bar
			const barContainer = document.createElement('div');
			barContainer.className = 'timeline-bar-container';
			barContainer.style.left = left + 'px';
			barContainer.style.width = width + 'px';
			
			const bar = document.createElement('div');
			bar.className = `timeline-bar-span ${barClass}`;
			bar.style.width = '100%';
			bar.style.left = '0px';
			
			// เพิ่ม event listener สำหรับ click
			bar.addEventListener('click', function() {
				showMaintenanceDetails(vehicleRegis, maintenanceType, lastDate, nextDate, daysDiff, vehicleName, remark);
			});
			
			// สร้าง point markers
			const startPoint = document.createElement('div');
			// startPoint.className = 'timeline-point start';
			// startPoint.title = `เริ่ม: ${lastDate}`;
			
			const endPoint = document.createElement('div');
			// endPoint.className = 'timeline-point end';
			// endPoint.title = `สิ้นสุด: ${nextDate}`;
			
			// สร้าง date labels
			const startDate = document.createElement('div');
			startDate.className = 'timeline-date start';
			startDate.textContent = lastDate;
			
			const endDate = document.createElement('div');
			endDate.className = 'timeline-date end';
			endDate.textContent = nextDate;
			
			// เพิ่มข้อมูลปีในกลาง bar
			const yearInfo = document.createElement('div');
			yearInfo.style.position = 'absolute';
			yearInfo.style.top = '50%';
			yearInfo.style.left = '50%';
			yearInfo.style.transform = 'translate(-50%, -50%)';
			yearInfo.style.fontSize = '9px';
			yearInfo.style.fontWeight = 'bold';
			yearInfo.style.color = barClass === 'warning' ? '#333' : 'white';
			yearInfo.style.textShadow = '1px 1px 2px rgba(0,0,0,0.5)';
			
			// เพิ่ม elements เข้าไปใน bar
			bar.appendChild(startPoint);
			bar.appendChild(endPoint);
			bar.appendChild(startDate);
			bar.appendChild(endDate);
			bar.appendChild(yearInfo);
			
			barContainer.appendChild(bar);
			
			// เพิ่ม bar เข้าไปในแถว
			const row = document.querySelector(`tr[data-row="${rowIndex}"]`);
			if (row) {
				row.style.position = 'relative';
				row.appendChild(barContainer);
			}
			
			// เก็บข้อมูล bar ไว้สำหรับ reposition
			createdBars.push({
				rowIndex: rowIndex,
				startYear: startYear,
				endYear: endYear,
				barContainer: barContainer,
				maintenanceType: maintenanceType,
				lastDate: lastDate,
				nextDate: nextDate,
				vehicleRegis: vehicleRegis,
				vehicleName: vehicleName,
				daysDiff: daysDiff
			});
        }

        function showMaintenanceDetails(vehicleRegis, maintenanceType, lastDate, nextDate, daysDiff, vehicleName, remark) {
            let statusText = '';
            let statusColor = '';
            let iconType = '';
            
            if (daysDiff < 0) {
                statusText = 'เกินกำหนดแล้ว ' + Math.abs(daysDiff) + ' วัน';
                statusColor = '#dc3545';
                iconType = 'warning';
            } else if (daysDiff <= 30) {
                statusText = 'ใกล้ครบกำหนด (เหลือ ' + daysDiff + ' วัน)';
                statusColor = '#ffc107';
                iconType = 'warning';
            } else if (daysDiff <= 90) {
                statusText = 'ปกติ (เหลือ ' + daysDiff + ' วัน)';
                statusColor = '#28a745';
                iconType = 'success';
            } else {
                statusText = 'ปกติ (เหลือ ' + daysDiff + ' วัน)';
                statusColor = '#28a745';
                iconType = 'success';
            }
            
            // แยกปีจากวันที่
            const startDateParts = lastDate.split('/');
            const endDateParts = nextDate.split('/');
            const startYear = startDateParts[2];
            const endYear = endDateParts[2];
            
            Swal.fire({
                title: '📋 รายละเอียดการบำรุงรักษา',
                html: `
                    <div style="text-align: left; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
                        <div style="margin-bottom: 15px; padding: 10px; background-color: white; border-radius: 5px;">
                            <strong>🚗 ทะเบียนรถ:</strong> ${vehicleRegis} / <strong>ชื่อรถ:</strong> ${vehicleName}
                        </div>
                        <div style="margin-bottom: 15px; padding: 10px; background-color: white; border-radius: 5px;">
                            <strong>🔧 ประเภทอะไหล่:</strong> ${maintenanceType}
                        </div>
                        <div style="margin-bottom: 15px; padding: 10px; background-color: white; border-radius: 5px;">
                            <strong>📅 วันที่เปลี่ยนล่าสุด:</strong> ${lastDate} (ปี ${startYear})
                        </div>
                        <div style="margin-bottom: 15px; padding: 10px; background-color: white; border-radius: 5px;">
                            <strong>⏰ วันที่กำหนดเปลี่ยนครั้งถัดไป:</strong> ${nextDate} (ปี ${endYear})
                        </div>
						<div style="margin-bottom: 15px; padding: 10px; background-color: white; border-radius: 5px;">
							<strong>💬 หมายเหตุ:</strong> ${remark ? remark : ''}
						</div>
                        <div style="padding: 15px; background-color: white; border-radius: 5px; border-left: 4px solid ${statusColor};">
                            <strong>📈 สถานะ:</strong> <span style="color: ${statusColor}; font-weight: bold;">${statusText}</span>
                        </div>
                    </div>
                `,
                // icon: iconType,
                confirmButtonText: 'ปิด',
                width: '650px',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }

        // เพิ่มฟังก์ชันสำหรับสร้าง PM Standard Bar
		function createPMStandardBar(pmData, vehicleRegis, vehicleName, yearStart, yearEnd, nextPmInfo) {
			if (!pmData || pmData.length === 0) return;
			
			// หาตำแหน่งของ cell เริ่มต้น
			const startCell = document.getElementById('last-change-pm');
			const endCell = document.getElementById(`cell-pm-${yearEnd}`);
			
			if (!startCell || !endCell) return;
			
			// คำนวณตำแหน่งและขนาดของ bar
			const startCellRect = startCell.getBoundingClientRect();
			const endCellRect = endCell.getBoundingClientRect();
			const tableRect = startCell.closest('table').getBoundingClientRect();
			
			const left = startCellRect.left - tableRect.left;
			const width = endCellRect.right - startCellRect.left;
			
			// สร้าง spanning bar
			const barContainer = document.createElement('div');
			barContainer.className = 'timeline-bar-container';
			barContainer.style.left = left + 'px';
			barContainer.style.width = width + 'px';
			
			const bar = document.createElement('div');
			bar.className = 'pm-bar-span';
			bar.style.width = '100%';
			bar.style.left = '0px';
			
			// เพิ่ม event listener สำหรับ click
			bar.addEventListener('click', function() {
				showPMStandardDetails(pmData, vehicleRegis, vehicleName, nextPmInfo);
			});
			
			// สร้าง milestone markers
			const milestones = pmData.filter(pm => {
				const pmYear = new Date(pm.RPATTM_PROCESS).getFullYear();
				return pmYear >= yearStart && pmYear <= yearEnd;
			});
			
			milestones.forEach((pm, index) => {
				const milestone = document.createElement('div');
				milestone.className = 'pm-milestone';
				milestone.style.position = 'absolute';
				// milestone.style.left = `${(index + 1) * (100 / (milestones.length + 1))}%`;
				milestone.style.left = `${(index + 1) * (100 / (milestones.length + 2))}%`;
				milestone.style.transform = 'translateX(-50%)';
				milestone.style.top = '50%';
				milestone.style.transform += ' translateY(-50%)';
				
				const mileageSpan = document.createElement('div');
				mileageSpan.className = 'mileage';
				mileageSpan.textContent = (pm.RPRQ_MILEAGEFINISH / 1000).toFixed(0) + 'k';
				
				const dateSpan = document.createElement('div');
				dateSpan.className = 'date';
				
				// แปลงวันที่ให้มี leading zero
				const pmDateObj = new Date(pm.RPATTM_PROCESS);
				const day = String(pmDateObj.getDate()).padStart(2, '0');
				const month = String(pmDateObj.getMonth() + 1).padStart(2, '0');
				dateSpan.textContent = `${day}/${month}`;
				
				milestone.appendChild(mileageSpan);
				milestone.appendChild(dateSpan);
				bar.appendChild(milestone);
			});
    
			// เพิ่มข้อมูล PM ครั้งถัดไปเป็น milestone
			if (nextPmInfo) {
				const nextMilestone = document.createElement('div');
				nextMilestone.className = 'pm-milestone next-pm';
				nextMilestone.style.position = 'absolute';
				nextMilestone.style.right = '10px';
				nextMilestone.style.top = '50%';
				nextMilestone.style.transform = 'translateY(-50%)';
				
				const nextMileageSpan = document.createElement('div');
				nextMileageSpan.className = 'mileage';
				nextMileageSpan.textContent = `ถัดไป: ${(nextPmInfo.target_mileage / 1000).toFixed(0)}k`;
				
				const nextDateSpan = document.createElement('div');
				nextDateSpan.className = 'date';
				
				// แปลงวันที่ครบกำหนด PM ครั้งถัดไป
				const nextDateObj = new Date(nextPmInfo.estimated_date);
				const nextDay = String(nextDateObj.getDate()).padStart(2, '0');
				const nextMonth = String(nextDateObj.getMonth() + 1).padStart(2, '0');
				nextDateSpan.textContent = `${nextDay}/${nextMonth} (${nextPmInfo.estimated_days})`;
				
				nextMilestone.appendChild(nextMileageSpan);
				nextMilestone.appendChild(nextDateSpan);
				bar.appendChild(nextMilestone);
			}

			barContainer.appendChild(bar);
			
			// เพิ่ม bar เข้าไปในแถว
			const row = document.querySelector('.pm-standard-row');
			if (row) {
				row.style.position = 'relative';
				row.appendChild(barContainer);
			}
			
			// เก็บข้อมูล PM bar ไว้สำหรับ reposition
			pmBar = {
				barContainer: barContainer,
				yearStart: yearStart,
				yearEnd: yearEnd,
				pmData: pmData,
				vehicleRegis: vehicleRegis,
				vehicleName: vehicleName,
				nextPmInfo: nextPmInfo
			};
		}
		
		function showPMStandardDetails(pmData, vehicleRegis, vehicleName, nextPmInfo) {
			// ฟังก์ชันแปลงเดือนเป็นชื่อย่อ
			function getThaiMonthName(month) {
				const months = [
					'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
					'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'
				];
				return months[month];
			}
			
			// สร้างรายละเอียด PM
			let pmDetails = '';
			pmData.forEach((pm, index) => {
				const pmDateObj = new Date(pm.RPATTM_PROCESS);
				const day = pmDateObj.getDate();
				const month = getThaiMonthName(pmDateObj.getMonth());
				const year = pmDateObj.getFullYear() + 543;
				const pmDate = `${day} ${month} ${year}`;
				
				const mileage = parseInt(pm.RPRQ_MILEAGEFINISH).toLocaleString();
				
				pmDetails += `
					<div style="margin-bottom: 8px; padding: 8px; background-color: white; border-radius: 4px; border-left: 3px solid #007bff;">
						<strong>ครั้งที่ ${index + 1}:</strong> ${pmDate} | 
						<strong>ไมล์:</strong> ${mileage} กม.
					</div>
				`;
			});
			
			const lastPM = pmData[pmData.length - 1];
			const lastPMDateObj = new Date(lastPM.RPATTM_PROCESS);
			const lastDay = lastPMDateObj.getDate();
			const lastMonth = getThaiMonthName(lastPMDateObj.getMonth());
			const lastYear = lastPMDateObj.getFullYear() + 543;
			const lastPMDate = `${lastDay} ${lastMonth} ${lastYear}`;
			const lastMileage = parseInt(lastPM.RPRQ_MILEAGEFINISH).toLocaleString();
			
			// ข้อมูล PM ครั้งถัดไป
			let nextPmSection = '';
			if (nextPmInfo) {
				const nextDateObj = new Date(nextPmInfo.estimated_date);
				const nextDay = nextDateObj.getDate();
				const nextMonth = getThaiMonthName(nextDateObj.getMonth());
				const nextYear = nextDateObj.getFullYear() + 543;
				const nextPmDate = `${nextDay} ${nextMonth} ${nextYear}`;
				
				let statusColor = '#28a745';
				let statusText = 'ปกติ';
				
				if (nextPmInfo.estimated_days <= 7) {
					statusColor = '#dc3545';
					statusText = 'เร่งด่วน';
				} else if (nextPmInfo.estimated_days <= 30) {
					statusColor = '#FF8C00';
					statusText = 'ใกล้ครบกำหนด';
				}
				
				nextPmSection = `
					<div style="margin-bottom: 15px; padding: 15px; background-color: white; border-radius: 5px; border: 2px solid ${statusColor};">
						<h4 style="color: ${statusColor}; margin-bottom: 10px;">🔧 PM ครั้งถัดไป</h4>
						<div style="margin-bottom: 8px;">
							<strong>รอบ PM:</strong> ${nextPmInfo.pm_name} <br>
						</div>
						<div style="margin-bottom: 8px;">
							<strong>ไมล์เป้าหมาย:</strong> ${parseInt(nextPmInfo.target_mileage).toLocaleString()} กม.
						</div>
						<div style="margin-bottom: 8px;">
							<strong>ไมล์ปัจจุบัน:</strong> ${parseInt(nextPmInfo.current_mileage).toLocaleString()} กม.
						</div>
						<div style="margin-bottom: 8px;">
							<strong>ไมล์ที่เหลือ:</strong> ${parseInt(nextPmInfo.remaining_mileage).toLocaleString()} กม.
						</div>
						<div style="margin-bottom: 8px;">
							<strong>วันที่คาดว่าจะถึง:</strong> <span style="color: ${statusColor}; font-weight: bold;">${nextPmDate}</span>
						</div>
						<div style="margin-bottom: 8px;">
							<strong>จำนวนวันที่เหลือ:</strong> <span style="color: ${statusColor}; font-weight: bold;">${nextPmInfo.estimated_days} วัน (${statusText})</span>
						</div>
						<div style="font-size: 12px; color: #666;">
							<em>* คำนวณจากอัตรา ${parseInt(nextPmInfo.km_per_day).toLocaleString()} กม./วัน</em>
						</div>
					</div>
				`;
			}
			
			Swal.fire({
				title: '🔧 รายละเอียด PM Standard',
				html: `
					<div style="text-align: left; padding: 20px; background-color: #f8f9fa; border-radius: 8px; max-height: 500px; overflow-y: auto;">
						<div style="margin-bottom: 8px; padding: 10px; background-color: white; border-radius: 5px;">
							<strong>🚗 ทะเบียนรถ:</strong> ${vehicleRegis} / <strong>ชื่อรถ:</strong> ${vehicleName} 
						</div>
						<div style="margin-bottom: 8px; padding: 10px; background-color: white; border-radius: 5px;">
							<strong>📊 สรุป:</strong> ทำ PM ทั้งหมด ${pmData.length} ครั้ง
						</div>
						<div style="margin-bottom: 8px; padding: 10px; background-color: white; border-radius: 5px;">
							<strong>📅 PM ครั้งล่าสุด:</strong> ${lastPMDate} |  
							<strong>ไมล์:</strong> ${lastMileage} กม.
						</div>
						${nextPmSection}
						<div style="margin-bottom: 15px;">
							<strong>📋 ประวัติ PM ทั้งหมด:</strong>
						</div>
						<div style="max-height: 200px; overflow-y: auto;">
							${pmDetails}
						</div>
					</div>
				`,
				confirmButtonText: 'ปิด',
				width: '800px',
				showClass: {
					popup: 'animate__animated animate__fadeInDown'
				},
				hideClass: {
					popup: 'animate__animated animate__fadeOutUp'
				}
			});
		}
    </script>
</body>
</html>