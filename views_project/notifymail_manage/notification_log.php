<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');
?>
<html>
<head><title>ประวัติการแจ้งเตือน</title></head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
    <tr class="CENTER">
        <td class="CENTER" align="center">
            <h3>ประวัติการส่งการแจ้งเตือน</h3>
            
            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                <thead>
                    <tr>
                        <th>วันที่-เวลา</th>
                        <th>กลุ่มการแจ้งเตือน</th>
                        <th>จำนวนผู้รับ</th>
                        <th>สถานะ</th>
                        <th>รายละเอียด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // แสดงจาก log file หรือ database
                    $log_files = glob('../logs/notification_*.log');
                    foreach(array_reverse($log_files) as $log_file) {
                        $lines = file($log_file, FILE_IGNORE_NEW_LINES);
                        foreach(array_reverse($lines) as $line) {
                            if(trim($line)) {
                                echo "<tr><td colspan='5'>" . htmlspecialchars($line) . "</td></tr>";
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </td>
    </tr>
</table>
</body>
</html>