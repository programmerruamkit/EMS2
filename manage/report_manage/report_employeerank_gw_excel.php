<?php
session_name("EMS"); session_start();
$path = "../";
require($path.'../include/connect.php');

$SESSION_AREA = isset($_GET['area']) ? $_GET['area'] : $_SESSION["AD_AREA"];
$ds = isset($_GET['ds']) ? $_GET['ds'] : '';
$de = isset($_GET['de']) ? $_GET['de'] : '';
$toprank = isset($_GET['toprank']) ? intval($_GET['toprank']) : 10;

$strExcelFileName = "อันดับแจ้งซ่อมพนักงาน($SESSION_AREA)_" . $ds . "_ถึง_" . $de . ".xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
header("Pragma:no-cache");

// แปลงวันที่เป็น Y-m-d สำหรับ SQL Server
$dsdate = str_replace('/', '-', $ds);
$dscon = date('Y-m-d', strtotime($dsdate));
$dedate = str_replace('/', '-', $de);
$decon = date('Y-m-d', strtotime($dedate));

// Query อันดับการแจ้งซ่อม
$sql_top_employee = "SELECT TOP $toprank
        A.RPRQ_CREATEBY,
        A.RPRQ_REQUESTBY,
        B.PositionNameT,
        COUNT(*) AS TotalRequests
    FROM dbo.REPAIRREQUEST AS A
    LEFT JOIN vwEMPLOYEE AS B ON B.PersonCode = A.RPRQ_CREATEBY COLLATE THAI_CI_AS
    WHERE
        A.RPRQ_WORKTYPE = 'BM'
        AND A.RPRQ_STATUS = 'Y'
        AND A.RPRQ_TYPECUSTOMER = 'cusin'
        AND A.RPRQ_AREA = '$SESSION_AREA'
        AND CONVERT(date, A.RPRQ_CREATEDATE_REQUEST, 103) BETWEEN '$dscon' AND '$decon'
    GROUP BY
        A.RPRQ_CREATEBY,
        A.RPRQ_REQUESTBY,
        B.PositionNameT
    ORDER BY TotalRequests DESC
";

$query_top_employee = sqlsrv_query($conn, $sql_top_employee);
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <table border="1" style="width: 100%;">
        <thead>
            <tr>
                <td colspan="5" style="text-align:center;background-color: #dedede">
                    รายงานอันดับแจ้งซ่อมพนักงาน (<?=$SESSION_AREA?>) วันที่ <?=$ds?> ถึง <?=$de?>
                </td>
            </tr>
            <tr>
                <th style="text-align:center;background-color: #dedede">ลำดับ</th>
                <th style="text-align:center;background-color: #dedede">รหัสพนักงาน</th>
                <th style="text-align:center;background-color: #dedede">ชื่อผู้แจ้ง</th>
                <th style="text-align:center;background-color: #dedede">ตำแหน่ง</th>
                <th style="text-align:center;background-color: #dedede">จำนวนแจ้งซ่อม</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            while($row = sqlsrv_fetch_array($query_top_employee, SQLSRV_FETCH_ASSOC)){
                $no++;
            ?>
            <tr>
                <td align="center"><?= $no ?></td>
                <td align="center"><?= $row['RPRQ_CREATEBY'] ?></td>
                <td align="center"><?= $row['RPRQ_REQUESTBY'] ?></td>
                <td align="center"><?= $row['PositionNameT'] ?></td>
                <td align="center"><?= $row['TotalRequests'] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
