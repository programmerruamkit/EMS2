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
			ajaxPopup2("<?=$path?>views_amt/roleaccount_manage/roleaccount_main_form.php","add","1=1","600","320","เพิ่มสิทธิ์ใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/roleaccount_manage/roleaccount_main_form.php","edit","1=1","1300","320","แก้ไขสิทธิ์");
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
						var url = "<?=$path?>views_amt/roleaccount_manage/roleaccount_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_roleaccount('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/roleaccount_manage/roleaccount_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });
</script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25" valign="middle" class=""><img src="../images/User-Group-icon48.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลสิทธิ์สมาชิก</h3></td>
        <td width="617" align="right" valign="bottom" class="" nowrap>
            <div class="toolbar">
                <!-- <button class="bg-color-blue" style="padding-top:8px;" title="New" id="button_new"><i class='icon-plus icon-large'></i></button> -->
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
				<th width="10%">รหัสพนักงาน</th>
				<th width="20%">ชื่อ-นามสกุล</th>
				<th width="25%">ตำแหน่ง</th>
				<th width="25%">สังกัด</th>
				<th width="15%">สิทธิ์ใช้งานเมนู</th>
			</tr>
		</thead> 
        <tbody>
			<?php
				if($SESSION_AREA=="AMT"){
					$findgroup="'RKL','RKR','RKS','RIT','RTD','RTC'";
				}else{
					$findgroup="'RCC','RATC','RRC','RIT','RTD','RTC'";
				}
				$sql_emp = "SELECT * FROM vwEMPLOYEE WHERE Company_Code IN($findgroup)";
				$query_emp = sqlsrv_query($conn, $sql_emp);
				$no=0;
				while($result_emp = sqlsrv_fetch_array($query_emp, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$PersonCode=$result_emp['PersonCode'];
					$nameT=$result_emp['nameT'];
					$PositionNameT=$result_emp['PositionNameT'];
					$THAINAME=$result_emp['THAINAME'];
					$Company_NameT=$result_emp['Company_NameT'];
					if($THAINAME==""){
						$rscompany="บริษัท ".$Company_NameT." จำกัด";
					}else{
						$rscompany=$THAINAME;
					}
					
					$sql_count = "SELECT COUNT(RA_PERSONCODE) COUNTMNID FROM ROLE_ACCOUNT LEFT JOIN ROLE_USER ON ROLE_USER.RU_ID = ROLE_ACCOUNT.RU_ID WHERE RA_PERSONCODE = ? AND RU_AREA = ?";
					$params_count = array($PersonCode,$SESSION_AREA);	
					$query_count = sqlsrv_query( $conn, $sql_count, $params_count);	
					$result_count = sqlsrv_fetch_array($query_count, SQLSRV_FETCH_ASSOC);
			?>
			<tr id="<?php print "ไม่มีไอดี"; ?>" height="25px" align="center">
				<td ><?php print "$no.";?></td>
				<td align="left" >&nbsp;<?php print $PersonCode; ?></td>
				<td align="left" >&nbsp;<?php print $nameT; ?></td>
				<td align="left" >&nbsp;<?php print $PositionNameT; ?></td>
				<td align="left" >&nbsp;<?php print $rscompany; ?></td>
				<td align="center" >&nbsp;<a href="javascript:void(0);" onClick="javascript:loadViewdetail('<?=$path?>views_amt/roleaccount_manage/roleaccount_sub.php?personcode=<?php print $PersonCode; ?>',);"><b><?=$result_count["COUNTMNID"];?></b>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/add-icon16.png" width="16" height="16"></a></td>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/roleaccount_manage/roleaccount_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>