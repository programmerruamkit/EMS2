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
	// $SPP_AREA = $_POST["SPP_AREA"];

	if($proc=="add"){
		$SPP_CODE = $_POST["spp_code"];
		$SPP_NAME = $_POST["SPP_NAME"];
		$SPP_LOCAT = $_POST["SPP_LOCAT"];
		$SPP_CODENAME = $_POST["SPP_CODENAME"];
		$SPP_STOCK = $_POST["SPP_STOCK"];
		$SPP_STATUS = $_POST["SPP_STATUS"];
		$SPP_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$SPP_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO SPAREPART (SPP_CODE, SPP_NAME, SPP_LOCAT, SPP_CODENAME, SPP_STOCK, SPP_STATUS, SPP_CREATEBY, SPP_CREATEDATE) VALUES (?,?,?,?,?,?,?,?)";
		$params = array($SPP_CODE, $SPP_NAME, $SPP_LOCAT, $SPP_CODENAME, $SPP_STOCK, $SPP_STATUS, $SPP_CREATEBY, $SPP_CREATEDATE);
		
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
		$SPP_ID = $_POST["SPP_ID"];
		$SPP_NAME = $_POST["SPP_NAME"];
		$SPP_LOCAT = $_POST["SPP_LOCAT"];
		$SPP_CODENAME = $_POST["SPP_CODENAME"];
		$SPP_STOCK = $_POST["SPP_STOCK"];
		$SPP_STATUS = $_POST["SPP_STATUS"];
		$SPP_EDITBY = $_SESSION["AD_PERSONCODE"];
		$SPP_EDITDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE SPAREPART SET 
					SPP_NAME = ? ,
					SPP_LOCAT = ? ,
					SPP_CODENAME = ?,
					SPP_STOCK = ?,
					SPP_STATUS = ?,
					SPP_EDITBY = ?,
					SPP_EDITDATE = ?
					WHERE SPP_ID = ? ";
		$params = array($SPP_NAME, $SPP_LOCAT, $SPP_CODENAME, $SPP_STOCK, $SPP_STATUS, $SPP_EDITBY, $SPP_EDITDATE, $SPP_ID);
	
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
		
		$SPP_STATUS = 'D';
		$SPP_EDITBY = $_SESSION["AD_PERSONCODE"];
		$SPP_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE SPAREPART SET SPP_STATUS = ?,SPP_EDITBY = ?,SPP_EDITDATE = ? WHERE SPP_CODE = ? ";
		$params = array($SPP_STATUS, $SPP_EDITBY, $SPP_EDITDATE, $id);

		// $sql = "DELETE FROM SPAREPART
		// 		WHERE SPP_ID = ? ";
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