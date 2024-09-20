<?php
	session_start();  	

	require('../include/connect.php');

	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];	
	$target=$_POST["target"];	
	$SESSION_AREA = $_SESSION["AD_AREA"];

	function time_diff($time1, $time2) {
		return (strtotime($time1) - strtotime($time2)) / 60;
	}
	######################################################################################################
	if($target=="save_openjob"){

		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
		$RPATTM_PROCESS = date("Y-m-d H:i:s");
		$RPATTM_GROUP = $_POST["RPATTM_GROUP"];
		$RPATTM_TOTAL = '0';
		$RPATTM_PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$RPATTM_PROCESSDATE = date("Y-m-d H:i:s");
			
		$sql = "INSERT INTO REPAIRACTUAL_TIME (RPRQ_CODE,RPC_SUBJECT,RPATTM_PROCESS,RPATTM_GROUP,RPATTM_TOTAL,RPATTM_PROCESSBY,RPATTM_PROCESSDATE) VALUES (?,?,?,?,?,?,?)";
		$params = array($RPRQ_CODE,$RPC_SUBJECT,$RPATTM_PROCESS,$RPATTM_GROUP,$RPATTM_TOTAL,$RPATTM_PROCESSBY,$RPATTM_PROCESSDATE);
		$stmt = sqlsrv_query( $conn, $sql, $params);
		
		$RPRQ_STATUSREQUEST = 'กำลังซ่อม';
		$sql1 = "UPDATE REPAIRREQUEST SET RPRQ_STATUSREQUEST = ? WHERE RPRQ_CODE = ?";
		$params1 = array($RPRQ_STATUSREQUEST, $RPRQ_CODE);		
		$stmt1 = sqlsrv_query( $conn, $sql1, $params1);
		
		if( ($stmt && $stmt1) === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "บันทึกข้อมูลเรียบร้อย";	
		}		
	};
	######################################################################################################
	if($target=="save_pausejob"){

		$RPATTM_PROCESS = date("Y-m-d H:i:s");
		$RPATTM_GROUP = $_POST["RPATTM_GROUP"];
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
		$RPATTM_PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$RPATTM_PROCESSDATE = date("Y-m-d H:i:s");
		
		$sql_chktime = "SELECT * FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$RPC_SUBJECT' ORDER BY RPATTM_ID DESC";
		$params_chktime = array();
		$query_chktime = sqlsrv_query($conn, $sql_chktime, $params_chktime);
		$result_chktime = sqlsrv_fetch_array($query_chktime, SQLSRV_FETCH_ASSOC); 
		$RS_RPATTM_PROCESS = $result_chktime["RPATTM_PROCESS"];

		$RPATTM_TOTAL = number_format(time_diff($RPATTM_PROCESS, $RS_RPATTM_PROCESS),0);

		$sql = "INSERT INTO REPAIRACTUAL_TIME (RPRQ_CODE,RPC_SUBJECT,RPATTM_PROCESS,RPATTM_GROUP,RPATTM_TOTAL,RPATTM_PROCESSBY,RPATTM_PROCESSDATE) VALUES (?,?,?,?,?,?,?)";
		$params = array($RPRQ_CODE,$RPC_SUBJECT,$RPATTM_PROCESS,$RPATTM_GROUP,$RPATTM_TOTAL,$RPATTM_PROCESSBY,$RPATTM_PROCESSDATE);		
		$stmt = sqlsrv_query( $conn, $sql, $params);

		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "บันทึกข้อมูลเรียบร้อย";	
		}		
	};
	######################################################################################################
	if($target=="save_continuejob"){

		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
		$RPATTM_PROCESS = date("Y-m-d H:i:s");
		$RPATTM_GROUP = $_POST["RPATTM_GROUP"];
		$RPATTM_TOTAL = '0';
		$RPATTM_PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$RPATTM_PROCESSDATE = date("Y-m-d H:i:s");
			
		$sql = "INSERT INTO REPAIRACTUAL_TIME (RPRQ_CODE,RPC_SUBJECT,RPATTM_PROCESS,RPATTM_GROUP,RPATTM_TOTAL,RPATTM_PROCESSBY,RPATTM_PROCESSDATE) VALUES (?,?,?,?,?,?,?)";
		$params = array($RPRQ_CODE,$RPC_SUBJECT,$RPATTM_PROCESS,$RPATTM_GROUP,$RPATTM_TOTAL,$RPATTM_PROCESSBY,$RPATTM_PROCESSDATE);
		$stmt = sqlsrv_query( $conn, $sql, $params);
		
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "บันทึกข้อมูลเรียบร้อย";	
		}		
	};
	######################################################################################################
	if($target=="save_successjob_bm"){

		$RPATTM_PROCESS = date("Y-m-d H:i:s");
		$RPATTM_GROUP = $_POST["RPATTM_GROUP"];
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
		$RPATTM_PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$RPATTM_PROCESSDATE = date("Y-m-d H:i:s");
		
		$sql_chktime = "SELECT * FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$RPC_SUBJECT' ORDER BY RPATTM_ID DESC";
		$params_chktime = array();
		$query_chktime = sqlsrv_query($conn, $sql_chktime, $params_chktime);
		$result_chktime = sqlsrv_fetch_array($query_chktime, SQLSRV_FETCH_ASSOC); 
		$RS_RPATTM_PROCESS = $result_chktime["RPATTM_PROCESS"];

		$RPATTM_TOTAL = number_format(time_diff($RPATTM_PROCESS, $RS_RPATTM_PROCESS),0);

		$sql = "INSERT INTO REPAIRACTUAL_TIME (RPRQ_CODE,RPC_SUBJECT,RPATTM_PROCESS,RPATTM_GROUP,RPATTM_TOTAL,RPATTM_PROCESSBY,RPATTM_PROCESSDATE) VALUES (?,?,?,?,?,?,?)";
		$params = array($RPRQ_CODE,$RPC_SUBJECT,$RPATTM_PROCESS,$RPATTM_GROUP,$RPATTM_TOTAL,$RPATTM_PROCESSBY,$RPATTM_PROCESSDATE);		
		$stmt = sqlsrv_query( $conn, $sql, $params);
		
		$RPRQ_STATUSREQUEST = 'ซ่อมเสร็จสิ้น';
		$sql1 = "UPDATE REPAIRREQUEST SET RPRQ_STATUSREQUEST = ? WHERE RPRQ_CODE = ?";
		$params1 = array($RPRQ_STATUSREQUEST, $RPRQ_CODE);		
		$stmt1 = sqlsrv_query( $conn, $sql1, $params1);

		// แจ้งเตือนไลน์--------------------------------------------------------------------------------
			if($RPC_SUBJECT=="EL"){
				$RPC_SUBJECT_NAME = "ระบบไฟ";
			}else if($RPC_SUBJECT=="TU"){
				$RPC_SUBJECT_NAME = "ยาง-ช่วงล่าง";
			}else if($RPC_SUBJECT=="BD"){
				$RPC_SUBJECT_NAME = "โครงสร้าง";
			}else if($RPC_SUBJECT=="EG"){
				$RPC_SUBJECT_NAME = "เครื่องยนต์";
			}
			$sql_timeact = "SELECT RPRQ_CODE,RPRQ_REGISHEAD,RPRQ_REGISTAIL,RPRQ_CARNAMEHEAD,RPRQ_CARNAMETAIL,
				(SELECT CONVERT(VARCHAR,CONVERT(DATETIME, RPATTM_PROCESS, 111),103)+' '+SUBSTRING(RPATTM_PROCESS, 12, 5) FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = '$RPC_SUBJECT' AND RPATTM_GROUP = 'START') AS A1,
				(SELECT CONVERT(VARCHAR,CONVERT(DATETIME, RPATTM_PROCESS, 111),103)+' '+SUBSTRING(RPATTM_PROCESS, 12, 5) FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = '$RPC_SUBJECT' AND RPATTM_GROUP = 'SUCCESS') AS A2
				FROM REPAIRREQUEST A WHERE RPRQ_CODE = '$RPRQ_CODE'";
			$params_timeact = array();
			$query_timeact = sqlsrv_query($conn, $sql_timeact, $params_timeact);
			$result_timeact = sqlsrv_fetch_array($query_timeact, SQLSRV_FETCH_ASSOC); 
				$JOBSTART = $result_timeact["A1"];
				$JOBEND = $result_timeact["A2"];
				
			if(isset($RPATTM_TOTAL)){
				$DIFF_TIME = $RPATTM_TOTAL;
				$SEC = $DIFF_TIME % 60;
				$MIN = floor($DIFF_TIME / 60);														
				if(isset($MIN)){
					$RSMIN=$MIN;
				}else{
					$RSMIN='';
				}
				if(isset($SEC)){
					if($SEC > 0){
						$RSSEC=':'.$SEC;
					}else{
						$RSSEC='';
					}
				}else{
					$RSSEC='';
				}
			}else{
				$RSMIN='';
				$RSSEC='';
			}

			$stmt_lastjob = "SELECT RPRQ_ID,RPC_DETAIL FROM vwREPAIRREQUEST WHERE RPRQ_CODE = ?";
			$params_lastjob = array($RPRQ_CODE);	
			$query_lastjob = sqlsrv_query( $conn, $stmt_lastjob, $params_lastjob);	
			$result_lastjob = sqlsrv_fetch_array($query_lastjob, SQLSRV_FETCH_ASSOC);
				$LAST_RPRQ_ID=$result_lastjob["RPRQ_ID"];	
				$RPC_DETAIL=$result_lastjob["RPC_DETAIL"];	
			
			$stmt_linenoti = "SELECT * FROM SETTING WHERE ST_TYPE = '30' AND ST_STATUS = 'Y' AND ST_AREA = '$SESSION_AREA'";
			$query_linenoti = sqlsrv_query( $conn, $stmt_linenoti);	
			$no=0;
			while($result_linenoti = sqlsrv_fetch_array($query_linenoti)){	
				$no++;
				$ST_DETAIL=$result_linenoti["ST_DETAIL"];
				$TK_RQRP="$ST_DETAIL";  
				$NOTI_LINE1=" 🟢 ซ่อมเสร็จ ($RPC_SUBJECT_NAME)"."\n";
				$NOTI_LINE2="ID : ".$LAST_RPRQ_ID."\n";
				$NOTI_LINE3="ทะเบียน(หัว) : ".$result_timeact['RPRQ_REGISHEAD']."\n";
				$NOTI_LINE4="ชื่อรถ(หัว) : ".$result_timeact['RPRQ_CARNAMEHEAD']."\n";
				$NOTI_LINE5="ทะเบียน(หาง) : ".$result_timeact['RPRQ_REGISTAIL']."\n";
				$NOTI_LINE6="ชื่อรถ(หาง) : ".$result_timeact['RPRQ_CARNAMETAIL']."\n";
				$NOTI_LINE7="ปัญหา : $RPC_DETAIL"."\n";
				$NOTI_LINE8="วันที่/เวลา เริ่มซ่อม : ".$JOBSTART.""."\n";
				$NOTI_LINE9="วันที่/เวลา ซ่อมเสร็จ : ".$JOBEND.""."\n";
				$NOTI_LINE10="รวมเวลาซ่อม : ".$RSMIN.$RSSEC."";   
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
				curl_close($chOne);   
			}
		// แจ้งเตือนไลน์--------------------------------------------------------------------------------
		
		$RPRQ_SPAREPART = $_POST["RPRQ_SPAREPART"];
		$SPAREPART_PROCESSDATE = date("d/m/Y");
		if(isset($RPRQ_SPAREPART)){			
			$sql_chktspp = "SELECT * FROM REPAIR_SPAREPART WHERE RPSPP_CODE = '$RPRQ_SPAREPART'";
			$params_chktspp = array();
			$query_chktspp = sqlsrv_query($conn, $sql_chktspp, $params_chktspp);
			$result_chktspp = sqlsrv_fetch_array($query_chktspp, SQLSRV_FETCH_ASSOC); 
				$RPSPP_LASTSPARE = $result_chktspp["RPSPP_LASTSPARE"];
				$RPSPP_NEXTSPARE = $result_chktspp["RPSPP_NEXTSPARE"];
				
			$sql_inspp = "INSERT INTO REPAIR_SPAREPART_LOG (RPSPPL_CODE,RPSPPL_OLDLASTCHANGE,RPSPPL_OLDNEXTCHANGE,RPSPPL_PROCESSDATE) VALUES (?,?,?,?)";
			$params_inspp = array($RPRQ_SPAREPART,$RPSPP_LASTSPARE,$RPSPP_NEXTSPARE,$RPATTM_PROCESSDATE);		
			$stmt_inspp = sqlsrv_query( $conn, $sql_inspp, $params_inspp);

			$sql_spp = "UPDATE REPAIR_SPAREPART SET RPSPP_LASTSPARE = ?,RPSPP_NEXTSPARE = ? WHERE RPSPP_CODE = ?";
			$params_spp = array($SPAREPART_PROCESSDATE,'', $RPRQ_SPAREPART);		
			$stmt_spp = sqlsrv_query( $conn, $sql_spp, $params_spp);
		}

		if( ($stmt && $stmt1) === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "บันทึกข้อมูลเรียบร้อย";	
		}		
	};
	######################################################################################################
	if($target=="save_successjob_pm"){

		$RPATTM_PROCESS = date("Y-m-d H:i:s");
		$RPATTM_GROUP = $_POST["RPATTM_GROUP"];
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
		$RPATTM_PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$RPATTM_PROCESSDATE = date("Y-m-d H:i:s");
		
		$sql_chktime = "SELECT * FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$RPC_SUBJECT' ORDER BY RPATTM_ID DESC";
		$params_chktime = array();
		$query_chktime = sqlsrv_query($conn, $sql_chktime, $params_chktime);
		$result_chktime = sqlsrv_fetch_array($query_chktime, SQLSRV_FETCH_ASSOC); 
		$RS_RPATTM_PROCESS = $result_chktime["RPATTM_PROCESS"];

		$RPATTM_TOTAL = number_format(time_diff($RPATTM_PROCESS, $RS_RPATTM_PROCESS),0);

		$sql = "INSERT INTO REPAIRACTUAL_TIME (RPRQ_CODE,RPC_SUBJECT,RPATTM_PROCESS,RPATTM_GROUP,RPATTM_TOTAL,RPATTM_PROCESSBY,RPATTM_PROCESSDATE) VALUES (?,?,?,?,?,?,?)";
		$params = array($RPRQ_CODE,$RPC_SUBJECT,$RPATTM_PROCESS,$RPATTM_GROUP,$RPATTM_TOTAL,$RPATTM_PROCESSBY,$RPATTM_PROCESSDATE);		
		$stmt = sqlsrv_query( $conn, $sql, $params);

		// ---------------------- เช็ค PM 4 รายการ ----------------------
			$sql_chk_el_success = "SELECT * FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = 'EL' ORDER BY RPATTM_ID DESC";
			$params_chk_el_success = array();
			$query_chk_el_success = sqlsrv_query($conn, $sql_chk_el_success, $params_chk_el_success);
			$result_chk_el_success = sqlsrv_fetch_array($query_chk_el_success, SQLSRV_FETCH_ASSOC); 
			$RPATTM_GROUP_EL = $result_chk_el_success["RPATTM_GROUP"];
			$sql_chk_tu_success = "SELECT * FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = 'TU' ORDER BY RPATTM_ID DESC";
			$params_chk_tu_success = array();
			$query_chk_tu_success = sqlsrv_query($conn, $sql_chk_tu_success, $params_chk_tu_success);
			$result_chk_tu_success = sqlsrv_fetch_array($query_chk_tu_success, SQLSRV_FETCH_ASSOC); 
			$RPATTM_GROUP_TU = $result_chk_tu_success["RPATTM_GROUP"];
			$sql_chk_bd_success = "SELECT * FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = 'BD' ORDER BY RPATTM_ID DESC";
			$params_chk_bd_success = array();
			$query_chk_bd_success = sqlsrv_query($conn, $sql_chk_bd_success, $params_chk_bd_success);
			$result_chk_bd_success = sqlsrv_fetch_array($query_chk_bd_success, SQLSRV_FETCH_ASSOC); 
			$RPATTM_GROUP_BD = $result_chk_bd_success["RPATTM_GROUP"];
			$sql_chk_eg_success = "SELECT * FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = 'EG' ORDER BY RPATTM_ID DESC";
			$params_chk_eg_success = array();
			$query_chk_eg_success = sqlsrv_query($conn, $sql_chk_eg_success, $params_chk_eg_success);
			$result_chk_eg_success = sqlsrv_fetch_array($query_chk_eg_success, SQLSRV_FETCH_ASSOC); 
			$RPATTM_GROUP_EG = $result_chk_eg_success["RPATTM_GROUP"];

		if(($RPATTM_GROUP_EL == 'SUCCESS' && $RPATTM_GROUP_TU == 'SUCCESS' && $RPATTM_GROUP_BD == 'SUCCESS' && $RPATTM_GROUP_EG == 'SUCCESS')  ){		
			$RPRQ_STATUSREQUEST = 'ซ่อมเสร็จสิ้น';
			$sql1 = "UPDATE REPAIRREQUEST SET RPRQ_STATUSREQUEST = ? WHERE RPRQ_CODE = ?";
			$params1 = array($RPRQ_STATUSREQUEST, $RPRQ_CODE);		
			$stmt1 = sqlsrv_query( $conn, $sql1, $params1);
				
			// แจ้งเตือนไลน์--------------------------------------------------------------------------------
				if($RPC_SUBJECT=="EL"){
					$RPC_SUBJECT_NAME = "ระบบไฟ";
				}else if($RPC_SUBJECT=="TU"){
					$RPC_SUBJECT_NAME = "ยาง-ช่วงล่าง";
				}else if($RPC_SUBJECT=="BD"){
					$RPC_SUBJECT_NAME = "โครงสร้าง";
				}else if($RPC_SUBJECT=="EG"){
					$RPC_SUBJECT_NAME = "เครื่องยนต์";
				}
				$sql_timeact = "SELECT RPRQ_MILEAGEFINISH,RPRQ_CODE,RPRQ_REGISHEAD,RPRQ_REGISTAIL,RPRQ_CARNAMEHEAD,RPRQ_CARNAMETAIL,
					(SELECT CONVERT(VARCHAR,CONVERT(DATETIME, RPATTM_PROCESS, 111),103)+' '+SUBSTRING(RPATTM_PROCESS, 12, 5) FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'EG' AND RPATTM_GROUP = 'START') AS EGS,
					(SELECT CONVERT(VARCHAR,CONVERT(DATETIME, RPATTM_PROCESS, 111),103)+' '+SUBSTRING(RPATTM_PROCESS, 12, 5) FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'EG' AND RPATTM_GROUP = 'SUCCESS') AS EGE,
					(SELECT RPATTM_TOTAL FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'EG' AND RPATTM_GROUP = 'SUCCESS') AS EGT,
					(SELECT CONVERT(VARCHAR,CONVERT(DATETIME, RPATTM_PROCESS, 111),103)+' '+SUBSTRING(RPATTM_PROCESS, 12, 5) FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'BD' AND RPATTM_GROUP = 'START') AS BDS,
					(SELECT CONVERT(VARCHAR,CONVERT(DATETIME, RPATTM_PROCESS, 111),103)+' '+SUBSTRING(RPATTM_PROCESS, 12, 5) FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'BD' AND RPATTM_GROUP = 'SUCCESS') AS BDE,
					(SELECT RPATTM_TOTAL FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'BD' AND RPATTM_GROUP = 'SUCCESS') AS BDT,
					(SELECT CONVERT(VARCHAR,CONVERT(DATETIME, RPATTM_PROCESS, 111),103)+' '+SUBSTRING(RPATTM_PROCESS, 12, 5) FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'TU' AND RPATTM_GROUP = 'START') AS TUS,
					(SELECT CONVERT(VARCHAR,CONVERT(DATETIME, RPATTM_PROCESS, 111),103)+' '+SUBSTRING(RPATTM_PROCESS, 12, 5) FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'TU' AND RPATTM_GROUP = 'SUCCESS') AS TUE,
					(SELECT RPATTM_TOTAL FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'TU' AND RPATTM_GROUP = 'SUCCESS') AS TUT,
					(SELECT CONVERT(VARCHAR,CONVERT(DATETIME, RPATTM_PROCESS, 111),103)+' '+SUBSTRING(RPATTM_PROCESS, 12, 5) FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'EL' AND RPATTM_GROUP = 'START') AS ELS,
					(SELECT CONVERT(VARCHAR,CONVERT(DATETIME, RPATTM_PROCESS, 111),103)+' '+SUBSTRING(RPATTM_PROCESS, 12, 5) FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'EL' AND RPATTM_GROUP = 'SUCCESS') AS ELE,
					(SELECT RPATTM_TOTAL FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'EL' AND RPATTM_GROUP = 'SUCCESS') AS ELT
					FROM REPAIRREQUEST A WHERE RPRQ_CODE = '$RPRQ_CODE'";
				$params_timeact = array();
				$query_timeact = sqlsrv_query($conn, $sql_timeact, $params_timeact);
				$result_timeact = sqlsrv_fetch_array($query_timeact, SQLSRV_FETCH_ASSOC);
					if(isset($result_timeact['EGT'])){
						$DIFF_TIME_EGT = $result_timeact['EGT'];
						$SEC_EGT = $DIFF_TIME_EGT % 60;
						$MIN_EGT = floor($DIFF_TIME_EGT / 60);														
						if(isset($MIN_EGT)){
							$RSMIN_EGT=$MIN_EGT;
						}else{
							$RSMIN_EGT='';
						}
						if(isset($SEC_EGT)){
							if($SEC_EGT > 0){
								$RSSEC_EGT=':'.$SEC_EGT;
							}else{
								$RSSEC_EGT='';
							}
						}else{
							$RSSEC_EGT='';
						}
					}else{
						$RSMIN_EGT='';
						$RSSEC_EGT='';
					}
					if(isset($result_timeact['BDT'])){
						$DIFF_TIME_BDT = $result_timeact['BDT'];
						$SEC_BDT = $DIFF_TIME_BDT % 60;
						$MIN_BDT = floor($DIFF_TIME_BDT / 60);														
						if(isset($MIN_BDT)){
							$RSMIN_BDT=$MIN_BDT;
						}else{
							$RSMIN_BDT='';
						}
						if(isset($SEC_BDT)){
							if($SEC_BDT > 0){
								$RSSEC_BDT=':'.$SEC_BDT;
							}else{
								$RSSEC_BDT='';
							}
						}else{
							$RSSEC_BDT='';
						}
					}else{
						$RSMIN_BDT='';
						$RSSEC_BDT='';
					}
					if(isset($result_timeact['TUT'])){
						$DIFF_TIME_TUT = $result_timeact['TUT'];
						$SEC_TUT = $DIFF_TIME_TUT % 60;
						$MIN_TUT = floor($DIFF_TIME_TUT / 60);														
						if(isset($MIN_TUT)){
							$RSMIN_TUT=$MIN_TUT;
						}else{
							$RSMIN_TUT='';
						}
						if(isset($SEC_TUT)){
							if($SEC_TUT > 0){
								$RSSEC_TUT=':'.$SEC_TUT;
							}else{
								$RSSEC_TUT='';
							}
						}else{
							$RSSEC_TUT='';
						}
					}else{
						$RSMIN_TUT='';
						$RSSEC_TUT='';
					}
					if(isset($result_timeact['ELT'])){
						$DIFF_TIME_ELT = $result_timeact['ELT'];
						$SEC_ELT = $DIFF_TIME_ELT % 60;
						$MIN_ELT = floor($DIFF_TIME_ELT / 60);														
						if(isset($MIN_ELT)){
							$RSMIN_ELT=$MIN_ELT;
						}else{
							$RSMIN_ELT='';
						}
						if(isset($SEC_ELT)){
							if($SEC_ELT > 0){
								$RSSEC_ELT=':'.$SEC_ELT;
							}else{
								$RSSEC_ELT='';
							}
						}else{
							$RSSEC_ELT='';
						}
					}else{
						$RSMIN_ELT='';
						$RSSEC_ELT='';
					}

				$stmt_lastjob = "SELECT RPRQ_ID,RPC_DETAIL FROM vwREPAIRREQUEST WHERE RPRQ_CODE = ?";
				$params_lastjob = array($RPRQ_CODE);	
				$query_lastjob = sqlsrv_query( $conn, $stmt_lastjob, $params_lastjob);	
				$result_lastjob = sqlsrv_fetch_array($query_lastjob, SQLSRV_FETCH_ASSOC);
					$LAST_RPRQ_ID=$result_lastjob["RPRQ_ID"];	
					$RPC_DETAIL=$result_lastjob["RPC_DETAIL"];	
				
				$stmt_linenoti = "SELECT * FROM SETTING WHERE ST_TYPE = '30' AND ST_STATUS = 'Y' AND ST_AREA = '$SESSION_AREA'";
				$query_linenoti = sqlsrv_query( $conn, $stmt_linenoti);	
				$no=0;
				while($result_linenoti = sqlsrv_fetch_array($query_linenoti)){	
					$no++;
					$ST_DETAIL=$result_linenoti["ST_DETAIL"];					
					$TK_RQRP="$ST_DETAIL";  
					$NOTI_LINE1=" 🟢 ซ่อมเสร็จ PM (".$result_timeact['RPRQ_MILEAGEFINISH'].")"."\n";
					$NOTI_LINE2="ID : ".$LAST_RPRQ_ID."\n";
					$NOTI_LINE3="ทะเบียน(หัว) : ".$result_timeact['RPRQ_REGISHEAD']."\n";
					$NOTI_LINE4="ชื่อรถ(หัว) : ".$result_timeact['RPRQ_CARNAMEHEAD']."\n";
					$NOTI_LINE5="ทะเบียน(หาง) : ".$result_timeact['RPRQ_REGISTAIL']."\n";
					$NOTI_LINE6="ชื่อรถ(หาง) : ".$result_timeact['RPRQ_CARNAMETAIL']."\n";
					$NOTI_LINE7="เครื่องยนต์ : "."\n";
					$NOTI_LINE8="วันที่/เวลา เริ่มซ่อม : ".$result_timeact['EGS']."\n";
					$NOTI_LINE9="วันที่/เวลา ซ่อมเสร็จ : ".$result_timeact['EGE']."\n";
					$NOTI_LINE10="รวมเวลาซ่อม : ".$RSMIN_EGT.$RSSEC_EGT."\n";
					$NOTI_LINE11="--------------------------------"."\n";
					$NOTI_LINE12="โครงสร้าง : "."\n";
					$NOTI_LINE13="วันที่/เวลา เริ่มซ่อม : ".$result_timeact['BDS']."\n";
					$NOTI_LINE14="วันที่/เวลา ซ่อมเสร็จ : ".$result_timeact['BDE']."\n";
					$NOTI_LINE15="รวมเวลาซ่อม : ".$RSMIN_BDT.$RSSEC_BDT."\n";
					$NOTI_LINE16="--------------------------------"."\n";
					$NOTI_LINE17="ยาง ช่วงล่าง : "."\n";
					$NOTI_LINE18="วันที่/เวลา เริ่มซ่อม : ".$result_timeact['TUS']."\n";
					$NOTI_LINE19="วันที่/เวลา ซ่อมเสร็จ : ".$result_timeact['TUE']."\n";
					$NOTI_LINE20="รวมเวลาซ่อม : ".$RSMIN_TUT.$RSSEC_TUT."\n";
					$NOTI_LINE21="--------------------------------"."\n";
					$NOTI_LINE22="ระบบไฟ : "."\n";
					$NOTI_LINE23="วันที่/เวลา เริ่มซ่อม : ".$result_timeact['ELS']."\n";
					$NOTI_LINE24="วันที่/เวลา ซ่อมเสร็จ : ".$result_timeact['ELE']."\n";
					$NOTI_LINE25="รวมเวลาซ่อม : ".$RSMIN_ELT.$RSSEC_ELT.""; 
					$MESSAGE_NOTI_LINE=$NOTI_LINE1.$NOTI_LINE2.$NOTI_LINE3.$NOTI_LINE4.$NOTI_LINE5.$NOTI_LINE6.$NOTI_LINE7.$NOTI_LINE8.$NOTI_LINE9.$NOTI_LINE10.$NOTI_LINE11.$NOTI_LINE12.$NOTI_LINE13.$NOTI_LINE14.$NOTI_LINE15.$NOTI_LINE16.$NOTI_LINE17.$NOTI_LINE18.$NOTI_LINE19.$NOTI_LINE20.$NOTI_LINE21.$NOTI_LINE22.$NOTI_LINE23.$NOTI_LINE24.$NOTI_LINE25;

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
					curl_close($chOne);   
				}
			// แจ้งเตือนไลน์--------------------------------------------------------------------------------

			echo json_encode(array("statusCode"=>200)); 
		}else{
			echo json_encode(array("statusCode"=>201)); 
		}

		// if( ($stmt) === false ) {
		// 	die( print_r( sqlsrv_errors(), true));
		// }else{
		// 	print "บันทึกข้อมูลเรียบร้อย";	
		// }		
	};
	######################################################################################################
	if($target=="save_successjob_pm_out"){
	
		$RPATTM_PROCESS = date("Y-m-d H:i:s");
		$RPATTM_GROUP = $_POST["RPATTM_GROUP"];
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
		$RPATTM_PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$RPATTM_PROCESSDATE = date("Y-m-d H:i:s");
		
		$sql_chktime = "SELECT * FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$RPC_SUBJECT' ORDER BY RPATTM_ID DESC";
		$params_chktime = array();
		$query_chktime = sqlsrv_query($conn, $sql_chktime, $params_chktime);
		$result_chktime = sqlsrv_fetch_array($query_chktime, SQLSRV_FETCH_ASSOC); 
		$RS_RPATTM_PROCESS = $result_chktime["RPATTM_PROCESS"];

		$RPATTM_TOTAL = number_format(time_diff($RPATTM_PROCESS, $RS_RPATTM_PROCESS),0);
		
		$sql = "INSERT INTO REPAIRACTUAL_TIME (RPRQ_CODE,RPC_SUBJECT,RPATTM_PROCESS,RPATTM_GROUP,RPATTM_TOTAL,RPATTM_PROCESSBY,RPATTM_PROCESSDATE) VALUES (?,?,?,?,?,?,?)";
		$params = array($RPRQ_CODE,$RPC_SUBJECT,$RPATTM_PROCESS,$RPATTM_GROUP,$RPATTM_TOTAL,$RPATTM_PROCESSBY,$RPATTM_PROCESSDATE);		
		$stmt = sqlsrv_query( $conn, $sql, $params);

		$sql_chk_success = "SELECT CASE WHEN A.RPC_SUBJECT='EL' THEN (SELECT RPATTM_GROUP FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'EL' AND RPATTM_GROUP = 'SUCCESS')	ELSE 'NON' END AS CHK
			FROM REPAIRCAUSE A
			WHERE A.RPRQ_CODE = '$RPRQ_CODE' AND A.RPC_INCARDATE IS NOT NULL AND A.RPC_SUBJECT='EL'
			UNION
			SELECT CASE WHEN A.RPC_SUBJECT='TU' THEN (SELECT RPATTM_GROUP FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'TU' AND RPATTM_GROUP = 'SUCCESS') ELSE 'NON' END AS CHK
			FROM REPAIRCAUSE A
			WHERE A.RPRQ_CODE = '$RPRQ_CODE' AND A.RPC_INCARDATE IS NOT NULL AND A.RPC_SUBJECT='TU'
			UNION
			SELECT CASE WHEN A.RPC_SUBJECT='BD' THEN (SELECT RPATTM_GROUP FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'BD' AND RPATTM_GROUP = 'SUCCESS') ELSE 'NON' END AS CHK
			FROM REPAIRCAUSE A
			WHERE A.RPRQ_CODE = '$RPRQ_CODE' AND A.RPC_INCARDATE IS NOT NULL AND A.RPC_SUBJECT='BD'
			UNION
			SELECT CASE WHEN A.RPC_SUBJECT='EG' THEN (SELECT RPATTM_GROUP FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = A.RPRQ_CODE AND RPC_SUBJECT = 'EG' AND RPATTM_GROUP = 'SUCCESS') ELSE 'NON' END AS CHK
			FROM REPAIRCAUSE A
			WHERE A.RPRQ_CODE = '$RPRQ_CODE' AND A.RPC_INCARDATE IS NOT NULL AND A.RPC_SUBJECT='EG'";
		$params_chk_success = array();
		$query_chk_success = sqlsrv_query($conn, $sql_chk_success, $params_chk_success);
		$result_chk_success = sqlsrv_fetch_array($query_chk_success, SQLSRV_FETCH_ASSOC); 
		if(isset($result_chk_success["CHK"])){
			$CHK = 'SUCCESS';
		}else{
			$CHK = 'UNSUCCESS';
		}
		if(($CHK == 'SUCCESS')  ){		
			$RPRQ_STATUSREQUEST = 'ซ่อมเสร็จสิ้น';
			// print_r($RPRQ_STATUSREQUEST);
			// echo "<br>";
			$sql1 = "UPDATE REPAIRREQUEST SET RPRQ_STATUSREQUEST = ? WHERE RPRQ_CODE = ?";
			$params1 = array($RPRQ_STATUSREQUEST, $RPRQ_CODE);		
			$stmt1 = sqlsrv_query( $conn, $sql1, $params1);
			
			echo json_encode(array("statusCode"=>200)); 
		}else{
			$RPRQ_STATUSREQUEST = 'ซ่อมไม่เสร็จสิ้น';
			// print_r($RPRQ_STATUSREQUEST);
			// echo "<br>";
			echo json_encode(array("statusCode"=>201)); 
		}		
	};
	######################################################################################################
	if($target=="save_openjob2222"){

		$RPATTM_PROCESS = date("Y-m-d H:i:s");
		$RPATTM_GROUP = $_POST["RPATTM_GROUP"];
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPC_SUBJECT = $_POST["RPC_SUBJECT"];
		$RPATTM_PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$RPATTM_PROCESSDATE = date("Y-m-d H:i:s");
		
		$sql_chktime = "SELECT * FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPRQ_SUBJECT = '$RPRQ_SUBJECT'";
		$params_chktime = array();
		$query_chktime = sqlsrv_query($conn, $sql_chktime, $params_chktime);
		$result_chktime = sqlsrv_fetch_array($query_chktime, SQLSRV_FETCH_ASSOC); 
	
		if(isset($result_chktime['RPATTM_PROCESS'])){
			$sql = "UPDATE REPAIRCAUSE SET 
						RPATTM_PROCESS = ?,
						RPATTM_GROUP = ?
						WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ?";
			$params = array($RPATTM_PROCESS, $RPATTM_GROUP, $RPRQ_CODE, $RPC_SUBJECT);
		}else{			
			$sql = "INSERT INTO REPAIRMANDRIVE (RPRQ_CODE,RPC_SUBJECT,RPATTM_PROCESS,RPATTM_GROUP,RPATTM_TOTAL,RPATTM_PROCESSBY,RPATTM_PROCESSDATE) VALUES (?,?,?,?,?,?,?)";
			$params = array($RPRQ_CODE,$RPC_SUBJECT,$RPATTM_PROCESS,$RPATTM_GROUP,$RPATTM_TOTAL,$RPATTM_PROCESSBY,$RPATTM_PROCESSDATE);
		}
	
		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "บันทึกข้อมูลเรียบร้อย";	
		}		
	};
	######################################################################################################
	if($target=="detail"){
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPC_SUBJECT = $_POST["SUBJECT"];
		$RPATDT_DETAIL = $_POST["detailrepair"];		
		$RPATDT_GROUP = $_POST["type"];
		$RPATDT_TOTAL = '';
		$RPATDT_PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$RPATDT_PROCESSDATE = date("Y-m-d H:i:s");
			
		$sql = "INSERT INTO REPAIRACTUAL_DETAIL (RPRQ_CODE,RPC_SUBJECT,RPATDT_DETAIL,RPATDT_GROUP,RPATDT_TOTAL,RPATDT_PROCESSBY,RPATDT_PROCESSDATE) VALUES (?,?,?,?,?,?,?)";
		$params = array($RPRQ_CODE,$RPC_SUBJECT,$RPATDT_DETAIL,$RPATDT_GROUP,$RPATDT_TOTAL,$RPATDT_PROCESSBY,$RPATDT_PROCESSDATE);
		$stmt = sqlsrv_query( $conn, $sql, $params);
		
		if($stmt === false) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "บันทึกข้อมูลเรียบร้อย";	
		}		
	};
	######################################################################################################
	if($target=="savechklist"){
		$RPRQ_CODE = $_POST["rprqcode"];
		$RPC_SUBJECT = $_POST["subject"];
		$CLRP_CODE = $_POST["clrpcode"];		
		$RPATCL_TYPE = $_POST["select"];
		$RPATCL_REMARK = $_POST["input"];
		$RPATCL_PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$RPATCL_PROCESSDATE = date("Y-m-d H:i:s");

		$sql1 = "SELECT * FROM REPAIRACTUAL_CHECKLIST WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ? AND CLRP_CODE = ?";
		$params1 = array($RPRQ_CODE,$RPC_SUBJECT,$CLRP_CODE);	
		$query1 = sqlsrv_query( $conn, $sql1, $params1);	
		$result1 = sqlsrv_fetch_array($query1, SQLSRV_FETCH_ASSOC);
			$RPATCL_ID=$result1["RPATCL_ID"];
			
		if(isset($RPATCL_ID)){
			$sql = "UPDATE REPAIRACTUAL_CHECKLIST SET RPATCL_TYPE=?,RPATCL_REMARK=?,RPATCL_PROCESSBY=?,RPATCL_PROCESSDATE=? WHERE RPRQ_CODE=? AND RPC_SUBJECT=? AND CLRP_CODE=?";
			$params = array($RPATCL_TYPE,$RPATCL_REMARK,$RPATCL_PROCESSBY,$RPATCL_PROCESSDATE,$RPRQ_CODE,$RPC_SUBJECT,$CLRP_CODE);	
			$stmt = sqlsrv_query( $conn, $sql, $params);
		}else{
			$sql = "INSERT INTO REPAIRACTUAL_CHECKLIST (RPRQ_CODE,RPC_SUBJECT,CLRP_CODE,RPATCL_TYPE,RPATCL_REMARK,RPATCL_PROCESSBY,RPATCL_PROCESSDATE) VALUES (?,?,?,?,?,?,?)";
			$params = array($RPRQ_CODE,$RPC_SUBJECT,$CLRP_CODE,$RPATCL_TYPE,$RPATCL_REMARK,$RPATCL_PROCESSBY,$RPATCL_PROCESSDATE);
			$stmt = sqlsrv_query( $conn, $sql, $params);
		}
		
		if($stmt === false) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "บันทึกข้อมูลเรียบร้อย";	
		}		
	};
	######################################################################################################
	if($target=="imagerepair"){
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPC_SUBJECT = $_POST["SUBJECT"];	
		$RPATIM_GROUP = $_POST["RPATIM_GROUP"];
		$RPATIM_PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$RPATIM_PROCESSDATE = date("Y-m-d H:i:s");
		
		isset( $_FILES['fileToUpload']['tmp_name'] ) ? $fileToUpload_tmp_name = $_FILES['fileToUpload']['tmp_name'] : $fileToUpload_tmp_name = "";
		isset( $_FILES['fileToUpload']['name'] ) ? $fileToUpload_name = $_FILES['fileToUpload']['name'] : $fileToUpload_name = "";
		if( !empty( $fileToUpload_tmp_name ) && !empty( $fileToUpload_name ) ) {
			$no = 1;					
			$n=15;
			function RandNum($n) {
				$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$randomString = '';      
				for ($i = 0; $i < $n; $i++) {
					$index = rand(0, strlen($characters) - 1);
					$randomString .= $characters[$index];
				}      
				return $randomString;
			} 	
			for( $i=0; $i<count( $fileToUpload_tmp_name ); $i++ ) {
				$rand="RPATIM_".RandNum($n);
				$RPC_IMAGES = $rand;
				$ext = pathinfo($fileToUpload_name[$i], PATHINFO_EXTENSION);
				$RPATIM_IMAGES = $RPC_IMAGES.".".$ext;
				if( move_uploaded_file( $fileToUpload_tmp_name[$i], "../../uploads/requestrepair/".$RPATIM_IMAGES) ) {	
					$sql = "INSERT INTO REPAIRACTUAL_IMAGE (RPRQ_CODE,RPC_SUBJECT,RPATIM_GROUP,RPATIM_IMAGES,RPATIM_PROCESSBY,RPATIM_PROCESSDATE) VALUES (?,?,?,?,?,?)";
					$params = array($RPRQ_CODE,$RPC_SUBJECT,$RPATIM_GROUP,$RPATIM_IMAGES,$RPATIM_PROCESSBY,$RPATIM_PROCESSDATE);
					$stmt = sqlsrv_query( $conn, $sql, $params);
					if( $stmt === false ) {
						die( print_r( sqlsrv_errors(), true));
					}else{
						// print "บันทึกข้อมูลเรียบร้อย";	
						// echo "อัปโหลดรูปภาพที่ {$no} ลงฐานข้อมูลเรียบร้อย<br/>";
						$no++;
					}	
				}
			}	
			if( $stmt === false ) {
				die( print_r( sqlsrv_errors(), true));
			}else{
				print "บันทึกข้อมูลเรียบร้อย";	
			}	
		}
	}
	######################################################################################################
	if($proc=="delete"){
		
		$RPRQ_CODE=$_GET["id"];
		$SUBJECT=$_GET["subject"];
		$RPATIM_GROUP=$_GET["groub"];
		$RPATIM_IMAGES=$_GET["image"];
		@unlink("../../uploads/requestrepair/".$RPATIM_IMAGES);

		$sql = "DELETE FROM REPAIRACTUAL_IMAGE WHERE RPRQ_CODE=? AND RPC_SUBJECT=? AND RPATIM_GROUP=? AND RPATIM_IMAGES=?";
		$params = array($RPRQ_CODE,$SUBJECT,$RPATIM_GROUP,$RPATIM_IMAGES);
		$stmt = sqlsrv_query( $conn, $sql, $params);

		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "ลบข้อมูลเรียบร้อยแล้ว";	
		}		
	};	
	######################################################################################################
?>