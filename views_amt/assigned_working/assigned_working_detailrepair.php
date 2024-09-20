<?php
	session_start();
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
      var url = "<?=$path?>views_amt/assigned_working/assigned_working_proc.php";
      $.ajax({
        type: "POST",
        url: url,
        data: $("#form_project_detail").serialize(),
        success: function (data) {
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: data,
            showConfirmButton: false,
            timer: 2000
          }).then((result) => {
            loadViewdetail('<?=$path?>views_amt/assigned_working/assigned_working_form.php?id=<?php print $RPRQ_CODE;?>&proc=add');
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
                      <td width="24" valign="middle" class=""><img src="../images/plus-icon32.png" width="32" height="32">
                      </td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;เพิ่มข้อมูล</h4>
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
                          <td width="20%" height="35" align="right" class="ui-state-default"><strong>รายละเอียด :</strong></td>
                          <td width="80%" height="35" align="left" class="bg-white">
                            <div class="input-control textarea">
                              <textarea name="detailrepair" id="detailrepair"></textarea>
                            </div>
                          </td>
                        </tr>             
                        <input type="hidden" name="type" id="type" value="<?=$TYPE;?>" />
                        <input type="hidden" name="target" id="target" value="detail" />
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
                    <tr height="35">
                      <th width="10%" align="center"><strong>ลำดับ</strong></th>
                      <th width="50%" align="left"><strong>รายละเอียด</strong></th>
                      <th width="20%" align="left"><strong>ผู้บันทึก</strong></th>
                      <th width="20%" align="center"><strong>วันเวลาที่บันทึก</strong></th>
                    </tr>
                  </thead>                
                  <?php
                    $sql_log = "SELECT * FROM vwREPAIRACTUALDETAIL WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ? AND RPATDT_GROUP = ? ORDER BY RPATDT_PROCESSDATE ASC";
                    $params = array($RPRQ_CODE,$SUBJECT,$TYPE);	
                    $query_log = sqlsrv_query($conn, $sql_log, $params);	
                    $no=0;
                    while($result_log = sqlsrv_fetch_array($query_log, SQLSRV_FETCH_ASSOC)){	
                      $no++;
                      $RPATDT_DETAIL=$result_log['RPATDT_DETAIL'];
                      $WORKINGBY=$result_log['WORKINGBY'];
                      $DATETIME1038=$result_log['DATETIME1038'];
                  ?>
                    <tr height="25">
                      <td align="center"><font size="2"><?php print "$no.";?></font></td>
                      <td><font size="2"><?php print "$RPATDT_DETAIL.";?></font></td>
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