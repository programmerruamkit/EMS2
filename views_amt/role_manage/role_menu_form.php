<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
  
	$GET_RM_ID=$_GET['rm_id'];
	
	$RU_AREA = $_GET["area"];	
  
	$SESSION_AREA=$_SESSION["AD_AREA"];

	$proc=$_GET["proc"];
	$rm_id=$_GET["id"];
	if($proc=="edit"){
		$stmt = "SELECT RM.RM_ID, RM.RU_ID, RM.MN_ID, RM.AREA, RM.RM_STATUS, RM.RM_CODE, MN.MN_CODE, MN.MN_NAME, MN.MN_STATUS
      FROM dbo.ROLE_MENU AS RM LEFT JOIN dbo.MENU AS MN ON MN.MN_ID = RM.MN_ID
      WHERE RM_CODE = ? ";
		$params = array($rm_id);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_edit_menu = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $RM_CODE=$result_edit_menu["RM_CODE"];
	};
	if($proc=="add"){
		$stmt = "SELECT TOP 1 RM_ID FROM ROLE_MENU WHERE NOT RM_STATUS='D' ORDER BY RM_ID DESC";
		$query = sqlsrv_query( $conn, $stmt);	
		$result_add_menu = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $RM_ID=$result_add_menu["RM_ID"];
    $CALRM_ID=$RM_ID+1;
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
	$rand="RM_".RandNum(6);
  
  function autocomplete_menu($CONDI) {
  // function autocomplete_menu($KEYWORD, $CONDI) {
	$path = "../";   	
    require($path.'../include/connect.php');
    $data = "";
    // $sql = "SELECT * FROM MENU WHERE MN_LEVEL='$KEYWORD' $CONDI";
    $sql = "SELECT * FROM MENU WHERE $CONDI";
    $query = sqlsrv_query($conn, $sql);
    while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $data .= "'" . $result['MN_NAME'] . "',";
    }
    return rtrim($data, ",");
  }
  $atcp_menu = autocomplete_menu("NOT MN_STATUS='D' AND MN_AREA = '$SESSION_AREA'");
  // $atcp_menu = autocomplete_menu('2', "AND NOT MN_STATUS='D'");
  // echo $atcp_menu;
?>
<html>
<head>
<script type="text/javascript">
	function save_data(){
		//if(!confirm("ยืนยันการบันทึก")) return false;
    var buttonname = $('#buttonname').val();
		var url = "<?=$path?>views_amt/role_manage/role_menu_proc.php"; 
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
              log_transac_role('LA17', '-', '<?=$rand;?>');
            }else{
              log_transac_role('LA18', '<?=$result_edit_menu["RU_NAME"];?>', '<?=$result_edit_menu["RM_CODE"];?>');
            }
				  	loadViewdetail('<?=$path?>views_amt/role_manage/role_menu.php?rm_id=<?php print $GET_RM_ID; ?>&area=<?=$RU_AREA;?>');
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
                        <td valign="bottom" class=""><h4>&nbsp;&nbsp;แก้ไขสิทธิ์เมนู</h4></td>
                      <?php }else{ ?>
                        <td width="24" valign="middle" class=""><img src="../images/plus-icon32.png" width="32" height="32"></td>
                        <td valign="bottom" class=""><h4>&nbsp;&nbsp;เพิ่มสิทธิ์เมนูใหม่</h4></td>
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
                        <tr align="center" id="22" height="25px">
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>ค้นหาเมนู  :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="RM_NAME" id="RM_NAME" class="time" placeholder="กรอกข้อมูลลงในช่อง" onFocus="$(this).select();" value="<?=$result_edit_menu["MN_NAME"];?>" />
                            </div>
                            <div id="result" class="autocomplete" ></div>
                          </td>
                        </tr>
                      </tr>
                      <tr align="center" height="25px">
                        <td height="35" align="right" class="ui-state-default"><strong>สถานะเมนู :</strong></td>
                        <td height="35" align="left" class="bg-white">
                          <div class="input-control text">
                              <select class="time" onFocus="$(this).select();" style="width: 100%;" name="RM_STATUS" id="RM_STATUS" required>
                                  <option value disabled selected>-------โปรดเลือก-------</option>
                                  <option value="Y" <?php if($result_edit_menu['RM_STATUS']== "Y"){echo "selected";} ?>>เปิดใช้งาน</option>
                                  <option value="N" <?php if($result_edit_menu['RM_STATUS']== "N"){echo "selected";} ?>>ปิดใช้งาน</option>
                              </select>
                          </div>
                        </td>
                      </tr>                      

                      <input type="hidden" name="RM_ID" id="RM_ID" value="<?=$result_edit_menu['RM_ID'];?>" />
                      <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
                      <input type="hidden" name="rm_id" id="rm_id" value="<?=$rm_id; ?>">
                      <input type="hidden" name="RU_ID" id="RU_ID" value="<?=$GET_RM_ID; ?>">
                      <tr align="center" height="25px">
                          <td height="35" colspan="2" align="center" >
                            <?php if($proc=="edit"){ ?>
                              <input type="hidden" name="rm_code" id="rm_code" value="<?=$result_edit_menu['RM_CODE']; ?>">
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onClick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                            <?php }else{ ?>
                              <input type="hidden" name="area" id="area" value="<?=$RU_AREA;?>" />
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
                    $params = array($RM_CODE);	
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
  <script type="text/javascript">
    $(document).ready(function (e) {
      var source = [<?= $atcp_menu ?>];
      AutoCompleteNormal("RM_NAME", source);      
    });
  </script>
</body>
</html>