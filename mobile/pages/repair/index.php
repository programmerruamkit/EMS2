<?php
	session_start ();
  $path = "../../";    
	require($path."include/connect.php");
	require($path."include/authen.php"); 
	require($path."include/head.php");		
	// print_r ($_SESSION);  
?>
<div id="page">
  <?php
    // echo"<pre>";
    // print_r($_GET);
    // echo"</pre>";
    if ($_GET['ISUS']!='1'){
      echo "<meta http-equiv='refresh' content='0;URL=../repair/?ISUS=1'>";
    }
  ?>
  
  <div class="header header-auto-show header-fixed header-logo-center">
      <a href="../" class="header-title"><?=$title?></a>
      <a href="#" data-menu="menu-main" class="header-icon header-icon-1"><i class="fas fa-bars"></i></a>
      <a href="#" data-toggle-theme class="header-icon header-icon-4 show-on-theme-dark"><i class="fas fa-sun"></i></a>
      <a href="#" data-toggle-theme class="header-icon header-icon-4 show-on-theme-light"><i class="fas fa-moon"></i></a>
  </div>

  <?php require($path."include/ftb.php"); ?>

  <div class="page-title page-title-fixed">
      <h1><?=$_SESSION['AD_NAMETHAI']?></h1>
      <a href="#" class="page-title-icon shadow-xl bg-theme color-theme show-on-theme-light" data-toggle-theme><i class="fa fa-moon"></i></a>
      <a href="#" class="page-title-icon shadow-xl bg-theme color-theme show-on-theme-dark" data-toggle-theme><i class="fa fa-lightbulb color-yellow-dark"></i></a>
      <a href="#" class="page-title-icon shadow-xl bg-theme color-theme" data-menu="menu-main"><i class="fa fa-bars"></i></a>
  </div>
  <div class="page-title-clear"></div>

  <div id="menu-main" class="menu menu-box-left rounded-0" data-menu-width="280" data-menu-active="nav-welcome" data-menu-load="<?=$path?>include/mnm.php"></div>
  <div id="menu-colors" class="menu menu-box-bottom rounded-m" data-menu-load="<?=$path?>include/mnc.php" data-menu-height="480"></div>

  <div class="page-content">        
    <div class="me-3">        
        <h5>
          <!-- รายการแจ้งซ่อมจากพนักงาน -->
            &nbsp;&nbsp;&nbsp;&nbsp;<img width="20" class="fluid-img rounded-m shadow-xl" src="https://img2.pic.in.th/pic/color_blue.png">&nbsp;สามารถแก้ไขได้
            &nbsp;&nbsp;&nbsp;&nbsp;<img width="20" class="fluid-img rounded-m shadow-xl" src="https://img2.pic.in.th/pic/color_gray.png">&nbsp;ไม่สามารถแก้ไขได้
            &nbsp;&nbsp;&nbsp;&nbsp;<img width="20" class="fluid-img rounded-m shadow-xl" src="https://img2.pic.in.th/pic/check_nothave.png">&nbsp;ยกเลิกแผนการซ่อม
        </h5>
    </div>
    <br>
    <?php
        $SESSION_PERSONCODE = $_SESSION["AD_PERSONCODE"];
        $SESSION_NAMETHAI = $_SESSION["AD_NAMETHAI"];
        
        $D1=$result_getdate_d1d7["D1"];
        $sql_rprq = "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_WORKTYPE = 'BM' AND RPRQ_REQUESTBY = '$SESSION_NAMETHAI' AND RPRQ_STATUS = 'Y' ORDER BY RPRQ_STATUSREQUEST DESC";
        $query_rprq = sqlsrv_query($conn, $sql_rprq);
        $no=0;
        while($result_rprq = sqlsrv_fetch_array($query_rprq, SQLSRV_FETCH_ASSOC)){	
          $no++;
          if($result_rprq['RPRQ_STATUSREQUEST']=='รอตรวจสอบ'){
              $stylecolor = 'bg-blue-dark';
          }else if($result_rprq['RPRQ_STATUSREQUEST']=='รอจ่ายงาน'){
              $stylecolor = 'bg-gray-dark';
          }else if($result_rprq['RPRQ_STATUSREQUEST']=='รอคิวซ่อม'){
              $stylecolor = 'bg-gray-dark';
          }else if($result_rprq['RPRQ_STATUSREQUEST']=='ซ่อมเสร็จสิ้น'){
              $stylecolor = 'bg-gray-dark';
          }else if($result_rprq['RPRQ_STATUSREQUEST']=='ไม่อนุมัติ'){
              $stylecolor = 'bg-gray-dark';
          }else if($result_rprq['RPRQ_STATUSREQUEST']=='กำลังซ่อม'){
              $stylecolor = 'bg-gray-dark';
          }
    ?>      
    <div class="card card-style <?=$stylecolor?>">
      <div class="content mb-0">
        <div class="row justify-content-center">
          <?php if($result_rprq['RPRQ_STATUSREQUEST']=='รอตรวจสอบ'){ ?>
            <div class="col-9">
            <a href="./request_repair_bm_form.php?id=<?php print $result_rprq["RPRQ_CODE"];?>&proc=edit">
          <?php }else{ ?> 
            <div class="col-12">
          <?php } ?> 
            <p class="mb-n1 color-white opacity-30 font-600">หมายเลขงาน <?php print $result_rprq['RPRQ_ID']; ?> / วันที่แจ้งซ่อม <?php print $result_rprq['RPRQ_CREATEDATE_REQUEST']; ?></p>
            <h1 class="color-white">
              <?php print $result_rprq['RPRQ_REGISHEAD']?> <?php if($result_rprq['RPRQ_CARNAMEHEAD']!='-'){echo $result_rprq['RPRQ_CARNAMEHEAD'];}; ?>
              <?php print $result_rprq['RPRQ_REGISTAIL'].' '.$result_rprq['RPRQ_CARNAMETAIL']; ?>
            </h1>
            <p class="color-white">
              <?php
                switch($result_rprq['RPC_SUBJECT']) {
                    case "EL":
                        $text="ระบบไฟ";
                    break;
                    case "TU":
                        $text="ยาง ช่วงล่าง";
                    break;
                    case "BD":
                        $text="โครงสร้าง";
                    break;
                    case "EG":
                        $text="เครื่องยนต์";
                    break;
                    case "AC":
                        $text="อุปกรณ์ประจำรถ";
                    break;
                }
                print $text.' / ';
                print $result_rprq['RPC_DETAIL'];
                print ' / สถานะ: '.$result_rprq['RPRQ_STATUSREQUEST']; 
                if($result_rprq['RPRQ_STATUSREQUEST']=='ไม่อนุมัติ'){
                  print ' เนื่องจาก: '.$result_rprq['RPRQ_REMARK']; 
                }
              ?>
            </p>
          <?php if($result_rprq['RPRQ_STATUSREQUEST']=='รอตรวจสอบ'){ ?>
            </a>
          <?php } ?>
          </div>
          <?php if($result_rprq['RPRQ_STATUSREQUEST']=='รอตรวจสอบ'){ ?>
            <div class="col-3">
              <a href="#" class="pointer" onclick="swaldelete_requestrepair('<?php print $result_rprq['RPRQ_CODE']; ?>','<?php print $result_rprq['RPRQ_ID']; ?>')">
                <i class="fa fa-times-circle color-red scale-box fa-5x pt-3"></i>
              </a>
            </div>
          <?php }  ?> 
        </div>
      </div>
    </div>  
    <?php } ?>    
  </div>
