<?php
session_name("EMS"); session_start();
$path = "../";
require($path.'../include/connect.php');

$SESSION_AREA = isset($_GET['area']) ? $_GET['area'] : $_SESSION["AD_AREA"];
$ds = isset($_GET['ds']) ? $_GET['ds'] : '';
$de = isset($_GET['de']) ? $_GET['de'] : '';
$personCode = isset($_GET['personCode']) ? $_GET['personCode'] : '';

$strExcelFileName = "รายละเอียดแจ้งซ่อมพนักงาน($SESSION_AREA)_$personCode"."_" . $ds . "_ถึง_" . $de . ".xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
header("Pragma:no-cache");

$employeeName = '';
$sql_emp = "SELECT nameT FROM vwEMPLOYEE WHERE PersonCode = '$personCode'";
$query_emp = sqlsrv_query($conn, $sql_emp);
if($emp = sqlsrv_fetch_array($query_emp, SQLSRV_FETCH_ASSOC)){
    $employeeName = $emp['nameT'];
}

// แปลงวันที่เป็น Y-m-d สำหรับ SQL Server
$dsdate = str_replace('/', '-', $ds);
$dscon = date('Y-m-d', strtotime($dsdate));
$dedate = str_replace('/', '-', $de);
$decon = date('Y-m-d', strtotime($dedate));

// Query รายละเอียดแจ้งซ่อมรายบุคคล
$sql_detail_employee = "SELECT		
    DISTINCT		
    	A.RPRQ_WORKTYPE, 	
        A.RPRQ_REGISHEAD, 	
        A.RPRQ_REGISTAIL, 	
        A.RPRQ_CARNAMEHEAD, 	
        A.RPRQ_CARNAMETAIL, 	
    	A.RPRQ_LINEOFWORK, 	
    	A.RPRQ_COMPANYCASH,  	
        A.RPRQ_CREATEBY, 	
        A.RPRQ_REQUESTBY, 	
        A.RPRQ_CREATEDATE, 	
    	A.RPRQ_CREATEDATE_REQUEST, 	
    	A.RPRQ_CARTYPE, 	
    	A.RPRQ_AREA,	
        CASE 	
            WHEN B.RPC_SUBJECT = 'EG' THEN 'งานเครื่องยนต์'
            WHEN B.RPC_SUBJECT = 'EL' THEN 'งานระบบไฟ'
            WHEN B.RPC_SUBJECT = 'BD' THEN 'งานโครงสร้าง'
            WHEN B.RPC_SUBJECT = 'TU' THEN 'งานยาง-ช่วงล่าง'
            ELSE B.RPC_SUBJECT
        END AS RPC_SUBJECT,	
        B.RPC_DETAIL,	
        A.RPRQ_STATUSREQUEST	
    FROM dbo.REPAIRREQUEST AS A
    LEFT JOIN dbo.REPAIRCAUSE AS B ON B.RPRQ_CODE = A.RPRQ_CODE
    WHERE
        A.RPRQ_WORKTYPE = 'BM'
        AND A.RPRQ_STATUS = 'Y'
        AND A.RPRQ_TYPECUSTOMER = 'cusin'
        AND A.RPRQ_AREA = '$SESSION_AREA'
        AND A.RPRQ_CREATEBY = '$personCode'
        AND CONVERT(date, A.RPRQ_CREATEDATE_REQUEST, 103) BETWEEN '$dscon' AND '$decon'
    ORDER BY A.RPRQ_CREATEDATE ASC";

$query_detail_employee = sqlsrv_query($conn, $sql_detail_employee);
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <table border="1" style="width: 100%;">
        <thead>
            <tr>
                <td colspan="9" style="text-align:center;background-color: #dedede">
                    รายละเอียดแจ้งซ่อมพนักงาน (<?=$SESSION_AREA?>) รหัสพนักงาน: <?=$personCode?> | ชื่อ: <?=$employeeName?> วันที่ <?=$ds?> ถึง <?=$de?>
                </td>
            </tr>
            <tr>
                <th style="text-align:center;background-color: #dedede">ลำดับ</th>
                <th style="text-align:center;background-color: #dedede">ทะเบียนหัว</th>
                <th style="text-align:center;background-color: #dedede">ทะเบียนหาง</th>
                <th style="text-align:center;background-color: #dedede">ชื่อรถหัว</th>
                <th style="text-align:center;background-color: #dedede">ชื่อรถหาง</th>
                <th style="text-align:center;background-color: #dedede">วันที่แจ้ง</th>
                <th style="text-align:center;background-color: #dedede">ประเภทงาน</th>
                <th style="text-align:center;background-color: #dedede">รายละเอียดงาน</th>
                <th style="text-align:center;background-color: #dedede">สถานะคำขอ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            while($row = sqlsrv_fetch_array($query_detail_employee, SQLSRV_FETCH_ASSOC)){
                $no++;
            ?>
            <tr>
                <td align="center"><?= $no ?></td>
                <td align="center"><?= $row['RPRQ_REGISHEAD'] ?></td>
                <td align="center"><?= $row['RPRQ_REGISTAIL'] ?></td>
                <td align="center"><?= $row['RPRQ_CARNAMEHEAD'] ?></td>
                <td align="center"><?= $row['RPRQ_CARNAMETAIL'] ?></td>
                <td align="center"><?= $row['RPRQ_CREATEDATE_REQUEST'] ?></td>
                <td align="left"><?= $row['RPC_SUBJECT'] ?></td>
                <td align="left"><?= $row['RPC_DETAIL'] ?></td>
                <td align="center"><?= $row['RPRQ_STATUSREQUEST'] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
