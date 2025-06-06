<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
?>
<html>
<head>
<script type="text/javascript">
	$(document).ready(function(e) {	   
		$("#button_new").click(function(){
			ajaxPopup2("<?=$path?>views_amt/menu_manage/menu_main_form.php","add","1=1","600","315","เพิ่มเมนูใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/menu_manage/menu_main_form.php","edit","1=1","1300","315","แก้ไขเมนู");
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
						var url = "<?=$path?>views_amt/menu_manage/menu_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_menu('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/menu_manage/menu_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_menu_main(refcode,no) {
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
					var url = "<?=$path?>views_amt/menu_manage/menu_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_menu('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/menu_manage/menu_main.php');
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
        <td width="25" valign="middle" class=""><img src="../images/Folder-Data-icon48.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลเมนูหลัก</h3></td>
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
				<th width="20%">ชื่อเมนู</th>
				<th width="15%">เมนูย่อยภายใน</th>
				<th width="15%">พื้นที่</th>
				<th width="15%">ลำดับการแสดง</th>
				<th width="15%">สถานะใช้งาน</th>
				<th width="20%">จัดการ</th>
			</tr>
		</thead>
        <tbody>
			<?php
				$sql_menu = "SELECT * FROM MENU WHERE MN_LEVEL='1' AND NOT MN_STATUS='D'";
				$query_menu = sqlsrv_query($conn, $sql_menu);
				$no=0;
				while($result_menu = sqlsrv_fetch_array($query_menu, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$MN_ID=$result_menu['MN_ID'];
					$MN_CODE=$result_menu['MN_CODE'];
					$MN_NAME=$result_menu['MN_NAME'];
					$MN_LEVEL=$result_menu['MN_LEVEL'];
					$MN_SORT=$result_menu['MN_SORT'];
					$MN_PARENT=$result_menu['MN_PARENT'];
					$MN_URL=$result_menu['MN_URL'];
					$MN_STATUS=$result_menu['MN_STATUS'];
					$MN_AREA=$result_menu['MN_AREA'];
					
					$sql_count = "SELECT COUNT(MN_ID) COUNTMNID FROM MENU WHERE MN_PARENT = ? AND NOT MN_STATUS='D'";
					$params_count = array($MN_ID);	
					$query_count = sqlsrv_query( $conn, $sql_count, $params_count);	
					$result_count = sqlsrv_fetch_array($query_count, SQLSRV_FETCH_ASSOC);
			?>
			<tr id="<?php print $MN_CODE; ?>" height="25px" align="center">
				<td ><?php print "$no.";?></td>
				<td align="left" >&nbsp;<?php print $MN_NAME; ?></td>
				<td align="center" >&nbsp;<a href="javascript:void(0);" onClick="javascript:loadViewdetail('<?=$path?>views_amt/menu_manage/menu_sub.php?mn_id=<?php print $MN_ID; ?>&area=<?=$MN_AREA;?>',);"><b><?=$result_count["COUNTMNID"];?></b>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/add-icon16.png" width="16" height="16"></a></td>
				<td align="center" >&nbsp;<?php print $MN_AREA; ?></td>
				<td align="center" >&nbsp;<?php print $MN_SORT; ?></td>
				<td align="center" >&nbsp;<?php if($MN_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >
					<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/menu_manage/menu_main_form.php','edit','<?php print $MN_CODE; ?>','1=1','1300','320','แก้ไขเมนู');"><i class='icon-pencil icon-large'></i></button>
					&nbsp;&nbsp;
					<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_menu_main('<?php print $MN_CODE; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/menu_manage/menu_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>