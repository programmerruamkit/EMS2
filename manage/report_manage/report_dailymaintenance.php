<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	$SESSION_AREA = $_SESSION["AD_AREA"];
	if($SESSION_AREA=="AMT"){
		$HREF="../manage/report_manage/report_dailymaintenance_amt_excel.php?ds='+ds+'&de='+de+'&rg='+rg+'&st='+st+'&gp='+gp+'&tc='+tc+'&area=$SESSION_AREA";
	}else{
		$HREF="../manage/report_manage/report_dailymaintenance_gw_excel.php?ds='+ds+'&de='+de+'&rg='+rg+'&st='+st+'&gp='+gp+'&tc='+tc+'&area=$SESSION_AREA";
	}

	$ds = $_GET['ds'];
	$de = $_GET['de'];
	$rg = $_GET['rg'];
	$st = $_GET['st'];
	$gp = $_GET['gp'];
	$tc = $_GET['tc'];
	if(isset($ds)&&isset($de)){
		$getselectdaystart = $ds;
		$getselectdayend = $de;		
		$dsdate = str_replace('/', '-', $ds);
		$dscon = date('Y-m-d', strtotime($dsdate));
		$dedate = str_replace('/', '-', $de);
		$decon = date('Y-m-d', strtotime($dedate));
	}else{
		$getselectdaystart = $GETDAYEN;
		$getselectdayend = $GETDAYEN;
	}
	if(isset($rg)){
		$rgsub = substr($rg,0,7);
	}else{
		$rgsub = '';
	}

	function autocomplete_regishead($CONDI) {
		$path = "../";   	
		require($path.'../include/connect.php');
		$data = "";
		$sql = "SELECT VEHICLEREGISNUMBER,THAINAME FROM vwVEHICLEINFO  WHERE $CONDI
		UNION	
		SELECT VEHICLEREGISNUMBER COLLATE Thai_CI_AI,THAINAME COLLATE Thai_CI_AI FROM vwVEHICLEINFO_OUT WHERE $CONDI";
		$query = sqlsrv_query($conn, $sql);
		while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
			$data .= "'".$result['VEHICLEREGISNUMBER']." / ".$result['THAINAME']."',";
		}
		return rtrim($data, ",");
	}
	$rqrp_regishead = autocomplete_regishead("ACTIVESTATUS = '1' AND VEHICLEGROUPDESC = 'Transport'");
	//   echo $rqrp_regishead;

?>
<html>
<head>
	<script type="text/javascript">
		$(document).ready(function(e) {
			$('.datepic').datetimepicker({
				timepicker:false,
				lang:'th',
				format:'d/m/Y',
				closeOnDateSelect: true
			});
		});
		function date1todate2(){
			document.getElementById('dateEnd').value = document.getElementById('dateStart').value;
		}
		function excel_dailymaintenance() {
			var ds = document.getElementById('dateStart').value;
			var de   = document.getElementById('dateEnd').value;
			var rg   = document.getElementById('VHCRGNM').value;
			var st   = document.getElementById('STATUSREPAIR').value;
			var gp   = document.getElementById('GROUP').value;
			var tc   = document.getElementById('TYPECUS').value;
			window.open('<?=$HREF?>','_blank');
		}
		
		var source1 = [<?= $rqrp_regishead ?>];
		AutoCompleteNormal("VHCRGNM", source1); 
	
		function queryreportdaily(){
			var ds = $("#dateStart").val();
			var de = $("#dateEnd").val();
			var rg = $("#VHCRGNM").val();	
			var st = $("#STATUSREPAIR").val();	
			var gp = $("#GROUP").val();				
			var tc = $("#TYPECUS").val();				
			
			var getsel = "?ds="+ds+"&de="+de+"&rg="+rg+"&st="+st+"&gp="+gp+"&tc="+tc;
			loadViewdetail('<?=$path?>manage/report_manage/report_dailymaintenance.php'+getsel);
		} 
	
	</script>
</head>
<style>
	.largerCheckbox2 {
		width: 20px;
		height: 20px;
	}
	.largerCheckbox1 {
		width: 20px;
		height: 20px;
	}
	.largerCheckbox {
		width: 20px;
		height: 20px;
	}
