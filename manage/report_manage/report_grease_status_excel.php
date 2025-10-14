<?php
session_name("EMS"); session_start();
$path = "../";
require($path.'../include/connect.php');

// รับค่าค้นหา
$search_regis = isset($_GET['search_regis']) ? trim($_GET['search_regis']) : '';
$search_company = isset($_GET['search_company']) ? trim($_GET['search_company']) : '';
$dateStart = isset($_GET['dateStart']) ? trim($_GET['dateStart']) : '';
$dateEnd = isset($_GET['dateEnd']) ? trim($_GET['dateEnd']) : '';

// ตัดเอาเฉพาะทะเบียนรถ (ก่อน /)
$search_regis_trim = '';
if ($search_regis != '') {
    $regis_parts = explode('/', $search_regis);
    $search_regis_trim = trim($regis_parts[0]);
}

// แปลงวันที่
function date_thai_to_sql($d) {
    $ex = explode("/", $d);
    if(count($ex)==3) return $ex[2].'-'.$ex[1].'-'.$ex[0];
    return '';
}
$dscon = date_thai_to_sql($dateStart);
$decon = date_thai_to_sql($dateEnd);

// ตั้งค่า header สำหรับ Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=grease_status_report.xls");
header("Pragma: no-cache");
header("Expires: 0");

// สร้างตาราง
echo "<table border='1'>";
echo "<tr style='background:#e8f4f8;'>
    <th>ทะเบียน</th>
    <th>ชื่อรถ</th>
    <th>รอบล่าสุด</th>
    <th>เลขไมล์ที่เปลี่ยนล่าสุด</th>
    <th>วันที่เปลี่ยนล่าสุด</th>
</tr>";

$sql = "SELECT
    RPG_ID,
    RPG_CODE,
    RPG_GROUP,
    RPG_VHCRG,
    RPG_VHCRGNM,
    V.AFFCOMPANY,
    RPG_LCD,
    RPG_CREATEBY,
    RPG_CREATEDATE,
    RPG_EDITBY,
    RPG_EDITDATE,
    RPG_REMARK,
    RPG_COUNT,
    RPG_LASTMILEAGE
FROM REPAIR_GREASE
LEFT JOIN vwVEHICLEINFO V ON V.VEHICLEREGISNUMBER = RPG_VHCRG COLLATE THAI_CI_AI
WHERE 1=1
";
if($search_regis_trim != '') {
    $sql .= " AND RPG_VHCRG LIKE '%".addslashes($search_regis_trim)."%'";
}
if($search_company != '') {
    $sql .= " AND AFFCOMPANY LIKE '%".addslashes($search_company)."%'";
}
if($dscon && $decon) {
    $sql .= " AND (RPG_LCD BETWEEN '$dscon 00:00:00' AND '$decon 23:59:59')";
}
$sql .= " ORDER BY RPG_ID ASC";

$query = sqlsrv_query($conn, $sql);
while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>
        <td>{$row['RPG_VHCRG']}</td>
        <td>{$row['RPG_VHCRGNM']}</td>
        <td>{$row['RPG_COUNT']}/{$row['RPG_GROUP']}</td>
        <td>".number_format($row['RPG_LASTMILEAGE'])."</td>
        <td>".($row['RPG_LCD'] ? date('d/m/Y', strtotime($row['RPG_LCD'])) : '')."</td>
    </tr>";
}
echo "</table>";
?>