<?php
	session_name("EMS"); session_start();
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
	$proc=$_GET["proc"];
	$rprq_code=$_GET["id"];
    $SESSION_AREA = $_SESSION["AD_AREA"];
	if($proc=="add"){
    $stmt = "SELECT * FROM vwASSIGN WHERE RPRQ_AREA = '$SESSION_AREA' AND RPRQ_CODE = ?";
		$params = array($rprq_code);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_rprq = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        $RPRQ_CODE=$result_rprq["RPRQ_CODE"];
        $RPRQ_REGISHEAD=$result_rprq["RPRQ_REGISHEAD"];
        $RPC_SUBJECT_CON=$result_rprq['RPC_SUBJECT_CON'];
	};

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
        .btn-full-width {
            display: block;
            height: 70px;
        } 
        .showfont {
            display: block;
            margin: auto;
            text-align: center;
        } 
        .showdescription {
            display: none;
        }           
        .upload-file-image {
            /* text-indent: -999px; */
            outline: none;
            width: 100%;
            height: 100%;
            color: rgba(0, 0, 0, 0) !important;
        }
        .menu-box-modal-forworking {
            top: 30%;
            left: 50%;
            width:50%;
            transform: translate(-50%, -40%);
            opacity: 0;
            pointer-events: none;
            transition: all 200ms ease;
        }
        .menu-box-modal-forworking-checklist {
            top: 50%;
            left: 50%;
            width:90%;
            height: 350px;
            transform: translate(-50%, -40%);
            opacity: 0;
            pointer-events: none;
            transition: all 200ms ease;
        }
        @media screen and (max-width: 650px){ 
            .btn-full-width {
                height: 45px;
                margin: auto;
                text-align: center;
            }  
            .menu-box-modal-forworking {
                width:100%;
            }
            .menu-box-modal-forworking-checklist {
                top: 40%;
                width:500;
                height: 500px;
            }
            .showfont {
                display: none;
            }  
            .showdescription {
                display: block;
                color: orange;
            }      
            .icenter {
                position: relative;
                top: 0px;
                right: 5px;
            }               
        }
    </style> 
    <div class="page-content">   
        <div class="card card-style">
            <div class="content mb-0">            
                <h4>ข้อมูลการแจ้งซ่อม <?=$result_rprq['RPRQ_SPAREPART']?></h4>
                <p>
                    สถานะแจ้งซ่อม :     
                                <?php switch($result_rprq['RPRQ_STATUSREQUEST']) {
                                    case "รอตรวจสอบ": $RPRQ_STATUSREQUEST="<strong><font color='red'>รอตรวจสอบ</font></strong>"; break;
                                    case "รอคิวซ่อม": $RPRQ_STATUSREQUEST="<strong><font color='red'>รอคิวซ่อม</font></strong>"; break;
                                    case "กำลังซ่อม": $RPRQ_STATUSREQUEST="<strong><font color='red'>กำลังซ่อม</font></strong>"; break;
                                    case "ซ่อมเสร็จสิ้น": $RPRQ_STATUSREQUEST="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>"; break;
                                    case "รอจ่ายงาน": $RPRQ_STATUSREQUEST="<strong><font color='blue'>รอจ่ายงาน</font></strong>"; break;
                                    case "ไม่อนุมัติ": $RPRQ_STATUSREQUEST="<strong><font color='red'>ไม่อนุมัติ</font></strong>"; break;
                                }
                                print $RPRQ_STATUSREQUEST;
                                ?><br>
                    เลขที่ใบแจ้งซ่อม : <?=$result_rprq['RPRQ_ID']; ?><br>
                    หมายเลขทะเบียน (หัว) : <?php print $result_rprq['RPRQ_REGISHEAD']?> <?php if(is_null($result_rprq['RPRQ_CARNAMEHEAD'])){echo 'ชือรถ '.$result_rprq['RPRQ_CARNAMEHEAD'];}; ?><br>
                    หมายเลขทะเบียน (หาง) : <?php print $result_rprq['RPRQ_REGISTAIL']?> <?php if(is_null($result_rprq['RPRQ_CARNAMETAIL'])){echo 'ชือรถ '.$result_rprq['RPRQ_CARNAMETAIL'];}; ?><br>
                    ลักษณะงานซ่อม/รายละเอียด : <?php	
                          if($result_rprq['RPRQ_WORKTYPE'] == 'BM'){
                            print 'งาน -'.$result_rprq['RPC_SUBJECT_CON'].' /รายละเอียด -'.$result_rprq['RPC_DETAIL'];
                          }else{
                            print 'งาน -'.$result_rprq['RPC_SUBJECT_CON'].' /รายละเอียด -'.$result_rprq['RPC_SUBJECT_MERGE'];
                          }
                        ?><br>
                    วันที่นำรถเข้าซ่อม : <?php print "<font color='gray'>".$result_rprq['RPRQ_REQUESTCARDATE']." เวลา ".$result_rprq['RPRQ_REQUESTCARTIME']." น.</font>" ?><br>
                    วันที่ต้องการ : <?php print "<font color='gray'>".$result_rprq['RPRQ_USECARDATE']." เวลา ".$result_rprq['RPRQ_USECARTIME']." น.</font>" ?>
                </p> 
                <div class="showdescription">
                    <b><u>คำอธิบายปุ่มกด</u></b><br>
                      <i class="fa fa-history"></i> = ระหว่างซ่อม 
                    | <i class="fa fa-wrench"></i> = หลังซ่อม 
                    | <i class="fa fa-search"></i> = รายการตรวจสอบ 
                    | <i class="fa fa-image"></i> = รูปภาพ 
                    | <i class="fa fa-pause"></i> = พักชั่วคราว 
                    | <i class="fa fa-forward"></i> = ซ่อมต่อ 
                    | <i class="fa fa-play"></i> = เริ่มงาน 
                    | <i class="fa fa-check"></i> = เสร็จสิ้น                    
                </div>
                <br>
                <?php
                    $SESSION_PERSONCODE = $_SESSION["AD_PERSONCODE"];

                    $sql_repaircause = "SELECT * FROM REPAIRCAUSE WHERE RPRQ_CODE = '$RPRQ_CODE'";
                    $query_repaircause = sqlsrv_query($conn, $sql_repaircause);
                    $no=0;
                    while($result_repaircause = sqlsrv_fetch_array($query_repaircause, SQLSRV_FETCH_ASSOC)){	
                        $no++;
                        $RPC_SUBJECT=$result_repaircause['RPC_SUBJECT'];
                        $SUBJECT=$result_repaircause['RPC_SUBJECT'];

                        $sql_repairtime = "SELECT * FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$RPC_SUBJECT' ORDER BY RPATTM_ID DESC";
                        $params_repairtime = array();
                        $query_repairtime = sqlsrv_query($conn, $sql_repairtime, $params_repairtime);
                        $result_repairtime = sqlsrv_fetch_array($query_repairtime, SQLSRV_FETCH_ASSOC);                         

                        $sql_repairman = "SELECT * FROM REPAIRMANEMP WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$RPC_SUBJECT' AND RPME_CODE = '$SESSION_PERSONCODE'";
                        $params_repairman = array();
                        $query_repairman = sqlsrv_query($conn, $sql_repairman, $params_repairman);
                        $result_repairman = sqlsrv_fetch_array($query_repairman, SQLSRV_FETCH_ASSOC);  

                ?>
                <h4>ปฏิบัติงานซ่อม 
                    <?php	
                        switch($result_repaircause['RPC_SUBJECT']) {
                            case "EL": $RPC_SUBJECT="ระบบไฟ"; break;
                            case "TU": $RPC_SUBJECT="ยาง ช่วงล่าง"; break;
                            case "BD": $RPC_SUBJECT="โครงสร้าง"; break;
                            case "EG": $RPC_SUBJECT="เครื่องยนต์"; break;
                            case "AC": $RPC_SUBJECT="อุปกรณ์ประจำรถ"; break;
                        }
                        print '<u>งาน'.$RPC_SUBJECT.'</u>';
                    ?>&nbsp;&nbsp;เวลา <?php echo $result_repaircause['RPC_INCARDATE'].' '.$result_repaircause['RPC_INCARTIME']; ?> - <?php echo $result_repaircause['RPC_OUTCARDATE'].' '.$result_repaircause['RPC_OUTCARTIME']; ?>
                </h4>                
                <?php 
                    if(($result_rprq['RPRQ_WORKTYPE'] == 'PM')&&($result_rprq['RPRQ_TYPECUSTOMER']=='cusin')){ 
                        // div
                        $css_during_div="col-2";
                        $css_after_div="col-2";
                        $css_checklist_div="col-2";
                        $css_image_div="col-2";
                        $css_open_div="col-2";
                        $css_close_div="col-2";         
                        // icenter
                        $css_during_icenter="icenter";
                        $css_after_icenter="icenter";
                        $css_checklist_icenter="icenter";
                        $css_image_icenter="icenter";
                        $css_open_icenter="icenter";
                        $css_close_icenter="icenter";       
                    }else{ 
                        // div
                        $css_during_div="col-2";
                        $css_after_div="col-2";
                        $css_checklist_div="col-2";
                        $css_image_div="col-2";
                        $css_open_div="col-3";
                        $css_close_div="col-3"; 
                        // icenter
                        $css_during_icenter="icenter";
                        $css_after_icenter="icenter";
                        $css_checklist_icenter="icenter";
                        $css_image_icenter="icenter";
                        $css_open_icenter="";
                        $css_close_icenter=""; 
                    } 
                ?>
                <div class="content mb-0">
                    <div class="row mb-3">
                        <!-- ระหว่างซ่อม -->
                        <div class="<?=$css_during_div?>">
                            <?php if(isset($result_repaircause['RPC_INCARDATE'])){ ?>
                                <?php if(isset($result_repairman['RPME_CODE'])){ ?> 
                                    <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-secondary" data-menu="repairdetail" onclick="sendid_detailrepair('เพิ่มข้อมูลระหว่างซ่อม','<?=$RPRQ_CODE;?>','<?=$SUBJECT;?>','DURING','edit','detail')">
                                        <i class="fa fa-history <?=$css_during_icenter;?>"></i><font size="2" class="showfont">ระหว่างซ่อม</font>           
                                    </a>
                                <?php }else{ ?> 
                                    <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                        <i class="fa fa-history <?=$css_during_icenter;?>"></i><font size="2" class="showfont">ระหว่างซ่อม</font>           
                                    </a> 
                                <?php } ?> 
                            <?php }else{ ?> 
                                <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                    <i class="fa fa-history <?=$css_during_icenter;?>"></i><font size="2" class="showfont">ระหว่างซ่อม</font>           
                                </a> 
                            <?php } ?>  
                        </div> 
                        <!-- หลังซ่อม   -->
                        <div class="<?=$css_after_div?>">
                            <?php if(isset($result_repaircause['RPC_INCARDATE'])){ ?>
                                <?php if(isset($result_repairman['RPME_CODE'])){ ?> 
                                    <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-secondary" data-menu="repairdetail" onclick="sendid_detailrepair('เพิ่มข้อมูลหลังซ่อม','<?=$RPRQ_CODE;?>','<?=$SUBJECT;?>','AFTER','edit','detail')">
                                        <i class="fa fa-wrench <?=$css_after_icenter;?>"></i><font size="2" class="showfont">หลังซ่อม</font>                              
                                    </a>
                                <?php }else{ ?> 
                                    <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                        <i class="fa fa-wrench <?=$css_after_icenter;?>"></i><font size="2" class="showfont">หลังซ่อม</font>                              
                                    </a> 
                                <?php } ?> 
                            <?php }else{ ?> 
                                <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                    <i class="fa fa-wrench <?=$css_after_icenter;?>"></i><font size="2" class="showfont">หลังซ่อม</font>                              
                                </a> 
                            <?php } ?>  
                        </div> 
                        <!-- รายการตรวจสอบ   -->
                        <?php if(($result_rprq['RPRQ_WORKTYPE'] == 'PM')&&($result_rprq['RPRQ_TYPECUSTOMER']=='cusin')){  ?>
                            <div class="<?=$css_checklist_div?>">
                                <?php if(isset($result_repaircause['RPC_INCARDATE'])){ ?>
                                    <?php if(isset($result_repairman['RPME_CODE'])){ ?> 
                                        <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 bg-secondary" onclick="sendid_checklist('checklist.php','add','<?php print $RPRQ_CODE;?>&regishead=<?php print $RPRQ_REGISHEAD;?>&pmrank=<?php print $RPC_SUBJECT_CON;?>&subject=<?php print $SUBJECT;?>');">
                                            <i class="fa fa-search <?=$css_checklist_icenter;?>"></i><font size="2" class="showfont">checklist</font>                                
                                        </a>
                                    <?php }else{ ?> 
                                        <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 bg-gray-dark" style="opacity: 0.2;">
                                            <i class="fa fa-search <?=$css_checklist_icenter;?>"></i><font size="2" class="showfont">checklist</font>                                
                                        </a> 
                                    <?php } ?> 
                                <?php }else{ ?> 
                                    <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 bg-gray-dark" style="opacity: 0.2;">
                                        <i class="fa fa-search <?=$css_checklist_icenter;?>"></i><font size="2" class="showfont">checklist</font>                                
                                    </a> 
                                <?php } ?>  
                            </div> 
                        <?php } ?>  
                        <!-- รูปภาพ -->
                        <div class="<?=$css_image_div?>">
                            <?php if(isset($result_repaircause['RPC_INCARDATE'])){ ?>
                                <?php if(isset($result_repairman['RPME_CODE'])){ ?> 
                                    <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-secondary" data-menu="repairimage" onclick="sendid_imagerepair('เพิ่มรูปภาพ','<?=$RPRQ_CODE;?>','<?=$SUBJECT;?>','IMAGE','edit','imagerepair')">
                                        <i class="fa fa-image <?=$css_image_icenter;?>"></i><font size="2" class="showfont">รูปภาพ</font>                                
                                    </a>
                                <?php }else{ ?> 
                                    <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                        <i class="fa fa-image <?=$css_image_icenter;?>"></i><font size="2" class="showfont">รูปภาพ</font>
                                    </a> 
                                <?php } ?> 
                            <?php }else{ ?> 
                                <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                    <i class="fa fa-image <?=$css_image_icenter;?>"></i><font size="2" class="showfont">รูปภาพ</font>                              
                                </a> 
                            <?php } ?>  
                        </div>  
                        <!-- เปิดงาน/พักงาน --> 
                        <div class="<?=$css_open_div?>">           
                            <center>             
                                <?php if(isset($result_repairtime['RPATTM_PROCESS'])){ ?>
                                    <?php if(($result_repairtime['RPATTM_GROUP']=='START')||($result_repairtime['RPATTM_GROUP']=='CONTINUE')){ ?>    
                                        <?php if(isset($result_repairman['RPME_CODE'])){ ?>   
                                            <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-red-dark" data-menu="repairpausejob" onclick="sendid_pausejob('เพิ่มรายละเอียด','save_pausejob','PAUSE','<?=$RPRQ_CODE;?>','<?=$SUBJECT;?>')">
                                            <!-- <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-red-dark" onclick="save_pausejob('save_pausejob','PAUSE','<?php print $RPRQ_CODE;?>','<?php print $result_repaircause['RPC_SUBJECT'];?>')"> -->
                                                <i class="fa fa-pause <?=$css_open_icenter;?>"></i><font size="2" class="showfont">พักชั่วคราว</font>                                
                                            </a>                    
                                        <?php }else{ ?>   
                                            <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                                <i class="fa fa-pause"></i><font size="2" class="showfont">พักชั่วคราว</font>                                
                                            </a>    
                                        <?php } ?>  
                                    <?php }else if($result_repairtime['RPATTM_GROUP']=='PAUSE'){ ?>  
                                        <?php if(isset($result_repairman['RPME_CODE'])){ ?>          
                                            <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-dark-dark" onclick="save_continuejob('save_continuejob','CONTINUE','<?php print $RPRQ_CODE;?>','<?php print $result_repaircause['RPC_SUBJECT'];?>')">
                                                <i class="fa fa-forward <?=$css_open_icenter;?>"></i><font size="2" class="showfont">ซ่อมต่อ</font>                                
                                            </a>         
                                        <?php }else{ ?>   
                                            <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                                <i class="fa fa-forward <?=$css_open_icenter;?>"></i><font size="2" class="showfont">ซ่อมต่อ</font>                                
                                            </a>    
                                        <?php } ?> 
                                    <?php } ?>
                                <?php }else{ ?>      
                                    <?php if(isset($result_repairman['RPME_CODE'])){ ?>       
                                        <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-blue-dark" onclick="save_openjob('save_openjob','START','<?php print $RPRQ_CODE;?>','<?php print $result_repaircause['RPC_SUBJECT'];?>')">
                                            <i class="fa fa-play <?=$css_open_icenter;?>"></i><font size="2" class="showfont">เริ่มงาน</font>                                
                                        </a> 
                                    <?php }else{ ?>   
                                        <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                            <i class="fa fa-play <?=$css_open_icenter;?>"></i><font size="2" class="showfont">เริ่มงาน</font>                                
                                        </a>    
                                    <?php } ?> 
                                <?php } ?>   
                            </center>      
                        </div>
                        <!-- ปิดงาน -->
                        <div class="<?=$css_close_div?>">
                            <center>      
                                <?php if(isset($result_repairtime['RPATTM_PROCESS'])){ ?>                                   
                                    <?php if($result_repairtime['RPATTM_GROUP']=='SUCCESS'){ ?>  
                                        <?php if(isset($result_repairman['RPME_CODE'])){ ?>   
                                            <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                                <i class="fa fa-check <?=$css_close_icenter;?>"></i><font size="2" class="showfont">เสร็จสิ้น</font>
                                            </a>
                                        <?php }else{ ?>   
                                            <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                                <i class="fa fa-check <?=$css_close_icenter;?>"></i><font size="2" class="showfont">เสร็จสิ้น</font>                                
                                            </a>    
                                        <?php } ?> 
                                    <?php }else{ ?>
                                        <?php if(isset($result_repairman['RPME_CODE'])){ ?>   
                                            <?php if($result_rprq['RPRQ_WORKTYPE'] == 'BM'){ ?>
                                                <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-green-dark" onclick="save_successjob_bm('<?=$SESSION_AREA;?>','save_successjob_bm','SUCCESS','<?php print $RPRQ_CODE;?>','<?php print $result_repaircause['RPC_SUBJECT'];?>','<?=$result_rprq['RPRQ_SPAREPART']?>')">
                                                    <i class="fa fa-check <?=$css_close_icenter;?>"></i><font size="2" class="showfont">เสร็จสิ้น</font>
                                                </a>
                                            <?php }else{ ?>                               
                                                <?php switch($result_rprq['RPRQ_TYPECUSTOMER']) {
                                                        case "cusout":
                                                        $save_type="save_successjob_pm_out";
                                                        break;
                                                        case "cusin":
                                                        $save_type="save_successjob_pm";
                                                        break;
                                                    }
                                                    // print $save_type;
                                                ?>
                                                <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-green-dark" onclick="save_successjob_pm('<?=$SESSION_AREA;?>','<?=$save_type?>','SUCCESS','<?php print $RPRQ_CODE;?>','<?php print $result_repaircause['RPC_SUBJECT'];?>')">
                                                    <i class="fa fa-check <?=$css_close_icenter;?>"></i><font size="2" class="showfont">เสร็จสิ้น</font>
                                                </a>
                                            <?php } ?>
                                        <?php }else{ ?>   
                                            <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                                <i class="fa fa-check <?=$css_close_icenter;?>"></i><font size="2" class="showfont">เสร็จสิ้น</font>                                
                                            </a>    
                                        <?php } ?> 
                                    <?php } ?>
                                <?php }else{ ?>
                                    <?php if(isset($result_repairman['RPME_CODE'])){ ?>   
                                        <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                            <i class="fa fa-check <?=$css_close_icenter;?>"></i><font size="2" class="showfont">เสร็จสิ้น</font>
                                        </a>
                                    <?php }else{ ?>   
                                        <a href="#" class="btn btn-m btn-full-width shadow-s rounded-s font-700 text-uppercase bg-gray-dark" style="opacity: 0.2;">
                                            <i class="fa fa-check <?=$css_close_icenter;?>"></i><font size="2" class="showfont">เสร็จสิ้น</font>                                
                                        </a>    
                                    <?php } ?> 
                                <?php } ?>
                            </center>      
                        </div>
                    </div>
                    <div class="clear"></div>
                </div> 
                <?php }; ?>           
            </div>
        </div>
    </div>
    <!-- MODAL ระหว่างซ่อม/หลังซ่อม -->
    <div id="repairdetail" class="menu menu-box-modal-forworking rounded-m" data-menu-height="100%" data-menu-width="500" data-backdrop="static">
        <div class="menu-title">
            <h1 class="font-24"><label id="title_det"></label></h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>
        <form action="#" method="post" enctype="multipart/form-data" name="form_project_detail" id="form_project_detail">
            <div class="content mb-0 mt-2">
                <div class="row mb-0">
                    <div class="col-12">
                        <label class="color-highlight" id="title2_det"></label>
                        <div class="input-style has-borders no-icon mb-4">
                            <textarea name="detailrepair" id="detailrepair" placeholder=""></textarea>
                        </div>
                    </div>
                </div>
                <center>
                    <div class="col-8">
                        <div class="row mb-0">
                            <div class="col-6">
                                <a href="#" class="btn btn-full btn-m bg-green-dark font-600 rounded-s" onclick="save_detailrepair()">บันทึกข้อมูล</a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="btn close-menu btn-full btn-m bg-red-dark font-600 rounded-s">ปิดหน้าต่าง</a>
                            </div>
                        </div>
                    </div>
                </center>
            </div>
            <input type="hidden" name="type_det" id="type_det">
            <input type="hidden" name="target_det" id="target_det">
            <input type="hidden" name="proc_det" id="proc_det">
            <input type="hidden" name="RPRQ_CODE_det" id="RPRQ_CODE_det">
            <input type="hidden" name="SUBJECT_det" id="SUBJECT_det">
        </form>
        <br>
    </div>    
    <!-- MODAL รูปภาพ -->
    <div id="repairimage" class="menu menu-box-modal-forworking rounded-m" data-menu-height="100%" data-menu-width="500" data-backdrop="static">
        <div class="menu-title">
            <h1 class="font-24"><label id="title"></label></h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>
        <form action="#" method="post" enctype="multipart/form-data" name="form_project_image" id="form_project_image">
            <div class="content mb-0 mt-2">
                <div class="row mb-0">
                    <div class="col-12">
                        <label class="color-highlight">เลือกกลุ่มรูปภาพ:</label>
                        <div class="input-style has-borders mb-4">
                            <select class="time" style="width: 100%;" name="RPATIM_GROUP" id="RPATIM_GROUP">
                                <option value disabled selected>----โปรดเลือก---</option>
                                <option value="DURING" <?php if($RPATCL_TYPE== "DURING"){echo "selected";} ?>>ระหว่างซ่อม</option>
                                <option value="AFTER" <?php if($RPATCL_TYPE== "AFTER"){echo "selected";} ?>>หลังซ่อม</option>
                            </select>
                            <span><i class="fa fa-chevron-down"></i></span>
                        </div>
                    </div>                           
                    <div class="col-12">
                        <label class="color-highlight">เลือกรูปภาพ:</label>
                        <div class="pb-4">
                            <input type="file" name="fileToUpload[]" id="fileToUpload" class="upload-file-image bg-highlight shadow-s rounded-s " onFocus="$(this).select();" accept="image/png, image/gif, image/jpeg, image/jpg, image/tif" multiple/>
                        </div>
                    </div>
                </div>
                <center>
                    <div class="col-8">
                        <div class="row mb-0">
                            <div class="col-6">
                                <a href="#" class="btn btn-full btn-m bg-green-dark font-600 rounded-s" onclick="save_imagerepair()">บันทึกข้อมูล</a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="btn close-menu btn-full btn-m bg-red-dark font-600 rounded-s">ปิดหน้าต่าง</a>
                            </div>
                        </div>
                    </div>
                </center>
            </div>
            <input type="hidden" name="type" id="type">
            <input type="hidden" name="target" id="target">
            <input type="hidden" name="proc" id="proc">
            <input type="hidden" name="RPRQ_CODE" id="RPRQ_CODE">
            <input type="hidden" name="SUBJECT" id="SUBJECT">
        </form>
        <br>
    </div>  
    <!-- MODAL หยุดพักงาน --> 
    <div id="repairpausejob" class="menu menu-box-modal-forworking rounded-m" data-menu-height="100%" data-menu-width="500" data-backdrop="static">
        <h1 class="text-center mt-4"><i class="fa fa-3x fa-info-circle scale-box color-yellow-dark shadow-xl rounded-circle"></i></h1>
        <h3 class="text-center mt-3 font-700">คุณแน่ใจหรือไม่...ที่จะหยุดพักงานซ่อมนี้</h3>
        <form action="#" method="post" enctype="multipart/form-data" name="form_project_pausejob" id="form_project_pausejob">
            <div class="content mb-2 mt-2">
                <div class="row mb-0">
                    <div class="col-12">
                        <label class="color-highlight" id="title_pj"></label>
                        <div class="input-style has-borders no-icon mb-4">
                            <textarea name="pausejobrepair" id="pausejobrepair" placeholder=""></textarea>
                        </div>
                    </div>
                </div>
                <center>
                    <div class="row mb-0 me-3 ms-3">
                        <div class="col-6">
                            <a href="#" class="btn btn-full btn-m bg-green-dark border-green-dark font-600 rounded-s" onclick="save_pausejob()">ใช่! บันทึกหยุดพักงาน</a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn close-menu btn-full btn-m bg-red-dark border-red-dark font-600 rounded-s">ยกเลิก</a>
                        </div>
                    </div>
                </center>
            </div>
            <input type="hidden" name="target_pj" id="target_pj">
            <input type="hidden" name="groub_pj" id="groub_pj">
            <input type="hidden" name="RPRQ_CODE_pj" id="RPRQ_CODE_pj">
            <input type="hidden" name="SUBJECT_pj" id="SUBJECT_pj">
        </form>
        <br>
    </div> 
</div>
<?php	
	require($path."include/script.php"); 
?>