<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	$SESSION_AREA=$_SESSION["AD_AREA"];
?>
<html>
<head>
<script type="text/javascript">
	$(document).ready(function(e) {	   
		$("#button_new").click(function(){
			ajaxPopup2("<?=$path?>views_amt/satisfaction_survey/survey_main_form.php","add","1=1","600","320","เพิ่มกลุ่มแบบประเมินใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/satisfaction_survey/survey_main_form.php","edit","1=1","1300","315","แก้ไขกลุ่มแบบประเมิน");
		});
		
		$("#button_delete").click(function(){
			Swal.fire({
				title: 'คุณแน่ใจหรือไม่...ที่จะลบรายการนี้?',
				text: "หากลบแล้ว คุณจะไม่สามารถกู้คืนข้อมูลได้!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#C82333',
				confirmButtonText: 'ใช่! ลบเลย',
				cancelButtonText: 'ยกเลิก'
			}).then((result) => {
				if (result.isConfirmed) {
					Swal.fire({
						icon: 'success',
						title: 'ลบข้อมูลเรียบร้อยแล้ว',
						showConfirmButton: false,
						timer: 2000
					}).then((result) => {	
						// if(!confirm("ยืนยันการลบข้อมูลอีกครั้ง")) return false;
						var ref = getIdSelect(); 
						var url = "<?=$path?>views_amt/satisfaction_survey/survey_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_menu('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_survey_main(refcode,no) {
		Swal.fire({
			title: 'คุณแน่ใจหรือไม่...ที่จะลบรายการที่ '+no+' นี้',
			text: "หากลบแล้ว คุณจะไม่สามารถกู้คืนข้อมูลได้!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#C82333',
			confirmButtonText: 'ใช่! ลบเลย',
			cancelButtonText: 'ยกเลิก'
		}).then((result) => {
			if (result.isConfirmed) {
				Swal.fire({
					icon: 'success',
					title: 'ลบข้อมูลเรียบร้อยแล้ว',
					showConfirmButton: false,
					timer: 2000
				}).then((result) => {	
					// if(!confirm("ยืนยันการลบข้อมูลอีกครั้ง")) return false;
					// var ref = getIdSelect(); 
					var ref = refcode; 
					var url = "<?=$path?>views_amt/satisfaction_survey/survey_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_menu('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_main.php');
							// alert(data);
						}
					});
				})	
			}
		})
	}
</script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25" valign="middle" class=""><img src="../images/checklist-icon128.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการแบบประเมินความพึงพอใจ</h3></td>
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
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
			<tbody>	
				<tr>
					<td>
						<button type="button" class="bg-color-blue" id="button_new">
							<font color="#FFFFFF"><i class="fa fa-plus"></i> เพิ่มกลุ่มแบบประเมิน</font>
						</button>
					</td>
				</tr>
			</tbody>
		</table>
		<form id="form1" name="form1" method="post" action="#">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
				<thead>
					<tr height="30">
						<th width="5%">ลำดับ.</th>
						<th width="35%">ชื่อกลุ่มแบบประเมิน (กลุ่มเป้าหมาย)</th>
						<th width="20%">จัดการหมวดหมู่ : คำถาม</th>
						<th width="10%">พื้นที่</th>
						<th width="10%">สถานะ</th>
						<th width="20%">จัดการ</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$stmt = "SELECT * FROM SURVEY_MAIN WHERE NOT SM_STATUS='D' AND SM_AREA = ? ORDER BY SM_ID ASC";
						$params = array($SESSION_AREA);
						$query = sqlsrv_query($conn, $stmt, $params);
						$number = 0;
						while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
							$number++;
							$SM_ID = $result["SM_ID"];
							$SM_CODE = $result["SM_CODE"];
							$SM_NAME = $result["SM_NAME"];
							$SM_DESCRIPTION = $result["SM_DESCRIPTION"];
							$SM_TARGET_GROUP = $result["SM_TARGET_GROUP"];
							$SM_AREA = $result["SM_AREA"];
							$SM_STATUS = $result["SM_STATUS"];
							
							$sql_count = "SELECT A.SM_CODE,A.SC_CODE,( SELECT COUNT ( SM_CODE ) FROM SURVEY_CATEGORY WHERE SM_CODE = A.SM_CODE AND SC_STATUS <> 'D' ) AS COUNTSMCODE 
								FROM SURVEY_CATEGORY A WHERE A.SM_CODE = ? AND A.SC_STATUS <> 'D' GROUP BY A.SM_CODE,A.SC_CODE;";
							$params_count = array($SM_CODE);	
							$query_count = sqlsrv_query( $conn, $sql_count, $params_count);	
							$result_count = sqlsrv_fetch_array($query_count, SQLSRV_FETCH_ASSOC);
								$SC_CODE = $result_count["SC_CODE"];
								$COUNTSMCODE = $result_count["COUNTSMCODE"];
								if($COUNTSMCODE==""){ $COUNTSMCODE=0; }
													

							$sql_count_question = "SELECT C.SC_CODE,COUNT(Q.SQ_ID) AS COUNTSCCODE
								FROM SURVEY_CATEGORY C LEFT JOIN SURVEY_QUESTION Q ON Q.SC_CODE = C.SC_CODE AND Q.SQ_STATUS <> 'D'
								WHERE C.SM_CODE = ? AND C.SC_STATUS <> 'D' GROUP BY C.SC_CODE;";
							$params_count_question = array($SM_CODE);	
							$query_count_question = sqlsrv_query( $conn, $sql_count_question, $params_count_question);	
							$total_count_question = 0; // ตัวแปรเก็บผลรวมทั้งหมด
							while ($row = sqlsrv_fetch_array($query_count_question, SQLSRV_FETCH_ASSOC)) {
								$SC_CODE = $row["SC_CODE"];
								$COUNTSCCODE = (int)$row["COUNTSCCODE"];								
								// รวมค่าทั้งหมด
								$total_count_question += $COUNTSCCODE;
							}

					?>
					<tr id="<?php print $SM_CODE; ?>" height="25px" align="center">
						<td ><?php print "$number.";?></td>
						<td align="left" >&nbsp;<?php print $SM_NAME; ?> (<?php print $SM_TARGET_GROUP; ?>)</td>
						<td align="center" >&nbsp;<a href="javascript:void(0);" onclick="javascript:loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_sub.php?sm_code=<?php print $SM_CODE; ?>&area=<?=$SM_AREA;?>',);"><b><?=$COUNTSMCODE;?> : <?=$total_count_question;?></b>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/add-icon16.png" width="16" height="16"></a></td>
						<td align="center" >&nbsp;<?php print $SM_AREA; ?></td>
						<td align="center" >&nbsp;<?php if($SM_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
						<td align="center" >
							<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/satisfaction_survey/survey_main_form.php','edit','<?php print $SM_CODE; ?>','1=1','1300','320','แก้ไขกลุ่มแบบประเมิน');"><i class='icon-pencil icon-large'></i></button>
							&nbsp;&nbsp;
							<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_survey_main('<?php print $SM_CODE; ?>','<?php print $number;?>')"><i class="icon-cancel icon-large"></i></button>
						</td>
					</tr>
					<?php }; ?>
				</tbody>
			</table>
		</form>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
		<center>
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>