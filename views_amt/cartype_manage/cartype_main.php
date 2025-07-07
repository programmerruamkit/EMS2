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
			ajaxPopup2("<?=$path?>views_amt/cartype_manage/cartype_main_form.php","add","1=1","700","440","เพิ่มกลุ่มรถ");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/cartype_manage/cartype_main_form.php","edit","1=1","1300","380","แก้ไขกลุ่มรถ");
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
						var url = "<?=$path?>views_amt/cartype_manage/cartype_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_cartype('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/cartype_manage/cartype_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_cartype(refcode,no) {
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
					var url = "<?=$path?>views_amt/cartype_manage/cartype_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_cartype('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/cartype_manage/cartype_main.php');
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
        <td width="25" valign="middle" class=""><img src="../images/car_icon.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลกลุ่มรถ</h3></td>
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
				<th width="15%">สายงาน</th>
				<th width="15%">รุ่นรถ</th>
				<th width="15%">ประเภทรถ</th>
				<th width="10%">กม./วัน</th>
				<th width="15%">กลุ่ม PM</th>
				<th width="10%">สถานะใช้งาน</th>
				<th width="20%">จัดการ</th>
			</tr>
		</thead>
        <tbody>
			<?php
				$sql_cartype = "SELECT * FROM VEHICLECARTYPE WHERE NOT VHCCT_STATUS = 'D' AND VHCCT_AREA = '$SESSION_AREA' ORDER BY VHCCT_ID ASC";
				$query_cartype = sqlsrv_query($conn, $sql_cartype);
				$no=0;
				while($result_cartype = sqlsrv_fetch_array($query_cartype, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$VHCCT_ID=$result_cartype['VHCCT_ID'];
					$VHCCT_CODE=$result_cartype['VHCCT_CODE'];
					$VHCCT_LINEOFWORK=$result_cartype['VHCCT_LINEOFWORK'];
					$VHCCT_SERIES=$result_cartype['VHCCT_SERIES'];
					$VHCCT_NAMETYPE=$result_cartype['VHCCT_NAMETYPE'];
					$VHCCT_STATUS=$result_cartype['VHCCT_STATUS'];
					$VHCCT_KILOFORDAY=$result_cartype["VHCCT_KILOFORDAY"];
					$VHCCT_PM=$result_cartype["VHCCT_PM"];
					
					$stmt_selline = "SELECT DISTINCT MLPM_LINEOFWORK,MLPM_REMARK FROM MILEAGESETPM WHERE MLPM_LINEOFWORK = '$VHCCT_PM' AND MLPM_AREA = '$SESSION_AREA'";
					$query_selline = sqlsrv_query($conn, $stmt_selline);
					$result_selline = sqlsrv_fetch_array($query_selline, SQLSRV_FETCH_ASSOC);
					$MLPM_REMARK=$result_selline['MLPM_REMARK'];
			?>
			<tr id="<?php print $VHCCT_CODE; ?>" height="25px" align="center">
				<td ><?php print "$no.";?></td>
				<td align="left" >&nbsp;<?php print $VHCCT_LINEOFWORK; ?></td>
				<td align="left" >&nbsp;<?php print $VHCCT_SERIES; ?></td>
				<td align="left" >&nbsp;<?php print $VHCCT_NAMETYPE; ?></td>
				<td align="center" >&nbsp;<?php print $VHCCT_KILOFORDAY; ?></td>
				<td align="center" >&nbsp;<?php print $MLPM_REMARK; ?></td>
				<td align="center" >&nbsp;<?php if($VHCCT_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >
					<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/cartype_manage/cartype_main_form.php','edit','<?php print $VHCCT_CODE; ?>','1=1','1300','440','แก้ไขกลุ่มรถ');"><i class='icon-pencil icon-large'></i></button>
					&nbsp;&nbsp;
					<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_cartype('<?php print $VHCCT_CODE; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/cartype_manage/cartype_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>