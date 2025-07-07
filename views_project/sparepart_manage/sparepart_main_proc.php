<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];	

	if($proc=="add"){
		$PJSPP_CODE = $_POST["spp_code"];
		$PJSPP_CODESPP = $_POST["PJSPP_CODESPP"];
		$PJSPP_NAME = $_POST["PJSPP_NAME"];
		$PJSPP_EXPIRE_YEAR = $_POST["PJSPP_EXPIRE_YEAR"];
		$PJSPP_SPECIFIED_DISTANCE = $_POST["PJSPP_SPECIFIED_DISTANCE"];
		$PJSPP_STATUS = $_POST["PJSPP_STATUS"];
		$PJSPP_AREA = $_POST["PJSPP_AREA"];
		$PJSPP_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$PJSPP_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO PROJECT_SPAREPART (PJSPP_CODE, PJSPP_CODESPP, PJSPP_NAME, PJSPP_EXPIRE_YEAR, PJSPP_SPECIFIED_DISTANCE, PJSPP_STATUS, PJSPP_AREA, PJSPP_CREATEBY, PJSPP_CREATEDATE) VALUES (?,?,?,?,?,?,?,?,?)";
		$params = array($PJSPP_CODE, $PJSPP_CODESPP, $PJSPP_NAME, $PJSPP_EXPIRE_YEAR, $PJSPP_SPECIFIED_DISTANCE, $PJSPP_STATUS, $PJSPP_AREA, $PJSPP_CREATEBY, $PJSPP_CREATEDATE);
		
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
		$PJSPP_ID = $_POST["PJSPP_ID"];
		$PJSPP_CODESPP = $_POST["PJSPP_CODESPP"];
		$PJSPP_NAME = $_POST["PJSPP_NAME"];
		$PJSPP_EXPIRE_YEAR = $_POST["PJSPP_EXPIRE_YEAR"];
		$PJSPP_SPECIFIED_DISTANCE = $_POST["PJSPP_SPECIFIED_DISTANCE"];
		$PJSPP_STATUS = $_POST["PJSPP_STATUS"];
		$PJSPP_AREA = $_POST["PJSPP_AREA"];
	
		$sql = "UPDATE PROJECT_SPAREPART SET 
					PJSPP_CODESPP = ? ,
					PJSPP_NAME = ? ,
					PJSPP_EXPIRE_YEAR = ? ,
					PJSPP_SPECIFIED_DISTANCE = ? ,
					PJSPP_STATUS = ? ,
					PJSPP_AREA = ?
				WHERE PJSPP_ID = ? ";
		$params = array($PJSPP_CODESPP, $PJSPP_NAME, $PJSPP_EXPIRE_YEAR, $PJSPP_SPECIFIED_DISTANCE, $PJSPP_STATUS, $PJSPP_AREA, $PJSPP_ID);
	
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
		
		$PJSPP_STATUS = 'D';
		$PJSPP_EDITBY = $_SESSION["AD_PERSONCODE"];
		$PJSPP_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE PROJECT_SPAREPART SET PJSPP_STATUS = ?,PJSPP_EDITBY = ?,PJSPP_EDITDATE = ? WHERE PJSPP_CODE = ? ";
		$params = array($PJSPP_STATUS, $PJSPP_EDITBY, $PJSPP_EDITDATE, $id);
		

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