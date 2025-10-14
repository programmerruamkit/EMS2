<?php
session_name("EMS"); 
session_start();
$path = "../";   	
require($path.'../include/connect.php');

header('Content-Type: application/json');

function RandNum($n) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';      
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }      
    return $randomString;
}  

// แก้ไข condition นี้
if ($_POST['proc'] == 'add_multiple_individual' || $_POST['proc'] == 'add_multiple') {
    $vehicle_data = $_POST['vehicle_data'];
    $pjsppname = $_POST['pjsppname'];
    $PJSPP_CODENAME = $_POST['pjsppcodename'];
    $ctmcomcode = $_POST['ctmcomcode'];
    $success_count = 0;
    $error_messages = [];

    // เพิ่ม debug
    error_log("Processing " . count($vehicle_data) . " vehicles");

    if (empty($vehicle_data) || !is_array($vehicle_data)) {
        echo json_encode([
            'success' => false,
            'message' => 'ไม่พบข้อมูลรถที่เลือก'
        ]);
        exit;
    }
    
    // ประกาศตัวแปรที่ใช้ร่วม
    $n=6;
    $table = "PROJECT_SPAREPART_".$PJSPP_CODENAME;
    $CODE = "PJSPP".$PJSPP_CODENAME."_CODE"; 
    $CODENAME = "PJSPP".$PJSPP_CODENAME."_CODENAME"; 
    $VHCRG = "PJSPP".$PJSPP_CODENAME."_VHCRG"; 
    $VHCRGNM = "PJSPP".$PJSPP_CODENAME."_VHCRGNM"; 
    $LDC = "PJSPP".$PJSPP_CODENAME."_LCD";
    $REMARK = "PJSPP".$PJSPP_CODENAME."_REMARK";
    $CREATEBY = "PJSPP".$PJSPP_CODENAME."_CREATEBY";
    $CREATEDATE = "PJSPP".$PJSPP_CODENAME."_CREATEDATE"; 
    
    // วนลูปประมวลผลแต่ละรายการ
    foreach($vehicle_data as $vehicle) {
        $vehicle_id = $vehicle['id'];
        $change_date = $vehicle['change_date'];
        $notes = $vehicle['notes'];

        error_log("Processing vehicle ID: " . $vehicle_id);

        // ตรวจสอบข้อมูลรถ - เปลี่ยนชื่อตัวแปร
        $stmt_check = "SELECT * FROM vwVEHICLEINFO WHERE VEHICLEINFOID = ?";
        $params_check = array($vehicle_id);
        $query_check = sqlsrv_query($conn, $stmt_check, $params_check);
        
        // เปลี่ยนชื่อตัวแปรเพื่อไม่ให้ซ้ำ
        if ($vehicle_info = sqlsrv_fetch_array($query_check, SQLSRV_FETCH_ASSOC)) {
            // สร้าง random code สำหรับแต่ละรายการ
            $rand = "PJSPP".$PJSPP_CODENAME."_".RandNum($n);

            $stmt_insert = "INSERT INTO $table ($CODE, $CODENAME, $VHCRG, $VHCRGNM, $LDC, $CREATEBY, $CREATEDATE, $REMARK) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $params_insert = array(
                $rand,
                $PJSPP_CODENAME,
                $vehicle_info['VEHICLEREGISNUMBER'], // ใช้ $vehicle_info แทน
                $vehicle_info['THAINAME'], // ใช้ $vehicle_info แทน
                $change_date,
                $_SESSION['AD_ROLEACCOUNT_USERNAME'],
                date('Y-m-d H:i:s'),
                $notes
            );
            
            $query_insert = sqlsrv_query($conn, $stmt_insert, $params_insert);
            if ($query_insert) {
                $success_count++;
                error_log("Successfully inserted for vehicle ID: " . $vehicle_id);
            } else {
                $error_msg = "ไม่สามารถบันทึกข้อมูลสำหรับรถ ID: " . $vehicle_id;
                $sql_errors = sqlsrv_errors();
                if ($sql_errors) {
                    $error_msg .= " - " . print_r($sql_errors, true);
                }
                $error_messages[] = $error_msg;
                error_log("Error inserting for vehicle ID: " . $vehicle_id . " - " . print_r($sql_errors, true));
            }
        } else {
            $error_msg = "ไม่พบข้อมูลรถ ID: " . $vehicle_id;
            $error_messages[] = $error_msg;
            error_log($error_msg);
        }
        
        // ปิด connection
        if ($query_check) {
            sqlsrv_free_stmt($query_check);
        }
    }
    
    error_log("Final result: success_count=" . $success_count . ", error_count=" . count($error_messages));
    
    if ($success_count > 0) {
        echo json_encode([
            'success' => true,
            'count' => $success_count,
            'total' => count($vehicle_data),
            'pjsppname' => $pjsppname,
            'ctmcomcode' => $ctmcomcode,
            'message' => "บันทึกข้อมูลสำเร็จ {$success_count} รายการ" . 
                    (count($error_messages) > 0 ? " (มีข้อผิดพลาด " . count($error_messages) . " รายการ)" : ""),
            'errors' => $error_messages
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'ไม่สามารถบันทึกข้อมูลได้เลย',
            'errors' => $error_messages
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid procedure call'
    ]);
}
?>