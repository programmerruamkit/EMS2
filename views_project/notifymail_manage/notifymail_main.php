<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');
    $SESSION_AREA = $_SESSION["AD_AREA"];
?>
<html>
<head>
<script type="text/javascript">
    $(document).ready(function(e) {	   
        $("#button_new").click(function(){
            ajaxPopup2("<?=$path?>views_project/notifymail_manage/notifymail_form.php","add","1=1","900","590","เพิ่มกลุ่มการแจ้งเตือนใหม่");
        });
    
        $("#button_edit").click(function(){
            var ref = getIdSelect(); 
            if(ref == ""){
                alert("กรุณาเลือกรายการที่ต้องการแก้ไข");
                return false;
            }
            ajaxPopup2("<?=$path?>views_project/notifymail_manage/notifymail_form.php","edit",ref,"900","500","แก้ไขกลุ่มการแจ้งเตือน");
        });
        
        $("#button_delete").click(function(){
            var ref = getIdSelect(); 
            if(ref == ""){
                alert("กรุณาเลือกรายการที่ต้องการลบ");
                return false;
            }
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่...ที่จะลบรายการนี้?',
                text: "หากลบแล้ว คุณจะไม่สามารถกู้คืนข้อมูลได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C82333',
                confirmButtonText: 'ใช่! ลบเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ลบข้อมูลเรียบร้อยแล้ว',
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {	
                        var url = "<?=$path?>views_project/notifymail_manage/notifymail_proc.php?proc=delete&id="+ref;
                        $.ajax({
                            type:'GET',
                            url:url,
                            data:"",				
                            success:function(data){
                                loadViewdetail('<?=$path?>views_project/notifymail_manage/notifymail_main.php');
                            }
                        });
                    })	
                }
            })
        });
    });

    function swaldelete_notifymail(refcode,no) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่...ที่จะลบกลุ่มที่ '+no+' นี้',
            text: "หากลบแล้ว คุณจะไม่สามารถกู้คืนข้อมูลได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#C82333',
            confirmButtonText: 'ใช่! ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'ลบข้อมูลเรียบร้อยแล้ว',
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {	
                    var ref = refcode; 
                    var url = "<?=$path?>views_project/notifymail_manage/notifymail_proc.php?proc=delete&id="+ref;
                    $.ajax({
                        type:'GET',
                        url:url,
                        data:"",				
                        success:function(data){
                            loadViewdetail('<?=$path?>views_project/notifymail_manage/notifymail_main.php');
                        }
                    });
                })	
            }
        })
    }

    function toggleStatus(group_code, current_status) {
        var new_status = (current_status == 'Y') ? 'N' : 'Y';
        var status_text = (new_status == 'Y') ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        
        Swal.fire({
            title: 'เปลี่ยนสถานะการใช้งาน',
            text: 'คุณต้องการ' + status_text + 'กลุ่มนี้หรือไม่?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "<?=$path?>views_project/notifymail_manage/notifymail_proc.php";
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        proc: 'toggle_status',
                        group_code: group_code,
                        status: new_status
                    },
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'เปลี่ยนสถานะเรียบร้อยแล้ว',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            loadViewdetail('<?=$path?>views_project/notifymail_manage/notifymail_main.php');
                        });
                    }
                });
            }
        });
    }

    // ใช้ SweetAlert แสดงรายละเอียดอีเมลแทน
    function viewEmailDetails(emails, group_name) {
        var emailArray = emails.split(',');
        var emailList = '';
        for(var i = 0; i < emailArray.length; i++) {
            emailList += (i + 1) + '. ' + emailArray[i].trim() + '\n';
        }
        
            // html: '<div style="text-align:left;"><strong>กลุ่ม: ' + group_name + '</strong><br><br>' +
        Swal.fire({
            title: 'รายชื่อผู้รับเมลทั้งหมด',
            html: '<div style="text-align:left;"><strong>กลุ่มแจ้งเตือน: ' + group_name + '</strong><br><br>' +
                '<div style="max-height:300px; overflow-y:auto;">' +
                emailArray.map(function(email, index) {
                    return '<div style="padding:5px 0; border-bottom:1px solid #eee;">' +
                            '<i class="icon-envelope"></i> ' + (index + 1) + '. ' + email.trim() +
                            '</div>';
                }).join('') +
                '</div>' +
                '<br><strong>รวมทั้งหมด: ' + emailArray.length + ' คน</strong></div>',
            // icon: 'info',
            confirmButtonText: 'ปิด',
            width: '500px'
        });
    }
