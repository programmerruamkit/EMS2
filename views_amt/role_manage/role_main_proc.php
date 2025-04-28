<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// print_r($_GET);
	// echo"</pre>";
	// exit();

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];

	if($proc=="add"){
		$RU_CODE = $_POST["ru_code"];
		$RU_NAME = $_POST["RU_NAME"];
		$RU_IMG = $_POST["RU_IMG"];
		$RU_SORT = $_POST["RU_SORT"];
		$RU_AREA = $_POST["area"];		
		$RU_STATUS = $_POST["RU_STATUS"];
		$RU_CREATE_BY = $_SESSION["AD_PERSONCODE"];
		$RU_CREATE_DATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO ROLE_USER (RU_CODE, RU_NAME, RU_IMG, RU_SORT, RU_AREA, RU_STATUS, RU_CREATE_BY, RU_CREATE_DATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$params = array($RU_CODE, $RU_NAME, $RU_IMG, $RU_SORT, $RU_AREA, $RU_STATUS, $RU_CREATE_BY, $RU_CREATE_DATE);
		
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
		$RU_ID = $_POST["RU_ID"];
		$RU_CODE = $_POST["ru_code"];
		$RU_NAME = $_POST["RU_NAME"];
		$RU_IMG = $_POST["RU_IMG"];
		$RU_SORT = $_POST["RU_SORT"];
		$RU_AREA = $_POST["area"];	
		$RU_STATUS = $_POST["RU_STATUS"];
		$RU_EDIT_BY = $_SESSION["AD_PERSONCODE"];
		$RU_EDIT_DATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE ROLE_USER SET 
					RU_NAME = ? ,
					RU_IMG = ? , 
					RU_SORT = ? ,
					RU_AREA = ? ,
					RU_STATUS = ?,
					RU_EDIT_BY = ?,
					RU_EDIT_DATE = ?
					WHERE RU_CODE = ? ";
		$params = array($RU_NAME, $RU_IMG, $RU_SORT, $RU_AREA, $RU_STATUS, $RU_EDIT_BY, $RU_EDIT_DATE, $RU_CODE);
	
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
		
		$RU_STATUS = 'D';
		$RU_EDIT_BY = $_SESSION["AD_PERSONCODE"];
		$RU_EDIT_DATE = date("Y-m-d H:i:s");
		$sql = "UPDATE ROLE_USER SET RU_STATUS = ?,RU_EDIT_BY = ?,RU_EDIT_DATE = ? WHERE RU_CODE = ? ";
		$params = array($RU_STATUS, $RU_EDIT_BY, $RU_EDIT_DATE, $id);

		// $sql = "DELETE FROM MENU
		// 		WHERE MN_ID = ? ";
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