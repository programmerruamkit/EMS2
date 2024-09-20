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
		$HREF_EXCEL="../manage/report_manage/report_repairhistory_amt_excel.php?ds='+datestartdetail+'&de='+dateenddetail+'&rg='+vhcrg+'&co='+com+'&cu='+cus+'&dt='+detail+'&area=$SESSION_AREA";
		$HREF_PDF="../manage/report_manage/report_repairhistory_amt_pdf.php?ds='+datestartdetail+'&de='+dateenddetail+'&rg='+vhcrg+'&co='+com+'&cu='+cus+'&dt='+detail+'&area=$SESSION_AREA";
	}else{
		$HREF_EXCEL="../manage/report_manage/report_repairhistory_gw_excel.php?ds='+datestartdetail+'&de='+dateenddetail+'&rg='+vhcrg+'&co='+com+'&cu='+cus+'&dt='+detail+'&area=$SESSION_AREA";
		$HREF_PDF="../manage/report_manage/report_repairhistory_gw_pdf.php?ds='+datestartdetail+'&de='+dateenddetail+'&rg='+vhcrg+'&co='+com+'&cu='+cus+'&dt='+detail+'&area=$SESSION_AREA";
	}

	$ds = $_GET['ds'];
	$de = $_GET['de'];
	$rg = $_GET['rg'];
	$co = $_GET['co'];
	$cu = $_GET['cu'];
	$dt = $_GET['dt'];
	if($ds!='' && $de!=''){
		$getselectdaystart = $ds;
		$getselectdayend = $de;		
		// $dsdate = str_replace('/', '-', $ds);
		// $dscon = date('Y-m-d', strtotime($dsdate));
		$start = explode("/", $ds);
		$startd = $start[0];
		$startif = $start[1];
			if($startif == "01"){
				$startm = "1";
			}else if($startif == "02"){
				$startm = "2";
			}else if($startif == "03"){
				$startm = "3";
			}else if($startif == "04"){
				$startm = "4";
			}else if($startif == "05"){
				$startm = "5";
			}else if($startif == "06"){
				$startm = "6";
			}else if($startif == "07"){
				$startm = "7";
			}else if($startif8== "08"){
				$startm = "8";
			}else if($startif == "09"){
				$startm = "9";
			}else if($startif == "10"){
				$startm = "10";
			}else if($startif == "11"){
				$startm = "11";
			}else if($startif == "12"){
				$startm = "12";
			}
		$dscon = $start[0].'/'.$startm.'/'.$start[2].' 0:00';

		// $dedate = str_replace('/', '-', $de);
		// $decon = date('Y-m-d', strtotime($dedate));		
		$end = explode("/", $de);
		$endd = $end[0];
		$endif = $end[1];
			if($endif == "01"){
				$endm = "1";
			}else if($endif == "02"){
				$endm = "2";
			}else if($endif == "03"){
				$endm = "3";
			}else if($endif == "04"){
				$endm = "4";
			}else if($endif == "05"){
				$endm = "5";
			}else if($endif == "06"){
				$endm = "6";
			}else if($endif == "07"){
				$endm = "7";
			}else if($endif8== "08"){
				$endm = "8";
			}else if($endif == "09"){
				$endm = "9";
			}else if($endif == "10"){
				$endm = "10";
			}else if($endif == "11"){
				$endm = "11";
			}else if($endif == "12"){
				$endm = "12";
			}
		$decon = $end[0].'/'.$endm.'/'.$end[2].' 0:00';
	}else{
		// $getselectdaystart = $GETDAYEN;
		// $getselectdayend = $GETDAYEN;
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
		function excel_reporthdms() {
			var datestartdetail = document.getElementById('dateStart').value;
			var dateenddetail   = document.getElementById('dateEnd').value;
			var vhcrg   = document.getElementById('VHCRGNM').value;
			var com   = document.getElementById('company').value;
			var cus   = document.getElementById('customer').value;
			var detail   = document.getElementById('detailrepair').value;
			window.open('<?=$HREF_EXCEL?>','_blank');
		}
		function pdf_reporthdms() {
			var datestartdetail = document.getElementById('dateStart').value;
			var dateenddetail   = document.getElementById('dateEnd').value;
			var vhcrg   = document.getElementById('VHCRGNM').value;
			var com   = document.getElementById('company').value;
			var cus   = document.getElementById('customer').value;
			var detail   = document.getElementById('detailrepair').value;
			window.open('<?=$HREF_PDF?>','_blank');
		}
		
		var source1 = [<?= $rqrp_regishead ?>];
		AutoCompleteNormal("VHCRGNM", source1); 
	
		function queryreporthdms(){
			var ds = $("#dateStart").val();
			var de = $("#dateEnd").val();
			var rg = $("#VHCRGNM").val();	
			var co = $("#company").val();	
			var cu = $("#customer").val();		
			var dt = $("#detailrepair").val();					
			
			// if(rg==''){
				// alert('1')
			// 	var getsel = "?ds="+ds+"&de="+de+"&st="+st;
			// 	loadViewdetail('<?=$path?>manage/report_manage/report_repairhistory.php'+getsel);
			// }else{
				// alert('2')
				// var getsel = "?ds="+ds+"&de="+de+"&st="+st+"&rg="+rg;
				var getsel = "?ds="+ds+"&de="+de+"&rg="+rg+"&co="+co+"&cu="+cu+"&dt="+dt;
				loadViewdetail('<?=$path?>manage/report_manage/report_repairhistory.php'+getsel);
			// }
		} 
	
	</script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25" valign="middle" class=""><img src="https://img2.pic.in.th/pic/reports-icon48.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;ประวัติการซ่อมบำรุง (HDMS)</h3></td>
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
        <table>
            <tbody>
				<tr align="center">
					<td colspan="11" align="right">&nbsp;</td>  
					<td align="center">
						<div class="row input-control"><br>
							<button title="Excel" type="button" class="bg-color-green" onclick="excel_reporthdms()"><font color="white" size="2"><i class="icon-file-excel icon-large"></i> พิม Excel</font></button>
						</div>
					</td>
					<td colspan="1" align="right">&nbsp;</td> 
					<td align="center">
						<div class="row input-control"><br>
							<button title="PDF" type="button" class="bg-color-red" onclick="pdf_reporthdms()"><font color="white" size="2"><i class="icon-file-pdf icon-large"></i> พิม PDF</font></button>
						</div>
					</td>

				</tr>
				<tr align="center">
					<td width="1%" align="right">&nbsp;</td>    
					<td width="16%" align="left">
						<div class="row input-control">ทะเบียนรถ/ชื่อรถ
							<input type="text" name="VHCRGNM" id="VHCRGNM" placeholder="ระบุทะเบียนรถ/ชื่อรถ" value="<?php if(isset($rg)){echo $rg;}?>" class="time" autocomplete="off">
						</div>
					</td>
					<td width="1%" align="right">&nbsp;</td>    
					<td width="16%" align="left">
						<div class="row input-control">บริษัท
							<input type="text" name="company" id="company" placeholder="ระบุบริษัท" value="<?php if(isset($co)){echo $co;}?>" class="time" autocomplete="off">
						</div>
					</td>
					<td width="1%" align="right">&nbsp;</td>    
					<td width="16%" align="left">
						<div class="row input-control">ลูกค้า
							<input type="text" name="customer" id="customer" placeholder="ระบุลูกค้า" value="<?php if(isset($cu)){echo $cu;}?>" class="time" autocomplete="off">
						</div>
					</td>
					<td width="1%" align="right">&nbsp;</td>    
					<td width="16%" align="left">
						<div class="row input-control">รายละเอียดงานซ่อม
							<input type="text" name="detailrepair" id="detailrepair" placeholder="ระบุรายละเอียดงานซ่อม" value="<?php if(isset($dt)){echo $dt;}?>" class="time" autocomplete="off">
						</div>
					</td>
					<td width="1%" align="right">&nbsp;</td>    
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
					<td align="right">&nbsp;</td>   
                    <td align="center"><br>
                        <button class="bg-color-blue" onclick="queryreporthdms()"><font color="white"><i class="icon-search"></i> ค้นหา</font></button>
                    </td>
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
					<th rowspan="2" align="center" width="5%" class="ui-state-default">บริษัท</th>
					<th rowspan="2" align="center" width="5%" class="ui-state-default">ลูกค้า</th>
					<th rowspan="2" align="center" width="5%" class="ui-state-default">วันที่</th>
					<th colspan="3" align="center" width="15%" class="ui-state-default">ข้อมูลรถ</th>
					<th rowspan="2" align="center" width="5%" class="ui-state-default">เลข JOB</th>
					<th colspan="10" align="center" width="60%" class="ui-state-default">รายละเอียดงานซ่อม</th>
				</tr>
				<tr height="30">
					<th align="center">ทะเบียนรถ</th>
					<th align="center">ทะเบียนเดิม</th>           
					<th align="center">เลขไมล์ล่าสุด</th>					
					<th align="center">ชื่องาน</th>
					<th align="center">รายละเอียด</th>
					<th align="center">ปริมาณ</th>
					<th align="center">ราคาต่อหน่วย</th>
					<th align="center">ราคาขาย</th>
					<th align="center">รวมเงิน</th>
					<th align="center">ค่าแรง</th>
					<th align="center">ผู้ขายอะไหล่</th>
					<th align="center">ชั่วโมงทำงานจริง</th>
					<th align="center">ชั่วโมงเก็บเงิน</th>
				</tr>
			</thead>
			<tbody>
				<?php
					// echo "RG=".$rgsub."<br>";
					// echo "DS=".$dscon."<br>";
					// echo "DE=".$decon."<br>";
					// $wh="";					
					if($rgsub!=''){
						$rsrg="REGNO LIKE '%$rgsub%' AND ";
					}else{$rsrg="";} 
					if($dscon!='' && $decon!=''){
						$rsse="OPENDATE BETWEEN '$dscon' AND '$decon' AND ";
					}else{$rsse="";}
					if($co!=''){
						$rsco="NICKNM LIKE '%$co%' AND ";
					}else{$rsco="";}
					if($cu!=''){
						$rscu="CUSCOD LIKE '%$cu%' AND ";
					}else{$rscu="";}
					if($dt!=''){
						$rsdt="SPAREPARTSDETAIL LIKE '%$dt%' AND";
					}else{$rsdt="";}
					$wh=$rsrg.$rsse.$rsco.$rscu.$rsdt;
					if($wh==''){
						$rswh="OPENDATE BETWEEN '00/00/0000 00:00' AND '00/00/000 00:00' AND ";
					}else{
						$rswh=$wh;
					}

					// $sql_reporthdms = "SELECT * FROM RKTC WHERE ".$wh."";
					$sql_reporthdms = "SELECT * FROM vwRKTC_MERGENEWOLD WHERE ".$rswh." 1=1";
					$query_reporthdms = sqlsrv_query($conn, $sql_reporthdms);
					// echo $sql_reporthdms;
					$no=0;
					while($result_reporthdms = sqlsrv_fetch_array($query_reporthdms, SQLSRV_FETCH_ASSOC)){	
						$no++;
						$temp0 = $result_reporthdms['RKTCID'];
						$temp1 = $result_reporthdms['NICKNM'];
						$temp2 = $result_reporthdms['CUSCOD'];
						$temp3 = $result_reporthdms['OPENDATE'];
						$temp4 = $result_reporthdms['CLOSEDATE'];
						$temp5 = $result_reporthdms['TAXINVOICEDATE'];
						$temp6 = $result_reporthdms['REGNO'];
						$temp7 = $result_reporthdms['CHASSIS'];
						$temp8 = $result_reporthdms['MILEAGE'];
						$temp9 = $result_reporthdms['JOBNO']; 
						$temp10 = $result_reporthdms['TYPNAME'];
						$temp11 = $result_reporthdms['SPAREPARTSDETAIL'];
						$temp12 = $result_reporthdms['NET'];
						$temp13 = $result_reporthdms['COST'];
						$temp14 = $result_reporthdms['SELLING'];
						$temp15 = $result_reporthdms['SPAREPARTSSELLER'];
						$temp16 = $result_reporthdms['SUMMARY'];
						$temp17 = $result_reporthdms['WAGES'];
						$temp18 = $result_reporthdms['MECHANIC'];
						$temp19 = $result_reporthdms['WORKINGHOURS'];
						$temp20 = $result_reporthdms['COLLECTIONHOURS'];
						
						$regisold = "SELECT VEHICLEREGISNUMBER,REMARK FROM vwVEHICLEINFO WHERE VEHICLEREGISNUMBER = '$temp6'";
						$queryregisold = sqlsrv_query($conn, $regisold);
						$resultregisold = sqlsrv_fetch_array($queryregisold, SQLSRV_FETCH_ASSOC);
						if(isset($resultregisold["REMARK"])){
							$REMARK = str_replace('ทะเบียนเดิม', '', $resultregisold["REMARK"]);
						}
				?>
				<tr height="25px">
					<td align="center"><?php print "$no";?></td>
					<td align="center"><?php print "$temp1";?></td>
					<td align="center"><?php print "$temp2";?></td>
					<td align="center"><?php print "$temp3";?></td>
					<td align="center"><?php print "$temp6";?></td>
					<td align="center"><?php print "$REMARK";?></td>
					<td align="center"><?php print "$temp8";?></td>
					<td align="center"><?php print "$temp9";?></td>
					<td align="left"><?php print "$temp10";?></td>
					<td align="left"><?php print "$temp11";?></td>
					<td align="right"><?php print "$temp12";?></td>
					<td align="right"><?php print "$temp13";?></td>
					<td align="right"><?php print "$temp14";?></td>
					<td align="right"><?php print "$temp16";?></td>
					<td align="right"><?php print "$temp17";?></td>
					<td align="left"><?php print "$temp15";?></td>
					<td align="left"><?php print "$temp19";?></td>
					<td align="right"><?php print "$temp20";?></td>
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
			<input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>manage/report_manage/report_repairhistory.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>