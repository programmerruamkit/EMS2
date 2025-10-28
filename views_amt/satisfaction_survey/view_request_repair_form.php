<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$get_id=$_GET["id"];
  $customer_name = isset($_GET['customer_name']) ? urldecode($_GET['customer_name']) : '';
  $repair_id = isset($_GET['repair_id']) ? $_GET['repair_id'] : '';

	if(($proc=="edit")||($proc=="copy")){
    // SQL
	};
	if($proc=="add"){
    // SQL
	};  
	if($proc=="edit"){
    // SQL
	}; 
  
  $SESSION_AREA=$_SESSION["AD_AREA"];
?>
<html>
  
<head>
    <!-- QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    
    <!-- SweetAlert2 for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- jQuery (if not already included) -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    
    <!-- DateTime Picker (if not already included) -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"> -->
    
    <!-- Custom JavaScript File -->
    <script type="text/javascript" src="<?=$path;?>views_amt/satisfaction_survey/view_request_repair_form.js?ver=<?php echo date('YmdHi')?>"></script>

    <!-- Debug Script Loading -->
    <script>
        console.log('🔄 Loading external JavaScript file...');
        console.log('📁 Current page:', window.location.href);
        console.log('⏰ Timestamp:', new Date().toISOString());
        
        // ตรวจสอบว่าไฟล์โหลดสำเร็จหรือไม่
        window.addEventListener('load', function() {
            if (typeof generateQRCode === 'function') {
                console.log('✅ JavaScript file loaded successfully!');
                console.log('🎯 Functions available:', {
                    generateQRCode: typeof generateQRCode,
                    save_data: typeof save_data,
                    copyToClipboard: typeof copyToClipboard,
                    // closeUI: typeof closeUI
                });
            } else {
                console.error('❌ JavaScript file failed to load!');
                console.log('🔍 Check file path: view_request_repair_form.js');
            }
        });
        
        // ตรวจสอบ JavaScript errors
        window.addEventListener('error', function(e) {
            if (e.filename && e.filename.includes('view_request_repair_form.js')) {
                console.error('💥 JavaScript Error in external file:', {
                    message: e.message,
                    filename: e.filename,
                    line: e.lineno,
                    column: e.colno
                });
            }
        });
    </script>
</head>

