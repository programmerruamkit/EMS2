<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
  
	$GET_EMP_CODE=$_GET['personcode'];
	$SESSION_AREA=$_SESSION["AD_AREA"];

	$proc=$_GET["proc"];
	$ra_id=$_GET["id"];
	if($proc=="edit"){
		$stmt = "SELECT * FROM ROLE_ACCOUNT WHERE RA_ID = ? ";
		$params = array($ra_id);
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_account_edit = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $RA_CODE=$result_account_edit["RA_CODE"];
	};
	if($proc=="add"){
		$stmt = "SELECT RA_USERNAME,RA_PASSWORD FROM ROLE_ACCOUNT WHERE RA_PERSONCODE = ? ";
		$params = array($GET_EMP_CODE);
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_account = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $RA_USERNAME=$result_account["RA_USERNAME"];
    $RA_PASSWORD=$result_account["RA_PASSWORD"];
	};
	function RandNum($n) {
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';      
		for ($i = 0; $i < $n; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}      
		return $randomString;
	}  
	$rand="RA_".RandNum(6);
?>
<html>
<head>
<script type="text/javascript">
	function save_data(){
		//if(!confirm("ยืนยันการบันทึก")) return false;
    var buttonname = $('#buttonname').val();
		var url = "<?=$path?>views_amt/roleaccount_manage/roleaccount_sub_proc.php"; 
		$.ajax({ type: "POST",
      url: url,
      data: $("#form_project").serialize(),
      success: function(data){
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: data,
            showConfirmButton: false,
            timer: 2000
          }).then((result) => {
            if(buttonname=='add'){
              log_transac_roleaccount('LA17', '-', '<?=$rand;?>');
            }else{
              log_transac_roleaccount('LA18', '-', '<?=$result_account_edit["RA_CODE"];?>');
            }
            loadViewdetail('<?=$path?>views_amt/roleaccount_manage/roleaccount_sub.php?personcode=<?php print $GET_EMP_CODE; ?>');
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
                <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                      <?php if($proc=="edit"){ ?>
                        <td width="24" valign="middle" class=""><img src="../images/Process-Info-icon32.png" width="32" height="32"></td>
                        <td valign="bottom" class=""><h4>&nbsp;&nbsp;แก้ไขสิทธิ์ใช้งาน</h4></td>
                      <?php }else{ ?>
                        <td width="24" valign="middle" class=""><img src="../images/plus-icon32.png" width="32" height="32"></td>
                        <td valign="bottom" class=""><h4>&nbsp;&nbsp;เพิ่มสิทธิ์ใช้งานใหม่</h4></td>
                      <?php } ?>
                    </tr>
                </table></td>
                <td class="RIGHT"></td>
              </tr>
              <tr class="CENTER">
                <td class="LEFT"></td>
                <td class="CENTER" align="center">
                <form action="#" method="post" enctype="multipart/form-data" name="form_project" id="form_project">
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                      <tbody>
                      <tr align="center" id="22" height="25px">
                        <td width="30%" height="35" align="right" class="ui-state-default"><strong>เลือกสิทธิ์  :</strong></td>
                        <td width="70%" height="35" align="left" class="bg-white">              
                          <div class="input-control select">                    
                            <?php
                              $stmt_selrole = "SELECT * FROM ROLE_USER WHERE NOT RU_STATUS IN ('D','N') AND RU_AREA = '$SESSION_AREA' AND NOT RU_NAME = 'DEV'";
                              $query_selrole = sqlsrv_query($conn, $stmt_selrole);
                            ?>
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="RU_ID" id="RU_ID" required>
                              <option value disabled selected>-------เลือกสิทธิ์-------</option>
                              <!-- sqlsrv_fetch_assoc -->
                              <?php while($result_selrole = sqlsrv_fetch_array($query_selrole)): ?>
                              <option value="<?=$result_selrole['RU_ID']?>" <?php if($result_account_edit['RU_ID']==$result_selrole['RU_ID']){echo "selected";} ?>><?=$result_selrole['RU_AREA']?> - <?=$result_selrole['RU_NAME']?></option>
                              <?php endwhile; ?>
                            </select>
                          </div>
                        </td>
                      </tr>
                      <tr align="center" height="25px">
                        <td height="35" align="right" class="ui-state-default"><strong>สถานะสิทธิ์ :</strong></td>
                        <td height="35" align="left" class="bg-white">
                          <div class="input-control text">
                              <select class="time" onFocus="$(this).select();" style="width: 100%;" name="RA_STATUS" id="RA_STATUS" required>
                                  <option value disabled selected>-------โปรดเลือก-------</option>
                                  <option value="Y" <?php if($result_account_edit['RA_STATUS']== "Y"){echo "selected";} ?>>เปิดใช้งาน</option>
                                  <option value="N" <?php if($result_account_edit['RA_STATUS']== "N"){echo "selected";} ?>>ปิดใช้งาน</option>
                              </select>
                          </div>
                        </td>
                      </tr>
                      
                      <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
                      <input type="hidden" name="ra_id" id="ra_id" value="<?=$ra_id; ?>">
                      <input type="hidden" name="personcode" id="personcode" value="<?=$GET_EMP_CODE; ?>">
                      <input type="hidden" name="RA_USERNAME" id="RA_USERNAME" value="<?=$RA_USERNAME; ?>">
                      <input type="hidden" name="RA_PASSWORD" id="RA_PASSWORD" value="<?=$RA_PASSWORD; ?>">
                      <tr align="center" height="25px">
                          <td height="35" colspan="2" align="center" >
                            <?php if($proc=="edit"){ ?>
                              <input type="hidden" name="rm_code" id="rm_code" value="<?=$RA_CODE ?>">
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onClick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                            <?php }else{ ?>
                              <input type="hidden" name="rm_code" id="rm_code" value="<?=$rand; ?>">
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
                    $params = array($RA_CODE);	
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