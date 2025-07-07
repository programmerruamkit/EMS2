<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	$SESSION_AREA=$_SESSION["AD_AREA"];
	if($SESSION_AREA=="AMT"){
		$GETmlpmline=$_GET['mlpmline'];
	}else{
		$GETmlpmline='กลุ่ม RANK PM สำหรับเกตเวย์';
	}
?>
<html>
<head>
<script type="text/javascript">
    
    function save_mlpm(code,val,group) {      
      var url = "<?=$path?>views_amt/mileagepm_manage/mileagepm_main_proc.php";
      $.ajax({
          type: 'post',
          url: url,
          data: {
              target: "edit", 
              code: code,
              val: val,
              group: group
          },
        success:function(data){
          // alert(data)
			var mlpmname = $("#MLPM_NAME").val();
			var mlpmline = $("#MLPM_REMARK").val();				
			if(mlpmname==''){
				var getselchecklist = "?mlpmline="+mlpmline;
				loadViewdetail('<?=$path?>views_amt/mileagepm_manage/mileagepm_main.php'+getselchecklist);
			}else{
				var getselchecklist = "?mlpmname="+mlpmname+"&mlpmline="+mlpmline;
				loadViewdetail('<?=$path?>views_amt/mileagepm_manage/mileagepm_main.php'+getselchecklist);
			}
        }
      });          
    }
	function querychecklist(){
		var mlpmname = $("#MLPM_NAME").val();
		var mlpmline = $("#MLPM_REMARK").val();		

        if($('#MLPM_REMARK').val() == '' && $('#MLPM_NAME').val() == '' ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาเลือกสายงาน',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#MLPM_REMARK").focus(), 0);
              }
          })
          return false;
        }	

        if($('#MLPM_REMARK').val() == '' ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาเลือกสายงาน',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#MLPM_REMARK").focus(), 0);
              }
          })
          return false;
        }			
        // if($('#MLPM_NAME').val() == '' ){
        //   Swal.fire({
        //       icon: 'warning',
        //       title: 'กรุณาเลือก RANK',
        //       showConfirmButton: false,
        //       timer: 1500,
        //       onAfterClose: () => {
        //           setTimeout(() => $("#MLPM_NAME").focus(), 0);
        //       }
        //   })
        //   return false;
        // }		
		
		if(mlpmname==''){
			// alert('1')
			var getselchecklist = "?mlpmline="+mlpmline;
			loadViewdetail('<?=$path?>views_amt/mileagepm_manage/mileagepm_main.php'+getselchecklist);
		}else{
			// alert('2')
			var getselchecklist = "?mlpmname="+mlpmname+"&mlpmline="+mlpmline;
			loadViewdetail('<?=$path?>views_amt/mileagepm_manage/mileagepm_main.php'+getselchecklist);
		}
	} 
	$(document).ready(function(e) {	   
		$("#button_new").click(function(){
			ajaxPopup2("<?=$path?>views_amt/mileagepm_manage/mileagepm_main_form.php","add","1=1","800","460","เพิ่ม RANK PM ใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/mileagepm_manage/mileagepm_main_form.php","edit","1=1","1000","340","แก้ไข RANK PM");
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
						var url = "<?=$path?>views_amt/mileagepm_manage/mileagepm_main_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_mileagepm('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/mileagepm_manage/mileagepm_main.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_mileagepm_main(refcode,no) {
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
					var url = "<?=$path?>views_amt/mileagepm_manage/mileagepm_main_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
							log_transac_mileagepm('LA19', '-', ref);
							loadViewdetail('<?=$path?>views_amt/mileagepm_manage/mileagepm_main.php');
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
        <td width="25" valign="middle" class=""><img src="../images/car_repair.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลเลขไมล์ Rank PM</h3></td>
        <td width="617" align="right" valign="bottom" class="" nowrap>
            <div class="toolbar">
                
                
                
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
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="MLPM_NAME" id="MLPM_NAME" required>
                                <option value disabled selected>-------เลือก RANK-------</option>
								<option value="PMoRS-1" <?php if($_GET['mlpmname']=="PMoRS-1"){echo "selected";} ?>>PMoRS-1</option>
								<option value="PMoRS-2" <?php if($_GET['mlpmname']=="PMoRS-2"){echo "selected";} ?>>PMoRS-2</option>
								<option value="PMoRS-3" <?php if($_GET['mlpmname']=="PMoRS-3"){echo "selected";} ?>>PMoRS-3</option>
								<option value="PMoRS-4" <?php if($_GET['mlpmname']=="PMoRS-4"){echo "selected";} ?>>PMoRS-4</option>
								<option value="PMoRS-5" <?php if($_GET['mlpmname']=="PMoRS-5"){echo "selected";} ?>>PMoRS-5</option>
								<option value="PMoRS-6" <?php if($_GET['mlpmname']=="PMoRS-6"){echo "selected";} ?>>PMoRS-6</option>
								<option value="PMoRS-7" <?php if($_GET['mlpmname']=="PMoRS-7"){echo "selected";} ?>>PMoRS-7</option>
								<option value="PMoRS-8" <?php if($_GET['mlpmname']=="PMoRS-8"){echo "selected";} ?>>PMoRS-8</option>
								<option value="PMoRS-9" <?php if($_GET['mlpmname']=="PMoRS-9"){echo "selected";} ?>>PMoRS-9</option>
								<option value="PMoRS-10" <?php if($_GET['mlpmname']=="PMoRS-10"){echo "selected";} ?>>PMoRS-10</option>
								<option value="PMoRS-11" <?php if($_GET['mlpmname']=="PMoRS-11"){echo "selected";} ?>>PMoRS-11</option>
								<?php if($SESSION_AREA=="AMT"){ ?>
									<option value="PMoRS-12" <?php if($_GET['mlpmname']=="PMoRS-12"){echo "selected";} ?>>PMoRS-12</option>
								<?php } ?>
                            </select>
                        </div>
                    </td>
					<td width="5%" align="right">&nbsp;</td>                                 
                    <td width="20%" align="center">
                        <div class="input-control select">                    
                            <?php
                                $stmt_selline = "SELECT DISTINCT MLPM_REMARK FROM MILEAGESETPM WHERE NOT MLPM_STATUS = 'D' AND MLPM_AREA = '$SESSION_AREA' ORDER BY MLPM_REMARK ASC";
                                $query_selline = sqlsrv_query($conn, $stmt_selline);
                            ?>
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="MLPM_REMARK" id="MLPM_REMARK" required>
                                <option value disabled selected>-------เลือกกลุ่มสายงาน-------</option>
                                
                                <?php while($result_selline = sqlsrv_fetch_array($query_selline)): ?>
                                    <option value="<?=$result_selline['MLPM_REMARK']?>" <?php if($GETmlpmline==$result_selline['MLPM_REMARK']){echo "selected";} ?>><?=$result_selline['MLPM_REMARK']?></option>
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
			<?php if($SESSION_AREA=="AMT"){ ?>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
			<?php }else{ ?>
				<table width="50%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
			<?php } ?>
				<thead>
					<?php if($SESSION_AREA=="AMT"){ ?>
						<tr height="30">
							<th rowspan="2" align="center" width="5%" class="ui-state-default">ลำดับ.</th>
							<th rowspan="2" align="center" width="10%" class="ui-state-default">Rank</th>
							<th colspan="3" align="center" width="65%" class="ui-state-default">เลขไมล์</th>
							<th rowspan="2" align="center" width="20%" class="ui-state-default">กลุ่ม PM</th>
						</tr>
						<tr height="30">
							<th align="center"width="10%">10,000-1,000,000</th>
							<th align="center"width="10%">1,010,000-2,000,000</th>           
							<th align="center"width="10%">2,010,000-3,000,000</th>
						</tr>
					<?php }else{ ?>
						<tr height="30">
							<th rowspan="2" align="center" width="5%" class="ui-state-default">ลำดับ.</th>
							<th rowspan="2" align="center" width="20%" class="ui-state-default">Rank</th>
							<th colspan="1" align="center" width="45%" class="ui-state-default">เลขไมล์</th>
							<th rowspan="2" align="center" width="30%" class="ui-state-default">กลุ่ม PM</th>
						</tr>
						<tr height="30">
							<th align="center"width="10%">10,000-1,000,000</th>
						</tr>
					<?php } ?>
				</thead>
				<tbody>
					<?php
						$mlpmname = $_GET['mlpmname'];
						$mlpmline = $GETmlpmline;
						// $wh="";
						if($mlpmname!="" && $mlpmline!=""){     					
							$wh="AND MLPM_NAME = '$mlpmname' AND MLPM_REMARK = '$mlpmline'";
						}else if($mlpmline!=""){     					
							$wh="AND MLPM_REMARK = '$mlpmline'";
						}else{
							$wh="null";
						}
						
						$sql_mileagepm = "SELECT * FROM MILEAGESETPM WHERE NOT MLPM_STATUS='D' AND MLPM_AREA = '$SESSION_AREA' ".$wh."";
						$query_mileagepm = sqlsrv_query($conn, $sql_mileagepm);
						$no=0;
						while($result_mileagepm = sqlsrv_fetch_array($query_mileagepm, SQLSRV_FETCH_ASSOC)){	
							$no++;
							$MLPM_ID=$result_mileagepm['MLPM_ID'];
							$MLPM_CODE=$result_mileagepm['MLPM_CODE'];
							$MLPM_NAME=$result_mileagepm['MLPM_NAME'];
							$MLPM_MILEAGE_10K1M=$result_mileagepm['MLPM_MILEAGE_10K1M'];
							$MLPM_MILEAGE_1M2M=$result_mileagepm['MLPM_MILEAGE_1M2M'];
							$MLPM_MILEAGE_2M3M=$result_mileagepm['MLPM_MILEAGE_2M3M'];
							$MLPM_LINEOFWORK=$result_mileagepm['MLPM_LINEOFWORK'];
							$MLPM_REMARK=$result_mileagepm['MLPM_REMARK'];
							$MLPM_STATUS=$result_mileagepm['MLPM_STATUS'];
					?>
					<?php if($SESSION_AREA=="AMT"){ ?>
						<tr height="25px" align="center">
							<td ><?php print "$no.";?></td>
							<td ><?php print "$MLPM_NAME";?></td>
							<td ><input type="text" name="MLPM_MILEAGE_10K1M" id="MLPM_MILEAGE_10K1M" class="datepic char" autocomplete="off" value="<?php echo $MLPM_MILEAGE_10K1M;?>" style="text-align:center;width:120px;" onchange="save_mlpm('<?=$MLPM_CODE;?>',this.value,'1')"></td>
							<td ><input type="text" name="MLPM_MILEAGE_1M2M" id="MLPM_MILEAGE_1M2M" class="datepic char" autocomplete="off" value="<?php echo $MLPM_MILEAGE_1M2M;?>" style="text-align:center;width:120px;" onchange="save_mlpm('<?=$MLPM_CODE;?>',this.value,'2')"></td>
							<td ><input type="text" name="MLPM_MILEAGE_2M3M" id="MLPM_MILEAGE_2M3M" class="datepic char" autocomplete="off" value="<?php echo $MLPM_MILEAGE_2M3M;?>" style="text-align:center;width:120px;" onchange="save_mlpm('<?=$MLPM_CODE;?>',this.value,'3')"></td>
							<td ><?php print "$MLPM_REMARK";?></td>
						</tr>
					<?php }else{ ?>
						<tr height="25px" align="center">
							<td ><?php print "$no.";?></td>
							<td ><?php print "$MLPM_NAME";?></td>
							<td ><input type="text" name="MLPM_MILEAGE_10K1M" id="MLPM_MILEAGE_10K1M" class="datepic char" autocomplete="off" value="<?php echo $MLPM_MILEAGE_10K1M;?>" style="text-align:center;width:100px;" onchange="save_mlpm('<?=$MLPM_CODE;?>',this.value,'1')"></td>
							<td ><?php print "$MLPM_REMARK";?></td>
						</tr>
					<?php } } ?>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/mileagepm_manage/mileagepm_main.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>