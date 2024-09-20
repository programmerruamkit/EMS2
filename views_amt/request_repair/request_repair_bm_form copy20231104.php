<?php
	session_start();
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
  
  function autocomplete_regishead($CONDI) {
    $path = "../";   	
    require($path.'../include/connect.php');
    $data = "";
    $sql = "SELECT * FROM vwVEHICLEINFO WHERE $CONDI";
    $query = sqlsrv_query($conn, $sql);
    while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $data .= "'".$result['VEHICLEREGISNUMBER']."',";
    }
    return rtrim($data, ",");
  }
  $rqrp_regishead = autocomplete_regishead("ACTIVESTATUS = '1' AND VEHICLEGROUPDESC = 'Transport'");
  // echo $rqrp_regishead;
  
  function autocomplete_regisheadname($CONDI) {
    $path = "../";   	
    require($path.'../include/connect.php');
    $data = "";
    $sql = "SELECT * FROM vwVEHICLEINFO WHERE $CONDI";
    $query = sqlsrv_query($conn, $sql);
    while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $data .= "'".$result['THAINAME']."',";
    }
    return rtrim($data, ",");
  }
  $rqrp_regisheadname = autocomplete_regisheadname("ACTIVESTATUS = '1' AND VEHICLEGROUPDESC = 'Transport'");
  // echo $rqrp_regisheadname;
  
  function autocomplete_regishead_out($CONDI) {
    $path = "../";   	
    require($path.'../include/connect.php');
    $data = "";
    $sql = "SELECT * FROM vwVEHICLEINFO_OUT WHERE $CONDI";
    $query = sqlsrv_query($conn, $sql);
    while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $data .= "'".$result['VEHICLEREGISNUMBER']."',";
    }
    return rtrim($data, ",");
  }
  $rqrp_regishead_out = autocomplete_regishead_out("ACTIVESTATUS = '1' AND VEHICLEGROUPDESC = 'Transport'");
  // echo $rqrp_regishead_out;
  
  function autocomplete_regisheadname_out($CONDI) {
    $path = "../";   	
    require($path.'../include/connect.php');
    $data = "";
    $sql = "SELECT * FROM vwVEHICLEINFO_OUT WHERE $CONDI";
    $query = sqlsrv_query($conn, $sql);
    while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $data .= "'".$result['THAINAME']."',";
    }
    return rtrim($data, ",");
  }
  $rqrp_regisheadname_out = autocomplete_regisheadname_out("ACTIVESTATUS = '1' AND VEHICLEGROUPDESC = 'Transport'");
  // echo $rqrp_regisheadname_out;
  
  function autocomplete_employee_sq($CONDI) {
    $path = "../";   	
    require($path.'../include/connect.php');
    $data = "";
    // $sql = "SELECT * FROM MENU WHERE MN_LEVEL='$KEYWORD' $CONDI";
    $sql = "SELECT * FROM vwPositionSQ WHERE $CONDI";
    $query = sqlsrv_query($conn, $sql);
    while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $data .= "'".$result['nameT']."',";
    }
    return rtrim($data, ",");
  }
  $AREA=$_SESSION["AD_AREA"];
  $rqrp_employee_sq = autocomplete_employee_sq("1=1 AND area = '$AREA'");
  // echo $rqrp_employee_sq;
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
    $(document).ready(function(e) {
      datepicker_thai('#dateRequest_in');
      datepicker_thai('#dateRequest_out'); 
      var source5 = [<?= $rqrp_employee_sq ?>];
      AutoCompleteNormal("RPRQ_CREATENAME", source5);  
    });    
       
		function changetypecus(){
			var typecus = $("#TYPECUSTOMERS").val();
      // alert(typecus)
      if(typecus === "cusin"){
        var source1 = [<?= $rqrp_regishead ?>];
        AutoCompleteNormal("VEHICLEREGISNUMBER1", source1); 
        var source2 = [<?= $rqrp_regisheadname ?>];
        AutoCompleteNormal("THAINAME1", source2);  
        var source3 = [<?= $rqrp_regishead ?>];
        AutoCompleteNormal("VEHICLEREGISNUMBER2", source3); 
        var source4 = [<?= $rqrp_regisheadname ?>];
        AutoCompleteNormal("THAINAME2", source4); 
      }else{
        var source1_out = [<?= $rqrp_regishead_out ?>];
        AutoCompleteNormal("VEHICLEREGISNUMBER1", source1_out); 
        var source2_out = [<?= $rqrp_regisheadname_out ?>];
        AutoCompleteNormal("THAINAME1", source2_out);  
        var source3_out = [<?= $rqrp_regishead_out ?>];
        AutoCompleteNormal("VEHICLEREGISNUMBER2", source3_out); 
        var source4_out = [<?= $rqrp_regisheadname_out ?>];
        AutoCompleteNormal("THAINAME2", source4_out); 
      }
			// var query = "?dateStart="+dateStart;
			// loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php'+query);
		}

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

    function select_vehiclenumber1(){         
      if($('#TYPECUSTOMERS').val() == '' ){
        window.setTimeout(function () { 
            alert("กรุณาเลือกลูกค้า");
            document.getElementById('TYPECUSTOMERS').focus(); 
            return false;
        }, 0); 
      }		
			var typecus = $("#TYPECUSTOMERS").val();    
      window.setTimeout(function () {     
        $.ajax({
          type: 'post',
          url: '../autocomplete/autocomplete_vehicle_request.php',
          data: {
            txt_flg: "select_vehiclenumber1", 
            typecus: typecus,
            vehiclenumber: document.getElementById('VEHICLEREGISNUMBER1').value
          },
          success: function (rs) {	
            // alert(rs)		
            var myArr = rs.split("|");                    
            document.getElementById('THAINAME1').value = myArr[2]; 
            document.getElementById('RPRQ_CARTYPE').value = myArr[3];           
            document.getElementById('AFFCOMPANY').value = myArr[4];          
          }
        });      
        $.ajax({
          type: 'post',
          url: '../autocomplete/autocomplete_vehicle_request.php',
          data: {
              txt_flg: "select_maxmileage", 
              vehiclenumber: document.getElementById('VEHICLEREGISNUMBER1').value
          },
          success: function (rs) {
              document.getElementById("MAXMILEAGENUMBER").value = rs;
          }
        });
      }, 100);
    }
    
    function select_thainame1(){    
      if($('#TYPECUSTOMERS').val() == '' ){
        window.setTimeout(function () { 
            document.getElementById('TYPECUSTOMERS').focus(); 
        }, 0); 
      }	
			var typecus = $("#TYPECUSTOMERS").val(); 
      window.setTimeout(function () {        
        $.ajax({
          type: 'post',
          url: '../autocomplete/autocomplete_vehicle_request.php',
          data: {
            txt_flg: "select_thainame1", 
            typecus: typecus,
            thainame: document.getElementById('THAINAME1').value
          },
          success: function (rs) {			
            var myArr = rs.split("|");                    
            document.getElementById('VEHICLEREGISNUMBER1').value = myArr[1]; 
            document.getElementById('RPRQ_CARTYPE').value = myArr[3];           
            document.getElementById('AFFCOMPANY').value = myArr[4];          
          }
        });      
        $.ajax({
          type: 'post',
          url: '../autocomplete/autocomplete_vehicle_request.php',
          data: {
              txt_flg: "select_maxmileage", 
              vehiclenumber: document.getElementById('VEHICLEREGISNUMBER1').value
          },
          success: function (rs) {
              document.getElementById("MAXMILEAGENUMBER").value = rs;
          }
        });
      }, 100);
    }

    function select_vehiclenumber2(){    
      if($('#TYPECUSTOMERS').val() == '' ){
        window.setTimeout(function () { 
            document.getElementById('TYPECUSTOMERS').focus(); 
        }, 0); 
      }	
			var typecus = $("#TYPECUSTOMERS").val(); 
      window.setTimeout(function () {        
        $.ajax({
          type: 'post',
          url: '../autocomplete/autocomplete_vehicle_request.php',
          data: {
            txt_flg: "select_vehiclenumber2", 
            typecus: typecus,
            vehiclenumber: document.getElementById('VEHICLEREGISNUMBER2').value
          },
          success: function (rs) {			
            var myArr = rs.split("|");                    
            document.getElementById('THAINAME2').value = myArr[2];     
          }
        });      
      }, 100);
    }
    
    function select_thainame2(){    
      if($('#TYPECUSTOMERS').val() == '' ){
        window.setTimeout(function () { 
            document.getElementById('TYPECUSTOMERS').focus(); 
        }, 0); 
      }	
			var typecus = $("#TYPECUSTOMERS").val(); 
      window.setTimeout(function () {        
        $.ajax({
          type: 'post',
          url: '../autocomplete/autocomplete_vehicle_request.php',
          data: {
            txt_flg: "select_thainame2", 
            typecus: typecus,
            thainame: document.getElementById('THAINAME2').value
          },
          success: function (rs) {			
            var myArr = rs.split("|");                    
            document.getElementById('VEHICLEREGISNUMBER2').value = myArr[1];    
          }
        });      
      }, 100);
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
    
    function save_data() {   
      var formData = new FormData($("#input_request")[0]);           
      var file_data = $('#RPC_IMAGES')[0].files;
      formData.append('file',file_data[0]);
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
                loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_bm.php');
                closeUI();
              }
            });
          })	
        }
      })
    }
    modify_row('request_piece_detail');
    
    // INSERT
    $(document).ready(function(){
      $("#buttonnameadd").click(function(){   
        if($('#VEHICLEREGISNUMBER1').val() == '' ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาระบุเลขทะเบียนรถ',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#VEHICLEREGISNUMBER1").focus(), 0);
              }
          })
          // alert("กรุณาระบุเลขทะเบียนรถ");
          // document.getElementById('VEHICLEREGISNUMBER1').focus();
          return false;
        }		
        if($('#THAINAME1').val() == '' ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาระบุชื่อรถ',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#THAINAME1").focus(), 0);
              }
          })
          // alert("กรุณาระบุชื่อรถ");
          // document.getElementById('THAINAME1').focus();
          return false;
        }	
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
        if($('#dateRequest_in').val() == 0 ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาระบุวันที่นำรถเข้าซ่อม',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#dateRequest_in").focus(), 0);
              }
          })
          // alert("กรุณาระบุวันที่นำรถเข้าซ่อม");
          // document.getElementById('dateRequest_in').focus();
          return false;
        }		
        if($('#timeRequest_in').val() == 0 ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาระบุเวลานำรถเข้าซ่อม',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#timeRequest_in").focus(), 0);
              }
          })
          // alert("กรุณาระบุเวลานำรถเข้าซ่อม");
          // document.getElementById('timeRequest_in').focus();
          return false;
        } 
        if($('#dateRequest_out').val() == 0 ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาระบุวันที่ต้องการใช้รถ',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#dateRequest_out").focus(), 0);
              }
          })
          // alert("กรุณาระบุวันที่ต้องการใช้รถ");
          // document.getElementById('dateRequest_out').focus();
          return false;
        }		
        if($('#timeRequest_out').val() == 0 ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาระบุเวลาต้องการใช้รถ',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#timeRequest_out").focus(), 0);
              }
          })
          // alert("กรุณาระบุเวลาต้องการใช้รถ");
          // document.getElementById('timeRequest_out').focus();
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

    // showrow2 - 10 #####################################################################
      function showrow2() {
        var showrow2_div = document.getElementById("showrow2_div");
        if (showrow2_div.style.display === "none") {
          showrow2_div.style.display = "block";
        }
      }
      function hiderow2() {
        var hiderow2_div = document.getElementById("showrow2_div");
        if (hiderow2_div.style.display === "block") {
          hiderow2_div.style.display = "none";
        }
      }    
      function showrow3() {
        var showrow3_div = document.getElementById("showrow3_div");
        if (showrow3_div.style.display === "none") {
          showrow3_div.style.display = "block";
        }
      }
      function hiderow3() {
        var hiderow3_div = document.getElementById("showrow3_div");
        if (hiderow3_div.style.display === "block") {
          hiderow3_div.style.display = "none";
        }
      }
      function showrow4() {
        var showrow4_div = document.getElementById("showrow4_div");
        if (showrow4_div.style.display === "none") {
          showrow4_div.style.display = "block";
        }
      }
      function hiderow4() {
        var hiderow4_div = document.getElementById("showrow4_div");
        if (hiderow4_div.style.display === "block") {
          hiderow4_div.style.display = "none";
        }
      }
      function showrow5() {
        var showrow5_div = document.getElementById("showrow5_div");
        if (showrow5_div.style.display === "none") {
          showrow5_div.style.display = "block";
        }
      }
      function hiderow5() {
        var hiderow5_div = document.getElementById("showrow5_div");
        if (hiderow5_div.style.display === "block") {
          hiderow5_div.style.display = "none";
        }
      }
      function showrow6() {
        var showrow6_div = document.getElementById("showrow6_div");
        if (showrow6_div.style.display === "none") {
          showrow6_div.style.display = "block";
        }
      }
      function hiderow6() {
        var hiderow6_div = document.getElementById("showrow6_div");
        if (hiderow6_div.style.display === "block") {
          hiderow6_div.style.display = "none";
        }
      }
      function showrow7() {
        var showrow7_div = document.getElementById("showrow7_div");
        if (showrow7_div.style.display === "none") {
          showrow7_div.style.display = "block";
        }
      }
      function hiderow7() {
        var hiderow7_div = document.getElementById("showrow7_div");
        if (hiderow7_div.style.display === "block") {
          hiderow7_div.style.display = "none";
        }
      }
      function showrow8() {
        var showrow8_div = document.getElementById("showrow8_div");
        if (showrow8_div.style.display === "none") {
          showrow8_div.style.display = "block";
        }
      }
      function hiderow8() {
        var hiderow8_div = document.getElementById("showrow8_div");
        if (hiderow8_div.style.display === "block") {
          hiderow8_div.style.display = "none";
        }
      }
      function showrow9() {
        var showrow9_div = document.getElementById("showrow9_div");
        if (showrow9_div.style.display === "none") {
          showrow9_div.style.display = "block";
        }
      }
      function hiderow9() {
        var hiderow9_div = document.getElementById("showrow9_div");
        if (hiderow9_div.style.display === "block") {
          hiderow9_div.style.display = "none";
        }
      }
      function showrow10() {
        var showrow10_div = document.getElementById("showrow10_div");
        if (showrow10_div.style.display === "none") {
          showrow10_div.style.display = "block";
        }
      }
      function hiderow10() {
        var hiderow10_div = document.getElementById("showrow10_div");
        if (hiderow10_div.style.display === "block") {
          hiderow10_div.style.display = "none";
        }
      }
    // ###################################################################################
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
              <h4>&nbsp;&nbsp;แก้ไขใบแจ้งซ่อม</h4>
            </td>
            <?php }else{ ?>
            <td width="24" valign="middle" class=""><img src="../images/plus-icon32.png" width="32" height="32">
            </td>
            <td valign="bottom" class="">
              <h4>&nbsp;&nbsp;เพิ่มใบแจ้งซ่อม</h4>
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
                <input type="text" name="RPRQ_ID" id="RPRQ_ID" disabled placeholder=""  onFocus="$(this).select();" value="<?php if($proc=="edit"){echo $result_edit_repairrequest["RPRQ_ID"];}else{echo "AUTO";}?>" readonly >
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>วันที่ใบแจ้งซ่อม:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="RPRQ_CREATEDATE_REQUEST" id="RPRQ_CREATEDATE_REQUEST" placeholder=""  onFocus="$(this).select();" value="<?php if($proc=="edit"){echo $result_edit_repairrequest["RPRQ_CREATEDATE_REQUEST"];}else{echo $GETDAYEN;}?>" class="readonly" readonly >
              </div>
            </td>
          </tr>
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>ประเภทลูกค้า:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">                
                <select class="char"  style="width: 100%;" name="TYPECUSTOMERS" id="TYPECUSTOMERS" onchange="changetypecus(this)">
                  <option value disabled selected>--เลือกประเภทลูกค้า--</option>
                  <option value="cusout" <?php if($result_edit_repairrequest['RPRQ_TYPECUSTOMER']== "cusout"){echo "selected";} ?>>ลูกค้าภายนอก</option>
                  <option value="cusin" <?php if($result_edit_repairrequest['RPRQ_TYPECUSTOMER']== "cusin"){echo "selected";} ?>>ลูกค้าภายใน</option>
                </select>
              </div>
            </td>
          </tr>         
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>ทะเบียนรถ(หัว):</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="VEHICLEREGISNUMBER1" id="VEHICLEREGISNUMBER1" placeholder="ระบุทะเบียนรถ(หัว)" value="<?php echo $result_edit_repairrequest["RPRQ_REGISHEAD"];?>" class="char" onclick="select_vehiclenumber1()" onchange="select_vehiclenumber1()" onkeypress="select_vehiclenumber1()" onkeyup="select_vehiclenumber1()" onkeydown="select_vehiclenumber1()" onfocus="select_vehiclenumber1()" autocomplete="off">
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ชื่อรถ(หัว):</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="THAINAME1" id="THAINAME1" placeholder="ชื่อรถ(หัว)" value="<?php echo $result_edit_repairrequest["RPRQ_CARNAMEHEAD"];?>" class="char" onclick="select_thainame1()" onchange="select_thainame1()" onkeypress="select_thainame1()" onkeyup="select_thainame1()" onkeydown="select_thainame1()" onfocus="select_thainame1()" autocomplete="off">
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ประเภทรถ:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="RPRQ_CARTYPE" id="RPRQ_CARTYPE" placeholder="ประเภทรถ" onFocus="$(this).select();" value="<?php echo $result_edit_repairrequest["RPRQ_CARTYPE"];?>" class="readonly" readonly>
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ลูกค้า/สายงาน:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="AFFCOMPANY" id="AFFCOMPANY" placeholder="ลูกค้า/สายงาน" onFocus="$(this).select();" value="<?php echo $result_edit_repairrequest["RPRQ_LINEOFWORK"];?>" class="readonly" readonly>
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>เลขไมค์:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="MAXMILEAGENUMBER" id="MAXMILEAGENUMBER" placeholder="เลขไมค์" onFocus="$(this).select();" value="<?php echo $result_edit_repairrequest["RPRQ_MILEAGELAST"];?>" class="readonly" readonly>
              </div>
            </td>
          </tr>
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>ทะเบียนรถ(หาง):</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="VEHICLEREGISNUMBER2" id="VEHICLEREGISNUMBER2" placeholder="ระบุทะเบียนรถ(หาง)" value="<?php echo $result_edit_repairrequest["RPRQ_REGISTAIL"];?>" class="char" onclick="select_vehiclenumber2()" onchange="select_vehiclenumber2()" onkeypress="select_vehiclenumber2()" onkeyup="select_vehiclenumber2()" onkeydown="select_vehiclenumber2()" onfocus="select_vehiclenumber2()" autocomplete="off">
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>ชื่อรถ(หาง):</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                <input type="text" name="THAINAME2" id="THAINAME2" placeholder="ชื่อรถ(หาง)" value="<?php echo $result_edit_repairrequest["RPRQ_CARNAMETAIL"];?>" class="char" onclick="select_thainame2()" onchange="select_thainame2()" onkeypress="select_thainame2()" onkeyup="select_thainame2()" onkeydown="select_thainame2()" onfocus="select_thainame2()" autocomplete="off">
              </div>
            </td>
          </tr>  
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>สินค้าบนรถ:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">                
                <select class="char"  style="width: 100%;" name="GOTC" id="GOTC">
                  <option value disabled selected>--เลือกสินค้าบนรถ--</option>
                  <option value="ist" <?php if($result_edit_repairrequest['RPRQ_PRODUCTINCAR']== "ist"){echo "selected";} ?>>มี</option>
                  <option value="ost" <?php if($result_edit_repairrequest['RPRQ_PRODUCTINCAR']== "ost"){echo "selected";}else{echo "selected";} ?>>ไม่มี</option>
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
                <input type="hidden" name="RPRQ_REQUESTBY_SQ" id="RPRQ_REQUESTBY_SQ" style="width:50px;" value="<?php echo $result_edit_emp_create["PersonCode"];?>">
                <input type="text" name="RPRQ_CREATENAME" id="RPRQ_CREATENAME" style="width:200px;" value="<?php echo $result_edit_emp_create["nameT"];?>" placeholder="ผู้รับแจ้งซ่อม" class="char"   onclick="select_employee()" onchange="select_employee()" onkeypress="select_employee()" onkeyup="select_employee()" onkeydown="select_employee()" onfocus="select_employee()">
              </div>
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
                <input type="text" name="EMP_COM_RQRP" id="EMP_COM_RQRP" style="width:200px;" value="<?php if($proc=="edit"){echo $result_edit_emp_create["Company_Code"];}else{echo $_SESSION["AD_COMPANYCODE"];}?>" class="readonly" readonly placeholder="ฝ่าย">
              </div>
            </td>
          </tr> 
          <tr align="center" height="25px">
            <td height="35" align="right" class="ui-state-default"><strong>วันที่นำรถเข้าซ่อม:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">
                
<link rel="stylesheet" type="text/css" href="<?=$path?>controllers/datetimepicker/jquery.datetimepicker.css"/>
	<input type="text" class="some_class" value="" id="some_class_1"/>
<script src="<?=$path?>controllers/datetimepicker/jquery.js"></script>
<script src="<?=$path?>controllers/datetimepicker/jquery.datetimepicker.js"></script>
<script>
  
$('.some_class').datetimepicker();
</script>
                <input type="text" name="dateRequest_in" id="dateRequest_in" placeholder="วันที่" style="width:60%;" class="char" value="<?php echo $result_edit_repairrequest["RPRQ_REQUESTCARDATE"];?>" autocomplete="off" >
                <input type="text" name="timeRequest_in" id="timeRequest_in" list="avail" style="width:35%;" placeholder="เวลา" class="char" value="<?php echo $result_edit_repairrequest["RPRQ_REQUESTCARTIME"];?>" autocomplete="off" >
                  <datalist id="avail">
                    <option value="00:00">
                    <option value="01:00">
                    <option value="02:00">
                    <option value="03:00">
                    <option value="04:00">
                    <option value="05:00">
                    <option value="06:00">
                    <option value="07:00">
                    <option value="08:00">
                    <option value="09:00">
                    <option value="10:00">
                    <option value="11:00">
                    <option value="12:00">
                    <option value="13:00">
                    <option value="14:00">
                    <option value="15:00">
                    <option value="16:00">
                    <option value="17:00">
                    <option value="18:00">
                    <option value="19:00">
                    <option value="20:00">
                    <option value="21:00">
                    <option value="22:00">
                    <option value="23:00">
                  </datalist>
                  <!-- https://www.tutorialspoint.com/How-to-use-time-input-type-in-HTML -->            
              </div>
            </td>
            <td height="35" align="right" class="ui-state-default"><strong>วันที่ต้องการใช้รถ:</strong></td>
            <td height="35" align="left">
              <div class="input-control text">      
                <input type="text" name="dateRequest_out" id="dateRequest_out" placeholder="วันที่" style="width:60%;" class="char" value="<?php echo $result_edit_repairrequest["RPRQ_USECARDATE"];?>" autocomplete="off" >
                <input type="text" name="timeRequest_out" id="timeRequest_out" list="avail" style="width:35%;" placeholder="เวลา" class="char" value="<?php echo $result_edit_repairrequest["RPRQ_USECARTIME"];?>" autocomplete="off" >
                  <datalist id="avail">
                    <option value="00:00">
                    <option value="01:00">
                    <option value="02:00">
                    <option value="03:00">
                    <option value="04:00">
                    <option value="05:00">
                    <option value="06:00">
                    <option value="07:00">
                    <option value="08:00">
                    <option value="09:00">
                    <option value="10:00">
                    <option value="11:00">
                    <option value="12:00">
                    <option value="13:00">
                    <option value="14:00">
                    <option value="15:00">
                    <option value="16:00">
                    <option value="17:00">
                    <option value="18:00">
                    <option value="19:00">
                    <option value="20:00">
                    <option value="21:00">
                    <option value="22:00">
                    <option value="23:00">
                  </datalist>
                  <!-- https://www.tutorialspoint.com/How-to-use-time-input-type-in-HTML -->            
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
        <tr>
          <td width="4%" align="right">
            <?php if($proc=="add"){ ?>           
              <span><img src="../images/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow2()"></span>
              &nbsp;&nbsp;
            <?php } ?>
          </td>
          <td height="40" width="7%" >
            <div class="input-control text" style="margin-right:3px"> 
              <?php if($proc=="edit"){ ?>
                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR" id="RPM_NATUREREPAIR">
              <?php }else{ ?>
                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR">
              <?php } ?>      
                <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                <option value="EL" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "EL"){echo "selected";} ?>>ระบบไฟ</option>
                <option value="TU" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "TU"){echo "selected";} ?>>ยาง ช่วงล่าง</option>
                <option value="BD" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "BD"){echo "selected";} ?>>โครงสร้าง</option>
                <option value="EG" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "EG"){echo "selected";} ?>>เครื่องยนต์</option>
                <option value="AC" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "AC"){echo "selected";} ?>>อุปกรณ์ประจำรถ</option>
              </select>
            </div>
          </td>
          <td height="40" width="30%" >
            <div class="input-control text" style="margin-right:3px">   
              <?php if($proc=="edit"){ ?>
                <input type="text" name="RPC_DETAIL" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
              <?php }else{ ?>
                <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
              <?php } ?>  
            </div>
          </td>
          <td height="40" width="10%" >
            <div class="input-control text" style="width:150px;">            
              <?php if($proc=="edit"){ ?>
                <input type="file" name="RPC_IMAGES" id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif" value="<?php echo $result_edit_repairrequest["RPC_IMAGES"];?>"/>
              <?php }else{ ?>
                <input type="file" name="RPC_IMAGES[]" id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif" value="<?php echo $result_edit_repairrequest["RPC_IMAGES"];?>"/>
              <?php } ?>  
            </div>
          </td>
        </tr>
      </table> 
      <?php if($proc=="edit"){                 
          $RPC_IMAGES=$result_edit_repairrequest["RPC_IMAGES"];
          $explodes = explode('-', $RPC_IMAGES);
          $RPC_IMAGES2 = $explodes[1];
          if($RPC_IMAGES2!=""){
      ?>    
        <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">        
          <tr>
            <td align="center">
              <div class="input-control text">
                <input type="hidden" name="imageoldfile" id="imageoldfile" style="width:100px;" value="<?=$result_edit_repairrequest["RPC_IMAGES"];?>">
                <img src="<?=$path?>uploads/requestrepair/<?=$result_edit_repairrequest["RPC_IMAGES"];?>" width="100%" height="100%">
              </div>
            </td>
          </tr>
        </table> 
      <?php } } ?>
      <?php if($proc=="add"){ ?>
        <div id="showrow2_div" style="display: none">
          <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">     
            <tr>
              <td width="4%" align="center">
                <span><img src="../images/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow3()"></span>
                &nbsp;&nbsp;&nbsp;
                <span><img src="../images/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow2()" title="ลบรายการ"></span>
              </td>
              <td height="40" width="7%" >
                <div class="input-control text" style="margin-right:3px">     
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                    <option value="AC">อุปกรณ์ประจำรถ</option>
                  </select>
                </div>
              </td>
              <td height="40" width="30%" >
                <div class="input-control text" style="margin-right:3px">
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?=$result1['RPC_DETAIL'];?>" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </td>
              <td height="40" width="10%" >
                <div class="input-control text" style="width:150px;">
                  <input type="file" name="RPC_IMAGES[]" id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>
              </td>
            </tr>
          </table> 
        </div> 
        <div id="showrow3_div" style="display: none">
          <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">     
            <tr>
              <td width="4%" align="center">
                <span><img src="../images/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow4()"></span>
                &nbsp;&nbsp;&nbsp;
                <span><img src="../images/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow3()" title="ลบรายการ"></span>
              </td>
              <td height="40" width="7%" >
                <div class="input-control text" style="margin-right:3px">     
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "EL"){echo "selected";} ?>>ระบบไฟ</option>
                    <option value="TU" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "TU"){echo "selected";} ?>>ยาง ช่วงล่าง</option>
                    <option value="BD" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "BD"){echo "selected";} ?>>โครงสร้าง</option>
                    <option value="EG" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "EG"){echo "selected";} ?>>เครื่องยนต์</option>
                    <option value="AC" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "AC"){echo "selected";} ?>>อุปกรณ์ประจำรถ</option>
                  </select>
                </div>
              </td>
              <td height="40" width="30%" >
                <div class="input-control text" style="margin-right:3px">
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?=$result1['RPC_DETAIL'];?>" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </td>
              <td height="40" width="10%" >
                <div class="input-control text" style="width:150px;">
                  <input type="file" name="RPC_IMAGES[]" id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>
              </td>
            </tr>
          </table> 
        </div> 
        <div id="showrow4_div" style="display: none">
          <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">     
            <tr>
              <td width="4%" align="center">
                <span><img src="../images/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow5()"></span>
                &nbsp;&nbsp;&nbsp;
                <span><img src="../images/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow4()" title="ลบรายการ"></span>
              </td>
              <td height="40" width="7%" >
                <div class="input-control text" style="margin-right:3px">     
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "EL"){echo "selected";} ?>>ระบบไฟ</option>
                    <option value="TU" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "TU"){echo "selected";} ?>>ยาง ช่วงล่าง</option>
                    <option value="BD" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "BD"){echo "selected";} ?>>โครงสร้าง</option>
                    <option value="EG" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "EG"){echo "selected";} ?>>เครื่องยนต์</option>
                    <option value="AC" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "AC"){echo "selected";} ?>>อุปกรณ์ประจำรถ</option>
                  </select>
                </div>
              </td>
              <td height="40" width="30%" >
                <div class="input-control text" style="margin-right:3px">
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?=$result1['RPC_DETAIL'];?>" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </td>
              <td height="40" width="10%" >
                <div class="input-control text" style="width:150px;">
                  <input type="file" name="RPC_IMAGES[]" id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>
              </td>
            </tr>
          </table> 
        </div> 
        <div id="showrow5_div" style="display: none">
          <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">     
            <tr>
              <td width="4%" align="center">
                <span><img src="../images/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow6()"></span>
                &nbsp;&nbsp;&nbsp;
                <span><img src="../images/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow5()" title="ลบรายการ"></span>
              </td>
              <td height="40" width="7%" >
                <div class="input-control text" style="margin-right:3px">     
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                    <option value="AC">อุปกรณ์ประจำรถ</option>
                  </select>
                </div>
              </td>
              <td height="40" width="30%" >
                <div class="input-control text" style="margin-right:3px">
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?=$result1['RPC_DETAIL'];?>" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </td>
              <td height="40" width="10%" >
                <div class="input-control text" style="width:150px;">
                  <input type="file" name="RPC_IMAGES[]" id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>
              </td>
            </tr>
          </table> 
        </div> 
        <div id="showrow6_div" style="display: none">
          <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">     
            <tr>
              <td width="4%" align="center">
                <span><img src="../images/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow7()"></span>
                &nbsp;&nbsp;&nbsp;
                <span><img src="../images/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow6()" title="ลบรายการ"></span>
              </td>
              <td height="40" width="7%" >
                <div class="input-control text" style="margin-right:3px">     
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                    <option value="AC">อุปกรณ์ประจำรถ</option>
                  </select>
                </div>
              </td>
              <td height="40" width="30%" >
                <div class="input-control text" style="margin-right:3px">
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?=$result1['RPC_DETAIL'];?>" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </td>
              <td height="40" width="10%" >
                <div class="input-control text" style="width:150px;">
                  <input type="file" name="RPC_IMAGES[]" id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>
              </td>
            </tr>
          </table> 
        </div> 
        <div id="showrow7_div" style="display: none">
          <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">     
            <tr>
              <td width="4%" align="center">
                <span><img src="../images/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow8()"></span>
                &nbsp;&nbsp;&nbsp;
                <span><img src="../images/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow7()" title="ลบรายการ"></span>
              </td>
              <td height="40" width="7%" >
                <div class="input-control text" style="margin-right:3px">     
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                    <option value="AC">อุปกรณ์ประจำรถ</option>
                  </select>
                </div>
              </td>
              <td height="40" width="30%" >
                <div class="input-control text" style="margin-right:3px">
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?=$result1['RPC_DETAIL'];?>" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </td>
              <td height="40" width="10%" >
                <div class="input-control text" style="width:150px;">
                  <input type="file" name="RPC_IMAGES[]" id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>
              </td>
            </tr>
          </table> 
        </div> 
        <div id="showrow8_div" style="display: none">
          <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">     
            <tr>
              <td width="4%" align="center">
                <span><img src="../images/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow9()"></span>
                &nbsp;&nbsp;&nbsp;
                <span><img src="../images/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow8()" title="ลบรายการ"></span>
              </td>
              <td height="40" width="7%" >
                <div class="input-control text" style="margin-right:3px">     
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                    <option value="AC">อุปกรณ์ประจำรถ</option>
                  </select>
                </div>
              </td>
              <td height="40" width="30%" >
                <div class="input-control text" style="margin-right:3px">
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?=$result1['RPC_DETAIL'];?>" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </td>
              <td height="40" width="10%" >
                <div class="input-control text" style="width:150px;">
                  <input type="file" name="RPC_IMAGES[]" id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>
              </td>
            </tr>
          </table> 
        </div> 
        <div id="showrow9_div" style="display: none">
          <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">     
            <tr>
              <td width="4%" align="center">
                <span><img src="../images/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow10()"></span>
                &nbsp;&nbsp;&nbsp;
                <span><img src="../images/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow9()" title="ลบรายการ"></span>
              </td>
              <td height="40" width="7%" >
                <div class="input-control text" style="margin-right:3px">     
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                    <option value="AC">อุปกรณ์ประจำรถ</option>
                  </select>
                </div>
              </td>
              <td height="40" width="30%" >
                <div class="input-control text" style="margin-right:3px">
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?=$result1['RPC_DETAIL'];?>" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </td>
              <td height="40" width="10%" >
                <div class="input-control text" style="width:150px;">
                  <input type="file" name="RPC_IMAGES[]" id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>
              </td>
            </tr>
          </table> 
        </div> 
        <div id="showrow10_div" style="display: none">
          <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">     
            <tr>
              <td width="4%" align="right">
                <span><img src="../images/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow10()" title="ลบรายการ"></span>
                &nbsp;&nbsp;
              </td>
              <td height="40" width="7%" >
                <div class="input-control text" style="margin-right:3px">     
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                    <option value="AC">อุปกรณ์ประจำรถ</option>
                  </select>
                </div>
              </td>
              <td height="40" width="30%" >
                <div class="input-control text" style="margin-right:3px">
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?=$result1['RPC_DETAIL'];?>" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </td>
              <td height="40" width="10%" >
                <div class="input-control text" style="width:150px;">
                  <input type="file" name="RPC_IMAGES[]" id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>
              </td>
            </tr>
          </table> 
        </div> 
      <?php }  ?>
      <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
      <input type="hidden" name="RPRQ_WORKTYPE" id="RPRQ_WORKTYPE" value="BM" />
      <input type="hidden" name="RPRQ_AREA" id="RPRQ_AREA" value="<?=$_SESSION["AD_AREA"];?>" />
      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
        <tbody>    
          <tr align="center" height="25px">
            <td height="35" colspan="2" align="center">
              <?php if($proc=="edit"){ ?>
                <input type="hidden" name="RPRQ_CODE" id="RPRQ_CODE" value="<?=$result_edit_repairrequest["RPRQ_CODE"];?>">
                <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onClick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
              <?php }else{ ?>
                <input type="hidden" name="RPRQ_CODE" id="RPRQ_CODE" value="<?=$rand; ?>">
                <button class="bg-color-green font-white" type="button" name="buttonnameadd" id="buttonnameadd" value="add">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
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