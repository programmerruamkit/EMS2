<?php
	session_name("EMS"); session_start();
	$path='../';
	require($path."include/authen.php"); 
	require($path."include/connect.php");
	require($path."include/head.php");		
	require($path."include/script.php"); 
	// print_r ($_SESSION);
	##########################################################################################################################
    
    $SESSION_AREA=$_SESSION["AD_AREA"];
    // รับพารามิเตอร์
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $menu_id = isset($_GET['menu_id']) ? $_GET['menu_id'] : '';
    
    // กำหนดชื่อหัวข้อตามประเภท
    $page_title = '';
    $filter_condition = '';
    
    switch($type) {
        case 'due_soon':
            $page_title = 'รายการถึงกำหนดเปลี่ยน (เหลือน้อยกว่า 5 วัน)';
            $filter_condition = 'due_soon';
            break;
        case 'approaching':
            $page_title = 'รายการใกล้ถึงกำหนด (เหลือน้อยกว่า 15 วัน)';
            $filter_condition = 'approaching';
            break;
        case 'overdue':
            $page_title = 'รายการเกินกำหนด (เกินมากกว่า 1 วัน)';
            $filter_condition = 'overdue';
            break;
        default:
            $page_title = 'รายการบำรุงรักษา';
    }
    
    // ฟังก์ชันดึงข้อมูลตามเงื่อนไข (ใช้ logic เดียวกับ dashboard_project.php)
    function getMaintenanceDataOptimized($conn, $SESSION_AREA, $filter_condition) {
        $result_data = array();
        
        // ดึงข้อมูลอะไหล่ทั้งหมด
        $stmt_spareparts = "SELECT DISTINCT PJSPP_NAME, PJSPP_CODENAME, PJSPP_EXPIRE_YEAR FROM [dbo].[PROJECT_SPAREPART] WHERE PJSPP_AREA = ?";
        $params_spareparts = array($SESSION_AREA);
        $query_spareparts = sqlsrv_query($conn, $stmt_spareparts, $params_spareparts);
        
        $sparepart_codes = array();
        while($sparepart = sqlsrv_fetch_array($query_spareparts, SQLSRV_FETCH_ASSOC)) {
            $sparepart_codes[] = $sparepart;
        }
        
        // ดึงข้อมูลรถทั้งหมดในครั้งเดียว
        $sql_vehicles = "SELECT DISTINCT vi.VEHICLEREGISNUMBER, vi.THAINAME, vi.REGISTYPE, vi.AFFCOMPANY
                        FROM vwVEHICLEINFO vi
                        WHERE vi.VEHICLEGROUPDESC = 'Transport' 
                        AND NOT vi.VEHICLEGROUPCODE = 'VG-1403-0755' 
                        AND vi.ACTIVESTATUS = '1'
                        AND AFFCOMPANY IN('RKR','RKS','RKL','RCC','RRC','RATC')
                        ORDER BY vi.REGISTYPE ASC, vi.AFFCOMPANY ASC, vi.VEHICLEREGISNUMBER ASC";
        
        $query_vehicles = sqlsrv_query($conn, $sql_vehicles);
        $vehicles = array();
        while($vehicle = sqlsrv_fetch_array($query_vehicles, SQLSRV_FETCH_ASSOC)) {
            $vehicles[] = $vehicle;
        }
        
        // วนลูปแยกตาม sparepart เพื่อประมวลผลแบบ batch
        foreach($sparepart_codes as $sparepart) {
            $PJSPP_NAME = $sparepart['PJSPP_NAME'];
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
            $REMARK = "PJSPP".$PJSPP_CODENAME."_REMARK";
            
            // สร้าง WHERE clause สำหรับรถทั้งหมด
            $vehicle_conditions = array();
            $params = array();
            
            foreach($vehicles as $vehicle) {
                $vehicle_conditions[] = "($VHCRG = ? OR $VHCRGNM = ?)";
                $params[] = $vehicle['VEHICLEREGISNUMBER'];
                $params[] = $vehicle['THAINAME'];
            }
            
            if (empty($vehicle_conditions)) continue;
            
            // Query ข้อมูลรถทั้งหมดสำหรับ sparepart นี้ในครั้งเดียว (ใช้ logic เดียวกับ dashboard_project.php)
            $sql_batch = "SELECT 
                            $VHCRG as VEHICLEREGISNUMBER,
                            $VHCRGNM as THAINAME,
                            $LCD as LAST_CHANGE_DATE,
                            $REMARK as REMARK,
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
                    $remarkdisplay = isset($row['REMARK']) ? $row['REMARK'] : '';
                    
                    if ($lactchangedate) {
                        // แปลงวันที่ (ใช้วิธีเดียวกับ dashboard_project.php)
                        if (is_object($lactchangedate)) {
                            $lactchangedate_display = $lactchangedate->format('d/m/Y');
                            $lactchangedate_str = $lactchangedate->format('Y-m-d');
                        } else {
                            $lactchangedate_display = date("d/m/Y", strtotime($lactchangedate));
                            $lactchangedate_str = date("Y-m-d", strtotime($lactchangedate));
                        }
                        
                        // คำนวณวันที่ครบกำหนด
                        $expire_date_str = date("Y-m-d", strtotime("+$PJSPP_EXPIRE_YEAR year", strtotime($lactchangedate_str)));
                        $expire_date_display = date("d/m/Y", strtotime($expire_date_str));
                        
                        // คำนวณจำนวนวันที่เหลือ (ใช้วิธีเดียวกับ dashboard_project.php)
                        $current_timestamp = time();
                        $expire_timestamp = strtotime($expire_date_str);
                        $day_diff = floor(($expire_timestamp - $current_timestamp) / (60 * 60 * 24));
                        
                        // หาข้อมูลรถที่ตรงกัน
                        $vehicle_info = null;
                        foreach($vehicles as $vehicle) {
                            if ($vehicle['VEHICLEREGISNUMBER'] == $row['VEHICLEREGISNUMBER'] || 
                                $vehicle['THAINAME'] == $row['THAINAME']) {
                                $vehicle_info = $vehicle;
                                break;
                            }
                        }
                        
                        // กรองข้อมูลตามเงื่อนไข (ใช้เงื่อนไขเดียวกับ dashboard_project.php)
                        $should_include = false;
                        $status_text = '';
                        $status_class = '';
                        
                        if ($filter_condition == 'due_soon' && $day_diff > 0 && $day_diff < 6) {
                            $should_include = true;
                            $status_text = "เหลือ " . $day_diff . " วัน";
                            $status_class = "text-info";
                        } else if ($filter_condition == 'approaching' && $day_diff > 5 && $day_diff < 16) {
                            $should_include = true;
                            $status_text = "เหลือ " . $day_diff . " วัน";
                            $status_class = "text-warning";
                        } else if ($filter_condition == 'overdue' && $day_diff < 0 && abs($day_diff) > 1) {
                            $should_include = true;
                            $status_text = "เกิน " . abs($day_diff) . " วัน";
                            $status_class = "text-danger";
                        }
                        
                        if ($should_include && $vehicle_info) {
                            $result_data[] = array(
                                'sparepart_name' => $PJSPP_NAME,
                                'vehicle_regis' => $vehicle_info['VEHICLEREGISNUMBER'],
                                'vehicle_name' => $vehicle_info['THAINAME'],
                                'vehicle_type' => $vehicle_info['REGISTYPE'],
                                'company' => $vehicle_info['AFFCOMPANY'],
                                'expire_year' => $PJSPP_EXPIRE_YEAR,
                                'last_change_date' => $lactchangedate_display,
                                'next_due_date' => $expire_date_display,
                                'days_diff' => $day_diff,
                                'status_text' => $status_text,
                                'status_class' => $status_class,
                                'remark' => $remarkdisplay
                            );
                        }
                    }
                }
            }
        }
        
        return $result_data;
    }
    
    // ใช้ cache สำหรับข้อมูลรายละเอียด (cache 1 นาที)
    $cache_file = sys_get_temp_dir() . '/details_data_' . $SESSION_AREA . '_' . $type . '_' . date('Y-m-d-H') . '_' . floor(date('i') / 1) . '.json';
    
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < 1800) {
        // ใช้ข้อมูลจาก cache
        $maintenance_data = json_decode(file_get_contents($cache_file), true);
    } else {
        // คำนวณข้อมูลใหม่
        $maintenance_data = getMaintenanceDataOptimized($conn, $SESSION_AREA, $filter_condition);
        // บันทึกลง cache
        file_put_contents($cache_file, json_encode($maintenance_data));
    }
    
    // เพิ่มการจัดการ refresh cache
    if (isset($_GET['refresh_cache'])) {
        // ลบไฟล์ cache ทั้งหมด
        $cache_pattern = sys_get_temp_dir() . '/details_data_' . $SESSION_AREA . '_' . $type . '_*.json';
        foreach (glob($cache_pattern) as $cache_file) {
            if (file_exists($cache_file)) {
                unlink($cache_file);
            }
        }
        // คำนวณข้อมูลใหม่
        $maintenance_data = getMaintenanceDataOptimized($conn, $SESSION_AREA, $filter_condition);
        file_put_contents($cache_file, json_encode($maintenance_data));
    }
	
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
    
    // ใช้ cache สำหรับข้อมูลการ์ด (cache 1 นาที)
    $cache_file = sys_get_temp_dir() . '/card_data_' . $SESSION_AREA . '_' . date('Y-m-d-H') . '_' . floor(date('i') / 1) . '.json';
    
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < 1800) {
        // ใช้ข้อมูลจาก cache
        $card_data = json_decode(file_get_contents($cache_file), true);
    } else {
        // คำนวณข้อมูลใหม่
        $card_data = calculateCardDataOptimized($conn, $SESSION_AREA);
        // บันทึกลง cache
        file_put_contents($cache_file, json_encode($card_data));
    }
	
