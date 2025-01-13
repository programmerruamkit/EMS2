<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$rprq_code=$_GET["id"];
  $SESSION_AREA = $_SESSION["AD_AREA"];

	if($proc=="add"){
    $stmt = "SELECT * FROM vwASSIGN WHERE RPRQ_AREA = '$SESSION_AREA' AND RPRQ_CODE = ?";
		$params = array($rprq_code);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_rprq = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $RPRQ_CODE=$result_rprq["RPRQ_CODE"];
    $RPRQ_REGISHEAD=$result_rprq["RPRQ_REGISHEAD"];
	};
?>
<html>
  
<head>
	<script type="text/javascript">
		$(document).ready(function(e) {
			dataTable('datatable2');
		});  
    function save_openjob(target,groub,rprqcode,subject) {
      // if(!confirm("ยืนยันการแก้ไข")) return false;      
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะเปิดงานซ่อมนี้',
        icon: 'warning',
        showCancelButton: true,
        // confirmButtonColor: '#C82333',
        confirmButtonText: 'ใช่! บันทึกเวลาเริ่มงาน',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          var url = "<?=$path?>views_amt/assigned_working/assigned_working_proc.php";
          $.ajax({
            type: "POST",
            url:url,
            // data: $("#form_project").serialize(),
            data: {
              target: target, 
              RPATTM_GROUP: groub, 
              RPRQ_CODE: rprqcode, 
              RPC_SUBJECT: subject
            },
            success:function(data){
              // console.log(data)
              // alert(data);                              
              Swal.fire({
                icon: 'success',
                title: 'บันทึกเวลาเสร็จสิ้น เริ่มงานได้',
                showConfirmButton: false,
                timer: 2000
              }).then((result) => {	 
                  // if(buttonname=='add'){
                  //   log_transac_requestrepair('LA3', '-', '<?=$rand;?>');
                  // }else{
                  //   log_transac_requestrepair('LA4', '-', '<?=$RPRQ_CODE;?>');
                  // }
                  loadViewdetail('<?=$path?>views_amt/assigned_working/assigned_working_form.php?id=<?php print $RPRQ_CODE;?>&proc=add');
                  closeUI();
              })	
            }
          });
        }
      })
    }  
    function save_pausejob(target,groub,rprqcode,subject) {
      // if(!confirm("ยืนยันการแก้ไข")) return false;      
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะหยุดพักงานซ่อมนี้',
        icon: 'warning',
        input: "text",
        inputAttributes: {
          autocapitalize: "off"
        },
        showCancelButton: true,
        confirmButtonColor: '#C82333',
        confirmButtonText: 'ใช่! บันทึกหยุดพักงาน',
        cancelButtonText: 'ยกเลิก',
        showLoaderOnConfirm: true,
        inputValidator: function (value) {
            return new Promise(function (resolve, reject) {
                if (value !== '') {
                    resolve();
                } else {
                    resolve('โปรดระบุสาเหตุที่ขอหยุดพักงาน!');
                }
                return value;
            });
        }, 
      }).then((result) => {
        var pausejobrepair = result.value;
        if (result.isConfirmed) {
          var url = "<?=$path?>views_amt/assigned_working/assigned_working_proc.php";
          $.ajax({
            type: "POST",
            url:url,
            // data: $("#form_project").serialize(),
            data: {
              DETAILPAUSE: pausejobrepair, 
              target: target, 
              RPATTM_GROUP: groub, 
              RPRQ_CODE: rprqcode, 
              RPC_SUBJECT: subject
            },
            success:function(data){
              // console.log(data)
              // alert(data);                              
              Swal.fire({
                icon: 'success',
                title: 'ขณะนี้ หยุดพักงานชั่วคราว เรียบร้อย',
                showConfirmButton: false,
                timer: 2000
              }).then((result) => {	 
                  // if(buttonname=='add'){
                  //   log_transac_requestrepair('LA3', '-', '<?=$rand;?>');
                  // }else{
                  //   log_transac_requestrepair('LA4', '-', '<?=$RPRQ_CODE;?>');
                  // }
                  loadViewdetail('<?=$path?>views_amt/assigned_working/assigned_working_form.php?id=<?php print $RPRQ_CODE;?>&proc=add');
                  closeUI();
              })	
            }
          });
        }
      })
    }   
    function save_continuejob(target,groub,rprqcode,subject) {
      // if(!confirm("ยืนยันการแก้ไข")) return false;      
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะเริ่มงานซ่อมต่อ',
        icon: 'warning',
        showCancelButton: true,
        // confirmButtonColor: '#C82333',
        confirmButtonText: 'ใช่! เริ่มงานต่อ',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          var url = "<?=$path?>views_amt/assigned_working/assigned_working_proc.php";
          $.ajax({
            type: "POST",
            url:url,
            // data: $("#form_project").serialize(),
            data: {
              target: target, 
              RPATTM_GROUP: groub, 
              RPRQ_CODE: rprqcode, 
              RPC_SUBJECT: subject
            },
            success:function(data){
              // console.log(data)
              // alert(data);                              
              Swal.fire({
                icon: 'success',
                title: 'บันทึกเวลาเสร็จสิ้น เริ่มงานต่อจากเดิมได้',
                showConfirmButton: false,
                timer: 2000
              }).then((result) => {	 
                  // if(buttonname=='add'){
                  //   log_transac_requestrepair('LA3', '-', '<?=$rand;?>');
                  // }else{
                  //   log_transac_requestrepair('LA4', '-', '<?=$RPRQ_CODE;?>');
                  // }
                  loadViewdetail('<?=$path?>views_amt/assigned_working/assigned_working_form.php?id=<?php print $RPRQ_CODE;?>&proc=add');
                  closeUI();
              })	
            }
          });
        }
      })
    }  
    function save_successjob_bm(target,groub,rprqcode,subject,sparepart) {
      // if(!confirm("ยืนยันการแก้ไข")) return false;      
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะปิดงานซ่อมนี้',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'green',
        confirmButtonText: 'ใช่! ปิดงานซ่อม',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          var url = "<?=$path?>views_amt/assigned_working/assigned_working_proc.php";
          $.ajax({
            type: "POST",
            url:url,
            // data: $("#form_project").serialize(),
            data: {
              target: target, 
              RPATTM_GROUP: groub, 
              RPRQ_CODE: rprqcode, 
              RPC_SUBJECT: subject,
              RPRQ_SPAREPART: sparepart
            },
            success:function(data){
              // console.log(data)
              // alert(data);                              
              Swal.fire({
                icon: 'success',
                title: 'ขณะนี้ คุณได้ปิดงานซ่อมเรียบร้อยแล้ว',
                showConfirmButton: false,
                timer: 2000
              }).then((result) => {	 
                  // if(buttonname=='add'){
                  //   log_transac_requestrepair('LA3', '-', '<?=$rand;?>');
                  // }else{
                  //   log_transac_requestrepair('LA4', '-', '<?=$RPRQ_CODE;?>');
                  // }
                  loadViewdetail('<?=$path?>views_amt/assigned_working/assigned_working.php');
                  closeUI();
              })	
            }
          });
        }
      })
    } 
    function save_successjob_pm(target,groub,rprqcode,subject) {
      // if(!confirm("ยืนยันการแก้ไข")) return false;      
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะปิดงานซ่อมนี้',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'green',
        confirmButtonText: 'ใช่! ปิดงานซ่อม',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          var url = "<?=$path?>views_amt/assigned_working/assigned_working_proc.php";
          $.ajax({
            type: "POST",
            url:url,
            // data: $("#form_project").serialize(),
            data: {
              target: target, 
              RPATTM_GROUP: groub, 
              RPRQ_CODE: rprqcode, 
              RPC_SUBJECT: subject
            },
            success:function(data){
                // console.log(data)
                // alert(data);    
                var data = JSON.parse(data);
                if(data.statusCode==200){	                          
                  Swal.fire({
                    icon: 'success',
                    title: 'ขณะนี้ คุณได้ปิดงานซ่อมเรียบร้อยแล้ว',
                    showConfirmButton: false,
                    timer: 2000
                  }).then((result) => {	 
                      loadViewdetail('<?=$path?>views_amt/assigned_working/assigned_working.php');
                      closeUI();
                  })
                }else if(data.statusCode==201){                                      
                  Swal.fire({
                    icon: 'success',
                    title: 'ปิดงานซ่อมเรียบร้อยแล้ว',
                    showConfirmButton: false,
                    timer: 2000
                  }).then((result) => {	 
                      loadViewdetail('<?=$path?>views_amt/assigned_working/assigned_working_form.php?id=<?php print $RPRQ_CODE;?>&proc=add');
                      closeUI();
                  })
                }	
            }
          });
        }
      })
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
                      <td width="24" valign="middle" class=""><img src="https://img2.pic.in.th/pic/product-sales-report-icon32.png" width="32" height="32"></td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;ข้อมูลการแจ้งซ่อม</h4>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="RIGHT"></td>
              </tr>
              <tr class="CENTER">
                <td class="LEFT"></td>
                <td class="CENTER" align="center">   
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                    <tr>
                      <td width="13%" align="right" bgcolor="#f9f9f9"><strong>เลขที่ใบแจ้งซ่อม</strong></td>
                      <td width="15%" align="left"><?=$result_rprq['RPRQ_ID']; ?></td>
                      <td width="12%" align="right" bgcolor="#f9f9f9"><strong>สถานะแจ้งซ่อม</strong></td>
                      <td width="15%" align="left">                        
                        <?php	switch($result_rprq['RPRQ_STATUSREQUEST']) {
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
                            case "รอจ่ายงาน":
                              $RPRQ_STATUSREQUEST="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
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
                          if($result_rprq['RPRQ_WORKTYPE'] == 'BM'){
                            print 'งาน -'.$result_rprq['RPC_SUBJECT_CON'].' /รายละเอียด -'.$result_rprq['RPC_DETAIL'];
                          }else{
                            print 'งาน -'.$result_rprq['RPC_SUBJECT_CON'].' /รายละเอียด -'.$result_rprq['RPC_SUBJECT_MERGE'];
                          }
                        ?>
                        <?php          
                          $RPC_IMAGES=$result_rprq["RPC_IMAGES"];
                          $explodes = explode('-', $RPC_IMAGES);
                          $RPC_IMAGES2 = $explodes[1];
                          if($RPC_IMAGES2!=""){
                        ?>                        
                          <a href="#" type="button" id="request_image_button" >
                            <img src="https://img2.pic.in.th/pic/Preview-icon48.png" width="20px" height="20px"> คลิกดูรูปภาพ
                          </a>
                          <div id="request_image" class="modal">
                            <div class="modal-content">
                              <span class="close">&times;</span>
                              <br><br>
                              <img src="<?=$path?>uploads/requestrepair/<?=$RPC_IMAGES;?>" width="100%" height="400px">
                            </div>
                          </div>
                        <?php } ?>
                      </td>
                    </tr>
                  </table>
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
                      <td width="24" valign="middle" class=""><img src="https://img2.pic.in.th/pic/PM.png" width="32" height="32"></td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;ปฏิบัติงานซ่อม</h4>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="RIGHT"></td>
              </tr>
              <tr class="CENTER">
                <td class="LEFT"></td>
                <td class="CENTER" align="center">   
                  <form action="#" method="post" enctype="multipart/form-data" name="form_project" id="form_project">                     
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover display" id="datatable2">
                      <thead>                      
                        <tr height="30">
                          <th rowspan="2" align="center" width="5%" class="ui-state-default">ลำดับ.</th>
                          <th rowspan="2" align="center" width="10%" class="ui-state-default">ลักษณะงานซ่อม</th>
                          <th rowspan="2" align="center" width="15%" class="ui-state-default">รายละเอียดงานซ่อม</th>
                          <th colspan="2" align="center" width="18%" class="ui-state-default">วันที่ซ่อม</th>     
                          <th rowspan="2" align="center" width="7%" class="ui-state-default">พื้นที่ซ่อม</th>          
                          <?php if(($result_rprq['RPRQ_WORKTYPE'] == 'PM')&&($result_rprq['RPRQ_TYPECUSTOMER']=='cusin')){ ?>
                            <th colspan="4" align="center" width="15%" class="ui-state-default">รายละอียด</th>
                          <?php }else{ ?>
                            <th colspan="3" align="center" width="15%" class="ui-state-default">รายละอียด</th>
                          <?php } ?>
                          <th colspan="2" align="center" width="30%" class="ui-state-default">จัดการ</th>
                        </tr>
                        <tr height="30">
                          <th align="center"width="10%">นำรถเข้าซ่อม</th>
                          <th align="center"width="10%">ซ่อมเสร็จ</th>                           
                          <?php if(($result_rprq['RPRQ_WORKTYPE'] == 'PM')&&($result_rprq['RPRQ_TYPECUSTOMER']=='cusin')){ ?>
                            <th align="center"width="5%">ระหว่างซ่อม</th>
                            <th align="center"width="5%">หลังซ่อม</th>   
                            <th align="center"width="5%">รายการ</th>   
                            <th align="center"width="5%">รูปภาพ</th>   
                          <?php }else{ ?>      
                            <th align="center"width="5%">ระหว่างซ่อม</th>
                            <th align="center"width="5%">หลังซ่อม</th>     
                            <th align="center"width="5%">รูปภาพ</th>    
                          <?php } ?>
                          <th align="center"width="10%">เปิดงาน / หยุดพัก</th>
                          <th align="center"width="10%">ปิดงาน</th>    
                        </tr>
                      </thead>
                      <tbody>
                        <?php                        
                          $SESSION_PERSONCODE = $_SESSION["AD_PERSONCODE"];

                          $sql_repaircause = "SELECT * FROM REPAIRCAUSE WHERE RPRQ_CODE = '$RPRQ_CODE'";
                          $query_repaircause = sqlsrv_query($conn, $sql_repaircause);
                          $no=0;
                          while($result_repaircause = sqlsrv_fetch_array($query_repaircause, SQLSRV_FETCH_ASSOC)){	
                            $no++;
                            $RPC_SUBJECT=$result_repaircause['RPC_SUBJECT'];

                            $sql_repairtime = "SELECT * FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$RPC_SUBJECT' ORDER BY RPATTM_ID DESC";
                            $params_repairtime = array();
                            $query_repairtime = sqlsrv_query($conn, $sql_repairtime, $params_repairtime);
                            $result_repairtime = sqlsrv_fetch_array($query_repairtime, SQLSRV_FETCH_ASSOC);                             

                            $sql_repairman = "SELECT * FROM REPAIRMANEMP WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$RPC_SUBJECT' AND RPME_CODE = '$SESSION_PERSONCODE'";
                            $params_repairman = array();
                            $query_repairman = sqlsrv_query($conn, $sql_repairman, $params_repairman);
                            $result_repairman = sqlsrv_fetch_array($query_repairman, SQLSRV_FETCH_ASSOC);                             

                        ?>
                        <tr id="<?php print $NTRP_CODE; ?>" height="25px" align="center">
                          <td ><?php print "$no.";?></td>
                          <td align="left" >&nbsp;                                         
                            <?php	
                                switch($result_repaircause['RPC_SUBJECT']) {
                                  case "EL": $RPC_SUBJECT="ระบบไฟ"; break;
                                  case "TU": $RPC_SUBJECT="ยาง ช่วงล่าง"; break;
                                  case "BD": $RPC_SUBJECT="โครงสร้าง"; break;
                                  case "EG": $RPC_SUBJECT="เครื่องยนต์"; break;
                                  case "AC": $RPC_SUBJECT="อุปกรณ์ประจำรถ"; break;
                                }
                                print 'งาน'.$RPC_SUBJECT;
                            ?>
                          </td>
                          <td align="left" >&nbsp;<?php print $result_repaircause['RPC_DETAIL']; ?></td>
                          <td align="center" >&nbsp;<?php echo $result_repaircause['RPC_INCARDATE'].' '.$result_repaircause['RPC_INCARTIME']; ?></td>
                          <td align="center" >&nbsp;<?php echo $result_repaircause['RPC_OUTCARDATE'].' '.$result_repaircause['RPC_OUTCARTIME']; ?></td>
                          <td align="center" >&nbsp;
                                <?php 
                                  if(isset($result_repaircause['RPC_AREA'])){
                                    echo $result_repaircause['RPC_AREA'];
                                  }else{
                                    if(isset($result_repaircause['RPC_AREA_OTHER'])){
                                      echo $result_repaircause['RPC_AREA_OTHER'];
                                    }
                                  } 
                                ?>
                          </td>
                          <td align="center">
                            <div class="toolbar">
                              <?php if(isset($result_repaircause['RPC_INCARDATE'])){ ?>
                                <?php if(isset($result_repairman['RPME_CODE'])){ ?> 
                                  <img src="https://img2.pic.in.th/pic/Document-Write-icon24.png" width="24px" height="24px" style="cursor:pointer" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assigned_working/assigned_working_detailrepair.php','edit','<?php print $RPRQ_CODE;?>&subject=<?php print $result_repaircause['RPC_SUBJECT'];?>&type=DURING','1=1','1300','330','เพิ่มข้อมูลระหว่างซ่อม');">
                                <?php }else{ ?> 
                                  <img src="https://img2.pic.in.th/pic/Document-Write-icon24-disable.png" width="24px" height="24px">
                                <?php } ?>
                              <?php }else{ ?> 
                                <img src="https://img2.pic.in.th/pic/Document-Write-icon24-disable.png" width="24px" height="24px">
                              <?php } ?> 
                            </div>
                          </td>
                          <td align="center">
                            <div class="toolbar">
                              <?php if(isset($result_repaircause['RPC_INCARDATE'])){ ?>
                                <?php if(isset($result_repairman['RPME_CODE'])){ ?> 
                                  <img src="https://img2.pic.in.th/pic/Document-Write-icon24.png" width="24px" height="24px" style="cursor:pointer" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assigned_working/assigned_working_detailrepair.php','edit','<?php print $RPRQ_CODE;?>&subject=<?php print $result_repaircause['RPC_SUBJECT'];?>&type=AFTER','1=1','1300','330','เพิ่มข้อมูลหลังซ่อม');">
                                <?php }else{ ?> 
                                  <img src="https://img2.pic.in.th/pic/Document-Write-icon24-disable.png" width="24px" height="24px">
                                <?php } ?> 
                              <?php }else{ ?> 
                                <img src="https://img2.pic.in.th/pic/Document-Write-icon24-disable.png" width="24px" height="24px">
                              <?php } ?>  
                            </div>
                          </td>                
                          <?php if(($result_rprq['RPRQ_WORKTYPE'] == 'PM')&&($result_rprq['RPRQ_TYPECUSTOMER']=='cusin')){ ?>
                            <td align="center">
                              <div class="toolbar">
                                <?php if(isset($result_repaircause['RPC_INCARDATE'])){ ?>
                                  <?php if(isset($result_repairman['RPME_CODE'])){ ?> 
                                    <img src="https://img2.pic.in.th/pic/Search-on.png" width="24" height="24" style="cursor:pointer" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assigned_working/assigned_working_checklish.php','add','<?php print $RPRQ_CODE;?>&regishead=<?php print $RPRQ_REGISHEAD;?>&pmrank=<?php print $result_rprq['RPC_SUBJECT_CON'];?>&subject=<?php print $result_repaircause['RPC_SUBJECT'];?>','1=1','1300','700','รายการตรวจสอบ');">
                                  <?php }else{ ?> 
                                    <img src="https://img2.pic.in.th/pic/Search-disable.png" width="24px" height="24px">
                                  <?php } ?> 
                                <?php }else{ ?> 
                                  <img src="https://img2.pic.in.th/pic/Search-disable.png" width="24px" height="24px">
                                <?php } ?> 
                              </div>
                            </td>
                          <?php } ?> 
                          <td align="center">
                            <div class="toolbar">
                              <?php if(isset($result_repaircause['RPC_INCARDATE'])){ ?>
                                <?php if(isset($result_repairman['RPME_CODE'])){ ?> 
                                  <img src="https://img2.pic.in.th/pic/Preview-icon48.png" width="24px" height="24px" style="cursor:pointer" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assigned_working/assigned_working_imagerepair.php','edit','<?php print $RPRQ_CODE;?>&subject=<?php print $result_repaircause['RPC_SUBJECT'];?>&type=IMAGE','1=1','1300','330','เพิ่มรูปภาพ');">
                                <?php }else{ ?> 
                                  <img src="https://img2.pic.in.th/pic/image13-disable.png" width="24px" height="24px">
                                <?php } ?>  
                              <?php }else{ ?> 
                                <img src="https://img2.pic.in.th/pic/image13-disable.png" width="24px" height="24px">
                              <?php } ?> 
                            </div>
                          </td> 
                          <td align="center" >&nbsp;                         
                            <?php if(isset($result_repairtime['RPATTM_PROCESS'])){ ?>
                              <?php if(($result_repairtime['RPATTM_GROUP']=='START')||($result_repairtime['RPATTM_GROUP']=='CONTINUE')){ ?>                                  
                                <?php if(isset($result_repairman['RPME_CODE'])){ ?>                               
                                  <a href="javascript:void(0);" onclick="save_pausejob('save_pausejob','PAUSE','<?php print $RPRQ_CODE;?>','<?php print $result_repaircause['RPC_SUBJECT'];?>')">
                                      <img src="https://img2.pic.in.th/pic/BT_PAUSE_TH.png" width="100" height="24">
                                  </a>                                
                                <?php }else{ ?>   
                                  <img src="https://img2.pic.in.th/pic/BT_PAUSE_TH_GRAY.png" width="100" height="24">  
                                <?php } ?>  
                              <?php }else if($result_repairtime['RPATTM_GROUP']=='PAUSE'){ ?>
                                <?php if(isset($result_repairman['RPME_CODE'])){ ?>                           
                                  <a href="javascript:void(0);" onclick="save_continuejob('save_continuejob','CONTINUE','<?php print $RPRQ_CODE;?>','<?php print $result_repaircause['RPC_SUBJECT'];?>')">  
                                      <img src="https://img2.pic.in.th/pic/BT_CONTINUE_TH.png" width="100" height="24">
                                  </a>                     
                                <?php }else{ ?>   
                                  <img src="https://img2.pic.in.th/pic/BT_CONTINUE_TH_GRAY.png" width="100" height="24">  
                                <?php } ?>  
                              <?php } ?>
                            <?php }else{ ?>    
                              <?php if(isset($result_repairman['RPME_CODE'])){ ?>                        
                                <a href="javascript:void(0);" onclick="save_openjob('save_openjob','START','<?php print $RPRQ_CODE;?>','<?php print $result_repaircause['RPC_SUBJECT'];?>')">  
                                    <img src="https://img2.pic.in.th/pic/BT_START_TH.png" width="100" height="24">
                                </a>        
                              <?php }else{ ?>   
                                <img src="https://img2.pic.in.th/pic/BT_START_TH_GRAY.png" width="100" height="24">  
                              <?php } ?>  
                            <?php } ?>
                          </td>
                          <td align="center" >&nbsp;                            
                            <?php if(isset($result_repairtime['RPATTM_PROCESS'])){ ?>                                   
                              <?php if($result_repairtime['RPATTM_GROUP']=='SUCCESS'){ ?>   
                                <?php if(isset($result_repairman['RPME_CODE'])){ ?>     
                                  <img src="https://img2.pic.in.th/pic/BT_SUCCESS_TH_GRAY.png" width="100" height="24"> 
                                <?php }else{ ?>   
                                  <img src="https://img2.pic.in.th/pic/BT_SUCCESS_TH_GRAY.png" width="100" height="24">  
                                <?php } ?> 
                              <?php }else{ ?>                                
                                <?php if(isset($result_repairman['RPME_CODE'])){?>    
                                  <?php if($result_rprq['RPRQ_WORKTYPE'] == 'BM'){ ?>
                                    <a href="javascript:void(0);" onclick="save_successjob_bm('save_successjob_bm','SUCCESS','<?php print $RPRQ_CODE;?>','<?php print $result_repaircause['RPC_SUBJECT'];?>','<?=$result_rprq['RPRQ_SPAREPART']?>')">  
                                        <img src="https://img2.pic.in.th/pic/BT_SUCCESS_TH.png" width="100" height="24">
                                    </a>
                                  <?php }else{ ?>                                    
                                    <?php	switch($result_rprq['RPRQ_TYPECUSTOMER']) {
                                        case "cusout":
                                          $save_type="save_successjob_pm_out";
                                        break;
                                        case "cusin":
                                          $save_type="save_successjob_pm";
                                        break;
                                      }
                                      // print $save_type;
                                    ?>
                                    <a href="javascript:void(0);" onclick="save_successjob_pm('<?=$save_type?>','SUCCESS','<?php print $RPRQ_CODE;?>','<?php print $result_repaircause['RPC_SUBJECT'];?>')">  
                                        <img src="https://img2.pic.in.th/pic/BT_SUCCESS_TH.png" width="100" height="24">
                                    </a>
                                  <?php } ?>
                                <?php }else{ ?>   
                                  <img src="https://img2.pic.in.th/pic/BT_SUCCESS_TH_GRAY.png" width="100" height="24">  
                                <?php } ?> 
                              <?php } ?>
                            <?php }else{ ?>
                                  <img src="https://img2.pic.in.th/pic/BT_SUCCESS_TH_GRAY.png" width="100" height="24">
                            <?php } ?>
                          </td>
                        </tr>
                        <?php }; ?>
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
    <br>    
			<button type="button" class="button_gray" onclick="javascript:loadViewdetail('<?=$path?>views_amt/assigned_working/assigned_working_form.php?id=<?php print $RPRQ_CODE;?>&proc=add');">อัพเดท</button>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <button class="bg-color-red font-white" type="button" onclick="javascript:loadViewdetail('<?=$path?>views_amt/assigned_working/assigned_working.php');">ย้อนกลับ</button>
    <br><br>
</body>

</html>
<style>
    .radioexam{
        width:20px;
        height:2em;
    }
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
  
  /* The Modal (background) */
  .modal {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 1; /* Sit on top */
      padding-top: 100px; /* Location of the box */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      overflow: auto; /* Enable scroll if needed */
      background-color: rgb(0,0,0); /* Fallback color */
      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }
    /* Modal Content */
    .modal-content {
      background-color: #fefefe;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 50%;
    }
    /* The Close Button */
    .close {
      color: #aaaaaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }
    .close:hover,
    .close:focus {
      color: #000;
      text-decoration: none;
      cursor: pointer;
    }
  /* The Modal (background) */
</style>

<!-- modal -->
<script>
  var modalimg = document.getElementById("request_image");
  var btnimg = document.getElementById("request_image_button");
  var spanimg = document.getElementsByClassName("close")[0];
  btnimg.onclick = function(){ modalimg.style.display = "block"; }
  spanimg.onclick = function(){ modalimg.style.display = "none"; }
  window.onclick = function(event){ if (event.target == modalimg) { modalimg.style.display = "none"; } }
</script>