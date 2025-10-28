// ========================================================================================
// JavaScript สำหรับระบบส่งแบบสอบถามความพึงพอใจ
// ไฟล์: view_request_repair_form.js
// อัปเดต: October 27, 2025
// ========================================================================================

// รอให้เอกสารโหลดเสร็จก่อนเรื่มทำงาน
$(document).ready(function() {
    console.log('🚀 Survey System Initialized');
    
    // เริ่มต้น DateTimePicker
    initializeDateTimePicker();
    
    // ตรวจสอบข้อมูลเริ่มต้น
    checkInitialData();
    
    // ตั้งค่าเริ่มต้นให้ปุ่มไปยังแบบประเมิน
    initializeSurveyButton();
    
    console.log('✅ All components loaded successfully');
});

// ========================================================================================
// ฟังก์ชันเริ่มต้น
// ========================================================================================

/**
 * เริ่มต้น DateTimePicker
 */
function initializeDateTimePicker() {
    try {
        $('.datepic').datetimepicker({
            lang:'th',
            format:'d/m/Y H:i',
        });
        console.log('📅 DateTimePicker initialized');
    } catch (error) {
        console.warn('⚠️ DateTimePicker initialization failed:', error);
    }
}

/**
 * ตรวจสอบข้อมูลเริ่มต้น
 */
function checkInitialData() {
    const repairId = document.getElementById('rp').value;
    if (!repairId) {
        console.log('⚠️ No repair ID provided');
    } else {
        console.log('📝 Repair ID:', repairId);
    }
}

/**
 * ตั้งค่าเริ่มต้นให้ปุ่มไปยังแบบประเมิน
 */
function initializeSurveyButton() {
    const surveyButton = document.getElementById('survey_link_btn');
    if (surveyButton) {
        surveyButton.disabled = true;
        surveyButton.style.opacity = '0.5';
        surveyButton.style.cursor = 'not-allowed';
        console.log('🔘 Survey button initialized (disabled)');
    }
}

// ========================================================================================
// ฟังก์ชันหลัก
// ========================================================================================

/**
 * สร้าง URL สำหรับแบบสอบถาม (ตรวจสอบ environment อัตโนมัติ)
 * @param {string} surveyId - ID ของแบบสอบถาม
 * @param {string} repairId - ID ของใบขอซ่อม
 * @returns {string} URL ของแบบสอบถาม
 */
function generateSurveyUrl(surveyId, repairId) {
    const currentHost = window.location.hostname;
    let baseUrl;
    let surveyPath;
    let environment;
    
    // ตรวจสอบว่าเป็น localhost หรือ server
    if (currentHost === 'localhost' || currentHost === '127.0.0.1' || currentHost.includes('localhost')) {
        // Local environment
        baseUrl = `${window.location.protocol}//${currentHost}${window.location.port ? ':' + window.location.port : ''}`;
        surveyPath = '/ENOMBAN/E-Maintenance/survey/customer_survey.php';
        environment = 'LOCAL';
    } else {
        // Production server environment (61.91.5.111:83 หรือ domain อื่นๆ)
        baseUrl = `${window.location.protocol}//${currentHost}${window.location.port ? ':' + window.location.port : ''}`;
        surveyPath = '/survey/customer_survey.php';
        environment = 'PRODUCTION';
    }
    
    const fullUrl = `${baseUrl}${surveyPath}?sm=${surveyId}&rp=${repairId}`;
    
    // แสดง log เพื่อ debug
    console.log(`🌐 Environment Detection:`, {
        hostname: currentHost,
        environment: environment,
        baseUrl: baseUrl,
        surveyPath: surveyPath,
        fullUrl: fullUrl
    });
    
    return fullUrl;
}

/**
 * สร้าง QR Code
 */
