<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
    
	$SESSION_AREA = $_SESSION["AD_AREA"];
		$HREF_PDF="../views_amt/request_repair/request_repair_pdf.php?id='+a1+'&area=$SESSION_AREA";
?>
<html>
<head>
	<script type="text/javascript">

		function pdf_reportrepair_assign(a1) {
			window.open('<?=$HREF_PDF?>','_blank');
		}
		// $(document).ready(function(e) {
		// 	datepicker_thai_between('#dateStart');
		// });    
		function search_request(){
			var dateStart = $("#dateStart").val();
			var query = "?dateStart="+dateStart;
			loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php'+query);
		}
		function search_request_waitassign(){
			var query = "?dateStart=waitassign";
			loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php'+query);
		}
		function search_request_waitrepair(){
			var query = "?dateStart=waitrepair";
			loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php'+query);
		}
		function search_request_working(){
			var query = "?dateStart=working";
			loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php'+query);
		}
		function swaldelete_requestrepair(refcode,no) {
			Swal.fire({
				title: 'คุณแน่ใจหรือไม่...ที่จะยกเลิกรายการซ่อมของ ID หมายเลข '+no,
				text: "หากยกเลิกแล้ว คุณจะไม่สามารถกู้คืนข้อมูลได้!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#C82333',
				confirmButtonText: 'ใช่! ยกเลิกเลย',
				cancelButtonText: 'ยกเลิก'
			}).then((result) => {
				if (result.isConfirmed) {
					Swal.fire({
						icon: 'success',
						title: 'ยกเลิกเรียบร้อยแล้ว',
						showConfirmButton: false,
						timer: 2000
					}).then((result) => {	
						// if(!confirm("ยืนยันการลบข้อมูลอีกครั้ง")) return false;
						// var ref = getIdSelect(); 
						var ref = refcode; 
						var url = "<?=$path?>views_amt/assign_repairwork/assign_repairwork_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php');
								// alert(data);
							}
						});
					})	
				}
			})
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function(e) {
			$('.datepic').datetimepicker({
				timepicker:false,
				lang:'th',
				format:'d/m/Y',
				beforeShowDay: noWeekends,	
				closeOnDateSelect: true
			});
		});
	</script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="25" valign="middle" class=""><img src="https://img2.pic.in.th/pic/Downloads-alt-icon48.png" width="48" height="48"></td>
			<td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;มอบหมายงานซ่อม</h3></td>
			<td width="617" align="right" valign="bottom" class="" nowrap>
				<div class="toolbar">
					<!-- <button class="bg-color-blue" style="padding-top:8px;" title="New" id="button_new"><i class='icon-plus icon-large'></i></button> -->
					<!-- <button class="bg-color-yellow" style="padding-top:8px;" title="Edit" id="button_edit"><i class='icon-pencil icon-large'></i></button> -->
					<!-- <button class="bg-color-red" style="padding-top:8px;" title="Del" id="button_delete"><i class="icon-cancel icon-large"></i></button> -->
				</div>
			</td>
		</tr>
    </table></td>
    <td class="RIGHT"></td>
  </tr>
    <?php           
        if($_GET['dateStart']!=""){
			if($_GET['dateStart']=="waitassign"){
				$wh="AND 1=1 AND RPRQ_STATUSREQUEST = 'รอจ่ายงาน'";
				$display_head="<h4>สถานะ <font color='blue'>รอจ่ายงาน</font> ทั้งหมด</h4>";
			}else if($_GET['dateStart']=="waitrepair"){
				$wh="AND 1=1 AND RPRQ_STATUSREQUEST = 'รอคิวซ่อม'";
				$display_head="<h4>สถานะ <font color='blue'>รอคิวซ่อม</font> ทั้งหมด</h4>";
			}else if($_GET['dateStart']=="working"){
				$wh="AND 1=1 AND RPRQ_STATUSREQUEST = 'กำลังซ่อม'";
				$display_head="<h4>สถานะ <font color='blue'>กำลังซ่อม</font> ทั้งหมด</h4>";
			}else{
				$getday = $_GET['dateStart'];			
				$sql_getdate_d1d7="SELECT
					CONVERT(VARCHAR,DATEADD(DAY,0,CONVERT(DATETIME,'$getday',103)),103) AS 'D1',
					CONVERT(VARCHAR,DATEADD(DAY,6,CONVERT(DATETIME,'$getday',103)),103) AS 'D7'";
				$params_getdate_d1d7 = array();	
				$query_getdate_d1d7 = sqlsrv_query( $conn, $sql_getdate_d1d7, $params_getdate_d1d7);	
				$result_getdate_d1d7 = sqlsrv_fetch_array($query_getdate_d1d7, SQLSRV_FETCH_ASSOC);
				$DAYSTARTWEEK = $result_getdate_d1d7['D1'];
				$DAYENDWEEK = $result_getdate_d1d7['D7'];				
				// $wh="AND RPRQ_CREATEDATE_REQUEST BETWEEN '$DAYSTARTWEEK' AND '$DAYENDWEEK'";
				// $wh="AND RPRQ_REQUESTCARDATE = ";
				// $wh="AND RPRQ_CREATEDATE_REQUEST = ";
				$wh="AND RPC_INCARDATE = ";
				$display_head="<h4>ข้อมูลประจำวันที่ $DAYSTARTWEEK - $DAYENDWEEK</h4>";
			}
        }else{
            $getday = $STARTWEEK;
            $DAYSTARTWEEK = $STARTWEEK;
            $DAYENDWEEK = $ENDWEEK;
			// $wh="AND RPRQ_CREATEDATE_REQUEST BETWEEN '$DAYSTARTWEEK' AND '$DAYENDWEEK'";
			// $wh="AND RPRQ_REQUESTCARDATE = ";
			// $wh="AND RPRQ_CREATEDATE_REQUEST = ";
			$wh="AND RPC_INCARDATE = ";
			$display_head="<h4>ข้อมูลประจำวันที่ $DAYSTARTWEEK - $DAYENDWEEK</h4>";
        }
        
        $SESSION_AREA = $_SESSION["AD_AREA"];
    ?>
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center">
        <br>          
        <table>
            <tbody>
                <tr height="25px" align="center">                               
                    <td width="15%" align="center">
                        <div class="row input-control">           
							<input type="text" name="dateStart" id="dateStart" class="datepic time" placeholder="วันที่เริ่มต้นสัปดาห์" autocomplete="off" value="<?=$getday;?>" onchange="search_request()">         
                            <!-- <input type="text" name="dateStart" id="dateStart" class="time" placeholder="วันที่เริ่มต้นสัปดาห์" style="date" value="<?=$getday;?>" onchange="search_request()" autocomplete="off"/> -->
                        </div>
                    </td>                
                    <!-- <td width="1%" align="center">&nbsp;</td>            
                    <td width="15%" align="center">
                        <div class="row input-control">            
                            <input type="text" name="dateEnd" id="dateEnd" class="time" placeholder="วันที่ค้นหาสิ้นสุด" style="date" value="" />
                        </div>
                    </td> -->
                    <!-- <td width="10%" align="center">
                        <button class="bg-color-blue" onclick="search_request()"><font color="white"><i class="icon-search"></i> ค้นหา</font></button>
                    </td> -->
                    <td width="20%" align="center">
                        <button class="bg-color-blue" onclick="search_request_waitassign()"><b><font color="white"><i class="icon-search"></i> สถานะ "รอจ่ายงาน" ทั้งหมด</font></b></button>
                    </td>
                    <td width="20%" align="center">
                        <button class="bg-color-yellow" onclick="search_request_waitrepair()"><b><font color="black"><i class="icon-search"></i> สถานะ "รอคิวซ่อม" ทั้งหมด</font></b></button>
                    </td>
                    <td width="20%" align="center">
                        <button class="bg-color-red" onclick="search_request_working()"><b><font color="white"><i class="icon-search"></i> สถานะ "กำลังซ่อม" ทั้งหมด</font></b></button>
                    </td>
                    <td align="center"></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>  
		<form id="form1" name="form1" method="post" action="#">
			<?=$display_head;?>
			<?php			
			if(($_GET['dateStart']!="waitassign")||($_GET['dateStart']!="waitrepair")||($_GET['dateStart']!="working")){
				$sql_getdate_d1d7="SELECT CONVERT(VARCHAR,DATEADD(DAY,6,CONVERT(DATETIME,'$getday',103)),103) AS 'ADDDATEWEEK',
					CONVERT(VARCHAR,DATEADD(DAY,0,CONVERT(DATETIME,'$getday',103)),103) AS 'D1',
					CONVERT(VARCHAR,DATEADD(DAY,1,CONVERT(DATETIME,'$getday',103)),103) AS 'D2',
					CONVERT(VARCHAR,DATEADD(DAY,2,CONVERT(DATETIME,'$getday',103)),103) AS 'D3',
					CONVERT(VARCHAR,DATEADD(DAY,3,CONVERT(DATETIME,'$getday',103)),103) AS 'D4',
					CONVERT(VARCHAR,DATEADD(DAY,4,CONVERT(DATETIME,'$getday',103)),103) AS 'D5',
					CONVERT(VARCHAR,DATEADD(DAY,5,CONVERT(DATETIME,'$getday',103)),103) AS 'D6',
					CONVERT(VARCHAR,DATEADD(DAY,6,CONVERT(DATETIME,'$getday',103)),103) AS 'D7'";
				$params_getdate_d1d7 = array();	
				$query_getdate_d1d7 = sqlsrv_query( $conn, $sql_getdate_d1d7, $params_getdate_d1d7);	
				$result_getdate_d1d7 = sqlsrv_fetch_array($query_getdate_d1d7, SQLSRV_FETCH_ASSOC);
				$D1=$result_getdate_d1d7["D1"];
				$D2=$result_getdate_d1d7["D2"];
				$D3=$result_getdate_d1d7["D3"];
				$D4=$result_getdate_d1d7["D4"];
				$D5=$result_getdate_d1d7["D5"];
				$D6=$result_getdate_d1d7["D6"];
				$D7=$result_getdate_d1d7["D7"];
			}
			?>
			<div id="tabs_menu" style="height:100%;">
				<ul>
					<?php if(($_GET['dateStart']!="waitassign")&&($_GET['dateStart']!="waitrepair")&&($_GET['dateStart']!="working")){ ?>
						<li><a href="#tabs-1"><span style="font-size:13px">อาทิตย์ (<?=$result_getdate_d1d7["D1"]?>)</span></a></li>
						<li><a href="#tabs-2"><span style="font-size:13px">จันทร์ (<?=$result_getdate_d1d7["D2"]?>)</span></a></li>
						<li><a href="#tabs-3"><span style="font-size:13px">อังคาร (<?=$result_getdate_d1d7["D3"]?>)</span></a></li>
						<li><a href="#tabs-4"><span style="font-size:13px">พุธ (<?=$result_getdate_d1d7["D4"]?>)</span></a></li>
						<li><a href="#tabs-5"><span style="font-size:13px">พฤหัสฯ (<?=$result_getdate_d1d7["D5"]?>)</span></a></li>
						<li><a href="#tabs-6"><span style="font-size:13px">ศุกร์ (<?=$result_getdate_d1d7["D6"]?>)</span></a></li>
						<li><a href="#tabs-7"><span style="font-size:13px">เสาร์ (<?=$result_getdate_d1d7["D7"]?>)</span></a></li>
					<?php }else { ?>
						<li><a href="#tabs-1"><span style="font-size:13px">ข้อมูลทั้งหมด</span></a></li>
					<?php } ?>
				</ul>  
				<div id="tabs-1">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable1"><!-- default hover pointer display hover pointer -->
						<thead>
							<tr height="30">
								<th rowspan="2" align="center" width="5%">เลขที่ใบขอซ่อม</th>								
								<?php
									if(($_GET['dateStart']=="waitassign")||($_GET['dateStart']=="waitrepair")||($_GET['dateStart']=="working")){
										echo "<th rowspan='2' align='center' width='15%'>วันที่นำรถเข้าซ่อม";
									}
								?>
								<th rowspan="2" align="center" width="3%">สถานะ</th>
								<th rowspan="2" align="center" width="3%">พิมพ์</th>
								<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
								<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
								<th rowspan="2" align="center" width="8%">ลักษณะงานซ่อม</th>
								<th rowspan="2" align="center" width="22%">ปัญหาก่อนการซ่อม</th>
								<th colspan="3" align="center" width="15%" class="ui-state-default">จัดการ</th>
							</tr>
							<tr height="30">
								<th align="center"width="5%">ทะเบียน</th>
								<th align="center"width="10%">ชื่อรถ</th>           
								<th align="center"width="5%">ทะเบียน</th>
								<th align="center"width="10%">ชื่อรถ</th> 
								<th align="center"width="50%">จ่ายงาน</th>    
								<!-- <th align="center"width="5%">ยกเลิกซ่อม</th> -->
								<th align="center"width="50%">ลบ</th>    
							</tr>
						</thead>
						<tbody>
							<?php
								if(($_GET['dateStart']=="waitassign")||($_GET['dateStart']=="waitrepair")||($_GET['dateStart']=="working")){
									$sql_assign_D1 = "SELECT DISTINCT RPRQ_WORKTYPE,RPC_SUBJECT_CON_PM,RPRQ_ID,RPRQ_CODE,RPRQ_REGISHEAD,RPRQ_CARNAMEHEAD,RPRQ_REGISTAIL,RPRQ_CARNAMETAIL,RPRQ_REQUESTCARDATE,RPRQ_REQUESTCARTIME,RPC_SUBJECT_CON,RPRQ_STATUSREQUEST,RPC_DETAIL,RPC_INCARDATE,RPC_INCARTIME FROM dbo.vwASSIGN WHERE	RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอคิวซ่อม','กำลังซ่อม') AND RPRQ_AREA = '$SESSION_AREA' ".$wh."";
								}else{
									$sql_assign_D1 = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh." '$D1'";
								}
								$query_assign_D1 = sqlsrv_query($conn, $sql_assign_D1);
								$no=0;
								while($result_assign_D1 = sqlsrv_fetch_array($query_assign_D1, SQLSRV_FETCH_ASSOC)){	
									$no++;
							?>
							<tr id="<?php print $result_assign_D1['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
								<td align="center"><?php print $result_assign_D1['RPRQ_ID']; ?></td>   			
								<?php
									if(($_GET['dateStart']=="waitassign")||($_GET['dateStart']=="waitrepair")||($_GET['dateStart']=="working")){
										if(isset($result_assign_D1['RPC_INCARDATE'])){
											echo '<td align="center">'.$result_assign_D1["RPC_INCARDATE"].' เวลา '.$result_assign_D1["RPC_INCARTIME"].' น.</td>';
										}else{
											echo '<td align="center">'.$result_assign_D1["RPRQ_REQUESTCARDATE"].' เวลา '.$result_assign_D1["RPRQ_REQUESTCARTIME"].' น.</td>';
										}
									}
								?>              
								<td align="center">               
									<?php
										switch($result_assign_D1['RPRQ_STATUSREQUEST']) {
											case "รอตรวจสอบ":
												$text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
											break;
											case "รอคิวซ่อม":
												$text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
											break;
											case "กำลังซ่อม":
												$text="<strong><font color='red'>กำลังซ่อม</font></strong>";
											break;
											case "ซ่อมเสร็จสิ้น":
												$text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
											break;
											case "รอจ่ายงาน":
												$text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
											break;
											case "ไม่อนุมัติ":
												$text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
											break;
										}
										print $text;
									?>
								</td>   		                
								<td align="center">
									<button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_assign('<?php print $result_assign_D1['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
								</td>
								<td align="center"><?php print $result_assign_D1['RPRQ_REGISHEAD']; ?></td>
								<td align="center"><?php print $result_assign_D1['RPRQ_CARNAMEHEAD']; ?></td>
								<td align="center"><?php print $result_assign_D1['RPRQ_REGISTAIL']; ?></td>
								<td align="center"><?php print $result_assign_D1['RPRQ_CARNAMETAIL']; ?></td>
								<td align="left"><?php print $result_assign_D1['RPC_SUBJECT_CON']; ?></td>
								<td align="left">
									<?php print $result_assign_D1['RPC_DETAIL']; ?>
									<?php
										if($result_assign_D1['RPRQ_WORKTYPE']=='PM'){
											echo ' : '.$result_assign_D1['RPC_SUBJECT_CON_PM'];
										}
									?>
								</td>
								<?php if($result_assign_D1['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
									<td align="center" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $result_assign_D1['RPRQ_CODE'];; ?>','1=1','1300','570','มอบหมายงานซ่อม');">
										<img src="https://img2.pic.in.th/pic/Process-Info-icon24.png" width="24" height="24">
									</td>
								<?php }else{ ?>									
									<td align="center"></td>
								<?php } ?>
								<?php if($result_assign_D1['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
									<td align="center" >
										<img src="https://img2.pic.in.th/pic/delete-icon24.png" width="20" height="20" onclick="swaldelete_requestrepair('<?php print $result_assign_D1['RPRQ_CODE']; ?>','<?php print $result_assign_D1['RPRQ_ID']; ?>')">
									</td>
								<?php }else{ ?>									
									<td align="center"></td>
								<?php } ?>
							</tr>
							<?php }; ?>
						</tbody>
					</table> 
				</div>
				<?php if(($_GET['dateStart']!="waitassign")&&($_GET['dateStart']!="waitrepair")&&($_GET['dateStart']!="working")){ ?>
					<div id="tabs-2">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable2"><!-- default hover pointer display hover pointer -->
							<thead>
								<tr height="30">
									<th rowspan="2" align="center" width="5%">เลขที่ใบขอซ่อม</th>
									<th rowspan="2" align="center" width="3%">สถานะ</th>
									<th rowspan="2" align="center" width="3%">พิมพ์</th>
									<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
									<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
									<th rowspan="2" align="center" width="8%">ลักษณะงานซ่อม</th>
									<th rowspan="2" align="center" width="22%">ปัญหาก่อนการซ่อม</th>
									<th colspan="3" align="center" width="15%" class="ui-state-default">จัดการ</th>
								</tr>
								<tr height="30">
									<th align="center"width="5%">ทะเบียน</th>
									<th align="center"width="10%">ชื่อรถ</th>           
									<th align="center"width="5%">ทะเบียน</th>
									<th align="center"width="10%">ชื่อรถ</th> 
									<th align="center"width="50%">จ่ายงาน</th>   
									<!-- <th align="center"width="5%">ยกเลิกซ่อม</th> -->    
									<th align="center"width="50%">ลบ</th>    
								</tr>
							</thead>
							<tbody>
								<?php
									// $sql_assign = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh."";
									$sql_assign_D2 = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh." '$D2'";
									$query_assign_D2 = sqlsrv_query($conn, $sql_assign_D2);
									$no=0;
									while($result_assign_D2 = sqlsrv_fetch_array($query_assign_D2, SQLSRV_FETCH_ASSOC)){	
										$no++;
								?>
								<tr id="<?php print $result_assign_D2['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
									<td align="center"><?php print $result_assign_D2['RPRQ_ID']; ?></td>                
									<td align="center">               
										<?php
											switch($result_assign_D2['RPRQ_STATUSREQUEST']) {
												case "รอตรวจสอบ":
													$text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
												break;
												case "รอคิวซ่อม":
													$text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
												break;
												case "กำลังซ่อม":
													$text="<strong><font color='red'>กำลังซ่อม</font></strong>";
												break;
												case "ซ่อมเสร็จสิ้น":
													$text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
												break;
												case "รอจ่ายงาน":
													$text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
												break;
												case "ไม่อนุมัติ":
													$text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
												break;
											}
											print $text;
										?>
									</td>                      
									<td align="center">
										<button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_assign('<?php print $result_assign_D2['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
									</td>
									<td align="center"><?php print $result_assign_D2['RPRQ_REGISHEAD']; ?></td>
									<td align="center"><?php print $result_assign_D2['RPRQ_CARNAMEHEAD']; ?></td>
									<td align="center"><?php print $result_assign_D2['RPRQ_REGISTAIL']; ?></td>
									<td align="center"><?php print $result_assign_D2['RPRQ_CARNAMETAIL']; ?></td>
									<td align="left"><?php print $result_assign_D2['RPC_SUBJECT_CON']; ?></td>
									<td align="left"><?php print $result_assign_D2['RPC_DETAIL']; ?></td>
									<?php if($result_assign_D2['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
										<td align="center" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $result_assign_D2['RPRQ_CODE'];; ?>','1=1','1300','570','มอบหมายงานซ่อม');">
											<img src="https://img2.pic.in.th/pic/Process-Info-icon24.png" width="24" height="24">
										</td>
									<?php }else{ ?>									
										<td align="center"></td>
									<?php } ?>
									<?php if($result_assign_D2['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
										<td align="center" >
											<img src="https://img2.pic.in.th/pic/delete-icon24.png" width="20" height="20" onclick="swaldelete_requestrepair('<?php print $result_assign_D2['RPRQ_CODE']; ?>','<?php print $result_assign_D2['RPRQ_ID']; ?>')">
										</td>
									<?php }else{ ?>									
										<td align="center"></td>
									<?php } ?>
								</tr>
								<?php }; ?>
							</tbody>
						</table> 
					</div>
					<div id="tabs-3">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable3"><!-- default hover pointer display hover pointer -->
							<thead>
								<tr height="30">
									<th rowspan="2" align="center" width="5%">เลขที่ใบขอซ่อม</th>
									<th rowspan="2" align="center" width="3%">สถานะ</th>
									<th rowspan="2" align="center" width="3%">พิมพ์</th>
									<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
									<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
									<th rowspan="2" align="center" width="8%">ลักษณะงานซ่อม</th>
									<th rowspan="2" align="center" width="22%">ปัญหาก่อนการซ่อม</th>
									<th colspan="3" align="center" width="15%" class="ui-state-default">จัดการ</th>
								</tr>
								<tr height="30">
									<th align="center"width="5%">ทะเบียน</th>
									<th align="center"width="10%">ชื่อรถ</th>           
									<th align="center"width="5%">ทะเบียน</th>
									<th align="center"width="10%">ชื่อรถ</th> 
									<th align="center"width="50%">จ่ายงาน</th>   
									<!-- <th align="center"width="5%">ยกเลิกซ่อม</th> -->    
									<th align="center"width="50%">ลบ</th>    
								</tr>
							</thead>
							<tbody>
								<?php
									// $sql_assign = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh."";
									$sql_assign_D3 = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh." '$D3'";
									$query_assign_D3 = sqlsrv_query($conn, $sql_assign_D3);
									$no=0;
									while($result_assign_D3 = sqlsrv_fetch_array($query_assign_D3, SQLSRV_FETCH_ASSOC)){	
										$no++;
								?>
								<tr id="<?php print $result_assign_D3['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
									<td align="center"><?php print $result_assign_D3['RPRQ_ID']; ?></td>                    
									<td align="center">               
										<?php
											switch($result_assign_D3['RPRQ_STATUSREQUEST']) {
												case "รอตรวจสอบ":
													$text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
												break;
												case "รอคิวซ่อม":
													$text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
												break;
												case "กำลังซ่อม":
													$text="<strong><font color='red'>กำลังซ่อม</font></strong>";
												break;
												case "ซ่อมเสร็จสิ้น":
													$text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
												break;
												case "รอจ่ายงาน":
													$text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
												break;
												case "ไม่อนุมัติ":
													$text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
												break;
											}
											print $text;
										?>
									</td>                        
									<td align="center">
										<button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_assign('<?php print $result_assign_D3['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
									</td>
									<td align="center"><?php print $result_assign_D3['RPRQ_REGISHEAD']; ?></td>
									<td align="center"><?php print $result_assign_D3['RPRQ_CARNAMEHEAD']; ?></td>
									<td align="center"><?php print $result_assign_D3['RPRQ_REGISTAIL']; ?></td>
									<td align="center"><?php print $result_assign_D3['RPRQ_CARNAMETAIL']; ?></td>
									<td align="left"><?php print $result_assign_D3['RPC_SUBJECT_CON']; ?></td>
									<td align="left"><?php print $result_assign_D3['RPC_DETAIL']; ?></td>
									<?php if($result_assign_D3['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
										<td align="center" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $result_assign_D3['RPRQ_CODE'];; ?>','1=1','1300','570','มอบหมายงานซ่อม');">
											<img src="https://img2.pic.in.th/pic/Process-Info-icon24.png" width="24" height="24">
										</td>
									<?php }else{ ?>									
										<td align="center"></td>
									<?php } ?>
									<?php if($result_assign_D3['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
										<td align="center" >
											<img src="https://img2.pic.in.th/pic/delete-icon24.png" width="20" height="20" onclick="swaldelete_requestrepair('<?php print $result_assign_D3['RPRQ_CODE']; ?>','<?php print $result_assign_D3['RPRQ_ID']; ?>')">
										</td>
									<?php }else{ ?>									
										<td align="center"></td>
									<?php } ?>
								</tr>
								<?php }; ?>
							</tbody>
						</table> 
					</div>
					<div id="tabs-4">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable4"><!-- default hover pointer display hover pointer -->
							<thead>
								<tr height="30">
									<th rowspan="2" align="center" width="5%">เลขที่ใบขอซ่อม</th>
									<th rowspan="2" align="center" width="3%">สถานะ</th>
									<th rowspan="2" align="center" width="3%">พิมพ์</th>
									<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
									<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
									<th rowspan="2" align="center" width="8%">ลักษณะงานซ่อม</th>
									<th rowspan="2" align="center" width="22%">ปัญหาก่อนการซ่อม</th>
									<th colspan="3" align="center" width="15%" class="ui-state-default">จัดการ</th>
								</tr>
								<tr height="30">
									<th align="center"width="5%">ทะเบียน</th>
									<th align="center"width="10%">ชื่อรถ</th>           
									<th align="center"width="5%">ทะเบียน</th>
									<th align="center"width="10%">ชื่อรถ</th> 
									<th align="center"width="50%">จ่ายงาน</th>   
									<!-- <th align="center"width="5%">ยกเลิกซ่อม</th> -->    
									<th align="center"width="50%">ลบ</th>    
								</tr>
							</thead>
							<tbody>
								<?php
									// $sql_assign = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh."";
									$sql_assign_D4 = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh." '$D4'";
									$query_assign_D4 = sqlsrv_query($conn, $sql_assign_D4);
									$no=0;
									while($result_assign_D4 = sqlsrv_fetch_array($query_assign_D4, SQLSRV_FETCH_ASSOC)){	
										$no++;
								?>
								<tr id="<?php print $result_assign_D4['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
									<td align="center"><?php print $result_assign_D4['RPRQ_ID']; ?></td>                  
									<td align="center">               
										<?php
											switch($result_assign_D4['RPRQ_STATUSREQUEST']) {
												case "รอตรวจสอบ":
													$text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
												break;
												case "รอคิวซ่อม":
													$text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
												break;
												case "กำลังซ่อม":
													$text="<strong><font color='red'>กำลังซ่อม</font></strong>";
												break;
												case "ซ่อมเสร็จสิ้น":
													$text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
												break;
												case "รอจ่ายงาน":
													$text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
												break;
												case "ไม่อนุมัติ":
													$text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
												break;
											}
											print $text;
										?>
									</td>                          
									<td align="center">
										<button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_assign('<?php print $result_assign_D4['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
									</td>
									<td align="center"><?php print $result_assign_D4['RPRQ_REGISHEAD']; ?></td>
									<td align="center"><?php print $result_assign_D4['RPRQ_CARNAMEHEAD']; ?></td>
									<td align="center"><?php print $result_assign_D4['RPRQ_REGISTAIL']; ?></td>
									<td align="center"><?php print $result_assign_D4['RPRQ_CARNAMETAIL']; ?></td>
									<td align="left"><?php print $result_assign_D4['RPC_SUBJECT_CON']; ?></td>
									<td align="left"><?php print $result_assign_D4['RPC_DETAIL']; ?></td>
									<?php if($result_assign_D4['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
										<td align="center" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $result_assign_D4['RPRQ_CODE'];; ?>','1=1','1300','570','มอบหมายงานซ่อม');">
											<img src="https://img2.pic.in.th/pic/Process-Info-icon24.png" width="24" height="24">
										</td>
									<?php }else{ ?>									
										<td align="center"></td>
									<?php } ?>
									<?php if($result_assign_D4['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
										<td align="center" >
											<img src="https://img2.pic.in.th/pic/delete-icon24.png" width="20" height="20" onclick="swaldelete_requestrepair('<?php print $result_assign_D4['RPRQ_CODE']; ?>','<?php print $result_assign_D4['RPRQ_ID']; ?>')">
										</td>
									<?php }else{ ?>									
										<td align="center"></td>
									<?php } ?>
								</tr>
								<?php }; ?>
							</tbody>
						</table> 
					</div>
					<div id="tabs-5">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable5"><!-- default hover pointer display hover pointer -->
							<thead>
								<tr height="30">
									<th rowspan="2" align="center" width="5%">เลขที่ใบขอซ่อม</th>
									<th rowspan="2" align="center" width="3%">สถานะ</th>
									<th rowspan="2" align="center" width="3%">พิมพ์</th>
									<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
									<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
									<th rowspan="2" align="center" width="8%">ลักษณะงานซ่อม</th>
									<th rowspan="2" align="center" width="22%">ปัญหาก่อนการซ่อม</th>
									<th colspan="3" align="center" width="15%" class="ui-state-default">จัดการ</th>
								</tr>
								<tr height="30">
									<th align="center"width="5%">ทะเบียน</th>
									<th align="center"width="10%">ชื่อรถ</th>           
									<th align="center"width="5%">ทะเบียน</th>
									<th align="center"width="10%">ชื่อรถ</th> 
									<th align="center"width="50%">จ่ายงาน</th>   
									<!-- <th align="center"width="5%">ยกเลิกซ่อม</th> -->    
									<th align="center"width="50%">ลบ</th>    
								</tr>
							</thead>
							<tbody>
								<?php
									// $sql_assign = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh."";
									$sql_assign_D5 = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh." '$D5'";
									$query_assign_D5 = sqlsrv_query($conn, $sql_assign_D5);
									$no=0;
									while($result_assign_D5 = sqlsrv_fetch_array($query_assign_D5, SQLSRV_FETCH_ASSOC)){	
										$no++;
								?>
								<tr id="<?php print $result_assign_D5['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
									<td align="center"><?php print $result_assign_D5['RPRQ_ID']; ?></td>                  
									<td align="center">               
										<?php
											switch($result_assign_D5['RPRQ_STATUSREQUEST']) {
												case "รอตรวจสอบ":
													$text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
												break;
												case "รอคิวซ่อม":
													$text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
												break;
												case "กำลังซ่อม":
													$text="<strong><font color='red'>กำลังซ่อม</font></strong>";
												break;
												case "ซ่อมเสร็จสิ้น":
													$text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
												break;
												case "รอจ่ายงาน":
													$text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
												break;
												case "ไม่อนุมัติ":
													$text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
												break;
											}
											print $text;
										?>
									</td>                          
									<td align="center">
										<button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_assign('<?php print $result_assign_D5['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
									</td>
									<td align="center"><?php print $result_assign_D5['RPRQ_REGISHEAD']; ?></td>
									<td align="center"><?php print $result_assign_D5['RPRQ_CARNAMEHEAD']; ?></td>
									<td align="center"><?php print $result_assign_D5['RPRQ_REGISTAIL']; ?></td>
									<td align="center"><?php print $result_assign_D5['RPRQ_CARNAMETAIL']; ?></td>
									<td align="left"><?php print $result_assign_D5['RPC_SUBJECT_CON']; ?></td>
									<td align="left"><?php print $result_assign_D5['RPC_DETAIL']; ?></td>
									<?php if($result_assign_D5['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
										<td align="center" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $result_assign_D5['RPRQ_CODE'];; ?>','1=1','1300','570','มอบหมายงานซ่อม');">
											<img src="https://img2.pic.in.th/pic/Process-Info-icon24.png" width="24" height="24">
										</td>
									<?php }else{ ?>									
										<td align="center"></td>
									<?php } ?>
									<?php if($result_assign_D5['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
										<td align="center" >
											<img src="https://img2.pic.in.th/pic/delete-icon24.png" width="20" height="20" onclick="swaldelete_requestrepair('<?php print $result_assign_D5['RPRQ_CODE']; ?>','<?php print $result_assign_D5['RPRQ_ID']; ?>')">
										</td>
									<?php }else{ ?>									
										<td align="center"></td>
									<?php } ?>
								</tr>
								<?php }; ?>
							</tbody>
						</table> 
					</div>
					<div id="tabs-6">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable6"><!-- default hover pointer display hover pointer -->
							<thead>
								<tr height="30">
									<th rowspan="2" align="center" width="5%">เลขที่ใบขอซ่อม</th>
									<th rowspan="2" align="center" width="3%">สถานะ</th>
									<th rowspan="2" align="center" width="3%">พิมพ์</th>
									<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
									<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
									<th rowspan="2" align="center" width="8%">ลักษณะงานซ่อม</th>
									<th rowspan="2" align="center" width="22%">ปัญหาก่อนการซ่อม</th>
									<th colspan="3" align="center" width="15%" class="ui-state-default">จัดการ</th>
								</tr>
								<tr height="30">
									<th align="center"width="5%">ทะเบียน</th>
									<th align="center"width="10%">ชื่อรถ</th>           
									<th align="center"width="5%">ทะเบียน</th>
									<th align="center"width="10%">ชื่อรถ</th> 
									<th align="center"width="50%">จ่ายงาน</th>   
									<!-- <th align="center"width="5%">ยกเลิกซ่อม</th> -->    
									<th align="center"width="50%">ลบ</th>    
								</tr>
							</thead>
							<tbody>
								<?php
									// $sql_assign = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh."";
									$sql_assign_D6 = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh." '$D6'";
									$query_assign_D6 = sqlsrv_query($conn, $sql_assign_D6);
									$no=0;
									while($result_assign_D6 = sqlsrv_fetch_array($query_assign_D6, SQLSRV_FETCH_ASSOC)){	
										$no++;
								?>
								<tr id="<?php print $result_assign_D6['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
									<td align="center"><?php print $result_assign_D6['RPRQ_ID']; ?></td>                 
									<td align="center">               
										<?php
											switch($result_assign_D6['RPRQ_STATUSREQUEST']) {
												case "รอตรวจสอบ":
													$text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
												break;
												case "รอคิวซ่อม":
													$text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
												break;
												case "กำลังซ่อม":
													$text="<strong><font color='red'>กำลังซ่อม</font></strong>";
												break;
												case "ซ่อมเสร็จสิ้น":
													$text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
												break;
												case "รอจ่ายงาน":
													$text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
												break;
												case "ไม่อนุมัติ":
													$text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
												break;
											}
											print $text;
										?>
									</td>                           
									<td align="center">
										<button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_assign('<?php print $result_assign_D6['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
									</td>
									<td align="center"><?php print $result_assign_D6['RPRQ_REGISHEAD']; ?></td>
									<td align="center"><?php print $result_assign_D6['RPRQ_CARNAMEHEAD']; ?></td>
									<td align="center"><?php print $result_assign_D6['RPRQ_REGISTAIL']; ?></td>
									<td align="center"><?php print $result_assign_D6['RPRQ_CARNAMETAIL']; ?></td>
									<td align="left"><?php print $result_assign_D6['RPC_SUBJECT_CON']; ?></td>
									<td align="left"><?php print $result_assign_D6['RPC_DETAIL']; ?></td>
									<?php if($result_assign_D6['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
										<td align="center" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $result_assign_D6['RPRQ_CODE'];; ?>','1=1','1300','570','มอบหมายงานซ่อม');">
											<img src="https://img2.pic.in.th/pic/Process-Info-icon24.png" width="24" height="24">
										</td>
									<?php }else{ ?>									
										<td align="center"></td>
									<?php } ?>
									<?php if($result_assign_D6['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
										<td align="center" >
											<img src="https://img2.pic.in.th/pic/delete-icon24.png" width="20" height="20" onclick="swaldelete_requestrepair('<?php print $result_assign_D6['RPRQ_CODE']; ?>','<?php print $result_assign_D6['RPRQ_ID']; ?>')">
										</td>
									<?php }else{ ?>									
										<td align="center"></td>
									<?php } ?>
								</tr>
								<?php }; ?>
							</tbody>
						</table> 
					</div>
					<div id="tabs-7">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable7"><!-- default hover pointer display hover pointer -->
							<thead>
								<tr height="30">
									<th rowspan="2" align="center" width="5%">เลขที่ใบขอซ่อม</th>
									<th rowspan="2" align="center" width="3%">สถานะ</th>
									<th rowspan="2" align="center" width="3%">พิมพ์</th>
									<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
									<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
									<th rowspan="2" align="center" width="8%">ลักษณะงานซ่อม</th>
									<th rowspan="2" align="center" width="22%">ปัญหาก่อนการซ่อม</th>
									<th colspan="3" align="center" width="15%" class="ui-state-default">จัดการ</th>
								</tr>
								<tr height="30">
									<th align="center"width="5%">ทะเบียน</th>
									<th align="center"width="10%">ชื่อรถ</th>           
									<th align="center"width="5%">ทะเบียน</th>
									<th align="center"width="10%">ชื่อรถ</th> 
									<th align="center"width="50%">จ่ายงาน</th>   
									<!-- <th align="center"width="5%">ยกเลิกซ่อม</th> -->    
									<th align="center"width="50%">ลบ</th>    
								</tr>
							</thead>
							<tbody>
								<?php
									// $sql_assign = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh."";
									$sql_assign_D7 = "SELECT * FROM vwASSIGN_MAIN WHERE RPRQ_AREA = '$SESSION_AREA' ".$wh." '$D7'";
									$query_assign_D7 = sqlsrv_query($conn, $sql_assign_D7);
									$no=0;
									while($result_assign_D7 = sqlsrv_fetch_array($query_assign_D7, SQLSRV_FETCH_ASSOC)){	
										$no++;
								?>
								<tr id="<?php print $result_assign_D7['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
									<td align="center"><?php print $result_assign_D7['RPRQ_ID']; ?></td>                    
									<td align="center">               
										<?php
											switch($result_assign_D7['RPRQ_STATUSREQUEST']) {
												case "รอตรวจสอบ":
													$text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
												break;
												case "รอคิวซ่อม":
													$text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
												break;
												case "กำลังซ่อม":
													$text="<strong><font color='red'>กำลังซ่อม</font></strong>";
												break;
												case "ซ่อมเสร็จสิ้น":
													$text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
												break;
												case "รอจ่ายงาน":
													$text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
												break;
												case "ไม่อนุมัติ":
													$text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
												break;
											}
											print $text;
										?>
									</td>                        
									<td align="center">
										<button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_assign('<?php print $result_assign_D7['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
									</td>
									<td align="center"><?php print $result_assign_D7['RPRQ_REGISHEAD']; ?></td>
									<td align="center"><?php print $result_assign_D7['RPRQ_CARNAMEHEAD']; ?></td>
									<td align="center"><?php print $result_assign_D7['RPRQ_REGISTAIL']; ?></td>
									<td align="center"><?php print $result_assign_D7['RPRQ_CARNAMETAIL']; ?></td>
									<td align="left"><?php print $result_assign_D7['RPC_SUBJECT_CON']; ?></td>
									<td align="left"><?php print $result_assign_D7['RPC_DETAIL']; ?></td>
									<?php if($result_assign_D7['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
										<td align="center" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $result_assign_D7['RPRQ_CODE'];; ?>','1=1','1300','570','มอบหมายงานซ่อม');">
											<img src="https://img2.pic.in.th/pic/Process-Info-icon24.png" width="24" height="24">
										</td>
									<?php }else{ ?>									
										<td align="center"></td>
									<?php } ?>
									<?php if($result_assign_D7['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
										<td align="center" >
											<img src="https://img2.pic.in.th/pic/delete-icon24.png" width="20" height="20" onclick="swaldelete_requestrepair('<?php print $result_assign_D7['RPRQ_CODE']; ?>','<?php print $result_assign_D7['RPRQ_ID']; ?>')">
										</td>
									<?php }else{ ?>									
										<td align="center"></td>
									<?php } ?>
								</tr>
								<?php }; ?>
							</tbody>
						</table> 
					</div>
				<?php } ?>
			</div>     
		</form>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
		<center>
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>