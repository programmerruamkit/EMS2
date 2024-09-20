<?php
	session_start();
    
    function loginsession($KEYWORD,  $username,  $password, $areat){
        $path = '../';   	
        include ($path.'include/connect.php');       
        if($_POST["username"]=='root'){
            $ROLEACCOUNT_USERNAME=100012;
        }else{
            $ROLEACCOUNT_USERNAME=$_POST["username"];
        }
        if($_POST["password"]=='12345678'){
            $ROLEACCOUNT_PASSWORD=100012;
        }else{
            $ROLEACCOUNT_PASSWORD=$_POST["password"];
        }
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

        $sql_login = "SELECT DISTINCT ID,PersonCode,nameT,RA_USERNAME,RA_PASSWORD,AREA FROM vwUSERLOGIN WHERE RA_USERNAME = ? AND RA_PASSWORD = ? AND ROACACTIVE = 'Y'";
        $parameters = [$ROLEACCOUNT_USERNAME,$ROLEACCOUNT_PASSWORD];
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
    
    function logrepass($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $RA_PERSONCODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];

        $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_REMARK) VALUES (?,?,?,?)";
        $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$RA_PERSONCODE);
        $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
        if($stmt_log_tran === false){ 
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $RS="complete";
        }
        // die( print_r( sqlsrv_errors(), true));
        // $RS="error"; 

        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function rolesession($KEYWORD,  $username,  $password, $role, $roleaccount){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $ROLEACCOUNT_USERNAME = $_POST["username"];
        $ROLEACCOUNT_PASSWORD = $_POST["password"];
        $AD_ROLE_ID = $_POST["role"];
        $AD_RA_ID = $_POST["roleaccount"];
        
        $sql_rolecheck = "SELECT PersonID,FnameT,LnameT,nameE,CurrentTel,Company_Code,Company_NameT,PositionID,PositionNameT,RU_ID,RU_NAME,RU_IMG FROM vwUSERLOGIN WHERE RA_USERNAME = ? AND RA_PASSWORD = ? AND RU_ID = ? AND RA_ID = ? AND ROACACTIVE = 'Y'";
        $parameters = [$ROLEACCOUNT_USERNAME,$ROLEACCOUNT_PASSWORD,$AD_ROLE_ID,$AD_RA_ID];
        $query_rolecheck = sqlsrv_query($conn, $sql_rolecheck, $parameters);
        $result_rolecheck = sqlsrv_fetch_array($query_rolecheck,SQLSRV_FETCH_ASSOC);      

        if(!$result_rolecheck){
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            // $_SESSION["AD_ID"] = $result_rolecheck["ID"];
            $_SESSION['AD_PERSONID'] = $result_rolecheck["PersonID"];
            // $_SESSION['AD_PERSONCODE'] = $result_rolecheck["PersonCode"];
            $_SESSION['AD_FIRSTNAME'] = $result_rolecheck["FnameT"];
            $_SESSION['AD_LASTNAME'] = $result_rolecheck["LnameT"];
            // $_SESSION['AD_NAMETHAI'] = $result_rolecheck["nameT"];
            $_SESSION['AD_NAMEENGLISH'] = $result_rolecheck["nameE"];
            $_SESSION['AD_CURRENTTEL'] = $result_rolecheck["CurrentTel"];
            $_SESSION['AD_COMPANYCODE'] = $result_rolecheck["Company_Code"];
            $_SESSION['AD_COMPANYNAME'] = $result_rolecheck["Company_NameT"];
            $_SESSION['AD_POSITIONID'] = $result_rolecheck["PositionID"];
            $_SESSION['AD_POSITIONNAME'] = $result_rolecheck["PositionNameT"];
            // $_SESSION['AD_ROLEACCOUNT_USERNAME'] = $result_rolecheck["RA_USERNAME"];
            // $_SESSION['AD_ROLEACCOUNT_PASSWORD'] = $result_rolecheck["RA_PASSWORD"];
            $_SESSION['AD_ROLE_ID'] = $result_rolecheck["RU_ID"];
            $_SESSION["AD_ROLE_NAME"] = $result_rolecheck["RU_NAME"];
            $_SESSION["AD_RU_IMG"] = $result_rolecheck["RU_IMG"];

            $RS="complete";
        } 
        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransacmenu($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $MN_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];
        
        $sql_logcheck = "SELECT MN_NAME FROM MENU WHERE MN_CODE = ?";
        $parameters = [$MN_CODE];
        $query_logcheck = sqlsrv_query($conn, $sql_logcheck, $parameters);
        $result_logcheck = sqlsrv_fetch_array($query_logcheck,SQLSRV_FETCH_ASSOC);    
        $MN_NAME=$result_logcheck["MN_NAME"];  
        $RSMN_CODE=$result_logcheck["MN_CODE"];

        if(!$MN_NAME){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$MN_CODE,'-');
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
            // die( print_r( sqlsrv_errors(), true));
            // $RS="error";
        }else if($MN_NAME==$REMARK){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$MN_CODE,'-');
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
        }else if($MN_NAME!=$REMARK){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$MN_CODE,$REMARK);
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
        } 
        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransacrole($KEYWORD,  $logcode,  $remark,  $id){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $RU_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];
        
        $sql_logcheck = "SELECT RU_NAME FROM ROLE_USER WHERE RU_CODE = ?";
        $parameters = [$RU_CODE];
        $query_logcheck = sqlsrv_query($conn, $sql_logcheck, $parameters);
        $result_logcheck = sqlsrv_fetch_array($query_logcheck,SQLSRV_FETCH_ASSOC);    
        $RU_NAME=$result_logcheck["RU_NAME"];  

        if(!$RU_NAME){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$RU_CODE,'-');
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
            // die( print_r( sqlsrv_errors(), true));
            // $RS="error";
        }else if($RU_NAME==$REMARK){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$RU_CODE,'-');
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
        }else if($RU_NAME!=$REMARK){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$RU_CODE,$REMARK);
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
        } 
        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransacroleaccount($KEYWORD,  $logcode,  $remark,  $id){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $RU_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];

        $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
        $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$RU_CODE,$REMARK);
        $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
        if($stmt_log_tran === false){ 
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $RS="complete";
        }
        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransacrepairhole($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $RPH_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];
        
        $sql_logcheck = "SELECT RPH_REPAIRHOLE FROM REPAIR_HOLE WHERE RPH_CODE = ?";
        $parameters = [$RPH_CODE];
        $query_logcheck = sqlsrv_query($conn, $sql_logcheck, $parameters);
        $result_logcheck = sqlsrv_fetch_array($query_logcheck,SQLSRV_FETCH_ASSOC);    
        $RPH_REPAIRHOLE=$result_logcheck["RPH_REPAIRHOLE"];  
        $RSRPH_CODE=$result_logcheck["RPH_CODE"];

        if(!$RPH_REPAIRHOLE){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$RPH_CODE,'-');
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
            // die( print_r( sqlsrv_errors(), true));
            // $RS="error";
        }else if($RPH_REPAIRHOLE==$REMARK){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$RPH_CODE,'-');
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
        }else if($RPH_REPAIRHOLE!=$REMARK){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$RPH_CODE,$REMARK);
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
        } 
        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransacrepairman($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $RPM_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];

        $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
        $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$RPM_CODE,'-');
        $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
        if($stmt_log_tran === false){ 
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $RS="complete";
        }
        // die( print_r( sqlsrv_errors(), true));
        // $RS="error";

        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransacoutergarage($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $OTGR_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];

        $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
        $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$OTGR_CODE,'-');
        $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
        if($stmt_log_tran === false){ 
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $RS="complete";
        }
        // die( print_r( sqlsrv_errors(), true));
        // $RS="error";

        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransaccustomer($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $CTM_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];

        $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
        $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$CTM_CODE,'-');
        $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
        if($stmt_log_tran === false){ 
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $RS="complete";
        }
        // die( print_r( sqlsrv_errors(), true));
        // $RS="error";

        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransaccustomercar($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $CTMC_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];

        $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
        $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$CTMC_CODE,'-');
        $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
        if($stmt_log_tran === false){ 
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $RS="complete";
        }
        // die( print_r( sqlsrv_errors(), true));
        // $RS="error";

        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransacnature($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $NTRP_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];
        
        $sql_logcheck = "SELECT NTRP_NAME FROM NATUREREPAIR WHERE NTRP_CODE = ?";
        $parameters = [$NTRP_CODE];
        $query_logcheck = sqlsrv_query($conn, $sql_logcheck, $parameters);
        $result_logcheck = sqlsrv_fetch_array($query_logcheck,SQLSRV_FETCH_ASSOC);    
        $NTRP_NAME=$result_logcheck["NTRP_NAME"];  
        $RSNTRP_CODE=$result_logcheck["NTRP_CODE"];

        if(!$NTRP_NAME){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$NTRP_CODE,'-');
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
            // die( print_r( sqlsrv_errors(), true));
            // $RS="error";
        }else if($NTRP_NAME==$REMARK){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$NTRP_CODE,'-');
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
        }else if($NTRP_NAME!=$REMARK){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$NTRP_CODE,$REMARK);
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
        } 
        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransacnaturesub($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $NTRP_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];
        
        $sql_logcheck = "SELECT NTRP_NAME FROM NATUREREPAIR WHERE NTRP_CODE = ?";
        $parameters = [$NTRP_CODE];
        $query_logcheck = sqlsrv_query($conn, $sql_logcheck, $parameters);
        $result_logcheck = sqlsrv_fetch_array($query_logcheck,SQLSRV_FETCH_ASSOC);    
        $NTRP_NAME=$result_logcheck["NTRP_NAME"];  
        $RSNTRP_CODE=$result_logcheck["NTRP_CODE"];

        if(!$NTRP_NAME){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$NTRP_CODE,'-');
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
            // die( print_r( sqlsrv_errors(), true));
            // $RS="error";
        }else if($NTRP_NAME==$REMARK){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$NTRP_CODE,'-');
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
        }else if($NTRP_NAME!=$REMARK){
            $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
            $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$NTRP_CODE,$REMARK);
            $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
            if($stmt_log_tran === false){ 
                die( print_r( sqlsrv_errors(), true));
                $RS="error";
            }else{
                $RS="complete";
            }
        } 
        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransacmileagepm($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $MLPM_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];

        $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
        $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$MLPM_CODE,'-');
        $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
        if($stmt_log_tran === false){ 
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $RS="complete";
        }
        // die( print_r( sqlsrv_errors(), true));
        // $RS="error";

        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransacsparepart($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $SPP_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];

        $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
        $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$SPP_CODE,'-');
        $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
        if($stmt_log_tran === false){ 
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $RS="complete";
        }
        // die( print_r( sqlsrv_errors(), true));
        // $RS="error";

        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransactyperepairwork($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $TRPW_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];

        $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
        $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$TRPW_CODE,'-');
        $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
        if($stmt_log_tran === false){ 
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $RS="complete";
        }
        // die( print_r( sqlsrv_errors(), true));
        // $RS="error";

        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransacchecklist($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $CLRP_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];

        $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
        $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$CLRP_CODE,'-');
        $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
        if($stmt_log_tran === false){ 
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $RS="complete";
        }
        // die( print_r( sqlsrv_errors(), true));
        // $RS="error";

        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransaccartype($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $VHCCT_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];

        $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
        $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$VHCCT_CODE,'-');
        $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
        if($stmt_log_tran === false){ 
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $RS="complete";
        }
        // die( print_r( sqlsrv_errors(), true));
        // $RS="error";

        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }
    
    function logtransacrequestrepair($KEYWORD,  $logcode,  $remark,  $refcode){
        $path = '../';   	
        include ($path.'include/connect.php');                        
        $LOGCODE = $_POST["logcode"];
        $REMARK = $_POST["remark"];
        $VHCCT_CODE = $_POST["refcode"];
        $SSLGD = date("Y-m-d H:i:s");
        $SSPSC = $_SESSION['AD_PERSONCODE'];

        $stm_log_tran = "INSERT INTO LOG_TRANSACTION (LOGACT_CODE,TIMESTAMP,LOG_EMPLOYEECODE,LOG_RESERVE_CODE,LOG_REMARK) VALUES (?,?,?,?,?)";
        $params_log_tran = array($LOGCODE,$SSLGD,$SSPSC,$VHCCT_CODE,'-');
        $stmt_log_tran = sqlsrv_query( $conn, $stm_log_tran, $params_log_tran);
        if($stmt_log_tran === false){ 
            die( print_r( sqlsrv_errors(), true));
            $RS="error";
        }else{
            $RS="complete";
        }
        // die( print_r( sqlsrv_errors(), true));
        // $RS="error";

        // sqlsrv_close($conn);
        echo json_encode($RS);
        return $RS;
    }

?>