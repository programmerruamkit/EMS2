<?php
    $path='../';
    require($path."include/connect.php");

    if ($_POST['txt_flg'] == "select_vehiclenumber1") {
        if ($_POST['typecus'] == "cusin"){
            $sql = "SELECT * FROM vwVEHICLEINFO WHERE VEHICLEREGISNUMBER = '".$_POST['vehiclenumber']."'";
        }else if ($_POST['typecus'] == "cusout"){
            $sql = "SELECT * FROM vwVEHICLEINFO_OUT WHERE VEHICLEREGISNUMBER = '".$_POST['vehiclenumber']."'";
        }
        $params = array();
        $query = sqlsrv_query($conn, $sql, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        echo $result['VEHICLEINFOID'].'|'.$result['VEHICLEREGISNUMBER'].'|'.$result['THAINAME'].'|'.$result['VEHICLETYPEDESC'].'|'.$result['AFFCOMPANY'];
        // echo $result['VEHICLEREGISNUMBER']; 
    }

    if ($_POST['txt_flg'] == "select_vehiclenumber2") {
        if ($_POST['typecus'] == "cusin"){
            $sql = "SELECT * FROM vwVEHICLEINFO WHERE VEHICLEREGISNUMBER = '".$_POST['vehiclenumber']."'";
        }else if ($_POST['typecus'] == "cusout"){
            $sql = "SELECT * FROM vwVEHICLEINFO_OUT WHERE VEHICLEREGISNUMBER = '".$_POST['vehiclenumber']."'";
        }
        $params = array();
        $query = sqlsrv_query($conn, $sql, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        echo $result['VEHICLEINFOID'].'|'.$result['VEHICLEREGISNUMBER'].'|'.$result['THAINAME'];
        // echo $result['VEHICLEREGISNUMBER']; 
    }
    
    if ($_POST['txt_flg'] == "select_thainame1") {
        if ($_POST['typecus'] == "cusin"){
            $sql = "SELECT * FROM vwVEHICLEINFO WHERE THAINAME = '".$_POST['thainame']."'";
        }else if ($_POST['typecus'] == "cusout"){
            $sql = "SELECT * FROM vwVEHICLEINFO_OUT WHERE THAINAME = '".$_POST['thainame']."'";
        }
        $params = array();
        $query = sqlsrv_query($conn, $sql, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        echo $result['VEHICLEINFOID'].'|'.$result['VEHICLEREGISNUMBER'].'|'.$result['THAINAME'].'|'.$result['VEHICLETYPEDESC'].'|'.$result['AFFCOMPANY'];
        // echo $result['VEHICLEREGISNUMBER']; 
    }
    
    if ($_POST['txt_flg'] == "select_thainame2") {
        if ($_POST['typecus'] == "cusin"){
            $sql = "SELECT * FROM vwVEHICLEINFO WHERE THAINAME = '".$_POST['thainame']."'";
        }else if ($_POST['typecus'] == "cusout"){
            $sql = "SELECT * FROM vwVEHICLEINFO_OUT WHERE THAINAME = '".$_POST['thainame']."'";
        }
        $params = array();
        $query = sqlsrv_query($conn, $sql, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        echo $result['VEHICLEINFOID'].'|'.$result['VEHICLEREGISNUMBER'].'|'.$result['THAINAME'];
        // echo $result['VEHICLEREGISNUMBER']; 
    }

    if ($_POST['txt_flg'] == "select_maxmileage") {
        if($_POST['vehiclenumber']!=''){
            // $wh="VEHICLEREGISNUMBER = '".$_POST['vehiclenumber']."'";
            if ($_POST['typecus'] == "cusin"){
                $sql1 = "SELECT * FROM vwVEHICLEINFO WHERE VEHICLEREGISNUMBER = '".$_POST['vehiclenumber']."'";
            }else if ($_POST['typecus'] == "cusout"){
                $sql1 = "SELECT * FROM vwVEHICLEINFO_OUT WHERE VEHICLEREGISNUMBER = '".$_POST['vehiclenumber']."'";
            }
        }
        if($_POST['thainame']!=''){
            $thainame = $_POST['thainame'];
            $explodes = explode('(', $thainame);
            $thainame = $explodes[0];
            // $wh="THAINAME = '".$thainame."'";
            // echo $explodes[0];
            if ($_POST['typecus'] == "cusin"){
                $sql1 = "SELECT * FROM vwVEHICLEINFO WHERE THAINAME = '".$_POST['thainame']."'";
            }else if ($_POST['typecus'] == "cusout"){
                $sql1 = "SELECT * FROM vwVEHICLEINFO_OUT WHERE THAINAME = '".$_POST['thainame']."'";
            }
        }
        $params1 = array();
        $query1 = sqlsrv_query($conn, $sql1, $params1);
        $result1 = sqlsrv_fetch_array($query1, SQLSRV_FETCH_ASSOC);

        $wh="VEHICLEREGISNUMBER = '".$result1['VEHICLEREGISNUMBER']."'";
        
        $sql = "SELECT TOP 1 * FROM vwMILEAGE WHERE $wh ORDER BY CREATEDATE DESC ";
        $params = array();
        $query = sqlsrv_query($conn, $sql, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        echo $result['MAXMILEAGENUMBER']; 
    } 