<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>use time function</title>
</head>
 
<?php
date_default_timezone_set('Asia/Bangkok');

    $tm1 = '13:55';
    $tm2 = '15:00';
    $t1 = strtotime($tm1);
    $t2 = strtotime($tm2);
    $h = round( abs($t2 - $t1) / 3600, 2 );
    // echo "คำนวณชั่วโมงได้เท่ากับ {$h} ชั่วโมง<br>";

    $ETM_PM = round( abs($t2 - $t1) / 3600, 2 );
    $CALETM_PM=$ETM_PM*60;	

    // 2024/02/12	06:40	2024/02/12	06:50
	$RPC_INCARDATETIME="2024/02/12	".$tm1; //ตัวอย่างวันที่
	$POSTDATE=str_replace('/', '-', $RPC_INCARDATETIME);
	$DATECONVERT = date('Y-m-d H:i', strtotime($POSTDATE));

    $ROUND_CALETM_PM=round($CALETM_PM);
    $TIMECON = "+".$ROUND_CALETM_PM." minutes";
    $DATETIMEEND = date ("Y-m-d H:i", strtotime($TIMECON, strtotime($DATECONVERT)));    
    $CONDATEFORMAT = strtotime($DATETIMEEND);		
    $RPC_OUTCARDATETIME = date("d/m/Y H:i", $CONDATEFORMAT);

    echo 'เวลาจากฐานข้อมูล = '.$ETM_PM;
    echo '<br>';
    echo 'แปลงเวลาจากฐานข้อมูลเป็นนาที = '.$CALETM_PM;
    echo '<br>';
    echo 'เวลาหลังปัดเศษ = '.$ROUND_CALETM_PM;
    echo '<br>';
    echo date("H:i:s" , mktime(0,0,$ROUND_CALETM_PM,0,0,0));
    echo '<br>';
    echo 'วันที่นำรถเข้าซ่อม = '.$DATECONVERT;
    echo '<br>';
    echo 'วันทีซ่อมเสร็จ = '.$DATETIMEEND;
    echo '<br>';
    echo 'แปลงวันทีซ่อมเสร็จ = '.$RPC_OUTCARDATETIME;
?>
<body>
</body>
</html>