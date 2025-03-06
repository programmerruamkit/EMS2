<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];	
	
	$SESSION_AREA=$_SESSION["AD_AREA"];

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();
                
	######################################################################################################        
		$CTM_GROUP=$_POST['TYPECUSTOMERS'];  
		if($CTM_GROUP=="cusout"){
			$sql_vehicleinfo = "SELECT * FROM CUSTOMER_CAR 
			LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
			LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
			WHERE 1=1 AND VEHICLEGROUPDESC = 'Transport' AND ACTIVESTATUS = '1' AND VEHICLEREGISNUMBER ='".$_POST["VEHICLEREGISNUMBER1"]."'";
			// WHERE 1=1 AND VEHICLEGROUPDESC = 'Transport' AND ACTIVESTATUS = '1' AND AFFCOMPANY ='".$_POST["AFFCOMPANY"]."'";
		}else{
			$sql_vehicleinfo = "SELECT * FROM vwVEHICLEINFO 
			LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
			LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
			WHERE 1=1 AND VEHICLEGROUPDESC = 'Transport' AND ACTIVESTATUS = '1' AND VEHICLEREGISNUMBER ='".$_POST["VEHICLEREGISNUMBER1"]."'";
			// WHERE 1=1 AND VEHICLEGROUPDESC = 'Transport' AND ACTIVESTATUS = '1' AND AFFCOMPANY ='".$_POST["AFFCOMPANY"]."'";
		}
		$query_vehicleinfo = sqlsrv_query($conn, $sql_vehicleinfo);
		$result_vehicleinfo = sqlsrv_fetch_array($query_vehicleinfo, SQLSRV_FETCH_ASSOC);
		
		$VHCTMG_LINEOFWORK = $result_vehicleinfo['VHCCT_PM'];		
		$VHCCT_ID = $result_vehicleinfo["VHCCT_ID"];
                
		$VEHICLEREGISNUMBER=$result_vehicleinfo['VEHICLEREGISNUMBER'];
		$THAINAME=$result_vehicleinfo['THAINAME'];
		$AFFCOMPANY = $_POST["AFFCOMPANY"];		
		if($AFFCOMPANY=="RKS"||$AFFCOMPANY=="RKR"||$AFFCOMPANY=="RKL"){     
			$field="VEHICLEREGISNUMBER = '$VEHICLEREGISNUMBER'";
		}else if($AFFCOMPANY=="RRC"||$AFFCOMPANY=="RCC"||$AFFCOMPANY=="RATC"){
			$explodes = explode('(', $THAINAME);
			$THAINAME = $explodes[0];
			$field="THAINAME = '$THAINAME'";
		}else{
			$field="VEHICLEREGISNUMBER = '$VEHICLEREGISNUMBER'";
		}
		
		if($CTM_GROUP=="cusout"){
			// $sql_mileage = "SELECT TOP 1 MILEAGENUMBER AS MAXMILEAGENUMBER,VEHICLEREGISNUMBER,CREATEDATE FROM TEST_MILEAGE_PM WHERE ACTIVESTATUS = 1  AND MILEAGENUMBER <> '0' AND VEHICLEREGISNUMBER = '".$result_vehicleinfo['VEHICLEREGISNUMBER']."' ORDER BY CREATEDATE DESC ";
			$sql_mileage = "SELECT TOP 1 MILEAGENUMBER AS MAXMILEAGENUMBER,VEHICLEREGISNUMBER,CREATEDATE FROM TEST_MILEAGE_PM WHERE ACTIVESTATUS = 1  AND MILEAGENUMBER <> '0' AND VEHICLEREGISNUMBER = '".$result_vehicleinfo['VEHICLEREGISNUMBER']."' ORDER BY MILEAGEID DESC ";
			$params_mileage = array();
			$query_mileage = sqlsrv_query($conn, $sql_mileage, $params_mileage);
			$result_mileage = sqlsrv_fetch_array($query_mileage, SQLSRV_FETCH_ASSOC); 
		}else{
			// $sql_mileage = "SELECT TOP 1 * FROM vwMILEAGE WHERE VEHICLEREGISNUMBER = '".$result_vehicleinfo['VEHICLEREGISNUMBER']."' ORDER BY CREATEDATE DESC ";
			$sql_mileage = "SELECT TOP 1 * FROM vwMILEAGE WHERE $field ORDER BY MILEAGEID DESC ";
			$params_mileage = array();
			$query_mileage = sqlsrv_query($conn, $sql_mileage, $params_mileage);
			$result_mileage = sqlsrv_fetch_array($query_mileage, SQLSRV_FETCH_ASSOC); 
		}
		
		if(isset($result_mileage['MAXMILEAGENUMBER'])){
			if($result_mileage['MAXMILEAGENUMBER']>1000000){
				$MAXMILEAGENUMBER = $result_mileage['MAXMILEAGENUMBER']-1000000;
			}else{
				$MAXMILEAGENUMBER = $result_mileage['MAXMILEAGENUMBER'];
			}
		}else{
			$MAXMILEAGENUMBER = 0;
		}

		if(($MAXMILEAGENUMBER >= '0') && ($MAXMILEAGENUMBER <= '1000000')){
			$fildsfindMLPM="MLPM_MILEAGE_10K1M";
		}else if(($MAXMILEAGENUMBER >= '1000001') && ($MAXMILEAGENUMBER <= '2000000')){
			$fildsfindMLPM="MLPM_MILEAGE_1M2M";
		}else if(($MAXMILEAGENUMBER >= '2000001') && ($MAXMILEAGENUMBER <= '3000000')){
			$fildsfindMLPM="MLPM_MILEAGE_2M3M";
		}

		$sql_rankpm = "SELECT TOP 1 * FROM MILEAGESETPM WHERE MLPM_LINEOFWORK = '$VHCTMG_LINEOFWORK' AND $fildsfindMLPM > '".$result_mileage['MAXMILEAGENUMBER']."' AND MLPM_AREA = '$SESSION_AREA' ORDER BY $fildsfindMLPM ASC";
		$params_rankpm = array();
		$query_rankpm = sqlsrv_query($conn, $sql_rankpm, $params_rankpm);
		$result_rankpm = sqlsrv_fetch_array($query_rankpm, SQLSRV_FETCH_ASSOC);                             
		$MLPM_MILEAGE=$result_rankpm[$fildsfindMLPM];

		if($result_rankpm["MLPM_NAME"]=="PMoRS-1"){
			$fildsfindETM = "ETM_PM1";
		}else if($result_rankpm["MLPM_NAME"]=="PMoRS-2"){
			$fildsfindETM = "ETM_PM2";
		}else if($result_rankpm["MLPM_NAME"]=="PMoRS-3"){
			$fildsfindETM = "ETM_PM3";
		}else if($result_rankpm["MLPM_NAME"]=="PMoRS-4"){
			$fildsfindETM = "ETM_PM4";
		}else if($result_rankpm["MLPM_NAME"]=="PMoRS-5"){
			$fildsfindETM = "ETM_PM5";
		}else if($result_rankpm["MLPM_NAME"]=="PMoRS-6"){
			$fildsfindETM = "ETM_PM6";
		}else if($result_rankpm["MLPM_NAME"]=="PMoRS-7"){
			$fildsfindETM = "ETM_PM7";
		}else if($result_rankpm["MLPM_NAME"]=="PMoRS-8"){
			$fildsfindETM = "ETM_PM8";
		}else if($result_rankpm["MLPM_NAME"]=="PMoRS-9"){
			$fildsfindETM = "ETM_PM9";
		}else if($result_rankpm["MLPM_NAME"]=="PMoRS-10"){
			$fildsfindETM = "ETM_PM10";
		}else if($result_rankpm["MLPM_NAME"]=="PMoRS-11"){
			$fildsfindETM = "ETM_PM11";
		}else if($result_rankpm["MLPM_NAME"]=="PMoRS-12"){
			$fildsfindETM = "ETM_PM12";
		}		

		// PM-เครื่องยนต์ ######################################################################################################
			$sql_selestimate1 = "SELECT TOP 1 $fildsfindETM FROM ESTIMATE WHERE ETM_NUM = '1' AND VHCCT_ID = '$VHCCT_ID' AND ETM_GROUP = 'PM-เครื่องยนต์' ORDER BY ETM_ID DESC";
			$params_selestimate1 = array();
			$query_selestimate1 = sqlsrv_query($conn, $sql_selestimate1, $params_selestimate1);
			$result_selestimate1 = sqlsrv_fetch_array($query_selestimate1, SQLSRV_FETCH_ASSOC);                       
			$RPETM_SPARESPART1=$result_selestimate1[$fildsfindETM];
			$sql_selestimate2 = "SELECT $fildsfindETM FROM ESTIMATE WHERE ETM_NUM = '3' AND VHCCT_ID = '$VHCCT_ID' AND ETM_GROUP = 'PM-เครื่องยนต์' ORDER BY ETM_ID DESC";
			$params_selestimate2 = array();
			$query_selestimate2 = sqlsrv_query($conn, $sql_selestimate2, $params_selestimate2);
			$result_selestimate2 = sqlsrv_fetch_array($query_selestimate2, SQLSRV_FETCH_ASSOC);                       
			$RPETM_WAGE2=$result_selestimate2[$fildsfindETM];
			$sql_selestimate3 = "SELECT $fildsfindETM FROM ESTIMATE WHERE ETM_NUM = '4' AND VHCCT_ID = '$VHCCT_ID' AND ETM_GROUP = 'PM-เครื่องยนต์' ORDER BY ETM_ID DESC";
			$params_selestimate3 = array();
			$query_selestimate3 = sqlsrv_query($conn, $sql_selestimate3, $params_selestimate3);
			$result_selestimate3 = sqlsrv_fetch_array($query_selestimate3, SQLSRV_FETCH_ASSOC);                       
			$RPETM_HOUR3=$result_selestimate3[$fildsfindETM];
		// PM-โครงสร้าง ######################################################################################################
			$sql_selestimate4 = "SELECT TOP 1 $fildsfindETM FROM ESTIMATE WHERE ETM_NUM = '1' AND VHCCT_ID = '$VHCCT_ID' AND ETM_GROUP = 'PM-โครงสร้าง' ORDER BY ETM_ID DESC";
			$params_selestimate4 = array();
			$query_selestimate4 = sqlsrv_query($conn, $sql_selestimate4, $params_selestimate4);
			$result_selestimate4 = sqlsrv_fetch_array($query_selestimate4, SQLSRV_FETCH_ASSOC);                       
			$RPETM_SPARESPART4=$result_selestimate4[$fildsfindETM];
			$sql_selestimate5 = "SELECT $fildsfindETM FROM ESTIMATE WHERE ETM_NUM = '3' AND VHCCT_ID = '$VHCCT_ID' AND ETM_GROUP = 'PM-โครงสร้าง' ORDER BY ETM_ID DESC";
			$params_selestimate5 = array();
			$query_selestimate5 = sqlsrv_query($conn, $sql_selestimate5, $params_selestimate5);
			$result_selestimate5 = sqlsrv_fetch_array($query_selestimate5, SQLSRV_FETCH_ASSOC);                       
			$RPETM_WAGE5=$result_selestimate5[$fildsfindETM];
			$sql_selestimate6 = "SELECT $fildsfindETM FROM ESTIMATE WHERE ETM_NUM = '4' AND VHCCT_ID = '$VHCCT_ID' AND ETM_GROUP = 'PM-โครงสร้าง' ORDER BY ETM_ID DESC";
			$params_selestimate6 = array();
			$query_selestimate6 = sqlsrv_query($conn, $sql_selestimate6, $params_selestimate6);
			$result_selestimate6 = sqlsrv_fetch_array($query_selestimate6, SQLSRV_FETCH_ASSOC);                       
			$RPETM_HOUR6=$result_selestimate6[$fildsfindETM];
		// PM-ยาง-ช่วงล่าง ######################################################################################################
			$sql_selestimate7 = "SELECT TOP 1 $fildsfindETM FROM ESTIMATE WHERE ETM_NUM = '1' AND VHCCT_ID = '$VHCCT_ID' AND ETM_GROUP = 'PM-ยาง-ช่วงล่าง' ORDER BY ETM_ID DESC";
			$params_selestimate7 = array();
			$query_selestimate7 = sqlsrv_query($conn, $sql_selestimate7, $params_selestimate7);
			$result_selestimate7 = sqlsrv_fetch_array($query_selestimate7, SQLSRV_FETCH_ASSOC);                       
			$RPETM_SPARESPART7=$result_selestimate7[$fildsfindETM];
			$sql_selestimate8 = "SELECT $fildsfindETM FROM ESTIMATE WHERE ETM_NUM = '3' AND VHCCT_ID = '$VHCCT_ID' AND ETM_GROUP = 'PM-ยาง-ช่วงล่าง' ORDER BY ETM_ID DESC";
			$params_selestimate8 = array();
			$query_selestimate8 = sqlsrv_query($conn, $sql_selestimate8, $params_selestimate8);
			$result_selestimate8 = sqlsrv_fetch_array($query_selestimate8, SQLSRV_FETCH_ASSOC);                       
			$RPETM_WAGE8=$result_selestimate8[$fildsfindETM];
			$sql_selestimate9 = "SELECT $fildsfindETM FROM ESTIMATE WHERE ETM_NUM = '4' AND VHCCT_ID = '$VHCCT_ID' AND ETM_GROUP = 'PM-ยาง-ช่วงล่าง' ORDER BY ETM_ID DESC";
			$params_selestimate9 = array();
			$query_selestimate9 = sqlsrv_query($conn, $sql_selestimate9, $params_selestimate9);
			$result_selestimate9 = sqlsrv_fetch_array($query_selestimate9, SQLSRV_FETCH_ASSOC);                       
			$RPETM_HOUR9=$result_selestimate9[$fildsfindETM];
		// PM-ระบบไฟ ######################################################################################################
			$sql_selestimate10 = "SELECT TOP 1 $fildsfindETM FROM ESTIMATE WHERE ETM_NUM = '1' AND VHCCT_ID = '$VHCCT_ID' AND ETM_GROUP = 'PM-ระบบไฟ' ORDER BY ETM_ID DESC";
			$params_selestimate10 = array();
			$query_selestimate10 = sqlsrv_query($conn, $sql_selestimate10, $params_selestimate10);
			$result_selestimate10 = sqlsrv_fetch_array($query_selestimate10, SQLSRV_FETCH_ASSOC);                       
			$RPETM_SPARESPART10=$result_selestimate10[$fildsfindETM];
			$sql_selestimate11 = "SELECT $fildsfindETM FROM ESTIMATE WHERE ETM_NUM = '3' AND VHCCT_ID = '$VHCCT_ID' AND ETM_GROUP = 'PM-ระบบไฟ' ORDER BY ETM_ID DESC";
			$params_selestimate11 = array();
			$query_selestimate11 = sqlsrv_query($conn, $sql_selestimate11, $params_selestimate11);
			$result_selestimate11 = sqlsrv_fetch_array($query_selestimate11, SQLSRV_FETCH_ASSOC);                       
			$RPETM_WAGE11=$result_selestimate11[$fildsfindETM];
			$sql_selestimate12 = "SELECT $fildsfindETM FROM ESTIMATE WHERE ETM_NUM = '4' AND VHCCT_ID = '$VHCCT_ID' AND ETM_GROUP = 'PM-ระบบไฟ' ORDER BY ETM_ID DESC";
			$params_selestimate12 = array();
			$query_selestimate12 = sqlsrv_query($conn, $sql_selestimate12, $params_selestimate12);
			$result_selestimate12 = sqlsrv_fetch_array($query_selestimate12, SQLSRV_FETCH_ASSOC);                       
			$RPETM_HOUR12=$result_selestimate12[$fildsfindETM];
		######################################################################################################

	######################################################################################################
	if($proc=="add"){
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPRQ_WORKTYPE = $_POST["RPRQ_WORKTYPE"];
		$RPRQ_REGISHEAD = $_POST["VEHICLEREGISNUMBER1"];
		$RPRQ_REGISTAIL = $_POST["VEHICLEREGISNUMBER2"];
		$RPRQ_CARNAMEHEAD = $_POST["THAINAME1"];
		$RPRQ_CARNAMETAIL = $_POST["THAINAME2"];		
		$RPRQ_CARTYPE = $_POST["RPRQ_CARTYPE"];	
		$RPRQ_LINEOFWORK = $_POST["AFFCOMPANY"];
		$RPRQ_MILEAGELAST = $_POST["MAXMILEAGENUMBER"];
		// $RPRQ_MILEAGEFINISH = $result_rankpm["MLPM_MILEAGE"];
		$RPRQ_MILEAGEFINISH = $MLPM_MILEAGE;
		$RPRQ_RANKPMTYPE = $result_rankpm["MLPM_NAME"];
		$RPRQ_RANKKILOMETER = $_POST["MAXMILEAGENUMBER"];
			$RPRQ_PMLASTDATE = $_POST["RPRQ_PMLASTDATE"];
			$RPRQ_TIMEREPAIR = $_POST["RPRQ_TIMEREPAIR"];
			$RPRQ_SCHEDULEDDATE = $_POST["RPRQ_SCHEDULEDDATE"];

		$datetimeRequest_in = $_POST["datetimeRequest_in"];		
		$exin = explode(" ", $datetimeRequest_in);
		$exin1 = $exin[0];
		$exin2 = $exin[1]; 
		$RPRQ_REQUESTCARDATE = $exin1;
		$RPRQ_REQUESTCARTIME = $exin2;
		$datetimeRequest_out = $_POST["datetimeRequest_out"];		
		$exout = explode(" ", $datetimeRequest_out);
		$exout1 = $exout[0];
		$exout2 = $exout[1]; 
		$RPRQ_USECARDATE = $exout1;
		$RPRQ_USECARTIME = $exout2;

		$RPRQ_PRODUCTINCAR = $_POST["GOTC"];
		$RPRQ_NATUREREPAIR = $_POST["NTORNNW"];
		$RPRQ_TYPECUSTOMER = $_POST["TYPECUSTOMERS"];
		$RPRQ_COMPANYCASH = $_POST["AFFCOMPANY"];
		$RPRQ_REQUESTBY = $_POST["EMP_NAME_RQRP"];
		$RPRQ_CREATEDATE_REQUEST = $_POST["RPRQ_CREATEDATE_REQUEST"];
		$RPRQ_AREA = $_POST["RPRQ_AREA"];
			$RPRQ_STATUS = 'Y';
			$RPRQ_STATUSREQUEST = 'รอตรวจสอบ';
		$RPRQ_CREATEBY = $_POST["RPRQ_CREATEBY"];
			$RPRQ_CREATEDATE = date("Y-m-d H:i:s");

		$RPC_SUBJECT = $_POST["RPM_NATUREREPAIR"];  
		// $RPC_DETAIL = $_POST["RPC_DETAIL"];  	
		$RPC_DETAIL = 'PM-'.$RPRQ_MILEAGEFINISH;  					
		$RPC_IMAGES = $_FILES["RPC_IMAGES"];	

		$sql = "INSERT INTO REPAIRREQUEST (
			RPRQ_CODE, 
			RPRQ_WORKTYPE, 
			RPRQ_REGISHEAD, 
			RPRQ_REGISTAIL, 
			RPRQ_CARNAMEHEAD, 
			RPRQ_CARNAMETAIL, 
			RPRQ_CARTYPE,
			RPRQ_LINEOFWORK,
			RPRQ_MILEAGELAST, 
			RPRQ_MILEAGEFINISH, 
			RPRQ_RANKPMTYPE, 
			RPRQ_RANKKILOMETER, 
			RPRQ_PMLASTDATE, 
			RPRQ_TIMEREPAIR,
			RPRQ_SCHEDULEDDATE, 
			RPRQ_REQUESTCARDATE,
			RPRQ_REQUESTCARTIME,
			RPRQ_USECARDATE,
			RPRQ_USECARTIME,
			RPRQ_PRODUCTINCAR,
			RPRQ_NATUREREPAIR,
			RPRQ_TYPECUSTOMER,
			RPRQ_COMPANYCASH,
			RPRQ_REQUESTBY,
			RPRQ_CREATEDATE_REQUEST,
			RPRQ_AREA,
			RPRQ_STATUS, 
			RPRQ_STATUSREQUEST,
			RPRQ_CREATEBY, 
			RPRQ_CREATEDATE) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$params = array(
			$RPRQ_CODE, 
			$RPRQ_WORKTYPE, 
			$RPRQ_REGISHEAD, 
			$RPRQ_REGISTAIL, 
			$RPRQ_CARNAMEHEAD, 
			$RPRQ_CARNAMETAIL, 
			$RPRQ_CARTYPE,
			$RPRQ_LINEOFWORK,
			$RPRQ_MILEAGELAST, 
			$RPRQ_MILEAGEFINISH, 
			$RPRQ_RANKPMTYPE, 
			$RPRQ_RANKKILOMETER, 
			$RPRQ_PMLASTDATE, 
			$RPRQ_TIMEREPAIR,
			$RPRQ_SCHEDULEDDATE, 
			$RPRQ_REQUESTCARDATE,
			$RPRQ_REQUESTCARTIME,
			$RPRQ_USECARDATE,
			$RPRQ_USECARTIME,
			$RPRQ_PRODUCTINCAR,
			$RPRQ_NATUREREPAIR,
			$RPRQ_TYPECUSTOMER,
			$RPRQ_COMPANYCASH,
			$RPRQ_REQUESTBY,
			$RPRQ_CREATEDATE_REQUEST,
			$RPRQ_AREA,
			$RPRQ_STATUS, 
			$RPRQ_STATUSREQUEST, 
			$RPRQ_CREATEBY, 
			$RPRQ_CREATEDATE
			
		);	
		$stmt = sqlsrv_query( $conn, $sql, $params);

		$sql1 = "INSERT INTO REPAIRCAUSE (RPRQ_CODE,RPC_SUBJECT,RPC_DETAIL,RPC_IMAGES) VALUES (?,?,?,?)";
		$params1 = array($RPRQ_CODE,'EG',$RPC_DETAIL,$RPC_IMAGES);		
		$stmt1 = sqlsrv_query( $conn, $sql1, $params1);	
		
		$sql2 = "INSERT INTO REPAIRCAUSE (RPRQ_CODE,RPC_SUBJECT,RPC_DETAIL,RPC_IMAGES) VALUES (?,?,?,?)";
		$params2 = array($RPRQ_CODE,'BD',$RPC_DETAIL,$RPC_IMAGES);		
		$stmt2 = sqlsrv_query( $conn, $sql2, $params2);	
		
		$sql3 = "INSERT INTO REPAIRCAUSE (RPRQ_CODE,RPC_SUBJECT,RPC_DETAIL,RPC_IMAGES) VALUES (?,?,?,?)";
		$params3 = array($RPRQ_CODE,'TU',$RPC_DETAIL,$RPC_IMAGES);		
		$stmt3 = sqlsrv_query( $conn, $sql3, $params3);	
		
		$sql4 = "INSERT INTO REPAIRCAUSE (RPRQ_CODE,RPC_SUBJECT,RPC_DETAIL,RPC_IMAGES) VALUES (?,?,?,?)";
		$params4 = array($RPRQ_CODE,'EL',$RPC_DETAIL,$RPC_IMAGES);		
		$stmt4 = sqlsrv_query( $conn, $sql4, $params4);	
		
		// $sql5 = "INSERT INTO REPAIRCAUSE (RPRQ_CODE,RPC_SUBJECT,RPC_DETAIL,RPC_IMAGES) VALUES (?,?,?,?)";
		// $params5 = array($RPRQ_CODE,'AC',$RPC_DETAIL,$RPC_IMAGES);		
		// $stmt5 = sqlsrv_query( $conn, $sql5, $params5);	
		
		$sql6 = "INSERT INTO REPAIRESTIMATE (RPRQ_CODE,RPETM_SPARESPART,RPETM_WAGE,RPETM_HOUR,RPETM_TYPEREPAIR,RPETM_NATURE,RPETM_PROCESSBY,RPETM_PROCESSDATE) VALUES (?,?,?,?,?,?,?,?)";
		$params6 = array($RPRQ_CODE,$RPETM_SPARESPART1,$RPETM_WAGE2,$RPETM_HOUR3,'PM','เครื่องยนต์',$RPRQ_CREATEBY,$RPRQ_CREATEDATE);		
		$stmt6 = sqlsrv_query( $conn, $sql6, $params6);	
		
		$sql7 = "INSERT INTO REPAIRESTIMATE (RPRQ_CODE,RPETM_SPARESPART,RPETM_WAGE,RPETM_HOUR,RPETM_TYPEREPAIR,RPETM_NATURE,RPETM_PROCESSBY,RPETM_PROCESSDATE) VALUES (?,?,?,?,?,?,?,?)";
		$params7 = array($RPRQ_CODE,$RPETM_SPARESPART4,$RPETM_WAGE5,$RPETM_HOUR6,'PM','โครงสร้าง',$RPRQ_CREATEBY,$RPRQ_CREATEDATE);		
		$stmt7 = sqlsrv_query( $conn, $sql7, $params7);	
		
		$sql8 = "INSERT INTO REPAIRESTIMATE (RPRQ_CODE,RPETM_SPARESPART,RPETM_WAGE,RPETM_HOUR,RPETM_TYPEREPAIR,RPETM_NATURE,RPETM_PROCESSBY,RPETM_PROCESSDATE) VALUES (?,?,?,?,?,?,?,?)";
		$params8 = array($RPRQ_CODE,$RPETM_SPARESPART7,$RPETM_WAGE8,$RPETM_HOUR9,'PM','ยาง-ช่วงล่าง',$RPRQ_CREATEBY,$RPRQ_CREATEDATE);		
		$stmt8 = sqlsrv_query( $conn, $sql8, $params8);	
		
		$sql9 = "INSERT INTO REPAIRESTIMATE (RPRQ_CODE,RPETM_SPARESPART,RPETM_WAGE,RPETM_HOUR,RPETM_TYPEREPAIR,RPETM_NATURE,RPETM_PROCESSBY,RPETM_PROCESSDATE) VALUES (?,?,?,?,?,?,?,?)";
		$params9 = array($RPRQ_CODE,$RPETM_SPARESPART10,$RPETM_WAGE11,$RPETM_HOUR12,'PM','ระบบไฟ',$RPRQ_CREATEBY,$RPRQ_CREATEDATE);		
		$stmt9 = sqlsrv_query( $conn, $sql9, $params9);		

		// แจ้งเตือนไลน์--------------------------------------------------------------------------------
			$stmt_lastjob = "SELECT RPRQ_ID FROM vwREPAIRREQUEST WHERE RPRQ_CODE = ?";
			$params_lastjob = array($RPRQ_CODE);	
			$query_lastjob = sqlsrv_query( $conn, $stmt_lastjob, $params_lastjob);	
			$result_lastjob = sqlsrv_fetch_array($query_lastjob, SQLSRV_FETCH_ASSOC);
			$LAST_RPRQ_ID=$result_lastjob["RPRQ_ID"];	
			
			$stmt_linenoti = "SELECT * FROM SETTING WHERE ST_TYPE = '27' AND ST_STATUS = 'Y' AND ST_AREA = '$SESSION_AREA'";
			$query_linenoti = sqlsrv_query( $conn, $stmt_linenoti);	
			$no=0;
			while($result_linenoti = sqlsrv_fetch_array($query_linenoti)){	
				$no++;
				$ST_DETAIL=$result_linenoti["ST_DETAIL"];
				$TK_RQRP="$ST_DETAIL";  
				$NOTI_LINE1=" 🔴 แจ้งซ่อม PM ($RPRQ_MILEAGEFINISH)"."\n";
				$NOTI_LINE2="ID : ".$LAST_RPRQ_ID.""."\n";
				$NOTI_LINE3="ทะเบียน(หัว) : ".$RPRQ_REGISHEAD."\n";
				$NOTI_LINE4="ชื่อรถ(หัว) : ".$RPRQ_CARNAMEHEAD."\n";
				$NOTI_LINE5="ทะเบียน(หาง) : ".$RPRQ_REGISTAIL."\n";
				$NOTI_LINE6="ชื่อรถ(หาง) : ".$RPRQ_CARNAMETAIL."\n";
				// $NOTI_LINE7="ปัญหา : $RPC_DETAIL"."\n";
				$NOTI_LINE8="วันที่/เวลา แจ้งซ่อม : ".$datetimeRequest_in.""."\n";
				$NOTI_LINE9="วันที่/เวลา ต้องการใช้รถ : ".$datetimeRequest_out.""."\n";
				$NOTI_LINE10="ผู้แจ้ง : ".$RPRQ_REQUESTBY."";  
				// $MESSAGE_NOTI_LINE=$NOTI_LINE1.$NOTI_LINE2.$NOTI_LINE3.$NOTI_LINE4.$NOTI_LINE5.$NOTI_LINE6.$NOTI_LINE7.$NOTI_LINE8;	
				$MESSAGE_NOTI_LINE=$NOTI_LINE1.$NOTI_LINE2.$NOTI_LINE3.$NOTI_LINE4.$NOTI_LINE5.$NOTI_LINE6.$NOTI_LINE7.$NOTI_LINE8.$NOTI_LINE9.$NOTI_LINE10;	 
				$chOne = curl_init(); 
				curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
				curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
				curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
				curl_setopt( $chOne, CURLOPT_POST, 1); 
				curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$MESSAGE_NOTI_LINE); 
				$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$TK_RQRP.'', );
				curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
				curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
				$result = curl_exec( $chOne ); 					
				//Result error 
					// if(curl_error($chOne)){ 
					//     echo 'error:' . curl_error($chOne); 
					// }else{ 
					//     $result_ = json_decode($result, true); 
					//     echo "status : ".$result_['status']; echo "message : ". $result_['message'];
					// } 
				curl_close($chOne);   
				// แจ้งเตือนเทเลแกรม--------------------------------------------------------------------------------
					$channelId = '-4748971185';
					$botApiToken  = '7789413047:AAEXveIx2Ba2J86Wdoobub-VQs4RYIwQ0Yw'; 
					$urltelegram = "https://api.telegram.org/bot$botApiToken/sendMessage?chat_id=$channelId&text=".urlencode($MESSAGE_NOTI_LINE);
					$response = file_get_contents($urltelegram);
				// แจ้งเตือนเทเลแกรม--------------------------------------------------------------------------------
			}
		// แจ้งเตือนไลน์--------------------------------------------------------------------------------	

		// if( ($stmt || $stmt1 || $stmt2 || $stmt3 || $stmt4 || $stmt5) === false ) {
		if( ($stmt && $stmt1 && $stmt2 && $stmt3 && $stmt4 && $stmt6) === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			// echo "Record add successfully";
			// print "บันทึกข้อมูลเรียบร้อยแล้ว";	
			// echo "<script>";
			// echo "alert('บันทึกข้อมูลเรียบร้อยแล้ว');";
			// echo "window.history.back();";
			// echo "</script>";
			print "บันทึกข้อมูลเรียบร้อยแล้ว";	
		}	
	};
	######################################################################################################
	if($proc=="edit"){
		$RPRQ_CODE = $_POST["RPRQ_CODE"];

		$RPRQ_REGISHEAD = $_POST["VEHICLEREGISNUMBER1"];
		$RPRQ_REGISTAIL = $_POST["VEHICLEREGISNUMBER2"];
		$RPRQ_CARNAMEHEAD = $_POST["THAINAME1"];
		$RPRQ_CARNAMETAIL = $_POST["THAINAME2"];		
		$RPRQ_CARTYPE = $_POST["RPRQ_CARTYPE"];	
		$RPRQ_LINEOFWORK = $_POST["AFFCOMPANY"];
		$RPRQ_MILEAGELAST = $_POST["MAXMILEAGENUMBER"];
		// $RPRQ_MILEAGEFINISH = $result_rankpm["MLPM_MILEAGE"];
		$RPRQ_MILEAGEFINISH = $MLPM_MILEAGE;
		$RPRQ_RANKPMTYPE = $result_rankpm["MLPM_NAME"];
		$RPRQ_RANKKILOMETER = $_POST["MAXMILEAGENUMBER"];
			$RPRQ_PMLASTDATE = $_POST["RPRQ_PMLASTDATE"];
			$RPRQ_TIMEREPAIR = $_POST["RPRQ_TIMEREPAIR"];
			$RPRQ_SCHEDULEDDATE = $_POST["RPRQ_SCHEDULEDDATE"];

		$datetimeRequest_in = $_POST["datetimeRequest_in"];		
		$exin = explode(" ", $datetimeRequest_in);
		$exin1 = $exin[0];
		$exin2 = $exin[1]; 
		$RPRQ_REQUESTCARDATE = $exin1;
		$RPRQ_REQUESTCARTIME = $exin2;
		$datetimeRequest_out = $_POST["datetimeRequest_out"];		
		$exout = explode(" ", $datetimeRequest_out);
		$exout1 = $exout[0];
		$exout2 = $exout[1]; 
		$RPRQ_USECARDATE = $exout1;
		$RPRQ_USECARTIME = $exout2;
		
		$RPRQ_PRODUCTINCAR = $_POST["GOTC"];
		$RPRQ_NATUREREPAIR = $_POST["NTORNNW"];
		$RPRQ_TYPECUSTOMER = $_POST["TYPECUSTOMERS"];
		$RPRQ_COMPANYCASH = $_POST["AFFCOMPANY"];
		$RPRQ_REQUESTBY = $_POST["EMP_NAME_RQRP"];
		$RPRQ_CREATEDATE_REQUEST = $_POST["RPRQ_CREATEDATE_REQUEST"];
		$RPRQ_EDITBY = $_SESSION["AD_PERSONCODE"];
		$RPRQ_EDITDATE = date("Y-m-d H:i:s");

		$RPC_SUBJECT = $_POST["RPM_NATUREREPAIR"];  
		$RPC_DETAIL = 'PM-'.$RPRQ_MILEAGEFINISH;  			

	
		$sql = "UPDATE REPAIRREQUEST SET 	
				RPRQ_REGISHEAD = ?, 
				RPRQ_REGISTAIL = ?, 
				RPRQ_CARNAMEHEAD = ?, 
				RPRQ_CARNAMETAIL = ?, 
				RPRQ_CARTYPE = ?,
				RPRQ_LINEOFWORK = ?,
				RPRQ_MILEAGELAST = ?, 
				RPRQ_MILEAGEFINISH = ?, 
				RPRQ_RANKPMTYPE = ?, 
				RPRQ_RANKKILOMETER = ?, 
				RPRQ_PMLASTDATE = ?, 
				RPRQ_TIMEREPAIR = ?,
				RPRQ_SCHEDULEDDATE = ?, 
				RPRQ_REQUESTCARDATE = ?, 
				RPRQ_REQUESTCARTIME = ?, 
				RPRQ_USECARDATE = ?,
				RPRQ_USECARTIME = ?,
				RPRQ_PRODUCTINCAR = ?,
				RPRQ_NATUREREPAIR = ?,
				RPRQ_TYPECUSTOMER = ?,
				RPRQ_COMPANYCASH = ?,
				RPRQ_REQUESTBY = ?,
				RPRQ_CREATEDATE_REQUEST = ?,
				RPRQ_EDITBY = ?,
				RPRQ_EDITDATE = ?
				WHERE RPRQ_CODE = ? ";
		$params = array(
				$RPRQ_REGISHEAD, 
				$RPRQ_REGISTAIL, 
				$RPRQ_CARNAMEHEAD, 
				$RPRQ_CARNAMETAIL, 
				$RPRQ_CARTYPE,
				$RPRQ_LINEOFWORK,
				$RPRQ_MILEAGELAST, 
				$RPRQ_MILEAGEFINISH, 
				$RPRQ_RANKPMTYPE, 
				$RPRQ_RANKKILOMETER, 
				$RPRQ_PMLASTDATE, 
				$RPRQ_TIMEREPAIR,
				$RPRQ_SCHEDULEDDATE, 
				$RPRQ_REQUESTCARDATE,
				$RPRQ_REQUESTCARTIME,
				$RPRQ_USECARDATE,
				$RPRQ_USECARTIME,
				$RPRQ_PRODUCTINCAR,
				$RPRQ_NATUREREPAIR,
				$RPRQ_TYPECUSTOMER,
				$RPRQ_COMPANYCASH,
				$RPRQ_REQUESTBY,
				$RPRQ_CREATEDATE_REQUEST, 
				$RPRQ_EDITBY, 
				$RPRQ_EDITDATE, 
				$RPRQ_CODE
		);	
		$stmt = sqlsrv_query( $conn, $sql, $params);
		 					
		$sql1 = "UPDATE REPAIRCAUSE SET RPC_DETAIL = ? WHERE RPRQ_CODE = ?";
		$params1 = array($RPC_DETAIL,$RPC_IMAGES,$RPRQ_CODE);		
		$stmt1 = sqlsrv_query( $conn, $sql1, $params1);	

		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "แก้ไขข้อมูลเรียบร้อยแล้ว";	
		}		
	};
	######################################################################################################
	if($proc=="delete" && !empty($id)){
		
		$RPRQ_STATUS = 'D';
		$RPRQ_EDITBY = $_SESSION["AD_PERSONCODE"];
		$RPRQ_EDITDATE = date("Y-m-d H:i:s");

		$sql = "UPDATE REPAIRREQUEST SET RPRQ_STATUS = ?,RPRQ_EDITBY = ?,RPRQ_EDITDATE = ? WHERE RPRQ_CODE = ? ";
		$params = array($RPRQ_STATUS, $RPRQ_EDITBY, $RPRQ_EDITDATE, $id);	


		$stmt1 = sqlsrv_query( $conn, $sql, $params);
		if( ($stmt === false) ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "ลบข้อมูลเรียบร้อยแล้ว";	
		}		
	};	
	######################################################################################################
	if($_POST['target'] =="save_mileagemax"){
              
		$VHCRGNB = $_POST["vhcrgnb"];
		$MILEAGENUMBER = $_POST["mileage"];	
		$MILEAGETYPE = 'MILEAGEEND';
		$LINEOFWORK = 'OTHER';
		$ACTIVESTATUS = '1';
		$PROCESS_BY = $_SESSION["AD_PERSONCODE"];
		$PROCESS_DATE = date("Y-m-d H:i:s");		
		
		$check = "SELECT * FROM TEST_MILEAGE_PM WHERE VEHICLEREGISNUMBER = '$VHCRGNB'";
		$querycheck = sqlsrv_query( $conn, $check);
		$resultcheck = sqlsrv_fetch_array($querycheck, SQLSRV_FETCH_ASSOC);
		$chk1null = $resultcheck['VEHICLEREGISNUMBER'];

		if($chk1null != ""){    
			
			$sql = "UPDATE TEST_MILEAGE_PM SET 
					MILEAGENUMBER = ?,
					MODIFIEDBY = ?,
					MODIFIEDDATE = ?
					WHERE VEHICLEREGISNUMBER = ? ";
			$params = array($MILEAGENUMBER,$PROCESS_BY,$PROCESS_DATE,$VHCRGNB);
			$stmt = sqlsrv_query( $conn, $sql, $params);

			if( $stmt === false ) {
				die( print_r( sqlsrv_errors(), true));
			}else{
				print "อัพเดทเรียบร้อย";	
			}
		}else if($chk1null == ""){      

			$sql1 = "INSERT INTO TEST_MILEAGE_PM (VEHICLEREGISNUMBER,MILEAGENUMBER,MILEAGETYPE,LINEOFWORK,ACTIVESTATUS,CREATEBY,CREATEDATE) VALUES (?,?,?,?,?,?,?)";
			$params1 = array($VHCRGNB,$MILEAGENUMBER,$MILEAGETYPE,$LINEOFWORK,$ACTIVESTATUS,$PROCESS_BY,$PROCESS_DATE);
			$stmt1 = sqlsrv_query( $conn, $sql1, $params1);	
			if( $stmt1 === false ) {
				die( print_r( sqlsrv_errors(), true));
			}else{
				print "เพิ่มเรียบร้อย";	
			}
		}
	}
?>