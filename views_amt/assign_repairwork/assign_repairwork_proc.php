<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];	
	$target=$_POST["target"];	
	
	$SESSION_AREA = $_SESSION["AD_AREA"];

	######################################################################################################
	if($target=="repairhole"){
		$sql_hole = "SELECT * FROM REPAIR_HOLE WHERE RPH_REPAIRHOLE = '".$_POST["RPH_REPAIRHOLE"]."' AND RPH_AREA = '$SESSION_AREA'";
		$params_hole = array();
		$query_hole = sqlsrv_query($conn, $sql_hole, $params_hole);
		$result_hole = sqlsrv_fetch_array($query_hole, SQLSRV_FETCH_ASSOC); 

		$RPC_AREA = $result_hole["RPH_REPAIRHOLE"];
		$RPC_AREAID = $result_hole["RPH_ID"];
		$RPC_AREA_OTHER = $_POST["RPC_AREA_OTHER"];
		$RPC_ASSIGN_HOLE_BY = $_SESSION["AD_PERSONCODE"];
		$RPC_ASSIGN_HOLE_DATE = date("Y-m-d H:i:s");
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
	
		$sql = "UPDATE REPAIRCAUSE SET 
					RPC_AREA = ?,
					RPC_AREAID = ?,
					RPC_AREA_OTHER = ?,
					RPC_ASSIGN_HOLE_BY = ?,
					RPC_ASSIGN_HOLE_DATE = ?
					WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ?";
		$params = array($RPC_AREA, $RPC_AREAID, $RPC_AREA_OTHER, $RPC_ASSIGN_HOLE_BY, $RPC_ASSIGN_HOLE_DATE, $RPRQ_CODE, $RPC_SUBJECT);
	
		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "บันทึกข้อมูลช่องซ่อมเรียบร้อยแล้ว";	
		}		
	};
	######################################################################################################
	if($target=="repairdate"){
		$RPC_INCARDATETIME = $_POST["RPC_INCARDATETIME"];		
		$exin = explode(" ", $RPC_INCARDATETIME);
		$exin1 = $exin[0];
		$exin2 = $exin[1]; 
		$RPC_INCARDATE = $exin1;
		$RPC_INCARTIME = $exin2;
		$RPC_OUTCARDATETIME = $_POST["RPC_OUTCARDATETIME"];		
		$exout = explode(" ", $RPC_OUTCARDATETIME);
		$exout1 = $exout[0];
		$exout2 = $exout[1]; 
		$RPC_OUTCARDATE = $exout1;
		$RPC_OUTCARTIME = $exout2;

		// $RPC_INCARDATE = $_POST["RPC_INCARDATE"];
		// $RPC_INCARTIME = $_POST["RPC_INCARTIME"];
		// $RPC_OUTCARDATE = $_POST["RPC_OUTCARDATE"];
		// $RPC_OUTCARTIME = $_POST["RPC_OUTCARTIME"];
		$RPC_ASSIGN_TIME_BY = $_SESSION["AD_PERSONCODE"];
		$RPC_ASSIGN_TIME_DATE = date("Y-m-d H:i:s");
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
	
		$sql = "UPDATE REPAIRCAUSE SET 
					RPC_INCARDATE = ?,
					RPC_INCARTIME = ?,
					RPC_OUTCARDATE = ?,
					RPC_OUTCARTIME = ?,
					RPC_ASSIGN_TIME_BY = ?,
					RPC_ASSIGN_TIME_DATE = ?
					WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ?";
		$params = array($RPC_INCARDATE, $RPC_INCARTIME, $RPC_OUTCARDATE, $RPC_OUTCARTIME, $RPC_ASSIGN_TIME_BY, $RPC_ASSIGN_TIME_DATE, $RPRQ_CODE, $RPC_SUBJECT);

		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "บันทึกข้อมูลเวลาซ่อมเรียบร้อยแล้ว";	
		}		
	};
	######################################################################################################
	if($target=="repairdate_pm"){
		$RPC_INCARDATETIME = $_POST["RPC_INCARDATETIME"];		
		$exin = explode(" ", $RPC_INCARDATETIME);
		$exin1 = $exin[0];
		$exin2 = $exin[1]; 
		$RPC_INCARDATE = $exin1;
		$RPC_INCARTIME = $exin2;
		
		if($_POST["RPC_SUBJECT"]=="EL"){
			$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
			$RPC_SUBJECT_NAME = "PM-ระบบไฟ";
		}else if($_POST["RPC_SUBJECT"]=="TU"){
			$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
			$RPC_SUBJECT_NAME = "PM-ยาง-ช่วงล่าง";
		}else if($_POST["RPC_SUBJECT"]=="BD"){
			$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
			$RPC_SUBJECT_NAME = "PM-โครงสร้าง";
		}else if($_POST["RPC_SUBJECT"]=="EG"){
			$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
			$RPC_SUBJECT_NAME = "PM-เครื่องยนต์";
		}else if($_POST["RPC_SUBJECT"]=="AC"){
			$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
			$RPC_SUBJECT_NAME = "อุปกรณ์ประจำรถ";
		}

		if($_POST["RPRQ_RANKPMTYPE"]=="PMoRS-1"){
			$fildsfind = "ETM_PM1";
		}else if($_POST["RPRQ_RANKPMTYPE"]=="PMoRS-2"){
			$fildsfind = "ETM_PM2";
		}else if($_POST["RPRQ_RANKPMTYPE"]=="PMoRS-3"){
			$fildsfind = "ETM_PM3";
		}else if($_POST["RPRQ_RANKPMTYPE"]=="PMoRS-4"){
			$fildsfind = "ETM_PM4";
		}else if($_POST["RPRQ_RANKPMTYPE"]=="PMoRS-5"){
			$fildsfind = "ETM_PM5";
		}else if($_POST["RPRQ_RANKPMTYPE"]=="PMoRS-6"){
			$fildsfind = "ETM_PM6";
		}else if($_POST["RPRQ_RANKPMTYPE"]=="PMoRS-7"){
			$fildsfind = "ETM_PM7";
		}else if($_POST["RPRQ_RANKPMTYPE"]=="PMoRS-8"){
			$fildsfind = "ETM_PM8";
		}else if($_POST["RPRQ_RANKPMTYPE"]=="PMoRS-9"){
			$fildsfind = "ETM_PM9";
		}else if($_POST["RPRQ_RANKPMTYPE"]=="PMoRS-10"){
			$fildsfind = "ETM_PM10";
		}else if($_POST["RPRQ_RANKPMTYPE"]=="PMoRS-11"){
			$fildsfind = "ETM_PM11";
		}else if($_POST["RPRQ_RANKPMTYPE"]=="PMoRS-12"){
			$fildsfind = "ETM_PM12";
		}

		$VHCCT_ID = $_POST["VHCCT_ID"];
		$sql_selhour = "SELECT TOP 1 ETM_NAME,$fildsfind,VHCCT_ID,ETM_AREA,ETM_GROUP,ETM_TYPE
		FROM dbo.ESTIMATE WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '6' AND ETM_GROUP = '$RPC_SUBJECT_NAME' ORDER BY ETM_ID DESC";
		$params_selhour = array();
		$query_selhour = sqlsrv_query($conn, $sql_selhour, $params_selhour);
		$result_selhour = sqlsrv_fetch_array($query_selhour, SQLSRV_FETCH_ASSOC); 
		$ETM_PM=$result_selhour[$fildsfind];
		$CALETM_PM=$ETM_PM*60;
		
		// $RPC_INCARDATETIME="24/11/2023 12:00"; ตัวอย่างวันที่
		$POSTDATE=str_replace('/', '-', $RPC_INCARDATETIME);
		$DATECONVERT = date('Y-m-d H:i', strtotime($POSTDATE));
			
		$ROUND_CALETM_PM=round($CALETM_PM);
		$TIMECON = "+".$ROUND_CALETM_PM." minutes";
		$DATETIMEEND = date ("Y-m-d H:i", strtotime($TIMECON, strtotime($DATECONVERT)));
		
		$CONDATEFORMAT = strtotime($DATETIMEEND);		
		$RPC_OUTCARDATETIME = date("d/m/Y H:i", $CONDATEFORMAT);

		// echo 'เวลาจากฐานข้อมูล = '.$ETM_PM;
		// echo '<br>';
		// echo 'แปลงเวลาจากฐานข้อมูลเป็นนาที = '.$CALETM_PM;
		// echo '<br>';
		// echo 'เวลาหลังปัดเศษ = '.$ROUND_CALETM_PM;
		// echo '<br>';
		// echo 'วันที่นำรถเข้าซ่อม = '.$DATECONVERT;
		// echo '<br>';
		// echo 'วันทีซ่อมเสร็จ = '.$DATETIMEEND;
		// echo '<br>';
		// echo 'แปลงวันทีซ่อมเสร็จ = '.$RPC_OUTCARDATETIME;
		// exit();

		// $RPC_OUTCARDATETIME = $_POST["RPC_OUTCARDATETIME"];		
		$exout = explode(" ", $RPC_OUTCARDATETIME);
		$exout1 = $exout[0];
		$exout2 = $exout[1]; 
		$RPC_OUTCARDATE = $exout1;
		$RPC_OUTCARTIME = $exout2;
		
		$RPC_ASSIGN_TIME_BY = $_SESSION["AD_PERSONCODE"];
		$RPC_ASSIGN_TIME_DATE = date("Y-m-d H:i:s");
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
	
		$sql = "UPDATE REPAIRCAUSE SET 
					RPC_INCARDATE = ?,
					RPC_INCARTIME = ?,
					RPC_OUTCARDATE = ?,
					RPC_OUTCARTIME = ?,
					RPC_ASSIGN_TIME_BY = ?,
					RPC_ASSIGN_TIME_DATE = ?
					WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ?";
		$params = array($RPC_INCARDATE, $RPC_INCARTIME, $RPC_OUTCARDATE, $RPC_OUTCARTIME, $RPC_ASSIGN_TIME_BY, $RPC_ASSIGN_TIME_DATE, $RPRQ_CODE, $RPC_SUBJECT);

		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "บันทึกข้อมูลเวลาซ่อมเรียบร้อยแล้ว";	
		}		
	};
	######################################################################################################
	if($target=="repairdate_pmout"){
		$RPC_OUTCARDATETIME = $_POST["RPC_OUTCARDATETIME"];		
		$exin = explode(" ", $RPC_OUTCARDATETIME);
		$exin1 = $exin[0];
		$exin2 = $exin[1]; 
		$RPC_OUTCARDATE = $exin1;
		$RPC_OUTCARTIME = $exin2;

		// echo 'เวลาจากฐานข้อมูล = '.$ETM_PM;
		// echo '<br>';
		// echo 'แปลงเวลาจากฐานข้อมูลเป็นนาที = '.$CALETM_PM;
		// echo '<br>';
		// echo 'เวลาหลังปัดเศษ = '.$ROUND_CALETM_PM;
		// echo '<br>';
		// echo 'วันที่นำรถเข้าซ่อม = '.$DATECONVERT;
		// echo '<br>';
		// echo 'วันทีซ่อมเสร็จ = '.$DATETIMEEND;
		// echo '<br>';
		// echo 'แปลงวันทีซ่อมเสร็จ = '.$RPC_OUTCARDATETIME;
		// exit();
		
		$RPC_ASSIGN_TIME_BY = $_SESSION["AD_PERSONCODE"];
		$RPC_ASSIGN_TIME_DATE = date("Y-m-d H:i:s");
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
	
		$sql = "UPDATE REPAIRCAUSE SET 
					RPC_OUTCARDATE = ?,
					RPC_OUTCARTIME = ?,
					RPC_ASSIGN_TIME_BY = ?,
					RPC_ASSIGN_TIME_DATE = ?
					WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ?";
		$params = array($RPC_OUTCARDATE, $RPC_OUTCARTIME, $RPC_ASSIGN_TIME_BY, $RPC_ASSIGN_TIME_DATE, $RPRQ_CODE, $RPC_SUBJECT);

		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "บันทึกข้อมูลเวลาซ่อมเรียบร้อยแล้ว";	
		}		
	};
	######################################################################################################
	if($target=="repairman"){
		$chkdata=$_POST["chkdata"];	

		$RPME_CODE = $_POST["RPM_PERSONCODE"];  	
		$RPRQ_CODE = $_POST["RPRQ_CODE"];  
		$RPC_SUBJECT = $_POST["RPC_SUBJECT"];  

		$PROCESS_BY = $_SESSION["AD_PERSONCODE"];
		$PROCESS_DATE = date("Y-m-d H:i:s");

		if($chkdata=='add'){		
			$RPME_STATUS = '1';

			$sql_repairman = "SELECT * FROM vwREPAIRMAN WHERE PersonCode = '$RPME_CODE'";
			$params_repairman = array();
			$query_repairman = sqlsrv_query($conn, $sql_repairman, $params_repairman);
			$result_repairman = sqlsrv_fetch_array($query_repairman, SQLSRV_FETCH_ASSOC); 
			$RPME_NAME = $result_repairman["nameT"];  	
			
			$sql_repairdetail = "SELECT RPC_DETAIL FROM vwASSIGN WHERE RPRQ_CODE = '$RPRQ_CODE'";
			$params_repairdetail = array();
			$query_repairdetail = sqlsrv_query($conn, $sql_repairdetail, $params_repairdetail);
			$result_repairdetail = sqlsrv_fetch_array($query_repairdetail, SQLSRV_FETCH_ASSOC); 
			$RPME_DETAIL = $result_repairdetail["RPC_DETAIL"];  	
			
			$sql1 = "INSERT INTO REPAIRMANEMP (RPRQ_CODE,RPC_SUBJECT,RPME_CODE,RPME_NAME,RPME_DETAIL,RPME_STATUS,RPME_CREATEBY,RPME_CREATEDATE) VALUES (?,?,?,?,?,?,?,?)";
			$params1 = array($RPRQ_CODE,$RPC_SUBJECT,$RPME_CODE,$RPME_NAME,$RPME_DETAIL,$RPME_STATUS,$PROCESS_BY,$PROCESS_DATE);
			$stmt1 = sqlsrv_query( $conn, $sql1, $params1);	

			if( $stmt1 === false ) {
				die( print_r( sqlsrv_errors(), true));
			}else{
				print "เพิ่มช่างเรียบร้อย";	
			}
		}
		
		if($chkdata=='delete'){	
			
			$sql1 = "DELETE FROM REPAIRMANEMP WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ? AND RPME_CODE = ?";
			$params1 = array($RPRQ_CODE,$RPC_SUBJECT,$RPME_CODE);	
			$stmt1 = sqlsrv_query( $conn, $sql1, $params1);

			if( $stmt1 === false ) {
				die( print_r( sqlsrv_errors(), true));
			}else{
				print "ลบช่างเรียบร้อย";	
			}
		}

		if($RPC_SUBJECT=="EL"){
		  $RPC_SUBJECT_NAME = "ระบบไฟ";
		}else if($RPC_SUBJECT=="TU"){
		  $RPC_SUBJECT_NAME = "ยาง-ช่วงล่าง";
		}else if($RPC_SUBJECT=="BD"){
		  $RPC_SUBJECT_NAME = "โครงสร้าง";
		}else if($RPC_SUBJECT=="EG"){
		  $RPC_SUBJECT_NAME = "เครื่องยนต์";
		}

		$sql_repairman = "SELECT COUNT(RPME_CODE) AS COUNTREPAIRMAN FROM REPAIRMANEMP WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$RPC_SUBJECT'";
		$params_repairman = array();
		$query_repairman = sqlsrv_query($conn, $sql_repairman, $params_repairman);
		$result_repairman = sqlsrv_fetch_array($query_repairman, SQLSRV_FETCH_ASSOC);  
		$COUNTREPAIRMAN=$result_repairman['COUNTREPAIRMAN'];
		
		$sql_uprpm = "UPDATE REPAIRESTIMATE SET RPETM_REPAIRMAN=?,RPETM_PROCESSBY=?,RPETM_PROCESSDATE=? WHERE RPRQ_CODE = ? AND RPETM_NATURE = ?";
		$params_uprpm = array($COUNTREPAIRMAN,$PROCESS_BY,$PROCESS_DATE,$RPRQ_CODE,$RPC_SUBJECT_NAME);		
		$stmt_uprpm = sqlsrv_query( $conn, $sql_uprpm, $params_uprpm);

	};
	######################################################################################################
	if($target=="saveestimate"){

		$RPRQ_CODE = $_POST["RPRQ_CODE"];  
		$THISVAL = $_POST["THISVAL"];  
		$ETMFIELD = $_POST["ETMFIELD"];  
		if($ETMFIELD=='2'){
			$fildsfindETM='RPETM_SPARESPART';
		}else if($ETMFIELD=='3'){
			$fildsfindETM='RPETM_WAGE';
		}else if($ETMFIELD=='4'){
			$fildsfindETM='RPETM_HOUR';
		}
		$WORKTYPE = $_POST["WORKTYPE"]; 
		$RPC_SUBJECT = $_POST["RPC_SUBJECT"]; 
		$PROCESS_BY = $_SESSION["AD_PERSONCODE"];
		$PROCESS_DATE = date("Y-m-d H:i:s");

		if($RPC_SUBJECT=="EL"){
		  $RPC_SUBJECT_NAME = "ระบบไฟ";
		}else if($RPC_SUBJECT=="TU"){
		  $RPC_SUBJECT_NAME = "ยาง-ช่วงล่าง";
		}else if($RPC_SUBJECT=="BD"){
		  $RPC_SUBJECT_NAME = "โครงสร้าง";
		}else if($RPC_SUBJECT=="EG"){
		  $RPC_SUBJECT_NAME = "เครื่องยนต์";
		}

		$sql_uprpm = "UPDATE REPAIRESTIMATE SET $fildsfindETM=?,RPETM_PROCESSBY=?,RPETM_PROCESSDATE=? WHERE RPRQ_CODE = ? AND RPETM_NATURE = ? AND RPETM_TYPEREPAIR = ?";
		$params_uprpm = array($THISVAL,$PROCESS_BY,$PROCESS_DATE,$RPRQ_CODE,$RPC_SUBJECT_NAME,$WORKTYPE);		
		$stmt_uprpm = sqlsrv_query( $conn, $sql_uprpm, $params_uprpm);

	};
	######################################################################################################
	if($target=="save_rpm_drive"){
		$select_repairman_drive = $_POST["select_repairman_drive"];  	
		$RPRQ_CODE = $_POST["rprq_code"];  
		$RPMD_ZONE = $_POST["zone"];  
		$PROCESS_BY = $_SESSION["AD_PERSONCODE"];
		$PROCESS_DATE = date("Y-m-d H:i:s");	
		
		if(isset($_POST["zone"])){
			$sql_repairman_drive = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPMD_ZONE = '$RPMD_ZONE'";
		}else{
			$sql_repairman_drive = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE = '$RPRQ_CODE'";
		}
		$params_repairman_drive = array();
		$query_repairman_drive = sqlsrv_query($conn, $sql_repairman_drive, $params_repairman_drive);
		$result_repairman_drive = sqlsrv_fetch_array($query_repairman_drive, SQLSRV_FETCH_ASSOC); 
		$RPRQ_CODE_CHK = $result_repairman_drive["RPRQ_CODE"]; 

		if($RPRQ_CODE_CHK==''){		
			$sql_repairman = "SELECT * FROM vwCARLICENCE WHERE PersonCode = '$select_repairman_drive'";
			$params_repairman = array();
			$query_repairman = sqlsrv_query($conn, $sql_repairman, $params_repairman);
			$result_repairman = sqlsrv_fetch_array($query_repairman, SQLSRV_FETCH_ASSOC);
			$RPMD_PERSONCODE = $result_repairman["PersonCode"];
			$RPMD_NAME = $result_repairman["nameT"];
			$RPMD_CARLICENCE = $result_repairman["CarLicenceID"];
			
			$sql1 = "INSERT INTO REPAIRMANDRIVE (RPRQ_CODE,RPMD_PERSONCODE,RPMD_NAME,RPMD_CARLICENCE,RPMD_ZONE,RPMD_PROCESSBY,RPMD_PROCESSDATE) VALUES (?,?,?,?,?,?,?)";
			$params1 = array($RPRQ_CODE,$RPMD_PERSONCODE,$RPMD_NAME,$RPMD_CARLICENCE,$RPMD_ZONE,$PROCESS_BY,$PROCESS_DATE);
			$stmt1 = sqlsrv_query( $conn, $sql1, $params1);	

			if( $stmt1 === false ) {
				die( print_r( sqlsrv_errors(), true));
			}else{
				print "เพิ่มช่างผู้ขับรถเรียบร้อย";	
			}
		}else if($RPRQ_CODE_CHK!=''){	
			$sql_repairman = "SELECT * FROM vwCARLICENCE WHERE PersonCode = '$select_repairman_drive'";
			$params_repairman = array();
			$query_repairman = sqlsrv_query($conn, $sql_repairman, $params_repairman);
			$result_repairman = sqlsrv_fetch_array($query_repairman, SQLSRV_FETCH_ASSOC); 
			$RPMD_PERSONCODE = $result_repairman["PersonCode"]; 
			$RPMD_NAME = $result_repairman["nameT"];  		
			$RPMD_CARLICENCE = $result_repairman["CarLicenceID"]; 
			
			if(isset($_POST["zone"])){			
				$sql = "UPDATE REPAIRMANDRIVE SET RPMD_PERSONCODE = ?,RPMD_NAME = ?,RPMD_CARLICENCE = ?,RPMD_PROCESSBY = ?,RPMD_PROCESSDATE = ?
						WHERE RPRQ_CODE = ? AND RPMD_ZONE = ?";
				$params = array($RPMD_PERSONCODE, $RPMD_NAME, $RPMD_CARLICENCE, $PROCESS_BY, $PROCESS_DATE, $RPRQ_CODE, $RPMD_ZONE);
			}else{			
				$sql = "UPDATE REPAIRMANDRIVE SET RPMD_PERSONCODE = ?,RPMD_NAME = ?,RPMD_CARLICENCE = ?, RPMD_ZONE = ?,RPMD_PROCESSBY = ?,RPMD_PROCESSDATE = ?
						WHERE RPRQ_CODE = ?";
				$params = array($RPMD_PERSONCODE, $RPMD_NAME, $RPMD_CARLICENCE, $RPMD_ZONE, $PROCESS_BY, $PROCESS_DATE, $RPRQ_CODE);
			}

			$stmt = sqlsrv_query( $conn, $sql, $params);
			if( $stmt === false ) {
				die( print_r( sqlsrv_errors(), true));
			}else{
				print "อัพเดทช่างผู้ขับรถเรียบร้อย";	
			}
		}
	};
	######################################################################################################
	if($target=="update_repair_status"){
		$RPRQ_STATUSREQUEST = 'รอคิวซ่อม';  		
		$RPRQ_CODE = $_POST["RPRQ_CODE"];  	
		$RPRQ_WORKTYPE = $_POST["RPRQ_WORKTYPE"];  		
		$RPRQ_ASSIGN_BY = $_SESSION["AD_PERSONCODE"];
		$RPRQ_ASSIGN_DATE = date("Y-m-d H:i:s");
		
		$sql = "UPDATE REPAIRREQUEST SET RPRQ_STATUSREQUEST = ?, RPRQ_ASSIGN_BY = ?, RPRQ_ASSIGN_DATE = ? WHERE RPRQ_CODE = ?";
		$params = array($RPRQ_STATUSREQUEST, $RPRQ_ASSIGN_BY, $RPRQ_ASSIGN_DATE, $RPRQ_CODE);		
		$stmt = sqlsrv_query( $conn, $sql, $params);

		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "อัพเดทเรียบร้อย";				

			// แจ้งเตือนไลน์--------------------------------------------------------------------------------
				if($RPRQ_WORKTYPE=='BM'){
					$stmt_select_job = "SELECT * FROM vwLINEASSIGN_BM WHERE RPRQ_CODE = ?";
					$params_select_job = array($RPRQ_CODE);	
					$query_select_job = sqlsrv_query( $conn, $stmt_select_job, $params_select_job);	
					$result_select_job = sqlsrv_fetch_array($query_select_job, SQLSRV_FETCH_ASSOC);
						$RPC_SUBJECT_CON=$result_select_job["RPC_SUBJECT_CON"];
						$RPC_INCARDATE=$result_select_job["RPC_INCARDATE"];	
						$RPC_INCARTIME=$result_select_job["RPC_INCARTIME"];
						$RPC_AREA=$result_select_job["RPC_AREA"];		
						$RPME_NAME=$result_select_job["RPME_NAME"];								
				}else if($RPRQ_WORKTYPE=='PM'){
					$stmt_select_job = "SELECT * FROM vwLINEASSIGN_PM WHERE RPRQ_CODE = ? ";
					$params_select_job = array($RPRQ_CODE);	
					$query_select_job = sqlsrv_query( $conn, $stmt_select_job, $params_select_job);	
					$result_select_job = sqlsrv_fetch_array($query_select_job, SQLSRV_FETCH_ASSOC);

					$stmt_select_jobEG = "SELECT RPC_SUBJECT_CON,RPC_INCARDATE,RPC_INCARTIME,RPC_AREA,RPME_NAME FROM vwLINEASSIGN_PM WHERE RPRQ_CODE = ? AND RPC_SUBJECT_CON = 'เครื่องยนต์'";
					$params_select_jobEG = array($RPRQ_CODE);	
					$query_select_jobEG = sqlsrv_query( $conn, $stmt_select_jobEG, $params_select_jobEG);	
					$result_select_jobEG = sqlsrv_fetch_array($query_select_jobEG, SQLSRV_FETCH_ASSOC);
						$RPC_SUBJECT_CON_EG=$result_select_jobEG["RPC_SUBJECT_CON"];
						$RPC_INCARDATE_EG=$result_select_jobEG["RPC_INCARDATE"];	
						$RPC_INCARTIME_EG=$result_select_jobEG["RPC_INCARTIME"];
						$RPC_AREA_EG=$result_select_jobEG["RPC_AREA"];		
						$RPME_NAME_EG=$result_select_jobEG["RPME_NAME"];	
					
					$stmt_select_jobBD = "SELECT RPC_SUBJECT_CON,RPC_INCARDATE,RPC_INCARTIME,RPC_AREA,RPME_NAME FROM vwLINEASSIGN_PM WHERE RPRQ_CODE = ? AND RPC_SUBJECT_CON = 'โครงสร้าง'";
					$params_select_jobBD = array($RPRQ_CODE);	
					$query_select_jobBD = sqlsrv_query( $conn, $stmt_select_jobBD, $params_select_jobBD);	
					$result_select_jobBD = sqlsrv_fetch_array($query_select_jobBD, SQLSRV_FETCH_ASSOC);
						$RPC_SUBJECT_CON_BD=$result_select_jobBD["RPC_SUBJECT_CON"];
						$RPC_INCARDATE_BD=$result_select_jobBD["RPC_INCARDATE"];	
						$RPC_INCARTIME_BD=$result_select_jobBD["RPC_INCARTIME"];
						$RPC_AREA_BD=$result_select_jobBD["RPC_AREA"];		
						$RPME_NAME_BD=$result_select_jobBD["RPME_NAME"];	
					
					$stmt_select_jobTU = "SELECT RPC_SUBJECT_CON,RPC_INCARDATE,RPC_INCARTIME,RPC_AREA,RPME_NAME FROM vwLINEASSIGN_PM WHERE RPRQ_CODE = ? AND RPC_SUBJECT_CON = 'ยาง ช่วงล่าง'";
					$params_select_jobTU = array($RPRQ_CODE);	
					$query_select_jobTU = sqlsrv_query( $conn, $stmt_select_jobTU, $params_select_jobTU);	
					$result_select_jobTU = sqlsrv_fetch_array($query_select_jobTU, SQLSRV_FETCH_ASSOC);
						$RPC_SUBJECT_CON_TU=$result_select_jobTU["RPC_SUBJECT_CON"];
						$RPC_INCARDATE_TU=$result_select_jobTU["RPC_INCARDATE"];	
						$RPC_INCARTIME_TU=$result_select_jobTU["RPC_INCARTIME"];
						$RPC_AREA_TU=$result_select_jobTU["RPC_AREA"];		
						$RPME_NAME_TU=$result_select_jobTU["RPME_NAME"];	
					
					$stmt_select_jobEL = "SELECT RPC_SUBJECT_CON,RPC_INCARDATE,RPC_INCARTIME,RPC_AREA,RPME_NAME FROM vwLINEASSIGN_PM WHERE RPRQ_CODE = ? AND RPC_SUBJECT_CON = 'ระบบไฟ'";
					$params_select_jobEL = array($RPRQ_CODE);	
					$query_select_jobEL = sqlsrv_query( $conn, $stmt_select_jobEL, $params_select_jobEL);	
					$result_select_jobEL = sqlsrv_fetch_array($query_select_jobEL, SQLSRV_FETCH_ASSOC);
						$RPC_SUBJECT_CON_EL=$result_select_jobEL["RPC_SUBJECT_CON"];
						$RPC_INCARDATE_EL=$result_select_jobEL["RPC_INCARDATE"];	
						$RPC_INCARTIME_EL=$result_select_jobEL["RPC_INCARTIME"];
						$RPC_AREA_EL=$result_select_jobEL["RPC_AREA"];		
						$RPME_NAME_EL=$result_select_jobEL["RPME_NAME"];	
				}
				$LAST_RPRQ_ID=$result_select_job["RPRQ_ID"];	
				$LAST_RPRQ_CODE=$result_select_job["RPRQ_CODE"];	
				$LAST_RPRQ_WORKTYPE=$result_select_job["RPRQ_WORKTYPE"];
				$RPRQ_NUMBER=$result_select_job["RPRQ_NUMBER"];
				$RPRQ_REGISHEAD=$result_select_job["RPRQ_REGISHEAD"];
				$RPRQ_REGISTAIL=$result_select_job["RPRQ_REGISTAIL"];
				$RPRQ_CARNAMEHEAD = $result_select_job["RPRQ_CARNAMEHEAD"];
				$RPRQ_CARNAMETAIL = $result_select_job["RPRQ_CARNAMETAIL"];
				$RPC_DETAIL=$result_select_job["RPC_DETAIL"];
				$RPRQ_RANKPMTYPE=$result_select_job["RPRQ_RANKPMTYPE"];	
				$RPRQ_MILEAGEFINISH=$result_select_job["RPRQ_MILEAGEFINISH"];	
				$LAST_RPRQ_ASSIGN_BY=$result_select_job["RPRQ_ASSIGN_BY"];
				
				$stmt_emp_approve = "SELECT * FROM vwEMPLOYEE WHERE PersonCode = ?";
				$params_emp_approve = array($LAST_RPRQ_ASSIGN_BY);	
				$query_emp_approve = sqlsrv_query( $conn, $stmt_emp_approve, $params_emp_approve);	
				$result_emp_approve = sqlsrv_fetch_array($query_emp_approve, SQLSRV_FETCH_ASSOC);			
					$ASSIGN_NAME=$result_emp_approve["nameT"];
				
				$stmt_linenoti = "SELECT * FROM SETTING WHERE ST_TYPE = '29' AND ST_STATUS = 'Y' AND ST_AREA = '$SESSION_AREA'";
				$query_linenoti = sqlsrv_query( $conn, $stmt_linenoti);	
				$no=0;
				while($result_linenoti = sqlsrv_fetch_array($query_linenoti)){	
					$no++;
					$ST_DETAIL=$result_linenoti["ST_DETAIL"];
					$TK_RQRP="$ST_DETAIL";  

						if($LAST_RPRQ_WORKTYPE=='BM'){						
							$NOTI_LINE1=" 🟡 แจ้งการจ่ายงาน BM ($RPC_SUBJECT_CON)"."\n";
							$NOTI_LINE2="ID : ".$LAST_RPRQ_ID.", ลำดับ : ".$RPRQ_NUMBER.""."\n";							
							$NOTI_LINE3="ทะเบียน(หัว) : ".$RPRQ_REGISHEAD."\n";
							$NOTI_LINE4="ชื่อรถ(หัว) : ".$RPRQ_CARNAMEHEAD."\n";
							$NOTI_LINE5="ทะเบียน(หาง) : ".$RPRQ_REGISTAIL."\n";
							$NOTI_LINE6="ชื่อรถ(หาง) : ".$RPRQ_CARNAMETAIL."\n";
							$NOTI_LINE7="ปัญหา : $RPC_DETAIL"."\n";
							$NOTI_LINE8="$RPC_SUBJECT_CON : $RPC_INCARDATE $RPC_INCARTIME $RPC_AREA"."\n";                
							$NOTI_LINE9="ช่างผู้รับผิดชอบ : ".$RPME_NAME.""."\n";
							$NOTI_LINE10="--------------------------------"."\n";               
							$NOTI_LINE11="ผู้จ่ายงาน : ".$ASSIGN_NAME."";   
							$MESSAGE_NOTI_LINE=$NOTI_LINE1.$NOTI_LINE2.$NOTI_LINE3.$NOTI_LINE4.$NOTI_LINE5.$NOTI_LINE6.$NOTI_LINE7.$NOTI_LINE8.$NOTI_LINE9.$NOTI_LINE10.$NOTI_LINE11;

						}else if($LAST_RPRQ_WORKTYPE=='PM'){
							$NOTI_LINE1=" 🟡 แจ้งการจ่ายงาน PM ($RPRQ_RANKPMTYPE)"."\n";		
							$NOTI_LINE2="ID : ".$LAST_RPRQ_ID.", ถึงระยะเลขไมล์ : ".$RPRQ_MILEAGEFINISH.""."\n";					
							$NOTI_LINE3="ทะเบียน(หัว) : ".$RPRQ_REGISHEAD."\n";
							$NOTI_LINE4="ชื่อรถ(หัว) : ".$RPRQ_CARNAMEHEAD."\n";
							$NOTI_LINE5="ทะเบียน(หาง) : ".$RPRQ_REGISTAIL."\n";
							$NOTI_LINE6="ชื่อรถ(หาง) : ".$RPRQ_CARNAMETAIL."\n";
							$NOTI_LINE7="--------------------------------"."\n";							
							$NOTI_LINE8="$RPC_SUBJECT_CON_EG : $RPC_INCARDATE_EG $RPC_INCARTIME_EG $RPC_AREA_EG"."\n";    
							$NOTI_LINE9="ช่างผู้รับผิดชอบ : ".$RPME_NAME_EG.""."\n";
							$NOTI_LINE10="--------------------------------"."\n";
							$NOTI_LINE11="$RPC_SUBJECT_CON_BD : $RPC_INCARDATE_BD $RPC_INCARTIME_BD $RPC_AREA_BD"."\n";   
							$NOTI_LINE12="ช่างผู้รับผิดชอบ : ".$RPME_NAME_BD.""."\n";
							$NOTI_LINE13="--------------------------------"."\n";
							$NOTI_LINE14="$RPC_SUBJECT_CON_TU : $RPC_INCARDATE_TU $RPC_INCARTIME_TU $RPC_AREA_TU"."\n";   
							$NOTI_LINE15="ช่างผู้รับผิดชอบ : ".$RPME_NAME_TU.""."\n";
							$NOTI_LINE16="--------------------------------"."\n";
							$NOTI_LINE17="$RPC_SUBJECT_CON_EL : $RPC_INCARDATE_EL $RPC_INCARTIME_EL $RPC_AREA_EL"."\n";   
							$NOTI_LINE18="ช่างผู้รับผิดชอบ : ".$RPME_NAME_EL.""."\n";
							$NOTI_LINE19="--------------------------------"."\n";               
							$NOTI_LINE20="ผู้จ่ายงาน : ".$ASSIGN_NAME."";  
							$MESSAGE_NOTI_LINE=$NOTI_LINE1.$NOTI_LINE2.$NOTI_LINE3.$NOTI_LINE4.$NOTI_LINE5.$NOTI_LINE6.$NOTI_LINE7.$NOTI_LINE8.$NOTI_LINE9.$NOTI_LINE10.$NOTI_LINE11.$NOTI_LINE12.$NOTI_LINE13.$NOTI_LINE14.$NOTI_LINE15.$NOTI_LINE16.$NOTI_LINE17.$NOTI_LINE18.$NOTI_LINE19.$NOTI_LINE20;
						}
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
				}
			// แจ้งเตือนไลน์--------------------------------------------------------------------------------
			// แจ้งเตือนเทเลแกรม-OPEN-------------------------------------------------------------------------------
				$stmt_telegram = "SELECT * FROM SETTING WHERE ST_TYPE = '35' AND ST_STATUS = 'Y' AND ST_AREA = '$SESSION_AREA'";
				$query_telegram = sqlsrv_query( $conn, $stmt_telegram);	
				$no=0;
				while($result_telegram = sqlsrv_fetch_array($query_telegram)){	
					$no++;
					$ST_DETAIL_TELEGRAM=$result_telegram["ST_DETAIL"];
					$channelId=$ST_DETAIL_TELEGRAM;  

					if($LAST_RPRQ_WORKTYPE=='BM'){						
						$NOTI_LINE1=" 🟡 แจ้งการจ่ายงาน BM ($RPC_SUBJECT_CON)"."\n";
						$NOTI_LINE2="ID : ".$LAST_RPRQ_ID.", ลำดับ : ".$RPRQ_NUMBER.""."\n";							
						$NOTI_LINE3="ทะเบียน(หัว) : ".$RPRQ_REGISHEAD."\n";
						$NOTI_LINE4="ชื่อรถ(หัว) : ".$RPRQ_CARNAMEHEAD."\n";
						$NOTI_LINE5="ทะเบียน(หาง) : ".$RPRQ_REGISTAIL."\n";
						$NOTI_LINE6="ชื่อรถ(หาง) : ".$RPRQ_CARNAMETAIL."\n";
						$NOTI_LINE7="ปัญหา : $RPC_DETAIL"."\n";
						$NOTI_LINE8="$RPC_SUBJECT_CON : $RPC_INCARDATE $RPC_INCARTIME $RPC_AREA"."\n";                
						$NOTI_LINE9="ช่างผู้รับผิดชอบ : ".$RPME_NAME.""."\n";
						$NOTI_LINE10="--------------------------------"."\n";               
						$NOTI_LINE11="ผู้จ่ายงาน : ".$ASSIGN_NAME."";   
						$MESSAGE_NOTI_LINE=$NOTI_LINE1.$NOTI_LINE2.$NOTI_LINE3.$NOTI_LINE4.$NOTI_LINE5.$NOTI_LINE6.$NOTI_LINE7.$NOTI_LINE8.$NOTI_LINE9.$NOTI_LINE10.$NOTI_LINE11;

					}else if($LAST_RPRQ_WORKTYPE=='PM'){
						$NOTI_LINE1=" 🟡 แจ้งการจ่ายงาน PM ($RPRQ_RANKPMTYPE)"."\n";		
						$NOTI_LINE2="ID : ".$LAST_RPRQ_ID.", ถึงระยะเลขไมล์ : ".$RPRQ_MILEAGEFINISH.""."\n";					
						$NOTI_LINE3="ทะเบียน(หัว) : ".$RPRQ_REGISHEAD."\n";
						$NOTI_LINE4="ชื่อรถ(หัว) : ".$RPRQ_CARNAMEHEAD."\n";
						$NOTI_LINE5="ทะเบียน(หาง) : ".$RPRQ_REGISTAIL."\n";
						$NOTI_LINE6="ชื่อรถ(หาง) : ".$RPRQ_CARNAMETAIL."\n";
						$NOTI_LINE7="--------------------------------"."\n";							
						$NOTI_LINE8="$RPC_SUBJECT_CON_EG : $RPC_INCARDATE_EG $RPC_INCARTIME_EG $RPC_AREA_EG"."\n";    
						$NOTI_LINE9="ช่างผู้รับผิดชอบ : ".$RPME_NAME_EG.""."\n";
						$NOTI_LINE10="--------------------------------"."\n";
						$NOTI_LINE11="$RPC_SUBJECT_CON_BD : $RPC_INCARDATE_BD $RPC_INCARTIME_BD $RPC_AREA_BD"."\n";   
						$NOTI_LINE12="ช่างผู้รับผิดชอบ : ".$RPME_NAME_BD.""."\n";
						$NOTI_LINE13="--------------------------------"."\n";
						$NOTI_LINE14="$RPC_SUBJECT_CON_TU : $RPC_INCARDATE_TU $RPC_INCARTIME_TU $RPC_AREA_TU"."\n";   
						$NOTI_LINE15="ช่างผู้รับผิดชอบ : ".$RPME_NAME_TU.""."\n";
						$NOTI_LINE16="--------------------------------"."\n";
						$NOTI_LINE17="$RPC_SUBJECT_CON_EL : $RPC_INCARDATE_EL $RPC_INCARTIME_EL $RPC_AREA_EL"."\n";   
						$NOTI_LINE18="ช่างผู้รับผิดชอบ : ".$RPME_NAME_EL.""."\n";
						$NOTI_LINE19="--------------------------------"."\n";               
						$NOTI_LINE20="ผู้จ่ายงาน : ".$ASSIGN_NAME."";  
						$MESSAGE_NOTI_LINE=$NOTI_LINE1.$NOTI_LINE2.$NOTI_LINE3.$NOTI_LINE4.$NOTI_LINE5.$NOTI_LINE6.$NOTI_LINE7.$NOTI_LINE8.$NOTI_LINE9.$NOTI_LINE10.$NOTI_LINE11.$NOTI_LINE12.$NOTI_LINE13.$NOTI_LINE14.$NOTI_LINE15.$NOTI_LINE16.$NOTI_LINE17.$NOTI_LINE18.$NOTI_LINE19.$NOTI_LINE20;
					}
					// $channelId = '-4748971185';
					$botApiToken  = '7514279565:AAG8L_IfiV1SD_4lF98WjtV5E4nLRqec_PY'; 
					$urltelegram = "https://api.telegram.org/bot$botApiToken/sendMessage?chat_id=$channelId&text=".urlencode($MESSAGE_NOTI_LINE);
					$response = file_get_contents($urltelegram); 
				}
			// แจ้งเตือนเทเลแกรม-CLOSE-------------------------------------------------------------------------------
		}
	};
	######################################################################################################
	if($target=="repairdetail"){
		$RPRQ_CODE = $_POST["RPRQ_CODE"];  
		$RPWD_DETAIL = $_POST["RPWD_DETAIL"];  
		$PROCESS_BY = $_SESSION["AD_PERSONCODE"];
		$PROCESS_DATE = date("Y-m-d H:i:s");

		$sql = "INSERT INTO REPAIRWAITDETAIL (RPRQ_CODE,RPWD_DETAIL,RPWD_CREATEBY,RPWD_CREATEDATE) VALUES (?,?,?,?)";
		$params = array($RPRQ_CODE,$RPWD_DETAIL,$PROCESS_BY,$PROCESS_DATE);
		$stmt = sqlsrv_query( $conn, $sql, $params);	

		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "บันทึกเรียบร้อย";	
		}
	};
	######################################################################################################
	if($proc=="delete" && !empty($id)){
		
		$RPRQ_STATUS = 'D';
		$RPRQ_EDITBY = $_SESSION["AD_PERSONCODE"];
		$RPRQ_EDITDATE = date("Y-m-d H:i:s");

		$sql = "UPDATE REPAIRREQUEST SET RPRQ_STATUS = ?,RPRQ_EDITBY = ?,RPRQ_EDITDATE = ? WHERE RPRQ_CODE = ? ";
		$params = array($RPRQ_STATUS, $RPRQ_EDITBY, $RPRQ_EDITDATE, $id);
		
		$sql1 = "SELECT * FROM REPAIRCAUSE WHERE RPRQ_CODE = ?";
		$params1 = array($id);	
		$query1 = sqlsrv_query( $conn, $sql1, $params1);	
		$result1 = sqlsrv_fetch_array($query1, SQLSRV_FETCH_ASSOC);
		$RPC_IMAGES1=$result1["RPC_IMAGES"];
		
		// Delete Old File		
		@unlink($path."../uploads/requestrepair/".$RPC_IMAGES1);

		// $sql2 = "DELETE FROM REPAIRCAUSE WHERE RPRQ_CODE = ? ";
		// $params2 = array($id);

		$stmt1 = sqlsrv_query( $conn, $sql, $params);
		// $stmt2 = sqlsrv_query( $conn, $sql2, $params2);
		// if( ($stmt1 === false) && ($stmt2 === false) ) {
		if( ($stmt1 === false) ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "ลบข้อมูลเรียบร้อยแล้ว";	
		}		
	};	
?>