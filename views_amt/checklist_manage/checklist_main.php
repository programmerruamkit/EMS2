<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	$SESSION_AREA=$_SESSION["AD_AREA"];
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
?>
<html>
<head>
<script type="text/javascript">
    function save_chklist(code,val,pmors,pmtype,cartype) {     
		// alert(code)  
		// alert(val)  
		// alert(pmors)  
		// alert(pmtype) 
		// alert(cartype) 
		var url = "<?=$path?>views_amt/checklist_manage/checklist_main_proc.php";
		$.ajax({
			type: 'post',
			url: url,
			data: {
				target: "editnew", 
				code: code,
				val: val,
				pmors: pmors,
				pmtype: pmtype,
				cartype: cartype
			},
			success:function(data){
				// alert(data)
				var clrptype = $("#CLRP_TYPE").val();
				var clrpcartype = $("#CLRP_CARTYPE").val();		
				var getselchecklist = "?clrptype="+clrptype+"&clrpcartype="+clrpcartype;
				loadViewdetail('<?=$path?>views_amt/checklist_manage/checklist_main.php'+getselchecklist);				
			}
		});          
    }
	function querychecklist(){
		var clrptype = $("#CLRP_TYPE").val();
		var clrpcartype = $("#CLRP_CARTYPE").val();
		
        if($('#CLRP_TYPE').val() == '' ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาเลือก RANK',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#CLRP_TYPE").focus(), 0);
              }
          })
          return false;
        }			
        if($('#CLRP_CARTYPE').val() == '' ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาเลือกสายงาน/รุ่นรถ',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#CLRP_CARTYPE").focus(), 0);
              }
          })
          return false;
        }					

		var getselchecklist = "?clrptype="+clrptype+"&clrpcartype="+clrpcartype;
		loadViewdetail('<?=$path?>views_amt/checklist_manage/checklist_main.php'+getselchecklist);
	} 

	$(document).ready(function(e) {	  
		$("#button_new").click(function(){
			ajaxPopup2("<?=$path?>views_amt/checklist_manage/checklist_main_form.php","add","1=1","600","430","เพิ่มรายการตรวจสอบ");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/checklist_manage/checklist_main_form.php","edit","1=1","1300","400","แก้ไขรายการตรวจสอบ");
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
						var url = "<?=$path?>views_amt/checklist_manage/checklist_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_checklist('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/checklist_manage/checklist_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_checklist(refcode,no) {
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
					var url = "<?=$path?>views_amt/checklist_manage/checklist_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_checklist('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/checklist_manage/checklist_main.php');
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
    <td class="CENTER">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="25" valign="middle" class=""><img src="../images/TASKLIST.png" width="48" height="48"></td>
				<td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลรายการตรวจสอบ</h3></td>
				<td width="617" align="right" valign="bottom" class="" nowrap>
					<div class="toolbar">
						<!-- <button class="bg-color-blue" style="padding-top:8px;" title="New" id="button_new"><i class='icon-plus icon-large'></i></button> -->
						<!-- <button class="bg-color-yellow" style="padding-top:8px;" title="Edit" id="button_edit"><i class='icon-pencil icon-large'></i></button> -->
						<!-- <button class="bg-color-red" style="padding-top:8px;" title="Del" id="button_delete"><i class="icon-cancel icon-large"></i></button> -->
					</div>
				</td>
			</tr>
		</table>
	</td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center">
        <br>    
        <table>
            <tbody>
                <tr align="center">   
					<td width="10%" align="right"><strong>เงื่อนไขการค้นหา :</strong></td>    
                    <td width="20%" align="center">
                        <div class="input-control select">             
                            <?php
                                $stmt_selgroup = "SELECT DISTINCT A.CLRP_TYPE FROM CHECKLISTREPAIR A WHERE A.CLRP_AREA = '$SESSION_AREA'";
                                $query_selgroup = sqlsrv_query($conn, $stmt_selgroup);
                            ?>    
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="CLRP_TYPE" id="CLRP_TYPE" required>
                                <option value disabled selected>-------เลือกประเภท-------</option>
                                <?php 
									while($result_selgroup = sqlsrv_fetch_array($query_selgroup)): 
									$CLRP_TYPE = $result_selgroup["CLRP_TYPE"];
								?>
								<option value="<?=$CLRP_TYPE?>" <?php if($_GET['clrptype'] == $CLRP_TYPE){echo "selected";} ?>><?=$CLRP_TYPE?></option>
								<?php endwhile; ?>
                            </select>
                        </div>
                    </td>     
					<td width="5%" align="right">&nbsp;</td>                       
                    <td width="20%" align="center">
                        <div class="input-control select">                    
                            <?php
                                $stmt_selline = "SELECT DISTINCT A.CLRP_CARTYPE,B.VHCCT_LINEOFWORK,B.VHCCT_SERIES,B.VHCCT_NAMETYPE FROM CHECKLISTREPAIR A
								LEFT JOIN VEHICLECARTYPE B ON B.VHCCT_ID = A.CLRP_CARTYPE AND B.VHCCT_STATUS = 'Y' WHERE A.CLRP_AREA = '$SESSION_AREA'";
                                $query_selline = sqlsrv_query($conn, $stmt_selline);
                            ?>
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="CLRP_CARTYPE" id="CLRP_CARTYPE" required>
                                <option value disabled selected>-------เลือกกลุ่มรถ-------</option>
								<option value disabled>สายงาน - โมเดลรถ - ประเภทรถ</option>
                                <?php 
									while($result_selline = sqlsrv_fetch_array($query_selline)): 
									$carmerge = $result_selline["VHCCT_LINEOFWORK"].' - '.$result_selline["VHCCT_SERIES"].' - '.$result_selline["VHCCT_NAMETYPE"];
								?>
                                    <option value="<?=$result_selline['CLRP_CARTYPE']?>" <?php if($_GET['clrpcartype']==$result_selline['CLRP_CARTYPE']){echo "selected";} ?>><?=$carmerge?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </td>
                    <td width="20%" align="center">
                        <button class="bg-color-blue" onclick="querychecklist()"><font color="white"><i class="icon-search"></i> ค้นหา</font></button>
                    </td>
                    <td align="center"></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table> 
		<form id="form1" name="form1" method="post" action="#">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
				<thead>
					<tr height="30">
						<th rowspan="2" align="center" width="5%" class="ui-state-default">ลำดับ.</th>
						<th rowspan="2" align="center" width="20%" class="ui-state-default">รายการตรวจเช็ค</th>
						<th rowspan="2" align="center" width="10%" class="ui-state-default">ระยะเปลี่ยน / เช็ค</th>
						<th colspan="12" align="center" width="65%" class="ui-state-default">PMoRS</th>
					</tr>
					<tr height="30">
						<th align="center" class="ui-state-default">1</th>
						<th align="center" class="ui-state-default">2</th>
						<th align="center" class="ui-state-default">3</th>
						<th align="center" class="ui-state-default">4</th>
						<th align="center" class="ui-state-default">5</th>
						<th align="center" class="ui-state-default">6</th>
						<th align="center" class="ui-state-default">7</th>
						<th align="center" class="ui-state-default">8</th>
						<th align="center" class="ui-state-default">9</th>
						<th align="center" class="ui-state-default">10</th>
						<th align="center" class="ui-state-default">11</th>
						<th align="center" class="ui-state-default">12</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$clrptype = $_GET['clrptype'];
						$clrpcartype = $_GET['clrpcartype'];
						// $wh="";
						if($clrptype!="" && $clrpcartype!=""){     					
							$wh="AND CLRP_TYPE = '$clrptype' AND CLRP_CARTYPE = '$clrpcartype'";
						}else{
							$wh="null";
						}
						$AREA=$_SESSION["AD_AREA"];
						$sql_checklist = "SELECT * FROM CHECKLISTREPAIR A LEFT JOIN VEHICLECARTYPE B ON B.VHCCT_ID = A.CLRP_CARTYPE WHERE NOT CLRP_STATUS = 'D' ".$wh."";
						$query_checklist = sqlsrv_query($conn, $sql_checklist);
						$no=0;
						while($result_checklist = sqlsrv_fetch_array($query_checklist, SQLSRV_FETCH_ASSOC)){	
							$no++;
							$CLRP_ID=$result_checklist['CLRP_ID'];
							$CLRP_CODE=$result_checklist['CLRP_CODE'];
							$CLRP_NUM=$result_checklist['CLRP_NUM'];
							$CLRP_NAME=$result_checklist['CLRP_NAME'];
							$CLRP_CHECK=$result_checklist['CLRP_CHECK'];
							$CLRP_PM1=$result_checklist['CLRP_PM1'];
							$CLRP_PM2=$result_checklist['CLRP_PM2'];
							$CLRP_PM3=$result_checklist['CLRP_PM3'];
							$CLRP_PM4=$result_checklist['CLRP_PM4'];
							$CLRP_PM5=$result_checklist['CLRP_PM5'];
							$CLRP_PM6=$result_checklist['CLRP_PM6'];
							$CLRP_PM7=$result_checklist['CLRP_PM7'];
							$CLRP_PM8=$result_checklist['CLRP_PM8'];
							$CLRP_PM9=$result_checklist['CLRP_PM9'];
							$CLRP_PM10=$result_checklist['CLRP_PM10'];
							$CLRP_PM11=$result_checklist['CLRP_PM11'];
							$CLRP_PM12=$result_checklist['CLRP_PM12'];
							$CLRP_TYPE=$result_checklist['CLRP_TYPE'];
							$CLRP_RANK=$result_checklist['CLRP_RANK'];
							$CLRP_CARTYPE=$result_checklist['CLRP_CARTYPE'];
							$CLRP_REMARK=$result_checklist['CLRP_REMARK'];
							$CLRP_STATUS=$result_checklist['CLRP_STATUS'];
							// echo $CLRP_PM1;							
					?>
					<tr id="<?php print $CLRP_CODE; ?>" height="25px" align="center">
						<td ><?php print "$no.";?></td>
						<td align="left" >&nbsp;<?php print $CLRP_NAME; ?></td>
						<td align="center" >&nbsp;<?php print $CLRP_CHECK; ?></td>
						<td ><input type="text" name="CLRP_PM1" id="CLRP_PM1" autocomplete="off" value="<?php echo $CLRP_PM1;?>" style="text-align:center;width:100%;" onchange="save_chklist('<?=$CLRP_CODE;?>',this.value,'1','<?=$CLRP_TYPE;?>','<?=$CLRP_CARTYPE;?>')"></td>
						<td ><input type="text" name="CLRP_PM2" id="CLRP_PM2" autocomplete="off" value="<?php echo $CLRP_PM2;?>" style="text-align:center;width:100%;" onchange="save_chklist('<?=$CLRP_CODE;?>',this.value,'2','<?=$CLRP_TYPE;?>','<?=$CLRP_CARTYPE;?>')"></td>
						<td ><input type="text" name="CLRP_PM3" id="CLRP_PM3" autocomplete="off" value="<?php echo $CLRP_PM3;?>" style="text-align:center;width:100%;" onchange="save_chklist('<?=$CLRP_CODE;?>',this.value,'3','<?=$CLRP_TYPE;?>','<?=$CLRP_CARTYPE;?>')"></td>
						<td ><input type="text" name="CLRP_PM4" id="CLRP_PM4" autocomplete="off" value="<?php echo $CLRP_PM4;?>" style="text-align:center;width:100%;" onchange="save_chklist('<?=$CLRP_CODE;?>',this.value,'4','<?=$CLRP_TYPE;?>','<?=$CLRP_CARTYPE;?>')"></td>
						<td ><input type="text" name="CLRP_PM5" id="CLRP_PM5" autocomplete="off" value="<?php echo $CLRP_PM5;?>" style="text-align:center;width:100%;" onchange="save_chklist('<?=$CLRP_CODE;?>',this.value,'5','<?=$CLRP_TYPE;?>','<?=$CLRP_CARTYPE;?>')"></td>
						<td ><input type="text" name="CLRP_PM6" id="CLRP_PM6" autocomplete="off" value="<?php echo $CLRP_PM6;?>" style="text-align:center;width:100%;" onchange="save_chklist('<?=$CLRP_CODE;?>',this.value,'6','<?=$CLRP_TYPE;?>','<?=$CLRP_CARTYPE;?>')"></td>
						<td ><input type="text" name="CLRP_PM7" id="CLRP_PM7" autocomplete="off" value="<?php echo $CLRP_PM7;?>" style="text-align:center;width:100%;" onchange="save_chklist('<?=$CLRP_CODE;?>',this.value,'7','<?=$CLRP_TYPE;?>','<?=$CLRP_CARTYPE;?>')"></td>
						<td ><input type="text" name="CLRP_PM8" id="CLRP_PM8" autocomplete="off" value="<?php echo $CLRP_PM8;?>" style="text-align:center;width:100%;" onchange="save_chklist('<?=$CLRP_CODE;?>',this.value,'8','<?=$CLRP_TYPE;?>','<?=$CLRP_CARTYPE;?>')"></td>
						<td ><input type="text" name="CLRP_PM9" id="CLRP_PM9" autocomplete="off" value="<?php echo $CLRP_PM9;?>" style="text-align:center;width:100%;" onchange="save_chklist('<?=$CLRP_CODE;?>',this.value,'9','<?=$CLRP_TYPE;?>','<?=$CLRP_CARTYPE;?>')"></td>
						<td ><input type="text" name="CLRP_PM10" id="CLRP_PM10" autocomplete="off" value="<?php echo $CLRP_PM10;?>" style="text-align:center;width:100%;" onchange="save_chklist('<?=$CLRP_CODE;?>',this.value,'10','<?=$CLRP_TYPE;?>','<?=$CLRP_CARTYPE;?>')"></td>
						<td ><input type="text" name="CLRP_PM11" id="CLRP_PM11" autocomplete="off" value="<?php echo $CLRP_PM11;?>" style="text-align:center;width:100%;" onchange="save_chklist('<?=$CLRP_CODE;?>',this.value,'11','<?=$CLRP_TYPE;?>','<?=$CLRP_CARTYPE;?>')"></td>
						<td ><input type="text" name="CLRP_PM12" id="CLRP_PM12" autocomplete="off" value="<?php echo $CLRP_PM12;?>" style="text-align:center;width:100%;" onchange="save_chklist('<?=$CLRP_CODE;?>',this.value,'12','<?=$CLRP_TYPE;?>','<?=$CLRP_CARTYPE;?>')"></td>
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
			<!-- <input type="button" class="button_gray" value="อัพเดท" onclick="querychecklist()"> -->
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/checklist_manage/checklist_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>