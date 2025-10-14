<?php
	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	
	$PROCESS_BY = $_SESSION["AD_PERSONCODE"];
	$PROCESS_DATE = date("Y-m-d H:i:s");	

	$count_temp = "SELECT COUNT(*) AS CNT FROM IMPORT_OUTER_GARAGE_TEMP";
	$query_count_temp = sqlsrv_query($conn, $count_temp);
	$result_count_temp = sqlsrv_fetch_array($query_count_temp, SQLSRV_FETCH_ASSOC);
	$CNT_INSERTED = $result_count_temp['CNT'];

	$temp = "SELECT * FROM IMPORT_OUTER_GARAGE_TEMP";
	$querytemp = sqlsrv_query( $conn, $temp);
	while($resulttemp = sqlsrv_fetch_array($querytemp)){	
		
		$temp0 = isset($resulttemp['Truck']) ? $resulttemp['Truck'] : '';   
		$temp1 = isset($resulttemp['Section']) ? $resulttemp['Section'] : '';
		$temp2 = isset($resulttemp['Problems']) ? $resulttemp['Problems'] : '';
		$temp3 = isset($resulttemp['Cause']) ? $resulttemp['Cause'] : '';
		$temp4 = isset($resulttemp['RepairMethod']) ? $resulttemp['RepairMethod'] : '';
		
		// จัดการวันที่ - ถ้าเป็น datetime object ให้แปลงเป็น string
		$temp5 = NULL;
		if (isset($resulttemp['RepairDate']) && !empty($resulttemp['RepairDate'])) {
			if (is_object($resulttemp['RepairDate']) && get_class($resulttemp['RepairDate']) == 'DateTime') {
				$temp5 = $resulttemp['RepairDate']->format('Y-m-d H:i:s');
			} else {
				$temp5 = $resulttemp['RepairDate'];
			}
		}
		
		$temp6 = NULL;
		if (isset($resulttemp['CompleteDate']) && !empty($resulttemp['CompleteDate'])) {
			if (is_object($resulttemp['CompleteDate']) && get_class($resulttemp['CompleteDate']) == 'DateTime') {
				$temp6 = $resulttemp['CompleteDate']->format('Y-m-d H:i:s');
			} else {
				$temp6 = $resulttemp['CompleteDate'];
			}
		}
		
		$temp7 = isset($resulttemp['Garage']) ? $resulttemp['Garage'] : '';
		$temp8 = isset($resulttemp['RepairPrice']) ? $resulttemp['RepairPrice'] : '';
		$temp9 = isset($resulttemp['Remark']) ? $resulttemp['Remark'] : '';
		$temp10 = $PROCESS_BY; // CREATEBY
		$temp11 = $PROCESS_DATE; // CREATEDATE
					
		// กรองเฉพาะฟิลด์ที่ไม่เป็น NULL
		$sql_fields = array();
		$sql_values = array();
		$params = array();
		
		// เพิ่มฟิลด์ที่จำเป็น
		$sql_fields[] = 'Truck'; $sql_values[] = '?'; $params[] = $temp0;
		$sql_fields[] = 'Section'; $sql_values[] = '?'; $params[] = $temp1;
		$sql_fields[] = 'Problems'; $sql_values[] = '?'; $params[] = $temp2;
		$sql_fields[] = 'Cause'; $sql_values[] = '?'; $params[] = $temp3;
		$sql_fields[] = 'RepairMethod'; $sql_values[] = '?'; $params[] = $temp4;
		
		// วันที่ - เพิ่มเฉพาะถ้าไม่เป็น NULL
		if ($temp5 !== NULL) {
			$sql_fields[] = 'RepairDate'; $sql_values[] = '?'; $params[] = $temp5;
		}
		if ($temp6 !== NULL) {
			$sql_fields[] = 'CompleteDate'; $sql_values[] = '?'; $params[] = $temp6;
		}
		
		// เพิ่มฟิลด์อื่นๆ
		$sql_fields[] = 'Garage'; $sql_values[] = '?'; $params[] = $temp7;
		$sql_fields[] = 'RepairPrice'; $sql_values[] = '?'; $params[] = $temp8;
		$sql_fields[] = 'Remark'; $sql_values[] = '?'; $params[] = $temp9;
		$sql_fields[] = 'CREATEBY'; $sql_values[] = '?'; $params[] = $temp10;
		$sql_fields[] = 'CREATEDATE'; $sql_values[] = '?'; $params[] = $temp11;
		
		// เปลี่ยนตารางปลายทางเป็น IMPORT_OUTER_GARAGE แทน IMPORT_HDMS
		$sql = "INSERT INTO IMPORT_OUTER_GARAGE (" . implode(', ', $sql_fields) . ") VALUES (" . implode(', ', $sql_values) . ")";
		
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

	// อัปเดต query สำหรับการนับ
	$countins = "SELECT COUNT(*) AS 'CNT' FROM dbo.IMPORT_OUTER_GARAGE WHERE CONVERT(VARCHAR(10),CONVERT(date, CREATEDATE, 105),23) = CONVERT(VARCHAR(10),CONVERT(date, GETDATE(), 105),23)";
	$querycountins = sqlsrv_query($conn, $countins);
	$resultcountins = sqlsrv_fetch_array($querycountins, SQLSRV_FETCH_ASSOC);
		$CNTINS = $resultcountins['CNT'];	
	$countup = "SELECT COUNT(*) AS 'CNT' FROM dbo.IMPORT_OUTER_GARAGE WHERE CONVERT(VARCHAR(10),CONVERT(date, MODIFIEDDATE, 105),23) = CONVERT(VARCHAR(10),CONVERT(date, GETDATE(), 105),23)";
	$querycountup = sqlsrv_query($conn, $countup);
	$resultcountup = sqlsrv_fetch_array($querycountup, SQLSRV_FETCH_ASSOC);
		$CNTUP = $resultcountup['CNT'];

	$sqldel = "DELETE FROM [dbo].[IMPORT_OUTER_GARAGE_TEMP]";
	$paramsdel = array();
	$stmtdel = sqlsrv_query( $conn, $sqldel, $paramsdel);
		// echo $CNTINS;
		// echo $CNTUP;
		// echo $CNTUP;
		
	// หลัง insert เสร็จ
	echo $CNT_INSERTED;
?>