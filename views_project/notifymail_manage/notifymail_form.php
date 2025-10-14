<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');
    $SESSION_AREA = $_SESSION["AD_AREA"];
  
    $proc=$_GET["proc"];
    $group_code=$_GET["id"];

    if($proc=="edit"){
        $stmt = "SELECT * FROM NOTIFY_EMAIL_GROUP WHERE GROUP_CODE = ? AND NOT STATUS='D' AND AREA = ?";
        $params = array($group_code, $SESSION_AREA);	
        $query = sqlsrv_query($conn, $stmt, $params);	
        $result_edit = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        
        // Debug: ตรวจสอบว่ามีข้อมูลหรือไม่
        if(!$result_edit) {
            echo "<script>alert('ไม่พบข้อมูลกลุ่มนี้');</script>";
        }
        // echo $stmt. "<br>";
    };
    
    if($proc=="add"){
        $stmt = "SELECT TOP 1 ID FROM NOTIFY_EMAIL_GROUP WHERE NOT STATUS='D' AND AREA = ? ORDER BY ID DESC";
        $params = array($SESSION_AREA);
        $query = sqlsrv_query( $conn, $stmt, $params);	
        $result_add = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        $LAST_ID = $result_add["ID"];
        $NEW_ID = $LAST_ID + 1;
    };  
    
    $n=8;
    function RandNum($n) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';      
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }      
        return $randomString;
    }  
    $rand_code = "NG_".RandNum($n);
