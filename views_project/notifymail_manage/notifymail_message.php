<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');

    $proc = $_GET["proc"];
    $group_code = $_GET["id"];
    $SESSION_AREA = $_SESSION["AD_AREA"];

    // ดึงข้อมูลกลุ่ม
    $sql_group = "SELECT * FROM NOTIFY_EMAIL_GROUP WHERE GROUP_CODE = ? AND AREA = ?";
    $params_group = array($group_code, $SESSION_AREA);
    $query_group = sqlsrv_query($conn, $sql_group, $params_group);
    $result_group = sqlsrv_fetch_array($query_group, SQLSRV_FETCH_ASSOC);

    // ดึงข้อมูลเทมเพลต
    $sql_template = "SELECT * FROM NOTIFY_EMAIL_TEMPLATE WHERE GROUP_CODE = ? AND STATUS = 'Y'";
    $params_template = array($group_code);
    $query_template = sqlsrv_query($conn, $sql_template, $params_template);

    if($result_group['NOTIFY_TYPE'] == 'reminder_daily') {
        $group_name = 'เตือนทุกวัน';
    } elseif($result_group['NOTIFY_TYPE'] == 'overdue_alert') {
        $group_name = 'เตือนเกินกำหนด';
    } elseif($result_group['NOTIFY_TYPE'] == 'reminder_weekly') {
        $group_name = 'เตือนรายสัปดาห์';
    } elseif($result_group['NOTIFY_TYPE'] == 'monthly_warning') {
        $group_name = 'เตือนรายเดือน';
    } elseif($result_group['NOTIFY_TYPE'] == 'advance_warning5') {
        $group_name = 'เตือนล่วงหน้า 5 วัน';
    } elseif($result_group['NOTIFY_TYPE'] == 'advance_warning10') {
        $group_name = 'เตือนล่วงหน้า 10 วัน';
    } elseif($result_group['NOTIFY_TYPE'] == 'advance_warning15') {
        $group_name = 'เตือนล่วงหน้า 15 วัน';
    } elseif($result_group['NOTIFY_TYPE'] == 'advance_warning20') {
        $group_name = 'เตือนล่วงหน้า 20 วัน';
    } elseif($result_group['NOTIFY_TYPE'] == 'advance_warning25') {
        $group_name = 'เตือนล่วงหน้า 25 วัน';
    } elseif($result_group['NOTIFY_TYPE'] == 'advance_warning30') {
        $group_name = 'เตือนล่วงหน้า 30 วัน';
    } else {
        $group_name = htmlspecialchars($result_group['NOTIFY_TYPE']);
    }
?>
<html>
<head>
<script type="text/javascript">
    function addTemplate() {
        var templateName = document.getElementById('template_name').value;
        var emailSubject = document.getElementById('email_subject').value;
        var emailBody = document.getElementById('email_body').value;
        
        if(!templateName.trim()) {
            alert('กรุณาระบุชื่อเทมเพลต');
            return false;
        }
        if(!emailSubject.trim()) {
            alert('กรุณาระบุหัวข้ออีเมล');
            return false;
        }
        if(!emailBody.trim()) {
            alert('กรุณาระบุเนื้อหาอีเมล');
            return false;
        }

        var url = "<?=$path?>views_project/notifymail_manage/notifymail_message_proc.php";
        var formData = {
            proc: 'add',
            group_code: '<?=$group_code?>',
            template_name: templateName,
            email_subject: emailSubject,
            email_body: emailBody,
            // template_type: document.getElementById('template_type').value
        };

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            success: function(data) {
                if(data.indexOf('สำเร็จ') > -1) {
                    Swal.fire({
                        icon: 'success',
                        title: data,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(function() {
                        loadTemplateList();
                        clearForm();
                    });
                } else {
                    alert(data);
                }
            },
            error: function() {
                alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            }
        });
    }

    // function editTemplate(id) {
    //     var url = "<?=$path?>views_project/notifymail_manage/notifymail_template_form.php?id=" + id + "&group_code=<?=$group_code?>";
    //     ajaxPopup2(url, "edit", "1=1", "700", "500", "แก้ไขเทมเพลต");
    // }
    function editTemplate(id) {
        var url = "<?=$path?>views_project/notifymail_manage/notifymail_template_form.php";
        ajaxPopup4(url, 'edit', id, 'group_code=<?=$group_code?>', '700', '500', 'แก้ไขเทมเพลต');
    }

    function deleteTemplate(id, name) {
        Swal.fire({
            title: 'ลบเทมเพลต: ' + name,
            text: "คุณแน่ใจหรือไม่ที่จะลบเทมเพลตนี้?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then(function(result) {
            if (result.isConfirmed) {
                var url = "<?=$path?>views_project/notifymail_manage/notifymail_message_proc.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {proc: 'delete', id: id},
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'ลบเรียบร้อยแล้ว',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            loadTemplateList();
                        });
                    }
                });
            }
        });
    }

    function loadTemplateList() {
        var url = "<?=$path?>views_project/notifymail_manage/notifymail_template_list.php?group_code=<?=$group_code?>";
        $.ajax({
            type: "GET",
            url: url,
            success: function(data) {
                document.getElementById('template_list').innerHTML = data;
            }
        });
    }

    function clearForm() {
        document.getElementById('template_name').value = '';
        document.getElementById('email_subject').value = '';
        document.getElementById('email_body').value = '';
        // document.getElementById('template_type').value = 'html';
    }

    function insertVariable(variable) {
        var textarea = document.getElementById('email_body');
        var startPos = textarea.selectionStart;
        var endPos = textarea.selectionEnd;
        var textBefore = textarea.value.substring(0, startPos);
        var textAfter = textarea.value.substring(endPos, textarea.value.length);
        textarea.value = textBefore + variable + textAfter;
        textarea.focus();
        textarea.setSelectionRange(startPos + variable.length, startPos + variable.length);
    }

    $(document).ready(function() {
        loadTemplateList();
    });
