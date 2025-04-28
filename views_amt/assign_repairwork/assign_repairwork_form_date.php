<?php
	session_name("EMS"); session_start();
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
  
  $sql_repaircause = "SELECT * FROM REPAIRCAUSE WHERE RPRQ_CODE = '$rprq_code'";
  $params_repaircause = array();
  $query_repaircause = sqlsrv_query($conn, $sql_repaircause, $params_repaircause);
  $result_repaircause = sqlsrv_fetch_array($query_repaircause, SQLSRV_FETCH_ASSOC); 
  $RPC_AREA=$result_repaircause['RPC_AREA'];
  
?>
<html>
  
<head>
  <script type="text/javascript">
		$(document).ready(function(e) {
      datepicker_thai('#RPC_INCARDATE');
      datepicker_thai('#RPC_OUTCARDATE');
		});
    
    function save_data() {   
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะเลือกช่วงเวลานี้',
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
            title: 'เลือกช่วงเวลาเสร็จสิ้น',
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
                              <center><strong>เลือกวันที่ / เวลา</strong></center>
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
                                      <center><strong>เลือกวันที่นำรถเข้าซ่อม</strong></center>
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
                                          <div class="col-md-1">&nbsp;</div>                             
                                          <div class="col-md-10">             
                                            <div class="row input-control">        
                                              <input type="text" name="RPC_INCARDATE" id="RPC_INCARDATE" placeholder="วันที่" style="width:70%;" class="char" value="<?php echo $result_repaircause["RPC_INCARDATE"];?>" autocomplete="off" >
                                              <input type="text" name="RPC_INCARTIME" id="RPC_INCARTIME" list="avail" style="width:30%;" placeholder="เวลา" class="char" value="<?php echo $result_repaircause["RPC_INCARTIME"];?>" autocomplete="off" >
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
                                            </div>
                                          </div>        
                                          <div class="col-md-1">&nbsp;</div>  
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
                                      <center><strong>เลือกวันทีต้องการใช้รถ</strong></center>
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
                                          <div class="col-md-1">&nbsp;</div>                             
                                          <div class="col-md-10">             
                                            <div class="row input-control">        
                                              <input type="text" name="RPC_OUTCARDATE" id="RPC_OUTCARDATE" placeholder="วันที่" style="width:70%;" class="char" value="<?php echo $result_repaircause["RPC_OUTCARDATE"];?>" autocomplete="off" >
                                              <input type="text" name="RPC_OUTCARTIME" id="RPC_OUTCARTIME" list="avail" style="width:30%;" placeholder="เวลา" class="char" value="<?php echo $result_repaircause["RPC_OUTCARTIME"];?>" autocomplete="off" >
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
                                            </div>
                                          </div>        
                                          <div class="col-md-1">&nbsp;</div>  
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
                    <br>
                    <input type="hidden" name="RPRQ_CODE" id="RPRQ_CODE" value="<?=$rprq_code;?>">
                    <input type="hidden" name="proc" id="proc" value="<?=$proc;?>">
                    <input type="hidden" name="RPC_SUBJECT" id="RPC_SUBJECT" value="<?=$nature;?>">
                    <input type="hidden" name="target" id="target" value="repairdate">
                    <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="<?=$proc;?>" onclick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
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