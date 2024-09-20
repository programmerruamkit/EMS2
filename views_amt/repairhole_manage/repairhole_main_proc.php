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
	// $RPH_AREA = $_POST["RPH_AREA"];

	if($proc=="add"){
		$RPH_CODE = $_POST["rph_code"];
		$RPH_REPAIRHOLE = $_POST["RPH_REPAIRHOLE"];
		$RPH_ZONE = $_POST["RPH_ZONE"];
		$RPH_AREA = $_POST["RPH_AREA"];
		$RPH_NATUREREPAIR = $_POST["RPH_NATUREREPAIR"];
		$RPH_HOURSSTANDARD = $_POST["RPH_HOURSSTANDARD"];
		$RPH_STATUS = $_POST["RPH_STATUS"];
		$RPH_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$RPH_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO REPAIR_HOLE (RPH_CODE, RPH_REPAIRHOLE, RPH_ZONE, RPH_AREA, RPH_NATUREREPAIR, RPH_HOURSSTANDARD, RPH_STATUS, RPH_CREATEBY, RPH_CREATEDATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$params = array($RPH_CODE, $RPH_REPAIRHOLE, $RPH_ZONE, $RPH_AREA, $RPH_NATUREREPAIR, $RPH_HOURSSTANDARD, $RPH_STATUS, $RPH_CREATEBY, $RPH_CREATEDATE);
		
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
		$RPH_ID = $_POST["RPH_ID"];
		$RPH_REPAIRHOLE = $_POST["RPH_REPAIRHOLE"];
		$RPH_ZONE = $_POST["RPH_ZONE"];
		$RPH_AREA = $_POST["RPH_AREA"];
		$RPH_NATUREREPAIR = $_POST["RPH_NATUREREPAIR"];
		$RPH_HOURSSTANDARD = $_POST["RPH_HOURSSTANDARD"];
		$RPH_STATUS = $_POST["RPH_STATUS"];
		$RPH_EDITBY = $_SESSION["AD_PERSONCODE"];
		$RPH_EDITDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE REPAIR_HOLE SET 
					RPH_REPAIRHOLE = ? ,
					RPH_ZONE = ? ,
					RPH_AREA = ? ,
					RPH_NATUREREPAIR = ?,
					RPH_HOURSSTANDARD = ?,
					RPH_STATUS = ?,
					RPH_EDITBY = ?,
					RPH_EDITDATE = ?
					WHERE RPH_ID = ? ";
		$params = array($RPH_REPAIRHOLE, $RPH_ZONE, $RPH_AREA, $RPH_NATUREREPAIR, $RPH_HOURSSTANDARD, $RPH_STATUS, $RPH_EDITBY, $RPH_EDITDATE, $RPH_ID);
	
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
		
		$RPH_STATUS = 'D';
		$RPH_EDITBY = $_SESSION["AD_PERSONCODE"];
		$RPH_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE REPAIR_HOLE SET RPH_STATUS = ?,RPH_EDITBY = ?,RPH_EDITDATE = ? WHERE RPH_CODE = ? ";
		$params = array($RPH_STATUS, $RPH_EDITBY, $RPH_EDITDATE, $id);

		// $sql = "DELETE FROM REPAIR_HOLE
		// 		WHERE RPH_ID = ? ";
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