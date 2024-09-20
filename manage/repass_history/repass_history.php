<?php
	session_start();
	$path = "../";   	
	include($path.'../include/connect.php');
	// print_r ($_SESSION);
?>
<html>
<head>
<script type="text/javascript">
	$(document).ready(function(e) {	   
		$("#button_new").click(function(){
			ajaxPopup2("<?=$path?>manage/repass_history/repass_history_form.php","add","1=1","600","315","เพิ่มเมนูใหม่");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>manage/repass_history/repass_history_form.php","edit","1=1","1300","315","แก้ไขเมนู");
		});
    });
</script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25" valign="middle" class=""><img src="https://img2.pic.in.th/pic/emp_survey.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;ประวัติการเปลี่ยนรหัสผ่าน</h3></td>
        <td width="617" align="right" valign="bottom" class="" nowrap>
            <!-- <div class="toolbar">
                <button class="bg-color-blue" style="padding-top:8px;" title="New" id="button_new"><i class='icon-plus icon-large'></i></button>
                <button class="bg-color-yellow" style="padding-top:8px;" title="Edit" id="button_edit"><i class='icon-pencil icon-large'></i></button>
            </div> -->
        </td>
        </tr>
    </table></td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center">
    <form id="form1" name="form1" method="post" action="#">
      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="display" id="datatable">
		<thead>
			<tr height="35">
			<th width="10%" align="center"><strong>#</strong></th>
			<th width="30%" align="left"><strong>รหัสผ่านที่ทำการเปลี่ยน</strong></th>
			<th width="30%" align="left"><strong>ผู้บันทึก</strong></th>
			<th width="30%" align="center"><strong>วันเวลาที่บันทึก</strong></th>
			</tr>
		</thead>
        <tbody>
          <?php
            $AD_PERSONCODE=$_SESSION['AD_PERSONCODE'];
            $sql_menu = "SELECT * FROM vwLOG_TRANSAC WHERE LOGACT_CODE = 'LA23' AND LOG_EMPLOYEECODE = '$AD_PERSONCODE' ORDER BY TIMESTAMP DESC";
            $query_menu = sqlsrv_query($conn, $sql_menu);
            $no=0;
            while($result_logmenu = sqlsrv_fetch_array($query_menu, SQLSRV_FETCH_ASSOC)){	
              $no++;
              $LOG_ID=$result_logmenu['LOG_ID'];
              $MEAN=$result_logmenu['MEAN'];
              $LOG_REMARK=$result_logmenu['LOG_REMARK'];
              $WORKINGBY=$result_logmenu['WORKINGBY'];
              $DATETIME1038=$result_logmenu['DATETIME1038'];
          ?>			
            <tr id="<?php print $LOG_ID; ?>" height="25">
              <td align="center"><font size="2"><?php print "$no.";?></font></td>
              <td><font size="2"><?php print $LOG_REMARK;?></font></td>
              <td><font size="2"><?php print $WORKINGBY;?></font></td>
              <td><font size="2"><?php print $DATETIME1038;?></font></td>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>manage/repass_history/repass_history.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>