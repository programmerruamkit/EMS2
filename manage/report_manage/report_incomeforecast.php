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
		$HREF="../manage/report_manage/report_incomeforecast_amt_excel.php?txt_datestart_amt='+datestartdetail+'&txt_dateend_amt='+dateenddetail+'&area=$SESSION_AREA";
		$HREF_SUM="../manage/report_manage/report_incomeforecast_amt_excel.php?txt_datestart_amt='+datestartsum+'&txt_dateend_amt='+dateendsum+'&area=$SESSION_AREA";
	}else{
		$HREF="../manage/report_manage/report_incomeforecast_gw_excel.php?txt_datestart_amt='+datestartdetail+'&txt_dateend_amt='+dateenddetail+'&area=$SESSION_AREA";
		$HREF_SUM="../manage/report_manage/report_incomeforecast_gw_excel.php?txt_datestart_amt='+datestartsum+'&txt_dateend_amt='+dateendsum+'&area=$SESSION_AREA";
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
		function datestartenddetail(){
			document.getElementById('dateenddetail').value = document.getElementById('datestartdetail').value;
		}
		function excel_incomeforecast() {         
			var datestartdetail = document.getElementById('datestartdetail').value;
			var dateenddetail   = document.getElementById('dateenddetail').value;
			window.open('<?=$HREF?>','_blank');

		}
		function datestartendsum(){
			document.getElementById('dateendsum').value = document.getElementById('datestartsum').value;
		}
		function excel_incomeforecast_summary() {
			var datestartsum = document.getElementById('datestartsum').value;
			var dateendsum   = document.getElementById('dateendsum').value;
			window.open('<?=$HREF_SUM?>','_blank');
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
                <td width="25" valign="middle" class=""><img src="https://img2.pic.in.th/pic/reports-icon48.png" width="48" height="48"></td>
                <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;รายงานประมาณการรายได้ (<?=$SESSION_AREA?>)</h3></td>
                <td width="617" align="right" valign="bottom" class="" nowrap>
                    <div class="toolbar">
                        
                        
                        
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
      <h3>รายงาน รายละเอียดประมาณการรายได้</h3> 
      <table>
        <tbody>
            <tr align="center">
              <td width="10%" align="right"><strong>เงื่อนไขการค้นหา :</strong></td>    
                <td width="20%" align="left">
                  <div class="row input-control">วันที่เริ่มต้น
                    <input type="text" name="datestartdetail" id="datestartdetail" class="datepic time" placeholder="วันที่เริ่มต้น" autocomplete="off" value="<?=$getday;?>" onchange="datestartenddetail()">
                  </div>
                </td>
                <td width="5%" align="right">&nbsp;</td>                       
                <td width="20%" align="left">
                  <div class="row input-control">วันที่สิ้นสุด
                    <input type="text" name="dateenddetail" id="dateenddetail" class="datepic time" placeholder="วันที่สิ้นสุด" autocomplete="off" value="<?=$getday;?>">
                  </div>
                </td>
                <td width="30%" align="center">
                  <div class="row input-control"><br>
                    <button title="Excel" type="button" class="bg-color-green big" onclick="excel_incomeforecast()"><font color="white" size="4"><i class="icon-file-excel icon-large"></i> Excel รายงานรายละเอียด</font></button>
                  </div>
                </td>
                <td align="center"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </tbody>
      </table> 
      <!-- <hr>
      <br>  
      <h3>รายงาน สรุปประมาณการรายได้</h3>  
      <table>
        <tbody>
            <tr align="center">
              <td width="10%" align="right"><strong>เงื่อนไขการค้นหา :</strong></td>    
                <td width="20%" align="left">
                  <div class="row input-control">วันที่เริ่มต้น
                    <input type="text" name="datestartsum" id="datestartsum" class="datepic time" placeholder="วันที่เริ่มต้น" autocomplete="off" value="<?=$getday;?>" onchange="datestartendsum()">
                  </div>
                </td>
                <td width="5%" align="right">&nbsp;</td>                       
                <td width="20%" align="left">
                  <div class="row input-control">วันที่สิ้นสุด
                    <input type="text" name="dateendsum" id="dateendsum" class="datepic time" placeholder="วันที่สิ้นสุด" autocomplete="off" value="<?=$getday;?>">
                  </div>
                </td>
                <td width="30%" align="center">
                  <div class="row input-control"><br>
                    <button  title="Excel" type="button" class="bg-color-green big" onclick="excel_incomeforecast_summary()"><font color="white" size="4"><i class="icon-file-excel icon-large"></i> Excel รายงานสรุป</font></button>
                  </div>
                </td>
                <td align="center"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </tbody>
      </table>  -->
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
      <center>
        <input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>manage/report_manage/report_incomeforecast.php');">
      </center>
    </td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>