</script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
    <tr class="TOP">
        <td class="LEFT"></td>
        <td class="CENTER">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="25" valign="middle" class="">
                        <img src="../images/Documents3422334.png" width="48" height="48">
                    </td>
                    <td width="419" height="10%" valign="bottom" class="">
                        <h3>&nbsp;&nbsp;จัดการการแจ้งเตือนผ่านอีเมล</h3>
                    </td>
                    <td width="617" align="right" valign="bottom" class="" nowrap>
                        <div class="toolbar">
                            <button class="bg-color-blue" style="padding-top:8px;" title="เพิ่มกลุ่มใหม่" id="button_new">
                                <i class='icon-plus icon-large'></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
        <td class="RIGHT"></td>
    </tr>
    <tr class="CENTER">
        <td class="LEFT"></td>
        <td class="CENTER" align="center">
            <form id="form1" name="form1" method="post" action="#">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
                    <thead>
                        <tr height="30">
                            <th width="6%">ลำดับ</th>
                            <th width="15%">กลุ่มการแจ้งเตือน</th>
                            <th width="20%">รายชื่อผู้รับเมล</th>
                            <th width="15%">ช่วงเวลาแจ้งเตือน</th>
                            <th width="15%">เวลาแจ้งเตือน</th>
                            <th width="15%">จัดการข้อความ</th>
                            <th width="10%">สถานะ</th>
                            <th width="15%">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql_notify = "SELECT * FROM NOTIFY_EMAIL_GROUP WHERE NOT STATUS='D' AND AREA = ? ORDER BY ID ASC";
                            $params = array($SESSION_AREA);
                            $query_notify = sqlsrv_query($conn, $sql_notify, $params);
                            $no = 0;
                            
                            if($query_notify === false) {
                                echo "<tr><td colspan='8'>Error: ไม่สามารถโหลดข้อมูลได้</td></tr>";
                            } else {
                                while($result_notify = sqlsrv_fetch_array($query_notify, SQLSRV_FETCH_ASSOC)){	
                                    $no++;
                                    $ID = $result_notify['ID'];
                                    $GROUP_CODE = $result_notify['GROUP_CODE'];
                                    $GROUP_NAME = $result_notify['GROUP_NAME'];
                                    $NOTIFY_TYPE = $result_notify['NOTIFY_TYPE'];
                                    $EMAIL_LIST = $result_notify['EMAIL_LIST'];
                                    $SCHEDULE_TYPE = $result_notify['SCHEDULE_TYPE'];
                                    $SCHEDULE_VALUE = $result_notify['SCHEDULE_VALUE'];
                                    $NOTIFY_TIME = $result_notify['NOTIFY_TIME'];
                                    $AREA = $result_notify['AREA'];
                                    $STATUS = $result_notify['STATUS'];
                                    
                                    // แปลงประเภทการแจ้งเตือน
                                    $notify_type_array = array(
                                        'reminder_daily' => 'เตือนทุกวัน',
                                        'overdue_alert' => 'เตือนเกินกำหนด',
                                        'reminder_weekly' => 'เตือนรายสัปดาห์',
                                        'monthly_warning' => 'เตือนรายเดือน',
                                        'advance_warning5' => 'เตือนล่วงหน้า 5 วัน',
                                        'advance_warning10' => 'เตือนล่วงหน้า 10 วัน',
                                        'advance_warning15' => 'เตือนล่วงหน้า 15 วัน',
                                        'advance_warning20' => 'เตือนล่วงหน้า 20 วัน',
                                        'advance_warning25' => 'เตือนล่วงหน้า 25 วัน',
                                        'advance_warning30' => 'เตือนล่วงหน้า 30 วัน'
                                    );
                                    $notify_type_name = isset($notify_type_array[$NOTIFY_TYPE]) ? $notify_type_array[$NOTIFY_TYPE] : $NOTIFY_TYPE;
                                    
                                    // แปลงช่วงการแจ้งเตือน
                                    $schedule_text = '';
                                    if($SCHEDULE_TYPE == 'daily') {
                                        $schedule_text = 'ทุกวัน';
                                    } elseif($SCHEDULE_TYPE == 'weekly') {
                                        $days = array(
                                            'monday' => 'จันทร์', 
                                            'tuesday' => 'อังคาร', 
                                            'wednesday' => 'พุธ', 
                                            'thursday' => 'พฤหัสบดี', 
                                            'friday' => 'ศุกร์', 
                                            'saturday' => 'เสาร์', 
                                            'sunday' => 'อาทิตย์'
                                        );
                                        $selected_days = explode(',', $SCHEDULE_VALUE);
                                        $day_names = array();
                                        foreach($selected_days as $day) {
                                            if(isset($days[$day])) {
                                                $day_names[] = $days[$day];
                                            }
                                        }
                                        $schedule_text = 'ทุก ' . implode(', ', $day_names);
                                    } elseif($SCHEDULE_TYPE == 'monthly') {
                                        $schedule_text = 'วันที่ ' . $SCHEDULE_VALUE . ' ของทุกเดือน';
                                    }
                                    
                                    // แสดงรายชื่อผู้รับเมล
                                    $emails = explode(',', $EMAIL_LIST);
                                    $email_count = count($emails);
                                    $display_emails = array_slice($emails, 0, 1); // แสดงสูงสุด 1 อีเมล
                                    $email_display = implode('<br>', $display_emails);
                                    if($email_count > 1) {
                                        $email_display .= ' <small style="color:#999;">และอีก ' . ($email_count - 1) . ' คน</small>';
                                    }
                                    
                                    // จัดรูปแบบเวลา
                                    $notify_time_display = '';
                                    if(isset($NOTIFY_TIME) && !empty($NOTIFY_TIME)) {
                                        // แปลงจาก datetime หรือ time object เป็น string format HH:MM
                                        if(is_object($NOTIFY_TIME)) {
                                            $notify_time_display = $NOTIFY_TIME->format('H:i');
                                        } else {
                                            // ถ้าเป็น string ให้แปลงให้เป็น HH:MM format
                                            $time_parts = explode(':', $NOTIFY_TIME);
                                            if(count($time_parts) >= 2) {
                                                $notify_time_display = sprintf('%02d:%02d', $time_parts[0], $time_parts[1]);
                                            }
                                        }
                                    }
                            ?>
                            <tr id="<?php echo $GROUP_CODE; ?>" height="35px" align="center" <?php if($STATUS == 'N') echo 'style="opacity:0.6;"'; ?>>
                                <td><?php echo $no; ?>.</td>
                                <!-- <td align="left" style="padding-left:8px;">
                                    <strong><?php echo htmlspecialchars($GROUP_NAME); ?></strong>
                                    <?php if($STATUS == 'N') { ?>
                                        &nbsp;<small style="color:#d9534f;">(ปิดใช้งาน)</small>
                                    <?php } ?>
                                </td> -->
                                <td align="left" style="font-size:11px; padding-left:8px;">
                                    <?php echo $notify_type_name; ?>
                                    <?php if($STATUS == 'N') { ?>
                                        &nbsp;<small style="color:#d9534f;">(ปิดใช้งาน)</small>
                                    <?php } ?>
                                </td>
                                <td align="left" style="font-size:11px; padding-left:8px;">
                                    <?php echo $email_display; ?>
                                    <?php if($email_count > 0) { ?>
                                        <br><a href="javascript:void(0)" onclick="viewEmailDetails('<?php echo htmlspecialchars($EMAIL_LIST); ?>', '<?php echo htmlspecialchars($notify_type_name); ?>')" 
                                            style="color:#007bff; font-size:10px;">
                                            ดูทั้งหมด (<?php echo $email_count; ?> คน)
                                        </a>
                                    <?php } ?>
                                </td>
                                <td align="left" style="font-size:11px; padding-left:8px;">
                                    <?php echo $schedule_text; ?>
                                </td>
                                <td align="center">
                                    <strong><?php echo $notify_time_display; ?></strong>
                                </td>
                                <td align="center" style="padding-left:8px;">
                                    <button type="button" class="mini bg-color-blue" style="padding:6px 8px; margin:1px;color:#fff;" 
                                            title="จัดการข้อความ" 
                                            onclick="javascript:ajaxPopup4('<?=$path?>views_project/notifymail_manage/notifymail_message.php','message','<?php echo $GROUP_CODE; ?>','1=1','800','500','จัดการข้อความแจ้งเตือน');">
                                            จัดการข้อความ
                                    </button>
                                </td>
                                <td align="center">
                                    <a href="javascript:void(0)" onclick="toggleStatus('<?php echo $GROUP_CODE; ?>', '<?php echo $STATUS; ?>')" title="คลิกเพื่อเปลี่ยนสถานะ">
                                        <?php if($STATUS == "Y") { ?>
                                            <img src='../images/check_true.gif' width='16' height='16' alt="เปิดใช้งาน">
                                        <?php } else { ?>
                                            <img src='../images/check_del.gif' width='16' height='16' alt="ปิดใช้งาน">
                                        <?php } ?>
                                    </a>
                                </td>
                                <td align="center">
                                    <button type="button" class="mini bg-color-yellow" style="padding:6px 8px; margin:1px;" 
                                            title="แก้ไข" 
                                            onclick="javascript:ajaxPopup4('<?=$path?>views_project/notifymail_manage/notifymail_form.php','edit','<?php echo $GROUP_CODE; ?>','1=1','900','630','แก้ไขกลุ่มการแจ้งเตือน');">
                                        <i class='icon-pencil'></i>
                                    </button>
                                    <button type="button" class="mini bg-color-red" style="padding:6px 8px; margin:1px;" 
                                            title="ลบ" 
                                            onclick="swaldelete_notifymail('<?php echo $GROUP_CODE; ?>','<?php echo $no; ?>')">
                                        <i class="icon-cancel"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php 
                                }
                            }
                            if($no == 0) { ?>
                                <tr>
                                    <td colspan="8" style="text-align:center; padding:30px; color:#999;">
                                        <h4>ยังไม่มีกลุ่มการแจ้งเตือน</h4>
                                        <p>คลิกปุ่ม "เพิ่มกลุ่มใหม่" เพื่อสร้างกลุ่มการแจ้งเตือนแรก</p>
                                    </td>
                                </tr>
                            <?php } ?>
                    </tbody>
                </table>
            </form>
        </td>
        <td class="RIGHT"></td>
    </tr>
    <tr class="BOTTOM">
        <td class="LEFT"></td>
        <td class="CENTER">&nbsp;		
            <center>
                <input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_project/notifymail_manage/notifymail_main.php');">
            </center>
        </td>
        <td class="RIGHT"></td>
    </tr>
</table>
</body>
</html>