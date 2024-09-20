<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');	
	$GET_CTM_COMCODE=$_GET['ctm_comcode'];
	$GET_FROM=$_GET['from'];
	$MN_AREA = $_GET["area"];
	
	$SESSION_AREA=$_SESSION["AD_AREA"];
	
	$stmt = "SELECT * FROM CUSTOMER WHERE CTM_COMCODE = ? AND NOT CTM_STATUS='D'";
	$params = array($GET_CTM_COMCODE);	
	$query = sqlsrv_query( $conn, $stmt, $params);	
	$result_edit_customer = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
	$CTM_GROUP=$result_edit_customer["CTM_GROUP"];
	$CTM_NAMETH=$result_edit_customer["CTM_NAMETH"];

?>
<html>
<head>
<script type="text/javascript">
	$(document).ready(function(e) {	   
		$("#button_new").click(function(){
			ajaxPopup2("<?=$path?>views_amt/customer_manage/customer_car_form.php","add","ctm_comcode=<?php print $GET_CTM_COMCODE; ?><?php if($GET_FROM=='pm'){ echo '&from=pm'; }?>","1300","370","เพิ่มรถลูกค้าใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/customer_manage/customer_car_form.php","edit","ctm_comcode=<?php print $GET_CTM_COMCODE; ?><?php if($GET_FROM=='pm'){ echo '&from=pm'; }?>","1300","370","แก้ไขรถลูกค้า");
		});

		$("#button_detail").click(function(){
			ajaxPopup2("<?=$path?>views_amt/customer_manage/customer_car_detail.php","detail","1=1","1300","480","รายละเอียด");
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
						var url = "<?=$path?>views_amt/customer_manage/customer_car_proc.php?proc=delete&id="+ref;
						$.ajax({type:'GET',
							url:url,
							data:"",
							success:function(data){
								log_transac_customer_car('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/customer_manage/customer_car.php?ctm_comcode=<?php print $GET_CTM_COMCODE; ?>');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });
	
	function save_cargroup(vhcrgnb,vhcctid){
		var url = "<?=$path?>views_amt/customer_manage/customer_car_proc.php";
		$.ajax({
			type: 'POST',
			url: url,			
			data: {
				target: "save_cargroup",
				vhcrgnb: vhcrgnb,
				vhcctid: vhcctid
			},
			success: function (rs) {
				// loadViewdetail('<?=$path?>views_amt/customer_manage/customer_car.php?ctm_comcode=<?=$GET_CTM_COMCODE;?><?php if($GET_FROM=='pm'){ echo '&from=pm'; }?>');
				closeUI();
			}
		});
	}
	
	function save_kiloforday(vhcrgnb,kilo){
		var url = "<?=$path?>views_amt/customer_manage/customer_car_proc.php";
		$.ajax({
			type: 'POST',
			url: url,			
			data: {
				target: "save_kiloforday",
				vhcrgnb: vhcrgnb,
				kilo: kilo
			},
			success: function (rs) {
				loadViewdetail('<?=$path?>views_amt/customer_manage/customer_car.php?ctm_comcode=<?=$GET_CTM_COMCODE;?>');
				closeUI();
			}
		});
	}

	function swaldelete_customer_car(refcode,no) {
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
					var url = "<?=$path?>views_amt/customer_manage/customer_car_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							// log_transac_customer('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/customer_manage/customer_car.php?ctm_comcode=<?=$GET_CTM_COMCODE;?>');
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
        <td width="5%" valign="middle" class=""><img src="../images/car_icon.png" width="48" height="48"></td>
        <td width="90%" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลลูกค้า - รถ (<?=$CTM_NAMETH?>)</h3></td>
        <td width="5%" align="right" valign="bottom" class="" nowrap>
            <div class="toolbar">
                <!-- <button class="bg-color-greenLight" style="padding-top:8px;" title="Detail" id="button_detail"><i class='icon-search icon-large'></i></button> -->
				<?php if($CTM_GROUP=="cusout"){ ?>
					<button class="bg-color-blue" style="padding-top:8px;" title="New" id="button_new"><i class='icon-plus icon-large'></i></button>
				<?php } ?>
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
	<form name="form_approve" id="form_approve">
      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
		<thead>
			<tr height="30">
				<th width="5%">ลำดับ.</th>
				<th width="10%">เลขทะเบียนรถ</th>
				<th width="8%">กลุ่มรถ</th>
				<th width="15%">ประเภทรถ</th>
				<?php if($CTM_GROUP=="cusin"){ ?>
					<th width="13%">ชื่อรถ</th>
				<?php } ?>
				<th width="15%">หมายเหตุ</th>
				<th width="14%">กลุ่มรถ</th>
				<!-- ย้ายไปจัดการที่กลุ่มรถแทน -->
					<!-- <th width="10%">กม./วัน</th>	 -->
				<th width="10%">สถานะใช้งาน</th>				
				<?php if($CTM_GROUP=="cusout"){ ?>
					<th width="10%">จัดการ</th>
				<?php } ?>
			</tr>
		</thead>
        <tbody>
			<?php
				if($CTM_GROUP=="cusout"){
					$sql_vehicleinfo = "SELECT * FROM CUSTOMER_CAR WHERE AFFCOMPANY = '$GET_CTM_COMCODE' AND ACTIVESTATUS = '1'";
				}else{
					$sql_vehicleinfo = "SELECT * FROM vwVEHICLEINFO WHERE AFFCOMPANY LIKE '%$GET_CTM_COMCODE%' AND NOT VEHICLEGROUPDESC = 'Car Pool' AND ACTIVESTATUS = '1'";
				}
				$query_vehicleinfo = sqlsrv_query($conn, $sql_vehicleinfo);
				$no=0;
				while($result_vehicleinfo = sqlsrv_fetch_array($query_vehicleinfo, SQLSRV_FETCH_ASSOC)){	
					$no++;
					$CTMC_CODE=$result_vehicleinfo['CTMC_CODE'];
					$VHCRGNB=$result_vehicleinfo['VEHICLEREGISNUMBER'];
					
					$stmt_vhctcmg = "SELECT * FROM VEHICLECARTYPEMATCHGROUP WHERE VHCTMG_VEHICLEREGISNUMBER = ?";
					$params_vhctcmg = array($VHCRGNB);	
					$query_vhctcmg = sqlsrv_query( $conn, $stmt_vhctcmg, $params_vhctcmg);	
					$result_vhctcmg= sqlsrv_fetch_array($query_vhctcmg, SQLSRV_FETCH_ASSOC);
					$VHCCT_ID=$result_vhctcmg["VHCCT_ID"];
					$VHCTMG_KILOFORDAY=$result_vhctcmg["VHCTMG_KILOFORDAY"];
			?>
			<tr height="25px" align="center" id="<?php print $result_vehicleinfo['VEHICLEINFOID']; ?>" onDblClick="javascript:ajaxPopup2('<?=$path?>views_amt/customer_manage/customer_car_detail.php','','id=<?php print $result_vehicleinfo['VEHICLEINFOID']; ?>&AFFCOMPANY=<?php print $GET_CTM_COMCODE; ?>','1300','<?php if($CTM_GROUP=="cusout"){echo '370';}else{echo '480';}?>','รายละเอียด')">
				<td ><?php print "$no.";?></td>
				<td align="center"><?= $result_vehicleinfo['VEHICLEREGISNUMBER'] ?></td>
				<td align="left"><?= $result_vehicleinfo['VEHICLEGROUPDESC'] ?></td>
				<td align="left"><?= $result_vehicleinfo['VEHICLETYPEDESC'] ?></td>				
				<?php if($CTM_GROUP=="cusin"){ ?>
					<td align="left"><?= $result_vehicleinfo['THAINAME'] ?></td>
				<?php } ?>
				<td align="left"><?= $result_vehicleinfo['REMARK'] ?></td>
				<td align="left">					
					<?php
						// $stmt_selcartype = "SELECT * FROM VEHICLECARTYPE WHERE VHCCT_STATUS = 'Y' AND VHCCT_AREA = '$SESSION_AREA' ORDER BY VHCCT_ID ASC";
						$stmt_selcartype = "SELECT VHCCT_ID,VHCCT_LINEOFWORK,VHCCT_SERIES,VHCCT_NAMETYPE,VHCCT_AREA
						FROM VEHICLECARTYPE WHERE VHCCT_STATUS = 'Y' AND VHCCT_AREA = '$SESSION_AREA' -- ORDER BY VHCCT_ID ASC						
						UNION						
						SELECT DISTINCT A.VHCCT_ID,VHCCT_LINEOFWORK,VHCCT_SERIES,VHCCT_NAMETYPE,VHCCT_AREA
						FROM VEHICLECARTYPEMATCHGROUP A LEFT JOIN VEHICLECARTYPE B ON A.VHCCT_ID = B.VHCCT_ID
						WHERE VHCCT_STATUS = 'Y' AND A.VHCTMG_VEHICLEREGISNUMBER = '$VHCRGNB' ORDER BY VHCCT_ID ASC";
						$query_selcartype = sqlsrv_query($conn, $stmt_selcartype);
						$num=0;
					?>
					<select style="width: 100%;" name="VHCCT_ID" id="VHCCT_ID" onchange="save_cargroup('<?= $result_vehicleinfo['VEHICLEREGISNUMBER'] ?>',this.value)"> 
						<option value disabled selected>-------เลือกกลุ่มรถ-------</option>
						<option value="0">-------ว่าง-------</option>
						<option value disabled>สายงาน - โมเดลรถ - ประเภทรถ</option>
						<!-- sqlsrv_fetch_assoc -->
						<?php while($result_selcartype = sqlsrv_fetch_array($query_selcartype)): $num++; ?>
							<option value="<?=$result_selcartype['VHCCT_ID']?>" <?php if($result_selcartype['VHCCT_ID']==$VHCCT_ID){echo "selected";} ?>><?php print "$num.";?> <?=$result_selcartype['VHCCT_AREA']?> - <?=$result_selcartype['VHCCT_LINEOFWORK']?>
								<?php if($result_selcartype['VHCCT_SERIES']!=''){ ?> - <?=$result_selcartype['VHCCT_SERIES']?> - <?=$result_selcartype['VHCCT_NAMETYPE']?> <?php } ?></option>
						<?php endwhile; ?>
					</select>
				</td>
				<!-- ย้ายไปจัดการที่กลุ่มรถแทน -->
					<!-- <td align="left">
						<input type="text" name="VHCTMG_KILOFORDAY" id="VHCTMG_KILOFORDAY" value="<?=$VHCTMG_KILOFORDAY?>" onchange="save_kiloforday('<?= $result_vehicleinfo['VEHICLEREGISNUMBER'] ?>',this.value)">
					</td> -->
				<td align="center"><?php echo ($result_vehicleinfo['ACTIVESTATUS'] == "1") ? "<img src='../images/check_true.gif' width='16' height='16'>" : "<img src='../images/check_del.gif' width='16' height='16'>"; ?></td>
				<?php if($CTM_GROUP=="cusout"){ ?>
					<td align="center" >
						<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/customer_manage/customer_car_form.php','edit','<?php print $CTMC_CODE; ?><?php if($GET_FROM=='pm'){ echo '&from=pm'; }?>','1=1','1300','370','แก้ไขข้อมูลรถลูกค้า');"><i class='icon-pencil icon-large'></i></button>
						&nbsp;&nbsp;
						<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_customer_car('<?php print $CTMC_CODE; ?>','<?php print $no;?>')"><i class="icon-cancel icon-large"></i></button>
					</td>				
				<?php } ?>
            </tr>
            <?php }; ?>
          </tbody>
      </table>
	  <input type="hidden" name="CTM_GROUP" value="<?php echo $CTM_GROUP;?>">
	  <input type="hidden" name="hdnLine" value="1">
	  <input type="hidden" name="addcartype" value="addcartype">
	  <b><font color="red" align="left" >*สามารถกด Double Click ข้อมูลในตาราง เพื่อดูรายละเอียดได้ทันที</font></b>
    </form>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
		<center>
			<?php if($_GET["from"]=='pm'){ ?>
				<input type="button" class="button_red2" value="ย้อนกลับ" onClick="javascript:loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm_form.php?ctmcomcode=cusout');"> <!-- < ?=$GET_CTM_COMCODE;?> -->
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/customer_manage/customer_car.php?ctm_comcode=<?=$GET_CTM_COMCODE;?>&from=pm');">
			<?php }else{ ?>
				<input type="button" class="button_red2" value="ย้อนกลับ" onClick="javascript:loadViewdetail('<?=$path?>views_amt/customer_manage/customer_main.php');">
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/customer_manage/customer_car.php?ctm_comcode=<?=$GET_CTM_COMCODE;?>');">
			<?php } ?>
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>