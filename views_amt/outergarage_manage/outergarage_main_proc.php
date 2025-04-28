<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];	

	if($proc=="add"){
		$OTGR_CODE = $_POST["OTGR_CODE"];
		$OTGR_NAME = $_POST["OTGR_NAME"];
		$OTGR_PHONE = $_POST["OTGR_PHONE"];
		$OTGR_ADDRESS = $_POST["OTGR_ADDRESS"];		
		$OTGR_STATUS = $_POST["OTGR_STATUS"];
		$OTGR_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$OTGR_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO OUTER_GARAGE (OTGR_CODE, OTGR_NAME, OTGR_PHONE, OTGR_ADDRESS, OTGR_STATUS, OTGR_CREATEBY, OTGR_CREATEDATE) VALUES (?,?,?,?,?,?,?)";
		$params = array($OTGR_CODE, $OTGR_NAME, $OTGR_PHONE, $OTGR_ADDRESS, $OTGR_STATUS, $OTGR_CREATEBY, $OTGR_CREATEDATE);
		
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
		$OTGR_ID = $_POST["OTGR_ID"];
		$OTGR_NAME = $_POST["OTGR_NAME"];
		$OTGR_PHONE = $_POST["OTGR_PHONE"];
		$OTGR_ADDRESS = $_POST["OTGR_ADDRESS"];
		$OTGR_STATUS = $_POST["OTGR_STATUS"];
		$OTGR_EDITBY = $_SESSION["AD_PERSONCODE"];
		$OTGR_EDITDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE OUTER_GARAGE SET 
					OTGR_NAME = ? ,
					OTGR_PHONE = ? ,
					OTGR_ADDRESS = ? ,
					OTGR_STATUS = ? ,
					OTGR_EDITBY = ?,
					OTGR_EDITDATE = ?
					WHERE OTGR_ID = ? ";
		$params = array($OTGR_NAME, $OTGR_PHONE, $OTGR_ADDRESS, $OTGR_STATUS, $OTGR_EDITBY, $OTGR_EDITDATE, $OTGR_ID);
	
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
		
		$OTGR_STATUS = 'D';
		$OTGR_EDITBY = $_SESSION["AD_PERSONCODE"];
		$OTGR_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE OUTER_GARAGE SET OTGR_STATUS = ?,OTGR_EDITBY = ?,OTGR_EDITDATE = ? WHERE OTGR_CODE = ? ";
		$params = array($OTGR_STATUS, $OTGR_EDITBY, $OTGR_EDITDATE, $id);

		// $sql = "DELETE FROM MENU
		// 		WHERE OTGR_ID = ? ";
		// $params = array($id);

		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "ลบข้อมูลเรียบร้อยแล้ว";	
		}		
	};	
?>