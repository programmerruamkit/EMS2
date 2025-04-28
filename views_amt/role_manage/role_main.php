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
			ajaxPopup2("<?=$path?>views_amt/role_manage/role_main_form.php","add","1=1","600","420","เพิ่มสิทธิ์ใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/role_manage/role_main_form.php","edit","1=1","1300","420","แก้ไขสิทธิ์");
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
						var url = "<?=$path?>views_amt/role_manage/role_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_role('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/role_manage/role_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_role_main(refcode,no) {
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
					var url = "<?=$path?>views_amt/role_manage/role_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_role('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/role_manage/role_main.php');
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
        <td width="25" valign="middle" class=""><img src="../images/Lock-Lock-icon48.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลสิทธิ์ใช้งาน</h3></td>
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
				<th width="15%">ชื่อสิทธิ์</th>
				<th width="10%">พื้นที่</th>
				<th width="15%">สิทธิ์ใช้งานเมนู</th>
				<th width="15%">รูปแสดงแทนสิทธิ์</th>
				<th width="10%">ลำดับการแสดง</th>
				<th width="15%">สถานะใช้งาน</th>
				<th width="20%">จัดการ</th>
			</tr>
		</thead>
        <tbody>
			<?php
				$sql_role = "SELECT * FROM ROLE_USER WHERE NOT RU_STATUS ='D' AND RU_AREA = '$SESSION_AREA'";
				$query_role = sqlsrv_query($conn, $sql_role);
				$no=0;
				while($result_role = sqlsrv_fetch_array($query_role, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$RU_ID=$result_role['RU_ID'];
					$RU_CODE=$result_role['RU_CODE'];
					$RU_NAME=$result_role['RU_NAME'];
					$RU_IMG=$result_role['RU_IMG'];
					$RU_SORT=$result_role['RU_SORT'];
					$RU_AREA=$result_role['RU_AREA'];
					$RU_STATUS=$result_role['RU_STATUS'];
					
					$sql_count = "SELECT COUNT(MN_ID) COUNTMNID FROM ROLE_MENU WHERE RU_ID = ? AND NOT RM_STATUS='D'";
					// $sql_count = "SELECT COUNT(MN_ID) COUNTMNID FROM ROLE_MENU WHERE RU_ID = ?";
					$params_count = array($RU_ID);	
					$query_count = sqlsrv_query( $conn, $sql_count, $params_count);	
					$result_count = sqlsrv_fetch_array($query_count, SQLSRV_FETCH_ASSOC);
			?>
			<tr id="<?php print $RU_CODE; ?>" height="25px" align="center">
				<td ><?php print "$no.";?></td>
				<td align="left" >&nbsp;<?php print $RU_NAME; ?></td>
				<td align="center" >&nbsp;<?php print $RU_AREA; ?></td>
				<td align="center" >&nbsp;<a href="javascript:void(0);" onClick="javascript:loadViewdetail('<?=$path?>views_amt/role_manage/role_menu.php?rm_id=<?php print $RU_ID; ?>&area=<?=$RU_AREA;?>',);"><b><?=$result_count["COUNTMNID"];?></b>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/add-icon16.png" width="16" height="16"></a></td>
				<td align="left" >&nbsp;<?php print $RU_IMG; ?></td>
				<td align="center" >&nbsp;<?php print $RU_SORT; ?></td>
				<td align="center" >&nbsp;<?php if($RU_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >
					<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/role_manage/role_main_form.php','edit','<?php print $RU_CODE; ?>','1=1','1300','420','แก้ไขสิทธิ์');"><i class='icon-pencil icon-large'></i></button>
					&nbsp;&nbsp;
					<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_role_main('<?php print $RU_CODE; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/role_manage/role_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>