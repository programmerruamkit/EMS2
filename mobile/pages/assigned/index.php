<?php
	session_name("EMS"); session_start();
    $path = "../../";    
	require($path."include/connect.php");
	require($path."include/authen.php"); 
	require($path."include/head.php");		
	// print_r ($_SESSION);

?>

<div id="page"> 

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