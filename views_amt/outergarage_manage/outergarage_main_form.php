<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$otgr_code=$_GET["id"];

	if($proc=="edit"){
		$stmt = "SELECT * FROM OUTER_GARAGE WHERE OTGR_CODE = ? AND NOT OTGR_STATUS='D'";
		$params = array($otgr_code);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_edit_outergarage = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $OTGR_CODE=$result_edit_outergarage["OTGR_CODE"];
	};
	if($proc=="add"){
		$stmt = "SELECT TOP 1 OTGR_ID FROM OUTER_GARAGE WHERE NOT OTGR_STATUS='D' ORDER BY OTGR_ID DESC";
		$query = sqlsrv_query( $conn, $stmt);	
		$result_add_otgr = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $OTGR_ID=$result_add_otgr["OTGR_ID"];
    $CALOTGR_ID=$OTGR_ID+1;
	};  
	$n=6;
	function RandNum($n) {
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';      
		for ($i = 0; $i < $n; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}      
		return $randomString;
	}  
	$rand="OTGR_".RandNum($n);  
?>
<html>
  
<head>
  <script type="text/javascript">
    function save_data() {
      // if(!confirm("ยืนยันการแก้ไข")) return false;
      var buttonname = $('#buttonname').val();
      var url = "<?=$path?>views_amt/outergarage_manage/outergarage_main_proc.php";
      $.ajax({
        type: "POST",
        url: url,
        data: $("#form_project").serialize(),
        success: function (data) {
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: data,
            showConfirmButton: false,
            timer: 2000
          }).then((result) => {
            if(buttonname=='add'){
              log_transac_outergarage('LA17', '-', '<?=$rand;?>');
            }else{
              log_transac_outergarage('LA18', '-', '<?=$OTGR_CODE;?>');
            }
            loadViewdetail('<?=$path?>views_amt/outergarage_manage/outergarage_main.php');
            closeUI();
            // alert(data);
          })
        }
      });
    }
  </script>
</head>

