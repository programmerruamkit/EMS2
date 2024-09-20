<?php
	session_start ();
    $path = "../../";    
	require($path."include/connect.php");
	require($path."include/authen.php"); 
	require($path."include/head.php");		
	// print_r ($_SESSION);
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
                <h4>ข้อมูลการแจ้งซ่อม </h4>
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
                <?php
                    $SESSION_PERSONCODE = $_SESSION["AD_PERSONCODE"];
                    $SUBJECT=$_GET['subject'];
                    $sql_repaircause = "SELECT * FROM REPAIRCAUSE WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$SUBJECT'";
                    $query_repaircause = sqlsrv_query($conn, $sql_repaircause);
                    $no=0;
                    $result_repaircause = sqlsrv_fetch_array($query_repaircause, SQLSRV_FETCH_ASSOC);     
                        $no++;
                        $RPC_SUBJECT=$result_repaircause['RPC_SUBJECT'];

                        $sql_repairtime = "SELECT * FROM REPAIRACTUAL_TIME WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$RPC_SUBJECT' ORDER BY RPATTM_ID DESC";
                        $params_repairtime = array();
                        $query_repairtime = sqlsrv_query($conn, $sql_repairtime, $params_repairtime);
                        $result_repairtime = sqlsrv_fetch_array($query_repairtime, SQLSRV_FETCH_ASSOC);                         

                        $sql_repairman = "SELECT * FROM REPAIRMANEMP WHERE RPRQ_CODE = '$RPRQ_CODE' AND RPC_SUBJECT = '$RPC_SUBJECT' AND RPME_CODE = '$SESSION_PERSONCODE'";
                        $params_repairman = array();
                        $query_repairman = sqlsrv_query($conn, $sql_repairman, $params_repairman);
                        $result_repairman = sqlsrv_fetch_array($query_repairman, SQLSRV_FETCH_ASSOC);  

                ?>
                <h4>รายการตรวจสอบงานซ่อม 
                    <?php	
                        switch($result_repaircause['RPC_SUBJECT']) {
                            case "EL": $RPC_SUBJECT="ระบบไฟ"; break;
                            case "TU": $RPC_SUBJECT="ยาง ช่วงล่าง"; break;
                            case "BD": $RPC_SUBJECT="โครงสร้าง"; break;
                            case "EG": $RPC_SUBJECT="เครื่องยนต์"; break;
                            case "AC": $RPC_SUBJECT="อุปกรณ์ประจำรถ"; break;
                        }
                        print '<u>งาน'.$RPC_SUBJECT.'</u>';
                    ?>
                </h4>    
                <div class="content mb-0">
                    <div class="row mb-3">
                        <div class="col-md-12">                        
                            <form action="#" method="post" enctype="multipart/form-data" name="form_project_chklist" id="form_project_chklist">
                                <div class="content mb-0 mt-2">
                                    <div class="row mb-0">
                                        <div class="col-12">              
                                            <font color="orange"><b><u>คำอธิบาย</u></b><br>I/C = ตรวจเช็คปรับตั้ง/ทำความสะอาด, I = ตรวจเช็คปรับตั้ง, R = เปลี่ยน, C = ทำความสะอาด</font>                       
                                            <table class="table table-borderless text-center rounded-sm shadow-l" style="overflow: hidden;">                 
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="bg-blue-dark color-white py-3 font-14" width="5%">ลำดับ.</th>
                                                        <th scope="col" class="bg-blue-dark color-white py-3 font-14" width="25%">รายการตรวจสอบ</th>
                                                        <th scope="col" class="bg-blue-dark color-white py-3 font-14" width="10%">ระยะเปลี่ยนเช็ค</th>
                                                        <th scope="col" class="bg-blue-dark color-white py-3 font-14" width="10%">สัญลักษณ์</th>
                                                        <th scope="col" class="bg-blue-dark color-white py-3 font-14" width="15%">สถานะ</th>
                                                        <th scope="col" class="bg-blue-dark color-white py-3 font-14" width="30%">หมายเหตุ</th>
                                                        <th scope="col" class="bg-blue-dark color-white py-3 font-14" width="5%">เคลียร์</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if($RPC_SUBJECT_CON=="PMoRS-1"){
                                                            $fildsfindCLRP = "CLRP_PM1";
                                                        }else if($RPC_SUBJECT_CON=="PMoRS-2"){
                                                            $fildsfindCLRP = "CLRP_PM2";
                                                        }else if($RPC_SUBJECT_CON=="PMoRS-3"){
                                                            $fildsfindCLRP = "CLRP_PM3";
                                                        }else if($RPC_SUBJECT_CON=="PMoRS-4"){
                                                            $fildsfindCLRP = "CLRP_PM4";
                                                        }else if($RPC_SUBJECT_CON=="PMoRS-5"){
                                                            $fildsfindCLRP = "CLRP_PM5";
                                                        }else if($RPC_SUBJECT_CON=="PMoRS-6"){
                                                            $fildsfindCLRP = "CLRP_PM6";
                                                        }else if($RPC_SUBJECT_CON=="PMoRS-7"){
                                                            $fildsfindCLRP = "CLRP_PM7";
                                                        }else if($RPC_SUBJECT_CON=="PMoRS-8"){
                                                            $fildsfindCLRP = "CLRP_PM8";
                                                        }else if($RPC_SUBJECT_CON=="PMoRS-9"){
                                                            $fildsfindCLRP = "CLRP_PM9";
                                                        }else if($RPC_SUBJECT_CON=="PMoRS-10"){
                                                            $fildsfindCLRP = "CLRP_PM10";
                                                        }else if($RPC_SUBJECT_CON=="PMoRS-11"){
                                                            $fildsfindCLRP = "CLRP_PM11";
                                                        }else if($RPC_SUBJECT_CON=="PMoRS-12"){
                                                            $fildsfindCLRP = "CLRP_PM12";
                                                        }

                                                        $AREA=$_SESSION["AD_AREA"];
                                                        $sql_vehicleinfo = "SELECT VEHICLECARTYPE.VHCCT_ID FROM vwVEHICLEINFO 
                                                        LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
                                                        LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
                                                        WHERE VEHICLEREGISNUMBER = '$RPRQ_REGISHEAD'";
                                                        $query_vehicleinfo = sqlsrv_query($conn, $sql_vehicleinfo);
                                                        $result_vehicleinfo = sqlsrv_fetch_array($query_vehicleinfo, SQLSRV_FETCH_ASSOC); 
                                                            $VHCCT_ID=$result_vehicleinfo['VHCCT_ID'];                                                             
                                                            $PMSUBJ = 'PM-'.$RPC_SUBJECT;     
                                                        $sql_checklist = "SELECT * FROM CHECKLISTREPAIR A 
                                                        LEFT JOIN VEHICLECARTYPE B ON B.VHCCT_ID = A.CLRP_CARTYPE 
                                                        WHERE NOT CLRP_STATUS = 'D' 
                                                        AND CLRP_CARTYPE = '$VHCCT_ID' 
                                                        AND CLRP_TYPE = '$PMSUBJ' 
                                                        AND $fildsfindCLRP IS NOT NULL 
                                                        ORDER BY CLRP_NUM ASC";
                                                        $query_checklist = sqlsrv_query($conn, $sql_checklist);
                                                        $no=0;
                                                        while($result_checklist = sqlsrv_fetch_array($query_checklist, SQLSRV_FETCH_ASSOC)){	
                                                        $no++;
                                                        $CLRP_ID=$result_checklist['CLRP_ID'];
                                                        $CLRP_CODE=$result_checklist['CLRP_CODE'];
                                                        $CLRP_NUM=$result_checklist['CLRP_NUM'];
                                                        $CLRP_NAME=$result_checklist['CLRP_NAME'];
                                                        $CLRP_CHECK=$result_checklist['CLRP_CHECK'];
                                                        $CLRP_PM=$result_checklist[$fildsfindCLRP];
                                                        $CLRP_TYPE=$result_checklist['CLRP_TYPE'];
                                                        $CLRP_RANK=$result_checklist['CLRP_RANK'];
                                                        $CLRP_CARTYPE=$result_checklist['CLRP_CARTYPE'];
                                                        $CLRP_REMARK=$result_checklist['CLRP_REMARK'];
                                                        $CLRP_STATUS=$result_checklist['CLRP_STATUS'];
                                                        
                                                        $sql1 = "SELECT * FROM REPAIRACTUAL_CHECKLIST WHERE RPRQ_CODE = ? AND RPC_SUBJECT = ? AND CLRP_CODE = ?";
                                                        $params1 = array($RPRQ_CODE,$SUBJECT,$CLRP_CODE);	
                                                        $query1 = sqlsrv_query( $conn, $sql1, $params1);	
                                                        $result1 = sqlsrv_fetch_array($query1, SQLSRV_FETCH_ASSOC);
                                                            $RPATCL_ID=$result1["RPATCL_ID"];
                                                            $RPATCL_TYPE=$result1["RPATCL_TYPE"];
                                                            $RPATCL_REMARK=$result1["RPATCL_REMARK"];
                                                    ?>
                                                    <tr height="25px" align="center">
                                                        <td class="bg-blue-light color-gray-light"><?php print "$no.";?></td>
                                                        <td align="left" class="bg-blue-light color-gray-light">&nbsp;<?php print $CLRP_NAME; ?></td>
                                                        <td align="center" class="bg-blue-light color-gray-light">&nbsp;<?php print $CLRP_CHECK; ?></td>
                                                        <td align="center" class="bg-blue-light color-gray-light">&nbsp;                                         
                                                            <?php	
                                                                // switch($CLRP_PM) {
                                                                // case "I/C": $CLRP_PM_CHK="ตรวจเช็คปรับตั้ง/ทำความสะอาด"; break;
                                                                // case "I": $CLRP_PM_CHK="ตรวจเช็คปรับตั้ง"; break;
                                                                // case "R": $CLRP_PM_CHK="เปลี่ยน"; break;
                                                                // case "C": $CLRP_PM_CHK="ทำความสะอาด"; break;
                                                                // }
                                                                // print $CLRP_PM.' = '.$CLRP_PM_CHK;
                                                                print $CLRP_PM;
                                                            ?>
                                                        </td>
                                                        <td align="center" class="bg-blue-light color-gray-light">                                        
                                                            <div class="input-style has-borders mb-4">
                                                                <select class="time" onFocus="$(this).select();" style="width: 100%;" name="CLRP_STATUS" id="CLRP_STATUS" onchange="save_chklist('<?=$RPRQ_CODE;?>','<?=$CLRP_CODE;?>','<?=$SUBJECT?>',this.value,'<?=$RPATCL_REMARK;?>')">
                                                                    <option value disabled selected>-โปรดเลือก-</option>
                                                                    <option value="S" <?php if($RPATCL_TYPE== "S"){echo "selected";} ?>>ซ่อมเสร็จ</option>
                                                                    <option value="W" <?php if($RPATCL_TYPE== "W"){echo "selected";} ?>>รอดำเนินการ</option>
                                                                    <option value="N" <?php if($RPATCL_TYPE== "N"){echo "selected";} ?>>ซ่อมไม่เสร็จ</option>
                                                                </select>
                                                                <span><i class="fa fa-chevron-down"></i></span>
                                                            </div>
                                                        </td>           
                                                        <td align="center" class="bg-blue-light color-gray-light">
                                                            <div class="input-style has-borders mb-4">       
                                                                <input type="text" name="RPATCL_REMARK" id="RPATCL_REMARK" autocomplete="off" value="<?=$RPATCL_REMARK?>" style="text-align:left;width:100%;" onchange="save_chklist('<?=$RPRQ_CODE;?>','<?=$CLRP_CODE;?>','<?=$SUBJECT;?>','<?=$RPATCL_TYPE;?>',this.value)">
                                                            </div>
                                                        </td>                
                                                        <td align="center" class="bg-blue-light color-gray-light" style="cursor:pointer">
                                                            <img src='https://img2.pic.in.th/pic/check_del.gif' width='16' height='16' onclick="save_chklist('<?=$RPRQ_CODE;?>','<?=$CLRP_CODE;?>','<?=$SUBJECT;?>','','')">
                                                        </td>                  
                                                    </tr>
                                                    <?php }; ?>
                                                </tbody>
                                            </table>
                                        </div>           
                                    </div>
                                </div>
                                <input type="hidden" name="proc_chk" id="proc_chk" value="<?=$_GET['proc'];?>">
                                <input type="hidden" name="RPRQ_CODE_chk" id="RPRQ_CODE_chk" value="<?=$_GET['id'];?>">
                                <input type="hidden" name="RPRQ_REGISHEAD_chk" id="RPRQ_REGISHEAD_chk" value="<?=$_GET['regishead'];?>">
                                <input type="hidden" name="RPC_SUBJECT_CON_chk" id="RPC_SUBJECT_CON_chk" value="<?=$_GET['pmrank'];?>">
                                <input type="hidden" name="SUBJECT_chk" id="SUBJECT_chk" value="<?=$_GET['subject'];?>">
                            </form>  
                        </div> 
                    </div>
                    <div class="clear"></div>
                </div>       
                <center>                    
                    <a href="#" class="btn btn-m shadow-s rounded-s font-700 bg-danger" onclick="javascript:location.href='./?id=<?php print $RPRQ_CODE;?>&proc=add'">
                        <i class="fa fa-reply <?=$css_checklist_icenter;?>"></i><font size="2" class="showfont">ย้อนกลับ</font>                                
                    </a>
                </center>
                <br>
            </div>
        </div>
    </div>
    
</div>
<?php	
	require($path."include/script.php"); 
?>