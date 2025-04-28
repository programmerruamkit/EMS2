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
      dataTable('datatable9');
      dataTable('datatable10');
   });
    
    function save_data(psc,rprq,nature,target,chkdata) {        
      var buttonname = $('#buttonname').val();
      var url = "<?=$path?>views_amt/assign_repairwork/assign_repairwork_proc.php";
      $.ajax({
        type: "POST",
        url:url,
        data: {
              RPM_PERSONCODE: psc, 
              RPRQ_CODE: rprq, 
              RPC_SUBJECT: nature, 
              target: target,
              chkdata: chkdata
        },
        success:function(data){
          // alert(data);                 
          // loadViewdetail('<?=$path?>views_amt/assign_repairwork/assign_repairwork.php');
          ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form_repairman.php','edit','<?php print $rprq_code; ?>&nature=<?=$nature;?>&rpcid=<?=$rpcid;?>','1=1','1300','570','มอบหมายงานซ่อม');
          // closeUI();
        }
      });
    }
    
  </script>

<style>
	.largerCheckbox1 {
		width: 20px;
		height: 20px;
	}
	.largerCheckbox {
		width: 20px;
		height: 20px;
	}
</style>
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
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                      <tr>
                        <td valign="top" width="50%">
                          <div class="panel panel-default" style="margin-left:5px; margin-right:5px;">           
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                              <thead>
                                <tr class="ui-state-default">
                                  <td height="35" colspan="3" >
                                    <div class="input-control text" style="width:100%; margin-left:3px;"> 
                                      <center>
                                        <strong>
                                          แสดงช่างซ่อมทั้งหมดของประเภทงาน
                                          <?php
                                            if($nature=="EL"){
                                              echo 'ระบบไฟ';
                                            }else if($nature=="TU"){
                                              echo 'ยาง ช่วงล่าง';
                                            }else if($nature=="BD"){
                                              echo 'โครงสร้าง';
                                            }else if($nature=="EG"){
                                              echo 'เครื่องยนต์';
                                            }else if($nature=="AC"){
                                              echo 'อุปกรณ์ประจำรถ';
                                            }
                                          ?>
                                        </strong>
                                      </center>
                                    </div>
                                  </td>
                                </tr>  
                              </thead>
                              <tbody>
                                <tr height="40">
                                  <td class="bg-white">                                              
                                    <div class="input-control text" style="width:100%;">   
                                      <div class="container-fluid">
                                        <form name="form_approve" id="form_approve">     
                                          <!-- <div class="row"> -->
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable9">
                                                <thead>
                                                  <tr height="30">
                                                      <th width="5%">ลำดับ.</th>
                                                      <th width="20%">รหัสพนักงาน</th>
                                                      <th width="35%">ชื่อพนักงาน</th>
                                                      <!-- <th width="15%">พื้นที่</th> -->
                                                      <!-- <th width="15%">สถานะ</th> -->
                                                      <th width="15%">เลือก</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php 
                                                      if($nature=="EL"){
                                                        $rpm_skill1="RPM_SKILL_EL = '1' AND RPM_AREA = '$SESSION_AREA' AND RPME_CODE IS NULL";
                                                        $rpm_skill2="RPM_SKILL_EL = '1' AND RPM_AREA = '$SESSION_AREA' AND RPME_CODE IS NULL";
                                                      }else if($nature=="TU"){
                                                        $rpm_skill1="RPM_SKILL_TU = '1' AND RPM_AREA = '$SESSION_AREA' AND RPME_CODE IS NULL";
                                                        $rpm_skill2="RPM_SKILL_TU = '1' AND RPM_AREA = '$SESSION_AREA' AND RPME_CODE IS NULL";
                                                      }else if($nature=="BD"){
                                                        $rpm_skill1="RPM_SKILL_BD = '1' AND RPM_AREA = '$SESSION_AREA' AND RPME_CODE IS NULL";
                                                        $rpm_skill2="RPM_SKILL_BD = '1' AND RPM_AREA = '$SESSION_AREA' AND RPME_CODE IS NULL";
                                                      }else if($nature=="EG"){
                                                        $rpm_skill1="RPM_SKILL_EG = '1' AND RPM_AREA = '$SESSION_AREA' AND RPME_CODE IS NULL";
                                                        $rpm_skill2="RPM_SKILL_EG = '1' AND RPM_AREA = '$SESSION_AREA' AND RPME_CODE IS NULL";
                                                      }else if($nature=="AC"){
                                                        $rpm_skill1="RPM_SKILL_AC = '1' AND RPM_AREA = '$SESSION_AREA' AND RPME_CODE IS NULL";
                                                        $rpm_skill2="RPM_SKILL_AC = '1' AND RPM_AREA = '$SESSION_AREA' AND RPME_CODE IS NULL";
                                                      }

                                                      // #########################
                                                        // $sql_repairman = "SELECT * FROM	vwREPAIRMAN
                                                        // LEFT JOIN REPAIRMANEMP ON PersonCode = RPME_CODE COLLATE Thai_CI_AI AND RPRQ_CODE = '$rprq_code' COLLATE Thai_CI_AI 
                                                        // WHERE ".$rpm_skill1." 
                                                        // UNION 
                                                        // SELECT * FROM	vwREPAIRMAN
                                                        // LEFT JOIN REPAIRMANEMP ON PersonCode = RPME_CODE COLLATE Thai_CI_AI AND RPRQ_CODE = '$rprq_code' COLLATE Thai_CI_AI 
                                                        // WHERE ".$rpm_skill2."";
                                                      $sql_repairman = "SELECT * FROM	vwREPAIRMAN
                                                      LEFT JOIN REPAIRMANEMP ON 
                                                        PersonCode = RPME_CODE  COLLATE Thai_CI_AI AND 
                                                        RPRQ_CODE = '$rprq_code' 
                                                        AND RPC_SUBJECT = '$nature' 
                                                      WHERE ".$rpm_skill1." ORDER BY PersonCode ASC";
                                                      $query_repairman = sqlsrv_query($conn, $sql_repairman);
                                                      $no=0;
                                                      while($result_repairman = sqlsrv_fetch_array($query_repairman, SQLSRV_FETCH_ASSOC)){	
                                                        $no++;
                                                        $RPM_ID=$result_repairman['ID'];
                                                        $RPM_CODE=$result_repairman['RPM_CODE'];
                                                        $RPM_PERSONCODE=$result_repairman['PersonCode'];
                                                        $RPM_PERSONNAME=$result_repairman['nameT'];
                                                        $RPM_HOURSSTANDARD=$result_repairman['RPM_HOURSSTANDARD'];
                                                        $RPMNATUREREPAIR=$result_repairman['RPM_NATUREREPAIR'];                                                        
                                                        $RPM_STATUS=$result_repairman['RPM_STATUS'];
                                                        $RPM_AREA=$result_repairman['RPM_AREA'];

                                                  ?>
                                                  <tr id="<?php print $RPM_CODE; ?>" height="25px" align="center" onclick="save_data('<?=$RPM_PERSONCODE;?>','<?=$rprq_code;?>','<?=$nature;?>','repairman','add')">
                                                      <td ><?php print "$no.";?></td>
                                                      <td align="center" >&nbsp;<?php print $RPM_PERSONCODE; ?></td>
                                                      <td align="left" >&nbsp;<?php print $RPM_PERSONNAME; ?></td>
                                                      <!-- <td align="center" >&nbsp;<?php print $RPM_AREA; ?></td> -->
                                                      <!-- <td align="center" >&nbsp;</td> -->
                                                      <td align="center" >
                                                        <label>
                                                          <img src="../images/add-icon16.png" width="16" height="16" style="cursor:pointer">
                                                          <!-- <img src="../images/add-icon16.png" width="16" height="16" style="cursor:pointer" onclick="save_data('<?=$RPM_PERSONCODE;?>','<?=$rprq_code;?>','<?=$nature;?>','repairman','add')"> -->
                                                        </label>
                                                      </td>
                                                  </tr>
                                                  <?php }; ?>
                                                </tbody>
                                            </table>                                           
                                          <!-- </div> -->
                                        </form>
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
                                      <center>
                                        <strong>
                                          แสดงช่างซ่อมที่เลือก
                                        </strong>
                                      </center>
                                    </div>
                                  </td>
                                </tr>  
                              </thead>
                              <tbody>
                                <tr height="40">
                                  <td class="bg-white">                                              
                                    <div class="input-control text" style="width:100%;">   
                                      <div class="container-fluid">
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable10">
                                                <thead>
                                                  <tr height="30">
                                                      <th width="5%">ลำดับ.</th>
                                                      <th width="20%">รหัสพนักงาน</th>
                                                      <th width="35%">ชื่อพนักงาน</th>
                                                      <!-- <th width="15%">พื้นที่</th> -->
                                                      <!-- <th width="15%">สถานะ</th> -->
                                                      <th width="15%">ลบ</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php 

                                                      $sql_repairman_select = "SELECT * FROM REPAIRMANEMP WHERE RPRQ_CODE = '$rprq_code' AND RPC_SUBJECT = '$nature'";
                                                      $query_repairman_select = sqlsrv_query($conn, $sql_repairman_select);
                                                      $no=0;
                                                      while($result_repairman_select = sqlsrv_fetch_array($query_repairman_select, SQLSRV_FETCH_ASSOC)){	
                                                        $no++;
                                                        $RPME_ID=$result_repairman_select['RPME_ID'];
                                                        $RPME_RPRQ_CODE=$result_repairman_select['RPRQ_CODE'];
                                                        $RPC_SUBJECT=$result_repairman_select['RPC_SUBJECT'];
                                                        $RPME_CODE=$result_repairman_select['RPME_CODE'];
                                                        $RPME_NAME=$result_repairman_select['RPME_NAME'];
                                                        $RPME_DETAIL=$result_repairman_select['RPME_DETAIL'];
                                                        $RPME_STATUS=$result_repairman_select['RPME_STATUS'];
                                                  ?>
                                                  <tr id="<?php print $RPME_RPRQ_CODE; ?>" height="25px" align="center" onclick="save_data('<?=$RPME_CODE;?>','<?=$rprq_code;?>','<?=$nature;?>','repairman','delete')">
                                                      <td ><?php print "$no.";?></td>
                                                      <td align="center" >&nbsp;<?php print $RPME_CODE; ?></td>
                                                      <td align="left" >&nbsp;<?php print $RPME_NAME; ?></td>
                                                      <!-- <td align="center" >&nbsp;<?php print $RPM_AREA; ?></td> -->
                                                      <!-- <td align="center" >&nbsp;</td> -->
                                                      <td align="center" >
                                                        <label>
                                                          <img src="../images/delete-icon16.png" width="16" height="16" style="cursor:pointer">
                                                          <!-- <img src="../images/delete-icon16.png" width="16" height="16" style="cursor:pointer" onclick="save_data('<?=$RPME_CODE;?>','<?=$rprq_code;?>','<?=$nature;?>','repairman','delete')"> -->
                                                        </label>
                                                      </td>
                                                  </tr>
                                                  <?php }; ?>
                                                </tbody>
                                            </table>  
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <br>
                            <center>
                              <button class="bg-color-red font-white" type="button" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assign_repairwork/assign_repairwork_form.php','add','<?php print $rprq_code; ?>','1=1','1300','570','มอบหมายงานซ่อม');">ย้อนกลับ</button>
                            </center>
                          </div>   
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