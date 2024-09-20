<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$RPRQ_CODE=$_GET["id"];
	$RPRQ_REGISHEAD=$_GET["regishead"];
	$RPC_SUBJECT_CON=$_GET["pmrank"];
	$SUBJECT=$_GET["subject"];
  
  $sql_vehicleinfo = "SELECT VEHICLECARTYPE.VHCCT_ID FROM vwVEHICLEINFO 
  LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
  LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
  WHERE VEHICLEREGISNUMBER = '$RPRQ_REGISHEAD'";
  $query_vehicleinfo = sqlsrv_query($conn, $sql_vehicleinfo);
  $result_vehicleinfo = sqlsrv_fetch_array($query_vehicleinfo, SQLSRV_FETCH_ASSOC); 
  $VHCCT_ID=$result_vehicleinfo['VHCCT_ID'];
?>                             
<?php	
    switch($SUBJECT) {
      case "EL": $RPC_SUBJECT="PM-ระบบไฟ"; break;
      case "TU": $RPC_SUBJECT="PM-ยาง/ช่วงล่าง"; break;
      case "BD": $RPC_SUBJECT="PM-โครงสร้าง"; break;
      case "EG": $RPC_SUBJECT="PM-เครื่องยนต์"; break;
      case "AC": $RPC_SUBJECT="PM-อุปกรณ์ประจำรถ"; break;
    }
    // print $RPC_SUBJECT;
    
    if($RPC_SUBJECT_CON=="PMoRS-1"){
        $fildsfindCLRP = "CLRP_PM1";
    }else if($RPC_SUBJECT_CON=="PMoRS-2"){
        $fildsfindCLRP = "CLRP_PM2";
    }else if($RPC_SUBJECT_CON=="PMoRS-3"){
        $fildsfindCLRP = "CLRP_PM3";
    }else if($RPC_SUBJECT_CON=="PMoRS-4"){
        $fildsfindCLRP = "CLRP_PM4";
    }else if($RPC_SUBJECT_CON=="PMoRS-5"){
        $fildsfindCLRP = "CLRP_PM5";
    }else if($RPC_SUBJECT_CON=="PMoRS-6"){
        $fildsfindCLRP = "CLRP_PM6";
    }else if($RPC_SUBJECT_CON=="PMoRS-7"){
        $fildsfindCLRP = "CLRP_PM7";
    }else if($RPC_SUBJECT_CON=="PMoRS-8"){
        $fildsfindCLRP = "CLRP_PM8";
    }else if($RPC_SUBJECT_CON=="PMoRS-9"){
        $fildsfindCLRP = "CLRP_PM9";
    }else if($RPC_SUBJECT_CON=="PMoRS-10"){
        $fildsfindCLRP = "CLRP_PM10";
    }else if($RPC_SUBJECT_CON=="PMoRS-11"){
        $fildsfindCLRP = "CLRP_PM11";
    }else if($RPC_SUBJECT_CON=="PMoRS-12"){
        $fildsfindCLRP = "CLRP_PM12";
    }
?>
<html>
  
