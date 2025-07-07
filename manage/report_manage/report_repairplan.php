<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	$SESSION_AREA = $_SESSION["AD_AREA"];
	if($SESSION_AREA=="AMT"){
		$HREF="../manage/report_manage/report_repairplan_amt.php?txt_daterepair='+datestartdetail+'&area=$SESSION_AREA";
		$HREF_EXCEL="../manage/report_manage/report_repairplan_amt_excel.php?txt_daterepair='+datestartdetail+'&area=$SESSION_AREA";
	}else{
		$HREF="../manage/report_manage/report_repairplan_gw.php?txt_daterepair='+datestartdetail+'&area=$SESSION_AREA";
		$HREF_EXCEL="../manage/report_manage/report_repairplan_gw_excel.php?txt_daterepair='+datestartdetail+'&area=$SESSION_AREA";
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
		function summaryreportrepairplan() {
			var datestartdetail = document.getElementById('dateStart').value;
			window.open('<?=$HREF?>','_blank');
		}
    function summaryreportrepairplan_excel() {
			var datestartdetail = document.getElementById('dateStart').value;
			window.open('<?=$HREF_EXCEL?>','_blank');
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
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;รายงานแผนงาน (<?=$SESSION_AREA?>)</h3></td>
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
                <td width="20%" align="left">
                  <div class="row input-control">แผนประจำวันที่
                    <input type="text" name="dateStart" id="dateStart" class="datepic time" placeholder="แผนประจำวันที่" autocomplete="off" value="<?=$getday;?>">
                  </div>
                </td>
                <td width="15%" align="center">
                  <div class="row input-control"><br>
                    <button  title="Excel" type="button" class="bg-color-blue big" onclick="summaryreportrepairplan()"><font color="white" size="4">ค้นหา</font></button>
                  </div>
                </td>
                <td width="30%" align="center">
                  <div class="row input-control"><br>
                    <button  title="Excel" type="button" class="bg-color-green big" onclick="summaryreportrepairplan_excel()"><font color="white" size="4"><i class="icon-file-excel icon-large"></i> Excel รายงานแผนงาน</font></button>
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
        <input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>manage/report_manage/report_repairplan.php');">
      </center>
    </td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>