function generateQRCode() {
    const surveySelect = document.getElementById('survey');
    const surveyId = surveySelect.value;
    const repairId = document.getElementById('rp').value;
    
    console.log('🎯 Generating QR Code for:', { surveyId, repairId });
    
    if (surveyId && repairId) {
        // สร้าง URL สำหรับแบบสอบถาม
        const surveyUrl = generateSurveyUrl(surveyId, repairId);
        
        // แสดง URL ใน input field
        document.getElementById('survey_url').value = surveyUrl;
        
        // ลบ QR Code เก่า
        const qrCodeContainer = document.getElementById('qrcode');
        qrCodeContainer.innerHTML = '';
        
        // สร้าง QR Code ใหม่
        try {
            const qr = new QRious({
                element: document.createElement('canvas'),
                value: surveyUrl,
                size: 150,
                background: 'white',
                foreground: 'black'
            });
            
            // เพิ่ม QR Code เข้าไปใน container
            qrCodeContainer.appendChild(qr.canvas);
            
            // แสดงส่วนของ QR Code และซ่อน placeholder
            document.getElementById('qrcode_display').style.display = 'block';
            document.getElementById('qrcode_placeholder').style.display = 'none';
            
            // เปิดใช้งานปุ่มไปยังแบบประเมิน
            enableSurveyButton();
            
            console.log('✅ QR Code generated successfully');
            
        } catch (error) {
            console.error('❌ Error generating QR Code:', error);
            showError('เกิดข้อผิดพลาดในการสร้าง QR Code');
        }
    } else {
        // ซ่อน QR Code และแสดง placeholder
        document.getElementById('qrcode_display').style.display = 'none';
        document.getElementById('qrcode_placeholder').style.display = 'block';
        
        // ปิดใช้งานปุ่มไปยังแบบประเมิน
        disableSurveyButton();
        
        console.log('⚠️ Missing survey or repair ID');
    }
}

/**
 * เปิดใช้งานปุ่มไปยังแบบประเมิน
 */
function enableSurveyButton() {
    const surveyButton = document.getElementById('survey_link_btn');
    if (surveyButton) {
        surveyButton.disabled = false;
        surveyButton.style.opacity = '1';
        surveyButton.style.cursor = 'pointer';
        console.log('🔘 Survey button enabled');
    }
}

/**
 * ปิดใช้งานปุ่มไปยังแบบประเมิน
 */
function disableSurveyButton() {
    const surveyButton = document.getElementById('survey_link_btn');
    if (surveyButton) {
        surveyButton.disabled = true;
        surveyButton.style.opacity = '0.5';
        surveyButton.style.cursor = 'not-allowed';
        console.log('🔘 Survey button disabled');
    }
}

// ========================================================================================
// ฟังก์ชันการทำงานกับ Clipboard
// ========================================================================================

/**
 * คัดลอก URL ไปยัง Clipboard
 */
function copyToClipboard() {
    const urlInput = document.getElementById('survey_url');
    urlInput.select();
    urlInput.setSelectionRange(0, 99999); // สำหรับ mobile devices
    
    try {
        document.execCommand('copy');
        showSuccess('คัดลอก URL สำเร็จ!', 2000);
        console.log('📋 URL copied to clipboard');
    } catch (err) {
        console.error('❌ Error copying to clipboard:', err);
        showError('เกิดข้อผิดพลาดในการคัดลอก URL');
    }
}

/**
 * ดาวน์โหลด QR Code
 */
function downloadQRCode() {
    const qrCodeCanvas = document.querySelector('#qrcode canvas');
    if (qrCodeCanvas) {
        const link = document.createElement('a');
        link.download = `survey_qr_${document.getElementById('rp').value}.png`;
        link.href = qrCodeCanvas.toDataURL('image/png');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showSuccess('ดาวน์โหลดสำเร็จ!', 'ไฟล์ QR Code ได้ถูกบันทึกแล้ว', 2000);
        console.log('💾 QR Code downloaded');
    } else {
        showError('ไม่พบ QR Code ที่จะดาวน์โหลด');
    }
}

// ========================================================================================
// ฟังก์ชันการนำทาง
// ========================================================================================

/**
 * เปิดแบบสอบถามจากปุ่ม
 */
function openSurveyFromButton() {
    const surveyId = document.getElementById('survey').value;
    const repairId = document.getElementById('rp').value;
    
    if (!surveyId) {
        showWarning('กรุณาเลือกกลุ่มแบบประเมินก่อน');
        return false;
    }
    
    if (!repairId) {
        showError('ไม่พบข้อมูลใบขอซ่อม');
        return false;
    }
    
    // สร้าง URL สำหรับแบบสอบถาม
    const surveyUrl = generateSurveyUrl(surveyId, repairId);
    
    // เปิดหน้าแบบสอบถามใน tab ใหม่
    window.open(surveyUrl, '_blank');
    console.log('🔗 Survey opened in new tab:', surveyUrl);
}

