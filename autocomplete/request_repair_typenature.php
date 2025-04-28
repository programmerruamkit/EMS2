<?php
session_name("EMS"); session_start();
$path = '../';
require($path."include/connect.php");

if (isset($_POST['function']) && $_POST['function'] == 'RPM_NATUREREPAIR') {
    $TRPW_ID = $_POST['trpw_id'];
    $AREA=$_SESSION["AD_AREA"];
    $sql_typerepair = "SELECT A.TRPW_ID,A.TRPW_NAME 
        FROM TYPEREPAIRWORK A 
        LEFT JOIN NATUREREPAIR B ON B.NTRP_ID = A.NTRP_ID 
        WHERE A.TRPW_STATUS='Y' 
        AND A.TRPW_NAME LIKE '%BM%' 
        AND A.TRPW_AREA = '$AREA'
        AND A.TRPW_REMARK = '$TRPW_ID'";
    $query_typerepair = sqlsrv_query($conn, $sql_typerepair);
    if($TRPW_ID=='EL'){
        echo '<option value="" selected disabled>--เลือกกลุ่มงานระบบไฟ--</option>';
    }else if($TRPW_ID=='TU'){
        echo '<option value="" selected disabled>--เลือกกลุ่มงานยาง/ช่วงล่าง--</option>';
    }else if($TRPW_ID=='BD'){
        echo '<option value="" selected disabled>--เลือกกลุ่มงานโครงสร้าง--</option>';
    }else if($TRPW_ID=='EG'){
        echo '<option value="" selected disabled>--เลือกกลุ่มงานเครื่องยนต์--</option>';
    }
    while ($result_typerepair = sqlsrv_fetch_array($query_typerepair, SQLSRV_FETCH_ASSOC)) {
?>
    <option value="<?php echo $result_typerepair["TRPW_ID"];?>"><?=$result_typerepair['TRPW_NAME']?></option>
<?php      
    }
}
?>