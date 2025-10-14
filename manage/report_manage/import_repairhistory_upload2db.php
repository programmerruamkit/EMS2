<?php
	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	
	$PROCESS_BY = $_SESSION["AD_PERSONCODE"];
	$PROCESS_DATE = date("Y-m-d H:i:s");	

	$count_temp = "SELECT COUNT(*) AS CNT FROM IMPORT_HDMS_TEMP";
	$query_count_temp = sqlsrv_query($conn, $count_temp);
	$result_count_temp = sqlsrv_fetch_array($query_count_temp, SQLSRV_FETCH_ASSOC);
	$CNT_INSERTED = $result_count_temp['CNT'];

	$temp = "SELECT * FROM IMPORT_HDMS_TEMP";
	$querytemp = sqlsrv_query( $conn, $temp);
	while($resulttemp = sqlsrv_fetch_array($querytemp)){	
		
		$temp0 = isset($resulttemp['JobNo']) ? $resulttemp['JobNo'] : '';   
		$temp1 = isset($resulttemp['JobDate']) ? date('Y-m-d H:i:s', strtotime($resulttemp['JobDate'])) : NULL;
		$temp2 = isset($resulttemp['JobClosedDate']) ? date('Y-m-d H:i:s', strtotime($resulttemp['JobClosedDate'])) : NULL;
		$temp3 = isset($resulttemp['VehicleDeliveryDate']) ? date('Y-m-d H:i:s', strtotime($resulttemp['VehicleDeliveryDate'])) : NULL;
		$temp4 = isset($resulttemp['Type']) ? $resulttemp['Type'] : '';
		$temp5 = isset($resulttemp['JobCode']) ? $resulttemp['JobCode'] : '';
		$temp6 = isset($resulttemp['JobName']) ? $resulttemp['JobName'] : '';
		$temp7 = isset($resulttemp['Quantity']) ? $resulttemp['Quantity'] : '';
		$temp8 = isset($resulttemp['PricePerUnit']) ? $resulttemp['PricePerUnit'] : '';
		$temp9 = isset($resulttemp['ChargeType']) ? $resulttemp['ChargeType'] : '';
		$temp10 = isset($resulttemp['AdditionalCharge']) ? $resulttemp['AdditionalCharge'] : '';
		$temp11 = isset($resulttemp['TotalPrice']) ? $resulttemp['TotalPrice'] : '';
		$temp12 = isset($resulttemp['DiscountPercent']) ? $resulttemp['DiscountPercent'] : '';
		$temp13 = isset($resulttemp['DiscountAmount']) ? $resulttemp['DiscountAmount'] : '';
		$temp14 = isset($resulttemp['NetPrice']) ? $resulttemp['NetPrice'] : '';
		$temp15 = isset($resulttemp['CostValueOnWithdrawalDate']) ? $resulttemp['CostValueOnWithdrawalDate'] : '';
		$temp16 = isset($resulttemp['CurrentCostValue']) ? $resulttemp['CurrentCostValue'] : '';
		$temp17 = isset($resulttemp['CustomerCode']) ? $resulttemp['CustomerCode'] : '';
		$temp18 = isset($resulttemp['CarModel']) ? $resulttemp['CarModel'] : '';
		$temp19 = isset($resulttemp['ChassisNo']) ? $resulttemp['ChassisNo'] : '';
		$temp20 = isset($resulttemp['LicensePlateNo']) ? $resulttemp['LicensePlateNo'] : '';
		$temp21 = isset($resulttemp['CustomerName']) ? $resulttemp['CustomerName'] : '';
		$temp22 = isset($resulttemp['EngineNo']) ? $resulttemp['EngineNo'] : '';
		$temp23 = isset($resulttemp['OdometerReading']) ? $resulttemp['OdometerReading'] : '';
		$temp24 = isset($resulttemp['RepairType']) ? $resulttemp['RepairType'] : '';
		$temp25 = isset($resulttemp['TaxInvoiceNo']) ? $resulttemp['TaxInvoiceNo'] : '';
		$temp26 = isset($resulttemp['LicensePlateProvince']) ? $resulttemp['LicensePlateProvince'] : '';
		$temp27 = isset($resulttemp['TruckSideNo']) ? $resulttemp['TruckSideNo'] : '';
		$temp28 = isset($resulttemp['Status']) ? $resulttemp['Status'] : '';
		$temp30 = $PROCESS_BY;
		$temp31 = $PROCESS_DATE;
					
		// กรองเฉพาะฟิลด์ที่ไม่เป็น NULL
		$sql_fields = array();
		$sql_values = array();
		$params = array();
		
		// เพิ่มฟิลด์ที่จำเป็น
		$sql_fields[] = 'JobNo'; $sql_values[] = '?'; $params[] = $temp0;
		
		if ($temp1 !== NULL) {
			$sql_fields[] = 'JobDate'; $sql_values[] = '?'; $params[] = $temp1;
		}
		if ($temp2 !== NULL) {
			$sql_fields[] = 'JobClosedDate'; $sql_values[] = '?'; $params[] = $temp2;
		}
		if ($temp3 !== NULL) {
			$sql_fields[] = 'VehicleDeliveryDate'; $sql_values[] = '?'; $params[] = $temp3;
		}
		
		// เพิ่มฟิลด์อื่นๆ
		$sql_fields[] = 'Type'; $sql_values[] = '?'; $params[] = $temp4;
		$sql_fields[] = 'JobCode'; $sql_values[] = '?'; $params[] = $temp5;
		$sql_fields[] = 'JobName'; $sql_values[] = '?'; $params[] = $temp6;
		$sql_fields[] = 'Quantity'; $sql_values[] = '?'; $params[] = $temp7;
		$sql_fields[] = 'PricePerUnit'; $sql_values[] = '?'; $params[] = $temp8;
		$sql_fields[] = 'ChargeType'; $sql_values[] = '?'; $params[] = $temp9;
		$sql_fields[] = 'AdditionalCharge'; $sql_values[] = '?'; $params[] = $temp10;
		$sql_fields[] = 'TotalPrice'; $sql_values[] = '?'; $params[] = $temp11;
		$sql_fields[] = 'DiscountPercent'; $sql_values[] = '?'; $params[] = $temp12;
		$sql_fields[] = 'DiscountAmount'; $sql_values[] = '?'; $params[] = $temp13;
		$sql_fields[] = 'NetPrice'; $sql_values[] = '?'; $params[] = $temp14;
		$sql_fields[] = 'CostValueOnWithdrawalDate'; $sql_values[] = '?'; $params[] = $temp15;
		$sql_fields[] = 'CurrentCostValue'; $sql_values[] = '?'; $params[] = $temp16;
		$sql_fields[] = 'CustomerCode'; $sql_values[] = '?'; $params[] = $temp17;
		$sql_fields[] = 'CarModel'; $sql_values[] = '?'; $params[] = $temp18;
		$sql_fields[] = 'ChassisNo'; $sql_values[] = '?'; $params[] = $temp19;
		$sql_fields[] = 'LicensePlateNo'; $sql_values[] = '?'; $params[] = $temp20;
		$sql_fields[] = 'CustomerName'; $sql_values[] = '?'; $params[] = $temp21;
		$sql_fields[] = 'EngineNo'; $sql_values[] = '?'; $params[] = $temp22;
		$sql_fields[] = 'OdometerReading'; $sql_values[] = '?'; $params[] = $temp23;
		$sql_fields[] = 'RepairType'; $sql_values[] = '?'; $params[] = $temp24;
		$sql_fields[] = 'TaxInvoiceNo'; $sql_values[] = '?'; $params[] = $temp25;
		$sql_fields[] = 'LicensePlateProvince'; $sql_values[] = '?'; $params[] = $temp26;
		$sql_fields[] = 'TruckSideNo'; $sql_values[] = '?'; $params[] = $temp27;
		$sql_fields[] = 'Status'; $sql_values[] = '?'; $params[] = $temp28;
		$sql_fields[] = 'CREATEBY'; $sql_values[] = '?'; $params[] = $temp30;
		$sql_fields[] = 'CREATEDATE'; $sql_values[] = '?'; $params[] = $temp31;
		
		$sql = "INSERT INTO IMPORT_HDMS (" . implode(', ', $sql_fields) . ") VALUES (" . implode(', ', $sql_values) . ")";
		
		$stmt = sqlsrv_query($conn, $sql, $params);
		
		if (!$stmt) {
			echo "Error occurred during insert: ";
			echo "<pre>";
			print_r(sqlsrv_errors());
			echo "</pre>";
			echo "<br>Data being inserted: ";
			echo "<pre>";
			print_r($params);
			echo "</pre>";
			die();
		}
	}

	
	$countins = "SELECT COUNT(*) AS 'CNT' FROM dbo.IMPORT_HDMS WHERE CONVERT(VARCHAR(10),CONVERT(date, CREATEDATE, 105),23) = CONVERT(VARCHAR(10),CONVERT(date, GETDATE(), 105),23)";
	$querycountins = sqlsrv_query($conn, $countins);
	$resultcountins = sqlsrv_fetch_array($querycountins, SQLSRV_FETCH_ASSOC);
		$CNTINS = $resultcountins['CNT'];	
	$countup = "SELECT COUNT(*) AS 'CNT' FROM dbo.IMPORT_HDMS WHERE CONVERT(VARCHAR(10),CONVERT(date, MODIFIEDDATE, 105),23) = CONVERT(VARCHAR(10),CONVERT(date, GETDATE(), 105),23)";
	$querycountup = sqlsrv_query($conn, $countup);
	$resultcountup = sqlsrv_fetch_array($querycountup, SQLSRV_FETCH_ASSOC);
		$CNTUP = $resultcountup['CNT'];

	$sqldel = "DELETE FROM [dbo].[IMPORT_HDMS_TEMP]";
	$paramsdel = array();
	$stmtdel = sqlsrv_query( $conn, $sqldel, $paramsdel);
		// echo $CNTINS;
		// echo $CNTUP;
		// echo $CNTUP;
		
	// หลัง insert เสร็จ
	echo $CNT_INSERTED;