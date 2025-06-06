<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
?>
<html>
<head>
<script type="text/javascript">

	$('#checkAll').click(function () {
		if ($(this).attr('checked')) {
			$('.largerCheckbox').attr('checked', true);
		} else {
			$('.largerCheckbox').attr('checked', false);
		}
	});

	$('.b_save').click(function () {
		type_ch = true;
		if (type_ch == false) return type_ch;
		var chk_list = $('#chk_list').val();
		var countCheck = 0;
		var typeChkCon = true;
		if (chk_list == 'none_select') {
			typeChkCon = false;
		};

		$('.largerCheckbox').each(function () {
			if ($(this).attr('checked')) {
				typeChkCon = true;
				return false;
			} else {
				typeChkCon = false;
			}
		});

		if (typeChkCon == false) {
			Swal.fire({
				icon: 'warning',
				title: 'กรุณาเลือกอย่างน้อย 1 รายการ',
				showConfirmButton: false,
				timer: 1500
			})
			// alert('กรุณาเลือกอย่างน้อย 1 รายการ');
			return false;
		}

		if (type_ch == true) {
			if ($('#type_chk').val() == 4) {
				Swal.fire({
					title: 'ยืนยันการ Approve ข้อมูลหรือไม่ ?',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#00A300',
					confirmButtonText: 'ยืนยัน',
					cancelButtonText: 'ยกเลิก'
				}).then((result) => {
					if (result.isConfirmed) {
						var url = "<?=$path?>views_amt/approve_manage/approve_manage_proc.php";
						$.ajax({
							type: "POST",
							url: url,
							data: $("#form_approve").serialize(),
							success: function (data) {
							Swal.fire({
								position: 'center',
								icon: 'success',
								title: data,
								showConfirmButton: false,
								timer: 2000
							}).then((result) => {
								loadViewdetail('<?=$path?>views_amt/approve_manage/approve_manage.php');
								closeUI();
								// alert(data);
							})
							}
						});
					}
				})
			} else {
				
				if($('#RPRQ_REMARK').val() == 0 ){
					Swal.fire({
						icon: 'warning',
						title: 'กรุณาระบุสาเหตุที่ไม่อนุมัติ',
						showConfirmButton: false,
						timer: 1500,
						onAfterClose: () => {
							setTimeout(() => $("#RPRQ_REMARK").focus(), 0);
						}
					})
					return false;
				}
				Swal.fire({
					title: 'ยืนยันการ Reject ข้อมูลหรือไม่ ?',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#C82333',
					confirmButtonText: 'ยืนยัน',
					cancelButtonText: 'ยกเลิก'
				}).then((result) => {
					if (result.isConfirmed) {
						var url = "<?=$path?>views_amt/approve_manage/approve_manage_proc.php";
						$.ajax({
							type: "POST",
							url: url,
							data: $("#form_approve").serialize(),
							success: function (data) {
							Swal.fire({
								position: 'center',
								icon: 'success',
								title: data,
								showConfirmButton: false,
								timer: 2000
							}).then((result) => {
								loadViewdetail('<?=$path?>views_amt/approve_manage/approve_manage.php');
								closeUI();
								// alert(data);
							})
							}
						});
					}
				})
			}
		}
	});
			
</script>
<style>
	.largerCheckbox1 {
		width: 20px;
		height: 20px;
	}
	.largerCheckbox {
		width: 20px;
		height: 20px;
	}
