<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$ru_id=$_GET["id"];
	if($proc=="edit"){
		$stmt = "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_CODE = ?";
		$params = array($ru_id);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_edit_repairrequest = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $RPRQ_CODE=$result_edit_repairrequest["RPRQ_CODE"];
	};
	if($proc=="add"){
    // SQL
	};  
?>
<html>
  
<head>
  <style>
    .radioexam{
        width:20px;
        height:2em;
    }
  </style>
  <script type="text/javascript">
    
    function send_plan(CASE,RPRQ_CODE){
        var url = "<?=$path?>views_amt/request_repair/request_repair_bm_proc.php";
        Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะส่งแผนซ่อมนี้',
        icon: 'warning',
        showCancelButton: true,
        // confirmButtonColor: '#C82333',
        confirmButtonText: 'บันทึก',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
              type: 'POST',
              url: url,			
              data: {
                proc: "send_plan",
                CASE: CASE,
                RPRQ_CODE: RPRQ_CODE
              },
              success: function (rs) {     
                Swal.fire({
                  icon: 'success',
                  title: 'บันทึกแผนเสร็จสิ้น',
                  showConfirmButton: false,
                  timer: 2000
                }).then((result) => {	
                  loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_bm.php');
                  closeUI();
                })
              }
          });
        }
      })
    }
    function not_send_plan(CASE,RPRQ_CODE){
      var url = "<?=$path?>views_amt/request_repair/request_repair_bm_proc.php";
      // var RPRQ_REMARK = $("#RPRQ_REMARK").val(); 
      
      var RPRQ_REMARK = $('#RPRQ_REMARK').val();
      if(RPRQ_REMARK==''){
        Swal.fire({
          icon: 'warning',
          title: 'กรุณาระบุสาเหตุที่ไม่อนุมัติ',
          showConfirmButton: false,
          timer: 1500,
          onAfterClose: () => {
            setTimeout(() => $("#RPRQ_REMARK").focus(), 0);
          }
        })
        return false;
      }

      Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะไม่อนุมัติแผนซ่อมนี้',
        icon: 'warning',
        showCancelButton: true,
        // confirmButtonColor: '#C82333',
        confirmButtonText: 'บันทึก',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
              type: 'POST',
              url: url,			
              data: {
                proc: "send_plan",
                CASE: CASE,
                RPRQ_REMARK: RPRQ_REMARK,
                RPRQ_CODE: RPRQ_CODE
              },
              success: function (rs) {     
                Swal.fire({
                  icon: 'success',
                  title: 'บันทึกแผนเสร็จสิ้น',
                  showConfirmButton: false,
                  timer: 2000
                }).then((result) => {	
                  loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_bm.php');
                  closeUI();
                })
              }
          });
        }
      })
    }
    
  </script>
</head>

