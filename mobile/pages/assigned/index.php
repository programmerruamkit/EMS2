<?php
	session_name("EMS"); session_start();
    $path = "../../";    
	require($path."include/connect.php");
	require($path."include/authen.php"); 
	require($path."include/head.php");		
	// print_r ($_SESSION);

?>

<!-- Update Notification Modal (modal ที่ 2) -->
<div id="updateNotifyModal" class="custom-modal-overlay" style="display:none;">
  <div class="custom-modal">
    <div class="custom-modal-header">
      <h3>📢 แจ้งอัปเดตการปิดงาน BM/PM</h3>
    </div>
    <div class="custom-modal-body" style="text-align:left;">
      <p style="font-size:1rem;">
        ตั้งแต่วันที่ <b>18</b> กันยายนนี้ ระบบการปิดงาน BM/PM จะเปลี่ยนขั้นตอนใหม่:<br>
        &emsp;1. เมื่อกดปุ่มปิดงาน ต้องกรอก เลข Job จาก HDMS<br>
        &emsp;2. กรอกหลาย Job ได้ในครั้งเดียว กดเพิ่ม/ลดแถวได้<br>
        &emsp;3. ระบบจะแสดงสรุปข้อมูลที่กรอก เพื่อให้ตรวจสอบก่อนกดบันทึกปิดงาน<br>
        &emsp;4. หลังยืนยันเลข Job และปิดงาน จะไม่สามารถแก้ไขได้<br><br>
        ** หากไม่กรอกเลข Job จะไม่สามารถปิดงานได้<br>
        ** โปรดเตรียมเลข Job ให้พร้อมก่อนปิดงาน<br>
        ** ตัวอย่างการกรอกเลข Job: <b>25040610</b><br><br>
        ขอให้ทุกท่านปรับตัวและปฏิบัติตามขั้นตอนใหม่ เพื่อความถูกต้องของข้อมูลและประสิทธิภาพในการทำงาน<br><br>
        หากมีข้อสงสัยในการใช้งาน กรุณาติดต่อ RIT<br>
        ขอบคุณสำหรับความร่วมมือครับ/ค่ะ
      </p>
      <center>
        <button id="closeUpdateNotifyBtn" class="btn btn-primary">รับทราบ</button>
      </center>
      <br>
      <br>
      <br>
      <!-- <br> -->
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
  #closeUpdateNotifyBtn {
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
  #closeUpdateNotifyBtn:hover {
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
    .custom-modal { 
      max-width: 98vw; 
      max-height: 70%;
    }
    .custom-modal-body { padding: 12px 4px 0 4px; max-height: 100%;}
    .custom-modal-header { padding: 16px 0 8px 0; }
    .custom-modal-footer { padding: 12px; }
  }
</style>

<script>
  // Show modal on page load
    // document.addEventListener('DOMContentLoaded', function() {
    //     document.body.style.overflow = 'hidden';
    //     document.getElementById('updateNotifyModal').style.display = 'flex';
    //     document.getElementById('closeUpdateNotifyBtn').onclick = function() {
    //         document.getElementById('updateNotifyModal').style.display = 'none';
    //         document.body.style.overflow = '';
    //     };
    // });
</script>

