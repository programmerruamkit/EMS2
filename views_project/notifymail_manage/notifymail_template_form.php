<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');

    $proc = $_GET["proc"];
    $id = $_GET["id"];
    $group_code = $_GET["group_code"];
    
    if($proc == "edit" && !empty($id)) {
        $sql = "SELECT * FROM NOTIFY_EMAIL_TEMPLATE WHERE ID = ?";
        $params = array($id);
        $query = sqlsrv_query($conn, $sql, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    }
?>
<html>
<head>
<script type="text/javascript">
    function saveTemplate() {
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
            proc: 'edit',
            id: '<?=$id?>',
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
                        // closeUI();
                        // Refresh the template list in parent window
                        // if(window.opener && window.opener.loadTemplateList) {
                        //     window.opener.loadTemplateList();
                        // }
                        ajaxPopup4('<?=$path?>views_project/notifymail_manage/notifymail_message.php','message','<?php echo $result['GROUP_CODE']; ?>','1=1','800','500','จัดการข้อความแจ้งเตือน');
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

    function insertVariable(variable) {
        var textarea = document.getElementById('email_body');
        if(textarea.setSelectionRange) {
            var startPos = textarea.selectionStart;
            var endPos = textarea.selectionEnd;
            var textBefore = textarea.value.substring(0, startPos);
            var textAfter = textarea.value.substring(endPos, textarea.value.length);
            textarea.value = textBefore + variable + textAfter;
            textarea.focus();
            textarea.setSelectionRange(startPos + variable.length, startPos + variable.length);
        } else {
            // For older browsers
            textarea.value += variable;
            textarea.focus();
        }
    }
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
                                        <h4>&nbsp;&nbsp;แก้ไขเทมเพลตข้อความ</h4>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr class="CENTER">
                        <td class="CENTER" align="center">
                            <table width="100%" cellpadding="10" cellspacing="0">
                                <tr>
                                    <td align="right" width="25%"><strong>ชื่อเทมเพลต:</strong></td>
                                    <td>
                                        <input type="text" id="template_name" style="width:100%;" value="<?php echo isset($result['TEMPLATE_NAME']) ? htmlspecialchars($result['TEMPLATE_NAME']) : ''; ?>">
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td align="right"><strong>ประเภท:</strong></td>
                                    <td>
                                        <select id="template_type" style="width:100%;">
                                            <option value="html" <?php echo (isset($result['TEMPLATE_TYPE']) && $result['TEMPLATE_TYPE']=='html') ? 'selected' : ''; ?>>HTML</option>
                                            <option value="text" <?php echo (isset($result['TEMPLATE_TYPE']) && $result['TEMPLATE_TYPE']=='text') ? 'selected' : ''; ?>>Text</option>
                                        </select>
                                    </td>
                                </tr> -->
                                <tr>
                                    <td align="right"><strong>หัวข้ออีเมล:</strong></td>
                                    <td>
                                        <input type="text" id="email_subject" style="width:100%;" value="<?php echo isset($result['EMAIL_SUBJECT']) ? htmlspecialchars($result['EMAIL_SUBJECT']) : ''; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top"><strong>เนื้อหา:</strong></td>
                                    <td>
                                        <textarea id="email_body" rows="15" style="width:100%;"><?php echo isset($result['EMAIL_BODY']) ? htmlspecialchars($result['EMAIL_BODY']) : ''; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center" style="padding:15px;">
                                        <button type="button" onclick="saveTemplate()" class="bg-color-green font-white">
                                            <i class="icon-save"></i> บันทึกการแก้ไข
                                        </button>
                                        <!-- <button type='button' onclick='editTemplate("<?php echo $result["GROUP_CODE"]; ?>")' class='mini bg-color-yellow' title='แก้ไข'>
                                            ย้อนกลับ
                                        </button> -->
                                        <button type="button" class="mini bg-color-red" style="padding:6px 8px; margin:1px;color:#fff;" 
                                            title="ย้อนกลับ" 
                                            onclick="javascript:ajaxPopup4('<?=$path?>views_project/notifymail_manage/notifymail_message.php','message','<?php echo $result['GROUP_CODE']; ?>','1=1','800','500','จัดการข้อความแจ้งเตือน');">
                                            ย้อนกลับ
                                        </button>
                                        <!-- <button type="button" onclick="closeUI()" class="bg-color-red font-white">
                                            <i class="icon-cancel"></i> ยกเลิก
                                        </button> -->
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
</body>
</html>