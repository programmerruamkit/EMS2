<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	$SESSION_AREA = $_SESSION["AD_AREA"];
	if($SESSION_AREA=="AMT"){
		$HREF="../manage/report_manage/report_detail_amt_excel.php?txt_datestart='+datestartdetail+'&txt_dateend='+dateenddetail+'&area=$SESSION_AREA";
	}else{
		$HREF="../manage/report_manage/report_detail_amt_excel.php?txt_datestart='+datestartdetail+'&txt_dateend='+dateenddetail+'&area=$SESSION_AREA";
	}
	$getday = $GETDAYEN;

?>
<html>
<head>
  <meta charset="utf-8">
	<script type="text/javascript">
		$(document).ready(function(e) {
			$('.datepic').datetimepicker({
				timepicker:false,
				lang:'th',
				format:'d/m/Y',
				closeOnDateSelect: true
			});
		});
		function date1todate2(){
			document.getElementById('dateEnd').value = document.getElementById('dateStart').value;
		}
		function excel_estimatecashdaily_plan() {
			var datestartdetail = document.getElementById('dateStart').value;
			var dateenddetail   = document.getElementById('dateEnd').value;
			window.open('<?=$HREF?>','_blank');
		}
	</script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25" valign="middle" class=""><img src="https://img2.pic.in.th/pic/reports-icon48.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;รายงานรายละเอียดงานซ่อม (<?=$SESSION_AREA?>)</h3></td>
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
                <td width="20%" align="left">
                  <div class="row input-control">วันที่เริ่มต้น
                    <input type="text" name="dateStart" id="dateStart" class="datepic time" placeholder="วันที่เริ่มต้น" autocomplete="off" value="<?=$getday;?>" onchange="date1todate2()">
                  </div>
                </td>
                <td width="5%" align="right">&nbsp;</td>                       
                <td width="20%" align="left">
                  <div class="row input-control">วันที่สิ้นสุด
                    <input type="text" name="dateEnd" id="dateEnd" class="datepic time" placeholder="วันที่สิ้นสุด" autocomplete="off" value="<?=$getday;?>">
                  </div>
                </td>
                <td width="30%" align="center">
                  <div class="row input-control"><br>
                    <button  title="Excel" type="button" class="bg-color-green big" onclick="excel_estimatecashdaily_plan()"><font color="white" size="4"><i class="icon-file-excel icon-large"></i> Excel รายงานรายละเอียดงานซ่อม</font></button>
                  </div>
                </td>
                <td align="center"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </tbody>
      </table> 
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
      <center>
        <input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>manage/report_manage/report_detail.php');">
      </center>
    </td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>