<?php

session_start();
$path = "../";   	
require($path.'../include/connect.php');
$SESSION_AREA = $_SESSION["AD_AREA"];
$txt_datestart_amt=$_GET['ds'];
$txt_dateend_amt=$_GET['de'];
$status=$_GET['st'];

$ds = $_GET['ds'];
$de = $_GET['de'];
$rg = $_GET['rg'];
$st = $_GET['st'];
$gp = $_GET['gp'];
$tc = $_GET['tc'];
if(isset($ds)&&isset($de)){
    $getselectdaystart = $ds;
    $getselectdayend = $de;		
    $dsdate = str_replace('/', '-', $ds);
    $dscon = date('Y-m-d', strtotime($dsdate));
    $dedate = str_replace('/', '-', $de);
    $decon = date('Y-m-d', strtotime($dedate));
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
$strExcelFileName = "รายงานประวัติการซ่อมบำรุง(".$SESSION_AREA.")" . $txt_datestart_amt . ' ถึง ' . $txt_dateend_amt . ".xls";

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
                <tr>
                    <td colspan="28" style="text-align:center;background-color: #dedede">รายงานประวัติการซ่อมบำรุง (ประจำวันที่ <?=$txt_datestart_amt?>-<?=$txt_dateend_amt?>)</td>
                </tr>
                <tr>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">ลำดับ</td>
					<th colspan="2" style="text-align:center;background-color: #dedede" width="10%">ข้อมูลรถ(หัว)</th>
					<th colspan="2" style="text-align:center;background-color: #dedede" width="10%">ข้อมูลรถ(หาง)</th>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">สายงาน/ลูกค้า</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">ประเภทรถ</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">ลักษณะงานซ่อม</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">ปัญหา</td>
                    <td colspan="2" style="text-align:center;background-color: #dedede">ประเภทงานซ่อม</td>
                    <td colspan="6" style="text-align:center;background-color: #dedede">สถานะงานซ่อม</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">ช่องซ่อม</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">เวลาในการซ่อม (แผน)</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">ชม. ในการซ่อม (แผน)</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">เวลาในการซ่อม (จริง)</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">ชม. ในการซ่อม (จริง)</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">ผู้แจ้งซ่อม</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">วันที่แจ้งซ่อม</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">ผู้รับแจ่งซ่อม</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">ผู้จัดแผน</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">ช่างผู้รับผิดชอบ</td>
                    <td rowspan="2" style="text-align:center;background-color: #dedede">หมายเหตุ</td>

                </tr>
                <tr>
					<th style="text-align:center;background-color: #dedede">ทะเบียน</th>
					<th style="text-align:center;background-color: #dedede">ชื่อรถ</th>           
					<th style="text-align:center;background-color: #dedede">ทะเบียน</th>
					<th style="text-align:center;background-color: #dedede">ชื่อรถ</th>
                    <td style="text-align:center;background-color: #dedede">BM</td>
                    <td style="text-align:center;background-color: #dedede">PM</td>
                    <td style="text-align:center;background-color: #FFFF00">รอตรวจสอบ</td>
                    <td style="text-align:center;background-color: #FFFF00">รอจ่ายงาน</td>
                    <td style="text-align:center;background-color: #1E90FF">รอคิวซ่อม</td>
                    <td style="text-align:center;background-color: #FFA500">กำลังซ่อม</td>
                    <td style="text-align:center;background-color: #32CD32">ซ่อมเสร็จสิ้น</td>
                    <td style="text-align:center;background-color: #FF0000">ไม่อนุมัติ</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;       
                    $sqlquery="SELECT 
                            -- DISTINCT
                            a.RPRQ_ID REPAIRPLANID,
                            a.RPRQ_CODE,
                            a.RPRQ_LINEOFWORK COMPANY,
                            a.RPRQ_COMPANYCASH COMPANYPAYMENT,
                            a.RPRQ_AREA AREA,
                            a.RPRQ_CARTYPE VEHICLETYPE,
                            b.RPC_AREA REPAIRAREA,
                            a.RPRQ_REGISHEAD VEHICLEREGISNUMBER1,
                            a.RPRQ_REGISTAIL VEHICLEREGISNUMBER2,
                            a.RPRQ_CARNAMEHEAD THAINAME1,
                            a.RPRQ_CARNAMETAIL THAINAME2,
                            a.RPRQ_TYPECUSTOMER JOBTYPE,
                            a.RPRQ_WORKTYPE REPAIRTYPE,
                            a.RPRQ_MILEAGEFINISH RANKPM,
                            a.RPRQ_RANKPMTYPE,
                            '' REPAIRTIME,
                            b.RPC_INCARDATE,
                            b.RPC_INCARTIME,
                            b.RPC_INCARDATE +' '+ b.RPC_INCARTIME TIMEPLANSTART,
                            b.RPC_OUTCARDATE,
                            b.RPC_OUTCARTIME,
                            b.RPC_OUTCARDATE +' '+ b.RPC_OUTCARTIME TIMEPLANEND,                             
                            -- DATEDIFF(MINUTE, b.RPC_INCARTIME, b.RPC_OUTCARTIME) AS 'PLANMINUTE',   
                            DATEDIFF(MINUTE,CASE WHEN ISDATE(b.RPC_INCARTIME) = 1 THEN CONVERT(DATETIME, b.RPC_INCARTIME) ELSE NULL END,CASE WHEN ISDATE(b.RPC_OUTCARTIME) = 1 THEN CONVERT(DATETIME, b.RPC_OUTCARTIME) ELSE NULL END) AS 'PLANMINUTE',                        
                            CONVERT(VARCHAR(10),CONVERT(date, b.RPC_INCARDATE, 105),23),
                            b.RPC_SUBJECT SUBJ,
                            CASE
                                WHEN b.RPC_SUBJECT = 'EL' THEN 'ระบบไฟ'
                                WHEN b.RPC_SUBJECT = 'TU' THEN 'ยาง-ช่วงล่าง'
                                WHEN b.RPC_SUBJECT = 'BD' THEN 'โครงสร้าง'
                                WHEN b.RPC_SUBJECT = 'EG' THEN 'เครื่องยนต์'
                                WHEN b.RPC_SUBJECT = 'AC' THEN 'อุปกรณ์ประจำรถ'
                                ELSE b.RPC_SUBJECT
                            END SUBJECT,
                            b.RPC_DETAIL DETAIL,
                            b.RPC_INCARDATE CARINPUTDATE,                            
                            a.RPRQ_REQUESTBY,                    
                            a.RPRQ_REQUESTBY_SQ,
                            a.RPRQ_CREATEDATE_REQUEST,
                            a.RPRQ_APPROVE,
                            a.RPRQ_STATUSREQUEST,
                            a.RPRQ_REMARK,
                            (SELECT TOP 1 CONVERT(VARCHAR, CONVERT(DATETIME, RPATTM_PROCESS, 101), 103)+' '+SUBSTRING(RPATTM_PROCESS, 12, 5) FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE=a.RPRQ_CODE AND RPC_SUBJECT=b.RPC_SUBJECT AND RPATTM_GROUP='START') AS REPAIRSTART,
                            (SELECT TOP 1 CONVERT(VARCHAR, CONVERT(DATETIME, RPATTM_PROCESS, 101), 103)+' '+SUBSTRING(RPATTM_PROCESS, 12, 5) FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE=a.RPRQ_CODE AND RPC_SUBJECT=b.RPC_SUBJECT AND RPATTM_GROUP='SUCCESS') AS REPAIREND,
                            (SELECT DISTINCT SUM(CAST(REPLACE(RPATTM_TOTAL,',','') as int)) FROM dbo.REPAIRACTUAL_TIME WHERE RPRQ_CODE=a.RPRQ_CODE AND RPC_SUBJECT=b.RPC_SUBJECT) AS RPATTM_TOTAL
                        FROM
                            REPAIRREQUEST a
                            INNER JOIN REPAIRCAUSE b ON b.RPRQ_CODE = a.RPRQ_CODE
                        WHERE 1=1 ";
                    $wsa="AND a.RPRQ_STATUS = 'Y' AND a.RPRQ_AREA = '$SESSION_AREA' ";
					$wd1="AND CONVERT(datetime,b.RPC_INCARDATE,103) BETWEEN '$dscon' AND '$decon' ";
					$wd2="AND CONVERT(datetime,a.RPRQ_CREATEDATE_REQUEST,103) BETWEEN '$dscon' AND '$decon' ";
					$wrg="AND (a.RPRQ_REGISHEAD LIKE '%$rgsub%' OR a.RPRQ_REGISTAIL LIKE '%$rgsub%') ";      
					$wwt="AND a.RPRQ_WORKTYPE LIKE '%$gp%' ";
					$wtc="AND a.RPRQ_TYPECUSTOMER LIKE '%$tc%' ";
					$wst1="AND a.RPRQ_STATUSREQUEST NOT IN('รอส่งแผน','รอตรวจสอบ','รอจ่ายงาน','ไม่อนุมัติ') ";
					$wst2="AND a.RPRQ_STATUSREQUEST IN('รอส่งแผน','รอตรวจสอบ','รอจ่ายงาน','ไม่อนุมัติ') ";
					$wst3="AND a.RPRQ_STATUSREQUEST LIKE '%$st%' ";
					$order="ORDER BY a.RPRQ_STATUSREQUEST ASC";

					if(isset($st)){
						if($st=="ทั้งหมด"){
							// echo "IF=1<br>";															
							$sql_seRepairplan = $sqlquery.$wsa.$wd1.$wrg.$wwt.$wtc.$wst1." UNION ALL ".$sqlquery.$wsa.$wd2.$wrg.$wwt.$wtc.$wst2.$order;
						}else if(($st=="รอส่งแผน")||($st=="รอตรวจสอบ")||($st=="รอจ่ายงาน")||($st=="ไม่อนุมัติ")){
							// echo "IF=2<br>";															
							$sql_seRepairplan = $sqlquery.$wsa.$wd2.$wrg.$wwt.$wtc.$wst3.$order;
						}else if(($st=="รอคิวซ่อม")||($st=="กำลังซ่อม")||($st=="ซ่อมเสร็จสิ้น")){
							// echo "IF=3<br>";															
							$sql_seRepairplan = $sqlquery.$wsa.$wd1.$wrg.$wwt.$wtc.$wst3.$order;	
						}else{
							// echo "IF=0<br>";
							$sql_seRepairplan = "";											
						}
					}else{
						// echo "IF=0<br>";
						$sql_seRepairplan = "";	
					}   
                    // echo $sql_seRepairplan."<br>";
                    $params_seRepairplan = array();
                    $query_seRepairplan = sqlsrv_query($conn, $sql_seRepairplan, $params_seRepairplan);
                    while ($result_seRepairplan = sqlsrv_fetch_array($query_seRepairplan, SQLSRV_FETCH_ASSOC)) {
                        $SUBJ=$result_seRepairplan['SUBJ'];
                        $RPRQ_CODE=$result_seRepairplan['RPRQ_CODE'];

                        $status1 = ($result_seRepairplan['RPRQ_STATUSREQUEST'] == 'รอตรวจสอบ') ? '1' : '';
                        $status2 = ($result_seRepairplan['RPRQ_STATUSREQUEST'] == 'รอจ่ายงาน') ? '1' : '';
                        $status3 = ($result_seRepairplan['RPRQ_STATUSREQUEST'] == 'รอคิวซ่อม') ? '1' : '';
                        $status4 = ($result_seRepairplan['RPRQ_STATUSREQUEST'] == 'กำลังซ่อม') ? '1' : '';
                        $status5 = ($result_seRepairplan['RPRQ_STATUSREQUEST'] == 'ซ่อมเสร็จสิ้น') ? '1' : '';
                        $status6 = ($result_seRepairplan['RPRQ_STATUSREQUEST'] == 'ไม่อนุมัติ') ? '1' : '';

                        $PLANHOUR1      = ($result_seRepairplan['PLANMINUTE']/60); 
                        $RPATTM_TOTAL   = $result_seRepairplan["RPATTM_TOTAL"];
                        $PLANHOUR2      = ($RPATTM_TOTAL/60);                  
                        //เช็คแผนว่าเป็น PM ไหน
                            if ($result_seRepairplan['REPAIRTYPE'] == 'PM') {
                                $REPAIRTYPE = $result_seRepairplan['RPRQ_RANKPMTYPE'];
                                $JOBPM = 1;
                                $JOBBM = '';
                            }else{
                                $REPAIRTYPE = $result_seRepairplan['REPAIRTYPE'];
                                $JOBPM = '';
                                $JOBBM = 1;
                            }     

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

                        // หาชื่อคนจ่ายงาน
                            $RPRQ_APPROVE=$result_seRepairplan['RPRQ_APPROVE'];
                            $stmt_emp_approve = "SELECT * FROM vwEMPLOYEE WHERE PersonCode = ?";
                            $params_emp_approve = array($RPRQ_APPROVE);	
                            $query_emp_approve = sqlsrv_query( $conn, $stmt_emp_approve, $params_emp_approve);	
                            $result_emp_approve = sqlsrv_fetch_array($query_emp_approve, SQLSRV_FETCH_ASSOC);			
                                $APPROVE_NAME=$result_emp_approve["nameT"];
                        ?>                            
                        <tr>
                            
                            <!-- <td style="text-align: left"><?=$result_seRepairplan['REPAIRPLANID']?></td> -->
                            <td style="text-align: center"><?=$i?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['VEHICLEREGISNUMBER1']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['THAINAME1']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['VEHICLEREGISNUMBER2']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['THAINAME2']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['COMPANY']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['VEHICLETYPE']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['SUBJECT']?></td>
                            <td style="text-align: left"><?=$result_seRepairplan['DETAIL']?></td>
                            <td style="text-align: center"><?=$JOBBM?></td>
                            <td style="text-align: center"><?=$JOBPM?></td>
                            <td style="text-align: center"><?=$status1?></td>
                            <td style="text-align: center"><?=$status2?></td>
                            <td style="text-align: center"><?=$status3?></td>
                            <td style="text-align: center"><?=$status4?></td>
                            <td style="text-align: center"><?=$status5?></td>
                            <td style="text-align: center"><?=$status6?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['REPAIRAREA']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['TIMEPLANSTART']?> - <?=$result_seRepairplan['TIMEPLANEND']?></td>
                            <td style="text-align: center"><?=number_format( $PLANHOUR1,2)?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['REPAIRSTART']?> - <?=$result_seRepairplan['REPAIREND']?></td>
                            <td style="text-align: center"><?=number_format( $PLANHOUR2,2)?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['RPRQ_REQUESTBY']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['RPRQ_CREATEDATE_REQUEST']?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['RPRQ_REQUESTBY_SQ']?></td>
                            <td style="text-align: center"><?=$APPROVE_NAME?></td>
                            <td style="text-align: center"><?=$TECHICIAN?></td>
                            <td style="text-align: center"><?=$result_seRepairplan['RPRQ_REMARK']?></td>
                        </tr>
                        <?php                        
                            $sumjobtypetenko = $sumjobtypetenko + $JOBBM;
                            $sumjobtypeplanning = $sumjobtypeplanning + $JOBPM;
                            $sumstatus1 = $sumstatus1 + $status1;
                            $sumstatus2 = $sumstatus2 + $status2;
                            $sumstatus3 = $sumstatus3 + $status3;
                            $sumstatus4 = $sumstatus4 + $status4;
                            $sumstatus5 = $sumstatus5 + $status5;
                            $sumstatus6 = $sumstatus6 + $status6;
                        $i++;   
                    }
                    ?>
                
                <tr>
                    <td colspan="9">&nbsp;</td>
                    <td style="text-align: center"><?= $sumjobtypetenko ?></td>
                    <td style="text-align: center"><?= $sumjobtypeplanning ?></td>
                    <td style="text-align: center"><?= $sumstatus1 ?></td>
                    <td style="text-align: center"><?= $sumstatus2 ?></td>
                    <td style="text-align: center"><?= $sumstatus3 ?></td>
                    <td style="text-align: center"><?= $sumstatus4 ?></td>
                    <td style="text-align: center"><?= $sumstatus5 ?></td>
                    <td style="text-align: center"><?= $sumstatus6 ?></td>
                    <td colspan="11"></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
