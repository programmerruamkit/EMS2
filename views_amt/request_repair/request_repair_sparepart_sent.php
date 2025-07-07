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
      $RPRQ_ID=$result_edit_repairrequest["RPRQ_ID"];
      $RPRQ_CODE=$result_edit_repairrequest["RPRQ_CODE"];
      $SUBJECT=$result_edit_repairrequest["RPC_SUBJECT"];
      $RPRQ_CREATEDATE_REQUEST=$result_edit_repairrequest["RPRQ_CREATEDATE_REQUEST"];
      $RPRQ_REGISHEAD=$result_edit_repairrequest["RPRQ_REGISHEAD"];
      $RPRQ_CARNAMEHEAD=$result_edit_repairrequest["RPRQ_CARNAMEHEAD"];
      $RPRQ_CARTYPE=$result_edit_repairrequest["RPRQ_CARTYPE"];
      $RPRQ_LINEOFWORK=$result_edit_repairrequest["RPRQ_LINEOFWORK"];
      $RPRQ_MILEAGELAST=$result_edit_repairrequest["RPRQ_MILEAGELAST"];
      $RPRQ_REGISTAIL=$result_edit_repairrequest["RPRQ_REGISTAIL"];
      $RPRQ_CARNAMETAIL=$result_edit_repairrequest["RPRQ_CARNAMETAIL"];
      $RPC_DETAIL=$result_edit_repairrequest["RPC_DETAIL"];
	};
	if($proc=="add"){
    $RPRQ_REGISHEAD=$_GET["RPSPP_REGISHEAD"];
    $RPRQ_CARNAMEHEAD=$_GET["THAINAME_HEAD"];
    $RPRQ_CARTYPE=$_GET["VEHICLETYPEDESC_HEAD"];
    // $RPRQ_LINEOFWORK=$_GET["RPSPP_LINEWORK"];
    $RPRQ_LINEOFWORK=$_GET["AFFCOMPANY_HEAD"];
    $RPRQ_MILEAGELAST=$_GET["MAXMILEAGENUMBER"];
    $RPRQ_REGISTAIL=$_GET["RPSPP_REGISTAIL"];
    $RPRQ_CARNAMETAIL=$_GET["THAINAME_TAIL"];
    $RPSPP_CODE=$_GET["RPSPP_CODE"];
    $RPSPP_NAME=$_GET["RPSPP_NAME"];
    $RPC_DETAIL='เปลี่ยน'.$RPSPP_NAME;
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
	$rand="RQRP_".RandNum($n);
  
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
    function modify_row(tb){
    	// console.log('aaa');
      i=0;
      $("#"+tb+" tr").each(function(){ 
      console.log('bbb');
        i++
        $(this).attr('id','row_'+i);			
        $(this).find('img.delRow').attr('data','row_'+i)
        $(this).find('td').each(function(){
          $(this).find('input,select').each(function(index, element) {
            id = $(this).attr('name')					
            if (id != undefined){
              id =id.split("[]").join(i);
              $(this).attr('id',id);
              $(this).attr('data-id',i);              
						// console.log(id)
            }
          });
        })
      })
    }  
    function select_employee(){    
      window.setTimeout(function () {        
        $.ajax({
          type: 'post',
          url: '../autocomplete/autocomplete_employee.php',
          data: {
            txt_flg: "select_employee", 
            empname: document.getElementById('RPRQ_CREATENAME').value
          },
          success: function (rs) {			
            var myArr = rs.split("|");                    
            document.getElementById('RPRQ_REQUESTBY_SQ').value = myArr[1];    
            document.getElementById('RPRQ_EMP_POS').value = myArr[3];    
            document.getElementById('RPRQ_EMP_COM').value = myArr[4];       
          }
        });      
      }, 100);
    }    
    // for EDIT
    function save_data() {   
      var formData = new FormData($("#input_request")[0]);           
      // var file_data = $('#RPC_IMAGES')[0].files;
      // formData.append('file',file_data[0]);
      var file_data = $('#RPC_IMAGES').files;
      formData.append('file[]',file_data);
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะบันทึกแจ้งซ่อมนี้',
        icon: 'warning',
        showCancelButton: true,
        // confirmButtonColor: '#C82333',
        confirmButtonText: 'บันทึก',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            icon: 'success',
            title: 'บันทึกข้อมูลเสร็จสิ้น',
            showConfirmButton: false,
            timer: 2000
          }).then((result) => {	
            var buttonname = $('#buttonname').val();
            var url = "<?=$path?>views_amt/request_repair/request_repair_bm_proc.php";
            $.ajax({
              type: "POST",
              url:url,
              data: formData,	
              contentType: false,
              processData: false,
              success:function(data){
                console.log(data)
                // alert(data);                 
                if(buttonname=='add'){
                  log_transac_requestrepair('LA3', '-', '<?=$rand;?>');
                }else{
                  log_transac_requestrepair('LA4', '-', '<?=$RPRQ_CODE;?>');
                }
                ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','edit','<?php print $RPRQ_CODE; ?>','1=1','1350','670','แก้ไขใบแจ้งซ่อม');
                // loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_bm.php');
                // closeUI();
              }
            });
          })	
        }
      })
    }
    modify_row('request_piece_detail');
    
    // for INSERT
    $(document).ready(function(){
      $("#buttonnameadd").click(function(){   
        if($('#GOTC').val() == '' ){
          alert("กรุณาเลือกสินค้าบนรถ");
          return false;
        }
        if($('#NTORNNW').val() == '' ){
          alert("กรุณาเลือกลักษณะการวิ่งงาน");
          return false;
        }
        if($('#RPRQ_CREATENAME').val() == 0 ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาระบุผู้รับแจ้งซ่อม',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#RPRQ_CREATENAME").focus(), 0);
              }
          })
          // alert("กรุณาระบุผู้แจ้งซ่อม");
          // document.getElementById('RPRQ_CREATENAME').focus();
          return false;
        }
        if($('#datetimeRequest_in').val() == 0 ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาระบุวันที่นำรถเข้าซ่อม',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#datetimeRequest_in").focus(), 0);
              }
          })
          // alert("กรุณาระบุวันที่นำรถเข้าซ่อม");
          // document.getElementById('datetimeRequest_in').focus();
          return false;
        }		
        if($('#datetimeRequest_out').val() == 0 ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาระบุวันที่ต้องการใช้รถ',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#datetimeRequest_out").focus(), 0);
              }
          })
          // alert("กรุณาระบุวันที่ต้องการใช้รถ");
          // document.getElementById('datetimeRequest_out').focus();
          return false;
        }	
        if($('#RPM_NATUREREPAIR').val() == 0 ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาเลือกลักษณะงานซ่อม',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#RPM_NATUREREPAIR").focus(), 0);
              }
          })
          // alert("กรุณาเลือกลักษณะงานซ่อม");
          // document.getElementById('RPM_NATUREREPAIR').focus();
          return false;
        }		
        Swal.fire({
          title: 'คุณแน่ใจหรือไม่...ที่จะบันทึกแจ้งซ่อมนี้',
          icon: 'warning',
          showCancelButton: true,
          // confirmButtonColor: '#C82333',
          confirmButtonText: 'บันทึก',
          cancelButtonText: 'ยกเลิก'
        }).then((result) => {
          if (result.isConfirmed) {	
            Swal.fire({
              icon: 'success',
              title: 'บันทึกข้อมูลเสร็จสิ้น',
              showConfirmButton: false,
              timer: 2000
            }).then((result) => {	
              $("#input_request").submit();
              var form = $(this);
              var actionUrl = form.attr('action');
              $.ajax({
                type: "POST",
                url: actionUrl,
                data: form.serialize(),
                dataType: "json",
                encode: true,
              }).done(function (data) {
                console.log(data);
              });            
            })	
          }
        })
      });
    });
    
    $('#RPM_NATUREREPAIR').change(function() {
        var id_nature = $(this).val();
        $.ajax({
          type: "POST",
          url: "../autocomplete/request_repair_typenature.php",
          data: {trpw_id:id_nature,function:'RPM_NATUREREPAIR'},
          success: function(data){$('#TYPEREPAIRWORK1').html(data);}
        });
      });
  </script>
