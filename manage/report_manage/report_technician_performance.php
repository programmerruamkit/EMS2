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
		$HREFDAY    ="../manage/report_manage/report_technician_performance_daily_amt_excelxlsx.php?txt_datestart='+datestartdetail+'&area=$SESSION_AREA";
		$HREFMONTH  ="../manage/report_manage/report_technician_performance_month_amt_excelxlsx.php?txt_datestart='+datestartdetail+'&txt_dateend='+dateenddetail+'&area=$SESSION_AREA";
	}else{
		$HREFDAY    ="../manage/report_manage/report_technician_performance_daily_gw_excelxlsx.php?txt_datestart='+datestartdetail+'&area=$SESSION_AREA";
		$HREFMONTH  ="../manage/report_manage/report_technician_performance_month_gw_excelxlsx.php?txt_datestart='+datestartdetail+'&txt_dateend='+dateenddetail+'&area=$SESSION_AREA";
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
			document.getElementById('dateEndMonth').value = document.getElementById('dateStartMonth').value;
		}
		function excel_daily() {
			var datestartdetail = document.getElementById('dateStartDaily').value;
			window.open('<?=$HREFDAY?>','_blank');
		}
		function excel_month() {
			var datestartdetail = document.getElementById('dateStartMonth').value;
			var dateenddetail   = document.getElementById('dateEndMonth').value;
			window.open('<?=$HREFMONTH?>','_blank');
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
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;ประสิทธิภาพช่างรายบุคคล (<?=$SESSION_AREA?>)</h3></td>
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
      <h3 align="left"><u>สรุปรายวัน</u></h3> 
      <table>
        <tbody>
            <tr align="center">
              <td width="10%" align="right"><strong>เงื่อนไขการค้นหา :</strong></td>    
                <td width="20%" align="left">
                  <div class="row input-control">วันที่เริ่มต้น
                    <input type="text" name="dateStartDaily" id="dateStartDaily" class="datepic time" placeholder="วันที่เริ่มต้น" autocomplete="off" value="<?=$getday;?>">
                  </div>
                </td>
                <td width="30%" align="center">
                  <div class="row input-control"><br>
                    <button  title="Excel" type="button" class="bg-color-green big" onclick="excel_daily()"><font color="white" size="4"><i class="icon-file-excel icon-large"></i> Excel สรุปรายวัน</font></button>
                  </div>
                </td>
                <td align="center"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </tbody>
      </table> 
      <hr>
      <br>   
      <h3 align="left"><u>สรุปรายเดือน</u></h3> 
      <table>
        <tbody>
            <tr align="center">
              <td width="10%" align="right"><strong>เงื่อนไขการค้นหา :</strong></td>    
                <td width="20%" align="left">
                  <div class="row input-control">วันที่เริ่มต้น
                    <input type="text" name="dateStartMonth" id="dateStartMonth" class="datepic time" placeholder="วันที่เริ่มต้น" autocomplete="off" value="<?=$getday;?>" onchange="date1todate2()">
                  </div>
                </td>
                <td width="5%" align="right">&nbsp;</td>
                <td width="20%" align="left">
                  <div class="row input-control">วันที่สิ้นสุด
                    <input type="text" name="dateEndMonth" id="dateEndMonth" class="datepic time" placeholder="วันที่สิ้นสุด" autocomplete="off" value="<?=$getday;?>">
                  </div>
                </td>
                <td width="30%" align="center">
                  <div class="row input-control"><br>
                    <button  title="Excel" type="button" class="bg-color-green big" onclick="excel_month()"><font color="white" size="4"><i class="icon-file-excel icon-large"></i> Excel สรุปรายเดือน</font></button>
                  </div>
                </td>
                <td align="center"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </tbody>
      </table> 
      <hr>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
      <center>
        <input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>manage/report_manage/report_technician_performance.php');">
      </center>
    </td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>