// ========================================================================================
// ฟังก์ชันการบันทึกข้อมูล
// ========================================================================================

/**
 * บันทึกข้อมูลและส่งอีเมล
 */
function save_data() {
    const surveyId = document.getElementById('survey').value;
    const repairId = document.getElementById('rp').value;
    
    if (!surveyId) {
        showWarning('กรุณาเลือกกลุ่มแบบประเมิน');
        return false;
    }
    
    if (!repairId) {
        showError('ไม่พบข้อมูลใบขอซ่อม');
        return false;
    }
    
    // สร้าง URL สำหรับแบบสอบถาม
    const surveyUrl = generateSurveyUrl(surveyId, repairId);
    
    // แสดง popup สำหรับส่งอีเมล
    showEmailPopup(surveyUrl, repairId);
    
    console.log('📧 Email popup initiated');
}

// ========================================================================================
// ฟังก์ชันการส่งอีเมล
// ========================================================================================

/**
 * แสดง popup สำหรับส่งอีเมล
 * @param {string} surveyUrl - URL ของแบบสอบถาม
 * @param {string} repairId - ID ของใบขอซ่อม
 */
function showEmailPopup(surveyUrl, repairId) {
    Swal.fire({
        title: '📧 ส่งแบบสอบถามผ่านอีเมล',
        html: `
            <div style="text-align: left; margin: 20px 0;">
                <label style="font-weight: bold; color: #333; margin-bottom: 10px; display: block;">
                    💌 ข้อความที่จะส่ง:
                </label>
                <textarea id="email_message" style="width: 100%;height: 300px;padding: 15px;border: 2px solid #e1e5e9;border-radius: 8px;font-family: 'Sarabun', Arial, sans-serif;font-size: 14px;line-height: 1.6;resize: vertical;background-color: #f8f9fa;white-space: pre-line;" placeholder="กรอกข้อความที่จะส่งให้ลูกค้า...">
                เรียน คุณลูกค้า
                    บริษัทฯ ขอขอบคุณที่ท่านไว้วางใจในการใช้บริการของเรา เพื่อเป็นการพัฒนาคุณภาพการให้บริการให้ดียิ่งขึ้น บริษัทฯ จึงขอความอนุเคราะห์จากท่าน ในการตอบแบบสอบถามความพึงพอใจของลูกค้า
                    ท่านสามารถเข้าใจแบบสอบถามได้ที่ลิงค์นี้: ${surveyUrl}
                    หรือกดที่ปุ่มด้านล่างได้เลย
                    ขอขอบคุณสำหรับความร่วมมือ
                    บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด</textarea>
                <div style="margin-top: 15px; padding: 10px; background-color: #e8f4f8; border-left: 4px solid #17a2b8; border-radius: 4px;">
                    <small style="color: #0c5460;">
                        <strong>💡 คำแนะนำ:</strong> ข้อความนี้เป็นข้อความตัวอย่าง สามารถแก้ไขได้ และจะถูกบันทึกเป็น Template สำหรับใช้ครั้งถัดไป
                    </small>
                </div>
            </div>
        `,
        width: '600px',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '📤 ส่งอีเมล',
        cancelButtonText: '❌ ยกเลิก',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const message = document.getElementById('email_message').value.trim();
            if (!message) {
                Swal.showValidationMessage('กรุณากรอกข้อความที่จะส่ง');
                return false;
            }
            return { message: message };
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            const emailData = result.value;
            
            // บันทึก template ลงใน localStorage
            localStorage.setItem('survey_email_template', emailData.message);
            console.log('💾 Email template saved');
            
            // ส่งอีเมล
            sendSurveyEmail(emailData.message, surveyUrl, repairId);
        }
    });

    // โหลด template ที่บันทึกไว้ (ถ้ามี)
    setTimeout(() => {
        const savedTemplate = localStorage.getItem('survey_email_template');
        if (savedTemplate) {
            const textarea = document.getElementById('email_message');
            if (textarea) {
                // แทนที่ URL ใน template ที่บันทึกไว้ด้วย URL ปัจจุบัน
                const updatedTemplate = savedTemplate.replace(
                    /https?:\/\/[^\s]+\/ENOMBAN\/E-Maintenance\/survey\/customer_survey\.php\?sm=\d+&rp=[^\s]*/g,
                    surveyUrl
                );
                textarea.value = updatedTemplate;
                console.log('📝 Loaded saved email template');
            }
        }
    }, 100);
}

