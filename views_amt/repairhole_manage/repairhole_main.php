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
			ajaxPopup2("<?=$path?>views_amt/repairhole_manage/repairhole_main_form.php","add","1=1","600","410","เพิ่มช่องซ่อมใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/repairhole_manage/repairhole_main_form.php","edit","1=1","1300","410","แก้ไขช่องซ่อม");
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
						var url = "<?=$path?>views_amt/repairhole_manage/repairhole_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_repairhole('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/repairhole_manage/repairhole_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_repairhole_main(refcode,no) {
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
					var url = "<?=$path?>views_amt/repairhole_manage/repairhole_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_repairhole('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/repairhole_manage/repairhole_main.php');
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
        <td width="25" valign="middle" class=""><img src="../images/Process-Info-icon48.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลช่องซ่อม</h3></td>
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
				<th width="10%">ชื่อช่องซ่อม</th>
				<th width="5%">โซน</th>
				<th width="20%">ลักษณะช่องซ่อม</th>
				<th width="15%">ชม.มาตรฐานช่องซ่อม</th>
				<th width="10%">สถานะใช้งาน</th>
				<th width="10%">จัดการ</th>
			</tr>
		</thead>
        <tbody>
			<?php
				$sql_repairhole = "SELECT * FROM REPAIR_HOLE WHERE NOT RPH_STATUS='D' AND RPH_AREA = '$SESSION_AREA'";
				$query_repairhole = sqlsrv_query($conn, $sql_repairhole);
				$no=0;
				while($result_repairhole = sqlsrv_fetch_array($query_repairhole, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$RPH_ID=$result_repairhole['RPH_ID'];
					$RPH_REPAIRHOLE=$result_repairhole['RPH_REPAIRHOLE'];
					$RPH_ZONE=$result_repairhole['RPH_ZONE'];
					$RPH_AREA=$result_repairhole['RPH_AREA'];
					$RPH_NATUREREPAIR=$result_repairhole['RPH_NATUREREPAIR'];
					$RPH_HOURSSTANDARD=$result_repairhole['RPH_HOURSSTANDARD'];
					$RPH_STATUS=$result_repairhole['RPH_STATUS'];
					$RPH_CODE=$result_repairhole['RPH_CODE'];
			?>
			<tr id="<?php print $RPH_CODE; ?>"  height="25px" align="center">
				<td ><?php print "$no.";?></td>
				<td align="center" >&nbsp;<?php print $RPH_REPAIRHOLE; ?></td>
				<td align="center" >&nbsp;<?php print $RPH_ZONE; ?></td>
				<td align="left" >&nbsp;<?php print $RPH_NATUREREPAIR; ?></td>
				<td align="center" >&nbsp;<?php print $RPH_HOURSSTANDARD; ?></td>
				<td align="center" >&nbsp;<?php if($RPH_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >
					<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/repairhole_manage/repairhole_main_form.php','edit','<?php print $RPH_CODE; ?>','1=1','1300','390','แก้ไขช่องซ่อม');"><i class='icon-pencil icon-large'></i></button>
					&nbsp;&nbsp;
					<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_repairhole_main('<?php print $RPH_CODE; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/repairhole_manage/repairhole_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>