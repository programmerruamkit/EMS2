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
	$CTM_AREA = $_POST["area"];

	if($proc=="add"){
		$CTM_CODE = $_POST["ctm_code"];
		$CTM_COMCODE = $_POST["CTM_COMCODE"];
		$CTM_NAMETH = $_POST["CTM_NAMETH"];
		$CTM_NAMEEN = $_POST["CTM_NAMEEN"];
		$CTM_TAXNUMBER = $_POST["CTM_TAXNUMBER"];
		$CTM_PHONE = $_POST["CTM_PHONE"];
		$CTM_FAX = $_POST["CTM_FAX"];
		$CTM_ADDRESS = $_POST["CTM_ADDRESS"];
		$CTM_CAPITAL = $_POST["CTM_CAPITAL"];
		$CTM_REGISTERED = $_POST["CTM_REGISTERED"];
		$CTM_GROUP = $_POST["CTM_GROUP"];
		$CTM_STATUS = $_POST["CTM_STATUS"];
		$CTM_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$CTM_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO CUSTOMER (CTM_CODE, CTM_COMCODE, CTM_NAMETH, CTM_NAMEEN, CTM_TAXNUMBER, CTM_PHONE, CTM_FAX, CTM_ADDRESS, CTM_CAPITAL, CTM_REGISTERED, CTM_GROUP, CTM_STATUS, CTM_CREATEBY, CTM_CREATEDATE) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$params = array($CTM_CODE, $CTM_COMCODE, $CTM_NAMETH, $CTM_NAMEEN, $CTM_TAXNUMBER, $CTM_PHONE, $CTM_FAX, $CTM_ADDRESS, $CTM_CAPITAL, $CTM_REGISTERED, $CTM_GROUP, $CTM_STATUS, $CTM_CREATEBY, $CTM_CREATEDATE);
		
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
		$CTM_ID = $_POST["CTM_ID"];
		
		$CTM_COMCODE = $_POST["CTM_COMCODE"];
		$CTM_NAMETH = $_POST["CTM_NAMETH"];
		$CTM_NAMEEN = $_POST["CTM_NAMEEN"];
		$CTM_TAXNUMBER = $_POST["CTM_TAXNUMBER"];
		$CTM_PHONE = $_POST["CTM_PHONE"];
		$CTM_FAX = $_POST["CTM_FAX"];
		$CTM_ADDRESS = $_POST["CTM_ADDRESS"];
		$CTM_CAPITAL = $_POST["CTM_CAPITAL"];
		$CTM_REGISTERED = $_POST["CTM_REGISTERED"];
		$CTM_GROUP = $_POST["CTM_GROUP"];
		$CTM_STATUS = $_POST["CTM_STATUS"];

		$CTM_EDITBY = $_SESSION["AD_PERSONCODE"];
		$CTM_EDITDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE CUSTOMER SET 
					CTM_COMCODE = ? ,
					CTM_NAMETH = ? ,
					CTM_NAMEEN  = ? ,
					CTM_TAXNUMBER = ? ,
					CTM_PHONE = ?,
					CTM_FAX = ?,
					CTM_ADDRESS = ?,
					CTM_CAPITAL = ?,
					CTM_REGISTERED = ? ,
					CTM_GROUP = ? ,
					CTM_STATUS = ? ,
					CTM_EDITBY = ?,
					CTM_EDITDATE = ?
					WHERE CTM_ID = ? ";
		$params = array($CTM_COMCODE, $CTM_NAMETH, $CTM_NAMEEN, $CTM_TAXNUMBER, $CTM_PHONE, $CTM_FAX, $CTM_ADDRESS, $CTM_CAPITAL, $CTM_REGISTERED, $CTM_GROUP, $CTM_STATUS, $CTM_EDITBY, $CTM_EDITDATE, $CTM_ID);
	
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
		
		$CTM_STATUS = 'D';
		$CTM_EDITBY = $_SESSION["AD_PERSONCODE"];
		$CTM_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE CUSTOMER SET CTM_STATUS = ?,CTM_EDITBY = ?,CTM_EDITDATE = ? WHERE CTM_CODE = ? ";
		$params = array($CTM_STATUS, $CTM_EDITBY, $CTM_EDITDATE, $id);

		// $sql = "DELETE FROM CUSTOMER
		// 		WHERE CTM_ID = ? ";
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