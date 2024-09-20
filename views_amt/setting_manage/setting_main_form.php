<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	$SESSION_AREA = $_SESSION["AD_AREA"];
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$mn_id=$_GET["id"];
	$ST_AREA = $_GET["area"];

	if($proc=="edit"){
		$stmt = "SELECT * FROM SETTING WHERE ST_CODE = ? AND NOT ST_STATUS='D' AND ST_AREA = '$SESSION_AREA'";
		$params = array($mn_id);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_edit_setting = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $ST_CODE=$result_edit_setting["ST_CODE"];
	};
	if($proc=="add"){
		$stmt = "SELECT TOP 1 ST_ID FROM SETTING WHERE NOT ST_STATUS='D' AND ST_AREA = '$SESSION_AREA' ORDER BY ST_ID DESC";
		$query = sqlsrv_query( $conn, $stmt);	
		$result_add_setting = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $ST_ID=$result_add_setting["ST_ID"];
    $CALST_ID=$ST_ID+1;
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
	$rand="ST_".RandNum($n);
?>
<html>
  
<head>
  <script type="text/javascript">
    function save_data() {
      // if(!confirm("ยืนยันการแก้ไข")) return false;
      var buttonname = $('#buttonname').val();
      var url = "<?=$path?>views_amt/setting_manage/setting_main_proc.php";
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
            loadViewdetail('<?=$path?>views_amt/setting_manage/setting_main.php');
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
                        <td width="24" valign="middle" class=""><img src="../images/Process-Info-icon32.png" width="32" height="32"></td>
                        <td valign="bottom" class="">
                          <h4>&nbsp;&nbsp;แก้ไขรายการ</h4>
                        </td>
                      <?php }else{ ?>
                        <td width="24" valign="middle" class=""><img src="../images/plus-icon32.png" width="32" height="32"></td>
                        <td valign="bottom" class="">
                          <h4>&nbsp;&nbsp;เพิ่มรายการใหม่</h4>
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
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>ประเภท :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <select class="time" onFocus="$(this).select();" style="width: 100%;" name="ST_TYPE"
                                id="ST_TYPE" required>
                                <option value disabled selected>-------โปรดเลือก-------</option>
                                <option value="27" <?php if($result_edit_setting['ST_TYPE']== "27"){echo "selected";} ?>>LineNotify แจ้งซ่อม</option>
                                <option value="28" <?php if($result_edit_setting['ST_TYPE']== "28"){echo "selected";} ?>>LineNotify ตรวจสอบแจ้งซ่อม</option>
                                <option value="29" <?php if($result_edit_setting['ST_TYPE']== "29"){echo "selected";} ?>>LineNotify จ่ายงาน</option>
                                <option value="30" <?php if($result_edit_setting['ST_TYPE']== "30"){echo "selected";} ?>>LineNotify ปิดงาน</option>
                                <option value="31" <?php if($result_edit_setting['ST_TYPE']== "31"){echo "selected";} ?>>LineNotify เตือนล่วงหน้า</option>
                                <option value="32" <?php if($result_edit_setting['ST_TYPE']== "32"){echo "selected";} ?>>LineNotify เข้าซ่อมล่าช้า</option>
                              </select>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" id="22" height="25px">
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>ชื่อรายการ :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="ST_NAME" id="ST_NAME" class="time" onFocus="$(this).select();" value="<?=$result_edit_setting["ST_NAME"];?>" required>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>รายละเอียด / TOKEN :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" class="time" name="ST_DETAIL" id="ST_DETAIL" value="<?=$result_edit_setting["ST_DETAIL"];?>" required />
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>ตั้งเวลา  :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" class="time" name="ST_TIME" id="ST_TIME" value="<?=$result_edit_setting["ST_TIME"];?>" onKeyUp="handleEnter(this, event, 0);" onBlur="$(this).val()" onFocus="onfocus_format(this);" required />
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td align="right" class="ui-state-default"><strong>เลือกพื้นที่  :</strong></td>
                          <td align="left" class="bg-white">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="container">
                              <input type="radio" name="area" id="area" value="AMT" <?php if($result_edit_setting["ST_AREA"]=="AMT"){echo "checked";}?>>
                              <span class="checkmark">AMT</span>
                            </label>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="container">
                              <input type="radio" name="area" id="area" value="GW" <?php if($result_edit_setting["ST_AREA"]=="GW"){echo "checked";}?>>
                              <span class="checkmark">GW</span>
                            </label>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>สถานะ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <select class="time" onFocus="$(this).select();" style="width: 100%;" name="ST_STATUS"
                                id="ST_STATUS" required>
                                <option value disabled selected>-------โปรดเลือก-------</option>
                                <option value="Y" <?php if($result_edit_setting['ST_STATUS']== "Y"){echo "selected";} ?>>เปิดใช้งาน</option>
                                <option value="N" <?php if($result_edit_setting['ST_STATUS']== "N"){echo "selected";} ?>>ปิดใช้งาน</option>
                              </select>
                            </div>
                          </td>
                        </tr>
                        <input type="hidden" name="ST_ID" id="ST_ID" value="<?=$result_edit_setting['ST_ID'];?>" />
                        <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
                        <input type="hidden" name="mn_id" id="mn_id" value="<?=$mn_id; ?>">
                        <input type="hidden" name="ST_GROUP" id="ST_GROUP" value="<?=$result_edit_setting['ST_GROUP']; ?>">
                        <tr align="center" height="25px">
                          <td height="35" colspan="2" align="center">
                            <?php if($proc=="edit"){ ?>
                              <input type="hidden" name="mn_code" id="mn_code" value="<?=$result_edit_setting['ST_CODE']; ?>">
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onClick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                            <?php }else{ ?>
                              <input type="hidden" name="mn_code" id="mn_code" value="<?=$rand; ?>">
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