<?php
    date_default_timezone_set('Asia/Bangkok');   
    header("Content-type:text/html; charset=UTF-8");        
    header("Cache-Control: no-store, no-cache, must-revalidate");       
    header("Cache-Control: post-check=0, pre-check=0", false);   

    include('../include/connect.php');
    $SS_AREA=$_SESSION["AD_AREA"];
    $SS_PSC=$_SESSION['AD_PERSONCODE'];

    if($_GET['target']==='1'){
        // echo date("Y-m-d H:i:s");
        // exit;
        $sql_rprq_bm = "SELECT COUNT(RPRQ_STATUSREQUEST) COUNTSTATUS FROM vwREPAIRREQUEST WHERE RPRQ_STATUSREQUEST = 'รอตรวจสอบ' AND RPRQ_WORKTYPE = 'BM' AND RPRQ_AREA = '$SS_AREA'";
        $query_rprq_bm = sqlsrv_query($conn, $sql_rprq_bm);
        $result_rprq_bm = sqlsrv_fetch_array($query_rprq_bm, SQLSRV_FETCH_ASSOC);
        $count1=$result_rprq_bm['COUNTSTATUS'];

        $countnum=$count1;
        if($countnum!=0){ 
            echo '<span class="badgeside blink_me">'.$countnum.'</span>';
        }  
    }

    if($_GET['target']==='2'){
        // echo date("Y-m-d H:i:s");
        // exit;
        $sql_rprq_pm = "SELECT COUNT(RPRQ_STATUSREQUEST) COUNTSTATUS FROM vwREPAIRREQUEST_PM WHERE RPRQ_STATUSREQUEST = 'รอตรวจสอบ' AND RPRQ_WORKTYPE = 'PM' AND RPRQ_AREA = '$SS_AREA'";
        $query_rprq_pm = sqlsrv_query($conn, $sql_rprq_pm);
        $result_rprq_pm = sqlsrv_fetch_array($query_rprq_pm, SQLSRV_FETCH_ASSOC);
        $count2=$result_rprq_pm['COUNTSTATUS'];

        $countnum=$count2;
        if($countnum!=0){ 
            echo '<span class="badgeside blink_me">'.$countnum.'</span>';
        }  
    }

    if($_GET['target']==='3'){
        // echo date("Y-m-d H:i:s");
        // exit;
        $sql_rprq_bm = "SELECT COUNT(RPRQ_STATUSREQUEST) COUNTSTATUS FROM vwREPAIRREQUEST WHERE RPRQ_STATUSREQUEST = 'รอตรวจสอบ' AND RPRQ_WORKTYPE = 'BM' AND RPRQ_AREA = '$SS_AREA'";
        $query_rprq_bm = sqlsrv_query($conn, $sql_rprq_bm);
        $result_rprq_bm = sqlsrv_fetch_array($query_rprq_bm, SQLSRV_FETCH_ASSOC);
        $count1=$result_rprq_bm['COUNTSTATUS'];

        $sql_rprq_pm = "SELECT COUNT(RPRQ_STATUSREQUEST) COUNTSTATUS FROM vwREPAIRREQUEST_PM WHERE RPRQ_STATUSREQUEST = 'รอตรวจสอบ' AND RPRQ_WORKTYPE = 'PM' AND RPRQ_AREA = '$SS_AREA'";
        $query_rprq_pm = sqlsrv_query($conn, $sql_rprq_pm);
        $result_rprq_pm = sqlsrv_fetch_array($query_rprq_pm, SQLSRV_FETCH_ASSOC);
        $count2=$result_rprq_pm['COUNTSTATUS'];

        $countnum=$count1+$count2;
        if($countnum!=0){ 
            echo '<span class="badgeside blink_me">'.$countnum.'</span>';
        }  
    }

    if($_GET['target']==='4'){
        // echo date("Y-m-d H:i:s");
        // exit;
        $sql_rprq_bm = "SELECT COUNT(RPRQ_STATUSREQUEST) COUNTSTATUS FROM vwREPAIRREQUEST WHERE RPRQ_STATUSREQUEST = 'รอจ่ายงาน' AND RPRQ_WORKTYPE = 'BM' AND RPRQ_AREA = '$SS_AREA'";
        $query_rprq_bm = sqlsrv_query($conn, $sql_rprq_bm);
        $result_rprq_bm = sqlsrv_fetch_array($query_rprq_bm, SQLSRV_FETCH_ASSOC);
        $count1=$result_rprq_bm['COUNTSTATUS'];

        $sql_rprq_pm = "SELECT COUNT(RPRQ_STATUSREQUEST) COUNTSTATUS FROM vwREPAIRREQUEST_PM WHERE RPRQ_STATUSREQUEST = 'รอจ่ายงาน' AND RPRQ_WORKTYPE = 'PM' AND RPRQ_AREA = '$SS_AREA'";
        $query_rprq_pm = sqlsrv_query($conn, $sql_rprq_pm);
        $result_rprq_pm = sqlsrv_fetch_array($query_rprq_pm, SQLSRV_FETCH_ASSOC);
        $count2=$result_rprq_pm['COUNTSTATUS'];

        $countnum=$count1+$count2;
        if($countnum!=0){ 
            echo '<span class="badgeside blink_me">'.$countnum.'</span>';
        }  
    }

    if($_GET['target']==='5'){
        // echo date("Y-m-d H:i:s");
        // exit;
        $sql_rprq_psc = "SELECT COUNT(DISTINCT A.RPRQ_CODE) COUNTSTATUS  
        FROM REPAIRMANEMP A LEFT JOIN REPAIRREQUEST B ON B.RPRQ_CODE = A.RPRQ_CODE
        WHERE RPME_CODE = '$SS_PSC' AND RPRQ_STATUSREQUEST = 'รอคิวซ่อม'";
        $query_rprq_psc = sqlsrv_query($conn, $sql_rprq_psc);
        $result_rprq_psc = sqlsrv_fetch_array($query_rprq_psc, SQLSRV_FETCH_ASSOC);
        $count1=$result_rprq_psc['COUNTSTATUS'];

        if($count1!=0){ 
            echo '<span class="badgeside blink_me">'.$count1.'</span>';
        }  
    }

    if($_GET['target']==='6'){
        // echo date("Y-m-d H:i:s");
        // exit;
        $sql_rprq_bm = "SELECT COUNT(RPRQ_STATUSREQUEST) COUNTSTATUS FROM vwREPAIRREQUEST WHERE RPRQ_STATUSREQUEST = 'รอส่งแผน' AND RPRQ_WORKTYPE = 'BM' AND RPRQ_AREA = '$SS_AREA'";
        $query_rprq_bm = sqlsrv_query($conn, $sql_rprq_bm);
        $result_rprq_bm = sqlsrv_fetch_array($query_rprq_bm, SQLSRV_FETCH_ASSOC);
        $count1=$result_rprq_bm['COUNTSTATUS'];

        $countnum=$count1;
        if($countnum!=0){ 
            echo '<span class="badgeside blink_me">'.$countnum.'</span>';
        }  
    }
    
sqlsrv_close($conn);
?>