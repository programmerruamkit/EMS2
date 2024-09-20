<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	$SESSION_AREA=$_SESSION["AD_AREA"];	
	if($SESSION_AREA=="AMT"){
		$RPMAREA="AMT";
	}
	if($SESSION_AREA=="GW"){
		$RPMAREA="GW";
	}
    $date1 = date("d-m-Y");
    $start = explode("-", $date1);
    $startd = $start[0];
    $startif = $start[1];
        if($startif == "01"){
            $startm = "มกราคม";
        }else if($startif == "02"){
            $startm = "กุมภาพันธ์";
        }else if($startif == "03"){
            $startm = "มีนาคม";
        }else if($startif == "04"){
            $startm = "เมษายน";
        }else if($startif == "05"){
            $startm = "พฤษภาคม";
        }else if($startif == "06"){
            $startm = "มิถุนายน";
        }else if($startif == "07"){
            $startm = "กรกฎาคม";
        }else if($startif == "08"){
            $startm = "สิงหาคม";
        }else if($startif == "09"){
            $startm = "กันยายน";
        }else if($startif == "10"){
            $startm = "ตุลาคม";
        }else if($startif == "11"){
            $startm = "พฤศจิกายน";
        }else if($startif == "12"){
            $startm = "ธันวาคม";
        }
    $starty = $start[2]+543;
    // $startymd = $start[2].'-'.$start[1].'-'.$start[0];
    $startymdth = $startd.' '.$startm.' '.$starty;
	$newdate=date("Y/m/d");
?>
<html>
<head>
<script type="text/javascript">
    function save_checkrepairman(group, value, type) {      
      var url = "<?=$path?>views_amt/repairman_manage/check_repairman_proc.php";
      $.ajax({
          type: 'post',
          url: url,
          data: {
			proc: "add", 
			group: group, 
			value: value, 
			type: type
          },
        success:function(){
            // loadViewdetail('<?=$path?>views_amt/repairman_manage/check_repairman.php');
        }
      });          
    }
    // window.setTimeout(function () {     
	// 	loadViewdetail('<?=$path?>views_amt/repairman_manage/check_repairman.php');   
    // }, 10000);
