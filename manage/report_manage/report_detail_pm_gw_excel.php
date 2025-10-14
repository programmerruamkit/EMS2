<?php
session_name("EMS"); session_start();
$path = "../";
require($path.'../include/connect.php');

$SESSION_AREA = isset($_GET['area']) ? $_GET['area'] : $_SESSION["AD_AREA"];
$GETCUS = isset($_GET['ctmcomcode']) ? $_GET['ctmcomcode'] : '';

// สร้างชื่อไฟล์ Excel
$strExcelFileName = "รายละเอียดรถขอซ่อม_PM($SESSION_AREA)_".$GETCUS.".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
header("Pragma:no-cache");

// สร้างเงื่อนไขบริษัท/กลุ่มลูกค้า
if($GETCUS!=""){                        
    if(($GETCUS!="ALL")&&($GETCUS!="cusout")&&($GETCUS!="AMT")&&($GETCUS!="GW")){
        $wh="AND ACTIVESTATUS = '1' AND AFFCOMPANY LIKE '%".$GETCUS."%'";
    }else if($GETCUS=="AMT"){
        $wh="AND ACTIVESTATUS = '1' AND AFFCOMPANY IN('RKR','RKS','RKL')";
    }else if($GETCUS=="GW"){
        $wh="AND ACTIVESTATUS = '1' AND AFFCOMPANY IN('RCC','RRC','RATC')";
    }else if($GETCUS=="cusout"){
        $wh="AND ACTIVESTATUS = '1'";
    }else {
        $wh="";
    }
}else{
    $wh="null";
}

if($GETCUS=="cusout"){
    $CTM_GROUP="cusout";
}else{
    $CTM_GROUP="cusin";
}

