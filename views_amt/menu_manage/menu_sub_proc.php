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

	if($proc=="add"){
		$MN_CODE = $_POST["mn_code"];
		$MN_NAME = $_POST["MN_NAME"];
		$MN_LEVEL = "2";
		$MN_AREA = $_POST["area"];		
		$MN_SORT = $_POST["MN_SORT"];
		$MN_PARENT = $_POST["MN_PARENT"];
		$MN_ICON = "nav_right_green.gif";
		$MN_URL = $_POST["MN_URL"];
		$MN_STATUS = $_POST["MN_STATUS"];
		$MN_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$MN_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO MENU (MN_CODE, MN_NAME, MN_LEVEL, MN_AREA, MN_SORT, MN_PARENT, MN_ICON, MN_URL, MN_STATUS, MN_CREATEBY, MN_CREATEDATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$params = array($MN_CODE, $MN_NAME, $MN_LEVEL, $MN_AREA, $MN_SORT, $MN_PARENT, $MN_ICON, $MN_URL, $MN_STATUS, $MN_CREATEBY, $MN_CREATEDATE);
		
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
		$MN_ID = $_POST["MN_ID"];
		$MN_NAME = $_POST["MN_NAME"];
		$MN_LEVEL = "2";
		$MN_AREA = $_POST["area"];		
		$MN_SORT = $_POST["MN_SORT"];
		$MN_PARENT = $_POST["MN_PARENT"];
		$MN_ICON = "nav_right_green.gif";
		$MN_URL = $_POST["MN_URL"];
		$MN_STATUS = $_POST["MN_STATUS"];
		$MN_EDITBY = $_SESSION["AD_PERSONCODE"];
		$MN_EDITDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE MENU SET 
					MN_NAME = ? ,
					MN_LEVEL = ? ,
					MN_AREA = ? ,
					MN_SORT = ? ,
					MN_PARENT = ?,
					MN_ICON = ?,
					MN_URL = ?,
					MN_STATUS = ?,
					MN_EDITBY = ?,
					MN_EDITDATE = ?
					WHERE MN_ID = ? ";
		$params = array($MN_NAME, $MN_LEVEL, $MN_AREA, $MN_SORT, $MN_PARENT, $MN_ICON, $MN_URL, $MN_STATUS, $MN_EDITBY, $MN_EDITDATE, $MN_ID);
	
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
		
		$MN_STATUS = 'D';
		$MN_EDITBY = $_SESSION["AD_PERSONCODE"];
		$MN_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE MENU SET MN_STATUS = ?,MN_EDITBY = ?,MN_EDITDATE = ? WHERE MN_CODE = ? ";
		$params = array($MN_STATUS, $MN_EDITBY, $MN_EDITDATE, $id);

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