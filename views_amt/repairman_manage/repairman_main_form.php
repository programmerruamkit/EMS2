<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$rpm_code=$_GET["id"];
	$MN_AREA = $_GET["area"];

	if($proc=="edit"){
		$stmt = "SELECT * FROM REPAIR_MAN WHERE RPM_CODE = ? AND NOT RPM_STATUS='D'";
		$params = array($rpm_code);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_edit_repairman = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $RPM_CODE=$result_edit_repairman["RPM_CODE"];
    $RPM_PERSONCODE=$result_edit_repairman["RPM_PERSONCODE"];
    $RPM_PERSONNAME=$result_edit_repairman["RPM_PERSONNAME"];

	};
	if($proc=="add2"){
		$stmt = "SELECT * FROM vwEMPLOYEE WHERE ID = ?";
		$params = array($rpm_code);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_edit_repairman = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $RPM_PERSONCODE=$result_edit_repairman["PersonCode"];
    $RPM_PERSONNAME=$result_edit_repairman["nameT"];
	}; 
	if($proc=="add"){
		$stmt = "SELECT TOP 1 RPM_ID FROM REPAIR_MAN WHERE NOT RPM_STATUS='D' ORDER BY RPM_ID DESC";
		$query = sqlsrv_query( $conn, $stmt);	
		$result_add_menu = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $RPM_ID=$result_add_menu["RPM_ID"];
    $CALRPM_ID=$RPM_ID+1;
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
	$rand="RPM_".RandNum($n);  

  function autocomplete_menu($CONDI) {
  // function autocomplete_menu($KEYWORD, $CONDI) {
    $path = "../";   	
    require($path.'../include/connect.php');
    $data = "";
    // $sql = "SELECT * FROM MENU WHERE MN_LEVEL='$KEYWORD' $CONDI";
    // $sql = "SELECT * FROM vwEMPLOYEE WHERE $CONDI";
    $sql = "SELECT * FROM vwEMPLOYEE  LEFT JOIN (SELECT * FROM REPAIR_MAN WHERE NOT RPM_STATUS='D') REPAIR_MAN ON PersonCode = RPM_PERSONCODE COLLATE THAI_CI_AS
    WHERE $CONDI
    UNION ALL
    SELECT * FROM vwEMPLOYEE LEFT JOIN (SELECT * FROM REPAIR_MAN WHERE NOT RPM_STATUS='D') REPAIR_MAN ON PersonCode = RPM_PERSONCODE COLLATE THAI_CI_AS
    WHERE PersonCode IN('060236','060274','060247','060243','050058','100012','100009')";
    $query = sqlsrv_query($conn, $sql);
    while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $data .= "'".$result['PersonCode'].' - '.$result['nameT']."',";
    }
    return rtrim($data, ",");
  }
  $atcp_repairman = autocomplete_menu("PositionNameT LIKE '%ช่าง%'");
  // $atcp_repairman = autocomplete_menu('2', "AND NOT RPM_STATUS='D'");
  // echo $atcp_repairman;
?>
<html>
  
