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
  $CTM_GROUP=$_GET['ctmgroup'];

	if($proc=="edit"){
		$stmt = "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_CODE = ?";
		$params = array($ru_id);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_edit_repairrequest = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $RPRQ_CODE=$result_edit_repairrequest["RPRQ_CODE"];

    $VEHICLEREGISNUMBER1=$result_edit_repairrequest["RPRQ_REGISHEAD"];
    $THAINAME1=$result_edit_repairrequest["RPRQ_CARNAMEHEAD"];
    $VEHICLEREGISNUMBER2=$result_edit_repairrequest["RPRQ_REGISTAIL"];
    $THAINAME2=$result_edit_repairrequest["RPRQ_CARNAMETAIL"];
    $AFFCOMPANY=$result_edit_repairrequest["RPRQ_LINEOFWORK"];
    $RPRQ_CARTYPE=$result_edit_repairrequest["RPRQ_CARTYPE"];
    $MAXMILEAGENUMBER=$result_edit_repairrequest["RPRQ_MILEAGELAST"];
    $VHCCT_KILOFORDAY=$result_edit_repairrequest["VHCCT_KILOFORDAY"];
    $RPRQ_REQUESTCARDATE=$result_edit_repairrequest["RPRQ_REQUESTCARDATE"];
    $RPRQ_REQUESTCARTIME=$result_edit_repairrequest["RPRQ_REQUESTCARTIME"];
    $RPRQ_USECARDATE=$result_edit_repairrequest["RPRQ_USECARDATE"];
    $RPRQ_USECARTIME=$result_edit_repairrequest["RPRQ_USECARTIME"];

	};
	if($proc=="add"){
    if($CTM_GROUP=="cusout"){
      $stmt = "SELECT * FROM CUSTOMER_CAR 
      LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
      LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
      WHERE VEHICLEINFOID = ?";      
    }else{
      $stmt = "SELECT * FROM vwVEHICLEINFO 
      LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
      LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
      WHERE VEHICLEINFOID = ?";
    }
		$params = array($ru_id);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_car = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    
    if($CTM_GROUP=="cusout"){
      // $sql_maxmil = "SELECT TOP 1 MILEAGENUMBER AS MAXMILEAGENUMBER,VEHICLEREGISNUMBER,CREATEDATE FROM TEST_MILEAGE_PM WHERE ACTIVESTATUS = 1  AND MILEAGENUMBER <> '0' AND VEHICLEREGISNUMBER = '".$result_car['VEHICLEREGISNUMBER']."' ORDER BY CREATEDATE DESC ";
      $sql_maxmil = "SELECT TOP 1 MILEAGENUMBER AS MAXMILEAGENUMBER,VEHICLEREGISNUMBER,CREATEDATE FROM TEST_MILEAGE_PM WHERE ACTIVESTATUS = 1  AND MILEAGENUMBER <> '0' AND VEHICLEREGISNUMBER = '".$result_car['VEHICLEREGISNUMBER']."' ORDER BY MILEAGEID DESC ";
    }else{
      // $sql_maxmil = "SELECT TOP 1 * FROM vwMILEAGE WHERE VEHICLEREGISNUMBER = '".$result_car['VEHICLEREGISNUMBER']."' ORDER BY CREATEDATE DESC ";
      $sql_maxmil = "SELECT TOP 1 * FROM vwMILEAGE WHERE VEHICLEREGISNUMBER = '".$result_car['VEHICLEREGISNUMBER']."' ORDER BY MILEAGEID DESC ";
    }
    $params_maxmil = array();
    $query_maxmil = sqlsrv_query($conn, $sql_maxmil, $params_maxmil);
    $result_maxmil = sqlsrv_fetch_array($query_maxmil, SQLSRV_FETCH_ASSOC);

    $VEHICLEREGISNUMBER1=$result_car["VEHICLEREGISNUMBER"];
    $THAINAME1=$result_car["THAINAME"];
    $VEHICLEREGISNUMBER2=$result_car["VEHICLEREGISNUMBER2"];
    $THAINAME2=$result_car["THAINAME2"];
    $AFFCOMPANY=$result_car["AFFCOMPANY"];
    $RPRQ_CARTYPE=$result_car["VEHICLETYPEDESC"];
    $MAXMILEAGENUMBER=$result_maxmil["MAXMILEAGENUMBER"];
    $VHCCT_KILOFORDAY=$result_car["VHCCT_KILOFORDAY"];
    

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
    $(document).ready(function(e) {
      datepicker_thai('#dateRequest_in');
      datepicker_thai('#dateRequest_out');
    });   

    function save_data() {   
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
      var ctmcomcode = $("#AFFCOMPANY").val();
      var getctmcomcode = "?ctmcomcode="+ctmcomcode;
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะบันทึกแจ้งซ่อมนี้',
        icon: 'warning',
        showCancelButton: true,
        // confirmButtonColor: '#C82333',
        confirmButtonText: 'บันทึก',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          var buttonname = $('#buttonname').val();
          var url = "<?=$path?>views_amt/request_repair/request_repair_pm_proc.php";
          $.ajax({
            type: "POST",
            url:url,
            data: $("#form_project").serialize(),
            success:function(data){
              console.log(data)
              Swal.fire({
                icon: 'success',
                title: 'บันทึกข้อมูลเสร็จสิ้น',
                showConfirmButton: false,
                timer: 2000
              }).then((result) => {	
                    // alert(data);                
                    if(buttonname=='add'){
                      log_transac_requestrepair('LA3', '-', '<?=$rand;?>');
                    }else{
                      log_transac_requestrepair('LA4', '-', '<?=$RPRQ_CODE;?>');
                    }
                    loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm_form.php'+getctmcomcode);
                    closeUI();
              })
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
        <td valign="top" width="60%">
          <div class="panel panel-default" style="margin-left:5px; margin-right:5px;">   
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
              <tr class="TOP">
                <td class="LEFT"></td>
                <td class="CENTER">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <?php if($proc=="edit"){ ?>
                        <td width="24" valign="middle" class=""><img src="../images/Process-Info-icon32.png" width="32" height="32"></td>
                        <td valign="bottom" class="">
                          <h4>&nbsp;&nbsp;แก้ไขแผน PM</h4>
                        </td>
                      <?php }else{ ?>
                        <td width="24" valign="middle" class=""><img src="../images/plus-icon32.png" width="32" height="32"></td>
                        <td valign="bottom" class="">
                          <h4>&nbsp;&nbsp;เพิ่มแผน PM</h4>
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
                  <form action="#" method="post" enctype="multipart/form-data" name="form_project" id="form_project">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                      <tbody>
                        <tr align="left" height="25px">
                          <td height="35" class="ui-state-default"><strong>ทะเบียน(หัว) :</strong></td>
                          <td height="35" class="ui-state-default"><strong>ชื่อรถ(หัว) :</strong></td>
                          <td height="35" class="ui-state-default"><strong>ทะเบียน(หาง) :</strong></td>
                          <td height="35" class="ui-state-default"><strong>ชื่อรถ(หาง) :</strong></td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="left" class="bg-white" style="width:25%;">
                            <div class="input-control text">
                              <input type="text" readonly class="readonly" onFocus="$(this).select();" name="VEHICLEREGISNUMBER1" id="VEHICLEREGISNUMBER1" value="<?php echo $VEHICLEREGISNUMBER1;?>">
                            </div>
                          </td>
                          <td height="35" align="left" class="bg-white" style="width:25%;">
                            <div class="input-control text">
                              <input type="text" readonly class="readonly" onFocus="$(this).select();" name="THAINAME1" id="THAINAME1" value="<?php echo $THAINAME1;?>">
                            </div>
                          </td>
                          <td height="35" align="left" class="bg-white" style="width:25%;">
                            <div class="input-control text">
                              <input type="text" readonly class="readonly" onFocus="$(this).select();" name="VEHICLEREGISNUMBER2" id="VEHICLEREGISNUMBER2" value="<?php echo $VEHICLEREGISNUMBER2;?>">
                            </div>
                          </td>
                          <td height="35" align="left" class="bg-white" style="width:25%;">
                            <div class="input-control text">
                              <input type="text" readonly class="readonly" onFocus="$(this).select();" name="THAINAME2" id="THAINAME2" value="<?php echo $THAINAME2;?>">
                            </div>
                          </td>
                        </tr>
                        <tr align="left" height="25px">
                          <td height="35" class="ui-state-default"><strong>ลูกค้า/สายงาน :</strong></td>
                          <td height="35" class="ui-state-default"><strong>ประเภทรถ :</strong></td>
                          <td height="35" class="ui-state-default"><strong>เลขไมล์ล่าสุด :</strong></td>
                          <td height="35" class="ui-state-default"><strong>กม./วัน :</strong></td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="left" class="bg-white" style="width:25%;">
                            <div class="input-control text">
                              <input type="text" readonly class="readonly" onFocus="$(this).select();" value="<?php echo $AFFCOMPANY;?>" name="AFFCOMPANY" id="AFFCOMPANY">
                            </div>
                          </td>
                          <td height="35" align="left" class="bg-white" style="width:25%;">
                            <div class="input-control text">
                              <input type="text" readonly class="readonly" onFocus="$(this).select();" value="<?php echo $RPRQ_CARTYPE;?>" name="RPRQ_CARTYPE" id="RPRQ_CARTYPE">
                            </div>
                          </td>
                          <td height="35" align="left" class="bg-white" style="width:25%;">
                            <div class="input-control text">
                              <input type="text" readonly class="readonly" onFocus="$(this).select();" value="<?php echo $MAXMILEAGENUMBER;?>" name="MAXMILEAGENUMBER" id="MAXMILEAGENUMBER">
                            </div>
                          </td>
                          <td height="35" align="left" class="bg-white" style="width:25%;">
                            <div class="input-control text">
                              <input type="text" readonly class="readonly" onFocus="$(this).select();" value="<?php echo $VHCCT_KILOFORDAY;?>">
                            </div>
                          </td>
                        </tr>
                        <tr align="left" height="25px">
                          <td height="35" class="ui-state-default"><strong>วันที่ เวลา นัดรถเข้าซ่อม :</strong></td>
                          <td height="35" class="ui-state-default"><strong>วันที่ เวลา ต้องการใช้รถ :</strong></td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="left" class="bg-white" style="width:25%;">
                            <div class="input-control text">
                              <input type="text" name="datetimeRequest_in" id="datetimeRequest_in" class="datepic char" placeholder="วันที่ เวลา" autocomplete="off" value="<?php echo $RPRQ_REQUESTCARDATE;?> <?php echo $RPRQ_REQUESTCARTIME;?>">               
                            </div>
                          </td>
                          <td height="35" align="left" class="bg-white" style="width:25%;">
                            <div class="input-control text">
                              <input type="text" name="datetimeRequest_out" id="datetimeRequest_out" class="datepic char" placeholder="วันที่ เวลา" autocomplete="off" value="<?php echo $RPRQ_USECARDATE;?> <?php echo $RPRQ_USECARTIME;?>">                         
                            </div>
                          </td>
                        </tr>                        
                        <?php     
                          if($proc=="edit"){
                            $CREATEBY=$result_edit_repairrequest["RPRQ_CREATEBY"];
                            $stmt_emp_create = "SELECT * FROM vwEMPLOYEE WHERE PersonCode = ?";
                            $params_emp_create = array($CREATEBY);	
                            $query_emp_create = sqlsrv_query( $conn, $stmt_emp_create, $params_emp_create);	
                            $result_edit_emp_create = sqlsrv_fetch_array($query_emp_create, SQLSRV_FETCH_ASSOC);
                            $RPRQ_CREATEBY= $result_edit_emp_create["PersonCode"];
                            $RPRQ_REQUESTBY = $result_edit_emp_create["nameT"];
                          }else{
                            $RPRQ_CREATEBY= $_SESSION["AD_PERSONCODE"];
                            $RPRQ_REQUESTBY = $_SESSION["AD_NAMETHAI"];
                          }                    
                  
                        ?>
                        <input type="hidden" name="RPRQ_CREATEDATE_REQUEST" id="RPRQ_CREATEDATE_REQUEST" value="<?php if($proc=="edit"){echo $result_edit_repairrequest["RPRQ_CREATEDATE_REQUEST"];}else{echo $GETDAYEN;}?>">
                        <input type="hidden" name="RPRQ_CREATEBY" id="RPRQ_CREATEBY" value="<?php echo $RPRQ_CREATEBY;?>">
                        <input type="hidden" name="EMP_NAME_RQRP" id="EMP_NAME_RQRP" value="<?php echo $RPRQ_REQUESTBY;?>">
                        <input type="hidden" name="GOTC" id="GOTC" value="ost" />
                        <input type="hidden" name="RPRQ_WORKTYPE" id="RPRQ_WORKTYPE" value="PM" />
                        <input type="hidden" name="NTORNNW" id="NTORNNW" value="bfw" >
                        <input type="hidden" name="TYPECUSTOMERS" id="TYPECUSTOMERS" value="<?=$CTM_GROUP;?>">
                        <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
                        <input type="hidden" name="RPRQ_AREA" id="RPRQ_AREA" value="<?=$_SESSION["AD_AREA"];?>" />
                        <tr align="center" height="25px">
                          <td height="35" colspan="4" align="center">
                            <?php if($proc=="edit"){ ?>
                              <input type="hidden" name="RPRQ_CODE" id="RPRQ_CODE" value="<?=$result_edit_repairrequest["RPRQ_CODE"];?>">
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onClick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                            <?php }else{ ?>
                              <input type="hidden" name="RPRQ_CODE" id="RPRQ_CODE" value="<?=$rand; ?>">
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="add" onClick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
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
          </div>
        </td>
        <?php if($proc=="edit"){ ?>
          <td valign="top">
            <div style="width:100%;" align="right">
              <div class="panel panel-default" style="height:100%">
                <div align="left" class="panel-heading">
                  <h5><img src="../images/task.png" width="32" height="32"> Log Transaction</h5>
                </div>   
                <table width="100%" class="table table-hover table-striped">
                  <thead>
                    <tr height="35">
                      <th width="5%" align="center"><strong>#</strong></th>
                      <th width="15%" align="left"><strong>กิจกรรม</strong></th>
                      <th width="40%" align="left"><strong>หมายเหตุ</strong></th>
                      <th width="20%" align="left"><strong>ผู้บันทึก</strong></th>
                      <th width="20%" align="center"><strong>วันเวลาที่บันทึก</strong></th>
                    </tr>
                  </thead>                
                  <?php
                    $sql_log = "SELECT * FROM vwLOG_TRANSAC WHERE LOG_RESERVE_CODE = ? AND LOGACT_CODE BETWEEN 'LA3' AND 'LA4' ORDER BY TIMESTAMP ASC";
                    $params = array($ru_id);	
                    $query_log = sqlsrv_query($conn, $sql_log, $params);	
                    $no=0;
                    while($result_log = sqlsrv_fetch_array($query_log, SQLSRV_FETCH_ASSOC)){	
                      $no++;
                      $MEAN=$result_log['MEAN'];
                      $LOG_REMARK=$result_log['LOG_REMARK'];
                      $WORKINGBY=$result_log['WORKINGBY'];
                      $DATETIME1038=$result_log['DATETIME1038'];
                  ?>
                    <tr height="25">
                      <td align="center"><font size="2"><?php print "$no.";?></font></td>
                      <td><font size="2"><?php print "$MEAN.";?></font></td>
                      <td><font size="2"><?php print "$LOG_REMARK.";?></font></td>
                      <td><font size="2"><?php print "$WORKINGBY.";?></font></td>
                      <td align="center"><font size="2"><?php print "$DATETIME1038.";?></font></td>
                    </tr>                
                  <?php }; ?>
                  <?php // echo "<tr><td height='35' colspan='4' align='center'>ไม่พบข้อมูล</td></tr>"; ?>
                </table>
              </div>
            </div>
          </td>
        <?php } ?>
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
        autoclose: true
    });
</script>