?>
<script type="text/javascript">
	$(document).ready(function(e) {
		dataTable('datatable');
	});
</script>
<link rel="stylesheet" href="<?=$path;?>css/dashboard.css" />	
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
<body> 
	<?php
		// echo"<pre>";
		// print_r($_GET);
		// print_r($_POST);
		// echo"</pre>";
		// exit();
	?>
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
	<table width="100%"  height="100%"  border="0" cellpadding="0" cellspacing="0" class="no-border"> <!--main_data -->
		<tr valign="top">
			<td height="1"><?php include ($path."include/navtop.php");?></td><!-- height="18" -->
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
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
							<tr class="TOP">
								<td class="LEFT"></td>
								<td class="CENTER">
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td width="25" valign="middle" class=""><img src="../images/alert.png" width="48" height="48"></td>
											<td width="500" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;<?php echo $page_title; ?></h3></td>
											<td width="617" align="right" valign="bottom" class="" nowrap></td>
										</tr>
									</table>
								</td>
								<td class="RIGHT"></td>
							</tr>
							<tr class="CENTER">
								<td class="LEFT"></td>
								<td class="CENTER" align="center">
									<h4>จำนวนรายการทั้งหมด:</strong> <?php echo count($maintenance_data); ?> รายการ</h4>
									<?php if (count($maintenance_data) > 0): ?>
										<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
											<thead>
												<tr>
													<th>ลำดับ</th>
													<th>ประเภทอะไหล่</th>
													<th>ทะเบียนรถ</th>
													<th>ชื่อรถ</th>
													<th>ประเภทรถ</th>
													<th>บริษัท</th>
													<th>กำหนดเปลี่ยน (ปี)</th>
													<th>วันที่เปลี่ยนล่าสุด</th>
													<th>วันที่ครบกำหนด</th>
													<th>จำนวนวันที่เหลือ/เกิน</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												// เรียงข้อมูลตามจำนวนวันที่เหลือ (น้อยไปมาก)
												usort($maintenance_data, function($a, $b) {
													return $a['days_diff'] - $b['days_diff'];
												});
												
												foreach($maintenance_data as $index => $data): 
												?>
												<tr>
													<td style="text-align: center;"><?php echo ($index + 1); ?></td>
													<td><?php echo htmlspecialchars($data['sparepart_name']); ?></td>
													<td style="text-align: center;"><?php echo htmlspecialchars($data['vehicle_regis']); ?></td>
													<td><?php echo htmlspecialchars($data['vehicle_name']); ?></td>
													<td style="text-align: center;"><?php echo htmlspecialchars($data['vehicle_type']); ?></td>
													<td style="text-align: center;"><?php echo htmlspecialchars($data['company']); ?></td>
													<td style="text-align: center;"><?php echo $data['expire_year']; ?></td>
													<td style="text-align: center;"><?php echo $data['last_change_date']; ?></td>
													<td style="text-align: center;"><?php echo $data['next_due_date']; ?></td>
													<td style="text-align: center; font-weight: bold; color: <?php 
														echo ($data['days_diff'] < 0 ? '#dc3545' : ($data['days_diff'] <= 5 ? '#17a2b8' : '#ffc107')); 
													?>;">
														<?php 
														if ($data['days_diff'] < 0) {
															echo "เกิน " . abs($data['days_diff']) . " วัน";
														} else {
															echo "เหลือ " . $data['days_diff'] . " วัน";
														}
														?>
													</td>
												</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									<?php else: ?>
										<div style="text-align: center; padding: 50px; background-color: #f8f9fa; border-radius: 8px;">
											<h3>ไม่พบข้อมูล</h3>
											<p>ไม่มีรายการที่ตรงกับเงื่อนไข <?php echo $page_title; ?></p>
											<p><small>💡 ลองรีเฟรชข้อมูลหรือตรวจสอบข้อมูลในระบบ</small></p>
										</div>
									<?php endif; ?>
								</td>
								<td class="RIGHT"></td>
							</tr>
							<tr class="BOTTOM">
								<td class="LEFT">&nbsp;</td>
								<td class="CENTER">&nbsp;		
									<center>
										<input type="button" class="button_gray" value="ย้อนกลับ" onclick="window.location.href='dashboard_project.php?&menu_id=<?php echo $menu_id; ?>&refresh_cache=1';">
									</center>
								</td>
								<td class="RIGHT">&nbsp;</td>
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
</body>
</html>
<?php 
require($path."include/realtime.php");  