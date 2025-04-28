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
	$SESSION_AREA=$_SESSION["AD_AREA"];

	if($proc=="add"){
		$VHCCT_CODE = $_POST["VHCCT_CODE"];
		$VHCCT_SERIES = $_POST["VHCCT_SERIES"];
		$VHCCT_LINEOFWORK = $_POST["VHCCT_LINEOFWORK"];
		$VHCCT_NAMETYPE = $_POST["VHCCT_NAMETYPE"];		
		$VHCCT_KILOFORDAY = $_POST["VHCCT_KILOFORDAY"];		
		$VHCCT_PM = $_POST["VHCCT_PM"];	
		$VHCCT_STATUS = $_POST["VHCCT_STATUS"];
		$VHCCT_AREA = $_POST["VHCCT_AREA"];
		$VHCCT_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$VHCCT_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO VEHICLECARTYPE (VHCCT_CODE, VHCCT_SERIES, VHCCT_LINEOFWORK, VHCCT_NAMETYPE, VHCCT_KILOFORDAY, VHCCT_PM, VHCCT_STATUS, VHCCT_CREATEBY, VHCCT_CREATEDATE, VHCCT_AREA) VALUES (?,?,?,?,?,?,?,?,?,?)";
		$params = array($VHCCT_CODE, $VHCCT_SERIES, $VHCCT_LINEOFWORK, $VHCCT_NAMETYPE, $VHCCT_KILOFORDAY, $VHCCT_PM, $VHCCT_STATUS, $VHCCT_CREATEBY, $VHCCT_CREATEDATE, $VHCCT_AREA);
		
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
		$VHCCT_ID = $_POST["VHCCT_ID"];
		$VHCCT_SERIES = $_POST["VHCCT_SERIES"];
		$VHCCT_LINEOFWORK = $_POST["VHCCT_LINEOFWORK"];
		$VHCCT_NAMETYPE = $_POST["VHCCT_NAMETYPE"];
		$VHCCT_KILOFORDAY = $_POST["VHCCT_KILOFORDAY"];	
		$VHCCT_PM = $_POST["VHCCT_PM"];	
		$VHCCT_STATUS = $_POST["VHCCT_STATUS"];
		$VHCCT_AREA = $_POST["VHCCT_AREA"];
		$VHCCT_EDITBY = $_SESSION["AD_PERSONCODE"];
		$VHCCT_EDITDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE VEHICLECARTYPE SET 
					VHCCT_LINEOFWORK = ? ,
					VHCCT_SERIES = ? ,
					VHCCT_NAMETYPE = ? ,
					VHCCT_KILOFORDAY = ? ,
					VHCCT_PM = ? ,
					VHCCT_STATUS = ? ,
					VHCCT_EDITBY = ?,
					VHCCT_EDITDATE = ?
					WHERE VHCCT_ID = ? ";
		$params = array($VHCCT_LINEOFWORK, $VHCCT_SERIES, $VHCCT_NAMETYPE, $VHCCT_KILOFORDAY, $VHCCT_PM, $VHCCT_STATUS, $VHCCT_EDITBY, $VHCCT_EDITDATE, $VHCCT_ID);
	
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
		
		$VHCCT_STATUS = 'D';
		$VHCCT_EDITBY = $_SESSION["AD_PERSONCODE"];
		$VHCCT_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE VEHICLECARTYPE SET VHCCT_STATUS = ?,VHCCT_EDITBY = ?,VHCCT_EDITDATE = ? WHERE VHCCT_CODE = ? ";
		$params = array($VHCCT_STATUS, $VHCCT_EDITBY, $VHCCT_EDITDATE, $id);

		// $sql = "DELETE FROM MENU
		// 		WHERE VHCCT_ID = ? ";
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
	######################################################################################################
?>