/**
 * ส่งอีเมลแบบสอบถาม (รองรับหลายอีเมล)
 */
function sendSurveyEmail(message, surveyUrl, repairId) {
    // ดึงอีเมลจาก input field
    const customerEmailField = document.getElementById('customer_email');
    let recipientEmails = '';
    
    if (customerEmailField && customerEmailField.value.trim()) {
        // ตรวจสอบรูปแบบอีเมลก่อนส่ง
        const emailValidation = validateMultipleEmails(customerEmailField.value);
        
        if (!emailValidation.valid) {
            showError('อีเมลไม่ถูกต้อง', emailValidation.errors.join('\n'));
            return;
        }
        
        recipientEmails = emailValidation.validEmails.join(',');
        console.log('📧 Sending to emails:', emailValidation.validEmails);
    } else {
        showError('กรุณากรอกอีเมลผู้รับ');
        return;
    }

    // สร้าง QR Code เป็นไฟล์ภาพสำหรับแนบ (โค้ดเดิมคงไว้)
    const qrCodeCanvas = document.querySelector('#qrcode canvas');
    let qrCodeDataUrl = null;
    
    if (qrCodeCanvas) {
        try {
            qrCodeDataUrl = qrCodeCanvas.toDataURL('image/png');
            
            const base64Size = qrCodeDataUrl.length;
            console.log('📊 QR Code Base64 size:', base64Size, 'bytes');
            
            if (base64Size > 1024 * 1024) {
                console.warn('⚠️ QR Code too large, compressing...');
                const qrSmall = new QRious({
                    element: document.createElement('canvas'),
                    value: surveyUrl,
                    size: 150,
                    background: 'white',
                    foreground: 'black'
                });
                qrCodeDataUrl = qrSmall.canvas.toDataURL('image/png', 0.8);
            }
            
        } catch (error) {
            console.error('❌ Error processing QR Code:', error);
            qrCodeDataUrl = null;
        }
    }

    // เตรียมข้อมูลสำหรับส่งอีเมล
    const emailData = {
        message: message,
        survey_url: surveyUrl,
        repair_id: repairId,
        survey_id: document.getElementById('survey').value,
        qr_code_image: qrCodeDataUrl,
        recipient_email: recipientEmails, // ส่งหลายอีเมลคั่นด้วย comma
        subject: `แบบสอบถามความพึงพอใจ - ใบขอซ่อม ${repairId}`
    };
    
    // ตรวจสอบขนาดข้อมูลทั้งหมด
    const totalDataSize = JSON.stringify(emailData).length;
    console.log('📊 Total email data size:', totalDataSize, 'bytes');
    
    if (totalDataSize > 2 * 1024 * 1024) {
        showWarning('ข้อมูลขนาดใหญ่เกินไป!', 'จะส่งอีเมลโดยไม่แนบ QR Code');
        emailData.qr_code_image = null;
    }

    // แสดงสถานะการส่ง
    const emailCount = recipientEmails.split(',').length;
    showLoading(`กำลังส่งอีเมลไปยัง ${emailCount} ที่อยู่...`, 'กรุณารอสักครู่');

    // ส่งข้อมูลไปยัง server (โค้ดเดิมคงไว้)
    $.ajax({
        url: '../views_amt/satisfaction_survey/send_survey_email.php',
        type: 'POST',
        data: emailData,
        dataType: 'json',
        cache: false,
        processData: true,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        timeout: 30000,
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            handleEmailSuccess(response, repairId, surveyUrl);
        },
        error: function(xhr, status, error) {
            handleEmailError(xhr, status, error, emailData);
        }
    });

    console.log('📧 Email Data to Send:', emailData);
}

/**
 * จัดการผลลัพธ์เมื่อส่งอีเมลสำเร็จ
 */
