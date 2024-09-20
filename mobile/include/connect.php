<?php include('db.php');
    session_start();
    error_reporting(0); //E_ALL แสดง error ทั้งหมด | ใส่ 0 ปิดแสดง error ทั้งหมด
    date_default_timezone_set('Asia/Bangkok');
        $serverName = $HOST;
        $userName = $USERNAME;
        $userPassword = $PASSWORD;
        $dbName = $DATABASE;

   	$connectionInfo = array("Database"=>$dbName, "UID"=>$userName, "PWD"=>$userPassword, "MultipleActiveResultSets"=>true,"CharacterSet"  => 'UTF-8');
    $conn = sqlsrv_connect($serverName, $connectionInfo);
       if($conn)
       {
        //    echo "Database Connected.";
        }else{
        //    die( print_r( sqlsrv_errors(), true));
       }
	###########################################################################################################

    $path = "../../";   
	$title="E-Maintenance";	
	$iconimage="https://img2.pic.in.th/pic/car_repair.png";	

    return $conn;
    sqlsrv_close($conn);
?>
