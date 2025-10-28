<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();
    $proc = isset($_POST["proc"]) ? $_POST["proc"] : (isset($_GET["proc"]) ? $_GET["proc"] : "");
    $id = isset($_POST["cat_id"]) ? $_POST["cat_id"] : (isset($_GET["id"]) ? $_GET["id"] : "");
	$sm_code = isset($_POST["sm_code"]) ? $_POST["sm_code"] : (isset($_GET["sm_code"]) ? $_GET["sm_code"] : "");

    // เพิ่ม/แก้ไข/ลบ หมวดหมู่แบบประเมิน
    if($proc=="add"){
		$SC_CODE = $_POST["sc_code"];
        $SC_NAME = $_POST["cat_name"];
        $SC_ORDER = $_POST["cat_order"];
        $SC_STATUS = $_POST["cat_status"];
        $SC_AREA = $_POST["session_area"];
        $SC_CREATED_BY = $_POST["session_personcode"];
        $SC_CREATED_DATE = date("Y-m-d H:i:s");

        $sql = "INSERT INTO SURVEY_CATEGORY (SC_CODE, SC_NAME, SM_CODE, SC_ORDER, SC_STATUS, SC_AREA, SC_CREATED_BY, SC_CREATED_DATE)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$params = array($SC_CODE, $SC_NAME, $sm_code, $SC_ORDER, $SC_STATUS, $SC_AREA, $SC_CREATED_BY, $SC_CREATED_DATE);

        $stmt = sqlsrv_query($conn, $sql, $params);
        if($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            print "บันทึกข้อมูลเรียบร้อยแล้ว";
        }
    }
    ######################################################################################################
    if($proc=="edit"){
        $SC_CODE = $_POST["sc_code"];
        $SC_NAME = $_POST["cat_name"];
        $SC_ORDER = $_POST["cat_order"];
        $SC_STATUS = $_POST["cat_status"];
        $SC_AREA = $_POST["session_area"];
        $SC_EDITED_BY = $_POST["session_personcode"];
        $SC_EDITED_DATE = date("Y-m-d H:i:s");

        $sql = "UPDATE SURVEY_CATEGORY SET 
                    SC_NAME = ?,
                    SC_ORDER = ?,
                    SC_STATUS = ?,
                    SC_AREA = ?,
                    SC_EDITED_BY = ?,
                    SC_EDITED_DATE = ?
                WHERE SC_CODE = ?";
        $params = array($SC_NAME, $SC_ORDER, $SC_STATUS, $SC_AREA, $SC_EDITED_BY, $SC_EDITED_DATE, $SC_CODE);

        $stmt = sqlsrv_query($conn, $sql, $params);
        if($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            print "แก้ไขข้อมูลเรียบร้อยแล้ว";
        }
    }
    ######################################################################################################
    if($proc=="delete" && !empty($id)){
        $SC_STATUS = 'D';
        $SC_EDITED_BY = $_SESSION["AD_PERSONCODE"];
        $SC_EDITED_DATE = date("Y-m-d H:i:s");
        $sql = "UPDATE SURVEY_CATEGORY SET SC_STATUS = ?, SC_EDITED_BY = ?, SC_EDITED_DATE = ? WHERE SC_ID = ?";
        $params = array($SC_STATUS, $SC_EDITED_BY, $SC_EDITED_DATE, $id);

        $stmt = sqlsrv_query($conn, $sql, $params);
        if($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            print "ลบข้อมูลเรียบร้อยแล้ว";
        }
    }
?>