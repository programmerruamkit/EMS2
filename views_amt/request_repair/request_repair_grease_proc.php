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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    (isset($_POST['proc']) && 
    ($_POST['proc'] == 'add_multiple_individual' || $_POST['proc'] == 'add_multiple'))) {

    // รับ vehicle_data จาก POST และแปลงเป็น array ถ้ายังเป็น string
    $vehicle_data = $_POST['vehicle_data'];
    if (is_string($vehicle_data)) {
        $vehicle_data = json_decode($vehicle_data, true);
    }
    $ctmcomcode = isset($_POST['ctmcomcode']) ? $_POST['ctmcomcode'] : '';
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
    
    // วนลูปประมวลผลแต่ละรายการ
    foreach($vehicle_data as $vehicle) {
        $vehicle_id = $vehicle['id'];
        $change_date = $vehicle['change_date'];
        $count_display_1 = $vehicle['count_display_1'];
        $count_display_2 = $vehicle['count_display_2'];
        $mileage = $vehicle['mileage'];

        error_log("Processing vehicle ID: " . $vehicle_id);

        // ตรวจสอบข้อมูลรถ - เปลี่ยนชื่อตัวแปร
        $stmt_check = "SELECT * FROM vwVEHICLEINFO WHERE VEHICLEINFOID = ?";
        $params_check = array($vehicle_id);
        $query_check = sqlsrv_query($conn, $stmt_check, $params_check);
        
        // เปลี่ยนชื่อตัวแปรเพื่อไม่ให้ซ้ำ
        if ($vehicle_info = sqlsrv_fetch_array($query_check, SQLSRV_FETCH_ASSOC)) {
            // สร้าง random code สำหรับแต่ละรายการ
            $rand = "RPG_".RandNum($n);

            $stmt_insert = "INSERT INTO REPAIR_GREASE (RPG_CODE, RPG_GROUP, RPG_VHCRG, RPG_VHCRGNM, RPG_LCD, RPG_CREATEBY, RPG_CREATEDATE, RPG_REMARK, RPG_COUNT, RPG_LASTMILEAGE)
                            VALUES (?,?,?,?,?,?,?,?,?,?)";
            $params_insert = array(
                $rand,
                $count_display_2, // ใช้ $count_display_2 แทน
                $vehicle_info['VEHICLEREGISNUMBER'], // ใช้ $vehicle_info แทน
                $vehicle_info['THAINAME'], // ใช้ $vehicle_info แทน
                $change_date,
                $_SESSION['AD_ROLEACCOUNT_USERNAME'],
                date('Y-m-d H:i:s'),
                '', // สมมติว่าไม่มีหมายเหตุ
                $count_display_1, // ใช้ $count_display_1 แทน
                $mileage // เพิ่ม mileage
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