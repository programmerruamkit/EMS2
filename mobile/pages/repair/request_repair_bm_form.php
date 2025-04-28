<?php
	session_name("EMS"); session_start();
  $path = "../../";    
	require($path."include/connect.php");
	require($path."include/authen.php"); 
	require($path."include/head.php");		
  // print_r ($_SESSION);
?>
<div id="page">
    <?php      
      // print_r ($_SESSION);
      // echo"<pre>";
      // print_r($_GET);
      // echo"</pre>";

      if(isset($_GET["proc"])){
        $proc=$_GET["proc"];
      }else{
        $proc='add';
      }
      $ru_id=$_GET["id"];
      // if($proc=="edit"){
        $stmt = "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_CODE = ?";
        $params = array($ru_id);	
        $query = sqlsrv_query( $conn, $stmt, $params);	
        $result_edit_repairrequest = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
          $RPRQ_CODE=$result_edit_repairrequest["RPRQ_CODE"];
          $SUBJECT=$result_edit_repairrequest["RPC_SUBJECT"];

            if(isset($result_edit_repairrequest["RPRQ_REQUESTCARDATE"])){
              $RPRQ_REQUESTCARDATE = $result_edit_repairrequest["RPRQ_REQUESTCARDATE"];
              $exin = explode("/", $RPRQ_REQUESTCARDATE);
              $exin1 = $exin[0];
              $exin2 = $exin[1]; 
              $exin3 = $exin[2]; 	
              $RQDT=$exin3.'-'.$exin2.'-'.$exin1.' '.$result_edit_repairrequest["RPRQ_REQUESTCARTIME"];
              // $RQDT=$result_edit_repairrequest["RPRQ_REQUESTCARDATE"].' '.$result_edit_repairrequest["RPRQ_REQUESTCARTIME"];
            }else{
                $getdate=date("Y-m-d H:i:s");
                $RQDT=$getdate;
            }
            if(isset($result_edit_repairrequest["RPRQ_USECARDATE"])){
              $RPRQ_USECARDATE = $result_edit_repairrequest["RPRQ_USECARDATE"];
              $exuse = explode("/", $RPRQ_USECARDATE);
              $exuse1 = $exuse[0];
              $exuse2 = $exuse[1]; 
              $exuse3 = $exuse[2]; 	
              $UCDT=$exuse3.'-'.$exuse2.'-'.$exuse1.' '.$result_edit_repairrequest["RPRQ_USECARTIME"];
              // $UCDT=$result_edit_repairrequest["RPRQ_USECARDATE"].' '.$result_edit_repairrequest["RPRQ_USECARTIME"];
            }else{
                $getdate=date("Y-m-d H:i:s");
                $UCDT=$getdate;
            }
      // };
        
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
          $path = "../../";    
          require($path."include/connect.php");
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
          $path = "../../";    
          require($path."include/connect.php");
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
          $path = "../../";    
          require($path."include/connect.php");
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
          $path = "../../";    
          require($path."include/connect.php");
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
      
        if ($_GET['ISUS']!='1'){
          if($proc=="edit"){
            echo "<meta http-equiv='refresh' content='0;URL=request_repair_bm_form.php?ISUS=1&id=$ru_id&proc=edit'>";
          }else{
            echo "<meta http-equiv='refresh' content='0;URL=request_repair_bm_form.php?ISUS=1'>";
          }
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
        <div class="card card-style">
            <div class="content mb-0">   
              <?php if($proc=="edit"){ ?>
                <form action="#" method="post" enctype="multipart/form-data" name="input_request" id="input_request">
              <?php }else{ ?>
                <form name="input_request" id="input_request" method="post" action="request_repair_bm_proc.php" enctype="multipart/form-data">      
              <?php } ?>        
                <center><h2><u>บันทึกแจ้งซ่อม</u></h2></center><br>     
                <div class="row mb-0">
                    <div class="col-6">
                        <label class="color-highlight">ทะเบียนรถ(หัว):</label>
                        <div class="input-style has-borders mb-4">       
                          <input type="text" name="VEHICLEREGISNUMBER1" id="VEHICLEREGISNUMBER1" placeholder="ระบุทะเบียนรถ(หัว)" value="<?php echo $result_edit_repairrequest["RPRQ_REGISHEAD"];?>" class="char" onchange="select_vehiclenumber1()" onkeypress="select_vehiclenumber1()" onkeyup="select_vehiclenumber1()" onkeydown="select_vehiclenumber1()" autocomplete="off">
                        </div>
                    </div>    
                    <div class="col-6">
                        <label class="color-highlight">ชื่อรถ(หัว):</label>
                        <div class="input-style has-borders mb-4">       
                          <input type="text" name="THAINAME1" id="THAINAME1" placeholder="ชื่อรถ(หัว)" value="<?php echo $result_edit_repairrequest["RPRQ_CARNAMEHEAD"];?>" class="char" onchange="select_thainame1()" onkeypress="select_thainame1()" onkeyup="select_thainame1()" onkeydown="select_thainame1()" autocomplete="off">
                        </div>
                    </div>                    
                    <input type="hidden" name="RPRQ_CARTYPE" id="RPRQ_CARTYPE" placeholder="ประเภทรถ" onFocus="$(this).select();" value="<?php echo $result_edit_repairrequest["RPRQ_CARTYPE"];?>" class="readonly" readonly>
                    <input type="hidden" name="AFFCOMPANY" id="AFFCOMPANY" placeholder="ลูกค้า/สายงาน" onFocus="$(this).select();" value="<?php echo $result_edit_repairrequest["RPRQ_LINEOFWORK"];?>" class="readonly" readonly>
                    <input type="hidden" name="MAXMILEAGENUMBER" id="MAXMILEAGENUMBER" placeholder="เลขไมค์" onFocus="$(this).select();" value="" class="readonly" readonly>
                    
                    <div class="col-6">
                        <label class="color-highlight">ทะเบียนรถ(หาง):</label>
                        <div class="input-style has-borders mb-4">       
                          <input type="text" name="VEHICLEREGISNUMBER2" id="VEHICLEREGISNUMBER2" placeholder="ระบุทะเบียนรถ(หาง)" value="<?php echo $result_edit_repairrequest["RPRQ_REGISTAIL"];?>" class="char" onchange="select_vehiclenumber2()" onkeypress="select_vehiclenumber2()" onkeyup="select_vehiclenumber2()" onkeydown="select_vehiclenumber2()" autocomplete="off">
                        </div>                     
                    </div>
                    <div class="col-6">
                        <label class="color-highlight">ทะเบียนรถ(หาง):</label>
                        <div class="input-style has-borders mb-4">       
                          <input type="text" name="THAINAME2" id="THAINAME2" placeholder="ชื่อรถ(หาง)" value="<?php echo $result_edit_repairrequest["RPRQ_CARNAMETAIL"];?>" class="char" onchange="select_thainame2()" onkeypress="select_thainame2()" onkeyup="select_thainame2()" onkeydown="select_thainame2()" autocomplete="off">
                        </div>                     
                    </div>
                </div>    
                <div class="row mb-0">
                    <div class="col-6">
                        <label class="color-highlight">สินค้าบนรถ:</label>
                        <div class="input-style has-borders mb-4">
                            <select class="char"  style="width: 100%;" name="GOTC" id="GOTC">
                                <option value>--เลือกสินค้าบนรถ--</option>
                                <option value="ost" <?php if($result_edit_repairrequest['RPRQ_PRODUCTINCAR']=="ost"){echo "selected";} ?>>ไม่มี</option>
                                <option value="ist" <?php if($result_edit_repairrequest['RPRQ_PRODUCTINCAR']=="ist"){echo "selected";} ?>>มี</option>
                            </select>
                            <span><i class="fa fa-chevron-down"></i></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="color-highlight">ลักษณะการวิ่งงาน:</label>
                        <div class="input-style has-borders mb-4">    
                            <select class="char"  style="width: 100%;" name="NTORNNW" id="NTORNNW">
                                <!-- <option value disabled selected>--เลือกลักษณะการวิ่งงาน--</option> -->
                                <option value>--เลือกลักษณะการวิ่งงาน--</option>
                                <option value="bfw" <?php if($result_edit_repairrequest['RPRQ_NATUREREPAIR']== "bfw"){echo "selected";} ?>>ก่อนปฏิบัติงาน</option>
                                <option value="wwk" <?php if($result_edit_repairrequest['RPRQ_NATUREREPAIR']== "wwk"){echo "selected";} ?>>ขณะปฏิบัติงาน</option>
                                <option value="atw" <?php if($result_edit_repairrequest['RPRQ_NATUREREPAIR']== "atw"){echo "selected";} ?>>หลังปฏิบัติงาน</option>
                            </select>
                            <span><i class="fa fa-chevron-down"></i></span>
                        </div>
                    </div>
                </div>                      
                <div class="row mb-0">
                    <div class="col-6">
                        <?php    
                            $RPRQ_REQUESTBY_SQ=$result_edit_repairrequest["RPRQ_REQUESTBY_SQ"];          
                            $stmt_emp_create = "SELECT * FROM vwEMPLOYEE WHERE nameT = ?";
                            $params_emp_create = array($RPRQ_REQUESTBY_SQ);	
                            $query_emp_create = sqlsrv_query( $conn, $stmt_emp_create, $params_emp_create);	
                            $result_edit_emp_create = sqlsrv_fetch_array($query_emp_create, SQLSRV_FETCH_ASSOC);
                            $AREA=$_SESSION["AD_AREA"];
                        ?>
                        <label class="color-highlight">ผู้รับแจ้งซ่อม:</label>
                        <div class="input-style has-borders mb-4">     
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
                            <span><i class="fa fa-chevron-down"></i></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <?php      
                            $RPRQ_REQUESTBY=$result_edit_repairrequest["RPRQ_REQUESTBY"];
                            $stmt_emp_request = "SELECT * FROM vwEMPLOYEE WHERE nameT = ?";
                            $params_emp_request = array($RPRQ_REQUESTBY);	
                            $query_emp_request = sqlsrv_query( $conn, $stmt_emp_request, $params_emp_request);	
                            $result_edit_emp_request = sqlsrv_fetch_array($query_emp_request, SQLSRV_FETCH_ASSOC);
                        ?>
                        <label class="color-highlight">ผู้แจ้งซ่อม:</label>
                        <div class="input-style has-borders mb-4">
                            <input type="hidden" name="EMP_ID_RQRP" id="EMP_ID_RQRP" style="width:100px;" value="<?php if($proc=="edit"){echo $result_edit_emp_request["PersonCode"];}else{echo $_SESSION["AD_PERSONCODE"];}?>">
                            <input type="text" name="EMP_NAME_RQRP" id="EMP_NAME_RQRP" value="<?php if($proc=="edit"){echo $result_edit_emp_request["nameT"];}else{echo $_SESSION["AD_NAMETHAI"];}?>" class="form-control" readonly placeholder="ผู้แจ้งซ่อม">
                        </div>
                    </div>
                </div>                    
                <div class="row mb-0">
                    <div class="col-6">
                        <label class="color-highlight">วันที่แจ้งซ่อมซ่อม:</label>
                        <div class="input-style has-borders mb-4">
                            <!-- <input type="datetime-local" value="2035-12-31 00:00:00" max="2030-01-01" min="2021-01-01" class="form-control" id="form6" placeholder="Phone"> -->
                            <input type="datetime-local" name="datetimeRequest_in" id="datetimeRequest_in" placeholder="วันที่ เวลา" autocomplete="off" value="<?=$RQDT?>">   
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="color-highlight">วันที่ต้องการใช้รถ:</label>
                        <div class="input-style has-borders mb-4">
                            <!-- <input type="date" value="2030-12-31" value="2030-12-31" max="2030-01-01" min="2021-01-01" class="form-control" id="form6" placeholder="Phone"> -->
                            <!-- <input type="datetime-local" value="2035-12-31 00:00:00" max="2030-01-01" min="2021-01-01" class="form-control" id="form6" placeholder="Phone"> -->
                            <input type="datetime-local" name="datetimeRequest_out" id="datetimeRequest_out" placeholder="วันที่ เวลา" autocomplete="off" value="<?=$UCDT?>">  
                        </div>
                    </div>
                </div> 
                <hr>   
                <?php 
                  if($proc=="edit"){ 
                    $css_display="style='display: none;'";
                  }else{
                    $css_display="style='display: block;'";
                  }
                ?>
                <style>          
                  .wdv1 {width: 10%;}        
                  .wdv2 {width: 20%;}        
                  .wdv3 {width: 20%;}        
                  .wdv4 {width: 20%;}  
                  .wdv5 {width: 30%;}        
                  @media screen and (max-width: 650px){ 
                    .wdv1 {width: 20%;}        
                    .wdv2 {width: 40%;}        
                    .wdv3 {width: 40%;}        
                    .wdv4 {width: 50%;}  
                    .wdv5 {width: 50%;}                  
                  }
                </style> 
                <div class="row mb-0">
                  <h4><center>รายละเอียดงานซ่อม 1</center></h4>
                  <div class="wdv1" <?=$css_display?>>
                      <div class="input-style has-borders mb-4"><br>
                          <center>
                            <?php if($proc=="add"){ ?>    
                              <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-green-light" onclick="showrow2()"><b>+</b></button>
                            <?php } ?> 
                          </center>
                      </div>
                  </div>
                  <div class="wdv2">
                      <label class="color-highlight">เลือกลักษณะงาน:</label>
                      <div class="input-style has-borders mb-4">
                          <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR1">
                              <option value disabled selected>--เลือกลักษณะงาน--</option>
                              <option value="EL" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "EL"){echo "selected";} ?>>ระบบไฟ</option>
                              <option value="TU" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "TU"){echo "selected";} ?>>ยาง ช่วงล่าง</option>
                              <option value="BD" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "BD"){echo "selected";} ?>>โครงสร้าง</option>
                              <option value="EG" <?php if($result_edit_repairrequest['RPC_SUBJECT']== "EG"){echo "selected";} ?>>เครื่องยนต์</option>
                          </select>
                      </div>
                  </div>
                  <div class="wdv3">
                      <label class="color-highlight">เลือกกลุ่มงาน:</label>
                      <div class="input-style has-borders mb-4">
                          <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK1">
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
                          </select>
                      </div>
                  </div>
                  <div class="wdv4">
                      <label class="color-highlight">รายละเอียดงาน:</label>
                      <div class="input-style has-borders mb-4">
                        <?php if($proc=="edit"){ ?>
                          <input type="text" name="RPC_DETAIL" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
                        <?php }else{ ?>
                          <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
                        <?php } ?> 
                      </div>
                  </div>
                  <div class="wdv5">
                      <label class="color-highlight">รูปภาพงาน:</label>
                      <div class="input-style has-borders mb-4">
                        <div class="row">
                          <?php if($proc=="edit"){ ?>
                            <input type="file" name="RPC_IMAGES[]" multiple id="RPC_IMAGES" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif" value="<?php echo $result_edit_repairrequest["RPC_IMAGES"];?>"/>
                          <?php }else{ ?>                
                            <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                            <input type="file" name="RPC_IMAGES_img1[]" multiple id="RPC_IMAGES_img1" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif" value="<?php echo $result_edit_repairrequest["RPC_IMAGES"];?>"/>
                          <?php } ?>  
                        </div>
                      </div>
                  </div>
                </div>                
                <?php if($proc=="edit"){ ?>    
                  <center>
                    <hr>                                  
                    <?php                
                      $sql_causeimage = "SELECT * FROM vwREPAIRCAUSEIMAGE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$SUBJECT' ORDER BY RPCIM_ID ASC";
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
                      <!-- <a href="<?=$path?>../uploads/requestrepair/<?=$result_causeimage["RPCIM_IMAGES"];?>" title="Fashion Glasses" class="default-link" data-gallery="gallery-2"><img src="<?=$path?>../uploads/requestrepair/<?=$result_causeimage["RPCIM_IMAGES"];?>" class="img-fluid rounded-sm shadow-xl" width="80px" height="80px"></a> -->
                      <img src="<?=$path?>../uploads/requestrepair/<?=$result_causeimage["RPCIM_IMAGES"];?>" width="100%" height="100%" style="cursor:pointer;border:5px solid #555;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <button type="button" class="btn btn-md rounded-s text-uppercase font-700 shadow-s border-red-dark bg-red-light" onclick="swaldelete_image('<?=$RPRQ_CODE;?>','<?=$SUBJECT;?>','<?=$RPCIM_IMAGES;?>')"><b><font color="white">ลบรูปภาพ</font></b></button>
                      <br><br>
                    <?php } ?>   
                  </center>
                <?php } ?>
                <?php if($proc=="add"){ ?>
                  <div id="showrow2_div" style="display: none">
                    <hr>  
                    <h4><center>รายละเอียดงานซ่อม 2</center></h4>
                    <div class="row mb-0">
                        <div class="wdv1">
                            <div class="input-style has-borders mb-4"><br>
                              <div class="row">
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-green-light" onclick="showrow3()"><b>+</b></button>
                                    </center>
                                  </div>
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-red-light" onclick="hiderow2()"><b>-</b></button>
                                    </center>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="wdv2">
                            <label class="color-highlight">เลือกลักษณะงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR2">
                                    <option value disabled selected>--เลือกลักษณะงาน--</option>
                                    <option value="EL">ระบบไฟ</option>
                                    <option value="TU">ยาง ช่วงล่าง</option>
                                    <option value="BD">โครงสร้าง</option>
                                    <option value="EG">เครื่องยนต์</option>
                                </select>
                            </div>
                        </div>
                        <div class="wdv3">
                            <label class="color-highlight">เลือกกลุ่มงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK2">
                                  <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                                </select>
                            </div>
                        </div>
                        <div class="wdv4">
                            <label class="color-highlight">รายละเอียดงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
                            </div>
                        </div>
                        <div class="wdv5">
                            <label class="color-highlight">รูปภาพงาน:</label>
                            <div class="input-style has-borders mb-4">                                
                              <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                              <input type="file" name="RPC_IMAGES_img2[]" multiple id="RPC_IMAGES_img2" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                            </div>
                        </div>
                    </div> 
                  </div>
                  <div id="showrow3_div" style="display: none">
                    <hr>  
                    <h4><center>รายละเอียดงานซ่อม 3</center></h4>
                    <div class="row mb-0">
                        <div class="wdv1">
                            <div class="input-style has-borders mb-4"><br>
                              <div class="row">
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-green-light" onclick="showrow4()"><b>+</b></button>
                                    </center>
                                  </div>
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-red-light" onclick="hiderow3()"><b>-</b></button>
                                    </center>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="wdv2">
                            <label class="color-highlight">เลือกลักษณะงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR3">
                                    <option value disabled selected>--เลือกลักษณะงาน--</option>
                                    <option value="EL">ระบบไฟ</option>
                                    <option value="TU">ยาง ช่วงล่าง</option>
                                    <option value="BD">โครงสร้าง</option>
                                    <option value="EG">เครื่องยนต์</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="wdv3">
                            <label class="color-highlight">เลือกกลุ่มงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK3">
                                  <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                                </select>
                            </div>
                        </div>
                        <div class="wdv4">
                            <label class="color-highlight">รายละเอียดงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
                            </div>
                        </div>
                        <div class="wdv5">
                            <label class="color-highlight">รูปภาพงาน:</label>
                            <div class="input-style has-borders mb-4">
                              <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                              <input type="file" name="RPC_IMAGES_img3[]" multiple id="RPC_IMAGES_img3" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                            </div>
                        </div>
                    </div> 
                  </div>
                  <div id="showrow4_div" style="display: none">
                    <hr>  
                    <h4><center>รายละเอียดงานซ่อม 4</center></h4>
                    <div class="row mb-0">
                        <div class="wdv1">
                            <div class="input-style has-borders mb-4"><br>
                              <div class="row">
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-green-light" onclick="showrow5()"><b>+</b></button>
                                    </center>
                                  </div>
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-red-light" onclick="hiderow4()"><b>-</b></button>
                                    </center>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="wdv2">
                            <label class="color-highlight">เลือกลักษณะงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR4">
                                    <option value disabled selected>--เลือกลักษณะงาน--</option>
                                    <option value="EL">ระบบไฟ</option>
                                    <option value="TU">ยาง ช่วงล่าง</option>
                                    <option value="BD">โครงสร้าง</option>
                                    <option value="EG">เครื่องยนต์</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="wdv3">
                            <label class="color-highlight">เลือกกลุ่มงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK4">
                                  <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                                </select>
                            </div>
                        </div>
                        <div class="wdv4">
                            <label class="color-highlight">รายละเอียดงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
                            </div>
                        </div>
                        <div class="wdv5">
                            <label class="color-highlight">รูปภาพงาน:</label>
                            <div class="input-style has-borders mb-4">
                              <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                              <input type="file" name="RPC_IMAGES_img4[]" multiple id="RPC_IMAGES_img4" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                            </div>
                        </div>
                    </div> 
                  </div>
                  <div id="showrow5_div" style="display: none">
                    <hr>  
                    <h4><center>รายละเอียดงานซ่อม 5</center></h4>
                    <div class="row mb-0">
                        <div class="wdv1">
                            <div class="input-style has-borders mb-4"><br>
                              <div class="row">
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-green-light" onclick="showrow6()"><b>+</b></button>
                                    </center>
                                  </div>
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-red-light" onclick="hiderow5()"><b>-</b></button>
                                    </center>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="wdv2">
                            <label class="color-highlight">เลือกลักษณะงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR5">
                                    <option value disabled selected>--เลือกลักษณะงาน--</option>
                                    <option value="EL">ระบบไฟ</option>
                                    <option value="TU">ยาง ช่วงล่าง</option>
                                    <option value="BD">โครงสร้าง</option>
                                    <option value="EG">เครื่องยนต์</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="wdv3">
                            <label class="color-highlight">เลือกกลุ่มงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK5">
                                  <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                                </select>
                            </div>
                        </div>
                        <div class="wdv4">
                            <label class="color-highlight">รายละเอียดงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
                            </div>
                        </div>
                        <div class="wdv5">
                            <label class="color-highlight">รูปภาพงาน:</label>
                            <div class="input-style has-borders mb-4">
                              <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                              <input type="file" name="RPC_IMAGES_img5[]" multiple id="RPC_IMAGES_img5" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                            </div>
                        </div>
                    </div> 
                  </div>
                  <div id="showrow6_div" style="display: none">
                    <hr>  
                    <h4><center>รายละเอียดงานซ่อม 6</center></h4>
                    <div class="row mb-0">
                        <div class="wdv1">
                            <div class="input-style has-borders mb-4"><br>
                              <div class="row">
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-green-light" onclick="showrow7()"><b>+</b></button>
                                    </center>
                                  </div>
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-red-light" onclick="hiderow6()"><b>-</b></button>
                                    </center>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="wdv2">
                            <label class="color-highlight">เลือกลักษณะงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR6">
                                    <option value disabled selected>--เลือกลักษณะงาน--</option>
                                    <option value="EL">ระบบไฟ</option>
                                    <option value="TU">ยาง ช่วงล่าง</option>
                                    <option value="BD">โครงสร้าง</option>
                                    <option value="EG">เครื่องยนต์</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="wdv3">
                            <label class="color-highlight">เลือกกลุ่มงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK6">
                                  <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                                </select>
                            </div>
                        </div>
                        <div class="wdv4">
                            <label class="color-highlight">รายละเอียดงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
                            </div>
                        </div>
                        <div class="wdv5">
                            <label class="color-highlight">รูปภาพงาน:</label>
                            <div class="input-style has-borders mb-4">
                              <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                              <input type="file" name="RPC_IMAGES_img6[]" multiple id="RPC_IMAGES_img6" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                            </div>
                        </div>
                    </div> 
                  </div>
                  <div id="showrow7_div" style="display: none">
                    <hr>  
                    <h4><center>รายละเอียดงานซ่อม 7</center></h4>
                    <div class="row mb-0">
                        <div class="wdv1">
                            <div class="input-style has-borders mb-4"><br>
                              <div class="row">
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-green-light" onclick="showrow8()"><b>+</b></button>
                                    </center>
                                  </div>
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-red-light" onclick="hiderow7()"><b>-</b></button>
                                    </center>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="wdv2">
                            <label class="color-highlight">เลือกลักษณะงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR7">
                                    <option value disabled selected>--เลือกลักษณะงาน--</option>
                                    <option value="EL">ระบบไฟ</option>
                                    <option value="TU">ยาง ช่วงล่าง</option>
                                    <option value="BD">โครงสร้าง</option>
                                    <option value="EG">เครื่องยนต์</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="wdv3">
                            <label class="color-highlight">เลือกกลุ่มงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK7">
                                  <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                                </select>
                            </div>
                        </div>
                        <div class="wdv4">
                            <label class="color-highlight">รายละเอียดงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
                            </div>
                        </div>
                        <div class="wdv5">
                            <label class="color-highlight">รูปภาพงาน:</label>
                            <div class="input-style has-borders mb-4">
                              <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                              <input type="file" name="RPC_IMAGES_img7[]" multiple id="RPC_IMAGES_img7" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                            </div>
                        </div>
                    </div> 
                  </div>
                  <div id="showrow8_div" style="display: none">
                    <hr>  
                    <h4><center>รายละเอียดงานซ่อม 8</center></h4>
                    <div class="row mb-0">
                        <div class="wdv1">
                            <div class="input-style has-borders mb-4"><br>
                              <div class="row">
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-green-light" onclick="showrow9()"><b>+</b></button>
                                    </center>
                                  </div>
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-red-light" onclick="hiderow8()"><b>-</b></button>
                                    </center>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="wdv2">
                            <label class="color-highlight">เลือกลักษณะงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR8">
                                    <option value disabled selected>--เลือกลักษณะงาน--</option>
                                    <option value="EL">ระบบไฟ</option>
                                    <option value="TU">ยาง ช่วงล่าง</option>
                                    <option value="BD">โครงสร้าง</option>
                                    <option value="EG">เครื่องยนต์</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="wdv3">
                            <label class="color-highlight">เลือกกลุ่มงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK8">
                                  <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                                </select>
                            </div>
                        </div>
                        <div class="wdv4">
                            <label class="color-highlight">รายละเอียดงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
                            </div>
                        </div>
                        <div class="wdv5">
                            <label class="color-highlight">รูปภาพงาน:</label>
                            <div class="input-style has-borders mb-4">
                              <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                              <input type="file" name="RPC_IMAGES_img8[]" multiple id="RPC_IMAGES_img8" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                            </div>
                        </div>
                    </div> 
                  </div>
                  <div id="showrow9_div" style="display: none">
                    <hr>  
                    <h4><center>รายละเอียดงานซ่อม 9</center></h4>
                    <div class="row mb-0">
                        <div class="wdv1">
                            <div class="input-style has-borders mb-4"><br>
                              <div class="row">
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-green-light" onclick="showrow10()"><b>+</b></button>
                                    </center>
                                  </div>
                                  <div class="col-6">
                                    <center>
                                      <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-red-light" onclick="hiderow9()"><b>-</b></button>
                                    </center>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="wdv2">
                            <label class="color-highlight">เลือกลักษณะงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR9">
                                    <option value disabled selected>--เลือกลักษณะงาน--</option>
                                    <option value="EL">ระบบไฟ</option>
                                    <option value="TU">ยาง ช่วงล่าง</option>
                                    <option value="BD">โครงสร้าง</option>
                                    <option value="EG">เครื่องยนต์</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="wdv3">
                            <label class="color-highlight">เลือกกลุ่มงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK9">
                                  <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                                </select>
                            </div>
                        </div>
                        <div class="wdv4">
                            <label class="color-highlight">รายละเอียดงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
                            </div>
                        </div>
                        <div class="wdv5">
                            <label class="color-highlight">รูปภาพงาน:</label>
                            <div class="input-style has-borders mb-4">
                              <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                              <input type="file" name="RPC_IMAGES_img9[]" multiple id="RPC_IMAGES_img9" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                            </div>
                        </div>
                    </div> 
                  </div>
                  <div id="showrow10_div" style="display: none">
                    <hr>  
                    <h4><center>รายละเอียดงานซ่อม 10</center></h4>
                    <div class="row mb-0">
                        <div class="wdv1">
                            <div class="input-style has-borders mb-4"><br>
                                <center>
                                  <button type="button" class="btn btn-xxs btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s bg-red-light" onclick="hiderow10()"><b>-</b></button>
                                </center>
                            </div>
                        </div>
                        <div class="wdv2">
                            <label class="color-highlight">เลือกลักษณะงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="RPM_NATUREREPAIR[]" id="RPM_NATUREREPAIR10">
                                    <option value disabled selected>--เลือกลักษณะงาน--</option>
                                    <option value="EL">ระบบไฟ</option>
                                    <option value="TU">ยาง ช่วงล่าง</option>
                                    <option value="BD">โครงสร้าง</option>
                                    <option value="EG">เครื่องยนต์</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="wdv3">
                            <label class="color-highlight">เลือกกลุ่มงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <select class="char"  style="width: 100%;" name="TYPEREPAIRWORK[]" id="TYPEREPAIRWORK10">
                                  <option value disabled selected>--เลือกกลุ่มงานซ่อม--</option>
                                </select>
                            </div>
                        </div>
                        <div class="wdv4">
                            <label class="color-highlight">รายละเอียดงาน:</label>
                            <div class="input-style has-borders mb-4">
                                <input type="text" name="RPC_DETAIL[]" id="RPC_DETAIL" class="clear_data char" value="<?php echo $result_edit_repairrequest["RPC_DETAIL"];?>" placeholder="รายละเอียด" autocomplete="off" >
                            </div>
                        </div>
                        <div class="wdv5">
                            <label class="color-highlight">รูปภาพงาน:</label>
                            <div class="input-style has-borders mb-4">
                              <input type="hidden" name="RPRQ_CODE[]" id="RPRQ_CODE" value="<?="RQRP_".RandNum($n); ?>">
                              <input type="file" name="RPC_IMAGES_img10[]" multiple id="RPC_IMAGES_img10" class="browsefile" onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif"/>
                            </div>
                        </div>
                    </div> 
                  </div>
                <?php } ?>
                <hr>
                <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
                <input type="hidden" name="RPRQ_WORKTYPE" id="RPRQ_WORKTYPE" value="BM" />
                <input type="hidden" name="RPRQ_AREA" id="RPRQ_AREA" value="<?=$_SESSION["AD_AREA"];?>" />
                <?php if($proc=="edit"){ ?>
                  <input type="hidden" name="RPRQ_CODE" id="RPRQ_CODE" value="<?=$result_edit_repairrequest["RPRQ_CODE"];?>">          
                  <div class="row">
                      <div class="col-12">
                        <center>
                          <button type="button" class="btn btn-3d btn-xl btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s  border-blue-dark bg-blue-light" name="buttonname" id="buttonname" value="edit" onClick="save_data()"><b>บันทึกข้อมูล</b></button>
                        </center>
                      </div>
                  </div>
                <?php }else{ ?>
                  <!-- <input type="hidden" name="RPRQ_CODE" id="RPRQ_CODE" value="< ?=$rand; ?>"> -->            
                  <div class="row">
                      <div class="col-12">
                        <center>
                          <button type="button" class="btn btn-3d btn-xl btn-full mb-3 rounded-xl text-uppercase font-700 shadow-s  border-blue-dark bg-blue-light" name="buttonnameadd" id="buttonnameadd" value="add" data-menu="menu-option-22" onclick="save_plan_insert()"><b>บันทึกข้อมูล</b></button>
                        </center>
                      </div>
                  </div>
                <?php } ?>
              </form>   
            </div>
        </div>  
    </div> 
