<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// print_r($_GET);
	// echo"</pre>";
	// exit();

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];

	if($proc=="add"){
		$RA_PERSONCODE = $_POST["personcode"];
		$RU_ID = $_POST["RU_ID"];
		$RA_USERNAME = $_POST["personcode"];
		$RA_PASSWORD = $_POST["personcode"];
		$RA_STATUS = $_POST["RA_STATUS"];
		$RA_CREATE_BY = $_SESSION["AD_PERSONCODE"];
		$RA_CREATE_DATE = date("Y-m-d H:i:s");
		$RA_CODE = $_POST["rm_code"];		
        
        $sql_check = "SELECT RU_ID FROM ROLE_ACCOUNT WHERE RA_PERSONCODE = ? AND RU_ID = ?";
        $parameters = [$RA_PERSONCODE,$RU_ID];
        $query_check = sqlsrv_query($conn, $sql_check, $parameters);
        $result_check = sqlsrv_fetch_array($query_check,SQLSRV_FETCH_ASSOC);    
        $CHECKRU_ID=$result_check["RU_ID"];  
		
		if(!$CHECKRU_ID){
			$sql = "INSERT INTO ROLE_ACCOUNT (RA_PERSONCODE, RU_ID, RA_USERNAME, RA_PASSWORD, RA_STATUS, RA_CREATE_BY, RA_CREATE_DATE, RA_CODE) VALUES (?,?,?,?,?,?,?,?)";
			$params = array($RA_PERSONCODE, $RU_ID, $RA_USERNAME, $RA_PASSWORD, $RA_STATUS, $RA_CREATE_BY, $RA_CREATE_DATE, $RA_CODE);
			$stmt = sqlsrv_query( $conn, $sql, $params);
			if( $stmt === false ) {
				die( print_r( sqlsrv_errors(), true));
			}
			else
			{
				print "บันทึกข้อมูลเรียบร้อยแล้ว";	
			}
		}else{
				print "ข้อมูลซ้ำ";	
		}	
	};
	######################################################################################################
	if($proc=="edit"){
		$RA_ID = $_POST["ra_id"];
		$RU_ID = $_POST["RU_ID"];
		$RA_STATUS = $_POST["RA_STATUS"];
		$RA_EDIT_BY = $_SESSION["AD_PERSONCODE"];
		$RA_EDIT_DATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE ROLE_ACCOUNT SET 
					RU_ID = ?,
					RA_STATUS = ?,
					RA_EDIT_BY = ?,
					RA_EDIT_DATE = ?
					WHERE RA_ID = ? ";
		$params = array($RU_ID, $RA_STATUS, $RA_EDIT_BY, $RA_EDIT_DATE, $RA_ID);
	
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
		
		$RA_STATUS = 'D';
		$RA_EDIT_BY = $_SESSION["AD_PERSONCODE"];
		$RA_EDIT_DATE = date("Y-m-d H:i:s");

		// $sql = "UPDATE ROLE_ACCOUNT SET RA_STATUS = ?,RA_EDIT_BY = ?,RA_EDIT_DATE = ? WHERE RA_ID = ? ";
		// $params = array($RA_STATUS, $RA_EDIT_BY, $RA_EDIT_DATE, $id);

		$sql = "DELETE FROM ROLE_ACCOUNT WHERE RA_ID = ? ";
		$params = array($id);

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