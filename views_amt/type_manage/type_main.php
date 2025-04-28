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
			ajaxPopup2("<?=$path?>views_amt/type_manage/type_main_form.php","add","1=1","600","355","เพิ่มประเภทงานซ่อม");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/type_manage/type_main_form.php","edit","1=1","1300","355","แก้ไขประเภทงานซ่อม");
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
						var url = "<?=$path?>views_amt/type_manage/type_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_typerepairwork('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/type_manage/type_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_typerepairwork(refcode,no) {
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
					var url = "<?=$path?>views_amt/type_manage/type_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_typerepairwork('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/type_manage/type_main.php');
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
        <td width="25" valign="middle" class=""><img src="../images/57.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการประเภทงานซ่อม</h3></td>
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
				<th width="20%">ชื่อประเภทงาน</th>
				<th width="20%">ลักษณะงานซ่อม</th>
				<th width="15%">ประเภทงานซ่อม</th>
				<th width="10%">สถานะใช้งาน</th>
				<th width="20%">จัดการ</th>
			</tr>
		</thead>
        <tbody>
			<?php
				$AREA=$_SESSION["AD_AREA"];
				$sql_typerepairwork = "SELECT A.TRPW_ID,A.TRPW_CODE,B.NTRP_NAME,A.TRPW_NAME,A.TRPW_REMARK,A.TRPW_AREA,A.TRPW_STATUS
				FROM dbo.TYPEREPAIRWORK AS A
				LEFT JOIN dbo.NATUREREPAIR AS B ON B.NTRP_ID = A.NTRP_ID
				WHERE NOT TRPW_STATUS = 'D' AND A.TRPW_AREA = '$AREA'";
				$query_typerepairwork = sqlsrv_query($conn, $sql_typerepairwork);
				$no=0;
				while($result_typerepairwork = sqlsrv_fetch_array($query_typerepairwork, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$TRPW_ID=$result_typerepairwork['TRPW_ID'];
					$TRPW_CODE=$result_typerepairwork['TRPW_CODE'];
					$NTRP_NAME=$result_typerepairwork['NTRP_NAME'];
					$TRPW_NAME=$result_typerepairwork['TRPW_NAME'];
					$TRPW_REMARK=$result_typerepairwork['TRPW_REMARK'];
					if($result_typerepairwork['TRPW_REMARK']=='EG'){
						$TRPW_REMARK_CON='เครื่องยนต์';
					}else if($result_typerepairwork['TRPW_REMARK']=='BD'){
						$TRPW_REMARK_CON='โครงสร้าง';
					}else if($result_typerepairwork['TRPW_REMARK']=='TU'){
						$TRPW_REMARK_CON='ยาง ช่วงล่าง';
					}else if($result_typerepairwork['TRPW_REMARK']=='EL'){
						$TRPW_REMARK_CON='ระบบไฟ';
					}else{
						$TRPW_REMARK_CON='';
					}
					$TRPW_AREA=$result_typerepairwork['TRPW_AREA'];
					$TRPW_STATUS=$result_typerepairwork['TRPW_STATUS'];
			?>
			<tr id="<?php print $TRPW_CODE; ?>" height="25px" align="center">
				<td ><?php print "$no.";?></td>
				<td align="left" >&nbsp;<?php print $TRPW_NAME; ?></td>
				<td align="left" >&nbsp;<?php print $NTRP_NAME; ?></td>
				<td align="left" >&nbsp;<?php print $TRPW_REMARK_CON; ?></td>
				<td align="center" >&nbsp;<?php if($TRPW_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >
					<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/type_manage/type_main_form.php','edit','<?php print $TRPW_CODE; ?>','1=1','1300','355','แก้ไขประเภทงานซ่อม');"><i class='icon-pencil icon-large'></i></button>
					&nbsp;&nbsp;
					<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_typerepairwork('<?php print $TRPW_CODE; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/type_manage/type_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>