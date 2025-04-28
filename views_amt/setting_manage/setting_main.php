<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	$SESSION_AREA = $_SESSION["AD_AREA"];
?>
<html>
<head>
<script type="text/javascript">
	$(document).ready(function(e) {	   
		$("#button_new").click(function(){
			ajaxPopup2("<?=$path?>views_amt/setting_manage/setting_main_form.php","add","1=1","600","400","เพิ่มรายการใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/setting_manage/setting_main_form.php","edit","1=1","1300","400","แก้ไขรายการ");
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
						var url = "<?=$path?>views_amt/setting_manage/setting_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								loadViewdetail('<?=$path?>views_amt/setting_manage/setting_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_setting_main(refcode,no) {
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
					var url = "<?=$path?>views_amt/setting_manage/setting_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							loadViewdetail('<?=$path?>views_amt/setting_manage/setting_main.php');
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
        <td width="25" valign="middle" class=""><img src="../images/cog.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการการแจ้งเตือน</h3></td>
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
				<th width="10%">ลำดับ.</th>
				<th width="30%">ชื่อรายการ</th>
				<!-- <th width="15%">รายการย่อยภายใน</th> -->
				<!-- <th width="15%">พื้นที่</th> -->
				<th width="30%">รายละเอียด / TOKEN</th>
				<th width="10%">เวลา</th>
				<!-- <th width="10%">พื้นที่</th> -->
				<th width="10%">สถานะ</th>
				<th width="10%">จัดการ</th>
			</tr>
		</thead>
        <tbody>
			<?php
				$sql_setting = "SELECT * FROM SETTING WHERE NOT ST_STATUS='D' AND ST_AREA = '$SESSION_AREA' ORDER BY ST_ID ASC";
				$query_setting = sqlsrv_query($conn, $sql_setting);
				$no=0;
				while($result_setting = sqlsrv_fetch_array($query_setting, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$ST_ID=$result_setting['ST_ID'];
					$ST_CODE=$result_setting['ST_CODE'];
					$ST_NAME=$result_setting['ST_NAME'];
					$ST_GROUP=$result_setting['ST_GROUP'];
					$ST_TYPE=$result_setting['ST_TYPE'];
					$ST_DETAIL=$result_setting['ST_DETAIL'];
					$ST_AREA=$result_setting['ST_AREA'];
					$ST_STATUS=$result_setting['ST_STATUS'];
					$ST_TIME=$result_setting['ST_TIME'];
					if($ST_TYPE=='27'){
						$ST_TYPE_NAME='LineNotify แจ้งซ่อม - ';
					}else if($ST_TYPE=='28'){
						$ST_TYPE_NAME='LineNotify ตรวจสอบแจ้งซ่อม - ';
					}else if($ST_TYPE=='29'){
						$ST_TYPE_NAME='LineNotify จ่ายงาน - ';
					}else if($ST_TYPE=='30'){
						$ST_TYPE_NAME='LineNotify ปิดงาน - ';
					}else if($ST_TYPE=='31'){
						$ST_TYPE_NAME='LineNotify เตือนล่วงหน้า - ';
					}else if($ST_TYPE=='32'){
						$ST_TYPE_NAME='LineNotify เข้าซ่อมล่าช้า - ';
					}else if($ST_TYPE=='33'||$ST_TYPE=='34'||$ST_TYPE=='35'||$ST_TYPE=='36'){
						$ST_TYPE_NAME='Telegram - ';
					}else{
						$ST_TYPE_NAME='';
					}
					
			?>
			<tr id="<?php print $ST_CODE; ?>" height="25px" align="center">
				<td ><?php print "$no.";?></td>
				<td align="left" >&nbsp;<?php print $ST_TYPE_NAME.$ST_NAME; ?></td>
				<td align="left" >&nbsp;<?php print $ST_DETAIL; ?></td>
				<td align="center" >&nbsp;<?php print $ST_TIME; ?></td>
				<!-- <td align="center" >&nbsp;<?php print $ST_AREA; ?></td> -->
				<td align="center" >&nbsp;<?php if($ST_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >
					<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/setting_manage/setting_main_form.php','edit','<?php print $ST_CODE; ?>','1=1','600','400','แก้ไขรายการ');"><i class='icon-pencil icon-large'></i></button>
					<!-- &nbsp;&nbsp; -->
					<!-- <button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_setting_main('<?php print $ST_CODE; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button> -->
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/setting_manage/setting_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>