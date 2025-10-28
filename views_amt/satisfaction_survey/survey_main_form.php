<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();

	$SESSION_AREA = $_SESSION["AD_AREA"];
	$SESSION_PERSONCODE = $_SESSION["AD_PERSONCODE"];
  
	$proc = $_GET["proc"];
	$sm_code = $_GET["id"];

	if($proc == "edit"){
		$stmt = "SELECT * FROM SURVEY_MAIN WHERE SM_CODE = ? AND NOT SM_STATUS='D' AND SM_AREA = ?";
		$params = array($sm_code, $SESSION_AREA);	
		$query = sqlsrv_query($conn, $stmt, $params);	
		$result_edit = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
		$SM_CODE = $result_edit["SM_CODE"];
		$SM_NAME = $result_edit["SM_NAME"];
		$SM_DESCRIPTION = $result_edit["SM_DESCRIPTION"];
		$SM_TARGET_GROUP = $result_edit["SM_TARGET_GROUP"];
    $SM_AREA = $result_edit["SM_AREA"];
    $SM_STATUS = $result_edit["SM_STATUS"];

	}
	
	if($proc == "add"){
		$stmt = "SELECT TOP 1 SM_ID FROM SURVEY_MAIN WHERE NOT SM_STATUS='D' AND SM_AREA = ? ORDER BY SM_ID DESC";
		$params = array($SESSION_AREA);
		$query = sqlsrv_query($conn, $stmt, $params);	
		$result_add = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
		$SM_ID = $result_add["SM_ID"] ? $result_add["SM_ID"] : 0;
		$CAL_SM_ID = $SM_ID + 1;
	}
	// Generate random code
	$n = 6;
	function RandNum($n) {
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';      
		for ($i = 0; $i < $n; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}      
		return $randomString;
	}  
	$rand = "SM_" . RandNum($n);
  
?>
<html>
  
<head>
  <script type="text/javascript">
    function save_data() {
      var buttonname = $('#buttonname').val();
      var url = "<?=$path?>views_amt/satisfaction_survey/survey_main_proc.php";
      $.ajax({
        type: "POST",
        url: url,
        data: $("#form_survey").serialize(),
        success: function (data) {
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: data,
            showConfirmButton: false,
            timer: 2000
          }).then((result) => {
            if(buttonname=='add'){
              log_transac_menu('LA17', '-', '<?=$rand;?>');
            }else{
              log_transac_menu('LA18', '<?=$SM_NAME;?>', '<?=$SM_CODE;?>');
            }
            loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_main.php');
            closeUI();
          });
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
                        <td width="24" valign="middle" class=""><img src="../images/Process-Info-icon32.png" width="32" height="32"></td>
                        <td valign="bottom" class="">
                          <h4>&nbsp;&nbsp;แก้ไขกลุ่มแบบประเมิน</h4>
                        </td>
                      <?php }else{ ?>
                        <td width="24" valign="middle" class=""><img src="../images/plus-icon32.png" width="32" height="32"></td>
                        <td valign="bottom" class="">
                          <h4>&nbsp;&nbsp;เพิ่มกลุ่มแบบประเมิน</h4>
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
                  <form action="#" method="post" enctype="multipart/form-data" name="form_survey" id="form_survey">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                      <tbody>
                        <tr align="center" id="22" height="25px" hidden>
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>รหัสกลุ่มแบบประเมิน :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <?php if($proc == "edit") { ?>
                                <input type="text" name="sm_code" value="<?=$SM_CODE?>" readonly class="time" onFocus="$(this).select();">
                              <?php } else { ?>
                                <input type="text" name="sm_code" value="<?=$rand?>" readonly class="time" onFocus="$(this).select();">
                              <?php } ?>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>ชื่อกลุ่มแบบประเมิน :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" class="time" name="sm_name" value="<?=$SM_NAME?>" placeholder="เช่น แบบประเมินผู้นำรถเข้าซ่อม" required autocomplete="off" />
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>กลุ่มเป้าหมาย :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <select class="time" onFocus="$(this).select();" style="width: 100%;" name="sm_target_group" required>
                                <option value disabled selected>-------เลือกกลุ่มเป้าหมาย-------</option>
                                <option value="ผู้นำรถเข้าใช้บริการ" <?=($SM_TARGET_GROUP == 'ผู้นำรถเข้าใช้บริการ') ? 'selected' : ''?>>ผู้นำรถเข้าใช้บริการ</option>
                                <option value="ผู้ใช้รถ" <?=($SM_TARGET_GROUP == 'ผู้ใช้รถ') ? 'selected' : ''?>>ผู้ใช้รถ</option>
                              </select>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px" hidden>
                          <td height="35" align="right" class="ui-state-default"><strong>คำอธิบาย :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <textarea name="sm_description" class="time" rows="3" placeholder="อธิบายรายละเอียดของแบบประเมิน"><?=$SM_DESCRIPTION?></textarea>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>สถานะ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <select class="time" onFocus="$(this).select();" style="width: 100%;" name="sm_status"
                                id="sm_status" required>
                                <option value disabled selected>-------โปรดเลือก-------</option>
                                <option value="Y" <?php if($SM_STATUS== "Y"){echo "selected";} ?>>เปิดใช้งาน</option>
                                <option value="N" <?php if($SM_STATUS== "N"){echo "selected";} ?>>ปิดใช้งาน</option>
                              </select>
                            </div>
                          </td>
                        </tr>
                        <input type="hidden" name="proc" value="<?=$proc?>">
                        <input type="hidden" name="buttonname" id="buttonname" value="<?=$proc?>">
                        <input type="hidden" name="session_area" value="<?=$SESSION_AREA?>">
                        <input type="hidden" name="session_personcode" value="<?=$SESSION_PERSONCODE?>">
                        <?php if($proc == "edit") { ?>
                          <input type="hidden" name="sm_code_old" value="<?=$SM_CODE?>">
                        <?php } ?>
                        <tr align="center" height="25px">
                          <td height="35" colspan="2" align="center">
                            <?php if($proc=="edit"){ ?>
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onclick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                            <?php }else{ ?>
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="add" onclick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                            <?php } ?>
                            <button class="bg-color-red font-white" type="button" onclick="closeUI()">ปิดหน้าจอ</button>
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
                    $params = array($sm_code);	
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