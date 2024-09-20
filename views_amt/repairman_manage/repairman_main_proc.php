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

	if(($proc=="add")||($proc=="add2")){
		$RPM_CODE = $_POST["RPM_CODE"];
		$RPM_NAME = $_POST["RPM_NAME"];
		$explodes = explode(' - ', $RPM_NAME);
		$RPM_PERSONCODE = $explodes[0];
		$RPM_PERSONNAME = $explodes[1];
		$RPM_NATUREREPAIR = $_POST["RPM_NATUREREPAIR"];
		$RPM_HOURSSTANDARD = $_POST["RPM_HOURSSTANDARD"];		
		$RPM_SKILL_EL = $_POST["EL"];
		$RPM_SKILL_TU = $_POST["TU"];
		$RPM_SKILL_BD = $_POST["BD"];
		$RPM_SKILL_EG = $_POST["EG"];
		$RPM_SKILL_AC = $_POST["AC"];
		$RPM_AREA = $_POST["area"];
		$RPM_STATUS = 'Y';
		$RPM_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$RPM_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO REPAIR_MAN (RPM_CODE, RPM_PERSONCODE, RPM_PERSONNAME, RPM_NATUREREPAIR, RPM_HOURSSTANDARD, RPM_SKILL_EL, RPM_SKILL_TU, RPM_SKILL_BD, RPM_SKILL_EG, RPM_SKILL_AC, RPM_AREA, RPM_STATUS, RPM_CREATEBY, RPM_CREATEDATE) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$params = array($RPM_CODE, $RPM_PERSONCODE, $RPM_PERSONNAME, $RPM_NATUREREPAIR, $RPM_HOURSSTANDARD, $RPM_SKILL_EL, $RPM_SKILL_TU, $RPM_SKILL_BD, $RPM_SKILL_EG, $RPM_SKILL_AC, $RPM_AREA, $RPM_STATUS, $RPM_CREATEBY, $RPM_CREATEDATE);
		
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
		$RPM_ID = $_POST["RPM_ID"];
		$RPM_NATUREREPAIR = $_POST["RPM_NATUREREPAIR"];
		$RPM_HOURSSTANDARD = $_POST["RPM_HOURSSTANDARD"];
		$RPM_SKILL_EL = $_POST["EL"];
		$RPM_SKILL_TU = $_POST["TU"];
		$RPM_SKILL_BD = $_POST["BD"];
		$RPM_SKILL_EG = $_POST["EG"];
		$RPM_SKILL_AC = $_POST["AC"];
		$RPM_AREA = $_POST["area"];
		$RPM_EDITBY = $_SESSION["AD_PERSONCODE"];
		$RPM_EDITDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE REPAIR_MAN SET 
					RPM_NATUREREPAIR = ? ,
					RPM_HOURSSTANDARD = ? ,
					RPM_SKILL_EL = ? ,
					RPM_SKILL_TU = ? ,
					RPM_SKILL_BD = ?,
					RPM_SKILL_EG = ?,
					RPM_SKILL_AC = ?,
					RPM_AREA = ?,
					RPM_EDITBY = ?,
					RPM_EDITDATE = ?
					WHERE RPM_ID = ? ";
		$params = array($RPM_NATUREREPAIR, $RPM_HOURSSTANDARD, $RPM_SKILL_EL, $RPM_SKILL_TU, $RPM_SKILL_BD, $RPM_SKILL_EG, $RPM_SKILL_AC, $RPM_AREA, $RPM_EDITBY, $RPM_EDITDATE, $RPM_ID);
	
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
		
		$RPM_STATUS = 'D';
		$RPM_EDITBY = $_SESSION["AD_PERSONCODE"];
		$RPM_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE REPAIR_MAN SET RPM_STATUS = ?,RPM_EDITBY = ?,RPM_EDITDATE = ? WHERE RPM_CODE = ? ";
		$params = array($RPM_STATUS, $RPM_EDITBY, $RPM_EDITDATE, $id);

		// $sql = "DELETE FROM MENU
		// 		WHERE RPM_ID = ? ";
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