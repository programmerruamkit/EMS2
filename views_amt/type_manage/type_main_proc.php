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
		$TRPW_CODE = $_POST["TRPW_CODE"];
		$NTRP_ID = $_POST["NTRP_ID"];
		$TRPW_NAME = $_POST["TRPW_NAME"];
		$TRPW_REMARK = $_POST["TRPW_REMARK"];
		$TRPW_AREA = $_POST["TRPW_AREA"];		
		$TRPW_STATUS = $_POST["TRPW_STATUS"];
		$TRPW_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$TRPW_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO TYPEREPAIRWORK (TRPW_CODE, NTRP_ID, TRPW_NAME, TRPW_REMARK, TRPW_AREA, TRPW_STATUS, TRPW_CREATEBY, TRPW_CREATEDATE) VALUES (?,?,?,?,?,?,?,?)";
		$params = array($TRPW_CODE, $NTRP_ID, $TRPW_NAME, $TRPW_REMARK, $TRPW_AREA, $TRPW_STATUS, $TRPW_CREATEBY, $TRPW_CREATEDATE);
		
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
		$TRPW_ID = $_POST["TRPW_ID"];
		$NTRP_ID = $_POST["NTRP_ID"];
		$TRPW_NAME = $_POST["TRPW_NAME"];
		$TRPW_REMARK = $_POST["TRPW_REMARK"];
		$TRPW_AREA = $_POST["TRPW_AREA"];
		$TRPW_STATUS = $_POST["TRPW_STATUS"];
		$TRPW_EDITBY = $_SESSION["AD_PERSONCODE"];
		$TRPW_EDITDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE TYPEREPAIRWORK SET 
					TRPW_NAME = ? ,
					NTRP_ID = ? ,
					TRPW_REMARK = ? ,
					TRPW_AREA = ? ,
					TRPW_STATUS = ? ,
					TRPW_EDITBY = ?,
					TRPW_EDITDATE = ?
					WHERE TRPW_ID = ? ";
		$params = array($TRPW_NAME, $NTRP_ID, $TRPW_REMARK, $TRPW_AREA, $TRPW_STATUS, $TRPW_EDITBY, $TRPW_EDITDATE, $TRPW_ID);
	
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
		
		$TRPW_STATUS = 'D';
		$TRPW_EDITBY = $_SESSION["AD_PERSONCODE"];
		$TRPW_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE TYPEREPAIRWORK SET TRPW_STATUS = ?,TRPW_EDITBY = ?,TRPW_EDITDATE = ? WHERE TRPW_CODE = ? ";
		$params = array($TRPW_STATUS, $TRPW_EDITBY, $TRPW_EDITDATE, $id);

		// $sql = "DELETE FROM MENU
		// 		WHERE TRPW_ID = ? ";
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