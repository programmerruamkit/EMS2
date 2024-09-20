<?php
	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	if(!empty($_FILES["file"]["name"])){
		$count = 0;

		$sqldel = "DELETE FROM [dbo].[RKTC_TEMP]";
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
					$temp0 = $getData[0];	//RKTCID
					$temp1 = $getData[1];	//NICKNM
					$temp2 = $getData[2];	//CUSCOD
					$temp3 = $getData[3];	//OPENDATE
					$temp4 = $getData[4];	//CLOSEDATE
					$temp5 = $getData[5];	//TAXINVOICEDATE
					$temp6 = $getData[6];	//REGNO
					$temp7 = $getData[7];	//CHASSIS
					$temp8 = $getData[8];	//MILEAGE
					$temp9 = $getData[9]; 	//JOBNO
					$temp10 = $getData[10];	//TYPNAME
					$temp11 = $getData[11];	//SPAREPARTSDETAIL
					$temp12 = $getData[12];	//NET
					$temp13 = $getData[13];	//COST
					$temp14 = $getData[14];	//SELLING
					$temp15 = $getData[15];	//SPAREPARTSSELLER
					$temp16 = $getData[16];	//SUMMARY
					$temp17 = $getData[17];	//WAGES
					$temp18 = $getData[18];	//MECHANIC
					$temp19 = $getData[19];	//WORKINGHOURS
					$temp20 = $getData[20];	//COLLECTIONHOURS
							// AREA
							// REMARK
							// ACTIVESTATUS
							// CREATEBY
							// CREATEDATE
							// MODIFIEDBY
							// MODIFIEDDATE
					// $query = "SELECT JOBNO,SPAREPARTSDETAIL FROM RKTC_TEMP WHERE JOBNO = '$temp9' AND SPAREPARTSDETAIL = '$temp11' AND SPAREPARTSSELLER = '$temp15'";
					// $check = sqlsrv_query($conn, $query);
					// $result = sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC);
					// 	$JOBNO=$result["JOBNO"];
					// 	$SPAREPARTSDETAIL=$result["SPAREPARTSDETAIL"];
					// if(isset($JOBNO)){                 
					// 		$sql = "UPDATE RKTC_TEMP SET NICKNM = ?,CUSCOD = ?,OPENDATE = ?,CLOSEDATE = ?,TAXINVOICEDATE = ?,REGNO = ?,CHASSIS = ?,MILEAGE = ?,TYPNAME = ?,NET = ?,COST = ?,SELLING = ?,SPAREPARTSSELLER = ?,SUMMARY = ?,WAGES = ?,MECHANIC = ?,WORKINGHOURS = ?,COLLECTIONHOURS = ?
					// 				WHERE JOBNO = ? AND SPAREPARTSDETAIL = ?";
					// 		$params = array($temp1,$temp2,$temp3,$temp4,$temp5,$temp6,$temp7,$temp8,$temp10,$temp12,$temp13,$temp14,$temp15,$temp16,$temp17,$temp18,$temp19,$temp20,$temp9,$temp11);
					// 		$stmt = sqlsrv_query( $conn, $sql, $params);
					// }else{
						$sql = "INSERT INTO RKTC_TEMP (NICKNM,CUSCOD,OPENDATE,CLOSEDATE,TAXINVOICEDATE,REGNO,CHASSIS,MILEAGE,JOBNO,TYPNAME,SPAREPARTSDETAIL,NET,COST,SELLING,SPAREPARTSSELLER,SUMMARY,WAGES,MECHANIC,WORKINGHOURS,COLLECTIONHOURS) 
							VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
						$params = array($temp0,$temp1,$temp2,$temp3,$temp4,$temp5,$temp6,$temp7,$temp8,$temp9,$temp10,$temp11,$temp12,$temp13,$temp14,$temp15,$temp16,$temp17,$temp18,$temp19);
						$stmt = sqlsrv_query( $conn, $sql, $params);
					// }
					$count++;
				}
				fclose($csvFile);

				echo $count;			
		}
	}