</script>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" width="100%">
            <div class="panel panel-default" style="margin:5px;">
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
                    <tr class="TOP">
                        <td class="CENTER">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="24" valign="middle">
                                        <img src="../images/Documents3422334.png" width="32" height="32">
                                    </td>
                                    <td valign="bottom">
                                        <h4>&nbsp;&nbsp;จัดการข้อความแจ้งเตือนกลุ่ม: <?php echo htmlspecialchars($group_name); ?></h4>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr class="CENTER">
                        <td class="CENTER" align="center">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="50%" valign="top">
                                        <br>
                                        <!-- ฟอร์มเพิ่มเทมเพลต -->
                                        <fieldset style="margin:10px; padding:15px;">
                                            <legend><strong>เพิ่มเทมเพลตใหม่</strong></legend>
                                            <table width="100%" cellpadding="5" cellspacing="0">
                                                <tr>
                                                    <td align="right" width="30%"><strong>ชื่อเทมเพลต:</strong></td>
                                                    <td>
                                                        <input type="text" id="template_name" style="width:100%;" placeholder="เช่น เทมเพลตมาตรฐาน">
                                                    </td>
                                                </tr>
                                                <!-- <tr>
                                                    <td align="right"><strong>ประเภท:</strong></td>
                                                    <td>
                                                        <select id="template_type" style="width:100%;">
                                                            <option value="html">HTML</option>
                                                            <option value="text">Text</option>
                                                        </select>
                                                    </td>
                                                </tr> -->
                                                <tr>
                                                    <td align="right"><strong>หัวข้ออีเมล:</strong></td>
                                                    <td>
                                                        <input type="text" id="email_subject" style="width:100%;" placeholder="หัวข้อการแจ้งเตือน">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="right" valign="top"><strong>เนื้อหา:</strong></td>
                                                    <td>
                                                        <textarea id="email_body" rows="10" style="width:100%;" placeholder="เนื้อหาอีเมล..."></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" align="center" style="padding:15px;">
                                                        <button type="button" onclick="addTemplate()" class="bg-color-green font-white">
                                                            <i class="icon-save"></i> บันทึกเทมเพลต
                                                        </button>
                                                        <button type="button" onclick="clearForm()" class="bg-color-gray font-white">
                                                            <i class="icon-refresh"></i> ล้างฟอร์ม
                                                        </button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </td>
                                    <td width="50%" valign="top">
                                        <br>
                                        <!-- รายการเทมเพลต -->
                                        <fieldset style="margin:10px; padding:15px;">
                                            <legend><strong>เทมเพลตที่มีอยู่</strong></legend>
                                            <div id="template_list" style="min-height:100px; max-height:400px; overflow-y:auto;">
                                                <!-- โหลดจาก AJAX -->
                                            </div>
                                        </fieldset>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr class="BOTTOM">
                        <td class="CENTER" align="center" style="padding:10px;">
                            <button type="button" onclick="closeUI()" class="bg-color-red font-white">
                                <i class="icon-cancel"></i> ปิดหน้าจอ
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
</body>
</html>