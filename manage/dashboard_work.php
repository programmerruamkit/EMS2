<?php
	session_name("EMS"); session_start();
	$path='../';
	require($path."include/authen.php"); 
	require($path."include/connect.php");
	require($path."include/head.php");		
	require($path."include/script.php"); 
	// print_r ($_SESSION);
	$SESSION_AREA=$_SESSION["AD_AREA"];
	##########################################################################################################################
?>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<link rel="stylesheet" href="<?=$path;?>css/dashboard_work.css" />	
<script type="text/javascript">
	var global_path = "<?=$path?>";
	$(document).ready(function(e) {
		$(this).scroll(function() {
			if(parseInt($(this).scrollTop())>=(parseInt($("table.comtop tr td img").attr("height"))+5) )$("#main_menu_top").css("position","fixed");
			else $("#main_menu_top").css("position","");
		});
		treeMenu(); 
        genMenuSidebar("<?=$_GET['menu_id']?>"); 
		$('#dialog_popup').dialog({
				autoOpen: false,
				modal: true,
				draggable : true
		});
		$("#dialog_popup").on("dialogclose",function(event,ui){$("#dialog_popup").empty();});
    });
	var wait_time=120;
	var vela;
	window.onload=function(){  
		vela=setInterval("decrease_num()",1000);  
	}  
	function decrease_num(){  
		console.log(wait_time);
		if(wait_time>0){  
		var show_place=document.getElementById('show_text');  
		show_place.innerHTML=wait_time+' secound.';  
		wait_time--;  
		}else{  
			if(wait_time===0){  
				clearInterval(vela);  
				location.reload();       
			}     
		}  
	} 
