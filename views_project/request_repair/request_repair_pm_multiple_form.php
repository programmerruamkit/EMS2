<?php
session_name("EMS"); 
session_start();
$path = "../";   	
require($path.'../include/connect.php');

$vehicle_data = json_decode($_GET['vehicle_data'], true);
$pjsppname = isset($_GET['pjsppname']) ? $_GET['pjsppname'] : '';
$ctmcomcode = isset($_GET['ctmcomcode']) ? $_GET['ctmcomcode'] : '';
$pjsppcodename = isset($_GET['pjsppcodename']) ? $_GET['pjsppcodename'] : '';
?>
<html>
<head>
    <script>
        function submitMultipleSend() {
            var vehicleData = [];
            var hasError = false;
            var errorMessage = '';
            
            // รวบรวมข้อมูลจากแต่ละแถว
            <?php foreach($vehicle_data as $index => $vehicle): ?>
                var changeDate<?php echo $index; ?> = document.getElementById('change_date_<?php echo $index; ?>').value;
                var notes<?php echo $index; ?> = document.getElementById('notes_<?php echo $index; ?>').value;
                
                if (!changeDate<?php echo $index; ?>) {
                    hasError = true;
                    errorMessage += 'กรุณาเลือกวันที่สำหรับรถ <?php echo $vehicle["regis"]; ?>\n';
                }
                
                vehicleData.push({
                    id: '<?php echo $vehicle["id"]; ?>',
                    regis: '<?php echo $vehicle["regis"]; ?>',
                    name: '<?php echo $vehicle["name"]; ?>',
                    change_date: changeDate<?php echo $index; ?>,
                    notes: notes<?php echo $index; ?>
                });
            <?php endforeach; ?>
            
            if (hasError) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาตรวจสอบข้อมูล',
                    text: errorMessage,
                    showConfirmButton: true,
                    confirmButtonText: 'ตกลง'
                });
                return false;
            }
            
            // แสดง loading
            Swal.fire({
                title: 'กำลังบันทึกข้อมูล...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            
            $.ajax({
                type: 'POST',
                url: '<?=$path?>views_project/request_repair/request_repair_pm_multiple_proc.php',
                data: {
                    vehicle_data: vehicleData,
                    pjsppname: '<?php echo $pjsppname; ?>',
                    pjsppcodename: '<?php echo $pjsppcodename; ?>',
                    ctmcomcode: '<?php echo $ctmcomcode; ?>',
                    proc: 'add_multiple_individual'
                },
                success: function(result) {
                    if (result.success) {
                        var message = `บันทึกข้อมูลสำหรับรถ ${result.count} คัน เรียบร้อยแล้ว`;
                        
                        if (result.errors && result.errors.length > 0) {
                            message += `\n\nแต่มีข้อผิดพลาด ${result.errors.length} รายการ:`;
                            result.errors.forEach(function(error, index) {
                                message += `\n${index + 1}. ${error}`;
                            });
                        }

                        var pjsppname = result.pjsppname || '<?php echo $pjsppname; ?>';
                        var ctmcomcode = result.ctmcomcode || '<?php echo $ctmcomcode; ?>';
                        
                        Swal.fire({
                            icon: result.errors && result.errors.length > 0 ? 'warning' : 'success',
                            title: result.errors && result.errors.length > 0 ? 'บันทึกข้อมูลสำเร็จ (มีข้อผิดพลาดบางรายการ)' : 'บันทึกข้อมูลสำเร็จ',
                            text: message,
                            showConfirmButton: true,
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            closeUI();
                            var getpjsppname = "?pjsppname="+pjsppname+"&ctmcomcode="+ctmcomcode;
                            loadViewdetail('<?=$path?>views_project/request_repair/request_repair_sparepart_form.php'+getpjsppname);
                        });
                    } else {
                        var errorMessage = result.message || 'ไม่สามารถบันทึกข้อมูลได้';
                        
                        if (result.errors && result.errors.length > 0) {
                            errorMessage += '\n\nรายละเอียดข้อผิดพลาด:';
                            result.errors.forEach(function(error, index) {
                                errorMessage += `\n${index + 1}. ${error}`;
                            });
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: errorMessage,
                            showConfirmButton: true,
                            confirmButtonText: 'ตกลง'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้\n' + error,
                        showConfirmButton: true,
                        confirmButtonText: 'ตกลง'
                    });
                }
            });
        }
    </script>
    <style>
        .form-section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .section-title {
            background-color: #4a90e2;
            color: white;
            padding: 10px;
            margin: -15px -15px 15px -15px;
            border-radius: 5px 5px 0 0;
            font-weight: bold;
        }
        .vehicle-list {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
            background-color: white;
            border-radius: 3px;
        }
        .readonly-field {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 8px;
            border-radius: 3px;
        }
        .input-date {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            vertical-align: middle;
        }
        .input-notes {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            resize: vertical;
            min-height: 60px;
        }
    </style>
</head>
<body style="padding: 20px;">

    <!-- ข้อมูลพื้นฐาน -->
    <div class="form-section">
        <div class="section-title">ข้อมูลพื้นฐาน</div>        
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="INVENDATA no-border">
            <tr align="center" height="25px">
                <td height="35" align="right" class="ui-state-default"><strong>หมวดหมู่อะไหล่:</strong></td>
                <td height="35" align="left" colspan="4">
                    <div class="readonly-field"><?php echo $pjsppname; ?></div>
                </td>
            </tr>
        </table>
    </div>

    <!-- รายการรถที่เลือก -->
    <div class="form-section">
        <div class="section-title">
            รายการรถที่เลือก (<?php echo count($vehicle_data); ?> คัน)
        </div>
        
        <div class="vehicle-list">
            <table width="100%" border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #e8f4f8;">
                        <th width="5%" align="center"><b>ลำดับ</b></th>
                        <th width="10%" align="center"><b>ทะเบียน</b></th>
                        <th width="20%" align="center"><b>ชื่อรถ</b></th>
                        <th width="25%" align="center"><b>วันที่เปลี่ยน</b></th>
                        <th width="40%" align="center"><b>หมายเหตุ</b></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach($vehicle_data as $index => $vehicle): 
                    ?>
                    <tr <?php echo ($no % 2 == 0) ? 'style="background-color: #f9f9f9;"' : ''; ?>>
                        <td align="center"><?php echo $no++; ?></td>
                        <td align="center"><strong><?php echo $vehicle['regis']; ?></strong></td>
                        <td><?php echo $vehicle['name']; ?></td>
                        <td align="center">
                            <input type="date" id="change_date_<?php echo $index; ?>" name="change_date_<?php echo $index; ?>" value="<?php echo date('Y-m-d'); ?>" class="input-date" required>
                        </td>
                        <td>
                            <textarea id="notes_<?php echo $index; ?>" name="notes_<?php echo $index; ?>" class="input-notes" placeholder="กรอกหมายเหตุ (ถ้ามี)"></textarea>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ปุ่มควบคุม -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default" style="margin-top: 20px;">
        <tbody>    
        <tr align="center" height="25px">
            <td height="50" colspan="2" align="center">
                <button class="bg-color-green font-white" type="button" name="buttonnameadd" id="buttonnameadd" value="add" onclick="submitMultipleSend()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                <button class="bg-color-red font-white" type="button" onclick="closeUI()">ปิดหน้าจอ</button>
            </td>
        </tr>
        </tbody>
    </table>  
                
</body>
</html>