<?php
	session_name("EMS"); session_start();
	$path='../';
	require($path."include/authen.php"); 
	require($path."include/connect.php");
	require($path."include/head.php");		
	require($path."include/script.php"); 
	// print_r ($_SESSION);
	##########################################################################################################################
?>

<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<link rel="stylesheet" href="<?=$path;?>css/dashboard.css" />	
<body>
	<?php
		// echo"<pre>";
		// print_r($_GET); 
		// print_r($_POST);
		// echo"</pre>";
		// exit();	
		$SS_ROLE=$_SESSION["AD_ROLE_NAME"];
		$SS_AREA=$_SESSION["AD_AREA"];
		$GETDAYNOW = $DAYMONTH.$GETYEAREN;
		// ใบแจ้งซ่อมรอตรวจ
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
				$WAIT = $countnum;
			}else{
				$WAIT = "0";
			}
		// กำลังซ่อม/ค้างซ่อม
			$sql_rprq_doing = "SELECT COUNT(RPRQ_STATUSREQUEST) COUNTSTATUS FROM vwREPAIRREQUEST WHERE RPRQ_STATUSREQUEST = 'กำลังซ่อม' AND RPRQ_AREA = '$SS_AREA'";
			$query_rprq_doing = sqlsrv_query($conn, $sql_rprq_doing);
			$result_rprq_doing = sqlsrv_fetch_array($query_rprq_doing, SQLSRV_FETCH_ASSOC);
			$count1=$result_rprq_doing['COUNTSTATUS'];
			if($count1!=0){ 
				$DOING = $count1;
			}else{
				$DOING = "0";
			}
		// แผนซ่อมประจำวัน	
			$sql_rprq_datenow = "SELECT DISTINCT COUNT(A.RPRQ_CODE) COUNTSTATUS FROM REPAIRCAUSE A LEFT JOIN REPAIRREQUEST B ON B.RPRQ_CODE = A.RPRQ_CODE WHERE A.RPC_INCARDATE = '$GETDAYNOW' AND B.RPRQ_STATUS = 'Y' AND NOT B.RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอตรวจสอบ') AND B.RPRQ_AREA = '$SS_AREA'";
			$query_rprq_datenow = sqlsrv_query($conn, $sql_rprq_datenow);
			$result_rprq_datenow = sqlsrv_fetch_array($query_rprq_datenow, SQLSRV_FETCH_ASSOC);
			$count2=$result_rprq_datenow['COUNTSTATUS'];
			if($count2!=0){ 
				$NOW = $count2;
			}else{
				$NOW = "0";
			}
		// ช่างประจำวัน	
			$newdate=date("Y/m/d");
			$sql_cm_datenow = "SELECT SUM(CAST(CM_READY AS DECIMAL(10,0))) AS SUMREADY FROM CHECKMAN WHERE CM_DATE='$newdate' AND CM_AREA='$SS_AREA'";
			$query_cm_datenow = sqlsrv_query($conn, $sql_cm_datenow);
			$result_cm_datenow = sqlsrv_fetch_array($query_cm_datenow, SQLSRV_FETCH_ASSOC);
			$sumcm=$result_cm_datenow['SUMREADY'];
			if($sumcm!=0){ 
				$CMNOW = $sumcm;
			}else{
				$CMNOW = "0";
			}
		// ไม่อนุมัติซ่อม
			$sql_rprq_nap = "SELECT DISTINCT COUNT(A.RPRQ_NAP_OLD_CODE) COUNTSTATUS 
			FROM REPAIRREQUEST_NONAPPROVE A
			FULL JOIN REPAIRREQUEST B ON B.RPRQ_CODE = A.RPRQ_NAP_OLD_CODE
			WHERE B.RPRQ_AREA = '$SS_AREA' AND NOT B.RPRQ_STATUS = 'D' AND B.RPRQ_WORKTYPE = 'BM' AND A.RPRQ_NAP_STATUS = 'WAIT'";
			$query_rprq_nap = sqlsrv_query($conn, $sql_rprq_nap);
			$result_rprq_nap = sqlsrv_fetch_array($query_rprq_nap, SQLSRV_FETCH_ASSOC);
			$count_nap=$result_rprq_nap['COUNTSTATUS'];
			if($count_nap!=0){ 
				$NAP = $count_nap;
			}else{
				$NAP = "0";
			}
		// แยกพื้นที่
			if($SS_AREA=='AMT'){ 
				$idrepair = 19;
				$idapprove = 26;
				$checkrpm = 1;
			}else if($SS_AREA=='GW'){ 
				$idrepair = 56;
				$idapprove = 62;
				$checkrpm = 44;
			}
	?>
	<table width="100%"  height="100%"  border="0" cellpadding="0" cellspacing="0" class="no-border"> 
		<tr valign="top">
			<td height="1"><?php include ($path."include/navtop.php");?></td>
		</tr>
		<tr valign="top">
			<td>
				<br>
				<div class="row">
					<div class="col-12">
						<div class="row">
							<div class="col-1">&nbsp;</div>							
							<div class="col-2">
								<a href="dashboard_work.php?menu_id=dashboard">									
									<div class="small-box bg-info">
										<div class="inner">
											<h3><font color='white'><?php echo $NOW; ?></font></h3>
											<b><font color='white' size='4'>แผนซ่อมประจำวัน</font></b>
										</div>
										<div class="small-box-footer"><b><font color='white' size='3'>จัดการ</font></b></div>
									</div>
								</a>
							</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<div class="col-2"> 
								<?php if($SS_ROLE=='PLANNING'){?>
									<a href="<?=$path?>main/main_menu.php?menu_id=<?=$idrepair?>&getpage=repairpm">	
								<?php }else{ ?>
									<a href="<?=$path?>main/main_menu.php?menu_id=<?=$idrepair?>&getpage=repairbm">		
								<?php } ?>									
									<div class="small-box bg-warning">
										<div class="inner">
											<h3><font color='white'><?php echo $DOING; ?></font></h3>
											<b><font color='white' size='4'>กำลังซ่อม/ค้างซ่อม</font></b>
										</div>
										<div class="small-box-footer"><b><font color='white' size='3'>จัดการ</font></b></div>
									</div>
								</a>
							</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<div class="col-2">
								<?php if($SS_ROLE=='DRIVER'){?>
									<a href="#">	
								<?php }else if($SS_ROLE=='PLANNING'){?>
									<a href="#">	
								<?php }else if($SS_ROLE=='ช่างซ่อมบำรุง'){?>
									<a href="#">	
								<?php }else{ ?>
									<a href="<?=$path?>main/main_menu.php?menu_id=<?=$idapprove?>&getpage=approve">	
								<?php } ?>									
									<div class="small-box bg-danger">
										<div class="inner">
											<h3><font color='white'><?php echo $WAIT; ?></font></h3>
											<b><font color='white' size='4'>ใบแจ้งซ่อมรอตรวจ</font></b>						
										</div>
										<div class="small-box-footer"><b><font color='white' size='3'>จัดการ</font></b></div>
									</div>
								</a>
							</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<div class="col-2">
								<?php if($SS_ROLE=='DRIVER'){?>
									<a href="#">	
								<?php }else if($SS_ROLE=='PLANNING'){?>
									<a href="#">
								<?php }else if($SS_ROLE=='ช่างซ่อมบำรุง'){?>
									<a href="#">	
								<?php }else{ ?>
									<a href="<?=$path?>main/main_menu.php?menu_id=<?=$checkrpm?>&getpage=checkrpm">	
								<?php } ?>								
									<div class="small-box bg-success">
										<div class="inner">
											<h3><font color='white'><?php echo $CMNOW; ?></font></h3>
											<b><font color='white' size='4'>ช่างประจำวัน</font></b>						
										</div>
										<div class="small-box-footer"><b><font color='white' size='3'>จัดการ</font></b></div>
									</div>
								</a>
							</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<div class="col-2">
								<?php if($SS_ROLE=='DRIVER'){?>
									<a href="#">	
								<?php }else if($SS_ROLE=='PLANNING'){?>
									<a href="#">
								<?php }else if($SS_ROLE=='ช่างซ่อมบำรุง'){?>
									<a href="#">	
								<?php }else{ ?>
									<a href="<?=$path?>main/main_menu.php?menu_id=<?=$idrepair?>&getpage=chknap">		
								<?php } ?>								
									<div class="small-box bg-purple">
										<div class="inner">
											<h3><font color='white'><?php echo $NAP; ?></font></h3>
											<b><font color='white' size='4'>ไม่อนุมัติซ่อม</font></b>						
										</div>
										<div class="small-box-footer"><b><font color='white' size='3'>จัดการ</font></b></div>
									</div>
								</a>
							</div>
						</div>
						<div class="row">
							<div class="col-1">&nbsp;</div>					
							<div class="col-4 bgdark"><br>
								<h4><font color='white'>&nbsp;&nbsp;&nbsp;อันดับรถที่มีจำนวนครั้งการซ่อมมากที่สุด 5 อันดับแรก</font></h4>
								<div id="chart1" class="morris-chart"></div>
							</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							
							<div class="col-3 bgdark"><br>
								<h4><font color='white'>&nbsp;&nbsp;&nbsp;ประเภทงานซ่อม BM ที่เกิดขึ้นบ่อย</font></h4>
								<div id="chart2" class="morris-chart"></div>
							</div>
							<div class="col-3">							
								<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
									<tr class="TOP">
										<td class="LEFT"></td>
										<td class="CENTER">
											<table border="0" cellpadding="0" cellspacing="0">
												<tr>
												</tr>
											</table>
										</td>
										<td class="RIGHT"></td>
									</tr>
									<tr class="CENTER">
										<td class="LEFT"></td>
										<td class="CENTER" align="center">
											<table width="50%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
												<thead>
													<tr align="center">
														<th width="100%" valign="bottom"><strong>ประเภทงานซ่อม</strong></th>
													</tr>
												</thead>
												<tbody>			
													<?php
														$sql_typerepairwork = "SELECT A.TRPW_ID,A.TRPW_CODE,B.NTRP_NAME,A.TRPW_NAME,A.TRPW_REMARK,A.TRPW_AREA,A.TRPW_STATUS
														FROM dbo.TYPEREPAIRWORK AS A
														LEFT JOIN dbo.NATUREREPAIR AS B ON B.NTRP_ID = A.NTRP_ID
														WHERE NOT TRPW_STATUS IN('D','N') AND NTRP_AREA = '$SS_AREA'";
														$query_typerepairwork = sqlsrv_query($conn, $sql_typerepairwork);
														$no=0;
														while($result_typerepairwork = sqlsrv_fetch_array($query_typerepairwork, SQLSRV_FETCH_ASSOC)){	
															$no++;
															$TRPW_ID=$result_typerepairwork['TRPW_ID'];
															$TRPW_CODE=$result_typerepairwork['TRPW_CODE'];
															$NTRP_NAME=$result_typerepairwork['NTRP_NAME'];
															$TRPW_NAME=$result_typerepairwork['TRPW_NAME'];
															$TRPW_REMARK=$result_typerepairwork['TRPW_REMARK'];
															$TRPW_AREA=$result_typerepairwork['TRPW_AREA'];
															$TRPW_STATUS=$result_typerepairwork['TRPW_STATUS'];
													?>
													<tr style="height:28px;cursor:pointer">
														<td align="left" >&nbsp;<?php print $TRPW_NAME; ?></td>
													</tr>
													<?php }; ?>
												</tbody>
											</table>	
										</td>
										<td class="RIGHT"></td>
									</tr>
								</table>
							</div>	
						</div>
					</div>
				</div>
			</td>
		</tr>			
	</table>
