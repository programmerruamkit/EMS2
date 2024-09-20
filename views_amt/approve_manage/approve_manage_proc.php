<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];	
	$GETTYPE=$_POST["GETTYPE"];
	$SESSION_AREA = $_SESSION["AD_AREA"];
	
	
	$type_chk=$_POST["type_chk"];
	$RPRQ_APPROVE = $_SESSION["AD_PERSONCODE"];
	$RPRQ_APPROVE_DATE = date("Y-m-d H:i:s");
	
	if($type_chk=="4"){ // กรณีอนุมัติรายการแจ้งซ่อม		
		$RPRQ_STATUSREQUEST = "รอจ่ายงาน";		
        for($epy=1;$epy<=$_POST["hdnLine"];$epy++){
			$sql1 = "UPDATE REPAIRREQUEST SET 
				RPRQ_STATUSREQUEST = '".$RPRQ_STATUSREQUEST."' ,
				RPRQ_APPROVE = '".$RPRQ_APPROVE."' ,
				RPRQ_APPROVE_DATE = '".$RPRQ_APPROVE_DATE."'
				WHERE RPRQ_ID = '".$_POST["RPRQ_ID$epy"]."'";
			$stmt1 = sqlsrv_query( $conn, $sql1);	
			$RPRQ_ID=$_POST["RPRQ_ID$epy"];

			// $confirmdate=$_POST["confirmdate$epy"];
			// if($confirmdate=='notconfirm'){
				// 	if($GETTYPE=='bm'){
				// 		$RPC_INCARDATETIME = $_POST["RPC_INCARDATETIME$epy"];		
				// 		$exin = explode(" ", $RPC_INCARDATETIME);
				// 		$exin1 = $exin[0];
				// 		$exin2 = $exin[1]; 
				// 		$RPC_INCARDATE = $exin1;
				// 		$RPC_INCARTIME = $exin2;
				// 		$RPC_OUTCARDATETIME = $_POST["RPC_OUTCARDATETIME$epy"];		
				// 		$exout = explode(" ", $RPC_OUTCARDATETIME);
				// 		$exout1 = $exout[0];
				// 		$exout2 = $exout[1]; 
				// 		$RPC_OUTCARDATE = $exout1;
				// 		$RPC_OUTCARTIME = $exout2;
		
				// 		$sql = "UPDATE REPAIRCAUSE SET 
				// 			RPC_INCARDATE = '".$RPC_INCARDATE."' ,
				// 			RPC_INCARTIME = '".$RPC_INCARTIME."' ,
				// 			RPC_OUTCARDATE = '".$RPC_OUTCARDATE."' ,
				// 			RPC_OUTCARTIME = '".$RPC_OUTCARTIME."'
				// 			WHERE RPRQ_CODE = '".$_POST["RPRQ_CODE$epy"]."'";
				// 		$params = sqlsrv_query( $conn, $sql);
				// 	}	
				// }else if($confirmdate=='confirm'){
				// 	if($GETTYPE=='bm'){
				// 		$RPC_INCARDATETIME = $_POST["RPC_INCARDATETIME$epy"];		
				// 		$exin = explode(" ", $RPC_INCARDATETIME);
				// 		$exin1 = $exin[0];
				// 		$exin2 = $exin[1]; 
				// 		$RPC_INCARDATE = $exin1;
				// 		$RPC_INCARTIME = $exin2;
				// 		$RPC_OUTCARDATETIME = $_POST["RPC_OUTCARDATETIME$epy"];		
				// 		$exout = explode(" ", $RPC_OUTCARDATETIME);
				// 		$exout1 = $exout[0];
				// 		$exout2 = $exout[1]; 
				// 		$RPC_OUTCARDATE = $exout1;
				// 		$RPC_OUTCARTIME = $exout2;
		
				// 		$sql = "UPDATE REPAIRCAUSE SET 
				// 			RPC_INCARDATE = '".$RPC_INCARDATE."' ,
				// 			RPC_INCARTIME = '".$RPC_INCARTIME."' ,
				// 			RPC_OUTCARDATE = '".$RPC_OUTCARDATE."' ,
				// 			RPC_OUTCARTIME = '".$RPC_OUTCARTIME."'
				// 			WHERE RPRQ_CODE = '".$_POST["RPRQ_CODE$epy"]."'";
				// 		$params = sqlsrv_query( $conn, $sql);
				// 	}
			// }		

			// แจ้งเตือนไลน์--------------------------------------------------------------------------------
				$stmt_lastjob = "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_ID = ?";
				$params_lastjob = array($RPRQ_ID);	
				$query_lastjob = sqlsrv_query( $conn, $stmt_lastjob, $params_lastjob);	
				$result_lastjob = sqlsrv_fetch_array($query_lastjob, SQLSRV_FETCH_ASSOC);
					$LAST_RPRQ_ID=$result_lastjob["RPRQ_ID"];	
					$LAST_RPRQ_WORKTYPE=$result_lastjob["RPRQ_WORKTYPE"];
					$RPRQ_REGISHEAD=$result_lastjob["RPRQ_REGISHEAD"];
					$RPRQ_REGISTAIL=$result_lastjob["RPRQ_REGISTAIL"];
					$RPRQ_REQUESTCARDATE=$result_lastjob["RPRQ_REQUESTCARDATE"];
					$RPRQ_REQUESTCARTIME=$result_lastjob["RPRQ_REQUESTCARTIME"];
					$RPRQ_USECARDATE=$result_lastjob["RPRQ_USECARDATE"];	
					$RPRQ_USECARTIME=$result_lastjob["RPRQ_USECARTIME"];					
					$LAST_RPRQ_APPROVE=$result_lastjob["RPRQ_APPROVE"];
					$RPC_SUBJECT_CON=$result_lastjob["RPC_SUBJECT_CON"];
					$RPRQ_RANKPMTYPE=$result_lastjob["RPRQ_RANKPMTYPE"];	
					$RPRQ_MILEAGEFINISH=$result_lastjob["RPRQ_MILEAGEFINISH"];
					$RPRQ_NUMBER=$result_lastjob["RPRQ_NUMBER"];	
					
				if(isset($LAST_RPRQ_ID)){
					$stmt_emp_approve = "SELECT * FROM vwEMPLOYEE WHERE PersonCode = ?";
					$params_emp_approve = array($LAST_RPRQ_APPROVE);	
					$query_emp_approve = sqlsrv_query( $conn, $stmt_emp_approve, $params_emp_approve);	
					$result_emp_approve = sqlsrv_fetch_array($query_emp_approve, SQLSRV_FETCH_ASSOC);			
						$APPROVE_NAME=$result_emp_approve["nameT"];
					
					$stmt_linenoti = "SELECT * FROM SETTING WHERE ST_TYPE = '28' AND ST_STATUS = 'Y' AND ST_AREA = '$SESSION_AREA'";
					$query_linenoti = sqlsrv_query( $conn, $stmt_linenoti);	
					$no=0;
					while($result_linenoti = sqlsrv_fetch_array($query_linenoti)){	
						$no++;
						$ST_DETAIL=$result_linenoti["ST_DETAIL"];
						$TK_RQRP="$ST_DETAIL";  

						if($LAST_RPRQ_WORKTYPE=='BM'){
							$NOTI_LINE1=" 🔵 ยืนยันใบแจ้งซ่อม BM ($RPC_SUBJECT_CON)"."\n";
							$NOTI_LINE2="ID : ".$LAST_RPRQ_ID.", ลำดับ : ".$RPRQ_NUMBER.""."\n";
							$NOTI_LINE3="ทะเบียน(หัว) : ".$RPRQ_REGISHEAD.""."\n";
							$NOTI_LINE4="ทะเบียน(หาง) : ".$RPRQ_REGISTAIL.""."\n";
							$NOTI_LINE5="วันที่/เวลา แจ้งซ่อม : ".$RPRQ_REQUESTCARDATE.' '.$RPRQ_REQUESTCARTIME.""."\n";
							$NOTI_LINE6="วันที่/เวลา ต้องการใช้รถ : ".$RPRQ_USECARDATE.' '.$RPRQ_USECARTIME.""."\n";
							$NOTI_LINE7="ผู้ตรวจสอบ : ".$APPROVE_NAME."";   
							$MESSAGE_NOTI_LINE=$NOTI_LINE1.$NOTI_LINE2.$NOTI_LINE3.$NOTI_LINE4.$NOTI_LINE5.$NOTI_LINE6.$NOTI_LINE7;	
						}else if($LAST_RPRQ_WORKTYPE=='PM'){
							$NOTI_LINE1=" 🔵 ยืนยันใบแจ้งซ่อม PM ($RPRQ_RANKPMTYPE)"."\n";
							$NOTI_LINE2="ID : ".$LAST_RPRQ_ID.", ถึงระยะเลขไมล์ : ".$RPRQ_MILEAGEFINISH.""."\n";
							$NOTI_LINE3="ทะเบียน(หัว) : ".$RPRQ_REGISHEAD.""."\n";
							$NOTI_LINE4="ทะเบียน(หาง) : ".$RPRQ_REGISTAIL.""."\n";
							$NOTI_LINE5="วันที่/เวลา แจ้งซ่อม : ".$RPRQ_REQUESTCARDATE.' '.$RPRQ_REQUESTCARTIME.""."\n";
							$NOTI_LINE6="วันที่/เวลา ต้องการใช้รถ : ".$RPRQ_USECARDATE.' '.$RPRQ_USECARTIME.""."\n";
							$NOTI_LINE7="ผู้ตรวจสอบ : ".$APPROVE_NAME."";   
							$MESSAGE_NOTI_LINE=$NOTI_LINE1.$NOTI_LINE2.$NOTI_LINE3.$NOTI_LINE4.$NOTI_LINE5.$NOTI_LINE6.$NOTI_LINE7;	
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
					
				}
			// แจ้งเตือนไลน์--------------------------------------------------------------------------------
		}
		if( $stmt1 === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "Approve เรียบร้อย";	
		}
	}
	
	if($type_chk=="5"){ //กรณีไม่อนุม้ติรายการแจ้งซ่อม
		$RPRQ_STATUSREQUEST = "ไม่อนุมัติ";
        for($epy=1;$epy<=$_POST["hdnLine"];$epy++){
			$sql1 = "UPDATE REPAIRREQUEST SET 
				RPRQ_STATUSREQUEST = '".$RPRQ_STATUSREQUEST."' ,
				RPRQ_REMARK = '".$_POST["RPRQ_REMARK$epy"]."',
				RPRQ_APPROVE = '".$RPRQ_APPROVE."' ,
				RPRQ_APPROVE_DATE = '".$RPRQ_APPROVE_DATE."'
				WHERE RPRQ_ID = '".$_POST["RPRQ_ID$epy"]."'";
			$stmt1 = sqlsrv_query( $conn, $sql1);
			$RPRQ_ID=$_POST["RPRQ_ID$epy"];	
			$RPRQ_REMARK=$_POST["RPRQ_REMARK$epy"];	

			// แจ้งเตือนไลน์--------------------------------------------------------------------------------
				$stmt_lastjob = "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_ID = ?";
				$params_lastjob = array($RPRQ_ID);	
				$query_lastjob = sqlsrv_query( $conn, $stmt_lastjob, $params_lastjob);	
				$result_lastjob = sqlsrv_fetch_array($query_lastjob, SQLSRV_FETCH_ASSOC);
					$LAST_RPRQ_ID=$result_lastjob["RPRQ_ID"];	
					$LAST_RPRQ_CODE=$result_lastjob["RPRQ_CODE"];	
					$LAST_RPRQ_WORKTYPE=$result_lastjob["RPRQ_WORKTYPE"];
					$RPRQ_REGISHEAD=$result_lastjob["RPRQ_REGISHEAD"];
					$RPRQ_REGISTAIL=$result_lastjob["RPRQ_REGISTAIL"];
					$RPRQ_REQUESTCARDATE=$result_lastjob["RPRQ_REQUESTCARDATE"];
					$RPRQ_REQUESTCARTIME=$result_lastjob["RPRQ_REQUESTCARTIME"];
					$RPRQ_USECARDATE=$result_lastjob["RPRQ_USECARDATE"];	
					$RPRQ_USECARTIME=$result_lastjob["RPRQ_USECARTIME"];					
					$LAST_RPRQ_APPROVE=$result_lastjob["RPRQ_APPROVE"];
					$RPC_SUBJECT_CON=$result_lastjob["RPC_SUBJECT_CON"];
					$RPRQ_RANKPMTYPE=$result_lastjob["RPRQ_RANKPMTYPE"];	
					$RPRQ_MILEAGEFINISH=$result_lastjob["RPRQ_MILEAGEFINISH"];
					$RPRQ_NUMBER=$result_lastjob["RPRQ_NUMBER"];	
					
				if(isset($LAST_RPRQ_ID)){
					$stmt_emp_approve = "SELECT * FROM vwEMPLOYEE WHERE PersonCode = ?";
					$params_emp_approve = array($LAST_RPRQ_APPROVE);	
					$query_emp_approve = sqlsrv_query( $conn, $stmt_emp_approve, $params_emp_approve);	
					$result_emp_approve = sqlsrv_fetch_array($query_emp_approve, SQLSRV_FETCH_ASSOC);			
						$APPROVE_NAME=$result_emp_approve["nameT"];
					
					$stmt_linenoti = "SELECT * FROM SETTING WHERE ST_TYPE = '28' AND ST_STATUS = 'Y' AND ST_AREA = '$SESSION_AREA'";
					$query_linenoti = sqlsrv_query( $conn, $stmt_linenoti);	
					$no=0;
					while($result_linenoti = sqlsrv_fetch_array($query_linenoti)){	
						$no++;
						$ST_DETAIL=$result_linenoti["ST_DETAIL"];
						$TK_RQRP="$ST_DETAIL";  

						if($LAST_RPRQ_WORKTYPE=='BM'){
							$NOTI_LINE1=" ❌ ยกเลิกใบแจ้งซ่อม BM ($RPC_SUBJECT_CON)"."\n";
							$NOTI_LINE2="ID : ".$LAST_RPRQ_ID.", ลำดับ : ".$RPRQ_NUMBER.""."\n";
							$NOTI_LINE3="ทะเบียน(หัว) : ".$RPRQ_REGISHEAD.""."\n";
							$NOTI_LINE4="ทะเบียน(หาง) : ".$RPRQ_REGISTAIL.""."\n";
							$NOTI_LINE5="วันที่/เวลา แจ้งซ่อม : ".$RPRQ_REQUESTCARDATE.' '.$RPRQ_REQUESTCARTIME.""."\n";
							$NOTI_LINE6="วันที่/เวลา ต้องการใช้รถ : ".$RPRQ_USECARDATE.' '.$RPRQ_USECARTIME.""."\n";
							$NOTI_LINE7="สาเหตุ : ".$RPRQ_REMARK.""."\n";
							$NOTI_LINE8="ผู้ยกเลิก : ".$APPROVE_NAME."";   
							$MESSAGE_NOTI_LINE=$NOTI_LINE1.$NOTI_LINE2.$NOTI_LINE3.$NOTI_LINE4.$NOTI_LINE5.$NOTI_LINE6.$NOTI_LINE7.$NOTI_LINE8;	
						}else if($LAST_RPRQ_WORKTYPE=='PM'){
							$NOTI_LINE1=" ❌ ยกเลิกใบแจ้งซ่อม PM ($RPRQ_RANKPMTYPE)"."\n";
							$NOTI_LINE2="ID : ".$LAST_RPRQ_ID.", ถึงระยะเลขไมล์ : ".$RPRQ_MILEAGEFINISH.""."\n";
							$NOTI_LINE3="ทะเบียน(หัว) : ".$RPRQ_REGISHEAD.""."\n";
							$NOTI_LINE4="ทะเบียน(หาง) : ".$RPRQ_REGISTAIL.""."\n";
							$NOTI_LINE5="วันที่/เวลา แจ้งซ่อม : ".$RPRQ_REQUESTCARDATE.' '.$RPRQ_REQUESTCARTIME.""."\n";
							$NOTI_LINE6="วันที่/เวลา ต้องการใช้รถ : ".$RPRQ_USECARDATE.' '.$RPRQ_USECARTIME.""."\n";
							$NOTI_LINE7="สาเหตุ : ".$RPRQ_REMARK.""."\n";
							$NOTI_LINE8="ผู้ยกเลิก : ".$APPROVE_NAME."";   
							$MESSAGE_NOTI_LINE=$NOTI_LINE1.$NOTI_LINE2.$NOTI_LINE3.$NOTI_LINE4.$NOTI_LINE5.$NOTI_LINE6.$NOTI_LINE7.$NOTI_LINE8;	
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
					
				}
			// แจ้งเตือนไลน์--------------------------------------------------------------------------------

			if(isset($LAST_RPRQ_ID)){
				$RPRQ_NAP_OLD_CODE = $LAST_RPRQ_CODE;
				$RPRQ_NAP_PROCESSBY = $_SESSION["AD_PERSONCODE"];
				$RPRQ_NAP_PROCESSDATE = date("Y-m-d H:i:s");
				$sql_nap = "INSERT INTO REPAIRREQUEST_NONAPPROVE (
				RPRQ_NAP_OLD_CODE,
				RPRQ_NAP_STATUS,
				RPRQ_NAP_PROCESSBY,
				RPRQ_NAP_PROCESSDATE) VALUES (?,?,?,?)";
				$params_nap = array(
					$RPRQ_NAP_OLD_CODE,
					'WAIT',
					$RPRQ_NAP_PROCESSBY,
					$RPRQ_NAP_PROCESSDATE);
				$stmt_nap = sqlsrv_query( $conn, $sql_nap, $params_nap);	
			}
		}
		if( $stmt1 === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "Reject เรียบร้อย";	
		}
	}

?>