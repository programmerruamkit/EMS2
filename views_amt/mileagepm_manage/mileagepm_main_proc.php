<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();

	$target=$_POST["target"];
	$proc=$_POST["proc"];
	$id=$_GET["id"];	
	// $MLPM_AREA = $_POST["MLPM_AREA"];

	if($proc=="add"){
		$MLPM_CODE = $_POST["mlpm_code"];
		$MLPM_NAME = $_POST["MLPM_NAME"];
		$MLPM_MILEAGE_10K1M = $_POST["MLPM_MILEAGE_10K1M"];
		$MLPM_MILEAGE_1M2M = $_POST["MLPM_MILEAGE_1M2M"];
		$MLPM_MILEAGE_2M3M = $_POST["MLPM_MILEAGE_2M3M"];
		$MLPM_LINEOFWORK = $_POST["MLPM_LINEOFWORK"];
		$MLPM_REMARK = $_POST["MLPM_REMARK"];
		$MLPM_STATUS = $_POST["MLPM_STATUS"];
		$MLPM_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$MLPM_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO MILEAGESETPM (MLPM_CODE, MLPM_NAME, MLPM_MILEAGE_10K1M, MLPM_MILEAGE_1M2M, MLPM_MILEAGE_2M3M, MLPM_LINEOFWORK, MLPM_REMARK, MLPM_STATUS, MLPM_CREATEBY, MLPM_CREATEDATE) VALUES (?,?,?,?,?,?,?,?,?,?)";
		$params = array($MLPM_CODE, $MLPM_NAME, $MLPM_MILEAGE_10K1M, $MLPM_MILEAGE_1M2M, $MLPM_MILEAGE_2M3M, $MLPM_LINEOFWORK, $MLPM_REMARK, $MLPM_STATUS, $MLPM_CREATEBY, $MLPM_CREATEDATE);
		
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
	if($target=="edit"){
		$group = $_POST["group"];
		$MLPM_CODE = $_POST["code"];
		$MLPM_MILEAGE = $_POST["val"];
		$MLPM_EDITBY = $_SESSION["AD_PERSONCODE"];
		$MLPM_EDITDATE = date("Y-m-d H:i:s");
		if($group == '1'){	
			$sql = "UPDATE MILEAGESETPM SET 
						MLPM_MILEAGE_10K1M = ? ,
						MLPM_EDITBY = ?,
						MLPM_EDITDATE = ?
						WHERE MLPM_CODE = ? ";
			$params = array($MLPM_MILEAGE, $MLPM_EDITBY, $MLPM_EDITDATE, $MLPM_CODE);
		}else if($group == '2'){
	
			$sql = "UPDATE MILEAGESETPM SET 
						MLPM_MILEAGE_1M2M = ? ,
						MLPM_EDITBY = ?,
						MLPM_EDITDATE = ?
						WHERE MLPM_CODE = ? ";
			$params = array($MLPM_MILEAGE, $MLPM_EDITBY, $MLPM_EDITDATE, $MLPM_CODE);
		}else if($group == '3'){
	
			$sql = "UPDATE MILEAGESETPM SET 
						MLPM_MILEAGE_2M3M = ? ,
						MLPM_EDITBY = ?,
						MLPM_EDITDATE = ?
						WHERE MLPM_CODE = ? ";
			$params = array($MLPM_MILEAGE, $MLPM_EDITBY, $MLPM_EDITDATE, $MLPM_CODE);
		}
	
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
		
		$MLPM_STATUS = 'D';
		$MLPM_EDITBY = $_SESSION["AD_PERSONCODE"];
		$MLPM_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE MILEAGESETPM SET MLPM_STATUS = ?,MLPM_EDITBY = ?,MLPM_EDITDATE = ? WHERE MLPM_CODE = ? ";
		$params = array($MLPM_STATUS, $MLPM_EDITBY, $MLPM_EDITDATE, $id);

		// $sql = "DELETE FROM MILEAGESETPM
		// 		WHERE MLPM_ID = ? ";
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