<body>

    
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="24" valign="middle" class=""><img src="https://img2.pic.in.th/pic/Process-Info-icon32.png" width="32"
                height="32"></td>
            <td valign="bottom" class="">
              <h4>&nbsp;&nbsp;ตรวจสอบใบแจ้งซ่อม</h4>
            </td>
          </tr>
        </table>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center">
      <form action="#" method="post" enctype="multipart/form-data" name="input_request" id="input_request">    
        <table width="100%" class="table table-striped datatable" id="datatable"></table>
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
          <tr class="CENTER">
            <td class="LEFT"></td>
            <td class="CENTER" align="center">
            <form name="form_approve" id="form_approve">
              <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                <tbody>			
                  <?php
                    $sql_rprq = "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_CODE = '$RPRQ_CODE'";
                    $query_rprq = sqlsrv_query($conn, $sql_rprq);
                    $no=0;
                    while($result_rprq = sqlsrv_fetch_array($query_rprq, SQLSRV_FETCH_ASSOC)){	
                      $no++;
                  ?>
                  <tr>
                    <td>
                      <table width="100%" class="table table-bordered" id="datatable">
                        <tr>
                          <td width="15%" align="right" bgcolor="#f9f9f9"><strong>เลขที่ใบแจ้งซ่อม</strong></td>
                          <td width="15%" align="left"><?=$result_rprq['RPRQ_ID']; ?></td>
                          <td width="12%" align="right" bgcolor="#f9f9f9"><strong>สถานะแจ้งซ่อม</strong></td>
                          <td width="15%" align="left">                        
                            <?php	switch($result_rprq['RPRQ_STATUSREQUEST']) {
                                case "รอส่งแผน":
                                  $RPRQ_STATUSREQUEST="<strong><font color='brown'>รอส่งแผน</font></strong>";
                                break;
                                case "รอตรวจสอบ":
                                  $RPRQ_STATUSREQUEST="<strong><font color='red'>รอตรวจสอบ</font></strong>";
                                break;
                                case "รอคิวซ่อม":
                                  $RPRQ_STATUSREQUEST="<strong><font color='red'>รอคิวซ่อม</font></strong>";
                                break;
                                case "กำลังซ่อม":
                                  $RPRQ_STATUSREQUEST="<strong><font color='red'>กำลังซ่อม</font></strong>";
                                break;
                                case "ซ่อมเสร็จสิ้น":
                                  $RPRQ_STATUSREQUEST="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
                                break;
                                case "อนุมัติ":
                                  $RPRQ_STATUSREQUEST="<strong><font color='green'>อนุมัติ</font></strong>";
                                break;
                                case "ไม่อนุมัติ":
                                  $RPRQ_STATUSREQUEST="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
                                break;
                              }
                              print $RPRQ_STATUSREQUEST;
                            ?>
                          </td>
                          <td width="12%" align="right" bgcolor="#f9f9f9"><strong>วันที่แจ้งซ่อม</strong></td>
                          <td colspan="5"><?php print $result_rprq['RPRQ_CREATEDATE_REQUEST']; ?></td>
                        </tr>
                        <tr>
                          <td align="right" bgcolor="#f9f9f9"><strong>วันที่นำรถเข้าซ่อม</strong></td>
                          <td><?php print $result_rprq['RPRQ_REQUESTCARDATE']; ?> เวลา <?php print $result_rprq['RPRQ_REQUESTCARTIME']; ?> น.</td>
                          <td align="right" bgcolor="#f9f9f9"><strong>วันที่ต้องการ</strong></td>
                          <td colspan="5"><?php print $result_rprq['RPRQ_USECARDATE']; ?> เวลา <?php print $result_rprq['RPRQ_USECARTIME']; ?> น.</td>
                        </tr>							
                        <tr>
                          <td align="right" bgcolor="#f9f9f9"><strong>ทะเบียนรถ(หัว)</strong></td>
                          <td><?php echo $result_rprq["RPRQ_REGISHEAD"];?></td>
                          <td align="right" bgcolor="#f9f9f9"><strong>ชื่อรถ(หัว)</strong></td>
                          <td><?php echo $result_rprq["RPRQ_CARNAMEHEAD"];?></td>
                          <td align="right" bgcolor="#f9f9f9"><strong>ประเภทรถ</strong></td>
                          <td width="15%" ><?php echo $result_rprq["RPRQ_CARTYPE"];?></td>
                          <td width="10%" align="right" bgcolor="#f9f9f9"><strong>ลูกค้า/สายงาน</strong></td>
                          <td><?php echo $result_rprq["RPRQ_LINEOFWORK"];?></td>
                        </tr>
                        <tr>
                          <td align="right" bgcolor="#f9f9f9"><strong>ทะเบียนรถ(หาง)</strong></td>
                          <td><?php echo $result_rprq["RPRQ_REGISTAIL"];?></td>
                          <td align="right" bgcolor="#f9f9f9"><strong>ชื่อรถ(หาง)</strong></td>
                          <td colspan="5"><?php echo $result_rprq["RPRQ_CARNAMETAIL"];?></td>
                        </tr>
                        <tr>
                          <td align="right" bgcolor="#f9f9f9"><strong>สินค้าบนรถ </strong></td>
                          <td>								             
                            <?php	switch($result_rprq['RPRQ_PRODUCTINCAR']) {
                                case "ist":
                                  $RPRQ_PRODUCTINCAR="มี";
                                break;
                                case "ost":
                                  $RPRQ_PRODUCTINCAR="ไม่มี";
                                break;
                              }
                              print $RPRQ_PRODUCTINCAR;
                            ?>
                          </td>
                          <td align="right" bgcolor="#f9f9f9"><strong>ลักษณะการวิ่งงาน</strong></td>
                          <td>								             
                            <?php 	switch($result_rprq['RPRQ_NATUREREPAIR']) {
                                case "bfw":
                                  $RPRQ_NATUREREPAIR="ก่อนปฏิบัติงาน";
                                break;
                                case "wwk":
                                  $RPRQ_NATUREREPAIR="ขณะปฏิบัติงาน";
                                break;
                                case "atw":
                                  $RPRQ_NATUREREPAIR="หลังปฏิบัติงาน";
                                break;
                              }
                              print $RPRQ_NATUREREPAIR;
                            ?>
                          </td>
                          <td align="right" bgcolor="#f9f9f9"><strong>ประเภทลูกค้า</strong></td>
                          <td colspan="5">								             
                            <?php	switch($result_rprq['RPRQ_TYPECUSTOMER']) {
                                case "cusout":
                                  $RPRQ_TYPECUSTOMER="ลูกค้านอก";
                                break;
                                case "cusin":
                                  $RPRQ_TYPECUSTOMER="ลูกค้าภายใน";
                                break;
                                case "center":
                                  $RPRQ_TYPECUSTOMER="รถส่วนกลาง";
                                break;
                              }
                              print $RPRQ_TYPECUSTOMER;
                            ?>
                          </td>
                        </tr>						
                        <?php     
                          $RPRQ_REQUESTBY=$result_rprq["RPRQ_REQUESTBY"];
                          $stmt_emp_request = "SELECT * FROM vwEMPLOYEE WHERE nameT = ?";
                          $params_emp_request = array($RPRQ_REQUESTBY);	
                          $query_emp_request = sqlsrv_query( $conn, $stmt_emp_request, $params_emp_request);	
                          $result_edit_emp_request = sqlsrv_fetch_array($query_emp_request, SQLSRV_FETCH_ASSOC);
                        ?>
                        <tr>
                          <td align="right" bgcolor="#f9f9f9"><strong>ผู้แจ้งซ่อม</strong></td>
                          <td><?php echo $result_edit_emp_request["nameT"];?></td>
                          <td align="right" bgcolor="#f9f9f9"><strong>ตำแหน่ง</strong></td>
                          <td><?php echo $result_edit_emp_request["PositionNameT"];?></tdalign=>
                          <td align="right" bgcolor="#f9f9f9"><strong>ฝ่าย</strong></td>
                          <td colspan="5"><?php echo $result_edit_emp_request["Company_Code"];?></td>
                        </tr>
                        <tr>
                          <td align="right" bgcolor="#f9f9f9"><strong>ลักษณะงานซ่อม/รายละเอียด</strong></td>
                          <td colspan="7">														             
                            <?php	
                                print $result_rprq['RPC_DETAIL'];
                            ?>	
                          </td>
                        </tr>
                                            
                        <?php  
                          $RPRQ_CODE=$result_rprq["RPRQ_CODE"];    
                          $SUBJECT=$result_rprq['RPC_SUBJECT'];                
                          $sql_causeimage = "SELECT * FROM vwREPAIRCAUSEIMAGE WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ? ORDER BY RPCIM_ID ASC";
                          $params_causeimage = array($RPRQ_CODE,$SUBJECT);	
                          $query_causeimage = sqlsrv_query($conn, $sql_causeimage, $params_causeimage);	
                          $qr_causeimage = sqlsrv_query($conn, $sql_causeimage, $params_causeimage);	
                          $rs_causeimage = sqlsrv_fetch_array($qr_causeimage, SQLSRV_FETCH_ASSOC);
                          if(isset($rs_causeimage["RPCIM_IMAGES"])){
                        ?>
                          <tr>
                            <td align="right" bgcolor="#f9f9f9"><strong>รูปภาพ</strong></td>
                            <td colspan="7">                       
                              <?php  
                                $no=0;
                                while($result_causeimage = sqlsrv_fetch_array($query_causeimage, SQLSRV_FETCH_ASSOC)){	
                                  $no++;
                                  $RPCIM_ID=$result_causeimage['RPCIM_ID'];
                                  $RPC_SUBJECT=$result_causeimage['RPC_SUBJECT'];
                                  $WORKINGBY=$result_causeimage['WORKINGBY'];
                                  $DATETIME1038=$result_causeimage['DATETIME1038'];  
                                  $RPCIM_IMAGES=$result_causeimage["RPCIM_IMAGES"];
                                ?>
                                <img src="<?=$path?>uploads/requestrepair/<?=$result_causeimage["RPCIM_IMAGES"];?>" width="80px" height="80px" style="cursor:pointer;border:5px solid #555;" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_detail_imageview.php','check','<?=$RPRQ_CODE;?>&subject=<?=$RPC_SUBJECT;?>&prpqid=<?=$RPRQ_ID?>&image=<?=$RPCIM_IMAGES?>','1=1','1000','700','รูปภาพแจ้งซ่อม');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <?php } ?>  
                            </td>
                          </tr>
                        <?php } ?>  
                      </table>
                    </td>
                  </tr>
                  <?php }; ?>
                </tbody>
              </table>
            </form>			
            </td>
            <td class="RIGHT"></td>
          </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
          <tbody>    
            <tr align="center" height="25px">
              <td height="35" colspan="2" align="center">
                <button class="bg-color-blue font-white" type="button" onclick="send_plan('send','<?=$result_edit_repairrequest['RPRQ_CODE'];?>')">ส่งแผน</button>&nbsp;&nbsp;&nbsp;
                <button class="bg-color-red font-white" type="button"  onclick="not_send_plan('not','<?=$result_edit_repairrequest['RPRQ_CODE'];?>')">ไม่อนุมัติ</button><br>
              </td>
            </tr>
            <tr align="center" height="25px">
              <td height="25" align="center">
                <div class="input-control text" style="width:100%; ">
                  <textarea id="RPRQ_REMARK" name="RPRQ_REMARK" placeholder="ระบุหมายเหตุ(กรณีไม่อนุมัติ)" class="form-control remark" style="width:100%"></textarea>
                </div>
              </td>
            </tr>
            <tr align="center" height="25px">
              <td height="35" colspan="2" align="center">
              <button class="bg-color-gray font-black" type="button" onclick="closeUI()">X ปิดหน้าจอ</button>
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