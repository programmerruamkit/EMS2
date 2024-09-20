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
	$nature=$_GET["nature"];
  $rpcid=$_GET['rpcid'];

  $SESSION_AREA = $_SESSION["AD_AREA"];
  if($SESSION_AREA=="AMT"){
    $RPH_ZONE1="S1";
    $RPH_ZONE2="S2";
  }else{
    $RPH_ZONE1="G1";
    $RPH_ZONE2="G2";
  }
  
?>
<html>
  
<head>
  <script type="text/javascript">   
    function save_data_hole(hole,rprq,proc,nature,target,areaother) {   
      // alert(hole)
      // alert(rprq)
      // alert(nature)
      // alert(target)
      // alert(areaother)     
      var buttonname = $('#buttonname').val();
      var url = "<?=$path?>views_amt/assign_repairwork/assign_repairwork_proc.php";
      $.ajax({
        type: "POST",
        url:url,
        data: {
              RPH_REPAIRHOLE: hole, 
              RPRQ_CODE: rprq, 
              proc: proc, 
              RPC_SUBJECT: nature, 
              target: target,
              RPC_AREA_OTHER: areaother
        },
        success:function(data){
          // alert(data);        
          ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form_repairhole.php','edit','<?php print $rprq_code; ?>&nature=<?=$nature;?>&rpcid=<?=$rpcid;?>','1=1','1300','570','มอบหมายงานซ่อม');
          // closeUI();
        }
      });
    } 
    function save_data() {   
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะเลือกช่องซ่อมนี้',
        icon: 'warning',
        showCancelButton: true,
        // confirmButtonColor: '#C82333',
        confirmButtonColor: 'green',
        confirmButtonText: 'ใช่! ยืนยัน',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            icon: 'success',
            title: 'เลือกช่องซ่อมเสร็จสิ้น',
            showConfirmButton: false,
            timer: 2000,            
            timerProgressBar: true,
          }).then((result) => {	
            var buttonname = $('#buttonname').val();
            var url = "<?=$path?>views_amt/assign_repairwork/assign_repairwork_proc.php";
            $.ajax({
              type: "POST",
              url:url,
              data: $("#form_project").serialize(),
              success:function(data){
                // alert(data);                 
                // if(buttonname=='add'){
                //   log_transac_requestrepair('LA3', '-', '<?=$rand;?>');
                // }else{
                //   log_transac_requestrepair('LA4', '-', '<?=$RPRQ_CODE;?>');
                // }
                // loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php');
                ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $rprq_code; ?>','1=1','1300','570','มอบหมายงานซ่อม');
                // closeUI();
              }
            });
          })	
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
                      <td width="24" valign="middle" class=""><img src="../images/pm_sheet.png" width="32" height="32"></td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;มอบหมายงาน เลขที่ใบแจ้งซ่อม <?=$rpcid?></h4>
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
                    <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">
                        <tr class="ui-state-default">
                          <td height="35" colspan="3" >
                            <div class="input-control text" style="width:100%; margin-left:3px;"> 
                              <center><strong>เลือกช่องซ่อม</strong></center>
                            </div>
                          </td>
                        </tr>          
                    </table>      
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                      <tr>
                        <td valign="top" width="50%">
                          <div class="panel panel-default" style="margin-left:5px; margin-right:5px;">           
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                              <thead>
                                <tr class="ui-state-default">
                                  <td height="35" colspan="3" >
                                    <div class="input-control text" style="width:100%; margin-left:3px;"> 
                                      <center><strong>ช่องซ่อม <?=$RPH_ZONE1;?></strong></center>
                                    </div>
                                  </td>
                                </tr>   
                              </thead>
                              <tbody>
                                <tr height="40">
                                  <td class="bg-white">                                              
                                    <div class="input-control text" style="width:100%;">   
                                      <div class="container-fluid">
                                        <div class="row">
                                          <?php 
                                            $sql_repairhole_left = "SELECT * FROM REPAIR_HOLE WHERE RPH_AREA = '$SESSION_AREA' AND RPH_STATUS = 'Y' AND RPH_ZONE = '$RPH_ZONE1'";
                                            $query_repairhole_left = sqlsrv_query($conn, $sql_repairhole_left);
                                            while($result_repairhole_left = sqlsrv_fetch_array($query_repairhole_left, SQLSRV_FETCH_ASSOC)){   
                                              $RPH_LEFT=$result_repairhole_left['RPH_REPAIRHOLE'];

                                              $sql_repaircause = "SELECT * FROM REPAIRCAUSE WHERE RPRQ_CODE = '$rprq_code' AND RPC_SUBJECT = '$nature'";
                                              $params_repaircause = array();
                                              $query_repaircause = sqlsrv_query($conn, $sql_repaircause, $params_repaircause);
                                              $result_repaircause = sqlsrv_fetch_array($query_repaircause, SQLSRV_FETCH_ASSOC); 
                                              $RPC_AREA=$result_repaircause['RPC_AREA'];

                                              $sql_plan_hole_left = "SELECT DISTINCT A.RPRQ_ID,A.RPRQ_REGISHEAD,B.RPC_AREA,B.RPC_AREAID,C.RPH_AREA,A.RPRQ_REQUESTCARDATE,A.RPRQ_USECARDATE,B.RPC_SUBJECT
                                                FROM dbo.REPAIRREQUEST AS A
                                                LEFT JOIN dbo.REPAIRCAUSE AS B ON B.RPRQ_CODE = A.RPRQ_CODE
                                                LEFT JOIN dbo.REPAIR_HOLE AS C ON C.RPH_ID = B.RPC_AREAID
                                                WHERE A.RPRQ_STATUS = 'Y' AND A.RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอคิวซ่อม','กำลังซ่อม')
                                                AND B.RPC_AREA = '$RPH_LEFT'";
                                              $params_plan_hole_left = array();
                                              $query_plan_hole_left = sqlsrv_query($conn, $sql_plan_hole_left, $params_plan_hole_left);
                                              // $result_plan_hole_left = sqlsrv_fetch_array($query_plan_hole_left, SQLSRV_FETCH_ASSOC); 
                                          ?>                                     
                                          <div class="col-md-3">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <center>
                                              <label><strong>ช่อง <?=$RPH_LEFT;?></strong></label><br>
                                              <?php 
                                                $no=0;
                                                $RPC_SUBJECT_L = array();
                                                while($result_plan_hole_left = sqlsrv_fetch_array($query_plan_hole_left, SQLSRV_FETCH_ASSOC)){ 
                                                  $no++;
                                                  $plan_hole_left=$result_plan_hole_left['RPRQ_REGISHEAD'];
                                                  $RPC_SUBJECT_L[] = $result_plan_hole_left['RPC_SUBJECT'];
                                                  echo $plan_hole_left.'<br>';
                                                }                                              
                                              ?>
                                              <input type="radio" name="RPH_REPAIRHOLE" id="RPH_REPAIRHOLE" value="<?=$RPH_LEFT;?>" style="cursor:pointer" class="radioexam" 
                                              <?php 
                                                for($is1=0; $is1<=count($RPC_SUBJECT_L); $is1++){
                                                  $RPC_SUBJECT_LEFT=$RPC_SUBJECT_L[$is1];
                                                  if(($RPC_SUBJECT_LEFT==$nature)&&($RPC_AREA==$RPH_LEFT)){
                                                      echo 'checked';break;
                                                  } 
                                                }  
                                              ?> onclick="save_data_hole('<?=$RPH_LEFT;?>','<?=$rprq_code;?>','<?=$proc;?>','<?=$nature;?>','repairhole','')"><br>
                                              <!-- <img src="../images/Truck-icon32.png" width="32" height="32"> -->
                                            </center>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          </div>
                                          <?php } ?>
                                        </div>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </td>
                        <td valign="top">
                          <div class="panel panel-default" style="margin-left:5px; margin-right:5px;">                        
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                              <thead>
                                <tr class="ui-state-default">
                                  <td height="35" colspan="3" >
                                    <div class="input-control text" style="width:100%; margin-left:3px;"> 
                                      <center><strong>ช่องซ่อม <?=$RPH_ZONE2;?></strong></center>
                                    </div>
                                  </td>
                                </tr>   
                              </thead>
                              <tbody>
                                <tr height="40">
                                  <td class="bg-white">                                              
                                    <div class="input-control text" style="width:100%;">   
                                      <div class="container-fluid">
                                        <div class="row">
                                          <?php 
                                            $sql_repairhole_right = "SELECT * FROM REPAIR_HOLE WHERE RPH_AREA = '$SESSION_AREA' AND RPH_STATUS = 'Y' AND RPH_ZONE = '$RPH_ZONE2'";
                                            $query_repairhole_right = sqlsrv_query($conn, $sql_repairhole_right);
                                            while($result_repairhole_right = sqlsrv_fetch_array($query_repairhole_right, SQLSRV_FETCH_ASSOC)){ 
                                              $RPH_RIGHT=$result_repairhole_right['RPH_REPAIRHOLE'];

                                              $sql_plan_hole_right = "SELECT DISTINCT A.RPRQ_ID,A.RPRQ_REGISHEAD,B.RPC_AREA,B.RPC_AREAID,C.RPH_AREA,A.RPRQ_REQUESTCARDATE,A.RPRQ_USECARDATE,B.RPC_SUBJECT
                                                FROM dbo.REPAIRREQUEST AS A
                                                LEFT JOIN dbo.REPAIRCAUSE AS B ON B.RPRQ_CODE = A.RPRQ_CODE
                                                LEFT JOIN dbo.REPAIR_HOLE AS C ON C.RPH_ID = B.RPC_AREAID
                                                WHERE A.RPRQ_STATUS = 'Y' AND A.RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอคิวซ่อม','กำลังซ่อม')
                                                AND B.RPC_AREA = '$RPH_RIGHT'";
                                              $params_plan_hole_right = array();
                                              $query_plan_hole_right = sqlsrv_query($conn, $sql_plan_hole_right, $params_plan_hole_right);
                                              // $result_plan_hole_right = sqlsrv_fetch_array($query_plan_hole_right, SQLSRV_FETCH_ASSOC); 
                                          ?>                                     
                                          <div class="col-md-3">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <center>
                                              <label><strong>ช่อง <?=$RPH_RIGHT;?></strong></label><br>
                                              <?php 
                                                $no=0;
                                                $RPC_SUBJECT_R = array();
                                                while($result_plan_hole_right = sqlsrv_fetch_array($query_plan_hole_right, SQLSRV_FETCH_ASSOC)){ 
                                                  $no++;
                                                  $plan_hole_right=$result_plan_hole_right['RPRQ_REGISHEAD'];
                                                  $RPC_SUBJECT_R[] = $result_plan_hole_right['RPC_SUBJECT'];
                                                  echo $plan_hole_right.'<br>';
                                                } 
                                              ?>
                                              <input type="radio" name="RPH_REPAIRHOLE" id="RPH_REPAIRHOLE" value="<?=$RPH_RIGHT;?>" style="cursor:pointer" class="radioexam" 
                                              <?php
                                                for($is2=0; $is2<=count($RPC_SUBJECT_R); $is2++){
                                                  $RPC_SUBJECT_RIGHT=$RPC_SUBJECT_R[$is2];
                                                  if(($RPC_SUBJECT_RIGHT==$nature)&&($RPC_AREA==$RPH_RIGHT)){
                                                      echo 'checked';break;
                                                  } 
                                                }
                                              ?> onclick="save_data_hole('<?=$RPH_RIGHT;?>','<?=$rprq_code;?>','<?=$proc;?>','<?=$nature;?>','repairhole','')"><br>
                                              <!-- <img src="../images/Truck-icon32.png" width="32" height="32"> -->
                                            </center>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          </div>
                                          <?php } ?>
                                        </div>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>   
                        </td>
                      </tr>
                    </table>   
                    <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">     
                      <tr class="bg-white">          
                        <td height="40" width="30%" >
                          <div class="input-control text" style="margin-right:3px">   
                            <?php
                              $sql_plan_hole_other = "SELECT DISTINCT A.RPRQ_ID,A.RPRQ_REGISHEAD,B.RPC_AREA,B.RPC_AREAID,B.RPC_AREA_OTHER,C.RPH_AREA,A.RPRQ_REQUESTCARDATE,A.RPRQ_USECARDATE
                                FROM dbo.REPAIRREQUEST AS A
                                LEFT JOIN dbo.REPAIRCAUSE AS B ON B.RPRQ_CODE = A.RPRQ_CODE
                                LEFT JOIN dbo.REPAIR_HOLE AS C ON C.RPH_ID = B.RPC_AREAID
                                WHERE A.RPRQ_STATUS = 'Y' AND A.RPRQ_STATUSREQUEST = 'รอจ่ายงาน'
                                AND A.RPRQ_ID = '$rpcid'";
                              $params_plan_hole_other = array();
                              $query_plan_hole_other = sqlsrv_query($conn, $sql_plan_hole_other, $params_plan_hole_other);
                              $result_plan_hole_other = sqlsrv_fetch_array($query_plan_hole_other, SQLSRV_FETCH_ASSOC); 
                            ?>
                            <input type="text" name="RPC_AREA_OTHER" id="RPC_AREA_OTHER" class="clear_data char" value="<?=$result_plan_hole_other['RPC_AREA_OTHER'];?>" placeholder="อื่น ๆ เช่น กลางลาน หรือ พื้นที่ลูกค้า" autocomplete="off" onchange="save_data_hole('','<?=$rprq_code;?>','<?=$proc;?>','<?=$nature;?>','repairhole',this.value)">
                          </div>
                        </td>
                      </tr>
                    </table>            
                    <table width="100%" cellpadding="0" id="request_piece_detail" class="display" style="margin-top:10px; margin-bottom:10px">
                        <tr class="ui-state-default">
                          <td height="35" width="50%" >
                              <strong>รายละเอียดช่องซ่อม <?=$RPH_ZONE1;?></strong>
                              <div class="row">  
                                <?php 
                                  $sql_repairhole_detail = "SELECT * FROM REPAIR_HOLE WHERE RPH_AREA = '$SESSION_AREA' AND RPH_STATUS = 'Y' AND RPH_ZONE = '$RPH_ZONE1'";
                                  $query_repairhole_detail = sqlsrv_query($conn, $sql_repairhole_detail);
                                  while($result_repairhole_detail = sqlsrv_fetch_array($query_repairhole_detail, SQLSRV_FETCH_ASSOC)){ 
                                ?>                                   
                                <div class="col-md-6">
                                  &nbsp;&nbsp;&nbsp;
                                  <font size='1'><strong><?=$result_repairhole_detail['RPH_REPAIRHOLE'];?> :</strong></font><font size='1'><?=$result_repairhole_detail['RPH_NATUREREPAIR'];?></font>
                                </div>
                                <?php } ?>
                              </div>
                          </td>
                          <td height="35" width="50%" >
                              <strong>รายละเอียดช่องซ่อม <?=$RPH_ZONE2;?></strong>
                              <div class="row">  
                                <?php 
                                  $sql_repairhole_detail = "SELECT * FROM REPAIR_HOLE WHERE RPH_AREA = '$SESSION_AREA' AND RPH_STATUS = 'Y' AND RPH_ZONE = '$RPH_ZONE2'";
                                  $query_repairhole_detail = sqlsrv_query($conn, $sql_repairhole_detail);
                                  while($result_repairhole_detail = sqlsrv_fetch_array($query_repairhole_detail, SQLSRV_FETCH_ASSOC)){ 
                                ?>                                   
                                <div class="col-md-6">
                                  &nbsp;&nbsp;&nbsp;
                                  <font size='1'><strong><?=$result_repairhole_detail['RPH_REPAIRHOLE'];?> :</strong></font><font size='1'><?=$result_repairhole_detail['RPH_NATUREREPAIR'];?></font>
                                </div>
                                <?php } ?>
                              </div>
                          </td>
                        </tr>          
                    </table>
                    <input type="hidden" name="RPRQ_CODE" id="RPRQ_CODE" value="<?=$rprq_code;?>">
                    <input type="hidden" name="proc" id="proc" value="<?=$proc;?>">
                    <input type="hidden" name="RPC_SUBJECT" id="RPC_SUBJECT" value="<?=$nature;?>">
                    <input type="hidden" name="target" id="target" value="repairhole">
                    <!-- <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="<?=$proc;?>" onclick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp; -->
                    <button class="bg-color-red font-white" type="button" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $rprq_code; ?>','1=1','1300','570','มอบหมายงานซ่อม');">ย้อนกลับ</button>
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


  .col-md-1 {
    -ms-flex: 0 0 8.333333%;
    flex: 0 0 8.333333%;
    max-width: 8.333333%;
  }
  .col-md-2 {
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }
  .col-md-3 {
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .col-md-4 {
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
  .col-md-5 {
    -ms-flex: 0 0 41.666667%;
    flex: 0 0 41.666667%;
    max-width: 41.666667%;
  }
  .col-md-6 {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .col-md-7 {
    -ms-flex: 0 0 58.333333%;
    flex: 0 0 58.333333%;
    max-width: 58.333333%;
  }
  .col-md-8 {
    -ms-flex: 0 0 66.666667%;
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
  }
  .col-md-9 {
    -ms-flex: 0 0 75%;
    flex: 0 0 75%;
    max-width: 75%;
  }
  .col-md-10 {
    -ms-flex: 0 0 83.333333%;
    flex: 0 0 83.333333%;
    max-width: 83.333333%;
  }
  .col-md-11 {
    -ms-flex: 0 0 91.666667%;
    flex: 0 0 91.666667%;
    max-width: 91.666667%;
  }
  .col-md-12 {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
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