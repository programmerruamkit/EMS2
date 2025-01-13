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
    function save_successjob_bm(target,groub,rprqcode,subject,sparepart) {
        // if(!confirm("ยืนยันการแก้ไข")) return false;      
        Swal.fire({
            title: '<font color="black">คุณแน่ใจหรือไม่...ที่จะปิดงานซ่อมนี้</font>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'green',
            confirmButtonText: 'ใช่! ปิดงานซ่อม',
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
                    RPC_SUBJECT: subject,
                    RPRQ_SPAREPART: sparepart
                    },
                    success:function(data){
                    // console.log(data)
                    // alert(data);                              
                    Swal.fire({
                        icon: 'success',
                        title: '<font color="black">ขณะนี้ คุณได้ปิดงานซ่อมเรียบร้อยแล้ว</font>',
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {	 
                        location.assign('../') 
                    })	
                    }
                });
            }
        })
    } 
    function save_successjob_pm(target,groub,rprqcode,subject) {
        // if(!confirm("ยืนยันการแก้ไข")) return false;      
        Swal.fire({
            title: '<font color="black">คุณแน่ใจหรือไม่...ที่จะปิดงานซ่อมนี้</font>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'green',
            confirmButtonText: 'ใช่! ปิดงานซ่อม',
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
                        var data = JSON.parse(data);
                        if(data.statusCode==200){	                             
                            Swal.fire({
                                icon: 'success',
                                title: '<font color="black">ขณะนี้ คุณได้ปิดงานซ่อมเรียบร้อยแล้ว</font>',
                                showConfirmButton: false,
                                timer: 2000
                            }).then((result) => {	 
                                location.assign('../') 
                            })	
                        }else if(data.statusCode==201){                                      
                            Swal.fire({
                            icon: 'success',
                            title: '<font color="black">ปิดงานซ่อมเรียบร้อยแล้ว</font>',
                            showConfirmButton: false,
                            timer: 2000
                            }).then((result) => {	 
                                location.assign('./?id='+rprqcode+'&proc=add') 
                            })
                        }	
                    }
                });
            }
        })
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