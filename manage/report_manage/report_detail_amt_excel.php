<?php

    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');
    $SESSION_AREA = $_SESSION["AD_AREA"];
    $txt_datestart=$_GET['txt_datestart'];
    $txt_dateend=$_GET['txt_dateend'];

    $strExcelFileName = "รายละเอียดข้อมูลแผนงานซ่อม(".$SESSION_AREA.") " . $txt_datestart . ' ถึง ' . $txt_dateend . ".xls";

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
                    <th colspan="16" style="text-align: center;background-color: #dedede">รายงานรายละเอียดงานซ่อม <?=$SESSION_AREA?> (ประจำวันที่ <?= $txt_datestart ?> - <?= $txt_dateend ?>)</th>
                </tr>
                <tr>   
                    <th rowspan="2" style="text-align:center;background-color: #dedede">ช่องซ่อม</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">ทะเบียน(หัว)</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">ลูกค้า</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">ลักษณะงานซ่อม</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">รายละเอียด</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">ประเภทงานซ่อม</th>
                    <th rowspan="2" style="text-align:center;background-color: #dedede">จำนวนช่าง/วัน</th>
                    <th colspan="2" style="text-align:center;background-color: #dedede">Capacity</th>
                    <th colspan="2" style="text-align:center;background-color: #dedede">PLAN</th>
                    <th colspan="2" style="text-align:center;background-color: #dedede">ACTUAL</th>
                    <th colspan="3" style="text-align:center;background-color: #dedede">ส่วนต่าง</th>

                </tr>
                <tr>
                    <th style="text-align:center;background-color: #dedede">ชม./วัน</th>
                    <th style="text-align:center;background-color: #dedede">รวม/ชม.</th>
                    <th style="text-align:center;background-color: #dedede">ชม./วัน</th>
                    <th style="text-align:center;background-color: #dedede">รวม(ชม.)/วัน</th>
                    <th style="text-align:center;background-color: #dedede">ชม./วัน</th>
                    <th style="text-align:center;background-color: #dedede">รวม(ชม.)/วัน</th>
                    <th style="text-align:center;background-color: #dedede">Cap/Plan</th>
                    <th style="text-align:center;background-color: #dedede">Plan/Act.</th>
                    <th style="text-align:center;background-color: #dedede">Cap/Act.</th>
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
                            AND CONVERT(VARCHAR(10),CONVERT(date, c.RPC_INCARDATE, 105),23) BETWEEN CONVERT(VARCHAR(10),CONVERT(date, '$txt_datestart', 105),23) AND CONVERT(VARCHAR(10),CONVERT(date, '$txt_dateend', 105),23)";                                            
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
                                // $ACTUALHOUR     = ($result_seActualtime['PLANMINUTE']/60);

                            $sql_seActualtimeEnd ="SELECT DISTINCT SUM(CAST(RPATTM_TOTAL as int)) AS 'RPATTM_TOTAL' 
                            FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE  = '$RPRQ_CODE' AND RPC_SUBJECT = '$SUBJ'";
                            $params_seActualtimeEnd    = array();
                            $query_seActualtimeEnd     = sqlsrv_query($conn, $sql_seActualtimeEnd, $params_seActualtimeEnd);
                            $result_seActualtimeEnd    = sqlsrv_fetch_array($query_seActualtimeEnd, SQLSRV_FETCH_ASSOC);    
                                $RPATTM_TOTAL = $result_seActualtimeEnd["RPATTM_TOTAL"];
                                $ACTUALHOUR     = ($RPATTM_TOTAL/60);
                            
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
                            $estimatecal=$wage + $sparepartcost;
                            // $estimatecal=($hourmoney * $wage) + $sparepartcost;
                            if($estimatecal!='0'){
                                $estimaters=$estimatecal;
                            }else{
                                $estimaters='';
                            }    
                        ?>                            
                        <tr>                                
                            <td style="text-align: left"><?=$result_seRepairplan['REPAIRAREA']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['VEHICLEREGISNUMBER1']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['COMPANYPAYMENT']?></td>
                            <td style="text-align: left"><?=$result_seRepairplan['SUBJECT']?></td>
                            <td style="text-align: left"><?=$result_seRepairplan['DETAIL']?></td>
                            <td style="text-align: center"><?=$REPAIRTYPE?></td>
                            <td style="text-align: center"><?=$result_seCountMechanic['COUNTMEC']?></td>
                            <td style="text-align: center">6.33</td>
                            <td style="text-align: center"><?=number_format($result_seCountMechanic['COUNTMEC']*6.33,2) ?></td>
                            <td style="text-align: center"><?=number_format($PLANHOUR,2)?></td>
                            <td style="text-align: center"><?=number_format($PLANHOUR*$result_seCountMechanic['COUNTMEC'],2)?></td>
                            <td style="text-align: center"><?=number_format($ACTUALHOUR,2)?></td>
                            <td style="text-align: center"><?=number_format($ACTUALHOUR*$result_seCountMechanic['COUNTMEC'],2)?></td>

                            <td style="text-align: center"><?=number_format(($PLANHOUR*$result_seCountMechanic['COUNTMEC'])-($result_seCountMechanic['COUNTMEC']*6.33),2)?></td>
                            <td style="text-align: center"><?=number_format(($ACTUALHOUR*$result_seCountMechanic['COUNTMEC'])-($PLANHOUR*$result_seCountMechanic['COUNTMEC']),2)?></td>
                            <td style="text-align: center"><?=number_format(($ACTUALHOUR*$result_seCountMechanic['COUNTMEC'])-($result_seCountMechanic['COUNTMEC']*6.33),2)?></td>
                        </tr>
                        <?php
                        $SUMMECHANIC        = $SUMMECHANIC + $result_seCountMechanic['COUNTMEC'];
                        $SUMCAPHOUR         = $SUMCAPHOUR + 6.33;
                        $SUMCAPTOTAL        = $SUMCAPTOTAL + $result_seCountMechanic['COUNTMEC']*6.33;
                        $SUMPLANHOUR        = $SUMPLANHOUR + $PLANHOUR;
                        $SUMPLANTOTAL       = $SUMPLANTOTAL + ($PLANHOUR*$result_seCountMechanic['COUNTMEC']);
                        $SUMACTUALHOUR      = $SUMACTUALHOUR + $ACTUALHOUR;
                        $SUMACTUALTOTAL     = $SUMACTUALTOTAL + ($ACTUALHOUR*$result_seCountMechanic['COUNTMEC']);
                        $SUMCAPPLAN     = $SUMCAPPLAN + (($PLANHOUR*$result_seCountMechanic['COUNTMEC'])-($result_seCountMechanic['COUNTMEC']*6.33));
                        $SUMPLANACT     = $SUMPLANACT + (($ACTUALHOUR*$result_seCountMechanic['COUNTMEC'])-($PLANHOUR*$result_seCountMechanic['COUNTMEC']));
                        $SUMCAPACT      = $SUMCAPACT + (($ACTUALHOUR*$result_seCountMechanic['COUNTMEC'])-($result_seCountMechanic['COUNTMEC']*6.33));
                        $i++;   
                    }
                    ?>
                <tr>
                    <th colspan="6" style="text-align: right;background-color: #dedede">TOTAL</th>
                    <td style="text-align: center"><b><?= number_format($SUMMECHANIC,2); ?></b></td>
                    <td style="text-align: center"><b><?= number_format($SUMCAPHOUR,2); ?></b></td>
                    <td style="text-align: center"><b><?= number_format($SUMCAPTOTAL,2); ?></b></td>
                    <td style="text-align: center"><b><?= number_format($SUMPLANHOUR,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMPLANTOTAL,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMACTUALHOUR,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMACTUALTOTAL,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMCAPPLAN,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMPLANACT,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMCAPACT,2); ?></td>
                </tr>
            </tbody>
        </table>

        <br><br><br><br>

        <?php                                                                                    
            $sql_rpm = "SELECT DISTINCT 
            (SELECT SUM(ISNULL(CAST(CM_TOTAL AS DECIMAL(6,0)),0)) FROM CHECKMAN WHERE CONVERT(VARCHAR(10),replace(CM_DATE,'/','-'),23) BETWEEN CONVERT(VARCHAR(10),CONVERT(date, '$txt_datestart', 105),23) AND CONVERT(VARCHAR(10),CONVERT(date, '$txt_dateend', 105),23) AND CM_AREA = '$SESSION_AREA' AND CM_TOTAL >= '0') AS total,
            (SELECT SUM(ISNULL(CAST(CM_READY AS DECIMAL(6,0)),0)) FROM CHECKMAN WHERE CONVERT(VARCHAR(10),replace(CM_DATE,'/','-'),23) BETWEEN CONVERT(VARCHAR(10),CONVERT(date, '$txt_datestart', 105),23) AND CONVERT(VARCHAR(10),CONVERT(date, '$txt_dateend', 105),23) AND CM_AREA = '$SESSION_AREA' AND CM_READY >= '0') AS ready";
            $query_rpm = sqlsrv_query($conn, $sql_rpm);
            $result_rpm = sqlsrv_fetch_array($query_rpm, SQLSRV_FETCH_ASSOC);
        ?>
        <table  border="1"  style="width: 100%;">
            <thead>
                <tr>   
                    <th colspan="2" style="text-align:center;background-color: #dedede">Tase</th>
                    <th colspan="2" style="text-align:center;background-color: #dedede">STANDARD</th>
                    <th colspan="1" style="text-align:center;background-color: #dedede">PLAN</th>
                    <th colspan="2" style="text-align:center;background-color: #dedede">ACTUAL</th>
                </tr>
            </thead>
            <tbody>                  
                <tr>                                
                    <td colspan="2" style="text-align: center"><b>ช่าง/วัน</b></td>
                    <td colspan="2" style="text-align: right"><?= number_format($result_rpm["total"],0); ?></td>
                    <td colspan="1" style="text-align: right"><?= number_format($result_rpm["total"],0); ?></td>
                    <td colspan="2" style="text-align: right"><?= number_format($result_rpm["ready"],0); ?></td>
                </tr>  
                <tr>                                
                    <td colspan="2" style="text-align: center"><b>ชม./วัน/คน</b></td>
                    <td colspan="2" style="text-align: right">6.33</td>
                    <td colspan="1" style="text-align: right"><?= number_format($SUMPLANHOUR/$result_rpm["total"],3); ?></td>
                    <td colspan="2" style="text-align: right"><?= number_format($SUMACTUALHOUR/$result_rpm["ready"],3); ?></td>
                </tr>  
                <tr>                                
                    <td colspan="2" style="text-align: center"><b>ชม.รวม/คน</b></td>
                    <td colspan="2" style="text-align: right"><?= number_format($result_rpm["total"]*6.33,0); ?></td>
                    <td colspan="1" style="text-align: right"><?= number_format($SUMPLANHOUR,2); ?></td>
                    <td colspan="2" style="text-align: right"><?= number_format($SUMACTUALHOUR,2); ?></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
