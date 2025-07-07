<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];	

	if($proc=="add"){
		$PJTRPW_CODE = $_POST["PJTRPW_CODE"];
		$PJTRPW_TYPECODE = $_POST["PJTRPW_TYPECODE"];
		$PJTRPW_NAME = $_POST["PJTRPW_NAME"];
		$PJTRPW_REMARK = $_POST["PJTRPW_REMARK"];
		$PJTRPW_AREA = $_POST["PJTRPW_AREA"];		
		$PJTRPW_STATUS = $_POST["PJTRPW_STATUS"];
		$PJTRPW_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$PJTRPW_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO PROJECT_TYPEREPAIRWORK (PJTRPW_CODE, PJTRPW_TYPECODE, PJTRPW_NAME, PJTRPW_REMARK, PJTRPW_AREA, PJTRPW_STATUS, PJTRPW_CREATEBY, PJTRPW_CREATEDATE) VALUES (?,?,?,?,?,?,?,?)";
		$params = array($PJTRPW_CODE, $PJTRPW_TYPECODE, $PJTRPW_NAME, $PJTRPW_REMARK, $PJTRPW_AREA, $PJTRPW_STATUS, $PJTRPW_CREATEBY, $PJTRPW_CREATEDATE);
		
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
		$PJTRPW_ID = $_POST["PJTRPW_ID"];
		$PJTRPW_TYPECODE = $_POST["PJTRPW_TYPECODE"];
		$PJTRPW_NAME = $_POST["PJTRPW_NAME"];
		$PJTRPW_REMARK = $_POST["PJTRPW_REMARK"];
		$PJTRPW_AREA = $_POST["PJTRPW_AREA"];
		$PJTRPW_STATUS = $_POST["PJTRPW_STATUS"];
		$PJTRPW_EDITBY = $_SESSION["AD_PERSONCODE"];
		$PJTRPW_EDITDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE PROJECT_TYPEREPAIRWORK SET 
					PJTRPW_NAME = ? ,
					PJTRPW_TYPECODE = ? ,
					PJTRPW_REMARK = ? ,
					PJTRPW_AREA = ? ,
					PJTRPW_STATUS = ? ,
					PJTRPW_EDITBY = ?,
					PJTRPW_EDITDATE = ?
					WHERE PJTRPW_ID = ? ";
		$params = array($PJTRPW_NAME, $PJTRPW_TYPECODE, $PJTRPW_REMARK, $PJTRPW_AREA, $PJTRPW_STATUS, $PJTRPW_EDITBY, $PJTRPW_EDITDATE, $PJTRPW_ID);
	
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
		
		$PJTRPW_STATUS = 'D';
		$PJTRPW_EDITBY = $_SESSION["AD_PERSONCODE"];
		$PJTRPW_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE PROJECT_TYPEREPAIRWORK SET PJTRPW_STATUS = ?,PJTRPW_EDITBY = ?,PJTRPW_EDITDATE = ? WHERE PJTRPW_CODE = ? ";
		$params = array($PJTRPW_STATUS, $PJTRPW_EDITBY, $PJTRPW_EDITDATE, $id);

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