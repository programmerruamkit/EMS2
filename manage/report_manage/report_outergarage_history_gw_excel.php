<?php

session_name("EMS"); session_start();
$path = "../";   	
require($path.'../include/connect.php');
$SESSION_AREA = $_SESSION["AD_AREA"];
$ds = $_GET['ds'];
$de = $_GET['de'];
$rg = $_GET['rg'];
$pr = $_GET['pr']; // ปัญหา
$ca = $_GET['ca']; // สาเหตุ
$cu = $_GET['cu']; // อู่ซ่อม

if(isset($ds) && isset($de) && $ds != '' && $de != ''){
    $getselectdaystart = $ds;
    $getselectdayend = $de;		
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
    $decon = $end[2].'-'.$endm.'-'.$end[0].' 23:59';
}else{
    $getselectdaystart = '';
    $getselectdayend = '';
}
if(isset($rg)){
    $rgsub = substr($rg,0,7);
}else{
    $rgsub = '';
}

// เพิ่มฟังก์ชันแปลงวันที่
function formatDate($date) {
    if($date && $date != '') {
        if(is_object($date)) {
            return $date->format('d/m/Y');
        } else {
            $dateObj = new DateTime($date);
            return $dateObj->format('d/m/Y');
        }
    }
    return '';
}

$strExcelFileName = "รายงานประวัติรถซ่อมอู่นอก(".$SESSION_AREA.")" . $ds . ' ถึง ' . $de . ".xls";

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
        <table border="1" style="width: 100%;">
            <thead>
                <tr height="30">
                    <th colspan="11" style="text-align:center;background-color: #dedede">รายงานประวัติรถซ่อมอู่นอก (ประจำวันที่ <?=$ds?> - <?=$de?>)</th>
                </tr>
                <tr height="30">
                    <th rowspan="2" width="5%" style="text-align:center;background-color: #dedede">ลำดับ</th>
                    <th rowspan="2" width="10%" style="text-align:center;background-color: #dedede">ทะเบียนรถ</th>
                    <th rowspan="2" width="8%" style="text-align:center;background-color: #dedede">สายงาน</th>
                    <th rowspan="2" width="10%" style="text-align:center;background-color: #dedede">วันที่ซ่อม</th>
                    <th rowspan="2" width="10%" style="text-align:center;background-color: #dedede">วันที่เสร็จ</th>
                    <th colspan="3" width="40%" style="text-align:center;background-color: #dedede">รายละเอียดงานซ่อม</th>
                    <th rowspan="2" width="8%" style="text-align:center;background-color: #dedede">อู่ซ่อม</th>
                    <th rowspan="2" width="8%" style="text-align:center;background-color: #dedede">ราคาซ่อม</th>
                    <th rowspan="2" width="15%" style="text-align:center;background-color: #dedede">หมายเหตุ</th>
                </tr>
                <tr height="30">
                    <th style="text-align:center;background-color: #dedede">ปัญหา</th>
                    <th style="text-align:center;background-color: #dedede">สาเหตุ</th>
                    <th style="text-align:center;background-color: #dedede">วิธีซ่อม</th>
                </tr>
            </thead>
            <tbody>                      
                <?php											   		
                    // ปรับเงื่อนไขการค้นหาให้เหมาะกับตาราง IMPORT_OUTER_GARAGE
                    if($rgsub!=''){
                        $rsrg="Truck LIKE '%$rgsub%' AND ";
                    }else{$rsrg="";} 
                    if($dscon!='' && $decon!=''){
                        $rsse="RepairDate BETWEEN '$dscon' AND '$decon' AND ";
                    }else{$rsse="";}
                    if($pr!=''){
                        $rspr="Problems LIKE '%$pr%' AND ";
                    }else{$rspr="";}
                    if($ca!=''){
                        $rsca="Cause LIKE '%$ca%' AND ";
                    }else{$rsca="";}
                    if($cu!=''){
                        $rscu="Garage LIKE '%$cu%' AND ";
                    }else{$rscu="";}
                    $wh=$rsrg.$rsse.$rspr.$rsca.$rscu;
                    if($wh==''){
                        $rswh="RepairDate BETWEEN '1900-01-01 00:00:00' AND '1900-01-01 00:00:00' AND ";
                    }else{
                        $rswh=$wh;
                    }

                    // ใช้ตาราง IMPORT_OUTER_GARAGE แทน IMPORT_HDMS
                    $sql_reporthdms = "SELECT * FROM IMPORT_OUTER_GARAGE WHERE ".$rswh." 1=1 ORDER BY RepairDate ASC";
                    $query_reporthdms = sqlsrv_query($conn, $sql_reporthdms);
                    $no=0;
                    while($result_reporthdms = sqlsrv_fetch_array($query_reporthdms, SQLSRV_FETCH_ASSOC)){	
                        $no++;
                        // ปรับตัวแปรให้ตรงกับฟิลด์ใหม่
                        $temp_id = $result_reporthdms['ID'];
                        $temp_truck = $result_reporthdms['Truck'];        
                        $temp_section = $result_reporthdms['Section'];        
                        $temp_problems = $result_reporthdms['Problems'];             
                        $temp_cause = $result_reporthdms['Cause'];
                        $temp_repairmethod = $result_reporthdms['RepairMethod'];
                        $temp_repairdate = $result_reporthdms['RepairDate'];      
                        $temp_completedate = $result_reporthdms['CompleteDate'];
                        $temp_garage = $result_reporthdms['Garage'];     
                        $temp_repairprice = $result_reporthdms['RepairPrice'];               
                        $temp_remark = $result_reporthdms['Remark'];            
                        $temp_createby = $result_reporthdms['CREATEBY'];               
                        $temp_createdate = $result_reporthdms['CREATEDATE'];
                ?>
                <tr height="25px">
                    <td align="center"><?php print "$no";?></td>
                    <td align="center"><?php print "$temp_truck";?></td>
                    <td align="center"><?php print "$temp_section";?></td>
                    <td align="center"><?php echo formatDate($temp_repairdate); ?></td>
                    <td align="center"><?php echo formatDate($temp_completedate); ?></td>
                    <td align="left"><?php print "$temp_problems";?></td>
                    <td align="left"><?php print "$temp_cause";?></td>
                    <td align="left"><?php print "$temp_repairmethod";?></td>
                    <td align="center"><?php print "$temp_garage";?></td>
                    <td align="right"><?php if($temp_repairprice > 0) echo number_format($temp_repairprice, 2); ?></td>
                    <td align="left"><?php print "$temp_remark";?></td>
                </tr>
                <?php }; ?>
            </tbody>
        </table>
    </body>
</html>
