<?php

session_start();
$path = "../";   	
require($path.'../include/connect.php');
$SESSION_AREA = $_SESSION["AD_AREA"];
$txt_daterepair=$_GET['txt_daterepair'];

$strExcelFileName = "แผนการทำงาน (".$SESSION_AREA.").xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
header("Pragma:no-cache");

// echo"<pre>";
// print_r($_GET);
// echo"</pre>";
// exit();
$date1 = $txt_daterepair;
$start = explode("/", $date1);
$startd = $start[0];
$startif = $start[1];
    if($startif == "01"){
        $startm = "มกราคม";
    }else if($startif == "02"){
        $startm = "กุมภาพันธ์";
    }else if($startif == "03"){
        $startm = "มีนาคม";
    }else if($startif == "04"){
        $startm = "เมษายน";
    }else if($startif == "05"){
        $startm = "พฤษภาคม";
    }else if($startif == "06"){
        $startm = "มิถุนายน";
    }else if($startif == "07"){
        $startm = "กรกฎาคม";
    }else if($startif == "08"){
        $startm = "สิงหาคม";
    }else if($startif == "09"){
        $startm = "กันยายน";
    }else if($startif == "10"){
        $startm = "ตุลาคม";
    }else if($startif == "11"){
        $startm = "พฤศจิกายน";
    }else if($startif == "12"){
        $startm = "ธันวาคม";
    }
$starty = $start[2]+543;
$startymd = $start[2].'-'.$start[1].'-'.$start[0];
$startymdth = $startd.' '.$startm.' '.$starty;
$newdate=date("Y/m/d");

$cntemps1 = "";
$cntemps2 = '';

if($_GET["area"]=="AMT"){
    $AREA="AMT";
}
if($_GET["area"]=="GW"){
    $AREA="GW";
}


?>
<style>
    .border, tr, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>
<html>
    <head>
        <link rel="shortcut icon" href="https://img2.pic.in.th/pic/car_repair.png" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <div id="datadef">
                <table class="border" border="1"  border="1">
                    <thead>
                        <tr>
                            <th class="border" border="1" width="100px" colspan="3" style="text-align: center;">&nbsp;</th>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px">READY</font></th>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px">LEAVE</font></th>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px">TRAINING</font></th>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px">LICENSE</font></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php                                                                                    
                            $sql_rpms1 = "SELECT DISTINCT 
                            (SELECT SUM(ISNULL(CAST(CM_TOTAL AS DECIMAL(6,0)),0)) FROM CHECKMAN WHERE CONVERT ( VARCHAR ( 10 ), CM_DATE, 20 ) = '$newdate' AND CM_GROUP IN(3,5) AND CM_AREA = '$AREA' AND CM_TOTAL >= '0') AS s1_total,
                            (SELECT SUM(ISNULL(CAST(CM_READY AS DECIMAL(6,0)),0)) FROM CHECKMAN WHERE CONVERT ( VARCHAR ( 10 ), CM_DATE, 20 ) = '$newdate' AND CM_GROUP IN(3,5) AND CM_AREA = '$AREA' AND CM_READY >= '0') AS s1_ready,
                            (SELECT SUM(ISNULL(CAST(CM_LEAVE AS DECIMAL(6,0)),0)) FROM CHECKMAN WHERE CONVERT ( VARCHAR ( 10 ), CM_DATE, 20 ) = '$newdate' AND CM_GROUP IN(3,5) AND CM_AREA = '$AREA' AND CM_LEAVE >= '0') AS s1_leave,
                            (SELECT SUM(ISNULL(CAST(CM_TRAINNING AS DECIMAL(6,0)),0)) FROM CHECKMAN WHERE CONVERT ( VARCHAR ( 10 ), CM_DATE, 20 ) = '$newdate' AND CM_GROUP IN(3,5) AND CM_AREA = '$AREA' AND CM_TRAINNING >= '0') AS s1_training,
                            (SELECT SUM(ISNULL(CAST(CM_LICENSE AS DECIMAL(6,0)),0)) FROM CHECKMAN WHERE CONVERT ( VARCHAR ( 10 ), CM_DATE, 20 ) = '$newdate' AND CM_GROUP IN(3,5) AND CM_AREA = '$AREA' AND CM_LICENSE >= '0') AS s1_license";
                            $query_rpms1 = sqlsrv_query($conn, $sql_rpms1);
                            $result_rpms1 = sqlsrv_fetch_array($query_rpms1, SQLSRV_FETCH_ASSOC);
                        ?>
                        <tr>
                            <?php if($AREA=='AMT'){ ?>
                                <th class="border" border="1" width="30px" style="text-align: center;"><font size="1px">S1</font></th>
                            <?php }else if($AREA=='GW'){ ?>
                                <th class="border" border="1" width="30px" style="text-align: center;"><font size="1px">G1</font></th>
                            <?php } ?>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px">REPAIRMAN</font></th>
                            <th class="border" border="1" width="30px" style="text-align: center;"><font size="1px"><?=$result_rpms1["s1_total"];?></font></th>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px"><?=$result_rpms1["s1_ready"];?></font></th>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px"><?=$result_rpms1["s1_leave"];?></font></th>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px"><?=$result_rpms1["s1_training"];?></font></th>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px"><?=$result_rpms1["s1_license"];?></font></th>
                        </tr>
                        <?php                                                                                    
                            $sql_rpms1 = "SELECT DISTINCT 
                            (SELECT SUM(ISNULL(CAST(CM_TOTAL AS DECIMAL(6,0)),0)) FROM CHECKMAN WHERE CONVERT ( VARCHAR ( 10 ), CM_DATE, 20 ) = '$newdate' AND CM_GROUP IN(1,2,4,6) AND CM_AREA = '$AREA' AND CM_TOTAL >= '0') AS s2_total,
                            (SELECT SUM(ISNULL(CAST(CM_READY AS DECIMAL(6,0)),0)) FROM CHECKMAN WHERE CONVERT ( VARCHAR ( 10 ), CM_DATE, 20 ) = '$newdate' AND CM_GROUP IN(1,2,4,6) AND CM_AREA = '$AREA' AND CM_READY >= '0') AS s2_ready,
                            (SELECT SUM(ISNULL(CAST(CM_LEAVE AS DECIMAL(6,0)),0)) FROM CHECKMAN WHERE CONVERT ( VARCHAR ( 10 ), CM_DATE, 20 ) = '$newdate' AND CM_GROUP IN(1,2,4,6) AND CM_AREA = '$AREA' AND CM_LEAVE >= '0') AS s2_leave,
                            (SELECT SUM(ISNULL(CAST(CM_TRAINNING AS DECIMAL(6,0)),0)) FROM CHECKMAN WHERE CONVERT ( VARCHAR ( 10 ), CM_DATE, 20 ) = '$newdate' AND CM_GROUP IN(1,2,4,6) AND CM_AREA = '$AREA' AND CM_TRAINNING >= '0') AS s2_training,
                            (SELECT SUM(ISNULL(CAST(CM_LICENSE AS DECIMAL(6,0)),0)) FROM CHECKMAN WHERE CONVERT ( VARCHAR ( 10 ), CM_DATE, 20 ) = '$newdate' AND CM_GROUP IN(1,2,4,6) AND CM_AREA = '$AREA' AND CM_LICENSE >= '0') AS s2_license";
                            $query_rpms1 = sqlsrv_query($conn, $sql_rpms1);
                            $result_rpms1 = sqlsrv_fetch_array($query_rpms1, SQLSRV_FETCH_ASSOC);
                        ?>
                        <tr>
                            <?php if($AREA=='AMT'){ ?>
                                <th class="border" border="1" width="30px" style="text-align: center;"><font size="1px">S2</font></th>
                            <?php }else if($AREA=='GW'){ ?>
                                <th class="border" border="1" width="30px" style="text-align: center;"><font size="1px">G2</font></th>
                            <?php } ?>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px">REPAIRMAN</font></th>
                            <th class="border" border="1" width="30px" style="text-align: center;"><font size="1px"><?=$result_rpms1["s2_total"];?></font></th>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px"><?=$result_rpms1["s2_ready"];?></font></th>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px"><?=$result_rpms1["s2_leave"];?></font></th>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px"><?=$result_rpms1["s2_training"];?></font></th>
                            <th class="border" border="1" width="70px" style="text-align: center;"><font size="1px"><?=$result_rpms1["s2_license"];?></font></th>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <?php if($AREA=='AMT'){ ?>
                                <th style="text-align: left;"><b>แผนการทำงาน บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด พื้นที่อมตะ S1,S2</b></th>
                            <?php }else if($AREA=='GW'){ ?>
                                <th style="text-align: left;"><b>แผนการทำงาน บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด พื้นที่เกตเวย์ G1,G2</b></th>
                            <?php } ?>
                        </tr>
                        <tr>
                            <th style="text-align: left;">
                                <b>ประจำวันที่ <?=$startymdth;?></b>
                            </th>
                        </tr>
                    </thead>
                </table>
                <!-- <br> -->
            <!-- S1 ############################################################################################################################## -->
                <?php
                    $sql_carcount1 = "SELECT COUNT(DISTINCT	a.RPRQ_REGISHEAD) CARCOUNT
                    FROM [dbo].[REPAIRREQUEST] a
                    INNER JOIN [dbo].[REPAIRCAUSE] b ON a.RPRQ_CODE=b.RPRQ_CODE
                    WHERE b.RPC_INCARDATE = '$txt_daterepair' AND a.RPRQ_STATUS = 'Y'
                    AND SUBSTRING(b.RPC_AREA,1,2)='S1' AND (a.RPRQ_REGISHEAD != '' OR a.RPRQ_REGISTAIL != '')  AND a.RPRQ_AREA='$AREA'";
                    $params_carcount1 = array();
                    $query_carcount1 = sqlsrv_query($conn, $sql_carcount1, $params_carcount1);
                    $result_carcount1 = sqlsrv_fetch_array($query_carcount1, SQLSRV_FETCH_ASSOC);
                    $CARCOUNT1=$result_carcount1["CARCOUNT"];
                    
                    $sql_rpmcount1 = "SELECT COUNT(DISTINCT C.RPME_NAME) REPAIRMAN
                    FROM [dbo].[REPAIRREQUEST] A
                    INNER JOIN [dbo].[REPAIRCAUSE] B ON A.RPRQ_CODE=B.RPRQ_CODE
                    LEFT JOIN REPAIRMANEMP C ON A.RPRQ_CODE = C.RPRQ_CODE
                    WHERE b.RPC_INCARDATE = '$txt_daterepair' AND a.RPRQ_STATUS = 'Y'
                    AND SUBSTRING(B.RPC_AREA,1,2)='S1' AND (A.RPRQ_REGISHEAD != '' OR A.RPRQ_REGISTAIL != '')  AND A.RPRQ_AREA='$AREA'
                    AND C.RPC_SUBJECT IN('EG','BD')";
                    $params_rpmcount1 = array();
                    $options_rpmcount1 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                    $query_rpmcount1 = sqlsrv_query($conn, $sql_rpmcount1, $params_rpmcount1, $options_rpmcount1);
                    $result_rpmcount1 = sqlsrv_fetch_array($query_rpmcount1, SQLSRV_FETCH_ASSOC);
                    $REPAIRMAN1=$result_rpmcount1["REPAIRMAN"];                                        
                ?>
                <?php if($AREA=='AMT'){ ?>
                    <h3><b>S1</b>&nbsp;&nbsp;<small>รถเข้าซ่อมจำนวน <?=$CARCOUNT1?> คัน และช่างซ่อมจำนวน <?=$REPAIRMAN1?> คน</small></h3>
                <?php }else if($AREA=='GW'){ ?>
                    <h3><b>G1</b>&nbsp;&nbsp;<small>รถเข้าซ่อมจำนวน <?=$CARCOUNT1?> คัน และช่างซ่อมจำนวน <?=$REPAIRMAN1?> คน</small></h3>
                <?php } ?>
                <table class="border" border="1" style="width: 100%;">
                    <thead>
                        <!-- <tr>
                            <th class="border" border="1" colspan="109" style="text-align: center;">S1</th>
                        </tr> -->
                        <tr>
                            <th class="border" border="1" width="2%" rowspan="2" style="text-align: center;">ที่</th>
                            <th class="border" border="1" width="5%" rowspan="2" style="text-align: center;">ทะเบียน(หัว)/ชื่อรถ</th>
                            <th class="border" border="1" width="5%" rowspan="2" style="text-align: center;">ทะเบียน(หาง)</th>
                            <th class="border" border="1" width="5%" rowspan="2" style="text-align: center;">สายงาน</th>
                            <th class="border" border="1" width="6%" rowspan="2" style="text-align: center;">ลักษณะงานซ่อม</th>
                            <th class="border" border="1" width="7%" rowspan="2" style="text-align: center;">รายละเอียด</th>
                            <th class="border" border="1" width="6%" rowspan="2" style="text-align: center;">ช่างผู้ขับรถเข้าซ่อม/เลขใบขับขี่</th>
                            <th class="border" border="1" width="3%" rowspan="2" style="text-align: center;">ช่องซ่อม</th>
                            <th class="border" border="1" width="3%" rowspan="2" style="text-align: center;">จำนวนช่าง</th>
                            <th class="border" border="1" width="7%" rowspan="2" style="text-align: center;">ผู้รับผิดชอบ</th>
                            <th class="border" border="1" width="7%" rowspan="2" style="text-align: center;">ผู้ตรวจสอบ/SA</th>
                            <th class="border" border="1" width="3%" rowspan="2" style="text-align: center;">ชั่วโมงทำงาน</th>
                            <th class="border" border="1" style="text-align: center;width: 25%" colspan="96">เวลาในการปฎิบัติงาน</th>
                            <th class="border" border="1" width="3%" rowspan="2" style="text-align: center;">รวมชั่วโมง</th>
                        </tr>
                        <tr>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">0</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">1</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">2</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">3</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">4</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">5</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">6</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">7</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">8</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">9</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">10</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">11</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">12</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">13</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">14</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">15</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">16</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">17</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">18</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">19</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">20</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">21</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">22</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">23</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // #################################################### HEADS1 ####################################################

                            $iS1 = 1;
                            $sql_seCntjob = "SELECT COUNT(*) AS 'CNT' FROM 
                                (SELECT DISTINCT a.RPRQ_ID FROM [dbo].[REPAIRREQUEST] a
                                    INNER JOIN [dbo].[REPAIRCAUSE] b ON a.RPRQ_CODE=b.RPRQ_CODE
                                    WHERE b.RPC_INCARDATE = '$txt_daterepair' AND (a.RPRQ_REGISHEAD != '' OR a.RPRQ_REGISTAIL != '')  AND a.RPRQ_AREA='$AREA') AS A";
                            $params_seCntjob = array();
                            $query_seCntjob = sqlsrv_query($conn, $sql_seCntjob, $params_seCntjob);
                            $result_seCntjob = sqlsrv_fetch_array($query_seCntjob, SQLSRV_FETCH_ASSOC);

                            $sql_seCnthour = "SELECT SUM(A.CNT) AS 'CNT' FROM 
                                (SELECT DATEDIFF(HOUR,CONVERT(CHAR(19), CONVERT(DATETIME, b.RPC_INCARDATE+' '+b.RPC_INCARTIME, 103), 120),CONVERT(CHAR(19), CONVERT(DATETIME, b.RPC_OUTCARDATE+' '+b.RPC_OUTCARTIME, 103), 120)) AS 'CNT' 
                                FROM [dbo].[REPAIRREQUEST] a
                                    INNER JOIN [dbo].[REPAIRCAUSE] b ON a.RPRQ_CODE=b.RPRQ_CODE
                                    WHERE b.RPC_INCARDATE = '$txt_daterepair'
                                    AND (a.RPRQ_REGISHEAD != '' OR a.RPRQ_REGISTAIL != '')  AND a.RPRQ_AREA='$AREA') AS A";
                            $params_seCnthour = array();
                            $query_seCnthour = sqlsrv_query($conn, $sql_seCnthour, $params_seCnthour);
                            $result_seCnthour = sqlsrv_fetch_array($query_seCnthour, SQLSRV_FETCH_ASSOC);

                            $sql_seS1 = "SELECT 
                                a.RPRQ_ASSIGN_BY,
                                c.nameT NAMEASSIGN,
                                a.RPRQ_REQUESTBY_SQ,
                                a.RPRQ_ID REPAIRPLANID,
                                a.RPRQ_CODE,
                                a.RPRQ_REGISHEAD VEHICLEREGISNUMBER1,
                                a.RPRQ_CARNAMEHEAD THAINAME1,
                                a.RPRQ_REGISTAIL VEHICLEREGISNUMBER2,
                                a.RPRQ_CARNAMETAIL THAINAME2,
                                a.RPRQ_LINEOFWORK CUSTOMER,
                                a.RPRQ_COMPANYCASH COMPANYPAYMENT,
                                'S1/'+SUBSTRING(b.RPC_AREA,4,1) AS CH,
                                CASE
                                    WHEN b.RPC_SUBJECT = 'EL' THEN 'ระบบไฟ'
                                    WHEN b.RPC_SUBJECT = 'TU' THEN 'ยาง ช่วงล่าง'
                                    WHEN b.RPC_SUBJECT = 'BD' THEN 'โครงสร้าง'
                                    WHEN b.RPC_SUBJECT = 'EG' THEN 'เครื่องยนต์'
                                    WHEN b.RPC_SUBJECT = 'AC' THEN 'อุปกรณ์ประจำรถ'
                                    ELSE ''
                                END SUBJECT,
                                b.RPC_SUBJECT,
                                a.RPRQ_STATUSREQUEST REPAIRSTATUS,
                                STUFF((SELECT DISTINCT ','+ CAST(RPME_NAME AS VARCHAR) FROM REPAIRMANEMP WHERE RPRQ_CODE = a.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT FOR XML PATH(''), TYPE).value('.','VARCHAR(max)'), 1, 1, '') AS REPAIRMAN
                                FROM [dbo].[REPAIRREQUEST] a
                                INNER JOIN [dbo].[REPAIRCAUSE] b ON a.RPRQ_CODE=b.RPRQ_CODE
                                LEFT JOIN vwEMPLOYEE c ON c.PersonCode = a.RPRQ_ASSIGN_BY
                                WHERE b.RPC_INCARDATE = '$txt_daterepair' AND a.RPRQ_STATUS = 'Y'
                                AND SUBSTRING(b.RPC_AREA,1,2)='S1' AND (a.RPRQ_REGISHEAD != '' OR a.RPRQ_REGISTAIL != '')  AND a.RPRQ_AREA='$AREA'
                                GROUP BY a.RPRQ_ASSIGN_BY,c.nameT,a.RPRQ_REQUESTBY_SQ,a.RPRQ_ID,a.RPRQ_CODE,a.RPRQ_REGISHEAD,a.RPRQ_CARNAMEHEAD,a.RPRQ_REGISTAIL,a.RPRQ_CARNAMETAIL,a.RPRQ_LINEOFWORK,a.RPRQ_COMPANYCASH,b.RPC_AREA,b.RPC_SUBJECT,a.RPRQ_STATUSREQUEST";
                            $params_seS1 = array();
                            $query_seS1 = sqlsrv_query($conn, $sql_seS1, $params_seS1);
                            while ($result_seS1 = sqlsrv_fetch_array($query_seS1, SQLSRV_FETCH_ASSOC)) {
                                if ($result_seS1['CUSTOMER'] == '' ) {
                                    $customercode1 = $result_seS1['COMPANYPAYMENT'];
                                }else {
                                    $customercode1 = $result_seS1['CUSTOMER'];
                                }

                                $sql_seEmpS1 = "SELECT COUNT(*) AS CNT FROM [dbo].[REPAIRMANEMP] WHERE RPRQ_CODE='".$result_seS1['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seEmpS1 = array();
                                $query_seEmpS1 = sqlsrv_query($conn, $sql_seEmpS1, $params_seEmpS1);
                                $result_seEmpS1 = sqlsrv_fetch_array($query_seEmpS1, SQLSRV_FETCH_ASSOC);

                                $sql_seCauS1 = "SELECT RPC_DETAIL DETAIL FROM [dbo].[REPAIRCAUSE] WHERE RPRQ_CODE='".$result_seS1['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seCauS1 = array();
                                $query_seCauS1 = sqlsrv_query($conn, $sql_seCauS1, $params_seCauS1);
                                $result_seCauS1 = sqlsrv_fetch_array($query_seCauS1, SQLSRV_FETCH_ASSOC);
                                
                                $sql_seActS1 = "SELECT RPATTM_GROUP FROM [dbo].[REPAIRACTUAL_TIME] WHERE RPRQ_CODE='".$result_seS1['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS1['RPC_SUBJECT']."' ORDER BY RPATTM_PROCESS DESC";
                                $params_seActS1 = array();
                                $query_seActS1 = sqlsrv_query($conn, $sql_seActS1, $params_seActS1);
                                $result_seActS1 = sqlsrv_fetch_array($query_seActS1, SQLSRV_FETCH_ASSOC);
                                
                                $sql_seHourS1 = "SELECT 
                                    CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AS 'CARINPUTDATE',
                                    CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120) AS 'COMPLETEDDATE'
                                    FROM [dbo].[REPAIRCAUSE] WHERE RPRQ_CODE='".$result_seS1['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS1['RPC_SUBJECT']."' AND (RPC_REMARK = '' OR RPC_REMARK IS NULL)";
                                $params_seHourS1 = array();
                                $query_seHourS1 = sqlsrv_query($conn, $sql_seHourS1, $params_seHourS1);
                                $result_seHourS1 = sqlsrv_fetch_array($query_seHourS1, SQLSRV_FETCH_ASSOC);

                                $remains1 = intval(strtotime($result_seHourS1['COMPLETEDDATE']) - strtotime($result_seHourS1['CARINPUTDATE']));
                                $wans1 = floor($remains1 / 86400);
                                $l_wans1 = $remains1 % 86400;
                                $hours1 = floor($l_wans1 / 3600);
                                $l_hours1 = $l_wans1 % 3600;
                                $minutes1 = floor($l_hours1 / 60);
                                $seconds1 = $l_hours1 % 60;
                                // echo $result_seS1['RPRQ_CODE']."<br>";
                                // echo $result_seS1['RPC_SUBJECT']."<br>";
                                // echo $result_seHourS1['CARINPUTDATE']."<br>";
                                // echo $result_seHourS1['COMPLETEDDATE']."<br>";
                                // echo "ผ่านมาแล้ว ".$wans1." วัน ".$hours1." ชั่วโมง ".$minutes1." นาที ".$seconds1." วินาที"."<br>";
                                // echo $result_seActS1['RPATTM_GROUP']."<br>";
                                
                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {                                    
                                    $sql_seHouracS1 = "SELECT DISTINCT A.RPRQ_CODE,A.RPC_SUBJECT,
                                        (SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME	WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'START') AS 'OPENDATE',
                                        (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') AS 'CLOSEDATE'
                                        FROM REPAIRACTUAL_TIME A WHERE RPRQ_CODE='".$result_seS1['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS1['RPC_SUBJECT']."'";
                                    $params_seHouracS1 = array();
                                    $query_seHouracS1 = sqlsrv_query($conn, $sql_seHouracS1, $params_seHouracS1);
                                    $result_seHouracS1 = sqlsrv_fetch_array($query_seHouracS1, SQLSRV_FETCH_ASSOC);
                                    
                                    $remainacs1 = intval(strtotime($result_seHouracS1['CLOSEDATE']) - strtotime($result_seHouracS1['OPENDATE']));
                                    $wanacs1 = floor($remainacs1 / 86400);
                                    $l_wanacs1 = $remainacs1 % 86400;
                                    $houracs1 = floor($l_wanacs1 / 3600);
                                    $l_houracs1 = $l_wanacs1 % 3600;
                                    $minuteacs1 = floor($l_houracs1 / 60);
                                    $secondacs1 = $l_houracs1 % 60;
                                    // echo "ผ่านมาแล้ว ".$wanacs1." วัน ".$houracs1." ชั่วโมง ".$minuteacs1." นาที ".$secondacs1." วินาที"."<br>";
                                } else {
                                    $sql_seHouracS1 = "SELECT A.RPRQ_CODE,A.RPC_SUBJECT,
                                        (SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME	WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'START') AS 'OPENDATE',
                                        CONVERT(VARCHAR(10), GETDATE(), 121) + ' '  + CONVERT(VARCHAR(5), GETDATE(), 14) AS 'CLOSEDATE' 
                                        FROM REPAIRACTUAL_TIME A WHERE RPRQ_CODE='".$result_seS1['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS1['RPC_SUBJECT']."'";
                                    $params_seHouracS1 = array();
                                    $query_seHouracS1 = sqlsrv_query($conn, $sql_seHouracS1, $params_seHouracS1);
                                    $result_seHouracS1 = sqlsrv_fetch_array($query_seHouracS1, SQLSRV_FETCH_ASSOC);
                                    
                                    $remainacs1 = intval(strtotime($result_seHouracS1['CLOSEDATE']) - strtotime($result_seHouracS1['OPENDATE']));
                                    $wanacs1 = floor($remainacs1 / 86400);
                                    $l_wanacs1 = $remainacs1 % 86400;
                                    $houracs1 = floor($l_wanacs1 / 3600);
                                    $l_houracs1 = $l_wanacs1 % 3600;
                                    $minuteacs1 = floor($l_houracs1 / 60);
                                    $secondacs1 = $l_houracs1 % 60;
                                }

                                $cntemps1 += $result_seEmpS1['CNT'];

                                $dateinput = $txt_daterepair;

                                $DETAIL1=$result_seCauS1['DETAIL'];
                                $DETAILCUT1 = explode("-", $DETAIL1);
                                $DETAILX10 = $DETAILCUT1[0];
                                $DETAILX11 = $DETAILCUT1[1];
                                $DETAILX12 = $DETAILCUT1[2];
                                
                                if($DETAILX10=="PM"){
                                    $sql_chketmrpmdrive1 = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE='".$result_seS1['RPRQ_CODE']."' AND RPMD_ZONE = 'S1'";
                                }else{
                                    $sql_chketmrpmdrive1 = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE='".$result_seS1['RPRQ_CODE']."'";
                                }
                                $query_chketmrpmdrive1 = sqlsrv_query($conn, $sql_chketmrpmdrive1);
                                $result_chketmrpmdrive1 = sqlsrv_fetch_array($query_chketmrpmdrive1, SQLSRV_FETCH_ASSOC);
                                $rsdrivename1 = $result_chketmrpmdrive1["RPMD_NAME"];
                                $rsdrivecard1 = $result_chketmrpmdrive1["RPMD_CARLICENCE"];

                                if($DETAILX10=="PM"){
                                    $TECHNICIANEMPLOYEES1 = $result_seS1['NAMEASSIGN'];
                                }else {
                                    $TECHNICIANEMPLOYEES1 = $result_seS1['RPRQ_REQUESTBY_SQ'];
                                }
                        // #################################################### PLANS1 ####################################################

                                $sql_seFP0000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0000 = array();
                                $query_seFP0000 = sqlsrv_query($conn, $sql_seFP0000, $params_seFP0000);
                                $result_seFP0000 = sqlsrv_fetch_array($query_seFP0000, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0015 = array();
                                $query_seFP0015 = sqlsrv_query($conn, $sql_seFP0015, $params_seFP0015);
                                $result_seFP0015 = sqlsrv_fetch_array($query_seFP0015, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0030 = array();
                                $query_seFP0030 = sqlsrv_query($conn, $sql_seFP0030, $params_seFP0030);
                                $result_seFP0030 = sqlsrv_fetch_array($query_seFP0030, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0045 = array();
                                $query_seFP0045 = sqlsrv_query($conn, $sql_seFP0045, $params_seFP0045);
                                $result_seFP0045 = sqlsrv_fetch_array($query_seFP0045, SQLSRV_FETCH_ASSOC);

                                $rsFP0000 = '';
                                $rsFP0015 = '';
                                $rsFP0030 = '';
                                $rsFP0045 = '';

                                if ($result_seFP0000['CNT'] != '0') {
                                    $rsFP0000 = "background-color: yellow";
                                }
                                if ($result_seFP0015['CNT'] != '0') {
                                    $rsFP0015 = "background-color: yellow";
                                }
                                if ($result_seFP0030['CNT'] != '0') {
                                    $rsFP0030 = "background-color: yellow";
                                }
                                if ($result_seFP0045['CNT'] != '0') {
                                    $rsFP0045 = "background-color: yellow";
                                }

                                $sql_seFP0100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0100 = array();
                                $query_seFP0100 = sqlsrv_query($conn, $sql_seFP0100, $params_seFP0100);
                                $result_seFP0100 = sqlsrv_fetch_array($query_seFP0100, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0115 = array();
                                $query_seFP0115 = sqlsrv_query($conn, $sql_seFP0115, $params_seFP0115);
                                $result_seFP0115 = sqlsrv_fetch_array($query_seFP0115, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0130 = array();
                                $query_seFP0130 = sqlsrv_query($conn, $sql_seFP0130, $params_seFP0130);
                                $result_seFP0130 = sqlsrv_fetch_array($query_seFP0130, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0145 = array();
                                $query_seFP0145 = sqlsrv_query($conn, $sql_seFP0145, $params_seFP0145);
                                $result_seFP0145 = sqlsrv_fetch_array($query_seFP0145, SQLSRV_FETCH_ASSOC);

                                $rsFP0100 = '';
                                $rsFP0115 = '';
                                $rsFP0130 = '';
                                $rsFP0145 = '';

                                if ($result_seFP0100['CNT'] != '0') {
                                    $rsFP0100 = "background-color: yellow";
                                }
                                if ($result_seFP0115['CNT'] != '0') {
                                    $rsFP0115 = "background-color: yellow";
                                }
                                if ($result_seFP0130['CNT'] != '0') {
                                    $rsFP0130 = "background-color: yellow";
                                }
                                if ($result_seFP0145['CNT'] != '0') {
                                    $rsFP0145 = "background-color: yellow";
                                }

                                $sql_seFP0200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0200 = array();
                                $query_seFP0200 = sqlsrv_query($conn, $sql_seFP0200, $params_seFP0200);
                                $result_seFP0200 = sqlsrv_fetch_array($query_seFP0200, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0215 = array();
                                $query_seFP0215 = sqlsrv_query($conn, $sql_seFP0215, $params_seFP0215);
                                $result_seFP0215 = sqlsrv_fetch_array($query_seFP0215, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0230 = array();
                                $query_seFP0230 = sqlsrv_query($conn, $sql_seFP0230, $params_seFP0230);
                                $result_seFP0230 = sqlsrv_fetch_array($query_seFP0230, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0245 = array();
                                $query_seFP0245 = sqlsrv_query($conn, $sql_seFP0245, $params_seFP0245);
                                $result_seFP0245 = sqlsrv_fetch_array($query_seFP0245, SQLSRV_FETCH_ASSOC);

                                $rsFP0200 = '';
                                $rsFP0215 = '';
                                $rsFP0230 = '';
                                $rsFP0245 = '';

                                if ($result_seFP0200['CNT'] != '0') {
                                    $rsFP0200 = "background-color: yellow";
                                }
                                if ($result_seFP0215['CNT'] != '0') {
                                    $rsFP0215 = "background-color: yellow";
                                }
                                if ($result_seFP0230['CNT'] != '0') {
                                    $rsFP0230 = "background-color: yellow";
                                }
                                if ($result_seFP0245['CNT'] != '0') {
                                    $rsFP0245 = "background-color: yellow";
                                }

                                $sql_seFP0300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0300 = array();
                                $query_seFP0300 = sqlsrv_query($conn, $sql_seFP0300, $params_seFP0300);
                                $result_seFP0300 = sqlsrv_fetch_array($query_seFP0300, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0315 = array();
                                $query_seFP0315 = sqlsrv_query($conn, $sql_seFP0315, $params_seFP0315);
                                $result_seFP0315 = sqlsrv_fetch_array($query_seFP0315, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0330 = array();
                                $query_seFP0330 = sqlsrv_query($conn, $sql_seFP0330, $params_seFP0330);
                                $result_seFP0330 = sqlsrv_fetch_array($query_seFP0330, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0345 = array();
                                $query_seFP0345 = sqlsrv_query($conn, $sql_seFP0345, $params_seFP0345);
                                $result_seFP0345 = sqlsrv_fetch_array($query_seFP0345, SQLSRV_FETCH_ASSOC);

                                $rsFP0300 = '';
                                $rsFP0315 = '';
                                $rsFP0330 = '';
                                $rsFP0345 = '';

                                if ($result_seFP0300['CNT'] != '0') {
                                    $rsFP0300 = "background-color: yellow";
                                }
                                if ($result_seFP0315['CNT'] != '0') {
                                    $rsFP0315 = "background-color: yellow";
                                }
                                if ($result_seFP0330['CNT'] != '0') {
                                    $rsFP0330 = "background-color: yellow";
                                }
                                if ($result_seFP0345['CNT'] != '0') {
                                    $rsFP0345 = "background-color: yellow";
                                }

                                $sql_seFP0400 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0400 = array();
                                $query_seFP0400 = sqlsrv_query($conn, $sql_seFP0400, $params_seFP0400);
                                $result_seFP0400 = sqlsrv_fetch_array($query_seFP0400, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0415 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0415 = array();
                                $query_seFP0415 = sqlsrv_query($conn, $sql_seFP0415, $params_seFP0415);
                                $result_seFP0415 = sqlsrv_fetch_array($query_seFP0415, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0430 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0430 = array();
                                $query_seFP0430 = sqlsrv_query($conn, $sql_seFP0430, $params_seFP0430);
                                $result_seFP0430 = sqlsrv_fetch_array($query_seFP0430, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0445 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0445 = array();
                                $query_seFP0445 = sqlsrv_query($conn, $sql_seFP0445, $params_seFP0445);
                                $result_seFP0445 = sqlsrv_fetch_array($query_seFP0445, SQLSRV_FETCH_ASSOC);

                                $rsFP0400 = '';
                                $rsFP0415 = '';
                                $rsFP0430 = '';
                                $rsFP0445 = '';

                                if ($result_seFP0400['CNT'] != '0') {
                                    $rsFP0400 = "background-color: yellow";
                                }
                                if ($result_seFP0415['CNT'] != '0') {
                                    $rsFP0415 = "background-color: yellow";
                                }
                                if ($result_seFP0430['CNT'] != '0') {
                                    $rsFP0430 = "background-color: yellow";
                                }
                                if ($result_seFP0445['CNT'] != '0') {
                                    $rsFP0445 = "background-color: yellow";
                                }

                                $sql_seFP0500 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0500 = array();
                                $query_seFP0500 = sqlsrv_query($conn, $sql_seFP0500, $params_seFP0500);
                                $result_seFP0500 = sqlsrv_fetch_array($query_seFP0500, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0515 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0515 = array();
                                $query_seFP0515 = sqlsrv_query($conn, $sql_seFP0515, $params_seFP0515);
                                $result_seFP0515 = sqlsrv_fetch_array($query_seFP0515, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0530 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0530 = array();
                                $query_seFP0530 = sqlsrv_query($conn, $sql_seFP0530, $params_seFP0530);
                                $result_seFP0530 = sqlsrv_fetch_array($query_seFP0530, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0545 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0545 = array();
                                $query_seFP0545 = sqlsrv_query($conn, $sql_seFP0545, $params_seFP0545);
                                $result_seFP0545 = sqlsrv_fetch_array($query_seFP0545, SQLSRV_FETCH_ASSOC);

                                $rsFP0500 = '';
                                $rsFP0515 = '';
                                $rsFP0530 = '';
                                $rsFP0545 = '';

                                if ($result_seFP0500['CNT'] != '0') {
                                    $rsFP0500 = "background-color: yellow";
                                }
                                if ($result_seFP0515['CNT'] != '0') {
                                    $rsFP0515 = "background-color: yellow";
                                }
                                if ($result_seFP0530['CNT'] != '0') {
                                    $rsFP0530 = "background-color: yellow";
                                }
                                if ($result_seFP0545['CNT'] != '0') {
                                    $rsFP0545 = "background-color: yellow";
                                }

                                $sql_seFP0600 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0600 = array();
                                $query_seFP0600 = sqlsrv_query($conn, $sql_seFP0600, $params_seFP0600);
                                $result_seFP0600 = sqlsrv_fetch_array($query_seFP0600, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0615 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0615 = array();
                                $query_seFP0615 = sqlsrv_query($conn, $sql_seFP0615, $params_seFP0615);
                                $result_seFP0615 = sqlsrv_fetch_array($query_seFP0615, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0630 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0630 = array();
                                $query_seFP0630 = sqlsrv_query($conn, $sql_seFP0630, $params_seFP0630);
                                $result_seFP0630 = sqlsrv_fetch_array($query_seFP0630, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0645 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0645 = array();
                                $query_seFP0645 = sqlsrv_query($conn, $sql_seFP0645, $params_seFP0645);
                                $result_seFP0645 = sqlsrv_fetch_array($query_seFP0645, SQLSRV_FETCH_ASSOC);

                                $rsFP0600 = '';
                                $rsFP0615 = '';
                                $rsFP0630 = '';
                                $rsFP0645 = '';

                                if ($result_seFP0600['CNT'] != '0') {
                                    $rsFP0600 = "background-color: yellow";
                                }
                                if ($result_seFP0615['CNT'] != '0') {
                                    $rsFP0615 = "background-color: yellow";
                                }
                                if ($result_seFP0630['CNT'] != '0') {
                                    $rsFP0630 = "background-color: yellow";
                                }
                                if ($result_seFP0645['CNT'] != '0') {
                                    $rsFP0645 = "background-color: yellow";
                                }

                                $sql_seFP0700 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0700 = array();
                                $query_seFP0700 = sqlsrv_query($conn, $sql_seFP0700, $params_seFP0700);
                                $result_seFP0700 = sqlsrv_fetch_array($query_seFP0700, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0715 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0715 = array();
                                $query_seFP0715 = sqlsrv_query($conn, $sql_seFP0715, $params_seFP0715);
                                $result_seFP0715 = sqlsrv_fetch_array($query_seFP0715, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0730 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0730 = array();
                                $query_seFP0730 = sqlsrv_query($conn, $sql_seFP0730, $params_seFP0730);
                                $result_seFP0730 = sqlsrv_fetch_array($query_seFP0730, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0745 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0745 = array();
                                $query_seFP0745 = sqlsrv_query($conn, $sql_seFP0745, $params_seFP0745);
                                $result_seFP0745 = sqlsrv_fetch_array($query_seFP0745, SQLSRV_FETCH_ASSOC);



                                $rsFP0700 = '';
                                $rsFP0715 = '';
                                $rsFP0730 = '';
                                $rsFP0745 = '';




                                if ($result_seFP0700['CNT'] != '0') {
                                    $rsFP0700 = "background-color: yellow";
                                }
                                if ($result_seFP0715['CNT'] != '0') {
                                    $rsFP0715 = "background-color: yellow";
                                }
                                if ($result_seFP0730['CNT'] != '0') {
                                    $rsFP0730 = "background-color: yellow";
                                }
                                if ($result_seFP0745['CNT'] != '0') {
                                    $rsFP0745 = "background-color: yellow";
                                }

                                $sql_seFP0800 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0800 = array();
                                $query_seFP0800 = sqlsrv_query($conn, $sql_seFP0800, $params_seFP0800);
                                $result_seFP0800 = sqlsrv_fetch_array($query_seFP0800, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0815 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0815 = array();
                                $query_seFP0815 = sqlsrv_query($conn, $sql_seFP0815, $params_seFP0815);
                                $result_seFP0815 = sqlsrv_fetch_array($query_seFP0815, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0830 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0830 = array();
                                $query_seFP0830 = sqlsrv_query($conn, $sql_seFP0830, $params_seFP0830);
                                $result_seFP0830 = sqlsrv_fetch_array($query_seFP0830, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0845 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0845 = array();
                                $query_seFP0845 = sqlsrv_query($conn, $sql_seFP0845, $params_seFP0845);
                                $result_seFP0845 = sqlsrv_fetch_array($query_seFP0845, SQLSRV_FETCH_ASSOC);

                                $rsFP0800 = '';
                                $rsFP0815 = '';
                                $rsFP0830 = '';
                                $rsFP0845 = '';

                                if ($result_seFP0800['CNT'] != '0') {
                                    $rsFP0800 = "background-color: yellow";
                                }
                                if ($result_seFP0815['CNT'] != '0') {
                                    $rsFP0815 = "background-color: yellow";
                                }
                                if ($result_seFP0830['CNT'] != '0') {
                                    $rsFP0830 = "background-color: yellow";
                                }
                                if ($result_seFP0845['CNT'] != '0') {
                                    $rsFP0845 = "background-color: yellow";
                                }

                                $sql_seFP0900 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0900 = array();
                                $query_seFP0900 = sqlsrv_query($conn, $sql_seFP0900, $params_seFP0900);
                                $result_seFP0900 = sqlsrv_fetch_array($query_seFP0900, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0915 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0915 = array();
                                $query_seFP0915 = sqlsrv_query($conn, $sql_seFP0915, $params_seFP0915);
                                $result_seFP0915 = sqlsrv_fetch_array($query_seFP0915, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0930 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0930 = array();
                                $query_seFP0930 = sqlsrv_query($conn, $sql_seFP0930, $params_seFP0930);
                                $result_seFP0930 = sqlsrv_fetch_array($query_seFP0930, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0945 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP0945 = array();
                                $query_seFP0945 = sqlsrv_query($conn, $sql_seFP0945, $params_seFP0945);
                                $result_seFP0945 = sqlsrv_fetch_array($query_seFP0945, SQLSRV_FETCH_ASSOC);

                                $rsFP0900 = '';
                                $rsFP0915 = '';
                                $rsFP0930 = '';
                                $rsFP0945 = '';

                                if ($result_seFP0900['CNT'] != '0') {
                                    $rsFP0900 = "background-color: yellow";
                                }
                                if ($result_seFP0915['CNT'] != '0') {
                                    $rsFP0915 = "background-color: yellow";
                                }
                                if ($result_seFP0930['CNT'] != '0') {
                                    $rsFP0930 = "background-color: yellow";
                                }
                                if ($result_seFP0945['CNT'] != '0') {
                                    $rsFP0945 = "background-color: yellow";
                                }

                                $sql_seFP1000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1000 = array();
                                $query_seFP1000 = sqlsrv_query($conn, $sql_seFP1000, $params_seFP1000);
                                $result_seFP1000 = sqlsrv_fetch_array($query_seFP1000, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1015 = array();
                                $query_seFP1015 = sqlsrv_query($conn, $sql_seFP1015, $params_seFP1015);
                                $result_seFP1015 = sqlsrv_fetch_array($query_seFP1015, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1030 = array();
                                $query_seFP1030 = sqlsrv_query($conn, $sql_seFP1030, $params_seFP1030);
                                $result_seFP1030 = sqlsrv_fetch_array($query_seFP1030, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1045 = array();
                                $query_seFP1045 = sqlsrv_query($conn, $sql_seFP1045, $params_seFP1045);
                                $result_seFP1045 = sqlsrv_fetch_array($query_seFP1045, SQLSRV_FETCH_ASSOC);

                                $rsFP1000 = '';
                                $rsFP1015 = '';
                                $rsFP1030 = '';
                                $rsFP1045 = '';

                                if ($result_seFP1000['CNT'] != '0') {
                                    $rsFP1000 = "background-color: yellow";
                                }
                                if ($result_seFP1015['CNT'] != '0') {
                                    $rsFP1015 = "background-color: yellow";
                                }
                                if ($result_seFP1030['CNT'] != '0') {
                                    $rsFP1030 = "background-color: yellow";
                                }
                                if ($result_seFP1045['CNT'] != '0') {
                                    $rsFP1045 = "background-color: yellow";
                                }

                                $sql_seFP1100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1100 = array();
                                $query_seFP1100 = sqlsrv_query($conn, $sql_seFP1100, $params_seFP1100);
                                $result_seFP1100 = sqlsrv_fetch_array($query_seFP1100, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1115 = array();
                                $query_seFP1115 = sqlsrv_query($conn, $sql_seFP1115, $params_seFP1115);
                                $result_seFP1115 = sqlsrv_fetch_array($query_seFP1115, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1130 = array();
                                $query_seFP1130 = sqlsrv_query($conn, $sql_seFP1130, $params_seFP1130);
                                $result_seFP1130 = sqlsrv_fetch_array($query_seFP1130, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1145 = array();
                                $query_seFP1145 = sqlsrv_query($conn, $sql_seFP1145, $params_seFP1145);
                                $result_seFP1145 = sqlsrv_fetch_array($query_seFP1145, SQLSRV_FETCH_ASSOC);

                                $rsFP1100 = '';
                                $rsFP1115 = '';
                                $rsFP1130 = '';
                                $rsFP1145 = '';

                                if ($result_seFP1100['CNT'] != '0') {
                                    $rsFP1100 = "background-color: yellow";
                                }
                                if ($result_seFP1115['CNT'] != '0') {
                                    $rsFP1115 = "background-color: yellow";
                                }
                                if ($result_seFP1130['CNT'] != '0') {
                                    $rsFP1130 = "background-color: yellow";
                                }
                                if ($result_seFP1145['CNT'] != '0') {
                                    $rsFP1145 = "background-color: yellow";
                                }

                                $sql_seFP1200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1200 = array();
                                $query_seFP1200 = sqlsrv_query($conn, $sql_seFP1200, $params_seFP1200);
                                $result_seFP1200 = sqlsrv_fetch_array($query_seFP1200, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1215 = array();
                                $query_seFP1215 = sqlsrv_query($conn, $sql_seFP1215, $params_seFP1215);
                                $result_seFP1215 = sqlsrv_fetch_array($query_seFP1215, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1230 = array();
                                $query_seFP1230 = sqlsrv_query($conn, $sql_seFP1230, $params_seFP1230);
                                $result_seFP1230 = sqlsrv_fetch_array($query_seFP1230, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1245 = array();
                                $query_seFP1245 = sqlsrv_query($conn, $sql_seFP1245, $params_seFP1245);
                                $result_seFP1245 = sqlsrv_fetch_array($query_seFP1245, SQLSRV_FETCH_ASSOC);

                                $rsFP1200 = '';
                                $rsFP1215 = '';
                                $rsFP1230 = '';
                                $rsFP1245 = '';

                                if ($result_seFP1200['CNT'] != '0') {
                                    $rsFP1200 = "background-color: yellow";
                                }
                                if ($result_seFP1215['CNT'] != '0') {
                                    $rsFP1215 = "background-color: yellow";
                                }
                                if ($result_seFP1230['CNT'] != '0') {
                                    $rsFP1230 = "background-color: yellow";
                                }
                                if ($result_seFP1245['CNT'] != '0') {
                                    $rsFP1245 = "background-color: yellow";
                                }

                                $sql_seFP1300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1300 = array();
                                $query_seFP1300 = sqlsrv_query($conn, $sql_seFP1300, $params_seFP1300);
                                $result_seFP1300 = sqlsrv_fetch_array($query_seFP1300, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1315 = array();
                                $query_seFP1315 = sqlsrv_query($conn, $sql_seFP1315, $params_seFP1315);
                                $result_seFP1315 = sqlsrv_fetch_array($query_seFP1315, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1330 = array();
                                $query_seFP1330 = sqlsrv_query($conn, $sql_seFP1330, $params_seFP1330);
                                $result_seFP1330 = sqlsrv_fetch_array($query_seFP1330, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1345 = array();
                                $query_seFP1345 = sqlsrv_query($conn, $sql_seFP1345, $params_seFP1345);
                                $result_seFP1345 = sqlsrv_fetch_array($query_seFP1345, SQLSRV_FETCH_ASSOC);

                                $rsFP1300 = '';
                                $rsFP1315 = '';
                                $rsFP1330 = '';
                                $rsFP1345 = '';

                                if ($result_seFP1300['CNT'] != '0') {
                                    $rsFP1300 = "background-color: yellow";
                                }
                                if ($result_seFP1315['CNT'] != '0') {
                                    $rsFP1315 = "background-color: yellow";
                                }
                                if ($result_seFP1330['CNT'] != '0') {
                                    $rsFP1330 = "background-color: yellow";
                                }
                                if ($result_seFP1345['CNT'] != '0') {
                                    $rsFP1345 = "background-color: yellow";
                                }

                                $sql_seFP1400 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1400 = array();
                                $query_seFP1400 = sqlsrv_query($conn, $sql_seFP1400, $params_seFP1400);
                                $result_seFP1400 = sqlsrv_fetch_array($query_seFP1400, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1415 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1415 = array();
                                $query_seFP1415 = sqlsrv_query($conn, $sql_seFP1415, $params_seFP1415);
                                $result_seFP1415 = sqlsrv_fetch_array($query_seFP1415, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1430 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1430 = array();
                                $query_seFP1430 = sqlsrv_query($conn, $sql_seFP1430, $params_seFP1430);
                                $result_seFP1430 = sqlsrv_fetch_array($query_seFP1430, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1445 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1445 = array();
                                $query_seFP1445 = sqlsrv_query($conn, $sql_seFP1445, $params_seFP1445);
                                $result_seFP1445 = sqlsrv_fetch_array($query_seFP1445, SQLSRV_FETCH_ASSOC);

                                $rsFP1400 = '';
                                $rsFP1415 = '';
                                $rsFP1430 = '';
                                $rsFP1445 = '';

                                if ($result_seFP1400['CNT'] != '0') {
                                    $rsFP1400 = "background-color: yellow";
                                }
                                if ($result_seFP1415['CNT'] != '0') {
                                    $rsFP1415 = "background-color: yellow";
                                }
                                if ($result_seFP1430['CNT'] != '0') {
                                    $rsFP1430 = "background-color: yellow";
                                }
                                if ($result_seFP1445['CNT'] != '0') {
                                    $rsFP1445 = "background-color: yellow";
                                }

                                $sql_seFP1500 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1500 = array();
                                $query_seFP1500 = sqlsrv_query($conn, $sql_seFP1500, $params_seFP1500);
                                $result_seFP1500 = sqlsrv_fetch_array($query_seFP1500, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1515 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1515 = array();
                                $query_seFP1515 = sqlsrv_query($conn, $sql_seFP1515, $params_seFP1515);
                                $result_seFP1515 = sqlsrv_fetch_array($query_seFP1515, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1530 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1530 = array();
                                $query_seFP1530 = sqlsrv_query($conn, $sql_seFP1530, $params_seFP1530);
                                $result_seFP1530 = sqlsrv_fetch_array($query_seFP1530, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1545 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1545 = array();
                                $query_seFP1545 = sqlsrv_query($conn, $sql_seFP1545, $params_seFP1545);
                                $result_seFP1545 = sqlsrv_fetch_array($query_seFP1545, SQLSRV_FETCH_ASSOC);

                                $rsFP1500 = '';
                                $rsFP1515 = '';
                                $rsFP1530 = '';
                                $rsFP1545 = '';

                                if ($result_seFP1500['CNT'] != '0') {
                                    $rsFP1500 = "background-color: yellow";
                                }
                                if ($result_seFP1515['CNT'] != '0') {
                                    $rsFP1515 = "background-color: yellow";
                                }
                                if ($result_seFP1530['CNT'] != '0') {
                                    $rsFP1530 = "background-color: yellow";
                                }
                                if ($result_seFP1545['CNT'] != '0') {
                                    $rsFP1545 = "background-color: yellow";
                                }

                                $sql_seFP1600 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1600 = array();
                                $query_seFP1600 = sqlsrv_query($conn, $sql_seFP1600, $params_seFP1600);
                                $result_seFP1600 = sqlsrv_fetch_array($query_seFP1600, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1615 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1615 = array();
                                $query_seFP1615 = sqlsrv_query($conn, $sql_seFP1615, $params_seFP1615);
                                $result_seFP1615 = sqlsrv_fetch_array($query_seFP1615, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1630 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1630 = array();
                                $query_seFP1630 = sqlsrv_query($conn, $sql_seFP1630, $params_seFP1630);
                                $result_seFP1630 = sqlsrv_fetch_array($query_seFP1630, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1645 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1645 = array();
                                $query_seFP1645 = sqlsrv_query($conn, $sql_seFP1645, $params_seFP1645);
                                $result_seFP1645 = sqlsrv_fetch_array($query_seFP1645, SQLSRV_FETCH_ASSOC);

                                $rsFP1600 = '';
                                $rsFP1615 = '';
                                $rsFP1630 = '';
                                $rsFP1645 = '';

                                if ($result_seFP1600['CNT'] != '0') {
                                    $rsFP1600 = "background-color: yellow";
                                }
                                if ($result_seFP1615['CNT'] != '0') {
                                    $rsFP1615 = "background-color: yellow";
                                }
                                if ($result_seFP1630['CNT'] != '0') {
                                    $rsFP1630 = "background-color: yellow";
                                }
                                if ($result_seFP1645['CNT'] != '0') {
                                    $rsFP1645 = "background-color: yellow";
                                }

                                $sql_seFP1700 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1700 = array();
                                $query_seFP1700 = sqlsrv_query($conn, $sql_seFP1700, $params_seFP1700);
                                $result_seFP1700 = sqlsrv_fetch_array($query_seFP1700, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1715 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1715 = array();
                                $query_seFP1715 = sqlsrv_query($conn, $sql_seFP1715, $params_seFP1715);
                                $result_seFP1715 = sqlsrv_fetch_array($query_seFP1715, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1730 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1730 = array();
                                $query_seFP1730 = sqlsrv_query($conn, $sql_seFP1730, $params_seFP1730);
                                $result_seFP1730 = sqlsrv_fetch_array($query_seFP1730, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1745 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1745 = array();
                                $query_seFP1745 = sqlsrv_query($conn, $sql_seFP1745, $params_seFP1745);
                                $result_seFP1745 = sqlsrv_fetch_array($query_seFP1745, SQLSRV_FETCH_ASSOC);

                                $rsFP1700 = '';
                                $rsFP1715 = '';
                                $rsFP1730 = '';
                                $rsFP1745 = '';

                                if ($result_seFP1700['CNT'] != '0') {
                                    $rsFP1700 = "background-color: yellow";
                                }
                                if ($result_seFP1715['CNT'] != '0') {
                                    $rsFP1715 = "background-color: yellow";
                                }
                                if ($result_seFP1730['CNT'] != '0') {
                                    $rsFP1730 = "background-color: yellow";
                                }
                                if ($result_seFP1745['CNT'] != '0') {
                                    $rsFP1745 = "background-color: yellow";
                                }

                                $sql_seFP1800 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1800 = array();
                                $query_seFP1800 = sqlsrv_query($conn, $sql_seFP1800, $params_seFP1800);
                                $result_seFP1800 = sqlsrv_fetch_array($query_seFP1800, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1815 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1815 = array();
                                $query_seFP1815 = sqlsrv_query($conn, $sql_seFP1815, $params_seFP1815);
                                $result_seFP1815 = sqlsrv_fetch_array($query_seFP1815, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1830 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1830 = array();
                                $query_seFP1830 = sqlsrv_query($conn, $sql_seFP1830, $params_seFP1830);
                                $result_seFP1830 = sqlsrv_fetch_array($query_seFP1830, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1845 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1845 = array();
                                $query_seFP1845 = sqlsrv_query($conn, $sql_seFP1845, $params_seFP1845);
                                $result_seFP1845 = sqlsrv_fetch_array($query_seFP1845, SQLSRV_FETCH_ASSOC);

                                $rsFP1800 = '';
                                $rsFP1815 = '';
                                $rsFP1830 = '';
                                $rsFP1845 = '';

                                if ($result_seFP1800['CNT'] != '0') {
                                    $rsFP1800 = "background-color: yellow";
                                }
                                if ($result_seFP1815['CNT'] != '0') {
                                    $rsFP1815 = "background-color: yellow";
                                }
                                if ($result_seFP1830['CNT'] != '0') {
                                    $rsFP1830 = "background-color: yellow";
                                }
                                if ($result_seFP1845['CNT'] != '0') {
                                    $rsFP1845 = "background-color: yellow";
                                }

                                $sql_seFP1900 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1900 = array();
                                $query_seFP1900 = sqlsrv_query($conn, $sql_seFP1900, $params_seFP1900);
                                $result_seFP1900 = sqlsrv_fetch_array($query_seFP1900, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1915 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1915 = array();
                                $query_seFP1915 = sqlsrv_query($conn, $sql_seFP1915, $params_seFP1915);
                                $result_seFP1915 = sqlsrv_fetch_array($query_seFP1915, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1930 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1930 = array();
                                $query_seFP1930 = sqlsrv_query($conn, $sql_seFP1930, $params_seFP1930);
                                $result_seFP1930 = sqlsrv_fetch_array($query_seFP1930, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1945 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP1945 = array();
                                $query_seFP1945 = sqlsrv_query($conn, $sql_seFP1945, $params_seFP1945);
                                $result_seFP1945 = sqlsrv_fetch_array($query_seFP1945, SQLSRV_FETCH_ASSOC);

                                $rsFP1900 = '';
                                $rsFP1915 = '';
                                $rsFP1930 = '';
                                $rsFP1945 = '';

                                if ($result_seFP1900['CNT'] != '0') {
                                    $rsFP1900 = "background-color: yellow";
                                }
                                if ($result_seFP1915['CNT'] != '0') {
                                    $rsFP1915 = "background-color: yellow";
                                }
                                if ($result_seFP1930['CNT'] != '0') {
                                    $rsFP1930 = "background-color: yellow";
                                }
                                if ($result_seFP1945['CNT'] != '0') {
                                    $rsFP1945 = "background-color: yellow";
                                }

                                $sql_seFP2000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2000 = array();
                                $query_seFP2000 = sqlsrv_query($conn, $sql_seFP2000, $params_seFP2000);
                                $result_seFP2000 = sqlsrv_fetch_array($query_seFP2000, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2015 = array();
                                $query_seFP2015 = sqlsrv_query($conn, $sql_seFP2015, $params_seFP2015);
                                $result_seFP2015 = sqlsrv_fetch_array($query_seFP2015, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2030 = array();
                                $query_seFP2030 = sqlsrv_query($conn, $sql_seFP2030, $params_seFP2030);
                                $result_seFP2030 = sqlsrv_fetch_array($query_seFP2030, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2045 = array();
                                $query_seFP2045 = sqlsrv_query($conn, $sql_seFP2045, $params_seFP2045);
                                $result_seFP2045 = sqlsrv_fetch_array($query_seFP2045, SQLSRV_FETCH_ASSOC);

                                $rsFP2000 = '';
                                $rsFP2015 = '';
                                $rsFP2030 = '';
                                $rsFP2045 = '';

                                if ($result_seFP2000['CNT'] != '0') {
                                    $rsFP2000 = "background-color: yellow";
                                }
                                if ($result_seFP2015['CNT'] != '0') {
                                    $rsFP2015 = "background-color: yellow";
                                }
                                if ($result_seFP2030['CNT'] != '0') {
                                    $rsFP2030 = "background-color: yellow";
                                }
                                if ($result_seFP2045['CNT'] != '0') {
                                    $rsFP2045 = "background-color: yellow";
                                }

                                $sql_seFP2100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2100 = array();
                                $query_seFP2100 = sqlsrv_query($conn, $sql_seFP2100, $params_seFP2100);
                                $result_seFP2100 = sqlsrv_fetch_array($query_seFP2100, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2115 = array();
                                $query_seFP2115 = sqlsrv_query($conn, $sql_seFP2115, $params_seFP2115);
                                $result_seFP2115 = sqlsrv_fetch_array($query_seFP2115, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2130 = array();
                                $query_seFP2130 = sqlsrv_query($conn, $sql_seFP2130, $params_seFP2130);
                                $result_seFP2130 = sqlsrv_fetch_array($query_seFP2130, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2145 = array();
                                $query_seFP2145 = sqlsrv_query($conn, $sql_seFP2145, $params_seFP2145);
                                $result_seFP2145 = sqlsrv_fetch_array($query_seFP2145, SQLSRV_FETCH_ASSOC);

                                $rsFP2100 = '';
                                $rsFP2115 = '';
                                $rsFP2130 = '';
                                $rsFP2145 = '';

                                if ($result_seFP2100['CNT'] != '0') {
                                    $rsFP2100 = "background-color: yellow";
                                }
                                if ($result_seFP2115['CNT'] != '0') {
                                    $rsFP2115 = "background-color: yellow";
                                }
                                if ($result_seFP2130['CNT'] != '0') {
                                    $rsFP2130 = "background-color: yellow";
                                }
                                if ($result_seFP2145['CNT'] != '0') {
                                    $rsFP2145 = "background-color: yellow";
                                }

                                $sql_seFP2200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2200 = array();
                                $query_seFP2200 = sqlsrv_query($conn, $sql_seFP2200, $params_seFP2200);
                                $result_seFP2200 = sqlsrv_fetch_array($query_seFP2200, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2215 = array();
                                $query_seFP2215 = sqlsrv_query($conn, $sql_seFP2215, $params_seFP2215);
                                $result_seFP2215 = sqlsrv_fetch_array($query_seFP2215, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2230 = array();
                                $query_seFP2230 = sqlsrv_query($conn, $sql_seFP2230, $params_seFP2230);
                                $result_seFP2230 = sqlsrv_fetch_array($query_seFP2230, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2245 = array();
                                $query_seFP2245 = sqlsrv_query($conn, $sql_seFP2245, $params_seFP2245);
                                $result_seFP2245 = sqlsrv_fetch_array($query_seFP2245, SQLSRV_FETCH_ASSOC);

                                $rsFP2200 = '';
                                $rsFP2215 = '';
                                $rsFP2230 = '';
                                $rsFP2245 = '';

                                if ($result_seFP2200['CNT'] != '0') {
                                    $rsFP2200 = "background-color: yellow";
                                }
                                if ($result_seFP2215['CNT'] != '0') {
                                    $rsFP2215 = "background-color: yellow";
                                }
                                if ($result_seFP2230['CNT'] != '0') {
                                    $rsFP2230 = "background-color: yellow";
                                }
                                if ($result_seFP2245['CNT'] != '0') {
                                    $rsFP2245 = "background-color: yellow";
                                }

                                $sql_seFP2300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2300 = array();
                                $query_seFP2300 = sqlsrv_query($conn, $sql_seFP2300, $params_seFP2300);
                                $result_seFP2300 = sqlsrv_fetch_array($query_seFP2300, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2315 = array();
                                $query_seFP2315 = sqlsrv_query($conn, $sql_seFP2315, $params_seFP2315);
                                $result_seFP2315 = sqlsrv_fetch_array($query_seFP2315, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2330 = array();
                                $query_seFP2330 = sqlsrv_query($conn, $sql_seFP2330, $params_seFP2330);
                                $result_seFP2330 = sqlsrv_fetch_array($query_seFP2330, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seFP2345 = array();
                                $query_seFP2345 = sqlsrv_query($conn, $sql_seFP2345, $params_seFP2345);
                                $result_seFP2345 = sqlsrv_fetch_array($query_seFP2345, SQLSRV_FETCH_ASSOC);

                                $rsFP2300 = '';
                                $rsFP2315 = '';
                                $rsFP2330 = '';
                                $rsFP2345 = '';

                                if ($result_seFP2300['CNT'] != '0') {
                                    $rsFP2300 = "background-color: yellow";
                                }
                                if ($result_seFP2315['CNT'] != '0') {
                                    $rsFP2315 = "background-color: yellow";
                                }
                                if ($result_seFP2330['CNT'] != '0') {
                                    $rsFP2330 = "background-color: yellow";
                                }
                                if ($result_seFP2345['CNT'] != '0') {
                                    $rsFP2345 = "background-color: yellow";
                                }

                        // #################################################### ACTUALS1 ####################################################
        
                                $sql_seAP0000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 00:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0000 = array();
                                $query_seAP0000 = sqlsrv_query($conn, $sql_seAP0000, $params_seAP0000);
                                $result_seAP0000 = sqlsrv_fetch_array($query_seAP0000, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 00:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0015 = array();
                                $query_seAP0015 = sqlsrv_query($conn, $sql_seAP0015, $params_seAP0015);
                                $result_seAP0015 = sqlsrv_fetch_array($query_seAP0015, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 00:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0030 = array();
                                $query_seAP0030 = sqlsrv_query($conn, $sql_seAP0030, $params_seAP0030);
                                $result_seAP0030 = sqlsrv_fetch_array($query_seAP0030, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 00:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0045 = array();
                                $query_seAP0045 = sqlsrv_query($conn, $sql_seAP0045, $params_seAP0045);
                                $result_seAP0045 = sqlsrv_fetch_array($query_seAP0045, SQLSRV_FETCH_ASSOC);

                                $rsAP0000 = '';
                                $rsAP0015 = '';
                                $rsAP0030 = '';
                                $rsAP0045 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0000['CNT'] != '0') {
                                        $rsAP0000 = "background-color: green";
                                    }
                                    if ($result_seAP0015['CNT'] != '0') {
                                        $rsAP0015 = "background-color: green";
                                    }
                                    if ($result_seAP0030['CNT'] != '0') {
                                        $rsAP0030 = "background-color: green";
                                    }
                                    if ($result_seAP0045['CNT'] != '0') {
                                        $rsAP0045 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0000['CNT'] != '0') {
                                        $rsAP0000 = "background-color: blue";
                                    }
                                    if ($result_seAP0015['CNT'] != '0') {
                                        $rsAP0015 = "background-color: blue";
                                    }
                                    if ($result_seAP0030['CNT'] != '0') {
                                        $rsAP0030 = "background-color: blue";
                                    }
                                    if ($result_seAP0045['CNT'] != '0') {
                                        $rsAP0045 = "background-color: blue";
                                    }
                                }
        
                                $sql_seAP0100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 01:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0100 = array();
                                $query_seAP0100 = sqlsrv_query($conn, $sql_seAP0100, $params_seAP0100);
                                $result_seAP0100 = sqlsrv_fetch_array($query_seAP0100, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 01:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0115 = array();
                                $query_seAP0115 = sqlsrv_query($conn, $sql_seAP0115, $params_seAP0115);
                                $result_seAP0115 = sqlsrv_fetch_array($query_seAP0115, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 01:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0130 = array();
                                $query_seAP0130 = sqlsrv_query($conn, $sql_seAP0130, $params_seAP0130);
                                $result_seAP0130 = sqlsrv_fetch_array($query_seAP0130, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 01:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0145 = array();
                                $query_seAP0145 = sqlsrv_query($conn, $sql_seAP0145, $params_seAP0145);
                                $result_seAP0145 = sqlsrv_fetch_array($query_seAP0145, SQLSRV_FETCH_ASSOC);

                                $rsAP0100 = '';
                                $rsAP0115 = '';
                                $rsAP0130 = '';
                                $rsAP0145 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0100['CNT'] != '0') {
                                        $rsAP0100 = "background-color: green";
                                    }
                                    if ($result_seAP0115['CNT'] != '0') {
                                        $rsAP0115 = "background-color: green";
                                    }
                                    if ($result_seAP0130['CNT'] != '0') {
                                        $rsAP0130 = "background-color: green";
                                    }
                                    if ($result_seAP0145['CNT'] != '0') {
                                        $rsAP0145 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0100['CNT'] != '0') {
                                        $rsAP0100 = "background-color: blue";
                                    }
                                    if ($result_seAP0115['CNT'] != '0') {
                                        $rsAP0115 = "background-color: blue";
                                    }
                                    if ($result_seAP0130['CNT'] != '0') {
                                        $rsAP0130 = "background-color: blue";
                                    }
                                    if ($result_seAP0145['CNT'] != '0') {
                                        $rsAP0145 = "background-color: blue";
                                    }
                                }
        
                                $sql_seAP0200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 02:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0200 = array();
                                $query_seAP0200 = sqlsrv_query($conn, $sql_seAP0200, $params_seAP0200);
                                $result_seAP0200 = sqlsrv_fetch_array($query_seAP0200, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 02:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0215 = array();
                                $query_seAP0215 = sqlsrv_query($conn, $sql_seAP0215, $params_seAP0215);
                                $result_seAP0215 = sqlsrv_fetch_array($query_seAP0215, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 02:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0230 = array();
                                $query_seAP0230 = sqlsrv_query($conn, $sql_seAP0230, $params_seAP0230);
                                $result_seAP0230 = sqlsrv_fetch_array($query_seAP0230, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 02:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0245 = array();
                                $query_seAP0245 = sqlsrv_query($conn, $sql_seAP0245, $params_seAP0245);
                                $result_seAP0245 = sqlsrv_fetch_array($query_seAP0245, SQLSRV_FETCH_ASSOC);

                                $rsAP0200 = '';
                                $rsAP0215 = '';
                                $rsAP0230 = '';
                                $rsAP0245 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0200['CNT'] != '0') {
                                        $rsAP0200 = "background-color: green";
                                    }
                                    if ($result_seAP0215['CNT'] != '0') {
                                        $rsAP0215 = "background-color: green";
                                    }
                                    if ($result_seAP0230['CNT'] != '0') {
                                        $rsAP0230 = "background-color: green";
                                    }
                                    if ($result_seAP0245['CNT'] != '0') {
                                        $rsAP0245 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0200['CNT'] != '0') {
                                        $rsAP0200 = "background-color: blue";
                                    }
                                    if ($result_seAP0215['CNT'] != '0') {
                                        $rsAP0215 = "background-color: blue";
                                    }
                                    if ($result_seAP0230['CNT'] != '0') {
                                        $rsAP0230 = "background-color: blue";
                                    }
                                    if ($result_seAP0245['CNT'] != '0') {
                                        $rsAP0245 = "background-color: blue";
                                    }
                                }
        
                                $sql_seAP0300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 03:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0300 = array();
                                $query_seAP0300 = sqlsrv_query($conn, $sql_seAP0300, $params_seAP0300);
                                $result_seAP0300 = sqlsrv_fetch_array($query_seAP0300, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 03:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0315 = array();
                                $query_seAP0315 = sqlsrv_query($conn, $sql_seAP0315, $params_seAP0315);
                                $result_seAP0315 = sqlsrv_fetch_array($query_seAP0315, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 03:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0330 = array();
                                $query_seAP0330 = sqlsrv_query($conn, $sql_seAP0330, $params_seAP0330);
                                $result_seAP0330 = sqlsrv_fetch_array($query_seAP0330, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 03:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0345 = array();
                                $query_seAP0345 = sqlsrv_query($conn, $sql_seAP0345, $params_seAP0345);
                                $result_seAP0345 = sqlsrv_fetch_array($query_seAP0345, SQLSRV_FETCH_ASSOC);

                                $rsAP0300 = '';
                                $rsAP0315 = '';
                                $rsAP0330 = '';
                                $rsAP0345 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0300['CNT'] != '0') {
                                        $rsAP0300 = "background-color: green";
                                    }
                                    if ($result_seAP0315['CNT'] != '0') {
                                        $rsAP0315 = "background-color: green";
                                    }
                                    if ($result_seAP0330['CNT'] != '0') {
                                        $rsAP0330 = "background-color: green";
                                    }
                                    if ($result_seAP0345['CNT'] != '0') {
                                        $rsAP0345 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0300['CNT'] != '0') {
                                        $rsAP0300 = "background-color: blue";
                                    }
                                    if ($result_seAP0315['CNT'] != '0') {
                                        $rsAP0315 = "background-color: blue";
                                    }
                                    if ($result_seAP0330['CNT'] != '0') {
                                        $rsAP0330 = "background-color: blue";
                                    }
                                    if ($result_seAP0345['CNT'] != '0') {
                                        $rsAP0345 = "background-color: blue";
                                    }
                                }
        
                                $sql_seAP0400 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 04:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0400 = array();
                                $query_seAP0400 = sqlsrv_query($conn, $sql_seAP0400, $params_seAP0400);
                                $result_seAP0400 = sqlsrv_fetch_array($query_seAP0400, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0415 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 04:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0415 = array();
                                $query_seAP0415 = sqlsrv_query($conn, $sql_seAP0415, $params_seAP0415);
                                $result_seAP0415 = sqlsrv_fetch_array($query_seAP0415, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0430 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 04:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0430 = array();
                                $query_seAP0430 = sqlsrv_query($conn, $sql_seAP0430, $params_seAP0430);
                                $result_seAP0430 = sqlsrv_fetch_array($query_seAP0430, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0445 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 04:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0445 = array();
                                $query_seAP0445 = sqlsrv_query($conn, $sql_seAP0445, $params_seAP0445);
                                $result_seAP0445 = sqlsrv_fetch_array($query_seAP0445, SQLSRV_FETCH_ASSOC);

                                $rsAP0400 = '';
                                $rsAP0415 = '';
                                $rsAP0430 = '';
                                $rsAP0445 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0400['CNT'] != '0') {
                                        $rsAP0400 = "background-color: green";
                                    }
                                    if ($result_seAP0415['CNT'] != '0') {
                                        $rsAP0415 = "background-color: green";
                                    }
                                    if ($result_seAP0430['CNT'] != '0') {
                                        $rsAP0430 = "background-color: green";
                                    }
                                    if ($result_seAP0445['CNT'] != '0') {
                                        $rsAP0445 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0400['CNT'] != '0') {
                                        $rsAP0400 = "background-color: blue";
                                    }
                                    if ($result_seAP0415['CNT'] != '0') {
                                        $rsAP0415 = "background-color: blue";
                                    }
                                    if ($result_seAP0430['CNT'] != '0') {
                                        $rsAP0430 = "background-color: blue";
                                    }
                                    if ($result_seAP0445['CNT'] != '0') {
                                        $rsAP0445 = "background-color: blue";
                                    }
                                }
        
                                $sql_seAP0500 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 05:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0500 = array();
                                $query_seAP0500 = sqlsrv_query($conn, $sql_seAP0500, $params_seAP0500);
                                $result_seAP0500 = sqlsrv_fetch_array($query_seAP0500, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0515 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 05:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0515 = array();
                                $query_seAP0515 = sqlsrv_query($conn, $sql_seAP0515, $params_seAP0515);
                                $result_seAP0515 = sqlsrv_fetch_array($query_seAP0515, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0530 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 05:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0530 = array();
                                $query_seAP0530 = sqlsrv_query($conn, $sql_seAP0530, $params_seAP0530);
                                $result_seAP0530 = sqlsrv_fetch_array($query_seAP0530, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0545 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 05:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0545 = array();
                                $query_seAP0545 = sqlsrv_query($conn, $sql_seAP0545, $params_seAP0545);
                                $result_seAP0545 = sqlsrv_fetch_array($query_seAP0545, SQLSRV_FETCH_ASSOC);

                                $rsAP0500 = '';
                                $rsAP0515 = '';
                                $rsAP0530 = '';
                                $rsAP0545 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0500['CNT'] != '0') {
                                        $rsAP0500 = "background-color: green";
                                    }
                                    if ($result_seAP0515['CNT'] != '0') {
                                        $rsAP0515 = "background-color: green";
                                    }
                                    if ($result_seAP0530['CNT'] != '0') {
                                        $rsAP0530 = "background-color: green";
                                    }
                                    if ($result_seAP0545['CNT'] != '0') {
                                        $rsAP0545 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0500['CNT'] != '0') {
                                        $rsAP0500 = "background-color: blue";
                                    }
                                    if ($result_seAP0515['CNT'] != '0') {
                                        $rsAP0515 = "background-color: blue";
                                    }
                                    if ($result_seAP0530['CNT'] != '0') {
                                        $rsAP0530 = "background-color: blue";
                                    }
                                    if ($result_seAP0545['CNT'] != '0') {
                                        $rsAP0545 = "background-color: blue";
                                    }
                                }

                                $sql_seAP0600 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 06:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0600 = array();
                                $query_seAP0600 = sqlsrv_query($conn, $sql_seAP0600, $params_seAP0600);
                                $result_seAP0600 = sqlsrv_fetch_array($query_seAP0600, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0615 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 06:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0615 = array();
                                $query_seAP0615 = sqlsrv_query($conn, $sql_seAP0615, $params_seAP0615);
                                $result_seAP0615 = sqlsrv_fetch_array($query_seAP0615, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0630 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 06:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0630 = array();
                                $query_seAP0630 = sqlsrv_query($conn, $sql_seAP0630, $params_seAP0630);
                                $result_seAP0630 = sqlsrv_fetch_array($query_seAP0630, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0645 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 06:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0645 = array();
                                $query_seAP0645 = sqlsrv_query($conn, $sql_seAP0645, $params_seAP0645);
                                $result_seAP0645 = sqlsrv_fetch_array($query_seAP0645, SQLSRV_FETCH_ASSOC);

                                $rsAP0600 = '';
                                $rsAP0615 = '';
                                $rsAP0630 = '';
                                $rsAP0645 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0600['CNT'] != '0') {
                                        $rsAP0600 = "background-color: green";
                                    }
                                    if ($result_seAP0615['CNT'] != '0') {
                                        $rsAP0615 = "background-color: green";
                                    }
                                    if ($result_seAP0630['CNT'] != '0') {
                                        $rsAP0630 = "background-color: green";
                                    }
                                    if ($result_seAP0645['CNT'] != '0') {
                                        $rsAP0645 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0600['CNT'] != '0') {
                                        $rsAP0600 = "background-color: blue";
                                    }
                                    if ($result_seAP0615['CNT'] != '0') {
                                        $rsAP0615 = "background-color: blue";
                                    }
                                    if ($result_seAP0630['CNT'] != '0') {
                                        $rsAP0630 = "background-color: blue";
                                    }
                                    if ($result_seAP0645['CNT'] != '0') {
                                        $rsAP0645 = "background-color: blue";
                                    }
                                }

                                $sql_seAP0700 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 07:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0700 = array();
                                $query_seAP0700 = sqlsrv_query($conn, $sql_seAP0700, $params_seAP0700);
                                $result_seAP0700 = sqlsrv_fetch_array($query_seAP0700, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0715 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 07:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0715 = array();
                                $query_seAP0715 = sqlsrv_query($conn, $sql_seAP0715, $params_seAP0715);
                                $result_seAP0715 = sqlsrv_fetch_array($query_seAP0715, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0730 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 07:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0730 = array();
                                $query_seAP0730 = sqlsrv_query($conn, $sql_seAP0730, $params_seAP0730);
                                $result_seAP0730 = sqlsrv_fetch_array($query_seAP0730, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0745 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 07:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0745 = array();
                                $query_seAP0745 = sqlsrv_query($conn, $sql_seAP0745, $params_seAP0745);
                                $result_seAP0745 = sqlsrv_fetch_array($query_seAP0745, SQLSRV_FETCH_ASSOC);

                                $rsAP0700 = '';
                                $rsAP0715 = '';
                                $rsAP0730 = '';
                                $rsAP0745 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0700['CNT'] != '0') {
                                        $rsAP0700 = "background-color: green";
                                    }
                                    if ($result_seAP0715['CNT'] != '0') {
                                        $rsAP0715 = "background-color: green";
                                    }
                                    if ($result_seAP0730['CNT'] != '0') {
                                        $rsAP0730 = "background-color: green";
                                    }
                                    if ($result_seAP0745['CNT'] != '0') {
                                        $rsAP0745 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0700['CNT'] != '0') {
                                        $rsAP0700 = "background-color: blue";
                                    }
                                    if ($result_seAP0715['CNT'] != '0') {
                                        $rsAP0715 = "background-color: blue";
                                    }
                                    if ($result_seAP0730['CNT'] != '0') {
                                        $rsAP0730 = "background-color: blue";
                                    }
                                    if ($result_seAP0745['CNT'] != '0') {
                                        $rsAP0745 = "background-color: blue";
                                    }
                                }

                                $sql_seAP0800 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 08:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0800 = array();
                                $query_seAP0800 = sqlsrv_query($conn, $sql_seAP0800, $params_seAP0800);
                                $result_seAP0800 = sqlsrv_fetch_array($query_seAP0800, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0815 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 08:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0815 = array();
                                $query_seAP0815 = sqlsrv_query($conn, $sql_seAP0815, $params_seAP0815);
                                $result_seAP0815 = sqlsrv_fetch_array($query_seAP0815, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0830 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 08:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0830 = array();
                                $query_seAP0830 = sqlsrv_query($conn, $sql_seAP0830, $params_seAP0830);
                                $result_seAP0830 = sqlsrv_fetch_array($query_seAP0830, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0845 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 08:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0845 = array();
                                $query_seAP0845 = sqlsrv_query($conn, $sql_seAP0845, $params_seAP0845);
                                $result_seAP0845 = sqlsrv_fetch_array($query_seAP0845, SQLSRV_FETCH_ASSOC);

                                $rsAP0800 = '';
                                $rsAP0815 = '';
                                $rsAP0830 = '';
                                $rsAP0845 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0800['CNT'] != '0') {
                                        $rsAP0800 = "background-color: green";
                                    }
                                    if ($result_seAP0815['CNT'] != '0') {
                                        $rsAP0815 = "background-color: green";
                                    }
                                    if ($result_seAP0830['CNT'] != '0') {
                                        $rsAP0830 = "background-color: green";
                                    }
                                    if ($result_seAP0845['CNT'] != '0') {
                                        $rsAP0845 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0800['CNT'] != '0') {
                                        $rsAP0800 = "background-color: blue";
                                    }
                                    if ($result_seAP0815['CNT'] != '0') {
                                        $rsAP0815 = "background-color: blue";
                                    }
                                    if ($result_seAP0830['CNT'] != '0') {
                                        $rsAP0830 = "background-color: blue";
                                    }
                                    if ($result_seAP0845['CNT'] != '0') {
                                        $rsAP0845 = "background-color: blue";
                                    }
                                }

                                $sql_seAP0900 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 09:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0900 = array();
                                $query_seAP0900 = sqlsrv_query($conn, $sql_seAP0900, $params_seAP0900);
                                $result_seAP0900 = sqlsrv_fetch_array($query_seAP0900, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0915 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 09:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0915 = array();
                                $query_seAP0915 = sqlsrv_query($conn, $sql_seAP0915, $params_seAP0915);
                                $result_seAP0915 = sqlsrv_fetch_array($query_seAP0915, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0930 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 09:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0930 = array();
                                $query_seAP0930 = sqlsrv_query($conn, $sql_seAP0930, $params_seAP0930);
                                $result_seAP0930 = sqlsrv_fetch_array($query_seAP0930, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0945 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 09:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP0945 = array();
                                $query_seAP0945 = sqlsrv_query($conn, $sql_seAP0945, $params_seAP0945);
                                $result_seAP0945 = sqlsrv_fetch_array($query_seAP0945, SQLSRV_FETCH_ASSOC);

                                $rsAP0900 = '';
                                $rsAP0915 = '';
                                $rsAP0930 = '';
                                $rsAP0945 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                if ($result_seAP0900['CNT'] != '0') {
                                    $rsAP0900 = "background-color: green";
                                }
                                if ($result_seAP0915['CNT'] != '0') {
                                    $rsAP0915 = "background-color: green";
                                }
                                if ($result_seAP0930['CNT'] != '0') {
                                    $rsAP0930 = "background-color: green";
                                }
                                if ($result_seAP0945['CNT'] != '0') {
                                    $rsAP0945 = "background-color: green";
                                }
                                } else {
                                if ($result_seAP0900['CNT'] != '0') {
                                    $rsAP0900 = "background-color: blue";
                                }
                                if ($result_seAP0915['CNT'] != '0') {
                                    $rsAP0915 = "background-color: blue";
                                }
                                if ($result_seAP0930['CNT'] != '0') {
                                    $rsAP0930 = "background-color: blue";
                                }
                                if ($result_seAP0945['CNT'] != '0') {
                                    $rsAP0945 = "background-color: blue";
                                }
                                }
                                $sql_seAP1000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 10:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1000 = array();
                                $query_seAP1000 = sqlsrv_query($conn, $sql_seAP1000, $params_seAP1000);
                                $result_seAP1000 = sqlsrv_fetch_array($query_seAP1000, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 10:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1015 = array();
                                $query_seAP1015 = sqlsrv_query($conn, $sql_seAP1015, $params_seAP1015);
                                $result_seAP1015 = sqlsrv_fetch_array($query_seAP1015, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 10:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1030 = array();
                                $query_seAP1030 = sqlsrv_query($conn, $sql_seAP1030, $params_seAP1030);
                                $result_seAP1030 = sqlsrv_fetch_array($query_seAP1030, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 10:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1045 = array();
                                $query_seAP1045 = sqlsrv_query($conn, $sql_seAP1045, $params_seAP1045);
                                $result_seAP1045 = sqlsrv_fetch_array($query_seAP1045, SQLSRV_FETCH_ASSOC);

                                $rsAP1000 = '';
                                $rsAP1015 = '';
                                $rsAP1030 = '';
                                $rsAP1045 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                if ($result_seAP1000['CNT'] != '0') {
                                    $rsAP1000 = "background-color: green";
                                }
                                if ($result_seAP1015['CNT'] != '0') {
                                    $rsAP1015 = "background-color: green";
                                }
                                if ($result_seAP1030['CNT'] != '0') {
                                    $rsAP1030 = "background-color: green";
                                }
                                if ($result_seAP1045['CNT'] != '0') {
                                    $rsAP1045 = "background-color: green";
                                }
                                } else {
                                if ($result_seAP1000['CNT'] != '0') {
                                    $rsAP1000 = "background-color: blue";
                                }
                                if ($result_seAP1015['CNT'] != '0') {
                                    $rsAP1015 = "background-color: blue";
                                }
                                if ($result_seAP1030['CNT'] != '0') {
                                    $rsAP1030 = "background-color: blue";
                                }
                                if ($result_seAP1045['CNT'] != '0') {
                                    $rsAP1045 = "background-color: blue";
                                }
                                }

                                $sql_seAP1100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 11:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1100 = array();
                                $query_seAP1100 = sqlsrv_query($conn, $sql_seAP1100, $params_seAP1100);
                                $result_seAP1100 = sqlsrv_fetch_array($query_seAP1100, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 11:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1115 = array();
                                $query_seAP1115 = sqlsrv_query($conn, $sql_seAP1115, $params_seAP1115);
                                $result_seAP1115 = sqlsrv_fetch_array($query_seAP1115, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 11:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1130 = array();
                                $query_seAP1130 = sqlsrv_query($conn, $sql_seAP1130, $params_seAP1130);
                                $result_seAP1130 = sqlsrv_fetch_array($query_seAP1130, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 11:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1145 = array();
                                $query_seAP1145 = sqlsrv_query($conn, $sql_seAP1145, $params_seAP1145);
                                $result_seAP1145 = sqlsrv_fetch_array($query_seAP1145, SQLSRV_FETCH_ASSOC);

                                $rsAP1100 = '';
                                $rsAP1115 = '';
                                $rsAP1130 = '';
                                $rsAP1145 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                if ($result_seAP1100['CNT'] != '0') {
                                    $rsAP1100 = "background-color: green";
                                }
                                if ($result_seAP1115['CNT'] != '0') {
                                    $rsAP1115 = "background-color: green";
                                }
                                if ($result_seAP1130['CNT'] != '0') {
                                    $rsAP1130 = "background-color: green";
                                }
                                if ($result_seAP1145['CNT'] != '0') {
                                    $rsAP1145 = "background-color: green";
                                }
                                } else {
                                if ($result_seAP1100['CNT'] != '0') {
                                    $rsAP1100 = "background-color: blue";
                                }
                                if ($result_seAP1115['CNT'] != '0') {
                                    $rsAP1115 = "background-color: blue";
                                }
                                if ($result_seAP1130['CNT'] != '0') {
                                    $rsAP1130 = "background-color: blue";
                                }
                                if ($result_seAP1145['CNT'] != '0') {
                                    $rsAP1145 = "background-color: blue";
                                }
                                }

                                $sql_seAP1200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 12:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1200 = array();
                                $query_seAP1200 = sqlsrv_query($conn, $sql_seAP1200, $params_seAP1200);
                                $result_seAP1200 = sqlsrv_fetch_array($query_seAP1200, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 12:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1215 = array();
                                $query_seAP1215 = sqlsrv_query($conn, $sql_seAP1215, $params_seAP1215);
                                $result_seAP1215 = sqlsrv_fetch_array($query_seAP1215, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 12:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1230 = array();
                                $query_seAP1230 = sqlsrv_query($conn, $sql_seAP1230, $params_seAP1230);
                                $result_seAP1230 = sqlsrv_fetch_array($query_seAP1230, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 12:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1245 = array();
                                $query_seAP1245 = sqlsrv_query($conn, $sql_seAP1245, $params_seAP1245);
                                $result_seAP1245 = sqlsrv_fetch_array($query_seAP1245, SQLSRV_FETCH_ASSOC);

                                $rsAP1200 = '';
                                $rsAP1215 = '';
                                $rsAP1230 = '';
                                $rsAP1245 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1200['CNT'] != '0') {
                                        $rsAP1200 = "background-color: green";
                                    }
                                    if ($result_seAP1215['CNT'] != '0') {
                                        $rsAP1215 = "background-color: green";
                                    }
                                    if ($result_seAP1230['CNT'] != '0') {
                                        $rsAP1230 = "background-color: green";
                                    }
                                    if ($result_seAP1245['CNT'] != '0') {
                                        $rsAP1245 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1200['CNT'] != '0') {
                                        $rsAP1200 = "background-color: blue";
                                    }
                                    if ($result_seAP1215['CNT'] != '0') {
                                        $rsAP1215 = "background-color: blue";
                                    }
                                    if ($result_seAP1230['CNT'] != '0') {
                                        $rsAP1230 = "background-color: blue";
                                    }
                                    if ($result_seAP1245['CNT'] != '0') {
                                        $rsAP1245 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 13:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1300 = array();
                                $query_seAP1300 = sqlsrv_query($conn, $sql_seAP1300, $params_seAP1300);
                                $result_seAP1300 = sqlsrv_fetch_array($query_seAP1300, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 13:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1315 = array();
                                $query_seAP1315 = sqlsrv_query($conn, $sql_seAP1315, $params_seAP1315);
                                $result_seAP1315 = sqlsrv_fetch_array($query_seAP1315, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 13:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1330 = array();
                                $query_seAP1330 = sqlsrv_query($conn, $sql_seAP1330, $params_seAP1330);
                                $result_seAP1330 = sqlsrv_fetch_array($query_seAP1330, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 13:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1345 = array();
                                $query_seAP1345 = sqlsrv_query($conn, $sql_seAP1345, $params_seAP1345);
                                $result_seAP1345 = sqlsrv_fetch_array($query_seAP1345, SQLSRV_FETCH_ASSOC);

                                $rsAP1300 = '';
                                $rsAP1315 = '';
                                $rsAP1330 = '';
                                $rsAP1345 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1300['CNT'] != '0') {
                                        $rsAP1300 = "background-color: green";
                                    }
                                    if ($result_seAP1315['CNT'] != '0') {
                                        $rsAP1315 = "background-color: green";
                                    }
                                    if ($result_seAP1330['CNT'] != '0') {
                                        $rsAP1330 = "background-color: green";
                                    }
                                    if ($result_seAP1345['CNT'] != '0') {
                                        $rsAP1345 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1300['CNT'] != '0') {
                                        $rsAP1300 = "background-color: blue";
                                    }
                                    if ($result_seAP1315['CNT'] != '0') {
                                        $rsAP1315 = "background-color: blue";
                                    }
                                    if ($result_seAP1330['CNT'] != '0') {
                                        $rsAP1330 = "background-color: blue";
                                    }
                                    if ($result_seAP1345['CNT'] != '0') {
                                        $rsAP1345 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1400 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 14:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1400 = array();
                                $query_seAP1400 = sqlsrv_query($conn, $sql_seAP1400, $params_seAP1400);
                                $result_seAP1400 = sqlsrv_fetch_array($query_seAP1400, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1415 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 14:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1415 = array();
                                $query_seAP1415 = sqlsrv_query($conn, $sql_seAP1415, $params_seAP1415);
                                $result_seAP1415 = sqlsrv_fetch_array($query_seAP1415, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1430 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 14:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1430 = array();
                                $query_seAP1430 = sqlsrv_query($conn, $sql_seAP1430, $params_seAP1430);
                                $result_seAP1430 = sqlsrv_fetch_array($query_seAP1430, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1445 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 14:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1445 = array();
                                $query_seAP1445 = sqlsrv_query($conn, $sql_seAP1445, $params_seAP1445);
                                $result_seAP1445 = sqlsrv_fetch_array($query_seAP1445, SQLSRV_FETCH_ASSOC);

                                $rsAP1400 = '';
                                $rsAP1415 = '';
                                $rsAP1430 = '';
                                $rsAP1445 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1400['CNT'] != '0') {
                                        $rsAP1400 = "background-color: green";
                                    }
                                    if ($result_seAP1415['CNT'] != '0') {
                                        $rsAP1415 = "background-color: green";
                                    }
                                    if ($result_seAP1430['CNT'] != '0') {
                                        $rsAP1430 = "background-color: green";
                                    }
                                    if ($result_seAP1445['CNT'] != '0') {
                                        $rsAP1445 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1400['CNT'] != '0') {
                                        $rsAP1400 = "background-color: blue";
                                    }
                                    if ($result_seAP1415['CNT'] != '0') {
                                        $rsAP1415 = "background-color: blue";
                                    }
                                    if ($result_seAP1430['CNT'] != '0') {
                                        $rsAP1430 = "background-color: blue";
                                    }
                                    if ($result_seAP1445['CNT'] != '0') {
                                        $rsAP1445 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1500 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 15:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1500 = array();
                                $query_seAP1500 = sqlsrv_query($conn, $sql_seAP1500, $params_seAP1500);
                                $result_seAP1500 = sqlsrv_fetch_array($query_seAP1500, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1515 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 15:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1515 = array();
                                $query_seAP1515 = sqlsrv_query($conn, $sql_seAP1515, $params_seAP1515);
                                $result_seAP1515 = sqlsrv_fetch_array($query_seAP1515, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1530 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 15:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1530 = array();
                                $query_seAP1530 = sqlsrv_query($conn, $sql_seAP1530, $params_seAP1530);
                                $result_seAP1530 = sqlsrv_fetch_array($query_seAP1530, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1545 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 15:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1545 = array();
                                $query_seAP1545 = sqlsrv_query($conn, $sql_seAP1545, $params_seAP1545);
                                $result_seAP1545 = sqlsrv_fetch_array($query_seAP1545, SQLSRV_FETCH_ASSOC);

                                $rsAP1500 = '';
                                $rsAP1515 = '';
                                $rsAP1530 = '';
                                $rsAP1545 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1500['CNT'] != '0') {
                                        $rsAP1500 = "background-color: green";
                                    }
                                    if ($result_seAP1515['CNT'] != '0') {
                                        $rsAP1515 = "background-color: green";
                                    }
                                    if ($result_seAP1530['CNT'] != '0') {
                                        $rsAP1530 = "background-color: green";
                                    }
                                    if ($result_seAP1545['CNT'] != '0') {
                                        $rsAP1545 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1500['CNT'] != '0') {
                                        $rsAP1500 = "background-color: blue";
                                    }
                                    if ($result_seAP1515['CNT'] != '0') {
                                        $rsAP1515 = "background-color: blue";
                                    }
                                    if ($result_seAP1530['CNT'] != '0') {
                                        $rsAP1530 = "background-color: blue";
                                    }
                                    if ($result_seAP1545['CNT'] != '0') {
                                        $rsAP1545 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1600 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 16:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1600 = array();
                                $query_seAP1600 = sqlsrv_query($conn, $sql_seAP1600, $params_seAP1600);
                                $result_seAP1600 = sqlsrv_fetch_array($query_seAP1600, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1615 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 16:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1615 = array();
                                $query_seAP1615 = sqlsrv_query($conn, $sql_seAP1615, $params_seAP1615);
                                $result_seAP1615 = sqlsrv_fetch_array($query_seAP1615, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1630 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 16:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1630 = array();
                                $query_seAP1630 = sqlsrv_query($conn, $sql_seAP1630, $params_seAP1630);
                                $result_seAP1630 = sqlsrv_fetch_array($query_seAP1630, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1645 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 16:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1645 = array();
                                $query_seAP1645 = sqlsrv_query($conn, $sql_seAP1645, $params_seAP1645);
                                $result_seAP1645 = sqlsrv_fetch_array($query_seAP1645, SQLSRV_FETCH_ASSOC);

                                $rsAP1600 = '';
                                $rsAP1615 = '';
                                $rsAP1630 = '';
                                $rsAP1645 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1600['CNT'] != '0') {
                                        $rsAP1600 = "background-color: green";
                                    }
                                    if ($result_seAP1615['CNT'] != '0') {
                                        $rsAP1615 = "background-color: green";
                                    }
                                    if ($result_seAP1630['CNT'] != '0') {
                                        $rsAP1630 = "background-color: green";
                                    }
                                    if ($result_seAP1645['CNT'] != '0') {
                                        $rsAP1645 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1600['CNT'] != '0') {
                                        $rsAP1600 = "background-color: blue";
                                    }
                                    if ($result_seAP1615['CNT'] != '0') {
                                        $rsAP1615 = "background-color: blue";
                                    }
                                    if ($result_seAP1630['CNT'] != '0') {
                                        $rsAP1630 = "background-color: blue";
                                    }
                                    if ($result_seAP1645['CNT'] != '0') {
                                        $rsAP1645 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1700 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 17:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1700 = array();
                                $query_seAP1700 = sqlsrv_query($conn, $sql_seAP1700, $params_seAP1700);
                                $result_seAP1700 = sqlsrv_fetch_array($query_seAP1700, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1715 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 17:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1715 = array();
                                $query_seAP1715 = sqlsrv_query($conn, $sql_seAP1715, $params_seAP1715);
                                $result_seAP1715 = sqlsrv_fetch_array($query_seAP1715, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1730 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 17:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1730 = array();
                                $query_seAP1730 = sqlsrv_query($conn, $sql_seAP1730, $params_seAP1730);
                                $result_seAP1730 = sqlsrv_fetch_array($query_seAP1730, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1745 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 17:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1745 = array();
                                $query_seAP1745 = sqlsrv_query($conn, $sql_seAP1745, $params_seAP1745);
                                $result_seAP1745 = sqlsrv_fetch_array($query_seAP1745, SQLSRV_FETCH_ASSOC);

                                $rsAP1700 = '';
                                $rsAP1715 = '';
                                $rsAP1730 = '';
                                $rsAP1745 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1700['CNT'] != '0') {
                                        $rsAP1700 = "background-color: green";
                                    }
                                    if ($result_seAP1715['CNT'] != '0') {
                                        $rsAP1715 = "background-color: green";
                                    }
                                    if ($result_seAP1730['CNT'] != '0') {
                                        $rsAP1730 = "background-color: green";
                                    }
                                    if ($result_seAP1745['CNT'] != '0') {
                                        $rsAP1745 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1700['CNT'] != '0') {
                                        $rsAP1700 = "background-color: blue";
                                    }
                                    if ($result_seAP1715['CNT'] != '0') {
                                        $rsAP1715 = "background-color: blue";
                                    }
                                    if ($result_seAP1730['CNT'] != '0') {
                                        $rsAP1730 = "background-color: blue";
                                    }
                                    if ($result_seAP1745['CNT'] != '0') {
                                        $rsAP1745 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1800 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 18:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1800 = array();
                                $query_seAP1800 = sqlsrv_query($conn, $sql_seAP1800, $params_seAP1800);
                                $result_seAP1800 = sqlsrv_fetch_array($query_seAP1800, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1815 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 18:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1815 = array();
                                $query_seAP1815 = sqlsrv_query($conn, $sql_seAP1815, $params_seAP1815);
                                $result_seAP1815 = sqlsrv_fetch_array($query_seAP1815, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1830 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 18:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1830 = array();
                                $query_seAP1830 = sqlsrv_query($conn, $sql_seAP1830, $params_seAP1830);
                                $result_seAP1830 = sqlsrv_fetch_array($query_seAP1830, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1845 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 18:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1845 = array();
                                $query_seAP1845 = sqlsrv_query($conn, $sql_seAP1845, $params_seAP1845);
                                $result_seAP1845 = sqlsrv_fetch_array($query_seAP1845, SQLSRV_FETCH_ASSOC);

                                $rsAP1800 = '';
                                $rsAP1815 = '';
                                $rsAP1830 = '';
                                $rsAP1845 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1800['CNT'] != '0') {
                                        $rsAP1800 = "background-color: green";
                                    }
                                    if ($result_seAP1815['CNT'] != '0') {
                                        $rsAP1815 = "background-color: green";
                                    }
                                    if ($result_seAP1830['CNT'] != '0') {
                                        $rsAP1830 = "background-color: green";
                                    }
                                    if ($result_seAP1845['CNT'] != '0') {
                                        $rsAP1845 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1800['CNT'] != '0') {
                                        $rsAP1800 = "background-color: blue";
                                    }
                                    if ($result_seAP1815['CNT'] != '0') {
                                        $rsAP1815 = "background-color: blue";
                                    }
                                    if ($result_seAP1830['CNT'] != '0') {
                                        $rsAP1830 = "background-color: blue";
                                    }
                                    if ($result_seAP1845['CNT'] != '0') {
                                        $rsAP1845 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1900 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 19:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1900 = array();
                                $query_seAP1900 = sqlsrv_query($conn, $sql_seAP1900, $params_seAP1900);
                                $result_seAP1900 = sqlsrv_fetch_array($query_seAP1900, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1915 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 19:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1915 = array();
                                $query_seAP1915 = sqlsrv_query($conn, $sql_seAP1915, $params_seAP1915);
                                $result_seAP1915 = sqlsrv_fetch_array($query_seAP1915, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1930 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 19:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1930 = array();
                                $query_seAP1930 = sqlsrv_query($conn, $sql_seAP1930, $params_seAP1930);
                                $result_seAP1930 = sqlsrv_fetch_array($query_seAP1930, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1945 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 19:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP1945 = array();
                                $query_seAP1945 = sqlsrv_query($conn, $sql_seAP1945, $params_seAP1945);
                                $result_seAP1945 = sqlsrv_fetch_array($query_seAP1945, SQLSRV_FETCH_ASSOC);

                                $rsAP1900 = '';
                                $rsAP1915 = '';
                                $rsAP1930 = '';
                                $rsAP1945 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1900['CNT'] != '0') {
                                        $rsAP1900 = "background-color: green";
                                    }
                                    if ($result_seAP1915['CNT'] != '0') {
                                        $rsAP1915 = "background-color: green";
                                    }
                                    if ($result_seAP1930['CNT'] != '0') {
                                        $rsAP1930 = "background-color: green";
                                    }
                                    if ($result_seAP1945['CNT'] != '0') {
                                        $rsAP1945 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1900['CNT'] != '0') {
                                        $rsAP1900 = "background-color: blue";
                                    }
                                    if ($result_seAP1915['CNT'] != '0') {
                                        $rsAP1915 = "background-color: blue";
                                    }
                                    if ($result_seAP1930['CNT'] != '0') {
                                        $rsAP1930 = "background-color: blue";
                                    }
                                    if ($result_seAP1945['CNT'] != '0') {
                                        $rsAP1945 = "background-color: blue";
                                    }
                                }

                                $sql_seAP2000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 20:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2000 = array();
                                $query_seAP2000 = sqlsrv_query($conn, $sql_seAP2000, $params_seAP2000);
                                $result_seAP2000 = sqlsrv_fetch_array($query_seAP2000, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 20:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2015 = array();
                                $query_seAP2015 = sqlsrv_query($conn, $sql_seAP2015, $params_seAP2015);
                                $result_seAP2015 = sqlsrv_fetch_array($query_seAP2015, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 20:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2030 = array();
                                $query_seAP2030 = sqlsrv_query($conn, $sql_seAP2030, $params_seAP2030);
                                $result_seAP2030 = sqlsrv_fetch_array($query_seAP2030, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 20:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2045 = array();
                                $query_seAP2045 = sqlsrv_query($conn, $sql_seAP2045, $params_seAP2045);
                                $result_seAP2045 = sqlsrv_fetch_array($query_seAP2045, SQLSRV_FETCH_ASSOC);

                                $rsAP2000 = '';
                                $rsAP2015 = '';
                                $rsAP2030 = '';
                                $rsAP2045 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP2000['CNT'] != '0') {
                                        $rsAP2000 = "background-color: green";
                                    }
                                    if ($result_seAP2015['CNT'] != '0') {
                                        $rsAP2015 = "background-color: green";
                                    }
                                    if ($result_seAP2030['CNT'] != '0') {
                                        $rsAP2030 = "background-color: green";
                                    }
                                    if ($result_seAP2045['CNT'] != '0') {
                                        $rsAP2045 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP2000['CNT'] != '0') {
                                        $rsAP2000 = "background-color: blue";
                                    }
                                    if ($result_seAP2015['CNT'] != '0') {
                                        $rsAP2015 = "background-color: blue";
                                    }
                                    if ($result_seAP2030['CNT'] != '0') {
                                        $rsAP2030 = "background-color: blue";
                                    }
                                    if ($result_seAP2045['CNT'] != '0') {
                                        $rsAP2045 = "background-color: blue";
                                    }
                                }

                                $sql_seAP2100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 21:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2100 = array();
                                $query_seAP2100 = sqlsrv_query($conn, $sql_seAP2100, $params_seAP2100);
                                $result_seAP2100 = sqlsrv_fetch_array($query_seAP2100, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 21:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2115 = array();
                                $query_seAP2115 = sqlsrv_query($conn, $sql_seAP2115, $params_seAP2115);
                                $result_seAP2115 = sqlsrv_fetch_array($query_seAP2115, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 21:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2130 = array();
                                $query_seAP2130 = sqlsrv_query($conn, $sql_seAP2130, $params_seAP2130);
                                $result_seAP2130 = sqlsrv_fetch_array($query_seAP2130, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 21:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2145 = array();
                                $query_seAP2145 = sqlsrv_query($conn, $sql_seAP2145, $params_seAP2145);
                                $result_seAP2145 = sqlsrv_fetch_array($query_seAP2145, SQLSRV_FETCH_ASSOC);

                                $rsAP2100 = '';
                                $rsAP2115 = '';
                                $rsAP2130 = '';
                                $rsAP2145 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP2100['CNT'] != '0') {
                                        $rsAP2100 = "background-color: green";
                                    }
                                    if ($result_seAP2115['CNT'] != '0') {
                                        $rsAP2115 = "background-color: green";
                                    }
                                    if ($result_seAP2130['CNT'] != '0') {
                                        $rsAP2130 = "background-color: green";
                                    }
                                    if ($result_seAP2145['CNT'] != '0') {
                                        $rsAP2145 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP2100['CNT'] != '0') {
                                        $rsAP2100 = "background-color: blue";
                                    }
                                    if ($result_seAP2115['CNT'] != '0') {
                                        $rsAP2115 = "background-color: blue";
                                    }
                                    if ($result_seAP2130['CNT'] != '0') {
                                        $rsAP2130 = "background-color: blue";
                                    }
                                    if ($result_seAP2145['CNT'] != '0') {
                                        $rsAP2145 = "background-color: blue";
                                    }
                                }

                                $sql_seAP2200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 22:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2200 = array();
                                $query_seAP2200 = sqlsrv_query($conn, $sql_seAP2200, $params_seAP2200);
                                $result_seAP2200 = sqlsrv_fetch_array($query_seAP2200, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 22:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2215 = array();
                                $query_seAP2215 = sqlsrv_query($conn, $sql_seAP2215, $params_seAP2215);
                                $result_seAP2215 = sqlsrv_fetch_array($query_seAP2215, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 22:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2230 = array();
                                $query_seAP2230 = sqlsrv_query($conn, $sql_seAP2230, $params_seAP2230);
                                $result_seAP2230 = sqlsrv_fetch_array($query_seAP2230, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 22:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2245 = array();
                                $query_seAP2245 = sqlsrv_query($conn, $sql_seAP2245, $params_seAP2245);
                                $result_seAP2245 = sqlsrv_fetch_array($query_seAP2245, SQLSRV_FETCH_ASSOC);

                                $rsAP2200 = '';
                                $rsAP2215 = '';
                                $rsAP2230 = '';
                                $rsAP2245 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP2200['CNT'] != '0') {
                                        $rsAP2200 = "background-color: green";
                                    }
                                    if ($result_seAP2215['CNT'] != '0') {
                                        $rsAP2215 = "background-color: green";
                                    }
                                    if ($result_seAP2230['CNT'] != '0') {
                                        $rsAP2230 = "background-color: green";
                                    }
                                    if ($result_seAP2245['CNT'] != '0') {
                                        $rsAP2245 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP2200['CNT'] != '0') {
                                        $rsAP2200 = "background-color: blue";
                                    }
                                    if ($result_seAP2215['CNT'] != '0') {
                                        $rsAP2215 = "background-color: blue";
                                    }
                                    if ($result_seAP2230['CNT'] != '0') {
                                        $rsAP2230 = "background-color: blue";
                                    }
                                    if ($result_seAP2245['CNT'] != '0') {
                                        $rsAP2245 = "background-color: blue";
                                    }
                                }

                                $sql_seAP2300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 23:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2300 = array();
                                $query_seAP2300 = sqlsrv_query($conn, $sql_seAP2300, $params_seAP2300);
                                $result_seAP2300 = sqlsrv_fetch_array($query_seAP2300, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 23:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2315 = array();
                                $query_seAP2315 = sqlsrv_query($conn, $sql_seAP2315, $params_seAP2315);
                                $result_seAP2315 = sqlsrv_fetch_array($query_seAP2315, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 23:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2330 = array();
                                $query_seAP2330 = sqlsrv_query($conn, $sql_seAP2330, $params_seAP2330);
                                $result_seAP2330 = sqlsrv_fetch_array($query_seAP2330, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 23:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS1['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS1['RPC_SUBJECT']."'";
                                $params_seAP2345 = array();
                                $query_seAP2345 = sqlsrv_query($conn, $sql_seAP2345, $params_seAP2345);
                                $result_seAP2345 = sqlsrv_fetch_array($query_seAP2345, SQLSRV_FETCH_ASSOC);

                                $rsAP2300 = '';
                                $rsAP2315 = '';
                                $rsAP2330 = '';
                                $rsAP2345 = '';

                                if ($result_seActS1['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP2300['CNT'] != '0') {
                                        $rsAP2300 = "background-color: green";
                                    }
                                    if ($result_seAP2315['CNT'] != '0') {
                                        $rsAP2315 = "background-color: green";
                                    }
                                    if ($result_seAP2330['CNT'] != '0') {
                                        $rsAP2330 = "background-color: green";
                                    }
                                    if ($result_seAP2345['CNT'] != '0') {
                                        $rsAP2345 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP2300['CNT'] != '0') {
                                        $rsAP2300 = "background-color: blue";
                                    }
                                    if ($result_seAP2315['CNT'] != '0') {
                                        $rsAP2315 = "background-color: blue";
                                    }
                                    if ($result_seAP2330['CNT'] != '0') {
                                        $rsAP2330 = "background-color: blue";
                                    }
                                    if ($result_seAP2345['CNT'] != '0') {
                                        $rsAP2345 = "background-color: blue";
                                    }
                                }

                                if ($result_seS1['VEHICLEREGISNUMBER1'] != '') {
                                    $VEHICLEREGISNUMBERS11 = $result_seS1['VEHICLEREGISNUMBER1'] . '(1)';
                                } else {
                                    $VEHICLEREGISNUMBERS11 = '';
                                }
                                if ($result_seS1['VEHICLEREGISNUMBER2'] != '') {
                                    $VEHICLEREGISNUMBERS12 = $result_seS1['VEHICLEREGISNUMBER2'] . '(2)';
                                } else {
                                    $VEHICLEREGISNUMBERS12 = '';
                                }

                                if ($result_seS1['VEHICLEREGISNUMBER1'] == '' || $result_seS1['VEHICLEREGISNUMBER2'] == '') {
                                    $commas1 = '';
                                } else {
                                    $commas1 = ',';
                                }
                        ?>
                        <tr>
                            <td style="text-align: center;" rowspan="2"><?= $iS1 ?></td>
                            <td style="text-align: center;" rowspan="2"><?= $result_seS1['VEHICLEREGISNUMBER1']?><?php if($result_seS1['THAINAME1']!=''){echo '/';}?> <?= $result_seS1['THAINAME1'] ?></td>
                            <td style="text-align: center;" rowspan="2"><?= $result_seS1['VEHICLEREGISNUMBER2'] ?></td>
                            <td style="text-align: center;" rowspan="2"><?= $customercode1 ?></td>
                            <td style="text-align: center;" rowspan="2"><?= $result_seS1['SUBJECT'] ?></td>
                            <td style="text-align: center;" rowspan="2"><?= $result_seCauS1['DETAIL'] ?></td>
                            <td style="text-align: center;" rowspan="2"><?= $rsdrivename1 ?> <?php if($rsdrivename1!=''){echo '/';}?> <?= $rsdrivecard1 ?></td>
                            <td style="text-align: center;" rowspan="2"><?= $result_seS1['CH'] ?></td>
                            <td style="text-align: center;" rowspan="2"><?= $result_seEmpS1['CNT'] ?></td>
                            <td style="text-align: center;" rowspan="2"><?= $result_seS1['REPAIRMAN'] ?></td>
                            <td style="text-align: center;" rowspan="2"><?= $TECHNICIANEMPLOYEES1 ?></td>
                            <td style="text-align: center;">แผน</td>
                            
                            <td width="0%" style="text-align: center;<?= $rsFP0000 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0015 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0030 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0045 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0100 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0115 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0130 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0145 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0200 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0215 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0230 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0245 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0300 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0315 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0330 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0345 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0400 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0415 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0430 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0445 ?>"></td>

                            <td width="0%" style="text-align: center;<?= $rsFP0500 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0515 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0530 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0545 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0600 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0615 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0630 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0645 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0700 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0715 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0730 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0745 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0800 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0815 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0830 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0845 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0900 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0915 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0930 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP0945 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1000 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1015 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1030 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1045 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1100 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1115 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1130 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1145 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1200 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1215 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1230 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1245 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1300 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1315 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1330 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1345 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1400 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1415 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1430 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1445 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1500 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1515 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1530 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1545 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1600 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1615 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1630 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1645 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1700 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1715 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1730 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1745 ?>"></td>
                            
                            <td width="0%" style="text-align: center;<?= $rsFP1800 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1815 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1830 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1845 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1900 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1915 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1930 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP1945 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2000 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2015 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2030 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2045 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2100 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2115 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2130 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2145 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2200 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2215 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2230 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2245 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2300 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2315 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2330 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsFP2345 ?>"></td>
                            
                            <td style="text-align: center;"><?= $hours1 . ' : ' . $minutes1 ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">ทำจริง</td>
                            
                            <td width="0%" style="text-align: center;<?= $rsAP0000 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0015 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0030 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0045 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0100 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0115 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0130 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0145 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0200 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0215 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0230 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0245 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0300 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0315 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0330 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0345 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0400 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0415 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0430 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0445 ?>"></td>

                            <td width="0%" style="text-align: center;<?= $rsAP0500 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0515 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0530 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0545 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0600 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0615 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0630 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0645 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0700 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0715 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0730 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0745 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0800 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0815 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0830 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0845 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0900 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0915 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0930 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP0945 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1000 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1015 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1030 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1045 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1100 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1115 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1130 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1145 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1200 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1215 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1230 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1245 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1300 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1315 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1330 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1345 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1400 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1415 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1430 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1445 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1500 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1515 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1530 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1545 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1600 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1615 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1630 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1645 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1700 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1715 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1730 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1745 ?>"></td>
                            
                            <td width="0%" style="text-align: center;<?= $rsAP1800 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1815 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1830 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1845 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1900 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1915 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1930 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP1945 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2000 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2015 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2030 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2045 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2100 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2115 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2130 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2145 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2200 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2215 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2230 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2245 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2300 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2315 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2330 ?>"></td>
                            <td width="0%" style="text-align: center;<?= $rsAP2345 ?>"></td>

                            <?php if ($result_seHouracS1['OPENDATE'] == '') { ?>
                                <td style="text-align: center;"></td>
                            <?php } else { ?>
                                <td style="text-align: center;"><?= $houracs1 . ' : ' . $minuteacs1 ?></td>
                            <?php } ?>
                        </tr>
                        <?php $iS1++; } ?>
                    </tbody>
                </table>
                <!-- <br> -->
            <!-- S2 ############################################################################################################################## -->
                <?php
                    $sql_carcount2 = "SELECT COUNT(DISTINCT	a.RPRQ_REGISHEAD) CARCOUNT
                    FROM [dbo].[REPAIRREQUEST] a
                    INNER JOIN [dbo].[REPAIRCAUSE] b ON a.RPRQ_CODE=b.RPRQ_CODE
                    WHERE b.RPC_INCARDATE = '$txt_daterepair' AND a.RPRQ_STATUS = 'Y'
                    AND SUBSTRING(b.RPC_AREA,1,2)='S2' AND (a.RPRQ_REGISHEAD != '' OR a.RPRQ_REGISTAIL != '')  AND a.RPRQ_AREA='$AREA'";
                    $params_carcount2 = array();
                    $query_carcount2 = sqlsrv_query($conn, $sql_carcount2, $params_carcount2);
                    $result_carcount2 = sqlsrv_fetch_array($query_carcount2, SQLSRV_FETCH_ASSOC);
                    $CARCOUNT2=$result_carcount2["CARCOUNT"];
                    
                    $sql_rpmcount2 = "SELECT COUNT(DISTINCT C.RPME_NAME) REPAIRMAN
                    FROM [dbo].[REPAIRREQUEST] A
                    INNER JOIN [dbo].[REPAIRCAUSE] B ON A.RPRQ_CODE=B.RPRQ_CODE
                    LEFT JOIN REPAIRMANEMP C ON A.RPRQ_CODE = C.RPRQ_CODE
                    WHERE b.RPC_INCARDATE = '$txt_daterepair' AND a.RPRQ_STATUS = 'Y'
                    AND SUBSTRING(B.RPC_AREA,1,2)='S2' AND (A.RPRQ_REGISHEAD != '' OR A.RPRQ_REGISTAIL != '')  AND A.RPRQ_AREA='$AREA'
                    AND C.RPC_SUBJECT IN('TU','EL')";
                    $params_rpmcount2 = array();
                    $options_rpmcount2 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                    $query_rpmcount2 = sqlsrv_query($conn, $sql_rpmcount2, $params_rpmcount2, $options_rpmcount2);
                    $result_rpmcount2 = sqlsrv_fetch_array($query_rpmcount2, SQLSRV_FETCH_ASSOC);
                    $REPAIRMAN2=$result_rpmcount2["REPAIRMAN"];                                        
                ?>
                <?php if($AREA=='AMT'){ ?>
                    <h3><b>S2</b>&nbsp;&nbsp;<small>รถเข้าซ่อมจำนวน <?=$CARCOUNT2?> คัน และช่างซ่อมจำนวน <?=$REPAIRMAN2?> คน</small></h3>
                <?php }else if($AREA=='GW'){ ?>
                    <h3><b>G2</b>&nbsp;&nbsp;<small>รถเข้าซ่อมจำนวน <?=$CARCOUNT2?> คัน และช่างซ่อมจำนวน <?=$REPAIRMAN2?> คน</small></h3>
                <?php } ?>
                <table class="border" border="1" style="width: 100%;">
                    <thead>
                        <!-- <tr>
                            <th class="border" border="1" colspan="70" style="text-align: center;">S2</th>
                        </tr> -->
                        <tr>
                            <th class="border" border="1" width="2%" rowspan="2" style="text-align: center;">ที่</th>
                            <th class="border" border="1" width="5%" rowspan="2" style="text-align: center;">ทะเบียน(หัว)/ชื่อรถ</th>
                            <th class="border" border="1" width="5%" rowspan="2" style="text-align: center;">ทะเบียน(หาง)</th>
                            <th class="border" border="1" width="5%" rowspan="2" style="text-align: center;">สายงาน</th>
                            <th class="border" border="1" width="6%" rowspan="2" style="text-align: center;">ลักษณะงานซ่อม</th>
                            <th class="border" border="1" width="7%" rowspan="2" style="text-align: center;">รายละเอียด</th>
                            <th class="border" border="1" width="6%" rowspan="2" style="text-align: center;">ช่างผู้ขับรถเข้าซ่อม/เลขใบขับขี่</th>
                            <th class="border" border="1" width="3%" rowspan="2" style="text-align: center;">ช่องซ่อม</th>
                            <th class="border" border="1" width="3%" rowspan="2" style="text-align: center;">จำนวนช่าง</th>
                            <th class="border" border="1" width="7%" rowspan="2" style="text-align: center;">ผู้รับผิดชอบ</th>
                            <th class="border" border="1" width="7%" rowspan="2" style="text-align: center;">ผู้ตรวจสอบ/SA</th>
                            <th class="border" border="1" width="3%" rowspan="2" style="text-align: center;">ชั่วโมงทำงาน</th>
                            <th class="border" border="1" style="text-align: center;width: 25%" colspan="96">เวลาในการปฎิบัติงาน</th>
                            <th class="border" border="1" width="3%" rowspan="2" style="text-align: center;">รวมชั่วโมง</th>
                        </tr>
                        <tr>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">0</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">1</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">2</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">3</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">4</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">5</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">6</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">7</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">8</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">9</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">10</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">11</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">12</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">13</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">14</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">15</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">16</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">17</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">18</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">19</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">20</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">21</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">22</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">23</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // #################################################### HEADS2 ####################################################
                            $iS2 = 1;
                            $sql_seS2 = "SELECT 
                                a.RPRQ_ASSIGN_BY,
                                c.nameT NAMEASSIGN,
                                a.RPRQ_REQUESTBY_SQ,
                                a.RPRQ_ID REPAIRPLANID,
                                a.RPRQ_CODE,
                                a.RPRQ_REGISHEAD VEHICLEREGISNUMBER1,
                                a.RPRQ_CARNAMEHEAD THAINAME1,
                                a.RPRQ_REGISTAIL VEHICLEREGISNUMBER2,
                                a.RPRQ_CARNAMETAIL THAINAME2,
                                a.RPRQ_LINEOFWORK CUSTOMER,
                                a.RPRQ_COMPANYCASH COMPANYPAYMENT,
                                'S2/'+SUBSTRING(b.RPC_AREA,4,1) AS CH,
                                CASE
                                    WHEN b.RPC_SUBJECT = 'EL' THEN 'ระบบไฟ'
                                    WHEN b.RPC_SUBJECT = 'TU' THEN 'ยาง ช่วงล่าง'
                                    WHEN b.RPC_SUBJECT = 'BD' THEN 'โครงสร้าง'
                                    WHEN b.RPC_SUBJECT = 'EG' THEN 'เครื่องยนต์'
                                    WHEN b.RPC_SUBJECT = 'AC' THEN 'อุปกรณ์ประจำรถ'
                                    ELSE ''
                                END SUBJECT,
                                b.RPC_SUBJECT,
                                a.RPRQ_STATUSREQUEST REPAIRSTATUS,
                                STUFF((SELECT DISTINCT ','+ CAST(RPME_NAME AS VARCHAR) FROM REPAIRMANEMP WHERE RPRQ_CODE = a.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT FOR XML PATH(''), TYPE).value('.','VARCHAR(max)'), 1, 1, '') AS REPAIRMAN
                                FROM [dbo].[REPAIRREQUEST] a
                                INNER JOIN [dbo].[REPAIRCAUSE] b ON a.RPRQ_CODE=b.RPRQ_CODE
                                LEFT JOIN vwEMPLOYEE c ON c.PersonCode = a.RPRQ_ASSIGN_BY
                                WHERE b.RPC_INCARDATE = '$txt_daterepair' AND a.RPRQ_STATUS = 'Y'
                                AND SUBSTRING(b.RPC_AREA,1,2)='S2' AND (a.RPRQ_REGISHEAD != '' OR a.RPRQ_REGISTAIL != '')  AND a.RPRQ_AREA='$AREA'
                                GROUP BY a.RPRQ_ASSIGN_BY,c.nameT,a.RPRQ_REQUESTBY_SQ,a.RPRQ_ID,a.RPRQ_CODE,a.RPRQ_REGISHEAD,a.RPRQ_CARNAMEHEAD,a.RPRQ_REGISTAIL,a.RPRQ_CARNAMETAIL,a.RPRQ_LINEOFWORK,a.RPRQ_COMPANYCASH,b.RPC_AREA,b.RPC_SUBJECT,a.RPRQ_STATUSREQUEST";
                            $params_seS2 = array();
                            $query_seS2 = sqlsrv_query($conn, $sql_seS2, $params_seS2);
                            while ($result_seS2 = sqlsrv_fetch_array($query_seS2, SQLSRV_FETCH_ASSOC)) {
                                if ($result_seS2['CUSTOMER'] == '' ) {
                                    $customercode2 = $result_seS2['COMPANYPAYMENT'];
                                }else {
                                    $customercode2 = $result_seS2['CUSTOMER'];
                                }

                                $sql_seEmpS2 = "SELECT COUNT(*) AS CNT FROM [dbo].[REPAIRMANEMP] WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seEmpS2 = array();
                                $query_seEmpS2 = sqlsrv_query($conn, $sql_seEmpS2, $params_seEmpS2);
                                $result_seEmpS2 = sqlsrv_fetch_array($query_seEmpS2, SQLSRV_FETCH_ASSOC);

                                $sql_seCauS2 = "SELECT RPC_DETAIL DETAIL FROM [dbo].[REPAIRCAUSE] WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seCauS2 = array();
                                $query_seCauS2 = sqlsrv_query($conn, $sql_seCauS2, $params_seEmpS2);
                                $result_seCauS2 = sqlsrv_fetch_array($query_seCauS2, SQLSRV_FETCH_ASSOC);

                                $sql_seActS2 = "SELECT RPATTM_GROUP FROM [dbo].[REPAIRACTUAL_TIME] WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS2['RPC_SUBJECT']."' ORDER BY RPATTM_PROCESS DESC";
                                $params_seActS2 = array();
                                $query_seActS2 = sqlsrv_query($conn, $sql_seActS2, $params_seActS2);
                                $result_seActS2 = sqlsrv_fetch_array($query_seActS2, SQLSRV_FETCH_ASSOC);

                                $sql_seHourS2 = "SELECT 
                                    CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AS 'CARINPUTDATE',
                                    CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120) AS 'COMPLETEDDATE'
                                    FROM [dbo].[REPAIRCAUSE] WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS2['RPC_SUBJECT']."' AND (RPC_REMARK = '' OR RPC_REMARK IS NULL)";
                                $params_seHourS2 = array();
                                $query_seHourS2 = sqlsrv_query($conn, $sql_seHourS2, $params_seHourS2);
                                $result_seHourS2 = sqlsrv_fetch_array($query_seHourS2, SQLSRV_FETCH_ASSOC);

                                $remains2 = intval(strtotime($result_seHourS2['COMPLETEDDATE']) - strtotime($result_seHourS2['CARINPUTDATE']));
                                $wans2 = floor($remains2 / 86400);
                                $l_wans2 = $remains2 % 86400;
                                $hours2 = floor($l_wans2 / 3600);
                                $l_hours2 = $l_wans2 % 3600;
                                $minutes2 = floor($l_hours2 / 60);
                                $seconds2 = $l_hours2 % 60;
                                // echo $result_seS2['RPRQ_CODE']."<br>";
                                // echo $result_seS2['RPC_SUBJECT']."<br>";
                                // echo $result_seHourS2['CARINPUTDATE']."<br>";
                                // echo $result_seHourS2['COMPLETEDDATE']."<br>";
                                // echo "ผ่านมาแล้ว ".$wan." วัน ".$hour." ชั่วโมง ".$minute." นาที ".$second." วินาที";

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    $sql_seHouracS2 = "SELECT DISTINCT A.RPRQ_CODE,A.RPC_SUBJECT,
                                        (SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME	WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'START') AS 'OPENDATE',
                                        (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') AS 'CLOSEDATE'
                                        FROM REPAIRACTUAL_TIME A WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS2['RPC_SUBJECT']."'";
                                    $params_seHouracS2 = array();
                                    $query_seHouracS2 = sqlsrv_query($conn, $sql_seHouracS2, $params_seHouracS2);
                                    $result_seHouracS2 = sqlsrv_fetch_array($query_seHouracS2, SQLSRV_FETCH_ASSOC);
                                
                                    $remainacs2 = intval(strtotime($result_seHouracS2['CLOSEDATE']) - strtotime($result_seHouracS2['OPENDATE']));
                                    $wanacs2 = floor($remainacs2 / 86400);
                                    $l_wanacs2 = $remainacs2 % 86400;
                                    $houracs2 = floor($l_wanacs2 / 3600);
                                    $l_houracs2 = $l_wanacs2 % 3600;
                                    $minuteacs2 = floor($l_houracs2 / 60);
                                    $secondacs2 = $l_houracs2 % 60;
                                    // echo "ผ่านมาแล้ว ".$wanacs2." วัน ".$houracs2." ชั่วโมง ".$minuteacs2." นาที ".$secondacs2." วินาที"."<br>";
                                } else {
                                    $sql_seHouracS2 = "SELECT A.RPRQ_CODE,A.RPC_SUBJECT,
                                        (SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME	WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'START') AS 'OPENDATE',
                                        CONVERT(VARCHAR(10), GETDATE(), 121) + ' '  + CONVERT(VARCHAR(5), GETDATE(), 14) AS 'CLOSEDATE' 
                                        FROM REPAIRACTUAL_TIME A WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS2['RPC_SUBJECT']."'";
                                    $params_seHouracS2 = array();
                                    $query_seHouracS2 = sqlsrv_query($conn, $sql_seHouracS2, $params_seHouracS2);
                                    $result_seHouracS2 = sqlsrv_fetch_array($query_seHouracS2, SQLSRV_FETCH_ASSOC);
                                    
                                    $remainacs2 = intval(strtotime($result_seHouracS2['CLOSEDATE']) - strtotime($result_seHouracS2['OPENDATE']));
                                    $wanacs2 = floor($remainacs2 / 86400);
                                    $l_wanacs2 = $remainacs2 % 86400;
                                    $houracs2 = floor($l_wanacs2 / 3600);
                                    $l_houracs2 = $l_wanacs2 % 3600;
                                    $minuteacs2 = floor($l_houracs2 / 60);
                                    $secondacs2 = $l_houracs2 % 60;
                                }

                                $cntemps2 += $result_seEmpS2['CNT'];

                                $dateinput = $txt_daterepair;

                                $DETAIL2=$result_seCauS2['DETAIL'];
                                $DETAILCUT2 = explode("-", $DETAIL2);
                                $DETAILX20 = $DETAILCUT2[0];
                                $DETAILX21 = $DETAILCUT2[1];
                                $DETAILX22 = $DETAILCUT2[2];
                                
                                if($DETAILX20=="PM"){
                                    $sql_chketmrpmdrive2 = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPMD_ZONE = 'S2'";
                                }else{
                                    $sql_chketmrpmdrive2 = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."'";
                                }
                                $query_chketmrpmdrive2 = sqlsrv_query($conn, $sql_chketmrpmdrive2);
                                $result_chketmrpmdrive2 = sqlsrv_fetch_array($query_chketmrpmdrive2, SQLSRV_FETCH_ASSOC);
                                $rsdrivename2 = $result_chketmrpmdrive2["RPMD_NAME"];
                                $rsdrivecard2 = $result_chketmrpmdrive2["RPMD_CARLICENCE"];

                                if($DETAILX20=="PM"){
                                    $TECHNICIANEMPLOYEES2 = $result_seS2['NAMEASSIGN'];
                                }else {
                                    $TECHNICIANEMPLOYEES2 = $result_seS2['RPRQ_REQUESTBY_SQ'];
                                }
                        // #################################################### PLANS2 ####################################################
                                
                                $sql_seFP0000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0000 = array();
                                $query_seFP0000 = sqlsrv_query($conn, $sql_seFP0000, $params_seFP0000);
                                $result_seFP0000 = sqlsrv_fetch_array($query_seFP0000, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0015 = array();
                                $query_seFP0015 = sqlsrv_query($conn, $sql_seFP0015, $params_seFP0015);
                                $result_seFP0015 = sqlsrv_fetch_array($query_seFP0015, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0030 = array();
                                $query_seFP0030 = sqlsrv_query($conn, $sql_seFP0030, $params_seFP0030);
                                $result_seFP0030 = sqlsrv_fetch_array($query_seFP0030, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0045 = array();
                                $query_seFP0045 = sqlsrv_query($conn, $sql_seFP0045, $params_seFP0045);
                                $result_seFP0045 = sqlsrv_fetch_array($query_seFP0045, SQLSRV_FETCH_ASSOC);

                                $rsFP0000 = '';
                                $rsFP0015 = '';
                                $rsFP0030 = '';
                                $rsFP0045 = '';

                                if ($result_seFP0000['CNT'] != '0') {
                                    $rsFP0000 = "background-color: yellow";
                                }
                                if ($result_seFP0015['CNT'] != '0') {
                                    $rsFP0015 = "background-color: yellow";
                                }
                                if ($result_seFP0030['CNT'] != '0') {
                                    $rsFP0030 = "background-color: yellow";
                                }
                                if ($result_seFP0045['CNT'] != '0') {
                                    $rsFP0045 = "background-color: yellow";
                                }
                                
                                $sql_seFP0100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0100 = array();
                                $query_seFP0100 = sqlsrv_query($conn, $sql_seFP0100, $params_seFP0100);
                                $result_seFP0100 = sqlsrv_fetch_array($query_seFP0100, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0115 = array();
                                $query_seFP0115 = sqlsrv_query($conn, $sql_seFP0115, $params_seFP0115);
                                $result_seFP0115 = sqlsrv_fetch_array($query_seFP0115, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0130 = array();
                                $query_seFP0130 = sqlsrv_query($conn, $sql_seFP0130, $params_seFP0130);
                                $result_seFP0130 = sqlsrv_fetch_array($query_seFP0130, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0145 = array();
                                $query_seFP0145 = sqlsrv_query($conn, $sql_seFP0145, $params_seFP0145);
                                $result_seFP0145 = sqlsrv_fetch_array($query_seFP0145, SQLSRV_FETCH_ASSOC);

                                $rsFP0100 = '';
                                $rsFP0115 = '';
                                $rsFP0130 = '';
                                $rsFP0145 = '';

                                if ($result_seFP0100['CNT'] != '0') {
                                    $rsFP0100 = "background-color: yellow";
                                }
                                if ($result_seFP0115['CNT'] != '0') {
                                    $rsFP0115 = "background-color: yellow";
                                }
                                if ($result_seFP0130['CNT'] != '0') {
                                    $rsFP0130 = "background-color: yellow";
                                }
                                if ($result_seFP0145['CNT'] != '0') {
                                    $rsFP0145 = "background-color: yellow";
                                }
                                
                                $sql_seFP0200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0200 = array();
                                $query_seFP0200 = sqlsrv_query($conn, $sql_seFP0200, $params_seFP0200);
                                $result_seFP0200 = sqlsrv_fetch_array($query_seFP0200, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0215 = array();
                                $query_seFP0215 = sqlsrv_query($conn, $sql_seFP0215, $params_seFP0215);
                                $result_seFP0215 = sqlsrv_fetch_array($query_seFP0215, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0230 = array();
                                $query_seFP0230 = sqlsrv_query($conn, $sql_seFP0230, $params_seFP0230);
                                $result_seFP0230 = sqlsrv_fetch_array($query_seFP0230, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0245 = array();
                                $query_seFP0245 = sqlsrv_query($conn, $sql_seFP0245, $params_seFP0245);
                                $result_seFP0245 = sqlsrv_fetch_array($query_seFP0245, SQLSRV_FETCH_ASSOC);

                                $rsFP0200 = '';
                                $rsFP0215 = '';
                                $rsFP0230 = '';
                                $rsFP0245 = '';

                                if ($result_seFP0200['CNT'] != '0') {
                                    $rsFP0200 = "background-color: yellow";
                                }
                                if ($result_seFP0215['CNT'] != '0') {
                                    $rsFP0215 = "background-color: yellow";
                                }
                                if ($result_seFP0230['CNT'] != '0') {
                                    $rsFP0230 = "background-color: yellow";
                                }
                                if ($result_seFP0245['CNT'] != '0') {
                                    $rsFP0245 = "background-color: yellow";
                                }
                                
                                $sql_seFP0300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0300 = array();
                                $query_seFP0300 = sqlsrv_query($conn, $sql_seFP0300, $params_seFP0300);
                                $result_seFP0300 = sqlsrv_fetch_array($query_seFP0300, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0315 = array();
                                $query_seFP0315 = sqlsrv_query($conn, $sql_seFP0315, $params_seFP0315);
                                $result_seFP0315 = sqlsrv_fetch_array($query_seFP0315, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0330 = array();
                                $query_seFP0330 = sqlsrv_query($conn, $sql_seFP0330, $params_seFP0330);
                                $result_seFP0330 = sqlsrv_fetch_array($query_seFP0330, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0345 = array();
                                $query_seFP0345 = sqlsrv_query($conn, $sql_seFP0345, $params_seFP0345);
                                $result_seFP0345 = sqlsrv_fetch_array($query_seFP0345, SQLSRV_FETCH_ASSOC);

                                $rsFP0300 = '';
                                $rsFP0315 = '';
                                $rsFP0330 = '';
                                $rsFP0345 = '';

                                if ($result_seFP0300['CNT'] != '0') {
                                    $rsFP0300 = "background-color: yellow";
                                }
                                if ($result_seFP0315['CNT'] != '0') {
                                    $rsFP0315 = "background-color: yellow";
                                }
                                if ($result_seFP0330['CNT'] != '0') {
                                    $rsFP0330 = "background-color: yellow";
                                }
                                if ($result_seFP0345['CNT'] != '0') {
                                    $rsFP0345 = "background-color: yellow";
                                }
                                
                                $sql_seFP0400 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0400 = array();
                                $query_seFP0400 = sqlsrv_query($conn, $sql_seFP0400, $params_seFP0400);
                                $result_seFP0400 = sqlsrv_fetch_array($query_seFP0400, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0415 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0415 = array();
                                $query_seFP0415 = sqlsrv_query($conn, $sql_seFP0415, $params_seFP0415);
                                $result_seFP0415 = sqlsrv_fetch_array($query_seFP0415, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0430 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0430 = array();
                                $query_seFP0430 = sqlsrv_query($conn, $sql_seFP0430, $params_seFP0430);
                                $result_seFP0430 = sqlsrv_fetch_array($query_seFP0430, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0445 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0445 = array();
                                $query_seFP0445 = sqlsrv_query($conn, $sql_seFP0445, $params_seFP0445);
                                $result_seFP0445 = sqlsrv_fetch_array($query_seFP0445, SQLSRV_FETCH_ASSOC);

                                $rsFP0400 = '';
                                $rsFP0415 = '';
                                $rsFP0430 = '';
                                $rsFP0445 = '';

                                if ($result_seFP0400['CNT'] != '0') {
                                    $rsFP0400 = "background-color: yellow";
                                }
                                if ($result_seFP0415['CNT'] != '0') {
                                    $rsFP0415 = "background-color: yellow";
                                }
                                if ($result_seFP0430['CNT'] != '0') {
                                    $rsFP0430 = "background-color: yellow";
                                }
                                if ($result_seFP0445['CNT'] != '0') {
                                    $rsFP0445 = "background-color: yellow";
                                }
                                
                                $sql_seFP0500 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0500 = array();
                                $query_seFP0500 = sqlsrv_query($conn, $sql_seFP0500, $params_seFP0500);
                                $result_seFP0500 = sqlsrv_fetch_array($query_seFP0500, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0515 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0515 = array();
                                $query_seFP0515 = sqlsrv_query($conn, $sql_seFP0515, $params_seFP0515);
                                $result_seFP0515 = sqlsrv_fetch_array($query_seFP0515, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0530 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0530 = array();
                                $query_seFP0530 = sqlsrv_query($conn, $sql_seFP0530, $params_seFP0530);
                                $result_seFP0530 = sqlsrv_fetch_array($query_seFP0530, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0545 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0545 = array();
                                $query_seFP0545 = sqlsrv_query($conn, $sql_seFP0545, $params_seFP0545);
                                $result_seFP0545 = sqlsrv_fetch_array($query_seFP0545, SQLSRV_FETCH_ASSOC);

                                $rsFP0500 = '';
                                $rsFP0515 = '';
                                $rsFP0530 = '';
                                $rsFP0545 = '';

                                if ($result_seFP0500['CNT'] != '0') {
                                    $rsFP0500 = "background-color: yellow";
                                }
                                if ($result_seFP0515['CNT'] != '0') {
                                    $rsFP0515 = "background-color: yellow";
                                }
                                if ($result_seFP0530['CNT'] != '0') {
                                    $rsFP0530 = "background-color: yellow";
                                }
                                if ($result_seFP0545['CNT'] != '0') {
                                    $rsFP0545 = "background-color: yellow";
                                }

                                $sql_seFP0600 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0600 = array();
                                $query_seFP0600 = sqlsrv_query($conn, $sql_seFP0600, $params_seFP0600);
                                $result_seFP0600 = sqlsrv_fetch_array($query_seFP0600, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0615 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0615 = array();
                                $query_seFP0615 = sqlsrv_query($conn, $sql_seFP0615, $params_seFP0615);
                                $result_seFP0615 = sqlsrv_fetch_array($query_seFP0615, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0630 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0630 = array();
                                $query_seFP0630 = sqlsrv_query($conn, $sql_seFP0630, $params_seFP0630);
                                $result_seFP0630 = sqlsrv_fetch_array($query_seFP0630, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0645 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0645 = array();
                                $query_seFP0645 = sqlsrv_query($conn, $sql_seFP0645, $params_seFP0645);
                                $result_seFP0645 = sqlsrv_fetch_array($query_seFP0645, SQLSRV_FETCH_ASSOC);

                                $rsFP0600 = '';
                                $rsFP0615 = '';
                                $rsFP0630 = '';
                                $rsFP0645 = '';

                                if ($result_seFP0600['CNT'] != '0') {
                                    $rsFP0600 = "background-color: yellow";
                                }
                                if ($result_seFP0615['CNT'] != '0') {
                                    $rsFP0615 = "background-color: yellow";
                                }
                                if ($result_seFP0630['CNT'] != '0') {
                                    $rsFP0630 = "background-color: yellow";
                                }
                                if ($result_seFP0645['CNT'] != '0') {
                                    $rsFP0645 = "background-color: yellow";
                                }

                                $sql_seFP0700 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0700 = array();
                                $query_seFP0700 = sqlsrv_query($conn, $sql_seFP0700, $params_seFP0700);
                                $result_seFP0700 = sqlsrv_fetch_array($query_seFP0700, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0715 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0715 = array();
                                $query_seFP0715 = sqlsrv_query($conn, $sql_seFP0715, $params_seFP0715);
                                $result_seFP0715 = sqlsrv_fetch_array($query_seFP0715, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0730 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0730 = array();
                                $query_seFP0730 = sqlsrv_query($conn, $sql_seFP0730, $params_seFP0730);
                                $result_seFP0730 = sqlsrv_fetch_array($query_seFP0730, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0745 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0745 = array();
                                $query_seFP0745 = sqlsrv_query($conn, $sql_seFP0745, $params_seFP0745);
                                $result_seFP0745 = sqlsrv_fetch_array($query_seFP0745, SQLSRV_FETCH_ASSOC);

                                $rsFP0700 = '';
                                $rsFP0715 = '';
                                $rsFP0730 = '';
                                $rsFP0745 = '';

                                if ($result_seFP0700['CNT'] != '0') {
                                    $rsFP0700 = "background-color: yellow";
                                }
                                if ($result_seFP0715['CNT'] != '0') {
                                    $rsFP0715 = "background-color: yellow";
                                }
                                if ($result_seFP0730['CNT'] != '0') {
                                    $rsFP0730 = "background-color: yellow";
                                }
                                if ($result_seFP0745['CNT'] != '0') {
                                    $rsFP0745 = "background-color: yellow";
                                }

                                $sql_seFP0800 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0800 = array();
                                $query_seFP0800 = sqlsrv_query($conn, $sql_seFP0800, $params_seFP0800);
                                $result_seFP0800 = sqlsrv_fetch_array($query_seFP0800, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0815 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0815 = array();
                                $query_seFP0815 = sqlsrv_query($conn, $sql_seFP0815, $params_seFP0815);
                                $result_seFP0815 = sqlsrv_fetch_array($query_seFP0815, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0830 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0830 = array();
                                $query_seFP0830 = sqlsrv_query($conn, $sql_seFP0830, $params_seFP0830);
                                $result_seFP0830 = sqlsrv_fetch_array($query_seFP0830, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0845 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0845 = array();
                                $query_seFP0845 = sqlsrv_query($conn, $sql_seFP0845, $params_seFP0845);
                                $result_seFP0845 = sqlsrv_fetch_array($query_seFP0845, SQLSRV_FETCH_ASSOC);

                                $rsFP0800 = '';
                                $rsFP0815 = '';
                                $rsFP0830 = '';
                                $rsFP0845 = '';

                                if ($result_seFP0800['CNT'] != '0') {
                                    $rsFP0800 = "background-color: yellow";
                                }
                                if ($result_seFP0815['CNT'] != '0') {
                                    $rsFP0815 = "background-color: yellow";
                                }
                                if ($result_seFP0830['CNT'] != '0') {
                                    $rsFP0830 = "background-color: yellow";
                                }
                                if ($result_seFP0845['CNT'] != '0') {
                                    $rsFP0845 = "background-color: yellow";
                                }

                                $sql_seFP0900 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0900 = array();
                                $query_seFP0900 = sqlsrv_query($conn, $sql_seFP0900, $params_seFP0900);
                                $result_seFP0900 = sqlsrv_fetch_array($query_seFP0900, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0915 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0915 = array();
                                $query_seFP0915 = sqlsrv_query($conn, $sql_seFP0915, $params_seFP0915);
                                $result_seFP0915 = sqlsrv_fetch_array($query_seFP0915, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0930 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0930 = array();
                                $query_seFP0930 = sqlsrv_query($conn, $sql_seFP0930, $params_seFP0930);
                                $result_seFP0930 = sqlsrv_fetch_array($query_seFP0930, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0945 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0945 = array();
                                $query_seFP0945 = sqlsrv_query($conn, $sql_seFP0945, $params_seFP0945);
                                $result_seFP0945 = sqlsrv_fetch_array($query_seFP0945, SQLSRV_FETCH_ASSOC);

                                $rsFP0900 = '';
                                $rsFP0915 = '';
                                $rsFP0930 = '';
                                $rsFP0945 = '';

                                if ($result_seFP0900['CNT'] != '0') {
                                    $rsFP0900 = "background-color: yellow";
                                }
                                if ($result_seFP0915['CNT'] != '0') {
                                    $rsFP0915 = "background-color: yellow";
                                }
                                if ($result_seFP0930['CNT'] != '0') {
                                    $rsFP0930 = "background-color: yellow";
                                }
                                if ($result_seFP0945['CNT'] != '0') {
                                    $rsFP0945 = "background-color: yellow";
                                }

                                $sql_seFP1000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1000 = array();
                                $query_seFP1000 = sqlsrv_query($conn, $sql_seFP1000, $params_seFP1000);
                                $result_seFP1000 = sqlsrv_fetch_array($query_seFP1000, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1015 = array();
                                $query_seFP1015 = sqlsrv_query($conn, $sql_seFP1015, $params_seFP1015);
                                $result_seFP1015 = sqlsrv_fetch_array($query_seFP1015, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1030 = array();
                                $query_seFP1030 = sqlsrv_query($conn, $sql_seFP1030, $params_seFP1030);
                                $result_seFP1030 = sqlsrv_fetch_array($query_seFP1030, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1045 = array();
                                $query_seFP1045 = sqlsrv_query($conn, $sql_seFP1045, $params_seFP1045);
                                $result_seFP1045 = sqlsrv_fetch_array($query_seFP1045, SQLSRV_FETCH_ASSOC);

                                $rsFP1000 = '';
                                $rsFP1015 = '';
                                $rsFP1030 = '';
                                $rsFP1045 = '';

                                if ($result_seFP1000['CNT'] != '0') {
                                    $rsFP1000 = "background-color: yellow";
                                }
                                if ($result_seFP1015['CNT'] != '0') {
                                    $rsFP1015 = "background-color: yellow";
                                }
                                if ($result_seFP1030['CNT'] != '0') {
                                    $rsFP1030 = "background-color: yellow";
                                }
                                if ($result_seFP1045['CNT'] != '0') {
                                    $rsFP1045 = "background-color: yellow";
                                }

                                $sql_seFP1100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1100 = array();
                                $query_seFP1100 = sqlsrv_query($conn, $sql_seFP1100, $params_seFP1100);
                                $result_seFP1100 = sqlsrv_fetch_array($query_seFP1100, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1115 = array();
                                $query_seFP1115 = sqlsrv_query($conn, $sql_seFP1115, $params_seFP1115);
                                $result_seFP1115 = sqlsrv_fetch_array($query_seFP1115, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1130 = array();
                                $query_seFP1130 = sqlsrv_query($conn, $sql_seFP1130, $params_seFP1130);
                                $result_seFP1130 = sqlsrv_fetch_array($query_seFP1130, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1145 = array();
                                $query_seFP1145 = sqlsrv_query($conn, $sql_seFP1145, $params_seFP1145);
                                $result_seFP1145 = sqlsrv_fetch_array($query_seFP1145, SQLSRV_FETCH_ASSOC);

                                $rsFP1100 = '';
                                $rsFP1115 = '';
                                $rsFP1130 = '';
                                $rsFP1145 = '';

                                if ($result_seFP1100['CNT'] != '0') {
                                    $rsFP1100 = "background-color: yellow";
                                }
                                if ($result_seFP1115['CNT'] != '0') {
                                    $rsFP1115 = "background-color: yellow";
                                }
                                if ($result_seFP1130['CNT'] != '0') {
                                    $rsFP1130 = "background-color: yellow";
                                }
                                if ($result_seFP1145['CNT'] != '0') {
                                    $rsFP1145 = "background-color: yellow";
                                }

                                $sql_seFP1200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1200 = array();
                                $query_seFP1200 = sqlsrv_query($conn, $sql_seFP1200, $params_seFP1200);
                                $result_seFP1200 = sqlsrv_fetch_array($query_seFP1200, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1215 = array();
                                $query_seFP1215 = sqlsrv_query($conn, $sql_seFP1215, $params_seFP1215);
                                $result_seFP1215 = sqlsrv_fetch_array($query_seFP1215, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1230 = array();
                                $query_seFP1230 = sqlsrv_query($conn, $sql_seFP1230, $params_seFP1230);
                                $result_seFP1230 = sqlsrv_fetch_array($query_seFP1230, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1245 = array();
                                $query_seFP1245 = sqlsrv_query($conn, $sql_seFP1245, $params_seFP1245);
                                $result_seFP1245 = sqlsrv_fetch_array($query_seFP1245, SQLSRV_FETCH_ASSOC);

                                $rsFP1200 = '';
                                $rsFP1215 = '';
                                $rsFP1230 = '';
                                $rsFP1245 = '';

                                if ($result_seFP1200['CNT'] != '0') {
                                    $rsFP1200 = "background-color: yellow";
                                }
                                if ($result_seFP1215['CNT'] != '0') {
                                    $rsFP1215 = "background-color: yellow";
                                }
                                if ($result_seFP1230['CNT'] != '0') {
                                    $rsFP1230 = "background-color: yellow";
                                }
                                if ($result_seFP1245['CNT'] != '0') {
                                    $rsFP1245 = "background-color: yellow";
                                }

                                $sql_seFP1300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1300 = array();
                                $query_seFP1300 = sqlsrv_query($conn, $sql_seFP1300, $params_seFP1300);
                                $result_seFP1300 = sqlsrv_fetch_array($query_seFP1300, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1315 = array();
                                $query_seFP1315 = sqlsrv_query($conn, $sql_seFP1315, $params_seFP1315);
                                $result_seFP1315 = sqlsrv_fetch_array($query_seFP1315, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1330 = array();
                                $query_seFP1330 = sqlsrv_query($conn, $sql_seFP1330, $params_seFP1330);
                                $result_seFP1330 = sqlsrv_fetch_array($query_seFP1330, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1345 = array();
                                $query_seFP1345 = sqlsrv_query($conn, $sql_seFP1345, $params_seFP1345);
                                $result_seFP1345 = sqlsrv_fetch_array($query_seFP1345, SQLSRV_FETCH_ASSOC);

                                $rsFP1300 = '';
                                $rsFP1315 = '';
                                $rsFP1330 = '';
                                $rsFP1345 = '';

                                if ($result_seFP1300['CNT'] != '0') {
                                    $rsFP1300 = "background-color: yellow";
                                }
                                if ($result_seFP1315['CNT'] != '0') {
                                    $rsFP1315 = "background-color: yellow";
                                }
                                if ($result_seFP1330['CNT'] != '0') {
                                    $rsFP1330 = "background-color: yellow";
                                }
                                if ($result_seFP1345['CNT'] != '0') {
                                    $rsFP1345 = "background-color: yellow";
                                }

                                $sql_seFP1400 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1400 = array();
                                $query_seFP1400 = sqlsrv_query($conn, $sql_seFP1400, $params_seFP1400);
                                $result_seFP1400 = sqlsrv_fetch_array($query_seFP1400, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1415 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1415 = array();
                                $query_seFP1415 = sqlsrv_query($conn, $sql_seFP1415, $params_seFP1415);
                                $result_seFP1415 = sqlsrv_fetch_array($query_seFP1415, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1430 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1430 = array();
                                $query_seFP1430 = sqlsrv_query($conn, $sql_seFP1430, $params_seFP1430);
                                $result_seFP1430 = sqlsrv_fetch_array($query_seFP1430, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1445 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1445 = array();
                                $query_seFP1445 = sqlsrv_query($conn, $sql_seFP1445, $params_seFP1445);
                                $result_seFP1445 = sqlsrv_fetch_array($query_seFP1445, SQLSRV_FETCH_ASSOC);

                                $rsFP1400 = '';
                                $rsFP1415 = '';
                                $rsFP1430 = '';
                                $rsFP1445 = '';

                                if ($result_seFP1400['CNT'] != '0') {
                                    $rsFP1400 = "background-color: yellow";
                                }
                                if ($result_seFP1415['CNT'] != '0') {
                                    $rsFP1415 = "background-color: yellow";
                                }
                                if ($result_seFP1430['CNT'] != '0') {
                                    $rsFP1430 = "background-color: yellow";
                                }
                                if ($result_seFP1445['CNT'] != '0') {
                                    $rsFP1445 = "background-color: yellow";
                                }

                                $sql_seFP1500 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1500 = array();
                                $query_seFP1500 = sqlsrv_query($conn, $sql_seFP1500, $params_seFP1500);
                                $result_seFP1500 = sqlsrv_fetch_array($query_seFP1500, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1515 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1515 = array();
                                $query_seFP1515 = sqlsrv_query($conn, $sql_seFP1515, $params_seFP1515);
                                $result_seFP1515 = sqlsrv_fetch_array($query_seFP1515, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1530 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1530 = array();
                                $query_seFP1530 = sqlsrv_query($conn, $sql_seFP1530, $params_seFP1530);
                                $result_seFP1530 = sqlsrv_fetch_array($query_seFP1530, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1545 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1545 = array();
                                $query_seFP1545 = sqlsrv_query($conn, $sql_seFP1545, $params_seFP1545);
                                $result_seFP1545 = sqlsrv_fetch_array($query_seFP1545, SQLSRV_FETCH_ASSOC);

                                $rsFP1500 = '';
                                $rsFP1515 = '';
                                $rsFP1530 = '';
                                $rsFP1545 = '';

                                if ($result_seFP1500['CNT'] != '0') {
                                    $rsFP1500 = "background-color: yellow";
                                }
                                if ($result_seFP1515['CNT'] != '0') {
                                    $rsFP1515 = "background-color: yellow";
                                }
                                if ($result_seFP1530['CNT'] != '0') {
                                    $rsFP1530 = "background-color: yellow";
                                }
                                if ($result_seFP1545['CNT'] != '0') {
                                    $rsFP1545 = "background-color: yellow";
                                }

                                $sql_seFP1600 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1600 = array();
                                $query_seFP1600 = sqlsrv_query($conn, $sql_seFP1600, $params_seFP1600);
                                $result_seFP1600 = sqlsrv_fetch_array($query_seFP1600, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1615 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1615 = array();
                                $query_seFP1615 = sqlsrv_query($conn, $sql_seFP1615, $params_seFP1615);
                                $result_seFP1615 = sqlsrv_fetch_array($query_seFP1615, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1630 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1630 = array();
                                $query_seFP1630 = sqlsrv_query($conn, $sql_seFP1630, $params_seFP1630);
                                $result_seFP1630 = sqlsrv_fetch_array($query_seFP1630, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1645 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1645 = array();
                                $query_seFP1645 = sqlsrv_query($conn, $sql_seFP1645, $params_seFP1645);
                                $result_seFP1645 = sqlsrv_fetch_array($query_seFP1645, SQLSRV_FETCH_ASSOC);

                                $rsFP1600 = '';
                                $rsFP1615 = '';
                                $rsFP1630 = '';
                                $rsFP1645 = '';

                                if ($result_seFP1600['CNT'] != '0') {
                                    $rsFP1600 = "background-color: yellow";
                                }
                                if ($result_seFP1615['CNT'] != '0') {
                                    $rsFP1615 = "background-color: yellow";
                                }
                                if ($result_seFP1630['CNT'] != '0') {
                                    $rsFP1630 = "background-color: yellow";
                                }
                                if ($result_seFP1645['CNT'] != '0') {
                                    $rsFP1645 = "background-color: yellow";
                                }

                                $sql_seFP1700 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1700 = array();
                                $query_seFP1700 = sqlsrv_query($conn, $sql_seFP1700, $params_seFP1700);
                                $result_seFP1700 = sqlsrv_fetch_array($query_seFP1700, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1715 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1715 = array();
                                $query_seFP1715 = sqlsrv_query($conn, $sql_seFP1715, $params_seFP1715);
                                $result_seFP1715 = sqlsrv_fetch_array($query_seFP1715, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1730 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1730 = array();
                                $query_seFP1730 = sqlsrv_query($conn, $sql_seFP1730, $params_seFP1730);
                                $result_seFP1730 = sqlsrv_fetch_array($query_seFP1730, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1745 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1745 = array();
                                $query_seFP1745 = sqlsrv_query($conn, $sql_seFP1745, $params_seFP1745);
                                $result_seFP1745 = sqlsrv_fetch_array($query_seFP1745, SQLSRV_FETCH_ASSOC);

                                $rsFP1700 = '';
                                $rsFP1715 = '';
                                $rsFP1730 = '';
                                $rsFP1745 = '';

                                if ($result_seFP1700['CNT'] != '0') {
                                    $rsFP1700 = "background-color: yellow";
                                }
                                if ($result_seFP1715['CNT'] != '0') {
                                    $rsFP1715 = "background-color: yellow";
                                }
                                if ($result_seFP1730['CNT'] != '0') {
                                    $rsFP1730 = "background-color: yellow";
                                }
                                if ($result_seFP1745['CNT'] != '0') {
                                    $rsFP1745 = "background-color: yellow";
                                }

                                $sql_seFP1800 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1800 = array();
                                $query_seFP1800 = sqlsrv_query($conn, $sql_seFP1800, $params_seFP1800);
                                $result_seFP1800 = sqlsrv_fetch_array($query_seFP1800, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1815 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1815 = array();
                                $query_seFP1815 = sqlsrv_query($conn, $sql_seFP1815, $params_seFP1815);
                                $result_seFP1815 = sqlsrv_fetch_array($query_seFP1815, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1830 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1830 = array();
                                $query_seFP1830 = sqlsrv_query($conn, $sql_seFP1830, $params_seFP1830);
                                $result_seFP1830 = sqlsrv_fetch_array($query_seFP1830, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1845 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1845 = array();
                                $query_seFP1845 = sqlsrv_query($conn, $sql_seFP1845, $params_seFP1845);
                                $result_seFP1845 = sqlsrv_fetch_array($query_seFP1845, SQLSRV_FETCH_ASSOC);

                                $rsFP1800 = '';
                                $rsFP1815 = '';
                                $rsFP1830 = '';
                                $rsFP1845 = '';

                                if ($result_seFP1800['CNT'] != '0') {
                                    $rsFP1800 = "background-color: yellow";
                                }
                                if ($result_seFP1815['CNT'] != '0') {
                                    $rsFP1815 = "background-color: yellow";
                                }
                                if ($result_seFP1830['CNT'] != '0') {
                                    $rsFP1830 = "background-color: yellow";
                                }
                                if ($result_seFP1845['CNT'] != '0') {
                                    $rsFP1845 = "background-color: yellow";
                                }

                                $sql_seFP1900 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1900 = array();
                                $query_seFP1900 = sqlsrv_query($conn, $sql_seFP1900, $params_seFP1900);
                                $result_seFP1900 = sqlsrv_fetch_array($query_seFP1900, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1915 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1915 = array();
                                $query_seFP1915 = sqlsrv_query($conn, $sql_seFP1915, $params_seFP1915);
                                $result_seFP1915 = sqlsrv_fetch_array($query_seFP1915, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1930 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1930 = array();
                                $query_seFP1930 = sqlsrv_query($conn, $sql_seFP1930, $params_seFP1930);
                                $result_seFP1930 = sqlsrv_fetch_array($query_seFP1930, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1945 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1945 = array();
                                $query_seFP1945 = sqlsrv_query($conn, $sql_seFP1945, $params_seFP1945);
                                $result_seFP1945 = sqlsrv_fetch_array($query_seFP1945, SQLSRV_FETCH_ASSOC);

                                $rsFP1900 = '';
                                $rsFP1915 = '';
                                $rsFP1930 = '';
                                $rsFP1945 = '';

                                if ($result_seFP1900['CNT'] != '0') {
                                    $rsFP1900 = "background-color: yellow";
                                }
                                if ($result_seFP1915['CNT'] != '0') {
                                    $rsFP1915 = "background-color: yellow";
                                }
                                if ($result_seFP1930['CNT'] != '0') {
                                    $rsFP1930 = "background-color: yellow";
                                }
                                if ($result_seFP1945['CNT'] != '0') {
                                    $rsFP1945 = "background-color: yellow";
                                }

                                $sql_seFP2000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2000 = array();
                                $query_seFP2000 = sqlsrv_query($conn, $sql_seFP2000, $params_seFP2000);
                                $result_seFP2000 = sqlsrv_fetch_array($query_seFP2000, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2015 = array();
                                $query_seFP2015 = sqlsrv_query($conn, $sql_seFP2015, $params_seFP2015);
                                $result_seFP2015 = sqlsrv_fetch_array($query_seFP2015, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2030 = array();
                                $query_seFP2030 = sqlsrv_query($conn, $sql_seFP2030, $params_seFP2030);
                                $result_seFP2030 = sqlsrv_fetch_array($query_seFP2030, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2045 = array();
                                $query_seFP2045 = sqlsrv_query($conn, $sql_seFP2045, $params_seFP2045);
                                $result_seFP2045 = sqlsrv_fetch_array($query_seFP2045, SQLSRV_FETCH_ASSOC);

                                $rsFP2000 = '';
                                $rsFP2015 = '';
                                $rsFP2030 = '';
                                $rsFP2045 = '';

                                if ($result_seFP2000['CNT'] != '0') {
                                    $rsFP2000 = "background-color: yellow";
                                }
                                if ($result_seFP2015['CNT'] != '0') {
                                    $rsFP2015 = "background-color: yellow";
                                }
                                if ($result_seFP2030['CNT'] != '0') {
                                    $rsFP2030 = "background-color: yellow";
                                }
                                if ($result_seFP2045['CNT'] != '0') {
                                    $rsFP2045 = "background-color: yellow";
                                }

                                $sql_seFP2100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2100 = array();
                                $query_seFP2100 = sqlsrv_query($conn, $sql_seFP2100, $params_seFP2100);
                                $result_seFP2100 = sqlsrv_fetch_array($query_seFP2100, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2115 = array();
                                $query_seFP2115 = sqlsrv_query($conn, $sql_seFP2115, $params_seFP2115);
                                $result_seFP2115 = sqlsrv_fetch_array($query_seFP2115, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2130 = array();
                                $query_seFP2130 = sqlsrv_query($conn, $sql_seFP2130, $params_seFP2130);
                                $result_seFP2130 = sqlsrv_fetch_array($query_seFP2130, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2145 = array();
                                $query_seFP2145 = sqlsrv_query($conn, $sql_seFP2145, $params_seFP2145);
                                $result_seFP2145 = sqlsrv_fetch_array($query_seFP2145, SQLSRV_FETCH_ASSOC);

                                $rsFP2100 = '';
                                $rsFP2115 = '';
                                $rsFP2130 = '';
                                $rsFP2145 = '';

                                if ($result_seFP2100['CNT'] != '0') {
                                    $rsFP2100 = "background-color: yellow";
                                }
                                if ($result_seFP2115['CNT'] != '0') {
                                    $rsFP2115 = "background-color: yellow";
                                }
                                if ($result_seFP2130['CNT'] != '0') {
                                    $rsFP2130 = "background-color: yellow";
                                }
                                if ($result_seFP2145['CNT'] != '0') {
                                    $rsFP2145 = "background-color: yellow";
                                }

                                $sql_seFP2200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2200 = array();
                                $query_seFP2200 = sqlsrv_query($conn, $sql_seFP2200, $params_seFP2200);
                                $result_seFP2200 = sqlsrv_fetch_array($query_seFP2200, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2215 = array();
                                $query_seFP2215 = sqlsrv_query($conn, $sql_seFP2215, $params_seFP2215);
                                $result_seFP2215 = sqlsrv_fetch_array($query_seFP2215, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2230 = array();
                                $query_seFP2230 = sqlsrv_query($conn, $sql_seFP2230, $params_seFP2230);
                                $result_seFP2230 = sqlsrv_fetch_array($query_seFP2230, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2245 = array();
                                $query_seFP2245 = sqlsrv_query($conn, $sql_seFP2245, $params_seFP2245);
                                $result_seFP2245 = sqlsrv_fetch_array($query_seFP2245, SQLSRV_FETCH_ASSOC);

                                $rsFP2200 = '';
                                $rsFP2215 = '';
                                $rsFP2230 = '';
                                $rsFP2245 = '';

                                if ($result_seFP2200['CNT'] != '0') {
                                    $rsFP2200 = "background-color: yellow";
                                }
                                if ($result_seFP2215['CNT'] != '0') {
                                    $rsFP2215 = "background-color: yellow";
                                }
                                if ($result_seFP2230['CNT'] != '0') {
                                    $rsFP2230 = "background-color: yellow";
                                }
                                if ($result_seFP2245['CNT'] != '0') {
                                    $rsFP2245 = "background-color: yellow";
                                }

                                $sql_seFP2300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2300 = array();
                                $query_seFP2300 = sqlsrv_query($conn, $sql_seFP2300, $params_seFP2300);
                                $result_seFP2300 = sqlsrv_fetch_array($query_seFP2300, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2315 = array();
                                $query_seFP2315 = sqlsrv_query($conn, $sql_seFP2315, $params_seFP2315);
                                $result_seFP2315 = sqlsrv_fetch_array($query_seFP2315, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2330 = array();
                                $query_seFP2330 = sqlsrv_query($conn, $sql_seFP2330, $params_seFP2330);
                                $result_seFP2330 = sqlsrv_fetch_array($query_seFP2330, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2345 = array();
                                $query_seFP2345 = sqlsrv_query($conn, $sql_seFP2345, $params_seFP2345);
                                $result_seFP2345 = sqlsrv_fetch_array($query_seFP2345, SQLSRV_FETCH_ASSOC);

                                $rsFP2300 = '';
                                $rsFP2315 = '';
                                $rsFP2330 = '';
                                $rsFP2345 = '';

                                if ($result_seFP2300['CNT'] != '0') {
                                    $rsFP2300 = "background-color: yellow";
                                }
                                if ($result_seFP2315['CNT'] != '0') {
                                    $rsFP2315 = "background-color: yellow";
                                }
                                if ($result_seFP2330['CNT'] != '0') {
                                    $rsFP2330 = "background-color: yellow";
                                }
                                if ($result_seFP2345['CNT'] != '0') {
                                    $rsFP2345 = "background-color: yellow";
                                }

                        // #################################################### ACTUALS2 ####################################################
                                
                                $sql_seAP0000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 00:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0000 = array();
                                $query_seAP0000 = sqlsrv_query($conn, $sql_seAP0000, $params_seAP0000);
                                $result_seAP0000 = sqlsrv_fetch_array($query_seAP0000, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 00:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0015 = array();
                                $query_seAP0015 = sqlsrv_query($conn, $sql_seAP0015, $params_seAP0015);
                                $result_seAP0015 = sqlsrv_fetch_array($query_seAP0015, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 00:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0030 = array();
                                $query_seAP0030 = sqlsrv_query($conn, $sql_seAP0030, $params_seAP0030);
                                $result_seAP0030 = sqlsrv_fetch_array($query_seAP0030, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 00:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0045 = array();
                                $query_seAP0045 = sqlsrv_query($conn, $sql_seAP0045, $params_seAP0045);
                                $result_seAP0045 = sqlsrv_fetch_array($query_seAP0045, SQLSRV_FETCH_ASSOC);

                                $rsAP0000 = '';
                                $rsAP0015 = '';
                                $rsAP0030 = '';
                                $rsAP0045 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0000['CNT'] != '0') {
                                        $rsAP0000 = "background-color: green";
                                    }
                                    if ($result_seAP0015['CNT'] != '0') {
                                        $rsAP0015 = "background-color: green";
                                    }
                                    if ($result_seAP0030['CNT'] != '0') {
                                        $rsAP0030 = "background-color: green";
                                    }
                                    if ($result_seAP0045['CNT'] != '0') {
                                        $rsAP0045 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0000['CNT'] != '0') {
                                        $rsAP0000 = "background-color: blue";
                                    }
                                    if ($result_seAP0015['CNT'] != '0') {
                                        $rsAP0015 = "background-color: blue";
                                    }
                                    if ($result_seAP0030['CNT'] != '0') {
                                        $rsAP0030 = "background-color: blue";
                                    }
                                    if ($result_seAP0045['CNT'] != '0') {
                                        $rsAP0045 = "background-color: blue";
                                    }
                                }
                                
                                $sql_seAP0100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 01:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0100 = array();
                                $query_seAP0100 = sqlsrv_query($conn, $sql_seAP0100, $params_seAP0100);
                                $result_seAP0100 = sqlsrv_fetch_array($query_seAP0100, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 01:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0115 = array();
                                $query_seAP0115 = sqlsrv_query($conn, $sql_seAP0115, $params_seAP0115);
                                $result_seAP0115 = sqlsrv_fetch_array($query_seAP0115, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 01:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0130 = array();
                                $query_seAP0130 = sqlsrv_query($conn, $sql_seAP0130, $params_seAP0130);
                                $result_seAP0130 = sqlsrv_fetch_array($query_seAP0130, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 01:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0145 = array();
                                $query_seAP0145 = sqlsrv_query($conn, $sql_seAP0145, $params_seAP0145);
                                $result_seAP0145 = sqlsrv_fetch_array($query_seAP0145, SQLSRV_FETCH_ASSOC);

                                $rsAP0100 = '';
                                $rsAP0115 = '';
                                $rsAP0130 = '';
                                $rsAP0145 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0100['CNT'] != '0') {
                                        $rsAP0100 = "background-color: green";
                                    }
                                    if ($result_seAP0115['CNT'] != '0') {
                                        $rsAP0115 = "background-color: green";
                                    }
                                    if ($result_seAP0130['CNT'] != '0') {
                                        $rsAP0130 = "background-color: green";
                                    }
                                    if ($result_seAP0145['CNT'] != '0') {
                                        $rsAP0145 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0100['CNT'] != '0') {
                                        $rsAP0100 = "background-color: blue";
                                    }
                                    if ($result_seAP0115['CNT'] != '0') {
                                        $rsAP0115 = "background-color: blue";
                                    }
                                    if ($result_seAP0130['CNT'] != '0') {
                                        $rsAP0130 = "background-color: blue";
                                    }
                                    if ($result_seAP0145['CNT'] != '0') {
                                        $rsAP0145 = "background-color: blue";
                                    }
                                }
                                
                                $sql_seAP0200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 02:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0200 = array();
                                $query_seAP0200 = sqlsrv_query($conn, $sql_seAP0200, $params_seAP0200);
                                $result_seAP0200 = sqlsrv_fetch_array($query_seAP0200, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 02:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0215 = array();
                                $query_seAP0215 = sqlsrv_query($conn, $sql_seAP0215, $params_seAP0215);
                                $result_seAP0215 = sqlsrv_fetch_array($query_seAP0215, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 02:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0230 = array();
                                $query_seAP0230 = sqlsrv_query($conn, $sql_seAP0230, $params_seAP0230);
                                $result_seAP0230 = sqlsrv_fetch_array($query_seAP0230, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 02:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0245 = array();
                                $query_seAP0245 = sqlsrv_query($conn, $sql_seAP0245, $params_seAP0245);
                                $result_seAP0245 = sqlsrv_fetch_array($query_seAP0245, SQLSRV_FETCH_ASSOC);

                                $rsAP0200 = '';
                                $rsAP0215 = '';
                                $rsAP0230 = '';
                                $rsAP0245 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0200['CNT'] != '0') {
                                        $rsAP0200 = "background-color: green";
                                    }
                                    if ($result_seAP0215['CNT'] != '0') {
                                        $rsAP0215 = "background-color: green";
                                    }
                                    if ($result_seAP0230['CNT'] != '0') {
                                        $rsAP0230 = "background-color: green";
                                    }
                                    if ($result_seAP0245['CNT'] != '0') {
                                        $rsAP0245 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0200['CNT'] != '0') {
                                        $rsAP0200 = "background-color: blue";
                                    }
                                    if ($result_seAP0215['CNT'] != '0') {
                                        $rsAP0215 = "background-color: blue";
                                    }
                                    if ($result_seAP0230['CNT'] != '0') {
                                        $rsAP0230 = "background-color: blue";
                                    }
                                    if ($result_seAP0245['CNT'] != '0') {
                                        $rsAP0245 = "background-color: blue";
                                    }
                                }
                                
                                $sql_seAP0300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 03:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0300 = array();
                                $query_seAP0300 = sqlsrv_query($conn, $sql_seAP0300, $params_seAP0300);
                                $result_seAP0300 = sqlsrv_fetch_array($query_seAP0300, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 03:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0315 = array();
                                $query_seAP0315 = sqlsrv_query($conn, $sql_seAP0315, $params_seAP0315);
                                $result_seAP0315 = sqlsrv_fetch_array($query_seAP0315, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 03:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0330 = array();
                                $query_seAP0330 = sqlsrv_query($conn, $sql_seAP0330, $params_seAP0330);
                                $result_seAP0330 = sqlsrv_fetch_array($query_seAP0330, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 03:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0345 = array();
                                $query_seAP0345 = sqlsrv_query($conn, $sql_seAP0345, $params_seAP0345);
                                $result_seAP0345 = sqlsrv_fetch_array($query_seAP0345, SQLSRV_FETCH_ASSOC);

                                $rsAP0300 = '';
                                $rsAP0315 = '';
                                $rsAP0330 = '';
                                $rsAP0345 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0300['CNT'] != '0') {
                                        $rsAP0300 = "background-color: green";
                                    }
                                    if ($result_seAP0315['CNT'] != '0') {
                                        $rsAP0315 = "background-color: green";
                                    }
                                    if ($result_seAP0330['CNT'] != '0') {
                                        $rsAP0330 = "background-color: green";
                                    }
                                    if ($result_seAP0345['CNT'] != '0') {
                                        $rsAP0345 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0300['CNT'] != '0') {
                                        $rsAP0300 = "background-color: blue";
                                    }
                                    if ($result_seAP0315['CNT'] != '0') {
                                        $rsAP0315 = "background-color: blue";
                                    }
                                    if ($result_seAP0330['CNT'] != '0') {
                                        $rsAP0330 = "background-color: blue";
                                    }
                                    if ($result_seAP0345['CNT'] != '0') {
                                        $rsAP0345 = "background-color: blue";
                                    }
                                }
                                
                                $sql_seAP0400 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 04:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0400 = array();
                                $query_seAP0400 = sqlsrv_query($conn, $sql_seAP0400, $params_seAP0400);
                                $result_seAP0400 = sqlsrv_fetch_array($query_seAP0400, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0415 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 04:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0415 = array();
                                $query_seAP0415 = sqlsrv_query($conn, $sql_seAP0415, $params_seAP0415);
                                $result_seAP0415 = sqlsrv_fetch_array($query_seAP0415, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0430 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 04:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0430 = array();
                                $query_seAP0430 = sqlsrv_query($conn, $sql_seAP0430, $params_seAP0430);
                                $result_seAP0430 = sqlsrv_fetch_array($query_seAP0430, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0445 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 04:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0445 = array();
                                $query_seAP0445 = sqlsrv_query($conn, $sql_seAP0445, $params_seAP0445);
                                $result_seAP0445 = sqlsrv_fetch_array($query_seAP0445, SQLSRV_FETCH_ASSOC);

                                $rsAP0400 = '';
                                $rsAP0415 = '';
                                $rsAP0430 = '';
                                $rsAP0445 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0400['CNT'] != '0') {
                                        $rsAP0400 = "background-color: green";
                                    }
                                    if ($result_seAP0415['CNT'] != '0') {
                                        $rsAP0415 = "background-color: green";
                                    }
                                    if ($result_seAP0430['CNT'] != '0') {
                                        $rsAP0430 = "background-color: green";
                                    }
                                    if ($result_seAP0445['CNT'] != '0') {
                                        $rsAP0445 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0400['CNT'] != '0') {
                                        $rsAP0400 = "background-color: blue";
                                    }
                                    if ($result_seAP0415['CNT'] != '0') {
                                        $rsAP0415 = "background-color: blue";
                                    }
                                    if ($result_seAP0430['CNT'] != '0') {
                                        $rsAP0430 = "background-color: blue";
                                    }
                                    if ($result_seAP0445['CNT'] != '0') {
                                        $rsAP0445 = "background-color: blue";
                                    }
                                }
                                
                                $sql_seAP0500 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 05:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0500 = array();
                                $query_seAP0500 = sqlsrv_query($conn, $sql_seAP0500, $params_seAP0500);
                                $result_seAP0500 = sqlsrv_fetch_array($query_seAP0500, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0515 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 05:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0515 = array();
                                $query_seAP0515 = sqlsrv_query($conn, $sql_seAP0515, $params_seAP0515);
                                $result_seAP0515 = sqlsrv_fetch_array($query_seAP0515, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0530 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 05:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0530 = array();
                                $query_seAP0530 = sqlsrv_query($conn, $sql_seAP0530, $params_seAP0530);
                                $result_seAP0530 = sqlsrv_fetch_array($query_seAP0530, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0545 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 05:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0545 = array();
                                $query_seAP0545 = sqlsrv_query($conn, $sql_seAP0545, $params_seAP0545);
                                $result_seAP0545 = sqlsrv_fetch_array($query_seAP0545, SQLSRV_FETCH_ASSOC);

                                $rsAP0500 = '';
                                $rsAP0515 = '';
                                $rsAP0530 = '';
                                $rsAP0545 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0500['CNT'] != '0') {
                                        $rsAP0500 = "background-color: green";
                                    }
                                    if ($result_seAP0515['CNT'] != '0') {
                                        $rsAP0515 = "background-color: green";
                                    }
                                    if ($result_seAP0530['CNT'] != '0') {
                                        $rsAP0530 = "background-color: green";
                                    }
                                    if ($result_seAP0545['CNT'] != '0') {
                                        $rsAP0545 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0500['CNT'] != '0') {
                                        $rsAP0500 = "background-color: blue";
                                    }
                                    if ($result_seAP0515['CNT'] != '0') {
                                        $rsAP0515 = "background-color: blue";
                                    }
                                    if ($result_seAP0530['CNT'] != '0') {
                                        $rsAP0530 = "background-color: blue";
                                    }
                                    if ($result_seAP0545['CNT'] != '0') {
                                        $rsAP0545 = "background-color: blue";
                                    }
                                }

                                $sql_seAP0600 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 06:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0600 = array();
                                $query_seAP0600 = sqlsrv_query($conn, $sql_seAP0600, $params_seAP0600);
                                $result_seAP0600 = sqlsrv_fetch_array($query_seAP0600, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0615 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 06:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0615 = array();
                                $query_seAP0615 = sqlsrv_query($conn, $sql_seAP0615, $params_seAP0615);
                                $result_seAP0615 = sqlsrv_fetch_array($query_seAP0615, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0630 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 06:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0630 = array();
                                $query_seAP0630 = sqlsrv_query($conn, $sql_seAP0630, $params_seAP0630);
                                $result_seAP0630 = sqlsrv_fetch_array($query_seAP0630, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0645 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 06:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0645 = array();
                                $query_seAP0645 = sqlsrv_query($conn, $sql_seAP0645, $params_seAP0645);
                                $result_seAP0645 = sqlsrv_fetch_array($query_seAP0645, SQLSRV_FETCH_ASSOC);

                                $rsAP0600 = '';
                                $rsAP0615 = '';
                                $rsAP0630 = '';
                                $rsAP0645 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0600['CNT'] != '0') {
                                        $rsAP0600 = "background-color: green";
                                    }
                                    if ($result_seAP0615['CNT'] != '0') {
                                        $rsAP0615 = "background-color: green";
                                    }
                                    if ($result_seAP0630['CNT'] != '0') {
                                        $rsAP0630 = "background-color: green";
                                    }
                                    if ($result_seAP0645['CNT'] != '0') {
                                        $rsAP0645 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0600['CNT'] != '0') {
                                        $rsAP0600 = "background-color: blue";
                                    }
                                    if ($result_seAP0615['CNT'] != '0') {
                                        $rsAP0615 = "background-color: blue";
                                    }
                                    if ($result_seAP0630['CNT'] != '0') {
                                        $rsAP0630 = "background-color: blue";
                                    }
                                    if ($result_seAP0645['CNT'] != '0') {
                                        $rsAP0645 = "background-color: blue";
                                    }
                                }

                                $sql_seAP0700 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 07:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0700 = array();
                                $query_seAP0700 = sqlsrv_query($conn, $sql_seAP0700, $params_seAP0700);
                                $result_seAP0700 = sqlsrv_fetch_array($query_seAP0700, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0715 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 07:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0715 = array();
                                $query_seAP0715 = sqlsrv_query($conn, $sql_seAP0715, $params_seAP0715);
                                $result_seAP0715 = sqlsrv_fetch_array($query_seAP0715, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0730 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 07:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0730 = array();
                                $query_seAP0730 = sqlsrv_query($conn, $sql_seAP0730, $params_seAP0730);
                                $result_seAP0730 = sqlsrv_fetch_array($query_seAP0730, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0745 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 07:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0745 = array();
                                $query_seAP0745 = sqlsrv_query($conn, $sql_seAP0745, $params_seAP0745);
                                $result_seAP0745 = sqlsrv_fetch_array($query_seAP0745, SQLSRV_FETCH_ASSOC);

                                $rsAP0700 = '';
                                $rsAP0715 = '';
                                $rsAP0730 = '';
                                $rsAP0745 = '';

                                        if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                            if ($result_seAP0700['CNT'] != '0') {
                                                $rsAP0700 = "background-color: green";
                                            }
                                            if ($result_seAP0715['CNT'] != '0') {
                                                $rsAP0715 = "background-color: green";
                                            }
                                            if ($result_seAP0730['CNT'] != '0') {
                                                $rsAP0730 = "background-color: green";
                                            }
                                            if ($result_seAP0745['CNT'] != '0') {
                                                $rsAP0745 = "background-color: green";
                                            }
                                        } else {
                                            if ($result_seAP0700['CNT'] != '0') {
                                                $rsAP0700 = "background-color: blue";
                                            }
                                            if ($result_seAP0715['CNT'] != '0') {
                                                $rsAP0715 = "background-color: blue";
                                            }
                                            if ($result_seAP0730['CNT'] != '0') {
                                                $rsAP0730 = "background-color: blue";
                                            }
                                            if ($result_seAP0745['CNT'] != '0') {
                                                $rsAP0745 = "background-color: blue";
                                            }
                                        }

                                $sql_seAP0800 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 08:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0800 = array();
                                $query_seAP0800 = sqlsrv_query($conn, $sql_seAP0800, $params_seAP0800);
                                $result_seAP0800 = sqlsrv_fetch_array($query_seAP0800, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0815 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 08:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0815 = array();
                                $query_seAP0815 = sqlsrv_query($conn, $sql_seAP0815, $params_seAP0815);
                                $result_seAP0815 = sqlsrv_fetch_array($query_seAP0815, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0830 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 08:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0830 = array();
                                $query_seAP0830 = sqlsrv_query($conn, $sql_seAP0830, $params_seAP0830);
                                $result_seAP0830 = sqlsrv_fetch_array($query_seAP0830, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0845 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 08:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0845 = array();
                                $query_seAP0845 = sqlsrv_query($conn, $sql_seAP0845, $params_seAP0845);
                                $result_seAP0845 = sqlsrv_fetch_array($query_seAP0845, SQLSRV_FETCH_ASSOC);

                                $rsAP0800 = '';
                                $rsAP0815 = '';
                                $rsAP0830 = '';
                                $rsAP0845 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0800['CNT'] != '0') {
                                        $rsAP0800 = "background-color: green";
                                    }
                                    if ($result_seAP0815['CNT'] != '0') {
                                        $rsAP0815 = "background-color: green";
                                    }
                                    if ($result_seAP0830['CNT'] != '0') {
                                        $rsAP0830 = "background-color: green";
                                    }
                                    if ($result_seAP0845['CNT'] != '0') {
                                        $rsAP0845 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0800['CNT'] != '0') {
                                        $rsAP0800 = "background-color: blue";
                                    }
                                    if ($result_seAP0815['CNT'] != '0') {
                                        $rsAP0815 = "background-color: blue";
                                    }
                                    if ($result_seAP0830['CNT'] != '0') {
                                        $rsAP0830 = "background-color: blue";
                                    }
                                    if ($result_seAP0845['CNT'] != '0') {
                                        $rsAP0845 = "background-color: blue";
                                    }
                                }

                                $sql_seAP0900 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 09:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0900 = array();
                                $query_seAP0900 = sqlsrv_query($conn, $sql_seAP0900, $params_seAP0900);
                                $result_seAP0900 = sqlsrv_fetch_array($query_seAP0900, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0915 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 09:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0915 = array();
                                $query_seAP0915 = sqlsrv_query($conn, $sql_seAP0915, $params_seAP0915);
                                $result_seAP0915 = sqlsrv_fetch_array($query_seAP0915, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0930 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 09:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0930 = array();
                                $query_seAP0930 = sqlsrv_query($conn, $sql_seAP0930, $params_seAP0930);
                                $result_seAP0930 = sqlsrv_fetch_array($query_seAP0930, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0945 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 09:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0945 = array();
                                $query_seAP0945 = sqlsrv_query($conn, $sql_seAP0945, $params_seAP0945);
                                $result_seAP0945 = sqlsrv_fetch_array($query_seAP0945, SQLSRV_FETCH_ASSOC);



                                $rsAP0900 = '';
                                $rsAP0915 = '';
                                $rsAP0930 = '';
                                $rsAP0945 = '';



                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0900['CNT'] != '0') {
                                        $rsAP0900 = "background-color: green";
                                    }
                                    if ($result_seAP0915['CNT'] != '0') {
                                        $rsAP0915 = "background-color: green";
                                    }
                                    if ($result_seAP0930['CNT'] != '0') {
                                        $rsAP0930 = "background-color: green";
                                    }
                                    if ($result_seAP0945['CNT'] != '0') {
                                        $rsAP0945 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0900['CNT'] != '0') {
                                        $rsAP0900 = "background-color: blue";
                                    }
                                    if ($result_seAP0915['CNT'] != '0') {
                                        $rsAP0915 = "background-color: blue";
                                    }
                                    if ($result_seAP0930['CNT'] != '0') {
                                        $rsAP0930 = "background-color: blue";
                                    }
                                    if ($result_seAP0945['CNT'] != '0') {
                                        $rsAP0945 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 10:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1000 = array();
                                $query_seAP1000 = sqlsrv_query($conn, $sql_seAP1000, $params_seAP1000);
                                $result_seAP1000 = sqlsrv_fetch_array($query_seAP1000, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 10:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1015 = array();
                                $query_seAP1015 = sqlsrv_query($conn, $sql_seAP1015, $params_seAP1015);
                                $result_seAP1015 = sqlsrv_fetch_array($query_seAP1015, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 10:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1030 = array();
                                $query_seAP1030 = sqlsrv_query($conn, $sql_seAP1030, $params_seAP1030);
                                $result_seAP1030 = sqlsrv_fetch_array($query_seAP1030, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 10:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1045 = array();
                                $query_seAP1045 = sqlsrv_query($conn, $sql_seAP1045, $params_seAP1045);
                                $result_seAP1045 = sqlsrv_fetch_array($query_seAP1045, SQLSRV_FETCH_ASSOC);

                                $rsAP1000 = '';
                                $rsAP1015 = '';
                                $rsAP1030 = '';
                                $rsAP1045 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1000['CNT'] != '0') {
                                        $rsAP1000 = "background-color: green";
                                    }
                                    if ($result_seAP1015['CNT'] != '0') {
                                        $rsAP1015 = "background-color: green";
                                    }
                                    if ($result_seAP1030['CNT'] != '0') {
                                        $rsAP1030 = "background-color: green";
                                    }
                                    if ($result_seAP1045['CNT'] != '0') {
                                        $rsAP1045 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1000['CNT'] != '0') {
                                        $rsAP1000 = "background-color: blue";
                                    }
                                    if ($result_seAP1015['CNT'] != '0') {
                                        $rsAP1015 = "background-color: blue";
                                    }
                                    if ($result_seAP1030['CNT'] != '0') {
                                        $rsAP1030 = "background-color: blue";
                                    }
                                    if ($result_seAP1045['CNT'] != '0') {
                                        $rsAP1045 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 11:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1100 = array();
                                $query_seAP1100 = sqlsrv_query($conn, $sql_seAP1100, $params_seAP1100);
                                $result_seAP1100 = sqlsrv_fetch_array($query_seAP1100, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 11:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1115 = array();
                                $query_seAP1115 = sqlsrv_query($conn, $sql_seAP1115, $params_seAP1115);
                                $result_seAP1115 = sqlsrv_fetch_array($query_seAP1115, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 11:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1130 = array();
                                $query_seAP1130 = sqlsrv_query($conn, $sql_seAP1130, $params_seAP1130);
                                $result_seAP1130 = sqlsrv_fetch_array($query_seAP1130, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 11:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1145 = array();
                                $query_seAP1145 = sqlsrv_query($conn, $sql_seAP1145, $params_seAP1145);
                                $result_seAP1145 = sqlsrv_fetch_array($query_seAP1145, SQLSRV_FETCH_ASSOC);

                                $rsAP1100 = '';
                                $rsAP1115 = '';
                                $rsAP1130 = '';
                                $rsAP1145 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1100['CNT'] != '0') {
                                        $rsAP1100 = "background-color: green";
                                    }
                                    if ($result_seAP1115['CNT'] != '0') {
                                        $rsAP1115 = "background-color: green";
                                    }
                                    if ($result_seAP1130['CNT'] != '0') {
                                        $rsAP1130 = "background-color: green";
                                    }
                                    if ($result_seAP1145['CNT'] != '0') {
                                        $rsAP1145 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1100['CNT'] != '0') {
                                        $rsAP1100 = "background-color: blue";
                                    }
                                    if ($result_seAP1115['CNT'] != '0') {
                                        $rsAP1115 = "background-color: blue";
                                    }
                                    if ($result_seAP1130['CNT'] != '0') {
                                        $rsAP1130 = "background-color: blue";
                                    }
                                    if ($result_seAP1145['CNT'] != '0') {
                                        $rsAP1145 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 12:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1200 = array();
                                $query_seAP1200 = sqlsrv_query($conn, $sql_seAP1200, $params_seAP1200);
                                $result_seAP1200 = sqlsrv_fetch_array($query_seAP1200, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 12:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1215 = array();
                                $query_seAP1215 = sqlsrv_query($conn, $sql_seAP1215, $params_seAP1215);
                                $result_seAP1215 = sqlsrv_fetch_array($query_seAP1215, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 12:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1230 = array();
                                $query_seAP1230 = sqlsrv_query($conn, $sql_seAP1230, $params_seAP1230);
                                $result_seAP1230 = sqlsrv_fetch_array($query_seAP1230, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 12:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1245 = array();
                                $query_seAP1245 = sqlsrv_query($conn, $sql_seAP1245, $params_seAP1245);
                                $result_seAP1245 = sqlsrv_fetch_array($query_seAP1245, SQLSRV_FETCH_ASSOC);

                                $rsAP1200 = '';
                                $rsAP1215 = '';
                                $rsAP1230 = '';
                                $rsAP1245 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1200['CNT'] != '0') {
                                        $rsAP1200 = "background-color: green";
                                    }
                                    if ($result_seAP1215['CNT'] != '0') {
                                        $rsAP1215 = "background-color: green";
                                    }
                                    if ($result_seAP1230['CNT'] != '0') {
                                        $rsAP1230 = "background-color: green";
                                    }
                                    if ($result_seAP1245['CNT'] != '0') {
                                        $rsAP1245 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1200['CNT'] != '0') {
                                        $rsAP1200 = "background-color: blue";
                                    }
                                    if ($result_seAP1215['CNT'] != '0') {
                                        $rsAP1215 = "background-color: blue";
                                    }
                                    if ($result_seAP1230['CNT'] != '0') {
                                        $rsAP1230 = "background-color: blue";
                                    }
                                    if ($result_seAP1245['CNT'] != '0') {
                                        $rsAP1245 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 13:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1300 = array();
                                $query_seAP1300 = sqlsrv_query($conn, $sql_seAP1300, $params_seAP1300);
                                $result_seAP1300 = sqlsrv_fetch_array($query_seAP1300, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 13:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1315 = array();
                                $query_seAP1315 = sqlsrv_query($conn, $sql_seAP1315, $params_seAP1315);
                                $result_seAP1315 = sqlsrv_fetch_array($query_seAP1315, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 13:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1330 = array();
                                $query_seAP1330 = sqlsrv_query($conn, $sql_seAP1330, $params_seAP1330);
                                $result_seAP1330 = sqlsrv_fetch_array($query_seAP1330, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 13:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1345 = array();
                                $query_seAP1345 = sqlsrv_query($conn, $sql_seAP1345, $params_seAP1345);
                                $result_seAP1345 = sqlsrv_fetch_array($query_seAP1345, SQLSRV_FETCH_ASSOC);

                                $rsAP1300 = '';
                                $rsAP1315 = '';
                                $rsAP1330 = '';
                                $rsAP1345 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1300['CNT'] != '0') {
                                        $rsAP1300 = "background-color: green";
                                    }
                                    if ($result_seAP1315['CNT'] != '0') {
                                        $rsAP1315 = "background-color: green";
                                    }
                                    if ($result_seAP1330['CNT'] != '0') {
                                        $rsAP1330 = "background-color: green";
                                    }
                                    if ($result_seAP1345['CNT'] != '0') {
                                        $rsAP1345 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1300['CNT'] != '0') {
                                        $rsAP1300 = "background-color: blue";
                                    }
                                    if ($result_seAP1315['CNT'] != '0') {
                                        $rsAP1315 = "background-color: blue";
                                    }
                                    if ($result_seAP1330['CNT'] != '0') {
                                        $rsAP1330 = "background-color: blue";
                                    }
                                    if ($result_seAP1345['CNT'] != '0') {
                                        $rsAP1345 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1400 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 14:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1400 = array();
                                $query_seAP1400 = sqlsrv_query($conn, $sql_seAP1400, $params_seAP1400);
                                $result_seAP1400 = sqlsrv_fetch_array($query_seAP1400, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1415 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 14:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1415 = array();
                                $query_seAP1415 = sqlsrv_query($conn, $sql_seAP1415, $params_seAP1415);
                                $result_seAP1415 = sqlsrv_fetch_array($query_seAP1415, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1430 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 14:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1430 = array();
                                $query_seAP1430 = sqlsrv_query($conn, $sql_seAP1430, $params_seAP1430);
                                $result_seAP1430 = sqlsrv_fetch_array($query_seAP1430, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1445 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 14:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1445 = array();
                                $query_seAP1445 = sqlsrv_query($conn, $sql_seAP1445, $params_seAP1445);
                                $result_seAP1445 = sqlsrv_fetch_array($query_seAP1445, SQLSRV_FETCH_ASSOC);

                                $rsAP1400 = '';
                                $rsAP1415 = '';
                                $rsAP1430 = '';
                                $rsAP1445 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1400['CNT'] != '0') {
                                        $rsAP1400 = "background-color: green";
                                    }
                                    if ($result_seAP1415['CNT'] != '0') {
                                        $rsAP1415 = "background-color: green";
                                    }
                                    if ($result_seAP1430['CNT'] != '0') {
                                        $rsAP1430 = "background-color: green";
                                    }
                                    if ($result_seAP1445['CNT'] != '0') {
                                        $rsAP1445 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1400['CNT'] != '0') {
                                        $rsAP1400 = "background-color: blue";
                                    }
                                    if ($result_seAP1415['CNT'] != '0') {
                                        $rsAP1415 = "background-color: blue";
                                    }
                                    if ($result_seAP1430['CNT'] != '0') {
                                        $rsAP1430 = "background-color: blue";
                                    }
                                    if ($result_seAP1445['CNT'] != '0') {
                                        $rsAP1445 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1500 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 15:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1500 = array();
                                $query_seAP1500 = sqlsrv_query($conn, $sql_seAP1500, $params_seAP1500);
                                $result_seAP1500 = sqlsrv_fetch_array($query_seAP1500, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1515 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 15:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1515 = array();
                                $query_seAP1515 = sqlsrv_query($conn, $sql_seAP1515, $params_seAP1515);
                                $result_seAP1515 = sqlsrv_fetch_array($query_seAP1515, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1530 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 15:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1530 = array();
                                $query_seAP1530 = sqlsrv_query($conn, $sql_seAP1530, $params_seAP1530);
                                $result_seAP1530 = sqlsrv_fetch_array($query_seAP1530, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1545 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 15:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1545 = array();
                                $query_seAP1545 = sqlsrv_query($conn, $sql_seAP1545, $params_seAP1545);
                                $result_seAP1545 = sqlsrv_fetch_array($query_seAP1545, SQLSRV_FETCH_ASSOC);

                                $rsAP1500 = '';
                                $rsAP1515 = '';
                                $rsAP1530 = '';
                                $rsAP1545 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1500['CNT'] != '0') {
                                        $rsAP1500 = "background-color: green";
                                    }
                                    if ($result_seAP1515['CNT'] != '0') {
                                        $rsAP1515 = "background-color: green";
                                    }
                                    if ($result_seAP1530['CNT'] != '0') {
                                        $rsAP1530 = "background-color: green";
                                    }
                                    if ($result_seAP1545['CNT'] != '0') {
                                        $rsAP1545 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1500['CNT'] != '0') {
                                        $rsAP1500 = "background-color: blue";
                                    }
                                    if ($result_seAP1515['CNT'] != '0') {
                                        $rsAP1515 = "background-color: blue";
                                    }
                                    if ($result_seAP1530['CNT'] != '0') {
                                        $rsAP1530 = "background-color: blue";
                                    }
                                    if ($result_seAP1545['CNT'] != '0') {
                                        $rsAP1545 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1600 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 16:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1600 = array();
                                $query_seAP1600 = sqlsrv_query($conn, $sql_seAP1600, $params_seAP1600);
                                $result_seAP1600 = sqlsrv_fetch_array($query_seAP1600, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1615 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 16:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1615 = array();
                                $query_seAP1615 = sqlsrv_query($conn, $sql_seAP1615, $params_seAP1615);
                                $result_seAP1615 = sqlsrv_fetch_array($query_seAP1615, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1630 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 16:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1630 = array();
                                $query_seAP1630 = sqlsrv_query($conn, $sql_seAP1630, $params_seAP1630);
                                $result_seAP1630 = sqlsrv_fetch_array($query_seAP1630, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1645 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 16:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1645 = array();
                                $query_seAP1645 = sqlsrv_query($conn, $sql_seAP1645, $params_seAP1645);
                                $result_seAP1645 = sqlsrv_fetch_array($query_seAP1645, SQLSRV_FETCH_ASSOC);

                                $rsAP1600 = '';
                                $rsAP1615 = '';
                                $rsAP1630 = '';
                                $rsAP1645 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1600['CNT'] != '0') {
                                        $rsAP1600 = "background-color: green";
                                    }
                                    if ($result_seAP1615['CNT'] != '0') {
                                        $rsAP1615 = "background-color: green";
                                    }
                                    if ($result_seAP1630['CNT'] != '0') {
                                        $rsAP1630 = "background-color: green";
                                    }
                                    if ($result_seAP1645['CNT'] != '0') {
                                        $rsAP1645 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1600['CNT'] != '0') {
                                        $rsAP1600 = "background-color: blue";
                                    }
                                    if ($result_seAP1615['CNT'] != '0') {
                                        $rsAP1615 = "background-color: blue";
                                    }
                                    if ($result_seAP1630['CNT'] != '0') {
                                        $rsAP1630 = "background-color: blue";
                                    }
                                    if ($result_seAP1645['CNT'] != '0') {
                                        $rsAP1645 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1700 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 17:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1700 = array();
                                $query_seAP1700 = sqlsrv_query($conn, $sql_seAP1700, $params_seAP1700);
                                $result_seAP1700 = sqlsrv_fetch_array($query_seAP1700, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1715 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 17:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1715 = array();
                                $query_seAP1715 = sqlsrv_query($conn, $sql_seAP1715, $params_seAP1715);
                                $result_seAP1715 = sqlsrv_fetch_array($query_seAP1715, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1730 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 17:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1730 = array();
                                $query_seAP1730 = sqlsrv_query($conn, $sql_seAP1730, $params_seAP1730);
                                $result_seAP1730 = sqlsrv_fetch_array($query_seAP1730, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1745 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 17:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1745 = array();
                                $query_seAP1745 = sqlsrv_query($conn, $sql_seAP1745, $params_seAP1745);
                                $result_seAP1745 = sqlsrv_fetch_array($query_seAP1745, SQLSRV_FETCH_ASSOC);

                                $rsAP1700 = '';
                                $rsAP1715 = '';
                                $rsAP1730 = '';
                                $rsAP1745 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1700['CNT'] != '0') {
                                        $rsAP1700 = "background-color: green";
                                    }
                                    if ($result_seAP1715['CNT'] != '0') {
                                        $rsAP1715 = "background-color: green";
                                    }
                                    if ($result_seAP1730['CNT'] != '0') {
                                        $rsAP1730 = "background-color: green";
                                    }
                                    if ($result_seAP1745['CNT'] != '0') {
                                        $rsAP1745 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1700['CNT'] != '0') {
                                        $rsAP1700 = "background-color: blue";
                                    }
                                    if ($result_seAP1715['CNT'] != '0') {
                                        $rsAP1715 = "background-color: blue";
                                    }
                                    if ($result_seAP1730['CNT'] != '0') {
                                        $rsAP1730 = "background-color: blue";
                                    }
                                    if ($result_seAP1745['CNT'] != '0') {
                                        $rsAP1745 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1800 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 18:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1800 = array();
                                $query_seAP1800 = sqlsrv_query($conn, $sql_seAP1800, $params_seAP1800);
                                $result_seAP1800 = sqlsrv_fetch_array($query_seAP1800, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1815 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 18:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1815 = array();
                                $query_seAP1815 = sqlsrv_query($conn, $sql_seAP1815, $params_seAP1815);
                                $result_seAP1815 = sqlsrv_fetch_array($query_seAP1815, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1830 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 18:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1830 = array();
                                $query_seAP1830 = sqlsrv_query($conn, $sql_seAP1830, $params_seAP1830);
                                $result_seAP1830 = sqlsrv_fetch_array($query_seAP1830, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1845 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 18:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1845 = array();
                                $query_seAP1845 = sqlsrv_query($conn, $sql_seAP1845, $params_seAP1845);
                                $result_seAP1845 = sqlsrv_fetch_array($query_seAP1845, SQLSRV_FETCH_ASSOC);

                                $rsAP1800 = '';
                                $rsAP1815 = '';
                                $rsAP1830 = '';
                                $rsAP1845 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1800['CNT'] != '0') {
                                        $rsAP1800 = "background-color: green";
                                    }
                                    if ($result_seAP1815['CNT'] != '0') {
                                        $rsAP1815 = "background-color: green";
                                    }
                                    if ($result_seAP1830['CNT'] != '0') {
                                        $rsAP1830 = "background-color: green";
                                    }
                                    if ($result_seAP1845['CNT'] != '0') {
                                        $rsAP1845 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1800['CNT'] != '0') {
                                        $rsAP1800 = "background-color: blue";
                                    }
                                    if ($result_seAP1815['CNT'] != '0') {
                                        $rsAP1815 = "background-color: blue";
                                    }
                                    if ($result_seAP1830['CNT'] != '0') {
                                        $rsAP1830 = "background-color: blue";
                                    }
                                    if ($result_seAP1845['CNT'] != '0') {
                                        $rsAP1845 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1900 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 19:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1900 = array();
                                $query_seAP1900 = sqlsrv_query($conn, $sql_seAP1900, $params_seAP1900);
                                $result_seAP1900 = sqlsrv_fetch_array($query_seAP1900, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1915 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 19:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1915 = array();
                                $query_seAP1915 = sqlsrv_query($conn, $sql_seAP1915, $params_seAP1915);
                                $result_seAP1915 = sqlsrv_fetch_array($query_seAP1915, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1930 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 19:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1930 = array();
                                $query_seAP1930 = sqlsrv_query($conn, $sql_seAP1930, $params_seAP1930);
                                $result_seAP1930 = sqlsrv_fetch_array($query_seAP1930, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1945 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 19:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1945 = array();
                                $query_seAP1945 = sqlsrv_query($conn, $sql_seAP1945, $params_seAP1945);
                                $result_seAP1945 = sqlsrv_fetch_array($query_seAP1945, SQLSRV_FETCH_ASSOC);

                                $rsAP1900 = '';
                                $rsAP1915 = '';
                                $rsAP1930 = '';
                                $rsAP1945 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1900['CNT'] != '0') {
                                        $rsAP1900 = "background-color: green";
                                    }
                                    if ($result_seAP1915['CNT'] != '0') {
                                        $rsAP1915 = "background-color: green";
                                    }
                                    if ($result_seAP1930['CNT'] != '0') {
                                        $rsAP1930 = "background-color: green";
                                    }
                                    if ($result_seAP1945['CNT'] != '0') {
                                        $rsAP1945 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1900['CNT'] != '0') {
                                        $rsAP1900 = "background-color: blue";
                                    }
                                    if ($result_seAP1915['CNT'] != '0') {
                                        $rsAP1915 = "background-color: blue";
                                    }
                                    if ($result_seAP1930['CNT'] != '0') {
                                        $rsAP1930 = "background-color: blue";
                                    }
                                    if ($result_seAP1945['CNT'] != '0') {
                                        $rsAP1945 = "background-color: blue";
                                    }
                                }

                                $sql_seAP2000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 20:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2000 = array();
                                $query_seAP2000 = sqlsrv_query($conn, $sql_seAP2000, $params_seAP2000);
                                $result_seAP2000 = sqlsrv_fetch_array($query_seAP2000, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 20:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2015 = array();
                                $query_seAP2015 = sqlsrv_query($conn, $sql_seAP2015, $params_seAP2015);
                                $result_seAP2015 = sqlsrv_fetch_array($query_seAP2015, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 20:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2030 = array();
                                $query_seAP2030 = sqlsrv_query($conn, $sql_seAP2030, $params_seAP2030);
                                $result_seAP2030 = sqlsrv_fetch_array($query_seAP2030, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 20:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2045 = array();
                                $query_seAP2045 = sqlsrv_query($conn, $sql_seAP2045, $params_seAP2045);
                                $result_seAP2045 = sqlsrv_fetch_array($query_seAP2045, SQLSRV_FETCH_ASSOC);

                                $rsAP2000 = '';
                                $rsAP2015 = '';
                                $rsAP2030 = '';
                                $rsAP2045 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP2000['CNT'] != '0') {
                                        $rsAP2000 = "background-color: green";
                                    }
                                    if ($result_seAP2015['CNT'] != '0') {
                                        $rsAP2015 = "background-color: green";
                                    }
                                    if ($result_seAP2030['CNT'] != '0') {
                                        $rsAP2030 = "background-color: green";
                                    }
                                    if ($result_seAP2045['CNT'] != '0') {
                                        $rsAP2045 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP2000['CNT'] != '0') {
                                        $rsAP2000 = "background-color: blue";
                                    }
                                    if ($result_seAP2015['CNT'] != '0') {
                                        $rsAP2015 = "background-color: blue";
                                    }
                                    if ($result_seAP2030['CNT'] != '0') {
                                        $rsAP2030 = "background-color: blue";
                                    }
                                    if ($result_seAP2045['CNT'] != '0') {
                                        $rsAP2045 = "background-color: blue";
                                    }
                                }

                                $sql_seAP2100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 21:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2100 = array();
                                $query_seAP2100 = sqlsrv_query($conn, $sql_seAP2100, $params_seAP2100);
                                $result_seAP2100 = sqlsrv_fetch_array($query_seAP2100, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 21:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2115 = array();
                                $query_seAP2115 = sqlsrv_query($conn, $sql_seAP2115, $params_seAP2115);
                                $result_seAP2115 = sqlsrv_fetch_array($query_seAP2115, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 21:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2130 = array();
                                $query_seAP2130 = sqlsrv_query($conn, $sql_seAP2130, $params_seAP2130);
                                $result_seAP2130 = sqlsrv_fetch_array($query_seAP2130, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 21:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2145 = array();
                                $query_seAP2145 = sqlsrv_query($conn, $sql_seAP2145, $params_seAP2145);
                                $result_seAP2145 = sqlsrv_fetch_array($query_seAP2145, SQLSRV_FETCH_ASSOC);

                                $rsAP2100 = '';
                                $rsAP2115 = '';
                                $rsAP2130 = '';
                                $rsAP2145 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP2100['CNT'] != '0') {
                                        $rsAP2100 = "background-color: green";
                                    }
                                    if ($result_seAP2115['CNT'] != '0') {
                                        $rsAP2115 = "background-color: green";
                                    }
                                    if ($result_seAP2130['CNT'] != '0') {
                                        $rsAP2130 = "background-color: green";
                                    }
                                    if ($result_seAP2145['CNT'] != '0') {
                                        $rsAP2145 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP2100['CNT'] != '0') {
                                        $rsAP2100 = "background-color: blue";
                                    }
                                    if ($result_seAP2115['CNT'] != '0') {
                                        $rsAP2115 = "background-color: blue";
                                    }
                                    if ($result_seAP2130['CNT'] != '0') {
                                        $rsAP2130 = "background-color: blue";
                                    }
                                    if ($result_seAP2145['CNT'] != '0') {
                                        $rsAP2145 = "background-color: blue";
                                    }
                                }

                                $sql_seAP2200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 22:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2200 = array();
                                $query_seAP2200 = sqlsrv_query($conn, $sql_seAP2200, $params_seAP2200);
                                $result_seAP2200 = sqlsrv_fetch_array($query_seAP2200, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 22:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2215 = array();
                                $query_seAP2215 = sqlsrv_query($conn, $sql_seAP2215, $params_seAP2215);
                                $result_seAP2215 = sqlsrv_fetch_array($query_seAP2215, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 22:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2230 = array();
                                $query_seAP2230 = sqlsrv_query($conn, $sql_seAP2230, $params_seAP2230);
                                $result_seAP2230 = sqlsrv_fetch_array($query_seAP2230, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 22:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2245 = array();
                                $query_seAP2245 = sqlsrv_query($conn, $sql_seAP2245, $params_seAP2245);
                                $result_seAP2245 = sqlsrv_fetch_array($query_seAP2245, SQLSRV_FETCH_ASSOC);

                                $rsAP2200 = '';
                                $rsAP2215 = '';
                                $rsAP2230 = '';
                                $rsAP2245 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP2200['CNT'] != '0') {
                                        $rsAP2200 = "background-color: green";
                                    }
                                    if ($result_seAP2215['CNT'] != '0') {
                                        $rsAP2215 = "background-color: green";
                                    }
                                    if ($result_seAP2230['CNT'] != '0') {
                                        $rsAP2230 = "background-color: green";
                                    }
                                    if ($result_seAP2245['CNT'] != '0') {
                                        $rsAP2245 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP2200['CNT'] != '0') {
                                        $rsAP2200 = "background-color: blue";
                                    }
                                    if ($result_seAP2215['CNT'] != '0') {
                                        $rsAP2215 = "background-color: blue";
                                    }
                                    if ($result_seAP2230['CNT'] != '0') {
                                        $rsAP2230 = "background-color: blue";
                                    }
                                    if ($result_seAP2245['CNT'] != '0') {
                                        $rsAP2245 = "background-color: blue";
                                    }
                                }

                                $sql_seAP2300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 23:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2300 = array();
                                $query_seAP2300 = sqlsrv_query($conn, $sql_seAP2300, $params_seAP2300);
                                $result_seAP2300 = sqlsrv_fetch_array($query_seAP2300, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 23:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2315 = array();
                                $query_seAP2315 = sqlsrv_query($conn, $sql_seAP2315, $params_seAP2315);
                                $result_seAP2315 = sqlsrv_fetch_array($query_seAP2315, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 23:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2330 = array();
                                $query_seAP2330 = sqlsrv_query($conn, $sql_seAP2330, $params_seAP2330);
                                $result_seAP2330 = sqlsrv_fetch_array($query_seAP2330, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 23:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2345 = array();
                                $query_seAP2345 = sqlsrv_query($conn, $sql_seAP2345, $params_seAP2345);
                                $result_seAP2345 = sqlsrv_fetch_array($query_seAP2345, SQLSRV_FETCH_ASSOC);

                                $rsAP2300 = '';
                                $rsAP2315 = '';
                                $rsAP2330 = '';
                                $rsAP2345 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP2300['CNT'] != '0') {
                                        $rsAP2300 = "background-color: green";
                                    }
                                    if ($result_seAP2315['CNT'] != '0') {
                                        $rsAP2315 = "background-color: green";
                                    }
                                    if ($result_seAP2330['CNT'] != '0') {
                                        $rsAP2330 = "background-color: green";
                                    }
                                    if ($result_seAP2345['CNT'] != '0') {
                                        $rsAP2345 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP2300['CNT'] != '0') {
                                        $rsAP2300 = "background-color: blue";
                                    }
                                    if ($result_seAP2315['CNT'] != '0') {
                                        $rsAP2315 = "background-color: blue";
                                    }
                                    if ($result_seAP2330['CNT'] != '0') {
                                        $rsAP2330 = "background-color: blue";
                                    }
                                    if ($result_seAP2345['CNT'] != '0') {
                                        $rsAP2345 = "background-color: blue";
                                    }
                                }

                                if ($result_seS2['VEHICLEREGISNUMBER1'] != '') {
                                    $VEHICLEREGISNUMBERS21 = $result_seS2['VEHICLEREGISNUMBER1'] . '(1)';
                                } else {
                                    $VEHICLEREGISNUMBERS21 = '';
                                }
                                if ($result_seS2['VEHICLEREGISNUMBER2'] != '') {
                                    $VEHICLEREGISNUMBERS22 = $result_seS2['VEHICLEREGISNUMBER2'] . '(2)';
                                } else {
                                    $VEHICLEREGISNUMBERS22 = '';
                                }

                                if ($result_seS2['VEHICLEREGISNUMBER1'] == '' || $result_seS2['VEHICLEREGISNUMBER2'] == '') {
                                    $commas2 = '';
                                } else {
                                    $commas2 = ',';
                                }
                    ?>
                    <tr>
                        <td style="text-align: center;" rowspan="2"><?= $iS2 ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seS2['VEHICLEREGISNUMBER1'].'/'.$result_seS2['THAINAME1'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seS2['VEHICLEREGISNUMBER2'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $customercode2 ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seS2['SUBJECT'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seCauS2['DETAIL'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $rsdrivename2 ?> <?php if($rsdrivename2!=''){echo '/';}?> <?= $rsdrivecard2 ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seS2['CH'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seEmpS2['CNT'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seS2['REPAIRMAN'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $TECHNICIANEMPLOYEES2 ?></td>
                        <td style="text-align: center;">แผน</td>
                            
                        <td width="0%" style="text-align: center;<?= $rsFP0000 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0015 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0030 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0045 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0100 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0115 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0130 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0145 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0200 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0215 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0230 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0245 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0300 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0315 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0330 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0345 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0400 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0415 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0430 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0445 ?>"></td>
                        
                        <td width="0%" style="text-align: center;<?= $rsFP0500 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0515 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0530 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0545 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0600 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0615 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0630 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0645 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0700 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0715 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0730 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0745 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0800 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0815 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0830 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0845 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0900 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0915 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0930 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0945 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1000 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1015 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1030 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1045 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1100 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1115 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1130 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1145 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1200 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1215 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1230 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1245 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1300 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1315 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1330 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1345 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1400 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1415 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1430 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1445 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1500 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1515 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1530 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1545 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1600 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1615 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1630 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1645 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1700 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1715 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1730 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1745 ?>"></td>
                            
                        <td width="0%" style="text-align: center;<?= $rsFP1800 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1815 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1830 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1845 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1900 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1915 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1930 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1945 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2000 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2015 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2030 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2045 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2100 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2115 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2130 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2145 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2200 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2215 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2230 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2245 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2300 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2315 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2330 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2345 ?>"></td>
                            
                        <td style="text-align: center;"><?= $hours2 . ' : ' . $minutes2 ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">ทำจริง</td>
                            
                        <td width="0%" style="text-align: center;<?= $rsAP0000 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0015 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0030 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0045 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0100 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0115 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0130 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0145 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0200 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0215 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0230 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0245 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0300 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0315 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0330 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0345 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0400 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0415 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0430 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0445 ?>"></td>
                        
                        <td width="0%" style="text-align: center;<?= $rsAP0500 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0515 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0530 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0545 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0600 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0615 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0630 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0645 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0700 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0715 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0730 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0745 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0800 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0815 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0830 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0845 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0900 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0915 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0930 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0945 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1000 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1015 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1030 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1045 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1100 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1115 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1130 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1145 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1200 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1215 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1230 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1245 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1300 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1315 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1330 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1345 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1400 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1415 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1430 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1445 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1500 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1515 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1530 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1545 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1600 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1615 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1630 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1645 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1700 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1715 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1730 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1745 ?>"></td>
                            
                        <td width="0%" style="text-align: center;<?= $rsAP1800 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1815 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1830 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1845 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1900 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1915 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1930 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1945 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2000 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2015 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2030 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2045 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2100 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2115 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2130 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2145 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2200 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2215 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2230 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2245 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2300 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2315 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2330 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2345 ?>"></td>

                        <?php if ($result_seHouracS2['OPENDATE'] == '') { ?>
                        <td style="text-align: center;"></td>
                        <?php } else { ?>
                        <td style="text-align: center;"><?= $houracs2 . ' : ' . $minuteacs2 ?></td>
                        <?php } ?>
                    </tr>
                    <?php $iS2++; } ?>
                    </tbody>
                </table>
            <!-- OTHER ############################################################################################################################## -->
                <?php
                    $sql_carcount2 = "SELECT COUNT(DISTINCT	a.RPRQ_REGISHEAD) CARCOUNT
                        FROM [dbo].[REPAIRREQUEST] a
                        INNER JOIN [dbo].[REPAIRCAUSE] b ON a.RPRQ_CODE=b.RPRQ_CODE
                        WHERE b.RPC_INCARDATE = '$txt_daterepair' AND a.RPRQ_STATUS = 'Y'
                        AND b.RPC_AREA IS NULL AND (a.RPRQ_REGISHEAD != '' OR a.RPRQ_REGISTAIL != '')  AND a.RPRQ_AREA='$AREA'";
                    $params_carcount2 = array();
                    $query_carcount2 = sqlsrv_query($conn, $sql_carcount2, $params_carcount2);
                    $result_carcount2 = sqlsrv_fetch_array($query_carcount2, SQLSRV_FETCH_ASSOC);
                    $CARCOUNT2=$result_carcount2["CARCOUNT"];
                    
                    $sql_rpmcount2 = "SELECT COUNT(DISTINCT C.RPME_NAME) REPAIRMAN
                    FROM [dbo].[REPAIRREQUEST] A
                    INNER JOIN [dbo].[REPAIRCAUSE] B ON A.RPRQ_CODE=B.RPRQ_CODE
                    LEFT JOIN REPAIRMANEMP C ON A.RPRQ_CODE = C.RPRQ_CODE
                    WHERE b.RPC_INCARDATE = '$txt_daterepair' AND a.RPRQ_STATUS = 'Y'
                    AND b.RPC_AREA IS NULL AND (A.RPRQ_REGISHEAD != '' OR A.RPRQ_REGISTAIL != '')  AND A.RPRQ_AREA='$AREA'
                    AND C.RPC_SUBJECT IN('TU','EL')";
                    $params_rpmcount2 = array();
                    $options_rpmcount2 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                    $query_rpmcount2 = sqlsrv_query($conn, $sql_rpmcount2, $params_rpmcount2, $options_rpmcount2);
                    $result_rpmcount2 = sqlsrv_fetch_array($query_rpmcount2, SQLSRV_FETCH_ASSOC);
                    $REPAIRMAN2=$result_rpmcount2["REPAIRMAN"];                                        
                ?>
                <?php if($AREA=='AMT'){ ?>
                    <h3><b>OTHER AMT</b>&nbsp;&nbsp;<small>รถซ่อมจำนวน <?=$CARCOUNT2?> คัน และช่างซ่อมจำนวน <?=$REPAIRMAN2?> คน</small></h3>
                <?php }else if($AREA=='GW'){ ?>
                    <h3><b>OTHER GW</b>&nbsp;&nbsp;<small>รถซ่อมจำนวน <?=$CARCOUNT2?> คัน และช่างซ่อมจำนวน <?=$REPAIRMAN2?> คน</small></h3>
                <?php } ?>
                <table class="border" border="1" style="width: 100%;">
                    <thead>
                        <!-- <tr>
                            <th class="border" border="1" colspan="70" style="text-align: center;">OTHER</th>
                        </tr> -->
                        <tr>
                            <th class="border" border="1" width="2%" rowspan="2" style="text-align: center;">ที่</th>
                            <th class="border" border="1" width="5%" rowspan="2" style="text-align: center;">ทะเบียน(หัว)/ชื่อรถ</th>
                            <th class="border" border="1" width="5%" rowspan="2" style="text-align: center;">ทะเบียน(หาง)</th>
                            <th class="border" border="1" width="5%" rowspan="2" style="text-align: center;">สายงาน</th>
                            <th class="border" border="1" width="6%" rowspan="2" style="text-align: center;">ลักษณะงานซ่อม</th>
                            <th class="border" border="1" width="7%" rowspan="2" style="text-align: center;">รายละเอียด</th>
                            <th class="border" border="1" width="6%" rowspan="2" style="text-align: center;">ช่างผู้ขับรถเข้าซ่อม/เลขใบขับขี่</th>
                            <th class="border" border="1" width="3%" rowspan="2" style="text-align: center;">สถานที่ซ่อม</th>
                            <th class="border" border="1" width="3%" rowspan="2" style="text-align: center;">จำนวนช่าง</th>
                            <th class="border" border="1" width="7%" rowspan="2" style="text-align: center;">ผู้รับผิดชอบ</th>
                            <th class="border" border="1" width="7%" rowspan="2" style="text-align: center;">ผู้ตรวจสอบ/SA</th>
                            <th class="border" border="1" width="3%" rowspan="2" style="text-align: center;">ชั่วโมงทำงาน</th>
                            <th class="border" border="1" style="text-align: center;width: 25%" colspan="96">เวลาในการปฎิบัติงาน</th>
                            <th class="border" border="1" width="3%" rowspan="2" style="text-align: center;">รวมชั่วโมง</th>
                        </tr>
                        <tr>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">0</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">1</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">2</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">3</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">4</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">5</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">6</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">7</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">8</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">9</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">10</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">11</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">12</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">13</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">14</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">15</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">16</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">17</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">18</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">19</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">20</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">21</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">22</th>
                            <th class="border" border="1" style="text-align: center;" colspan="4" width="1%">23</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // #################################################### HEADS2 ####################################################
                            $iS2 = 1;
                            $sql_seS2 = "SELECT 
                                a.RPRQ_REQUESTBY_SQ TECHNICIANEMPLOYEE,
                                a.RPRQ_ID REPAIRPLANID,
                                a.RPRQ_CODE,
                                a.RPRQ_REGISHEAD VEHICLEREGISNUMBER1,
                                a.RPRQ_CARNAMEHEAD THAINAME1,
                                a.RPRQ_REGISTAIL VEHICLEREGISNUMBER2,
                                a.RPRQ_CARNAMETAIL THAINAME2,
                                a.RPRQ_LINEOFWORK CUSTOMER,
                                a.RPRQ_COMPANYCASH COMPANYPAYMENT,
                                'S0/'+SUBSTRING(b.RPC_AREA,4,1) AS CH_NOTUSE,
                                b.RPC_AREA_OTHER AS CH,
                                CASE
                                    WHEN b.RPC_SUBJECT = 'EL' THEN 'ระบบไฟ'
                                    WHEN b.RPC_SUBJECT = 'TU' THEN 'ยาง ช่วงล่าง'
                                    WHEN b.RPC_SUBJECT = 'BD' THEN 'โครงสร้าง'
                                    WHEN b.RPC_SUBJECT = 'EG' THEN 'เครื่องยนต์'
                                    WHEN b.RPC_SUBJECT = 'AC' THEN 'อุปกรณ์ประจำรถ'
                                    ELSE ''
                                END SUBJECT,
                                b.RPC_SUBJECT,
                                a.RPRQ_STATUSREQUEST REPAIRSTATUS,
                                STUFF((SELECT DISTINCT ','+ CAST(RPME_NAME AS VARCHAR) FROM REPAIRMANEMP WHERE RPRQ_CODE = a.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT FOR XML PATH(''), TYPE).value('.','VARCHAR(max)'), 1, 1, '') AS REPAIRMAN
                                FROM [dbo].[REPAIRREQUEST] a
                                INNER JOIN [dbo].[REPAIRCAUSE] b ON a.RPRQ_CODE=b.RPRQ_CODE
                                WHERE b.RPC_INCARDATE = '$txt_daterepair' AND a.RPRQ_STATUS = 'Y'
                                AND b.RPC_AREA IS NULL AND (a.RPRQ_REGISHEAD != '' OR a.RPRQ_REGISTAIL != '')  AND a.RPRQ_AREA='$AREA'
                                GROUP BY a.RPRQ_REQUESTBY_SQ,a.RPRQ_ID,a.RPRQ_CODE,a.RPRQ_REGISHEAD,a.RPRQ_CARNAMEHEAD,a.RPRQ_REGISTAIL,a.RPRQ_CARNAMETAIL,a.RPRQ_LINEOFWORK,a.RPRQ_COMPANYCASH,b.RPC_AREA,b.RPC_AREA_OTHER,b.RPC_SUBJECT,a.RPRQ_STATUSREQUEST";
                            $params_seS2 = array();
                            $query_seS2 = sqlsrv_query($conn, $sql_seS2, $params_seS2);
                            while ($result_seS2 = sqlsrv_fetch_array($query_seS2, SQLSRV_FETCH_ASSOC)) {
                                if ($result_seS2['CUSTOMER'] == '' ) {
                                    $customercode2 = $result_seS2['COMPANYPAYMENT'];
                                }else {
                                    $customercode2 = $result_seS2['CUSTOMER'];
                                }

                                $sql_seEmpS2 = "SELECT COUNT(*) AS CNT FROM [dbo].[REPAIRMANEMP] WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seEmpS2 = array();
                                $query_seEmpS2 = sqlsrv_query($conn, $sql_seEmpS2, $params_seEmpS2);
                                $result_seEmpS2 = sqlsrv_fetch_array($query_seEmpS2, SQLSRV_FETCH_ASSOC);

                                $sql_seCauS2 = "SELECT RPC_DETAIL DETAIL FROM [dbo].[REPAIRCAUSE] WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seCauS2 = array();
                                $query_seCauS2 = sqlsrv_query($conn, $sql_seCauS2, $params_seEmpS2);
                                $result_seCauS2 = sqlsrv_fetch_array($query_seCauS2, SQLSRV_FETCH_ASSOC);

                                $sql_seActS2 = "SELECT RPATTM_GROUP FROM [dbo].[REPAIRACTUAL_TIME] WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS2['RPC_SUBJECT']."' ORDER BY RPATTM_PROCESS DESC";
                                $params_seActS2 = array();
                                $query_seActS2 = sqlsrv_query($conn, $sql_seActS2, $params_seActS2);
                                $result_seActS2 = sqlsrv_fetch_array($query_seActS2, SQLSRV_FETCH_ASSOC);

                                $sql_seHourS2 = "SELECT 
                                    CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AS 'CARINPUTDATE',
                                    CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120) AS 'COMPLETEDDATE'
                                    FROM [dbo].[REPAIRCAUSE] WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS2['RPC_SUBJECT']."' AND (RPC_REMARK = '' OR RPC_REMARK IS NULL)";
                                $params_seHourS2 = array();
                                $query_seHourS2 = sqlsrv_query($conn, $sql_seHourS2, $params_seHourS2);
                                $result_seHourS2 = sqlsrv_fetch_array($query_seHourS2, SQLSRV_FETCH_ASSOC);

                                $remains2 = intval(strtotime($result_seHourS2['COMPLETEDDATE']) - strtotime($result_seHourS2['CARINPUTDATE']));
                                $wans2 = floor($remains2 / 86400);
                                $l_wans2 = $remains2 % 86400;
                                $hours2 = floor($l_wans2 / 3600);
                                $l_hours2 = $l_wans2 % 3600;
                                $minutes2 = floor($l_hours2 / 60);
                                $seconds2 = $l_hours2 % 60;
                                // echo $result_seS2['RPRQ_CODE']."<br>";
                                // echo $result_seS2['RPC_SUBJECT']."<br>";
                                // echo $result_seHourS2['CARINPUTDATE']."<br>";
                                // echo $result_seHourS2['COMPLETEDDATE']."<br>";
                                // echo "ผ่านมาแล้ว ".$wan." วัน ".$hour." ชั่วโมง ".$minute." นาที ".$second." วินาที";

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    $sql_seHouracS2 = "SELECT DISTINCT A.RPRQ_CODE,A.RPC_SUBJECT,
                                        (SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME	WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'START') AS 'OPENDATE',
                                        (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') AS 'CLOSEDATE'
                                        FROM REPAIRACTUAL_TIME A WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS2['RPC_SUBJECT']."'";
                                    $params_seHouracS2 = array();
                                    $query_seHouracS2 = sqlsrv_query($conn, $sql_seHouracS2, $params_seHouracS2);
                                    $result_seHouracS2 = sqlsrv_fetch_array($query_seHouracS2, SQLSRV_FETCH_ASSOC);
                                
                                    $remainacs2 = intval(strtotime($result_seHouracS2['CLOSEDATE']) - strtotime($result_seHouracS2['OPENDATE']));
                                    $wanacs2 = floor($remainacs2 / 86400);
                                    $l_wanacs2 = $remainacs2 % 86400;
                                    $houracs2 = floor($l_wanacs2 / 3600);
                                    $l_houracs2 = $l_wanacs2 % 3600;
                                    $minuteacs2 = floor($l_houracs2 / 60);
                                    $secondacs2 = $l_houracs2 % 60;
                                    // echo "ผ่านมาแล้ว ".$wanacs2." วัน ".$houracs2." ชั่วโมง ".$minuteacs2." นาที ".$secondacs2." วินาที"."<br>";
                                } else {
                                    $sql_seHouracS2 = "SELECT A.RPRQ_CODE,A.RPC_SUBJECT,
                                        (SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME	WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'START') AS 'OPENDATE',
                                        CONVERT(VARCHAR(10), GETDATE(), 121) + ' '  + CONVERT(VARCHAR(5), GETDATE(), 14) AS 'CLOSEDATE' 
                                        FROM REPAIRACTUAL_TIME A WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPC_SUBJECT='".$result_seS2['RPC_SUBJECT']."'";
                                    $params_seHouracS2 = array();
                                    $query_seHouracS2 = sqlsrv_query($conn, $sql_seHouracS2, $params_seHouracS2);
                                    $result_seHouracS2 = sqlsrv_fetch_array($query_seHouracS2, SQLSRV_FETCH_ASSOC);
                                    
                                    $remainacs2 = intval(strtotime($result_seHouracS2['CLOSEDATE']) - strtotime($result_seHouracS2['OPENDATE']));
                                    $wanacs2 = floor($remainacs2 / 86400);
                                    $l_wanacs2 = $remainacs2 % 86400;
                                    $houracs2 = floor($l_wanacs2 / 3600);
                                    $l_houracs2 = $l_wanacs2 % 3600;
                                    $minuteacs2 = floor($l_houracs2 / 60);
                                    $secondacs2 = $l_houracs2 % 60;
                                }

                                $cntemps2 += $result_seEmpS2['CNT'];

                                $dateinput = $txt_daterepair;

                                $DETAIL2=$result_seCauS2['DETAIL'];
                                $DETAILCUT2 = explode("-", $DETAIL2);
                                $DETAILX20 = $DETAILCUT2[0];
                                $DETAILX21 = $DETAILCUT2[1];
                                $DETAILX22 = $DETAILCUT2[2];
                                
                                if($DETAILX20=="PM"){
                                    $sql_chketmrpmdrive2 = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."' AND RPMD_ZONE = 'S2'";
                                }else{
                                    $sql_chketmrpmdrive2 = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE='".$result_seS2['RPRQ_CODE']."'";
                                }
                                $query_chketmrpmdrive2 = sqlsrv_query($conn, $sql_chketmrpmdrive2);
                                $result_chketmrpmdrive2 = sqlsrv_fetch_array($query_chketmrpmdrive2, SQLSRV_FETCH_ASSOC);
                                $rsdrivename2 = $result_chketmrpmdrive2["RPMD_NAME"];
                                $rsdrivecard2 = $result_chketmrpmdrive2["RPMD_CARLICENCE"];

                        // #################################################### OTHER PLANS2 ####################################################
                                
                                $sql_seFP0000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0000 = array();
                                $query_seFP0000 = sqlsrv_query($conn, $sql_seFP0000, $params_seFP0000);
                                $result_seFP0000 = sqlsrv_fetch_array($query_seFP0000, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0015 = array();
                                $query_seFP0015 = sqlsrv_query($conn, $sql_seFP0015, $params_seFP0015);
                                $result_seFP0015 = sqlsrv_fetch_array($query_seFP0015, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0030 = array();
                                $query_seFP0030 = sqlsrv_query($conn, $sql_seFP0030, $params_seFP0030);
                                $result_seFP0030 = sqlsrv_fetch_array($query_seFP0030, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0045 = array();
                                $query_seFP0045 = sqlsrv_query($conn, $sql_seFP0045, $params_seFP0045);
                                $result_seFP0045 = sqlsrv_fetch_array($query_seFP0045, SQLSRV_FETCH_ASSOC);

                                $rsFP0000 = '';
                                $rsFP0015 = '';
                                $rsFP0030 = '';
                                $rsFP0045 = '';

                                if ($result_seFP0000['CNT'] != '0') {
                                    $rsFP0000 = "background-color: yellow";
                                }
                                if ($result_seFP0015['CNT'] != '0') {
                                    $rsFP0015 = "background-color: yellow";
                                }
                                if ($result_seFP0030['CNT'] != '0') {
                                    $rsFP0030 = "background-color: yellow";
                                }
                                if ($result_seFP0045['CNT'] != '0') {
                                    $rsFP0045 = "background-color: yellow";
                                }
                                
                                $sql_seFP0100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0100 = array();
                                $query_seFP0100 = sqlsrv_query($conn, $sql_seFP0100, $params_seFP0100);
                                $result_seFP0100 = sqlsrv_fetch_array($query_seFP0100, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0115 = array();
                                $query_seFP0115 = sqlsrv_query($conn, $sql_seFP0115, $params_seFP0115);
                                $result_seFP0115 = sqlsrv_fetch_array($query_seFP0115, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0130 = array();
                                $query_seFP0130 = sqlsrv_query($conn, $sql_seFP0130, $params_seFP0130);
                                $result_seFP0130 = sqlsrv_fetch_array($query_seFP0130, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0145 = array();
                                $query_seFP0145 = sqlsrv_query($conn, $sql_seFP0145, $params_seFP0145);
                                $result_seFP0145 = sqlsrv_fetch_array($query_seFP0145, SQLSRV_FETCH_ASSOC);

                                $rsFP0100 = '';
                                $rsFP0115 = '';
                                $rsFP0130 = '';
                                $rsFP0145 = '';

                                if ($result_seFP0100['CNT'] != '0') {
                                    $rsFP0100 = "background-color: yellow";
                                }
                                if ($result_seFP0115['CNT'] != '0') {
                                    $rsFP0115 = "background-color: yellow";
                                }
                                if ($result_seFP0130['CNT'] != '0') {
                                    $rsFP0130 = "background-color: yellow";
                                }
                                if ($result_seFP0145['CNT'] != '0') {
                                    $rsFP0145 = "background-color: yellow";
                                }
                                
                                $sql_seFP0200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0200 = array();
                                $query_seFP0200 = sqlsrv_query($conn, $sql_seFP0200, $params_seFP0200);
                                $result_seFP0200 = sqlsrv_fetch_array($query_seFP0200, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0215 = array();
                                $query_seFP0215 = sqlsrv_query($conn, $sql_seFP0215, $params_seFP0215);
                                $result_seFP0215 = sqlsrv_fetch_array($query_seFP0215, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0230 = array();
                                $query_seFP0230 = sqlsrv_query($conn, $sql_seFP0230, $params_seFP0230);
                                $result_seFP0230 = sqlsrv_fetch_array($query_seFP0230, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0245 = array();
                                $query_seFP0245 = sqlsrv_query($conn, $sql_seFP0245, $params_seFP0245);
                                $result_seFP0245 = sqlsrv_fetch_array($query_seFP0245, SQLSRV_FETCH_ASSOC);

                                $rsFP0200 = '';
                                $rsFP0215 = '';
                                $rsFP0230 = '';
                                $rsFP0245 = '';

                                if ($result_seFP0200['CNT'] != '0') {
                                    $rsFP0200 = "background-color: yellow";
                                }
                                if ($result_seFP0215['CNT'] != '0') {
                                    $rsFP0215 = "background-color: yellow";
                                }
                                if ($result_seFP0230['CNT'] != '0') {
                                    $rsFP0230 = "background-color: yellow";
                                }
                                if ($result_seFP0245['CNT'] != '0') {
                                    $rsFP0245 = "background-color: yellow";
                                }
                                
                                $sql_seFP0300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0300 = array();
                                $query_seFP0300 = sqlsrv_query($conn, $sql_seFP0300, $params_seFP0300);
                                $result_seFP0300 = sqlsrv_fetch_array($query_seFP0300, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0315 = array();
                                $query_seFP0315 = sqlsrv_query($conn, $sql_seFP0315, $params_seFP0315);
                                $result_seFP0315 = sqlsrv_fetch_array($query_seFP0315, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0330 = array();
                                $query_seFP0330 = sqlsrv_query($conn, $sql_seFP0330, $params_seFP0330);
                                $result_seFP0330 = sqlsrv_fetch_array($query_seFP0330, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0345 = array();
                                $query_seFP0345 = sqlsrv_query($conn, $sql_seFP0345, $params_seFP0345);
                                $result_seFP0345 = sqlsrv_fetch_array($query_seFP0345, SQLSRV_FETCH_ASSOC);

                                $rsFP0300 = '';
                                $rsFP0315 = '';
                                $rsFP0330 = '';
                                $rsFP0345 = '';

                                if ($result_seFP0300['CNT'] != '0') {
                                    $rsFP0300 = "background-color: yellow";
                                }
                                if ($result_seFP0315['CNT'] != '0') {
                                    $rsFP0315 = "background-color: yellow";
                                }
                                if ($result_seFP0330['CNT'] != '0') {
                                    $rsFP0330 = "background-color: yellow";
                                }
                                if ($result_seFP0345['CNT'] != '0') {
                                    $rsFP0345 = "background-color: yellow";
                                }
                                
                                $sql_seFP0400 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0400 = array();
                                $query_seFP0400 = sqlsrv_query($conn, $sql_seFP0400, $params_seFP0400);
                                $result_seFP0400 = sqlsrv_fetch_array($query_seFP0400, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0415 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0415 = array();
                                $query_seFP0415 = sqlsrv_query($conn, $sql_seFP0415, $params_seFP0415);
                                $result_seFP0415 = sqlsrv_fetch_array($query_seFP0415, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0430 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0430 = array();
                                $query_seFP0430 = sqlsrv_query($conn, $sql_seFP0430, $params_seFP0430);
                                $result_seFP0430 = sqlsrv_fetch_array($query_seFP0430, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0445 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0445 = array();
                                $query_seFP0445 = sqlsrv_query($conn, $sql_seFP0445, $params_seFP0445);
                                $result_seFP0445 = sqlsrv_fetch_array($query_seFP0445, SQLSRV_FETCH_ASSOC);

                                $rsFP0400 = '';
                                $rsFP0415 = '';
                                $rsFP0430 = '';
                                $rsFP0445 = '';

                                if ($result_seFP0400['CNT'] != '0') {
                                    $rsFP0400 = "background-color: yellow";
                                }
                                if ($result_seFP0415['CNT'] != '0') {
                                    $rsFP0415 = "background-color: yellow";
                                }
                                if ($result_seFP0430['CNT'] != '0') {
                                    $rsFP0430 = "background-color: yellow";
                                }
                                if ($result_seFP0445['CNT'] != '0') {
                                    $rsFP0445 = "background-color: yellow";
                                }
                                
                                $sql_seFP0500 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0500 = array();
                                $query_seFP0500 = sqlsrv_query($conn, $sql_seFP0500, $params_seFP0500);
                                $result_seFP0500 = sqlsrv_fetch_array($query_seFP0500, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0515 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0515 = array();
                                $query_seFP0515 = sqlsrv_query($conn, $sql_seFP0515, $params_seFP0515);
                                $result_seFP0515 = sqlsrv_fetch_array($query_seFP0515, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0530 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0530 = array();
                                $query_seFP0530 = sqlsrv_query($conn, $sql_seFP0530, $params_seFP0530);
                                $result_seFP0530 = sqlsrv_fetch_array($query_seFP0530, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0545 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0545 = array();
                                $query_seFP0545 = sqlsrv_query($conn, $sql_seFP0545, $params_seFP0545);
                                $result_seFP0545 = sqlsrv_fetch_array($query_seFP0545, SQLSRV_FETCH_ASSOC);

                                $rsFP0500 = '';
                                $rsFP0515 = '';
                                $rsFP0530 = '';
                                $rsFP0545 = '';

                                if ($result_seFP0500['CNT'] != '0') {
                                    $rsFP0500 = "background-color: yellow";
                                }
                                if ($result_seFP0515['CNT'] != '0') {
                                    $rsFP0515 = "background-color: yellow";
                                }
                                if ($result_seFP0530['CNT'] != '0') {
                                    $rsFP0530 = "background-color: yellow";
                                }
                                if ($result_seFP0545['CNT'] != '0') {
                                    $rsFP0545 = "background-color: yellow";
                                }

                                $sql_seFP0600 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0600 = array();
                                $query_seFP0600 = sqlsrv_query($conn, $sql_seFP0600, $params_seFP0600);
                                $result_seFP0600 = sqlsrv_fetch_array($query_seFP0600, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0615 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0615 = array();
                                $query_seFP0615 = sqlsrv_query($conn, $sql_seFP0615, $params_seFP0615);
                                $result_seFP0615 = sqlsrv_fetch_array($query_seFP0615, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0630 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0630 = array();
                                $query_seFP0630 = sqlsrv_query($conn, $sql_seFP0630, $params_seFP0630);
                                $result_seFP0630 = sqlsrv_fetch_array($query_seFP0630, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0645 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0645 = array();
                                $query_seFP0645 = sqlsrv_query($conn, $sql_seFP0645, $params_seFP0645);
                                $result_seFP0645 = sqlsrv_fetch_array($query_seFP0645, SQLSRV_FETCH_ASSOC);

                                $rsFP0600 = '';
                                $rsFP0615 = '';
                                $rsFP0630 = '';
                                $rsFP0645 = '';

                                if ($result_seFP0600['CNT'] != '0') {
                                    $rsFP0600 = "background-color: yellow";
                                }
                                if ($result_seFP0615['CNT'] != '0') {
                                    $rsFP0615 = "background-color: yellow";
                                }
                                if ($result_seFP0630['CNT'] != '0') {
                                    $rsFP0630 = "background-color: yellow";
                                }
                                if ($result_seFP0645['CNT'] != '0') {
                                    $rsFP0645 = "background-color: yellow";
                                }

                                $sql_seFP0700 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0700 = array();
                                $query_seFP0700 = sqlsrv_query($conn, $sql_seFP0700, $params_seFP0700);
                                $result_seFP0700 = sqlsrv_fetch_array($query_seFP0700, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0715 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0715 = array();
                                $query_seFP0715 = sqlsrv_query($conn, $sql_seFP0715, $params_seFP0715);
                                $result_seFP0715 = sqlsrv_fetch_array($query_seFP0715, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0730 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0730 = array();
                                $query_seFP0730 = sqlsrv_query($conn, $sql_seFP0730, $params_seFP0730);
                                $result_seFP0730 = sqlsrv_fetch_array($query_seFP0730, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0745 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0745 = array();
                                $query_seFP0745 = sqlsrv_query($conn, $sql_seFP0745, $params_seFP0745);
                                $result_seFP0745 = sqlsrv_fetch_array($query_seFP0745, SQLSRV_FETCH_ASSOC);

                                $rsFP0700 = '';
                                $rsFP0715 = '';
                                $rsFP0730 = '';
                                $rsFP0745 = '';

                                if ($result_seFP0700['CNT'] != '0') {
                                    $rsFP0700 = "background-color: yellow";
                                }
                                if ($result_seFP0715['CNT'] != '0') {
                                    $rsFP0715 = "background-color: yellow";
                                }
                                if ($result_seFP0730['CNT'] != '0') {
                                    $rsFP0730 = "background-color: yellow";
                                }
                                if ($result_seFP0745['CNT'] != '0') {
                                    $rsFP0745 = "background-color: yellow";
                                }

                                $sql_seFP0800 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0800 = array();
                                $query_seFP0800 = sqlsrv_query($conn, $sql_seFP0800, $params_seFP0800);
                                $result_seFP0800 = sqlsrv_fetch_array($query_seFP0800, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0815 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0815 = array();
                                $query_seFP0815 = sqlsrv_query($conn, $sql_seFP0815, $params_seFP0815);
                                $result_seFP0815 = sqlsrv_fetch_array($query_seFP0815, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0830 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0830 = array();
                                $query_seFP0830 = sqlsrv_query($conn, $sql_seFP0830, $params_seFP0830);
                                $result_seFP0830 = sqlsrv_fetch_array($query_seFP0830, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0845 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0845 = array();
                                $query_seFP0845 = sqlsrv_query($conn, $sql_seFP0845, $params_seFP0845);
                                $result_seFP0845 = sqlsrv_fetch_array($query_seFP0845, SQLSRV_FETCH_ASSOC);

                                $rsFP0800 = '';
                                $rsFP0815 = '';
                                $rsFP0830 = '';
                                $rsFP0845 = '';

                                if ($result_seFP0800['CNT'] != '0') {
                                    $rsFP0800 = "background-color: yellow";
                                }
                                if ($result_seFP0815['CNT'] != '0') {
                                    $rsFP0815 = "background-color: yellow";
                                }
                                if ($result_seFP0830['CNT'] != '0') {
                                    $rsFP0830 = "background-color: yellow";
                                }
                                if ($result_seFP0845['CNT'] != '0') {
                                    $rsFP0845 = "background-color: yellow";
                                }

                                $sql_seFP0900 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0900 = array();
                                $query_seFP0900 = sqlsrv_query($conn, $sql_seFP0900, $params_seFP0900);
                                $result_seFP0900 = sqlsrv_fetch_array($query_seFP0900, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0915 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0915 = array();
                                $query_seFP0915 = sqlsrv_query($conn, $sql_seFP0915, $params_seFP0915);
                                $result_seFP0915 = sqlsrv_fetch_array($query_seFP0915, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0930 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0930 = array();
                                $query_seFP0930 = sqlsrv_query($conn, $sql_seFP0930, $params_seFP0930);
                                $result_seFP0930 = sqlsrv_fetch_array($query_seFP0930, SQLSRV_FETCH_ASSOC);

                                $sql_seFP0945 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP0945 = array();
                                $query_seFP0945 = sqlsrv_query($conn, $sql_seFP0945, $params_seFP0945);
                                $result_seFP0945 = sqlsrv_fetch_array($query_seFP0945, SQLSRV_FETCH_ASSOC);

                                $rsFP0900 = '';
                                $rsFP0915 = '';
                                $rsFP0930 = '';
                                $rsFP0945 = '';

                                if ($result_seFP0900['CNT'] != '0') {
                                    $rsFP0900 = "background-color: yellow";
                                }
                                if ($result_seFP0915['CNT'] != '0') {
                                    $rsFP0915 = "background-color: yellow";
                                }
                                if ($result_seFP0930['CNT'] != '0') {
                                    $rsFP0930 = "background-color: yellow";
                                }
                                if ($result_seFP0945['CNT'] != '0') {
                                    $rsFP0945 = "background-color: yellow";
                                }

                                $sql_seFP1000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1000 = array();
                                $query_seFP1000 = sqlsrv_query($conn, $sql_seFP1000, $params_seFP1000);
                                $result_seFP1000 = sqlsrv_fetch_array($query_seFP1000, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1015 = array();
                                $query_seFP1015 = sqlsrv_query($conn, $sql_seFP1015, $params_seFP1015);
                                $result_seFP1015 = sqlsrv_fetch_array($query_seFP1015, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1030 = array();
                                $query_seFP1030 = sqlsrv_query($conn, $sql_seFP1030, $params_seFP1030);
                                $result_seFP1030 = sqlsrv_fetch_array($query_seFP1030, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1045 = array();
                                $query_seFP1045 = sqlsrv_query($conn, $sql_seFP1045, $params_seFP1045);
                                $result_seFP1045 = sqlsrv_fetch_array($query_seFP1045, SQLSRV_FETCH_ASSOC);

                                $rsFP1000 = '';
                                $rsFP1015 = '';
                                $rsFP1030 = '';
                                $rsFP1045 = '';

                                if ($result_seFP1000['CNT'] != '0') {
                                    $rsFP1000 = "background-color: yellow";
                                }
                                if ($result_seFP1015['CNT'] != '0') {
                                    $rsFP1015 = "background-color: yellow";
                                }
                                if ($result_seFP1030['CNT'] != '0') {
                                    $rsFP1030 = "background-color: yellow";
                                }
                                if ($result_seFP1045['CNT'] != '0') {
                                    $rsFP1045 = "background-color: yellow";
                                }

                                $sql_seFP1100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1100 = array();
                                $query_seFP1100 = sqlsrv_query($conn, $sql_seFP1100, $params_seFP1100);
                                $result_seFP1100 = sqlsrv_fetch_array($query_seFP1100, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1115 = array();
                                $query_seFP1115 = sqlsrv_query($conn, $sql_seFP1115, $params_seFP1115);
                                $result_seFP1115 = sqlsrv_fetch_array($query_seFP1115, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1130 = array();
                                $query_seFP1130 = sqlsrv_query($conn, $sql_seFP1130, $params_seFP1130);
                                $result_seFP1130 = sqlsrv_fetch_array($query_seFP1130, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1145 = array();
                                $query_seFP1145 = sqlsrv_query($conn, $sql_seFP1145, $params_seFP1145);
                                $result_seFP1145 = sqlsrv_fetch_array($query_seFP1145, SQLSRV_FETCH_ASSOC);

                                $rsFP1100 = '';
                                $rsFP1115 = '';
                                $rsFP1130 = '';
                                $rsFP1145 = '';

                                if ($result_seFP1100['CNT'] != '0') {
                                    $rsFP1100 = "background-color: yellow";
                                }
                                if ($result_seFP1115['CNT'] != '0') {
                                    $rsFP1115 = "background-color: yellow";
                                }
                                if ($result_seFP1130['CNT'] != '0') {
                                    $rsFP1130 = "background-color: yellow";
                                }
                                if ($result_seFP1145['CNT'] != '0') {
                                    $rsFP1145 = "background-color: yellow";
                                }

                                $sql_seFP1200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1200 = array();
                                $query_seFP1200 = sqlsrv_query($conn, $sql_seFP1200, $params_seFP1200);
                                $result_seFP1200 = sqlsrv_fetch_array($query_seFP1200, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1215 = array();
                                $query_seFP1215 = sqlsrv_query($conn, $sql_seFP1215, $params_seFP1215);
                                $result_seFP1215 = sqlsrv_fetch_array($query_seFP1215, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1230 = array();
                                $query_seFP1230 = sqlsrv_query($conn, $sql_seFP1230, $params_seFP1230);
                                $result_seFP1230 = sqlsrv_fetch_array($query_seFP1230, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1245 = array();
                                $query_seFP1245 = sqlsrv_query($conn, $sql_seFP1245, $params_seFP1245);
                                $result_seFP1245 = sqlsrv_fetch_array($query_seFP1245, SQLSRV_FETCH_ASSOC);

                                $rsFP1200 = '';
                                $rsFP1215 = '';
                                $rsFP1230 = '';
                                $rsFP1245 = '';

                                if ($result_seFP1200['CNT'] != '0') {
                                    $rsFP1200 = "background-color: yellow";
                                }
                                if ($result_seFP1215['CNT'] != '0') {
                                    $rsFP1215 = "background-color: yellow";
                                }
                                if ($result_seFP1230['CNT'] != '0') {
                                    $rsFP1230 = "background-color: yellow";
                                }
                                if ($result_seFP1245['CNT'] != '0') {
                                    $rsFP1245 = "background-color: yellow";
                                }

                                $sql_seFP1300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1300 = array();
                                $query_seFP1300 = sqlsrv_query($conn, $sql_seFP1300, $params_seFP1300);
                                $result_seFP1300 = sqlsrv_fetch_array($query_seFP1300, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1315 = array();
                                $query_seFP1315 = sqlsrv_query($conn, $sql_seFP1315, $params_seFP1315);
                                $result_seFP1315 = sqlsrv_fetch_array($query_seFP1315, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1330 = array();
                                $query_seFP1330 = sqlsrv_query($conn, $sql_seFP1330, $params_seFP1330);
                                $result_seFP1330 = sqlsrv_fetch_array($query_seFP1330, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1345 = array();
                                $query_seFP1345 = sqlsrv_query($conn, $sql_seFP1345, $params_seFP1345);
                                $result_seFP1345 = sqlsrv_fetch_array($query_seFP1345, SQLSRV_FETCH_ASSOC);

                                $rsFP1300 = '';
                                $rsFP1315 = '';
                                $rsFP1330 = '';
                                $rsFP1345 = '';

                                if ($result_seFP1300['CNT'] != '0') {
                                    $rsFP1300 = "background-color: yellow";
                                }
                                if ($result_seFP1315['CNT'] != '0') {
                                    $rsFP1315 = "background-color: yellow";
                                }
                                if ($result_seFP1330['CNT'] != '0') {
                                    $rsFP1330 = "background-color: yellow";
                                }
                                if ($result_seFP1345['CNT'] != '0') {
                                    $rsFP1345 = "background-color: yellow";
                                }

                                $sql_seFP1400 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1400 = array();
                                $query_seFP1400 = sqlsrv_query($conn, $sql_seFP1400, $params_seFP1400);
                                $result_seFP1400 = sqlsrv_fetch_array($query_seFP1400, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1415 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1415 = array();
                                $query_seFP1415 = sqlsrv_query($conn, $sql_seFP1415, $params_seFP1415);
                                $result_seFP1415 = sqlsrv_fetch_array($query_seFP1415, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1430 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1430 = array();
                                $query_seFP1430 = sqlsrv_query($conn, $sql_seFP1430, $params_seFP1430);
                                $result_seFP1430 = sqlsrv_fetch_array($query_seFP1430, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1445 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1445 = array();
                                $query_seFP1445 = sqlsrv_query($conn, $sql_seFP1445, $params_seFP1445);
                                $result_seFP1445 = sqlsrv_fetch_array($query_seFP1445, SQLSRV_FETCH_ASSOC);

                                $rsFP1400 = '';
                                $rsFP1415 = '';
                                $rsFP1430 = '';
                                $rsFP1445 = '';

                                if ($result_seFP1400['CNT'] != '0') {
                                    $rsFP1400 = "background-color: yellow";
                                }
                                if ($result_seFP1415['CNT'] != '0') {
                                    $rsFP1415 = "background-color: yellow";
                                }
                                if ($result_seFP1430['CNT'] != '0') {
                                    $rsFP1430 = "background-color: yellow";
                                }
                                if ($result_seFP1445['CNT'] != '0') {
                                    $rsFP1445 = "background-color: yellow";
                                }

                                $sql_seFP1500 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1500 = array();
                                $query_seFP1500 = sqlsrv_query($conn, $sql_seFP1500, $params_seFP1500);
                                $result_seFP1500 = sqlsrv_fetch_array($query_seFP1500, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1515 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1515 = array();
                                $query_seFP1515 = sqlsrv_query($conn, $sql_seFP1515, $params_seFP1515);
                                $result_seFP1515 = sqlsrv_fetch_array($query_seFP1515, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1530 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1530 = array();
                                $query_seFP1530 = sqlsrv_query($conn, $sql_seFP1530, $params_seFP1530);
                                $result_seFP1530 = sqlsrv_fetch_array($query_seFP1530, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1545 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1545 = array();
                                $query_seFP1545 = sqlsrv_query($conn, $sql_seFP1545, $params_seFP1545);
                                $result_seFP1545 = sqlsrv_fetch_array($query_seFP1545, SQLSRV_FETCH_ASSOC);

                                $rsFP1500 = '';
                                $rsFP1515 = '';
                                $rsFP1530 = '';
                                $rsFP1545 = '';

                                if ($result_seFP1500['CNT'] != '0') {
                                    $rsFP1500 = "background-color: yellow";
                                }
                                if ($result_seFP1515['CNT'] != '0') {
                                    $rsFP1515 = "background-color: yellow";
                                }
                                if ($result_seFP1530['CNT'] != '0') {
                                    $rsFP1530 = "background-color: yellow";
                                }
                                if ($result_seFP1545['CNT'] != '0') {
                                    $rsFP1545 = "background-color: yellow";
                                }

                                $sql_seFP1600 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1600 = array();
                                $query_seFP1600 = sqlsrv_query($conn, $sql_seFP1600, $params_seFP1600);
                                $result_seFP1600 = sqlsrv_fetch_array($query_seFP1600, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1615 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1615 = array();
                                $query_seFP1615 = sqlsrv_query($conn, $sql_seFP1615, $params_seFP1615);
                                $result_seFP1615 = sqlsrv_fetch_array($query_seFP1615, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1630 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1630 = array();
                                $query_seFP1630 = sqlsrv_query($conn, $sql_seFP1630, $params_seFP1630);
                                $result_seFP1630 = sqlsrv_fetch_array($query_seFP1630, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1645 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1645 = array();
                                $query_seFP1645 = sqlsrv_query($conn, $sql_seFP1645, $params_seFP1645);
                                $result_seFP1645 = sqlsrv_fetch_array($query_seFP1645, SQLSRV_FETCH_ASSOC);

                                $rsFP1600 = '';
                                $rsFP1615 = '';
                                $rsFP1630 = '';
                                $rsFP1645 = '';

                                if ($result_seFP1600['CNT'] != '0') {
                                    $rsFP1600 = "background-color: yellow";
                                }
                                if ($result_seFP1615['CNT'] != '0') {
                                    $rsFP1615 = "background-color: yellow";
                                }
                                if ($result_seFP1630['CNT'] != '0') {
                                    $rsFP1630 = "background-color: yellow";
                                }
                                if ($result_seFP1645['CNT'] != '0') {
                                    $rsFP1645 = "background-color: yellow";
                                }

                                $sql_seFP1700 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1700 = array();
                                $query_seFP1700 = sqlsrv_query($conn, $sql_seFP1700, $params_seFP1700);
                                $result_seFP1700 = sqlsrv_fetch_array($query_seFP1700, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1715 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1715 = array();
                                $query_seFP1715 = sqlsrv_query($conn, $sql_seFP1715, $params_seFP1715);
                                $result_seFP1715 = sqlsrv_fetch_array($query_seFP1715, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1730 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1730 = array();
                                $query_seFP1730 = sqlsrv_query($conn, $sql_seFP1730, $params_seFP1730);
                                $result_seFP1730 = sqlsrv_fetch_array($query_seFP1730, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1745 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1745 = array();
                                $query_seFP1745 = sqlsrv_query($conn, $sql_seFP1745, $params_seFP1745);
                                $result_seFP1745 = sqlsrv_fetch_array($query_seFP1745, SQLSRV_FETCH_ASSOC);

                                $rsFP1700 = '';
                                $rsFP1715 = '';
                                $rsFP1730 = '';
                                $rsFP1745 = '';

                                if ($result_seFP1700['CNT'] != '0') {
                                    $rsFP1700 = "background-color: yellow";
                                }
                                if ($result_seFP1715['CNT'] != '0') {
                                    $rsFP1715 = "background-color: yellow";
                                }
                                if ($result_seFP1730['CNT'] != '0') {
                                    $rsFP1730 = "background-color: yellow";
                                }
                                if ($result_seFP1745['CNT'] != '0') {
                                    $rsFP1745 = "background-color: yellow";
                                }

                                $sql_seFP1800 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1800 = array();
                                $query_seFP1800 = sqlsrv_query($conn, $sql_seFP1800, $params_seFP1800);
                                $result_seFP1800 = sqlsrv_fetch_array($query_seFP1800, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1815 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1815 = array();
                                $query_seFP1815 = sqlsrv_query($conn, $sql_seFP1815, $params_seFP1815);
                                $result_seFP1815 = sqlsrv_fetch_array($query_seFP1815, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1830 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1830 = array();
                                $query_seFP1830 = sqlsrv_query($conn, $sql_seFP1830, $params_seFP1830);
                                $result_seFP1830 = sqlsrv_fetch_array($query_seFP1830, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1845 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1845 = array();
                                $query_seFP1845 = sqlsrv_query($conn, $sql_seFP1845, $params_seFP1845);
                                $result_seFP1845 = sqlsrv_fetch_array($query_seFP1845, SQLSRV_FETCH_ASSOC);

                                $rsFP1800 = '';
                                $rsFP1815 = '';
                                $rsFP1830 = '';
                                $rsFP1845 = '';

                                if ($result_seFP1800['CNT'] != '0') {
                                    $rsFP1800 = "background-color: yellow";
                                }
                                if ($result_seFP1815['CNT'] != '0') {
                                    $rsFP1815 = "background-color: yellow";
                                }
                                if ($result_seFP1830['CNT'] != '0') {
                                    $rsFP1830 = "background-color: yellow";
                                }
                                if ($result_seFP1845['CNT'] != '0') {
                                    $rsFP1845 = "background-color: yellow";
                                }

                                $sql_seFP1900 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1900 = array();
                                $query_seFP1900 = sqlsrv_query($conn, $sql_seFP1900, $params_seFP1900);
                                $result_seFP1900 = sqlsrv_fetch_array($query_seFP1900, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1915 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1915 = array();
                                $query_seFP1915 = sqlsrv_query($conn, $sql_seFP1915, $params_seFP1915);
                                $result_seFP1915 = sqlsrv_fetch_array($query_seFP1915, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1930 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1930 = array();
                                $query_seFP1930 = sqlsrv_query($conn, $sql_seFP1930, $params_seFP1930);
                                $result_seFP1930 = sqlsrv_fetch_array($query_seFP1930, SQLSRV_FETCH_ASSOC);

                                $sql_seFP1945 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP1945 = array();
                                $query_seFP1945 = sqlsrv_query($conn, $sql_seFP1945, $params_seFP1945);
                                $result_seFP1945 = sqlsrv_fetch_array($query_seFP1945, SQLSRV_FETCH_ASSOC);

                                $rsFP1900 = '';
                                $rsFP1915 = '';
                                $rsFP1930 = '';
                                $rsFP1945 = '';

                                if ($result_seFP1900['CNT'] != '0') {
                                    $rsFP1900 = "background-color: yellow";
                                }
                                if ($result_seFP1915['CNT'] != '0') {
                                    $rsFP1915 = "background-color: yellow";
                                }
                                if ($result_seFP1930['CNT'] != '0') {
                                    $rsFP1930 = "background-color: yellow";
                                }
                                if ($result_seFP1945['CNT'] != '0') {
                                    $rsFP1945 = "background-color: yellow";
                                }

                                $sql_seFP2000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2000 = array();
                                $query_seFP2000 = sqlsrv_query($conn, $sql_seFP2000, $params_seFP2000);
                                $result_seFP2000 = sqlsrv_fetch_array($query_seFP2000, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2015 = array();
                                $query_seFP2015 = sqlsrv_query($conn, $sql_seFP2015, $params_seFP2015);
                                $result_seFP2015 = sqlsrv_fetch_array($query_seFP2015, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2030 = array();
                                $query_seFP2030 = sqlsrv_query($conn, $sql_seFP2030, $params_seFP2030);
                                $result_seFP2030 = sqlsrv_fetch_array($query_seFP2030, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2045 = array();
                                $query_seFP2045 = sqlsrv_query($conn, $sql_seFP2045, $params_seFP2045);
                                $result_seFP2045 = sqlsrv_fetch_array($query_seFP2045, SQLSRV_FETCH_ASSOC);

                                $rsFP2000 = '';
                                $rsFP2015 = '';
                                $rsFP2030 = '';
                                $rsFP2045 = '';

                                if ($result_seFP2000['CNT'] != '0') {
                                    $rsFP2000 = "background-color: yellow";
                                }
                                if ($result_seFP2015['CNT'] != '0') {
                                    $rsFP2015 = "background-color: yellow";
                                }
                                if ($result_seFP2030['CNT'] != '0') {
                                    $rsFP2030 = "background-color: yellow";
                                }
                                if ($result_seFP2045['CNT'] != '0') {
                                    $rsFP2045 = "background-color: yellow";
                                }

                                $sql_seFP2100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2100 = array();
                                $query_seFP2100 = sqlsrv_query($conn, $sql_seFP2100, $params_seFP2100);
                                $result_seFP2100 = sqlsrv_fetch_array($query_seFP2100, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2115 = array();
                                $query_seFP2115 = sqlsrv_query($conn, $sql_seFP2115, $params_seFP2115);
                                $result_seFP2115 = sqlsrv_fetch_array($query_seFP2115, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2130 = array();
                                $query_seFP2130 = sqlsrv_query($conn, $sql_seFP2130, $params_seFP2130);
                                $result_seFP2130 = sqlsrv_fetch_array($query_seFP2130, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2145 = array();
                                $query_seFP2145 = sqlsrv_query($conn, $sql_seFP2145, $params_seFP2145);
                                $result_seFP2145 = sqlsrv_fetch_array($query_seFP2145, SQLSRV_FETCH_ASSOC);

                                $rsFP2100 = '';
                                $rsFP2115 = '';
                                $rsFP2130 = '';
                                $rsFP2145 = '';

                                if ($result_seFP2100['CNT'] != '0') {
                                    $rsFP2100 = "background-color: yellow";
                                }
                                if ($result_seFP2115['CNT'] != '0') {
                                    $rsFP2115 = "background-color: yellow";
                                }
                                if ($result_seFP2130['CNT'] != '0') {
                                    $rsFP2130 = "background-color: yellow";
                                }
                                if ($result_seFP2145['CNT'] != '0') {
                                    $rsFP2145 = "background-color: yellow";
                                }

                                $sql_seFP2200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2200 = array();
                                $query_seFP2200 = sqlsrv_query($conn, $sql_seFP2200, $params_seFP2200);
                                $result_seFP2200 = sqlsrv_fetch_array($query_seFP2200, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2215 = array();
                                $query_seFP2215 = sqlsrv_query($conn, $sql_seFP2215, $params_seFP2215);
                                $result_seFP2215 = sqlsrv_fetch_array($query_seFP2215, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2230 = array();
                                $query_seFP2230 = sqlsrv_query($conn, $sql_seFP2230, $params_seFP2230);
                                $result_seFP2230 = sqlsrv_fetch_array($query_seFP2230, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2245 = array();
                                $query_seFP2245 = sqlsrv_query($conn, $sql_seFP2245, $params_seFP2245);
                                $result_seFP2245 = sqlsrv_fetch_array($query_seFP2245, SQLSRV_FETCH_ASSOC);

                                $rsFP2200 = '';
                                $rsFP2215 = '';
                                $rsFP2230 = '';
                                $rsFP2245 = '';

                                if ($result_seFP2200['CNT'] != '0') {
                                    $rsFP2200 = "background-color: yellow";
                                }
                                if ($result_seFP2215['CNT'] != '0') {
                                    $rsFP2215 = "background-color: yellow";
                                }
                                if ($result_seFP2230['CNT'] != '0') {
                                    $rsFP2230 = "background-color: yellow";
                                }
                                if ($result_seFP2245['CNT'] != '0') {
                                    $rsFP2245 = "background-color: yellow";
                                }

                                $sql_seFP2300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:00',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2300 = array();
                                $query_seFP2300 = sqlsrv_query($conn, $sql_seFP2300, $params_seFP2300);
                                $result_seFP2300 = sqlsrv_fetch_array($query_seFP2300, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:15',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2315 = array();
                                $query_seFP2315 = sqlsrv_query($conn, $sql_seFP2315, $params_seFP2315);
                                $result_seFP2315 = sqlsrv_fetch_array($query_seFP2315, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:30',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2330 = array();
                                $query_seFP2330 = sqlsrv_query($conn, $sql_seFP2330, $params_seFP2330);
                                $result_seFP2330 = sqlsrv_fetch_array($query_seFP2330, SQLSRV_FETCH_ASSOC);

                                $sql_seFP2345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRCAUSE] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:45',103) BETWEEN CONVERT(CHAR(19), CONVERT(DATETIME, RPC_INCARDATE+' '+RPC_INCARTIME, 103), 120) AND CONVERT(CHAR(19), CONVERT(DATETIME, RPC_OUTCARDATE+' '+RPC_OUTCARTIME, 103), 120)
                                AND b.RPC_REMARK IS NULL AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seFP2345 = array();
                                $query_seFP2345 = sqlsrv_query($conn, $sql_seFP2345, $params_seFP2345);
                                $result_seFP2345 = sqlsrv_fetch_array($query_seFP2345, SQLSRV_FETCH_ASSOC);

                                $rsFP2300 = '';
                                $rsFP2315 = '';
                                $rsFP2330 = '';
                                $rsFP2345 = '';

                                if ($result_seFP2300['CNT'] != '0') {
                                    $rsFP2300 = "background-color: yellow";
                                }
                                if ($result_seFP2315['CNT'] != '0') {
                                    $rsFP2315 = "background-color: yellow";
                                }
                                if ($result_seFP2330['CNT'] != '0') {
                                    $rsFP2330 = "background-color: yellow";
                                }
                                if ($result_seFP2345['CNT'] != '0') {
                                    $rsFP2345 = "background-color: yellow";
                                }

                        // #################################################### OTHER ACTUALS2 ####################################################
                                
                                $sql_seAP0000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 00:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0000 = array();
                                $query_seAP0000 = sqlsrv_query($conn, $sql_seAP0000, $params_seAP0000);
                                $result_seAP0000 = sqlsrv_fetch_array($query_seAP0000, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 00:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0015 = array();
                                $query_seAP0015 = sqlsrv_query($conn, $sql_seAP0015, $params_seAP0015);
                                $result_seAP0015 = sqlsrv_fetch_array($query_seAP0015, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 00:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0030 = array();
                                $query_seAP0030 = sqlsrv_query($conn, $sql_seAP0030, $params_seAP0030);
                                $result_seAP0030 = sqlsrv_fetch_array($query_seAP0030, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 00:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 00:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0045 = array();
                                $query_seAP0045 = sqlsrv_query($conn, $sql_seAP0045, $params_seAP0045);
                                $result_seAP0045 = sqlsrv_fetch_array($query_seAP0045, SQLSRV_FETCH_ASSOC);

                                $rsAP0000 = '';
                                $rsAP0015 = '';
                                $rsAP0030 = '';
                                $rsAP0045 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0000['CNT'] != '0') {
                                        $rsAP0000 = "background-color: green";
                                    }
                                    if ($result_seAP0015['CNT'] != '0') {
                                        $rsAP0015 = "background-color: green";
                                    }
                                    if ($result_seAP0030['CNT'] != '0') {
                                        $rsAP0030 = "background-color: green";
                                    }
                                    if ($result_seAP0045['CNT'] != '0') {
                                        $rsAP0045 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0000['CNT'] != '0') {
                                        $rsAP0000 = "background-color: blue";
                                    }
                                    if ($result_seAP0015['CNT'] != '0') {
                                        $rsAP0015 = "background-color: blue";
                                    }
                                    if ($result_seAP0030['CNT'] != '0') {
                                        $rsAP0030 = "background-color: blue";
                                    }
                                    if ($result_seAP0045['CNT'] != '0') {
                                        $rsAP0045 = "background-color: blue";
                                    }
                                }
                                
                                $sql_seAP0100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 01:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0100 = array();
                                $query_seAP0100 = sqlsrv_query($conn, $sql_seAP0100, $params_seAP0100);
                                $result_seAP0100 = sqlsrv_fetch_array($query_seAP0100, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 01:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0115 = array();
                                $query_seAP0115 = sqlsrv_query($conn, $sql_seAP0115, $params_seAP0115);
                                $result_seAP0115 = sqlsrv_fetch_array($query_seAP0115, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 01:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0130 = array();
                                $query_seAP0130 = sqlsrv_query($conn, $sql_seAP0130, $params_seAP0130);
                                $result_seAP0130 = sqlsrv_fetch_array($query_seAP0130, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 01:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 01:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0145 = array();
                                $query_seAP0145 = sqlsrv_query($conn, $sql_seAP0145, $params_seAP0145);
                                $result_seAP0145 = sqlsrv_fetch_array($query_seAP0145, SQLSRV_FETCH_ASSOC);

                                $rsAP0100 = '';
                                $rsAP0115 = '';
                                $rsAP0130 = '';
                                $rsAP0145 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0100['CNT'] != '0') {
                                        $rsAP0100 = "background-color: green";
                                    }
                                    if ($result_seAP0115['CNT'] != '0') {
                                        $rsAP0115 = "background-color: green";
                                    }
                                    if ($result_seAP0130['CNT'] != '0') {
                                        $rsAP0130 = "background-color: green";
                                    }
                                    if ($result_seAP0145['CNT'] != '0') {
                                        $rsAP0145 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0100['CNT'] != '0') {
                                        $rsAP0100 = "background-color: blue";
                                    }
                                    if ($result_seAP0115['CNT'] != '0') {
                                        $rsAP0115 = "background-color: blue";
                                    }
                                    if ($result_seAP0130['CNT'] != '0') {
                                        $rsAP0130 = "background-color: blue";
                                    }
                                    if ($result_seAP0145['CNT'] != '0') {
                                        $rsAP0145 = "background-color: blue";
                                    }
                                }
                                
                                $sql_seAP0200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 02:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0200 = array();
                                $query_seAP0200 = sqlsrv_query($conn, $sql_seAP0200, $params_seAP0200);
                                $result_seAP0200 = sqlsrv_fetch_array($query_seAP0200, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 02:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0215 = array();
                                $query_seAP0215 = sqlsrv_query($conn, $sql_seAP0215, $params_seAP0215);
                                $result_seAP0215 = sqlsrv_fetch_array($query_seAP0215, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 02:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0230 = array();
                                $query_seAP0230 = sqlsrv_query($conn, $sql_seAP0230, $params_seAP0230);
                                $result_seAP0230 = sqlsrv_fetch_array($query_seAP0230, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 02:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 02:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0245 = array();
                                $query_seAP0245 = sqlsrv_query($conn, $sql_seAP0245, $params_seAP0245);
                                $result_seAP0245 = sqlsrv_fetch_array($query_seAP0245, SQLSRV_FETCH_ASSOC);

                                $rsAP0200 = '';
                                $rsAP0215 = '';
                                $rsAP0230 = '';
                                $rsAP0245 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0200['CNT'] != '0') {
                                        $rsAP0200 = "background-color: green";
                                    }
                                    if ($result_seAP0215['CNT'] != '0') {
                                        $rsAP0215 = "background-color: green";
                                    }
                                    if ($result_seAP0230['CNT'] != '0') {
                                        $rsAP0230 = "background-color: green";
                                    }
                                    if ($result_seAP0245['CNT'] != '0') {
                                        $rsAP0245 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0200['CNT'] != '0') {
                                        $rsAP0200 = "background-color: blue";
                                    }
                                    if ($result_seAP0215['CNT'] != '0') {
                                        $rsAP0215 = "background-color: blue";
                                    }
                                    if ($result_seAP0230['CNT'] != '0') {
                                        $rsAP0230 = "background-color: blue";
                                    }
                                    if ($result_seAP0245['CNT'] != '0') {
                                        $rsAP0245 = "background-color: blue";
                                    }
                                }
                                
                                $sql_seAP0300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 03:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0300 = array();
                                $query_seAP0300 = sqlsrv_query($conn, $sql_seAP0300, $params_seAP0300);
                                $result_seAP0300 = sqlsrv_fetch_array($query_seAP0300, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 03:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0315 = array();
                                $query_seAP0315 = sqlsrv_query($conn, $sql_seAP0315, $params_seAP0315);
                                $result_seAP0315 = sqlsrv_fetch_array($query_seAP0315, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 03:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0330 = array();
                                $query_seAP0330 = sqlsrv_query($conn, $sql_seAP0330, $params_seAP0330);
                                $result_seAP0330 = sqlsrv_fetch_array($query_seAP0330, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 03:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 03:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0345 = array();
                                $query_seAP0345 = sqlsrv_query($conn, $sql_seAP0345, $params_seAP0345);
                                $result_seAP0345 = sqlsrv_fetch_array($query_seAP0345, SQLSRV_FETCH_ASSOC);

                                $rsAP0300 = '';
                                $rsAP0315 = '';
                                $rsAP0330 = '';
                                $rsAP0345 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0300['CNT'] != '0') {
                                        $rsAP0300 = "background-color: green";
                                    }
                                    if ($result_seAP0315['CNT'] != '0') {
                                        $rsAP0315 = "background-color: green";
                                    }
                                    if ($result_seAP0330['CNT'] != '0') {
                                        $rsAP0330 = "background-color: green";
                                    }
                                    if ($result_seAP0345['CNT'] != '0') {
                                        $rsAP0345 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0300['CNT'] != '0') {
                                        $rsAP0300 = "background-color: blue";
                                    }
                                    if ($result_seAP0315['CNT'] != '0') {
                                        $rsAP0315 = "background-color: blue";
                                    }
                                    if ($result_seAP0330['CNT'] != '0') {
                                        $rsAP0330 = "background-color: blue";
                                    }
                                    if ($result_seAP0345['CNT'] != '0') {
                                        $rsAP0345 = "background-color: blue";
                                    }
                                }
                                
                                $sql_seAP0400 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 04:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0400 = array();
                                $query_seAP0400 = sqlsrv_query($conn, $sql_seAP0400, $params_seAP0400);
                                $result_seAP0400 = sqlsrv_fetch_array($query_seAP0400, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0415 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 04:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0415 = array();
                                $query_seAP0415 = sqlsrv_query($conn, $sql_seAP0415, $params_seAP0415);
                                $result_seAP0415 = sqlsrv_fetch_array($query_seAP0415, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0430 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 04:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0430 = array();
                                $query_seAP0430 = sqlsrv_query($conn, $sql_seAP0430, $params_seAP0430);
                                $result_seAP0430 = sqlsrv_fetch_array($query_seAP0430, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0445 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 04:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 04:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0445 = array();
                                $query_seAP0445 = sqlsrv_query($conn, $sql_seAP0445, $params_seAP0445);
                                $result_seAP0445 = sqlsrv_fetch_array($query_seAP0445, SQLSRV_FETCH_ASSOC);

                                $rsAP0400 = '';
                                $rsAP0415 = '';
                                $rsAP0430 = '';
                                $rsAP0445 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0400['CNT'] != '0') {
                                        $rsAP0400 = "background-color: green";
                                    }
                                    if ($result_seAP0415['CNT'] != '0') {
                                        $rsAP0415 = "background-color: green";
                                    }
                                    if ($result_seAP0430['CNT'] != '0') {
                                        $rsAP0430 = "background-color: green";
                                    }
                                    if ($result_seAP0445['CNT'] != '0') {
                                        $rsAP0445 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0400['CNT'] != '0') {
                                        $rsAP0400 = "background-color: blue";
                                    }
                                    if ($result_seAP0415['CNT'] != '0') {
                                        $rsAP0415 = "background-color: blue";
                                    }
                                    if ($result_seAP0430['CNT'] != '0') {
                                        $rsAP0430 = "background-color: blue";
                                    }
                                    if ($result_seAP0445['CNT'] != '0') {
                                        $rsAP0445 = "background-color: blue";
                                    }
                                }
                                
                                $sql_seAP0500 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 05:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0500 = array();
                                $query_seAP0500 = sqlsrv_query($conn, $sql_seAP0500, $params_seAP0500);
                                $result_seAP0500 = sqlsrv_fetch_array($query_seAP0500, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0515 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 05:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0515 = array();
                                $query_seAP0515 = sqlsrv_query($conn, $sql_seAP0515, $params_seAP0515);
                                $result_seAP0515 = sqlsrv_fetch_array($query_seAP0515, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0530 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 05:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0530 = array();
                                $query_seAP0530 = sqlsrv_query($conn, $sql_seAP0530, $params_seAP0530);
                                $result_seAP0530 = sqlsrv_fetch_array($query_seAP0530, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0545 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 05:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 05:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0545 = array();
                                $query_seAP0545 = sqlsrv_query($conn, $sql_seAP0545, $params_seAP0545);
                                $result_seAP0545 = sqlsrv_fetch_array($query_seAP0545, SQLSRV_FETCH_ASSOC);

                                $rsAP0500 = '';
                                $rsAP0515 = '';
                                $rsAP0530 = '';
                                $rsAP0545 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0500['CNT'] != '0') {
                                        $rsAP0500 = "background-color: green";
                                    }
                                    if ($result_seAP0515['CNT'] != '0') {
                                        $rsAP0515 = "background-color: green";
                                    }
                                    if ($result_seAP0530['CNT'] != '0') {
                                        $rsAP0530 = "background-color: green";
                                    }
                                    if ($result_seAP0545['CNT'] != '0') {
                                        $rsAP0545 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0500['CNT'] != '0') {
                                        $rsAP0500 = "background-color: blue";
                                    }
                                    if ($result_seAP0515['CNT'] != '0') {
                                        $rsAP0515 = "background-color: blue";
                                    }
                                    if ($result_seAP0530['CNT'] != '0') {
                                        $rsAP0530 = "background-color: blue";
                                    }
                                    if ($result_seAP0545['CNT'] != '0') {
                                        $rsAP0545 = "background-color: blue";
                                    }
                                }

                                $sql_seAP0600 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 06:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0600 = array();
                                $query_seAP0600 = sqlsrv_query($conn, $sql_seAP0600, $params_seAP0600);
                                $result_seAP0600 = sqlsrv_fetch_array($query_seAP0600, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0615 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 06:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0615 = array();
                                $query_seAP0615 = sqlsrv_query($conn, $sql_seAP0615, $params_seAP0615);
                                $result_seAP0615 = sqlsrv_fetch_array($query_seAP0615, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0630 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 06:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0630 = array();
                                $query_seAP0630 = sqlsrv_query($conn, $sql_seAP0630, $params_seAP0630);
                                $result_seAP0630 = sqlsrv_fetch_array($query_seAP0630, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0645 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 06:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 06:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0645 = array();
                                $query_seAP0645 = sqlsrv_query($conn, $sql_seAP0645, $params_seAP0645);
                                $result_seAP0645 = sqlsrv_fetch_array($query_seAP0645, SQLSRV_FETCH_ASSOC);

                                $rsAP0600 = '';
                                $rsAP0615 = '';
                                $rsAP0630 = '';
                                $rsAP0645 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0600['CNT'] != '0') {
                                        $rsAP0600 = "background-color: green";
                                    }
                                    if ($result_seAP0615['CNT'] != '0') {
                                        $rsAP0615 = "background-color: green";
                                    }
                                    if ($result_seAP0630['CNT'] != '0') {
                                        $rsAP0630 = "background-color: green";
                                    }
                                    if ($result_seAP0645['CNT'] != '0') {
                                        $rsAP0645 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0600['CNT'] != '0') {
                                        $rsAP0600 = "background-color: blue";
                                    }
                                    if ($result_seAP0615['CNT'] != '0') {
                                        $rsAP0615 = "background-color: blue";
                                    }
                                    if ($result_seAP0630['CNT'] != '0') {
                                        $rsAP0630 = "background-color: blue";
                                    }
                                    if ($result_seAP0645['CNT'] != '0') {
                                        $rsAP0645 = "background-color: blue";
                                    }
                                }

                                $sql_seAP0700 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 07:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0700 = array();
                                $query_seAP0700 = sqlsrv_query($conn, $sql_seAP0700, $params_seAP0700);
                                $result_seAP0700 = sqlsrv_fetch_array($query_seAP0700, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0715 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 07:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0715 = array();
                                $query_seAP0715 = sqlsrv_query($conn, $sql_seAP0715, $params_seAP0715);
                                $result_seAP0715 = sqlsrv_fetch_array($query_seAP0715, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0730 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 07:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0730 = array();
                                $query_seAP0730 = sqlsrv_query($conn, $sql_seAP0730, $params_seAP0730);
                                $result_seAP0730 = sqlsrv_fetch_array($query_seAP0730, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0745 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 07:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 07:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0745 = array();
                                $query_seAP0745 = sqlsrv_query($conn, $sql_seAP0745, $params_seAP0745);
                                $result_seAP0745 = sqlsrv_fetch_array($query_seAP0745, SQLSRV_FETCH_ASSOC);

                                $rsAP0700 = '';
                                $rsAP0715 = '';
                                $rsAP0730 = '';
                                $rsAP0745 = '';

                                        if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                            if ($result_seAP0700['CNT'] != '0') {
                                                $rsAP0700 = "background-color: green";
                                            }
                                            if ($result_seAP0715['CNT'] != '0') {
                                                $rsAP0715 = "background-color: green";
                                            }
                                            if ($result_seAP0730['CNT'] != '0') {
                                                $rsAP0730 = "background-color: green";
                                            }
                                            if ($result_seAP0745['CNT'] != '0') {
                                                $rsAP0745 = "background-color: green";
                                            }
                                        } else {
                                            if ($result_seAP0700['CNT'] != '0') {
                                                $rsAP0700 = "background-color: blue";
                                            }
                                            if ($result_seAP0715['CNT'] != '0') {
                                                $rsAP0715 = "background-color: blue";
                                            }
                                            if ($result_seAP0730['CNT'] != '0') {
                                                $rsAP0730 = "background-color: blue";
                                            }
                                            if ($result_seAP0745['CNT'] != '0') {
                                                $rsAP0745 = "background-color: blue";
                                            }
                                        }

                                $sql_seAP0800 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 08:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0800 = array();
                                $query_seAP0800 = sqlsrv_query($conn, $sql_seAP0800, $params_seAP0800);
                                $result_seAP0800 = sqlsrv_fetch_array($query_seAP0800, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0815 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 08:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0815 = array();
                                $query_seAP0815 = sqlsrv_query($conn, $sql_seAP0815, $params_seAP0815);
                                $result_seAP0815 = sqlsrv_fetch_array($query_seAP0815, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0830 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 08:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0830 = array();
                                $query_seAP0830 = sqlsrv_query($conn, $sql_seAP0830, $params_seAP0830);
                                $result_seAP0830 = sqlsrv_fetch_array($query_seAP0830, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0845 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 08:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 08:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0845 = array();
                                $query_seAP0845 = sqlsrv_query($conn, $sql_seAP0845, $params_seAP0845);
                                $result_seAP0845 = sqlsrv_fetch_array($query_seAP0845, SQLSRV_FETCH_ASSOC);

                                $rsAP0800 = '';
                                $rsAP0815 = '';
                                $rsAP0830 = '';
                                $rsAP0845 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0800['CNT'] != '0') {
                                        $rsAP0800 = "background-color: green";
                                    }
                                    if ($result_seAP0815['CNT'] != '0') {
                                        $rsAP0815 = "background-color: green";
                                    }
                                    if ($result_seAP0830['CNT'] != '0') {
                                        $rsAP0830 = "background-color: green";
                                    }
                                    if ($result_seAP0845['CNT'] != '0') {
                                        $rsAP0845 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0800['CNT'] != '0') {
                                        $rsAP0800 = "background-color: blue";
                                    }
                                    if ($result_seAP0815['CNT'] != '0') {
                                        $rsAP0815 = "background-color: blue";
                                    }
                                    if ($result_seAP0830['CNT'] != '0') {
                                        $rsAP0830 = "background-color: blue";
                                    }
                                    if ($result_seAP0845['CNT'] != '0') {
                                        $rsAP0845 = "background-color: blue";
                                    }
                                }

                                $sql_seAP0900 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 09:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0900 = array();
                                $query_seAP0900 = sqlsrv_query($conn, $sql_seAP0900, $params_seAP0900);
                                $result_seAP0900 = sqlsrv_fetch_array($query_seAP0900, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0915 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 09:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0915 = array();
                                $query_seAP0915 = sqlsrv_query($conn, $sql_seAP0915, $params_seAP0915);
                                $result_seAP0915 = sqlsrv_fetch_array($query_seAP0915, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0930 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 09:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0930 = array();
                                $query_seAP0930 = sqlsrv_query($conn, $sql_seAP0930, $params_seAP0930);
                                $result_seAP0930 = sqlsrv_fetch_array($query_seAP0930, SQLSRV_FETCH_ASSOC);

                                $sql_seAP0945 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 09:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 09:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP0945 = array();
                                $query_seAP0945 = sqlsrv_query($conn, $sql_seAP0945, $params_seAP0945);
                                $result_seAP0945 = sqlsrv_fetch_array($query_seAP0945, SQLSRV_FETCH_ASSOC);



                                $rsAP0900 = '';
                                $rsAP0915 = '';
                                $rsAP0930 = '';
                                $rsAP0945 = '';



                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP0900['CNT'] != '0') {
                                        $rsAP0900 = "background-color: green";
                                    }
                                    if ($result_seAP0915['CNT'] != '0') {
                                        $rsAP0915 = "background-color: green";
                                    }
                                    if ($result_seAP0930['CNT'] != '0') {
                                        $rsAP0930 = "background-color: green";
                                    }
                                    if ($result_seAP0945['CNT'] != '0') {
                                        $rsAP0945 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP0900['CNT'] != '0') {
                                        $rsAP0900 = "background-color: blue";
                                    }
                                    if ($result_seAP0915['CNT'] != '0') {
                                        $rsAP0915 = "background-color: blue";
                                    }
                                    if ($result_seAP0930['CNT'] != '0') {
                                        $rsAP0930 = "background-color: blue";
                                    }
                                    if ($result_seAP0945['CNT'] != '0') {
                                        $rsAP0945 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 10:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1000 = array();
                                $query_seAP1000 = sqlsrv_query($conn, $sql_seAP1000, $params_seAP1000);
                                $result_seAP1000 = sqlsrv_fetch_array($query_seAP1000, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 10:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1015 = array();
                                $query_seAP1015 = sqlsrv_query($conn, $sql_seAP1015, $params_seAP1015);
                                $result_seAP1015 = sqlsrv_fetch_array($query_seAP1015, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 10:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1030 = array();
                                $query_seAP1030 = sqlsrv_query($conn, $sql_seAP1030, $params_seAP1030);
                                $result_seAP1030 = sqlsrv_fetch_array($query_seAP1030, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 10:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 10:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1045 = array();
                                $query_seAP1045 = sqlsrv_query($conn, $sql_seAP1045, $params_seAP1045);
                                $result_seAP1045 = sqlsrv_fetch_array($query_seAP1045, SQLSRV_FETCH_ASSOC);

                                $rsAP1000 = '';
                                $rsAP1015 = '';
                                $rsAP1030 = '';
                                $rsAP1045 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1000['CNT'] != '0') {
                                        $rsAP1000 = "background-color: green";
                                    }
                                    if ($result_seAP1015['CNT'] != '0') {
                                        $rsAP1015 = "background-color: green";
                                    }
                                    if ($result_seAP1030['CNT'] != '0') {
                                        $rsAP1030 = "background-color: green";
                                    }
                                    if ($result_seAP1045['CNT'] != '0') {
                                        $rsAP1045 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1000['CNT'] != '0') {
                                        $rsAP1000 = "background-color: blue";
                                    }
                                    if ($result_seAP1015['CNT'] != '0') {
                                        $rsAP1015 = "background-color: blue";
                                    }
                                    if ($result_seAP1030['CNT'] != '0') {
                                        $rsAP1030 = "background-color: blue";
                                    }
                                    if ($result_seAP1045['CNT'] != '0') {
                                        $rsAP1045 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 11:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1100 = array();
                                $query_seAP1100 = sqlsrv_query($conn, $sql_seAP1100, $params_seAP1100);
                                $result_seAP1100 = sqlsrv_fetch_array($query_seAP1100, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 11:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1115 = array();
                                $query_seAP1115 = sqlsrv_query($conn, $sql_seAP1115, $params_seAP1115);
                                $result_seAP1115 = sqlsrv_fetch_array($query_seAP1115, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 11:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1130 = array();
                                $query_seAP1130 = sqlsrv_query($conn, $sql_seAP1130, $params_seAP1130);
                                $result_seAP1130 = sqlsrv_fetch_array($query_seAP1130, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 11:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 11:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1145 = array();
                                $query_seAP1145 = sqlsrv_query($conn, $sql_seAP1145, $params_seAP1145);
                                $result_seAP1145 = sqlsrv_fetch_array($query_seAP1145, SQLSRV_FETCH_ASSOC);

                                $rsAP1100 = '';
                                $rsAP1115 = '';
                                $rsAP1130 = '';
                                $rsAP1145 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1100['CNT'] != '0') {
                                        $rsAP1100 = "background-color: green";
                                    }
                                    if ($result_seAP1115['CNT'] != '0') {
                                        $rsAP1115 = "background-color: green";
                                    }
                                    if ($result_seAP1130['CNT'] != '0') {
                                        $rsAP1130 = "background-color: green";
                                    }
                                    if ($result_seAP1145['CNT'] != '0') {
                                        $rsAP1145 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1100['CNT'] != '0') {
                                        $rsAP1100 = "background-color: blue";
                                    }
                                    if ($result_seAP1115['CNT'] != '0') {
                                        $rsAP1115 = "background-color: blue";
                                    }
                                    if ($result_seAP1130['CNT'] != '0') {
                                        $rsAP1130 = "background-color: blue";
                                    }
                                    if ($result_seAP1145['CNT'] != '0') {
                                        $rsAP1145 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 12:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1200 = array();
                                $query_seAP1200 = sqlsrv_query($conn, $sql_seAP1200, $params_seAP1200);
                                $result_seAP1200 = sqlsrv_fetch_array($query_seAP1200, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 12:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1215 = array();
                                $query_seAP1215 = sqlsrv_query($conn, $sql_seAP1215, $params_seAP1215);
                                $result_seAP1215 = sqlsrv_fetch_array($query_seAP1215, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 12:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1230 = array();
                                $query_seAP1230 = sqlsrv_query($conn, $sql_seAP1230, $params_seAP1230);
                                $result_seAP1230 = sqlsrv_fetch_array($query_seAP1230, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 12:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 12:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1245 = array();
                                $query_seAP1245 = sqlsrv_query($conn, $sql_seAP1245, $params_seAP1245);
                                $result_seAP1245 = sqlsrv_fetch_array($query_seAP1245, SQLSRV_FETCH_ASSOC);

                                $rsAP1200 = '';
                                $rsAP1215 = '';
                                $rsAP1230 = '';
                                $rsAP1245 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1200['CNT'] != '0') {
                                        $rsAP1200 = "background-color: green";
                                    }
                                    if ($result_seAP1215['CNT'] != '0') {
                                        $rsAP1215 = "background-color: green";
                                    }
                                    if ($result_seAP1230['CNT'] != '0') {
                                        $rsAP1230 = "background-color: green";
                                    }
                                    if ($result_seAP1245['CNT'] != '0') {
                                        $rsAP1245 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1200['CNT'] != '0') {
                                        $rsAP1200 = "background-color: blue";
                                    }
                                    if ($result_seAP1215['CNT'] != '0') {
                                        $rsAP1215 = "background-color: blue";
                                    }
                                    if ($result_seAP1230['CNT'] != '0') {
                                        $rsAP1230 = "background-color: blue";
                                    }
                                    if ($result_seAP1245['CNT'] != '0') {
                                        $rsAP1245 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 13:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1300 = array();
                                $query_seAP1300 = sqlsrv_query($conn, $sql_seAP1300, $params_seAP1300);
                                $result_seAP1300 = sqlsrv_fetch_array($query_seAP1300, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 13:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1315 = array();
                                $query_seAP1315 = sqlsrv_query($conn, $sql_seAP1315, $params_seAP1315);
                                $result_seAP1315 = sqlsrv_fetch_array($query_seAP1315, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 13:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1330 = array();
                                $query_seAP1330 = sqlsrv_query($conn, $sql_seAP1330, $params_seAP1330);
                                $result_seAP1330 = sqlsrv_fetch_array($query_seAP1330, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 13:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 13:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1345 = array();
                                $query_seAP1345 = sqlsrv_query($conn, $sql_seAP1345, $params_seAP1345);
                                $result_seAP1345 = sqlsrv_fetch_array($query_seAP1345, SQLSRV_FETCH_ASSOC);

                                $rsAP1300 = '';
                                $rsAP1315 = '';
                                $rsAP1330 = '';
                                $rsAP1345 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1300['CNT'] != '0') {
                                        $rsAP1300 = "background-color: green";
                                    }
                                    if ($result_seAP1315['CNT'] != '0') {
                                        $rsAP1315 = "background-color: green";
                                    }
                                    if ($result_seAP1330['CNT'] != '0') {
                                        $rsAP1330 = "background-color: green";
                                    }
                                    if ($result_seAP1345['CNT'] != '0') {
                                        $rsAP1345 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1300['CNT'] != '0') {
                                        $rsAP1300 = "background-color: blue";
                                    }
                                    if ($result_seAP1315['CNT'] != '0') {
                                        $rsAP1315 = "background-color: blue";
                                    }
                                    if ($result_seAP1330['CNT'] != '0') {
                                        $rsAP1330 = "background-color: blue";
                                    }
                                    if ($result_seAP1345['CNT'] != '0') {
                                        $rsAP1345 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1400 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 14:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1400 = array();
                                $query_seAP1400 = sqlsrv_query($conn, $sql_seAP1400, $params_seAP1400);
                                $result_seAP1400 = sqlsrv_fetch_array($query_seAP1400, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1415 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 14:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1415 = array();
                                $query_seAP1415 = sqlsrv_query($conn, $sql_seAP1415, $params_seAP1415);
                                $result_seAP1415 = sqlsrv_fetch_array($query_seAP1415, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1430 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 14:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1430 = array();
                                $query_seAP1430 = sqlsrv_query($conn, $sql_seAP1430, $params_seAP1430);
                                $result_seAP1430 = sqlsrv_fetch_array($query_seAP1430, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1445 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 14:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 14:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1445 = array();
                                $query_seAP1445 = sqlsrv_query($conn, $sql_seAP1445, $params_seAP1445);
                                $result_seAP1445 = sqlsrv_fetch_array($query_seAP1445, SQLSRV_FETCH_ASSOC);

                                $rsAP1400 = '';
                                $rsAP1415 = '';
                                $rsAP1430 = '';
                                $rsAP1445 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1400['CNT'] != '0') {
                                        $rsAP1400 = "background-color: green";
                                    }
                                    if ($result_seAP1415['CNT'] != '0') {
                                        $rsAP1415 = "background-color: green";
                                    }
                                    if ($result_seAP1430['CNT'] != '0') {
                                        $rsAP1430 = "background-color: green";
                                    }
                                    if ($result_seAP1445['CNT'] != '0') {
                                        $rsAP1445 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1400['CNT'] != '0') {
                                        $rsAP1400 = "background-color: blue";
                                    }
                                    if ($result_seAP1415['CNT'] != '0') {
                                        $rsAP1415 = "background-color: blue";
                                    }
                                    if ($result_seAP1430['CNT'] != '0') {
                                        $rsAP1430 = "background-color: blue";
                                    }
                                    if ($result_seAP1445['CNT'] != '0') {
                                        $rsAP1445 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1500 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 15:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1500 = array();
                                $query_seAP1500 = sqlsrv_query($conn, $sql_seAP1500, $params_seAP1500);
                                $result_seAP1500 = sqlsrv_fetch_array($query_seAP1500, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1515 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 15:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1515 = array();
                                $query_seAP1515 = sqlsrv_query($conn, $sql_seAP1515, $params_seAP1515);
                                $result_seAP1515 = sqlsrv_fetch_array($query_seAP1515, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1530 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 15:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1530 = array();
                                $query_seAP1530 = sqlsrv_query($conn, $sql_seAP1530, $params_seAP1530);
                                $result_seAP1530 = sqlsrv_fetch_array($query_seAP1530, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1545 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 15:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 15:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1545 = array();
                                $query_seAP1545 = sqlsrv_query($conn, $sql_seAP1545, $params_seAP1545);
                                $result_seAP1545 = sqlsrv_fetch_array($query_seAP1545, SQLSRV_FETCH_ASSOC);

                                $rsAP1500 = '';
                                $rsAP1515 = '';
                                $rsAP1530 = '';
                                $rsAP1545 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1500['CNT'] != '0') {
                                        $rsAP1500 = "background-color: green";
                                    }
                                    if ($result_seAP1515['CNT'] != '0') {
                                        $rsAP1515 = "background-color: green";
                                    }
                                    if ($result_seAP1530['CNT'] != '0') {
                                        $rsAP1530 = "background-color: green";
                                    }
                                    if ($result_seAP1545['CNT'] != '0') {
                                        $rsAP1545 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1500['CNT'] != '0') {
                                        $rsAP1500 = "background-color: blue";
                                    }
                                    if ($result_seAP1515['CNT'] != '0') {
                                        $rsAP1515 = "background-color: blue";
                                    }
                                    if ($result_seAP1530['CNT'] != '0') {
                                        $rsAP1530 = "background-color: blue";
                                    }
                                    if ($result_seAP1545['CNT'] != '0') {
                                        $rsAP1545 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1600 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 16:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1600 = array();
                                $query_seAP1600 = sqlsrv_query($conn, $sql_seAP1600, $params_seAP1600);
                                $result_seAP1600 = sqlsrv_fetch_array($query_seAP1600, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1615 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 16:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1615 = array();
                                $query_seAP1615 = sqlsrv_query($conn, $sql_seAP1615, $params_seAP1615);
                                $result_seAP1615 = sqlsrv_fetch_array($query_seAP1615, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1630 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 16:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1630 = array();
                                $query_seAP1630 = sqlsrv_query($conn, $sql_seAP1630, $params_seAP1630);
                                $result_seAP1630 = sqlsrv_fetch_array($query_seAP1630, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1645 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 16:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 16:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1645 = array();
                                $query_seAP1645 = sqlsrv_query($conn, $sql_seAP1645, $params_seAP1645);
                                $result_seAP1645 = sqlsrv_fetch_array($query_seAP1645, SQLSRV_FETCH_ASSOC);

                                $rsAP1600 = '';
                                $rsAP1615 = '';
                                $rsAP1630 = '';
                                $rsAP1645 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1600['CNT'] != '0') {
                                        $rsAP1600 = "background-color: green";
                                    }
                                    if ($result_seAP1615['CNT'] != '0') {
                                        $rsAP1615 = "background-color: green";
                                    }
                                    if ($result_seAP1630['CNT'] != '0') {
                                        $rsAP1630 = "background-color: green";
                                    }
                                    if ($result_seAP1645['CNT'] != '0') {
                                        $rsAP1645 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1600['CNT'] != '0') {
                                        $rsAP1600 = "background-color: blue";
                                    }
                                    if ($result_seAP1615['CNT'] != '0') {
                                        $rsAP1615 = "background-color: blue";
                                    }
                                    if ($result_seAP1630['CNT'] != '0') {
                                        $rsAP1630 = "background-color: blue";
                                    }
                                    if ($result_seAP1645['CNT'] != '0') {
                                        $rsAP1645 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1700 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 17:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1700 = array();
                                $query_seAP1700 = sqlsrv_query($conn, $sql_seAP1700, $params_seAP1700);
                                $result_seAP1700 = sqlsrv_fetch_array($query_seAP1700, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1715 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 17:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1715 = array();
                                $query_seAP1715 = sqlsrv_query($conn, $sql_seAP1715, $params_seAP1715);
                                $result_seAP1715 = sqlsrv_fetch_array($query_seAP1715, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1730 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 17:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1730 = array();
                                $query_seAP1730 = sqlsrv_query($conn, $sql_seAP1730, $params_seAP1730);
                                $result_seAP1730 = sqlsrv_fetch_array($query_seAP1730, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1745 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 17:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 17:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1745 = array();
                                $query_seAP1745 = sqlsrv_query($conn, $sql_seAP1745, $params_seAP1745);
                                $result_seAP1745 = sqlsrv_fetch_array($query_seAP1745, SQLSRV_FETCH_ASSOC);

                                $rsAP1700 = '';
                                $rsAP1715 = '';
                                $rsAP1730 = '';
                                $rsAP1745 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1700['CNT'] != '0') {
                                        $rsAP1700 = "background-color: green";
                                    }
                                    if ($result_seAP1715['CNT'] != '0') {
                                        $rsAP1715 = "background-color: green";
                                    }
                                    if ($result_seAP1730['CNT'] != '0') {
                                        $rsAP1730 = "background-color: green";
                                    }
                                    if ($result_seAP1745['CNT'] != '0') {
                                        $rsAP1745 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1700['CNT'] != '0') {
                                        $rsAP1700 = "background-color: blue";
                                    }
                                    if ($result_seAP1715['CNT'] != '0') {
                                        $rsAP1715 = "background-color: blue";
                                    }
                                    if ($result_seAP1730['CNT'] != '0') {
                                        $rsAP1730 = "background-color: blue";
                                    }
                                    if ($result_seAP1745['CNT'] != '0') {
                                        $rsAP1745 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1800 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 18:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1800 = array();
                                $query_seAP1800 = sqlsrv_query($conn, $sql_seAP1800, $params_seAP1800);
                                $result_seAP1800 = sqlsrv_fetch_array($query_seAP1800, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1815 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 18:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1815 = array();
                                $query_seAP1815 = sqlsrv_query($conn, $sql_seAP1815, $params_seAP1815);
                                $result_seAP1815 = sqlsrv_fetch_array($query_seAP1815, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1830 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 18:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1830 = array();
                                $query_seAP1830 = sqlsrv_query($conn, $sql_seAP1830, $params_seAP1830);
                                $result_seAP1830 = sqlsrv_fetch_array($query_seAP1830, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1845 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 18:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 18:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1845 = array();
                                $query_seAP1845 = sqlsrv_query($conn, $sql_seAP1845, $params_seAP1845);
                                $result_seAP1845 = sqlsrv_fetch_array($query_seAP1845, SQLSRV_FETCH_ASSOC);

                                $rsAP1800 = '';
                                $rsAP1815 = '';
                                $rsAP1830 = '';
                                $rsAP1845 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1800['CNT'] != '0') {
                                        $rsAP1800 = "background-color: green";
                                    }
                                    if ($result_seAP1815['CNT'] != '0') {
                                        $rsAP1815 = "background-color: green";
                                    }
                                    if ($result_seAP1830['CNT'] != '0') {
                                        $rsAP1830 = "background-color: green";
                                    }
                                    if ($result_seAP1845['CNT'] != '0') {
                                        $rsAP1845 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1800['CNT'] != '0') {
                                        $rsAP1800 = "background-color: blue";
                                    }
                                    if ($result_seAP1815['CNT'] != '0') {
                                        $rsAP1815 = "background-color: blue";
                                    }
                                    if ($result_seAP1830['CNT'] != '0') {
                                        $rsAP1830 = "background-color: blue";
                                    }
                                    if ($result_seAP1845['CNT'] != '0') {
                                        $rsAP1845 = "background-color: blue";
                                    }
                                }

                                $sql_seAP1900 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 19:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1900 = array();
                                $query_seAP1900 = sqlsrv_query($conn, $sql_seAP1900, $params_seAP1900);
                                $result_seAP1900 = sqlsrv_fetch_array($query_seAP1900, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1915 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 19:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1915 = array();
                                $query_seAP1915 = sqlsrv_query($conn, $sql_seAP1915, $params_seAP1915);
                                $result_seAP1915 = sqlsrv_fetch_array($query_seAP1915, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1930 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 19:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1930 = array();
                                $query_seAP1930 = sqlsrv_query($conn, $sql_seAP1930, $params_seAP1930);
                                $result_seAP1930 = sqlsrv_fetch_array($query_seAP1930, SQLSRV_FETCH_ASSOC);

                                $sql_seAP1945 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 19:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 19:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP1945 = array();
                                $query_seAP1945 = sqlsrv_query($conn, $sql_seAP1945, $params_seAP1945);
                                $result_seAP1945 = sqlsrv_fetch_array($query_seAP1945, SQLSRV_FETCH_ASSOC);

                                $rsAP1900 = '';
                                $rsAP1915 = '';
                                $rsAP1930 = '';
                                $rsAP1945 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP1900['CNT'] != '0') {
                                        $rsAP1900 = "background-color: green";
                                    }
                                    if ($result_seAP1915['CNT'] != '0') {
                                        $rsAP1915 = "background-color: green";
                                    }
                                    if ($result_seAP1930['CNT'] != '0') {
                                        $rsAP1930 = "background-color: green";
                                    }
                                    if ($result_seAP1945['CNT'] != '0') {
                                        $rsAP1945 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP1900['CNT'] != '0') {
                                        $rsAP1900 = "background-color: blue";
                                    }
                                    if ($result_seAP1915['CNT'] != '0') {
                                        $rsAP1915 = "background-color: blue";
                                    }
                                    if ($result_seAP1930['CNT'] != '0') {
                                        $rsAP1930 = "background-color: blue";
                                    }
                                    if ($result_seAP1945['CNT'] != '0') {
                                        $rsAP1945 = "background-color: blue";
                                    }
                                }

                                $sql_seAP2000 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 20:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2000 = array();
                                $query_seAP2000 = sqlsrv_query($conn, $sql_seAP2000, $params_seAP2000);
                                $result_seAP2000 = sqlsrv_fetch_array($query_seAP2000, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2015 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 20:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2015 = array();
                                $query_seAP2015 = sqlsrv_query($conn, $sql_seAP2015, $params_seAP2015);
                                $result_seAP2015 = sqlsrv_fetch_array($query_seAP2015, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2030 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 20:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2030 = array();
                                $query_seAP2030 = sqlsrv_query($conn, $sql_seAP2030, $params_seAP2030);
                                $result_seAP2030 = sqlsrv_fetch_array($query_seAP2030, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2045 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 20:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 20:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2045 = array();
                                $query_seAP2045 = sqlsrv_query($conn, $sql_seAP2045, $params_seAP2045);
                                $result_seAP2045 = sqlsrv_fetch_array($query_seAP2045, SQLSRV_FETCH_ASSOC);

                                $rsAP2000 = '';
                                $rsAP2015 = '';
                                $rsAP2030 = '';
                                $rsAP2045 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP2000['CNT'] != '0') {
                                        $rsAP2000 = "background-color: green";
                                    }
                                    if ($result_seAP2015['CNT'] != '0') {
                                        $rsAP2015 = "background-color: green";
                                    }
                                    if ($result_seAP2030['CNT'] != '0') {
                                        $rsAP2030 = "background-color: green";
                                    }
                                    if ($result_seAP2045['CNT'] != '0') {
                                        $rsAP2045 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP2000['CNT'] != '0') {
                                        $rsAP2000 = "background-color: blue";
                                    }
                                    if ($result_seAP2015['CNT'] != '0') {
                                        $rsAP2015 = "background-color: blue";
                                    }
                                    if ($result_seAP2030['CNT'] != '0') {
                                        $rsAP2030 = "background-color: blue";
                                    }
                                    if ($result_seAP2045['CNT'] != '0') {
                                        $rsAP2045 = "background-color: blue";
                                    }
                                }

                                $sql_seAP2100 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 21:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2100 = array();
                                $query_seAP2100 = sqlsrv_query($conn, $sql_seAP2100, $params_seAP2100);
                                $result_seAP2100 = sqlsrv_fetch_array($query_seAP2100, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2115 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 21:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2115 = array();
                                $query_seAP2115 = sqlsrv_query($conn, $sql_seAP2115, $params_seAP2115);
                                $result_seAP2115 = sqlsrv_fetch_array($query_seAP2115, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2130 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 21:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2130 = array();
                                $query_seAP2130 = sqlsrv_query($conn, $sql_seAP2130, $params_seAP2130);
                                $result_seAP2130 = sqlsrv_fetch_array($query_seAP2130, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2145 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 21:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 21:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2145 = array();
                                $query_seAP2145 = sqlsrv_query($conn, $sql_seAP2145, $params_seAP2145);
                                $result_seAP2145 = sqlsrv_fetch_array($query_seAP2145, SQLSRV_FETCH_ASSOC);

                                $rsAP2100 = '';
                                $rsAP2115 = '';
                                $rsAP2130 = '';
                                $rsAP2145 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP2100['CNT'] != '0') {
                                        $rsAP2100 = "background-color: green";
                                    }
                                    if ($result_seAP2115['CNT'] != '0') {
                                        $rsAP2115 = "background-color: green";
                                    }
                                    if ($result_seAP2130['CNT'] != '0') {
                                        $rsAP2130 = "background-color: green";
                                    }
                                    if ($result_seAP2145['CNT'] != '0') {
                                        $rsAP2145 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP2100['CNT'] != '0') {
                                        $rsAP2100 = "background-color: blue";
                                    }
                                    if ($result_seAP2115['CNT'] != '0') {
                                        $rsAP2115 = "background-color: blue";
                                    }
                                    if ($result_seAP2130['CNT'] != '0') {
                                        $rsAP2130 = "background-color: blue";
                                    }
                                    if ($result_seAP2145['CNT'] != '0') {
                                        $rsAP2145 = "background-color: blue";
                                    }
                                }

                                $sql_seAP2200 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 22:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2200 = array();
                                $query_seAP2200 = sqlsrv_query($conn, $sql_seAP2200, $params_seAP2200);
                                $result_seAP2200 = sqlsrv_fetch_array($query_seAP2200, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2215 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 22:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2215 = array();
                                $query_seAP2215 = sqlsrv_query($conn, $sql_seAP2215, $params_seAP2215);
                                $result_seAP2215 = sqlsrv_fetch_array($query_seAP2215, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2230 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 22:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2230 = array();
                                $query_seAP2230 = sqlsrv_query($conn, $sql_seAP2230, $params_seAP2230);
                                $result_seAP2230 = sqlsrv_fetch_array($query_seAP2230, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2245 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 22:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 22:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2245 = array();
                                $query_seAP2245 = sqlsrv_query($conn, $sql_seAP2245, $params_seAP2245);
                                $result_seAP2245 = sqlsrv_fetch_array($query_seAP2245, SQLSRV_FETCH_ASSOC);

                                $rsAP2200 = '';
                                $rsAP2215 = '';
                                $rsAP2230 = '';
                                $rsAP2245 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP2200['CNT'] != '0') {
                                        $rsAP2200 = "background-color: green";
                                    }
                                    if ($result_seAP2215['CNT'] != '0') {
                                        $rsAP2215 = "background-color: green";
                                    }
                                    if ($result_seAP2230['CNT'] != '0') {
                                        $rsAP2230 = "background-color: green";
                                    }
                                    if ($result_seAP2245['CNT'] != '0') {
                                        $rsAP2245 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP2200['CNT'] != '0') {
                                        $rsAP2200 = "background-color: blue";
                                    }
                                    if ($result_seAP2215['CNT'] != '0') {
                                        $rsAP2215 = "background-color: blue";
                                    }
                                    if ($result_seAP2230['CNT'] != '0') {
                                        $rsAP2230 = "background-color: blue";
                                    }
                                    if ($result_seAP2245['CNT'] != '0') {
                                        $rsAP2245 = "background-color: blue";
                                    }
                                }

                                $sql_seAP2300 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:00',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 23:00',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2300 = array();
                                $query_seAP2300 = sqlsrv_query($conn, $sql_seAP2300, $params_seAP2300);
                                $result_seAP2300 = sqlsrv_fetch_array($query_seAP2300, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2315 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:15',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 23:15',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2315 = array();
                                $query_seAP2315 = sqlsrv_query($conn, $sql_seAP2315, $params_seAP2315);
                                $result_seAP2315 = sqlsrv_fetch_array($query_seAP2315, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2330 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:30',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 23:30',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2330 = array();
                                $query_seAP2330 = sqlsrv_query($conn, $sql_seAP2330, $params_seAP2330);
                                $result_seAP2330 = sqlsrv_fetch_array($query_seAP2330, SQLSRV_FETCH_ASSOC);

                                $sql_seAP2345 = "SELECT COUNT(*) AS 'CNT' FROM [dbo].[REPAIRACTUAL_TIME] b
                                WHERE CONVERT(DATETIME,'".$dateinput."' + ' 23:45',103) BETWEEN DATEADD(MINUTE,-16,(SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'START')) AND CONVERT(DATETIME,GETDATE(),103) 
                                AND ((SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') IS NULL OR (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE = b.RPRQ_CODE AND RPC_SUBJECT = b.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') > CONVERT(DATETIME,'".$dateinput."' + ' 23:45',103))
                                AND b.[RPRQ_CODE] = '".$result_seS2['RPRQ_CODE']."' AND b.[RPC_SUBJECT]='".$result_seS2['RPC_SUBJECT']."'";
                                $params_seAP2345 = array();
                                $query_seAP2345 = sqlsrv_query($conn, $sql_seAP2345, $params_seAP2345);
                                $result_seAP2345 = sqlsrv_fetch_array($query_seAP2345, SQLSRV_FETCH_ASSOC);

                                $rsAP2300 = '';
                                $rsAP2315 = '';
                                $rsAP2330 = '';
                                $rsAP2345 = '';

                                if ($result_seActS2['RPATTM_GROUP'] == 'SUCCESS') {
                                    if ($result_seAP2300['CNT'] != '0') {
                                        $rsAP2300 = "background-color: green";
                                    }
                                    if ($result_seAP2315['CNT'] != '0') {
                                        $rsAP2315 = "background-color: green";
                                    }
                                    if ($result_seAP2330['CNT'] != '0') {
                                        $rsAP2330 = "background-color: green";
                                    }
                                    if ($result_seAP2345['CNT'] != '0') {
                                        $rsAP2345 = "background-color: green";
                                    }
                                } else {
                                    if ($result_seAP2300['CNT'] != '0') {
                                        $rsAP2300 = "background-color: blue";
                                    }
                                    if ($result_seAP2315['CNT'] != '0') {
                                        $rsAP2315 = "background-color: blue";
                                    }
                                    if ($result_seAP2330['CNT'] != '0') {
                                        $rsAP2330 = "background-color: blue";
                                    }
                                    if ($result_seAP2345['CNT'] != '0') {
                                        $rsAP2345 = "background-color: blue";
                                    }
                                }

                                if ($result_seS2['VEHICLEREGISNUMBER1'] != '') {
                                    $VEHICLEREGISNUMBERS21 = $result_seS2['VEHICLEREGISNUMBER1'] . '(1)';
                                } else {
                                    $VEHICLEREGISNUMBERS21 = '';
                                }
                                if ($result_seS2['VEHICLEREGISNUMBER2'] != '') {
                                    $VEHICLEREGISNUMBERS22 = $result_seS2['VEHICLEREGISNUMBER2'] . '(2)';
                                } else {
                                    $VEHICLEREGISNUMBERS22 = '';
                                }

                                if ($result_seS2['VEHICLEREGISNUMBER1'] == '' || $result_seS2['VEHICLEREGISNUMBER2'] == '') {
                                    $commas2 = '';
                                } else {
                                    $commas2 = ',';
                                }
                    ?>
                    <tr>
                        <td style="text-align: center;" rowspan="2"><?= $iS2 ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seS2['VEHICLEREGISNUMBER1'].'/'.$result_seS2['THAINAME1'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seS2['VEHICLEREGISNUMBER2'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $customercode2 ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seS2['SUBJECT'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seCauS2['DETAIL'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $rsdrivename2 ?> <?php if($rsdrivename2!=''){echo '/';}?> <?= $rsdrivecard2 ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seS2['CH'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seEmpS2['CNT'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seS2['REPAIRMAN'] ?></td>
                        <td style="text-align: center;" rowspan="2"><?= $result_seS2['TECHNICIANEMPLOYEE'] ?></td>
                        <td style="text-align: center;">แผน</td>
                            
                        <td width="0%" style="text-align: center;<?= $rsFP0000 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0015 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0030 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0045 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0100 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0115 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0130 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0145 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0200 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0215 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0230 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0245 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0300 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0315 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0330 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0345 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0400 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0415 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0430 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0445 ?>"></td>
                        
                        <td width="0%" style="text-align: center;<?= $rsFP0500 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0515 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0530 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0545 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0600 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0615 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0630 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0645 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0700 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0715 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0730 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0745 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0800 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0815 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0830 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0845 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0900 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0915 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0930 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP0945 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1000 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1015 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1030 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1045 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1100 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1115 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1130 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1145 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1200 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1215 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1230 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1245 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1300 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1315 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1330 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1345 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1400 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1415 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1430 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1445 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1500 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1515 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1530 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1545 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1600 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1615 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1630 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1645 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1700 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1715 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1730 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1745 ?>"></td>
                            
                        <td width="0%" style="text-align: center;<?= $rsFP1800 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1815 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1830 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1845 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1900 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1915 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1930 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP1945 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2000 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2015 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2030 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2045 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2100 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2115 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2130 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2145 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2200 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2215 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2230 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2245 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2300 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2315 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2330 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsFP2345 ?>"></td>
                            
                        <td style="text-align: center;"><?= $hours2 . ' : ' . $minutes2 ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">ทำจริง</td>
                            
                        <td width="0%" style="text-align: center;<?= $rsAP0000 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0015 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0030 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0045 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0100 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0115 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0130 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0145 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0200 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0215 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0230 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0245 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0300 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0315 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0330 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0345 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0400 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0415 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0430 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0445 ?>"></td>
                        
                        <td width="0%" style="text-align: center;<?= $rsAP0500 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0515 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0530 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0545 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0600 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0615 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0630 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0645 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0700 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0715 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0730 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0745 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0800 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0815 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0830 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0845 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0900 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0915 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0930 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP0945 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1000 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1015 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1030 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1045 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1100 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1115 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1130 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1145 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1200 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1215 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1230 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1245 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1300 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1315 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1330 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1345 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1400 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1415 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1430 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1445 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1500 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1515 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1530 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1545 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1600 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1615 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1630 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1645 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1700 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1715 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1730 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1745 ?>"></td>
                            
                        <td width="0%" style="text-align: center;<?= $rsAP1800 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1815 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1830 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1845 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1900 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1915 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1930 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP1945 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2000 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2015 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2030 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2045 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2100 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2115 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2130 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2145 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2200 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2215 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2230 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2245 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2300 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2315 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2330 ?>"></td>
                        <td width="0%" style="text-align: center;<?= $rsAP2345 ?>"></td>

                        <?php if ($result_seHouracS2['OPENDATE'] == '') { ?>
                        <td style="text-align: center;"></td>
                        <?php } else { ?>
                        <td style="text-align: center;"><?= $houracs2 . ' : ' . $minuteacs2 ?></td>
                        <?php } ?>
                    </tr>
                    <?php $iS2++; } ?>
                    </tbody>
                </table>
            <!-- REMARK ############################################################################################################################## -->
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="text-align: left;"><b>หมายเหตุ : ช่างทำงานเต็มประสิทธภาพ</b></th>
                        </tr>
                    </thead>
                </table>
                <br>
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="text-align: left;"><b>สรุปแผนและผลการทำงาน</b></th>
                        </tr>
                    </thead>
                </table>
                <table class="border" border="1" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="border" border="1" colspan="2" rowspan="2" style="text-align: center;width: 10%">จำนวนช่าง</th>
                            <th class="border" border="1" colspan="2" rowspan="2" style="text-align: center;width: 10%">ชั่วโมง</th>
                            <th class="border" border="1" colspan="2" rowspan="2" style="text-align: center;width: 10%">รวมชั่วโมง</th>
                            <th class="border" border="1" colspan="2" rowspan="2" style="text-align: center;width: 10%">จำนวนคัน</th>
                            <th class="border" border="1" colspan="2" rowspan="2" style="text-align: center;width: 10%">จำนวน JOB</th>
                            <th class="border" border="1" colspan="3" rowspan="2" style="text-align: center;width: 20%">งานนอกเหนือแผน</th>
                            <th class="border" border="1" colspan="10" style="text-align: center;width: 40%">สรุป</th>
                        </tr>
                        <tr>
                            <th class="border" border="1" colspan="2" style="text-align: center;">ช่าง</th>
                            <th class="border" border="1" colspan="2" style="text-align: center;">ชั่วโมง</th>
                            <th class="border" border="1" colspan="2" style="text-align: center;">จำนวนคัน</th>
                            <th class="border" border="1" colspan="2" style="text-align: center;">จำนวน JOB</th>
                        </tr>
                        <tr>
                            <th class="border" border="1" style="text-align: center;">แผน</th>
                            <th class="border" border="1" style="text-align: center;">ผล</th>
                            <th class="border" border="1" style="text-align: center;">แผน</th>
                            <th class="border" border="1" style="text-align: center;">ผล</th>
                            <th class="border" border="1" style="text-align: center;">แผน</th>
                            <th class="border" border="1" style="text-align: center;">ผล</th>
                            <th class="border" border="1" style="text-align: center;">แผน</th>
                            <th class="border" border="1" style="text-align: center;">ผล</th>
                            <th class="border" border="1" style="text-align: center;">แผน</th>
                            <th class="border" border="1" style="text-align: center;">ผล</th>
                            <th class="border" border="1" style="text-align: center;">ชั่วโมง</th>
                            <th class="border" border="1" style="text-align: center;">คัน</th>
                            <th class="border" border="1" style="text-align: center;">JOB</th>
                            <th class="border" border="1" style="text-align: center;">แผน</th>
                            <th class="border" border="1" style="text-align: center;">ผล</th>
                            <th class="border" border="1" style="text-align: center;">แผน</th>
                            <th class="border" border="1" style="text-align: center;">ผล</th>
                            <th class="border" border="1" style="text-align: center;">แผน</th>
                            <th class="border" border="1" style="text-align: center;">ผล</th>
                            <th class="border" border="1" style="text-align: center;">แผน</th>
                            <th class="border" border="1" style="text-align: center;">ผล</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center;"><?= $cntemps1 + $cntemps2 ?></td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;"><?= $result_seCnthour['CNT'] ?></td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;"><?= $result_seCnthour['CNT'] ?></td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;"><?= $result_seCntjob['CNT'] ?></td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;"><?= $result_seCntjob['CNT'] ?></td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">OT</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;"><?= $result_seCntemp['CNT'] ?></td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;"><?= $result_seCnthour['CNT'] ?></td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;"><?= $result_seCntjob['CNT'] ?></td>
                            <td style="text-align: center;">&nbsp;</td>
                            <td style="text-align: center;"><?= $result_seCntjob['CNT'] ?></td>
                            <td style="text-align: center;">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="datasr"></div>
        </div>
    </body>
</html>