?>
<?php
// เพิ่มบรรทัดนี้ก่อน HTML เพื่อดูค่า $proc
// echo "Proc value: " . $proc . "<br>";
// echo "Group code: " . $group_code . "<br>";
if($proc == "edit") {
    // echo "Edit mode detected<br>";
    if(!empty($result_edit)) {
        // echo "Data found<br>";
        // print_r($result_edit);
    } else {
        // echo "No data found<br>";
    }
}
?>
<html>
<head>
  <script type="text/javascript">
    function save_data() {
      // ตรวจสอบสำหรับแบบสัปดาห์
      var scheduleType = document.getElementById('SCHEDULE_TYPE').value;
      if(scheduleType == 'weekly') {
        var checkedDays = document.querySelectorAll('input[name="days[]"]:checked');
        if(checkedDays.length == 0) {
          alert('กรุณาเลือกวันในสัปดาห์อย่างน้อย 1 วัน');
          return false;
        }
        updateWeeklyValue(); // อัพเดทค่าก่อนส่ง
      }
      
      // ตรวจสอบอีเมล
      var emailList = document.getElementById('EMAIL_LIST').value;
      if(!emailList || emailList.trim() == '') {
        alert('กรุณาเพิ่มอีเมลอย่างน้อย 1 อีเมล');
        return false;
      }
      
      var url = "<?=$path?>views_project/notifymail_manage/notifymail_proc.php";
      $.ajax({
        type: "POST",
        url: url,
        data: $("#form_notify").serialize(),
        success: function (data) {
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: data,
            showConfirmButton: false,
            timer: 2000
          }).then((result) => {
            loadViewdetail('<?=$path?>views_project/notifymail_manage/notifymail_main.php');
            closeUI();
          })
        },
        error: function(xhr, status, error) {
          console.log('Error:', error);
          alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
      });
    }

    function addEmail() {
      var emailInput = document.getElementById('new_email');
      var emailList = document.getElementById('email_list');
      var hiddenEmails = document.getElementById('EMAIL_LIST');
      
      if(emailInput.value.trim() != '') {
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(emailPattern.test(emailInput.value.trim())) {
          var newEmail = emailInput.value.trim();
          
          // เพิ่มลงในรายการแสดง
          var emailItem = document.createElement('div');
          emailItem.className = 'email-item';
          emailItem.innerHTML = newEmail + ' <button type="button" onclick="removeEmail(this)" style="color:red;">[ลบ]</button>';
          emailList.appendChild(emailItem);
          
          // อัพเดท hidden input
          var currentEmails = hiddenEmails.value;
          hiddenEmails.value = currentEmails ? currentEmails + ',' + newEmail : newEmail;
          
          emailInput.value = '';
        } else {
          alert('กรุณาใส่อีเมลที่ถูกต้อง');
        }
      }
    }

    function removeEmail(button) {
      var emailItem = button.parentNode;
      var emailText = emailItem.textContent.replace(' [ลบ]', '');
      var hiddenEmails = document.getElementById('EMAIL_LIST');
      
      // ลบจากรายการแสดง
      emailItem.remove();
      
      // อัพเดท hidden input
      var emails = hiddenEmails.value.split(',');
      emails = emails.filter(function(email) {
        return email !== emailText;
      });
      hiddenEmails.value = emails.join(',');
    }

    function updateScheduleValue() {
      var scheduleType = document.getElementById('SCHEDULE_TYPE').value;
      var valueContainer = document.getElementById('schedule_value_container');
      
      if(scheduleType == 'daily') {
        valueContainer.innerHTML = '<input type="hidden" name="SCHEDULE_VALUE" id="SCHEDULE_VALUE" value="daily">';
      } else if(scheduleType == 'weekly') {
        valueContainer.innerHTML = `
          <table>
            <tr>
              <td colspan="7"><strong>เลือกวันในสัปดาห์:</strong></td>
            </tr>
            <tr>
              <td><input type="checkbox" name="days[]" value="monday" id="mon" onchange="updateWeeklyValue()"><label for="mon">จันทร์</label></td>
              <td><input type="checkbox" name="days[]" value="tuesday" id="tue" onchange="updateWeeklyValue()"><label for="tue">อังคาร</label></td>
              <td><input type="checkbox" name="days[]" value="wednesday" id="wed" onchange="updateWeeklyValue()"><label for="wed">พุธ</label></td>
              <td><input type="checkbox" name="days[]" value="thursday" id="thu" onchange="updateWeeklyValue()"><label for="thu">พฤหัสบดี</label></td>
              <td><input type="checkbox" name="days[]" value="friday" id="fri" onchange="updateWeeklyValue()"><label for="fri">ศุกร์</label></td>
              <td><input type="checkbox" name="days[]" value="saturday" id="sat" onchange="updateWeeklyValue()"><label for="sat">เสาร์</label></td>
              <td><input type="checkbox" name="days[]" value="sunday" id="sun" onchange="updateWeeklyValue()"><label for="sun">อาทิตย์</label></td>
            </tr>
          </table>
          <input type="hidden" name="SCHEDULE_VALUE" id="SCHEDULE_VALUE" value="">
        `;
        // เรียกใช้ฟังก์ชันเพื่อตั้งค่าเริ่มต้น
        setTimeout(function() {
          updateWeeklyValue();
        }, 100);
      } else if(scheduleType == 'monthly') {
        valueContainer.innerHTML = `
          <label>วันที่ของเดือน (1-31):</label>
          <select name="SCHEDULE_VALUE" id="SCHEDULE_VALUE">
            ${Array.from({length: 31}, (_, i) => `<option value="${i+1}">${i+1}</option>`).join('')}
          </select>
        `;
      }
    }

    // อัพเดทค่า SCHEDULE_VALUE เมื่อมีการเปลี่ยนแปลง checkbox
    function updateWeeklyValue() {
      var checkboxes = document.querySelectorAll('input[name="days[]"]:checked');
      var values = Array.from(checkboxes).map(cb => cb.value);
      var hiddenInput = document.getElementById('SCHEDULE_VALUE');
      if(hiddenInput) {
        hiddenInput.value = values.join(',');
      }
    }

    $(document).ready(function() {
      // โหลดข้อมูลเดิมถ้าเป็นการแก้ไข
      <?php if($proc == "edit" && !empty($result_edit['EMAIL_LIST'])) { ?>
        var emails = "<?=$result_edit['EMAIL_LIST']?>".split(',');
        var emailList = document.getElementById('email_list');
        emails.forEach(function(email) {
          if(email.trim()) {
            var emailItem = document.createElement('div');
            emailItem.className = 'email-item';
            emailItem.innerHTML = email.trim() + ' <button type="button" onclick="removeEmail(this)" style="color:red;">[ลบ]</button>';
            emailList.appendChild(emailItem);
          }
        });
      <?php } ?>

      // ตั้งค่า schedule type เดิม
      <?php if($proc == "edit" && !empty($result_edit['SCHEDULE_TYPE'])) { ?>
        updateScheduleValue();
        setTimeout(function() {
          <?php if($result_edit['SCHEDULE_TYPE'] == 'weekly' && !empty($result_edit['SCHEDULE_VALUE'])) { ?>
            var selectedDays = "<?=$result_edit['SCHEDULE_VALUE']?>".split(',');
            selectedDays.forEach(function(day) {
              if(day.trim()) {
                var checkbox = document.querySelector('input[value="' + day.trim() + '"]');
                if(checkbox) {
                  checkbox.checked = true;
                }
              }
            });
            updateWeeklyValue(); // อัพเดทค่าหลังจากเลือก checkbox
          <?php } elseif($result_edit['SCHEDULE_TYPE'] == 'monthly' && !empty($result_edit['SCHEDULE_VALUE'])) { ?>
            var monthlySelect = document.getElementById('SCHEDULE_VALUE');
            if(monthlySelect) {
              monthlySelect.value = "<?=$result_edit['SCHEDULE_VALUE']?>";
            }
          <?php } ?>
        }, 200);
      <?php } ?>
    });
  </script>
