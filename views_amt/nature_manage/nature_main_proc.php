<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];	
	$NTRP_AREA = $_POST["area"];

	if($proc=="add"){
		$NTRP_CODE = $_POST["ntrp_code"];
		$NTRP_NAME = $_POST["NTRP_NAME"];
		$NTRP_LEVEL = "1";
		$NTRP_PARENT = "0";
		$NTRP_REMARK = $_POST["NTRP_REMARK"];
		$NTRP_STATUS = $_POST["NTRP_STATUS"];
		$NTRP_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$NTRP_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO NATUREREPAIR (NTRP_CODE, NTRP_NAME, NTRP_LEVEL, NTRP_PARENT,  NTRP_REMARK, NTRP_STATUS, NTRP_CREATEBY, NTRP_CREATEDATE, NTRP_AREA) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$params = array($NTRP_CODE, $NTRP_NAME, $NTRP_LEVEL, $NTRP_PARENT, $NTRP_REMARK, $NTRP_STATUS, $NTRP_CREATEBY, $NTRP_CREATEDATE, $NTRP_AREA);
		
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
		$NTRP_ID = $_POST["NTRP_ID"];
		$NTRP_NAME = $_POST["NTRP_NAME"];
		$NTRP_REMARK = $_POST["NTRP_REMARK"];
		$NTRP_STATUS = $_POST["NTRP_STATUS"];
		$NTRP_EDITBY = $_SESSION["AD_PERSONCODE"];
		$NTRP_EDITDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE NATUREREPAIR SET 
					NTRP_NAME = ? ,
					NTRP_REMARK = ? ,					
					NTRP_STATUS = ?,
					NTRP_EDITBY = ?,
					NTRP_EDITDATE = ?
					WHERE NTRP_ID = ? ";
		$params = array($NTRP_NAME, $NTRP_REMARK, $NTRP_STATUS, $NTRP_EDITBY, $NTRP_EDITDATE, $NTRP_ID);
	
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
		
		$NTRP_STATUS = 'D';
		$NTRP_EDITBY = $_SESSION["AD_PERSONCODE"];
		$NTRP_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE NATUREREPAIR SET NTRP_STATUS = ?,NTRP_EDITBY = ?,NTRP_EDITDATE = ? WHERE NTRP_CODE = ? ";
		$params = array($NTRP_STATUS, $NTRP_EDITBY, $NTRP_EDITDATE, $id);

		// $sql = "DELETE FROM NATUREREPAIR
		// 		WHERE NTRP_ID = ? ";
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