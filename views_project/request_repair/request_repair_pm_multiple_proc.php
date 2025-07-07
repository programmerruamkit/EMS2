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

if ($_POST['proc'] == 'add_multiple') {
    $vehicle_ids = $_POST['vehicle_ids'];
    $change_date = $_POST['change_date'];
    $pjsppname = $_POST['pjsppname'];
    $PJSPP_CODENAME = $_POST['pjsppcodename'];
    $ctmcomcode = $_POST['ctmcomcode'];
    $success_count = 0;
    $error_messages = [];

    error_log("Processing " . count($vehicle_ids) . " vehicles");

    if (empty($vehicle_ids) || !is_array($vehicle_ids)) {
        echo json_encode([
            'success' => false,
            'message' => 'ไม่พบข้อมูลรถที่เลือก'
        ]);
        exit;
    }
    
    if (empty($change_date)) {
        echo json_encode([
            'success' => false,
            'message' => 'กรุณาเลือกวันที่'
        ]);
        exit;
    }
    
    $n=6;
    $table = "PROJECT_SPAREPART_".$PJSPP_CODENAME;
    $CODE = "PJSPP".$PJSPP_CODENAME."_CODE"; 
    $CODENAME = "PJSPP".$PJSPP_CODENAME."_CODENAME"; 
    $VHCRG = "PJSPP".$PJSPP_CODENAME."_VHCRG"; 
    $VHCRGNM = "PJSPP".$PJSPP_CODENAME."_VHCRGNM"; 
    $LDC = "PJSPP".$PJSPP_CODENAME."_LCD";
    $CREATEBY = "PJSPP".$PJSPP_CODENAME."_CREATEBY";
    $CREATEDATE = "PJSPP".$PJSPP_CODENAME."_CREATEDATE"; 
    
    foreach ($vehicle_ids as $vehicle_id) {
        error_log("Processing vehicle ID: " . $vehicle_id);

        $stmt_check = "SELECT * FROM vwVEHICLEINFO WHERE VEHICLEINFOID = ?";
        $params_check = array($vehicle_id);
        $query_check = sqlsrv_query($conn, $stmt_check, $params_check);
        
        if ($vehicle_data = sqlsrv_fetch_array($query_check, SQLSRV_FETCH_ASSOC)) {
            $rand = "PJSPP".$PJSPP_CODENAME."_".RandNum($n);

            $stmt_insert = "INSERT INTO $table ($CODE, $CODENAME, $VHCRG, $VHCRGNM, $LDC, $CREATEBY, $CREATEDATE) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $params_insert = array(
                $rand,
                $PJSPP_CODENAME,
                $vehicle_data['VEHICLEREGISNUMBER'], 
                $vehicle_data['THAINAME'],
                $change_date, 
                $_SESSION['AD_ROLEACCOUNT_USERNAME'],
                date('Y-m-d H:i:s')
            );
            $query_insert = sqlsrv_query($conn, $stmt_insert, $params_insert);
            if ($query_insert) {
                $success_count++;
                error_log("Successfully inserted for vehicle ID: " . $vehicle_id);
            } else {
                $error_messages[] = "ไม่สามารถบันทึข้อมูล สำหรับรถ ID: " . $vehicle_id . " - " . print_r(sqlsrv_errors(), true);
                error_log("Error inserting for vehicle ID: " . $vehicle_id . " - " . $error_message);
            }
        } else {
            $error_messages = "ไม่พบข้อมูลรถ ID: " . $vehicle_id;
            $error_messages[] = $error_message;
            error_log($error_message);
        }
    }
    
    error_log("Final result: success_count=" . $success_count . ", error_count=" . count($error_messages));
    
    
    if ($success_count > 0) {
        echo json_encode([
            'success' => true,
            'count' => $success_count,
            'total' => count($vehicle_ids),
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
}
?>