<head>
  <script type="text/javascript">
    function save_data() {
      // if(!confirm("ยืนยันการแก้ไข")) return false;
      var buttonname = $('#buttonname').val();
      var url = "<?=$path?>views_amt/repairman_manage/repairman_main_proc.php";
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
              log_transac_repairman('LA17', '-', '<?=$rand;?>');
            }else{
              log_transac_repairman('LA18', '-', '<?=$RPM_CODE;?>');
            }
            loadViewdetail('<?=$path?>views_amt/repairman_manage/repairman_main.php');
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
                      <?php if(($proc=="edit")||($proc=="add2")){ ?>
                      <td width="24" valign="middle" class=""><img src="../images/Process-Info-icon32.png" width="32"
                          height="32"></td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;แก้ไขช่าง</h4>
                      </td>
                      <?php }else{ ?>
                      <td width="24" valign="middle" class=""><img src="../images/plus-icon32.png" width="32" height="32">
                      </td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;เพิ่มช่าง</h4>
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
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>เลือกชื่อช่าง :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <?php if(($proc=="edit")||($proc=="add2")){ ?>
                                <input type="text" name="RPM_NAME" id="RPM_NAME" class="time" placeholder="กรอกข้อมูลลงในช่อง" onFocus="$(this).select();" value="<?=$RPM_PERSONCODE.' - '.$RPM_PERSONNAME;?>" readonly/>
                              <?php }else{ ?>
                                <input type="text" name="RPM_NAME" id="RPM_NAME" class="time" placeholder="กรอกข้อมูลลงในช่อง" onFocus="$(this).select();" value="" />
                              <?php } ?>
                            </div>
                            <div id="result" class="autocomplete" ></div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td align="right" class="ui-state-default"><strong>เลือกพื้นที่  :</strong></td>
                          <td align="left" class="bg-white">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="container">
                              <input type="radio" name="area" id="area" value="AMT" <?php if($result_edit_repairman['RPM_AREA']== "AMT"){echo "checked";} ?>>
                              <span class="checkmark">AMT</span>
                            </label>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="container">
                              <input type="radio" name="area" id="area" value="GW" <?php if($result_edit_repairman['RPM_AREA']== "GW"){echo "checked";} ?>>
                              <span class="checkmark">GW</span>
                            </label>
                          </td>
                        </tr>
                        <input type="hidden" name="RPM_NATUREREPAIR" id="RPM_NATUREREPAIR" value="">
                        <!-- <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>เลือกลักษณะงานซ่อม :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <select class="time" onFocus="$(this).select();" style="width: 100%;" name="RPM_NATUREREPAIR" id="RPM_NATUREREPAIR" required>
                                <option value disabled selected>-------โปรดเลือก-------</option>
                                <option value="EL" <?php if($result_edit_repairman['RPM_NATUREREPAIR']== "EL"){echo "selected";} ?>>ระบบไฟ</option>
                                <option value="TU" <?php if($result_edit_repairman['RPM_NATUREREPAIR']== "TU"){echo "selected";} ?>>ยาง ช่วงล่าง</option>
                                <option value="BD" <?php if($result_edit_repairman['RPM_NATUREREPAIR']== "BD"){echo "selected";} ?>>โครงสร้าง</option>
                                <option value="EG" <?php if($result_edit_repairman['RPM_NATUREREPAIR']== "EG"){echo "selected";} ?>>เครื่องยนต์</option>
                                <option value="AC" <?php if($result_edit_repairman['RPM_NATUREREPAIR']== "AC"){echo "selected";} ?>>อุปกรณ์ประจำรถ</option>
                              </select>
                            </div>
                          </td>
                        </tr> -->
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>ทักษะความสามารถ :</strong></td>
                          <td height="35" align="left" class="bg-white">  
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                              <tbody>
                                <tr align="left" height="25px">
                                  <td width="30%">
                                    <label class="input-control checkbox">
                                      <input type="checkbox" name="EL" id="EL" value="1" <?php if($result_edit_repairman['RPM_SKILL_EL']== "1"){echo "checked";} ?>><span class="helper">ระบบไฟ</span>
                                    </label> 
                                  </td>
                                  <td width="30%">
                                    <label class="input-control checkbox">
                                      <input type="checkbox" name="TU" id="TU" value="1" <?php if($result_edit_repairman['RPM_SKILL_TU']== "1"){echo "checked";} ?>><span class="helper">ยาง ช่วงล่าง</span>
                                    </label>
                                  </td>
                                  <td width="30%">
                                    <label class="input-control checkbox">
                                      <input type="checkbox" name="BD" id="BD" value="1" <?php if($result_edit_repairman['RPM_SKILL_BD']== "1"){echo "checked";} ?>><span class="helper">โครงสร้าง</span>
                                    </label>
                                  </td>
                                </tr>
                                <tr align="left" height="25px">
                                  <td width="30%">
                                    <label class="input-control checkbox">
                                      <input type="checkbox" name="EG" id="EG" value="1" <?php if($result_edit_repairman['RPM_SKILL_EG']== "1"){echo "checked";} ?>><span class="helper">เครื่องยนต์</span>
                                    </label>
                                  </td>
                                  <td>
                                    <label class="input-control checkbox">
                                      <input type="checkbox" name="AC" id="AC" value="1" <?php if($result_edit_repairman['RPM_SKILL_AC']== "1"){echo "checked";} ?>><span class="helper">อุปกรณ์ประจำรถ</span>
                                    </label>   
                                  </td>
                                </tr>
                              </tbody>
                            </table>        
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>ชม.มาตรฐานการซ่อม :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="RPM_HOURSSTANDARD" id="RPM_HOURSSTANDARD" class="time" value="<?=$result_edit_repairman["RPM_HOURSSTANDARD"];?>" onKeyUp="handleEnter(this, event, 0);" onBlur="$(this).val(number_format_num($(this).val(),0))" onFocus="onfocus_format(this);" >
                            </div>
                          </td>
                        </tr>
                        <input type="hidden" name="RPM_ID" id="RPM_ID" value="<?=$result_edit_repairman['RPM_ID'];?>" />
                        <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
                        <input type="hidden" name="rpm_code" id="rpm_code" value="<?=$rpm_code; ?>">
                        <tr align="center" height="25px">
                          <td height="35" colspan="2" align="center">
                            <?php if($proc=="edit"){ ?>
                              <input type="hidden" name="RPM_CODE" id="RPM_CODE" value="<?=$result_edit_repairman["RPM_CODE"];?>">
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onClick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                              <?php }else if($proc=="add2"){ ?>
                              <input type="hidden" name="RPM_CODE" id="RPM_CODE" value="<?=$rand; ?>">
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="add" onClick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                            <?php }else{ ?>
                              <input type="hidden" name="RPM_CODE" id="RPM_CODE" value="<?=$rand; ?>">
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
                    $params = array($RPM_CODE);	
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

<script type="text/javascript">
  $(document).ready(function (e) {
    var source = [<?= $atcp_repairman ?>];
    AutoCompleteNormal("RPM_NAME", source);      
  });
</script>
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