</head>

<body>
    
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <?php if($proc=="edit"){ ?>
            <td width="24" valign="middle" class=""><img src="../images/Process-Info-icon32.png" width="32"
                height="32"></td>
            <td valign="bottom" class="">
              <h4>&nbsp;&nbsp;แก้ไขใบแจ้งซ่อมอะไหล่</h4>
            </td>
            <?php }else{ ?>
            <td width="24" valign="middle" class=""><img src="../images/plus-icon32.png" width="32" height="32">
            </td>
            <td valign="bottom" class="">
              <h4>&nbsp;&nbsp;เพิ่มใบแจ้งซ่อมอะไหล่</h4>
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
      <?php if($proc=="edit"){ ?>
        <form action="#" method="post" enctype="multipart/form-data" name="input_request" id="input_request">
      <?php }else{ ?>
        <form name="input_request" id="input_request" method="post" action="<?=$path?>views_amt/request_repair/request_repair_bm_proc.php" enctype="multipart/form-data">      
      <?php } ?>           
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="display">
        <tr  class="ui-state-default">
          <td align="center" height="50"><h3>ใบแจ้งซ่อม</h3></td>
        </tr>
      </table>

      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="INVENDATA no-border">
        <tbody>
          <tr height="0px">
            <td width="12%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="10%">&nbsp;</td>
          </tr>
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>เลขที่ใบแจ้งซ่อม:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="RPRQ_ID" id="RPRQ_ID" disabled placeholder=""  onFocus="$(this).select();" value="<?php if($proc=="edit"){echo $RPRQ_ID;}else{echo "AUTO";}?>" readonly >
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>วันที่ใบแจ้งซ่อม:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="RPRQ_CREATEDATE_REQUEST" id="RPRQ_CREATEDATE_REQUEST" placeholder=""  onFocus="$(this).select();" value="<?php if($proc=="edit"){echo $RPRQ_CREATEDATE_REQUEST;}else{echo $GETDAYEN;}?>" class="readonly" readonly >
              </div>
            </td>
          </tr>
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>ประเภทลูกค้า:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">                
                <select style="width: 100%;" name="TYPECUSTOMERS" id="TYPECUSTOMERS" class="readonly" readonly>
                  <option value="cusin" selected>ลูกค้าภายใน</option>
                </select>
              </div>
            </td>
          </tr>         
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>ทะเบียนรถ(หัว):</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="VEHICLEREGISNUMBER1" id="VEHICLEREGISNUMBER1" placeholder="ระบุทะเบียนรถ(หัว)" value="<?php echo $RPRQ_REGISHEAD;?>" class="readonly" readonly autocomplete="off">
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ชื่อรถ(หัว):</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="THAINAME1" id="THAINAME1" placeholder="ชื่อรถ(หัว)" value="<?php echo $RPRQ_CARNAMEHEAD;?>" class="readonly" readonly autocomplete="off">
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ประเภทรถ:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="RPRQ_CARTYPE" id="RPRQ_CARTYPE" placeholder="ประเภทรถ" onFocus="$(this).select();" value="<?php echo $RPRQ_CARTYPE;?>" class="readonly" readonly>
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ลูกค้า/สายงาน:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="AFFCOMPANY" id="AFFCOMPANY" placeholder="ลูกค้า/สายงาน" onFocus="$(this).select();" value="<?php echo $RPRQ_LINEOFWORK;?>" class="readonly" readonly>
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>เลขไมค์:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="MAXMILEAGENUMBER" id="MAXMILEAGENUMBER" placeholder="เลขไมค์" onFocus="$(this).select();" value="<?php echo $RPRQ_MILEAGELAST;?>" class="readonly" readonly>
              </div>
            </td>
          </tr>
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>ทะเบียนรถ(หาง):</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="VEHICLEREGISNUMBER2" id="VEHICLEREGISNUMBER2" placeholder="ระบุทะเบียนรถ(หาง)" value="<?php echo $RPRQ_REGISTAIL;?>" class="readonly" readonly autocomplete="off">
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ชื่อรถ(หาง):</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="THAINAME2" id="THAINAME2" placeholder="ชื่อรถ(หาง)" value="<?php echo $RPRQ_CARNAMETAIL;?>" class="readonly" readonly autocomplete="off">
              </div>
            </td>
          </tr>  
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>สินค้าบนรถ:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">      
                <select class="char"  style="width: 100%;" name="GOTC" id="GOTC">
                  <option value disabled selected>--เลือกสินค้าบนรถ--</option>
                  <option value="ost" <?php if($result_edit_repairrequest['RPRQ_PRODUCTINCAR']=="ost"){echo "selected";}else{echo "selected";} ?>>ไม่มี</option>
                  <option value="ist" <?php if($result_edit_repairrequest['RPRQ_PRODUCTINCAR']=="ist"){echo "selected";} ?>>มี</option>
                </select>
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ลักษณะการวิ่งงาน:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">                
                <select class="char"  style="width: 100%;" name="NTORNNW" id="NTORNNW">
                  <option value disabled selected>--เลือกลักษณะการวิ่งงาน--</option>
                  <option value="bfw" <?php if($result_edit_repairrequest['RPRQ_NATUREREPAIR']== "bfw"){echo "selected";}else{echo "selected";} ?>>ก่อนปฏิบัติงาน</option>
                  <option value="wwk" <?php if($result_edit_repairrequest['RPRQ_NATUREREPAIR']== "wwk"){echo "selected";} ?>>ขณะปฏิบัติงาน</option>
                  <option value="atw" <?php if($result_edit_repairrequest['RPRQ_NATUREREPAIR']== "atw"){echo "selected";} ?>>หลังปฏิบัติงาน</option>
                </select>
              </div>
            </td>
          </tr>
          <?php    
            $RPRQ_REQUESTBY_SQ=$result_edit_repairrequest["RPRQ_REQUESTBY_SQ"];          
            $stmt_emp_create = "SELECT * FROM vwEMPLOYEE WHERE nameT = ?";
            $params_emp_create = array($RPRQ_REQUESTBY_SQ);	
            $query_emp_create = sqlsrv_query( $conn, $stmt_emp_create, $params_emp_create);	
            $result_edit_emp_create = sqlsrv_fetch_array($query_emp_create, SQLSRV_FETCH_ASSOC);
          ?>
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>ผู้รับแจ้งซ่อม SQ:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">                
                <select class="char"  style="width: 100%;" name="RPRQ_CREATENAME" id="RPRQ_CREATENAME" onchange="select_employee()">
                  <option value disabled selected>--เลือกผู้รับแจ้งซ่อม SQ--</option>
                  <?php
                    $AREA=$_SESSION["AD_AREA"];
                    $sql_sq = "SELECT * FROM vwPositionSQ WHERE 1=1 AND area = '$AREA'";
                    $query_sq = sqlsrv_query($conn, $sql_sq);
                    while ($result_sq = sqlsrv_fetch_array($query_sq, SQLSRV_FETCH_ASSOC)) {
                  ?>
                  <option value="<?php echo $result_sq["nameT"];?>" <?php if($result_edit_emp_create["nameT"] == $result_sq["nameT"]){echo "selected";} ?>><?=$result_sq['nameT']?></option>
                  <?php } ?>
                </select>
              </div>
              <input type="hidden" name="RPRQ_REQUESTBY_SQ" id="RPRQ_REQUESTBY_SQ" style="width:50px;" value="<?php echo $result_edit_emp_create["PersonCode"];?>">
              <!-- <div class="input-control text">     
                <input type="hidden" name="RPRQ_REQUESTBY_SQ" id="RPRQ_REQUESTBY_SQ" style="width:50px;" value="<?php echo $result_edit_emp_create["PersonCode"];?>">
                <input type="text" name="RPRQ_CREATENAME" id="RPRQ_CREATENAME" style="width:200px;" value="<?php echo $result_edit_emp_create["nameT"];?>" placeholder="ผู้รับแจ้งซ่อม" class="char"   onclick="select_employee()" onchange="select_employee()" onkeypress="select_employee()" onkeyup="select_employee()" onkeydown="select_employee()" onfocus="select_employee()">
              </div> -->
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ตำแหน่ง:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">      
                <input type="text" name="RPRQ_EMP_POS" id="RPRQ_EMP_POS" style="width:200px;" value="<?php echo $result_edit_emp_create["PositionNameT"];?>" placeholder="ตำแหน่ง" class="readonly" readonly>
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ฝ่าย:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">      
                <input type="text" name="RPRQ_EMP_COM" id="RPRQ_EMP_COM" style="width:200px;" value="<?php echo $result_edit_emp_create["Company_Code"];?>" placeholder="ฝ่าย" class="readonly" readonly>
              </div>
            </td>
          </tr>  
          <?php      
            $RPRQ_REQUESTBY=$result_edit_repairrequest["RPRQ_REQUESTBY"];
            $stmt_emp_request = "SELECT * FROM vwEMPLOYEE WHERE nameT = ?";
            $params_emp_request = array($RPRQ_REQUESTBY);	
            $query_emp_request = sqlsrv_query( $conn, $stmt_emp_request, $params_emp_request);	
            $result_edit_emp_request = sqlsrv_fetch_array($query_emp_request, SQLSRV_FETCH_ASSOC);
          ?>
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>ผู้แจ้งซ่อม:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">        
                <input type="hidden" name="EMP_ID_RQRP" id="EMP_ID_RQRP" style="width:100px;" value="<?php if($proc=="edit"){echo $result_edit_emp_request["PersonCode"];}else{echo $_SESSION["AD_PERSONCODE"];}?>">
                <input type="text" name="EMP_NAME_RQRP" id="EMP_NAME_RQRP" style="width:200px;" value="<?php if($proc=="edit"){echo $result_edit_emp_request["nameT"];}else{echo $_SESSION["AD_NAMETHAI"];}?>" class="readonly" readonly placeholder="ผู้แจ้งซ่อม">
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ตำแหน่ง:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">      
                <input type="text" name="EMP_POS_RQRP" id="EMP_POS_RQRP" style="width:200px;" value="<?php if($proc=="edit"){echo $result_edit_emp_request["PositionNameT"];}else{echo $_SESSION["AD_POSITIONNAME"];}?>" class="readonly" readonly placeholder="ตำแหน่ง">
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ฝ่าย:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">      
                <input type="text" name="EMP_COM_RQRP" id="EMP_COM_RQRP" style="width:200px;" value="<?php if($proc=="edit"){echo $result_edit_emp_request["Company_Code"];}else{echo $_SESSION["AD_COMPANYCODE"];}?>" class="readonly" readonly placeholder="ฝ่าย">
              </div>
            </td>
          </tr> 
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>วันที่นำรถเข้าซ่อม:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="datetimeRequest_in" id="datetimeRequest_in" class="datepic char" placeholder="วันที่ เวลา" autocomplete="off" value="<?php echo $result_edit_repairrequest["RPRQ_REQUESTCARDATE"];?> <?php echo $result_edit_repairrequest["RPRQ_REQUESTCARTIME"];?>">        
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>วันที่ต้องการใช้รถ:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">     
                <input type="text" name="datetimeRequest_out" id="datetimeRequest_out" class="datepic char" placeholder="วันที่ เวลา" autocomplete="off" value="<?php echo $result_edit_repairrequest["RPRQ_USECARDATE"];?> <?php echo $result_edit_repairrequest["RPRQ_USECARTIME"];?>">               
             </div>
            </td>
          </tr>  
        </tbody>
      </table>

      <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">
          <tr class="ui-state-default">
            <td height="35" colspan="3" >
              <div class="input-control text" style="width:100%; margin-left:3px;"> 
                <strong>ลักษณะประเภทงานซ่อม</strong>
              </div>
            </td>
          </tr>          
      </table> 

      <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">
          <tr class="ui-state-default">
            <td height="35" colspan="3" >
              <div class="input-control text" style="width:100%; margin-left:3px;"> 
                <strong>ลักษณะประเภทงานซ่อม</strong>
              </div>
            </td>
          </tr>          
      </table> 
      <div class="row"> 
        <div class="col-md-2">&ensp;</div>
        <div class="col-md-2">
          <div class="input-control text" style="width:170px;">
            <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR">  
              <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
              <option value="EL" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "EL"){echo "selected";} ?>>ระบบไฟ</option>
              <option value="TU" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "TU"){echo "selected";} ?>>ยาง ช่วงล่าง</option>
              <option value="BD" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "BD"){echo "selected";} ?>>โครงสร้าง</option>
              <option value="EG" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "EG"){echo "selected";} ?>>เครื่องยนต์</option>
            </select>
          </div>
        </div>&ensp;
        <div class="col-md-2">
          <div class="input-control text" style="width:200px;">   
            <?php if($proc=="edit"){ ?>
              <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK" id="TYPEREPAIRWORK1">
                <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                <?php
                  $AREA=$_SESSION["AD_AREA"];
                  $RPRQ_TYPEREPAIRWORK=$result_edit_repairrequest["RPRQ_TYPEREPAIRWORK"];
                  $sql_typerepair = "SELECT A.TRPW_ID,A.TRPW_NAME FROM TYPEREPAIRWORK A LEFT JOIN NATUREREPAIR B ON B.NTRP_ID = A.NTRP_ID WHERE A.TRPW_STATUS='Y' AND A.TRPW_NAME LIKE '%BM%' AND A.TRPW_AREA = '$AREA' AND A.TRPW_ID = '$RPRQ_TYPEREPAIRWORK'
                  UNION
                  SELECT A.TRPW_ID,A.TRPW_NAME FROM TYPEREPAIRWORK A LEFT JOIN NATUREREPAIR B ON B.NTRP_ID = A.NTRP_ID WHERE A.TRPW_STATUS='Y' AND A.TRPW_NAME LIKE '%BM%' AND A.TRPW_AREA = '$AREA' AND A.TRPW_REMARK = '$SUBJECT'";
                  $query_typerepair = sqlsrv_query($conn, $sql_typerepair);
                  while ($result_typerepair = sqlsrv_fetch_array($query_typerepair, SQLSRV_FETCH_ASSOC)) {
                ?>
                <option value="<?php echo $result_typerepair["TRPW_ID"];?>" <?php if($result_edit_repairrequest["RPRQ_TYPEREPAIRWORK"] == $result_typerepair["TRPW_ID"]){echo "selected";} ?>><?=$result_typerepair['TRPW_NAME']?></option>
                <?php } ?>
            <?php }else{ ?>
              <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK1">
                <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
            <?php } ?>  
            </select>
          </div>
        </div>&ensp;
        <div class="col-md-8">
          <div class="input-control text" style="width:600px;margin-right:3px">
            <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $RPC_DETAIL;?>" placeholder="รายละเอียด" autocomplete="off" >
          </div>
        </div>&ensp;
        <div class="col-md-2">
          <div class="input-control text" style="width:200px;width:10%;">
            <?php if($proc=="edit"){ ?>
              <input type="file" name="RPC_IMAGES[]" multiple id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif" value="<?php echo $result_edit_repairrequest["RPC_IMAGES"];?>"/>
            <?php }else{ ?>                
              <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
              <input type="file" name="RPC_IMAGES_img1[]" multiple id="RPC_IMAGES_img1" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif" value="<?php echo $result_edit_repairrequest["RPC_IMAGES"];?>"/>
            <?php } ?>  
          </div>   
        </div>
      </div>
      <?php if($proc=="edit"){ ?>    
        <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">        
            <td align="center" >
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
                  <img src="<?=$path?>uploads/requestrepair/<?=$result_causeimage["RPCIM_IMAGES"];?>" width="80px" height="80px" style="cursor:pointer;border:5px solid #555;" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_imagerepairview.php','edit','<?=$RPRQ_CODE;?>&subject=<?=$RPC_SUBJECT;?>&image=<?=$RPCIM_IMAGES?>','1=1','1000','700','รูปภาพแจ้งซ่อม');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php } ?>
              </font>  
            </td>
        </table> 
      <?php } ?>
      <br><br>
      <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
      <input type="hidden" name="RPRQ_WORKTYPE" id="RPRQ_WORKTYPE" value="BM" />
      <input type="hidden" name="RPRQ_NUMBER" id="RPRQ_NUMBER" value="SPP" />
      <input type="hidden" name="RPSPP_CODE" id="RPSPP_CODE" value="<?=$RPSPP_CODE;?>" />
      <input type="hidden" name="RPRQ_AREA" id="RPRQ_AREA" value="<?=$_SESSION["AD_AREA"];?>" />
      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
        <tbody>    
          <tr align="center" height="25px">
            <td height="35" colspan="2" align="center">
              <?php if($proc=="edit"){ ?>
                <input type="hidden" name="RPRQ_CODE" id="RPRQ_CODE" value="<?=$result_edit_repairrequest["RPRQ_CODE"];?>">
                <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onclick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
              <?php }else{ ?>
                
                <button class="bg-color-green font-white" type="button" name="buttonnameadd" id="buttonnameadd" value="add">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
              <?php } ?>
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
<script>  
    $('.datepic').datetimepicker({
        lang:'th',
        format:'d/m/Y H:i',
    });
</script>