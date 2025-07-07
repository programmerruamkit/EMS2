<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');	
	$GET_RU_ID=$_GET['rm_id'];
	$RU_AREA = $_GET["area"];	

	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$sql_selmainrole = "SELECT * FROM ROLE_USER WHERE RU_ID = ? AND NOT RU_STATUS='D'";
	$params_selmainrole = array($GET_RU_ID);	
	$query_selmainrole = sqlsrv_query( $conn, $sql_selmainrole, $params_selmainrole);	
	$result_selmainrole = sqlsrv_fetch_array($query_selmainrole, SQLSRV_FETCH_ASSOC);
?>
<html>
<head>
<script type="text/javascript">
	$(document).ready(function(e) {	   
		$("#button_new").click(function(){
			ajaxPopup2("<?=$path?>views_amt/role_manage/role_menu_form.php","add","rm_id=<?php print $GET_RU_ID; ?>&area=<?=$RU_AREA;?>","600","280","เพิ่มสิทธิ์เมนูใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/role_manage/role_menu_form.php","edit","rm_id=<?php print $GET_RU_ID; ?>&area=<?=$RU_AREA;?>","1300","280","แก้ไขสิทธิ์เมนู");
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
						var url = "<?=$path?>views_amt/role_manage/role_menu_proc.php?proc=delete&id="+ref;
						$.ajax({type:'GET',
							url:url,
							data:"",
							success:function(data){
								log_transac_role('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/role_manage/role_menu.php?rm_id=<?php print $GET_RU_ID; ?>&area=<?=$RU_AREA;?>');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_role_menu(refcode,no) {
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
					var url = "<?=$path?>views_amt/role_manage/role_menu_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_role('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/role_manage/role_menu.php?rm_id=<?php print $GET_RU_ID; ?>&area=<?=$RU_AREA;?>');
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
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการสิทธิ์ใช้งานเมนู</h3></td>
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
				<th width="20%">ชื่อเมนูตามสิทธิ์ใช้งาน</th>
				<th width="10%">กลุ่มเมนู</th>
				<th width="10%">พื้นที่</th>
				<th width="10%">สถานะสิทธิ์เมนู</th>
				<th width="10%">สถานะสิทธิ์</th>
				<th width="10%">สถานะใช้งาน</th>
				<th width="10%">จัดการ</th>
			</tr>
		</thead>
        <tbody>
			<?php
				$sql_role_menu = "SELECT RM.RM_ID,RM.RM_CODE,RM.RU_ID,RU.RU_NAME,RM.MN_ID,MN.MN_NAME,RM.AREA,MN.MN_LEVEL,RM.RM_STATUS,RU.RU_STATUS,MN.MN_STATUS
					FROM dbo.ROLE_MENU AS RM
					LEFT JOIN dbo.ROLE_USER AS RU	ON RU.RU_ID = RM.RU_ID
					LEFT JOIN dbo.MENU AS MN ON MN.MN_ID = RM.MN_ID
					WHERE RM.RU_ID = '$GET_RU_ID' AND NOT RM.RM_STATUS ='D' ORDER BY RM.RM_ID ASC";
					// WHERE NOT RM_STATUS = 'D' AND RM.RU_ID = '$GET_RU_ID'";
				$query_role_menu = sqlsrv_query($conn, $sql_role_menu);
				$no=0;
				while($result_role_menu = sqlsrv_fetch_array($query_role_menu, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$RM_ID=$result_role_menu['RM_ID'];
					$RM_CODE=$result_role_menu['RM_CODE'];
					$RU_ID=$result_role_menu['RU_ID'];
					$RU_NAME=$result_role_menu['RU_NAME'];
					$MN_ID=$result_role_menu['MN_ID'];
					$MN_NAME=$result_role_menu['MN_NAME'];
					$AREA=$result_role_menu['AREA'];
					$MN_LEVEL=$result_role_menu['MN_LEVEL'];
					$RM_STATUS=$result_role_menu['RM_STATUS'];
					$RU_STATUS=$result_role_menu['RU_STATUS'];
					$MN_STATUS=$result_role_menu['MN_STATUS'];
			?>
			<tr id="<?php print $RM_CODE; ?>" height="25px" align="center">
				<td ><?php print "$no.";?></td>
				<td align="left" >&nbsp;<?php print $MN_NAME; ?></td>
				<td align="center" >&nbsp;<?php if($MN_LEVEL=="1"){print "<font color='black'>MAIN</font>";}else{print "<font color='black'>SUB</font>";}?></td>
				<td align="center" >&nbsp;<?php print $AREA; ?></td>
				<td align="center" >&nbsp;<?php if($RM_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >&nbsp;<?php if($RU_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >&nbsp;<?php if($MN_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >
					<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/role_manage/role_menu_form.php','edit','<?php print $RM_CODE; ?>&rm_id=<?php print $GET_RU_ID; ?>&area=<?=$RU_AREA;?>','1=1','1300','280','แก้ไขสิทธิ์เมนู');"><i class='icon-pencil icon-large'></i></button>
					&nbsp;&nbsp;
					<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_role_menu('<?php print $RM_CODE; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button>
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
			<input type="button" class="button_red2" value="ย้อนกลับ" onClick="javascript:loadViewdetail('<?=$path?>views_amt/role_manage/role_main.php');">
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/role_manage/role_menu.php?rm_id=<?=$GET_RU_ID;?>&area=<?=$RU_AREA;?>');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>