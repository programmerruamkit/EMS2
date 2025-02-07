<?php
session_start();
$path = "../";   	
require($path.'../include/connect.php');
require($path.'../include/Classes/PDF/vendor/autoload.php');

// echo"<pre>";
// print_r($_GET);
// echo"</pre>";

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


$mpdf = new mPDF('th', 'A4-L', '0', '');
$style = '
<style>
	body{
		font-family: "Garuda";font-size:12px//เรียกใช้font Garuda สำหรับแสดงผล ภาษาไทย
	}
</style>';
$tableopen =    "<table  border='1' style='width: 100%;'>";
$tablehead =    "<thead>
                    <tr height='30'>
                        <th colspan='18' style='text-align:center;background-color: #dedede'>รายงานประวัติการซ่อมบำรุง E-Maintenance (ประจำวันที่ ".$ds." - ".$de.")</th>
                    </tr>
                    <tr height='30'>
                        <th rowspan='2' width='5%' style='text-align:center;background-color: #dedede'>ลำดับ.</th>
                        <th rowspan='2' width='5%' style='text-align:center;background-color: #dedede'>บริษัท</th>
                        <th rowspan='2' width='5%' style='text-align:center;background-color: #dedede'>ลูกค้า</th>
                        <th rowspan='2' width='5%' style='text-align:center;background-color: #dedede'>วันที่</th>
                        <th colspan='3' width='15%' style='text-align:center;background-color: #dedede'>ข้อมูลรถ</th>
                        <th rowspan='2' width='5%' style='text-align:center;background-color: #dedede'>เลข JOB</th>
                        <th colspan='10' width='60%' style='text-align:center;background-color: #dedede'>รายละเอียดงานซ่อม</th>
                    </tr>
                    <tr height='30'>
                        <th style='text-align:center;background-color: #dedede'>ทะเบียนรถ</th>
                        <th style='text-align:center;background-color: #dedede'>ทะเบียนเดิม</th>           
                        <th style='text-align:center;background-color: #dedede'>เลขไมล์ล่าสุด</th>					
                        <th style='text-align:center;background-color: #dedede'>ชื่องาน</th>
                        <th style='text-align:center;background-color: #dedede'>รายละเอียด</th>
                        <th style='text-align:center;background-color: #dedede'>ปริมาณ</th>
                        <th style='text-align:center;background-color: #dedede'>ราคาต่อหน่วย</th>
                        <th style='text-align:center;background-color: #dedede'>ราคาขาย</th>
                        <th style='text-align:center;background-color: #dedede'>รวมเงิน</th>
                        <th style='text-align:center;background-color: #dedede'>ค่าแรง</th>
                        <th style='text-align:center;background-color: #dedede'>ผู้ขายอะไหล่</th>
                        <th style='text-align:center;background-color: #dedede'>ชั่วโมงทำงานจริง</th>
                        <th style='text-align:center;background-color: #dedede'>ชั่วโมงเก็บเงิน</th>
                    </tr>
                </thead>";
