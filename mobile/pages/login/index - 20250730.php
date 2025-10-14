<?php
    $path = "../../";    
	require($path."include/connect.php");
	require($path."include/head.php");	
    session_destroy();
?>

<div id="page">
    <div id="menu-colors" class="menu menu-box-bottom rounded-m" data-menu-load="<?=$path?>include/mnc.php" data-menu-height="480"></div>
    <div class="page-content pb-10">
        <div class="card mb-0 rounded-0" data-card-height="cover-full">
            <div class="card card-center ps-3">
                <a href="#" class="page-title-icon shadow-xl bg-theme color-theme show-on-theme-light" data-toggle-theme><i class="fa fa-moon"></i></a>
                <a href="#" class="page-title-icon shadow-xl bg-theme color-theme show-on-theme-dark" data-toggle-theme><i class="fa fa-lightbulb color-yellow-dark"></i></a>
                <div class="content">               
                    <center>
                        <h1 class="font-30">เข้าสู่ระบบ</h1>     
                        <?php if($conn){ ?>
                            <b><font style="color: green;"><u>ระบบ <?=$title?> พร้อมใช้งาน</u></font></b>
                        <?php } else { ?>
                            <b><font style="color: red;"><u>ขณะนี้ไม่สามารถเชื่อมต่อระบบ <?=$title?> ได้</u></font></b>
                        <?php } ?>
                    </center>
                    <form name="form1" method="post">
                        <div class="input-style no-borders has-icon validate-field mb-4">
                            <i class="fa fa-user"></i>
                            <input type="number" class="form-control validate-name" name="username" id="username" placeholder="กรอกรหัสพนักงาน 6 ตัว" onkeypress="return isNumberKey(event)" maxlength="6" autocomplete="off" required>
                            <label for="form1a" class="color-highlight">Username</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>(required)</em>
                        </div>
                        <div class="input-style no-borders has-icon validate-field mb-4">
                            <i class="fa fa-lock"></i>
                            <input type="number" name="password" id="password" class="form-control validate-password" placeholder="กรอกรหัสผ่าน" maxlength="100" autocomplete="off" required>
                            <label for="form1ab" class="color-highlight">Password</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>(required)</em>
                        </div>
                        <center>  
                            <h1 class="font-15">เลือกพื้นที่ใช้งาน</h1>   
                            <label for="toggle-1" class="toggle-1">
                                <input type="checkbox" name="toggle-1" id="toggle-1" class="toggle-1__input" value="GW">
                                <span class="toggle-1__button"></span>
                            </label>
                            <br><br>
                            <button type="button" name="save" class="btn btn-primary btn-block btn-large" onclick="login_session_mobile()">เข้าสู่ระบบ</button> 
                        </center>
                    </form>
                </div>
                <br><br><br>
                <center> 
                    <a href="#" onclick="window.open('<?=$path?>../manual/manual_download.php?mobile=1');" class="pointer">
                        <img title="pdf" src="<?=$path?>../images/pdf-icon-150x150.png" width="45" height="45">
                        <h1 class="font-20">คู่มือผู้ใช้งาน ระบบแจ้งซ่อม</h1>   
                    </a> 
                </center>
            </div> 
        </div>
    </div>
</div>
<?php	
	require($path."include/script.php"); 
?>
<style>
	.largerCheckbox {
		width: 50px;
		height: 50px;
	}

    .toggle-1 {
        display: inline-block;
        vertical-align: top;
        margin: 0 15px 0 0;
    }
    .toggle-1__input {
        display: none;
    }
    .toggle-1__button {
        position: relative;
        cursor: pointer;
        display: inline-block;

        font-family: 'Roboto', sans-serif;
        text-transform: uppercase;
        font-size: 1rem;
        line-height: 20px;

        width: 150px;
        height: 30px;
        color: white;
        background-color: #f2395a;
        border: solid 1px #f2395a;
        box-shadow: 0 0 12px rgba(255, 61, 94, 0.5);
        border-radius: 20px;

        transition: all 0.3 ease;
    }
    .toggle-1__button::before {
        position: absolute;
        display: flex;
        align-items: center;

        top: 4px;
        left: 90px;
        height: 20px;
        padding: 0 10px;

        content: "AMT";
        background: white;
        color: #f2395a;
        transition: all 0.3s ease;
        border-radius: 20px;
        
    }
    .toggle-1__input:checked + .toggle-1__button {
        background: #8ECF45;
        border: solid 1px #8ECF45;
        box-shadow: 0 2px 20px rgba(142, 207, 69, 0.5);

    }
    .toggle-1__input:checked + .toggle-1__button::before {
        content: 'GW';
        left: 5px;
        color:#8ECF45;
    }
</style>