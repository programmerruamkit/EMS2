<?php
	session_start();  	
	include('../../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();
	
	$newpass = $_POST["newpass"];
	$PersonCode = $_POST["PersonCode"];

	$sql = "UPDATE ROLE_ACCOUNT SET RA_PASSWORD = ? WHERE RA_PERSONCODE = ? ";
	$params = array($newpass, $PersonCode);

	$stmt = sqlsrv_query( $conn, $sql, $params);
	if( $stmt === false ) {
		die( print_r( sqlsrv_errors(), true));
	}
	else
	{
		print "บันทึกข้อมูลเรียบร้อยแล้ว";	
	}	
?>