<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
?>
<html>
<head>
	<script type="text/javascript">
		$(document).ready(function(e) {
			datepicker_thai_between('#dateStart');
		});    
		function search_request(){
			var dateStart = $("#dateStart").val();
			var query = "?dateStart="+dateStart;
			loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php'+query);
		}
	</script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="25" valign="middle" class=""><img src="../images/Folder-Downloads-icon48.png" width="48" height="48"></td>
			<td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;งานที่ได้รับมอบหมาย</h3></td>
			<td width="617" align="right" valign="bottom" class="" nowrap><div class="toolbar"></div></td></tr>
    </table></td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable1"><!-- default hover pointer display hover pointer -->
			<thead>
				<tr height="30">
					<th rowspan="2" align="center" width="5%" class="ui-state-default">เลขที่ใบขอซ่อม</th>
					<th rowspan="2" align="center" width="3%" class="ui-state-default">สถานะ</th>
					<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
					<th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
					<th rowspan="2" align="center" width="8%" class="ui-state-default">ลักษณะงานซ่อม</th>
					<th rowspan="2" align="center" width="22%" class="ui-state-default">ปัญหาก่อนการซ่อม</th>
					<th rowspan="2" align="center" width="15%" class="ui-state-default">จัดการ</th>
				</tr>
				<tr height="30">
					<th align="center"width="5%">ทะเบียน</th>
					<th align="center"width="10%">ชื่อรถ</th>           
					<th align="center"width="5%">ทะเบียน</th>
					<th align="center"width="10%">ชื่อรถ</th>    
				</tr>
			</thead>
			<tbody>
				<?php
					$SESSION_PERSONCODE = $_SESSION["AD_PERSONCODE"];
					$sql_assigned_working = "SELECT 
						DISTINCT
						REPAIRREQUEST.RPRQ_ID, 
						REPAIRREQUEST.RPRQ_CODE,
						REPAIRREQUEST.RPRQ_WORKTYPE,
						REPAIRREQUEST.RPRQ_STATUSREQUEST, 
						REPAIRREQUEST.RPRQ_REGISHEAD, 
						REPAIRREQUEST.RPRQ_CARNAMEHEAD, 
						REPAIRREQUEST.RPRQ_REGISTAIL, 
						REPAIRREQUEST.RPRQ_CARNAMETAIL, 
					-- 	REPAIRCAUSE.RPC_SUBJECT, 
						REPAIRCAUSE.RPC_DETAIL, 
						CASE 			
							WHEN REPAIRREQUEST.RPRQ_WORKTYPE = 'PM' THEN REPAIRCAUSE.RPC_DETAIL
							ELSE 	REPAIRCAUSE.RPC_SUBJECT
						END AS RPC_SUBJECT
					FROM REPAIRMANEMP
					LEFT JOIN REPAIRREQUEST	ON REPAIRREQUEST.RPRQ_CODE	= REPAIRMANEMP.RPRQ_CODE 
					LEFT JOIN REPAIRCAUSE	ON REPAIRCAUSE.RPRQ_CODE	= REPAIRMANEMP.RPRQ_CODE
					WHERE RPME_CODE = '$SESSION_PERSONCODE' AND RPRQ_STATUSREQUEST IN('กำลังซ่อม','รอคิวซ่อม') AND NOT RPRQ_STATUS = 'D'";
					$query_assigned_working = sqlsrv_query($conn, $sql_assigned_working);
					$no=0;
					while($result_assigned_working = sqlsrv_fetch_array($query_assigned_working, SQLSRV_FETCH_ASSOC)){	
						$no++;
				?>
				<tr id="" style="cursor:pointer" height="25px" align="center">   
					<td align="center"><?php print $result_assigned_working['RPRQ_ID']; ?></td>               
					<td align="center">               
						<?php
							switch($result_assigned_working['RPRQ_STATUSREQUEST']) {
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
					<td align="center"><?php print $result_assigned_working['RPRQ_REGISHEAD']; ?></td>
					<td align="center"><?php print $result_assigned_working['RPRQ_CARNAMEHEAD']; ?></td>
					<td align="center"><?php print $result_assigned_working['RPRQ_REGISTAIL']; ?></td>
					<td align="center"><?php print $result_assigned_working['RPRQ_CARNAMETAIL']; ?></td>
					<td align="left">
						<?php
							if($result_assigned_working['RPC_SUBJECT'] == "EL"){
								$text="ระบบไฟ";
							}else if($result_assigned_working['RPC_SUBJECT'] == "TU"){
								$text="ยาง ช่วงล่าง";
							}else if($result_assigned_working['RPC_SUBJECT'] == "BD"){
								$text="โครงสร้าง";
							}else if($result_assigned_working['RPC_SUBJECT'] == "EG"){
								$text="เครื่องยนต์";
							}else if($result_assigned_working['RPC_SUBJECT'] == "AC"){
								$text="อุปกรณ์ประจำรถ";
							}else{
								$text=$result_assigned_working['RPC_SUBJECT'];
							}
							print $text;
						?></td>
					<td align="left"><?php print $result_assigned_working['RPC_DETAIL']; ?></td>
					<td align="center" onclick="javascript:loadViewdetail('<?=$path?>views_amt/assigned_working/assigned_working_form.php?id=<?php print $result_assigned_working['RPRQ_CODE'];?>&proc=add');">
						<img src="../images/Process-Info-icon24.png" width="24" height="24">
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
			<input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>views_amt/assigned_working/assigned_working.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>