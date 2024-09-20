<?php
	session_start();
    
    function loginsession($KEYWORD,  $username,  $password, $areat){
        $path = '../';   	
        include ($path.'include/connect.php');                
        $ROLEACCOUNT_USERNAME=$_POST["username"];
        $ROLEACCOUNT_PASSWORD=$_POST["password"];
        $AREA=$_POST["area"];
 
        $sql_login = "SELECT DISTINCT ID,PersonCode,nameT,RA_USERNAME,RA_PASSWORD FROM vwUSERLOGIN WHERE RA_USERNAME = ? AND RA_PASSWORD = ? AND AREA = ? AND ROACACTIVE = 'Y'";
        $parameters = [$ROLEACCOUNT_USERNAME,$ROLEACCOUNT_PASSWORD,$AREA];
        $query_login = sqlsrv_query($conn, $sql_login, $parameters);
        $result_login = sqlsrv_fetch_array($query_login,SQLSRV_FETCH_ASSOC);     

        if(!$result_login){
            // echo json_encode(array("statusCode"=>201));
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $_SESSION["AD_ID"] = $result_login["ID"];
            // $_SESSION['AD_PERSONID'] = $result_login["PersonID"];
            $_SESSION['AD_PERSONCODE'] = $result_login["PersonCode"];
            // $_SESSION['AD_FIRSTNAME'] = $result_login["FnameT"];$_SESSION['AD_LASTNAME'] = $result_login["LnameT"];
            $_SESSION['AD_NAMETHAI'] = $result_login["nameT"];
            // $_SESSION['AD_NAMEENGLISH'] = $result_login["nameE"];$_SESSION['AD_CURRENTTEL'] = $result_login["CurrentTel"];$_SESSION['AD_COMPANYCODE'] = $result_login["Company_Code"];$_SESSION['AD_COMPANYNAME'] = $result_login["Company_NameT"];$_SESSION['AD_POSITIONID'] = $result_login["PositionID"];$_SESSION['AD_POSITIONNAME'] = $result_login["PositionNameT"];
            $_SESSION['AD_ROLEACCOUNT_USERNAME'] = $result_login["RA_USERNAME"];
            $_SESSION['AD_ROLEACCOUNT_PASSWORD'] = $result_login["RA_PASSWORD"];
            // $_SESSION['AD_ROLE_ID'] = $result_login["RU_ID"];$_SESSION["AD_ROLE_NAME"] = $result_login["RU_NAME"];
            $_SESSION["AD_AREA"] = $AREA;
        
            // session_write_close();
            date_default_timezone_set("Asia/Bangkok");
            $SSPSC = $_SESSION['AD_PERSONCODE'];
            $SSROID ='';
            $SSLGS = 1;
            $SSLGD = date("Y-m-d H:i:s");

            $LAC='LA1';
        
            // $check = "SELECT TOP 1 * FROM LOGING WHERE PersonCode = ? AND CONVERT(VARCHAR(10),LOGING_DATETIME,20) = CONVERT(VARCHAR(10),GETDATE(),20)";
            $check = "SELECT TOP 1 * FROM LOGING WHERE PersonCode = ? AND LAC = ?";
            $paramscheck = array($SSPSC,$LAC);
            $querycheck = sqlsrv_query( $conn, $check, $paramscheck);
            $resultcheck = sqlsrv_fetch_array($querycheck, SQLSRV_FETCH_ASSOC);
            $chk1null = $resultcheck['PersonCode'];
            if($chk1null == ""){
                $stm_loging = "INSERT INTO LOGING (PersonCode,RU_ID,LOGING_STATUS,LOGING_DATETIME,LAC) VALUES (?,?,?,?,?)";
                $params_loging = array($SSPSC,$SSROID,$SSLGS,$SSLGD,$LAC);
                $stmt_loging = sqlsrv_query( $conn, $stm_loging, $params_loging);
                if($stmt_loging === false){ 
                    die( print_r( sqlsrv_errors(), true));
                    $RS="error";
                }else{
                    $RS="complete";
                }				
                $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE) VALUES (?,?,?)";
                $params_log_tran = array($LAC,$SSLGD,$SSPSC);
                $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
                if($stmt_log_tran === false){ 
                    die( print_r( sqlsrv_errors(), true));
                    $RS="error";
                }else{
                    $RS="complete";
                }
            }else{
                $stm_loging = "UPDATE LOGING SET LOGING_DATETIME=? WHERE PersonCode=?";
                $params_loging = array($SSLGD,$chk1null);
                $stmt_loging = sqlsrv_query( $conn, $stm_loging, $params_loging);
                if($stmt_loging === false){ 
                    die( print_r( sqlsrv_errors(), true));
                    $RS="error";
                }else{
                    $RS="complete";
                }
                $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE) VALUES (?,?,?)";
                $params_log_tran = array($LAC,$SSLGD,$SSPSC);
                $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
                // $stm_log_tran= "UPDATE LOG_TRANSACTION SET TIMESTAMP=? WHERE LOG_EMPLOYEECODE=? AND LOGACT_CODE=?";
                // $params_log_tran= array($SSLGD,$SSPSC,$LAC);
                // $stmt_log_tran= sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
                if($stmt_log_tran === false){ 
                    die( print_r( sqlsrv_errors(), true));
                    $RS="error";
                }else{
                    $RS="complete";
                }
            }
            // echo json_encode(array("statusCode"=>200));
            $RS="complete";
        } 
        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function loginsessionmobile($KEYWORD,  $username,  $password, $area){
        $path = '../';   	
        include ($path.'include/connect.php');                
        $ROLEACCOUNT_USERNAME=$_POST["username"];
        $ROLEACCOUNT_PASSWORD=$_POST["password"];
        $AREA=$_POST["area"]; 

        $sql_login = "SELECT DISTINCT ID,PersonCode,nameT,PositionNameT,RU_NAME,RA_USERNAME,RA_PASSWORD,AREA FROM vwUSERLOGIN WHERE RA_USERNAME = ? AND RA_PASSWORD = ? AND AREA = ? AND ROACACTIVE = 'Y' AND RU_NAME IN('DRIVER','ช่างซ่อมบำรุง') ORDER BY RU_NAME DESC";
        $parameters = [$ROLEACCOUNT_USERNAME,$ROLEACCOUNT_PASSWORD,$AREA];
        $query_login = sqlsrv_query($conn, $sql_login, $parameters);
        $result_login = sqlsrv_fetch_array($query_login,SQLSRV_FETCH_ASSOC);     

        if(!$result_login){
            // echo json_encode(array("statusCode"=>201));
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $_SESSION["AD_ID"] = $result_login["ID"];
            // $_SESSION['AD_PERSONID'] = $result_login["PersonID"];
            $_SESSION['AD_PERSONCODE'] = $result_login["PersonCode"];
            // $_SESSION['AD_FIRSTNAME'] = $result_login["FnameT"];$_SESSION['AD_LASTNAME'] = $result_login["LnameT"];
            $_SESSION['AD_NAMETHAI'] = $result_login["nameT"];
            // $_SESSION['AD_NAMEENGLISH'] = $result_login["nameE"];$_SESSION['AD_CURRENTTEL'] = $result_login["CurrentTel"];$_SESSION['AD_COMPANYCODE'] = $result_login["Company_Code"];$_SESSION['AD_COMPANYNAME'] = $result_login["Company_NameT"];$_SESSION['AD_POSITIONID'] = $result_login["PositionID"];
            $_SESSION['AD_POSITIONNAME'] = $result_login["PositionNameT"];
            $_SESSION['AD_ROLEACCOUNT_USERNAME'] = $result_login["RA_USERNAME"];
            $_SESSION['AD_ROLEACCOUNT_PASSWORD'] = $result_login["RA_PASSWORD"];
            // $_SESSION['AD_ROLE_ID'] = $result_login["RU_ID"];
            $_SESSION["AD_ROLE_NAME"] = $result_login["RU_NAME"];
            $_SESSION["AD_AREA"] = $result_login["AREA"];
        
            // session_write_close();
            date_default_timezone_set("Asia/Bangkok");
            $SSPSC = $_SESSION['AD_PERSONCODE'];
            $SSROID ='';
            $SSLGS = 1; 
            $SSLGD = date("Y-m-d H:i:s");

            $LAC='LA1';
        
            // $check = "SELECT TOP 1 * FROM LOGING WHERE PersonCode = ? AND CONVERT(VARCHAR(10),LOGING_DATETIME,20) = CONVERT(VARCHAR(10),GETDATE(),20)";
            $check = "SELECT TOP 1 * FROM LOGING WHERE PersonCode = ? AND LAC = ?";
            $paramscheck = array($SSPSC,$LAC);
            $querycheck = sqlsrv_query( $conn, $check, $paramscheck);
            $resultcheck = sqlsrv_fetch_array($querycheck, SQLSRV_FETCH_ASSOC);
            $chk1null = $resultcheck['PersonCode'];
            if($chk1null == ""){
                $stm_loging = "INSERT INTO LOGING (PersonCode,RU_ID,LOGING_STATUS,LOGING_DATETIME,LAC) VALUES (?,?,?,?,?)";
                $params_loging = array($SSPSC,$SSROID,$SSLGS,$SSLGD,$LAC);
                $stmt_loging = sqlsrv_query( $conn, $stm_loging, $params_loging);
                if($stmt_loging === false){ 
                    die( print_r( sqlsrv_errors(), true));
                    $RS="error";
                }else{
                    $RS="complete";
                }				
                $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE) VALUES (?,?,?)";
                $params_log_tran = array($LAC,$SSLGD,$SSPSC);
                $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
                if($stmt_log_tran === false){ 
                    die( print_r( sqlsrv_errors(), true));
                    $RS="error";
                }else{
                    $RS="complete";
                }
            }else{
                $stm_loging = "UPDATE LOGING SET LOGING_DATETIME=? WHERE PersonCode=?";
                $params_loging = array($SSLGD,$chk1null);
                $stmt_loging = sqlsrv_query( $conn, $stm_loging, $params_loging);
                if($stmt_loging === false){ 
                    die( print_r( sqlsrv_errors(), true));
                    $RS="error";
                }else{
                    $RS="complete";
                }
                $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE) VALUES (?,?,?)";
                $params_log_tran = array($LAC,$SSLGD,$SSPSC);
                $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
                // $stm_log_tran= "UPDATE LOG_TRANSACTION SET TIMESTAMP=? WHERE LOG_EMPLOYEECODE=? AND LOGACT_CODE=?";
                // $params_log_tran= array($SSLGD,$SSPSC,$LAC);
                // $stmt_log_tran= sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
                if($stmt_log_tran === false){ 
                    die( print_r( sqlsrv_errors(), true));
                    $RS="error";
                }else{
                    $RS="complete";
                }
            }
            // echo json_encode(array("statusCode"=>200));
            $RS="complete";
        } 
        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }

    function logout($KEYWORD,  $personcode,  $logact){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $SSPSC = $personcode;
        $SSROID ='';
        $SSLGS = 1;
        $SSLGD = date("Y-m-d H:i:s");
        $LAC=$logact;
        $check = "SELECT * FROM LOGING WHERE PersonCode = ?";
        $paramscheck = array(array($personcode, SQLSRV_PARAM_IN));
        $querycheck = sqlsrv_query( $conn, $check, $paramscheck);
        $resultcheck = sqlsrv_fetch_array($querycheck, SQLSRV_FETCH_ASSOC);
        $chk1null = $resultcheck['PersonCode'];
        if($chk1null != ""){            
            $stm_logout = "DELETE FROM LOGING WHERE PersonCode = ? ";
            $params_logout = array($chk1null);
            $stmt_logout = sqlsrv_query( $conn, $stm_logout, $params_logout);
            if($stmt_logout === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE) VALUES (?,?,?)";
            $params_log_tran = array($LAC,$SSLGD,$SSPSC);
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
            session_destroy();
        }else if($chk1null == ""){            
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE) VALUES (?,?,?)";
            $params_log_tran = array($LAC,$SSLGD,$SSPSC);
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
            session_destroy();
        }
        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }

?>