<body>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
    <tr class="TOP">
      <td class="LEFT"></td>
      <td class="CENTER">
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="24" valign="middle" class=""><img src="https://img2.pic.in.th/pic/product-sales-report-icon32.png" width="32"
                  height="32"></td>
              <td valign="bottom" class="">
                <h4>&nbsp;&nbsp;ส่งแบบสอบถาม</h4>
              </td>
            </tr>
          </table>
      </td>
      <td class="RIGHT"></td>
    </tr>
    <tr class="CENTER">
      <td class="LEFT"></td>
      <td class="CENTER" align="center">
        <form action="#" method="post" enctype="multipart/form-data" name="input_request" id="input_request">      
          <table width="100%" cellpadding="0" cellspacing="0" border="0" class="INVENDATA no-border">
            <tbody>
              <tr height="0px">
                <td width="30%">&nbsp;</td>
                <td width="70%">&nbsp;</td>
              </tr>
              <tr align="center" height="25px">
                <td height="35" align="right" class="ui-state-default"><strong>เลือกกลุ่มแบบประเมิน:</strong></td>
                <td height="35" align="left">
                  <div class="input-control text">                          
                    <?php
                        // ดึงข้อมูลกลุ่มแบบสอบถามที่ active
                        $stmt_surveys = "SELECT SM_ID, SM_CODE, SM_NAME, SM_TARGET_GROUP FROM SURVEY_MAIN WHERE SM_STATUS = 'Y' AND SM_AREA = '$SESSION_AREA' ORDER BY SM_ID ASC";
                        $query_surveys = sqlsrv_query($conn, $stmt_surveys);
                    ?>
                    <select class="char" onFocus="$(this).select();" style="width: 100%;" name="survey" id="survey" required onchange="generateQRCode()">
                        <option value="" disabled selected>-------เลือกกลุ่มแบบประเมิน-------</option>
                        <?php while($survey = sqlsrv_fetch_array($query_surveys, SQLSRV_FETCH_ASSOC)) {
                            $SM_ID = $survey['SM_ID'];
                            $SM_NAME = $survey['SM_NAME'];
                            $SM_TARGET_GROUP = $survey['SM_TARGET_GROUP'];
                            
                            // ตัดชื่อให้สั้นลงถ้ายาวเกินไป
                            $button_text = $SM_NAME;
                            if (mb_strlen($button_text) > 20) {
                                $button_text = mb_substr($button_text, 0, 17) . '...';
                            }                                                       
                        ?>
                          <option value="<?= $SM_ID ?>" <?php if($SM_ID==$getsurvey){echo "selected";} ?>><?= htmlspecialchars($SM_TARGET_GROUP) ?></option>
                        <?php } ?>   
                      </select>
                  </div>
                </td>
              </tr> 
              <tr align="center" height="25px">
                <td height="35" align="right" class="ui-state-default"><strong>QR Code แบบสอบถาม:</strong></td>
                <td height="35" align="left">
                  <div class="input-control text" id="qrcode_container" style="padding: 10px; text-align: center; border: 2px dashed #ccc; border-radius: 8px; background-color: #f9f9f9;">      
                    <div id="qrcode_display" style="display: none;">
                        <div id="qrcode" style="margin: 0px 0;"></div>
                        <div style="margin-top: 5px;">
                            <strong>URL สำหรับแบบสอบถาม:</strong><br>
                            <input type="text" id="survey_url" readonly style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background-color: #f5f5f5; font-size: 12px;">
                            <br><br>
                            <button type="button" onclick="copyToClipboard()" class="bg-color-yellow" >📋 คัดลอก URL</button>
                            <!-- &nbsp;&nbsp;
                            <button type="button" onclick="downloadQRCode()" class="bg-color-orange" >💾 ดาวน์โหลด QR</button> -->
                            &nbsp;&nbsp;
                            <button type="button" id="survey_link_btn" onclick="openSurveyFromButton()" class="bg-color-blue" >ไปยังแบบประเมิน</button>
                        </div>
                    </div>
                    <div id="qrcode_placeholder">
                        <i class="icon-qrcode" style="font-size: 48px; color: #ccc;"></i><br>
                        <span style="color: #999;">เลือกกลุ่มแบบประเมินเพื่อสร้าง QR Code</span>
                    </div>
                  </div>
                </td>
              </tr>
              <tr align="center" height="25px">
                <td height="35" align="right" class="ui-state-default"><strong>อีเมลของลูกค้าที่มีในระบบ:</strong></td>
                <td height="35" align="left">
                  <div class="input-control text" style="display: flex; align-items: center; gap: 10px;">                          
                    <?php
                        // ดึงข้อมูลอีเมลลูกค้าที่มีในระบบ
                        $stmt_emails = "SELECT * FROM CUSTOMER WHERE CTM_COMCODE = '$customer_name' OR CTM_NAMETH = '$customer_name'";
                        $query_emails = sqlsrv_query($conn, $stmt_emails);
                        $result_email = sqlsrv_fetch_array($query_emails, SQLSRV_FETCH_ASSOC);
                        $customer_id = isset($result_email['CTM_ID']) ? $result_email['CTM_ID'] : '';
                    ?>
                    <input type="email" 
                            class="char" 
                            onFocus="$(this).select();" 
                            style="width: 70%; flex: 1;" 
                            name="customer_email" 
                            id="customer_email" 
                            value="<?php echo isset($result_email['CTM_MAIL']) ? $result_email['CTM_MAIL'] : ''; ?>" 
                            placeholder="กรอกอีเมลลูกค้า เช่น a@email.com,b@email.com" 
                            multiple>
                      <input type="hidden" id="customer_name" value="<?php echo addslashes($customer_name); ?>">
                      <button type="button" 
                              class="bg-color-blue" 
                              id="update_email_btn" 
                              onclick="updateCustomerEmail()"
                              style="padding: 0px 16px; min-width: 120px;"
                              title="อัพเดทอีเมลลงฐานข้อมูล">
                          💾 อัพเดทอีเมล
                      </button>
                  </div>
                            
                  <!-- เพิ่มคำแนะนำใต้ช่อง input -->
                  <div style="margin-top: 5px; font-size: 11px; color: #6c757d;">
                      💡 <strong>เคล็ดลับ:</strong> ใส่หลายอีเมลได้ โดยใช้เครื่องหมาย , คั่น เช่น aa@mail.com,bb@mail.com
                  </div>
                  
                  <!-- แสดงสถานะการอัพเดท -->
                  <div id="email_status" style="margin-top: 5px; font-size: 12px;"></div>
                  
                  <!-- เก็บข้อมูลลูกค้า -->
                  <input type="hidden" id="customer_id" value="<?php echo $customer_id; ?>">
                  <input type="hidden" id="original_email" value="<?php echo isset($result_email['CTM_MAIL']) ? $result_email['CTM_MAIL'] : ''; ?>">
                </td>
              </tr>
            </tbody>
          </table>
          <br>
          <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
          <input type="hidden" name="rp" id="rp" value="<?=$get_id;?>">
          <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
            <tbody>    
              <tr align="center" height="25px">
                <td height="35" colspan="2" align="center">
                  <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname"  onclick="save_data()">📋 ส่งแบบสอบถามผ่านเมล
                  </button>
                  <button class="bg-color-red font-white" type="button" onclick="closeUI3()">❌ ปิดหน้าจอ</button>
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
</body>
</html>
<style>
  input[type=file]::file-selector-button {
    margin-right: 20px;
    border: none;
    background: #0000FF;
    padding: 10px 20px;
    border-radius: 10px;
    color: #fff;
    cursor: pointer;
    transition: background .2s ease-in-out;
  }
  input[type=file]::file-selector-button:hover {
    background: #4169E1;
  }
  
  /* Button Gradient Styles */
  .bg-color-yellow {
      background: linear-gradient(135deg, #FFD700, #FFC107);
      border: none;
      color: #333;
      padding: 0px 16px;
      border-radius: 25px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
  }

  .bg-color-yellow:hover {
      background: linear-gradient(45deg, #FFA500, #FFD700);
      box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
  }

  .bg-color-orange {
      background: linear-gradient(135deg, #FF6B35, #F7931E);
      border: none;
      color: white;
      padding: 0px 16px;
      border-radius: 25px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
  }

  .bg-color-orange:hover {
      background: linear-gradient(45deg, #FF8A65, #FFB74D);
      box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
  }

  .bg-color-blue {
      background: linear-gradient(135deg, #007BFF, #0056B3);
      border: none;
      color: white;
      padding: 0px 16px;
      border-radius: 25px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
  }

  .bg-color-blue:hover {
      background: linear-gradient(45deg, #0056B3, #007BFF);
      box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
  }

  .bg-color-green {
      background: linear-gradient(135deg, #28A745, #28A745);
      border: none;
      color: white;
      padding: 0px 20px;
      border-radius: 25px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
  }

  .bg-color-green:hover {
      background: linear-gradient(45deg, #20C997, #20C997);
      box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
  }

  .bg-color-red {
      background: linear-gradient(135deg, #DC3545, #C82333);
      border: none;
      color: white;
      padding: 0px 20px;
      border-radius: 25px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
  }

  .bg-color-red:hover {
      background: linear-gradient(45deg, #C82333, #DC3545);
      box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
  }
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

  .col-md-6 {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%
  }
</style>
<!-- 🎯 JavaScript ทั้งหมดถูกย้ายไปยังไฟล์ view_request_repair_form.js แล้ว -->
