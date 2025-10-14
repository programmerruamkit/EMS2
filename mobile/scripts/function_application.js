// เข้าสู่ระบบ/ออกจากระบบ #########################################################################################################################
    function login_session_mobile(){
        var username = $('#username').val();
        var password = $('#password').val();
        var area = $("input[type='checkbox']:checked").val();
        // alert(username)
        if(area=="GW") {
            chkarea=area;
        }else{
            chkarea="AMT";
        }
        if(chkarea == ""){
            Swal.fire({
                icon: 'warning',
                title: '<font color="black">โปรดเลือกพื้นที่</font>',
                showConfirmButton: false,
                timer: 2000,
                onAfterClose: () => {
                    setTimeout(() => $("#toggle-1").focus(), 110);
                }
            })
        } else if(username == ""){
            Swal.fire({
                icon: 'warning',
                title: '<font color="black">โปรดกรอกชื่อผู้ใช้</font>',
                showConfirmButton: false,
                timer: 2000,
                onAfterClose: () => {
                    setTimeout(() => $("#username").focus(), 110);
                }
            })
        } else if(password == ""){
            Swal.fire({
                icon: 'warning',
                title: '<font color="black">โปรดกรอกรหัสผ่าน</font>',
                showConfirmButton: false,
                timer: 2000,
                onAfterClose: () => {
                    setTimeout(() => $("#password").focus(), 110);
                }
            })
        } else if(username != "" && password != "") {
            $.ajax({
                type: 'post',
                url: '../../controllers/setting.php',
                data: {
                    keyword: "login_session_mobile", 
                    area: chkarea,
                    username: username,
                    password: password
                },
                cache: false,
                success: function(RS){
                    // alert(RS);
                    console.log(2)
                    console.log(RS)
                    if (RS == '"complete"') {                   
                        Swal.fire({ 
                            icon: 'success',
                            title: '<font color="black">เข้าสู่ระบบเรียบร้อย</font>',
                            html: '<b></b>',
                            timer: 3000,  
                            timerProgressBar: true,
                            showConfirmButton: false,
                        // didOpen: () => {
                        //     Swal.showLoading()
                        //     const b = Swal.getHtmlContainer().querySelector('b')
                        //     timerInterval = setInterval(() => {
                        //     b.textContent = Swal.getTimerLeft()}, 100)
                        // },
                        // willClose: () => {
                        //     clearInterval(timerInterval)
                        // }
                        }).then(() => {
                            location.assign('../')
                        })	
                    }else{                    
                        Swal.fire({
                            icon: 'error',
                            title: '<font color="black">ตรวจสอบการเข้าสู่ระบบให้ถูกต้อง!</font>',
                            html: '<b></b>',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            // didOpen: () => {
                            //     Swal.showLoading()
                            //     const b = Swal.getHtmlContainer().querySelector('b')
                            //     timerInterval = setInterval(() => {
                            //     b.textContent = Swal.getTimerLeft()}, 100)
                            // },willClose: () => {
                            //     clearInterval(timerInterval)
                            // }
                        })
                    }
                },
                    error: function(){
                    console.log(3)
                }
            });
        } else {
            alert('โปรดกรอกข้อมูลให้ถูกต้อง');
        }
    }
    function log_outsession(personcode, logact){
        // alert(personcode);
        // alert(logact);
        $.ajax({
            type: 'post',
            url: '../../controllers/setting.php',
            data: {
                keyword: "log_outsession", 
                personcode: personcode,
                logact: logact
            },
            cache: false,
            beforeSend: function(){
                console.log(1)
            },
            success: function(RS){
                // alert(RS);
                console.log(2)
                console.log(RS)
                if (RS == '"complete"') {
                    location.href = "../"
                }
            },
                error: function(){
                console.log(3)
            }
        });
    }
