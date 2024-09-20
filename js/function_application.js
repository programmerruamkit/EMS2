
function topmenu() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

function login_session(){
    var username = $('#username').val();
    var password = $('#password').val();
    var area = $("input[type='radio']:checked").val();
    if(area=="AMT") {
        chkarea=area;
    }else if(area=="GW"){
        chkarea=area;
    }else{
        chkarea="";
    }
    if(chkarea == ""){
        // alert('โปรดเลือกพื้นที่');
        Swal.fire({
            icon: 'warning',
            title: 'โปรดเลือกพื้นที่',
            showConfirmButton: false,
            timer: 2000,
            onAfterClose: () => {
                setTimeout(() => $("#area").focus(), 110);
            }
        })
        // document.getElementById("area").focus();
    } else if(username == ""){
        // alert('โปรดกรอกชื่อผู้ใช้');
        Swal.fire({
            icon: 'warning',
            title: 'โปรดกรอกชื่อผู้ใช้',
            showConfirmButton: false,
            timer: 2000,
            onAfterClose: () => {
                setTimeout(() => $("#username").focus(), 110);
            }
        })
        // document.getElementById("username").focus();
    } else if(password == ""){
        // alert('โปรดกรอกรหัสผ่าน');
        Swal.fire({
            icon: 'warning',
            title: 'โปรดกรอกรหัสผ่าน',
            showConfirmButton: false,
            timer: 2000,
            onAfterClose: () => {
                setTimeout(() => $("#password").focus(), 110);
            }
        })
        // document.getElementById("password").focus();
    } else if(username != "" && password != "") {
        $.ajax({
            type: 'post',
            url: '../controllers/setting.php',
            data: {
                keyword: "login_session", 
                area: area,
                username: username,
                password: password
            },
            cache: false,
            //  beforeSend: function(){
            //     $.blockUI();
            //     console.log(1)
            // },
            success: function(RS){
                // alert(RS);
                $.unblockUI(); 
                console.log(2)
                console.log(RS)
                if (RS == '"complete"') {
                    // toastr.options = {
                    //     "progressBar": true,
                    //     "timeOut": "1000",
                    //     "positionClass": "toast-top-right"
                    // }
                    // toastr.success("เข้าสู่ระบบเรียบร้อย")
                    // setTimeout(() => {
                    //     location.href = "../main/role.php"
                    // }, 1000)                     
                    Swal.fire({
                        icon: 'success',
                        title: 'เข้าสู่ระบบเรียบร้อย',
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
                        location.assign('../main/role.php')
                    })	
                }else{                    
                    // toastr.options = {
                    //     "positionClass": "toast-top-right"
                    // }
                    // toastr.error("ตรวจสอบการเข้าสู่ระบบให้ถูกต้อง")
                    Swal.fire({
                        icon: 'error',
                        title: 'ตรวจสอบการเข้าสู่ระบบให้ถูกต้อง!',
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
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_outsession", 
            personcode: personcode,
            logact: logact
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                location.href = "../login/"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_repass(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_repass", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function role_session(username, password, role, roleaccount){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "role_session", 
            username: username,
            password: password,
            role: role,
            roleaccount: roleaccount
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                location.href = "../manage/dashboard.php?menu_id=dashboard"
                // location.href = "../main/main_menu.php"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_menu(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_menu", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_role(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_role", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_roleaccount(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_roleaccount", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_repairhole(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_repairhole", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_repairman(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_repairman", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_outergarage(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_outergarage", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_customer(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_customer", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_customer_car(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_customer_car", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_nature(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_nature", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_nature_sub(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_nature_sub", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_mileagepm(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_mileagepm", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_sparepart(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_sparepart", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_typerepairwork(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_typerepairwork", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_checklist(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_checklist", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_cartype(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_cartype", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}

function log_transac_requestrepair(logcode,remark,refcode){
    $.ajax({
        type: 'post',
        url: '../controllers/setting.php',
        data: {
            keyword: "log_transac_requestrepair", 
            logcode: logcode,
            remark: remark,
            refcode: refcode
        },
        cache: false,
         beforeSend: function(){
            $.blockUI();
            console.log(1)
        },
        success: function(RS){
            // alert(RS);
            $.unblockUI(); 
            console.log(2)
            console.log(RS)
            if (RS == '"complete"') {
                // location.href = "../main/main_menu"
            }
        },
            error: function(){
            console.log(3)
        }
    });
}