<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="50%">
          <div class="panel panel-default" style="margin-left:5px; margin-right:5px;">   
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
              <tr class="TOP">
                <td class="LEFT"></td>
                <td class="CENTER">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <?php if($proc=="edit"){ ?>
                      <td width="24" valign="middle" class=""><img src="../images/Process-Info-icon32.png" width="32"
                          height="32"></td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;แก้ไขอู่นอก</h4>
                      </td>
                      <?php }else{ ?>
                      <td width="24" valign="middle" class=""><img src="../images/plus-icon32.png" width="32" height="32">
                      </td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;เพิ่มอู่นอก</h4>
                      </td>
                      <?php } ?>
                    </tr>
                  </table>
                </td>
                <td class="RIGHT"></td>
              </tr>
              <tr class="CENTER">
                <td class="LEFT"></td>
                <td class="CENTER" align="center">
                  <form action="#" method="post" enctype="multipart/form-data" name="form_project" id="form_project">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                      <tbody>
                        <tr align="center" id="22" height="25px">
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>ชื่ออู่นอก :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="OTGR_NAME" id="OTGR_NAME" class="time" placeholder="กรอกชื่ออู่นอก" value="<?=$result_edit_outergarage["OTGR_NAME"];?>"/>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>เบอร์โทรศัพท์ :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="OTGR_PHONE" id="OTGR_PHONE" class="time" placeholder="กรอกเบอร์โทรศัพท์" value="<?=$result_edit_outergarage["OTGR_PHONE"];?>" onKeyUp="handleEnter(this, event, 0);" onFocus="onfocus_format(this);" >
                            </div>
                          </td>
                        </tr>
                        <tr align="center" id="22" height="25px">
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>ที่อยู่ :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="OTGR_ADDRESS" id="OTGR_ADDRESS" class="time" placeholder="กรอกที่อยู่" value="<?=$result_edit_outergarage["OTGR_ADDRESS"];?>"/>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>สถานะอู่นอก :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                                <select class="time" onFocus="$(this).select();" style="width: 100%;" name="OTGR_STATUS" id="OTGR_STATUS" required>
                                    <option value disabled selected>-------โปรดเลือก-------</option>
                                    <option value="Y" <?php if($result_edit_outergarage['OTGR_STATUS']== "Y"){echo "selected";} ?>>เปิดใช้งาน</option>
                                    <option value="N" <?php if($result_edit_outergarage['OTGR_STATUS']== "N"){echo "selected";} ?>>ปิดใช้งาน</option>
                                </select>
                            </div>
                          </td>
                        </tr>
                        
                        <input type="hidden" name="OTGR_ID" id="OTGR_ID" value="<?=$result_edit_outergarage['OTGR_ID'];?>" />
                        <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
                        <input type="hidden" name="otgr_code" id="otgr_code" value="<?=$otgr_code; ?>">
                        <tr align="center" height="25px">
                          <td height="35" colspan="2" align="center">
                            <?php if($proc=="edit"){ ?>
                              <input type="hidden" name="OTGR_CODE" id="OTGR_CODE" value="<?=$result_edit_outergarage['OTGR_CODE'];?>">
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onClick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                            <?php }else{ ?>
                              <input type="hidden" name="OTGR_CODE" id="OTGR_CODE" value="<?=$rand; ?>">
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="add" onClick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                            <?php } ?>
                            <button class="bg-color-red font-white" type="button" onClick="closeUI()">ปิดหน้าจอ</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </form>
                </td>
                <td class="RIGHT"></td>
              </tr>
              <tr class="BOTTOM">
                <td class="LEFT">&nbsp;</td>
                <td class="CENTER">&nbsp;</td>
                <td class="RIGHT">&nbsp;</td>
              </tr>
            </table>
          </div>
        </td>
        <?php if($proc=="edit"){ ?>
          <td valign="top">
            <div style="width:100%;" align="right">
              <div class="panel panel-default" style="height:100%">
                <div align="left" class="panel-heading">
                  <h5><img src="../images/task.png" width="32" height="32"> Log Transaction</h5>
                </div>   
                <table width="100%" class="table table-hover table-striped">
                  <thead>
                    <tr height="35">
                      <th width="5%" align="center"><strong>#</strong></th>
                      <th width="15%" align="left"><strong>กิจกรรม</strong></th>
                      <th width="40%" align="left"><strong>หมายเหตุ</strong></th>
                      <th width="20%" align="left"><strong>ผู้บันทึก</strong></th>
                      <th width="20%" align="center"><strong>วันเวลาที่บันทึก</strong></th>
                    </tr>
                  </thead>                
                  <?php
                    $sql_log = "SELECT * FROM vwLOG_TRANSAC WHERE LOG_RESERVE_CODE = ? AND LOGACT_CODE BETWEEN 'LA17' AND 'LA19' ORDER BY TIMESTAMP ASC";
                    $params = array($OTGR_CODE);	
                    $query_log = sqlsrv_query($conn, $sql_log, $params);	
                    $no=0;
                    while($result_log = sqlsrv_fetch_array($query_log, SQLSRV_FETCH_ASSOC)){	
                      $no++;
                      $MEAN=$result_log['MEAN'];
                      $LOG_REMARK=$result_log['LOG_REMARK'];
                      $WORKINGBY=$result_log['WORKINGBY'];
                      $DATETIME1038=$result_log['DATETIME1038'];
                  ?>
                    <tr height="25">
                      <td align="center"><font size="2"><?php print "$no.";?></font></td>
                      <td><font size="2"><?php print "$MEAN.";?></font></td>
                      <td><font size="2"><?php print "$LOG_REMARK.";?></font></td>
                      <td><font size="2"><?php print "$WORKINGBY.";?></font></td>
                      <td align="center"><font size="2"><?php print "$DATETIME1038.";?></font></td>
                    </tr>                
                  <?php }; ?>
                  <?php // echo "<tr><td height='35' colspan='4' align='center'>ไม่พบข้อมูล</td></tr>"; ?>
                </table>
              </div>
            </div>
          </td>
        <?php } ?>
      </tr>
    </table>  
<!-- <select class="form-control select2bs4" style="width: 100%;">
  <option>Alabama</option>
  <option>Alaska</option>
  <option>California</option>
  <option>Delaware</option>
  <option>Tennessee</option>
  <option>Texas</option>
  <option>Washington</option>
</select>
<script type="text/javascript" src="< ?=$path;?>plugins/select2/js/script.js"></script> -->

</body>

</html>
<style>
  .row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -7.5px;
    margin-left: -7.5px;
  }

  .col-md-6 {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%
  }
</style>