function handleEmailSuccess(response, repairId, surveyUrl) {
    Swal.close();
    
    if (response.success) {
        // แสดงข้อความสำเร็จ
        Swal.fire({
            title: '✅ ส่งอีเมลสำเร็จ!',
            html: `
                <div style="text-align: left; margin: 20px 0;">
                    <p style="color: #28a745; font-weight: bold; margin-bottom: 15px;">
                        📧 ข้อความและ QR Code ได้ถูกส่งไปยังลูกค้าเรียบร้อยแล้ว
                    </p>
                    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #28a745;">
                        <p style="margin: 0; color: #555;">
                            <strong>📋 รหัสใบขอซ่อม:</strong> ${repairId}<br>
                            <strong>📧 ส่งไปยัง:</strong> ${response.recipient_email || 'ลูกค้า'}<br>
                            <strong>🔗 ลิงค์แบบสอบถาม:</strong><br>
                            <small style="word-break: break-all; color: #007bff;">${surveyUrl}</small>
                        </p>
                    </div>
                </div>
            `,
                            // <strong>📎 ไฟล์แนบ:</strong> QR Code (${response.qr_file_size || 'N/A'})<br>
            icon: 'success',
            confirmButtonColor: '#28a745',
            confirmButtonText: '✅ ตกลง',
            timer: 7000
        });
        console.log('✅ Email sent successfully');
    } else {
        showError(response.message || 'ไม่สามารถส่งอีเมลได้ กรุณาลองใหม่อีกครั้ง');
    }
}

/**
 * จัดการข้อผิดพลาดเมื่อส่งอีเมล
 */
function handleEmailError(xhr, status, error, emailData) {
    Swal.close();
    
    let errorMessage = '';
    let errorTitle = 'เกิดข้อผิดพลาดในการเชื่อมต่อ!';
    
    console.error('❌ Email sending error:', { xhr, status, error });
    
    // ตรวจสอบประเภทของ error
    if (status === 'timeout') {
        errorTitle = 'การเชื่อมต่อหมดเวลา!';
        errorMessage = 'การส่งข้อมูลใช้เวลานานเกินไป กรุณาลองใหม่อีกครั้ง';
    } else if (xhr.status === 413) {
        errorTitle = 'ข้อมูลขนาดใหญ่เกินไป!';
        errorMessage = 'ขนาดข้อมูลเกินขีดจำกัดของเซิร์ฟเวอร์ จะลองส่งโดยไม่แนบ QR Code';
        
        // ลองส่งอีกครั้งโดยไม่มี QR Code
        retryWithoutQRCode(emailData);
        return;
    } else if (xhr.status === 414) {
        errorTitle = 'URL ยาวเกินไป!';
        errorMessage = 'ข้อมูลที่ส่งมีขนาดใหญ่เกินไป กรุณาลองใหม่';
    } else if (xhr.status === 500) {
        errorTitle = 'ข้อผิดพลาดของเซิร์ฟเวอร์!';
        errorMessage = 'เกิดข้อผิดพลาดภายในเซิร์ฟเวอร์ กรุณาลองใหม่อีกครั้ง';
    } else {
        errorMessage = `
            <div style="text-align: left;">
                <p>ไม่สามารถส่งอีเมลได้ กรุณาตรวจสอบ:</p>
                <ul style="text-align: left; margin: 10px 0;">
                    <li>การเชื่อมต่ออินเทอร์เน็ต</li>
                    <li>การตั้งค่า email server</li>
                    <li>ไฟล์ send_survey_email.php</li>
                </ul>
                <p><strong>Status:</strong> ${status}</p>
                <p><strong>Error:</strong> ${error}</p>
                <p><strong>HTTP Status:</strong> ${xhr.status}</p>
            </div>
        `;
    }
    
    // แสดงข้อผิดพลาด
    Swal.fire({
        icon: 'error',
        title: errorTitle,
        html: errorMessage,
        showConfirmButton: true,
        confirmButtonText: 'ตกลง'
    });
}

/**
 * ลองส่งอีเมลอีกครั้งโดยไม่แนบ QR Code
 */