</script>
<body> 
	<?php
		// echo"<pre>";
		// print_r($_GET); 
		// print_r($_POST);
		// echo"</pre>";
		// exit();		
		$SS_AREA=$_SESSION["AD_AREA"];
		$SYSDATE = $result_getdate["SYSDATE"];		
		$explodes = explode('/', $SYSDATE);
		$SYSDATEDAY = $explodes[0];
		$IFMONTH = $explodes[1];
			if($IFMONTH==='01'){
				$SYSDATEMONTH = "JAN";
			}else if($IFMONTH==='02'){
				$SYSDATEMONTH = "FEB";
			}else if($IFMONTH==='03'){
				$SYSDATEMONTH = "MAR";
			}else if($IFMONTH==='04'){
				$SYSDATEMONTH = "APR";
			}else if($IFMONTH==='05'){
				$SYSDATEMONTH = "MAY";
			}else if($IFMONTH==='06'){
				$SYSDATEMONTH = "JUN";
			}else if($IFMONTH==='07'){
				$SYSDATEMONTH = "JUL";
			}else if($IFMONTH==='08'){
				$SYSDATEMONTH = "AUG";
			}else if($IFMONTH==='09'){
				$SYSDATEMONTH = "SEP";
			}else if($IFMONTH==='10'){
				$SYSDATEMONTH = "OCT";
			}else if($IFMONTH==='11'){
				$SYSDATEMONTH = "NOV";
			}else if($IFMONTH==='12'){
				$SYSDATEMONTH = "DEC";
			}
		$SYSDATEYEAR = $explodes[2];	

		Class SetTimeObject{
			public $startPx;
			public $diff;
			public $leftMin = 0;		   
			public function getWidthPos($startTime, $endTime){
				$s = explode(":", $startTime);
				$this->startPx = ((int)$s[0] * 60) + (int)$s[1];
				list($sTime1, $sTime2) = explode(":", $startTime);
				list($eTime1, $eTime2) = explode(":", $endTime);
				$sTime = (float)$sTime1.".". ($sTime2*100)/60;
				$eTime = (float)$eTime1.".". ($eTime2*100)/60;
				$this->diff = ($eTime - $sTime);
				$l = ($this->startPx - 0) - $this->leftMin;
				$w = ($this->diff * 60);
				return array('left' => $l, 'width' => $w);
			}
		}
		// $timeArr = array("04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00","15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00");
		// $timeArr = array("00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14","15", "16", "17", "18", "19", "20", "21", "22", "23");
		$timeArr = array("00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00","15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00");

		
		$objTime = new SetTimeObject;

		$repairhole = array();
		$sql_repairhole = "SELECT * FROM REPAIR_HOLE WHERE NOT RPH_STATUS='D' AND RPH_AREA = '$SESSION_AREA'";
		$query_repairhole = sqlsrv_query($conn, $sql_repairhole);
		while($result_repairhole = sqlsrv_fetch_array($query_repairhole, SQLSRV_FETCH_ASSOC)){	
			$repairhole[] = array('RPH_ID'=>$result_repairhole['RPH_ID'],'RPH_REPAIRHOLE'=>$result_repairhole['RPH_REPAIRHOLE']);
		}

		$repairrequest = array();
		$sql_repairrequest = "SELECT B.RPRQ_ASSIGN_BY,B.RPRQ_STATUSREQUEST,B.RPRQ_COMPANYCASH,B.RPRQ_REGISHEAD,B.RPRQ_CARNAMEHEAD,B.RPRQ_REGISTAIL,B.RPRQ_CARNAMETAIL,B.RPRQ_WORKTYPE,B.RPRQ_RANKPMTYPE,B.RPRQ_TYPECUSTOMER,
			A.RPRQ_CODE,A.RPC_AREA,A.RPC_AREAID,A.RPC_INCARDATE,A.RPC_INCARTIME,A.RPC_OUTCARDATE,A.RPC_OUTCARTIME,A.RPC_SUBJECT 
			FROM [dbo].[REPAIRCAUSE] A
		 	LEFT JOIN REPAIRREQUEST B ON B.RPRQ_CODE = A.RPRQ_CODE 
		 	WHERE B.RPRQ_STATUS = 'Y' AND B.RPRQ_AREA = '$SS_AREA' AND A.RPC_INCARDATE = '$SYSDATE' AND NOT B.RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอตรวจสอบ')";
		$query_repairrequest = sqlsrv_query($conn, $sql_repairrequest);
		while($result_repairrequest = sqlsrv_fetch_array($query_repairrequest, SQLSRV_FETCH_ASSOC)){	
			$repairrequest[$result_repairrequest['RPC_AREAID']][] = array(
				'RPRQ_ASSIGN_BY'=>$result_repairrequest['RPRQ_ASSIGN_BY'],
				'RPRQ_STATUSREQUEST'=>$result_repairrequest['RPRQ_STATUSREQUEST'],
				'RPRQ_COMPANYCASH'=>$result_repairrequest['RPRQ_COMPANYCASH'],
				'RPRQ_REGISHEAD'=>$result_repairrequest['RPRQ_REGISHEAD'],
				'RPRQ_CARNAMEHEAD'=>$result_repairrequest['RPRQ_CARNAMEHEAD'],	
				'RPRQ_REGISTAIL'=>$result_repairrequest['RPRQ_REGISTAIL'],
				'RPRQ_CARNAMETAIL'=>$result_repairrequest['RPRQ_CARNAMETAIL'],				
				'RPRQ_WORKTYPE'=>$result_repairrequest['RPRQ_WORKTYPE'],
				'RPRQ_RANKPMTYPE'=>$result_repairrequest['RPRQ_RANKPMTYPE'],
				'RPRQ_TYPECUSTOMER'=>$result_repairrequest['RPRQ_TYPECUSTOMER'],
				'RPRQ_CODE'=>$result_repairrequest['RPRQ_CODE'],
				'RPC_AREA'=>$result_repairrequest['RPC_AREA'],
				'RPC_AREAID'=>$result_repairrequest['RPC_AREAID'],
				'RPC_INCARDATE'=>$result_repairrequest['RPC_INCARDATE'],
				'RPC_INCARTIME'=>$result_repairrequest['RPC_INCARTIME'],
				'RPC_OUTCARDATE'=>$result_repairrequest['RPC_OUTCARDATE'],
				'RPC_OUTCARTIME'=>$result_repairrequest['RPC_OUTCARTIME'],
				'RPC_SUBJECT'=>$result_repairrequest['RPC_SUBJECT']
			);
		}
		
		// echo"<pre>";
		// print_r($repairrequest->RPRQ_TYPECUSTOMER); 
		// echo"</pre>";

		// echo"<pre>";
		// print_r($repairrequest); 
		// echo"</pre>";


		function dateDiv($t1,$t2){ // ส่งวันที่ที่ต้องการเปรียบเทียบ ในรูปแบบ มาตรฐาน 2006-03-27 21:39:12		
			$t1Arr=splitTime($t1);
			$t2Arr=splitTime($t2);			
			$Time1=mktime($t1Arr["h"], $t1Arr["m"], $t1Arr["s"], $t1Arr["M"], $t1Arr["D"], $t1Arr["Y"]);
			$Time2=mktime($t2Arr["h"], $t2Arr["m"], $t2Arr["s"], $t2Arr["M"], $t2Arr["D"], $t2Arr["Y"]);
			$TimeDiv=abs($Time2-$Time1);			
			$Time["D"]=intval($TimeDiv/86400); // จำนวนวัน
			$Time["H"]=intval(($TimeDiv%86400)/3600); // จำนวน ชั่วโมง
			$Time["M"]=intval((($TimeDiv%86400)%3600)/60); // จำนวน นาที
			$Time["S"]=intval(((($TimeDiv%86400)%3600)%60)); // จำนวน วินาที
			return $Time;
		}		
		function splitTime($time){ // เวลาในรูปแบบ มาตรฐาน 2006-03-27 21:39:12 
			$timeArr["Y"]= substr($time,2,2);
			$timeArr["M"]= substr($time,5,2);
			$timeArr["D"]= substr($time,8,2);
			$timeArr["h"]= substr($time,11,2);
			$timeArr["m"]= substr($time,14,2);
			$timeArr["s"]= substr($time,17,2);
			return $timeArr;
		}
	?>
	<div id="dialog_popup" align="center"></div>
	<table width="100%"  height="100%"  border="0" cellpadding="0" cellspacing="0" class="no-border"> <!--main_data -->
		<tr valign="top">
			<td height="1"><?php include ($path."include/navtop.php");?></td><!-- height="18" -->
		</tr>
		<tr valign="top">
			<td><input type="hidden" id="toggle_menu" value="ปิด">
				<input name="current_menu_id" type="hidden" id="current_menu_id">
				<table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" id="main">
					<tr>						
						<td>
							<br>
							<h1>DASHBOARD <?php echo $SYSDATEDAY.' '.$SYSDATEMONTH.' '.$SYSDATEYEAR?> <small><font size='4px'>Load latest data  <?=date("H:i:s");?> Reload page in <span id="show_text"></span></font></small></h1>
							<br>
							<div id="innerbox">
								<div class="row">							
									&emsp;&emsp;&emsp;
									<div class="col-8">				
										<table>
											<tr>
												<th align="right" width="5%"></th>
												<th>
													<p class="tb-text-right">
														<div class="td_time">
															<div><?php echo implode("</div><div>", $timeArr) ?></div>
														</div>
													</p>
												</th>
											</tr>
										</table>
										<div class="spacetable"></div>							
										<?php								
											foreach($repairhole as $result_repairhole){
										?>		
										<table>
											<tr>
												<th scope="row" class="room" width="5%"><?=$result_repairhole['RPH_REPAIRHOLE']?></th>								
												<td class="event">		
													
													<?php
														if(isset($repairrequest[$result_repairhole['RPH_ID']])){
															$objTime->leftMin = 0;
															foreach($repairrequest[$result_repairhole['RPH_ID']] as $bookData){
																$arr = $objTime->getWidthPos($bookData['RPC_INCARTIME'], $bookData['RPC_OUTCARTIME']);
																$left = $arr['left'];
																$width = $arr['width'];
																$objTime->leftMin += $arr['width'];													
																$RPRQ_CODE = $bookData['RPRQ_CODE'];									
																$RPRQ_ASSIGN_BY = $bookData['RPRQ_ASSIGN_BY'];					
																$IFRPC_SUBJECT = $bookData['RPC_SUBJECT'];
																// open if subject ##########################################	
																	if($IFRPC_SUBJECT==='EG'){
																		$RPC_SUBJECT='เครื่องยนต์';
																	}else if($IFRPC_SUBJECT==='BD'){
																		$RPC_SUBJECT='โครงสร้าง';
																	}else if($IFRPC_SUBJECT==='TU'){
																		$RPC_SUBJECT='ยาง-ช่วงล่าง';
																	}else if($IFRPC_SUBJECT==='EL'){
																		$RPC_SUBJECT='ระบบไฟ';
																	}
																// close if subject ##########################################	
																$RPRQ_WORKTYPE = $bookData['RPRQ_WORKTYPE'];
																$RPRQ_RANKPMTYPE = $bookData['RPRQ_RANKPMTYPE'];
																$RPRQ_TYPECUSTOMER = $bookData['RPRQ_TYPECUSTOMER'];
																$RPRQ_REGISHEAD = $bookData['RPRQ_REGISHEAD'];
																$RPRQ_CARNAMEHEAD = $bookData['RPRQ_CARNAMEHEAD'];	
																$RPRQ_REGISTAIL = $bookData['RPRQ_REGISTAIL'];
																$RPRQ_CARNAMETAIL = $bookData['RPRQ_CARNAMETAIL'];	
																if($RPRQ_REGISHEAD==''){
																	$RS_REGIS = '- / -, '.$RPRQ_REGISTAIL.' / ';
																	$RS_CARNAME = $RPRQ_CARNAMETAIL;
																}else{
																	$RS_REGIS = $RPRQ_REGISHEAD.' / ';
																	$RS_CARNAME = $RPRQ_CARNAMEHEAD;
																}
																$RPC_INCARDATE=$bookData['RPC_INCARDATE'];
																$RPC_INCARTIME=$bookData['RPC_INCARTIME'];
																$RPC_OUTCARDATE=$bookData['RPC_OUTCARDATE'];
																$RPC_OUTCARTIME=$bookData['RPC_OUTCARTIME'];				

																$explodesRPC_INCARDATE = explode('/', $RPC_INCARDATE);
																$EXRPC_INCARDATE1 = $explodesRPC_INCARDATE[0];
																$EXRPC_INCARDATE2 = $explodesRPC_INCARDATE[1];
																$EXRPC_INCARDATE3 = $explodesRPC_INCARDATE[2];
																$DurationStart=$EXRPC_INCARDATE3.'-'.$EXRPC_INCARDATE2.'-'.$EXRPC_INCARDATE1.' '.$RPC_INCARTIME;
																$explodesRPC_OUTCARDATE = explode('/', $RPC_OUTCARDATE);
																$EXRPC_OUTCARDATE1 = $explodesRPC_OUTCARDATE[0];
																$EXRPC_OUTCARDATE2 = $explodesRPC_OUTCARDATE[1];
																$EXRPC_OUTCARDATE3 = $explodesRPC_OUTCARDATE[2];
																$DurationEND=$EXRPC_OUTCARDATE3.'-'.$EXRPC_OUTCARDATE2.'-'.$EXRPC_OUTCARDATE1.' '.$RPC_OUTCARTIME;
																// print "<br> $DurationStart <br> $DurationEND  <br>  ";
																$Duration=dateDiv($DurationStart,$DurationEND);
																// echo $Duration['H'].' '.$Duration['M'];
																if(isset($Duration['H'])){
																	$hours=$Duration['H'];
																}else{
																	$hours='';
																}
																if(isset($Duration['M'])){
																	if($Duration['M'] > 0){
																		$minute='.'.$Duration['M'].' Hrs.';
																	}else{
																		$minute=' Hrs.';
																	}
																}else{
																	$minute=' Hrs.';
																}													

																if($RPRQ_TYPECUSTOMER==="cusout"){
																	$tb_from = 'CUSTOMER_CAR';
																}else{
																	$tb_from = 'vwVEHICLEINFO';
																}
																$sql_vehicleinfo = "SELECT * FROM $tb_from 
																LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
																LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
																WHERE 1=1 AND VEHICLEGROUPDESC = 'Transport' AND ACTIVESTATUS = '1' AND VEHICLEREGISNUMBER = '$RPRQ_REGISHEAD'";
																$query_vehicleinfo = sqlsrv_query($conn, $sql_vehicleinfo);
																while($result_vehicleinfo = sqlsrv_fetch_array($query_vehicleinfo, SQLSRV_FETCH_ASSOC)){
																	// echo $result_vehicleinfo['VEHICLEREGISNUMBER'];
																}

																$stmt_emp_request = "SELECT * FROM vwEMPLOYEE WHERE PersonCode = ?";
																$params_emp_request = array($RPRQ_ASSIGN_BY);	
																$query_emp_request = sqlsrv_query( $conn, $stmt_emp_request, $params_emp_request);	
																$result_edit_emp_request = sqlsrv_fetch_array($query_emp_request, SQLSRV_FETCH_ASSOC);
																$nameE=$result_edit_emp_request["nameE"];													
						
																$repairtime_start = array();
																$sql_repairtime_start = "SELECT TOP 1 RPATTM_PROCESS FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPATTM_GROUP = 'START'";
																$query_repairtime_start = sqlsrv_query($conn, $sql_repairtime_start);
																$result_repairtime_start = sqlsrv_fetch_array($query_repairtime_start, SQLSRV_FETCH_ASSOC);
																$TIMESTART = $result_repairtime_start["RPATTM_PROCESS"];	
																$explodesTIMESTART = explode(' ', $TIMESTART);
																$EXTIMESTART = $explodesTIMESTART[1];
																$RSTIMESTART = substr($EXTIMESTART,0,5);

																$repairtime_success = array();
																$sql_repairtime_success = "SELECT TOP 1 RPATTM_PROCESS FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPATTM_GROUP = 'SUCCESS'";
																$query_repairtime_success = sqlsrv_query($conn, $sql_repairtime_success);
																$result_repairtime_success = sqlsrv_fetch_array($query_repairtime_success, SQLSRV_FETCH_ASSOC);
																$TIMESUCCESS = $result_repairtime_success["RPATTM_PROCESS"];	
																$explodesTIMESUCCESS = explode(' ', $TIMESUCCESS);
																$EXTIMESUCCESS = $explodesTIMESUCCESS[1];
																$RSTIMESUCCESS = substr($EXTIMESUCCESS,0,5);
																
																$repairtime_actual = array();
																$sql_repairtime_actual = "SELECT SUM(CAST(RPATTM_TOTAL as int)) RPATTM_TOTAL FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPATTM_GROUP IN('PAUSE','SUCCESS')";
																$query_repairtime_actual = sqlsrv_query($conn, $sql_repairtime_actual);
																$result_repairtime_actual = sqlsrv_fetch_array($query_repairtime_actual, SQLSRV_FETCH_ASSOC);
																$RPATTM_TOTAL = $result_repairtime_actual["RPATTM_TOTAL"];	

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
																			$RSSEC='.'.$SEC.' Hrs.';
																		}else{
																			$RSSEC=' Hrs.';
																		}
																	}else{
																		$RSSEC=' Hrs.';
																	}
																}else{
																	$RSMIN='';
																	$RSSEC='';
																}
																
																// open if color ##########################################
																	if(($RSTIMESTART!='')&&($RSTIMESUCCESS=='')){
																		$colortb = 'tb-red';
																		$colorfont = 'black';
																		if($RPRQ_RANKPMTYPE==='PMoRS-1'){
																			$headtable = $RS_REGIS.' PMoRS-1';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-2'){
																			$headtable = $RS_REGIS.' PMoRS-2';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-3'){
																			$headtable = $RS_REGIS.' PMoRS-3';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-4'){
																			$headtable = $RS_REGIS.' PMoRS-4';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-5'){
																			$headtable = $RS_REGIS.' PMoRS-5';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-6'){
																			$headtable = $RS_REGIS.' PMoRS-6';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-7'){
																			$headtable = $RS_REGIS.' PMoRS-7';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-8'){
																			$headtable = $RS_REGIS.' PMoRS-8';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-9'){
																			$headtable = $RS_REGIS.' PMoRS-9';
																		}else if($RPRQ_WORKTYPE==='BM'){
																			$headtable = $RS_REGIS.' BM / '.$RPC_SUBJECT;
																		}else{
																			$headtable = $RS_REGIS.' BM / '.$RPC_SUBJECT;
																		}
																	}else if(($RSTIMESTART!='')&&($RSTIMESUCCESS!='')){
																		$colortb = 'tb-green2';
																		$colorfont = 'black';
																		if($RPRQ_RANKPMTYPE==='PMoRS-1'){
																			$headtable = $RS_REGIS.' PMoRS-1';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-2'){
																			$headtable = $RS_REGIS.' PMoRS-2';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-3'){
																			$headtable = $RS_REGIS.' PMoRS-3';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-4'){
																			$headtable = $RS_REGIS.' PMoRS-4';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-5'){
																			$headtable = $RS_REGIS.' PMoRS-5';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-6'){
																			$headtable = $RS_REGIS.' PMoRS-6';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-7'){
																			$headtable = $RS_REGIS.' PMoRS-7';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-8'){
																			$headtable = $RS_REGIS.' PMoRS-8';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-9'){
																			$headtable = $RS_REGIS.' PMoRS-9';
																		}else if($RPRQ_WORKTYPE==='BM'){
																			$headtable = $RS_REGIS.' BM / '.$RPC_SUBJECT;
																		}else{
																			$headtable = $RS_REGIS.' BM / '.$RPC_SUBJECT;
																		}
																	}else{
																		if($RPRQ_RANKPMTYPE==='PMoRS-1'){
																			$headtable = $RS_REGIS.' PMoRS-1';
																			$colortb = 'tb-purple1';
																			$colorfont = 'black';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-2'){
																			$headtable = $RS_REGIS.' PMoRS-2';
																			$colortb = 'tb-purple2';
																			$colorfont = 'black';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-3'){
																			$headtable = $RS_REGIS.' PMoRS-3';
																			$colortb = 'tb-info1';
																			$colorfont = 'black';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-4'){
																			$headtable = $RS_REGIS.' PMoRS-4';
																			$colortb = 'tb-info2';
																			$colorfont = 'black';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-5'){
																			$headtable = $RS_REGIS.' PMoRS-5';
																			$colortb = 'tb-yellow1';
																			$colorfont = 'black';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-6'){
																			$headtable = $RS_REGIS.' PMoRS-6';
																			$colortb = 'tb-yellow2';
																			$colorfont = 'black';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-7'){
																			$headtable = $RS_REGIS.' PMoRS-7';
																			$colortb = 'tb-yellow3';
																			$colorfont = 'black';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-8'){
																			$headtable = $RS_REGIS.' PMoRS-8';
																			$colortb = 'tb-pink1';
																			$colorfont = 'black';
																		}else if($RPRQ_RANKPMTYPE==='PMoRS-9'){
																			$headtable = $RS_REGIS.' PMoRS-9';
																			$colortb = 'tb-pink2';
																			$colorfont = 'black';
																		}else if($RPRQ_WORKTYPE==='BM'){
																			$headtable = $RS_REGIS.' BM / '.$RPC_SUBJECT;
																			$colortb = 'tb-brown';
																			$colorfont = 'black';
																		}else{
																			$headtable = $RS_REGIS.' BM / '.$RPC_SUBJECT;
																			$colortb = 'tb-draggable2';
																			$colorfont = 'black';
																		}
																	}
																// close if color ##########################################	

																if($RPRQ_WORKTYPE==='PM'){	
																	$repair_estimate = array();
																	$sql_repair_estimate = "SELECT * FROM REPAIRESTIMATE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPETM_TYPEREPAIR = 'PM' AND RPETM_NATURE = 'เครื่องยนต์'";
																	$query_repair_estimate = sqlsrv_query($conn, $sql_repair_estimate);
																	$result_repair_estimate = sqlsrv_fetch_array($query_repair_estimate, SQLSRV_FETCH_ASSOC);
																		$EG_SPARESPART = number_format($result_repair_estimate["RPETM_SPARESPART"],2);
																		$EG_SPARESPART1 = $result_repair_estimate["RPETM_SPARESPART"];
																		$EG_WAGE = $result_repair_estimate["RPETM_WAGE"];
																	$repair_estimate = array();
																	$sql_repair_estimate = "SELECT * FROM REPAIRESTIMATE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPETM_TYPEREPAIR = 'PM' AND RPETM_NATURE = 'โครงสร้าง'";
																	$query_repair_estimate = sqlsrv_query($conn, $sql_repair_estimate);
																	$result_repair_estimate = sqlsrv_fetch_array($query_repair_estimate, SQLSRV_FETCH_ASSOC);
																		$BD_SPARESPART = number_format($result_repair_estimate["RPETM_SPARESPART"],2);
																		$BD_SPARESPART1 = $result_repair_estimate["RPETM_SPARESPART"];	
																		$BD_WAGE = $result_repair_estimate["RPETM_WAGE"];
																	$repair_estimate = array();
																	$sql_repair_estimate = "SELECT * FROM REPAIRESTIMATE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPETM_TYPEREPAIR = 'PM' AND RPETM_NATURE = 'ยาง-ช่วงล่าง'";
																	$query_repair_estimate = sqlsrv_query($conn, $sql_repair_estimate);
																	$result_repair_estimate = sqlsrv_fetch_array($query_repair_estimate, SQLSRV_FETCH_ASSOC);
																		$TU_SPARESPART = number_format($result_repair_estimate["RPETM_SPARESPART"],2);	
																		$TU_SPARESPART1 = $result_repair_estimate["RPETM_SPARESPART"];
																		$TU_WAGE = $result_repair_estimate["RPETM_WAGE"];	
																	$repair_estimate = array();
																	$sql_repair_estimate = "SELECT * FROM REPAIRESTIMATE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPETM_TYPEREPAIR = 'PM' AND RPETM_NATURE = 'ระบบไฟ'";
																	$query_repair_estimate = sqlsrv_query($conn, $sql_repair_estimate);
																	$result_repair_estimate = sqlsrv_fetch_array($query_repair_estimate, SQLSRV_FETCH_ASSOC);
																		$EL_SPARESPART = number_format($result_repair_estimate["RPETM_SPARESPART"],2);	
																		$EL_SPARESPART1 = $result_repair_estimate["RPETM_SPARESPART"];
																		$EL_WAGE = $result_repair_estimate["RPETM_WAGE"];		
																	$WAGE = $EG_WAGE+$BD_WAGE+$TU_WAGE+$EL_WAGE;	
																	$RPETM_WAGE	= number_format($EG_WAGE+$BD_WAGE+$TU_WAGE+$EL_WAGE,2);	
																		
																	$CALTOTAL = number_format($WAGE+$EG_SPARESPART1+$BD_SPARESPART1+$TU_SPARESPART1+$EL_SPARESPART1,2);	
																	$CALTOTAL1 = $WAGE+$EG_SPARESPART1+$BD_SPARESPART1+$TU_SPARESPART1+$EL_SPARESPART1;
																	$PART = $EG_SPARESPART1+$BD_SPARESPART1+$TU_SPARESPART1+$EL_SPARESPART1;
																	$SERVICE = $WAGE;
																}else if($RPRQ_WORKTYPE==='BM'){	
																	$repair_estimate = array();
																	$sql_repair_estimate = "SELECT * FROM REPAIRESTIMATE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPETM_TYPEREPAIR = 'BM' AND RPETM_NATURE = '$RPC_SUBJECT'";
																	$query_repair_estimate = sqlsrv_query($conn, $sql_repair_estimate);
																	$result_repair_estimate = sqlsrv_fetch_array($query_repair_estimate, SQLSRV_FETCH_ASSOC);
																	$RPETM_SPARESPART = number_format($result_repair_estimate["RPETM_SPARESPART"],2);	
																	$RPETM_SPARESPART1 = $result_repair_estimate["RPETM_SPARESPART"];	
																	$WAGE = $result_repair_estimate["RPETM_WAGE"];
																	$RPETM_WAGE = number_format($result_repair_estimate["RPETM_WAGE"],2);	
																	// open if estimate ##########################################	
																		if($IFRPC_SUBJECT==='EG'){
																			$EG_SPARESPART=$RPETM_SPARESPART;
																			$EG_SPARESPART1=$RPETM_SPARESPART1;
																		}else{
																			$EG_SPARESPART='';
																			$EG_SPARESPART1='';
																		}
																		if($IFRPC_SUBJECT==='BD'){
																			$BD_SPARESPART=$RPETM_SPARESPART;
																			$BD_SPARESPART1=$RPETM_SPARESPART1;
																		}else{
																			$BD_SPARESPART='';
																			$BD_SPARESPART1='';
																		}
																		if($IFRPC_SUBJECT==='TU'){
																			$TU_SPARESPART=$RPETM_SPARESPART;
																			$TU_SPARESPART1=$RPETM_SPARESPART1;
																		}else{
																			$TU_SPARESPART='';
																			$TU_SPARESPART1='';
																		}
																		if($IFRPC_SUBJECT==='EL'){
																			$EL_SPARESPART=$RPETM_SPARESPART;
																			$EL_SPARESPART1=$RPETM_SPARESPART1;
																		}else{
																			$EL_SPARESPART='';
																			$EL_SPARESPART1='';
																		}
																	// close if estimate ##########################################								
																	$CALTOTAL = number_format($WAGE+$EG_SPARESPART1+$BD_SPARESPART1+$TU_SPARESPART1+$EL_SPARESPART1,2);	
																	$CALTOTAL1 = $WAGE+$EG_SPARESPART1+$BD_SPARESPART1+$TU_SPARESPART1+$EL_SPARESPART1;	
																	$PART = $EG_SPARESPART1+$BD_SPARESPART1+$TU_SPARESPART1+$EL_SPARESPART1;
																	$SERVICE = $WAGE;											
																}	
																$CALPART=$CALPART+$PART;
																$CALSERVICE=$CALSERVICE+$SERVICE;

																echo '
																<div class="tooltip" style="left:'.$left.'px;width:'.$width.'px">
																	<div class="draggable2 '.$colortb.'">
																		<b>
																			<font color="'.$colorfont.'">'.$RS_REGIS.$RS_CARNAME.'<br/>('.$RPC_INCARTIME.'-'.$RPC_OUTCARTIME.')
																			</font>
																		</b>
																	</div>';
																	echo '
																	<span class="tooltiptext">
																		<div class="row">
																			<div class="col-12">';		
																				$sql_rq_image = "SELECT * FROM vwREPAIRCAUSEIMAGE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$IFRPC_SUBJECT' ORDER BY RPCIM_ID ASC";
																				$query_rq_image = sqlsrv_query($conn, $sql_rq_image);
																				while($result_rq_image = sqlsrv_fetch_array($query_rq_image, SQLSRV_FETCH_ASSOC)){														
																					$RPCIM_ID=$result_rq_image['RPCIM_ID'];
																					$RPC_SUBJECT=$result_rq_image['RPC_SUBJECT'];
																					$WORKINGBY=$result_rq_image['WORKINGBY'];
																					$DATETIME1038=$result_rq_image['DATETIME1038'];  
																					$RPCIM_IMAGES=$result_rq_image["RPCIM_IMAGES"];
																					?>				
																					<img src="<?=$path?>uploads/requestrepair/<?=$result_rq_image["RPCIM_IMAGES"];?>" width="100px" height="100px" style="cursor:pointer;border:5px solid #555;" onclick="javascript:ajaxPopup4('dashboard_imagerepairview.php','edit','<?=$RPRQ_CODE;?>&subject=<?=$RPC_SUBJECT;?>&image=<?=$RPCIM_IMAGES?>','1=1','1000','700','รูปภาพแจ้งซ่อม');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																					<?php
																				}
																				echo '
																			</div>
																			&emsp;&ensp;
																			<div class="col-7">
																				<table class="tablecolor">
																					<tr class="tablecolorth">
																						<th class="tablecolor" width="10%">ที่</th>
																						<th class="tablecolor" width="70%">'.$headtable.'</th>
																						<th class="tablecolor" width="20%">฿</th>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center>1</center></td>
																						<td class="tablecolor">Standard HINO [Cost] PART [Engine]</td>
																						<td class="tablecolor" align="right">'.$EG_SPARESPART.'</td>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center>2</center></td>
																						<td class="tablecolor">Standard HINO [Cost] PART [Air, Light, Battery]</td>
																						<td class="tablecolor" align="right">'.$EL_SPARESPART.'</td>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center>3</center></td>
																						<td class="tablecolor">Standard HINO [Cost] PART [Structure]</td>
																						<td class="tablecolor" align="right">'.$BD_SPARESPART.'</td>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center>4</center></td>
																						<td class="tablecolor">Standard HINO [Cost] PART [Tire/Suspension]</td>
																						<td class="tablecolor" align="right">'.$TU_SPARESPART.'</td>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center>5</center></td>
																						<td class="tablecolor">Standard HINO [Cost] SERVICE</td>
																						<td class="tablecolor" align="right">'.$RPETM_WAGE.'</td>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center>6</center></td>
																						<td class="tablecolor">TIRE/MONTH [Cost]</td>
																						<td class="tablecolor"></td>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center>7</center></td>
																						<td class="tablecolor">Standard Check STRHYD,TIRE,ELEC. [Cost]</td>
																						<td class="tablecolor"></td>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center>8</center></td>
																						<td class="tablecolor">PART CHANGE BY PROJECT</td>
																						<td class="tablecolor"></td>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center>9</center></td>
																						<td class="tablecolor">Booster Clutch</td>
																						<td class="tablecolor"></td>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center>10</center></td>
																						<td class="tablecolor">Battery</td>
																						<td class="tablecolor"></td>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center>11</center></td>
																						<td class="tablecolor">HYD Oil</td>
																						<td class="tablecolor"></td>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center></center></td>
																						<td class="tablecolor"></td>
																						<td class="tablecolor"></td>
																					</tr>
																					<tr class="tablecolor">
																						<td class="tablecolor"><center></center></td>
																						<td class="tablecolorth"><center><b>Total</b></center></td>
																						<td class="tablecolor" align="right">'.$CALTOTAL.'</td>
																					</tr>
																				</table>
																			</div>
																			&emsp;
																			<div class="col-4">
																				<table>
																					<tr><td><b>Assignment</b></td></tr>
																					<tr><td><b>By: '.$nameE.'</b></td></tr>
																					<tr><td><b>Plan: '.$RPC_INCARTIME.'-'.$RPC_OUTCARTIME.'</b></td></tr>
																					<tr><td><b>Start: '.$RSTIMESTART.'</b></td></tr>
																					<tr><td><b>End: '.$RSTIMESUCCESS.'</b></td></tr>
																					<tr><td>&nbsp;</td></tr>
																					<tr><td><b>Duration: '.$hours.$minute.'</b></td></tr>
																					<tr><td><b>Actual: '.$RSMIN.$RSSEC.'</b></td></tr>
																					<tr><td>&nbsp;</td></tr>
																					<tr><td><b>Part: '.number_format($PART,2).'</b></td></tr>
																					<tr><td><b>Service: '.number_format($SERVICE,2).'</b></td></tr>
																					<tr><td><b>Total: '.$CALTOTAL.'</b></td></tr>
																				</table>
																			</div>															
																		</div>
																	</span>
																</div>';
															}
														}
													?>									
												</td>
											</tr>			
										</table>
										<div class="spacetable"></div>	
										<?php } ?>		
										<br>
										<table>
											<tr>
												<th scope="row" width="5%">&nbsp;</th>
												<!-- <td><font color="red" style="font-weight: bold;" size="4">BAD NEW: PMORS1 60-5587 MISSED TARGET Urgently use truck by Customer</font></td> -->
											</tr>			
										</table>
									</div>&nbsp;
									<div class="col-2">			
										<?php		
											$pmors1 = array();
											$sql_pmors1 = "SELECT COUNT(RPRQ_RANKPMTYPE) PMORS1 FROM vwREPAIRREQUEST_PM WHERE RPRQ_RANKPMTYPE = 'PMoRS-1' AND RPRQ_STATUS = 'Y' AND NOT RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอตรวจสอบ') AND RPRQ_AREA = '$SS_AREA' AND RPC_INCARDATE = '$SYSDATE'";
											$query_pmors1 = sqlsrv_query($conn, $sql_pmors1);
											$result_pmors1 = sqlsrv_fetch_array($query_pmors1, SQLSRV_FETCH_ASSOC);
											$PMORS1 = $result_pmors1['PMORS1'];
											$pmors2 = array();
											$sql_pmors2 = "SELECT COUNT(RPRQ_RANKPMTYPE) PMORS2 FROM vwREPAIRREQUEST_PM WHERE RPRQ_RANKPMTYPE = 'PMoRS-2' AND RPRQ_STATUS = 'Y' AND NOT RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอตรวจสอบ') AND RPRQ_AREA = '$SS_AREA' AND RPC_INCARDATE = '$SYSDATE'";
											$query_pmors2 = sqlsrv_query($conn, $sql_pmors2);
											$result_pmors2 = sqlsrv_fetch_array($query_pmors2, SQLSRV_FETCH_ASSOC);
											$PMORS2 = $result_pmors2['PMORS2'];
											$pmors3 = array();
											$sql_pmors3 = "SELECT COUNT(RPRQ_RANKPMTYPE) PMORS3 FROM vwREPAIRREQUEST_PM WHERE RPRQ_RANKPMTYPE = 'PMoRS-3' AND RPRQ_STATUS = 'Y' AND NOT RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอตรวจสอบ') AND RPRQ_AREA = '$SS_AREA' AND RPC_INCARDATE = '$SYSDATE'";
											$query_pmors3 = sqlsrv_query($conn, $sql_pmors3);
											$result_pmors3 = sqlsrv_fetch_array($query_pmors3, SQLSRV_FETCH_ASSOC);
											$PMORS3 = $result_pmors3['PMORS3'];
											$pmors4 = array();
											$sql_pmors4 = "SELECT COUNT(RPRQ_RANKPMTYPE) PMORS4 FROM vwREPAIRREQUEST_PM WHERE RPRQ_RANKPMTYPE = 'PMoRS-4' AND RPRQ_STATUS = 'Y' AND NOT RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอตรวจสอบ') AND RPRQ_AREA = '$SS_AREA' AND RPC_INCARDATE = '$SYSDATE'";
											$query_pmors4 = sqlsrv_query($conn, $sql_pmors4);
											$result_pmors4 = sqlsrv_fetch_array($query_pmors4, SQLSRV_FETCH_ASSOC);
											$PMORS4 = $result_pmors4['PMORS4'];
											$pmors5 = array();
											$sql_pmors5 = "SELECT COUNT(RPRQ_RANKPMTYPE) PMORS5 FROM vwREPAIRREQUEST_PM WHERE RPRQ_RANKPMTYPE = 'PMoRS-5' AND RPRQ_STATUS = 'Y' AND NOT RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอตรวจสอบ') AND RPRQ_AREA = '$SS_AREA' AND RPC_INCARDATE = '$SYSDATE'";
											$query_pmors5 = sqlsrv_query($conn, $sql_pmors5);
											$result_pmors5 = sqlsrv_fetch_array($query_pmors5, SQLSRV_FETCH_ASSOC);
											$PMORS5 = $result_pmors5['PMORS5'];
											$pmors6 = array();
											$sql_pmors6 = "SELECT COUNT(RPRQ_RANKPMTYPE) PMORS6 FROM vwREPAIRREQUEST_PM WHERE RPRQ_RANKPMTYPE = 'PMoRS-6' AND RPRQ_STATUS = 'Y' AND NOT RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอตรวจสอบ') AND RPRQ_AREA = '$SS_AREA' AND RPC_INCARDATE = '$SYSDATE'";
											$query_pmors6 = sqlsrv_query($conn, $sql_pmors6);
											$result_pmors6 = sqlsrv_fetch_array($query_pmors6, SQLSRV_FETCH_ASSOC);
											$PMORS6 = $result_pmors6['PMORS6'];
											$pmors7 = array();
											$sql_pmors7 = "SELECT COUNT(RPRQ_RANKPMTYPE) PMORS7 FROM vwREPAIRREQUEST_PM WHERE RPRQ_RANKPMTYPE = 'PMoRS-7' AND RPRQ_STATUS = 'Y' AND NOT RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอตรวจสอบ') AND RPRQ_AREA = '$SS_AREA' AND RPC_INCARDATE = '$SYSDATE'";
											$query_pmors7 = sqlsrv_query($conn, $sql_pmors7);
											$result_pmors7 = sqlsrv_fetch_array($query_pmors7, SQLSRV_FETCH_ASSOC);
											$PMORS7 = $result_pmors7['PMORS7'];
											$pmors8 = array();
											$sql_pmors8 = "SELECT COUNT(RPRQ_RANKPMTYPE) PMORS8 FROM vwREPAIRREQUEST_PM WHERE RPRQ_RANKPMTYPE = 'PMoRS-8' AND RPRQ_STATUS = 'Y' AND NOT RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอตรวจสอบ') AND RPRQ_AREA = '$SS_AREA' AND RPC_INCARDATE = '$SYSDATE'";
											$query_pmors8 = sqlsrv_query($conn, $sql_pmors8);
											$result_pmors8 = sqlsrv_fetch_array($query_pmors8, SQLSRV_FETCH_ASSOC);
											$PMORS8 = $result_pmors8['PMORS8'];
											$pmors9 = array();
											$sql_pmors9 = "SELECT COUNT(RPRQ_RANKPMTYPE) PMORS9 FROM vwREPAIRREQUEST_PM WHERE RPRQ_RANKPMTYPE = 'PMoRS-9' AND RPRQ_STATUS = 'Y' AND NOT RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอตรวจสอบ') AND RPRQ_AREA = '$SS_AREA' AND RPC_INCARDATE = '$SYSDATE'";
											$query_pmors9 = sqlsrv_query($conn, $sql_pmors9);
											$result_pmors9 = sqlsrv_fetch_array($query_pmors9, SQLSRV_FETCH_ASSOC);
											$PMORS9 = $result_pmors9['PMORS9'];
											$typeBM = array();
											$sql_typeBM = "SELECT COUNT(RPRQ_WORKTYPE) COUNT_BM FROM REPAIRREQUEST LEFT JOIN REPAIRCAUSE ON REPAIRCAUSE.RPRQ_CODE = REPAIRREQUEST.RPRQ_CODE WHERE RPRQ_WORKTYPE = 'BM' AND RPRQ_STATUS = 'Y' AND NOT RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอตรวจสอบ') AND RPRQ_AREA = '$SS_AREA' AND RPC_INCARDATE = '$SYSDATE'";
											$query_typeBM = sqlsrv_query($conn, $sql_typeBM);
											$result_typeBM = sqlsrv_fetch_array($query_typeBM, SQLSRV_FETCH_ASSOC);
											$COUNT_BM = $result_typeBM['COUNT_BM'];
											$working = array();
											$sql_working = "SELECT COUNT(RPRQ_WORKTYPE) COUNT_WK FROM REPAIRREQUEST LEFT JOIN REPAIRACTUAL_TIME ON REPAIRACTUAL_TIME.RPRQ_CODE = REPAIRREQUEST.RPRQ_CODE WHERE RPRQ_STATUS = 'Y' AND RPRQ_STATUSREQUEST IN('กำลังซ่อม') AND RPRQ_AREA = '$SS_AREA' AND Convert(VARCHAR,CONVERT(DATETIME, RPATTM_PROCESS, 111),103) = '$SYSDATE'";
											$query_working = sqlsrv_query($conn, $sql_working);
											$result_working = sqlsrv_fetch_array($query_working, SQLSRV_FETCH_ASSOC);
											$COUNT_WK = $result_working['COUNT_WK'];
											$success = array();
											$sql_success = "SELECT COUNT(RPRQ_WORKTYPE) COUNT_SC FROM REPAIRREQUEST LEFT JOIN REPAIRACTUAL_TIME ON REPAIRACTUAL_TIME.RPRQ_CODE = REPAIRREQUEST.RPRQ_CODE WHERE RPRQ_STATUS = 'Y' AND RPRQ_STATUSREQUEST IN('ซ่อมเสร็จสิ้น') AND RPRQ_AREA = '$SS_AREA' AND RPATTM_GROUP = 'SUCCESS' AND Convert(VARCHAR,CONVERT(DATETIME, RPATTM_PROCESS, 111),103) = '$SYSDATE'";
											$query_success = sqlsrv_query($conn, $sql_success);
											$result_success = sqlsrv_fetch_array($query_success, SQLSRV_FETCH_ASSOC);
											$COUNT_SC = $result_success['COUNT_SC'];

											// open Mechanic ##########################################
												$newdate=date("Y/m/d");
												$sql_cm_datenow = "SELECT
													(SELECT SUM(CAST(CM_READY AS DECIMAL(10,0))) FROM CHECKMAN WHERE CM_DATE='$newdate' AND CM_AREA='$SS_AREA') CM_READY,
													(SELECT SUM(CAST(CM_LEAVE AS DECIMAL(10,0))) FROM CHECKMAN WHERE CM_DATE='$newdate' AND CM_AREA='$SS_AREA') CM_LEAVE,
													(SELECT SUM(CAST(CM_TRAINNING AS DECIMAL(10,0))) FROM CHECKMAN WHERE CM_DATE='$newdate' AND CM_AREA='$SS_AREA') CM_TRAINNING,
													(SELECT SUM(CAST(CM_LICENSE AS DECIMAL(10,0))) FROM CHECKMAN WHERE CM_DATE='$newdate' AND CM_AREA='$SS_AREA') CM_LICENSE";
												$query_cm_datenow = sqlsrv_query($conn, $sql_cm_datenow);
												$result_cm_datenow = sqlsrv_fetch_array($query_cm_datenow, SQLSRV_FETCH_ASSOC);
												$sumrd=$result_cm_datenow['CM_READY'];
												$sumlv=$result_cm_datenow['CM_LEAVE'];
												$sumtn=$result_cm_datenow['CM_TRAINNING'];
												$sumlc=$result_cm_datenow['CM_LICENSE'];
												if($sumrd>0){$CMRD = $sumrd;}else{$CMRD = "0";}
												if($sumlv>0){$CMLV = $sumlv;}else{$CMLV = "0";}
												if($sumtn>0){$CMTN = $sumtn;}else{$CMTN = "0";}
												if($sumlc>0){$CMLC = $sumlc;}else{$CMLC = "0";}
											// close Mechanic ##########################################

										?>		
										<table>
											<tr class="text-center">
												<th width="10%"><p class="tb-text-right">&nbsp;</p></th>
												<th width="10%"></th>
											</tr>
										</table>
										<div class="spacetable"></div>				
										<table>
											<tr>
												<td class="tb-purple1" width="10%"><center><b><font class="tb-font-black">PMORS1</font></b></center></td>
												<td>&nbsp;&nbsp;<b><font size="4px"><?=$PMORS1?></font></b></td>
											</tr>			
										</table>
										<div class="spacetable"></div>		
										<table>
											<tr>
												<td class="tb-purple2" width="10%"><center><b><font class="tb-font-black">PMORS2</font></b></center></td>
												<td>&nbsp;&nbsp;<b><font size="4px"><?=$PMORS2?></font></b></td>
											</tr>				
										</table>
										<div class="spacetable"></div>
										<table>
											<tr>
												<td class="tb-info1" width="10%"><center><b><font class="tb-font-black">PMORS3</font></b></center></td>
												<td>&nbsp;&nbsp;<b><font size="4px"><?=$PMORS3?></font></b></td>
											</tr>			
										</table>
										<div class="spacetable"></div>
										<table>
											<tr>
												<td class="tb-info2" width="10%"><center><b><font class="tb-font-black">PMORS4</font></b></center></td>
												<td>&nbsp;&nbsp;<b><font size="4px"><?=$PMORS4?></font></b></td>
											</tr>			
										</table>
										<div class="spacetable"></div>
										<table>
											<tr>
												<td class="tb-yellow1" width="10%"><center><b><font class="tb-font-black">PMORS5</font></b></center></td>
												<td>&nbsp;&nbsp;<b><font size="4px"><?=$PMORS5?></font></b></td>
											</tr>			
										</table>
										<div class="spacetable"></div>
										<table>
											<tr>
												<td class="tb-yellow2" width="10%"><center><b><font class="tb-font-black">PMORS6</font></b></center></td>
												<td>&nbsp;&nbsp;<b><font size="4px"><?=$PMORS6?></font></b></td>
											</tr>			
										</table>
										<div class="spacetable"></div>
										<table>
											<tr>
												<td class="tb-yellow3" width="10%"><center><b><font class="tb-font-black">PMORS7</font></b></center></td>
												<td>&nbsp;&nbsp;<b><font size="4px"><?=$PMORS7?></font></b></td>
											</tr>			
										</table>
										<div class="spacetable"></div>
										<table>
											<tr>
												<td class="tb-pink1" width="10%"><center><b><font class="tb-font-black">PMORS8</font></b></center></td>
												<td>&nbsp;&nbsp;<b><font size="4px"><?=$PMORS8?></font></b></td>
											</tr>			
										</table>
										<div class="spacetable"></div>
										<table>
											<tr>
												<td class="tb-pink2" width="10%"><center><b><font class="tb-font-black">PMORS9</font></b></center></td>
												<td>&nbsp;&nbsp;<b><font size="4px"><?=$PMORS9?></font></b></td>
											</tr>			
										</table>
										<div class="spacetable"></div>
										<table>
											<tr>
												<!-- <td class="tb-red" width="10%"><center><b>BM</b></center></td> -->
												<td class="tb-brown" width="10%"><center><b><font class="tb-font-black">BM</font></b></center></td>
												<td>&nbsp;&nbsp;<b><font size="4px"><?=$COUNT_BM?></font></b></td>
											</tr>			
										</table>
										<div class="spacetable"></div>
										<table>
											<tr>
												<td class="tb-red" width="10%"><center><b><font class="tb-font-black">WORKING</font></b></center></td>
												<td>&nbsp;&nbsp;<b><font size="4px"><?=$COUNT_WK?></font></b></td>
											</tr>			
										</table>
										<div class="spacetable"></div>
										<table>
											<tr>
												<td class="tb-green2" width="10%"><center><b><font class="tb-font-black">SUCCESS</font></b></center></td>
												<td>&nbsp;&nbsp;<b><font size="4px"><?=$COUNT_SC?></font></b></td>
											</tr>			
										</table>
										<br>
										<br>&nbsp;
										<?php $RSPART=$CALPART;$RSSERVICE=$CALSERVICE; ?>
										<?php if($_SESSION['AD_ROLE_NAME']!='ช่างซ่อมบำรุง'){ ?>
											<b><font size="4px">Total Part <u><?=number_format($RSPART,2)?></u> | Service <u><?=number_format($RSSERVICE,2)?></u></font></b>
											<br>
											<br>&nbsp;
											<b><font size="4px">Today Mechanic &emsp;&emsp;&emsp;&emsp;<u><?=$CMRD?></u></font></b>
											<br>&nbsp;
											<b><font size="4px">Assignment</font></b>
											<br>&nbsp;
											<b><font size="4px">Training/Truck wash &emsp;&emsp;<u><?=$CMTN?></u></font></b>
											<br>&nbsp;
											<b><font size="4px">Today BE</font></b>
										<?php } ?>
									</div>
								</div>	
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>				
	</table>
	<center>
		<input type="button" class="button_red2" value="ย้อนกลับ" onclick="javascript:window.location.href='dashboard.php?menu_id=dashboard';">
	</center>
	<br><br>
