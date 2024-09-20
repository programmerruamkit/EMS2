<?php
	session_start ();
    $SS_ROLE=$_SESSION["AD_ROLE_NAME"];
    // $AD_POSITIONNAME = $_SESSION['AD_POSITIONNAME'];
    $word = "ช่าง";
    $mystring = "ช่างซ่อมบำรุง";
    if(strpos($SS_ROLE, $word) !== false){
        $_SESSION['NEW_POSITION']="REPAIRMAN";
    } else{
        $_SESSION['NEW_POSITION']="NOTREPAIRMAN";
    }
?>
<!-- MAIN -->
<div class="card rounded-0 bgprofile" data-card-height="150">
    <div class="card-top">
        <a href="#" class="close-menu float-end me-2 text-center mt-3 icon-40 notch-clear"><i class="fa fa-times color-white"></i></a>
    </div>
    <div class="card-bottom">
        <h1 class="color-white ps-3 mb-n1 font-20"><?=$_SESSION["AD_NAMETHAI"]?></h1>
    </div>
    <div class="card-overlay bg-gradient"></div>
</div>
<div class="mt-4"></div>
<h6 class="menu-divider">จัดการข้อมูล</h6>
<div class="list-group list-custom-small list-menu">
    <!-- <a id="nav-homepages" href="../main/">
        <i class="fa fa-home gradient-blue color-white"></i>
        <span>หน้าหลัก</span>
    </a> -->
    <a id="nav-welcome" href="../repair/request_repair_bm_form.php">
        <i class="fa fa-plus gradient-blue color-white"></i>
        <span>แจ้งซ่อม</span>
    </a>
    <a id="nav-welcome" href="../repair/">
        <i class="fa fa-history gradient-green color-white"></i>
        <span>ประวัติการแจ้งซ่อม</span>
    </a>
    <?php if($_SESSION['NEW_POSITION']=="REPAIRMAN"){ ?>
    <a id="nav-welcome" href="../assigned/">
        <i class="fa fa-wrench gradient-red color-white"></i>
        <span>ปฏิบัติงาน</span>
    </a>
    <?php } ?>
</div>
<h6 class="menu-divider mt-4">อื่น ๆ</h6>
<div class="list-group list-custom-small list-menu">
    <a href="#" data-toggle-theme="" data-trigger-switch="switch-dark-mode">
        <i class="fa fa-moon gradient-dark color-white"></i>
        <span>เปิด/ปิด โหมดมืด</span>
        <div class="custom-control small-switch ios-switch">
            <input data-toggle-theme type="checkbox" class="ios-input" id="toggle-dark-menu">
            <label class="custom-control-label" for="toggle-dark-menu"></label>
        </div>
    </a>
    <a id="nav-components" href="#" onclick="log_outsession('<?=$_SESSION['AD_PERSONCODE'];?>','LA2')">
        <i class="fa fa-sign-out gradient-red color-white"></i>
        <span>ออกจากระบบ</span>
    </a>
</div>
<h6 class="menu-divider font-10 mt-4">Made with by RIT in <span class="copyright-year">2024</span></h6>
