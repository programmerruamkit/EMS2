<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');

    $proc = $_POST["proc"];
    $SESSION_AREA = $_SESSION["AD_AREA"];
    $USER_CODE = $_SESSION["AD_PERSONCODE"];

    if($proc == "add") {
        $group_code = $_POST["group_code"];
        $template_name = $_POST["template_name"];
        $email_subject = $_POST["email_subject"];
        $email_body = $_POST["email_body"];
        $template_type = $_POST["template_type"];
        
        $sql = "INSERT INTO NOTIFY_EMAIL_TEMPLATE (GROUP_CODE, TEMPLATE_NAME, EMAIL_SUBJECT, EMAIL_BODY, TEMPLATE_TYPE, STATUS, CREATE_BY, CREATE_DATE, AREA) VALUES (?,?,?,?,?,?,?,?,?)";
        $params = array($group_code, $template_name, $email_subject, $email_body, $template_type, 'Y', $USER_CODE, date('Y-m-d H:i:s'), $SESSION_AREA);
        
        $stmt = sqlsrv_query($conn, $sql, $params);
        if($stmt === false) {
            echo "เกิดข้อผิดพลาด: " . print_r(sqlsrv_errors(), true);
        } else {
            echo "บันทึกเทมเพลตสำเร็จ";
        }
    }

    if($proc == "edit") {
        $id = $_POST["id"];
        $template_name = $_POST["template_name"];
        $email_subject = $_POST["email_subject"];
        $email_body = $_POST["email_body"];
        $template_type = $_POST["template_type"];
        
        $sql = "UPDATE NOTIFY_EMAIL_TEMPLATE SET TEMPLATE_NAME = ?, EMAIL_SUBJECT = ?, EMAIL_BODY = ?, TEMPLATE_TYPE = ?, EDIT_BY = ?, EDIT_DATE = ? WHERE ID = ?";
        $params = array($template_name, $email_subject, $email_body, $template_type, $USER_CODE, date('Y-m-d H:i:s'), $id);
        
        $stmt = sqlsrv_query($conn, $sql, $params);
        if($stmt === false) {
            echo "เกิดข้อผิดพลาด: " . print_r(sqlsrv_errors(), true);
        } else {
            echo "แก้ไขเทมเพลตสำเร็จ";
        }
    }

    if($proc == "delete") {
        $id = $_POST["id"];
        
        $sql = "UPDATE NOTIFY_EMAIL_TEMPLATE SET STATUS = 'D', EDIT_BY = ?, EDIT_DATE = ? WHERE ID = ?";
        $params = array($USER_CODE, date('Y-m-d H:i:s'), $id);
        
        $stmt = sqlsrv_query($conn, $sql, $params);
        if($stmt === false) {
            echo "เกิดข้อผิดพลาด";
        } else {
            echo "ลบเทมเพลตสำเร็จ";
        }
    }
?>