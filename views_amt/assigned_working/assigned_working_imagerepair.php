<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$RPRQ_CODE=$_GET["id"];
	$SUBJECT=$_GET["subject"];
	$TYPE=$_GET["type"];
?>
<html>
  
<head>
  <script type="text/javascript">
    function save_data() {
        // data: $("#form_project_detail").serialize(),
      var formData = new FormData($("#form_project_detail")[0]);           
      var file_data = $('#fileToUpload')[0].files;
      formData.append('file',file_data[0]);
      var url = "<?=$path?>views_amt/assigned_working/assigned_working_proc.php";
      $.ajax({
        type: "POST",
        url: url,
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: data,
            showConfirmButton: false,
            timer: 2000
          }).then((result) => {
            ajaxPopup4('<?=$path?>views_amt/assigned_working/assigned_working_imagerepair.php','edit','<?php print $RPRQ_CODE;?>&subject=<?php print $SUBJECT;?>&type=IMAGE','1=1','1300','330','เพิ่มรูปภาพ')
          })
        }
      });
    }
  </script>
  <style>
    input[type=file] {
      width: 100%;
      max-width: 100%;
      color: #444;
      padding: 5px;
      background: #fff;
      border-radius: 0px;
      border: 1px solid #555;
    }
  </style>
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
                      <td width="24" valign="middle" class=""><img src="../images/plus-icon32.png" width="32" height="32">
                      </td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;เพิ่มรูปภาพ</h4>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="RIGHT"></td>
              </tr>
              <tr class="CENTER">
                <td class="LEFT"></td>
                <td class="CENTER" align="center">
                  <form action="#" method="post" enctype="multipart/form-data" name="form_project_detail" id="form_project_detail">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                      <tbody>   
                        <tr align="center" id="22" height="25px">
                          <td width="20%" height="35" align="right" class="ui-state-default"><strong>กลุ่มรูปภาพ :</strong></td>
                          <td width="80%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                                <select class="time" onFocus="$(this).select();" style="width: 100%;" name="RPATIM_GROUP" id="RPATIM_GROUP">
                                    <option value disabled selected>----โปรดเลือก---</option>
                                    <option value="DURING" <?php if($RPATCL_TYPE== "DURING"){echo "selected";} ?>>ระหว่างซ่อม</option>
                                    <option value="AFTER" <?php if($RPATCL_TYPE== "AFTER"){echo "selected";} ?>>หลังซ่อม</option>
                                </select>
                            </div>
                          </td>
                        </tr>  
                        <tr align="center" id="22" height="25px">
                          <td width="20%" height="35" align="right" class="ui-state-default"><strong>รูปภาพ :</strong></td>
                          <td width="80%" height="35" align="left" class="bg-white">
                            <input type="file" name="fileToUpload[]" id="fileToUpload" class="form-control-file" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif" multiple/>
                          </td>
                        </tr>          
                        <input type="hidden" name="target" id="target" value="imagerepair" />
                        <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
                        <input type="hidden" name="RPRQ_CODE" id="RPRQ_CODE" value="<?=$RPRQ_CODE; ?>">
                        <input type="hidden" name="SUBJECT" id="SUBJECT" value="<?=$SUBJECT; ?>">
                        <tr align="center" height="25px">
                          <td height="35" colspan="2" align="center">
                            <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onclick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
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
                  <h5><img src="../images/task.png" width="32" height="32"> ประวัติการบันทึกข้อมูล</h5>
                </div>   
                <table width="100%" class="table table-hover table-striped">
                  <thead>
                    <tr height="35" align="center">
                      <th width="5%" align="center"><strong>ลำดับ</strong></th>
                      <th width="20%" align="center"><strong>กลุ่มรูปภาพ</strong></th>
                      <th width="75%" align="center"><strong>รูปภาพ</strong></th>
                      <!-- <th width="20%" align="left"><strong>ผู้บันทึก</strong></th> -->
                    </tr>
                  </thead>   
                    <tr height="25">
                      <td align="center"><font size="2">1.</font></td>
                      <td><font size="2">ระหว่างซ่อม</font></td>  
                      <td>
                        <font size="2">          
                          <?php
                            $sql_during = "SELECT * FROM vwREPAIRACTUALIMAGE WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ? AND RPATIM_GROUP = 'DURING' ORDER BY RPATIM_GROUP ASC";
                            $params_during = array($RPRQ_CODE,$SUBJECT,$TYPE);	
                            $query_during = sqlsrv_query($conn, $sql_during, $params_during);	
                            $no=0;
                            while($result_during = sqlsrv_fetch_array($query_during, SQLSRV_FETCH_ASSOC)){	
                              $no++;
                              $RPATIMGROUP_DURING=$result_during['RPATIM_GROUP'];
                              $RPC_SUBJECT_DURING=$result_during['RPC_SUBJECT'];
                              $WORKINGBY_DURING=$result_during['WORKINGBY'];
                              $RPATIM_IMAGES_DURING=$result_during['RPATIM_IMAGES'];
                              $DATETIME1038_DURING=$result_during['DATETIME1038'];
                              
                              switch($result_during['RPATIM_GROUP']) {
                                  case "DURING":
                                    $RPATIM_GROUP_DURING="ระหว่างซ่อม";
                                  break;
                                  case "AFTER":
                                    $RPATIM_GROUP_DURING="หลังซ่อม";
                                  break;
                                }
                          ?>
                            <img src="<?=$path?>uploads/requestrepair/<?=$RPATIM_IMAGES_DURING;?>" width="80px" height="80px" style="cursor:pointer;border:5px solid #555;" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assigned_working/assigned_working_imagerepairview.php','edit','<?=$RPRQ_CODE;?>&subject=<?=$RPC_SUBJECT_DURING;?>&groub=<?=$RPATIMGROUP_DURING;?>&image=<?=$RPATIM_IMAGES_DURING?>','1=1','1000','700','กลุ่มรูปภาพ <?=$RPATIM_GROUP_DURING?>');">
                          <?php }; ?>
                        </font>
                      </td>       
                    </tr>  
                    <tr height="25">
                      <td align="center"><font size="2">2.</font></td>
                      <td><font size="2">หลังซ่อม</font></td>  
                      <td>
                        <font size="2">          
                          <?php
                            $sql_after = "SELECT * FROM vwREPAIRACTUALIMAGE WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ? AND RPATIM_GROUP = 'AFTER' ORDER BY RPATIM_GROUP ASC";
                            $params_after = array($RPRQ_CODE,$SUBJECT,$TYPE);	
                            $query_after = sqlsrv_query($conn, $sql_after, $params_after);	
                            $no=0;
                            while($result_after = sqlsrv_fetch_array($query_after, SQLSRV_FETCH_ASSOC)){	
                              $no++;
                              $RPATIMGROUP_AFTER=$result_after['RPATIM_GROUP'];
                              $RPC_SUBJECT_AFTER=$result_after['RPC_SUBJECT'];
                              $WORKINGBY_AFTER=$result_after['WORKINGBY'];
                              $RPATIM_IMAGES_AFTER=$result_after['RPATIM_IMAGES'];
                              $DATETIME1038_AFTER=$result_after['DATETIME1038'];
                              
                              switch($result_after['RPATIM_GROUP']) {
                                  case "DURING":
                                    $RPATIM_GROUP_AFTER="ระหว่างซ่อม";
                                  break;
                                  case "AFTER":
                                    $RPATIM_GROUP_AFTER="หลังซ่อม";
                                  break;
                                }
                          ?>
                            <img src="<?=$path?>uploads/requestrepair/<?=$RPATIM_IMAGES_AFTER;?>" width="80px" height="80px" style="cursor:pointer;border:5px solid #555;" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assigned_working/assigned_working_imagerepairview.php','edit','<?=$RPRQ_CODE;?>&subject=<?=$RPC_SUBJECT_AFTER;?>&groub=<?=$RPATIMGROUP_AFTER;?>&image=<?=$RPATIM_IMAGES_AFTER?>','1=1','1000','700','กลุ่มรูปภาพ <?=$RPATIM_GROUP_AFTER?>');">
                          <?php }; ?>
                        </font>
                      </td>       
                    </tr>  
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