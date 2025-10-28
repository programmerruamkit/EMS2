<?php

session_name("EMS"); session_start();
$path = "../";   	
require($path.'../include/connect.php');
$SESSION_AREA = $_SESSION["AD_AREA"];
$ds = $_GET['ds'];
$de = $_GET['de'];
$rg = $_GET['rg'];
$co = $_GET['co'];
$cu = $_GET['cu'];
$dt = $_GET['dt'];
if(isset($ds)&&isset($de)){
    $getselectdaystart = $ds;
    $getselectdayend = $de;		
    // $dsdate = str_replace('/', '-', $ds);
    // $dscon = date('Y-m-d', strtotime($dsdate));
    $start = explode("/", $ds);
    $startd = $start[0];
    $startif = $start[1];
		if($startif == "01"){
			$startm = "01";
		}else if($startif == "02"){
			$startm = "02";
		}else if($startif == "03"){
			$startm = "03";
		}else if($startif == "04"){
			$startm = "04";
		}else if($startif == "05"){
			$startm = "05";
		}else if($startif == "06"){
			$startm = "06";
		}else if($startif == "07"){
			$startm = "07";
		}else if($startif== "08"){
			$startm = "08";
		}else if($startif == "09"){
			$startm = "09";
		}else if($startif == "10"){
			$startm = "10";
		}else if($startif == "11"){
			$startm = "11";
		}else if($startif == "12"){
			$startm = "12";
		}
	$dscon = $start[2].'-'.$startm.'-'.$start[0].' 00:00';

    // $dedate = str_replace('/', '-', $de);
    // $decon = date('Y-m-d', strtotime($dedate));		
    $end = explode("/", $de);
    $endd = $end[0];
    $endif = $end[1];
		if($endif == "01"){
			$endm = "01";
		}else if($endif == "02"){
			$endm = "02";
		}else if($endif == "03"){
			$endm = "03";
		}else if($endif == "04"){
			$endm = "04";
		}else if($endif == "05"){
			$endm = "05";
		}else if($endif == "06"){
			$endm = "06";
		}else if($endif == "07"){
			$endm = "07";
		}else if($endif== "08"){
			$endm = "08";
		}else if($endif == "09"){
			$endm = "09";
		}else if($endif == "10"){
			$endm = "10";
		}else if($endif == "11"){
			$endm = "11";
		}else if($endif == "12"){
			$endm = "12";
		}
	$decon = $end[2].'-'.$endm.'-'.$end[0].' 00:00';
}else{
    $getselectdaystart = $GETDAYEN;
    $getselectdayend = $GETDAYEN;
}
if(isset($rg)){
    $rgsub = substr($rg,0,7);
}else{
    $rgsub = '';
}

// echo"<pre>";
// print_r($_GET);
// echo"</pre>";
// exit();
$strExcelFileName = "รายงานประวัติการซ่อมบำรุง(".$SESSION_AREA.")" . $ds . ' ถึง ' . $de . ".xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
header("Pragma:no-cache");

