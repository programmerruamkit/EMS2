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
	$ST_AREA = $_POST["area"];

	if($proc=="add"){
		$ST_CODE = $_POST["mn_code"];
		$ST_NAME = $_POST["ST_NAME"];
		$ST_GROUP = "2";
		$ST_TYPE = $_POST["ST_TYPE"];
		$ST_DETAIL = $_POST["ST_DETAIL"];
		// $ST_AREA = $_POST["ST_AREA"];
		$ST_STATUS = $_POST["ST_STATUS"];
		$ST_TIME = $_POST["ST_TIME"];
		
		$sql = "INSERT INTO SETTING (ST_CODE, ST_NAME, ST_GROUP, ST_TYPE, ST_DETAIL,ST_AREA, ST_STATUS, ST_TIME) VALUES (?,?,?,?,?,?,?,?)";
		$params = array($ST_CODE, $ST_NAME, $ST_GROUP, $ST_TYPE, $ST_DETAIL, $ST_AREA, $ST_STATUS, $ST_TIME);
		
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
		$ST_ID = $_POST["ST_ID"];
		$ST_NAME = $_POST["ST_NAME"];
		$ST_GROUP = $_POST["ST_GROUP"];
		$ST_TYPE = $_POST["ST_TYPE"];
		$ST_DETAIL = $_POST["ST_DETAIL"];
		// $ST_AREA = $_POST["ST_AREA"];
		$ST_STATUS = $_POST["ST_STATUS"];
		$ST_TIME = $_POST["ST_TIME"];
	
		$sql = "UPDATE SETTING SET 
					ST_NAME = ? ,
					ST_GROUP = ? ,
					ST_TYPE = ?,
					ST_DETAIL = ? ,
					ST_AREA = ? ,
					ST_STATUS = ?,
					ST_TIME = ?
					WHERE ST_ID = ? ";
		$params = array($ST_NAME, $ST_GROUP, $ST_TYPE, $ST_DETAIL, $ST_AREA, $ST_STATUS, $ST_TIME, $ST_ID);
	
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
		
		$ST_STATUS = 'D';
		$ST_EDITBY = $_SESSION["AD_PERSONCODE"];
		$ST_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE SETTING SET ST_STATUS = ?,ST_EDITBY = ?,ST_EDITDATE = ? WHERE ST_CODE = ? ";
		$params = array($ST_STATUS, $ST_EDITBY, $ST_EDITDATE, $id);

		// $sql = "DELETE FROM SETTING
		// 		WHERE ST_ID = ? ";
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