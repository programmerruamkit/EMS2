<?php

session_name("EMS"); session_start();
$path = "../";   	
require($path.'../include/connect.php');
$SESSION_AREA = $_SESSION["AD_AREA"];
$txt_datestart_amt=$_GET['txt_datestart_amt'];
$txt_dateend_amt=$_GET['txt_dateend_amt'];
// echo"<pre>";
// print_r($_GET);
// echo"</pre>";
// exit();
$strExcelFileName = "รายงานรายละเอียดประมาณการรายได้(".$SESSION_AREA.")" . $txt_datestart_amt . ' ถึง ' . $txt_dateend_amt . ".xls";

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
        <table  border="1"  style="width: 100%;">
            <thead>
                <tr>
                    <th colspan="21" style="text-align: center;background-color: #dedede">รายงานข้อมูลสรุปแผนงาน <?=$SESSION_AREA?> (ประจำวันที่ <?= $txt_datestart_amt ?> - <?= $txt_dateend_amt ?>)</th>
                </tr>
                <tr>   
                    <!-- <th rowspan="2" style="text-align:center;background-color: #dedede">แผน</th> -->
                    <th rowspan="2" style="text-align:center;background-color: #dedede">ช่องซ่อม</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">ทะเบียน(หัว)</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">ลูกค้า</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">ลักษณะงานซ่อม</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">รายละเอียด</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">ประเภทงานซ่อม</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">จำนวนช่าง</th>
                    <th colspan="3" style="text-align:center;background-color: #dedede">PLAN</th>
                    <th colspan="3" style="text-align:center;background-color: #dedede">ACTUAL</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">Dif.</th>
                    <th colspan="2" style="text-align:center;background-color: #dedede">ประมาณรายได้</th>
                    <th colspan="2" style="text-align:center;background-color: #dedede">ประสิทธิภาพช่าง(ชม.)</th>
                    <th colspan="2" style="text-align:center;background-color: #dedede">ประสิทธิภาพช่องซ่อม</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">Dif.</th>

                </tr>
                <tr>
                    <th style="text-align:center;background-color: #dedede">เวลาเริ่ม</th>
                    <th style="text-align:center;background-color: #dedede">เวลาสิ้นสุด</th>
                    <th style="text-align:center;background-color: #dedede">ชม.</th>
                    <th style="text-align:center;background-color: #dedede">เวลาเริ่ม</th>
                    <th style="text-align:center;background-color: #dedede">เวลาสิ้นสุด</th>
                    <th style="text-align:center;background-color: #dedede">ชม.</th>
                    <th style="text-align:center;background-color: #dedede">ค่าอะไหล่/Job</th>
                    <th style="text-align:center;background-color: #dedede">ค่าแรง/Job</th>
                    <th style="text-align:center;background-color: #dedede">Cap/Mec</th>
                    <th style="text-align:center;background-color: #dedede">Act</th>
                    <th style="text-align:center;background-color: #dedede">Cap(Hrs.)</th>
                    <th style="text-align:center;background-color: #dedede">Act(Hrs.)</th>
                </tr>


            </thead>
            <tbody>
                <?php
                    $i = 1;        
                    $sql_seRepairplan = "SELECT DISTINCT
                            a.RPRQ_ID REPAIRPLANID,
                            a.RPRQ_CODE,
                            a.RPRQ_LINEOFWORK COMPANY,
                            a.RPRQ_COMPANYCASH COMPANYPAYMENT,
                            a.RPRQ_AREA AREA,
                            a.RPRQ_CARTYPE VEHICLETYPE,
                            c.RPC_AREA REPAIRAREA,
                            a.RPRQ_REGISHEAD VEHICLEREGISNUMBER1,
                            a.RPRQ_REGISTAIL VEHICLEREGISNUMBER2,
                            a.RPRQ_CARNAMEHEAD THAINAME1,
                            a.RPRQ_CARNAMETAIL THAINAME2,
                            a.RPRQ_TYPECUSTOMER JOBTYPE,
                            a.RPRQ_WORKTYPE REPAIRTYPE,
                            a.RPRQ_MILEAGEFINISH RANKPM,
                            a.RPRQ_RANKPMTYPE,
                            '' REPAIRTIME,
                            c.RPC_INCARDATE,
                            c.RPC_INCARTIME,
                            c.RPC_INCARDATE +' '+ c.RPC_INCARTIME TIMEPLANSTART,
                            c.RPC_OUTCARDATE,
                            c.RPC_OUTCARTIME,
                            c.RPC_OUTCARDATE +' '+ c.RPC_OUTCARTIME TIMEPLANEND,                            
                            DATEDIFF(MINUTE, c.RPC_INCARTIME, c.RPC_OUTCARTIME) AS 'PLANMINUTE',                            
                            CONVERT(VARCHAR(10),CONVERT(date, c.RPC_INCARDATE, 105),23),
                            c.RPC_SUBJECT SUBJ,
                            CASE
                                WHEN c.RPC_SUBJECT = 'EL' THEN 'ระบบไฟ'
                                WHEN c.RPC_SUBJECT = 'TU' THEN 'ยาง-ช่วงล่าง'
                                WHEN c.RPC_SUBJECT = 'BD' THEN 'โครงสร้าง'
                                WHEN c.RPC_SUBJECT = 'EG' THEN 'เครื่องยนต์'
                                WHEN c.RPC_SUBJECT = 'AC' THEN 'อุปกรณ์ประจำรถ'
                                ELSE c.RPC_SUBJECT
                            END SUBJECT,
                            c.RPC_DETAIL DETAIL,
                            c.RPC_INCARDATE CARINPUTDATE
                        FROM
                            REPAIRREQUEST a
                            INNER JOIN REPAIRCAUSE c ON c.RPRQ_CODE = a.RPRQ_CODE
                        WHERE
                            a.RPRQ_AREA = '$SESSION_AREA' AND a.RPRQ_STATUS = 'Y'
                            AND CONVERT(VARCHAR(10),CONVERT(date, c.RPC_INCARDATE, 105),23) BETWEEN CONVERT(VARCHAR(10),CONVERT(date, '$txt_datestart_amt', 105),23) AND CONVERT(VARCHAR(10),CONVERT(date, '$txt_dateend_amt', 105),23)";                                            
                    $params_seRepairplan = array();
                    $query_seRepairplan = sqlsrv_query($conn, $sql_seRepairplan, $params_seRepairplan);
                    while ($result_seRepairplan = sqlsrv_fetch_array($query_seRepairplan, SQLSRV_FETCH_ASSOC)) {
                        $SUBJ=$result_seRepairplan['SUBJ'];
                        $RPRQ_CODE=$result_seRepairplan['RPRQ_CODE'];

                        $PLANHOUR        = ($result_seRepairplan['PLANMINUTE']/60);                            
                            // $PLANCARINPUTDATE   = $result_seRepairplan['RPC_INCARDATE'];
                            // $PLANCARINPUTTIME   = $result_seRepairplan['RPC_INCARTIME'];
                            // $PLANCOMPLETEDDATE  = $result_seRepairplan['RPC_OUTCARDATE'];
                            // $PLANCOMPLETEDTIME  = $result_seRepairplan['RPC_OUTCARTIME'];
                            // $PLANTIMEIN         = $PLANCARINPUTTIME;
                            // $PLANTIMEOUT        = $PLANCOMPLETEDTIME;
                            // $PLANT1             = strtotime($PLANTIMEIN);
                            // $PLANT2             = strtotime($PLANTIMEOUT);
                            // $PLANT3             = round( abs($PLANT2 - $PLANT1) / 3600, 2 );
                            // $PLANCAL            = $PLANT3*60;	
                            // $PLANINCARDATETIME  = $PLANCARINPUTDATE.$PLANTIMEIN; 
                            // $PLANROUNDCAL       = round($PLANCAL);
                            // if(isset($PLANCARINPUTDATE)&&isset($PLANCOMPLETEDDATE)){
                            //     // $PLANHOUR = date("i:s" , mktime(0,0,$PLANROUNDCAL,0,0,0));
                            // }else{
                            //     // $PLANHOUR ='';
                            // }
                        //เช็คแผนว่าเป็น PM ไหน
                            if ($result_seRepairplan['REPAIRTYPE'] == 'PM') {
                                $REPAIRTYPE = $result_seRepairplan['RPRQ_RANKPMTYPE'];
                            }else{
                                $REPAIRTYPE = $result_seRepairplan['REPAIRTYPE'];
                            }     

                        // หาจำนวนช่างในแต่ละแผนงาน และ ประเภทงานซ่อม
                            $sql_seCountMechanic    = "SELECT COUNT(DISTINCT RPME_CODE) AS 'COUNTMEC' 
                            FROM REPAIRMANEMP 
                            WHERE RPRQ_CODE  = '$RPRQ_CODE'
                            AND RPC_SUBJECT = '$SUBJ'";
                            $params_seCountMechanic = array();
                            $query_seCountMechanic  = sqlsrv_query($conn, $sql_seCountMechanic, $params_seCountMechanic);
                            $result_seCountMechanic = sqlsrv_fetch_array($query_seCountMechanic, SQLSRV_FETCH_ASSOC);
                        
                        // หารายชื่อช่างในแต่ละแผนงาน
                            $sql_seTechnician = "SELECT DISTINCT(RPME_NAME) AS 'TECHICIANNAME'
                                FROM REPAIRMANEMP
                                WHERE RPRQ_CODE  = '$RPRQ_CODE' AND RPC_SUBJECT = '$SUBJ'";
                            $params_seTechnician  = array();
                            $query_seTechnician   = sqlsrv_query($conn, $sql_seTechnician, $params_seTechnician);                            
                            $TECHICIAN = '';
                            while ($result_seTechnician = sqlsrv_fetch_array($query_seTechnician, SQLSRV_FETCH_ASSOC)) {
                                $TECHICIAN = $TECHICIAN . $result_seTechnician['TECHICIANNAME'] . ",";
                            }
                        
                            // หาเวลา ACTUAL ในการเปิดปิดงาน
                            $sql_seActualtime ="SELECT DISTINCT A.RPRQ_CODE,A.RPC_SUBJECT, 		
                                (SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME	WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'START') AS MIN,
                                (SELECT MAX(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS') AS MAX,
                                DATEDIFF(MINUTE, 
                                (SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME	WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'START')
                                , 
                                (SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME	WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS')) AS 'PLANMINUTE'
                                FROM REPAIRACTUAL_TIME A WHERE A.RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$SUBJ'";
                            $params_seActualtime    = array();
                            $query_seActualtime     = sqlsrv_query($conn, $sql_seActualtime, $params_seActualtime);
                            $result_seActualtime    = sqlsrv_fetch_array($query_seActualtime, SQLSRV_FETCH_ASSOC);
                                $ACTUALMIN     = $result_seActualtime['MIN'];
                                $ACTUALMAX     = $result_seActualtime['MAX'];
                                $ACTUALHOUR     = ($result_seActualtime['PLANMINUTE']/60);
                        
                        // หาเวลารวมของ ACTUAL ในการเปิดปิดงาน คอลัมย์ Act(Hrs)
                            $sql_seSumActualtime = "SELECT SUM(a.ACTUALMINUTE) AS 'SUMACTUALMINUTE' FROM (SELECT DATEDIFF(MINUTE, 
                            (SELECT MIN(RPATTM_PROCESS) FROM dbo.REPAIRACTUAL_TIME	WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'START')
                            , 
                            (SELECT MAX(RPATTM_PROCESS) FROM	dbo.REPAIRACTUAL_TIME	WHERE RPRQ_CODE=A.RPRQ_CODE AND RPC_SUBJECT = A.RPC_SUBJECT AND RPATTM_GROUP = 'SUCCESS'))  AS 'ACTUALMINUTE'
                            FROM REPAIRACTUAL_TIME A WHERE A.RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$SUBJ') a";
                            $params_seSumActualtime = array();
                            $query_seSumActualtime = sqlsrv_query($conn, $sql_seSumActualtime, $params_seSumActualtime);
                            $result_seSumActualtime = sqlsrv_fetch_array($query_seSumActualtime, SQLSRV_FETCH_ASSOC);
                            
                                $PLANHOUR       = ($result_seRepairplan['PLANMINUTE']/60);
                                $ACTUALHOUR = ($result_seActualtime['PLANMINUTE']/60);
                                // $ACTUALHOUR     = ($result_seActualtime['ACTUALMINUTE']);
                                $SUMACTUALHOUR  =  ($result_seSumActualtime['SUMACTUALMINUTE']/60);
                                    
                        // หาประสิทธิภาพช่าง(ชม.)
                            if ($result_seRepairplan['ROWNUM'] > 1) {
                                $CapMec =  6 * $result_seCountMechanic['COUNTMEC'];
                                $CapAct = $ACTUALHOUR * $result_seCountMechanic['COUNTMEC'];
                                $CapHrs = '';
                                $ActHrs = '';
                                $SumDif = '';
                              }else {
                                $CapMec =  6 * $result_seCountMechanic['COUNTMEC'];
                                $CapAct = $ACTUALHOUR * $result_seCountMechanic['COUNTMEC'];
                                $CapHrs = '8';
                                $ActHrs = $SUMACTUALHOUR ;
                                $SumDif = $ActHrs-$CapHrs;
                              }
                        
                        // หาประสิทธิภาพช่าง(ชม.)
                            if ($result_seRepairplan['ROWNUM'] > 1) {
                                $CapMec =  6 * $result_seCountMechanic['COUNTMEC'];
                                $CapAct = $ACTUALHOUR * $result_seCountMechanic['COUNTMEC'];
                                $CapHrs = '';
                                $ActHrs = '';
                                $SumDif = '';
                            }else {
                                $CapMec =  6 * $result_seCountMechanic['COUNTMEC'];
                                $CapAct = $ACTUALHOUR * $result_seCountMechanic['COUNTMEC'];
                                $CapHrs = '8';
                                $ActHrs = $SUMACTUALHOUR ;
                                $SumDif = $ActHrs-$CapHrs;
                            }
                            
                        // ประมาณการ
                            $REPAIRPLANID=$result_seRepairplan['REPAIRPLANID'];
                            $SUBJECT=$result_seRepairplan['SUBJECT'];
             
                            $sql_selEstimate     = "SELECT DISTINCT
                                    RPETM_REPAIRMAN repairman,
                                    RPETM_HOUR hourmoney,
                                    RPETM_SPARESPART sparepartcost,
                                    RPETM_WAGE wage 
                                FROM REPAIRESTIMATE
                                WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPETM_NATURE = '".$result_seRepairplan['SUBJECT']."'";
                            $params_selEstimate  = array();
                            $query_selEstimate   = sqlsrv_query($conn, $sql_selEstimate, $params_selEstimate);                            
                                $repairman = '';
                                $hourmoney = '';
                                $sparepartcost = '';
                                $wage = '';
                            while ($result_selEstimate = sqlsrv_fetch_array($query_selEstimate, SQLSRV_FETCH_ASSOC)) {
                                $repairman = $repairman . $result_selEstimate['repairman'];
                                $hourmoney = $hourmoney . $result_selEstimate['hourmoney'];
                                $sparepartcost = $sparepartcost . $result_selEstimate['sparepartcost'];
                                $wage = $wage . $result_selEstimate['wage'];
                            }
                            $estimatecal=($hourmoney * $wage) + $sparepartcost;
                            if($estimatecal!='0'){
                                $estimaters=$estimatecal;
                            }else{
                                $estimaters='';
                            }                        
                        // ช่างขับรถ
                            $REPAIRPLANID=$result_seRepairplan['REPAIRPLANID'];
                            
                            $REPAIRAREA=$result_seRepairplan['REPAIRAREA'];
                            $REPAIRAREACUT = explode("-", $REPAIRAREA);
                            $REPAIRAREAX0 = $REPAIRAREACUT[0];
                            $REPAIRAREAX1 = $REPAIRAREACUT[1];
                            $REPAIRAREAX2 = $REPAIRAREACUT[2];
                            // echo $result_seRepairplan['REPAIRPLANID'];    

                            if ($result_seRepairplan['REPAIRTYPE'] == 'PM') {
                                $sql_chketmrpmdrive = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE='$RPRQ_CODE' AND RPMD_ZONE = '$REPAIRAREAX0'";
                            }else{
                                $sql_chketmrpmdrive = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE='$RPRQ_CODE'";
                            }     
                            $query_chketmrpmdrive = sqlsrv_query($conn, $sql_chketmrpmdrive);
                            $result_chketmrpmdrive = sqlsrv_fetch_array($query_chketmrpmdrive, SQLSRV_FETCH_ASSOC);
                            $RPMD_NAME = $result_chketmrpmdrive["RPMD_NAME"];           
                            $RPMD_CARLICENCE = $result_chketmrpmdrive["RPMD_CARLICENCE"];               
                            $rsdrivename = $RPMD_NAME;
                            $rsdrivecard = $RPMD_CARLICENCE;
                        ?>                            
                        <tr>
                            
                            <!-- <td style="text-align: left"><?=$result_seRepairplan['REPAIRPLANID']?></td> -->
                            <td style="text-align: left"><?=$result_seRepairplan['REPAIRAREA']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['VEHICLEREGISNUMBER1']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['COMPANY']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['SUBJECT']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['DETAIL']?></td>
                            <td style="text-align: center"><?=$REPAIRTYPE?></td>
                            <td style="text-align: center"><?=$result_seCountMechanic['COUNTMEC']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['TIMEPLANSTART']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['TIMEPLANEND']?></td>
                            <td style="text-align: center"><?=number_format( $PLANHOUR,2)?></td>
                            <td style="text-align: center"><?=$ACTUALMIN?></td>
                            <td style="text-align: center"><?=$ACTUALMAX?></td>
                            <td style="text-align: center"><?=number_format( $ACTUALHOUR,2)?></td>
                            <td style="text-align: center"><?=number_format( $ACTUALHOUR,2)-number_format( $PLANHOUR,2)?></td>
                            <td style="text-align: center"><?=number_format( $sparepartcost,2)?></td>
                            <td style="text-align: center"><?=number_format( $wage,2)?></td>
                            <td style="text-align: center"><?= number_format(($CapMec),2)?></td>
                            <td style="text-align: center"><?= number_format(($CapAct),2)?></td>                            
                            <td style="text-align: center"><?=$CapHrs?></td>
                            <td style="text-align: center"><?=number_format($ActHrs,2)?></td>
                            <td style="text-align: center"><?=number_format($SumDif,2)?></td>
                        </tr>
                        <?php
                        $SUMMECHANIC        = $SUMMECHANIC + $result_seCountMechanic['COUNTMEC'];
                        $SUMPLANHOURALL     = $SUMPLANHOURALL + number_format($PLANHOUR,2);
                        $SUMACTUALHOURALL   = $SUMACTUALHOURALL + number_format($ACTUALHOUR,2);
                        $SUMDIF             = $SUMDIF + (number_format( $ACTUALHOUR,2)-number_format( $PLANHOUR,2));
                        $SUMCAPMEC          = $SUMCAPMEC + $CapMec;
                        $SUMACTUALMEC       = $SUMACTUALMEC + $CapAct;
                        $SUMCAPHRS          = $SUMCAPHRS + $CapHrs;
                        $SUMACTHRS          = $SUMACTHRS + number_format($ActHrs,2);
                        $SUMDIFALL          = $SUMDIFALL + number_format($SumDif,2);
                        $SUMSPAER           = $SUMSPAER + $sparepartcost;
                        $SUMWAGE            = $SUMWAGE + $wage;
                        $i++;   
                    }
                    ?>
                <tr>
                    <th colspan="6" style="text-align: right;background-color: #dedede">TOTAL</th>
                    <td style="text-align: center"><b><?= $SUMMECHANIC?></b></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"><b><?= $SUMPLANHOURALL?></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"><b><?= $SUMACTUALHOURALL?></td>
                    <td style="text-align: center"><b><?= $SUMDIF ?></b></td>
                    <td style="text-align: center"><b><?= number_format($SUMSPAER,2) ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMWAGE,2) ?></td>
                    <td style="text-align: center"><b><?= $SUMCAPMEC ?></td>
                    <td style="text-align: center"><b><?= $SUMACTUALMEC ?></b></td>
                    <td style="text-align: center"><b><?= $SUMCAPHRS ?></b></td>
                    <td style="text-align: center"><b><?= $SUMACTHRS ?></b></td>
                    <td style="text-align: center"><b><?= $SUMDIFALL ?></b></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