</body>
</html>
<?php 

	if($SESSION_AREA=="AMT"){
		$SET2='2';
		$SET3='3';
		$SET4='4';
	}else{
		$SET2='9';
		$SET3='10';
		$SET4='11';
	}
	$sql_setting1="SELECT  * FROM SETTING WHERE ST_ID = '$SET2' AND ST_STATUS = 'Y'";
	$params_setting1 = array();	
	$query_setting1 = sqlsrv_query( $conn, $sql_setting1, $params_setting1);	
	$result_setting1 = sqlsrv_fetch_array($query_setting1, SQLSRV_FETCH_ASSOC);

	$sql_setting2="SELECT  * FROM SETTING WHERE ST_ID = '$SET3' AND ST_STATUS = 'Y'";
	$params_setting2 = array();	
	$query_setting2 = sqlsrv_query( $conn, $sql_setting2, $params_setting2);	
	$result_setting2 = sqlsrv_fetch_array($query_setting2, SQLSRV_FETCH_ASSOC);

	$sql_setting3="SELECT  * FROM SETTING WHERE ST_ID = '$SET4' AND ST_STATUS = 'Y'";
	$params_setting3 = array();	
	$query_setting3 = sqlsrv_query( $conn, $sql_setting3, $params_setting3);	
	$result_setting3 = sqlsrv_fetch_array($query_setting3, SQLSRV_FETCH_ASSOC);