// Query รถตามเงื่อนไขบริษัท/กลุ่มลูกค้า
if($GETCUS=="cusout"){
    $sql_vehicleinfo = "SELECT * FROM CUSTOMER_CAR 
    LEFT JOIN CUSTOMER ON CUSTOMER.CTM_COMCODE = CUSTOMER_CAR.AFFCOMPANY
    LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
    LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
    WHERE 1=1 AND NOT CTM_STATUS='D' AND VEHICLEGROUPDESC = 'Transport' $wh";
}else{
    $sql_vehicleinfo = "SELECT * FROM vwVEHICLEINFO 
    LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
    LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
    WHERE 1=1 AND VEHICLEGROUPDESC = 'Transport' AND NOT VEHICLEGROUPCODE = 'VG-1403-0755' $wh";
}
$query_vehicleinfo = sqlsrv_query($conn, $sql_vehicleinfo);
$no=0;
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<table border="1" style="width: 100%;">
    <thead>
        <tr>
            <td colspan="16" style="text-align:center;background-color: #dedede">
                รายละเอียดรถขอซ่อม PM (<?=$SESSION_AREA?>) บริษัท/กลุ่มลูกค้า: <?=$GETCUS?>
            </td>
        </tr>
        <tr>
            <th rowspan="2" align="center" style="text-align:center;background-color: #dedede">ลำดับ</th>
            <th rowspan="2" align="center" style="text-align:center;background-color: #dedede">ข้อมูลรถ (หัว) - ทะเบียน</th>
            <th rowspan="2" align="center" style="text-align:center;background-color: #dedede">ข้อมูลรถ (หัว) - ชื่อรถ</th>
            <?php if($CTM_GROUP=="cusout"){ ?>
                <th rowspan="2" align="center" style="text-align:center;background-color: #dedede">เพิ่ม/แก้ไขรถลูกค้า - ชื่อลูกค้า</th>
            <?php } ?>
            <th rowspan="2" align="center" style="text-align:center;background-color: #dedede">ไมล์ล่าสุด</th>
            <?php if($CTM_GROUP!="cusout"){ ?>
                <th rowspan="2" align="center" style="text-align:center;background-color: #dedede">ไมล์เกินระยะ</th>
                <th rowspan="2" align="center" style="text-align:center;background-color: #dedede">กม.วัน</th>
                <th rowspan="2" align="center" style="text-align:center;background-color: #dedede">PM/วัน</th>
            <?php } ?>
            <th colspan="2" align="center" style="text-align:center;background-color: #dedede">กำหนดระยะ PM</th>
            <th colspan="2" align="center" style="text-align:center;background-color: #dedede">PM ครั้งต่อไป</th>
            <th rowspan="2" align="center" style="text-align:center;background-color: #dedede">เวลาในการซ่อม (ชม.)</th>
            <th rowspan="2" align="center" style="text-align:center;background-color: #dedede">วันที่/เวลานัดรถเข้าซ่อม</th>
        </tr>
        <tr>
            <th align="center" style="text-align:center;background-color: #dedede">ระยะเลขไมล์</th>
            <th align="center" style="text-align:center;background-color: #dedede">PM</th>
            <th align="center" style="text-align:center;background-color: #dedede">ระยะเลขไมล์</th>
            <th align="center" style="text-align:center;background-color: #dedede">PM</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while($result_vehicleinfo = sqlsrv_fetch_array($query_vehicleinfo, SQLSRV_FETCH_ASSOC)){	
            $no++;
            $VEHICLEREGISNUMBER=$result_vehicleinfo['VEHICLEREGISNUMBER'];
            $THAINAME=$result_vehicleinfo['THAINAME'];
            if($GETCUS=="AMT"||$GETCUS=="RKS"||$GETCUS=="RKR"||$GETCUS=="RKL"){     
                $field="VEHICLEREGISNUMBER = '$VEHICLEREGISNUMBER'";
            }else if($GETCUS=="GW"||$GETCUS=="RRC"||$GETCUS=="RCC"||$GETCUS=="RATC"){
                $explodes = explode('(', $THAINAME);
                $THAINAME = $explodes[0];
                $field="THAINAME = '$THAINAME'";
            }else{
                $field="VEHICLEREGISNUMBER = '$VEHICLEREGISNUMBER'";
            }

            if($CTM_GROUP=="cusout"){
                $sql_mileage = "SELECT TOP 1 MILEAGENUMBER AS MAXMILEAGENUMBER,VEHICLEREGISNUMBER,CREATEDATE FROM TEST_MILEAGE_PM WHERE ACTIVESTATUS = 1  AND MILEAGENUMBER <> '0' AND VEHICLEREGISNUMBER = '".$result_vehicleinfo['VEHICLEREGISNUMBER']."' ORDER BY MILEAGEID DESC ";
                $params_mileage = array();
                $query_mileage = sqlsrv_query($conn, $sql_mileage, $params_mileage);
                $result_mileage = sqlsrv_fetch_array($query_mileage, SQLSRV_FETCH_ASSOC); 
            }else{
                $sql_mileage = "SELECT TOP 1 MAXMILEAGENUMBER,VEHICLEREGISNUMBER,THAINAME,CREATEDATE FROM TEMP_MILEAGE WHERE MAXMILEAGENUMBER > '0' AND $field ORDER BY CREATEDATE DESC";
                $params_mileage = array();
                $query_mileage = sqlsrv_query($conn, $sql_mileage, $params_mileage);
                $result_mileage = sqlsrv_fetch_array($query_mileage, SQLSRV_FETCH_ASSOC); 
            }
            $VHCTMG_LINEOFWORK = $result_vehicleinfo['VHCCT_PM'];
            if(isset($result_mileage['MAXMILEAGENUMBER'])){
                if($result_mileage['MAXMILEAGENUMBER']>1000000){
                    $MAXMILEAGENUMBER = $result_mileage['MAXMILEAGENUMBER']-1000000;
                }else{
                    $MAXMILEAGENUMBER = $result_mileage['MAXMILEAGENUMBER'];
                }
            }else{
                $MAXMILEAGENUMBER = 0;
            }
            if(($MAXMILEAGENUMBER >= '0') && ($MAXMILEAGENUMBER <= '1000000')){
                $fildsfind="MLPM_MILEAGE_10K1M";
            }else if(($MAXMILEAGENUMBER >= '1000001') && ($MAXMILEAGENUMBER <= '2000000')){
                $fildsfind="MLPM_MILEAGE_1M2M";
            }else if(($MAXMILEAGENUMBER >= '2000001') && ($MAXMILEAGENUMBER <= '3000000')){
                $fildsfind="MLPM_MILEAGE_2M3M";
            }
            $MAXCUT = SUBSTR($MAXMILEAGENUMBER,-4);
            if($MAXCUT > 2000){
                $MAXMILEAGENUMBER2000=$MAXMILEAGENUMBER;
            }else{
                if($GETCUS=="AMT"||$GETCUS=="RKS"||$GETCUS=="RKR"||$GETCUS=="RKL"){     
                    $MAXMILEAGENUMBER2000=$MAXMILEAGENUMBER-1000;
                }else if($GETCUS=="GW"||$GETCUS=="RRC"||$GETCUS=="RCC"||$GETCUS=="RATC"){
                    $MAXMILEAGENUMBER2000=$MAXMILEAGENUMBER-1000;
                }
            }
            $sql_rankpm = "SELECT TOP 1 * FROM MILEAGESETPM WHERE MLPM_LINEOFWORK = '$VHCTMG_LINEOFWORK' AND $fildsfind > '".$MAXMILEAGENUMBER2000."' ORDER BY $fildsfind ASC";
            $params_rankpm = array();
            $query_rankpm = sqlsrv_query($conn, $sql_rankpm, $params_rankpm);
            $result_rankpm = sqlsrv_fetch_array($query_rankpm, SQLSRV_FETCH_ASSOC);        
            $MLPM_MILEAGE=$result_rankpm[$fildsfind];

            if(($MLPM_MILEAGE > '0') && ($MLPM_MILEAGE < '1000000')){
                $fildsfindnext="MLPM_MILEAGE_10K1M";
            }else if(($MLPM_MILEAGE >= '1000000') && ($MLPM_MILEAGE < '2000000')){
                $fildsfindnext="MLPM_MILEAGE_1M2M";
            }else if(($MLPM_MILEAGE >= '2000000') && ($MLPM_MILEAGE < '3000000')){
                $fildsfindnext="MLPM_MILEAGE_2M3M";
            }
            $sql_ranknextpm = "SELECT TOP 1 * FROM MILEAGESETPM WHERE MLPM_LINEOFWORK = '$VHCTMG_LINEOFWORK' AND $fildsfindnext > '$MLPM_MILEAGE' ORDER BY $fildsfindnext ASC";
            $params_ranknextpm = array();
            $query_ranknextpm = sqlsrv_query($conn, $sql_ranknextpm, $params_ranknextpm);
            $result_ranknextpm = sqlsrv_fetch_array($query_ranknextpm, SQLSRV_FETCH_ASSOC);
            $MLNEXTPM_MILEAGE=$result_ranknextpm[$fildsfindnext];

            // เวลาในการซ่อม (ชม.)
            $pmFields = array(
                'PMoRS-1'  => 'ETM_PM1',
                'PMoRS-2'  => 'ETM_PM2',
                'PMoRS-3'  => 'ETM_PM3',
                'PMoRS-4'  => 'ETM_PM4',
                'PMoRS-5'  => 'ETM_PM5',
                'PMoRS-6'  => 'ETM_PM6',
                'PMoRS-7'  => 'ETM_PM7',
                'PMoRS-8'  => 'ETM_PM8',
                'PMoRS-9'  => 'ETM_PM9',
                'PMoRS-10' => 'ETM_PM10',
                'PMoRS-11' => 'ETM_PM11',
                'PMoRS-12' => 'ETM_PM12'
            );
            if(isset($pmFields[$result_rankpm["MLPM_NAME"]])) {
                $fildsfindETM = $pmFields[$result_rankpm["MLPM_NAME"]];
            } else {
                $fildsfindETM = 'ETM_PM1';
            }
            $VHCCT_ID = $result_vehicleinfo["VHCCT_ID"];
            $sql_selhour = "SELECT SUM(CAST($fildsfindETM AS DECIMAL(10,2))) AS SUMETM FROM ESTIMATE
            WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '6' AND NOT ETM_TYPE = 'รวม'";
            $params_selhour = array();
            $query_selhour = sqlsrv_query($conn, $sql_selhour, $params_selhour);
            $result_selhour = sqlsrv_fetch_array($query_selhour, SQLSRV_FETCH_ASSOC); 
            $ETM_PM=$result_selhour['SUMETM'];
            $CALETM_PM=$ETM_PM*60;
            $ROUND_CALETM_PM=round($CALETM_PM);
            $HOUR_CALETM_PM = floor($ROUND_CALETM_PM/60);
            $MINUIT_CALETM_PM = $ROUND_CALETM_PM - ($HOUR_CALETM_PM * 60);
            $HOUR_ROUND_CALETM_PM = $HOUR_CALETM_PM.':'.$MINUIT_CALETM_PM;

            // วันที่/เวลานัดรถเข้าซ่อม
            $sql_rprq_check = "SELECT DISTINCT REPAIRREQUEST.RPRQ_CODE,RPRQ_WORKTYPE,RPRQ_REGISHEAD,RPRQ_REGISTAIL,RPRQ_STATUSREQUEST,RPRQ_REQUESTCARDATE,RPRQ_REQUESTCARTIME,RPC_INCARDATE,RPC_INCARTIME
            FROM REPAIRREQUEST LEFT JOIN REPAIRCAUSE ON REPAIRCAUSE.RPRQ_CODE = REPAIRREQUEST.RPRQ_CODE
            WHERE RPRQ_STATUS = 'Y' AND RPRQ_REGISHEAD = '$VEHICLEREGISNUMBER' AND NOT RPRQ_STATUSREQUEST IN('ไม่อนุมัติ','ซ่อมเสร็จสิ้น') ORDER BY RPRQ_WORKTYPE DESC";
            $query_rprq_check = sqlsrv_query($conn, $sql_rprq_check);
            $result_rprq_check = sqlsrv_fetch_array($query_rprq_check, SQLSRV_FETCH_ASSOC);

            $RPC_INCARDATE=$result_rprq_check['RPC_INCARDATE'];
            $RPC_INCARTIME=$result_rprq_check['RPC_INCARTIME'];
        ?>
        <tr align="center">
            <td><?= $no ?></td>
            <td><?= $result_vehicleinfo['VEHICLEREGISNUMBER'] ?></td>
            <td><?= $result_vehicleinfo['THAINAME'] ?></td>
            <?php if($CTM_GROUP=="cusout"){ ?>
                <td>
                    <?= $result_vehicleinfo['CTM_NAMETH']; ?>
                </td>
            <?php } ?>
            <td align="right"><?= number_format($MAXMILEAGENUMBER) ?></td>
            <?php if($CTM_GROUP!="cusout"){ ?>
                <td align="right"><?= number_format($MLPM_MILEAGE - $MAXMILEAGENUMBER) ?></td>
                <td align="right"><?= number_format($result_vehicleinfo['VHCCT_KILOFORDAY']) ?></td>
                <td align="right"><?= number_format(($MLPM_MILEAGE - $MAXMILEAGENUMBER) / $result_vehicleinfo['VHCCT_KILOFORDAY']) ?></td>
            <?php } ?>
            <td align="right"><?= number_format($MLPM_MILEAGE) ?></td>
            <td align="center"><?= $result_rankpm['MLPM_NAME'] ?></td>
            <td align="right"><?= number_format($MLNEXTPM_MILEAGE) ?></td>
            <td align="center"><?= $result_ranknextpm['MLPM_NAME'] ?></td>
            <td align="right"><?= $HOUR_ROUND_CALETM_PM;?></td>
            <td align="right"><?= $RPC_INCARDATE ?> <?= $RPC_INCARTIME ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
</body>
</html>