</head>

<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="100%">
          <div class="panel panel-default" style="margin-left:5px; margin-right:5px;">   
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
              <tr class="TOP">
                <td class="LEFT"></td>
                <td class="CENTER">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <?php if($proc=="edit"){ ?>
                        <td width="24" valign="middle" class=""><img src="https://img2.pic.in.th/pic/Process-Info-icon32.png" width="32" height="32"></td>
                        <td valign="bottom" class="">
                          <h4>&nbsp;&nbsp;แก้ไขกลุ่มการแจ้งเตือน</h4>
                        </td>
                      <?php }else{ ?>
                        <td width="24" valign="middle" class=""><img src="https://img2.pic.in.th/pic/plus-icon32.png" width="32" height="32"></td>
                        <td valign="bottom" class="">
                          <h4>&nbsp;&nbsp;เพิ่มกลุ่มการแจ้งเตือนใหม่</h4>
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
                  <form action="#" method="post" enctype="multipart/form-data" name="form_notify" id="form_notify">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                      <tbody>
                        <!-- <tr align="center" height="25px">
                          <td width="30%" height="35" align="right" class="ui-state-default"><strong>ชื่อกลุ่ม :</strong></td>
                          <td width="70%" height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="text" name="GROUP_NAME" id="GROUP_NAME" class="time" onFocus="$(this).select();" value="<?=isset($result_edit["GROUP_NAME"]) ? $result_edit["GROUP_NAME"] : '';?>" required>
                            </div>
                          </td>
                        </tr> -->
                        
                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>กลุ่มการแจ้งเตือน :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <select class="time" onFocus="$(this).select();" style="width: 100%;" name="NOTIFY_TYPE" id="NOTIFY_TYPE" required>
                                <option value="" disabled selected>-------โปรดเลือก-------</option>
                                <option value="reminder_daily"    <?php if(isset($result_edit['NOTIFY_TYPE']) && $result_edit['NOTIFY_TYPE']== "reminder_daily"){echo "selected";} ?>>เตือนทุกวัน</option>
                                <option value="overdue_alert"     <?php if(isset($result_edit['NOTIFY_TYPE']) && $result_edit['NOTIFY_TYPE']== "overdue_alert"){echo "selected";} ?>>เตือนเกินกำหนด</option>
                                <option value="reminder_weekly"   <?php if(isset($result_edit['NOTIFY_TYPE']) && $result_edit['NOTIFY_TYPE']== "reminder_weekly"){echo "selected";} ?>>เตือนรายสัปดาห์</option>
                                <option value="monthly_warning"   <?php if(isset($result_edit['NOTIFY_TYPE']) && $result_edit['NOTIFY_TYPE']== "monthly_warning"){echo "selected";} ?>>เตือนรายเดือน</option>
                                <option value="advance_warning5"  <?php if(isset($result_edit['NOTIFY_TYPE']) && $result_edit['NOTIFY_TYPE']== "advance_warning5"){echo "selected";} ?>>เตือนล่วงหน้า 5 วัน</option>
                                <option value="advance_warning10" <?php if(isset($result_edit['NOTIFY_TYPE']) && $result_edit['NOTIFY_TYPE']== "advance_warning10"){echo "selected";} ?>>เตือนล่วงหน้า 10 วัน</option>
                                <option value="advance_warning15" <?php if(isset($result_edit['NOTIFY_TYPE']) && $result_edit['NOTIFY_TYPE']== "advance_warning15"){echo "selected";} ?>>เตือนล่วงหน้า 15 วัน</option>
                                <option value="advance_warning20" <?php if(isset($result_edit['NOTIFY_TYPE']) && $result_edit['NOTIFY_TYPE']== "advance_warning20"){echo "selected";} ?>>เตือนล่วงหน้า 20 วัน</option>
                                <option value="advance_warning25" <?php if(isset($result_edit['NOTIFY_TYPE']) && $result_edit['NOTIFY_TYPE']== "advance_warning25"){echo "selected";} ?>>เตือนล่วงหน้า 25 วัน</option>
                                <option value="advance_warning30" <?php if(isset($result_edit['NOTIFY_TYPE']) && $result_edit['NOTIFY_TYPE']== "advance_warning30"){echo "selected";} ?>>เตือนล่วงหน้า 30 วัน</option>
                              </select>
                            </div>
                          </td>
                        </tr>

                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>รายชื่อผู้รับเมล :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <input type="email" id="new_email" placeholder="ใส่อีเมลแล้วกดเพิ่ม" style="width:70%;">
                              <button type="button" onclick="addEmail()" class="bg-color-blue font-white">เพิ่ม</button>
                              <div id="email_list" style="margin-top:10px; max-height:100px; overflow-y:auto; border:1px solid #ccc; padding:5px;">
                                <!-- รายการอีเมลจะแสดงที่นี่ -->
                              </div>
                              <input type="hidden" name="EMAIL_LIST" id="EMAIL_LIST" value="<?=isset($result_edit["EMAIL_LIST"]) ? $result_edit["EMAIL_LIST"] : '';?>">
                            </div>
                          </td>
                        </tr>

                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>ช่วงการแจ้งเตือน :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <select class="time" onFocus="$(this).select();" style="width: 100%;" name="SCHEDULE_TYPE" id="SCHEDULE_TYPE" onchange="updateScheduleValue()" required>
                                <option value="" disabled selected>-------โปรดเลือก-------</option>
                                <option value="daily" <?php if(isset($result_edit['SCHEDULE_TYPE']) && $result_edit['SCHEDULE_TYPE']== "daily"){echo "selected";} ?>>ทุกวัน</option>
                                <option value="weekly" <?php if(isset($result_edit['SCHEDULE_TYPE']) && $result_edit['SCHEDULE_TYPE']== "weekly"){echo "selected";} ?>>รายสัปดาห์</option>
                                <option value="monthly" <?php if(isset($result_edit['SCHEDULE_TYPE']) && $result_edit['SCHEDULE_TYPE']== "monthly"){echo "selected";} ?>>รายเดือน</option>
                              </select>
                            </div>
                          </td>
                        </tr>

                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>กำหนดการ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div id="schedule_value_container">
                              <!-- เนื้อหาจะเปลี่ยนตาม schedule type -->
                            </div>
                          </td>
                        </tr>

                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>เวลาแจ้งเตือน :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <?php 
                              $notify_time_value = '';
                              if(isset($result_edit["NOTIFY_TIME"]) && !empty($result_edit["NOTIFY_TIME"])) {
                                  // แปลงจาก datetime หรือ time object เป็น string format HH:MM
                                  if(is_object($result_edit["NOTIFY_TIME"])) {
                                      $notify_time_value = $result_edit["NOTIFY_TIME"]->format('H:i');
                                  } else {
                                      // ถ้าเป็น string ให้แปลงให้เป็น HH:MM format
                                      $time_parts = explode(':', $result_edit["NOTIFY_TIME"]);
                                      if(count($time_parts) >= 2) {
                                          $notify_time_value = sprintf('%02d:%02d', $time_parts[0], $time_parts[1]);
                                      }
                                  }
                              }
                              ?>
                              <input type="time" class="time" name="NOTIFY_TIME" id="NOTIFY_TIME" value="<?=$notify_time_value;?>" required />
                            </div>
                          </td>
                        </tr>

                        <tr align="center" height="25px">
                          <td height="35" align="right" class="ui-state-default"><strong>สถานะ :</strong></td>
                          <td height="35" align="left" class="bg-white">
                            <div class="input-control text">
                              <select class="time" onFocus="$(this).select();" style="width: 100%;" name="STATUS" id="STATUS" required>
                                <option value="" disabled selected>-------โปรดเลือก-------</option>
                                <option value="Y" <?php if(isset($result_edit['STATUS']) && $result_edit['STATUS']== "Y"){echo "selected";} ?>>เปิดใช้งาน</option>
                                <option value="N" <?php if(isset($result_edit['STATUS']) && $result_edit['STATUS']== "N"){echo "selected";} ?>>ปิดใช้งาน</option>
                              </select>
                            </div>
                          </td>
                        </tr>

                        <input type="hidden" name="ID" id="ID" value="<?=isset($result_edit['ID']) ? $result_edit['ID'] : '';?>" />
                        <input type="hidden" name="proc" id="proc" value="<?=$proc;?>" />
                        
                        <tr align="center" height="25px">
                          <td height="25" colspan="2" align="center" style="padding:20px;">
                            <?php if($proc=="edit"){ ?>
                              <input type="hidden" name="GROUP_CODE" id="GROUP_CODE" value="<?=isset($result_edit['GROUP_CODE']) ? $result_edit['GROUP_CODE'] : $group_code;?>">
                              <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onClick="save_data()">แก้ไขข้อมูล</button>&nbsp;&nbsp;&nbsp;
                            <?php } else { ?>
                              <input type="hidden" name="GROUP_CODE" id="GROUP_CODE" value="<?=$rand_code;?>">
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
  .email-item {
    background: #f0f0f0;
    padding: 5px;
    margin: 2px 0;
    border-radius: 3px;
  }
  
  .email-item button {
    background: none;
    border: none;
    cursor: pointer;
  }
</style>