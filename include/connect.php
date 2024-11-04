<?php include('db.php');
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
    {
        header('Location: ../MOBILE');
        // echo "เป็นการดูจากโทรศัพท์เคลื่อนที่";
    }
    if (!session_id()) session_start(); 
    error_reporting(0); //E_ALL แสดง error ทั้งหมด | ใส่ 0 ปิดแสดง error ทั้งหมด
    date_default_timezone_set('Asia/Bangkok');
    // FOR EMS_NEW
   	$connectionInfo = array("Database"=>$DATABASE, "UID"=>$USERNAME, "PWD"=>$PASSWORD, "MultipleActiveResultSets"=>true,"CharacterSet"  => 'UTF-8');
    $conn = sqlsrv_connect($HOST, $connectionInfo);    
    if( $conn === false ) {
        die( print_r( sqlsrv_errors(), true));
    }else{        
        // echo "Database Connected1.";
    }

    // FOR EMS_OLD
    $connectionInfo_old = array("Database"=>$DATABASE_OLD, "UID"=>$USERNAME_OLD, "PWD"=>$PASSWORD_OLD, "MultipleActiveResultSets"=>true,"CharacterSet"  => 'UTF-8');
    $conn_old = sqlsrv_connect($HOST_OLD, $connectionInfo_old);    
    if( $conn_old === false ) {
        die( print_r( sqlsrv_errors(), true));
    }else{        
        // echo "Database Connected2.";
    }
    
    // FOR EMS_TMS
    $connectionInfo_tms = array("Database"=>$DATABASE_TMS, "UID"=>$USERNAME_TMS, "PWD"=>$PASSWORD_TMS, "MultipleActiveResultSets"=>true,"CharacterSet"  => 'UTF-8');
    $conn_tms = sqlsrv_connect($HOST_TMS, $connectionInfo_tms);    
    if( $conn_tms === false ) {
        die( print_r( sqlsrv_errors(), true));
    }else{        
        // echo "Database Connected2.";
    }
	###########################################################################################################

	// $path='../';
	$title="E-Maintenance";	
	$iconimage="https://img2.pic.in.th/pic/car_repair.png";	
    
    // GETDATENOW
    $sql_getdate="SELECT  * FROM vwGETDATE";
    $params_getdate = array();	
    $query_getdate = sqlsrv_query( $conn, $sql_getdate, $params_getdate);	
    $result_getdate = sqlsrv_fetch_array($query_getdate, SQLSRV_FETCH_ASSOC);
    $GETYEARTH = $result_getdate["GETYEAR"]+543;
    $GETYEAREN = $result_getdate["GETYEAR"];
    $GETDAYEN = $result_getdate["SYSDATE"];
    $DAYMONTH = $result_getdate["DAYMONTH"];
    $STARTWEEKDAYMONTH = $result_getdate["STARTWEEKDAYMONTH"];
    $ENDWEEKDAYMONTH = $result_getdate["ENDWEEKDAYMONTH"];

    $STARTWEEKNEW = $result_getdate["STARTWEEK"];
	$EXPSW = explode('/', $STARTWEEKNEW);
	$FIRSTYEAR = $EXPSW[2];

    $GETDAYTH = $DAYMONTH.$GETYEARTH;
    $STARTWEEK = $result_getdate["STARTWEEKDAYMONTH"].'/'.$FIRSTYEAR;
    $ENDWEEK = $result_getdate["ENDWEEKDAYMONTH"].'/'.$GETYEAREN;

    return $conn;
    sqlsrv_close($conn);
    
    return $conn_old;
    sqlsrv_close($conn_old);
    
    return $conn_tms;
    sqlsrv_close($conn_tms);
?>
