<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];	
	$SESSION_AREA = $_SESSION["AD_AREA"];

	if($proc=="add"){
		$GROUP_CODE = $_POST["GROUP_CODE"];
		$GROUP_NAME = "";
		// $GROUP_NAME = $_POST["GROUP_NAME"];
		$NOTIFY_TYPE = $_POST["NOTIFY_TYPE"];
		$EMAIL_LIST = $_POST["EMAIL_LIST"];
		$SCHEDULE_TYPE = $_POST["SCHEDULE_TYPE"];
		$SCHEDULE_VALUE = $_POST["SCHEDULE_VALUE"];
		$NOTIFY_TIME = $_POST["NOTIFY_TIME"];
		$STATUS = $_POST["STATUS"];
		$AREA = $SESSION_AREA;
		$CREATE_BY = $_SESSION["AD_PERSONCODE"];
		$CREATE_DATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO NOTIFY_EMAIL_GROUP (GROUP_CODE, GROUP_NAME, NOTIFY_TYPE, EMAIL_LIST, SCHEDULE_TYPE, SCHEDULE_VALUE, NOTIFY_TIME, AREA, STATUS, CREATE_BY, CREATE_DATE) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
		$params = array($GROUP_CODE, $GROUP_NAME, $NOTIFY_TYPE, $EMAIL_LIST, $SCHEDULE_TYPE, $SCHEDULE_VALUE, $NOTIFY_TIME, $AREA, $STATUS, $CREATE_BY, $CREATE_DATE);
		
		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "บันทึกข้อมูลเรียบร้อยแล้ว";	
		}	
	};
	######################################################################################################
	if($proc=="edit"){
		$ID = $_POST["ID"];
		$GROUP_NAME = "";
		// $GROUP_NAME = $_POST["GROUP_NAME"];
		$NOTIFY_TYPE = $_POST["NOTIFY_TYPE"];
		$EMAIL_LIST = $_POST["EMAIL_LIST"];
		$SCHEDULE_TYPE = $_POST["SCHEDULE_TYPE"];
		$SCHEDULE_VALUE = $_POST["SCHEDULE_VALUE"];
		$NOTIFY_TIME = $_POST["NOTIFY_TIME"];
		$STATUS = $_POST["STATUS"];
		$EDIT_BY = $_SESSION["AD_PERSONCODE"];
		$EDIT_DATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE NOTIFY_EMAIL_GROUP SET 
					GROUP_NAME = ?,
					NOTIFY_TYPE = ?,
					EMAIL_LIST = ?,
					SCHEDULE_TYPE = ?,
					SCHEDULE_VALUE = ?,
					NOTIFY_TIME = ?,
					STATUS = ?,
					EDIT_BY = ?,
					EDIT_DATE = ?
					WHERE ID = ?";
		$params = array($GROUP_NAME, $NOTIFY_TYPE, $EMAIL_LIST, $SCHEDULE_TYPE, $SCHEDULE_VALUE, $NOTIFY_TIME, $STATUS, $EDIT_BY, $EDIT_DATE, $ID);
	
		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "แก้ไขข้อมูลเรียบร้อยแล้ว";	
		}		
	};
	######################################################################################################
	if($proc=="delete" && !empty($id)){
		
		$STATUS = 'D';
		$EDIT_BY = $_SESSION["AD_PERSONCODE"];
		$EDIT_DATE = date("Y-m-d H:i:s");
		$sql = "UPDATE NOTIFY_EMAIL_GROUP SET STATUS = ?, EDIT_BY = ?, EDIT_DATE = ? WHERE GROUP_CODE = ?";
		$params = array($STATUS, $EDIT_BY, $EDIT_DATE, $id);

		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "ลบข้อมูลเรียบร้อยแล้ว";	
		}		
	};	
	if($proc=="toggle_status"){
    $group_code = $_POST["group_code"];
    $status = $_POST["status"];
    $EDIT_BY = $_SESSION["AD_PERSONCODE"];
    $EDIT_DATE = date("Y-m-d H:i:s");

    $sql = "UPDATE NOTIFY_EMAIL_GROUP SET STATUS = ?, EDIT_BY = ?, EDIT_DATE = ? WHERE GROUP_CODE = ? AND AREA = ?";
    $params = array($status, $EDIT_BY, $EDIT_DATE, $group_code, $SESSION_AREA);

    $stmt = sqlsrv_query($conn, $sql, $params);
    if($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        print "เปลี่ยนสถานะเรียบร้อยแล้ว";
    }
};
?>