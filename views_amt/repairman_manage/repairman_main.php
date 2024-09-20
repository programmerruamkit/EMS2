<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	$SESSION_AREA=$_SESSION["AD_AREA"];
?>
<html>
<head>
<script type="text/javascript">
	$(document).ready(function(e) {	   
		$("#button_new").click(function(){
			ajaxPopup2("<?=$path?>views_amt/repairman_manage/repairman_main_form.php","add","1=1","650","350","เพิ่มช่าง");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/repairman_manage/repairman_main_form.php","edit","1=1","1300","400","แก้ไขช่าง");
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
						var url = "<?=$path?>views_amt/repairman_manage/repairman_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_repairman('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/repairman_manage/repairman_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_repairman_main(refcode,no) {
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
					var url = "<?=$path?>views_amt/repairman_manage/repairman_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_repairman('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/repairman_manage/repairman_main.php');
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
        <td width="25" valign="middle" class=""><img src="../images/RPM.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลช่าง</h3></td>
        <td width="617" align="right" valign="bottom" class="" nowrap>
            <div class="toolbar">
                <button class="bg-color-blue" style="padding-top:8px;" title="New" id="button_new"><i class='icon-plus icon-large'></i></button>
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
    <form id="form1" name="form1" method="post" action="#">
      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
		<thead>
			<tr height="30">
				<th width="5%">ลำดับ.</th>
				<th width="10%">รหัสพนักงาน</th>
				<th width="15%">ชื่อพนักงาน</th>
				<th width="5%">พื้นที่</th>
				<!-- <th width="10%">ลักษณะการซ่อม</th> -->
				<th width="20%">ทักษะความสามารถ</th>
				<th width="15%">ชม.มาตรฐานการซ่อม</th>
				<th width="15%">จัดการ</th>
			</tr>
		</thead>
        <tbody>
			<?php
				$sql_menu = "SELECT * FROM vwREPAIRMAN";
				$query_menu = sqlsrv_query($conn, $sql_menu);
				$no=0;
				while($result_repairman = sqlsrv_fetch_array($query_menu, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$RPM_ID=$result_repairman['ID'];
					$RPM_CODE=$result_repairman['RPM_CODE'];
					$RPM_PERSONCODE=$result_repairman['PersonCode'];
					$RPM_PERSONNAME=$result_repairman['nameT'];
					$RPM_HOURSSTANDARD=$result_repairman['RPM_HOURSSTANDARD'];
					$RPM_SKILL_EL=$result_repairman['RPM_SKILL_EL'];
					$RPM_SKILL_TU=$result_repairman['RPM_SKILL_TU'];
					$RPM_SKILL_BD=$result_repairman['RPM_SKILL_BD'];
					$RPM_SKILL_EG=$result_repairman['RPM_SKILL_EG'];
					$RPM_SKILL_AC=$result_repairman['RPM_SKILL_AC'];
					if($RPM_SKILL_EL=="1"){$EL='ระบบไฟ,';}else{$EL='';}
					if($RPM_SKILL_TU=="1"){$TU='ยาง ช่วงล่าง,';}else{$TU='';}
					if($RPM_SKILL_BD=="1"){$BD='โครงสร้าง,';}else{$BD='';}
					if($RPM_SKILL_EG=="1"){$EG='เครื่องยนต์,';}else{$EG='';}
					if($RPM_SKILL_AC=="1"){$AC='อุปกรณ์ประจำรถ';}else{$AC='';}
					$SKILL=$EL.$TU.$BD.$EG.$AC;
					$RPMNATUREREPAIR=$result_repairman['RPM_NATUREREPAIR'];
					if($RPMNATUREREPAIR=="EL"){
						$RPM_NATUREREPAIR='ระบบไฟ';
					}else if($RPMNATUREREPAIR=="TU"){
						$RPM_NATUREREPAIR='ยาง ช่วงล่าง';
					}else if($RPMNATUREREPAIR=="BD"){
						$RPM_NATUREREPAIR='โครงสร้าง';
					}else if($RPMNATUREREPAIR=="EG"){
						$RPM_NATUREREPAIR='เครื่องยนต์';
					}else if($RPMNATUREREPAIR=="AC"){
						$RPM_NATUREREPAIR='อุปกรณ์ประจำรถ';
					}else{
						$RPM_NATUREREPAIR='';
					}
					$RPM_STATUS=$result_repairman['RPM_STATUS'];
					$RPM_AREA=$result_repairman['RPM_AREA'];
			?>
			<tr id="<?php print $RPM_CODE; ?>" height="25px" align="center">
				<td ><?php print "$no.";?></td>
				<td align="center" >&nbsp;<?php print $RPM_PERSONCODE; ?></td>
				<td align="left" >&nbsp;<?php print $RPM_PERSONNAME; ?></td>
				<td align="center" >&nbsp;<?php print $RPM_AREA; ?></td>
				<!-- <td align="center" >&nbsp;<?php print $RPM_NATUREREPAIR; ?></td> -->
				<td align="left" >&nbsp;<?php print $SKILL; ?></td>
				<td align="center" >&nbsp;<?php print $RPM_HOURSSTANDARD; ?></td>
				<td align="center" >
					<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/repairman_manage/repairman_main_form.php','<?php if($RPM_CODE!=''){print 'edit';}else{print 'add2';} ?>','<?php if($RPM_CODE!=''){print $RPM_CODE;}else{print $RPM_ID;} ?>','1=1','<?php if($RPM_CODE!=''){print '1300';}else{print '650';} ?>','350','แก้ไขช่างซ่อม');"><i class='icon-pencil icon-large'></i></button>
					&nbsp;&nbsp;
					<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_repairman_main('<?php print $RPM_CODE; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/repairman_manage/repairman_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>