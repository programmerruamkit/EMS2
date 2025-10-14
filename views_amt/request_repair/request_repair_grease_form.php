<!-- /*
===== ขั้นตอนการทำงานของระบบบันทึกอัดจารบี =====

1. การเริ่มต้นระบบ:
    - เริ่ม session และเชื่อมต่อฐานข้อมูล
    - รับค่า SESSION_AREA จาก session

2. การเลือกบริษัท:
    - แสดง dropdown เลือกบริษัท (RKR, RKS, RKL, RCC, RRC, RATC)
    - มี checkbox เลือกแบบกลุ่ม AMT (RKR,RKS,RKL) หรือ GW (RCC,RRC,RATC)
    - ฟังก์ชัน selectcustomer() จะรีเฟรชข้อมูลตามบริษัทที่เลือก

3. การแสดงข้อมูลรถ:
    - ดึงข้อมูลรถจาก vwVEHICLEINFO (เฉพาะทะเบียนหัว, Transport)
    - แสดงข้อมูล: ทะเบียน, ชื่อรถ, กม./วัน, เลขไมล์ล่าสุด
    - ดึงข้อมูลการอัดจารบีล่าสุดจาก REPAIR_GREASE
    - คำนวณรอบการอัดจารบี (1000กม./รอบ, รอบที่5=5000กม.)

4. การคำนวณกำหนดอัดจารบี:
    - คำนวณระยะที่ใช้ไป = เลขไมล์ปัจจุบัน - เลขไมล์อัดครั้งล่าสุด
    - คำนวณระยะที่เหลือ = รอบถัดไป - ระยะที่ใช้ไป
    - คำนวณจำนวนวันที่เหลือ = ระยะที่เหลือ / กม.ต่อวัน
    - แสดงสถานะ: อีกกี่วัน / เกินกี่วัน / ควรอัดทันที

5. การเลือกและบันทึกข้อมูล:
    - ผู้ใช้เลือกรถผ่าน checkbox (เลือกได้ไม่เกิน 20 คัน)
    - คลิกแถวเพื่อเลือก/ยกเลิก checkbox
    - ฟังก์ชัน sendMultiple() เก็บข้อมูลรถที่เลือก
    - ส่งข้อมูลไป request_repair_grease_form.php เพื่อบันทึก

6. การแสดงผลแบบสี:
    - สีดำ: ปกติ
    - สีแดง: เกินกำหนดหรือควรอัดทันที

7. ฟังก์ชันหลัก:
    - selectcustomer(): เลือกบริษัทและรีเฟรชข้อมูล
    - sendMultiple(): เก็บข้อมูลรถที่เลือกและส่งไปบันทึก
    - showSendForm(): แสดงฟอร์มบันทึกข้อมูล
    - Event listeners: จัดการการคลิกแถวเพื่อเลือก checkbox
*/ -->
<?php
session_name("EMS"); 
session_start();
$path = "../";   	
require($path.'../include/connect.php');

// รับค่าจาก POST ก่อน ถ้าไม่มีค่อย fallback ไป GET
if (isset($_POST['vehicle_data'])) {
    $vehicle_data = json_decode($_POST['vehicle_data'], true);
    $ctmcomcode = isset($_POST['ctmcomcode']) ? $_POST['ctmcomcode'] : '';
} else {
    $vehicle_data = json_decode($_GET['vehicle_data'], true);
    $ctmcomcode = isset($_GET['ctmcomcode']) ? $_GET['ctmcomcode'] : '';
}
?>
<html>
<head>
    <script>
        function submitMultipleSend() {
            var vehicleData = [];
            var hasError = false;
            var errorMessage = '';
            var changeDate = document.getElementById('change_date').value;

            // ดึงค่าจาก select ทุกแถว
            var countSelects = document.getElementsByClassName('input-count-display');

            <?php foreach($vehicle_data as $index => $vehicle): ?>
                // ดึงค่าที่เลือกจาก select
                var count_display = countSelects[<?php echo $index; ?>].value;
                var count_parts = count_display.split('/');
                var count_display_1 = count_parts[0];
                var count_display_2 = count_parts[1];

                vehicleData.push({
                    id: '<?php echo $vehicle["id"]; ?>',
                    regis: '<?php echo $vehicle["regis"]; ?>',
                    name: '<?php echo $vehicle["name"]; ?>',
                    mileage: '<?php echo $vehicle["mileage"]; ?>',
                    count_display: count_display,
                    count_display_1: count_display_1,
                    count_display_2: count_display_2,
                    change_date: changeDate,
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
                url: '<?=$path?>views_amt/request_repair/request_repair_grease_proc.php',
                data: {
                    vehicle_data: vehicleData,
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

                        var ctmcomcode = result.ctmcomcode || '<?php echo $ctmcomcode; ?>';

                        Swal.fire({
                            icon: result.errors && result.errors.length > 0 ? 'warning' : 'success',
                            title: result.errors && result.errors.length > 0 ? 'บันทึกข้อมูลสำเร็จ (มีข้อผิดพลาดบางรายการ)' : 'บันทึกข้อมูลสำเร็จ',
                            text: message,
                            showConfirmButton: true,
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            closeUI();
                            var getctmcomcode = "?ctmcomcode="+ctmcomcode;
                            loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_grease.php'+getctmcomcode);
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
                <td height="35" align="right" class="ui-state-default"><strong>วันที่เปลี่ยน:</strong></td>
                <td height="35" align="left" colspan="4">
                    <input type="date" id="change_date" name="change_date" value="<?php echo date('Y-m-d'); ?>" class="input-date" required>
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
                        <th width="15%" align="center"><b>ชื่อรถ</b></th>
                        <th width="15%" align="center"><b>เลขไมล์ปัจจุบัน</b></th>
                        <th width="15%" align="center"><b>อัดจารบีครั้งที่</b></th>
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
                        <td align="center"><?php echo number_format($vehicle['mileage']); ?></td>
                        <td align="center">
                            <select name="count_display[]" class="input-count-display" style="width:90px;">
                                <?php
                                    // ดึงค่าปัจจุบัน
                                    $current_count = isset($vehicle['count_display_1']) ? intval($vehicle['count_display_1']) : 1;
                                    $current_round = isset($vehicle['count_display_2']) ? intval($vehicle['count_display_2']) : 1000;
                                    // สร้าง option 1/1000 ถึง 4/1000 และ 5/5000
                                    for ($i = 1; $i <= 5; $i++) {
                                        $round = ($i == 5) ? 5000 : 1000;
                                        $value = $i . '/' . $round;
                                        $selected = ($vehicle['count_display'] == $value) ? 'selected' : '';
                                        echo "<option value='$value' $selected>$value</option>";
                                    }
                                ?>
                            </select>
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