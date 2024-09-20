<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$ctmc_id=$_GET["id"];
	$CTM_AREA = $_GET["area"];
	$AFFCOMPANY=$_GET["AFFCOMPANY"];

  
	$stmtcar = "SELECT * FROM CUSTOMER WHERE CTM_COMCODE = ? AND NOT CTM_STATUS='D'";
	$paramscar = array($AFFCOMPANY);	
	$querycar = sqlsrv_query( $conn, $stmtcar, $paramscar);	
	$result_edit_customer = sqlsrv_fetch_array($querycar, SQLSRV_FETCH_ASSOC);
	$CTM_GROUP=$result_edit_customer["CTM_GROUP"];

  if($CTM_GROUP=="cusout"){
    $stmt = "SELECT * FROM CUSTOMER_CAR WHERE VEHICLEINFOID = ?";
  }else{
    $stmt = "SELECT * FROM vwVEHICLEINFO WHERE VEHICLEINFOID = ?";
  }
  $params = array($ctmc_id);	
  $query = sqlsrv_query( $conn, $stmt, $params);	
  $result_edit_customer = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    
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
                    <?php if($CTM_GROUP=="cusin"){ ?>
                      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                        <tbody>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>เลขทะเบียนรถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEREGISNUMBER"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>กลุ่มรถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEGROUPDESC"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ประเภทรถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLETYPEDESC"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ยี่ห้อรถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["BRANDDESC"];?>">
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>ชื่อรถ (ไทย) :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["THAINAME"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ชื่อรถ (อังกฤษ) :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["ENGNAME"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ซีรีส์/รุ่น :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["SERIES"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>สีรถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["COLORDESC"];?>">
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>ประเภทเกียร์รถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["GEARTYPEDESC"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>แรงม้า :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["HORSEPOWER"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>CC :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["CC"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>เลขเครื่องยนต์ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["MACHINENUMBER"];?>">
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>เลขตัวถัง :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["CHASSISNUMBER"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ประเภทพลังงาน :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["ENERGY"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>น้ำหนักรถ (กิโลกรัม) :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["WEIGHT"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ประเภทเพลา :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["AXLETYPE"];?>">
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>ลูกสูบ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["PISTON"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>น้ำหนักบรรทุกสูงสุด :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["MAXIMUMLOAD"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>การใช้งาน (ปี) :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["USED"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ซื้อรถที่ใหน :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEBUYWHERE"];?>">
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>วันที่ซื้อ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEBUYDATE"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ราคารถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEBUYPRICE"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>เงื่อนไขการซื้อ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEBUYCONDITION"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ต่อโครงสร้างที่ใหน :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLESTRUCTUREWHERE"];?>">
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>วันที่ต่อโครงสร้าง :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLESTRUCTUREDATE"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ราคา :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLESTRUCTUREPRICE"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>วันที่จดทะเบียนครั้งแรก :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEREGISTERDATE"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>อุปกรณ์เฉพาะ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLESPECIAL"];?>">
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>หมายเหตุ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?=$result_edit_customer["REMARK"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>สถานะใช้งาน :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" onFocus="$(this).select();" disabled value="<?php if($result_edit_customer['ACTIVESTATUS']== "1"){echo "กำลังใช้งาน";}else if($result_edit_customer['CTM_STATUS']== "0"){echo "ปิดใช้งาน";} ?>" />
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" colspan="12" align="center">
                              <button class="bg-color-red font-white" type="button" onClick="closeUI()">ปิดหน้าจอ</button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    <?php }else{ ?>
                      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                        <tbody>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>เลขทะเบียนรถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="VEHICLEREGISNUMBER" id="VEHICLEREGISNUMBER" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEREGISNUMBER"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>กลุ่มรถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="VEHICLEGROUPDESC" id="VEHICLEGROUPDESC" onFocus="$(this).select();" value="<?php if($result_edit_customer["VEHICLEGROUPDESC"]!=''){echo $result_edit_customer["VEHICLEGROUPDESC"];}else{echo "Transport";};?>" class="readonly" readonly>
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ประเภทรถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="VEHICLETYPEDESC" id="VEHICLETYPEDESC" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLETYPEDESC"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ยี่ห้อรถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="BRANDDESC" id="BRANDDESC" onFocus="$(this).select();" value="<?=$result_edit_customer["BRANDDESC"];?>">
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <!-- <td height="35" align="right" class="ui-state-default"><strong>ชื่อรถ (ไทย) :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["THAINAME"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ชื่อรถ (อังกฤษ) :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["ENGNAME"];?>">
                              </div>
                            </td> -->
                            <td height="35" align="right" class="ui-state-default"><strong>ซีรีส์/รุ่น :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="SERIES" id="SERIES" onFocus="$(this).select();" value="<?=$result_edit_customer["SERIES"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>สีรถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="COLORDESC" id="COLORDESC" onFocus="$(this).select();" value="<?=$result_edit_customer["COLORDESC"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ประเภทเกียร์รถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="GEARTYPEDESC" id="GEARTYPEDESC" onFocus="$(this).select();" value="<?=$result_edit_customer["GEARTYPEDESC"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>แรงม้า :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="HORSEPOWER" id="HORSEPOWER" onFocus="$(this).select();" value="<?=$result_edit_customer["HORSEPOWER"];?>">
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>CC :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="CC" id="CC" onFocus="$(this).select();" value="<?=$result_edit_customer["CC"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>เลขเครื่องยนต์ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="MACHINENUMBER" id="MACHINENUMBER" onFocus="$(this).select();" value="<?=$result_edit_customer["MACHINENUMBER"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>เลขตัวถัง :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="CHASSISNUMBER" id="CHASSISNUMBER" onFocus="$(this).select();" value="<?=$result_edit_customer["CHASSISNUMBER"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ประเภทพลังงาน :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="ENERGY" id="ENERGY" onFocus="$(this).select();" value="<?php if($result_edit_customer["ENERGY"]!=''){echo $result_edit_customer["ENERGY"];}else{echo 'ดีเซล';};?>" class="readonly" readonly>
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>น้ำหนักรถ (กิโลกรัม) :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="WEIGHT" id="WEIGHT" onFocus="$(this).select();" value="<?=$result_edit_customer["WEIGHT"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ประเภทเพลา :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="AXLETYPE" id="AXLETYPE" onFocus="$(this).select();" value="<?=$result_edit_customer["AXLETYPE"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ลูกสูบ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="PISTON" id="PISTON" onFocus="$(this).select();" value="<?=$result_edit_customer["PISTON"];?>">
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>น้ำหนักบรรทุกสูงสุด :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="MAXIMUMLOAD" id="MAXIMUMLOAD" onFocus="$(this).select();" value="<?=$result_edit_customer["MAXIMUMLOAD"];?>">
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>การใช้งาน (ปี) :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="USED" id="USED" onFocus="$(this).select();" value="<?=$result_edit_customer["USED"];?>">
                              </div>
                            </td>
                            <!-- <td height="35" align="right" class="ui-state-default"><strong>ซื้อรถที่ใหน :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEBUYWHERE"];?>" class="readonly" readonly>
                              </div>
                            </td> -->
                          <!-- </tr> -->
                          <!-- <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>วันที่ซื้อ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEBUYDATE"];?>" class="readonly" readonly>
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ราคารถ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEBUYPRICE"];?>" class="readonly" readonly>
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>เงื่อนไขการซื้อ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEBUYCONDITION"];?>" class="readonly" readonly>
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ต่อโครงสร้างที่ใหน :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLESTRUCTUREWHERE"];?>" class="readonly" readonly>
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" align="right" class="ui-state-default"><strong>วันที่ต่อโครงสร้าง :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLESTRUCTUREDATE"];?>" class="readonly" readonly>
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>ราคา :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLESTRUCTUREPRICE"];?>" class="readonly" readonly>
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>วันที่จดทะเบียนครั้งแรก :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEREGISTERDATE"];?>" class="readonly" readonly>
                              </div>
                            </td>
                            <td height="35" align="right" class="ui-state-default"><strong>อุปกรณ์เฉพาะ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLESPECIAL"];?>" class="readonly" readonly>
                              </div>
                            </td>
                          </tr> -->
                          <!-- <tr align="center" height="25px"> -->
                            <td height="35" align="right" class="ui-state-default"><strong>หมายเหตุ :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled name="REMARK" id="REMARK" onFocus="$(this).select();" value="<?=$result_edit_customer["REMARK"];?>">
                              </div>
                            </td>         
                            <td height="35" align="right" class="ui-state-default"><strong>สถานะใช้งาน :</strong></td>
                            <td height="35" align="left" class="bg-white">
                              <div class="input-control text">
                                <input type="text" disabled onFocus="$(this).select();" value="<?php if($result_edit_customer['ACTIVESTATUS']== "1"){echo "กำลังใช้งาน";}else if($result_edit_customer['CTM_STATUS']== "0"){echo "ปิดใช้งาน";} ?>" />
                              </div>
                            </td>
                          </tr>
                          <tr align="center" height="25px">
                            <td height="35" colspan="12" align="center">
                              <button class="bg-color-red font-white" type="button" onClick="closeUI()">ปิดหน้าจอ</button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    <?php } ?>
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