</div>
<?php	require($path."include/script.php"); ?>
<script>
  function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
          /*check if the item starts with the same letters as the text field value:*/
          if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            /*make the matching letters bold:*/
            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
            b.innerHTML += arr[i].substr(val.length);
            /*insert a input field that will hold the current array item's value:*/
            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
            /*execute a function when someone clicks on the item value (DIV element):*/
            b.addEventListener("click", function(e) {
                /*insert the value for the autocomplete text field:*/
                inp.value = this.getElementsByTagName("input")[0].value;
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();
            });
            a.appendChild(b);
          }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          /*If the arrow DOWN key is pressed,
          increase the currentFocus variable:*/
          currentFocus++;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 38) { //up
          /*If the arrow UP key is pressed,
          decrease the currentFocus variable:*/
          currentFocus--;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 13) {
          /*If the ENTER key is pressed, prevent the form from being submitted,*/
          e.preventDefault();
          if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
          }
        }
    });
    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }
    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
          x[i].parentNode.removeChild(x[i]);
        }
      }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
  }
  var source1 = [<?= $rqrp_regishead ?>];
  var source2 = [<?= $rqrp_regisheadname ?>];
  var source3 = [<?= $rqrp_regishead ?>];
  var source4 = [<?= $rqrp_regisheadname ?>];
  autocomplete(document.getElementById("VEHICLEREGISNUMBER1"), source1);
  autocomplete(document.getElementById("THAINAME1"), source2);
  autocomplete(document.getElementById("VEHICLEREGISNUMBER2"), source3);
  autocomplete(document.getElementById("THAINAME2"), source4);

  function select_vehiclenumber1(){        
      var typecus = 'cusin';    
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
      }, 100);
  }
  function select_thainame1(){    
      var typecus = 'cusin'; 
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
      }, 100);
  }
  function select_vehiclenumber2(){    
      var typecus = 'cusin'; 
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
      var typecus = 'cusin'; 
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
  // EDIT
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
  // INSERT  
  function save_plan_insert() {     
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
          title: '<font color="black" size="5"><b>กรุณาระบุผู้รับแจ้งซ่อม</b></font>',
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
          title: '<font color="black" size="5"><b>กรุณาระบุวันที่นำรถเข้าซ่อม</b></font>',
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
          title: '<font color="black" size="5"><b>กรุณาระบุวันที่ต้องการใช้รถ</b></font>',
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
      title: `<font color='black' size='5'><b>คุณแน่ใจหรือไม่...ที่จะบันทึกแจ้งซ่อมนี้</b></font>`,
      icon: 'warning',
      showCancelButton: true,
      // confirmButtonColor: '#C82333',
      confirmButtonText: 'บันทึก',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed) {	
        Swal.fire({
          icon: 'success',
          title: `<font color='black' size='5'><b>บันทึกข้อมูลเสร็จสิ้น</b></font>`,
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
  }
  // DELETE
	function swaldelete_requestrepair(refcode,no) {
		Swal.fire({
			title: `<font color='black' size='5'><b>คุณแน่ใจหรือไม่...ที่จะยกเลิกรายการซ่อมของ ID หมายเลข `+no+`</b></font>`,
			text: "หากยกเลิกแล้ว คุณจะไม่สามารถกู้คืนข้อมูลได้!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#C82333',
			confirmButtonText: 'ใช่! ยกเลิกเลย',
			cancelButtonText: 'ยกเลิก'
		}).then((result) => {
			if (result.isConfirmed) {
				Swal.fire({
					icon: 'success',
          title: `<font color='black' size='5'><b>ยกเลิกเรียบร้อยแล้ว</b></font>`,
					showConfirmButton: false,
					timer: 2000
				}).then((result) => {	
					var ref = refcode; 
					var url = "request_repair_bm_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
              // window.location.reload();
						}
					});
				})	
			}
		})
	}

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
  
  function openModal() {
    document.getElementById("myModal").style.display = "block";
  }

  function closeModal() {
    document.getElementById("myModal").style.display = "none";
  }

  var slideIndex = 1;
  showSlides(slideIndex);

  function plusSlides(n) {
    showSlides(slideIndex += n);
  }

  function currentSlide(n) {
    showSlides(slideIndex = n);
  }

  function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("demo");
    var captionText = document.getElementById("caption");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
    captionText.innerHTML = dots[slideIndex-1].alt;
  }
