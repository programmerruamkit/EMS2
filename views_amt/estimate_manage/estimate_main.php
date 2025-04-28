<?php
	session_name("EMS"); session_start();
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
    
    function save_etm(code,val,pmors,num,vhcid,group,pmtype) {     
		// alert(code)  
		// alert(val)  
		// alert(pmors)  
		// alert(num) 
		// alert(vhcid) 
		// alert(group) 
		// alert(pmtype) 
		var rsnumtotal = $("#RS_NUM_TOTAL").val();
		// alert(rsnumtotal) 
		var url = "<?=$path?>views_amt/estimate_manage/estimate_main_proc.php";
		$.ajax({
			type: 'post',
			url: url,
			data: {
				target: "edit", 
				code: code,
				val: val,
				pmors: pmors,
				num: num,
				vhcid: vhcid,
				group: group,
				pmtype: pmtype,
				rsnumtotal: rsnumtotal
			},
			success:function(data){
				// alert(data)
				var etmgroup = $("#ETM_GROUP").val();
				var vhcctline = $("#VHCCT_ID").val();			
				var getselchecklist = "?etmgroup="+etmgroup+"&vhcctline="+vhcctline;
				loadViewdetail('<?=$path?>views_amt/estimate_manage/estimate_main.php'+getselchecklist);
				
			}
		});          
    }
	function finechecklist(){
		var etmgroup = $("#ETM_GROUP").val();
		var vhcctline = $("#VHCCT_ID").val();			

        if($('#ETM_GROUP').val() == '' ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาเลือกประเภท',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#ETM_GROUP").focus(), 0);
              }
          })
          return false;
        }		

        if($('#VHCCT_ID').val() == '' ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาเลือกกลุ่มรถ',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#VHCCT_ID").focus(), 0);
              }
          })
          return false;
        }	

		if(etmgroup==''){
			// alert('1')
			var getselchecklist = "?vhcctline="+vhcctline;
			loadViewdetail('<?=$path?>views_amt/estimate_manage/estimate_main.php'+getselchecklist);
		}else{
			// alert('2')
			var getselchecklist = "?etmgroup="+etmgroup+"&vhcctline="+vhcctline;
			loadViewdetail('<?=$path?>views_amt/estimate_manage/estimate_main.php'+getselchecklist);
		}
	} 
	$(document).ready(function(e) {	   
		$("#button_new").click(function(){
			ajaxPopup2("<?=$path?>views_amt/estimate_manage/estimate_main_form.php","add","1=1","800","460","เพิ่ม RANK PM ใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/estimate_manage/estimate_main_form.php","edit","1=1","1000","340","แก้ไข RANK PM");
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
						var url = "<?=$path?>views_amt/estimate_manage/estimate_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_estimate('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/estimate_manage/estimate_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_estimate_main(refcode,no) {
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
					var url = "<?=$path?>views_amt/estimate_manage/estimate_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_estimate('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/estimate_manage/estimate_main.php');
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
        <td width="25" valign="middle" class=""><img src="../images/sales-report-icon48.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลประมาณการ</h3></td>
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
        <br>    
        <table>
            <tbody>
                <tr align="center">   
					<td width="10%" align="right"><strong>เงื่อนไขการค้นหา :</strong></td>    
                    <td width="20%" align="center">
                        <div class="input-control select">             
                            <?php
                                $stmt_selgroup = "SELECT DISTINCT A.ETM_GROUP FROM ESTIMATE A WHERE A.ETM_AREA = '$SESSION_AREA'";
                                $query_selgroup = sqlsrv_query($conn, $stmt_selgroup);
                            ?>    
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="ETM_GROUP" id="ETM_GROUP" required>
                                <option value disabled selected>-------เลือกประเภท-------</option>
                                <?php 
									while($result_selgroup = sqlsrv_fetch_array($query_selgroup)): 
									$ETM_GROUP = $result_selgroup["ETM_GROUP"];
								?>
								<option value="<?=$ETM_GROUP?>" <?php if($_GET['etmgroup'] == $ETM_GROUP){echo "selected";} ?>><?=$ETM_GROUP?></option>
								<?php endwhile; ?>
                            </select>
                        </div>
                    </td>     
					<td width="5%" align="right">&nbsp;</td>                       
                    <td width="20%" align="center">
                        <div class="input-control select">                    
                            <?php
                                $stmt_selline = "SELECT DISTINCT A.VHCCT_ID,B.VHCCT_LINEOFWORK,B.VHCCT_SERIES,B.VHCCT_NAMETYPE FROM ESTIMATE A
								LEFT JOIN VEHICLECARTYPE B ON B.VHCCT_ID = A.VHCCT_ID AND B.VHCCT_STATUS = 'Y' WHERE A.ETM_AREA = '$SESSION_AREA'";
                                $query_selline = sqlsrv_query($conn, $stmt_selline);
                            ?>
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="VHCCT_ID" id="VHCCT_ID" required>
                                <option value disabled selected>-------เลือกกลุ่มรถ-------</option>
								<option value disabled>สายงาน - โมเดลรถ - ประเภทรถ</option>
                                <?php 
									while($result_selline = sqlsrv_fetch_array($query_selline)): 
									$carmerge = $result_selline["VHCCT_LINEOFWORK"].' - '.$result_selline["VHCCT_SERIES"].' - '.$result_selline["VHCCT_NAMETYPE"];
								?>
                                    <option value="<?=$result_selline['VHCCT_ID']?>" <?php if($_GET['vhcctline']==$result_selline['VHCCT_ID']){echo "selected";} ?>><?=$carmerge?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </td>
                    <td width="20%" align="center">
                        <button class="bg-color-blue" onclick="finechecklist()"><font color="white"><i class="icon-search"></i> ค้นหา</font></button>
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
						<th rowspan="2" align="center" width="20%" class="ui-state-default">รายการ</th>
						<th colspan="12" align="center" width="65%" class="ui-state-default">PMoRS</th>
						<th rowspan="2" align="center" width="10%" class="ui-state-default">ประเภท</th>
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
						$etmgroup = $_GET['etmgroup'];
						$vhcctline = $_GET['vhcctline'];
						// $wh="";
						if($etmgroup!="" && $vhcctline!=""){     					
							$wh="ETM_GROUP = '$etmgroup' AND VHCCT_ID = '$vhcctline'";
						}else if($vhcctline!=""){     					
							$wh="VHCCT_ID = '$vhcctline'";
						}else{
							$wh="null";
						}
						
						$sql_estimate = "SELECT * FROM ESTIMATE WHERE ".$wh."";
						$query_estimate = sqlsrv_query($conn, $sql_estimate);
						$no=0;
						while($result_estimate = sqlsrv_fetch_array($query_estimate, SQLSRV_FETCH_ASSOC)){	
							$no++;
							$ETM_CODE=$result_estimate['ETM_CODE'];
							$ETM_NUM=$result_estimate['ETM_NUM'];
							$ETM_NAME=$result_estimate['ETM_NAME'];
							$ETM_PM1=$result_estimate['ETM_PM1'];
							$ETM_PM2=$result_estimate['ETM_PM2'];
							$ETM_PM3=$result_estimate['ETM_PM3'];
							$ETM_PM4=$result_estimate['ETM_PM4'];
							$ETM_PM5=$result_estimate['ETM_PM5'];
							$ETM_PM6=$result_estimate['ETM_PM6'];
							$ETM_PM7=$result_estimate['ETM_PM7'];
							$ETM_PM8=$result_estimate['ETM_PM8'];
							$ETM_PM9=$result_estimate['ETM_PM9'];
							$ETM_PM10=$result_estimate['ETM_PM10'];
							$ETM_PM11=$result_estimate['ETM_PM11'];
							$ETM_PM12=$result_estimate['ETM_PM12'];
							$VHCCT_ID=$result_estimate['VHCCT_ID'];
							$ETM_GROUP=$result_estimate['ETM_GROUP'];
							$ETM_TYPE=$result_estimate['ETM_TYPE'];
							if(($ETM_NUM=='5')||($ETM_NUM=='3')||($ETM_TYPE=='รวม')){
								$rol = 'readonly';
								$cls = 'background-color:#d1d1d1;';
							}else if(($ETM_NUM=='1')||($ETM_NUM=='4')){
								$rol = '';
								$cls = 'background-color:#FFE4E1;';
							}else if(($ETM_NUM=='6')){
								$rol = '';
								$cls = 'background-color:#FFFFCC;';
							}else if($ETM_NUM=='2'){
								$rol = '';
								$cls = 'background-color:#F0FFFF;';
							}
					?>
					<tr height="25px" align="left">
						<td style="text-align:center;" ><?php print "$no.$ETM_NUM";?></td>
						<td ><?php print "$ETM_NAME";?></td>
						<td ><input type="text" name="ETM_PM1" id="ETM_PM1" autocomplete="off" value="<?php echo $ETM_PM1;?>" style="text-align:center;width:100%;<?=$cls?>" onchange="save_etm('<?=$ETM_CODE;?>',this.value,'1','<?=$ETM_NUM;?>','<?=$VHCCT_ID;?>','<?=$ETM_GROUP;?>','<?=$ETM_TYPE;?>')" <?=$rol?>></td>
						<td ><input type="text" name="ETM_PM2" id="ETM_PM2" autocomplete="off" value="<?php echo $ETM_PM2;?>" style="text-align:center;width:100%;<?=$cls?>" onchange="save_etm('<?=$ETM_CODE;?>',this.value,'2','<?=$ETM_NUM;?>','<?=$VHCCT_ID;?>','<?=$ETM_GROUP;?>','<?=$ETM_TYPE;?>')" <?=$rol?>></td>
						<td ><input type="text" name="ETM_PM3" id="ETM_PM3" autocomplete="off" value="<?php echo $ETM_PM3;?>" style="text-align:center;width:100%;<?=$cls?>" onchange="save_etm('<?=$ETM_CODE;?>',this.value,'3','<?=$ETM_NUM;?>','<?=$VHCCT_ID;?>','<?=$ETM_GROUP;?>','<?=$ETM_TYPE;?>')" <?=$rol?>></td>
						<td ><input type="text" name="ETM_PM4" id="ETM_PM4" autocomplete="off" value="<?php echo $ETM_PM4;?>" style="text-align:center;width:100%;<?=$cls?>" onchange="save_etm('<?=$ETM_CODE;?>',this.value,'4','<?=$ETM_NUM;?>','<?=$VHCCT_ID;?>','<?=$ETM_GROUP;?>','<?=$ETM_TYPE;?>')" <?=$rol?>></td>
						<td ><input type="text" name="ETM_PM5" id="ETM_PM5" autocomplete="off" value="<?php echo $ETM_PM5;?>" style="text-align:center;width:100%;<?=$cls?>" onchange="save_etm('<?=$ETM_CODE;?>',this.value,'5','<?=$ETM_NUM;?>','<?=$VHCCT_ID;?>','<?=$ETM_GROUP;?>','<?=$ETM_TYPE;?>')" <?=$rol?>></td>
						<td ><input type="text" name="ETM_PM6" id="ETM_PM6" autocomplete="off" value="<?php echo $ETM_PM6;?>" style="text-align:center;width:100%;<?=$cls?>" onchange="save_etm('<?=$ETM_CODE;?>',this.value,'6','<?=$ETM_NUM;?>','<?=$VHCCT_ID;?>','<?=$ETM_GROUP;?>','<?=$ETM_TYPE;?>')" <?=$rol?>></td>
						<td ><input type="text" name="ETM_PM7" id="ETM_PM7" autocomplete="off" value="<?php echo $ETM_PM7;?>" style="text-align:center;width:100%;<?=$cls?>" onchange="save_etm('<?=$ETM_CODE;?>',this.value,'7','<?=$ETM_NUM;?>','<?=$VHCCT_ID;?>','<?=$ETM_GROUP;?>','<?=$ETM_TYPE;?>')" <?=$rol?>></td>
						<td ><input type="text" name="ETM_PM8" id="ETM_PM8" autocomplete="off" value="<?php echo $ETM_PM8;?>" style="text-align:center;width:100%;<?=$cls?>" onchange="save_etm('<?=$ETM_CODE;?>',this.value,'8','<?=$ETM_NUM;?>','<?=$VHCCT_ID;?>','<?=$ETM_GROUP;?>','<?=$ETM_TYPE;?>')" <?=$rol?>></td>
						<td ><input type="text" name="ETM_PM9" id="ETM_PM9" autocomplete="off" value="<?php echo $ETM_PM9;?>" style="text-align:center;width:100%;<?=$cls?>" onchange="save_etm('<?=$ETM_CODE;?>',this.value,'9','<?=$ETM_NUM;?>','<?=$VHCCT_ID;?>','<?=$ETM_GROUP;?>','<?=$ETM_TYPE;?>')" <?=$rol?>></td>
						<td ><input type="text" name="ETM_PM10" id="ETM_PM10" autocomplete="off" value="<?php echo $ETM_PM10;?>" style="text-align:center;width:100%;<?=$cls?>" onchange="save_etm('<?=$ETM_CODE;?>',this.value,'10','<?=$ETM_NUM;?>','<?=$VHCCT_ID;?>','<?=$ETM_GROUP;?>','<?=$ETM_TYPE;?>')" <?=$rol?>></td>
						<td ><input type="text" name="ETM_PM11" id="ETM_PM11" autocomplete="off" value="<?php echo $ETM_PM11;?>" style="text-align:center;width:100%;<?=$cls?>" onchange="save_etm('<?=$ETM_CODE;?>',this.value,'11','<?=$ETM_NUM;?>','<?=$VHCCT_ID;?>','<?=$ETM_GROUP;?>','<?=$ETM_TYPE;?>')" <?=$rol?>></td>
						<td ><input type="text" name="ETM_PM12" id="ETM_PM12" autocomplete="off" value="<?php echo $ETM_PM12;?>" style="text-align:center;width:100%;<?=$cls?>" onchange="save_etm('<?=$ETM_CODE;?>',this.value,'12','<?=$ETM_NUM;?>','<?=$VHCCT_ID;?>','<?=$ETM_GROUP;?>','<?=$ETM_TYPE;?>')" <?=$rol?>></td>
						<td ><?php print "$ETM_TYPE";?></td>
					</tr>
					<?php }; ?>
				</tbody>
			</table>
			<input type="hidden" name="RS_NUM_TOTAL" id="RS_NUM_TOTAL" value="<?php print "$no";?>">
			
		</form>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
		<center>
			<input type="button" class="button_gray" value="อัพเดท" onclick="finechecklist()">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>