$tablebody =    "<tbody>";     
			
                    if($rgsub!=''){
                        $rsrg="REGNO LIKE '%$rgsub%' AND ";
                    }else{$rsrg="";} 
					if($dscon!='-- 00:00' && $decon!='-- 00:00'){
                        $rsse="OPENDATECON BETWEEN '$dscon' AND '$decon' AND ";
                        // $rsse="OPENDATECON BETWEEN '$dscon' AND '$decon' AND ";
                    }else{$rsse="";}
                    if($co!=''){
                        $rsco="NICKNM LIKE '%$co%' AND ";
                    }else{$rsco="";}
                    if($cu!=''){
                        $rscu="CUSCOD LIKE '%$cu%' AND ";
                    }else{$rscu="";}
                    if($dt!=''){
                        $rsdt="SPAREPARTSDETAIL LIKE '%$dt%' AND";
                    }else{$rsdt="";}
                    $wh=$rsrg.$rsse.$rsco.$rscu.$rsdt;
                    if($wh==''){
                        $rswh="OPENDATECON BETWEEN '00/00/0000 00:00' AND '00/00/000 00:00' AND ";
                    }else{
                        $rswh=$wh;
                    }

					$sql_reporthdms = "SELECT * FROM vwRKTC_MERGENEWOLD_TEST WHERE ".$rswh." 1=1 ORDER BY OPENDATECON ASC";

                    // $wh="REGNO LIKE '%$rgsub%' AND OPENDATE BETWEEN '$dscon' AND '$decon' AND NICKNM LIKE '%$co%' AND CUSCOD LIKE '%$cu%' AND SPAREPARTSDETAIL LIKE '%$dt%'";
                    // $sql_reporthdms = "SELECT * FROM RKTC WHERE ".$wh."";
                    $query_reporthdms = sqlsrv_query($conn, $sql_reporthdms);
                    $no=0;
                    while($result_reporthdms = sqlsrv_fetch_array($query_reporthdms, SQLSRV_FETCH_ASSOC)){	
                        $no++;
                        $temp0 = $result_reporthdms['RKTCID'];
                        $temp1 = $result_reporthdms['NICKNM'];
                        $temp2 = $result_reporthdms['CUSCOD'];
                        $temp3 = $result_reporthdms['OPENDATECON'];
                        $temp4 = $result_reporthdms['CLOSEDATE'];
                        $temp5 = $result_reporthdms['TAXINVOICEDATE'];
                        $temp6 = $result_reporthdms['REGNO'];
                        $temp7 = $result_reporthdms['CHASSIS'];
                        $temp8 = $result_reporthdms['MILEAGE'];
                        $temp9 = $result_reporthdms['JOBNO']; 
                        $temp10 = $result_reporthdms['TYPNAME'];
                        $temp11 = $result_reporthdms['SPAREPARTSDETAIL'];
                        $temp12 = $result_reporthdms['NET'];
                        $temp13 = $result_reporthdms['COST'];
                        $temp14 = $result_reporthdms['SELLING'];
                        $temp15 = $result_reporthdms['SPAREPARTSSELLER'];
                        $temp16 = $result_reporthdms['SUMMARY'];
                        $temp17 = $result_reporthdms['WAGES'];
                        $temp18 = $result_reporthdms['MECHANIC'];
                        $temp19 = $result_reporthdms['WORKINGHOURS'];
                        $temp20 = $result_reporthdms['COLLECTIONHOURS'];
                        
                        $regisold = "SELECT VEHICLEREGISNUMBER,REMARK FROM vwVEHICLEINFO WHERE VEHICLEREGISNUMBER = '$temp6'";
                        $queryregisold = sqlsrv_query($conn, $regisold);
                        $resultregisold = sqlsrv_fetch_array($queryregisold, SQLSRV_FETCH_ASSOC);
                        if(isset($resultregisold['REMARK'])){
                            $REMARK = str_replace('ทะเบียนเดิม', '', $resultregisold['REMARK']);
                        }
$tablebody .=   "<tr height='25px'>
                    <td align='center'>".$no."</td>
                    <td align='center'>".$temp1."</td>
                    <td align='center'>".$temp2."</td>
                    <td align='center'>".$temp3."</td>
                    <td align='center'>".$temp6."</td>
                    <td align='center'>".$REMARK."</td>
                    <td align='center'>".$temp8."</td>
                    <td align='center'>".$temp9."</td>
                    <td align='left'>".$temp10."</td>
                    <td align='left'>".$temp11."</td>
                    <td align='right'>".$temp12."</td>
                    <td align='right'>".$temp13."</td>
                    <td align='right'>".$temp14."</td>
                    <td align='right'>".$temp16."</td>
                    <td align='right'>".$temp17."</td>
                    <td align='left'>".$temp15."</td>
                    <td align='left'>".$temp19."</td>
                    <td align='right'>".$temp20."</td>
                </tr>";
                }; 
$tableclose = "</tbody></table>";

$mpdf->WriteHTML($style);
$mpdf->WriteHTML($tableopen);
$mpdf->WriteHTML($tablehead);
$mpdf->WriteHTML($tablebody);
$mpdf->WriteHTML($tableclose);
$mpdf->Output();
// $mpdf->Output('blankdocument.pdf', new mPDF);

