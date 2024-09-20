<?php
    $path='../';
    require($path."include/connect.php");

    if ($_POST['txt_flg'] == "select_employee") {
        $sql = "SELECT * FROM vwEMPLOYEE WHERE nameT = '".$_POST['empname']."'";
        $params = array();
        $query = sqlsrv_query($conn, $sql, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        echo $result['ID'].'|'.$result['PersonCode'].'|'.$result['nameT'].'|'.$result['PositionNameT'].'|'.$result['Company_Code'].'|'.$result['Company_NameT'].'|'.$result['THAINAME'];
        // echo $result['VEHICLEREGISNUMBER']; 
    } 
