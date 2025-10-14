<?php
session_name("EMS"); session_start();
$path = "../";   	
require($path.'../include/connect.php');
require($path.'../include/Classes/PDF/vendor/autoload.php');

$SESSION_AREA = $_SESSION["AD_AREA"];
$ds = $_GET['ds'];
$de = $_GET['de'];
$rg = $_GET['rg'];
$co = $_GET['co'];
$cu = $_GET['cu'];
$dt = $_GET['dt'];

// ปรับส่วนการแปลงวันที่ให้เข้าใจง่ายขึ้น
if($ds!='' && $de!=''){
    $getselectdaystart = $ds;
    $getselectdayend = $de;		
    
    // แปลงวันที่จาก dd/mm/yyyy เป็น yyyy-mm-dd
    $start = explode("/", $ds);
    $dscon = $start[2].'-'.str_pad($start[1], 2, '0', STR_PAD_LEFT).'-'.str_pad($start[0], 2, '0', STR_PAD_LEFT).' 00:00';
    
    $end = explode("/", $de);
    $decon = $end[2].'-'.str_pad($end[1], 2, '0', STR_PAD_LEFT).'-'.str_pad($end[0], 2, '0', STR_PAD_LEFT).' 00:00';
}else{
    $getselectdaystart = '';
    $getselectdayend = '';
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
                        <th colspan='17' style='text-align:center;background-color: #dedede'>รายงานประวัติการซ่อมบำรุง L1 (ประจำวันที่ ".$ds." - ".$de.")</th>
                    </tr>
                    <tr height='30'>
                        <th rowspan='1' width='3%' style='text-align:center;background-color: #dedede'>ลำดับ</th>
                        <th rowspan='1' width='8%' style='text-align:center;background-color: #dedede'>COMPANY</th>
                        <th rowspan='1' width='7%' style='text-align:center;background-color: #dedede'>DATE</th>
                        <th rowspan='1' width='4%' style='text-align:center;background-color: #dedede'>YYYY</th>
                        <th rowspan='1' width='3%' style='text-align:center;background-color: #dedede'>MM</th>
                        <th rowspan='1' width='6%' style='text-align:center;background-color: #dedede'>JOBNO</th>
                        <th rowspan='1' width='5%' style='text-align:center;background-color: #dedede'>TYPE</th>
                        <th rowspan='1' width='6%' style='text-align:center;background-color: #dedede'>GROUP</th>
                        <th rowspan='1' width='6%' style='text-align:center;background-color: #dedede'>MILEAGE</th>
                        <th rowspan='1' width='6%' style='text-align:center;background-color: #dedede'>CUSTOMER</th>
                        <th rowspan='1' width='6%' style='text-align:center;background-color: #dedede'>TRUCKNO</th>
                        <th rowspan='1' width='6%' style='text-align:center;background-color: #dedede'>SERVICEFEE</th>
                        <th rowspan='1' width='6%' style='text-align:center;background-color: #dedede'>PARTFEE</th>
                        <th rowspan='1' width='6%' style='text-align:center;background-color: #dedede'>TOTAL</th>
                        <th rowspan='1' width='6%' style='text-align:center;background-color: #dedede'>RUNNO</th>
                        <th rowspan='1' width='10%' style='text-align:center;background-color: #dedede'>REPDESC</th>
                        <th rowspan='1' width='8%' style='text-align:center;background-color: #dedede'>REMARK</th>
                    </tr>
                </thead>";
$tablebody =    "<tbody>";     					   		
                    // ปรับเงื่อนไขการค้นหาให้เหมาะกับตาราง IMPORT_L1
                    if($rgsub!=''){
                        $rsrg="TRUCKNO LIKE '%$rgsub%' AND ";
                    }else{$rsrg="";} 
                    
                    if($dscon!='' && $decon!=''){
                        $dscon_parts = explode('-', str_replace(' 00:00', '', $dscon));
                        if(count($dscon_parts) == 3){
                            $dscon_converted = $dscon_parts[2] . '/' . $dscon_parts[1] . '/' . $dscon_parts[0]; // dd/mm/yyyy
                        } else {
                            $dscon_converted = $dscon;
                        }
                        
                        $decon_parts = explode('-', str_replace(' 00:00', '', $decon));
                        if(count($decon_parts) == 3){
                            $decon_converted = $decon_parts[2] . '/' . $decon_parts[1] . '/' . $decon_parts[0]; // dd/mm/yyyy
                        } else {
                            $decon_converted = $decon;
                        }
                        
                        $rsse="CONVERT(datetime, DATE, 103) BETWEEN CONVERT(datetime, '$dscon_converted', 103) AND CONVERT(datetime, '$decon_converted', 103) AND ";
                    }else{$rsse="";}
                    
                    if($co!=''){
                        $rsco="COMPANY LIKE '%$co%' AND ";
                    }else{$rsco="";}
                    if($cu!=''){
                        $rscu="CUSTOMER LIKE '%$cu%' AND ";
                    }else{$rscu="";}
                    if($dt!=''){
                        $rsdt="REPDESC LIKE '%$dt%' AND ";
                    }else{$rsdt="";}
                    
                    $wh=$rsrg.$rsse.$rsco.$rscu.$rsdt;
                    if($wh==''){
                        $rswh="CONVERT(datetime, DATE, 103) BETWEEN CONVERT(datetime, '01/01/1900', 103) AND CONVERT(datetime, '01/01/1900', 103) AND ";
                    }else{
                        $rswh=$wh;
                    }

                    // ใช้ตาราง IMPORT_L1 แทน IMPORT_HDMS
                    $sql_reportl1 = "SELECT * FROM IMPORT_L1 WHERE ".$rswh." ACTIVESTATUS = '1' ORDER BY CONVERT(datetime, DATE, 103) ASC";
                    $query_reportl1 = sqlsrv_query($conn, $sql_reportl1);
                    $no=0;
                    while($result_reportl1 = sqlsrv_fetch_array($query_reportl1, SQLSRV_FETCH_ASSOC)){	
                        $no++;
                        // ใช้ฟิลด์จากตาราง IMPORT_L1 โดยตรง
                        $temp1 = htmlspecialchars($result_reportl1['COMPANY']);      // COMPANY
                        $temp2 = htmlspecialchars($result_reportl1['DATE']);         // DATE
                        $temp3 = htmlspecialchars($result_reportl1['YYYY']);         // YYYY
                        $temp4 = htmlspecialchars($result_reportl1['MM']);           // MM
                        $temp5 = htmlspecialchars($result_reportl1['JOBNO']);        // JOBNO
                        $temp6 = htmlspecialchars($result_reportl1['TYPE']);         // TYPE
                        $temp7 = htmlspecialchars($result_reportl1['GROUP']);        // GROUP
                        $temp8 = htmlspecialchars($result_reportl1['MILEAGE']);      // MILEAGE
                        $temp9 = htmlspecialchars($result_reportl1['CUSTOMER']);     // CUSTOMER
                        $temp10 = htmlspecialchars($result_reportl1['TRUCKNO']);     // TRUCKNO
                        $temp11 = htmlspecialchars($result_reportl1['SERVICEFEE']);  // SERVICEFEE
                        $temp12 = htmlspecialchars($result_reportl1['PARTFEE']);     // PARTFEE
                        $temp13 = htmlspecialchars($result_reportl1['TOTAL']);       // TOTAL
                        $temp14 = htmlspecialchars($result_reportl1['RUNNO']);       // RUNNO
                        $temp15 = htmlspecialchars($result_reportl1['REPDESC']);     // REPDESC
                        $temp16 = htmlspecialchars($result_reportl1['REMARK']);      // REMARK
                        $temp17 = htmlspecialchars($result_reportl1['ACTIVESTATUS']); // ACTIVESTATUS
                        
$tablebody .=   "<tr height='25px'>
                    <td align='center'>".$no."</td>
                    <td align='center'>".$temp1."</td>
                    <td align='center'>".$temp2."</td>
                    <td align='center'>".$temp3."</td>
                    <td align='center'>".$temp4."</td>
                    <td align='center'>".$temp5."</td>
                    <td align='center'>".$temp6."</td>
                    <td align='center'>".$temp7."</td>
                    <td align='right'>".$temp8."</td>
                    <td align='center'>".$temp9."</td>
                    <td align='center'>".$temp10."</td>
                    <td align='right'>".$temp11."</td>
                    <td align='right'>".$temp12."</td>
                    <td align='right'>".$temp13."</td>
                    <td align='center'>".$temp14."</td>
                    <td align='left'>".$temp15."</td>
                    <td align='left'>".$temp16."</td>
                </tr>";
                }; 
$tableclose = "</tbody></table>";

$mpdf->WriteHTML($style);
$mpdf->WriteHTML($tableopen);
$mpdf->WriteHTML($tablehead);
$mpdf->WriteHTML($tablebody);
$mpdf->WriteHTML($tableclose);
$mpdf->Output();
?>

