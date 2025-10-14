<?php
session_name("EMS"); session_start(); //ประกาศใช้ session
session_destroy(); //เคลียร์ค่า session
$path='../';
// echo "\$_SERVER[\"HTTP_USER_AGENT\"] = ".$_SERVER["HTTP_USER_AGENT"]."<br>";
require($path."include/connect.php");
require($path."include/head.php");		
require($path."include/script.php"); 
// print_r ($_SESSION);
?>
<!-- <div class="bg-image"></div> -->
<div class="bodylogin animated-box in"></div>
<div class="bg-text">
    <center>
        <?php if($conn){ ?>
            <b><font style="color: Chartreuse;text-shadow: 2px 2px 2px black;font-size: 30px;width:100%;"><u>ระบบ E-Maintenance พร้อมใช้งาน</u></font></b>
        <?php } else { ?>
            <b><font style="color: red;text-shadow: 2px 2px 2px black;font-size: 30px;width:100%;"><u>ขณะนี้ไม่สามารถเชื่อมต่อระบบ E-Maintenance ได้</u></font></b>
        <?php } ?>
    </center>
    
    <!-- Container for IT Policy and Login -->
    <div class="main-container main-center">

        <!-- Login Section -->
        <div class="login" id="loginSection" >
            <br><br><br><br><br>
            <b><font style="color: white;font-size: 32px;">เข้าสู่ระบบ</font></b><br><br>
            <form name="form1" method="post">
                <input name="username" type="text" id="username" placeholder="กรอกรหัสพนักงาน 6 ตัว" onkeypress="return isNumberKey(event)" maxlength="6" required>
                <input name="password" type="password" id="password" placeholder="กรอกรหัสผ่าน" maxlength="100" required>
                <div id="paymentContainer" name="paymentContainer" class="paymentOptions">
                    <label class="container">AMT
                        <input type="radio" name="area" id="area" value="AMT">
                        <span class="checkmark"></span>
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label class="container">GW
                        <input type="radio" name="area" id="area" value="GW">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <center>
                    <button type="button" name="save" class="btn btn-primary btn-block btn-large" onclick="login_session()">เข้าสู่ระบบ</button> 
                </center>
            </form>
            <br><br><br><br>
            <center>
                <a href="../manual/" target="_blank">
                    <img title="pdf" src="<?=$path?>images/pdf-icon-150x150.png" width="60" height="60">
                    <h3><font color="white">คู่มือการใช้งานระบบแจ้งซ่อม</font></h3>
                </a>
            </center>
        </div>
    </div>
</div>

<script>
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const loginSection = document.getElementById('loginSection');
        loginSection.onclick = checkPolicyAcceptance;
    });
</script>

