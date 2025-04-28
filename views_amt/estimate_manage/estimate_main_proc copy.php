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
	// $ETM_AREA = $_POST["ETM_AREA"];

	if($proc=="add"){
		$ETM_CODE = $_POST["mlpm_code"];
		$ETM_NAME = $_POST["ETM_NAME"];
		$ETM_MILEAGE_10K1M = $_POST["ETM_MILEAGE_10K1M"];
		$ETM_MILEAGE_1M2M = $_POST["ETM_MILEAGE_1M2M"];
		$ETM_MILEAGE_2M3M = $_POST["ETM_MILEAGE_2M3M"];
		$ETM_LINEOFWORK = $_POST["ETM_LINEOFWORK"];
		$ETM_REMARK = $_POST["ETM_REMARK"];
		$ETM_STATUS = $_POST["ETM_STATUS"];
		$ETM_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$ETM_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO ESTIMATE (ETM_CODE, ETM_NAME, ETM_MILEAGE_10K1M, ETM_MILEAGE_1M2M, ETM_MILEAGE_2M3M, ETM_LINEOFWORK, ETM_REMARK, ETM_STATUS, ETM_CREATEBY, ETM_CREATEDATE) VALUES (?,?,?,?,?,?,?,?,?,?)";
		$params = array($ETM_CODE, $ETM_NAME, $ETM_MILEAGE_10K1M, $ETM_MILEAGE_1M2M, $ETM_MILEAGE_2M3M, $ETM_LINEOFWORK, $ETM_REMARK, $ETM_STATUS, $ETM_CREATEBY, $ETM_CREATEDATE);
		
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
		$pmors = $_POST["pmors"];
		$ETM_CODE = $_POST["code"];
		$ETM_PM = $_POST["val"];
		$ETM_NUM = $_POST["num"];
		$VHCCT_ID = $_POST["vhcid"];
		$ETM_EDITBY = $_SESSION["AD_PERSONCODE"];
		$ETM_EDITDATE = date("Y-m-d H:i:s");
		if($pmors == '1'){
			$sql = "UPDATE ESTIMATE SET 
						ETM_PM1 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE ETM_CODE = ? ";
			$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
		}else if($pmors == '2'){
			$sql = "UPDATE ESTIMATE SET 
						ETM_PM2 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE ETM_CODE = ? ";
			$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
		}else if($pmors == '3'){
			$sql = "UPDATE ESTIMATE SET 
						ETM_PM3 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE ETM_CODE = ? ";
			$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
		}else if($pmors == '4'){
			$sql = "UPDATE ESTIMATE SET 
						ETM_PM4 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE ETM_CODE = ? ";
			$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
		}else if($pmors == '5'){
			$sql = "UPDATE ESTIMATE SET 
						ETM_PM5 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE ETM_CODE = ? ";
			$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
		}else if($pmors == '6'){
			$sql = "UPDATE ESTIMATE SET 
						ETM_PM6 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE ETM_CODE = ? ";
			$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
		}else if($pmors == '7'){
			$sql = "UPDATE ESTIMATE SET 
						ETM_PM7 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE ETM_CODE = ? ";
			$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
		}else if($pmors == '8'){
			$sql = "UPDATE ESTIMATE SET 
						ETM_PM8 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE ETM_CODE = ? ";
			$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
		}else if($pmors == '9'){
			$sql = "UPDATE ESTIMATE SET 
						ETM_PM9 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE ETM_CODE = ? ";
			$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
		}else if($pmors == '10'){
			$sql = "UPDATE ESTIMATE SET 
						ETM_PM10 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE ETM_CODE = ? ";
			$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
		}else if($pmors == '11'){
			$sql = "UPDATE ESTIMATE SET 
						ETM_PM11 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE ETM_CODE = ? ";
			$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
		}else if($pmors == '12'){
			$sql = "UPDATE ESTIMATE SET 
						ETM_PM12 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE ETM_CODE = ? ";
			$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
			if($ETM_NUM=='4'){  
				$CAL=$ETM_PM*600;
				// echo"<pre>";
				// print_r($CAL);
				// echo"</pre>";
				// exit();
				$sql1 = "UPDATE ESTIMATE SET 
						ETM_PM12 = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE VHCCT_ID = '10' AND ETM_NUM = '3'";
				$params1 = array($CAL, $ETM_EDITBY, $ETM_EDITDATE);
				
				// $sql_chklistcost = "SELECT DISTINCT	ETM_CODE,ETM_NUM,ETM_PM12 FROM dbo.ESTIMATE WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '1'";
				// $params_chklistcost = array();
				// $query_chklistcost = sqlsrv_query($conn, $sql_chklistcost, $params_chklistcost);
				// $result_chklistcost = sqlsrv_fetch_array($query_chklistcost, SQLSRV_FETCH_ASSOC); 
				// $ETM_PM12=$result_chklistcost['ETM_PM12']; 
			}
			
			

		}
	
		$stmt = sqlsrv_query( $conn, $sql, $params);
		$stmt1 = sqlsrv_query( $conn, $sql1, $params1);
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
		
		$ETM_STATUS = 'D';
		$ETM_EDITBY = $_SESSION["AD_PERSONCODE"];
		$ETM_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE ESTIMATE SET ETM_STATUS = ?,ETM_EDITBY = ?,ETM_EDITDATE = ? WHERE ETM_CODE = ? ";
		$params = array($ETM_STATUS, $ETM_EDITBY, $ETM_EDITDATE, $id);

		// $sql = "DELETE FROM ESTIMATE
		// 		WHERE ETM_ID = ? ";
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