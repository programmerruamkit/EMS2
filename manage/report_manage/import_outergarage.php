<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
?>
<html>
<head>
</head>
<style>
	input[type=file] {
		width: 250px;
		max-width: 100%;
		color: #444;
		padding: 5px;
		background: #fff;
		border-radius: 10px;
		border: 1px solid #555;
	}
</style>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="25" valign="middle" class=""><img src="https://img2.pic.in.th/pic/Folder-Downloads-icon48.png" width="48" height="48"></td>
			<td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;นำเข้าข้อมูลรถซ่อมอู่นอก</h3></td>
			<td width="617" align="right" valign="bottom" class="" nowrap></td>
        </tr>
    </table></td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center"><br>  
		<form id="form_search" name="form_search" onSubmit="return false;" method="post" enctype="multipart/form-data">			
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tbody>
					<tr height="30">
						<th width="10%" align="left">โหลดตัวอย่างไฟล์ CSV
							<button type="button" class="bg-color-yellow font-black" name="examplecsv" id="examplecsv" value="add" onClick="downloadcsv()"><i class="fa fa-download"></i> <b>CSV</b></font></button>
						</th>
						<th width="10%" align="left">เลือกไฟล์ CSV
							<input type="file" name="file" class="form-control-file" id="exampleFormControlFile1" accept=".csv">
						</th>
						<th width="1%" align="center"><br>
							<button type="button" class="bg-color-blue font-white" name="buttonname" id="buttonname" value="add" onClick="save_data_temp()"><i class="fa fa-search"></i> ตรวจสอบ</font></button>
						</th>
						<th width="15%" align="center"><br>
							<b><label id="lbl_cnt">ข้อมูลจากไฟล์ Excel : 0 รายการ</label></b>
						</th>
						<th width="1%" align="center"><br>
							<button type="button" class="bg-color-green font-white" name="buttonname" id="buttonname" value="edit" onClick="save_data_db()"><i class="fa fa-save"></i> บันทึก</font></button>
						</th>
						<th width="25%" align="center"><br>
							<b><label id="lbl_cntsus">ข้อมูลที่บันทึกลงฐานข้อมูล : 0 รายการ</label></b>
						</th>
					</tr>
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
			<input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>manage/report_manage/import_outergarage.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
	<script type="text/javascript">
		function downloadcsv() {
			// ดาวน์โหลดไฟล์ CSV ที่มีอยู่แล้ว
			window.open('<?=$path?>manage/report_manage/import_outergarage.csv', '_blank');
			
			// แสดงข้อความแจ้งเตือน
			Swal.fire({
				position: 'center',
				icon: 'success',
				title: 'กำลังดาวน์โหลดไฟล์ตัวอย่าง',
				text: 'กรุณาใช้ไฟล์นี้เป็นแบบอย่างในการนำเข้าข้อมูล',
				showConfirmButton: false,
				timer: 2000
			});
		}
		function save_data_temp() {
			// $(document).ready(function(e) {
				// $("#form_search").submit(function(e){
					var form = $('#form_search')[0];
					// e.preventDefault();
					Swal.fire({
						title: 'กำลังตรวจสอบไฟล์...',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
					$.ajax({
						url:'<?=$path?>manage/report_manage/import_outergarage_upload2temp.php',
						type:'POST',
						data:new FormData(form),
						contentType: false,       
						cache: false,             
						processData:false,
						dataType:'json',
						// beforeSend:function(){
						// 	$.blockUI();
						// },
						success:function(data){
							//console.log(data);			
							// alert(data);
							if(data>1){
								$('#form_search')[0].reset();    			
								document.getElementById("lbl_cnt").innerHTML = 'ข้อมูลจากไฟล์ Excel : '+data+' รายการ';
								Swal.fire({
									icon: 'success',
									title: 'ตรวจสอบไฟล์เรียบร้อย',
									showConfirmButton: false,
									timer: 1500
								});
							}else{							
								$('#form_search')[0].reset();    			
								document.getElementById("lbl_cnt").innerHTML = 'ข้อมูลจากไฟล์ Excel : 0 รายการ';
								Swal.fire({
									icon: 'warning',
									title: 'ไม่พบข้อมูลในไฟล์',
									showConfirmButton: false,
									timer: 1500
								});
							}
						},
						error:function(err){
							// alert('Error:'+err.responseText);
							Swal.close();
							Swal.fire({
								icon: 'error',
								title: 'เกิดข้อผิดพลาด',
								text: err.responseText || 'ไม่สามารถตรวจสอบไฟล์ได้'
							});
							closeUI();
							// console.log(err);
						}
					}).then(function(data){
						closeUI();
					});
				// });
			// });
		}
		function save_data_db() {
			var buttonname = $('#buttonname').val();
			var url = "<?=$path?>manage/report_manage/import_outergarage_upload2db.php";
			// แสดง loading
			Swal.fire({
				title: 'กำลังนำเข้าข้อมูล...',
				allowOutsideClick: false,
				didOpen: () => {
					Swal.showLoading();
				}
			});
			$.ajax({
				type: "POST",
				url: url,
				data: $("#form_search").serialize(),
				success: function (data) {
					Swal.close(); // ปิด loading
					// alert(data);
					document.getElementById("lbl_cntsus").innerHTML = 'ข้อมูลที่บันทึกลงฐานข้อมูล : '+data+' รายการ';
					Swal.fire({
						position: 'center',
						icon: 'success',
						title: 'บันทึกเรียบร้อย',
						showConfirmButton: false,
						timer: 2000
					}).then((result) => {
						// loadViewdetail('<?=$path?>manage/report_manage/import_outergarage_upload.php');
						closeUI();
					})
				},
				error: function(err) {
					Swal.close();
					Swal.fire({
						icon: 'error',
						title: 'เกิดข้อผิดพลาด',
						text: err.responseText || 'ไม่สามารถนำเข้าข้อมูลได้'
					});
				}
			});
		}
	</script>
</body>
</html>