// เปิด/ปิดงาน #########################################################################################################################
    function save_openjob(target,groub,rprqcode,subject) {  
        Swal.fire({
            title: '<font color="black">คุณแน่ใจหรือไม่...ที่จะเปิดงานซ่อมนี้</font>',
            icon: 'warning',
            showCancelButton: true,
            // confirmButtonColor: '#C82333',
            confirmButtonText: 'ใช่! บันทึกเวลาเริ่มงาน',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "../../service/assigned_working_proc.php";
                $.ajax({
                    type: "POST",
                    url:url,
                    // data: $("#form_project").serialize(),
                    data: {
                        target: target, 
                        RPATTM_GROUP: groub, 
                        RPRQ_CODE: rprqcode, 
                        RPC_SUBJECT: subject
                    },
                    success:function(data){
                    console.log(data)
                    // alert(data);                              
                    Swal.fire({
                        icon: 'success',
                        title: '<font color="black">บันทึกเวลาเสร็จสิ้น เริ่มงานได้</font>',
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {	
                        location.assign('./?id='+rprqcode+'&proc=add') 
                    })	
                    }
                });
            }
        })
    }  
    // function save_pausejob(target,groub,rprqcode,subject) {
    //     Swal.fire({
    //     title: '<font color="black">คุณแน่ใจหรือไม่...ที่จะหยุดพักงานซ่อมนี้</font>',
    //     icon: 'warning',
    //     showCancelButton: true,
    //     confirmButtonColor: '#C82333',
    //     confirmButtonText: 'ใช่! บันทึกหยุดพักงาน',
    //     cancelButtonText: 'ยกเลิก'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             var url = "../../service/assigned_working_proc.php";
    //             $.ajax({
    //             type: "POST",
    //             url:url,
    //             // data: $("#form_project").serialize(),
    //             data: {
    //                 target: target, 
    //                 RPATTM_GROUP: groub, 
    //                 RPRQ_CODE: rprqcode, 
    //                 RPC_SUBJECT: subject
    //             },
    //             success:function(data){
    //                 // console.log(data)
    //                 // alert(data);                              
    //                 Swal.fire({
    //                 icon: 'success',
    //                 title: '<font color="black">ขณะนี้ หยุดพักงานชั่วคราว เรียบร้อย</font>',
    //                 showConfirmButton: false,
    //                 timer: 2000
    //                 }).then((result) => {	 
    //                     location.assign('./?id='+rprqcode+'&proc=add') 
    //                 })	
    //             }
    //             });
    //         }
    //     })
    // } 
    function save_pausejob() {
        if($('#pausejobrepair').val() == '' ){
            Swal.fire({
                icon: 'warning',
                title: '<font color="black">กรุณาระบุรายละเอียด</font>',
                showConfirmButton: false,
                timer: 1500,
                onAfterClose: () => {
                    setTimeout(() => $("#pausejobrepair").focus(), 0);
                }
            })
            return false;
        }
        var pausejobrepair = $("#pausejobrepair").val();
        var target_pj = $("#target_pj").val();
        var groub_pj = $("#groub_pj").val();
        var rprqcode_pj = $("#RPRQ_CODE_pj").val();
        var subject_pj = $("#SUBJECT_pj").val();
        var url = "../../service/assigned_working_proc.php";
        $.ajax({
            type: "POST",
            url:url,
            // data: $("#form_project").serialize(),
            data: {
                DETAILPAUSE: pausejobrepair, 
                target: target_pj, 
                RPATTM_GROUP: groub_pj, 
                RPRQ_CODE: rprqcode_pj, 
                RPC_SUBJECT: subject_pj
            },
            success:function(data){                        
                Swal.fire({
                icon: 'success',
                title: '<font color="black">ขณะนี้ หยุดพักงานชั่วคราว เรียบร้อย</font>',
                showConfirmButton: false,
                timer: 2000
                }).then((result) => {	 
                    location.assign('./?id='+rprqcode_pj+'&proc=add') 
                })	
            }
        });
    } 
    function save_continuejob(target,groub,rprqcode,subject) {
        // if(!confirm("ยืนยันการแก้ไข")) return false;      
        Swal.fire({
            title: '<font color="black">คุณแน่ใจหรือไม่...ที่จะเริ่มงานซ่อมต่อ</font>',
            icon: 'warning',
            showCancelButton: true,
            // confirmButtonColor: '#C82333',
            confirmButtonText: 'ใช่! เริ่มงานต่อ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "../../service/assigned_working_proc.php";
                $.ajax({
                    type: "POST",
                    url:url,
                    // data: $("#form_project").serialize(),
                    data: {
                        target: target, 
                        RPATTM_GROUP: groub, 
                        RPRQ_CODE: rprqcode, 
                        RPC_SUBJECT: subject
                    },
                    success:function(data){
                    // console.log(data)
                    // alert(data);                              
                    Swal.fire({
                        icon: 'success',
                        title: '<font color="black">บันทึกเวลาเสร็จสิ้น เริ่มงานต่อจากเดิมได้</font>',
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {	 
                        location.assign('./?id='+rprqcode+'&proc=add') 
                    })	
                    }
                });
            }
        })
    } 
    // function save_successjob_bm(target, groub, rprqcode, subject, sparepart) {
    //     // ขั้นตอนที่ 1: กรอกข้อความ
    //     Swal.fire({
    //         title: '<font color="black">กรอกข้อความเพื่อปิดงานซ่อม</font>',
    //         html: '<p style="color: #666; margin-bottom: 15px;">กรุณากรอกข้อความสำหรับการปิดงานซ่อม</p>',
    //         input: 'text',
    //         inputPlaceholder: 'ตัวอย่าง 2JB-25060407',
    //         inputAttributes: {
    //             maxlength: 50,
    //             style: `
    //                 text-align: center; 
    //                 font-size: 16px; 
    //                 padding: 10px; 
    //                 color: #000 !important; 
    //                 background-color: #fff !important; 
    //                 border: 2px solid #ddd !important; 
    //                 border-radius: 5px !important;
    //                 box-shadow: 0 2px 5px rgba(0,0,0,0.1) !important;
    //             `
    //         },
    //         customClass: {
    //             input: 'swal2-input-custom'
    //         },
    //         showCancelButton: true,
    //         confirmButtonText: 'ถัดไป',
    //         cancelButtonText: 'ยกเลิก',
    //         confirmButtonColor: '#3085d6',
    //         inputValidator: (value) => {
    //             if (!value || value.trim() === '') {
    //                 return 'กรุณากรอกข้อความก่อนดำเนินการต่อ!';
    //             }
    //             if (value.length < 3) {
    //                 return 'ข้อความต้องมีอย่างน้อย 3 ตัวอักษร!';
    //             }
    //         },
    //         didOpen: () => {
    //             // เพิ่ม CSS เพิ่มเติมเมื่อ modal เปิด
    //             const style = document.createElement('style');
    //             style.innerHTML = `
    //                 .swal2-input-custom::placeholder {
    //                     color: #999 !important;
    //                     opacity: 1 !important;
    //                 }
    //                 .swal2-input-custom:focus {
    //                     border-color: #3085d6 !important;
    //                     outline: none !important;
    //                     box-shadow: 0 0 10px rgba(48, 133, 214, 0.3) !important;
    //                 }
    //                 .swal2-input {
    //                     color: #000 !important;
    //                     background-color: #fff !important;
    //                     border: 2px solid #ddd !important;
    //                 }
    //             `;
    //             document.head.appendChild(style);
    //         }
    //     }).then((result) => {
    //         if (result.isConfirmed && result.value) {
    //             const inputText = result.value.trim();
                
    //             // ขั้นตอนที่ 2: ยืนยันข้อความ
    //             Swal.fire({
    //                 title: '<font color="black">ยืนยันข้อความ</font>',
    //                 html: `
    //                     <div style="text-align: center;">
    //                         <p style="color: #666; margin-bottom: 15px;">กรุณาตรวจสอบข้อความที่กรอก</p>
    //                         <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; border: 1px solid #ddd;">
    //                             <strong style="font-size: 18px; color: #2c3e50;">${inputText}</strong>
    //                         </div>
    //                         <p style="color: #e74c3c; font-size: 14px;">⚠️ ข้อความนี้จะถูกบันทึกลงในระบบและไม่สามารถแก้ไขได้</p>
    //                     </div>
    //                 `,
    //                 icon: 'question',
    //                 showCancelButton: true,
    //                 confirmButtonText: 'ยืนยันและปิดงาน',
    //                 cancelButtonText: 'แก้ไขข้อความ',
    //                 confirmButtonColor: '#28a745',
    //                 cancelButtonColor: '#6c757d'
    //             }).then((confirmResult) => {
    //                 if (confirmResult.isConfirmed) {
    //                     // ขั้นตอนที่ 3: บันทึกข้อความและปิดงาน
    //                     performCloseJob_BM_Mobile(target, groub, rprqcode, subject, sparepart, inputText);
    //                 } else if (confirmResult.dismiss === Swal.DismissReason.cancel) {
    //                     // กลับไปขั้นตอนที่ 1
    //                     save_successjob_bm(target, groub, rprqcode, subject, sparepart);
    //                 }
    //             });
    //         }
    //     });
    // }

    // function save_successjob_pm(target, groub, rprqcode, subject) {
    //     // ขั้นตอนที่ 1: กรอกข้อความ
    //     Swal.fire({
    //         title: '<font color="black">กรอกข้อความเพื่อปิดงานซ่อม</font>',
    //         html: '<p style="color: #666; margin-bottom: 15px;">กรุณากรอกข้อความสำหรับการปิดงานซ่อม</p>',
    //         input: 'text',
    //         inputPlaceholder: 'ตัวอย่าง 2JB-25060407',
    //         inputAttributes: {
    //             maxlength: 50,
    //             style: `
    //                 text-align: center; 
    //                 font-size: 16px; 
    //                 padding: 10px; 
    //                 color: #000 !important; 
    //                 background-color: #fff !important; 
    //                 border: 2px solid #ddd !important; 
    //                 border-radius: 5px !important;
    //                 box-shadow: 0 2px 5px rgba(0,0,0,0.1) !important;
    //             `
    //         },
    //         customClass: {
    //             input: 'swal2-input-custom'
    //         },
    //         showCancelButton: true,
    //         confirmButtonText: 'ถัดไป',
    //         cancelButtonText: 'ยกเลิก',
    //         confirmButtonColor: '#3085d6',
    //         inputValidator: (value) => {
    //             if (!value || value.trim() === '') {
    //                 return 'กรุณากรอกข้อความก่อนดำเนินการต่อ!';
    //             }
    //             if (value.length < 3) {
    //                 return 'ข้อความต้องมีอย่างน้อย 3 ตัวอักษร!';
    //             }
    //         },
    //         didOpen: () => {
    //             // เพิ่ม CSS เพิ่มเติมเมื่อ modal เปิด
    //             const style = document.createElement('style');
    //             style.innerHTML = `
    //                 .swal2-input-custom::placeholder {
    //                     color: #999 !important;
    //                     opacity: 1 !important;
    //                 }
    //                 .swal2-input-custom:focus {
    //                     border-color: #3085d6 !important;
    //                     outline: none !important;
    //                     box-shadow: 0 0 10px rgba(48, 133, 214, 0.3) !important;
    //                 }
    //                 .swal2-input {
    //                     color: #000 !important;
    //                     background-color: #fff !important;
    //                     border: 2px solid #ddd !important;
    //                 }
    //             `;
    //             document.head.appendChild(style);
    //         }
    //     }).then((result) => {
    //         if (result.isConfirmed && result.value) {
    //             const inputText = result.value.trim();
                
    //             // ขั้นตอนที่ 2: ยืนยันข้อความ
    //             Swal.fire({
    //                 title: '<font color="black">ยืนยันข้อความ</font>',
    //                 html: `
    //                     <div style="text-align: center;">
    //                         <p style="color: #666; margin-bottom: 15px;">กรุณาตรวจสอบข้อความที่กรอก</p>
    //                         <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; border: 1px solid #ddd;">
    //                             <strong style="font-size: 18px; color: #2c3e50;">${inputText}</strong>
    //                         </div>
    //                         <p style="color: #e74c3c; font-size: 14px;">⚠️ ข้อความนี้จะถูกบันทึกลงในระบบและไม่สามารถแก้ไขได้</p>
    //                     </div>
    //                 `,
    //                 icon: 'question',
    //                 showCancelButton: true,
    //                 confirmButtonText: 'ยืนยันและปิดงาน',
    //                 cancelButtonText: 'แก้ไขข้อความ',
    //                 confirmButtonColor: '#28a745',
    //                 cancelButtonColor: '#6c757d'
    //             }).then((confirmResult) => {
    //                 if (confirmResult.isConfirmed) {
    //                     // ขั้นตอนที่ 3: บันทึกข้อความและปิดงาน
    //                     performCloseJob_PM_Mobile(target, groub, rprqcode, subject, inputText);
    //                 } else if (confirmResult.dismiss === Swal.DismissReason.cancel) {
    //                     // กลับไปขั้นตอนที่ 1
    //                     save_successjob_pm(target, groub, rprqcode, subject);
    //                 }
    //             });
    //         }
    //     });
    // }
    
    function save_successjob_bm(area, target, groub, rprqcode, subject, sparepart) {
        var prefix = (area === 'AMT') ? '1JB-' : '2JB-';
                
        const now = new Date();
        // ปี พ.ศ. 2 หลัก
        const year = (now.getFullYear()).toString().slice(-2);
        // เดือน 2 หลัก
        const month = ('0' + (now.getMonth() + 1)).slice(-2);
        // เลขท้าย 4 ตัว (เช่น 0000)
        const last4 = '0000';

        Swal.fire({
            title: '<font color="black">กรอกเลข Job จาก HDMS ก่อนปิดงานซ่อม</font>',
            html: `
                <p style="color: #666;">
                    กรอกเฉพาะตัวเลข เช่น <strong>${year}${month}${last4}</strong><br>
                    โดย <strong>${year}</strong> คือปี, <strong>${month}</strong> คือเดือน, <strong>${last4}</strong> คือเลขลำดับ 4 ตัว
                </p>
                <div id="jobnumber_rows_bm">
                    ${renderJobRowMobile(prefix, 1, 'bm')}
                </div>
                <div style="margin:10px 0; text-align:center;">
                    <button type="button" id="addRowBtn_bm" style="margin-right:10px;"><font color="blue"><b>+เพิ่มแถว</b></font></button>
                    <button type="button" id="removeRowBtn_bm"><font color="red"><b>-ลดแถว</b></font></button>
                </div>
            `,
            //ตัวอย่าง <strong>${prefix}25060407</strong>
            showCancelButton: true,
            confirmButtonText: 'ถัดไป',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#3085d6',
            didOpen: () => {
                let rowCount = 1;
                document.getElementById('addRowBtn_bm').onclick = function() {
                    rowCount++;
                    document.getElementById('jobnumber_rows_bm').insertAdjacentHTML('beforeend', renderJobRowMobile(prefix, rowCount, 'bm'));
                };
                document.getElementById('removeRowBtn_bm').onclick = function() {
                    if(rowCount > 1) {
                        document.getElementById('job_row_bm_' + rowCount).remove();
                        rowCount--;
                    }
                };
            },
            preConfirm: () => {
                let jobs = [];
                let rows = document.querySelectorAll('[id^="job_row_bm_"]');
                for(let i=1; i<=rows.length; i++) {
                    let val = document.getElementById('jobnumber_input_bm_' + i).value.trim();
                    if (!val) {
                        Swal.showValidationMessage('กรุณากรอกเลข Job ทุกแถว!');
                        return false;
                    }
                    if (val.length < 3) {
                        Swal.showValidationMessage('เลข Job ในแถวที่ ' + i + ' ต้องมีอย่างน้อย 3 ตัวอักษร!');
                        return false;
                    }
                    if (!/^\d+$/.test(val)) {
                        Swal.showValidationMessage('กรุณากรอกเฉพาะตัวเลขในแถวที่ ' + i + '!');
                        return false;
                    }
                    jobs.push(prefix + val);
                }
                return jobs;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                let jobListHtml = result.value.map((job, idx) => `<div style="margin-bottom:5px;"><strong>${job}</strong></div>`).join('');
                Swal.fire({
                    title: '<font color="black">ยืนยันเลข Job</font>',
                    html: `
                        <div style="text-align: center;">
                            <p style="color: #666; margin-bottom: 15px;">กรุณาตรวจสอบเลข Job ที่กรอก</p>
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0;">
                                ${jobListHtml}
                            </div>
                            <p style="color: #e74c3c; font-size: 14px;">⚠️ ข้อความนี้จะถูกบันทึกลงในระบบและไม่สามารถแก้ไขได้</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยันและปิดงาน',
                    cancelButtonText: 'แก้ไขเลข Job',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d'
                }).then((confirmResult) => {
                    if (confirmResult.isConfirmed) {
                        performCloseJob_BM_Mobile(target, groub, rprqcode, subject, sparepart, result.value);
                    } else if (confirmResult.dismiss === Swal.DismissReason.cancel) {
                        save_successjob_bm(area, target, groub, rprqcode, subject, sparepart);
                    }
                });
            }
        });
    }
    
    function save_successjob_pm(area, target, groub, rprqcode, subject) {
        var prefix = (area === 'AMT') ? '1JB-' : '2JB-';
                
        const now = new Date();
        // ปี พ.ศ. 2 หลัก
        const year = (now.getFullYear()).toString().slice(-2);
        // เดือน 2 หลัก
        const month = ('0' + (now.getMonth() + 1)).slice(-2);
        // เลขท้าย 4 ตัว (เช่น 0000)
        const last4 = '0000';

        Swal.fire({
            title: '<font color="black">กรอกเลข Job จาก HDMS ก่อนปิดงานซ่อม</font>',
            html: `
                <p style="color: #666;">
                    กรอกเฉพาะตัวเลข เช่น <strong>${year}${month}${last4}</strong><br>
                    โดย <strong>${year}</strong> คือปี, <strong>${month}</strong> คือเดือน, <strong>${last4}</strong> คือเลขลำดับ 4 ตัว
                </p>
                <div id="jobnumber_rows_pm">
                    ${renderJobRowMobile(prefix, 1, 'pm')}
                </div>
                <div style="margin:10px 0; text-align:center;">
                    <button type="button" id="addRowBtn_pm" style="margin-right:10px;"><font color="blue"><b>+เพิ่มแถว</b></font></button>
                    <button type="button" id="removeRowBtn_pm"><font color="red"><b>-ลดแถว</b></font></button>
                </div>
            `,
            //ตัวอย่าง <strong>${prefix}25060407</strong>
            showCancelButton: true,
            confirmButtonText: 'ถัดไป',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#3085d6',
            didOpen: () => {
                let rowCount = 1;
                document.getElementById('addRowBtn_pm').onclick = function() {
                    rowCount++;
                    document.getElementById('jobnumber_rows_pm').insertAdjacentHTML('beforeend', renderJobRowMobile(prefix, rowCount, 'pm'));
                };
                document.getElementById('removeRowBtn_pm').onclick = function() {
                    if(rowCount > 1) {
                        document.getElementById('job_row_pm_' + rowCount).remove();
                        rowCount--;
                    }
                };
            },
            preConfirm: () => {
                let jobs = [];
                let rows = document.querySelectorAll('[id^="job_row_pm_"]');
                for(let i=1; i<=rows.length; i++) {
                    let val = document.getElementById('jobnumber_input_pm_' + i).value.trim();
                    if (!val) {
                        Swal.showValidationMessage('กรุณากรอกเลข Job ทุกแถว!');
                        return false;
                    }
                    if (val.length < 3) {
                        Swal.showValidationMessage('เลข Job ในแถวที่ ' + i + ' ต้องมีอย่างน้อย 3 ตัวอักษร!');
                        return false;
                    }
                    if (!/^\d+$/.test(val)) {
                        Swal.showValidationMessage('กรุณากรอกเฉพาะตัวเลขในแถวที่ ' + i + '!');
                        return false;
                    }
                    jobs.push(prefix + val);
                }
                return jobs;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                let jobListHtml = result.value.map((job, idx) => `<div style="margin-bottom:5px;"><strong>${job}</strong></div>`).join('');
                Swal.fire({
                    title: '<font color="black">ยืนยันเลข Job</font>',
                    html: `
                        <div style="text-align: center;">
                            <p style="color: #666; margin-bottom: 15px;">กรุณาตรวจสอบเลข Job ที่กรอก</p>
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0;">
                                ${jobListHtml}
                            </div>
                            <p style="color: #e74c3c; font-size: 14px;">⚠️ ข้อความนี้จะถูกบันทึกลงในระบบและไม่สามารถแก้ไขได้</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยันและปิดงาน',
                    cancelButtonText: 'แก้ไขเลข Job',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d'
                }).then((confirmResult) => {
                    if (confirmResult.isConfirmed) {
                        performCloseJob_PM_Mobile(target, groub, rprqcode, subject, result.value);
                    } else if (confirmResult.dismiss === Swal.DismissReason.cancel) {
                        save_successjob_pm(area, target, groub, rprqcode, subject);
                    }
                });
            }
        });
    }
    
    // สร้าง HTML สำหรับแต่ละแถว (Mobile)
    // function renderJobRowMobile(prefix, idx, type) {
    //     return `
    //         <div id="job_row_${type}_${idx}" style="display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
    //             <div style="background: #ddd; color: #222; font-size: 1em; font-weight: bold; height: 48px; padding: 10px 20px; border: 1px solid #222; border-right: none; min-width: 90px;">
    //                 ${prefix}
    //             </div>
    //             <input id="jobnumber_input_${type}_${idx}" type="text" maxlength="50"
    //                 style="height: 48px; font-size: 2em; text-align: left; padding: 10px; color: #000 !important; background-color: #fff !important; border: 2px solid #ddd !important; border-left: none; border-radius: 0 5px 5px 0 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.1) !important; width: 250px;">
    //         </div>
    //     `;
    // }
    // สร้าง HTML สำหรับแต่ละแถว (Mobile) แสดงเฉพาะตัวเลข
    function renderJobRowMobile(prefix, idx, type) {
        return `
            <div id="job_row_${type}_${idx}" style="display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                <div style="background: #ddd; color: #222; font-size: 1em; font-weight: bold; height: 48px; padding: 10px 20px; border: 1px solid #222; border-right: none; min-width: 90px;">
                    ${prefix}
                </div>
                <input
                    id="jobnumber_input_${type}_${idx}"
                    type="number"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="50"
                    style="height: 48px; font-size: 2em; text-align: left; padding: 10px; color: #000 !important; background-color: #fff !important; border: 2px solid #ddd !important; border-left: none; border-radius: 0 5px 5px 0 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.1) !important; width: 250px;"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                >
            </div>
        `;
    }
    // ฟังก์ชันสำหรับปิดงาน BM (Mobile) รับ array
    function performCloseJob_BM_Mobile(target, groub, rprqcode, subject, sparepart, jobNumbers) {
        Swal.fire({
            title: '<font color="black">กำลังบันทึกข้อมูล...</font>',
            html: 'กรุณารอสักครู่',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    
        var url = "../../service/assigned_working_proc.php";
        $.ajax({
            type: "POST",
            url: url,
            data: {
                target: target,
                RPATTM_GROUP: groub,
                RPRQ_CODE: rprqcode,
                RPC_SUBJECT: subject,
                RPRQ_SPAREPART: sparepart,
                CLOSE_MESSAGE: JSON.stringify(jobNumbers) // ส่งเป็น array
            },
            success: function(data) {
                Swal.fire({
                    icon: 'success',
                    title: '<font color="black">ปิดงานซ่อมเรียบร้อยแล้ว</font>',
                    html: `<p style="color: #666;">ข้อความที่บันทึก:</p>${jobNumbers.map(j => `<strong style="color: #2c3e50;">${j}</strong>`).join('<br>')}`,
                    showConfirmButton: false,
                    timer: 3000
                }).then((result) => {
                    location.assign('../');
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: '<font color="black">เกิดข้อผิดพลาด</font>',
                    text: 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง',
                    confirmButtonText: 'ตกลง'
                });
            }
        });
    }
    
    // ฟังก์ชันสำหรับปิดงาน PM (Mobile) รับ array
    function performCloseJob_PM_Mobile(target, groub, rprqcode, subject, jobNumbers) {
        Swal.fire({
            title: '<font color="black">กำลังบันทึกข้อมูล...</font>',
            html: 'กรุณารอสักครู่',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    
        var url = "../../service/assigned_working_proc.php";
        $.ajax({
            type: "POST",
            url: url,
            data: {
                target: target,
                RPATTM_GROUP: groub,
                RPRQ_CODE: rprqcode,
                RPC_SUBJECT: subject,
                CLOSE_MESSAGE: JSON.stringify(jobNumbers) // ส่งเป็น array
            },
            success: function(data) {
                try {
                    var responseData = JSON.parse(data);
                    Swal.fire({
                        icon: 'success',
                        title: '<font color="black">ปิดงานซ่อมเรียบร้อยแล้ว</font>',
                        html: `<p style="color: #666;">ข้อความที่บันทึก:</p>${jobNumbers.map(j => `<strong style="color: #2c3e50;">${j}</strong>`).join('<br>')}`,
                        showConfirmButton: false,
                        timer: 3000
                    }).then((result) => {
                        location.assign('../');
                    });
                } catch (e) {
                    Swal.fire({
                        icon: 'success',
                        title: '<font color="black">ปิดงานซ่อมเรียบร้อยแล้ว</font>',
                        html: `<p style="color: #666;">ข้อความที่บันทึก:</p>${jobNumbers.map(j => `<strong style="color: #2c3e50;">${j}</strong>`).join('<br>')}`,
                        showConfirmButton: false,
                        timer: 3000
                    }).then((result) => {
                        location.assign('../');
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: '<font color="black">เกิดข้อผิดพลาด</font>',
                    text: 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง',
                    confirmButtonText: 'ตกลง'
                });
            }
        });
    }
    function sendid_detailrepair(title,a1,a2,a3,a4,a5){
        document.getElementById('title_det').innerHTML = title;
        document.getElementById('title2_det').innerHTML = title;
        document.getElementById('RPRQ_CODE_det').value = a1;
        document.getElementById('SUBJECT_det').value = a2;
        document.getElementById('type_det').value = a3;
        document.getElementById('proc_det').value = a4;
        document.getElementById('target_det').value = a5;
        document.getElementById('detailrepair').value = '';    
    }
    function sendid_imagerepair(title,a1,a2,a3,a4,a5){
        document.getElementById('title').innerHTML = title;
        document.getElementById('RPRQ_CODE').value = a1;
        document.getElementById('SUBJECT').value = a2;
        document.getElementById('type').value = a3;
        document.getElementById('proc').value = a4;
        document.getElementById('target').value = a5;   
    }
    function sendid_pausejob(title,a1,a2,a3,a4){
        document.getElementById('title_pj').innerHTML = title;
        document.getElementById('target_pj').value = a1;  
        document.getElementById('groub_pj').value = a2; 
        document.getElementById('RPRQ_CODE_pj').value = a3;
        document.getElementById('SUBJECT_pj').value = a4;
    } 
    function sendid_checklist(url,proc,id){
        var url = url+"?proc="+proc;
        var id = id;    
        url+="&id="+id;	
        $.ajax({
            type: "POST",
            url: url,
            data: {id:id},
            success: function (data) {
                location.assign('checklist.php?id='+id+'&proc='+proc) 
            }
        });
    }
    function save_detailrepair() {
        if($('#detailrepair').val() == '' ){
            Swal.fire({
                icon: 'warning',
                title: '<font color="black">กรุณาระบุรายละเอียด</font>',
                showConfirmButton: false,
                timer: 1500,
                onAfterClose: () => {
                    setTimeout(() => $("#datetimeRequest_in").focus(), 0);
                }
            })
        return false;
        }
        var detailrepair = $("#detailrepair").val();
        var type = $("#type_det").val();
        var target = $("#target_det").val();
        var proc = $("#proc_det").val();
        var rprqcode = $("#RPRQ_CODE_det").val();
        var subject = $("#SUBJECT_det").val();
        var url = "../../service/assigned_working_proc.php";
        $.ajax({
            type: "POST",
            url: url,
            data: {
                detailrepair: detailrepair, 
                type: type, 
                target: target, 
                RPRQ_CODE: rprqcode, 
                SUBJECT: subject
            },
            success: function (data) {
                Swal.fire({
                position: 'center',
                icon: 'success',
                title: '<font color="black">'+data+'</font>',
                showConfirmButton: false,
                timer: 2000
                }).then((result) => {
                    location.assign('./?id='+rprqcode+'&proc=add') 
                })
            }
        });
    }
    function save_imagerepair() {
        if($('#RPATIM_GROUP').val() == '' ){
            Swal.fire({
                icon: 'warning',
                title: '<font color="black">กรุณาเลือกกลุ่มรูปภาพ</font>',
                showConfirmButton: false,
                timer: 1500,
                onAfterClose: () => {
                    setTimeout(() => $("#datetimeRequest_in").focus(), 0);
                }
            })
        return false;
        }
        var rprqcode = $("#RPRQ_CODE").val();
        var formData = new FormData($("#form_project_image")[0]);           
        var file_data = $('#fileToUpload')[0].files;
        formData.append('file',file_data[0]);
        var url = "../../service/assigned_working_proc.php";
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '<font color="black">'+data+'</font>',
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    location.assign('./?id='+rprqcode+'&proc=add') 
                })
            }
        });
    }
    function save_chklist(rprqcode,clrpcode,subject,select,input) {     
        var url = "../../service/assigned_working_proc.php";
        $.ajax({
            type: 'post',
            url: url,
            data: {
            target: "savechklist", 
            rprqcode: rprqcode,
            clrpcode: clrpcode,
            subject: subject,
            select: select,
            input: input
            },
            success:function(data){
            // alert(data)
            }
        });          
    }
// แจ้งซ่อม #########################################################################################################################