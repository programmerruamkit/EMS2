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
			ajaxPopup2("<?=$path?>views_project/sparepart_manage/sparepart_main_form.php","add","1=1","550","400","เพิ่มอะไหล่ใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_project/sparepart_manage/sparepart_main_form.php","edit","1=1","1000","340","แก้ไขอะไหล่");
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
						var ref = getIdSelect(); 
						var url = "<?=$path?>views_project/sparepart_manage/sparepart_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_sparepart('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_project/sparepart_manage/sparepart_main.php');
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_sparepart_main(refcode,no) {
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
					var ref = refcode; 
					var url = "<?=$path?>views_project/sparepart_manage/sparepart_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_sparepart('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_project/sparepart_manage/sparepart_main.php');
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
        <td width="25" valign="middle" class=""><img src="../images/PM.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลอะไหล่</h3></td>
        <td width="617" align="right" valign="bottom" class="" nowrap>
            <div class="toolbar">
                <button class="bg-color-blue" style="padding-top:8px;" title="New" id="button_new"><i class='icon-plus icon-large'></i></button>
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
				<th width="10%">รหัสอะไหล่</th>
				<th width="20%">ชื่ออะไหล่</th>
				<th width="15%">อายุอะไหล่/ปี</th>
				<th width="10%">สถานะ</th>
				<th width="15%">จัดการ</th>
			</tr>
		</thead>
        <tbody>
			<?php
				$AREA=$_SESSION["AD_AREA"];
				$sql_sparepart = "SELECT * FROM PROJECT_SPAREPART WHERE PJSPP_AREA = '$AREA' 
									AND PJSPP_STATUS <> 'D' 
									ORDER BY PJSPP_CODENAME ASC";
				$query_sparepart = sqlsrv_query($conn, $sql_sparepart);
				$no=0;
				while($result_sparepart = sqlsrv_fetch_array($query_sparepart, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$PJSPP_ID=$result_sparepart['PJSPP_ID'];
					$PJSPP_CODE=$result_sparepart['PJSPP_CODE'];
					$PJSPP_CODENAME=$result_sparepart['PJSPP_CODENAME'];
					$PJSPP_CODESPP=$result_sparepart['PJSPP_CODESPP'];
					$PJSPP_NAME=$result_sparepart['PJSPP_NAME'];
					$PJSPP_EXPIRE_YEAR=$result_sparepart['PJSPP_EXPIRE_YEAR'];
					$PJSPP_SPECIFIED_DISTANCE=$result_sparepart['PJSPP_SPECIFIED_DISTANCE'];
					$PJSPP_STATUS=$result_sparepart['PJSPP_STATUS'];
					$PJSPP_AREA=$result_sparepart['PJSPP_AREA'];
			?>
			<tr id="<?php print $PJSPP_CODE; ?>" height="25px" align="center">
				<td ><?php print "$no.";?></td>
				<td align="center" ><?php print $PJSPP_CODESPP; ?></td>
				<td align="left" ><?php print $PJSPP_NAME; ?></td>
				<td align="center" ><?php print $PJSPP_EXPIRE_YEAR; ?></td>
				<td align="center" ><?php if($PJSPP_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >
					<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_project/sparepart_manage/sparepart_main_form.php','edit','<?php print $PJSPP_CODE; ?>','1=1','1300','400','แก้ไขอะไหล่');"><i class='icon-pencil icon-large'></i></button>
					&nbsp;&nbsp;
					<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_sparepart_main('<?php print $PJSPP_CODE; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_project/sparepart_manage/sparepart_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>