</div>
<?php	
	require($path."include/script.php"); 
?>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<link href="../../plugins/autocomplete/jquery.autocomplete.css" rel="stylesheet">
<script src="../../plugins/autocomplete/jquery.autocomplete.js"></script>
<script>
  var source1 = [<?= $rqrp_regishead ?>];
  var source2 = [<?= $rqrp_regisheadname ?>];
  var source3 = [<?= $rqrp_regishead ?>];
  var source4 = [<?= $rqrp_regisheadname ?>];
  
  $("#VEHICLEREGISNUMBER1").autocomplete({source: [source1]});
  $("#THAINAME1").autocomplete({source: [source2]});
  $("#VEHICLEREGISNUMBER2").autocomplete({source: [source3]});
  $("#THAINAME2").autocomplete({source: [source4]});

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
      $.ajax({
        type: 'post',
        url: '../autocomplete/autocomplete_vehicle_request.php',
        data: {
            txt_flg: "select_maxmileage", 
            thainame: document.getElementById('THAINAME1').value
        },
        success: function (rs) {
            document.getElementById("MAXMILEAGENUMBER").value = rs;
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
  // for EDIT
  function save_data() {   
      var formData = new FormData($("#input_request")[0]);           
      // var file_data = $('#RPC_IMAGES')[0].files;
      // formData.append('file',file_data[0]);
      var file_data = $('#RPC_IMAGES').files;
      formData.append('file[]',file_data);
      Swal.fire({
        title: '<font color="black" size="4"><b>คุณแน่ใจหรือไม่...ที่จะบันทึกแจ้งซ่อมนี้</b></font>',
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
            title: '<font color="black" size="4"><b>บันทึกข้อมูลเสร็จสิ้น</b></font>',
            showConfirmButton: false,
            timer: 2000
          }).then((result) => {	
            var buttonname = $('#buttonname').val();
            var url = "request_repair_bm_proc.php";
            $.ajax({
              type: "POST",
              url:url,
              data: formData,	
              contentType: false,
              processData: false,
              success:function(data){
                location.assign('./request_repair_bm_form.php?ISUS=1&id=<?php print $RPRQ_CODE; ?>&proc=edit') 
              }
            });  
          })	
        }
      })
  }
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
      // alert("กรุณาเลือกสินค้าบนรถ");
      Swal.fire({
          icon: 'warning',
          title: '<font color="black" size="5"><b>กรุณาเลือกสินค้าบนรถ</b></font>',
          showConfirmButton: false,
          timer: 1500,
          onAfterClose: () => {
              setTimeout(() => $("#GOTC").focus(), 0);
          }
      })
      return false;
    }
    if($('#NTORNNW').val() == '' ){
      // alert("กรุณาเลือกลักษณะการวิ่งงาน");
      Swal.fire({
          icon: 'warning',
          title: '<font color="black" size="5"><b>กรุณาเลือกลักษณะการวิ่งงาน</b></font>',
          showConfirmButton: false,
          timer: 1500,
          onAfterClose: () => {
              setTimeout(() => $("#NTORNNW").focus(), 0);
          }
      })
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

  function swaldelete_image(a1,a2,a3) {
      Swal.fire({
          title: '<font color="black" size="4"><b>คุณแน่ใจหรือไม่...ที่จะลบรูปนี้</b></font>',
          text: 'หากลบแล้ว จะไม่สามารถกู้คืนรูปได้!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#C82333',
          confirmButtonText: 'ใช่! ลบเลย',
          cancelButtonText: 'ยกเลิก'
      }).then((result) => {
          if (result.isConfirmed) {
              Swal.fire({
                  icon: 'success',
                  title: '<font color="black" size="4"><b>ลบข้อมูลเรียบร้อยแล้ว</b></font>',
                  showConfirmButton: false,
                  timer: 2000
              }).then((result) => {	
                  var url = "request_repair_bm_proc.php?proc=deleteimageonly&id="+a1+"&subject="+a2+"&image="+a3;
                  $.ajax({
                      type:'GET',
                      url:url,
                      data:"",				
                      success:function(data){
                          // alert(data);
                          location.assign('./request_repair_bm_form.php?ISUS=1&id='+a1+'&proc=edit') 
                      }
                  });
              })	
          }
      })
  }
</script>
