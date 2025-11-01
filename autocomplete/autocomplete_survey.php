<?php
    $path='../';
    require($path."include/connect.php");

    if ($_POST['txt_flg'] == "select_customer_survey") {
        $customer = $_POST['customer'];
        $sql = "SELECT CTM_ID, CTM_MAIL, CTM_COMCODE, CTM_NAMETH, CTM_NAMEEN FROM CUSTOMER WHERE CTM_COMCODE = ? OR CTM_NAMETH = ?";
        $params = array($customer, $customer);
        $query = sqlsrv_query($conn, $sql, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        
        if ($result) {
            echo $result['CTM_ID'].'|'.$result['CTM_MAIL'].'|'.$result['CTM_COMCODE'].'|'.$result['CTM_NAMETH'].'|'.$result['CTM_NAMEEN'];
        } else {
            echo "||||"; // ส่งค่าว่างถ้าไม่พบข้อมูล
        }
    }
    
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