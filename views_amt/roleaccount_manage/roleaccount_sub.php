<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');	
	$GET_EMP_CODE=$_GET['personcode'];
	$SESSION_AREA=$_SESSION["AD_AREA"];

	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
?>
<html>
<head>
<script type="text/javascript">
	$(document).ready(function(e) {	   
		$("#button_new").click(function(){
			ajaxPopup2("<?=$path?>views_amt/roleaccount_manage/roleaccount_sub_form.php","add","personcode=<?php print $GET_EMP_CODE; ?>","600","260","เพิ่มสิทธิ์เมนูใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/roleaccount_manage/roleaccount_sub_form.php","edit","personcode=<?php print $GET_EMP_CODE; ?>","1300","260","แก้ไขสิทธิ์เมนู");
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
						//alert(id);
						var url = "<?=$path?>views_amt/roleaccount_manage/roleaccount_sub_proc.php?proc=delete&id="+ref;
						$.ajax({type:'GET',
							url:url,
							data:"",
							success:function(data){
								log_transac_roleaccount('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/roleaccount_manage/roleaccount_sub.php?personcode=<?php print $GET_EMP_CODE; ?>');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_roleaccount_sub(refcode,no) {
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
					var url = "<?=$path?>views_amt/roleaccount_manage/roleaccount_sub_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_roleaccount('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/roleaccount_manage/roleaccount_sub.php?personcode=<?php print $GET_EMP_CODE; ?>');
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
        <td width="25" valign="middle" class=""><img src="../images/User-Group-icon48.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการสิทธิ์ใช้งานเมนู</h3></td>
        <td width="617" align="right" valign="bottom" class="" nowrap>
            <div class="toolbar">
                <!-- <button class="bg-color-blue" style="padding-top:8px;" title="New" id="button_new"><i class='icon- new icon-large'></i></button> -->
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
				<th width="20%">ชื่อสิทธิ์ใช้งาน</th>
				<th width="15%">พื้นที่</th>
				<th width="15%">สถานะใช้งาน</th>
				<th width="15%">จัดการ</th>
			</tr>
		</thead>
        <tbody>
			<?php
				$sql_selmainrole = "SELECT * FROM vwROLEACOUNT WHERE PersonCode = ? AND RU_AREA = ? AND NOT RU_ID IS NULL ORDER BY RU_ID ASC";
				$params_selmainrole = array($GET_EMP_CODE,$SESSION_AREA);	
				$query_selmainrole = sqlsrv_query( $conn, $sql_selmainrole, $params_selmainrole);	
				$no=0;
				while($result_selmainrole = sqlsrv_fetch_array($query_selmainrole, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$RA_ID=$result_selmainrole['RA_ID'];
					$RU_NAME=$result_selmainrole['RU_NAME'];
					$RU_AREA=$result_selmainrole['RU_AREA'];
					$ROACACTIVE=$result_selmainrole['ROACACTIVE'];
			?>
			<tr id="<?php print $RA_ID; ?>" height="25px" align="center">
				<td ><?php print "$no.";?></td>
				<td align="left" >&nbsp;<?php print $RU_NAME; ?></td>
				<td align="center" >&nbsp;<?php print $RU_AREA; ?></td>
				<td align="center" >&nbsp;<?php if($ROACACTIVE=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >
					<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/roleaccount_manage/roleaccount_sub_form.php','edit','<?php print $RA_ID; ?>&personcode=<?php print $GET_EMP_CODE; ?>','1=1','1300','260','แก้ไขสิทธิ์เมนู');"><i class='icon-pencil icon-large'></i></button>
					&nbsp;&nbsp;
					<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_roleaccount_sub('<?php print $RA_ID; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button>
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
			<input type="button" class="button_red2" value="ย้อนกลับ" onClick="javascript:loadViewdetail('<?=$path?>views_amt/roleaccount_manage/roleaccount_main.php');">
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/roleaccount_manage/roleaccount_sub.php?personcode=<?=$GET_EMP_CODE;?>');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>