<?php

    session_start();
    $path = "../";   	
    require($path.'../include/connect.php');
    $SESSION_AREA = $_SESSION["AD_AREA"];
    $txt_datestart=$_GET['txt_datestart'];

    $strExcelFileName = "รายงานประสิทธิภาพช่องซ่อม(".$SESSION_AREA.") " . $txt_datestart .".xls";

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
                    <th colspan="13" style="text-align: center;background-color: #dedede">รายงานประสิทธิภาพช่องซ่อม <?=$SESSION_AREA?> ประจำวันที่ <?= $txt_datestart ?></th>
                </tr>
                <tr>   
                    <th rowspan="2" style="text-align:center;background-color: #dedede">BAY</th>
                    <th colspan="3" style="text-align:center;background-color: #dedede">CAPACITY</th>
                    <th colspan="3" style="text-align:center;background-color: #dedede">PLAN</th>
                    <th colspan="3" style="text-align:center;background-color: #dedede">ACTUAL</th>
                    <th colspan="3" style="text-align:center;background-color: #dedede">ส่วนต่าง (Hr.)</th>
                </tr>
                <tr>
                    <th style="text-align:center;background-color: #dedede">ช่าง/วัน/ช่อง</th>
                    <th style="text-align:center;background-color: #dedede">ชม./คน/ช่อง</th>
                    <th style="text-align:center;background-color: #dedede">รวม ชม./ช่อง</th>
                    <th style="text-align:center;background-color: #dedede">ช่าง/วัน/ช่อง</th>
                    <th style="text-align:center;background-color: #dedede">ชม./คน/ช่อง</th>
                    <th style="text-align:center;background-color: #dedede">รวม ชม./ช่อง</th>
                    <th style="text-align:center;background-color: #dedede">ช่าง/วัน/ช่อง</th>
                    <th style="text-align:center;background-color: #dedede">ชม./คน/ช่อง</th>
                    <th style="text-align:center;background-color: #dedede">รวม ชม./ช่อง</th>
                    <th style="text-align:center;background-color: #dedede">Cap/Plan</th>
                    <th style="text-align:center;background-color: #dedede">Plan/Act</th>
                    <th style="text-align:center;background-color: #dedede">Cap/Act</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;        
                    $sql_seRepairChannal = "SELECT DISTINCT
                        CASE 
                            WHEN RPH_REPAIRHOLE LIKE '%(%' THEN SUBSTRING(RPH_REPAIRHOLE, 0,CHARINDEX('(',RPH_REPAIRHOLE))
                            ELSE RPH_REPAIRHOLE 
                        END RPH_REPAIRHOLE,
                        RPH_AREA,
                        RPH_STATUS
                        FROM REPAIR_HOLE a
                        WHERE RPH_AREA = '$SESSION_AREA' AND RPH_STATUS = 'Y'
                        ORDER BY RPH_REPAIRHOLE ASC";                                            
                    $params_seRepairChannal = array();
                    $query_seRepairChannal = sqlsrv_query($conn, $sql_seRepairChannal, $params_seRepairChannal);
                    while ($result_seRepairChannal = sqlsrv_fetch_array($query_seRepairChannal, SQLSRV_FETCH_ASSOC)) {
                        $RPH_REPAIRHOLE=$result_seRepairChannal['RPH_REPAIRHOLE'];

                        // หาจำนวน ช่าง/วัน/ช่อง
                            $sql_plan1 = "SELECT 
                                COUNT(RPC_AREA) AS PLAN1
                                FROM REPAIRMANEMP 
                                WHERE RPC_AREA LIKE '%$RPH_REPAIRHOLE%'
                                AND CONVERT(VARCHAR(10),CONVERT(date,RPC_INCARDATE, 105),23) = CONVERT(VARCHAR(10),CONVERT(date, '$txt_datestart', 105),23) ";                                            
                            $params_plan1 = array();
                            $query_plan1 = sqlsrv_query($conn, $sql_plan1, $params_plan1);
                            $result_plan1 = sqlsrv_fetch_array($query_plan1, SQLSRV_FETCH_ASSOC);
                            if($result_plan1['PLAN1']>0){
                                $PLAN1=$result_plan1['PLAN1'];
                            }else{
                                $PLAN1="";
                            }
                        // หาจำนวน ชม./คน/ช่อง
                            $sql_plan2 = "SELECT DISTINCT
                                SUM(DATEDIFF(MINUTE, c.RPC_INCARTIME, c.RPC_OUTCARTIME)) AS 'PLAN2'
                                FROM REPAIRREQUEST a
                                INNER JOIN REPAIRCAUSE c ON c.RPRQ_CODE = a.RPRQ_CODE
                                WHERE a.RPRQ_AREA = 'AMT' AND a.RPRQ_STATUS = 'Y'
                                AND CONVERT(VARCHAR(10),CONVERT(date, c.RPC_INCARDATE, 105),23) = CONVERT(VARCHAR(10),CONVERT(date, '$txt_datestart', 105),23) 
                                AND c.RPC_AREA LIKE '%$RPH_REPAIRHOLE%'";                                            
                            $params_plan2 = array();
                            $query_plan2 = sqlsrv_query($conn, $sql_plan2, $params_plan2);
                            $result_plan2 = sqlsrv_fetch_array($query_plan2, SQLSRV_FETCH_ASSOC);
                            if($result_plan2['PLAN2']>0){
                                $PLAN2= number_format($result_plan2['PLAN2']/60,2);
                            }else{
                                $PLAN2="";
                            }
                        // หาจำนวน รวม ชม./ช่อง
                            if(number_format($PLAN1*number_format($PLAN2,2),2)>0){
                                $PLAN3= number_format($PLAN1*number_format($PLAN2,2),2);
                            }else{
                                $PLAN3="";
                            }
                        // หาจำนวน ชม./คน/ช่อง
                            $sql_act2 ="SELECT DISTINCT SUM(CAST(RPATTM_TOTAL as int)) AS 'ACT2' FROM REPAIRACTUAL_TIME a
                            WHERE RPC_AREA LIKE '%$RPH_REPAIRHOLE%' AND CONVERT(VARCHAR(10),CONVERT(date, RPC_INCARDATE, 105),23) = CONVERT(VARCHAR(10),CONVERT(date, '$txt_datestart', 105),23)";
                            $params_act2    = array();
                            $query_act2     = sqlsrv_query($conn, $sql_act2, $params_act2);
                            $result_act2    = sqlsrv_fetch_array($query_act2, SQLSRV_FETCH_ASSOC);
                            if($result_act2['ACT2']>0){
                                $ACT2= number_format($result_act2['ACT2']/60,2);
                            }else{
                                $ACT2="";
                            }
                        // หาจำนวน รวม ชม./ช่อง
                            if(number_format($PLAN1*number_format($ACT2,2),2)>0){
                                $ACT3= number_format($PLAN1*number_format($ACT2,2),2);
                            }else{
                                $ACT3="";
                            }
                        ?>                            
                        <tr>                                
                            <td style="text-align: center"><?=$result_seRepairChannal['RPH_REPAIRHOLE']?></td>
                            <td style="text-align: center">2</td>
                            <td style="text-align: center">6.33</td>
                            <td style="text-align: center"><?=number_format(2*6.33,2)?></td>
                            <td style="text-align: center"><?=$PLAN1?></td>
                            <td style="text-align: center"><?=$PLAN2?></td>
                            <td style="text-align: center"><?=$PLAN3?></td>
                            <td style="text-align: center"><?=$PLAN1?></td>
                            <td style="text-align: center"><?=$ACT2?></td>
                            <td style="text-align: center"><?=$ACT3?></td>
                            <td style="text-align: center"><?=number_format(($PLAN3-number_format(2*6.33,2)),2)?></td>
                            <td style="text-align: center"><?=number_format($ACT3-$PLAN3,2)?></td>
                            <td style="text-align: center"><?=number_format($ACT3-number_format(2*6.33,2),2)?></td>
                        </tr>
                        <?php
                        $SUMCAP1    = $SUMCAP1 + 2;
                        $SUMCAP2    = $SUMCAP2 + 6.33;
                        $SUMCAP3    = $SUMCAP3 + (2*6.33);
                        $SUMPLAN1   = $SUMPLAN1 + $PLAN1;
                        $SUMPLAN2   = $SUMPLAN2 + $PLAN2;;
                        $SUMPLAN3   = $SUMPLAN3 + $PLAN3;
                        $SUMACT1    = $SUMACT1 + $PLAN1;
                        $SUMACT2    = $SUMACT2 + $ACT2;
                        $SUMACT3    = $SUMACT3 + $ACT3;
                        $SUMDIF1    = $SUMDIF1 + number_format(($PLAN3-number_format(2*6.33,2)),2);
                        $SUMDIF2    = $SUMDIF2 + number_format($ACT3-$PLAN3,2);
                        $SUMDIF3    = $SUMDIF3 + number_format($ACT3-number_format(2*6.33,2),2);
                        $i++;   
                    }
                    ?>
                <tr>
                    <th colspan="1" style="text-align: right;background-color: #dedede">TOTAL</th>
                    <td style="text-align: center"><b><?= number_format($SUMCAP1,2); ?></b></td>
                    <td style="text-align: center"><b><?= number_format($SUMCAP2,2); ?></b></td>
                    <td style="text-align: center"><b><?= number_format($SUMCAP3,2); ?></b></td>
                    <td style="text-align: center"><b><?= number_format($SUMPLAN1,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMPLAN2,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMPLAN3,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMACT1,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMACT2,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMACT3,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMDIF1,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMDIF2,2); ?></td>
                    <td style="text-align: center"><b><?= number_format($SUMDIF3,2); ?></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
