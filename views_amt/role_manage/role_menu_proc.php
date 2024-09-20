<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// print_r($MN_ID);
	// echo"</pre>";
	// exit();

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];

	if($proc=="add"){
		$AREA = $_POST["area"];	
		$RM_NAME = $_POST["RM_NAME"];
			$sql_semenu = "SELECT MN_ID FROM MENU WHERE MN_NAME = '$RM_NAME' AND MN_AREA = '$AREA'";
			$query_semenu = sqlsrv_query( $conn, $sql_semenu);	
			$result_semenu = sqlsrv_fetch_array($query_semenu, SQLSRV_FETCH_ASSOC);
		$RU_ID = $_POST["RU_ID"];
		$RM_CODE = $_POST["rm_code"];
		$MN_ID = $result_semenu["MN_ID"];	
		$RM_STATUS = $_POST["RM_STATUS"];
		$RM_CREATE_BY = $_SESSION["AD_PERSONCODE"];
		$RM_CREATE_DATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO ROLE_MENU (RU_ID, RM_CODE, MN_ID, AREA, RM_STATUS, RM_CREATE_BY, RM_CREATE_DATE) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$params = array($RU_ID, $RM_CODE, $MN_ID, $AREA, $RM_STATUS, $RM_CREATE_BY, $RM_CREATE_DATE);
		
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
		$RM_ID = $_POST["RM_ID"];
		$RM_STATUS = $_POST["RM_STATUS"];
		$RM_EDIT_BY = $_SESSION["AD_PERSONCODE"];
		$RM_EDIT_DATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE ROLE_MENU SET 
					RM_STATUS = ?,
					RM_EDIT_BY = ?,
					RM_EDIT_DATE = ?
					WHERE RM_ID = ? ";
		$params = array($RM_STATUS, $RM_EDIT_BY, $RM_EDIT_DATE, $RM_ID);
	
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
		
		$RM_STATUS = 'D';
		$RM_EDIT_BY = $_SESSION["AD_PERSONCODE"];
		$RM_EDIT_DATE = date("Y-m-d H:i:s");
		$sql = "UPDATE ROLE_MENU SET RM_STATUS = ?,RM_EDIT_BY = ?,RM_EDIT_DATE = ? WHERE RM_CODE = ? ";
		$params = array($RM_STATUS, $RM_EDIT_BY, $RM_EDIT_DATE, $id);

		// $sql = "DELETE FROM MENU
		// 		WHERE RM_ID = ? ";
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