</style>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25" valign="middle" class=""><img src="https://img2.pic.in.th/pic/reports-icon48.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;รายงานซ่อมบำรุงประจำวัน</h3></td>
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
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center">
        <br>    
        <table>
            <tbody>
				<tr align="center">
					<!-- <td width="10%" align="right"><strong>เงื่อนไขการค้นหา :</strong></td> -->
					<td width="10%" align="left">
						<div class="row input-control">วันที่เริ่มต้น
							<input type="text" name="dateStart" id="dateStart" class="datepic time" placeholder="วันที่เริ่มต้น" autocomplete="off" value="<?=$getselectdaystart;?>" onchange="date1todate2()">
						</div>
					</td>
					<td width="1%" align="right">&nbsp;</td>                       
					<td width="10%" align="left">
						<div class="row input-control">วันที่สิ้นสุด
							<input type="text" name="dateEnd" id="dateEnd" class="datepic time" placeholder="วันที่สิ้นสุด" autocomplete="off" value="<?=$getselectdayend;?>">
						</div>
					</td>
					<td width="1%" align="right">&nbsp;</td>    
					<td width="10%" align="left">
						<div class="row input-control">ทะเบียนรถ/ชื่อรถ
							<input type="text" name="VHCRGNM" id="VHCRGNM" placeholder="ระบุทะเบียนรถ/ชื่อรถ" value="<?php if(isset($rg)){echo $rg;}?>" class="time" autocomplete="off">
						</div>
					</td>
					<td width="1%" align="right">&nbsp;</td>   
                    <td width="10%" align="left">
                        <div class="input-control select">ประเภทงาน       
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="GROUP" id="GROUP">
								<option value="" <?php if(isset($gp)){if($gp=='ทั้งหมด'){echo 'selected';}}?>>ทั้งหมด</option>
								<option value="BM" <?php if(isset($gp)){if($gp=='BM'){echo 'selected';}}?>>BM</option>
								<option value="PM" <?php if(isset($gp)){if($gp=='PM'){echo 'selected';}}?>>PM</option>
                            </select>
                        </div>
                    </td>
					<td width="1%" align="right">&nbsp;</td>   
                    <td width="10%" align="left">
                        <div class="input-control select">กลุ่มลูกค้า       
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="TYPECUS" id="TYPECUS">
								<option value="" <?php if(isset($tc)){if($tc=='ทั้งหมด'){echo 'selected';}}?>>ทั้งหมด</option>
								<option value="cusout" <?php if(isset($tc)){if($tc=='cusout'){echo 'selected';}}?>>ภายนอก</option>
								<option value="cusin" <?php if(isset($tc)){if($tc=='cusin'){echo 'selected';}}?>>ภายใน</option>
                            </select>
                        </div>
                    </td>
					<td width="1%" align="right">&nbsp;</td>   
                    <td width="10%" align="left">
                        <div class="input-control select">สถานะ       
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="STATUSREPAIR" id="STATUSREPAIR" required>
								<option value="ทั้งหมด" <?php if(isset($st)){if($st=='ทั้งหมด'){echo 'selected';}}?>>ทั้งหมด</option>
								<option value="รอส่งแผน" <?php if(isset($st)){if($st=='รอส่งแผน'){echo 'selected';}}?>>รอส่งแผน</option>
								<option value="รอตรวจสอบ" <?php if(isset($st)){if($st=='รอตรวจสอบ'){echo 'selected';}}?>>รอตรวจสอบ</option>
								<option value="รอจ่ายงาน" <?php if(isset($st)){if($st=='รอจ่ายงาน'){echo 'selected';}}?>>รอจ่ายงาน</option>
								<option value="รอคิวซ่อม" <?php if(isset($st)){if($st=='รอคิวซ่อม'){echo 'selected';}}?>>รอคิวซ่อม</option>
								<option value="กำลังซ่อม" <?php if(isset($st)){if($st=='กำลังซ่อม'){echo 'selected';}}?>>กำลังซ่อม</option>
								<option value="ซ่อมเสร็จสิ้น" <?php if(isset($st)){if($st=='ซ่อมเสร็จสิ้น'){echo 'selected';}}?>>ซ่อมเสร็จสิ้น</option>
								<option value="ไม่อนุมัติ" <?php if(isset($st)){if($st=='ไม่อนุมัติ'){echo 'selected';}}?>>ไม่อนุมัติ</option>
                            </select>
                        </div>
                    </td>
					<td width="1%" align="right">&nbsp;</td>  
                    <td width="10%" align="center"><br>
                        <button class="bg-color-blue" onclick="queryreportdaily()"><font color="white"><i class="icon-search"></i> ค้นหา</font></button>
                    </td>
                    <!-- <td width="10%" align="center"><br>
                        <button class="button_gray" onclick="javascript:loadViewdetail('<?=$path?>manage/report_manage/report_dailymaintenance.php');"><font color="black"><i class="fa fa-refresh"></i> รีเซ็ต</font></button>
                    </td> -->
					<td width="30%" align="center">
						<div class="row input-control"><br>
							<button  title="Excel" type="button" class="bg-color-green big" onclick="excel_dailymaintenance()"><font color="white" size="2"><i class="icon-file-excel icon-large"></i> Excel รายงานซ่อมบำรุงประจำวัน</font></button>
						</div>
					</td>
					<td align="center"></td>
				</tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table> 
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
			<thead>
				<tr height="30">
					<th rowspan="2" align="center" width="5%" class="ui-state-default">ลำดับ.</th>
					<th rowspan="2" align="center" width="7%" class="ui-state-default">สถานะการซ่อม</th>
					<th colspan="2" align="center" width="13%" class="ui-state-default">ข้อมูลรถ(หัว)</th>
					<th colspan="2" align="center" width="13%" class="ui-state-default">ข้อมูลรถ(หาง)</th>
					<th rowspan="2" align="center" width="10%" class="ui-state-default">ลักษณะงานซ่อม</th>
					<th colspan="2" align="center" width="16%" class="ui-state-default">วันที่/เวลา</th>
					<th rowspan="2" align="center" width="20%" class="ui-state-default">รายละเอียดการซ่อม</th>
					<!-- <th rowspan="2" align="center" width="" class="ui-state-default">ค่าใช้จ่าย</th> -->
					<!-- <th rowspan="2" align="center" width="" class="ui-state-default">พขร. แจ้งซ่อม</th> -->
					<th rowspan="2" align="center" width="10%" class="ui-state-default">ช่างผู้รับผิดชอบ</th>
					<!-- <th colspan="2" align="center" width="10%" class="ui-state-default">รูปถาพการแจ้งซ่อม</th> -->
				</tr>
				<tr height="30">
					<th align="center"width="5%">ทะเบียน</th>
					<th align="center"width="7%">ชื่อรถ</th>           
					<th align="center"width="5%">ทะเบียน</th>
					<th align="center"width="7%">ชื่อรถ</th>
					<th align="center"width="8%">เริ่มซ่อม</th>
					<th align="center"width="8%">เสร็จ</th>
					<!-- <th align="center"width="5%">ก่อน</th> -->
					<!-- <th align="center"width="5%">หลัง</th> -->
				</tr>
			</thead>
			<tbody>
				<?php
					// echo "RG=".$rgsub."<br>";
					// echo "DS=".$dscon."<br>";
					// echo "DE=".$decon."<br>";
					// echo "GP=".$gp."<br>";
					// echo "TC=".$tc."<br>";
					// echo "ST=".$st."<br>";
					// $where1="";
					$sqlquery="SELECT DISTINCT a.RPRQ_ID,a.RPRQ_CODE,a.RPRQ_STATUSREQUEST,a.RPRQ_REGISHEAD,a.RPRQ_REGISTAIL,a.RPRQ_CARNAMEHEAD,a.RPRQ_CARNAMETAIL,b.RPC_SUBJECT SUBJ,
						CASE
							WHEN a.RPRQ_WORKTYPE = 'BM' THEN
								CASE 
									WHEN b.RPC_SUBJECT = 'EL' THEN 'ระบบไฟ' 
									WHEN b.RPC_SUBJECT = 'TU' THEN 'ยาง ช่วงล่าง' 
									WHEN b.RPC_SUBJECT = 'BD' THEN 'โครงสร้าง' 
									WHEN b.RPC_SUBJECT = 'EG' THEN 'เครื่องยนต์' 
									WHEN b.RPC_SUBJECT = 'AC' THEN 'อุปกรณ์ประจำรถ' 
								ELSE b.RPC_SUBJECT 
							END ELSE RPRQ_RANKPMTYPE 
						END RPC_SUBJECT_CON,
						-- CONVERT(datetime,a.RPRQ_REQUESTCARDATE,103) RPRQ_REQUESTCARDATE,
						b.RPC_INCARDATE+' '+b.RPC_INCARTIME REPAIRSTART,
						b.RPC_OUTCARDATE+' '+b.RPC_OUTCARTIME REPAIREND,
						b.RPC_DETAIL,
						b.RPC_IMAGES,
						a.RPRQ_REQUESTBY
						FROM [dbo].[REPAIRREQUEST] a
						INNER JOIN [dbo].[REPAIRCAUSE] b ON a.[RPRQ_CODE] = b.[RPRQ_CODE] 
						WHERE 1=1 ";
					
					$wsa="AND a.RPRQ_STATUS = 'Y' AND a.RPRQ_AREA = '$SESSION_AREA' ";
					$wd1="AND CONVERT(datetime,b.RPC_INCARDATE,103) BETWEEN '$dscon' AND '$decon' ";
					$wd2="AND CONVERT(datetime,a.RPRQ_CREATEDATE_REQUEST,103) BETWEEN '$dscon' AND '$decon' ";
					$wrg="AND (a.RPRQ_REGISHEAD LIKE '%$rgsub%' OR a.RPRQ_REGISTAIL LIKE '%$rgsub%') ";      
					$wwt="AND a.RPRQ_WORKTYPE LIKE '%$gp%' ";
					$wtc="AND a.RPRQ_TYPECUSTOMER LIKE '%$tc%' ";
					$wst1="AND a.RPRQ_STATUSREQUEST NOT IN('รอส่งแผน','รอตรวจสอบ','รอจ่ายงาน','ไม่อนุมัติ') ";
					$wst2="AND a.RPRQ_STATUSREQUEST IN('รอส่งแผน','รอตรวจสอบ','รอจ่ายงาน','ไม่อนุมัติ') ";
					$wst3="AND a.RPRQ_STATUSREQUEST LIKE '%$st%' ";
					$order="ORDER BY a.RPRQ_STATUSREQUEST ASC";

					if(isset($st)){
						if($st=="ทั้งหมด"){
							// echo "IF=1<br>";															
							$sql_seRepairplan = $sqlquery.$wsa.$wd1.$wrg.$wwt.$wtc.$wst1." UNION ALL ".$sqlquery.$wsa.$wd2.$wrg.$wwt.$wtc.$wst2.$order;
						}else if(($st=="รอส่งแผน")||($st=="รอตรวจสอบ")||($st=="รอจ่ายงาน")||($st=="ไม่อนุมัติ")){
							// echo "IF=2<br>";															
							$sql_seRepairplan = $sqlquery.$wsa.$wd2.$wrg.$wwt.$wtc.$wst3.$order;
						}else if(($st=="รอคิวซ่อม")||($st=="กำลังซ่อม")||($st=="ซ่อมเสร็จสิ้น")){
							// echo "IF=3<br>";															
							$sql_seRepairplan = $sqlquery.$wsa.$wd1.$wrg.$wwt.$wtc.$wst3.$order;	
						}else{
							// echo "IF=0<br>";
							$sql_seRepairplan = "";											
						}
					}else{
						// echo "IF=0<br>";
						$sql_seRepairplan = "";	
					}
					// echo "<br>";
					// echo $sql_seRepairplan;
					$query_reportdaily = sqlsrv_query($conn, $sql_seRepairplan);
					$no=0;
					while($result_reportdaily = sqlsrv_fetch_array($query_reportdaily, SQLSRV_FETCH_ASSOC)){	
						$no++;
						$RPRQ_ID=$result_reportdaily['RPRQ_ID'];
						$RPRQ_CODE=$result_reportdaily['RPRQ_CODE'];
						$SUBJ=$result_reportdaily['SUBJ'];
						$RPRQ_STATUSREQUEST=$result_reportdaily['RPRQ_STATUSREQUEST'];
						$RPRQ_REGISHEAD=$result_reportdaily['RPRQ_REGISHEAD'];
						$RPRQ_REGISTAIL=$result_reportdaily['RPRQ_REGISTAIL'];
						$RPRQ_CARNAMEHEAD=$result_reportdaily['RPRQ_CARNAMEHEAD'];
						$RPRQ_CARNAMETAIL=$result_reportdaily['RPRQ_CARNAMETAIL'];
						$RPC_SUBJECT_CON=$result_reportdaily['RPC_SUBJECT_CON'];
						$REPAIRSTART=$result_reportdaily['REPAIRSTART'];
						$REPAIREND=$result_reportdaily['REPAIREND'];
						$RPC_DETAIL=$result_reportdaily['RPC_DETAIL'];
						$RPC_IMAGES=$result_reportdaily["RPC_IMAGES"];
						$RPRQ_REQUESTBY=$result_reportdaily['RPRQ_REQUESTBY'];
                            
                        // หารายชื่อช่างในแต่ละแผนงาน
                            $sql_seTechnician = "SELECT DISTINCT(RPME_NAME) AS 'TECHICIANNAME'
                                FROM REPAIRMANEMP
                                WHERE RPRQ_CODE  = '$RPRQ_CODE' AND RPC_SUBJECT = '$SUBJ'";
                            $params_seTechnician  = array();
                            $query_seTechnician   = sqlsrv_query($conn, $sql_seTechnician, $params_seTechnician);                            
                            $TECHICIAN = '';
                            while ($result_seTechnician = sqlsrv_fetch_array($query_seTechnician, SQLSRV_FETCH_ASSOC)) {
                                $TECHICIAN = $TECHICIAN . $result_seTechnician['TECHICIANNAME'] . ",";
                            }
				?>

				<tr height="25px">
					<td align="center"><?php print "$no";?></td>
					<!-- <td align="center"><?php print "$RPRQ_ID";?></td> -->
					<td align="center"><?php print "$RPRQ_STATUSREQUEST";?></td>
					<td align="center"><?php print "$RPRQ_REGISHEAD";?></td>
					<td align="center"><?php print "$RPRQ_CARNAMEHEAD";?></td>
					<td align="center"><?php print "$RPRQ_REGISTAIL";?></td>
					<td align="center"><?php print "$RPRQ_CARNAMETAIL";?></td>
					<td align="left"><?php print "$RPC_SUBJECT_CON";?></td>
					<td align="center"><?php print "$REPAIRSTART";?></td>
					<td align="center"><?php print "$REPAIREND";?></td>
					<td align="left"><?php print "$RPC_DETAIL";?></td>
					<!-- <td align="center"></td> -->
					<!-- <td align="left"><?php print "$RPRQ_REQUESTBY";?></td> -->
					<td align="left"><?=$TECHICIAN?></td>
					<!-- <td align="center">
						<?php          
							$explodes = explode('-', $RPC_IMAGES);
							$RPC_IMAGES2 = $explodes[1];
							if($RPC_IMAGES2!=""){
						?>
							<a href="#" onClick="javascript:ajaxPopup4('<?=$path?>manage/report_manage/report_dailymaintenance_image.php','edit','<?=$RPRQ_ID?>','1=1','800','700','รูปภาพจากใบแจ้งซ่อมหมายเลข <?=$RPRQ_ID?>');">
								<img src="https://img2.pic.in.th/pic/image13.png" width="20px" height="20px"> 
							</a>
						<?php } ?>
					</td> -->
					<!-- <td align="center"></td> -->
				</tr>
				<?php }; ?>
			</tbody>
		</table>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
		<center>
			<input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>manage/report_manage/report_dailymaintenance.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>