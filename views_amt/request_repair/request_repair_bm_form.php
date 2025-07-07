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
	if(($proc=="edit")||($proc=="copy")){
		$stmt = "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_CODE = ?";
		$params = array($ru_id);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_edit_repairrequest = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $RPRQ_CODE=$result_edit_repairrequest["RPRQ_CODE"];
    $SUBJECT=$result_edit_repairrequest["RPC_SUBJECT"];
	};
	if($proc=="add"){
    // SQL
	};  
	if($proc=="edit"){
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
  
  function autocomplete_regishead_center($CONDI) {
    $path = "../";   	
    require($path.'../include/connect.php');
    $data = "";
    $sql = "SELECT * FROM vwVEHICLEINFO_CARPOOL WHERE $CONDI";
    $query = sqlsrv_query($conn, $sql);
    while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $data .= "'".$result['VEHICLEREGISNUMBER']."',";
    }
    return rtrim($data, ",");
  }
  $rqrp_regishead_center = autocomplete_regishead_center("ACTIVESTATUS = '1' AND VEHICLEGROUPDESC = 'Transport'");
  // echo $rqrp_regishead_center;
  
  function autocomplete_regisheadname_center($CONDI) {
    $path = "../";   	
    require($path.'../include/connect.php');
    $data = "";
    $sql = "SELECT * FROM vwVEHICLEINFO_CARPOOL WHERE $CONDI";
    $query = sqlsrv_query($conn, $sql);
    while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $data .= "'".$result['THAINAME']."',";
    }
    return rtrim($data, ",");
  }
  $rqrp_regisheadname_center = autocomplete_regisheadname_center("ACTIVESTATUS = '1' AND VEHICLEGROUPDESC = 'Transport'");
  // echo $rqrp_regisheadname_center;
  
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
  <script type="text/javascript">
    $(document).ready(function(e) {
      var source5 = [<?= $rqrp_employee_sq ?>];
      AutoCompleteNormal("RPRQ_CREATENAME", source5);  
			var typecus = $("#TYPECUSTOMERS").val();
      changetypecus()
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
        var maxmile = document.querySelector("#MAXMILEAGENUMBER");
        maxmile.classList.replace("char", "readonly");
      }else if(typecus === "cusout"){
        var source1_out = [<?= $rqrp_regishead_out ?>];
        AutoCompleteNormal("VEHICLEREGISNUMBER1", source1_out); 
        var source2_out = [<?= $rqrp_regisheadname_out ?>];
        AutoCompleteNormal("THAINAME1", source2_out);  
        var source3_out = [<?= $rqrp_regishead_out ?>];
        AutoCompleteNormal("VEHICLEREGISNUMBER2", source3_out); 
        var source4_out = [<?= $rqrp_regisheadname_out ?>];
        AutoCompleteNormal("THAINAME2", source4_out); 
        var maxmile_out = document.querySelector("#MAXMILEAGENUMBER");
        maxmile_out.classList.replace("readonly", "char");
        $("input[name='MAXMILEAGENUMBER']").removeAttr( "readonly" ); 
      }else if(typecus === "center"){
        var source1_center = [<?= $rqrp_regishead_center ?>];
        AutoCompleteNormal("VEHICLEREGISNUMBER1", source1_center); 
        var source2_center = [<?= $rqrp_regisheadname_center ?>];
        AutoCompleteNormal("THAINAME1", source2_center);  
        var source3_center = [<?= $rqrp_regishead_center ?>];
        AutoCompleteNormal("VEHICLEREGISNUMBER2", source3_center); 
        var source4_center = [<?= $rqrp_regisheadname_center ?>];
        AutoCompleteNormal("THAINAME2", source4_center); 
        var maxmile_center = document.querySelector("#MAXMILEAGENUMBER");
        maxmile_center.classList.replace("readonly", "char");
        $("input[name='MAXMILEAGENUMBER']").removeAttr( "readonly" ); 
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
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเลือกประเภทลูกค้า',
                showConfirmButton: false,
                timer: 1500,
                onAfterClose: () => {
                    setTimeout(() => $("#TYPECUSTOMERS").focus(), 0);
                }
            })
            // alert("กรุณาเลือกลูกค้า");
            // document.getElementById('TYPECUSTOMERS').focus(); 
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
              typecus: typecus,
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
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเลือกประเภทลูกค้า',
                showConfirmButton: false,
                timer: 1500,
                onAfterClose: () => {
                    setTimeout(() => $("#TYPECUSTOMERS").focus(), 0);
                }
            })
            // document.getElementById('TYPECUSTOMERS').focus(); 
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
              typecus: typecus,
              thainame: document.getElementById('THAINAME1').value
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
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเลือกประเภทลูกค้า',
                showConfirmButton: false,
                timer: 1500,
                onAfterClose: () => {
                    setTimeout(() => $("#TYPECUSTOMERS").focus(), 0);
                }
            })
            // document.getElementById('TYPECUSTOMERS').focus(); 
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
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเลือกประเภทลูกค้า',
                showConfirmButton: false,
                timer: 1500,
                onAfterClose: () => {
                    setTimeout(() => $("#TYPECUSTOMERS").focus(), 0);
                }
            })
            // document.getElementById('TYPECUSTOMERS').focus(); 
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
                // ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','edit','<?php print $RPRQ_CODE; ?>','1=1','1350','670','แก้ไขใบแจ้งซ่อม');
                loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_bm.php');
                closeUI();
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
        // ปิดวันที่ 15032024
          // if($('#VEHICLEREGISNUMBER1').val() == '' ){
          //   Swal.fire({
          //       icon: 'warning',
          //       title: 'กรุณาระบุเลขทะเบียนรถ',
          //       showConfirmButton: false,
          //       timer: 1500,
          //       onAfterClose: () => {
          //           setTimeout(() => $("#VEHICLEREGISNUMBER1").focus(), 0);
          //       }
          //   })
          //   // alert("กรุณาระบุเลขทะเบียนรถ");
          //   // document.getElementById('VEHICLEREGISNUMBER1').focus();
          //   return false;
          // }		
          // if($('#THAINAME1').val() == '' ){
          //   Swal.fire({
          //       icon: 'warning',
          //       title: 'กรุณาระบุชื่อรถ',
          //       showConfirmButton: false,
          //       timer: 1500,
          //       onAfterClose: () => {
          //           setTimeout(() => $("#THAINAME1").focus(), 0);
          //       }
          //   })
          //   // alert("กรุณาระบุชื่อรถ");
          //   // document.getElementById('THAINAME1').focus();
          //   return false;
          // }	
        if($('#GOTC').val() == '' ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาเลือกสินค้าบนรถ',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#GOTC").focus(), 0);
              }
          })
          // alert("กรุณาเลือกสินค้าบนรถ");
          return false;
        }
        if($('#NTORNNW').val() == '' ){
          Swal.fire({
              icon: 'warning',
              title: 'กรุณาเลือกลักษณะการวิ่งงาน',
              showConfirmButton: false,
              timer: 1500,
              onAfterClose: () => {
                  setTimeout(() => $("#NTORNNW").focus(), 0);
              }
          })
          // alert("กรุณาเลือกลักษณะการวิ่งงาน");
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
    function select_grouptype(){ 
      // alert("กรุณาเลือกลูกค้า");  
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
    // RPM_NATUREREPAIR to TYPEREPAIRWORK 1-10 ###########################################
      $('#RPM_NATUREREPAIR1').change(function() {
        var id_nature = $(this).val();
        $.ajax({
          type: "POST",
          url: "../autocomplete/request_repair_typenature.php",
          data: {trpw_id:id_nature,function:'RPM_NATUREREPAIR'},
          success: function(data){$('#TYPEREPAIRWORK1').html(data);}
        });
      });
      $('#RPM_NATUREREPAIR2').change(function() {
        var id_nature = $(this).val();
        $.ajax({
          type: "POST",
          url: "../autocomplete/request_repair_typenature.php",
          data: {trpw_id:id_nature,function:'RPM_NATUREREPAIR'},
          success: function(data){$('#TYPEREPAIRWORK2').html(data);}
        });
      });
      $('#RPM_NATUREREPAIR3').change(function() {
        var id_nature = $(this).val();
        $.ajax({
          type: "POST",
          url: "../autocomplete/request_repair_typenature.php",
          data: {trpw_id:id_nature,function:'RPM_NATUREREPAIR'},
          success: function(data){$('#TYPEREPAIRWORK3').html(data);}
        });
      });
      $('#RPM_NATUREREPAIR4').change(function() {
        var id_nature = $(this).val();
        $.ajax({
          type: "POST",
          url: "../autocomplete/request_repair_typenature.php",
          data: {trpw_id:id_nature,function:'RPM_NATUREREPAIR'},
          success: function(data){$('#TYPEREPAIRWORK4').html(data);}
        });
      });
      $('#RPM_NATUREREPAIR5').change(function() {
        var id_nature = $(this).val();
        $.ajax({
          type: "POST",
          url: "../autocomplete/request_repair_typenature.php",
          data: {trpw_id:id_nature,function:'RPM_NATUREREPAIR'},
          success: function(data){$('#TYPEREPAIRWORK5').html(data);}
        });
      });
      $('#RPM_NATUREREPAIR6').change(function() {
        var id_nature = $(this).val();
        $.ajax({
          type: "POST",
          url: "../autocomplete/request_repair_typenature.php",
          data: {trpw_id:id_nature,function:'RPM_NATUREREPAIR'},
          success: function(data){$('#TYPEREPAIRWORK6').html(data);}
        });
      });
      $('#RPM_NATUREREPAIR7').change(function() {
        var id_nature = $(this).val();
        $.ajax({
          type: "POST",
          url: "../autocomplete/request_repair_typenature.php",
          data: {trpw_id:id_nature,function:'RPM_NATUREREPAIR'},
          success: function(data){$('#TYPEREPAIRWORK7').html(data);}
        });
      });
      $('#RPM_NATUREREPAIR8').change(function() {
        var id_nature = $(this).val();
        $.ajax({
          type: "POST",
          url: "../autocomplete/request_repair_typenature.php",
          data: {trpw_id:id_nature,function:'RPM_NATUREREPAIR'},
          success: function(data){$('#TYPEREPAIRWORK8').html(data);}
        });
      });
      $('#RPM_NATUREREPAIR9').change(function() {
        var id_nature = $(this).val();
        $.ajax({
          type: "POST",
          url: "../autocomplete/request_repair_typenature.php",
          data: {trpw_id:id_nature,function:'RPM_NATUREREPAIR'},
          success: function(data){$('#TYPEREPAIRWORK9').html(data);}
        });
      });
      $('#RPM_NATUREREPAIR10').change(function() {
        var id_nature = $(this).val();
        $.ajax({
          type: "POST",
          url: "../autocomplete/request_repair_typenature.php",
          data: {trpw_id:id_nature,function:'RPM_NATUREREPAIR'},
          success: function(data){$('#TYPEREPAIRWORK10').html(data);}
        });
      });
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
              <td width="24" valign="middle" class=""><img src="https://img2.pic.in.th/pic/Process-Info-icon32.png" width="32"
                  height="32"></td>
              <td valign="bottom" class="">
                <h4>&nbsp;&nbsp;แก้ไขใบแจ้งซ่อม</h4>
              </td>
              <?php }else if($proc=="copy"){ ?>
              <td width="24" valign="middle" class=""><img src="https://img2.pic.in.th/pic/plus-icon32.png" width="32" height="32">
              </td>
              <td valign="bottom" class="">
                <h4>&nbsp;&nbsp;คัดลอกใบแจ้งซ่อม</h4>
              </td>
              <?php }else{ ?>
              <td width="24" valign="middle" class=""><img src="https://img2.pic.in.th/pic/plus-icon32.png" width="32" height="32">
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
            <?php if($_SESSION["AD_ROLE_NAME"]!='DRIVER'){ ?>
              <tr align="center" height="25px">
                <td height="35" align="right" class="ui-state-default"><strong>ประเภทลูกค้า:</strong></td>
                <td height="35" align="left">
                  <div class="input-control text">                
                    <select class="char"  style="width: 100%;" name="TYPECUSTOMERS" id="TYPECUSTOMERS" onchange="changetypecus(this)">
                      <option value disabled selected>--เลือกประเภทลูกค้า--</option>
                      <?php
                        $AREA=$_SESSION["AD_AREA"];
                        $sql_ntrp = "SELECT NTRP_ID,NTRP_CODE,NTRP_NAME,NTRP_REMARK FROM NATUREREPAIR WHERE NTRP_NAME LIKE '%BM%' AND NTRP_STATUS = 'Y'	AND NTRP_LEVEL='1' AND NTRP_AREA = '$AREA'";
                        $query_ntrp = sqlsrv_query($conn, $sql_ntrp);
                        while ($result_ntrp = sqlsrv_fetch_array($query_ntrp, SQLSRV_FETCH_ASSOC)) {
                          $NTRP_ID = $result_ntrp["NTRP_ID"];
                          $sql_ntrp_sub = "SELECT CASE WHEN NTRP_NAME LIKE '%ภายใน%' THEN 'cusin' WHEN NTRP_NAME LIKE '%ภายนอก%' THEN 'cusout' WHEN NTRP_NAME LIKE '%กลาง%' THEN 'center' ELSE '0' END AS TYPE,NTRP_NAME FROM NATUREREPAIR WHERE NTRP_PARENT = ? AND NTRP_STATUS = 'Y' AND NTRP_LEVEL='2' AND NTRP_AREA = '$AREA' ORDER BY NTRP_NAME ASC";
                          $params_ntrp_sub = array($NTRP_ID);	
                          $query_ntrp_sub = sqlsrv_query( $conn, $sql_ntrp_sub, $params_ntrp_sub);	
                          while ($result_ntrp_sub = sqlsrv_fetch_array($query_ntrp_sub, SQLSRV_FETCH_ASSOC)) {
                      ?>
                      <option value="<?php echo $result_ntrp_sub["TYPE"];?>" <?php if($result_ntrp_sub["TYPE"] == $result_edit_repairrequest["RPRQ_TYPECUSTOMER"]){echo "selected";} ?>><?=$result_ntrp_sub['NTRP_NAME']?></option>
                      <?php }} ?>
                      
                      
                    </select>
                  </div>
                </td>
              </tr>        
            <?php }else{ ?> 
              <input type="hidden" name="TYPECUSTOMERS" id="TYPECUSTOMERS" value="cusin">
            <?php } ?> 
            <tr align="center" height="25px">
              <td height="35" align="right" class="ui-state-default"><strong>ทะเบียนรถ(หัว):</strong></td>
              <td height="35" align="left">
                <div class="input-control text">
                  <input type="text" name="VEHICLEREGISNUMBER1" id="VEHICLEREGISNUMBER1" placeholder="ระบุทะเบียนรถ(หัว)" value="<?php echo $result_edit_repairrequest["RPRQ_REGISHEAD"];?>" class="char" onchange="select_vehiclenumber1()" autocomplete="off">
                </div>
              </td>
              <td height="35" align="right" class="ui-state-default"><strong>ชื่อรถ(หัว):</strong></td>
              <td height="35" align="left">
                <div class="input-control text">
                  <input type="text" name="THAINAME1" id="THAINAME1" placeholder="ชื่อรถ(หัว)" value="<?php echo $result_edit_repairrequest["RPRQ_CARNAMEHEAD"];?>" class="char" onchange="select_thainame1()" autocomplete="off">
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
                  <input type="text" name="VEHICLEREGISNUMBER2" id="VEHICLEREGISNUMBER2" placeholder="ระบุทะเบียนรถ(หาง)" value="<?php echo $result_edit_repairrequest["RPRQ_REGISTAIL"];?>" class="char" onchange="select_vehiclenumber2()" onkeypress="select_vehiclenumber2()" onkeyup="select_vehiclenumber2()" onkeydown="select_vehiclenumber2()" autocomplete="off">
                </div>
              </td>
              <td height="35" align="right" class="ui-state-default"><strong>ชื่อรถ(หาง):</strong></td>
              <td height="35" align="left">
                <div class="input-control text">
                  <input type="text" name="THAINAME2" id="THAINAME2" placeholder="ชื่อรถ(หาง)" value="<?php echo $result_edit_repairrequest["RPRQ_CARNAMETAIL"];?>" class="char" onchange="select_thainame2()" onkeypress="select_thainame2()" onkeyup="select_thainame2()" onkeydown="select_thainame2()" autocomplete="off">
                </div>
              </td>
            </tr>  
            <tr align="center" height="25px">
              <td height="35" align="right" class="ui-state-default"><strong>สินค้าบนรถ:</strong></td>
              <td height="35" align="left">
                <div class="input-control text">      
                  <select class="char"  style="width: 100%;" name="GOTC" id="GOTC">
                    <option value disabled selected>--เลือกสินค้าบนรถ--</option>
                    <option value="ost" <?php if($result_edit_repairrequest['RPRQ_PRODUCTINCAR']=="ost"){echo "selected";} ?>>ไม่มี</option>
                    <option value="ist" <?php if($result_edit_repairrequest['RPRQ_PRODUCTINCAR']=="ist"){echo "selected";} ?>>มี</option>
                  </select>
                </div>
              </td>
              <td height="35" align="right" class="ui-state-default"><strong>ลักษณะการวิ่งงาน:</strong></td>
              <td height="35" align="left">
                <div class="input-control text">                
                  <select class="char"  style="width: 100%;" name="NTORNNW" id="NTORNNW">
                    <option value disabled selected>--เลือกลักษณะการวิ่งงาน--</option>
                    <option value="bfw" <?php if($result_edit_repairrequest['RPRQ_NATUREREPAIR']== "bfw"){echo "selected";} ?>>ก่อนปฏิบัติงาน</option>
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
        <div class="row"> 
          <div class="col-md-2">
            <div class="input-control text" style="width:100px;"> 
              <center>
              <?php if($proc!="edit"){ ?>           
                <span><img src="https://img2.pic.in.th/pic/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow2()"></span>
              <?php } ?>
              </center>
            </div>  
          </div>
          <div class="col-md-10">
            <div class="input-control text" style="width:170px;">   
              <?php if($proc=="edit"){ ?>
                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR" id="RPM_NATUREREPAIR1">
              <?php }else{ ?>
                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR1">
              <?php } ?>      
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
          <div class="col-md-2">
            <div class="input-control text" style="width:500px;">   
              <?php if($proc=="edit"){ ?>
                <input type="text" name="RPC_DETAIL" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
              <?php }else{ ?>
                <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
              <?php } ?>  
            </div>
          </div>&ensp;
          <div class="col-md-2">
            <div class="input-control text" style="width:10%;">
              <?php if($proc=="edit"){ ?> 
                <input type="file" name="RPC_IMAGES[]" multiple id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif" value="<?php echo $result_edit_repairrequest["RPC_IMAGES"];?>"/>
              <?php }else{ ?>
                <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                <input type="file" name="RPC_IMAGES_img1[]" multiple id="RPC_IMAGES_img1" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif" value="<?php echo $result_edit_repairrequest["RPC_IMAGES"];?>"/>
              <?php } ?>  
            </div>   
          </div>
        </div>
        <?php if($proc!="edit"){ ?>
          <div id="showrow2_div" style="display: none"><br>
            <div class="row"> 
              <div class="col-md-2">
                <div class="input-control text" style="width:100px;"> 
                  <center>
                    <span><img src="https://img2.pic.in.th/pic/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow3()"></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><img src="https://img2.pic.in.th/pic/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow2()" title="ลบรายการ"></span>
                  </center>
                </div>  
              </div>
              <div class="col-md-10">
                <div class="input-control text" style="width:170px;">   
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR2">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:200px;">   
                  <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK2">
                    <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:500px;">   
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:10%;">
                  <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                  <input type="file" name="RPC_IMAGES_img2[]" multiple id="RPC_IMAGES_img2" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>   
              </div>
            </div>
          </div> 
          <div id="showrow3_div" style="display: none"><br>
            <div class="row"> 
              <div class="col-md-2">
                <div class="input-control text" style="width:100px;"> 
                  <center>
                    <span><img src="https://img2.pic.in.th/pic/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow4()"></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><img src="https://img2.pic.in.th/pic/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow3()" title="ลบรายการ"></span>
                  </center>
                </div>  
              </div>
              <div class="col-md-10">
                <div class="input-control text" style="width:170px;">   
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR3">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:200px;">   
                  <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK3">
                    <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:500px;">   
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:10%;">
                  <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                  <input type="file" name="RPC_IMAGES_img3[]" multiple id="RPC_IMAGES_img3" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>   
              </div>
            </div>
          </div> 
          <div id="showrow4_div" style="display: none"><br>       
            <div class="row"> 
              <div class="col-md-2">
                <div class="input-control text" style="width:100px;"> 
                  <center>
                    <span><img src="https://img2.pic.in.th/pic/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow5()"></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><img src="https://img2.pic.in.th/pic/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow4()" title="ลบรายการ"></span>
                  </center>
                </div>  
              </div>
              <div class="col-md-10">
                <div class="input-control text" style="width:170px;">   
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR4">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:200px;">   
                  <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK4">
                    <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:500px;">   
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:10%;">
                  <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                  <input type="file" name="RPC_IMAGES_img4[]" multiple id="RPC_IMAGES_img4" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>   
              </div>
            </div>
          </div> 
          <div id="showrow5_div" style="display: none"><br>
            <div class="row"> 
              <div class="col-md-2">
                <div class="input-control text" style="width:100px;"> 
                  <center>
                    <span><img src="https://img2.pic.in.th/pic/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow6()"></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><img src="https://img2.pic.in.th/pic/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow5()" title="ลบรายการ"></span>
                  </center>
                </div>  
              </div>
              <div class="col-md-10">
                <div class="input-control text" style="width:170px;">   
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR5">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:200px;">   
                  <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK5">
                    <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:500px;">   
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:10%;">
                  <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                  <input type="file" name="RPC_IMAGES_img5[]" multiple id="RPC_IMAGES_img5" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>   
              </div>
            </div>
          </div> 
          <div id="showrow6_div" style="display: none"><br>
            <div class="row"> 
              <div class="col-md-2">
                <div class="input-control text" style="width:100px;"> 
                  <center>                  
                    <span><img src="https://img2.pic.in.th/pic/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow7()"></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><img src="https://img2.pic.in.th/pic/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow6()" title="ลบรายการ"></span>
                  </center>
                </div>  
              </div>
              <div class="col-md-10">
                <div class="input-control text" style="width:170px;">   
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR6">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:200px;">   
                  <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK6">
                    <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:500px;">   
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:10%;">
                  <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                  <input type="file" name="RPC_IMAGES_img6[]" multiple id="RPC_IMAGES_img6" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>   
              </div>
            </div>
          </div> 
          <div id="showrow7_div" style="display: none"><br>
            <div class="row"> 
              <div class="col-md-2">
                <div class="input-control text" style="width:100px;"> 
                  <center>
                    <span><img src="https://img2.pic.in.th/pic/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow8()"></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><img src="https://img2.pic.in.th/pic/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow7()" title="ลบรายการ"></span>
                  </center>
                </div>  
              </div>
              <div class="col-md-10">
                <div class="input-control text" style="width:170px;">   
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR7">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:200px;">   
                  <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK7">
                    <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:500px;">   
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:10%;">
                  <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                  <input type="file" name="RPC_IMAGES_img7[]" multiple id="RPC_IMAGES_img7" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>   
              </div>
            </div> 
          </div> 
          <div id="showrow8_div" style="display: none"><br>
            <div class="row"> 
              <div class="col-md-2">
                <div class="input-control text" style="width:100px;"> 
                  <center>
                    <span><img src="https://img2.pic.in.th/pic/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow9()"></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><img src="https://img2.pic.in.th/pic/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow8()" title="ลบรายการ"></span>
                  </center>
                </div>  
              </div>
              <div class="col-md-10">
                <div class="input-control text" style="width:170px;">   
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR8">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:200px;">   
                  <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK8">
                    <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:500px;">   
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:10%;">
                  <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                  <input type="file" name="RPC_IMAGES_img8[]" multiple id="RPC_IMAGES_img8" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>   
              </div>
            </div> 
          </div> 
          <div id="showrow9_div" style="display: none"><br>
            <div class="row"> 
              <div class="col-md-2">
                <div class="input-control text" style="width:100px;"> 
                  <center>                  
                    <span><img src="https://img2.pic.in.th/pic/add-icon16.png" width="20" height="20" style="cursor:pointer" title="เพิ่มรายการ" onclick="showrow10()"></span>
                    &nbsp;&nbsp;&nbsp;
                    <span><img src="https://img2.pic.in.th/pic/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow9()" title="ลบรายการ"></span>
                  </center>
                </div>  
              </div>
              <div class="col-md-10">
                <div class="input-control text" style="width:170px;">   
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR9">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:200px;">   
                  <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK9">
                    <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:500px;">   
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:10%;">
                  <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                  <input type="file" name="RPC_IMAGES_img9[]" multiple id="RPC_IMAGES_img9" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>   
              </div>
            </div> 
          </div> 
          <div id="showrow10_div" style="display: none"><br>
            <div class="row"> 
              <div class="col-md-2">
                <div class="input-control text" style="width:100px;"> 
                  <center>   
                    <span><img src="https://img2.pic.in.th/pic/Action-remove-icon16.png" width="22" height="22" style="cursor:pointer" onclick="hiderow10()" title="ลบรายการ"></span>
                  </center>
                </div>  
              </div>
              <div class="col-md-10">
                <div class="input-control text" style="width:170px;">   
                  <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR10">
                    <option value disabled selected>--เลือกลักษณะงานซ่อม--</option>
                    <option value="EL">ระบบไฟ</option>
                    <option value="TU">ยาง ช่วงล่าง</option>
                    <option value="BD">โครงสร้าง</option>
                    <option value="EG">เครื่องยนต์</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:200px;">   
                  <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK10">
                    <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                  </select>
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:500px;">   
                  <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="" placeholder="รายละเอียด" autocomplete="off" >
                </div>
              </div>&ensp;
              <div class="col-md-2">
                <div class="input-control text" style="width:10%;">
                  <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                  <input type="file" name="RPC_IMAGES_img10[]" multiple id="RPC_IMAGES_img10" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                </div>   
              </div>
            </div> 
          </div> 
        <?php }  ?>
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
        <br>
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
                  
                  <input type="hidden" name="RPRQ_NAP_OLD_CODE" id="RPRQ_NAP_OLD_CODE" value="<?=$result_edit_repairrequest["RPRQ_CODE"];?>">
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
  input[type=file]::file-selector-button {
    margin-right: 20px;
    border: none;
    background: #0000FF;
    padding: 10px 20px;
    border-radius: 10px;
    color: #fff;
    cursor: pointer;
    transition: background .2s ease-in-out;
  }
  input[type=file]::file-selector-button:hover {
    background: #4169E1;
  }
</style>
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
</style>
<script>  
    $('.datepic').datetimepicker({
        lang:'th',
        format:'d/m/Y H:i',
    });
</script>