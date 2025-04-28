<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$chrp_code=$_GET["id"];

	if($proc=="edit"){
		$stmt = "SELECT * FROM CHECKLISTREPAIR WHERE CLRP_CODE = ? AND NOT CLRP_STATUS='D'";
		$params = array($chrp_code);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_edit_checklist = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $CLRP_CODE=$result_edit_checklist["CLRP_CODE"];
	};
	if($proc=="add"){
		$stmt = "SELECT TOP 1 CLRP_ID FROM CHECKLISTREPAIR WHERE NOT CLRP_STATUS='D' ORDER BY CLRP_ID DESC";
		$query = sqlsrv_query( $conn, $stmt);	
		$result_add_chrp = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $CLRP_ID=$result_add_chrp["CLRP_ID"];
    $CALCLRP_ID=$CLRP_ID+1;
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
	$rand="CLRP_".RandNum($n);  
?>
<html>
  
<head>
  <script type="text/javascript">
    function save_data() {
      // if(!confirm("ยืนยันการแก้ไข")) return false;
      var buttonname = $('#buttonname').val();
      var url = "<?=$path?>views_amt/checklist_manage/checklist_main_proc.php";
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
              log_transac_checklist('LA17', '-', '<?=$rand;?>');
            }else{
              log_transac_checklist('LA18', '-', '<?=$CLRP_CODE;?>');
            }
            loadViewdetail('<?=$path?>views_amt/checklist_manage/checklist_main.php');
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
                        <h4>&nbsp;&nbsp;แก้ไขรายการตรวจสอบ</h4>
                      </td>
                      <?php }else{ ?>
                      <td width="24" valign="middle" class=""><img src="../images/plus-icon32.png" width="32" height="32">
                      </td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;เพิ่มรายการตรวจสอบ</h4>
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
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>ลำดับรายการ :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CLRP_NUM" id="CLRP_NUM" class="time" placeholder="กรอกลำดับรายการ" value="<?=$result_edit_checklist["CLRP_NUM"];?>"  onKeyUp="handleEnter(this, event, 0);" onBlur="$(this).val(number_format_num($(this).val(),0))" onFocus="onfocus_format(this);"/>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" id="22" height="25px">
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>ชื่อรายการตรวจสอบ :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CLRP_NAME" id="CLRP_NAME" class="time" placeholder="กรอกชื่อรายการตรวจสอบ" value="<?=$result_edit_checklist["CLRP_NAME"];?>"/>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" id="22" height="25px">
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>RANK PM :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CLRP_RANK" id="CLRP_RANK" class="time" placeholder="RANK PM" value="<?=$result_edit_checklist["CLRP_RANK"];?>"/>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" id="22" height="25px">
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>CHECK :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CLRP_CHECK" id="CLRP_CHECK" class="time" placeholder="CLRP_CHECK" value="<?=$result_edit_checklist["CLRP_CHECK"];?>"/>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" id="22" height="25px">
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>รุ่นรถ :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CLRP_CARTYPE" id="CLRP_CARTYPE" class="time" placeholder="รุ่นรถ" value="<?=$result_edit_checklist["CLRP_CARTYPE"];?>"/>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" id="22" height="25px">
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>ลักษณะงานซ่อม :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CLRP_REMARK" id="CLRP_REMARK" class="time" placeholder="ลักษณะงานซ่อม" value="<?=$result_edit_checklist["CLRP_REMARK"];?>"/>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>สถานะรายการตรวจสอบ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                                <select class="time" onFocus="$(this).select();" style="width: 100%;" name="CLRP_STATUS" id="CLRP_STATUS" required>
                                    <option value disabled selected>-------โปรดเลือก-------</option>
                                    <option value="Y" <?php if($result_edit_checklist['CLRP_STATUS']== "Y"){echo "selected";} ?>>เปิดใช้งาน</option>
                                    <option value="N" <?php if($result_edit_checklist['CLRP_STATUS']== "N"){echo "selected";} ?>>ปิดใช้งาน</option>
                                </select>
                            </div>
                          </td>
                        </tr>                      
                        <input type="hidden" name="CLRP_ID" id="CLRP_ID" value="<?=$result_edit_checklist['CLRP_ID'];?>" />
                        <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
                        <input type="hidden" name="chrp_code" id="chrp_code" value="<?=$chrp_code; ?>">
                        <tr align="center" height="25px">
                          <td height="35" colspan="2" align="center">
                            <?php if($proc=="edit"){ ?>
                              <input type="hidden" name="CLRP_CODE" id="CLRP_CODE" value="<?=$result_edit_checklist['CLRP_CODE'];?>">
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onClick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                            <?php }else{ ?>
                              <input type="hidden" name="CLRP_CODE" id="CLRP_CODE" value="<?=$rand; ?>">
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
                    $params = array($CLRP_CODE);	
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