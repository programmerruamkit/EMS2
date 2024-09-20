<?php
	session_start ();
    $path = "../../";    
	require($path."include/connect.php");
	require($path."include/authen.php"); 
	require($path."include/head.php");		
	// echo"<pre>";
	// print_r ($_SESSION);
	// echo"</pre>";
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	$PERSONCODE=$_SESSION['AD_PERSONCODE'];
	$sql_profile = "SELECT A.PersonCode,A.nameT,A.RA_USERNAME,A.RA_PASSWORD,B.TaxID,
		B.nameE,B.CurrentTel,B.Email,B.PositionNameT,B.Company_Code,B.THAINAME
		FROM dbo.vwROLEACOUNT AS A
		LEFT JOIN dbo.vwEMPLOYEE AS B ON B.PersonCode = A.PersonCode WHERE A.PersonCode = ?";
	$params_profile = array($PERSONCODE);
	$query_profile = sqlsrv_query( $conn, $sql_profile, $params_profile);
	$rsp = sqlsrv_fetch_array($query_profile, SQLSRV_FETCH_ASSOC);
	$RA_PASSWORD=$rsp["RA_PASSWORD"];

?>
<div id="page">
    <?php
      if ($_GET['ISUS']!='1'){
        echo "<meta http-equiv='refresh' content='0;URL=index.php?ISUS=1'>";
      }
    ?>

    <div class="header header-auto-show header-fixed header-logo-center">
        <a href="../" class="header-title"><?=$title?></a>
        <a href="#" data-menu="menu-main" class="header-icon header-icon-1"><i class="fas fa-bars"></i></a>
        <a href="#" data-toggle-theme class="header-icon header-icon-4 show-on-theme-dark"><i class="fas fa-sun"></i></a>
        <a href="#" data-toggle-theme class="header-icon header-icon-4 show-on-theme-light"><i class="fas fa-moon"></i></a>
    </div>

    <?php require($path."include/ftb.php"); ?>

    <div class="page-title page-title-fixed">
        <h1><?=$_SESSION['AD_NAMETHAI']?></h1>
        <a href="#" class="page-title-icon shadow-xl bg-theme color-theme show-on-theme-light" data-toggle-theme><i class="fa fa-moon"></i></a>
        <a href="#" class="page-title-icon shadow-xl bg-theme color-theme show-on-theme-dark" data-toggle-theme><i class="fa fa-lightbulb color-yellow-dark"></i></a>
        <a href="#" class="page-title-icon shadow-xl bg-theme color-theme" data-menu="menu-main"><i class="fa fa-bars"></i></a>
    </div>
    <div class="page-title-clear"></div>

    <div id="menu-main" class="menu menu-box-left rounded-0" data-menu-width="280" data-menu-active="nav-welcome" data-menu-load="<?=$path?>include/mnm.php"></div>
    <div id="menu-colors" class="menu menu-box-bottom rounded-m" data-menu-load="<?=$path?>include/mnc.php" data-menu-height="480"></div>

    <div class="page-content">   
        <div class="card card-style">
            <div class="content mb-0">            
                <h4><center>ข้อมูลส่วนตัว</center></h4>                
                <div class="row mb-0">
                    <div class="col-2">&nbsp;</div>
                    <div class="col-4">
                        รหัสประจำตัว : <?=$rsp["PersonCode"];?>
                    </div>
                    <div class="col-4">
                        ชื่อ-สกุล : <?=$rsp["nameT"];?>
                    </div>
                    <div class="col-2">&nbsp;</div>
                </div>             
                <div class="row mb-0">
                    <div class="col-2">&nbsp;</div>
                    <div class="col-4">
                        เบอร์โทรศัพท์ : <?=$rsp["CurrentTel"];?>
                    </div>
                    <div class="col-4">
                        อีเมล : <?=$rsp["Email"];?>
                    </div>
                    <div class="col-2">&nbsp;</div>
                </div>             
                <div class="row mb-0">
                    <div class="col-2">&nbsp;</div>
                    <div class="col-4">
                        ตำแหน่ง : <?=$rsp["PositionNameT"];?>
                    </div>
                    <div class="col-4">
                        รหัสบริษัท : <?=$rsp["Company_Code"];?>
                    </div>
                    <div class="col-2">&nbsp;</div>
                </div>             
                <div class="row mb-0">
                    <div class="col-2">&nbsp;</div>
                    <div class="col-4">
                        ชื่อบริษัท : <?=$rsp["THAINAME"];?>
                    </div>
                </div>         
            </div>
            <div class="content mb-0">   
                <form action="#" method="post" enctype="multipart/form-data" name="form_project" id="form_project">    
                    <h4><center>ข้อมูลสำหรับเข้าสู่ระบบ</center></h4>                
                    <div class="row mb-0">
                        <div class="col-2">&nbsp;</div>
                        <div class="col-4">
                            ชื่อผู้ใช้ : <?=$rsp["RA_USERNAME"];?>
                        </div>
                        <div class="col-4">
                            รหัสผ่านเดิม : <?=$rsp["RA_PASSWORD"];?>
                        </div>
                        <div class="col-2">&nbsp;</div>
                    </div>             
                    <div class="row mb-0">
                        <div class="col-2">&nbsp;</div>
                        <div class="col-4">&nbsp;</div>
                        <div class="col-4">
                            รหัสผ่านใหม่ : <input type="text" size="20" name="newpass" id="newpass" value="<?=$RA_PASSWORD;?>" style="width: 200px;height: 40px;border-radius: 10px;">
                        </div>
                        <div class="col-2">&nbsp;</div>
                    </div>   
                    <br>      
                    <div class="row mb-0">
                        <div class="col-4">&nbsp;</div>
                        <div class="col-4">
                            <center>
                                <button type="button" class="btn btn-m rounded-s text-uppercase font-700 shadow-s bg-green-dark" name="buttonname" id="buttonname" onclick="newpassword()"><b>บันทึกข้อมูล</b></button>
                            </center>
                        </div>
                        <div class="col-4">&nbsp;</div>
                    </div>  
                    <br>
                    <input type="hidden" name="PersonCode" id="PersonCode" value="<?=$rsp["PersonCode"];?>">
                </form>      
            </div>
        </div>
    </div>
</div>
<?php	
	require($path."include/script.php"); 
?>
<script>
    // เปลี่ยนรหัสผ่าน #########################################################################################################################
    function newpassword() {
        Swal.fire({
            title: '<font color="black">ยืนยันการเปลี่ยนรหัสผ่านหรือไม่</font>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                var buttonname = $('#buttonname').val();
                var url = "../../service/repass_proc.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#form_project").serialize(),
                    success: function (data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: '<font color="black">'+data+'</font>',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            location.assign('../login') 
                        })
                        
                    }
                });
            }
        })        
    }
</script>