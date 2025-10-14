<?php
	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	$PROCESS_BY = $_SESSION["AD_PERSONCODE"];
	$PROCESS_DATE = date("Y-m-d H:i:s");	

// แปลงรูปแบบวันที่ให้ถูกต้อง และจัดการค่าว่าง (PHP 5 compatible)
function convertDateFormat($dateString) {
    if (empty($dateString) || trim($dateString) == '') {
        return NULL;
    }
    
    // ลบ " 0:00" ออก
    $dateString = str_replace(' 0:00', '', trim($dateString));
    
    // แยกวันที่ด้วย "/"
    $dateParts = explode('/', $dateString);
    
    if (count($dateParts) == 3) {
        $day = str_pad($dateParts[0], 2, '0', STR_PAD_LEFT);
        $month = str_pad($dateParts[1], 2, '0', STR_PAD_LEFT);
        $year = $dateParts[2];
        
        // สร้างวันที่ในรูปแบบ Y-m-d H:i:s
        return $year . '-' . $month . '-' . $day . ' 00:00:00';
    }
    
    return NULL;
}

	if(!empty($_FILES["file"]["name"])){
		$count = 0;

		$sqldel = "DELETE FROM [dbo].[IMPORT_HDMS_TEMP]";
		$paramsdel = array();
		$stmtdel = sqlsrv_query( $conn, $sqldel, $paramsdel);

		$fileMimes = array(
			'text/x-comma-separated-values',
			'text/comma-separated-values',
			'application/octet-stream',
			'application/vnd.ms-excel',
			'application/x-csv',
			'text/x-csv',
			'text/csv',
			'application/csv',
			'application/excel',
			'application/vnd.msexcel',
			'text/plain'
		);
		
		if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)){

				$csvFile = fopen($_FILES['file']['tmp_name'], 'r');

				fgetcsv($csvFile);

				while (($getData = fgetcsv($csvFile, 10000, ",")) !== FALSE){
					$temp0 = isset($getData[0]) ? $getData[0] : '';   
					
					$temp1 = convertDateFormat($getData[1]);
					$temp2 = convertDateFormat($getData[2]);
					$temp3 = convertDateFormat($getData[3]);
					
					$temp4 = isset($getData[4]) ? $getData[4] : '';
					$temp5 = isset($getData[5]) ? $getData[5] : '';
					$temp6 = isset($getData[6]) ? $getData[6] : '';
					$temp7 = isset($getData[7]) ? $getData[7] : '';
					$temp8 = isset($getData[8]) ? $getData[8] : '';
					$temp9 = isset($getData[9]) ? $getData[9] : '';
					$temp10 = isset($getData[10]) ? $getData[10] : '';
					$temp11 = isset($getData[11]) ? $getData[11] : '';
					$temp12 = isset($getData[12]) ? $getData[12] : '';
					$temp13 = isset($getData[13]) ? $getData[13] : '';
					$temp14 = isset($getData[14]) ? $getData[14] : '';
					$temp15 = isset($getData[15]) ? $getData[15] : '';
					$temp16 = isset($getData[16]) ? $getData[16] : '';
					$temp17 = isset($getData[17]) ? $getData[17] : '';
					$temp18 = isset($getData[18]) ? $getData[18] : '';
					$temp19 = isset($getData[19]) ? $getData[19] : '';
					$temp20 = isset($getData[20]) ? $getData[20] : '';
					$temp21 = isset($getData[21]) ? $getData[21] : '';
					$temp22 = isset($getData[22]) ? $getData[22] : '';
					$temp23 = isset($getData[23]) ? $getData[23] : '';
					$temp24 = isset($getData[24]) ? $getData[24] : '';
					$temp25 = isset($getData[25]) ? $getData[25] : '';
					$temp26 = isset($getData[26]) ? $getData[26] : '';
					$temp27 = isset($getData[27]) ? $getData[27] : '';
					$temp28 = isset($getData[28]) ? $getData[28] : '';
					$temp29 = isset($getData[29]) ? $getData[29] : '';
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
					
					$sql = "INSERT INTO IMPORT_HDMS_TEMP (" . implode(', ', $sql_fields) . ") VALUES (" . implode(', ', $sql_values) . ")";
					
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
					
					$count++;
				}
				fclose($csvFile);

				echo $count;			
		}
	}