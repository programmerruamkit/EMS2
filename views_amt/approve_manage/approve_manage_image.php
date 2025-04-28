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
	$RPRQ_ID=$_GET["prpqid"];
  
?>
<html>
  
<head>
  <style>
    .radioexam{
        width:20px;
        height:2em;
    }
  </style>
</head>

<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top">
          <div style="width:100%;" align="center">
            <div class="panel panel-default" style="height:100%">
              <div align="left" class="panel-heading">
                <!-- <h5><img src="../images/task.png" width="32" height="32"> ประวัติการบันทึกข้อมูล</h5> -->
              </div>
              <font size="2">    
                <?php                
                  $sql_causeimage = "SELECT * FROM vwREPAIRCAUSEIMAGE WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ? ORDER BY RPCIM_ID ASC";
                  $params_causeimage = array($RPRQ_CODE,$SUBJECT);	
                  $query_causeimage = sqlsrv_query($conn, $sql_causeimage, $params_causeimage);	
                  $no=0;
                  while($result_causeimage = sqlsrv_fetch_array($query_causeimage, SQLSRV_FETCH_ASSOC)){	
                    $no++;
                    $RPCIM_ID=$result_causeimage['RPCIM_ID'];
                    $RPC_SUBJECT=$result_causeimage['RPC_SUBJECT'];
                    $WORKINGBY=$result_causeimage['WORKINGBY'];
                    $DATETIME1038=$result_causeimage['DATETIME1038'];  
                    $RPCIM_IMAGES=$result_causeimage["RPCIM_IMAGES"];
                  ?>
                  <input type="hidden" name="imageoldfile[]" id="imageoldfile" style="width:100px;" value="<?=$result_causeimage["RPCIM_IMAGES"];?>">
                  <img src="<?=$path?>uploads/requestrepair/<?=$result_causeimage["RPCIM_IMAGES"];?>" width="80px" height="80px" style="cursor:pointer;border:5px solid #555;" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/approve_manage/approve_manage_imageview.php','edit','<?=$RPRQ_CODE;?>&subject=<?=$RPC_SUBJECT;?>&prpqid=<?=$RPRQ_ID?>&image=<?=$RPCIM_IMAGES?>','1=1','1000','700','รูปภาพแจ้งซ่อม');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php } ?>                
              </font>
            </div>
          </div>
        </td>
      </tr>
      <tr align="center" height="25px">
        <td height="35" colspan="2" align="center">
          <button class="bg-color-red font-white" type="button" onclick="closeUI()">ปิดหน้าจอ</button>
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