</style>
</head>
<body>
<table width="100%" class="table table-striped datatable" id="datatable"></table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="25" valign="middle" class=""><img src="../images/Preview-icon48.png" width="48" height="48"></td>
				<td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;ตรวจสอบใบขอซ่อมรถ</h3></td>
				<td width="617" align="right" valign="bottom" class="" nowrap></td>
			</tr>
		</table>
	</td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center">
		<form name="form_approve" id="form_approve">
        <!-- <form name="form_approve" id="form_approve" method="post" action="<?=$path?>views_amt/approve_manage/approve_manage_proc.php" enctype="multipart/form-data">  -->
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
				<thead>
					<tr align="center">
						<th width="4%" valign="bottom"><strong>ลำดับ</strong></th>
						<th width="3%" nowrap>
							<input type="checkbox" name="checkAll" id="checkAll" value="checkbox" class="largerCheckbox1"> 
							ALL
							<input name="type_chk" type="hidden" id="type_chk" value="4">
						</th>
						<th width="93%" valign="bottom"><strong>รายละเอียด</strong></th>
					</tr>
				</thead>
				<tbody>			
					<?php
						$sql_check = "SELECT DISTINCT RPRQ_ID, A.RPRQ_CODE, RPRQ_WORKTYPE, RPRQ_AREA
						FROM REPAIRREQUEST A LEFT JOIN REPAIRCAUSE B ON B.RPRQ_CODE = A.RPRQ_CODE
						WHERE NOT RPRQ_STATUS = 'D' AND RPRQ_STATUSREQUEST = 'รอตรวจสอบ'";
						$query_check = sqlsrv_query($conn, $sql_check);
						$no=0;
						while($result_check = sqlsrv_fetch_array($query_check, SQLSRV_FETCH_ASSOC)){	
							$no++;
							$RPRQ_WORKTYPE = $result_check['RPRQ_WORKTYPE'];
							
							if($RPRQ_WORKTYPE == 'BM'){
								$sql_rprq = "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_STATUSREQUEST = 'รอตรวจสอบ'";
							}else{
								$sql_rprq = "SELECT * FROM vwREPAIRREQUEST_PM WHERE RPRQ_STATUSREQUEST = 'รอตรวจสอบ'";
							}
						$query_rprq = sqlsrv_query($conn, $sql_rprq);
						$result_rprq = sqlsrv_fetch_array($query_rprq, SQLSRV_FETCH_ASSOC); 
							
					?>
					<tr>
						<td valign="top" align="center"><?=$no;?></td>
						<td valign="top" align="center">
							<div>
								<label>
									<input type="checkbox" name="RPRQ_ID<?php echo $no;?>" id="RPRQ_ID" value="<?=$result_rprq['RPRQ_ID'];?>" style="cursor:pointer" class="largerCheckbox" >&nbsp;
								</label>
							</div>
						</td>
						<td>
							<table width="100%" class="table table-bordered" id="datatable">
								<tr>
									<td width="13%" align="right" bgcolor="#f9f9f9"><strong>เลขที่ใบแจ้งซ่อม</strong></td>
									<td width="15%" align="left"><?=$result_rprq['RPRQ_ID']; ?></td>
									<td width="12%" align="right" bgcolor="#f9f9f9"><strong>สถานะแจ้งซ่อม</strong></td>
									<td width="15%" align="left">                        
										<?php	switch($result_rprq['RPRQ_STATUSREQUEST']) {
												case "รอตรวจสอบ":
													$RPRQ_STATUSREQUEST="<strong><font color='red'>รอตรวจสอบ</font></strong>";
												break;
												case "รอคิวซ่อม":
													$RPRQ_STATUSREQUEST="<strong><font color='red'>รอคิวซ่อม</font></strong>";
												break;
												case "กำลังซ่อม":
													$RPRQ_STATUSREQUEST="<strong><font color='red'>กำลังซ่อม</font></strong>";
												break;
												case "ซ่อมเสร็จสิ้น":
													$RPRQ_STATUSREQUEST="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
												break;
												case "อนุมัติ":
													$RPRQ_STATUSREQUEST="<strong><font color='green'>อนุมัติ</font></strong>";
												break;
												case "ไม่อนุมัติ":
													$RPRQ_STATUSREQUEST="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
												break;
											}
											print $RPRQ_STATUSREQUEST;
										?>
									</td>
									<td width="12%" align="right" bgcolor="#f9f9f9"><strong>วันที่แจ้งซ่อม</strong></td>
									<td colspan="5"><?php print $result_rprq['RPRQ_CREATEDATE_REQUEST']; ?></td>
								</tr>
								<tr>
									<td align="right" bgcolor="#f9f9f9"><strong>วันที่นำรถเข้าซ่อม</strong></td>
									<td><?php print $result_rprq['RPRQ_REQUESTCARDATE']; ?> เวลา <?php print $result_rprq['RPRQ_REQUESTCARTIME']; ?> น.</td>
									<td align="right" bgcolor="#f9f9f9"><strong>วันที่ต้องการ</strong></td>
									<td colspan="5"><?php print $result_rprq['RPRQ_USECARDATE']; ?> เวลา <?php print $result_rprq['RPRQ_USECARTIME']; ?> น.</td>
								</tr>
								<tr>
									<td align="right" bgcolor="#f9f9f9"><strong>ทะเบียนรถ(หัว)</strong></td>
									<td><?php echo $result_rprq["RPRQ_REGISHEAD"];?></td>
									<td align="right" bgcolor="#f9f9f9"><strong>ชื่อรถ(หัว)</strong></td>
									<td><?php echo $result_rprq["RPRQ_CARNAMEHEAD"];?></td>
									<td align="right" bgcolor="#f9f9f9"><strong>ประเภทรถ</strong></td>
									<td width="15%" ><?php echo $result_rprq["RPRQ_CARTYPE"];?></td>
									<td width="10%" align="right" bgcolor="#f9f9f9"><strong>ลูกค้า/สายงาน</strong></td>
									<td><?php echo $result_rprq["RPRQ_LINEOFWORK"];?></td>
								</tr>
								<tr>
									<td align="right" bgcolor="#f9f9f9"><strong>ทะเบียนรถ(หาง)</strong></td>
									<td><?php echo $result_rprq["RPRQ_REGISTAIL"];?></td>
									<td align="right" bgcolor="#f9f9f9"><strong>ชื่อรถ(หาง)</strong></td>
									<td colspan="5"><?php echo $result_rprq["RPRQ_CARNAMETAIL"];?></td>
								</tr>
								<tr>
									<td align="right" bgcolor="#f9f9f9"><strong>สินค้าบนรถ </strong></td>
									<td>								             
										<?php	switch($result_rprq['RPRQ_PRODUCTINCAR']) {
												case "ist":
													$RPRQ_PRODUCTINCAR="มี";
												break;
												case "ost":
													$RPRQ_PRODUCTINCAR="ไม่มี";
												break;
											}
											print $RPRQ_PRODUCTINCAR;
										?>
									</td>
									<td align="right" bgcolor="#f9f9f9"><strong>ลักษณะการวิ่งงาน</strong></td>
									<td>								             
										<?php 	switch($result_rprq['RPRQ_NATUREREPAIR']) {
												case "bfw":
													$RPRQ_NATUREREPAIR="ก่อนปฏิบัติงาน";
												break;
												case "wwk":
													$RPRQ_NATUREREPAIR="ขณะปฏิบัติงาน";
												break;
												case "atw":
													$RPRQ_NATUREREPAIR="หลังปฏิบัติงาน";
												break;
											}
											print $RPRQ_NATUREREPAIR;
										?>
									</td>
									<td align="right" bgcolor="#f9f9f9"><strong>ประเภทลูกค้า</strong></td>
									<td colspan="5">								             
										<?php	switch($result_rprq['RPRQ_TYPECUSTOMER']) {
												case "cusout":
													$RPRQ_TYPECUSTOMER="ลูกค้านอก";
												break;
												case "cusin":
													$RPRQ_TYPECUSTOMER="ลูกค้าภายใน";
												break;
											}
											print $RPRQ_TYPECUSTOMER;
										?>
									</td>
								</tr>						
								<?php     
									$RPRQ_REQUESTBY=$result_rprq["RPRQ_REQUESTBY"];
									$stmt_emp_request = "SELECT * FROM vwEMPLOYEE WHERE nameT = ?";
									$params_emp_request = array($RPRQ_REQUESTBY);	
									$query_emp_request = sqlsrv_query( $conn, $stmt_emp_request, $params_emp_request);	
									$result_edit_emp_request = sqlsrv_fetch_array($query_emp_request, SQLSRV_FETCH_ASSOC);
								?>
								<tr>
									<td align="right" bgcolor="#f9f9f9"><strong>ผู้แจ้งซ่อม</strong></td>
									<td><?php echo $result_edit_emp_request["nameT"];?></td>
									<td align="right" bgcolor="#f9f9f9"><strong>ตำแหน่ง</strong></td>
									<td><?php echo $result_edit_emp_request["PositionNameT"];?></tdalign=>
									<td align="right" bgcolor="#f9f9f9"><strong>ฝ่าย</strong></td>
									<td colspan="5"><?php echo $result_edit_emp_request["Company_Code"];?></td>
								</tr>
								<tr>
									<td align="right" bgcolor="#f9f9f9"><strong>ลักษณะงานซ่อม/รายละเอียด</strong></td>
									<td colspan="7">														             
										<?php	switch($result_rprq['RPC_SUBJECT']) {
												case "EL":
													$RPC_SUBJECT="ระบบไฟ";
												break;
												case "TU":
													$RPC_SUBJECT="ยาง ช่วงล่าง";
												break;
												case "BD":
													$RPC_SUBJECT="โครงสร้าง";
												break;
												case "EG":
													$RPC_SUBJECT="เครื่องยนต์";
												break;
												case "AC":
													$RPC_SUBJECT="อุปกรณ์ประจำรถ";
												break;
											}
											print 'งาน'.$RPC_SUBJECT;
										?>
										<?php          
											$RPC_IMAGES=$result_rprq["RPC_IMAGES"];
											$explodes = explode('-', $RPC_IMAGES);
											$RPC_IMAGES2 = $explodes[1];
											if($RPC_IMAGES2!=""){
										?>
											<a href="#" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/approve_manage/approve_manage_image.php','edit','<?=$result_rprq["RPRQ_ID"]?>','1=1','800','700','รูปภาพจากใบแจ้งซ่อมหมายเลข <?=$result_rprq["RPRQ_ID"]?>');">
												<img src="../images/Preview-icon48.png" width="20px" height="20px"> คลิกดูรูปภาพ
											</a>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td align="right" valign="top" bgcolor="#f9f9f9"><strong>ระบุหมายเหตุ(กรณีไม่อนุมัติ)</strong></td>
									<td colspan="7">
										<div class="input-control text" style="width:100%; ">
											<textarea id="RPRQ_REMARK" name="RPRQ_REMARK<?php echo $no;?>" placeholder="ระบุหมายเหตุ(กรณีไม่อนุมัติ)" class="form-control" style="width:100%"></textarea>
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<?php }; ?>
					<?php if($no == 0){ ?>
						<tr style="height:28px;cursor:pointer">
							<td colspan="3" align="center" bgcolor="#FFDDE2"><font color="#990000">- ไม่มีข้อมูล -</font></td>
						</tr>
					<?php } ?>
					<input type="hidden" name="hdnLine" value="<?php echo $no;?>">
				</tbody>
			</table>
			<?php if($no >= 1){ ?>
				<div style="margin-top:10px; margin-right:15px; margin-bottom:10px;">
					<div align="left">
						<span id="show_save">
							<button type="button" class="bg-color-green font-white b_save" id="b_save" onclick="$('#type_chk').val('4')">Approve</button>
						</span>&nbsp;&nbsp;&nbsp;
						<span id="show_reject">
							<button type="button" class="bg-color-red font-white b_save" id="b_reject" onclick="$('#type_chk').val('5')">Reject</button>
						</span>
					</div>
				</div>
			<?php } ?>
		</form>			
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
		<center>
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/approve_manage/approve_manage.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>