</script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25" valign="middle" class=""><img src="../images/RPM.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการข้อมูลช่างประจำวัน</h3></td>
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
		<h2>ข้อมูลจำนวนช่าง <?=$RPMAREA;?> ประจำวันที่ <?=$startymdth?></h2><br>
		<form id="form1" name="form1" method="post" action="#">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
				<thead>
					<tr height="30">
						<th align="center" width="5%" class="ui-state-default">ลำดับ.</th>
						<th align="center" width="20%" class="ui-state-default">ประเภท</th>
						<th align="center" width="10%" class="ui-state-default">ช่างทั้งหมด</th>
						<th align="center" width="10%" class="ui-state-default">พร้อมปฏิบัติงาน</th>
						<th align="center" width="10%" class="ui-state-default">ลางาน</th>
						<th align="center" width="10%" class="ui-state-default">อบรม</th>
						<th align="center" width="10%" class="ui-state-default">ต่ออายุใบขับขี่</th>
						<th align="center" width="25%" class="ui-state-default">อื่น ๆ</th>
					</tr>
				</thead>
				<tbody>
					<?php                                                   
						$sql_rpm1 = "SELECT * FROM CHECKMAN WHERE CM_DATE='$newdate' AND CM_GROUP=1 AND CM_AREA='$SESSION_AREA'";
						$query_rpm1 = sqlsrv_query($conn, $sql_rpm1);
						$result_rpm1 = sqlsrv_fetch_array($query_rpm1, SQLSRV_FETCH_ASSOC);
						
						$sql_rpm2 = "SELECT * FROM CHECKMAN WHERE CM_DATE='$newdate' AND CM_GROUP=2 AND CM_AREA='$SESSION_AREA'";
						$query_rpm2 = sqlsrv_query($conn, $sql_rpm2);
						$result_rpm2 = sqlsrv_fetch_array($query_rpm2, SQLSRV_FETCH_ASSOC);
						
						$sql_rpm3 = "SELECT * FROM CHECKMAN WHERE CM_DATE='$newdate' AND CM_GROUP=3 AND CM_AREA='$SESSION_AREA'";
						$query_rpm3 = sqlsrv_query($conn, $sql_rpm3);
						$result_rpm3 = sqlsrv_fetch_array($query_rpm3, SQLSRV_FETCH_ASSOC);
						
						$sql_rpm4 = "SELECT * FROM CHECKMAN WHERE CM_DATE='$newdate' AND CM_GROUP=4 AND CM_AREA='$SESSION_AREA'";
						$query_rpm4 = sqlsrv_query($conn, $sql_rpm4);
						$result_rpm4 = sqlsrv_fetch_array($query_rpm4, SQLSRV_FETCH_ASSOC);
						
						$sql_rpm5 = "SELECT * FROM CHECKMAN WHERE CM_DATE='$newdate' AND CM_GROUP=5 AND CM_AREA='$SESSION_AREA'";
						$query_rpm5 = sqlsrv_query($conn, $sql_rpm5);
						$result_rpm5 = sqlsrv_fetch_array($query_rpm5, SQLSRV_FETCH_ASSOC);
						
						$sql_rpm6 = "SELECT * FROM CHECKMAN WHERE CM_DATE='$newdate' AND CM_GROUP=6 AND CM_AREA='$SESSION_AREA'";
						$query_rpm6 = sqlsrv_query($conn, $sql_rpm6);
						$result_rpm6 = sqlsrv_fetch_array($query_rpm6, SQLSRV_FETCH_ASSOC);

						$CM_TOTAL=$result_rpm1['CM_TOTAL']+$result_rpm2['CM_TOTAL']+$result_rpm3['CM_TOTAL']+$result_rpm4['CM_TOTAL']+$result_rpm5['CM_TOTAL']+$result_rpm6['CM_TOTAL'];
						$CM_READY=$result_rpm1['CM_READY']+$result_rpm2['CM_READY']+$result_rpm3['CM_READY']+$result_rpm4['CM_READY']+$result_rpm5['CM_READY']+$result_rpm6['CM_READY'];
						$CM_LEAVE=$result_rpm1['CM_LEAVE']+$result_rpm2['CM_LEAVE']+$result_rpm3['CM_LEAVE']+$result_rpm4['CM_LEAVE']+$result_rpm5['CM_LEAVE']+$result_rpm6['CM_LEAVE'];
						$CM_TRAINNING=$result_rpm1['CM_TRAINNING']+$result_rpm2['CM_TRAINNING']+$result_rpm3['CM_TRAINNING']+$result_rpm4['CM_TRAINNING']+$result_rpm5['CM_TRAINNING']+$result_rpm6['CM_TRAINNING'];
						$CM_LICENSE=$result_rpm1['CM_LICENSE']+$result_rpm2['CM_LICENSE']+$result_rpm3['CM_LICENSE']+$result_rpm4['CM_LICENSE']+$result_rpm5['CM_LICENSE']+$result_rpm6['CM_LICENSE'];
						$CM_OTHER=$result_rpm1['CM_OTHER']+$result_rpm2['CM_OTHER']+$result_rpm3['CM_OTHER']+$result_rpm4['CM_OTHER']+$result_rpm5['CM_OTHER']+$result_rpm6['CM_OTHER'];
					?>
					<tr height="25px">
						<td align="center">1</td>
						<td align="left">ระบบไฟ</td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm1['CM_TOTAL'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('1',this.value,'CM_TOTAL')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm1['CM_READY'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('1',this.value,'CM_READY')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm1['CM_LEAVE'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('1',this.value,'CM_LEAVE')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm1['CM_TRAINNING'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('1',this.value,'CM_TRAINNING')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm1['CM_LICENSE'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('1',this.value,'CM_LICENSE')"></td>
						<td align="center"><input type="text" class="char" autocomplete="off" value="<?=$result_rpm1['CM_OTHER'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('1',this.value,'CM_OTHER')"></td>
					</tr>
					<tr height="25px">
						<td align="center">2</td>
						<td align="left">ระบบแอร์</td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm2['CM_TOTAL'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('2',this.value,'CM_TOTAL')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm2['CM_READY'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('2',this.value,'CM_READY')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm2['CM_LEAVE'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('2',this.value,'CM_LEAVE')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm2['CM_TRAINNING'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('2',this.value,'CM_TRAINNING')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm2['CM_LICENSE'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('2',this.value,'CM_LICENSE')"></td>
						<td align="center"><input type="text" class="char" autocomplete="off" value="<?=$result_rpm2['CM_OTHER'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('2',this.value,'CM_OTHER')"></td>
					</tr>
					<tr height="25px">
						<td align="center">3</td>
						<td align="left">โครงสร้าง</td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm3['CM_TOTAL'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('3',this.value,'CM_TOTAL')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm3['CM_READY'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('3',this.value,'CM_READY')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm3['CM_LEAVE'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('3',this.value,'CM_LEAVE')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm3['CM_TRAINNING'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('3',this.value,'CM_TRAINNING')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm3['CM_LICENSE'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('3',this.value,'CM_LICENSE')"></td>
						<td align="center"><input type="text" class="char" autocomplete="off" value="<?=$result_rpm3['CM_OTHER'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('3',this.value,'CM_OTHER')"></td>
					</tr>
					<tr height="25px">
						<td align="center">4</td>
						<td align="left">ยาง ช่วงล่าง</td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm4['CM_TOTAL'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('4',this.value,'CM_TOTAL')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm4['CM_READY'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('4',this.value,'CM_READY')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm4['CM_LEAVE'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('4',this.value,'CM_LEAVE')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm4['CM_TRAINNING'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('4',this.value,'CM_TRAINNING')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm4['CM_LICENSE'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('4',this.value,'CM_LICENSE')"></td>
						<td align="center"><input type="text" class="char" autocomplete="off" value="<?=$result_rpm4['CM_OTHER'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('4',this.value,'CM_OTHER')"></td>
					</tr>
					<tr height="25px">
						<td align="center">5</td>
						<td align="left">เครื่องยนต์</td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm5['CM_TOTAL'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('5',this.value,'CM_TOTAL')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm5['CM_READY'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('5',this.value,'CM_READY')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm5['CM_LEAVE'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('5',this.value,'CM_LEAVE')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm5['CM_TRAINNING'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('5',this.value,'CM_TRAINNING')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm5['CM_LICENSE'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('5',this.value,'CM_LICENSE')"></td>
						<td align="center"><input type="text" class="char" autocomplete="off" value="<?=$result_rpm5['CM_OTHER'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('5',this.value,'CM_OTHER')"></td>
					</tr>
					<tr height="25px">
						<td align="center">6</td>
						<td align="left">อุปกรณ์ประจำรถ</td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm6['CM_TOTAL'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('6',this.value,'CM_TOTAL')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm6['CM_READY'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('6',this.value,'CM_READY')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm6['CM_LEAVE'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('6',this.value,'CM_LEAVE')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm6['CM_TRAINNING'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('6',this.value,'CM_TRAINNING')"></td>
						<td align="center"><input type="number" class="char" autocomplete="off" value="<?=$result_rpm6['CM_LICENSE'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('6',this.value,'CM_LICENSE')"></td>
						<td align="center"><input type="text" class="char" autocomplete="off" value="<?=$result_rpm6['CM_OTHER'];?>" style="text-align:center;width:100%;height:25px" onchange="save_checkrepairman('6',this.value,'CM_OTHER')"></td>
					</tr>
					<tr height="25px">
						<td align="center"><input type="hidden" value='7'></td>
						<td align="center"><b>รวม</b></td>
						<td align="center"><?=$CM_TOTAL;?></td>
						<td align="center"><?=$CM_READY;?></td>
						<td align="center"><?=$CM_LEAVE;?></td>
						<td align="center"><?=$CM_TRAINNING;?></td>
						<td align="center"><?=$CM_LICENSE;?></td>
						<td align="center"></td>
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
			<!-- <font color="red">***โหลดข้อมูลทุก 10 วินาที หากต้องการโหลดทันที ให้กดปุ่ม อัพเดท</font><br><br> -->
			<font color="red">***โหลดข้อมูลให้เป็นปัจจุบัน กรุณากดปุ่ม อัพเดท</font><br><br>
			<input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>views_amt/repairman_manage/check_repairman.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>