</body>
</html>

<?php						
	$query="select * from account";
	$result=sqlsrv_query($conn,$query);
	$chart_data='';
	while ($row=sqlsrv_fetch_array($result)) {
		$chart_data.="{year:'".$row['year']."',value:'".$row['profit']."'},";
	}
	// echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$chart_data;
	
	$sql_rankcar1 = "WITH ROWNUMRANK1 AS
	(SELECT ROW_NUMBER() OVER(ORDER BY COUNT(a.RPRQ_REGISHEAD) DESC) AS 'ROWNUMRANK1',a.RPRQ_REGISHEAD,COUNT(a.RPRQ_REGISHEAD) CTN FROM REPAIRREQUEST a 
	WHERE a.RPRQ_STATUSREQUEST='ซ่อมเสร็จสิ้น' AND a.RPRQ_AREA = '$SS_AREA' GROUP BY a.RPRQ_REGISHEAD)
	select RPRQ_REGISHEAD,CTN from ROWNUMRANK1
	where ROWNUMRANK1 between 1 and 5 ORDER BY CTN ASC";
	$query_rankcar1 = sqlsrv_query($conn, $sql_rankcar1);
	$rankcar1='';
	while($result_rankcar1 = sqlsrv_fetch_array($query_rankcar1, SQLSRV_FETCH_ASSOC)){	
		$RPRQ_REGISHEAD1=$result_rankcar1['RPRQ_REGISHEAD'];
		$CTNRANK1=$result_rankcar1['CTN'];
		$rankcar1.="{year:'".$RPRQ_REGISHEAD1."',value:'".$CTNRANK1."'},";
	}
	if($rankcar1!=''){
		$rsrankcar1 = $rankcar1;
	}else{
		$rsrankcar1 = "{year:'0',value:'0'},";
	}
		
	$sql_ranktype1 = "WITH ROWNUMTYPE1 AS
	(SELECT DISTINCT ROW_NUMBER() OVER(ORDER BY COUNT(b.RPC_SUBJECT) DESC) AS 'ROWNUMTYPE1',a.RPRQ_WORKTYPE,b.RPC_SUBJECT,
		CASE
		WHEN b.RPC_SUBJECT = 'EL' THEN 'ระบบไฟ'
		WHEN b.RPC_SUBJECT = 'TU' THEN 'ยาง ช่วงล่าง'
		WHEN b.RPC_SUBJECT = 'BD' THEN 'โครงสร้าง' 
		WHEN b.RPC_SUBJECT = 'EG' THEN 'เครื่องยนต์' 
		WHEN b.RPC_SUBJECT = 'AC' THEN 'อุปกรณ์ประจำรถ'	
		ELSE b.RPC_SUBJECT END RPC_SUBJECT_CON,COUNT(b.RPC_SUBJECT) CTN
	 FROM REPAIRREQUEST a LEFT JOIN REPAIRCAUSE b ON a.RPRQ_CODE = b.RPRQ_CODE WHERE a.RPRQ_STATUSREQUEST= 'ซ่อมเสร็จสิ้น' AND a.RPRQ_WORKTYPE = 'BM' AND a.RPRQ_AREA = '$SS_AREA' GROUP BY a.RPRQ_WORKTYPE,b.RPC_SUBJECT)
	select RPC_SUBJECT_CON,CTN from ROWNUMTYPE1
	where ROWNUMTYPE1 between 1 and 5 ORDER BY CTN ASC";
	$query_ranktype1 = sqlsrv_query($conn, $sql_ranktype1);
	$ranktype1='';
	while($result_ranktype1 = sqlsrv_fetch_array($query_ranktype1, SQLSRV_FETCH_ASSOC)){	
		$RPC_SUBJECT_CON1=$result_ranktype1['RPC_SUBJECT_CON'];
		$CTNTYPE1=$result_ranktype1['CTN'];
		$ranktype1.="{year:'BM-".$RPC_SUBJECT_CON1."',value:'".$CTNTYPE1."'},";
	}
	if($ranktype1!=''){
		$rsranktype1 = $ranktype1;
	}else{
		$rsranktype1 = "{year:'0',value:'0'},";
	}

?>
<script type="text/javascript">
	Morris.Bar({
		element:'chart1',
		data:[<?php echo $rsrankcar1; ?>],
		xkey:'year',
		ykeys:['value'],
		labels:['จำนวนครั้ง'],
		xLabelAngle: 20,
		resize: true
	});
	Morris.Bar({
		element:'chart2',
		data:[<?php echo $rsranktype1; ?>],
		xkey:'year',
		ykeys:['value'],
		labels:['จำนวนครั้ง'],
		xLabelAngle: 20,
		resize: true
	});
</script>
<?php 
	$SESSION_AREA=$_SESSION["AD_AREA"];
	require($path."include/realtime.php"); 
?>