function retryWithoutQRCode(originalData) {
    const retryData = {...originalData};
    retryData.qr_code_image = null;
    
    setTimeout(() => {
        $.ajax({
            url: '../views_amt/satisfaction_survey/send_survey_email.php',
            type: 'POST',
            data: retryData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showSuccess('ส่งอีเมลสำเร็จ! (ไม่มี QR Code)', 'อีเมลได้ถูกส่งเรียบร้อยแล้ว แต่ไม่มีไฟล์ QR Code แนบ', 5000);
                    console.log('✅ Email sent successfully without QR Code');
                }
            },
            error: function() {
                showError('ยังคงเกิดข้อผิดพลาด!', 'กรุณาติดต่อผู้ดูแลระบบ');
            }
        });
    }, 1000);
}

// ========================================================================================
// ฟังก์ชัน Utilities สำหรับแสดงข้อความ
// ========================================================================================

/**
 * แสดงข้อความสำเร็จ
 * @param {string} title - หัวข้อ
 * @param {string} text - ข้อความ (optional)
 * @param {number} timer - เวลาในการปิดอัตโนมัติ (optional)
 */
function showSuccess(title, text = null, timer = null) {
    const options = {
        icon: 'success',
        title: title,
        showConfirmButton: !timer,
        confirmButtonColor: '#28a745'
    };
    
    if (text) options.text = text;
    if (timer) options.timer = timer;
    
    Swal.fire(options);
}

/**
 * แสดงข้อความแจ้งเตือน
 * @param {string} title - หัวข้อ
 * @param {string} text - ข้อความ (optional)
 */
function showWarning(title, text = null) {
    const options = {
        icon: 'warning',
        title: title,
        showConfirmButton: true,
        confirmButtonColor: '#ffc107'
    };
    
    if (text) options.text = text;
    
    Swal.fire(options);
}

/**
 * แสดงข้อความข้อผิดพลาด
 * @param {string} title - หัวข้อ
 * @param {string} text - ข้อความ (optional)
 */
function showError(title, text = null) {
    const options = {
        icon: 'error',
        title: title,
        showConfirmButton: true,
        confirmButtonColor: '#dc3545'
    };
    
    if (text) options.text = text;
    
    Swal.fire(options);
}

/**
 * แสดงสถานะการโหลด
 * @param {string} title - หัวข้อ
 * @param {string} text - ข้อความ (optional)
 */
function showLoading(title, text = null) {
    const options = {
        title: title,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    };
    
    if (text) options.text = text;
    
    Swal.fire(options);
}

// ========================================================================================
// ฟังก์ชันอื่นๆ
// ========================================================================================

/**
 * ปิดหน้าต่างปัจจุบัน
 */
function closeUI2() {
    window.close();
}
function closeUI3() {
	$.unblockUI();
	$("#dialog_popup").dialog("close"); 
}

// ========================================================================================
// Global Error Handling
// ========================================================================================

window.addEventListener('error', function(e) {
    console.error('🚨 Global JavaScript Error:', e.error);
});

// ========================================================================================
// Debug Information
// ========================================================================================

console.log(`
🔧 Survey System JavaScript Loaded
📅 Version: October 27, 2025
🌍 Environment: ${window.location.hostname}
📝 File: view_request_repair_form.js
`);

/**
 * อัพเดทอีเมลลูกค้า (รองรับหลายอีเมล)
 */
