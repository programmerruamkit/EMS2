<?php
// filepath: c:\AppServ\www\ENOMBAN\E-Maintenance\views_amt\satisfaction_survey\update_customer_email.php
session_name("EMS"); 
session_start();
$path = "../";   	
require($path.'../include/connect.php');

// ตั้งค่า header สำหรับ JSON response
header('Content-Type: application/json; charset=utf-8');

// ตรวจสอบการเข้าถึง
if (!isset($_SESSION["AD_AREA"])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'ไม่มีสิทธิ์เข้าถึง'
    ]);
    exit;
}

// ตรวจสอบ method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method ไม่ถูกต้อง'
    ]);
    exit;
}

// รับข้อมูลจาก POST
$customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
$new_email = isset($_POST['new_email']) ? trim($_POST['new_email']) : '';
$customer_id = isset($_POST['customer_id']) ? trim($_POST['customer_id']) : '';
$PROCESSBY = $_SESSION["AD_PERSONCODE"];
$PROCESSDATE = date("Y-m-d H:i:s");

// Validate ข้อมูล
if (empty($customer_name) || empty($new_email)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ข้อมูลไม่ครบถ้วน (ชื่อลูกค้า และ อีเมล จำเป็น)'
    ]);
    exit;
}

// ฟังก์ชันตรวจสอบรูปแบบอีเมลหลายตัว
function validateMultipleEmails($emailString) {
    if (empty(trim($emailString))) {
        return [
            'valid' => false,
            'message' => 'กรุณากรอกอีเมล'
        ];
    }
    
    // แยกอีเมลที่คั่นด้วย comma
    $emailList = array_map('trim', explode(',', $emailString));
    $invalidEmails = [];
    $validEmails = [];
    
    foreach ($emailList as $email) {
        if (!empty($email)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validEmails[] = $email;
            } else {
                $invalidEmails[] = $email;
            }
        }
    }
    
    if (empty($validEmails)) {
        return [
            'valid' => false,
            'message' => 'ไม่พบอีเมลที่ถูกต้อง'
        ];
    }
    
    if (!empty($invalidEmails)) {
        return [
            'valid' => false,
            'message' => 'รูปแบบอีเมลไม่ถูกต้อง: ' . implode(', ', $invalidEmails)
        ];
    }
    
    return [
        'valid' => true,
        'validEmails' => $validEmails,
        'totalCount' => count($validEmails),
        'emailString' => implode(',', $validEmails) // ทำความสะอาดและจัดรูปแบบใหม่
    ];
}

// ตรวจสอบรูปแบบอีเมล (รองรับหลายอีเมล)
$emailValidation = validateMultipleEmails($new_email);
if (!$emailValidation['valid']) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $emailValidation['message']
    ]);
    exit;
}

// ใช้อีเมลที่ผ่านการตรวจสอบแล้ว
$cleaned_email = $emailValidation['emailString'];
$email_count = $emailValidation['totalCount'];

try {
    // ค้นหาข้อมูลลูกค้าจากชื่อ หรือ รหัสบริษัท
    $stmt_check = "SELECT CTM_ID, CTM_MAIL, CTM_COMCODE, CTM_NAMETH, CTM_NAMEEN FROM CUSTOMER WHERE CTM_COMCODE = ? OR CTM_NAMETH = ?";
    $params_check = [$customer_name, $customer_name];
    $query_check = sqlsrv_query($conn, $stmt_check, $params_check);
    
    if ($query_check === false) {
        $errors = sqlsrv_errors();
        throw new Exception('ไม่สามารถค้นหาข้อมูลลูกค้าได้: ' . $errors[0]['message']);
    }
    
    $customer_data = sqlsrv_fetch_array($query_check, SQLSRV_FETCH_ASSOC);
    
    if (!$customer_data) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'ไม่พบข้อมูลลูกค้า: ' . htmlspecialchars($customer_name)
        ]);
        exit;
    }
    
    $old_email = $customer_data['CTM_MAIL'];
    $actual_customer_id = $customer_data['CTM_ID'];
    $customer_display_name = $customer_data['CTM_NAMETH'] ?: $customer_data['CTM_NAMEENG'];
    
    // ตรวจสอบว่าอีเมลเปลี่ยนแปลงหรือไม่
    if (trim($old_email) === trim($cleaned_email)) {
        echo json_encode([
            'success' => false,
            'message' => 'อีเมลไม่มีการเปลี่ยนแปลง'
        ]);
        exit;
    }
    
    // ไม่ต้องตรวจสอบอีเมลซ้ำ เพราะอนุญาตให้หลายคนใช้อีเมลเดียวกันได้
    // (เนื่องจากเป็นการเก็บหลายอีเมลในฟิลด์เดียว)
    
    // อัพเดทอีเมลในฐานข้อมูล (เก็บเป็น string เดียวคั่นด้วย comma)
    $stmt_update = "UPDATE CUSTOMER 
                    SET CTM_MAIL = ?, 
                        CTM_EDITBY = ?,
                        CTM_EDITDATE = ?
                    WHERE CTM_ID = ?";
    $params_update = [$cleaned_email, $PROCESSBY, $PROCESSDATE, $actual_customer_id];
    $query_update = sqlsrv_query($conn, $stmt_update, $params_update);
    
    if ($query_update === false) {
        $errors = sqlsrv_errors();
        throw new Exception('ไม่สามารถอัพเดทได้: ' . $errors[0]['message']);
    }
    
    // ตรวจสอบจำนวนแถวที่ถูกอัพเดท
    $rows_affected = sqlsrv_rows_affected($query_update);
    
    if ($rows_affected > 0) {
        // Commit transaction
        sqlsrv_commit($conn);
        
        // บันทึก Log การเปลี่ยนแปลง
        $log_data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'user_session' => $_SESSION["AD_AREA"],
            'customer_id' => $actual_customer_id,
            'customer_name' => $customer_display_name,
            'old_email' => $old_email,
            'new_email' => $cleaned_email,
            'email_count' => $email_count,
            'ip_address' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown',
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown'
        ];
        
        $log_json = json_encode($log_data, JSON_UNESCAPED_UNICODE);
        error_log("EMAIL_UPDATE_SUCCESS: " . $log_json);
        
        // ส่งผลลัพธ์สำเร็จ
        echo json_encode([
            'success' => true,
            'message' => "อัพเดทอีเมลสำเร็จ ({$email_count} อีเมล)",
            'data' => [
                'customer_id' => $actual_customer_id,
                'customer_name' => $customer_display_name,
                'old_email' => $old_email ?: 'ไม่มี',
                'new_email' => $cleaned_email,
                'email_count' => $email_count,
                'email_list' => $emailValidation['validEmails'],
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $_SESSION["AD_AREA"]
            ]
        ]);
        
    } else {
        // Rollback transaction
        sqlsrv_rollback($conn);
        
        echo json_encode([
            'success' => false,
            'message' => 'ไม่มีข้อมูลที่ถูกอัพเดท (อาจมีข้อมูลเดียวกันอยู่แล้ว)'
        ]);
    }
    
} catch (Exception $e) {
    // Rollback transaction ในกรณีเกิดข้อผิดพลาด
    sqlsrv_rollback($conn);
    
    // บันทึก error log
    error_log("EMAIL_UPDATE_ERROR: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'เกิดข้อผิดพลาดในระบบ: ' . $e->getMessage()
    ]);
    
} finally {
    // ปิดการเชื่อมต่อฐานข้อมูล
    if (isset($conn)) {
        sqlsrv_close($conn);
    }
}
?>