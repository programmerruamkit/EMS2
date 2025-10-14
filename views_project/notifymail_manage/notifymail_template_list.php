<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');

    $group_code = $_GET["group_code"];
    
    $sql = "SELECT * FROM NOTIFY_EMAIL_TEMPLATE WHERE GROUP_CODE = ? AND STATUS = 'Y' ORDER BY CREATE_DATE DESC";
    $params = array($group_code);
    $query = sqlsrv_query($conn, $sql, $params);
    
    if(sqlsrv_has_rows($query)) {
        echo "<table width='100%' border='0' cellpadding='5' cellspacing='1' style='background:#ccc;'>";
        echo "<tr style='background:#f5f5f5;'>";
        echo "<th width='40%'>ชื่อเทมเพลต</th>";
        echo "<th width='30%'>หัวข้อ</th>";
        echo "<th width='20%'>จัดการ</th>";
        echo "</tr>";
        
        while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            echo "<tr style='background:white;'>";
            echo "<td>" . htmlspecialchars($row['TEMPLATE_NAME']) . "</td>";
            echo "<td>" . htmlspecialchars(substr($row['EMAIL_SUBJECT'], 0, 30)) . "...</td>";
            echo "<td align='center'>";
            echo "<button type='button' onclick='editTemplate(" . $row['ID'] . ")' class='mini bg-color-yellow' title='แก้ไข'>";
            echo "<i class='icon-pencil'></i>";
            echo "</button> ";
            echo "<button type='button' onclick='deleteTemplate(" . $row['ID'] . ",\"" . htmlspecialchars($row['TEMPLATE_NAME']) . "\")' class='mini bg-color-red' title='ลบ'>";
            echo "<i class='icon-cancel'></i>";
            echo "</button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div style='text-align:center; padding:30px; color:#999;'>";
        echo "<h4>ยังไม่มีเทมเพลต</h4>";
        echo "<p>เพิ่มเทมเพลตแรกของคุณ</p>";
        echo "</div>";
    }
?>