<?php
	session_start();
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

	if($target=="edit"){
		$pmors = $_POST["pmors"];
		$ETM_CODE = $_POST["code"];
		$ETM_PM = $_POST["val"];
		$ETM_NUM = $_POST["num"];
		$VHCCT_ID = $_POST["vhcid"];
		$ETM_GROUP = $_POST["group"];
		$ETM_TYPE = $_POST["pmtype"];
		$ETM_AREA = $_SESSION["AD_AREA"];
		$ETM_EDITBY = $_SESSION["AD_PERSONCODE"];
		$ETM_EDITDATE = date("Y-m-d H:i:s");
		
		$RS_NUM_TOTAL = $_POST["rsnumtotal"];
			
		$sql = "UPDATE ESTIMATE SET 
					ETM_PM$pmors = ?,
					ETM_EDITBY = ?,
					ETM_EDITDATE = ?
					WHERE ETM_CODE = ? ";
		$params = array($ETM_PM, $ETM_EDITBY, $ETM_EDITDATE, $ETM_CODE);
		$stmt = sqlsrv_query( $conn, $sql, $params);

		if(($ETM_NUM=='1')||($ETM_NUM=='4')){  
			$CAL0=$ETM_PM*600;
			$sql_updatenum3 = "UPDATE ESTIMATE SET 
					ETM_PM$pmors = ?,
					ETM_EDITBY = ?,
					ETM_EDITDATE = ?
					WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '3' AND ETM_AREA = '$ETM_AREA' AND ETM_GROUP = '$ETM_GROUP' AND ETM_TYPE = '$ETM_TYPE'";
			$params_updatenum3 = array($CAL0, $ETM_EDITBY, $ETM_EDITDATE);
			$stmt_updatenum3 = sqlsrv_query( $conn, $sql_updatenum3, $params_updatenum3);
			
			$sql_checknum1 = "SELECT ETM_CODE,ETM_NUM,ETM_PM$pmors FROM dbo.ESTIMATE WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '1' AND ETM_AREA = '$ETM_AREA' AND ETM_GROUP = '$ETM_GROUP' AND ETM_TYPE = '$ETM_TYPE'";
			$params_checknum1 = array();
			$query_checknum1 = sqlsrv_query($conn, $sql_checknum1, $params_checknum1);
			$result_checknum1 = sqlsrv_fetch_array($query_checknum1, SQLSRV_FETCH_ASSOC); 
			$RSETMPM0=$result_checknum1['ETM_PM'.$pmors]; 
			$CAL_FOR_UPDATE5=$CAL0+$RSETMPM0;

			$sql_updatenum5 = "UPDATE ESTIMATE SET 
					ETM_PM$pmors = ?,
					ETM_EDITBY = ?,
					ETM_EDITDATE = ?
					WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '5' AND ETM_AREA = '$ETM_AREA' AND ETM_GROUP = '$ETM_GROUP' AND ETM_TYPE = '$ETM_TYPE'";
			$params_updatenum5 = array($CAL_FOR_UPDATE5, $ETM_EDITBY, $ETM_EDITDATE);
			$stmt_updatenum5 = sqlsrv_query( $conn, $sql_updatenum5, $params_updatenum5);			
		}

		if($RS_NUM_TOTAL>'6'){		
			// for ($numrow = 1; $numrow <= 6; $numrow++) {	
				$sql_checknum1_nottotal = "SELECT ETM_CODE,ETM_NUM,ETM_PM$pmors FROM dbo.ESTIMATE WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '$ETM_NUM' AND ETM_AREA = '$ETM_AREA' AND ETM_GROUP = '$ETM_GROUP' AND NOT ETM_TYPE = 'รวม'";
				$params_checknum1_nottotal = array();
				$query_checknum1_nottotal = sqlsrv_query($conn, $sql_checknum1_nottotal, $params_checknum1_nottotal);
				while($result_checknum1_nottotal = sqlsrv_fetch_array($query_checknum1_nottotal)) {  
					$RSETMPM1=$result_checknum1_nottotal['ETM_PM'.$pmors]; 
					$CALRSETMPM1=$CALRSETMPM1+$RSETMPM1; 
				}
				$CAL_FOR_UPDATE1=$CALRSETMPM1;

				$sql_updatenum1_total = "UPDATE ESTIMATE SET 
						ETM_PM$pmors = ? ,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '$ETM_NUM' AND ETM_AREA = '$ETM_AREA' AND ETM_GROUP = '$ETM_GROUP' AND ETM_TYPE = 'รวม'";
				$params_updatenum1_total = array($CAL_FOR_UPDATE1, $ETM_EDITBY, $ETM_EDITDATE);
				$stmt_updatenum1_total = sqlsrv_query( $conn, $sql_updatenum1_total, $params_updatenum1_total);

								
				$sql_checknum3_nottotal = "SELECT ETM_CODE,ETM_NUM,ETM_PM$pmors FROM dbo.ESTIMATE WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '3' AND ETM_AREA = '$ETM_AREA' AND ETM_GROUP = '$ETM_GROUP' AND NOT ETM_TYPE = 'รวม'";
				$params_checknum3_nottotal = array();
				$query_checknum3_nottotal = sqlsrv_query($conn, $sql_checknum3_nottotal, $params_checknum3_nottotal);
				// $result_checknum3_nottotal = sqlsrv_fetch_array($query_checknum3_nottotal, SQLSRV_FETCH_ASSOC); 
				while($result_checknum3_nottotal = sqlsrv_fetch_array($query_checknum3_nottotal)) {  
					$RSETMPM3=$result_checknum3_nottotal['ETM_PM'.$pmors]; 
					$CALRSETMPM3=$CALRSETMPM3+$RSETMPM3; 
				}
				$CAL_FOR_UPDATE3=$CALRSETMPM3;
								
				$sql_checknum5_nottotal = "SELECT ETM_CODE,ETM_NUM,ETM_PM$pmors FROM dbo.ESTIMATE WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '5' AND ETM_AREA = '$ETM_AREA' AND ETM_GROUP = '$ETM_GROUP' AND NOT ETM_TYPE = 'รวม'";
				$params_checknum5_nottotal = array();
				$query_checknum5_nottotal = sqlsrv_query($conn, $sql_checknum5_nottotal, $params_checknum5_nottotal);
				// $result_checknum5_nottotal = sqlsrv_fetch_array($query_checknum5_nottotal, SQLSRV_FETCH_ASSOC); 
				while($result_checknum5_nottotal = sqlsrv_fetch_array($query_checknum5_nottotal)) {  
					$RSETMPM5=$result_checknum5_nottotal['ETM_PM'.$pmors]; 
					$CALRSETMPM5=$CALRSETMPM5+$RSETMPM5; 
				}
				$CAL_FOR_UPDATE5=$CALRSETMPM5;
	
				$sql_updatenum3 = "UPDATE ESTIMATE SET 
						ETM_PM$pmors = ?,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '3' AND ETM_AREA = '$ETM_AREA' AND ETM_GROUP = '$ETM_GROUP' AND ETM_TYPE = 'รวม'";
				$params_updatenum3 = array($CAL_FOR_UPDATE3, $ETM_EDITBY, $ETM_EDITDATE);
				$stmt_updatenum3 = sqlsrv_query( $conn, $sql_updatenum3, $params_updatenum3);

				$sql_updatenum5 = "UPDATE ESTIMATE SET 
						ETM_PM$pmors = ?,
						ETM_EDITBY = ?,
						ETM_EDITDATE = ?
						WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '5' AND ETM_AREA = '$ETM_AREA' AND ETM_GROUP = '$ETM_GROUP' AND ETM_TYPE = 'รวม'";
				$params_updatenum5 = array($CAL_FOR_UPDATE5, $ETM_EDITBY, $ETM_EDITDATE);
				$stmt_updatenum5 = sqlsrv_query( $conn, $sql_updatenum5, $params_updatenum5);		
				
				// echo"<pre>";
				// print_r($CAL_FOR_UPDATE3);
				// echo"</pre>";
				// exit();
			// } 
		}
	
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "แก้ไขข้อมูลเรียบร้อยแล้ว";	
		}		
	};	
?>