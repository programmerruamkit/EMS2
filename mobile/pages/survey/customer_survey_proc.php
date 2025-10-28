<?php
header('Content-Type: application/json; charset=utf-8');
$path = "../";
require($path.'../include/connect.php');

// เริ่มต้น response
$response = array(
    'success' => false,
    'message' => '',
    'data' => array()
);

try {
    // ตรวจสอบ method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    // รับข้อมูลจากฟอร์ม
    $survey_id = isset($_POST['survey_id']) ? $_POST['survey_id'] : '';
    $repair_id = isset($_POST['repair_id']) ? trim($_POST['repair_id']) : '';
    $survey_date = isset($_POST['survey_date']) ? $_POST['survey_date'] : '';
    $additional_comments = isset($_POST['additional_comments']) ? trim($_POST['additional_comments']) : '';
    $total_questions = isset($_POST['total_questions']) ? intval($_POST['total_questions']) : 0;

    // ตรวจสอบข้อมูลจำเป็น
    if (empty($survey_id)) {
        throw new Exception('ไม่พบรหัสแบบประเมิน');
    }
    
    if (empty($survey_date)) {
        throw new Exception('กรุณาระบุวันที่ประเมิน');
    }

    if ($total_questions <= 0) {
        throw new Exception('ไม่พบคำถามในแบบประเมิน');
    }

    // ตรวจสอบการให้คะแนน
    $ratings = array();
    $comments = array();
    $question_ids = array();
    
    for ($i = 0; $i < $total_questions; $i++) {
        $rating_key = "rating_" . $i;
        $comment_key = "comment_" . $i;
        $question_id_key = "question_id_" . $i;
        
        if (!isset($_POST[$rating_key]) || empty($_POST[$rating_key])) {
            throw new Exception('กรุณาให้คะแนนความพึงพอใจให้ครบทุกข้อ (ข้อที่ ' . ($i + 1) . ')');
        }
        
        $ratings[$i] = intval($_POST[$rating_key]);
        $comments[$i] = isset($_POST[$comment_key]) ? trim($_POST[$comment_key]) : '';
        $question_ids[$i] = isset($_POST[$question_id_key]) ? intval($_POST[$question_id_key]) : 0;
        
        // ตรวจสอบค่าคะแนน
        if ($ratings[$i] < 1 || $ratings[$i] > 5) {
            throw new Exception('คะแนนไม่ถูกต้อง (ข้อที่ ' . ($i + 1) . ')');
        }
        
        if ($question_ids[$i] <= 0) {
            throw new Exception('ไม่พบรหัสคำถาม (ข้อที่ ' . ($i + 1) . ')');
        }
    }

    // เริ่มต้น transaction
    if (sqlsrv_begin_transaction($conn) === false) {
        throw new Exception('ไม่สามารถเริ่มต้น transaction ได้');
    }

    try {
        // สร้างรหัสการตอบแบบสอบถาม
        $response_code = 'SR' . date('Ymd') . sprintf('%04d', rand(1, 9999));
        $create_date = date('Y-m-d H:i:s');
        
        // ตรวจสอบรหัสซ้ำ
        $stmt_check = "SELECT COUNT(*) as count_code FROM SURVEY_RESPONSES WHERE SR_CODE = ?";
        $params_check = array($response_code);
        $query_check = sqlsrv_query($conn, $stmt_check, $params_check);
        
        if ($query_check) {
            $check_result = sqlsrv_fetch_array($query_check, SQLSRV_FETCH_ASSOC);
            if ($check_result['count_code'] > 0) {
                // สร้างรหัสใหม่หากซ้ำ
                $response_code = 'SR' . date('Ymd') . sprintf('%04d', rand(1000, 9999));
            }
        }

        // บันทึกข้อมูลหลักของการตอบแบบสอบถาม (เฉพาะฟิลด์ที่จำเป็น)
        $stmt_response = "INSERT INTO SURVEY_RESPONSES (
            SR_CODE, SM_ID, SR_REPAIR_ID, SR_SURVEY_DATE, 
            SR_ADDITIONAL_COMMENTS, SR_CREATED_DATE, SR_CREATED_BY
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $params_response = array(
            $response_code,
            $survey_id,
            $repair_id,
            $survey_date,
            $additional_comments,
            $create_date,
            'CUSTOMER'
        );
        
        $query_response = sqlsrv_query($conn, $stmt_response, $params_response);
        
        if ($query_response === false) {
            $errors = sqlsrv_errors();
            throw new Exception('ไม่สามารถบันทึกข้อมูลหลักได้: ' . print_r($errors, true));
        }

        // ดึง ID ของ response ที่เพิ่งสร้าง
        $stmt_get_id = "SELECT SR_ID FROM SURVEY_RESPONSES WHERE SR_CODE = ?";
        $params_get_id = array($response_code);
        $query_get_id = sqlsrv_query($conn, $stmt_get_id, $params_get_id);
        
        if ($query_get_id === false) {
            throw new Exception('ไม่สามารถดึงรหัสการตอบแบบสอบถามได้');
        }
        
        $response_data = sqlsrv_fetch_array($query_get_id, SQLSRV_FETCH_ASSOC);
        $response_id = $response_data['SR_ID'];

        // บันทึกคำตอบแต่ละข้อ
        $stmt_answer = "INSERT INTO SURVEY_ANSWERS (
            SR_ID, SQ_ID, SA_RATING, SA_COMMENT, SA_CREATE_DATE
        ) VALUES (?, ?, ?, ?, ?)"; 

        for ($i = 0; $i < $total_questions; $i++) {
            $params_answer = array(
                $response_id,
                $question_ids[$i],
                $ratings[$i],
                $comments[$i],
                $create_date
            );
            
            $query_answer = sqlsrv_query($conn, $stmt_answer, $params_answer);
            
            if ($query_answer === false) {
                $errors = sqlsrv_errors();
                throw new Exception('ไม่สามารถบันทึกคำตอบข้อที่ ' . ($i + 1) . ' ได้: ' . print_r($errors, true));
            }
        }

        // Commit transaction
        if (sqlsrv_commit($conn) === false) {
            throw new Exception('ไม่สามารถ commit transaction ได้');
        }

        // สำเร็จ
        $response['success'] = true;
        $response['message'] = 'ขอบคุณสำหรับการประเมิน ข้อมูลของท่านได้รับการบันทึกเรียบร้อยแล้ว';
        $response['data'] = array(
            'response_code' => $response_code,
            'response_id' => $response_id,
            'total_questions' => $total_questions,
            'repair_id' => $repair_id,
            'survey_date' => $survey_date
        );

    } catch (Exception $e) {
        // Rollback transaction
        sqlsrv_rollback($conn);
        throw $e;
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    
    // Log error (optional)
    error_log("Customer Survey Error: " . $e->getMessage());
}

// ส่งผลลัพธ์กลับ
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit();
?>