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
			ajaxPopup2("<?=$path?>views_amt/customer_manage/customer_main_form.php","add","1=1","800","430","เพิ่มลูกค้าใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/customer_manage/customer_main_form.php","edit","1=1","1300","430","แก้ไขข้อมูลลูกค้า");
		});

		$("#button_detail").click(function(){
			ajaxPopup2("<?=$path?>views_amt/customer_manage/customer_main_detail.php","detail","1=1","800","430","รายละเอียด");
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
						var url = "<?=$path?>views_amt/customer_manage/customer_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_customer('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/customer_manage/customer_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_customer_main(refcode,no) {
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
					var url = "<?=$path?>views_amt/customer_manage/customer_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_customer('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/customer_manage/customer_main.php');
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
        <td width="5%" valign="middle" class=""><img src="../images/47.png" width="48" height="48"></td>
        <td width="90%" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลลูกค้า - บริษัท</h3></td>
        <td width="5%" align="right" valign="bottom" class="" nowrap>
            <div class="toolbar">
                <!-- <button class="bg-color-greenLight" style="padding-top:8px;" title="Detail" id="button_detail"><i class='icon-search icon-large'></i></button> -->
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
				<th width="10%">รหัสบริษัท</th>
				<th width="20%">ชื่อไทย</th>
				<th width="10%">จำนวนรถ</th>
				<th width="10%">เบอร์โทรศัพท์</th>	
				<th width="10%">ประเภทลูกค้า</th>			
				<th width="10%">สถานะใช้งาน</th>		
				<th width="10%">จัดการ</th>			
			</tr>
		</thead>
        <tbody>
			<?php
				$sql_customer = "SELECT * FROM CUSTOMER WHERE NOT CTM_STATUS='D'";
				$query_customer = sqlsrv_query($conn, $sql_customer);
				$no=0;
				while($result_customer = sqlsrv_fetch_array($query_customer, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$CTM_ID=$result_customer['CTM_ID'];
					$CTM_CODE=$result_customer['CTM_CODE'];
					$CTM_COMCODE=$result_customer['CTM_COMCODE'];
					$CTM_NAMETH=$result_customer['CTM_NAMETH'];
					$CTM_NAMEEN=$result_customer['CTM_NAMEEN'];
					$CTM_TAXNUMBER=$result_customer['CTM_TAXNUMBER'];
					$CTM_PHONE=$result_customer['CTM_PHONE'];
					$CTM_FAX=$result_customer['CTM_FAX'];
					$CTM_ADDRESS=$result_customer['CTM_ADDRESS'];
					$CTM_CAPITAL=$result_customer['CTM_CAPITAL'];
					$CTM_REGISTERED=$result_customer['CTM_REGISTERED'];
					$CTM_STATUS=$result_customer['CTM_STATUS'];	
					$CTM_GROUP=$result_customer["CTM_GROUP"];

					if($CTM_GROUP=="cusout"){
						$sql_count = "SELECT COUNT(VEHICLEINFOID) COUNTCTMID FROM CUSTOMER_CAR WHERE AFFCOMPANY = '$CTM_COMCODE' AND ACTIVESTATUS = '1'";
					}else{
						$sql_count = "SELECT COUNT(VEHICLEINFOID) COUNTCTMID FROM vwVEHICLEINFO WHERE AFFCOMPANY LIKE '%$CTM_COMCODE%' AND NOT VEHICLEGROUPDESC = 'Car Pool' AND ACTIVESTATUS = '1'";
					}				
					$query_count = sqlsrv_query( $conn, $sql_count);	
					$result_count = sqlsrv_fetch_array($query_count, SQLSRV_FETCH_ASSOC);
					$COUNTCTMID=$result_count["COUNTCTMID"];
			?>
			<tr id="<?php print $CTM_CODE; ?>"  height="25px" align="center" onDblClick="javascript:ajaxPopup2('<?=$path?>views_amt/customer_manage/customer_main_detail.php','','id=<?php print $CTM_CODE; ?>','800','430','รายละเอียด')">
				<td ><?php print "$no.";?></td>
				<td align="center" >&nbsp;<?php print $CTM_COMCODE; ?></td>
				<td align="left" >&nbsp;<?php print $CTM_NAMETH; ?></td>
				<td align="center" >&nbsp;<a href="javascript:void(0);" onClick="javascript:loadViewdetail('<?=$path?>views_amt/customer_manage/customer_car.php?ctm_comcode=<?php print $CTM_COMCODE; ?>',);"><b><?php print $COUNTCTMID; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/add-icon16.png" width="16" height="16"></a></td>
				<td align="left" >&nbsp;<?php print $CTM_PHONE; ?></td>
				<td align="left" >&nbsp;<?php if($result_customer['CTM_GROUP']== "cusin"){ echo "ลูกค้าภายใน"; }else if($result_customer['CTM_GROUP']== "cusout"){ echo "ลูกค้าภายนอก"; } ?></td>
				<td align="center" >&nbsp;<?php if($CTM_STATUS=="Y"){print "<img src='../images/check_true.gif' width='16' height='16'>";}else{print "<img src='../images/check_del.gif' width='16' height='16'>";}?></td>
				<td align="center" >
					<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/customer_manage/customer_main_form.php','edit','<?php print $CTM_CODE; ?>','1=1','1350','430','แก้ไขข้อมูลลูกค้า');"><i class='icon-pencil icon-large'></i></button>
					&nbsp;&nbsp;
					<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_customer_main('<?php print $CTM_CODE; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button>
				</td>
			</tr>
            <?php }; ?>
          </tbody>
      </table>
	  <b><font color="red" align="left" >*สามารถกด Double Click ข้อมูลในตาราง เพื่อดูรายละเอียดได้ทันที</font></b>
    </form>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
		<center>
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/customer_manage/customer_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>