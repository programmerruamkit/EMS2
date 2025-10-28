<?php
session_name("EMS"); session_start();
$path = "../";
require($path.'../include/connect.php');

$proc = isset($_POST["proc"]) ? $_POST["proc"] : (isset($_GET["proc"]) ? $_GET["proc"] : "");
$id = isset($_POST["sq_id"]) ? $_POST["sq_id"] : (isset($_GET["id"]) ? $_GET["id"] : "");
$sm_code = isset($_POST["sm_code"]) ? $_POST["sm_code"] : (isset($_GET["sm_code"]) ? $_GET["sm_code"] : "");

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();

if($proc=="add"){
    $SQ_QUESTION = $_POST["sq_text"];
    $SQ_CODE = $_POST["sq_code"];
    $SQ_ORDER = $_POST["sq_order"];
    $SQ_STATUS = $_POST["sq_status"];
    $SC_CODE = $_POST["sc_code"];
    $SESSION_AREA = $_POST["session_area"];
    $SESSION_PERSONCODE = $_POST["session_personcode"];
    $SQ_CREATED_DATE = date("Y-m-d H:i:s");

    $sql = "INSERT INTO SURVEY_QUESTION (SQ_CODE, SQ_QUESTION, SQ_ORDER, SQ_STATUS, SC_CODE, SQ_AREA, SQ_CREATED_BY, SQ_CREATED_DATE)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array($SQ_CODE, $SQ_QUESTION, $SQ_ORDER, $SQ_STATUS, $SC_CODE, $SESSION_AREA, $SESSION_PERSONCODE, $SQ_CREATED_DATE);

    $stmt = sqlsrv_query($conn, $sql, $params);
    if($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        print "บันทึกข้อมูลเรียบร้อยแล้ว";
    }
}
if($proc=="edit"){
    $SQ_CODE = $_POST["sq_code"];
    $SQ_QUESTION = $_POST["sq_text"];
    $SQ_ORDER = $_POST["sq_order"];
    $SQ_STATUS = $_POST["sq_status"];
    $SC_CODE = $_POST["sc_code"];
    $SESSION_AREA = $_POST["session_area"];
    $SESSION_PERSONCODE = $_POST["session_personcode"];
    $EDITDATE = date("Y-m-d H:i:s");

    $sql = "UPDATE SURVEY_QUESTION SET 
                SQ_QUESTION = ?,
                SQ_ORDER = ?,
                SQ_STATUS = ?,
                SC_CODE = ?,
                SQ_AREA = ?,
                SQ_EDITED_BY = ?,
                SQ_EDITED_DATE = ?
            WHERE SQ_CODE = ?";
    $params = array($SQ_QUESTION, $SQ_ORDER, $SQ_STATUS, $SC_CODE, $SESSION_AREA, $SESSION_PERSONCODE, $SQ_EDITED_DATE, $SQ_CODE);

    $stmt = sqlsrv_query($conn, $sql, $params);
    if($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        print "แก้ไขข้อมูลเรียบร้อยแล้ว";
    }
}
if($proc=="delete" && !empty($id)){
    $SQ_STATUS = 'D';
    $SESSION_PERSONCODE = $_SESSION["AD_PERSONCODE"];
    $SQ_EDITED_DATE = date("Y-m-d H:i:s");
    $sql = "UPDATE SURVEY_QUESTION SET SQ_STATUS = ?, SQ_EDITED_BY = ?, SQ_EDITED_DATE = ? WHERE SQ_ID = ?";
    $params = array($SQ_STATUS, $SESSION_PERSONCODE, $SQ_EDITED_DATE, $id);

    $stmt = sqlsrv_query($conn, $sql, $params);
    if($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        print "ลบข้อมูลเรียบร้อยแล้ว";
    }
}
?>