<div id="page"> 

    <?php
        // echo"<pre>";
        // print_r($_GET);
        // echo"</pre>";
        if ($_GET['ISUS']!='1'){
        echo "<meta http-equiv='refresh' content='0;URL=../assigned/?ISUS=1'>";
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
        <div class="me-3">        
            <h5>
                &nbsp;&nbsp;&nbsp;&nbsp;<img width="20" class="fluid-img rounded-m shadow-xl" src="https://img2.pic.in.th/pic/color_blue.png">&nbsp;รอคิวซ่อม
                &nbsp;&nbsp;&nbsp;&nbsp;<img width="20" class="fluid-img rounded-m shadow-xl" src="https://img2.pic.in.th/pic/color_red.png">&nbsp;กำลังซ่อม
            </h5>
        </div>
        <br>
        <?php
            $SESSION_PERSONCODE = $_SESSION["AD_PERSONCODE"];
            $SESSION_AREA = $_SESSION["AD_AREA"];
            $sql_assigned_working = "SELECT 
                DISTINCT
                REPAIRREQUEST.RPRQ_ID, 
                REPAIRREQUEST.RPRQ_CODE,
                REPAIRREQUEST.RPRQ_WORKTYPE,
                REPAIRREQUEST.RPRQ_STATUSREQUEST, 
                REPAIRREQUEST.RPRQ_REGISHEAD, 
                REPAIRREQUEST.RPRQ_CARNAMEHEAD, 
                REPAIRREQUEST.RPRQ_REGISTAIL, 
                REPAIRREQUEST.RPRQ_CARNAMETAIL, 
            -- 	REPAIRCAUSE.RPC_SUBJECT, 
                REPAIRCAUSE.RPC_DETAIL, 
                CASE 			
                    WHEN REPAIRREQUEST.RPRQ_WORKTYPE = 'PM' THEN REPAIRCAUSE.RPC_DETAIL
                    ELSE 	REPAIRCAUSE.RPC_SUBJECT
                END AS RPC_SUBJECT
            FROM REPAIRMANEMP
            LEFT JOIN REPAIRREQUEST	ON REPAIRREQUEST.RPRQ_CODE	= REPAIRMANEMP.RPRQ_CODE 
            LEFT JOIN REPAIRCAUSE	ON REPAIRCAUSE.RPRQ_CODE	= REPAIRMANEMP.RPRQ_CODE
            WHERE RPME_CODE = '$SESSION_PERSONCODE' AND RPRQ_STATUSREQUEST IN('กำลังซ่อม','รอคิวซ่อม') AND NOT RPRQ_STATUS = 'D' AND RPRQ_AREA = '$SESSION_AREA'";
            $query_assigned_working = sqlsrv_query($conn, $sql_assigned_working);
            $no=0;
            while($result_assigned_working = sqlsrv_fetch_array($query_assigned_working, SQLSRV_FETCH_ASSOC)){	
                $no++;
                if($result_assigned_working['RPRQ_STATUSREQUEST']=='รอคิวซ่อม'){
                    $stylecolor = 'bg-blue-dark';
                }else{
                    $stylecolor = 'bg-red-dark';
                }
        ?>        
        <a href="../working/?id=<?php print $result_assigned_working['RPRQ_CODE'];?>&proc=add">
            <div class="card card-style <?=$stylecolor?>">
                <div class="content">
                    <p class="mb-n1 color-white opacity-30 font-600">หมายเลขงาน <?php print $result_assigned_working['RPRQ_ID']; ?></p>
                    <h1 class="color-white">
                        <?php print $result_assigned_working['RPRQ_REGISHEAD']?> <?php if($result_assigned_working['RPRQ_CARNAMEHEAD']!='-'){echo $result_assigned_working['RPRQ_CARNAMEHEAD'];}; ?>
                        
                        <?php print $result_assigned_working['RPRQ_REGISTAIL'].' '.$result_assigned_working['RPRQ_CARNAMETAIL']; ?>
                    </h1>
                    <p class="color-white">
                        <?php
                            if($result_assigned_working['RPRQ_WORKTYPE']=='BM'){
                                switch($result_assigned_working['RPC_SUBJECT']) {
                                    case "EL":
                                        $text="ระบบไฟ";
                                    break;
                                    case "TU":
                                        $text="ยาง ช่วงล่าง";
                                    break;
                                    case "BD":
                                        $text="โครงสร้าง";
                                    break;
                                    case "EG":
                                        $text="เครื่องยนต์";
                                    break;
                                    case "AC":
                                        $text="อุปกรณ์ประจำรถ";
                                    break;
                                }
                                print $text.' / ';
                            }
                            print $result_assigned_working['RPC_DETAIL']; 
                        ?>
                    </p>
                </div>
            </div> 
        </a>    
        <?php }; ?>        

    </div>

</div>
<?php	
	require($path."include/script.php"); 
?>