?>

<?php if(isset($result_setting1["ST_ID"])){ $ST_TIME1 = $result_setting1["ST_TIME"]; ?>
	<?php if($_GET['menu_id']===26 || $_GET['menu_id']===62){ ?>
		<script type="text/javascript">
			$(function(){
			setInterval(function(){ 
				var getData=$.ajax({ 
					url:'../include/noncheckrequest.php',
					data: { 
					target: "1"
					},
					async:false,
					success:function(getData){
					$("span#checksidebm").html(getData); 
					}
				}).responseText;
			},<?=$ST_TIME1?>);    
			});
			$(function(){
			setInterval(function(){ 
				var getData=$.ajax({ 
					url:'../include/noncheckrequest.php',
					data: { 
					target: "2"
					},
					async:false,
					success:function(getData){
					$("span#checksidepm").html(getData); 
					}
				}).responseText;
			},<?=$ST_TIME1?>);    
			});
		</script>
	<?php } ?>
	<script type="text/javascript">
		$(function(){
			setInterval(function(){ 
				var getData=$.ajax({ 
						url:'../include/noncheckrequest.php',
						data: { 
							target: "3"
						},
						async:false,
						success:function(getData){
							$("span#checksidebmpm").html(getData); 
						}
				}).responseText;
			},<?=$ST_TIME1?>);    
		});
	</script>
<?php } ?>

<?php if(isset($result_setting2["ST_ID"])){ $ST_TIME2 = $result_setting2["ST_TIME"]; ?>
	<script type="text/javascript">
		$(function(){
			setInterval(function(){ 
				var getData=$.ajax({ 
						url:'../include/noncheckrequest.php',
						data: { 
							target: "4"
						},
						async:false,
						success:function(getData){
							$("span#checksideassign").html(getData); 
						}
				}).responseText;
			},<?=$ST_TIME2?>);    
		});
	</script>
<?php } ?>

<?php if(isset($result_setting3["ST_ID"])){ $ST_TIME3 = $result_setting3["ST_TIME"]; ?>
	<script type="text/javascript">
		$(function(){
			setInterval(function(){ 
				var getData=$.ajax({ 
						url:'../include/noncheckrequest.php',
						data: { 
							target: "5"
						},
						async:false,
						success:function(getData){
							$("span#checksidework").html(getData); 
						}
				}).responseText;
			},<?=$ST_TIME3?>);    
		});
	</script>
<?php } ?>