<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

// ตั้งค่า Content-Type เป็น JSON
header('Content-Type: application/json; charset=utf-8');

// ฟังก์ชันส่ง JSON response
function sendResponse($success, $message, $data = null) {
    $response = array(
        'success' => $success,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    );
    
    if ($data) {
        $response = array_merge($response, $data);
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

// ตรวจสอบ HTTP Method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed. กรุณาใช้ POST request');
}

// ตรวจสอบ Session
if (!isset($_SESSION["AD_PERSONCODE"]) || empty($_SESSION["AD_PERSONCODE"])) {
    sendResponse(false, 'กรุณาเข้าสู่ระบบก่อนใช้งาน');
}

try {
    // รับข้อมูลจาก POST
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $survey_url = isset($_POST['survey_url']) ? trim($_POST['survey_url']) : '';
    $repair_id = isset($_POST['repair_id']) ? trim($_POST['repair_id']) : '';
    $survey_id = isset($_POST['survey_id']) ? trim($_POST['survey_id']) : '';
    $qr_code_image = isset($_POST['qr_code_image']) ? $_POST['qr_code_image'] : '';
    $recipient_email = isset($_POST['recipient_email']) ? trim($_POST['recipient_email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : 'แบบสอบถามความพึงพอใจ';
    
    // ตรวจสอบข้อมูลที่จำเป็น
    if (empty($message)) {
        sendResponse(false, 'กรุณากรอกข้อความที่จะส่ง');
    }
    
    if (empty($survey_url)) {
        sendResponse(false, 'ไม่พบ URL แบบสอบถาม');
    }
    
    if (empty($repair_id)) {
        sendResponse(false, 'ไม่พบรหัสใบขอซ่อม');
    }
    
    if (empty($survey_id)) {
        sendResponse(false, 'ไม่พบรหัสแบบประเมิน');
    }

    // ดึงข้อมูลใบขอซ่อมจากฐานข้อมูล
    $stmt_repair = "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_ID = ?";
    $params_repair = array($repair_id);
    $query_repair = sqlsrv_query($conn, $stmt_repair, $params_repair);
    
    if (!$query_repair) {
        $errors = sqlsrv_errors();
        $error_message = "Database query error: ";
        foreach($errors as $error) {
            $error_message .= $error['message'] . " ";
        }
        sendResponse(false, 'เกิดข้อผิดพลาดในการดึงข้อมูลใบขอซ่อม: ' . $error_message);
    }
    
    $repair_data = sqlsrv_fetch_array($query_repair, SQLSRV_FETCH_ASSOC);
    if (!$repair_data) {
        sendResponse(false, 'ไม่พบข้อมูลใบขอซ่อม ID: ' . $repair_id);
    }

    // ดึงข้อมูลแบบประเมิน
    $stmt_survey = "SELECT SM_CODE, SM_NAME, SM_TARGET_GROUP FROM SURVEY_MAIN WHERE SM_ID = ? AND SM_STATUS = 'Y'";
    $params_survey = array($survey_id);
    $query_survey = sqlsrv_query($conn, $stmt_survey, $params_survey);
    
    if (!$query_survey) {
        sendResponse(false, 'เกิดข้อผิดพลาดในการดึงข้อมูลแบบประเมิน');
    }
    
    $survey_data = sqlsrv_fetch_array($query_survey, SQLSRV_FETCH_ASSOC);
    if (!$survey_data) {
        sendResponse(false, 'ไม่พบข้อมูลแบบประเมิน ID: ' . $survey_id);
    }

    // กำหนดอีเมลปลายทางถ้าไม่ได้ระบุ
    if (empty($recipient_email)) {
        // ลองหาอีเมลจากข้อมูลลูกค้า
        $customer_email = '';
        
        // ถ้าไม่พบอีเมลลูกค้า ใช้อีเมลทดสอบ
        if (empty($customer_email)) {
            $recipient_email = '-'; // อีเมลทดสอบ - ควรเปลี่ยนเป็นอีเมลจริง
            // nattakit_it@ruamkit.co.th
            // nattakit2744@gmail.com
            // independence2744@hotmail.com
        } else {
            $recipient_email = $customer_email;
        }
    }

    // เตรียมข้อมูลลูกค้า
    $customer_name = '';
    if (!empty($repair_data['RPRQ_LINEOFWORK'])) {
        $customer_name = $repair_data['RPRQ_LINEOFWORK'];
    } elseif (!empty($repair_data['RPRQ_COMPANYCASH'])) {
        $customer_name = $repair_data['RPRQ_COMPANYCASH'];
    } else {
        $customer_name = 'ลูกค้า';
    }

    // สร้างไฟล์ QR Code จาก Base64 (ถ้ามี)
    $qr_file_path = null;
    $qr_file_size = 0;
    
    if (!empty($qr_code_image) && strpos($qr_code_image, 'data:image/png;base64,') === 0) {
        // ลบ header ของ Base64
        $qr_image_data = str_replace('data:image/png;base64,', '', $qr_code_image);
        $qr_image_data = base64_decode($qr_image_data);
        
        if ($qr_image_data !== false) {
            // สร้างชื่อไฟล์ QR Code
            $qr_filename = "survey_qr_" . $repair_id . "_" . date('YmdHis') . ".png";
            $qr_file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $qr_filename;
            
            // บันทึกไฟล์ QR Code
            if (file_put_contents($qr_file_path, $qr_image_data) !== false) {
                $qr_file_size = filesize($qr_file_path);
            } else {
                // ถ้าบันทึกไฟล์ไม่ได้ ให้ลบ path
                $qr_file_path = null;
            }
        }
    }

    // เตรียม HTML email content
    $email_html = "
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body {
                font-family: 'Sarabun', Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                margin: 0;
                padding: 20px;
                // background-color: #fff;
            }
            .email-container {
                max-width: 600px;
                margin: 0 auto;
                background-color: white;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            .email-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: Black;
                padding: 30px 20px;
                text-align: center;
            }
            .email-header h1 {
                margin: 0;
                font-size: 24px;
                font-weight: bold;
            }
            .email-header p {
                margin: 10px 0 0 0;
                opacity: 0.9;
            }
            .email-body {
                padding: 30px 20px;
            }
            .survey-info {
                background-color: #fff;
                border-left: 4px solid #007bff;
                padding: 20px;
                margin: 20px 0;
                border-radius: 0 5px 5px 0;
            }
            .survey-info h3 {
                margin: 0 0 10px 0;
                color: #007bff;
                font-size: 18px;
            }
            .info-item {
                margin: 8px 0;
                padding: 5px 0;
            }
            .info-label {
                font-weight: bold;
                color: #555;
                display: inline-block;
                min-width: 120px;
            }
            .button {
                background: green;
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer; /* 👆 เพิ่มนี้ */
            }
            .survey-link {
                display: inline-block;
                background: green;
                color: Black !important;x
                text-decoration: none;
                border-radius: 15px;
                font-weight: bold;
                font-size: 20px;
                margin: 30px auto;
                box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
                transition: all 0.3s ease;
                border: 3px solid #fff;
                text-align: center;
                min-width: 300px;
                cursor: pointer; /* เพิ่มบรรทัดนี้ */
                border: none; /* เอา border default ออก */
            }
            .survey-link h1 {
                margin: 0;
                font-size: 22px;
                font-weight: bold;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
                cursor: pointer; /* 👆 เพิ่มนี้ */
            }
            .qr-section {
                text-align: center;
                margin: 30px 0;
                padding: 20px;
                background-color: #fff;
                border-radius: 10px;
            }
            .footer {
                background-color: #fff;
                padding: 20px;
                text-align: center;
                border-top: 1px solid #dee2e6;
                font-size: 14px;
                color: #6c757d;
            }
            .company-logo {
                max-width: 150px;
                margin-bottom: 15px;
            }
            @media (max-width: 600px) {
                .email-container {
                    margin: 0;
                    border-radius: 0;
                }
                .email-body {
                    padding: 20px 15px;
                }
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>
                <h3>📋 แบบสอบถามความพึงพอใจ<br>บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด</h3>
            </div>
            
            <div class='email-body'>
                <div style='white-space: pre-line; line-height: 1.6; margin-bottom: 20px;'>" . nl2br(htmlspecialchars($message)) . "</div>
                                
                <div style='text-align: center; margin: 0px 0; padding: 0px; border-radius: 15px;'>
                    <a href='" . htmlspecialchars($survey_url) . "' class='survey-link' target='_blank'>
                        <button class='button' role='button'><h1>📝 เริ่มทำแบบสอบถาม</h1></button>
                    </a>
                </div>

                <div class='survey-info'>
                    <h3>📊 ข้อมูลการใช้บริการ</h3>
                    <div class='info-item'>
                        <span class='info-label'>ลูกค้า:</span>
                        " . htmlspecialchars($customer_name) . "
                    </div>
                    <div class='info-item'>
                        <span class='info-label'>ทะเบียนรถ:</span>
                        " . htmlspecialchars(!empty($repair_data['RPRQ_REGISHEAD']) ? $repair_data['RPRQ_REGISHEAD'] : 'ไม่ระบุ') . "
                    </div>
                    <div class='info-item'>
                        <span class='info-label'>รายการซ่อม:</span>
                        " . htmlspecialchars(!empty($repair_data['RPC_DETAIL']) ? $repair_data['RPC_DETAIL'] : 'ไม่ระบุ') . "
                    </div>
                    <div class='info-item'>
                        <span class='info-label'>รหัสใบขอซ่อม:</span>
                        " . htmlspecialchars($repair_id) . "
                    </div>
                    <div class='info-item'>
                        <span class='info-label'>แบบสอบถามสำหรับ:</span>
                        " . htmlspecialchars(!empty($survey_data['SM_TARGET_GROUP']) ? $survey_data['SM_TARGET_GROUP'] : 'ไม่ระบุ') . "
                    </div>
                </div>
                
                <div style='margin-top: 30px; padding: 20px; background-color: #e8f4f8; border-radius: 8px; border-left: 4px solid #17a2b8;'>
                    <p style='margin: 0; color: #0c5460; font-size: 14px;'>
                        <strong>💡 หมายเหตุ:</strong> การประเมินนี้ใช้เวลาประมาณ 3-5 นาที และจะช่วยให้เราปรับปรุงการบริการให้ดียิ่งขึ้น
                    </p>
                </div>
            </div>
            
            <div class='footer'>
                <p><strong>บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด</strong></p>
                <p>RUAMKIT RUNGRUENG TRUCK DETAILS CO.,LTD.</p>
                <p style='margin-top: 10px; font-size: 14px;'><font color='red'>อีเมลนี้ส่งโดยอัตโนมัติ กรุณาอย่าตอบกลับ</font></p>
            </div>
        </div>
    </body>
    </html>
    ";

    // ** สำหรับการทดสอบ - บันทึก log แทนการส่งอีเมลจริง **
    
    // สร้าง log directory ถ้ายังไม่มี
    $log_dir = $path . '../views_amt/satisfaction_survey/logs/email_logs';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0777, true);
    }
    
    // สร้างชื่อไฟล์ log
    $log_filename = 'survey_email_' . date('Ymd') . '.log';
    $log_filepath = $log_dir . DIRECTORY_SEPARATOR . $log_filename;
    
    // เตรียมข้อมูล log
    $log_data = array(
        'timestamp' => date('Y-m-d H:i:s'),
        'repair_id' => $repair_id,
        'survey_id' => $survey_id,
        'customer_name' => $customer_name,
        'recipient_email' => $recipient_email,
        'subject' => $subject,
        'survey_url' => $survey_url,
        'has_qr_attachment' => ($qr_file_path !== null),
        'qr_file_size' => $qr_file_size,
        'message_length' => strlen($message),
        'user_id' => $_SESSION["AD_USER"],
        'status' => 'SUCCESS'
    );
    
    // บันทึก log
    $log_entry = "[" . date('Y-m-d H:i:s') . "] Email Sent - " . json_encode($log_data, JSON_UNESCAPED_UNICODE) . "\n";
    file_put_contents($log_filepath, $log_entry, FILE_APPEND | LOCK_EX);
    
    // ลบไฟล์ QR Code ชั่วคราว (ถ้ามี)
    if ($qr_file_path && file_exists($qr_file_path)) {
        unlink($qr_file_path);
    }
    
    // *** ส่งอีเมลจริงด้วย PHPMailer ***
    try {
        // โหลด PHPMailer
        require_once($path.'../include/PHPMailer/PHPMailerAutoload.php');
        
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet = "utf-8";
        $mail->Host = "mail.ruamkit.co.th";
        $mail->SMTPAuth = true;
        $mail->Username = "easyinfo@ruamkit.co.th";
        $mail->Password = "Ruamkit1993";
        $mail->SMTPSecure = 'TLS'; 
        $mail->Port = 587;         
        $mail->From = "easyinfo@ruamkit.co.th";
        $mail->FromName = "บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด - แบบประเมินความพึงพอใจ";
        $mail->isHTML(true); // ใช้ HTML format
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer'  => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // ผู้รับหลัก - ถ้าไม่มีอีเมลลูกค้า ใช้อีเมลทดสอบ
        if (empty($recipient_email)) {
            // สามารถเพิ่มการดึงอีเมลลูกค้าจากฐานข้อมูลได้ที่นี่
            $recipient_email = "-"; // อีเมลทดสอบ
            // nattakit_it@ruamkit.co.th
            // nattakit2744@gmail.com
            // independence2744@hotmail.com
        }
        
        $recipients = explode(',', $recipient_email);
        foreach ($recipients as $to_email) {
            $to_email = trim($to_email);
            if (filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress($to_email, $customer_name);
            }
        }
        
        // แนบ QR Code ถ้ามี
        if ($qr_file_path && file_exists($qr_file_path)) {
            $mail->addAttachment($qr_file_path, 'survey_qr_code.png', 'base64', 'image/png');
        }
        
        // ตั้งค่าเนื้อหาอีเมล
        $mail->Subject = $subject;
        $mail->Body = $email_html;
        $mail->AltBody = strip_tags(str_replace('<br>', "\n", $message));
        
        // ส่งอีเมล
        if (!$mail->send()) {
            throw new Exception('PHPMailer Error: ' . $mail->ErrorInfo);
        }
        
        // อัพเดทข้อมูล log เมื่อส่งสำเร็จ
        $log_data['email_sent'] = true;
        $log_data['email_method'] = 'PHPMailer';
        $log_data['recipients'] = $recipients;
        
    } catch (Exception $e) {
        // ลบไฟล์ QR Code ชั่วคราว (ถ้ามี) เมื่อเกิด error
        if ($qr_file_path && file_exists($qr_file_path)) {
            unlink($qr_file_path);
        }
        
        // บันทึก error log
        $error_log_data = array_merge($log_data, array(
            'error' => $e->getMessage(),
            'email_sent' => false,
            'status' => 'ERROR'
        ));
        $error_log_entry = "[" . date('Y-m-d H:i:s') . "] Email Error - " . json_encode($error_log_data, JSON_UNESCAPED_UNICODE) . "\n";
        file_put_contents($log_filepath, $error_log_entry, FILE_APPEND | LOCK_EX);
        
        sendResponse(false, 'เกิดข้อผิดพลาดในการส่งอีเมล: ' . $e->getMessage());
    }
    
    // ลบไฟล์ QR Code ชั่วคราว (ถ้ามี) หลังส่งอีเมลสำเร็จ
    if ($qr_file_path && file_exists($qr_file_path)) {
        unlink($qr_file_path);
    }
    
    // ส่งผลลัพธ์สำเร็จ
    sendResponse(true, 'ส่งแบบสอบถามผ่านอีเมลเรียบร้อยแล้ว', array(
        'repair_id' => $repair_id,
        'survey_id' => $survey_id,
        'customer_name' => $customer_name,
        'recipient_email' => implode(', ', $recipients),
        'survey_url' => $survey_url,
        'qr_file_size' => $qr_file_size > 0 ? number_format($qr_file_size / 1024, 2) . ' KB' : 'N/A',
        'has_qr_attachment' => ($qr_file_path !== null),
        'log_file' => $log_filename,
        'email_sent_via' => 'PHPMailer SMTP',
        'smtp_host' => 'mail.ruamkit.co.th'
    ));

} catch (Exception $e) {
    // จัดการ error
    error_log("Survey Email Error: " . $e->getMessage());
    sendResponse(false, 'เกิดข้อผิดพลาดภายในระบบ: ' . $e->getMessage());
}
?>