function updateCustomerEmail() {
    const customerEmail = document.getElementById('customer_email');
    const customerName = document.getElementById('customer_name');
    const customerId = document.getElementById('customer_id');
    const originalEmail = document.getElementById('original_email');
    const statusDiv = document.getElementById('email_status');

    // Validate
    if (!customerEmail.value.trim()) {
        showError('กรุณากรอกอีเมล');
        customerEmail.focus();
        return;
    }
    
    // แยกอีเมลที่คั่นด้วย comma
    const emailList = customerEmail.value.split(',').map(email => email.trim());
    const invalidEmails = [];
    
    // ตรวจสอบรูปแบบอีเมลทุกตัว
    emailList.forEach(email => {
        if (email && !isValidEmail(email)) {
            invalidEmails.push(email);
        }
    });
    
    if (invalidEmails.length > 0) {
        showError('รูปแบบอีเมลไม่ถูกต้อง', `อีเมลที่ผิด: ${invalidEmails.join(', ')}`);
        customerEmail.focus();
        return;
    }
    
    // ตรวจสอบว่าเปลี่ยนแปลงหรือไม่
    if (customerEmail.value === originalEmail.value) {
        showWarning('อีเมลไม่มีการเปลี่ยนแปลง');
        return;
    }
    
    // แสดงข้อความยืนยัน
    Swal.fire({
        title: '💾 ยืนยันการอัพเดทอีเมล',
        html: `
            <div style="text-align: left; padding: 20px;">
                <p><strong>ลูกค้า:</strong> ${customerName ? customerName.value : 'ไม่ระบุ'}</p>
                <p><strong>อีเมลเดิม:</strong> <span style="color: #dc3545;">${originalEmail.value || 'ไม่มี'}</span></p>
                <p><strong>อีเมลใหม่ (${emailList.length} อีเมล):</strong></p>
                <div style="background-color: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0;">
                    ${emailList.map(email => `<span style="color: #28a745; display: block;">✉️ ${email}</span>`).join('')}
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '✅ ยืนยันอัพเดท',
        cancelButtonText: '❌ ยกเลิก',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return performEmailUpdate(
                customerName ? customerName.value : '', 
                customerEmail.value, 
                customerId ? customerId.value : ''
            );
        }
    }).then((result) => {
        if (result.isConfirmed && result.value.success) {
            // อัพเดทสำเร็จ
            originalEmail.value = customerEmail.value; // อัพเดทค่าเดิม
            showEmailStatus(`✅ อัพเดทอีเมลสำเร็จ (${emailList.length} อีเมล)`, 'success');
            
            Swal.fire({
                title: 'สำเร็จ!',
                html: `
                    <p>อัพเดทอีเมลลูกค้าเรียบร้อยแล้ว</p>
                    <small style="color: #6c757d;">จำนวน ${emailList.length} อีเมล</small>
                `,
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
}

/**
 * ส่งคำขออัพเดทอีเมลไปยัง server
 */
function performEmailUpdate(customerName, newEmail, customerId) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../views_amt/satisfaction_survey/update_customer_email.php',
            type: 'POST',
            dataType: 'json',
            timeout: 15000,
            data: {
                customer_name: customerName,
                new_email: newEmail,
                customer_id: customerId
            },
            success: function(response) {
                console.log('📧 Email update response:', response);
                resolve(response);
            },
            error: function(xhr, status, error) {
                console.error('❌ Email update error:', { xhr, status, error });
                
                let errorMessage = 'เกิดข้อผิดพลาดในการอัพเดท';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (status === 'timeout') {
                    errorMessage = 'การเชื่อมต่อหมดเวลา กรุณาลองใหม่';
                }
                
                showEmailStatus('❌ ' + errorMessage, 'error');
                Swal.showValidationMessage(errorMessage);
                resolve({ success: false, message: errorMessage });
            }
        });
    });
}

/**
 * แสดงสถานะการอัพเดทอีเมล
 */
function showEmailStatus(message, type = 'info') {
    const statusDiv = document.getElementById('email_status');
    if (!statusDiv) return;
    
    const colors = {
        success: '#28a745',
        error: '#dc3545',
        warning: '#ffc107',
        info: '#17a2b8'
    };
    
    statusDiv.innerHTML = `<span style="color: ${colors[type] || colors.info};">${message}</span>`;
    
    // ซ่อนข้อความหลัง 5 วินาที
    setTimeout(() => {
        statusDiv.innerHTML = '';
    }, 5000);
}

/**
 * ตรวจสอบรูปแบบอีเมล (รองรับหลายอีเมล)
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email.trim());
}

/**
 * ตรวจสอบรูปแบบอีเมลหลายตัว
 */
function validateMultipleEmails(emailString) {
    if (!emailString.trim()) return { valid: false, errors: ['กรุณากรอกอีเมล'] };
    
    const emailList = emailString.split(',').map(email => email.trim()).filter(email => email);
    const invalidEmails = [];
    const validEmails = [];
    
    emailList.forEach(email => {
        if (isValidEmail(email)) {
            validEmails.push(email);
        } else {
            invalidEmails.push(email);
        }
    });
    
    return {
        valid: invalidEmails.length === 0,
        validEmails: validEmails,
        invalidEmails: invalidEmails,
        totalCount: emailList.length,
        errors: invalidEmails.length > 0 ? [`รูปแบบอีเมลไม่ถูกต้อง: ${invalidEmails.join(', ')}`] : []
    };
}