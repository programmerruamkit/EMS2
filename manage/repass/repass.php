<?php
	session_name("EMS"); session_start();
	$path = "../";   
	include($path.'../include/connect.php'); 	
?>
<?php 
	$PERSONCODE=$_SESSION['AD_PERSONCODE'];
	$sql_profile = "SELECT A.PersonCode,A.nameT,A.RA_USERNAME,A.RA_PASSWORD,B.TaxID,
		B.nameE,B.CurrentTel,B.Email,B.PositionNameT,B.Company_Code,B.THAINAME
		FROM dbo.vwROLEACOUNT AS A
		LEFT JOIN dbo.vwEMPLOYEE AS B ON B.PersonCode = A.PersonCode WHERE A.PersonCode = ?";
	$params_profile = array($PERSONCODE);
	$query_profile = sqlsrv_query( $conn, $sql_profile, $params_profile);
	$rsp = sqlsrv_fetch_array($query_profile, SQLSRV_FETCH_ASSOC);
	$RA_PASSWORD=$rsp["RA_PASSWORD"];
?>
<html>
<head>
  <script type="text/javascript">
    function save_data() {
      if(!confirm("ยืนยันการเปลี่ยนรหัสผ่านหรือไม่ ?")) return false;
      var buttonname = $('#buttonname').val();
      var url = "<?=$path?>manage/repass/repass_proc.php";
      $.ajax({
        type: "POST",
        url: url,
        data: $("#form_project").serialize(),
        success: function (data) {
          closeUI();
          alert(data);
		  log_repass('LA23', '-', '<?=$RA_PASSWORD;?>');
		  log_outsession('<?=$_SESSION['AD_PERSONCODE'];?>','LA2')
        }
      });
    }
  </script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>&nbsp;</tr>
		</table>
	</td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center">
		<h2><u>ข้อมูลส่วนตัว</u></h2>	
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
			<tbody>	
				<tr>
					<td>
						<table width="100%" class="table table-bordered" id="datatable">
							<tr height="50">
								<td width="10%" colspan="1" bgcolor="#f9f9f9">&nbsp;</td>
								<td colspan="5" align="left" bgcolor="#f9f9f9"><h3>รหัสประจำตัว : <?=$rsp["PersonCode"];?></h3></td>
								<td colspan="2" align="left" bgcolor="#f9f9f9"><h3>ชื่อ-สกุล : <?=$rsp["nameT"];?></h3></td>
							</tr>
							<tr height="50">
								<td colspan="1" bgcolor="#f9f9f9">&nbsp;</td>
								<td colspan="5" align="left" bgcolor="#f9f9f9"><h3>เบอร์โทรศัพท์ : <?=$rsp["CurrentTel"];?></h3></td>
								<td colspan="2" align="left" bgcolor="#f9f9f9"><h3>อีเมล : <?=$rsp["Email"];?></h3></td>
							</tr>
							<tr height="50">
								<td colspan="1" bgcolor="#f9f9f9">&nbsp;</td>
								<td colspan="5" align="left" bgcolor="#f9f9f9"><h3>ตำแหน่ง : <?=$rsp["PositionNameT"];?></h3></td>
								<td colspan="2" align="left" bgcolor="#f9f9f9"><h3>รหัสบริษัท : <?=$rsp["Company_Code"];?></h3></td>
							</tr>
							<tr height="50">
								<td colspan="1" bgcolor="#f9f9f9">&nbsp;</td>
								<td colspan="7" align="left" bgcolor="#f9f9f9"><h3>ชื่อบริษัท : <?=$rsp["THAINAME"];?></h3></td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<h2><u>เปลี่ยนรหัสผ่าน</u></h2>	
		<form action="#" method="post" enctype="multipart/form-data" name="form_project" id="form_project">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
				<tbody>	
					<tr>
						<td>
							<table width="100%" class="table table-bordered" id="datatable">
								<tr height="50">
									<td width="10%" colspan="1" bgcolor="#f9f9f9">&nbsp;</td>
									<td colspan="5" align="left" bgcolor="#f9f9f9"><h3>ชื่อผู้ใช้ : <?=$rsp["RA_USERNAME"];?></h3></td>
									<td colspan="2" align="left" bgcolor="#f9f9f9"><h3>รหัสผ่านเดิม : <?=$rsp["RA_PASSWORD"];?></h3></td>
								</tr>
								<tr height="50">
									<td colspan="1" bgcolor="#f9f9f9">&nbsp;</td>
									<td colspan="5" align="left" bgcolor="#f9f9f9">&nbsp;</td>
									<td colspan="2" align="left" bgcolor="#f9f9f9"><h3>รหัสผ่านใหม่ : <input type="text" size="20" class="wideinput time" name="newpass" id="newpass" value="<?=$RA_PASSWORD;?>"></h3></td>
								</tr>
							</table>
							<br>	
							<input type="hidden" name="PersonCode" id="PersonCode" value="<?=$rsp["PersonCode"];?>">
							<center>
								<input class="button_green3" type="button" name="buttonname" id="buttonname" value="บันทึกข้อมูล" onClick="save_data()">
							</center>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
<style>
.wideinput {
	font-size: 20px;
}
</style>
</body>
</html>