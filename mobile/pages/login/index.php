<?php
    $path = "../../";    
    require($path."include/connect.php");
    require($path."include/head.php");	
    session_destroy();
?>

<!-- IT Policy Modal (modal ที่ 1) -->
<div id="customPolicyModal" class="custom-modal-overlay">
  <div class="custom-modal">
    <div class="custom-modal-header">
      <h3>IT Policy - นโยบายการใช้งานระบบ</h3>
    </div>
    <div class="custom-modal-body">
      <iframe style="width:100%;height:350px;border-radius:8px;border:2px solid #6eb6de;box-shadow:0 2px 16px #0002;" src="https://online.anyflip.com/wcwkk/auwh/index.html" seamless="seamless" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true"></iframe>
    </div>
    <div class="custom-modal-footer">
      <button id="acceptPolicyModalBtn" class="btn btn-primary">ฉันได้อ่านและยอมรับนโยบายแล้ว</button>
    </div>
  </div>
</div>

<style>
  .custom-modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    width: 100vw; height: 100vh;
    background: rgba(30,40,60,0.85);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeInModalBg 0.5s;
  }
  .custom-modal {
    background: linear-gradient(135deg, #232526 0%, #414345 100%);
    border-radius: 18px;
    box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37);
    padding: 0;
    width: 100%;
    max-width: 800px;
    height: 100%;
    max-height: 510px;
    animation: popInModal 0.5s;
    overflow: hidden;
    border: 2px solid #6eb6de;
  }
  .custom-modal-header {
    background: linear-gradient(90deg, #6eb6de 0%, #4a77d4 100%);
    padding: 24px 0 12px 0;
    text-align: center;
    color: #fff;
    border-bottom: 1px solid #6eb6de;
    box-shadow: 0 2px 8px #0001;
  }
  .custom-modal-header h2 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-shadow: 0 2px 8px #0003;
  }
  .custom-modal-body {
    padding: 24px 24px 0 24px;
    background: rgba(255,255,255,0.03);
    max-height: 100%;
    overflow-y: auto; 
  }
  .custom-modal-footer {
    padding: 24px;
    text-align: center;
    vertical-align: middle;
    background: transparent;
  }
  #acceptPolicyModalBtn {
    font-size: 20px;
    padding: 0px 40px;
    border-radius: 8px;
    background: linear-gradient(90deg, #6eb6de 0%, #4a77d4 100%);
    border: none;
    text-align: center;
    vertical-align: middle;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 2px 8px #0002;
    transition: background 0.2s, box-shadow 0.2s;
  }
  #acceptPolicyModalBtn:hover {
    background: linear-gradient(90deg, #4a77d4 0%, #6eb6de 100%);
    box-shadow: 0 4px 16px #4a77d4aa;
  }
  @keyframes fadeInModalBg {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  @keyframes popInModal {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
  }
  @media (max-width: 700px) {
    .custom-modal { max-width: 98vw; }
    .custom-modal-body { padding: 12px 4px 0 4px; max-height: 100%;}
    .custom-modal-header { padding: 16px 0 8px 0; }
    .custom-modal-footer { padding: 12px; }
  }
</style>

<script>
  // Show IT Policy Modal on page load
  document.addEventListener('DOMContentLoaded', function() {
    document.body.style.overflow = 'hidden';
    document.getElementById('customPolicyModal').style.display = 'flex';
    document.getElementById('acceptPolicyModalBtn').onclick = function() {
      document.getElementById('customPolicyModal').style.display = 'none';
      // Show update notification modal
      document.getElementById('updateNotifyModal').style.display = 'flex';
    };
    document.getElementById('closeUpdateNotifyBtn').onclick = function() {
      document.getElementById('updateNotifyModal').style.display = 'none';
      document.body.style.overflow = '';
    };
  });
</script>

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

        content: "อมตะ";
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
        content: 'เกตเวย์';
        left: 5px;
        color:#336633;
    }
</style>