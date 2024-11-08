<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_REQUEST);
	// echo"</pre>";
	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// echo"<pre>";
	// print_r($_FILES);
	// echo"</pre>";
	// exit();
	
	if(isset($_POST["proc"])){
		if($_POST["proc"]=="add"){
			$proc="add";
		}else if($_POST["proc"]=="copy"){
			$proc="add";
			$copy="copy";
		}else if($_POST["proc"]=="edit"){
			$proc="edit";
		}else if($_POST["proc"]=="delete"){
			$proc="delete";
		}else if($_POST["proc"]=="deleteimageonly"){
			$proc="deleteimageonly";
		}else if($_POST["proc"]=="send_plan"){
			$proc="send_plan";
		}
	}
	
	if(isset($_GET["proc"])){
		if($_GET["proc"]=="add"){
			$proc="add";
		}else if($_GET["proc"]=="copy"){
			$proc="add";
			$copy="copy";
		}else if($_GET["proc"]=="edit"){
			$proc="edit";
		}else if($_GET["proc"]=="delete"){
			$proc="delete";
		}else if($_GET["proc"]=="deleteimageonly"){
			$proc="deleteimageonly";
		}
	}
	// $proc=$_POST["proc"].$_GET["proc"];
	if(isset($_GET["id"])){
		$id=$_GET["id"];	
	}
	$SESSION_AREA = $_SESSION["AD_AREA"];

	// if(isset($_POST)) {
	// 	echo json_encode($_POST);
	// }

	if($proc=="add"){
		// $RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPRQ_WORKTYPE = $_POST["RPRQ_WORKTYPE"];
		$RPRQ_REGISHEAD = $_POST["VEHICLEREGISNUMBER1"];
		$RPRQ_REGISTAIL = $_POST["VEHICLEREGISNUMBER2"];
		$RPRQ_CARNAMEHEAD = $_POST["THAINAME1"];
		$RPRQ_CARNAMETAIL = $_POST["THAINAME2"];		
		$RPRQ_CARTYPE = $_POST["RPRQ_CARTYPE"];	
		$RPRQ_LINEOFWORK = $_POST["AFFCOMPANY"];
		$RPRQ_MILEAGELAST = $_POST["MAXMILEAGENUMBER"];
		$RPRQ_MILEAGEFINISH = $_POST["RPRQ_MILEAGEFINISH"];
		$RPRQ_RANKPMTYPE = $_POST["RPRQ_RANKPMTYPE"];
		$RPRQ_RANKKILOMETER = $_POST["RPRQ_RANKKILOMETER"];
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
		$RPRQ_REQUESTBY_SQ = $_POST["RPRQ_CREATENAME"];
		$RPRQ_CREATEDATE_REQUEST = $_POST["RPRQ_CREATEDATE_REQUEST"];
		$RPRQ_AREA = $_POST["RPRQ_AREA"];
		$RPRQ_STATUS = 'Y';
		if($RPRQ_TYPECUSTOMER=='cusin'){
			$RPRQ_STATUSREQUEST = 'รอส่งแผน';
		}else{
			$RPRQ_STATUSREQUEST = 'รอตรวจสอบ';
		}
		$RPRQ_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$RPRQ_CREATEDATE = date("Y-m-d H:i:s");
		$RPCIM_PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$RPCIM_PROCESSDATE = date("Y-m-d H:i:s");

		// $n=6;
		function RandNum($n) {
			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';      
			for ($i = 0; $i < $n; $i++) {
				$index = rand(0, strlen($characters) - 1);
				$randomString .= $characters[$index];
			}      
			return $randomString;
		} 
		for($inum=0; $inum < count($_POST["RPM_NATUREREPAIR"]); $inum++){
			if($_POST["RPM_NATUREREPAIR"][$inum] != "" ){	
				 
				// $rand="RQRP_".RandNum('6');
				// $RPRQ_CODE = $rand;
				$TYPEREPAIRWORK = $_POST["TYPEREPAIRWORK"][$inum];  					
				$RPRQ_CODE = $_POST["RPRQ_CODE"][$inum];  
				if($inum==0){
					$inum1=$inum+1;
				}else if($inum>0){
					$inum1=$inum+1;
				}
				if(isset($_POST['RPRQ_NUMBER'])){					
					$RPSPP_CODE = $_POST["RPSPP_CODE"];  
					$RPRQ_NUMBER = $_POST['RPRQ_NUMBER'].'-'.$inum1;
				}else{			
					$RPSPP_CODE = ''; 
					$RPRQ_NUMBER = "BM-".$inum1;
				}
				$RPC_SUBJECT = $_POST["RPM_NATUREREPAIR"][$inum];  
				$RPC_DETAIL = $_POST["RPC_DETAIL"][$inum];  					
				$RPC_IMAGES = $RPRQ_CODE.'-'.$_FILES["RPC_IMAGES"]["name"][$inum];		

				// if(move_uploaded_file($_FILES["RPC_IMAGES"]["tmp_name"][$inum],$path."../uploads/requestrepair/".$RPC_IMAGES)){					
					// echo "Copy/Upload Complete<br>";	
				// }	
					
				$sql = "INSERT INTO REPAIRREQUEST (RPRQ_CODE,RPRQ_WORKTYPE,RPRQ_NUMBER,RPRQ_REGISHEAD,RPRQ_REGISTAIL,RPRQ_CARNAMEHEAD,RPRQ_CARNAMETAIL,RPRQ_CARTYPE,RPRQ_LINEOFWORK,RPRQ_MILEAGELAST,RPRQ_MILEAGEFINISH,RPRQ_RANKPMTYPE,RPRQ_RANKKILOMETER,RPRQ_PMLASTDATE,RPRQ_TIMEREPAIR,RPRQ_SCHEDULEDDATE,RPRQ_REQUESTCARDATE,RPRQ_REQUESTCARTIME,RPRQ_USECARDATE,RPRQ_USECARTIME,RPRQ_PRODUCTINCAR,RPRQ_NATUREREPAIR,RPRQ_TYPECUSTOMER,RPRQ_COMPANYCASH,RPRQ_REQUESTBY,RPRQ_REQUESTBY_SQ,RPRQ_CREATEDATE_REQUEST,RPRQ_AREA,RPRQ_STATUS,RPRQ_STATUSREQUEST,RPRQ_CREATEBY,RPRQ_CREATEDATE,RPRQ_SPAREPART,RPRQ_TYPEREPAIRWORK) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				$params = array($RPRQ_CODE,$RPRQ_WORKTYPE,$RPRQ_NUMBER,$RPRQ_REGISHEAD,$RPRQ_REGISTAIL,$RPRQ_CARNAMEHEAD,$RPRQ_CARNAMETAIL,$RPRQ_CARTYPE,$RPRQ_LINEOFWORK,$RPRQ_MILEAGELAST,$RPRQ_MILEAGEFINISH,$RPRQ_RANKPMTYPE,$RPRQ_RANKKILOMETER,$RPRQ_PMLASTDATE,$RPRQ_TIMEREPAIR,$RPRQ_SCHEDULEDDATE,$RPRQ_REQUESTCARDATE,$RPRQ_REQUESTCARTIME,$RPRQ_USECARDATE,$RPRQ_USECARTIME,$RPRQ_PRODUCTINCAR,$RPRQ_NATUREREPAIR,$RPRQ_TYPECUSTOMER,$RPRQ_COMPANYCASH,$RPRQ_REQUESTBY,$RPRQ_REQUESTBY_SQ,$RPRQ_CREATEDATE_REQUEST,$RPRQ_AREA,$RPRQ_STATUS,$RPRQ_STATUSREQUEST,$RPRQ_CREATEBY,$RPRQ_CREATEDATE,$RPSPP_CODE,$TYPEREPAIRWORK);	
				$stmt = sqlsrv_query( $conn, $sql, $params);			

				$sql1 = "INSERT INTO REPAIRCAUSE (RPRQ_CODE,RPC_SUBJECT,RPC_DETAIL) VALUES (?,?,?)";
				$params1 = array($RPRQ_CODE,$RPC_SUBJECT,$RPC_DETAIL);		
				$stmt1 = sqlsrv_query( $conn, $sql1, $params1);				

				if($RPC_SUBJECT=="EL"){
					$RPC_SUBJECT_NAME = "ระบบไฟ";
				}else if($RPC_SUBJECT=="TU"){
					$RPC_SUBJECT_NAME = "ยาง-ช่วงล่าง";
				}else if($RPC_SUBJECT=="BD"){
					$RPC_SUBJECT_NAME = "โครงสร้าง";
				}else if($RPC_SUBJECT=="EG"){
					$RPC_SUBJECT_NAME = "เครื่องยนต์";
				}

				$sql2 = "INSERT INTO REPAIRESTIMATE (RPRQ_CODE,RPETM_TYPEREPAIR,RPETM_NATURE,RPETM_PROCESSBY,RPETM_PROCESSDATE) VALUES (?,?,?,?,?)";
				$params2 = array($RPRQ_CODE,'BM',$RPC_SUBJECT_NAME,$RPRQ_CREATEBY,$RPRQ_CREATEDATE);		
				$stmt2 = sqlsrv_query( $conn, $sql2, $params2);	

				if($RPRQ_TYPECUSTOMER=='cusout'){
					$sql3 = "INSERT INTO TEST_MILEAGE_OUT (VEHICLEREGISNUMBER,JOBNO,MILEAGENUMBER,MILEAGETYPE,REMARK,ACTIVESTATUS,CREATEBY,CREATEDATE) VALUES (?,?,?,?,?,?,?,?)";
					$params3 = array($RPRQ_REGISHEAD,'external_customers',$RPRQ_MILEAGELAST,'MILEAGEEND',$RPRQ_COMPANYCASH,'1',$RPRQ_CREATEBY,$RPRQ_CREATEDATE);		
					$stmt3 = sqlsrv_query( $conn, $sql3, $params3);	

					$check = "SELECT * FROM TEST_MILEAGE_PM WHERE VEHICLEREGISNUMBER = '$RPRQ_REGISHEAD'";
					$querycheck = sqlsrv_query( $conn, $check);
					$resultcheck = sqlsrv_fetch_array($querycheck, SQLSRV_FETCH_ASSOC);
					$chk1null = $resultcheck['VEHICLEREGISNUMBER'];
					if($chk1null != ""){    			
						$sql4 = "UPDATE TEST_MILEAGE_PM SET MILEAGENUMBER = ?,MODIFIEDBY = ?,MODIFIEDDATE = ? WHERE VEHICLEREGISNUMBER = ? ";
						$params4 = array($RPRQ_MILEAGELAST,$RPRQ_CREATEBY,$RPRQ_CREATEDATE,$RPRQ_REGISHEAD);
						$stmt4 = sqlsrv_query( $conn, $sql4, $params4);
					}else if($chk1null == ""){      
						$sql5 = "INSERT INTO TEST_MILEAGE_PM (VEHICLEREGISNUMBER,MILEAGENUMBER,MILEAGETYPE,LINEOFWORK,ACTIVESTATUS,CREATEBY,CREATEDATE) VALUES (?,?,?,?,?,?,?)";
						$params5 = array($RPRQ_REGISHEAD,$RPRQ_MILEAGELAST,'MILEAGEEND','OTHER','1',$RPRQ_CREATEBY,$RPRQ_CREATEDATE);
						$stmt5 = sqlsrv_query( $conn, $sql5, $params5);	
					}		
				}

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
						$NOTI_LINE1=" 🔴 แจ้งซ่อม BM ($RPC_SUBJECT_NAME)"."\n";
						$NOTI_LINE2="ID : ".$LAST_RPRQ_ID.", ลำดับ : ".$RPRQ_NUMBER.""."\n";
						$NOTI_LINE3="ทะเบียน(หัว) : ".$RPRQ_REGISHEAD."\n";
						$NOTI_LINE4="ชื่อรถ(หัว) : ".$RPRQ_CARNAMEHEAD."\n";
						$NOTI_LINE5="ทะเบียน(หาง) : ".$RPRQ_REGISTAIL."\n";
						$NOTI_LINE6="ชื่อรถ(หาง) : ".$RPRQ_CARNAMETAIL."\n";
						$NOTI_LINE7="ปัญหา : $RPC_DETAIL"."\n";
						$NOTI_LINE8="วันที่/เวลา แจ้งซ่อม : ".$datetimeRequest_in.""."\n";
						$NOTI_LINE9="วันที่/เวลา ต้องการใช้รถ : ".$datetimeRequest_out.""."\n";
						$NOTI_LINE10="ผู้แจ้ง : ".$RPRQ_REQUESTBY."";   
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
					}
				// แจ้งเตือนไลน์--------------------------------------------------------------------------------
			}
		}	
		// เพิ่มรูปหลายรูป หลายลักษณะ
		for($icode=0; $icode < count($_POST["RPRQ_CODE"]); $icode++){
			if($_POST["RPM_NATUREREPAIR"][$icode] != "" ){				
				for( $img1=0; $img1<count( $_FILES["RPC_IMAGES_img1"]["tmp_name"] ); $img1++ ) {
					$RPC_SUBJECT_img1 = $_POST["RPM_NATUREREPAIR"][0];  
					$RPRQ_CODE_img1 = $_POST["RPRQ_CODE"][0];  
					$rand_img1="RPCIM_".RandNum('15');
					$RENAME_img1 = $rand_img1;
					$ext_img1 = pathinfo($_FILES["RPC_IMAGES_img1"]["name"][$img1], PATHINFO_EXTENSION);
					$RPCIM_IMAGES_img1 = $RENAME_img1.".".$ext_img1;
					if( move_uploaded_file( $_FILES["RPC_IMAGES_img1"]["tmp_name"][$img1], $path."../uploads/requestrepair/".$RPCIM_IMAGES_img1) ) {	
						$sql_img1 = "INSERT INTO REPAIRCAUSE_IMAGE (RPRQ_CODE,RPC_SUBJECT,RPCIM_GROUP,RPCIM_IMAGES,RPCIM_PROCESSBY,RPCIM_PROCESSDATE) VALUES (?,?,?,?,?,?)";
						$params_img1 = array($RPRQ_CODE_img1,$RPC_SUBJECT_img1,$RPCIM_GROUP,$RPCIM_IMAGES_img1,$RPCIM_PROCESSBY,$RPCIM_PROCESSDATE);
						$stmt_img1 = sqlsrv_query( $conn, $sql_img1, $params_img1);	
					}
				}	
				for( $img2=0; $img2<count( $_FILES["RPC_IMAGES_img2"]["tmp_name"] ); $img2++ ) {
					$RPC_SUBJECT_img2 = $_POST["RPM_NATUREREPAIR"][1];  
					$RPRQ_CODE_img2 = $_POST["RPRQ_CODE"][1];  
					$rand_img2="RPCIM_".RandNum('15');
					$RENAME_img2 = $rand_img2;
					$ext_img2 = pathinfo($_FILES["RPC_IMAGES_img2"]["name"][$img2], PATHINFO_EXTENSION);
					$RPCIM_IMAGES_img2 = $RENAME_img2.".".$ext_img2;
					if( move_uploaded_file( $_FILES["RPC_IMAGES_img2"]["tmp_name"][$img2], $path."../uploads/requestrepair/".$RPCIM_IMAGES_img2) ) {	
						$sql_img2 = "INSERT INTO REPAIRCAUSE_IMAGE (RPRQ_CODE,RPC_SUBJECT,RPCIM_GROUP,RPCIM_IMAGES,RPCIM_PROCESSBY,RPCIM_PROCESSDATE) VALUES (?,?,?,?,?,?)";
						$params_img2 = array($RPRQ_CODE_img2,$RPC_SUBJECT_img2,$RPCIM_GROUP,$RPCIM_IMAGES_img2,$RPCIM_PROCESSBY,$RPCIM_PROCESSDATE);
						$stmt_img2 = sqlsrv_query( $conn, $sql_img2, $params_img2);	
					}
				}	
				for( $img3=0; $img3<count( $_FILES["RPC_IMAGES_img3"]["tmp_name"] ); $img3++ ) {
					$RPC_SUBJECT_img3 = $_POST["RPM_NATUREREPAIR"][2];  
					$RPRQ_CODE_img3 = $_POST["RPRQ_CODE"][2];  
					$rand_img3="RPCIM_".RandNum('15');
					$RENAME_img3 = $rand_img3;
					$ext_img3 = pathinfo($_FILES["RPC_IMAGES_img3"]["name"][$img3], PATHINFO_EXTENSION);
					$RPCIM_IMAGES_img3 = $RENAME_img3.".".$ext_img3;
					if( move_uploaded_file( $_FILES["RPC_IMAGES_img3"]["tmp_name"][$img3], $path."../uploads/requestrepair/".$RPCIM_IMAGES_img3) ) {	
						$sql_img3 = "INSERT INTO REPAIRCAUSE_IMAGE (RPRQ_CODE,RPC_SUBJECT,RPCIM_GROUP,RPCIM_IMAGES,RPCIM_PROCESSBY,RPCIM_PROCESSDATE) VALUES (?,?,?,?,?,?)";
						$params_img3 = array($RPRQ_CODE_img3,$RPC_SUBJECT_img3,$RPCIM_GROUP,$RPCIM_IMAGES_img3,$RPCIM_PROCESSBY,$RPCIM_PROCESSDATE);
						$stmt_img3 = sqlsrv_query( $conn, $sql_img3, $params_img3);	
					}
				}	
				for( $img4=0; $img4<count( $_FILES["RPC_IMAGES_img4"]["tmp_name"] ); $img4++ ) {
					$RPC_SUBJECT_img4 = $_POST["RPM_NATUREREPAIR"][3];  
					$RPRQ_CODE_img4 = $_POST["RPRQ_CODE"][3];  
					$rand_img4="RPCIM_".RandNum('15');
					$RENAME_img4 = $rand_img4;
					$ext_img4 = pathinfo($_FILES["RPC_IMAGES_img4"]["name"][$img4], PATHINFO_EXTENSION);
					$RPCIM_IMAGES_img4 = $RENAME_img4.".".$ext_img4;
					if( move_uploaded_file( $_FILES["RPC_IMAGES_img4"]["tmp_name"][$img4], $path."../uploads/requestrepair/".$RPCIM_IMAGES_img4) ) {	
						$sql_img4 = "INSERT INTO REPAIRCAUSE_IMAGE (RPRQ_CODE,RPC_SUBJECT,RPCIM_GROUP,RPCIM_IMAGES,RPCIM_PROCESSBY,RPCIM_PROCESSDATE) VALUES (?,?,?,?,?,?)";
						$params_img4 = array($RPRQ_CODE_img4,$RPC_SUBJECT_img4,$RPCIM_GROUP,$RPCIM_IMAGES_img4,$RPCIM_PROCESSBY,$RPCIM_PROCESSDATE);
						$stmt_img4 = sqlsrv_query( $conn, $sql_img4, $params_img4);	
					}
				}	
				for( $img5=0; $img5<count( $_FILES["RPC_IMAGES_img5"]["tmp_name"] ); $img5++ ) {
					$RPC_SUBJECT_img5 = $_POST["RPM_NATUREREPAIR"][4];  
					$RPRQ_CODE_img5 = $_POST["RPRQ_CODE"][4];  
					$rand_img5="RPCIM_".RandNum('15');
					$RENAME_img5 = $rand_img5;
					$ext_img5 = pathinfo($_FILES["RPC_IMAGES_img5"]["name"][$img5], PATHINFO_EXTENSION);
					$RPCIM_IMAGES_img5 = $RENAME_img5.".".$ext_img5;
					if( move_uploaded_file( $_FILES["RPC_IMAGES_img5"]["tmp_name"][$img5], $path."../uploads/requestrepair/".$RPCIM_IMAGES_img5) ) {	
						$sql_img5 = "INSERT INTO REPAIRCAUSE_IMAGE (RPRQ_CODE,RPC_SUBJECT,RPCIM_GROUP,RPCIM_IMAGES,RPCIM_PROCESSBY,RPCIM_PROCESSDATE) VALUES (?,?,?,?,?,?)";
						$params_img5 = array($RPRQ_CODE_img5,$RPC_SUBJECT_img5,$RPCIM_GROUP,$RPCIM_IMAGES_img5,$RPCIM_PROCESSBY,$RPCIM_PROCESSDATE);
						$stmt_img5 = sqlsrv_query( $conn, $sql_img5, $params_img5);	
					}
				}	
				for( $img6=0; $img6<count( $_FILES["RPC_IMAGES_img6"]["tmp_name"] ); $img6++ ) {
					$RPC_SUBJECT_img6 = $_POST["RPM_NATUREREPAIR"][5];  
					$RPRQ_CODE_img6 = $_POST["RPRQ_CODE"][5];  
					$rand_img6="RPCIM_".RandNum('15');
					$RENAME_img6 = $rand_img6;
					$ext_img6 = pathinfo($_FILES["RPC_IMAGES_img6"]["name"][$img6], PATHINFO_EXTENSION);
					$RPCIM_IMAGES_img6 = $RENAME_img6.".".$ext_img6;
					if( move_uploaded_file( $_FILES["RPC_IMAGES_img6"]["tmp_name"][$img6], $path."../uploads/requestrepair/".$RPCIM_IMAGES_img6) ) {	
						$sql_img6 = "INSERT INTO REPAIRCAUSE_IMAGE (RPRQ_CODE,RPC_SUBJECT,RPCIM_GROUP,RPCIM_IMAGES,RPCIM_PROCESSBY,RPCIM_PROCESSDATE) VALUES (?,?,?,?,?,?)";
						$params_img6 = array($RPRQ_CODE_img6,$RPC_SUBJECT_img6,$RPCIM_GROUP,$RPCIM_IMAGES_img6,$RPCIM_PROCESSBY,$RPCIM_PROCESSDATE);
						$stmt_img6 = sqlsrv_query( $conn, $sql_img6, $params_img6);	
					}
				}	
				for( $img7=0; $img7<count( $_FILES["RPC_IMAGES_img7"]["tmp_name"] ); $img7++ ) {
					$RPC_SUBJECT_img7 = $_POST["RPM_NATUREREPAIR"][6];  
					$RPRQ_CODE_img7 = $_POST["RPRQ_CODE"][6];  
					$rand_img7="RPCIM_".RandNum('15');
					$RENAME_img7 = $rand_img7;
					$ext_img7 = pathinfo($_FILES["RPC_IMAGES_img7"]["name"][$img7], PATHINFO_EXTENSION);
					$RPCIM_IMAGES_img7 = $RENAME_img7.".".$ext_img7;
					if( move_uploaded_file( $_FILES["RPC_IMAGES_img7"]["tmp_name"][$img7], $path."../uploads/requestrepair/".$RPCIM_IMAGES_img7) ) {	
						$sql_img7 = "INSERT INTO REPAIRCAUSE_IMAGE (RPRQ_CODE,RPC_SUBJECT,RPCIM_GROUP,RPCIM_IMAGES,RPCIM_PROCESSBY,RPCIM_PROCESSDATE) VALUES (?,?,?,?,?,?)";
						$params_img7 = array($RPRQ_CODE_img7,$RPC_SUBJECT_img7,$RPCIM_GROUP,$RPCIM_IMAGES_img7,$RPCIM_PROCESSBY,$RPCIM_PROCESSDATE);
						$stmt_img7 = sqlsrv_query( $conn, $sql_img7, $params_img7);	
					}
				}	
				for( $img8=0; $img8<count( $_FILES["RPC_IMAGES_img8"]["tmp_name"] ); $img8++ ) {
					$RPC_SUBJECT_img8 = $_POST["RPM_NATUREREPAIR"][7];  
					$RPRQ_CODE_img8 = $_POST["RPRQ_CODE"][7];  
					$rand_img8="RPCIM_".RandNum('15');
					$RENAME_img8 = $rand_img8;
					$ext_img8 = pathinfo($_FILES["RPC_IMAGES_img8"]["name"][$img8], PATHINFO_EXTENSION);
					$RPCIM_IMAGES_img8 = $RENAME_img8.".".$ext_img8;
					if( move_uploaded_file( $_FILES["RPC_IMAGES_img8"]["tmp_name"][$img8], $path."../uploads/requestrepair/".$RPCIM_IMAGES_img8) ) {	
						$sql_img8 = "INSERT INTO REPAIRCAUSE_IMAGE (RPRQ_CODE,RPC_SUBJECT,RPCIM_GROUP,RPCIM_IMAGES,RPCIM_PROCESSBY,RPCIM_PROCESSDATE) VALUES (?,?,?,?,?,?)";
						$params_img8 = array($RPRQ_CODE_img8,$RPC_SUBJECT_img8,$RPCIM_GROUP,$RPCIM_IMAGES_img8,$RPCIM_PROCESSBY,$RPCIM_PROCESSDATE);
						$stmt_img8 = sqlsrv_query( $conn, $sql_img8, $params_img8);	
					}
				}	
				for( $img9=0; $img9<count( $_FILES["RPC_IMAGES_img9"]["tmp_name"] ); $img9++ ) {
					$RPC_SUBJECT_img9 = $_POST["RPM_NATUREREPAIR"][8];  
					$RPRQ_CODE_img9 = $_POST["RPRQ_CODE"][8];  
					$rand_img9="RPCIM_".RandNum('15');
					$RENAME_img9 = $rand_img9;
					$ext_img9 = pathinfo($_FILES["RPC_IMAGES_img9"]["name"][$img9], PATHINFO_EXTENSION);
					$RPCIM_IMAGES_img9 = $RENAME_img9.".".$ext_img9;
					if( move_uploaded_file( $_FILES["RPC_IMAGES_img9"]["tmp_name"][$img9], $path."../uploads/requestrepair/".$RPCIM_IMAGES_img9) ) {	
						$sql_img9 = "INSERT INTO REPAIRCAUSE_IMAGE (RPRQ_CODE,RPC_SUBJECT,RPCIM_GROUP,RPCIM_IMAGES,RPCIM_PROCESSBY,RPCIM_PROCESSDATE) VALUES (?,?,?,?,?,?)";
						$params_img9 = array($RPRQ_CODE_img9,$RPC_SUBJECT_img9,$RPCIM_GROUP,$RPCIM_IMAGES_img9,$RPCIM_PROCESSBY,$RPCIM_PROCESSDATE);
						$stmt_img9 = sqlsrv_query( $conn, $sql_img9, $params_img9);	
					}
				}	
				for( $img10=0; $img10<count( $_FILES["RPC_IMAGES_img10"]["tmp_name"] ); $img10++ ) {
					$RPC_SUBJECT_img10 = $_POST["RPM_NATUREREPAIR"][9];  
					$RPRQ_CODE_img10 = $_POST["RPRQ_CODE"][9];  
					$rand_img10="RPCIM_".RandNum('15');
					$RENAME_img10 = $rand_img10;
					$ext_img10 = pathinfo($_FILES["RPC_IMAGES_img10"]["name"][$img10], PATHINFO_EXTENSION);
					$RPCIM_IMAGES_img10 = $RENAME_img10.".".$ext_img10;
					if( move_uploaded_file( $_FILES["RPC_IMAGES_img10"]["tmp_name"][$img10], $path."../uploads/requestrepair/".$RPCIM_IMAGES_img10) ) {	
						$sql_img10 = "INSERT INTO REPAIRCAUSE_IMAGE (RPRQ_CODE,RPC_SUBJECT,RPCIM_GROUP,RPCIM_IMAGES,RPCIM_PROCESSBY,RPCIM_PROCESSDATE) VALUES (?,?,?,?,?,?)";
						$params_img10 = array($RPRQ_CODE_img10,$RPC_SUBJECT_img10,$RPCIM_GROUP,$RPCIM_IMAGES_img10,$RPCIM_PROCESSBY,$RPCIM_PROCESSDATE);
						$stmt_img10 = sqlsrv_query( $conn, $sql_img10, $params_img10);	
					}
				}	
			}
		}
		
		if(isset($copy)){
			$RPRQ_NAP_OLD_CODE = $_POST["RPRQ_NAP_OLD_CODE"];
			$RPRQ_NAP_PROCESSBY = $_SESSION["AD_PERSONCODE"];
			$RPRQ_NAP_PROCESSDATE = date("Y-m-d H:i:s");
			
			$sql_nap = "UPDATE REPAIRREQUEST_NONAPPROVE SET 
					RPRQ_NAP_NEW_CODE = ?,
					RPRQ_NAP_PROCESSBY = ?,
					RPRQ_NAP_PROCESSDATE = ?
					WHERE RPRQ_NAP_OLD_CODE = ?";
			$params_nap = array($RPRQ_CODE,$RPRQ_NAP_PROCESSBY,$RPRQ_NAP_PROCESSDATE,$RPRQ_NAP_OLD_CODE);		
			$stmt_nap = sqlsrv_query( $conn, $sql_nap, $params_nap);		
		}

		if( ($stmt && $stmt1 && $stmt2) === false  ) {
			die( print_r( sqlsrv_errors(), true));
		}else{				
			echo "<script>";
			// echo "alert('บันทึกข้อมูลเรียบร้อยแล้ว');";
			echo "window.history.back();";
			// echo "location.href='../'";
			echo "</script>";
		}	
	};
	######################################################################################################
	if($proc=="edit"){
		$RPRQ_ID = $_POST["RPRQ_ID"];
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPRQ_REGISHEAD = $_POST["VEHICLEREGISNUMBER1"];
		$RPRQ_REGISTAIL = $_POST["VEHICLEREGISNUMBER2"];
		$RPRQ_CARNAMEHEAD = $_POST["THAINAME1"];
		$RPRQ_CARNAMETAIL = $_POST["THAINAME2"];		
		$RPRQ_CARTYPE = $_POST["RPRQ_CARTYPE"];	
		$RPRQ_LINEOFWORK = $_POST["AFFCOMPANY"];
		$RPRQ_MILEAGELAST = $_POST["MAXMILEAGENUMBER"];
		$RPRQ_MILEAGEFINISH = $_POST["RPRQ_MILEAGEFINISH"];
		$RPRQ_RANKPMTYPE = $_POST["RPRQ_RANKPMTYPE"];
		$RPRQ_RANKKILOMETER = $_POST["RPRQ_RANKKILOMETER"];
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
		$RPRQ_REQUESTBY_SQ = $_POST["RPRQ_CREATENAME"];
		$RPRQ_CREATEDATE_REQUEST = $_POST["RPRQ_CREATEDATE_REQUEST"];
		$RPRQ_EDITBY = $_SESSION["AD_PERSONCODE"];
		$RPRQ_EDITDATE = date("Y-m-d H:i:s");
		$RPCIM_PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$RPCIM_PROCESSDATE = date("Y-m-d H:i:s");
		$TYPEREPAIRWORK = $_POST["TYPEREPAIRWORK"];
	
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
				RPRQ_REQUESTBY_SQ = ?,
				RPRQ_CREATEDATE_REQUEST = ?,
				RPRQ_TYPEREPAIRWORK = ?,
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
				$RPRQ_REQUESTBY_SQ,
				$RPRQ_CREATEDATE_REQUEST, 
				$TYPEREPAIRWORK,
				$RPRQ_EDITBY, 
				$RPRQ_EDITDATE, 
				$RPRQ_CODE
		);	
		$stmt = sqlsrv_query( $conn, $sql, $params);
		
		$RPC_SUBJECT = $_POST["RPM_NATUREREPAIR"];  
		$RPC_DETAIL = $_POST["RPC_DETAIL"];  

		$sql1 = "UPDATE REPAIRCAUSE SET 
				RPC_SUBJECT = ?,
				RPC_DETAIL = ?
				WHERE RPRQ_CODE = ?";
		$params1 = array($RPC_SUBJECT,$RPC_DETAIL,$RPRQ_CODE);		
		$stmt1 = sqlsrv_query( $conn, $sql1, $params1);		

		$sql2 = "UPDATE REPAIRCAUSE_IMAGE SET RPC_SUBJECT = ? WHERE RPRQ_CODE = ?";
		$params2 = array($RPC_SUBJECT,$RPRQ_CODE);		
		$stmt2 = sqlsrv_query( $conn, $sql2, $params2);	

		// ยกเลิกใช้ หลังจากเพิ่มให้อัปโหลดได้หลายรูป
			// $imageoldfile = $_POST["imageoldfile"];  	
			// if($imageoldfile != ""){
			// 	@unlink($path."../uploads/requestrepair/".$imageoldfile);
			// 	$RPC_IMAGES = $RPRQ_CODE.'-'.$_FILES["RPC_IMAGES"]["name"];								
			// 	if(move_uploaded_file($_FILES["RPC_IMAGES"]["tmp_name"],$path."../uploads/requestrepair/".$RPC_IMAGES)){	
			// 		$sql2 = "UPDATE REPAIRCAUSE SET RPC_IMAGES = ? WHERE RPRQ_CODE = ?";
			// 		$params2 = array($RPC_IMAGES,$RPRQ_CODE);		
			// 		$stmt2 = sqlsrv_query( $conn, $sql2, $params2);					
			// 		// echo "Copy/Upload Complete<br>";
			// 	}	
			// }else{			
			// 	$RPC_IMAGES = $RPRQ_CODE.'-'.$_FILES["RPC_IMAGES"]["name"];								
			// 	if(move_uploaded_file($_FILES["RPC_IMAGES"]["tmp_name"],$path."../uploads/requestrepair/".$RPC_IMAGES)){	
			// 		$sql2 = "UPDATE REPAIRCAUSE SET RPC_IMAGES = ? WHERE RPRQ_CODE = ?";
			// 		$params2 = array($RPC_IMAGES,$RPRQ_CODE);		
			// 		$stmt2 = sqlsrv_query( $conn, $sql2, $params2);					
			// 		// echo "Copy/Upload Complete<br>";
			// 	}	
			// }
		
		function RandNum($n) {
			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';      
			for ($i = 0; $i < $n; $i++) {
				$index = rand(0, strlen($characters) - 1);
				$randomString .= $characters[$index];
			}      
			return $randomString;
		} 		
		// แก้ไขรูป เพิ่มได้หลายรูป			
		for( $img1=0; $img1<count($_FILES["RPC_IMAGES"]["tmp_name"]); $img1++ ) {    
			$rand_img1="RPCIM_".RandNum('15');
			$RENAME_img1 = $rand_img1;
			$ext_img1 = pathinfo($_FILES["RPC_IMAGES"]["name"][$img1], PATHINFO_EXTENSION);
			$RPCIM_IMAGES_img1 = $RENAME_img1.".".$ext_img1;
			if( move_uploaded_file( $_FILES["RPC_IMAGES"]["tmp_name"][$img1], $path."../uploads/requestrepair/".$RPCIM_IMAGES_img1) ) {	
				$sql_img1 = "INSERT INTO REPAIRCAUSE_IMAGE (RPRQ_CODE,RPC_SUBJECT,RPCIM_IMAGES,RPCIM_PROCESSBY,RPCIM_PROCESSDATE) VALUES (?,?,?,?,?)";
				$params_img1 = array($RPRQ_CODE,$RPC_SUBJECT,$RPCIM_IMAGES_img1,$RPCIM_PROCESSBY,$RPCIM_PROCESSDATE);
				$stmt_img1 = sqlsrv_query( $conn, $sql_img1, $params_img1);	
			}
		}	

		if($RPRQ_TYPECUSTOMER=='cusout'){
			$sql3 = "INSERT INTO TEST_MILEAGE_OUT (VEHICLEREGISNUMBER,JOBNO,MILEAGENUMBER,MILEAGETYPE,REMARK,ACTIVESTATUS,CREATEBY,CREATEDATE) VALUES (?,?,?,?,?,?,?,?)";
			$params3 = array($RPRQ_REGISHEAD,'external_customers',$RPRQ_MILEAGELAST,'MILEAGEEND',$RPRQ_COMPANYCASH,'1',$RPRQ_CREATEBY,$RPRQ_CREATEDATE);		
			$stmt3 = sqlsrv_query( $conn, $sql3, $params3);	

			$check = "SELECT * FROM TEST_MILEAGE_OUT WHERE VEHICLEREGISNUMBER = '$RPRQ_REGISHEAD'";
			$querycheck = sqlsrv_query( $conn, $check);
			$resultcheck = sqlsrv_fetch_array($querycheck, SQLSRV_FETCH_ASSOC);
			$chk1null = $resultcheck['VEHICLEREGISNUMBER'];
			if($chk1null != ""){    			
				$sql4 = "UPDATE TEST_MILEAGE_PM SET MILEAGENUMBER = ?,MODIFIEDBY = ?,MODIFIEDDATE = ? WHERE VEHICLEREGISNUMBER = ? ";
				$params4 = array($RPRQ_MILEAGELAST,$RPRQ_CREATEBY,$RPRQ_CREATEDATE,$RPRQ_REGISHEAD);
				$stmt4 = sqlsrv_query( $conn, $sql4, $params4);
			}else if($chk1null == ""){      
				$sql5 = "INSERT INTO TEST_MILEAGE_PM (VEHICLEREGISNUMBER,MILEAGENUMBER,MILEAGETYPE,LINEOFWORK,ACTIVESTATUS,CREATEBY,CREATEDATE) VALUES (?,?,?,?,?,?,?)";
				$params5 = array($RPRQ_REGISHEAD,$RPRQ_MILEAGELAST,'MILEAGEEND','OTHER','1',$RPRQ_CREATEBY,$RPRQ_CREATEDATE);
				$stmt5 = sqlsrv_query( $conn, $sql5, $params5);	
			}		
		}

		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
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
		
		$sql1 = "SELECT * FROM REPAIRCAUSE_IMAGE WHERE RPRQ_CODE = ?";
		$params1 = array($id);	
		$query1 = sqlsrv_query( $conn, $sql1, $params1);	
		// $result1 = sqlsrv_fetch_array($query1, SQLSRV_FETCH_ASSOC);
		while($result1 = sqlsrv_fetch_array($query1, SQLSRV_FETCH_ASSOC)){	
			$RPCIM_IMAGES=$result1["RPCIM_IMAGES"];		
			// Delete Old File		
			@unlink($path."../uploads/requestrepair/".$RPCIM_IMAGES);
		}
		

		if( ($stmt1 === false) ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			$sql2 = "DELETE FROM REPAIRCAUSE_IMAGE WHERE RPRQ_CODE = ? ";
			$params2 = array($id);
			$stmt2 = sqlsrv_query( $conn, $sql2, $params2);
			print "ลบข้อมูลเรียบร้อยแล้ว";	
		}		
	};	
	######################################################################################################
	if($proc=="deleteimageonly"){
		
		$RPRQ_CODE=$_GET["id"];
		$SUBJECT=$_GET["subject"];
		$RPCIM_IMAGES=$_GET["image"];
		@unlink($path."../uploads/requestrepair/".$RPCIM_IMAGES);

		$sql = "DELETE FROM REPAIRCAUSE_IMAGE WHERE RPRQ_CODE=? AND RPC_SUBJECT=? AND RPCIM_IMAGES=?";
		$params = array($RPRQ_CODE,$SUBJECT,$RPCIM_IMAGES);
		$stmt = sqlsrv_query( $conn, $sql, $params);

		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "ลบข้อมูลเรียบร้อยแล้ว";	
		}		
	};
	######################################################################################################
	if($proc=="send_plan"){
		$RPRQ_CODE = $_POST["RPRQ_CODE"];
		$RPRQ_REMARK = $_POST["RPRQ_REMARK"];
		$CASE = $_POST["CASE"];
		if($CASE=='send'){
			$RPRQ_STATUSREQUEST = 'รอตรวจสอบ';
		}else{
			$RPRQ_STATUSREQUEST = 'ไม่อนุมัติ';
		}
		$PROCESSBY = $_SESSION["AD_PERSONCODE"];
		$PROCESSDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE REPAIRREQUEST SET 	
				RPRQ_STATUSREQUEST = ?, 
				RPRQ_REMARK = ?,
				RPRQ_SENDPLAN_BY = ?,
				RPRQ_SENDPLAN_DATE = ?
				WHERE RPRQ_CODE = ? ";
		$params = array(
				$RPRQ_STATUSREQUEST, 
				$RPRQ_REMARK,
				$PROCESSBY, 
				$PROCESSDATE, 
				$RPRQ_CODE
		);	
		$stmt = sqlsrv_query( $conn, $sql, $params);

		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "ส่งแผนเรียบร้อยแล้ว"; 	
		}	

		if($CASE=='not'){
			$sql_nap = "INSERT INTO REPAIRREQUEST_NONAPPROVE (
			RPRQ_NAP_OLD_CODE,
			RPRQ_NAP_STATUS,
			RPRQ_NAP_PROCESSBY,
			RPRQ_NAP_PROCESSDATE) VALUES (?,?,?,?)";
			$params_nap = array(
				$RPRQ_CODE,
				'WAIT',
				$PROCESSBY,
				$PROCESSDATE);
			$stmt_nap = sqlsrv_query( $conn, $sql_nap, $params_nap);	
		}	
	};
?>