<style class="cp-pen-styles">
    ::placeholder {
        color: white;
    }
    @import url(https://fonts.googleapis.com/css?family=Open+Sans);
    .toast-top-center {
        top: 15%;
        margin: 0 auto;
        left: 0%;
    }
    
    /* Main container layout */
    .main-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100vw;
        height: 100vh;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        gap: 40px;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1;
        background: none;
    }
    .main-center {
        justify-content: center !important;
        align-items: center !important;
    }
    
    /* Policy section */
    .policy-section {
        flex: 1;
        max-width: 700px;
    }
    
    .pdf-container {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 15px;
    }
    
    .policy-checkbox {
        text-align: center;
        margin-top: 15px;
    }
    
    .policy-container {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        font-size: 14px;
        padding: 10px;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 5px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .policy-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        width: auto;
        margin: 0;
    }
    
    .policy-checkmark {
        height: 20px;
        width: 20px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
        margin-right: 10px;
        position: relative;
    }
    
    .policy-container:hover .policy-checkmark {
        background-color: rgba(255, 255, 255, 0.3);
    }
    
    .policy-container input:checked ~ .policy-checkmark {
        background-color: #2196F3;
    }
    
    .policy-checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 7px;
        top: 3px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        transform: rotate(45deg);
    }
    
    .policy-container input:checked ~ .policy-checkmark:after {
        display: block;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .main-container {
            flex-direction: column;
            gap: 20px;
        }
        
        .login {
            position: relative !important;
            top: auto !important;
            left: auto !important;
            margin: 0 auto !important;
            width: 100% !important;
            max-width: 300px !important;
            height: auto !important;
        }
        
        .pdf-container iframe {
            height: 300px !important;
        }
    }

    /* Original styles */
    .btn {
        display: inline-block;
        *display: inline;
        *zoom: 1;
        padding: 4px 10px 4px;
        margin-bottom: 0;
        font-size: 13px;
        line-height: 18px;
        color: #333333;
        text-align: center;
        text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
        vertical-align: middle;
        background-color: #f5f5f5;
        background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6);
        background-image: -ms-linear-gradient(top, #ffffff, #e6e6e6);
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6));
        background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6);
        background-image: -o-linear-gradient(top, #ffffff, #e6e6e6);
        background-image: linear-gradient(top, #ffffff, #e6e6e6);
        background-repeat: repeat-x;
        filter: progid:dximagetransform.microsoft.gradient(startColorstr=#ffffff, endColorstr=#e6e6e6, GradientType=0);
        border-color: #e6e6e6 #e6e6e6 #e6e6e6;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
        border: 1px solid #e6e6e6;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
        -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
        cursor: pointer;
        *margin-left: .3em;
    }

    .btn:hover,
    .btn:active,
    .btn.active,
    .btn.disabled,
    .btn[disabled] {
        background-color: #e6e6e6;
    }

    .btn-large {
        padding: 0px;
        font-size: 15px;
        width: 50px;
        height: 35px;
        text-align: center;
        line-height: center;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }

    .btn:hover {
        color: #333333;
        text-decoration: none;
        background-color: #e6e6e6;
        background-position: 0 -15px;
        -webkit-transition: background-position 0.1s linear;
        -moz-transition: background-position 0.1s linear;
        -ms-transition: background-position 0.1s linear;
        -o-transition: background-position 0.1s linear;
        transition: background-position 0.1s linear;
    }

    .btn-primary,
    .btn-primary:hover {
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        color: #ffffff;
    }

    .btn-primary.active {
        color: rgba(255, 255, 255, 0.75);
    }

    .btn-primary {
        background-color: #4a77d4;
        background-image: -moz-linear-gradient(top, #6eb6de, #4a77d4);
        background-image: -ms-linear-gradient(top, #6eb6de, #4a77d4);
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#6eb6de), to(#4a77d4));
        background-image: -webkit-linear-gradient(top, #6eb6de, #4a77d4);
        background-image: -o-linear-gradient(top, #6eb6de, #4a77d4);
        background-image: linear-gradient(top, #6eb6de, #4a77d4);
        background-repeat: repeat-x;
        filter: progid:dximagetransform.microsoft.gradient(startColorstr=#6eb6de, endColorstr=#4a77d4, GradientType=0);
        border: 1px solid #3762bc;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.4);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.5);
    }

    .btn-primary:hover,
    .btn-primary:active,
    .btn-primary.active,
    .btn-primary.disabled,
    .btn-primary[disabled] {
        filter: none;
        background-color: #4a77d4;
    }

    .btn-block {
        width: 100%;
        display: block;
    }

    .bg-image {
        background-image: url("https://img2.pic.in.th/pic/bgrole.jpeg");
        filter: blur(20px);
        -webkit-filter: blur(20px);
        height: 90%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    .bg-text {
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0, 0.4);
        color: white;
        font-weight: bold;
        border: 0px solid #f1f1f1;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%; 
        padding: 20px;
        text-align: center;
        overflow-y: auto;
    }
    .bodylogin {
        width: 100%;
        height: 100%;
        font-family: 'Open Sans', sans-serif;
        background-image: url("https://img2.pic.in.th/pic/bgrole.jpeg");
        filter: blur(20px);
        -webkit-filter: blur(40px);
        height: 100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .login {
        position: relative;
        width: 400px;
        height: auto;
        flex-shrink: 0;
        margin: 0 auto;
        padding: 50px;
        color: white;
        font-family: 'Open Sans', sans-serif;
        transition: opacity 0.5s ease;
        z-index: 2;
    }

    .login h2 {
        color: #fff;
        text-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        letter-spacing: 1px;
        text-align: center;
    }

    input {
        width: 100%;
        margin-bottom: 10px;
        background: rgba(0, 0, 0, 0.3);
        border: none;
        outline: none;
        padding: 10px;
        font-size: 13px;
        color: #fff;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 0, 0, 0.3);
        border-radius: 4px;
        box-shadow: inset 0 -5px 45px rgba(100, 100, 100, 0.2), 0 1px 1px rgba(255, 255, 255, 0.2);
        -webkit-transition: box-shadow .5s ease;
        -moz-transition: box-shadow .5s ease;
        -o-transition: box-shadow .5s ease;
        -ms-transition: box-shadow .5s ease;
        transition: box-shadow .5s ease;
    }

    input:focus {
        box-shadow: inset 0 -5px 45px rgba(100, 100, 100, 0.4), 0 1px 1px rgba(255, 255, 255, 0.2);
    }

    .container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
        border-radius: 50%;
    }

    .container:hover input~.checkmark {
        background-color: #ccc;
    }

    .container input:checked~.checkmark {
        background-color: #2196F3;
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .container input:checked~.checkmark:after {
        display: block;
    }

    .container .checkmark:after {
        top: 9px;
        left: 9px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: white;
    }

    label {
        display: block;
        color: white;
    }

    .floatBlock {
        margin: 0 1.81em 0 0;
    }

    .labelish {
        color: #7d7d7d;
        margin: 0;
    }

    .paymentOptions {
        border: none;
        display: flex;
        flex-direction: row;
        justify-content: center;
        break-before: always;
        margin: 1em 0em 1em 0;
    }

    #purchaseOrder {
        margin: 0 0 2em 0;
    }

    .animated-box {
        position: absolute;
        font-family: 'Lato';
        padding: 30px;
        width: 100%;
        height: 100%; 
        text-align: center;
        border-radius: 4px;
    }
    .animated-box:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%; 
        border-radius: 0px;
        background: linear-gradient(
        120deg,hsl(224, 85%, 66%),hsl(269, 85%, 66%),hsl(314, 85%, 66%),hsl(359, 85%, 66%),hsl(44, 85%, 66%),hsl(89, 85%, 66%),hsl(134, 85%, 66%),hsl(179, 85%, 66%)
    );
        background-size: 300% 300%;
        clip-path: polygon(0% 100%, 3px 100%, 3px 3px, calc(100% - 3px) 3px, calc(100% - 3px) calc(100% - 3px), 3px calc(100% - 3px), 3px 100%, 100% 100%, 100% 0%, 0% 0%);
    }
    .animated-box.in:after {
        animation: frame-enter 1s forwards ease-in-out reverse, gradient-animation 3s ease-in-out infinite;
    }
    @keyframes gradient-animation {
        0% {
            background-position: 15% 0%;
        }
        50% {
            background-position: 85% 100%;
        }
        100% {
            background-position: 15% 0%;
        }
    }
    @keyframes frame-enter {
        0% {
            clip-path: polygon(0% 100%, 3px 100%, 3px 3px, calc(100% - 3px) 3px, calc(100% - 3px) calc(100% - 3px), 3px calc(100% - 3px), 3px 100%, 100% 100%, 100% 0%, 0% 0%);
        }
        25% {
            clip-path: polygon(0% 100%, 3px 100%, 3px 3px, calc(100% - 3px) 3px, calc(100% - 3px) calc(100% - 3px), calc(100% - 3px) calc(100% - 3px), calc(100% - 3px) 100%, 100% 100%, 100% 0%, 0% 0%);
        }
        50% {
            clip-path: polygon(0% 100%, 3px 100%, 3px 3px, calc(100% - 3px) 3px, calc(100% - 3px) 3px, calc(100% - 3px) 3px, calc(100% - 3px) 3px, calc(100% - 3px) 3px, 100% 0%, 0% 0%);
        }
        75% {
            -webkit-clip-path: polygon(0% 100%, 3px 100%, 3px 3px, 3px 3px, 3px 3px, 3px 3px, 3px 3px, 3px 3px, 3px 0%, 0% 0%);
        }
        100% {
            -webkit-clip-path: polygon(0% 100%, 3px 100%, 3px 100%, 3px 100%, 3px 100%, 3px 100%, 3px 100%, 3px 100%, 3px 100%, 0% 100%);
        }
    }

    /* Highlight animation for policy checkbox */
    @keyframes highlight {
        0%, 100% { 
            border-color: rgba(255, 255, 255, 0.3);
            background: rgba(0, 0, 0, 0.3);
        }
        50% { 
            border-color: #ff6b6b;
            background: rgba(255, 107, 107, 0.2);
            box-shadow: 0 0 20px rgba(255, 107, 107, 0.5);
        }
    }

    /* Make login section clickable when disabled */
    .login[style*="pointer-events: none"] {
        pointer-events: auto !important;
        cursor: not-allowed;
    }

    .login[style*="pointer-events: none"] * {
        pointer-events: none;
    }
</style>
<!-- Custom IT Policy Modal -->
<div id="customPolicyModal" class="custom-modal-overlay">
  <div class="custom-modal">
    <div class="custom-modal-header">
      <h2>IT Policy - นโยบายการใช้งานระบบ</h2>
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
    .custom-modal-body { padding: 12px 4px 0 4px; }
    .custom-modal-header { padding: 16px 0 8px 0; }
    .custom-modal-footer { padding: 12px; }
  }
</style>

<script>
  // Show modal on page load
  document.addEventListener('DOMContentLoaded', function() {
    document.body.style.overflow = 'hidden';
    document.getElementById('customPolicyModal').style.display = 'flex';
    document.getElementById('acceptPolicyModalBtn').onclick = function() {
      document.getElementById('customPolicyModal').style.display = 'none';
      document.body.style.overflow = '';
    };
  });
</script>