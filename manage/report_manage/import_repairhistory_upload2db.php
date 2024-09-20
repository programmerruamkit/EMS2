<?php
	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	
	$PROCESS_BY = $_SESSION["AD_PERSONCODE"];
	$PROCESS_DATE = date("Y-m-d H:i:s");		
	
	$temp = "SELECT * FROM RKTC_TEMP";
	$querytemp = sqlsrv_query( $conn, $temp);
	while($resulttemp = sqlsrv_fetch_array($querytemp)){	
		$temp0 = $resulttemp['RKTCID'];
		$temp1 = $resulttemp['NICKNM'];
		$temp2 = $resulttemp['CUSCOD'];
		$temp3 = $resulttemp['OPENDATE'];
		$temp4 = $resulttemp['CLOSEDATE'];
		$temp5 = $resulttemp['TAXINVOICEDATE'];
		$temp6 = $resulttemp['REGNO'];
		$temp7 = $resulttemp['CHASSIS'];
		$temp8 = $resulttemp['MILEAGE'];
		$temp9 = $resulttemp['JOBNO']; 
		$temp10 = $resulttemp['TYPNAME'];
		$temp11 = $resulttemp['SPAREPARTSDETAIL'];
		$temp12 = $resulttemp['NET'];
		$temp13 = $resulttemp['COST'];
		$temp14 = $resulttemp['SELLING'];
		$temp15 = $resulttemp['SPAREPARTSSELLER'];
		$temp16 = $resulttemp['SUMMARY'];
		$temp17 = $resulttemp['WAGES'];
		$temp18 = $resulttemp['MECHANIC'];
		$temp19 = $resulttemp['WORKINGHOURS'];
		$temp20 = $resulttemp['COLLECTIONHOURS'];
			
		$fromdb = "SELECT JOBNO,SPAREPARTSDETAIL,SPAREPARTSSELLER FROM RKTC WHERE JOBNO = '$temp9' AND SPAREPARTSDETAIL = '$temp11' AND SPAREPARTSSELLER = '$temp15'";
		$queryfromdb = sqlsrv_query($conn, $fromdb);
		$resultfromdb = sqlsrv_fetch_array($queryfromdb, SQLSRV_FETCH_ASSOC);
			$fromdb9 = $resultfromdb['JOBNO']; 
			$fromdb11 = $resultfromdb['SPAREPARTSDETAIL'];
			$fromdb15 = $resultfromdb['SPAREPARTSSELLER'];

		// if(isset($fromdb9)){                 
		// 	$sql = "UPDATE RKTC SET NICKNM = ?,CUSCOD = ?,OPENDATE = ?,CLOSEDATE = ?,TAXINVOICEDATE = ?,REGNO = ?,CHASSIS = ?,MILEAGE = ?,TYPNAME = ?,NET = ?,COST = ?,SELLING = ?,SPAREPARTSSELLER = ?,SUMMARY = ?,WAGES = ?,MECHANIC = ?,WORKINGHOURS = ?,COLLECTIONHOURS = ?,MODIFIEDBY = ?,MODIFIEDDATE = ?
		// 			WHERE JOBNO = ? AND SPAREPARTSDETAIL = ?";
		// 	$params = array($temp1,$temp2,$temp3,$temp4,$temp5,$temp6,$temp7,$temp8,$temp10,$temp12,$temp13,$temp14,$temp15,$temp16,$temp17,$temp18,$temp19,$temp20,'SYSTEM',$PROCESS_DATE,$temp9,$temp11);
		// 	$update = sqlsrv_query( $conn, $sql, $params);
		// 	if(isset($update)) {
		// 		// echo "อัพเดท 1";
		// 	}else{
		// 		die( print_r( sqlsrv_errors(), true));
		// 	}
		// }else{
			$sql1 = "INSERT INTO RKTC (NICKNM,CUSCOD,OPENDATE,CLOSEDATE,TAXINVOICEDATE,REGNO,CHASSIS,MILEAGE,JOBNO,TYPNAME,SPAREPARTSDETAIL,NET,COST,SELLING,SPAREPARTSSELLER,SUMMARY,WAGES,MECHANIC,WORKINGHOURS,COLLECTIONHOURS,ACTIVESTATUS,CREATEBY,CREATEDATE,MODIFIEDBY,MODIFIEDDATE) 
				VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$params1 = array($temp1,$temp2,$temp3,$temp4,$temp5,$temp6,$temp7,$temp8,$temp9,$temp10,$temp11,$temp12,$temp13,$temp14,$temp15,$temp16,$temp17,$temp18,$temp19,$temp20,1,'SYSTEM',$PROCESS_DATE,'SYSTEM',$PROCESS_DATE);
			$insert = sqlsrv_query( $conn, $sql1, $params1);
			if(isset($insert)) {
				// echo "เพิ่ม 2";
			}else{
				die( print_r( sqlsrv_errors(), true));
			}
		// }
	}

	
	$countins = "SELECT COUNT(*) AS 'CNT' FROM dbo.RKTC WHERE CONVERT(VARCHAR(10),CONVERT(date, CREATEDATE, 105),23) = CONVERT(VARCHAR(10),CONVERT(date, GETDATE(), 105),23)";
	$querycountins = sqlsrv_query($conn, $countins);
	$resultcountins = sqlsrv_fetch_array($querycountins, SQLSRV_FETCH_ASSOC);
		$CNTINS = $resultcountins['CNT'];	
	$countup = "SELECT COUNT(*) AS 'CNT' FROM dbo.RKTC WHERE CONVERT(VARCHAR(10),CONVERT(date, MODIFIEDDATE, 105),23) = CONVERT(VARCHAR(10),CONVERT(date, GETDATE(), 105),23)";
	$querycountup = sqlsrv_query($conn, $countup);
	$resultcountup = sqlsrv_fetch_array($querycountup, SQLSRV_FETCH_ASSOC);
		$CNTUP = $resultcountup['CNT'];

		// echo $CNTINS;
		// echo $CNTUP;
		echo $CNTUP;