?>
<html>
    <head>
        <link rel="shortcut icon" href="https://img2.pic.in.th/pic/car_repair.png" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <table  border="1" style="width: 100%;">
            <thead>
                <tr height="30">
                    <th colspan="19" style="text-align:center;background-color: #dedede">รายงานประวัติการซ่อมบำรุง E-Maintenance (ประจำวันที่ <?=$ds?> - <?=$de?>)</th>
                </tr>
                <tr height="30">
                    <th rowspan="2" width="3%" style="text-align:center;background-color: #dedede">ลำดับ.</th>
                    <th rowspan="2" width="8%" style="text-align:center;background-color: #dedede">ชื่อลูกค้า</th>
                    <th rowspan="2" width="5%" style="text-align:center;background-color: #dedede">รหัสลูกค้า</th>
                    <th rowspan="2" width="7%" style="text-align:center;background-color: #dedede">วันที่ Job</th>
                    <th colspan="5" width="25%" style="text-align:center;background-color: #dedede">ข้อมูลรถ</th>
                    <th rowspan="2" width="8%" style="text-align:center;background-color: #dedede">เลขที่ Job</th>
                    <th colspan="9" width="49%" style="text-align:center;background-color: #dedede">รายละเอียดงานซ่อม</th>
                </tr>
                <tr height="30">
                    <th style="text-align:center;background-color: #dedede">ทะเบียน</th>
                    <th style="text-align:center;background-color: #dedede">ชื่อรถ</th>
                    <th style="text-align:center;background-color: #dedede">รุ่นรถ</th>
                    <th style="text-align:center;background-color: #dedede">เลขตัวถัง</th>
                    <th style="text-align:center;background-color: #dedede">เลขไมล์</th>
                    <th style="text-align:center;background-color: #dedede">เลขใบกำกับ</th>
                    <th style="text-align:center;background-color: #dedede">ประเภท</th>
                    <th style="text-align:center;background-color: #dedede">ชื่องาน</th>
                    <th style="text-align:center;background-color: #dedede">ประเภทการซ่อม</th>
                    <th style="text-align:center;background-color: #dedede">จำนวน</th>
                    <th style="text-align:center;background-color: #dedede">ราคา/หน่วย</th>
                    <th style="text-align:center;background-color: #dedede">ราคาสุทธิ</th>
                    <th style="text-align:center;background-color: #dedede">รวมราคา</th>
                    <th style="text-align:center;background-color: #dedede">ทะเบียนจังหวัด</th>
                </tr>
            </thead>
            <tbody>                      
				<?php											   		
					// ปรับเงื่อนไขการค้นหาให้เหมาะกับตาราง IMPORT_HDMS
					if($rgsub!=''){
						$rsrg="LicensePlateNo LIKE '%$rgsub%' AND ";
					}else{$rsrg="";} 
					if($dscon!='-- 00:00' && $decon!='-- 00:00'){
						$rsse="JobDate BETWEEN '$dscon' AND '$decon' AND ";
					}else{$rsse="";}
					if($co!=''){
						$rsco="CustomerCode LIKE '%$co%' AND ";
					}else{$rsco="";}
					if($cu!=''){
						$rscu="CustomerCode LIKE '%$cu%' AND ";
					}else{$rscu="";}
					if($dt!=''){
						$rsdt="JobName LIKE '%$dt%' AND";
					}else{$rsdt="";}
					$wh=$rsrg.$rsse.$rsco.$rscu.$rsdt;
					if($wh==''){
						$rswh="JobDate BETWEEN '1900-01-01 00:00:00' AND '1900-01-01 00:00:00' AND ";
					}else{
						$rswh=$wh;
					}

					// ใช้ตาราง IMPORT_HDMS แทน vwRKTC_MERGENEWOLD_TEST
					$sql_reporthdms = "SELECT * FROM IMPORT_HDMS 
					LEFT JOIN vwVEHICLEINFO A ON A.VEHICLEREGISNUMBER = LicensePlateNo
					WHERE ".$rswh." 1=1 ORDER BY JobDate ASC";
					$query_reporthdms = sqlsrv_query($conn, $sql_reporthdms);
					$no=0;
					while($result_reporthdms = sqlsrv_fetch_array($query_reporthdms, SQLSRV_FETCH_ASSOC)){	
						$no++;
						// ปรับตัวแปรให้ตรงกับฟิลด์และข้อมูลที่แสดง
						$temp0 = $result_reporthdms['ID'];
						$temp1 = $result_reporthdms['CustomerName'];           // ชื่อลูกค้า
						$temp2 = $result_reporthdms['CustomerCode'];           // รหัสลูกค้า
						$temp3 = $result_reporthdms['JobDate'];                // วันที่ Job
						$temp4 = $result_reporthdms['LicensePlateNo'];         // เลขทะเบียน
						$temp5 = $result_reporthdms['CarModel'];               // รุ่นรถ
						$temp6 = $result_reporthdms['ChassisNo'];              // เลขตัวถัง
						$temp7 = $result_reporthdms['OdometerReading'];        // เลขไมค์
						$temp8 = $result_reporthdms['JobNo'];                  // เลขที่ Job
						$temp9 = $result_reporthdms['Type'];                   // ประเภท
						$temp10 = $result_reporthdms['JobName'];               // ชื่องาน
						$temp11 = $result_reporthdms['Quantity'];              // จำนวน
						$temp12 = $result_reporthdms['PricePerUnit'];          // ราคา/หน่วย
						$temp13 = $result_reporthdms['NetPrice'];              // ราคาสุทธิ
						$temp14 = $result_reporthdms['TotalPrice'];            // รวมราคา
						$temp15 = $result_reporthdms['TaxInvoiceNo'];          // เลขใบกำกับ
						$temp16 = $result_reporthdms['RepairType'];            // ประเภทการซ่อม
						$temp17 = $result_reporthdms['LicensePlateProvince'];  // ทะเบียนจังหวัด
						$temp18 = $result_reporthdms['TruckSideNo'];           // เลขข้างรถ
						$temp19 = $result_reporthdms['THAINAME'];			   // ชื่อรถ
						if(empty($temp18)){
							$namecar = $temp19;
						}else{
							$namecar = $temp18;
						}
				?>
				<tr height="25px">
					<td align="center"><?php print "$no";?></td>
					<td align="left"><?php print "$temp1";?></td>
					<td align="center"><?php print "$temp2";?></td>
					<td align="center"><?php print "$temp3";?></td>
					<td align="center"><?php print "$temp4";?></td>
					<td align="center"><?php print "$namecar";?></td>
					<td align="center"><?php print "$temp5";?></td>
					<td align="center"><?php print "$temp6";?></td>
					<td align="right"><?php print "$temp7";?></td>
					<td align="center"><?php print "$temp8";?></td>
					<td align="center"><?php print "$temp15";?></td>
					<td align="left"><?php print "$temp9";?></td>
					<td align="left"><?php print "$temp10";?></td>
					<td align="left"><?php print "$temp16";?></td>
					<td align="right"><?php print "$temp11";?></td>
					<td align="right"><?php print "$temp12";?></td>
					<td align="right"><?php print "$temp13";?></td>
					<td align="right"><?php print "$temp14";?></td>
					<td align="center"><?php print "$temp17";?></td>
				</tr>
				<?php }; ?>
			</tbody>
        </table>
    </body>
</html>