<head>
	<script type="text/javascript">
		$(document).ready(function(e) {
			dataTable('datatable3');
		}); 
    function save_chklist(rprqcode,clrpcode,subject,select,input) {     
      var url = "<?=$path?>views_amt/assigned_working/assigned_working_proc.php";
      $.ajax({
        type: 'post',
        url: url,
        data: {
          target: "savechklist", 
          rprqcode: rprqcode,
          clrpcode: clrpcode,
          subject: subject,
          select: select,
          input: input
        },
        success:function(data){
          // alert(data)
          ajaxPopup4('<?=$path?>views_amt/assigned_working/assigned_working_checklish.php','add','<?php print $RPRQ_CODE;?>&regishead=<?php print $RPRQ_REGISHEAD;?>&pmrank=<?php print $RPC_SUBJECT_CON;?>&subject=<?php print $SUBJECT;?>','1=1','1300','700','รายการตรวจสอบ')
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
                      <td width="24" valign="middle" class=""><img src="../images/Process-Info-icon32.png" width="32"
                          height="32"></td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;รายการตรวจสอบ</h4>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="RIGHT"></td>
              </tr>
              <tr class="CENTER">
                <td class="LEFT"></td>
                <td class="CENTER" align="center">
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" > <!-- id="datatable3" -->
                    <thead>
                      <tr height="30">
                        <th width="5%">ลำดับ.</th>
                        <th width="25%">รายการตรวจสอบ</th>
                        <th width="10%">ระยะเปลี่ยนเช็ค</th>
                        <th width="10%">สัญลักษณ์/ความหมาย</th>
                        <th width="10%">สถานะ</th>
                        <th width="35%">หมายเหตุ</th>
                        <th width="5%">เคลียร์</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $AREA=$_SESSION["AD_AREA"];
                        $sql_checklist = "SELECT * FROM CHECKLISTREPAIR A 
                          LEFT JOIN VEHICLECARTYPE B ON B.VHCCT_ID = A.CLRP_CARTYPE 
                          WHERE NOT CLRP_STATUS = 'D' 
                          AND CLRP_CARTYPE = '$VHCCT_ID' 
                          AND CLRP_TYPE = '$RPC_SUBJECT' 
                          AND $fildsfindCLRP IS NOT NULL 
                          ORDER BY CLRP_NUM ASC";
                        $query_checklist = sqlsrv_query($conn, $sql_checklist);
                        $no=0;
                        while($result_checklist = sqlsrv_fetch_array($query_checklist, SQLSRV_FETCH_ASSOC)){	
                          $no++;
                          $CLRP_ID=$result_checklist['CLRP_ID'];
                          $CLRP_CODE=$result_checklist['CLRP_CODE'];
                          $CLRP_NUM=$result_checklist['CLRP_NUM'];
                          $CLRP_NAME=$result_checklist['CLRP_NAME'];
                          $CLRP_CHECK=$result_checklist['CLRP_CHECK'];
                          $CLRP_PM=$result_checklist[$fildsfindCLRP];
                          $CLRP_TYPE=$result_checklist['CLRP_TYPE'];
                          $CLRP_RANK=$result_checklist['CLRP_RANK'];
                          $CLRP_CARTYPE=$result_checklist['CLRP_CARTYPE'];
                          $CLRP_REMARK=$result_checklist['CLRP_REMARK'];
                          $CLRP_STATUS=$result_checklist['CLRP_STATUS'];
                          
                          $sql1 = "SELECT * FROM REPAIRACTUAL_CHECKLIST WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ? AND CLRP_CODE = ?";
                          $params1 = array($RPRQ_CODE,$SUBJECT,$CLRP_CODE);	
                          $query1 = sqlsrv_query( $conn, $sql1, $params1);	
                          $result1 = sqlsrv_fetch_array($query1, SQLSRV_FETCH_ASSOC);
                            $RPATCL_ID=$result1["RPATCL_ID"];
                            $RPATCL_TYPE=$result1["RPATCL_TYPE"];
                            $RPATCL_REMARK=$result1["RPATCL_REMARK"];
                      ?>
                      <tr id="<?php print $CLRP_CODE; ?>" height="25px" align="center">
                        <td ><?php print "$no.";?></td>
                        <td align="left" >&nbsp;<?php print $CLRP_NAME; ?></td>
                        <td align="center" >&nbsp;<?php print $CLRP_CHECK; ?></td>
                        <td align="center" >&nbsp;                                         
                            <?php	
                                switch($CLRP_PM) {
                                  case "I/C": $CLRP_PM_CHK="ตรวจเช็คปรับตั้ง/ทำความสะอาด"; break;
                                  case "I": $CLRP_PM_CHK="ตรวจเช็คปรับตั้ง"; break;
                                  case "R": $CLRP_PM_CHK="เปลี่ยน"; break;
                                  case "C": $CLRP_PM_CHK="ทำความสะอาด"; break;
                                }
                                print $CLRP_PM.' = '.$CLRP_PM_CHK;
                            ?>
                        </td>
                        <td align="center" >
                          <div class="input-control text">
                              <select class="time" onFocus="$(this).select();" style="width: 100%;" name="CLRP_STATUS" id="CLRP_STATUS" onchange="save_chklist('<?=$RPRQ_CODE;?>','<?=$CLRP_CODE;?>','<?=$SUBJECT?>',this.value,'<?=$RPATCL_REMARK;?>')">
                                  <option value disabled selected>----โปรดเลือก---</option>
                                  <option value="S" <?php if($RPATCL_TYPE== "S"){echo "selected";} ?>>ซ่อมเสร็จ</option>
                                  <option value="W" <?php if($RPATCL_TYPE== "W"){echo "selected";} ?>>รอดำเนินการ</option>
                                  <option value="N" <?php if($RPATCL_TYPE== "N"){echo "selected";} ?>>ซ่อมไม่เสร็จ</option>
                              </select>
                          </div>
                        </td>           
                        <td align="center" >
                          <div class="input-control text">
                            <input type="text" name="RPATCL_REMARK" id="RPATCL_REMARK" autocomplete="off" value="<?=$RPATCL_REMARK?>" style="text-align:left;width:100%;" onchange="save_chklist('<?=$RPRQ_CODE;?>','<?=$CLRP_CODE;?>','<?=$SUBJECT;?>','<?=$RPATCL_TYPE;?>',this.value)">
                          </div>
                        </td>                
                        <td align="center" >
                          <div class="input-control text">
                            <img src='../images/check_del.gif' width='16' height='16' onclick="save_chklist('<?=$RPRQ_CODE;?>','<?=$CLRP_CODE;?>','<?=$SUBJECT;?>','','')">
                          </div>
                        </td>                  
                      </tr>
                      <?php }; ?>
                      <tr align="center" height="25px">
                        <td height="35" colspan="6" align="center">
                          <button class="bg-color-red font-white" type="button" onclick="closeUI()">ปิดหน้าจอ</button>
                        </td>
                      </tr>
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