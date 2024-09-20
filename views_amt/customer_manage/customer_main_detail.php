<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$ctm_id=$_GET["id"];
	$CTM_AREA = $_GET["area"];

	// if($proc=="detail"){
		$stmt = "SELECT * FROM CUSTOMER WHERE CTM_CODE = ? AND NOT CTM_STATUS='D'";
		$params = array($ctm_id);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_edit_customer = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $CTM_CODE=$result_edit_customer["CTM_CODE"];
	// };
?>
<html>
  
<head>
  
</head>

<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="55%">
          <div class="panel panel-default" style="margin-left:5px; margin-right:5px;">   
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
              <tr class="TOP">
                <td class="LEFT"></td>
                <td class="CENTER">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="24" valign="middle" class=""><img src="../images/distributor-report-icon32.png" width="32" height="32">
                      </td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;รายละเอียด</h4>
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
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                      <tbody>
                        <tr align="center" id="22" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>รหัสบริษัท :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CTM_COMCODE" id="CTM_COMCODE" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["CTM_COMCODE"];?>" >
                            </div>
                          </td>
                        </tr>
                        <tr align="center" id="22" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>ชื่อภาษาไทย :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CTM_NAMETH" id="CTM_NAMETH" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["CTM_NAMETH"];?>">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>ชื่อภาษาอังกฤษ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CTM_NAMEEN" id="CTM_NAMEEN" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["CTM_NAMEEN"];?>">
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>หมายเลขผู้เสียภาษี :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CTM_TAXNUMBER" id="CTM_TAXNUMBER" value="<?=$result_edit_customer["CTM_TAXNUMBER"];?>" disabled onFocus="$(this).select();" />
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>เบอร์โทรศัพท์ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CTM_PHONE" id="CTM_PHONE" value="<?=$result_edit_customer["CTM_PHONE"];?>" disabled onFocus="$(this).select();"  />
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>แฟกซ์ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CTM_FAX" id="CTM_FAX" value="<?=$result_edit_customer["CTM_FAX"];?>" disabled onFocus="$(this).select();" />
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>ที่อยู่ :</strong></td>
                          <td height="35" align="left" class="bg-white" colspan="3">
                            <div class="input-control text">
                              <input type="text" name="CTM_ADDRESS" id="CTM_ADDRESS" disabled onFocus="$(this).select();" value="<?=$result_edit_customer['CTM_ADDRESS'] ;?>" />
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>ทุนจดทะเบียน :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CTM_CAPITAL" id="CTM_CAPITAL" value="<?=$result_edit_customer["CTM_CAPITAL"];?>" onKeyUp="handleEnter(this, event, 0);" onBlur="$(this).val(number_format_num($(this).val(),0))" disabled onFocus="onfocus_format(this);" />
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>วันที่จดทะเบียน :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CTM_REGISTERED" id="dateStart" style="date" disabled value="<?=$result_edit_customer["CTM_REGISTERED"];?>" />
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>ประเภทลูกค้า :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CTM_GROUP" id="CTM_GROUP" onFocus="$(this).select();" disabled value="<?php if($result_edit_customer['CTM_GROUP']== "cusin"){ echo "ลูกค้าภายใน"; }else if($result_edit_customer['CTM_GROUP']== "cusout"){ echo "ลูกค้าภายนอก"; } ?>" />
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>สถานะใช้งาน :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CTM_STATUS" id="CTM_STATUS" onFocus="$(this).select();" disabled value="<?php if($result_edit_customer['CTM_STATUS']== "Y"){echo "กำลังใช้งาน";}else if($result_edit_customer['CTM_STATUS']== "N"){echo "ปิดใช้งาน";} ?>" />
                            </div>
                          </td>
                        </tr>
                        <input type="hidden" name="CTM_ID" id="CTM_ID" value="<?=$result_edit_customer['CTM_ID'];?>" />
                        <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
                        <input type="hidden" name="ctm_id" id="ctm_id" value="<?=$ctm_id; ?>">
                        <input type="hidden" name="ctm_code" id="ctm_code" value="<?=$rand; ?>">
                        <tr align="center" height="25px">
                          <td height="35" colspan="6" align="center">
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