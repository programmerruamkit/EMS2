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
	$GET_FROM=$_GET['from'];
	$CTMC_COMCODE = $_GET["ctm_comcode"];
  
  $stmt = "SELECT * FROM CUSTOMER_CAR WHERE CTMC_CODE = ?";
  $params = array($ctmc_id);	
  $query = sqlsrv_query( $conn, $stmt, $params);	
  $result_edit_customer = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    
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
	$rand="CTMC_".RandNum($n);
?>
<html>
  
<head>
  <script type="text/javascript">
    function save_data() {
      // if(!confirm("ยืนยันการแก้ไข")) return false;
      var buttonname = $('#buttonname').val();
      var url = "<?=$path?>views_amt/customer_manage/customer_car_proc.php";
      
      if($('#VEHICLEREGISNUMBER').val() == '' ){
        Swal.fire({
            icon: 'warning',
            title: 'กรุณาระบุเลขทะเบียนรถ',
            showConfirmButton: false,
            timer: 1500,
            onAfterClose: () => {
                setTimeout(() => $("#VEHICLEREGISNUMBER").focus(), 0);
            }
        })
        // alert("กรุณาระบุเลขทะเบียนรถ");
        // document.getElementById('VEHICLEREGISNUMBER').focus();
        return false;
      }	
      if($('#ACTIVESTATUS').val() == '' ){
        Swal.fire({
            icon: 'warning',
            title: 'กรุณาเลือกสถานะใช้งาน',
            showConfirmButton: false,
            timer: 1500,
            onAfterClose: () => {
                setTimeout(() => $("#ACTIVESTATUS").focus(), 0);
            }
        })
        // alert("กรุณาระบุวันที่นำรถเข้าซ่อม");
        // document.getElementById('ACTIVESTATUS').focus();
        return false;
      }	
      $.ajax({
        type: "POST",
        url: url,
        data: $("#form_project").serialize(),
        success: function (data) {
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: data,
            showConfirmButton: false,
            timer: 2000
          }).then((result) => {
            if(buttonname=='add'){
              // log_transac_customer('LA17', '-', '<?=$rand;?>');
              loadViewdetail('<?=$path?>views_amt/customer_manage/customer_car.php?ctm_comcode=<?=$CTMC_COMCODE;?><?php if($GET_FROM=='pm'){ echo '&from=pm'; }?>');
            }else{
              // log_transac_customer('LA18', '-', '<?=$result_edit_customer["CTMC_CODE"];?>');
              loadViewdetail('<?=$path?>views_amt/customer_manage/customer_car.php?ctm_comcode=<?=$result_edit_customer["AFFCOMPANY"];?><?php if($GET_FROM=='pm'){ echo '&from=pm'; }?>');
            }
            closeUI();
            // alert(data);
          })
        }
      });
    }
  </script>
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
                      <td width="24" valign="middle" class=""><img src="../images/add-icon32.png" width="32" height="32">
                      </td>
                      <td valign="bottom" class="">
                        <h4>&nbsp;&nbsp;เพิ่มรถลูกค้าใหม่</h4>
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
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>เลขทะเบียนรถ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="VEHICLEREGISNUMBER" id="VEHICLEREGISNUMBER" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEREGISNUMBER"];?>" class="char">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>กลุ่มรถ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="VEHICLEGROUPDESC" id="VEHICLEGROUPDESC" onFocus="$(this).select();" value="<?php if($result_edit_customer["VEHICLEGROUPDESC"]!=''){echo $result_edit_customer["VEHICLEGROUPDESC"];}else{echo "Transport";};?>" class="readonly" readonly>
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>ประเภทรถ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="VEHICLETYPEDESC" id="VEHICLETYPEDESC" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLETYPEDESC"];?>">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>ยี่ห้อรถ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="BRANDDESC" id="BRANDDESC" onFocus="$(this).select();" value="<?=$result_edit_customer["BRANDDESC"];?>">
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <!-- <td height="35" align="right" class="ui-state-default"><strong>ชื่อรถ (ไทย) :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["THAINAME"];?>">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>ชื่อรถ (อังกฤษ) :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["ENGNAME"];?>">
                            </div>
                          </td> -->
                          <td height="35" align="right" class="ui-state-default"><strong>ซีรีส์/รุ่น :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="SERIES" id="SERIES" onFocus="$(this).select();" value="<?=$result_edit_customer["SERIES"];?>">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>สีรถ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="COLORDESC" id="COLORDESC" onFocus="$(this).select();" value="<?=$result_edit_customer["COLORDESC"];?>">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>ประเภทเกียร์รถ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="GEARTYPEDESC" id="GEARTYPEDESC" onFocus="$(this).select();" value="<?=$result_edit_customer["GEARTYPEDESC"];?>">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>แรงม้า :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="HORSEPOWER" id="HORSEPOWER" onFocus="$(this).select();" value="<?=$result_edit_customer["HORSEPOWER"];?>">
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>CC :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CC" id="CC" onFocus="$(this).select();" value="<?=$result_edit_customer["CC"];?>">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>เลขเครื่องยนต์ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="MACHINENUMBER" id="MACHINENUMBER" onFocus="$(this).select();" value="<?=$result_edit_customer["MACHINENUMBER"];?>">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>เลขตัวถัง :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="CHASSISNUMBER" id="CHASSISNUMBER" onFocus="$(this).select();" value="<?=$result_edit_customer["CHASSISNUMBER"];?>">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>ประเภทพลังงาน :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="ENERGY" id="ENERGY" onFocus="$(this).select();" value="<?php if($result_edit_customer["ENERGY"]!=''){echo $result_edit_customer["ENERGY"];}else{echo 'ดีเซล';};?>" class="readonly" readonly>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>น้ำหนักรถ (กิโลกรัม) :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="WEIGHT" id="WEIGHT" onFocus="$(this).select();" value="<?=$result_edit_customer["WEIGHT"];?>">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>ประเภทเพลา :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="AXLETYPE" id="AXLETYPE" onFocus="$(this).select();" value="<?=$result_edit_customer["AXLETYPE"];?>">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>ลูกสูบ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="PISTON" id="PISTON" onFocus="$(this).select();" value="<?=$result_edit_customer["PISTON"];?>">
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>น้ำหนักบรรทุกสูงสุด :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="MAXIMUMLOAD" id="MAXIMUMLOAD" onFocus="$(this).select();" value="<?=$result_edit_customer["MAXIMUMLOAD"];?>">
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>การใช้งาน (ปี) :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="USED" id="USED" onFocus="$(this).select();" value="<?=$result_edit_customer["USED"];?>">
                            </div>
                          </td>
                          <!-- <td height="35" align="right" class="ui-state-default"><strong>ซื้อรถที่ใหน :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEBUYWHERE"];?>" class="readonly" readonly>
                            </div>
                          </td> -->
                        <!-- </tr> -->
                        <!-- <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>วันที่ซื้อ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEBUYDATE"];?>" class="readonly" readonly>
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>ราคารถ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEBUYPRICE"];?>" class="readonly" readonly>
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>เงื่อนไขการซื้อ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEBUYCONDITION"];?>" class="readonly" readonly>
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>ต่อโครงสร้างที่ใหน :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLESTRUCTUREWHERE"];?>" class="readonly" readonly>
                            </div>
                          </td>
                        </tr>
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>วันที่ต่อโครงสร้าง :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLESTRUCTUREDATE"];?>" class="readonly" readonly>
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>ราคา :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLESTRUCTUREPRICE"];?>" class="readonly" readonly>
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>วันที่จดทะเบียนครั้งแรก :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLEREGISTERDATE"];?>" class="readonly" readonly>
                            </div>
                          </td>
                          <td height="35" align="right" class="ui-state-default"><strong>อุปกรณ์เฉพาะ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="" id="" onFocus="$(this).select();" value="<?=$result_edit_customer["VEHICLESPECIAL"];?>" class="readonly" readonly>
                            </div>
                          </td>
                        </tr> -->
                        <!-- <tr align="center" height="25px"> -->
                          <td height="35" align="right" class="ui-state-default"><strong>หมายเหตุ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="REMARK" id="REMARK" onFocus="$(this).select();" value="<?=$result_edit_customer["REMARK"];?>">
                            </div>
                          </td>                          
                          <td height="35" align="right" class="ui-state-default"><strong>สถานะใช้งาน :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <select class="char" onFocus="$(this).select();" style="width: 100%;" name="ACTIVESTATUS" id="ACTIVESTATUS" required>
                                <option value disabled selected>-------โปรดเลือก-------</option>
                                <option value="1" <?php if($result_edit_customer['ACTIVESTATUS']== "1"){echo "selected";} ?>>เปิดใช้งาน
                                </option>
                                <option value="N" <?php if($result_edit_customer['ACTIVESTATUS']== "N"){echo "selected";} ?>>ปิดใช้งาน
                                </option>
                              </select>
                            </div>
                          </td>
                        </tr>
                        <input type="hidden" name="AFFCOMPANY" id="AFFCOMPANY" value="<?=$CTMC_COMCODE;?>" />
                        <input type="hidden" name="VEHICLEINFOID" id="VEHICLEINFOID" value="<?=$result_edit_customer['VEHICLEINFOID'];?>" />
                        <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
                        <input type="hidden" name="ctmc_id" id="ctmc_id" value="<?=$ctmc_id; ?>">
                        <tr align="center" height="25px">
                          <td height="35" colspan="8" align="center">
                            <?php if($proc=="edit"){ ?>
                              <input type="hidden" name="ctmc_code" id="ctmc_code" value="<?=$result_edit_customer['CTMC_CODE'];?>">
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onClick="save_data()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                            <?php }else{ ?>
                              <input type="hidden" name="ctmc_code" id="ctmc_code" value="<?=$rand; ?>">
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