<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();

	$target=$_POST["target"];
	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];	

	if($proc=="add"){
		$CLRP_CODE = $_POST["CLRP_CODE"];
		$CLRP_NUM = $_POST["CLRP_NUM"];
		$CLRP_RANK = $_POST["CLRP_RANK"];
		$CLRP_NAME = $_POST["CLRP_NAME"];
		$CLRP_CARTYPE = $_POST["CLRP_CARTYPE"];
		$CLRP_REMARK = $_POST["CLRP_REMARK"];
		$CLRP_CHECK = $_POST["CLRP_CHECK"];		
		$CLRP_STATUS = $_POST["CLRP_STATUS"];
		$CLRP_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$CLRP_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO CHECKLISTREPAIR (CLRP_CODE, CLRP_NUM, CLRP_RANK, CLRP_NAME, CLRP_CARTYPE, CLRP_REMARK, CLRP_CHECK, CLRP_STATUS, CLRP_CREATEBY, CLRP_CREATEDATE) VALUES (?,?,?,?,?,?,?,?,?,?)";
		$params = array($CLRP_CODE, $CLRP_NUM, $CLRP_RANK, $CLRP_NAME, $CLRP_CARTYPE, $CLRP_REMARK, $CLRP_CHECK, $CLRP_STATUS, $CLRP_CREATEBY, $CLRP_CREATEDATE);
		
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
		$CLRP_ID = $_POST["CLRP_ID"];
		$CLRP_NUM = $_POST["CLRP_NUM"];
		$CLRP_RANK = $_POST["CLRP_RANK"];
		$CLRP_NAME = $_POST["CLRP_NAME"];
		$CLRP_CARTYPE = $_POST["CLRP_CARTYPE"];
		$CLRP_REMARK = $_POST["CLRP_REMARK"];
		$CLRP_CHECK = $_POST["CLRP_CHECK"];
		$CLRP_STATUS = $_POST["CLRP_STATUS"];
		$CLRP_EDITBY = $_SESSION["AD_PERSONCODE"];
		$CLRP_EDITDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE CHECKLISTREPAIR SET 
					CLRP_NUM = ? ,
					CLRP_NAME = ? ,
					CLRP_RANK = ? ,
					CLRP_CARTYPE = ? ,
					CLRP_REMARK = ? ,
					CLRP_CHECK = ? ,
					CLRP_STATUS = ? ,
					CLRP_EDITBY = ?,
					CLRP_EDITDATE = ?
					WHERE CLRP_ID = ? ";
		$params = array($CLRP_NUM, $CLRP_NAME, $CLRP_RANK, $CLRP_CARTYPE, $CLRP_REMARK, $CLRP_CHECK, $CLRP_STATUS, $CLRP_EDITBY, $CLRP_EDITDATE, $CLRP_ID);
	
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
		
		$CLRP_STATUS = 'D';
		$CLRP_EDITBY = $_SESSION["AD_PERSONCODE"];
		$CLRP_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE CHECKLISTREPAIR SET CLRP_STATUS = ?,CLRP_EDITBY = ?,CLRP_EDITDATE = ? WHERE CLRP_CODE = ? ";
		$params = array($CLRP_STATUS, $CLRP_EDITBY, $CLRP_EDITDATE, $id);

		// $sql = "DELETE FROM MENU
		// 		WHERE CLRP_ID = ? ";
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
	if($target=="editnew"){
		$pmors = $_POST["pmors"];
		$CLRP_CODE = $_POST["code"];
		$VALUE = $_POST["val"];
		$CLRP_NUM = $_POST["num"];
		$CLRP_TYPE = $_POST["pmtype"];
		$CLRP_CARTYPE = $_POST["cartype"];
		$CLRP_EDITBY = $_SESSION["AD_PERSONCODE"];
		$CLRP_EDITDATE = date("Y-m-d H:i:s");
			
		$sql = "UPDATE CHECKLISTREPAIR SET 
					CLRP_PM$pmors = ?,
					CLRP_EDITBY = ?,
					CLRP_EDITDATE = ?
					WHERE CLRP_CODE = ? AND CLRP_TYPE = ? AND CLRP_CARTYPE = ?";
		$params = array($VALUE, $CLRP_EDITBY, $CLRP_EDITDATE, $CLRP_CODE, $CLRP_TYPE, $CLRP_CARTYPE);
		$stmt = sqlsrv_query( $conn, $sql, $params);
	
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "แก้ไขข้อมูลเรียบร้อยแล้ว";	
		}		
	};	
?>