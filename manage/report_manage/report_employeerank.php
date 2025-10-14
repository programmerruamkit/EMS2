<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	$SESSION_AREA = $_SESSION["AD_AREA"];
	if($SESSION_AREA=="AMT"){
		$HREF="../manage/report_manage/report_employeerank_amt_excel.php?ds='+ds+'&de='+de+'&toprank='+toprank+'&area=$SESSION_AREA";
		$HREFSPLIT="../manage/report_manage/report_employeerank_amt_split_excel.php?ds='+ds+'&de='+de+'&personCode='+personCode+'&area=$SESSION_AREA";
	}else{
		$HREF="../manage/report_manage/report_employeerank_gw_excel.php?ds='+ds+'&de='+de+'&toprank='+toprank+'&area=$SESSION_AREA";
		$HREFSPLIT="../manage/report_manage/report_employeerank_gw_split_excel.php?ds='+ds+'&de='+de+'&personCode='+personCode+'&area=$SESSION_AREA";
	}

	$ds = $_GET['ds'];
	$de = $_GET['de'];
	$toprank = isset($_GET['toprank']) ? intval($_GET['toprank']) : 10; // ค่า default 10

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
		function excel_employeerank() {
			var ds = document.getElementById('dateStart').value;
			var de   = document.getElementById('dateEnd').value;
			var toprank   = document.getElementById('TOPRANK').value;
			window.open('<?=$HREF?>','_blank');
		}
		
		var source1 = [<?= $rqrp_regishead ?>];
		AutoCompleteNormal("VHCRGNM", source1); 
	
		function queryemployeerank(){
			var ds = $("#dateStart").val();
			var de = $("#dateEnd").val();
			var toprank = $("#TOPRANK").val(); // เพิ่มตรงนี้
		
			var getsel = "?ds="+ds+"&de="+de+"&toprank="+toprank; // ปรับตรงนี้
			loadViewdetail('<?=$path?>manage/report_manage/report_employeerank.php'+getsel);
		}

		function excel_employeerank_split(personCode) {
			var personCode = personCode;
			var ds = document.getElementById('dateStart').value;
			var de = document.getElementById('dateEnd').value;
			window.open('<?=$HREFSPLIT?>','_blank');
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
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;รายงานอันดับแจ้งซ่อมจากพนักงาน</h3></td>
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
						<div class="input-control select">อันดับ       
							<select class="time" style="width: 100%;" name="TOPRANK" id="TOPRANK">
								<option value="3" <?php if(isset($_GET['toprank']) && $_GET['toprank']=='3'){echo 'selected';}?>>3 อันดับแรก</option>
								<option value="5" <?php if(isset($_GET['toprank']) && $_GET['toprank']=='5'){echo 'selected';}?>>5 อันดับแรก</option>
								<option value="10" <?php if(isset($_GET['toprank']) && $_GET['toprank']=='10'){echo 'selected';}?>>10 อันดับแรก</option>
								<option value="15" <?php if(isset($_GET['toprank']) && $_GET['toprank']=='15'){echo 'selected';}?>>15 อันดับแรก</option>
								<option value="20" <?php if(isset($_GET['toprank']) && $_GET['toprank']=='20'){echo 'selected';}?>>20 อันดับแรก</option>
							</select>
						</div>
					</td>
					<td width="1%" align="right">&nbsp;</td>  
                    <td width="10%" align="center"><br>
                        <button class="bg-color-blue" onclick="queryemployeerank()"><font color="white"><i class="icon-search"></i> ค้นหา</font></button>
                    </td>
                    <!-- <td width="10%" align="center"><br>
                        <button class="button_gray" onclick="javascript:loadViewdetail('<?=$path?>manage/report_manage/report_employeerank.php');"><font color="black"><i class="fa fa-refresh"></i> รีเซ็ต</font></button>
                    </td> -->
					<td width="30%" align="center">
						<div class="row input-control"><br>
							<button  title="Excel" type="button" class="bg-color-green big" onclick="excel_employeerank()"><font color="white" size="2"><i class="icon-file-excel icon-large"></i> Excel รายงานอันดับแจ้งซ่อมจากพนักงาน</font></button>
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
					<tr>
						<th align="center">ลำดับ</th>
						<th align="center">รหัสพนักงาน</th>
						<th align="center">ชื่อผู้แจ้ง</th>
						<th align="center">ตำแหน่ง</th>
						<th align="center">จำนวนแจ้งซ่อม</th>
						<th align="center">รายละเอียดรายบุคคล</th>
					</tr>
				</thead>
			<tbody>
				<?php
					$sql_top_employee = "SELECT TOP $toprank
							A.RPRQ_CREATEBY,
							A.RPRQ_REQUESTBY,
							B.PositionNameT,
							COUNT(*) AS TotalRequests
						FROM dbo.REPAIRREQUEST AS A
						LEFT JOIN vwEMPLOYEE AS B ON B.PersonCode = A.RPRQ_CREATEBY COLLATE THAI_CI_AS
						WHERE
							A.RPRQ_WORKTYPE = 'BM'
							AND A.RPRQ_STATUS = 'Y'
							AND A.RPRQ_TYPECUSTOMER = 'cusin'
							AND A.RPRQ_AREA = '$SESSION_AREA'
							AND CONVERT(date, A.RPRQ_CREATEDATE_REQUEST, 103) BETWEEN '$dscon' AND '$decon'
						GROUP BY
							A.RPRQ_CREATEBY,
							A.RPRQ_REQUESTBY,
							B.PositionNameT
						ORDER BY TotalRequests DESC";

					$query_top_employee = sqlsrv_query($conn, $sql_top_employee);
					$no = 0;

					while($row = sqlsrv_fetch_array($query_top_employee, SQLSRV_FETCH_ASSOC)){	
						$no++;
				?>
				<tr>
					<td align="center"><?= $no ?></td>
					<td align="center"><?= $row['RPRQ_CREATEBY'] ?></td>
					<td align="center"><?= $row['RPRQ_REQUESTBY'] ?></td>
					<td align="center"><?= $row['PositionNameT'] ?></td>
					<td align="center"><?= $row['TotalRequests'] ?></td>
					<td align="center">
						<button title="Excel" type="button" class="bg-color-green"
							onclick="excel_employeerank_split('<?= $row['RPRQ_CREATEBY'] ?>')">
							<font color="white" size="1"><i class="icon-file-excel icon-large"></i> Excel</font>
						</button>
					</td>
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
			<input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>manage/report_manage/report_employeerank.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>