</script>
<style>
  .pointer {
    cursor: pointer;
  }
  .autocomplete {
    position: relative;
    display: inline-block;
  }

  .autocomplete-items {
    position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    top: 100%;
    left: 0;
    right: 0;
  }

  .autocomplete-items div {
    padding: 10px;
    cursor: pointer;
    background-color: #fff; 
    border-bottom: 1px solid #d4d4d4; 
  }

  /*when hovering an item:*/
  .autocomplete-items div:hover {
    background-color: #e9e9e9; 
  }

  .autocomplete-active {
    background-color: DodgerBlue !important; 
    color: #ffffff; 
  }
</style>
<style>
  * {
    box-sizing: border-box;
  }

  .row > .column {
    padding: 0 8px;
  }

  .row:after {
    content: "";
    display: table;
    clear: both;
  }

  .column {
    float: left;
    width: 25%;
  }

  /* The Modal (background) */
  .modal {
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: black;
  }

  /* Modal Content */
  .modal-content {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    width: 90%;
    max-width: 1200px;
  }

  /* The Close Button */
  .close {
    color: white;
    position: absolute;
    top: 10px;
    right: 25px;
    font-size: 35px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: #999;
    text-decoration: none;
    cursor: pointer;
  }

  .mySlides {
    display: none;
  }

  .cursor {
    cursor: pointer;
  }

  /* Next & previous buttons */
  .prev,
  .next {
    cursor: pointer;
    position: absolute;
    top: 50%;
    width: auto;
    padding: 16px;
    margin-top: -50px;
    color: white;
    font-weight: bold;
    font-size: 20px;
    transition: 0.6s ease;
    border-radius: 0 3px 3px 0;
    user-select: none;
    -webkit-user-select: none;
  }

  /* Position the "next button" to the right */
  .next {
    right: 0;
    border-radius: 3px 0 0 3px;
  }

  /* On hover, add a black background color with a little bit see-through */
  .prev:hover,
  .next:hover {
    background-color: rgba(0, 0, 0, 0.8);
  }

  /* Number text (1/3 etc) */
  .numbertext {
    color: #f2f2f2;
    font-size: 12px;
    padding: 8px 12px;
    position: absolute;
    top: 0;
  }

  img {
    margin-bottom: -4px;
  }

  .caption-container {
    text-align: center;
    background-color: black;
    padding: 2px 16px;
    color: white;
  }

  .demo {
    opacity: 0.6;
  }

  .active,
  .demo:hover {
    opacity: 1;
  }

  img.hover-shadow {
    transition: 0.3s;
  }

  .hover-shadow:hover {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }
</style>