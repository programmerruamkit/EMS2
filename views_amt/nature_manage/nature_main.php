<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
?>
<html>
<head>
<script type="text/javascript">
	$(document).ready(function(e) {	   
		$("#button_new").click(function(){
			ajaxPopup2("<?=$path?>views_amt/nature_manage/nature_main_form.php","add","1=1","600","290","เพิ่มลักษณะงานซ่อมใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/nature_manage/nature_main_form.php","edit","1=1","1300","290","แก้ไขลักษณะงานซ่อม");
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
						var url = "<?=$path?>views_amt/nature_manage/nature_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_nature('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/nature_manage/nature_main.php');
								// alert(data);
							}
						});	
					})	
				}
			})
		});	
    });

	function swaldelete_nature_main(refcode,no) {
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
					var url = "<?=$path?>views_amt/nature_manage/nature_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_nature('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/nature_manage/nature_main.php');
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
        <td width="25" valign="middle" class=""><img src="../images/TASKLIST.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลลักษณะงานซ่อม</h3></td>
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
				<th width="20%">ชื่อลักษณะงานซ่อม</th>
				<th width="20%">รายละเอียดภายใน</th>
				<th width="20%">หมายเหตุ</th>
				<th width="20%">สถานะใช้งาน</th>
				<th width="15%">จัดการ</th>
			</tr>
		</thead>
        <tbody>
			<?php
				$AREA=$_SESSION["AD_AREA"];
				$sql_nature = "SELECT * FROM NATUREREPAIR WHERE NTRP_LEVEL='1' AND NOT NTRP_STATUS='D' AND NTRP_AREA = '$AREA'";
				$query_nature = sqlsrv_query($conn, $sql_nature);
				$no=0;
				while($result_nature = sqlsrv_fetch_array($query_nature, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$NTRP_ID=$result_nature['NTRP_ID'];
					$NTRP_CODE=$result_nature['NTRP_CODE'];
					$NTRP_NAME=$result_nature['NTRP_NAME'];
					$NTRP_LEVEL=$result_nature['NTRP_LEVEL'];
					$NTRP_PARENT=$result_nature['NTRP_PARENT'];
					$NTRP_REMARK=$result_nature['NTRP_REMARK'];
					$NTRP_STATUS=$result_nature['NTRP_STATUS'];
					$NTRP_AREA=$result_nature['NTRP_AREA'];
					
					$sql_count = "SELECT COUNT(NTRP_ID) COUNTNTRPID FROM NATUREREPAIR WHERE NTRP_PARENT = ? AND NOT NTRP_STATUS='D'";
					$params_count = array($NTRP_ID);	
					$query_count = sqlsrv_query( $conn, $sql_count, $params_count);	
					$result_count = sqlsrv_fetch_array($query_count, SQLSRV_FETCH_ASSOC);
			?>
			<tr id="<?php print $NTRP_CODE; ?>" height="25px" align="center">
				<td ><?php print "$no.";?></td>
				<td align="left" >&nbsp;<?php print $NTRP_NAME; ?></td>
				<td align="center" >&nbsp;<a href="javascript:void(0);" onClick="javascript:loadViewdetail('<?=$path?>views_amt/nature_manage/nature_sub.php?ntrp_id=<?php print $NTRP_ID; ?>&area=<?=$NTRP_AREA;?>',);"><b><?=$result_count["COUNTNTRPID"];?></b>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/add-icon16.png" width="16" height="16"></a></td>
				<td align="center" >&nbsp;<?php print $NTRP_REMARK; ?></td>
				<td align="center" >&nbsp;<?php if($NTRP_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >
					<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/nature_manage/nature_main_form.php','edit','<?php print $NTRP_CODE; ?>','1=1','1300','290','แก้ไขลักษณะงานซ่อม');"><i class='icon-pencil icon-large'></i></button>
					&nbsp;&nbsp;
					<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_nature_main('<?php print $NTRP_CODE; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/nature_manage/nature_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>