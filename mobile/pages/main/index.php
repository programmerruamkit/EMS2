<?php
	session_name("EMS"); session_start();
    unset($_SESSION["reload"]);
    $path = "../../";    
	require($path."include/connect.php");
	require($path."include/authen.php"); 
	require($path."include/head.php");		
	// print_r ($_SESSION);
	$SESSION_PERSONCODE = $_SESSION["AD_PERSONCODE"];
	$SESSION_NAMETHAI = $_SESSION["AD_NAMETHAI"];
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

	<style>
		.wdv1 {width: 33.33333333%;}
		.wdv2 {width: 33.33333333%;}
		.wdv3 {width: 33.33333333%;}
		.showfont { display: block; }
        @media screen and (max-width: 650px){
			.wdv1 {width: 50%;}
			.wdv2 {width: 50%;}
			.wdv3 {width: 50%;}
            .showfont { display: none; }
            .showfont-size {
				font-size: 200%;
				text-align: center;
            }
        }
	</style>
    <div class="page-content">
		<?php
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
		<div class="content mt-0">
			<div class="row mb-0">
				<div class="wdv1 pe-2">
                    <a href="../repair/request_repair_bm_form.php">
                        <div class="card card-style gradient-blue m-0" data-card-height="130">
                            <div class="card-top p-3">
                                <h1 class="showfont-size">แจ้งซ่อม</h1>
                            </div>
							<div class="card-bottom p-3">
								<h4 class="color-white font-14 opacity-70 mb-0 showfont">สร้างใบแจ้งซ่อม</h4>
								<h4 class="color-white font-14 opacity-70 mb-0 showfont">พนักงานสามารถแจ้งซ่อมได้ด้วยตัวเอง</h4>
							</div>
                        </div>
                    </a>
				</div><br>
                <div class="wdv2 ps-2">
                    <a href="../repair/">
                        <div class="card card-style gradient-green mx-0 m-0" data-card-height="130">
                            <div class="card-top p-3">
                                <h1 class="showfont-size">ประวัติการแจ้งซ่อม</h1>
                            </div>
							<div class="card-bottom p-3">
								<?php								
									$HISTORY = "SELECT 
										(SELECT COUNT(RPRQ_CODE) FROM REPAIRREQUEST WHERE RPRQ_WORKTYPE = A.RPRQ_WORKTYPE AND RPRQ_REQUESTBY = A.RPRQ_REQUESTBY AND RPRQ_STATUS = A.RPRQ_STATUS) CNT_ALL,
										(SELECT COUNT(RPRQ_CODE) FROM REPAIRREQUEST WHERE RPRQ_WORKTYPE = A.RPRQ_WORKTYPE AND RPRQ_REQUESTBY = A.RPRQ_REQUESTBY AND RPRQ_STATUS = A.RPRQ_STATUS AND RPRQ_STATUSREQUEST='รอตรวจสอบ') CNT_WAIT,
										(SELECT COUNT(RPRQ_CODE) FROM REPAIRREQUEST WHERE RPRQ_WORKTYPE = A.RPRQ_WORKTYPE AND RPRQ_REQUESTBY = A.RPRQ_REQUESTBY AND RPRQ_STATUS = A.RPRQ_STATUS AND RPRQ_STATUSREQUEST IN('รอจ่ายงาน','รอคิวซ่อม','ซ่อมเสร็จสิ้น','ไม่อนุมัติ')) CNT_PROCESS
										FROM REPAIRREQUEST A WHERE A.RPRQ_WORKTYPE = 'BM' AND A.RPRQ_REQUESTBY = '$SESSION_NAMETHAI' AND A.RPRQ_STATUS = 'Y' 
										GROUP BY A.RPRQ_WORKTYPE,A.RPRQ_REQUESTBY,A.RPRQ_STATUS";
									$QUERY_HISTORY = sqlsrv_query( $conn, $HISTORY);	
									$RESULT_HISTORY = sqlsrv_fetch_array($QUERY_HISTORY, SQLSRV_FETCH_ASSOC);
								?>
								<h4 class="color-white font-14 opacity-70 mb-0 showfont">ทั้งหมด <?=$RESULT_HISTORY['CNT_ALL'];?></h4>
								<h4 class="color-white font-14 opacity-70 mb-0 showfont">รอตรวจสอบ <?=$RESULT_HISTORY['CNT_WAIT'];?> / ดำเนินการแล้ว <?=$RESULT_HISTORY['CNT_PROCESS'];?></h4>
							</div>
                        </div>
                    </a>
                </div>
				<?php if($_SESSION['NEW_POSITION']=="REPAIRMAN"){ ?>
				<br><br><br><br><br><br>
				<div class="wdv3 ps-2">
                    <a href="../assigned/">
                        <div class="card card-style gradient-red mx-0 m-0" data-card-height="130">
                            <div class="card-top p-3">
                                <h1 class="showfont-size">ปฏิบัติงาน</h1>
                            </div>
							<div class="card-bottom p-3">
								<?php								
									$WORKING = "SELECT 
									(SELECT COUNT(DISTINCT A.RPRQ_CODE) FROM REPAIRREQUEST A
									LEFT JOIN REPAIRCAUSE B ON B.RPRQ_CODE=A.RPRQ_CODE
									LEFT JOIN REPAIRMANEMP C ON C.RPRQ_CODE=A.RPRQ_CODE AND C.RPC_SUBJECT=B.RPC_SUBJECT
									WHERE A.RPRQ_STATUSREQUEST IN ( 'รอคิวซ่อม' ) AND A.RPRQ_STATUS = 'Y' AND C.RPME_CODE = '$SESSION_PERSONCODE') CNT_WAIT,
									(SELECT COUNT(DISTINCT A.RPRQ_CODE) FROM REPAIRREQUEST A
									LEFT JOIN REPAIRCAUSE B ON B.RPRQ_CODE=A.RPRQ_CODE
									LEFT JOIN REPAIRMANEMP C ON C.RPRQ_CODE=A.RPRQ_CODE AND C.RPC_SUBJECT=B.RPC_SUBJECT
									WHERE A.RPRQ_STATUSREQUEST IN ( 'กำลังซ่อม' ) AND A.RPRQ_STATUS = 'Y' AND C.RPME_CODE = '$SESSION_PERSONCODE' ) CNT_PROCESS";
									$QUERY_WORKING = sqlsrv_query( $conn, $WORKING);	
									$RESULT_WORKING = sqlsrv_fetch_array($QUERY_WORKING, SQLSRV_FETCH_ASSOC);
								?>
								<h4 class="color-white font-14 opacity-70 mb-0 showfont">รายการซ่อมประจำวันของช่าง</h4>
								<h4 class="color-white font-14 opacity-70 mb-0 showfont">รอซ่อม <?=$RESULT_WORKING['CNT_WAIT'];?> / อยู่ระหว่างซ่อม <?=$RESULT_WORKING['CNT_PROCESS'];?></h4>
							</div>
                        </div>
                    </a>
				</div>
				<?php } ?>
			</div>
		</div>

        <!-- <div class="content mt-n2 mb-0">
            <div class="d-flex">
                <div class="align-self-center">
                    <h1 class="mb-0 font-15">To do List</h1>
                </div>
                <div class="ms-auto align-self-center">
                    <a href="#" class="float-end font-12 font-400">See All</a>
                </div>
            </div>
        </div>
        <div class="splide double-slider visible-slider slider-no-arrows slider-no-dots" id="double-slider-1" data-splide='{"interval":"50000", "perPage":2}'>
            <div class="splide__track">
                <div class="splide__list">
                    <div class="splide__slide">
						<a href="#" class="card card-style me-0 mb-0" data-card-height="160">
							<div class="card-top px-2 mx-1 pt-1">
								<div class="d-flex">
									<div><span class="color-theme opacity-40 font-600 font-11">Appkit PWA</span></div>
									<div class="ms-auto"><span class="color-theme opacity-40 font-500 font-11"><i class="fa fa-check-circle color-green-dark"></i> 3</span></div>
								</div>
							</div>
							<div class="card-center px-2 mx-1">
								<h4 class="pb-3 font-700">1Project and <br> Task Pack</h4>
							</div>
							<div class="card-bottom px-2 mx-1 pb-2">
								<div class="d-flex">
									<div>
										<span class="d-block pe-2 font-10 font-400 color-theme opacity-50">Priority</span>
										<span class="d-block pe-2 font-12 mt-n3 pt-1 color-red-light font-600">Urgent</span>
									</div>
									<div class="ms-auto text-end">
										<span class="d-block font-10 font-400 color-theme opacity-50">Due Date</span>
										<span class="d-block font-12 mt-n3 pt-1 color-theme font-600">2 Days</span>
									</div>
								</div>
							</div>
						</a>
                    </div>
					<div class="splide__slide">
						<a href="#" class="card card-style me-0 mb-0" data-card-height="160">
							<div class="card-top px-2 mx-1 pt-1">
								<div class="d-flex">
									<div><span class="color-theme opacity-40 font-600 font-11">Azures PWA</span></div>
									<div class="ms-auto"><span class="color-theme opacity-40 font-500 font-11"><i class="fa fa-check-circle color-green-dark"></i> 5</span></div>
								</div>
							</div>
							<div class="card-center px-2 mx-1">
								<h4 class="pb-3 font-700">2Pack for <br>Education</h4>
							</div>
							<div class="card-bottom px-2 mx-1 pb-2">
								<div class="d-flex">
									<div>
										<span class="d-block pe-2 font-10 font-400 color-theme opacity-50">Priority</span>
										<span class="d-block pe-2 font-12 mt-n3 pt-1 color-blue-dark font-600">Medium</span>
									</div>
									<div class="ms-auto text-end">
										<span class="d-block font-10 font-400 color-theme opacity-50">Due Date</span>
										<span class="d-block font-12 mt-n3 pt-1 color-theme font-600">4 Days</span>
									</div>
								</div>
							</div>
						</a>
                    </div>
					<div class="splide__slide">
						<a href="#" class="card card-style me-0 mb-0" data-card-height="160">
							<div class="card-top px-2 mx-1 pt-1">
								<div class="d-flex">
									<div><span class="color-theme opacity-40 font-600 font-11">Sticky PWA</span></div>
									<div class="ms-auto"><span class="color-theme opacity-40 font-500 font-11"><i class="fa fa-check-circle color-green-dark"></i> 5</span></div>
								</div>
							</div>
							<div class="card-center px-2 mx-1">
								<h4 class="pb-3 font-700">3Develop <br> Social Pack</h4>
							</div>
							<div class="card-bottom px-2 mx-1 pb-2">
								<div class="d-flex">
									<div>
										<span class="d-block pe-2 font-10 font-400 color-theme opacity-50">Priority</span>
										<span class="d-block pe-2 font-12 mt-n3 pt-1 color-green-dark font-600">Normal</span>
									</div>
									<div class="ms-auto text-end">
										<span class="d-block font-10 font-400 color-theme opacity-50">Due Date</span>
										<span class="d-block font-12 mt-n3 pt-1 color-theme font-600">4 Days</span>
									</div>
								</div>
							</div>
						</a>
                    </div>
                </div>
            </div>
        </div>
         -->
    </div>

</div>
<?php	
	require($path."include/script.php"); 
?>