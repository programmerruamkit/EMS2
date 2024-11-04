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
	};
  
  // ไม่ได้ใช้ 2023/11/24
  // function DateTimeDiff($strDateTime1,$strDateTime2){
  //   return (strtotime($strDateTime2) - strtotime($strDateTime1))/(60*60);
  // }
?>
<html>
  
<head>
  <script>  
      $('.datepic').datetimepicker({
          lang:'th',
          format:'d/m/Y H:i',
        closeOnDateSelect: true
      });
  </script>
  <script type="text/javascript">    
		$(document).ready(function(e) {
      dataTable('datatable8');
		});    
    
    function save_date(incar,outcar,rprq_code,rpc_subject) {      
      // var rprq_code = '<?php print $rprq_code; ?>';
      // var rpc_subject = $('#RPC_SUBJECT').val();
      // var incar = $('#RPC_INCARDATETIME').val();
      // var outcar = $('#RPC_OUTCARDATETIME').val();
      var url = "<?=$path?>views_amt/assign_repairwork/assign_repairwork_proc.php";
      $.ajax({
          type: 'post',
          url: url,
          data: {
              target: "repairdate", 
              RPRQ_CODE: rprq_code,
              RPC_SUBJECT: rpc_subject,
              RPC_INCARDATETIME: incar,
              RPC_OUTCARDATETIME: outcar
          },
        success:function(data){
          // alert(data)
          ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $rprq_code; ?>','1=1','1300','570','มอบหมายงานซ่อม');
        }
      });          
    }
    function save_date_pm(incar,rankpmtype,typecar,rprq_code,rpc_subject) {      
      var url = "<?=$path?>views_amt/assign_repairwork/assign_repairwork_proc.php";
      $.ajax({
          type: 'post',
          url: url,
          data: {
              target: "repairdate_pm", 
              RPRQ_CODE: rprq_code,
              RPC_SUBJECT: rpc_subject,
              RPC_INCARDATETIME: incar,
              VHCCT_ID: typecar,
              RPRQ_RANKPMTYPE: rankpmtype
          },
        success:function(data){
          // alert(data)
          ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $rprq_code; ?>','1=1','1300','570','มอบหมายงานซ่อม');
        }
      });          
    }
    function save_date_pmout(outcar,rankpmtype,typecar,rprq_code,rpc_subject) {      
      var url = "<?=$path?>views_amt/assign_repairwork/assign_repairwork_proc.php";
      $.ajax({
          type: 'post',
          url: url,
          data: {
              target: "repairdate_pmout", 
              RPRQ_CODE: rprq_code,
              RPC_SUBJECT: rpc_subject,
              RPC_OUTCARDATETIME: outcar,
              VHCCT_ID: typecar,
              RPRQ_RANKPMTYPE: rankpmtype
          },
        success:function(data){
          // alert(data)
          ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $rprq_code; ?>','1=1','1300','570','มอบหมายงานซ่อม');
        }
      });          
    }
    
    function save_estimate(rprq_code,thisval,etmfield,worktype,rpc_subject) {  
      // alert(rprq_code)
      // alert(thisval)
      // alert(etmfield)
      // alert(worktype)
      // alert(rpc_subject)
      var url = "<?=$path?>views_amt/assign_repairwork/assign_repairwork_proc.php";
      $.ajax({
          type: 'post',
          url: url,
          data: {
              target: "saveestimate", 
              RPRQ_CODE: rprq_code,
              THISVAL: thisval,
              ETMFIELD: etmfield,
              WORKTYPE: worktype,
              RPC_SUBJECT: rpc_subject,
          },
        success:function(data){
          // alert(data)
          // ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $rprq_code; ?>','1=1','1300','570','มอบหมายงานซ่อม');
        }
      });          
    }
    function save_data_assign() {
      // if(!confirm("ยืนยันการแก้ไข")) return false;      
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะจ่ายงานแจ้งซ่อมนี้',
        icon: 'warning',
        showCancelButton: true,
        // confirmButtonColor: '#C82333',
        confirmButtonText: 'ใช่! บันทึกจ่ายงาน',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          var url = "<?=$path?>views_amt/assign_repairwork/assign_repairwork_proc.php";
          $.ajax({
            type: "POST",
            url:url,
            data: $("#form_project").serialize(),
            success:function(data){
              // console.log(data)
              // alert(data);                              
              Swal.fire({
                icon: 'success',
                title: 'บันทึกจ่ายงานเสร็จสิ้น',
                showConfirmButton: false,
                timer: 2000
              }).then((result) => {	 
                  // if(buttonname=='add'){
                  //   log_transac_requestrepair('LA3', '-', '<?=$rand;?>');
                  // }else{
                  //   log_transac_requestrepair('LA4', '-', '<?=$RPRQ_CODE;?>');
                  // }
                  loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php');
                  closeUI();
              })	
            }
          });
        }
      })
    }    
    function save_repairman_drive(){
        var rprq_code = '<?php print $rprq_code; ?>';
        var select_repairman_drive = $('#select_repairman_drive').val();
        var url = "<?=$path?>views_amt/assign_repairwork/assign_repairwork_proc.php";
        $.ajax({
            type: 'post',
            url: url,
            data: {
                target: "save_rpm_drive", 
                rprq_code: rprq_code,
                select_repairman_drive: select_repairman_drive
            },
            success:function(data){  
              // alert(data);                 
              // loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php');
              ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $rprq_code; ?>','1=1','1300','570','มอบหมายงานซ่อม');
              // closeUI();
            }
        });
    }    
    function save_repairman_drive_s1(){
        var rprq_code = '<?php print $rprq_code; ?>';
        var select_repairman_drive_s1 = $('#select_repairman_drive_s1').val();
        var url = "<?=$path?>views_amt/assign_repairwork/assign_repairwork_proc.php";
        $.ajax({
            type: 'post',
            url: url,
            data: {
                target: "save_rpm_drive", 
                zone: "S1", 
                rprq_code: rprq_code,
                select_repairman_drive: select_repairman_drive_s1
            },
            success:function(data){  
              // alert(data);                 
              // loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php');
              ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $rprq_code; ?>','1=1','1300','570','มอบหมายงานซ่อม');
              // closeUI();
            }
        });
    }    
    function save_repairman_drive_s2(){
        var rprq_code = '<?php print $rprq_code; ?>';
        var select_repairman_drive_s2 = $('#select_repairman_drive_s2').val();
        var url = "<?=$path?>views_amt/assign_repairwork/assign_repairwork_proc.php";
        $.ajax({
            type: 'post',
            url: url,
            data: {
                target: "save_rpm_drive", 
                zone: "S2", 
                rprq_code: rprq_code,
                select_repairman_drive: select_repairman_drive_s2
            },
            success:function(data){  
              // alert(data);                 
              // loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php');
              ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $rprq_code; ?>','1=1','1300','570','มอบหมายงานซ่อม');
              // closeUI();
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
                      <td width="24" valign="middle" class=""><img src="../images/product-sales-report-icon32.png" width="32" height="32"></td>
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
                    <!-- <tr>
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
                            <img src="../images/Preview-icon48.png" width="20px" height="20px"> คลิกดูรูปภาพ
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
                    </tr> -->
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
                      <td width="24" valign="middle" class=""><img src="../images/pm_sheet.png" width="32" height="32"></td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;มอบหมายงาน</h4>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="RIGHT"></td>
              </tr>
              <tr class="CENTER">
                <td class="LEFT"></td>
                <td class="CENTER" align="center">    
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover display" id="datatable8">
                    <thead>
                      <tr height="30">
                        <th rowspan="2" width="5%" class="ui-state-default">ลำดับ.</th>
                        <th rowspan="2" width="10%" class="ui-state-default">ลักษณะงานซ่อม</th>
                        <th rowspan="2" width="20%" class="ui-state-default">รายละเอียดงานซ่อม</th>
                        <th rowspan="2" width="32%" class="ui-state-default">วันที่นำรถเข้าซ่อม - วันทีซ่อมเสร็จ</th>
                        <th rowspan="2" width="10%" class="ui-state-default">พื้นที่ซ่อม</th>
                        <th rowspan="2" width="10%" class="ui-state-default">ช่างซ่อม</th>
                        <th colspan="4" width="18%" class="ui-state-default">ข้อมูลประมาณการ</th>
                      </tr>
                      <tr height="30">
                        <th align="center">จำนวนช่าง</th>      
                        <th align="center">ค่าอะไหล่</th>
                        <th align="center">ค่าแรง</th>  
                        <th align="center">ชม.เก็บเงิน</th>        
                      </tr>
                    </thead>
                    <tbody>			
                      <?php
                        $sql_repaircause = "SELECT * FROM REPAIRCAUSE WHERE RPRQ_CODE = '$RPRQ_CODE'";
                        $query_repaircause = sqlsrv_query($conn, $sql_repaircause);
                        $no=0;
                        while($result_repaircause = sqlsrv_fetch_array($query_repaircause, SQLSRV_FETCH_ASSOC)){	
                          $no++;
                          $RPC_SUBJECT=$result_repaircause['RPC_SUBJECT'];
                          $sql_repairman = "SELECT COUNT(RPME_CODE) AS COUNTREPAIRMAN FROM REPAIRMANEMP WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$RPC_SUBJECT'";
                          $params_repairman = array();
                          $query_repairman = sqlsrv_query($conn, $sql_repairman, $params_repairman);
                          $result_repairman = sqlsrv_fetch_array($query_repairman, SQLSRV_FETCH_ASSOC);                           
                          
                          // if($result_rprq['RPRQ_WORKTYPE'] == 'BM'){
                            if($RPC_SUBJECT=="EL"){
                              $RPC_SUBJECT_NAME = "ระบบไฟ";
                            }else if($RPC_SUBJECT=="TU"){
                              $RPC_SUBJECT_NAME = "ยาง-ช่วงล่าง";
                            }else if($RPC_SUBJECT=="BD"){
                              $RPC_SUBJECT_NAME = "โครงสร้าง";
                            }else if($RPC_SUBJECT=="EG"){
                              $RPC_SUBJECT_NAME = "เครื่องยนต์";
                            }
                          // }else if($result_rprq['RPRQ_WORKTYPE'] == 'PM'){
                          //   if($RPC_SUBJECT=="EL"){
                          //     $RPC_SUBJECT_NAME = "PM-ระบบไฟ";
                          //   }else if($RPC_SUBJECT=="TU"){
                          //     $RPC_SUBJECT_NAME = "PM-ยาง-ช่วงล่าง";
                          //   }else if($RPC_SUBJECT=="BD"){
                          //     $RPC_SUBJECT_NAME = "PM-โครงสร้าง";
                          //   }else if($RPC_SUBJECT=="EG"){
                          //     $RPC_SUBJECT_NAME = "PM-เครื่องยนต์";
                          //   }
                          // }

                          $sql_seletmrpm = "SELECT TOP 1 RPETM_REPAIRMAN FROM REPAIRESTIMATE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPETM_NATURE = '$RPC_SUBJECT_NAME'";
                          $params_seletmrpm = array();
                          $query_seletmrpm = sqlsrv_query($conn, $sql_seletmrpm, $params_seletmrpm);
                          $result_seletmrpm = sqlsrv_fetch_array($query_seletmrpm, SQLSRV_FETCH_ASSOC);                       
                          $RPETM_REPAIRMAN=$result_seletmrpm['RPETM_REPAIRMAN'];
                          $sql_seletmspp = "SELECT TOP 1 RPETM_SPARESPART FROM REPAIRESTIMATE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPETM_NATURE = '$RPC_SUBJECT_NAME'";
                          $params_seletmspp = array();
                          $query_seletmspp = sqlsrv_query($conn, $sql_seletmspp, $params_seletmspp);
                          $result_seletmspp = sqlsrv_fetch_array($query_seletmspp, SQLSRV_FETCH_ASSOC);                       
                          $RPETM_SPARESPART=$result_seletmspp['RPETM_SPARESPART'];
                          $sql_seletmwg = "SELECT TOP 1 RPETM_WAGE FROM REPAIRESTIMATE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPETM_NATURE = '$RPC_SUBJECT_NAME'";
                          $params_seletmwg = array();
                          $query_seletmwg = sqlsrv_query($conn, $sql_seletmwg, $params_seletmwg);
                          $result_seletmwg = sqlsrv_fetch_array($query_seletmwg, SQLSRV_FETCH_ASSOC);                       
                          $RPETM_WAGE=$result_seletmwg['RPETM_WAGE'];
                          $sql_seletmhr = "SELECT TOP 1 RPETM_HOUR FROM REPAIRESTIMATE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPETM_NATURE = '$RPC_SUBJECT_NAME'";
                          $params_seletmhr = array();
                          $query_seletmhr = sqlsrv_query($conn, $sql_seletmhr, $params_seletmhr);
                          $result_seletmhr = sqlsrv_fetch_array($query_seletmhr, SQLSRV_FETCH_ASSOC);                       
                          $RPETM_HOUR=$result_seletmhr['RPETM_HOUR'];

                      ?>
                      <tr id="<?php print $NTRP_CODE; ?>" height="25px" align="center">
                        <td ><?php print "$no.";?></td>
                        <td align="left" >&nbsp;                                         
                          <?php	
                              switch($result_repaircause['RPC_SUBJECT']) {
                                case "EL": $RPC_SUBJECT="ระบบไฟ"; break;
                                case "TU": $RPC_SUBJECT="ยาง-ช่วงล่าง"; break;
                                case "BD": $RPC_SUBJECT="โครงสร้าง"; break;
                                case "EG": $RPC_SUBJECT="เครื่องยนต์"; break;
                                case "AC": $RPC_SUBJECT="อุปกรณ์ประจำรถ"; break;
                              }
                              print 'งาน'.$RPC_SUBJECT;
                          ?>
                        </td>
                        <td align="left" >&nbsp;<?php if(isset($result_rprq['RPRQ_RANKPMTYPE'])){print $result_rprq['RPRQ_RANKPMTYPE'].', ';} ?><?php print $result_repaircause['RPC_DETAIL']; ?></td>
                        <td align="center" >      
                          <div class="row">                    
                            <div class="col-md-1">&nbsp;</div>                             
                            <div class="col-md-10">&nbsp;                              											             
                              <?php if($result_rprq['RPRQ_WORKTYPE'] == 'BM'){ ?>     

                                <input type="text" name="RPC_INCARDATETIME" id="RPC_INCARDATETIME" class="datepic char" placeholder="เลือกวันที่นำรถเข้าซ่อม" autocomplete="off" value="<?php echo $result_repaircause["RPC_INCARDATE"];?> <?php echo $result_repaircause["RPC_INCARTIME"];?>" style="width:110px;" onchange="save_date(this.value,'<?=$result_repaircause['RPC_OUTCARDATE'];?> <?=$result_repaircause['RPC_OUTCARTIME'];?>','<?=$RPRQ_CODE;?>','<?=$result_repaircause['RPC_SUBJECT'];?>')">
                                - 
                                <input type="text" name="RPC_OUTCARDATETIME" id="RPC_OUTCARDATETIME" class="datepic char" placeholder="เลือกวันทีซ่อมเสร็จ" autocomplete="off" value="<?php echo $result_repaircause["RPC_OUTCARDATE"];?> <?php echo $result_repaircause["RPC_OUTCARTIME"];?>" style="width:110px;" onchange="save_date('<?=$result_repaircause['RPC_INCARDATE'];?> <?=$result_repaircause['RPC_INCARTIME'];?>',this.value,'<?=$RPRQ_CODE;?>','<?=$result_repaircause['RPC_SUBJECT'];?>')">
                                <?php 
                                  $incar = $result_repaircause["RPC_INCARDATE"].' '.$result_repaircause["RPC_INCARTIME"];
                                  $dateincar = str_replace('/', '-', $incar);
                                  $new_incar = date('Y-m-d H:i:s', strtotime($dateincar));
                                  $outcar = $result_repaircause["RPC_OUTCARDATE"].' '.$result_repaircause["RPC_OUTCARTIME"];
                                  $dateoutcar = str_replace('/', '-', $outcar);
                                  $new_outcar = date('Y-m-d H:i:s', strtotime($dateoutcar));
                                  // ไม่ได้ใช้ 2023/11/24
                                  // echo " = ".number_format(DateTimeDiff($new_incar,$new_outcar),2).' ชม.';
                                  $date1 = new DateTime($new_incar);
                                  $date2 = new DateTime($new_outcar);
                                  $diff = $date1->diff($date2);
                                  $dfh = $diff->h;
                                  $dfi = $diff->i;
                                  // echo $diff->h." ชม. ".$diff->i." น.";
                                  if($dfh>0){
                                    $ifdfh=$dfh;
                                  }else{
                                    $ifdfh='0';
                                  }
                                  if($dfi>0){
                                    $ifdfi='.'.$dfi;
                                  }else{
                                    $ifdfi='';
                                  }
                                  echo ' = '.$ifdfh.$ifdfi.' ชม.';
                                ?> 

                              <?php }else{ ?>
                                <?php
                                    $sql_chktype = "SELECT RPRQ_RANKPMTYPE,RPRQ_REGISHEAD,C.VHCCT_ID FROM	REPAIRREQUEST 
                                      LEFT JOIN VEHICLECARTYPEMATCHGROUP B ON RPRQ_REGISHEAD = B.VHCTMG_VEHICLEREGISNUMBER
                                      LEFT JOIN VEHICLECARTYPE C ON C.VHCCT_ID = B.VHCCT_ID
                                      WHERE	RPRQ_CODE = '$RPRQ_CODE'";
                                    $params_chktype = array();
                                    $query_chktype = sqlsrv_query($conn, $sql_chktype, $params_chktype);
                                    $result_chktype = sqlsrv_fetch_array($query_chktype, SQLSRV_FETCH_ASSOC); 
                                    $RPRQ_RANKPMTYPE = $result_chktype["RPRQ_RANKPMTYPE"];
                                    $VHCCT_ID = $result_chktype["VHCCT_ID"];
                                      switch($result_rprq['RPRQ_TYPECUSTOMER']) {
                                        case "cusout":
                                          $READONLY='class="datepic char"';
                                        break;
                                        case "cusin":
                                          $READONLY='class="readonly" readonly';
                                        break;
                                      }                                  
                                ?>
                                <input type="text" name="RPC_INCARDATETIME" id="RPC_INCARDATETIME" class="datepic char" placeholder="เลือกวันที่นำรถเข้าซ่อม" autocomplete="off" value="<?php echo $result_repaircause["RPC_INCARDATE"];?> <?php echo $result_repaircause["RPC_INCARTIME"];?>" style="width:110px;" onchange="save_date_pm(this.value,'<?=$RPRQ_RANKPMTYPE;?>','<?=$VHCCT_ID;?>','<?=$RPRQ_CODE;?>','<?=$result_repaircause['RPC_SUBJECT'];?>')">
                                - 
                                <input type="text" <?php print $READONLY; ?> value="<?php echo $result_repaircause["RPC_OUTCARDATE"];?> <?php echo $result_repaircause["RPC_OUTCARTIME"];?>" style="width:110px;" onchange="save_date_pmout(this.value,'<?=$RPRQ_RANKPMTYPE;?>','<?=$VHCCT_ID;?>','<?=$RPRQ_CODE;?>','<?=$result_repaircause['RPC_SUBJECT'];?>')">
                                <?php 
                                  $incar = $result_repaircause["RPC_INCARDATE"].' '.$result_repaircause["RPC_INCARTIME"];
                                  $dateincar = str_replace('/', '-', $incar);
                                  $new_incar = date('Y-m-d H:i:s', strtotime($dateincar));
                                  $outcar = $result_repaircause["RPC_OUTCARDATE"].' '.$result_repaircause["RPC_OUTCARTIME"];
                                  $dateoutcar = str_replace('/', '-', $outcar);
                                  $new_outcar = date('Y-m-d H:i:s', strtotime($dateoutcar));
                                  // ไม่ได้ใช้ 2023/11/24
                                  // echo " = ".number_format(DateTimeDiff($new_incar,$new_outcar),2).' ชม.';
                                  $date1 = new DateTime($new_incar);
                                  $date2 = new DateTime($new_outcar);
                                  $diff = $date1->diff($date2);
                                  $dfh = $diff->h;
                                  $dfi = $diff->i;
                                  // echo $diff->h." ชม. ".$diff->i." น.";
                                  if($dfh>0){
                                    $ifdfh=$dfh;
                                  }else{
                                    $ifdfh='0';
                                  }
                                  if($dfi>0){
                                    $ifdfi='.'.$dfi;
                                  }else{
                                    $ifdfi='';
                                  }
                                  echo ' = '.$ifdfh.$ifdfi.' ชม.';
                                ?>  

                              <?php } ?>
                            </div>  
                            <div class="col-md-1">&nbsp;</div>  
                          </div>    
                        </td>
                        <td align="center" >
                          <a href="javascript:void(0);" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form_repairhole.php','edit','<?php print $RPRQ_CODE; ?>&nature=<?=$result_repaircause['RPC_SUBJECT'];?>&rpcid=<?=$result_rprq['RPRQ_ID'];?>','1=1','1300','570','มอบหมายงานซ่อม');">
                            <b>
                              <?php 
                                if(isset($result_repaircause['RPC_AREA'])){
                                  echo '<font color="blue">'.$result_repaircause['RPC_AREA'].'</font>';
                                }else{
                                  if(isset($result_repaircause['RPC_AREA_OTHER'])){
                                    echo '<font color="blue">ซ่อมนอก</font>';
                                  }else{
                                    echo '<font color="red">เลือกช่องซ่อม</font>';
                                  }
                                } ?>
                            </b>
                          </a>
                        </td>
                        <td align="center" >
                          <a href="javascript:void(0);" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form_repairman.php','edit','<?php print $RPRQ_CODE; ?>&nature=<?=$result_repaircause['RPC_SUBJECT'];?>&rpcid=<?=$result_rprq['RPRQ_ID'];?>','1=1','1300','570','มอบหมายงานซ่อม');">
                            <b>
                              <?php 
                                if(isset($result_repairman['COUNTREPAIRMAN'])){
                                  if($result_repairman['COUNTREPAIRMAN']!='0'){
                                    echo '<font color="blue">'.$result_repairman['COUNTREPAIRMAN'].' คน</font>';
                                  }else{
                                    echo '<font color="red">เลือกช่างซ่อม</font>';
                                  }
                                } ?>
                            </b>
                          </a>
                        </td>                                            											             
                        <?php if($result_rprq['RPRQ_WORKTYPE'] == 'BM'){ ?>  
                          <td ><?=$RPETM_REPAIRMAN?></td>
                          <td ><input type="text" name="RPETM_SPARESPART" id="RPETM_SPARESPART" class="char" autocomplete="off" value="<?=$RPETM_SPARESPART;?>" style="width:100%;" onchange="save_estimate('<?=$RPRQ_CODE;?>',this.value,'2','<?=$result_rprq['RPRQ_WORKTYPE'];?>','<?=$result_repaircause['RPC_SUBJECT'];?>')"></td>
                          <td ><input type="text" name="RPETM_WAGE" id="RPETM_WAGE" class="char" autocomplete="off" value="<?=$RPETM_WAGE;?>" style="width:100%;" onchange="save_estimate('<?=$RPRQ_CODE;?>',this.value,'3','<?=$result_rprq['RPRQ_WORKTYPE'];?>','<?=$result_repaircause['RPC_SUBJECT'];?>')"></td>
                          <td ><input type="text" name="RPETM_HOUR" id="RPETM_HOUR" class="char" autocomplete="off" value="<?=$RPETM_HOUR;?>" style="width:100%;" onchange="save_estimate('<?=$RPRQ_CODE;?>',this.value,'4','<?=$result_rprq['RPRQ_WORKTYPE'];?>','<?=$result_repaircause['RPC_SUBJECT'];?>')"></td>
                        <?php }else{ ?>
                          <td ><?=$RPETM_REPAIRMAN?></td>
                          <td ><?=$RPETM_SPARESPART?></td>
                          <td ><?=$RPETM_WAGE?></td>
                          <td ><?=$RPETM_HOUR?></td>
                        <?php } ?>
                      </tr>
                      <?php }; ?>
                    </tbody>
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
                      <td width="24" valign="middle" class=""><img src="../images/Truck-icon32.png" width="32" height="32"></td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;ข้อมูลช่างผู้ขับรถส่งซ่อม</h4>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="RIGHT"></td>
              </tr>
              <tr class="CENTER">
                <td class="LEFT"></td>
                <td class="CENTER" align="center">   
                  <?php if($result_rprq['RPRQ_WORKTYPE'] == 'BM'){ ?>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                      <tr>
                        <td width="13%" align="right" bgcolor="#f9f9f9"><strong>เลือกช่างผู้ขับรถ :</strong></td>
                        <td width="15%" align="left" bgcolor="#f9f9f9">  
                          <select class="char"  style="width: 100%;" name="select_repairman_drive" id="select_repairman_drive" onchange='save_repairman_drive();'>
                            <option value=''>--เลือกช่างผู้ขับรถ--</option>
                            <?php                          
                                $sql_repairman_drive = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE = '$rprq_code'";
                                $params_repairman_drive = array();
                                $query_repairman_drive = sqlsrv_query($conn, $sql_repairman_drive, $params_repairman_drive);
                                $result_repairman_drive = sqlsrv_fetch_array($query_repairman_drive, SQLSRV_FETCH_ASSOC); 
                                $RPMD_PERSONCODE=$result_repairman_drive["RPMD_PERSONCODE"];
                                $RPMD_CARLICENCE=$result_repairman_drive["RPMD_CARLICENCE"];

                                $sql_select_repairman_drive = "SELECT * FROM vwCARLICENCE WHERE RPM_AREA = ?";
                                $params_select_repairman_drive = array($SESSION_AREA);
                                $query_select_repairman_drive = sqlsrv_query($conn, $sql_select_repairman_drive, $params_select_repairman_drive);
                                while ($result_select_repairman_drive = sqlsrv_fetch_array($query_select_repairman_drive, SQLSRV_FETCH_ASSOC)) {
                                    $srpmd1=$result_select_repairman_drive['PersonCode'];
                                    $srpmd2=$result_select_repairman_drive['nameT'];
                                    $srpmd3=$result_select_repairman_drive['CarLicenceID'];
                                    $srpmd4=$result_select_repairman_drive['RPM_AREA'];
                            ?>
                            <option value="<?=$srpmd1?>" <?php if(isset($RPMD_PERSONCODE)){ if($RPMD_PERSONCODE==$srpmd1){echo 'selected';} } ?> ><?= $srpmd4 ?> - <?= $srpmd1 ?> - <?= $srpmd2 ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td width="12%" align="right" bgcolor="#f9f9f9"><strong>หมายเลขใบขับขี่ :</strong></td>
                        <td width="15%" align="left" bgcolor="#f9f9f9"><?=$RPMD_CARLICENCE; ?></td>
                      </tr>	
                    </table>
                  <?php }else{ ?>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                      <tr>
                        <td width="13%" align="right" bgcolor="#f9f9f9"><strong>เลือกช่างผู้ขับรถ S1 :</strong></td>
                        <td width="15%" align="left" bgcolor="#f9f9f9">  
                          <select class="char"  style="width: 100%;" name="select_repairman_drive_s1" id="select_repairman_drive_s1" onchange='save_repairman_drive_s1();'>
                            <option value=''>--เลือกช่างผู้ขับรถ S1--</option>
                            <?php                          
                                $sql_repairman_drive = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE = '$rprq_code' AND RPMD_ZONE = 'S1'";
                                $params_repairman_drive = array();
                                $query_repairman_drive = sqlsrv_query($conn, $sql_repairman_drive, $params_repairman_drive);
                                $result_repairman_drive = sqlsrv_fetch_array($query_repairman_drive, SQLSRV_FETCH_ASSOC); 
                                $RPMD_PERSONCODE=$result_repairman_drive["RPMD_PERSONCODE"];
                                $RPMD_CARLICENCE=$result_repairman_drive["RPMD_CARLICENCE"];
                                $RPMD_ZONE=$result_repairman_drive["RPMD_ZONE"];

                                $sql_select_repairman_drive = "SELECT * FROM vwCARLICENCE WHERE RPM_AREA = ?";
                                $params_select_repairman_drive = array($SESSION_AREA);
                                $query_select_repairman_drive = sqlsrv_query($conn, $sql_select_repairman_drive, $params_select_repairman_drive);
                                while ($result_select_repairman_drive = sqlsrv_fetch_array($query_select_repairman_drive, SQLSRV_FETCH_ASSOC)) {
                                    $srpmd1=$result_select_repairman_drive['PersonCode'];
                                    $srpmd2=$result_select_repairman_drive['nameT'];
                                    $srpmd3=$result_select_repairman_drive['CarLicenceID'];
                                    $srpmd4=$result_select_repairman_drive['RPM_AREA'];
                            ?>
                            <option value="<?=$srpmd1?>" <?php if(isset($RPMD_PERSONCODE)){ if($RPMD_PERSONCODE==$srpmd1){echo 'selected';} } ?> ><?= $srpmd4 ?> - <?= $srpmd1 ?> - <?= $srpmd2 ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td width="12%" align="right" bgcolor="#f9f9f9"><strong>หมายเลขใบขับขี่ :</strong></td>
                        <td width="15%" align="left" bgcolor="#f9f9f9"><?=$RPMD_CARLICENCE; ?></td>
                      </tr>	
                      <tr>
                        <td width="13%" align="right" bgcolor="#f9f9f9"><strong>เลือกช่างผู้ขับรถ S2 :</strong></td>
                        <td width="15%" align="left" bgcolor="#f9f9f9">  
                          <select class="char"  style="width: 100%;" name="select_repairman_drive_s2" id="select_repairman_drive_s2" onchange='save_repairman_drive_s2();'>
                            <option value=''>--เลือกช่างผู้ขับรถ S2--</option>
                            <?php                          
                                $sql_repairman_drive = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE = '$rprq_code' AND RPMD_ZONE = 'S2'";
                                $params_repairman_drive = array();
                                $query_repairman_drive = sqlsrv_query($conn, $sql_repairman_drive, $params_repairman_drive);
                                $result_repairman_drive = sqlsrv_fetch_array($query_repairman_drive, SQLSRV_FETCH_ASSOC); 
                                $RPMD_PERSONCODE=$result_repairman_drive["RPMD_PERSONCODE"];
                                $RPMD_CARLICENCE=$result_repairman_drive["RPMD_CARLICENCE"];
                                $RPMD_ZONE=$result_repairman_drive["RPMD_ZONE"];

                                $sql_select_repairman_drive = "SELECT * FROM vwCARLICENCE WHERE RPM_AREA = ?";
                                $params_select_repairman_drive = array($SESSION_AREA);
                                $query_select_repairman_drive = sqlsrv_query($conn, $sql_select_repairman_drive, $params_select_repairman_drive);
                                while ($result_select_repairman_drive = sqlsrv_fetch_array($query_select_repairman_drive, SQLSRV_FETCH_ASSOC)) {
                                    $srpmd1=$result_select_repairman_drive['PersonCode'];
                                    $srpmd2=$result_select_repairman_drive['nameT'];
                                    $srpmd3=$result_select_repairman_drive['CarLicenceID'];
                                    $srpmd4=$result_select_repairman_drive['RPM_AREA'];
                            ?>
                            <option value="<?=$srpmd1?>" <?php if(isset($RPMD_PERSONCODE)){ if($RPMD_PERSONCODE==$srpmd1){echo 'selected';} } ?> ><?= $srpmd4 ?> - <?= $srpmd1 ?> - <?= $srpmd2 ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td width="12%" align="right" bgcolor="#f9f9f9"><strong>หมายเลขใบขับขี่ :</strong></td>
                        <td width="15%" align="left" bgcolor="#f9f9f9"><?=$RPMD_CARLICENCE; ?></td>
                      </tr>	
                    </table>
                  <?php } ?>
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
    <form action="#" method="post" enctype="multipart/form-data" name="form_project" id="form_project">
      <input type="hidden" name="RPRQ_CODE" id="RPRQ_CODE" value="<?=$RPRQ_CODE;?>"/>
      <input type="hidden" name="RPRQ_WORKTYPE" id="RPRQ_WORKTYPE" value="<?=$result_rprq['RPRQ_WORKTYPE'];?>"/>
      <input type="hidden" name="target" id="target" value="update_repair_status"/>
      <?php if($result_rprq['RPRQ_STATUSREQUEST']!='ซ่อมเสร็จสิ้น'){ ?>
        <button class="bg-color-green font-white" type="button" onclick="save_data_assign()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
      <?php } ?> 
      <button class="bg-color-red font-white" type="button" onclick="closeUI()">ปิดหน้าจอ</button>
    </form>
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