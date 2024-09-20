<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	$SESSION_AREA=$_SESSION["AD_AREA"];

    // $wh="";
    if($_GET['ctmcomcode']!=""){                        
        if(($_GET['ctmcomcode']!="ALL")&&($_GET['ctmcomcode']!="cusout")&&($_GET['ctmcomcode']!="AMT")&&($_GET['ctmcomcode']!="GW")){
            $wh="AND ACTIVESTATUS = '1' AND AFFCOMPANY LIKE '%".$_GET['ctmcomcode']."%'";
            // echo "1";
        }else if($_GET['ctmcomcode']=="AMT"){
            $wh="AND ACTIVESTATUS = '1' AND AFFCOMPANY IN('RKR','RKS','RKL')";
            // echo "2";
        }else if(($_GET['ctmcomcode']=="GW")){
            $wh="AND ACTIVESTATUS = '1' AND AFFCOMPANY IN('RCC','RRC','RATC')";
            // echo "3";
        }else if($_GET['ctmcomcode']=="cusout"){
            $wh="AND ACTIVESTATUS = '1'";
            // echo "4";
        }else {
            $wh="";
            // echo "5";
        }
    }else{
        $wh="null";
    }
    
    $GETCUS=$_GET['ctmcomcode'];
    // $stmt_checkcus = "SELECT * FROM CUSTOMER WHERE CTM_COMCODE = ? AND NOT CTM_STATUS='D'";
    // $params_checkcus = array($GETCUS);	
    // $query_checkcus = sqlsrv_query( $conn, $stmt_checkcus, $params_checkcus);	
    // $result_checkcus = sqlsrv_fetch_array($query_checkcus, SQLSRV_FETCH_ASSOC);    
    if($GETCUS=="cusout"){
        $CTM_GROUP="cusout";
    }else{
        $CTM_GROUP="cusin";
        // $CTM_GROUP=$result_checkcus["CTM_GROUP"];
    }
    // echo "<pre>";
    // print_r($_GET);
    // print_r($CTM_GROUP);
    // echo "</pre>";
    
    if($GETCUS=="cusout"){
        $sql_vehicleinfo = "SELECT * FROM CUSTOMER_CAR 
        LEFT JOIN CUSTOMER ON CUSTOMER.CTM_COMCODE = CUSTOMER_CAR.AFFCOMPANY
        LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
        LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
        WHERE 1=1 AND NOT CTM_STATUS='D' AND VEHICLEGROUPDESC = 'Transport' ".$wh."";
    }else{
        $sql_vehicleinfo = "SELECT * FROM vwVEHICLEINFO 
        LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
        LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
        WHERE 1=1 AND VEHICLEGROUPDESC = 'Transport' ".$wh."";
    }
    // AND VEHICLEGROUPDESC = 'Transport' 
    $query_vehicleinfo = sqlsrv_query($conn, $sql_vehicleinfo);
    $no=0;
?>
<html>
<head>
    <script>        
        function selectcustomer(a1){
            if (a1=='AMT') {
                var ctmcomcode = a1;
            }else if(a1=='GW'){
                var ctmcomcode = a1;
            }else if(a1=='cusout'){
                var ctmcomcode = a1;
            }else{
                var ctmcomcode = $("#CTM_COMCODE").val();
            }
            var getctmcomcode = "?ctmcomcode="+ctmcomcode;
            loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm_form.php'+getctmcomcode);
        }
        $(document).ready(function(e) {	   
            $("#button_new").click(function(){
                ajaxPopup2("<?=$path?>views_amt/request_repair/request_repair_pm_form.php","add","1=1","1350","670","เพิ่มใบแจ้งซ่อม");
            });
        });        
	
        function save_mileagemax(vhcrgnb,ctmcomcode,mileage){
            var url = "<?=$path?>views_amt/request_repair/request_repair_pm_proc.php";
            var getctmcomcode = "?ctmcomcode="+ctmcomcode;
            $.ajax({
                type: 'POST',
                url: url,			
                data: {
                    target: "save_mileagemax",
                    vhcrgnb: vhcrgnb,
                    mileage: mileage
                },
                success: function (rs) {
                    loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm_form.php'+getctmcomcode);
                    closeUI();
                }
            });
        }
        
        function swaldelete_requestrepair(refcode,no,ctmcomcode) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่...ที่จะยกเลิกรายการซ่อมของทะเบียนหมายเลข '+no+' นี้',
                text: "หากยกเลิกแล้ว คุณจะไม่สามารถกู้คืนข้อมูลได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C82333',
                confirmButtonText: 'ใช่! ยกเลิกเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ยกเลิกเรียบร้อยแล้ว',
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {	
                        // if(!confirm("ยืนยันการลบข้อมูลอีกครั้ง")) return false;
                        // var ref = getIdSelect(); 
                        var ref = refcode; 
                        var url = "<?=$path?>views_amt/request_repair/request_repair_pm_proc.php?proc=delete&id="+ref;
                        var getctmcomcode = "?ctmcomcode="+ctmcomcode;
                        $.ajax({
                            type:'GET',
                            url:url,
                            data:"",				
                            success:function(data){
                                log_transac_requestrepair('LA5', '-', ref);
                                loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm_form.php'+getctmcomcode);
                                // alert(data);
                            }
                        });
                    })	
                }
            })
        }
    </script>
    <style>
        .largerCheckbox2 {
            width: 20px;
            height: 20px;
        }
        .largerCheckbox1 {
            width: 20px;
            height: 20px;
        }
        .largerCheckbox {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25" valign="middle" class=""><img src="https://img2.pic.in.th/pic/car_repair.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;ข้อมูลใบขอซ่อมรถ PM</h3></td>
        <td width="617" align="right" valign="bottom" class="" nowrap>
            <div class="toolbar">
                <!-- <button class="bg-color-blue" style="padding-top:8px;" title="New" id="button_new"><i class='icon-plus icon-large'></i></button> -->
                <!-- <button class="bg-color-yellow" style="padding-top:8px;" title="Edit" id="button_edit"><i class='icon-pencil icon-large'></i></button> -->
                <!-- <button class="bg-color-red" style="padding-top:8px;" title="Del" id="button_delete"><i class="icon-cancel icon-large"></i></button> -->
            </div>
        </td>
        </tr>
    </table></td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center">    
        <br>    
        <table>
            <tbody>
                <tr style="cursor:pointer" height="40px" align="center">                               
                    <td width="15%" align="center">
                        <div class="input-control select">                    
                            <?php
                                $stmt_selcus = "SELECT * FROM CUSTOMER WHERE NOT CTM_STATUS IN ('D','N') AND CTM_GROUP='cusin'"; //  AND CTM_GROUP='cusin' แสดงเฉพาะภายใน
                                $query_selcus = sqlsrv_query($conn, $stmt_selcus);
                            ?>
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="CTM_COMCODE" id="CTM_COMCODE" required>
                                <option value disabled selected>-------เลือกบริษัท-------</option>
                                <option value="ALL" <?php if($_GET['ctmcomcode']=="ALL"){echo "selected";} ?>>เลือกทั้งหมด</option>
                                <!-- sqlsrv_fetch_assoc -->
                                <?php while($result_selcus = sqlsrv_fetch_array($query_selcus)): 
                                    $CTM_COMCODE=$result_selcus['CTM_COMCODE'];
                                    $CTM_GROUP1=$result_selcus["CTM_GROUP"];
                                    if($CTM_GROUP1=="cusout"){
                                        $sql_count = "SELECT COUNT(VEHICLEINFOID) COUNTCTMID FROM CUSTOMER_CAR WHERE AFFCOMPANY = ? AND ACTIVESTATUS = '1'";
                                    }else{
                                        $sql_count = "SELECT COUNT(VEHICLEINFOID) COUNTCTMID FROM vwVEHICLEINFO WHERE AFFCOMPANY = ? AND NOT VEHICLEGROUPDESC = 'Car Pool' AND ACTIVESTATUS = '1'";
                                    }				
                                    $params_count = array($CTM_COMCODE);	
                                    $query_count = sqlsrv_query( $conn, $sql_count, $params_count);	
                                    $result_count = sqlsrv_fetch_array($query_count, SQLSRV_FETCH_ASSOC);
                                    $COUNTCTMID=$result_count["COUNTCTMID"];
                                    if($COUNTCTMID>0){                                                                           
                                ?>
                                    <option value="<?=$result_selcus['CTM_COMCODE']?>" <?php if($_GET['ctmcomcode']==$result_selcus['CTM_COMCODE']){echo "selected";} ?>><?=$result_selcus['CTM_COMCODE']?> - <?=$result_selcus['CTM_NAMETH']?></option>
                                <?php  } endwhile; ?>
                                <option value="cusout" <?php if($_GET['ctmcomcode']=="cusout"){echo "selected";} ?>>ลูกค้านอก</option>
                            </select>
                        </div>
                    </td>
                    <td width="10%" align="center">
                        <button class="bg-color-blue" onclick="selectcustomer()"><font color="white"><i class="icon-search"></i> ค้นหา</font></button>
                    </td>
                    <td width="5%" align="center">
                        <font size="10">|</font>
                    </td>
                    <td width="30%" align="center">
                        <!-- < ?php if($CTM_GROUP=="cusout"){ ?>
                            <button class="bg-color-yellow" onclick="javascript:loadViewdetail('< ?=$path?>views_amt/customer_manage/customer_car.php?ctm_comcode=< ?php print $_GET['ctmcomcode']; ?>&from=pm',);"><font color="black"><i class="icon-plus"></i> เพิ่ม/แก้ไข ข้อมูลรถลูกค้านอก</font></button>
                        < ?php } ?> -->
                        <label onclick="selectcustomer('AMT')" style="cursor:pointer;">
							<input type="checkbox" name="CTM_COMCODE" id="CTM_COMCODE" class="largerCheckbox1" <?php if($_GET['ctmcomcode']=='AMT'){echo 'checked';}?> >&ensp;<font size="5">AMT</font>
                        </label>&emsp;&emsp;
                        <label onclick="selectcustomer('GW')" style="cursor:pointer;">
							<input type="checkbox" name="CTM_COMCODE" id="CTM_COMCODE" class="largerCheckbox1" <?php if($_GET['ctmcomcode']=='GW'){echo 'checked';}?> >&ensp;<font size="5">GW</font>
                        </label>&emsp;&emsp;
                        <label onclick="selectcustomer('cusout')" style="cursor:pointer;">
							<input type="checkbox" name="CTM_COMCODE" id="CTM_COMCODE" class="largerCheckbox1" <?php if($_GET['ctmcomcode']=='cusout'){echo 'checked';}?> >&ensp;<font size="5">ลูกค้านอก</font>
                        </label>
                    </td>
                    <td width="5%" align="center">
                        <font size="10">|</font>
                    </td>
                    <td align="center">
                        <h4>
                            <img src="https://img2.pic.in.th/pic/color_blue.png" width="20" height="20"> = อยู่ระหว่างการซ่อม PM
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <img src="https://img2.pic.in.th/pic/color_gray.png" width="20" height="20"> = อยู่ระหว่างการซ่อม BM
                        </h4>                        
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>         
        <form id="form1" name="form1" method="post" action="#">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
                <thead>
                    <tr height="30">
                        <th rowspan="2" align="center" width="5%">ลำดับ</th>
                        <th rowspan="2" align="center" width="5%">ส่งแผน/จัดการ</th>
                        <!-- <th rowspan="2" align="center" width="5%">แก้ไขแผน</th> -->
                        <th colspan="2" align="center" width="13%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
                        <?php if($CTM_GROUP=="cusout"){ ?>
                            <th rowspan="2" align="center" width="13%" class="ui-state-default">เพิ่ม/แก้ไขรถลูกค้า - ชื่อลูกค้า</th>
                        <?php } ?>
                        <th rowspan="2" align="center" width="5%">ไมล์ล่าสุด</th>
                        <?php if($CTM_GROUP!="cusout"){ ?>
                            <th rowspan="2" align="center" width="5%">ไมล์เกินระยะ</th>
                            <th rowspan="2" align="center" width="5%">กม.วัน</th>
                            <th rowspan="2" align="center" width="5%">PM/วัน</th>
                        <?php } ?>
                        <th colspan="2" align="center" width="11%" class="ui-state-default">กำหนดระยะ PM</th>
                        <th colspan="2" align="center" width="11%" class="ui-state-default">PM ครั้งต่อไป</th>
                        <th rowspan="2" align="center" width="5%">เวลาในการซ่อม (ชม.)</th>
                        <th rowspan="2" align="center" width="10%">วันที่/เวลานัดรถเข้าซ่อม</th>
                    </tr>
                    <tr height="30">
                        <th align="center" width="5%">ทะเบียน</th>
                        <th align="center">ชื่อรถ</th>  
                        <?php if($CTM_GROUP!="cusout"){ ?>                  
                            <!-- <th align="center" width="5%">ทะเบียน</th> -->
                            <!-- <th align="center">ชื่อรถ</th> -->
                        <?php } ?>
                        <th align="center">ระยะเลขไมล์</th>
                        <th align="center">PM</th>
                        <th align="center">ระยะเลขไมล์</th>
                        <th align="center">PM</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while($result_vehicleinfo = sqlsrv_fetch_array($query_vehicleinfo, SQLSRV_FETCH_ASSOC)){	
                            $no++;

                            $VEHICLEREGISNUMBER=$result_vehicleinfo['VEHICLEREGISNUMBER'];
                            $THAINAME=$result_vehicleinfo['THAINAME'];
                            if($_GET['ctmcomcode']=="AMT"||$_GET['ctmcomcode']=="RKS"||$_GET['ctmcomcode']=="RKR"||$_GET['ctmcomcode']=="RKL"){     
                                $field="VEHICLEREGISNUMBER = '$VEHICLEREGISNUMBER'";
                            }else if($_GET['ctmcomcode']=="GW"||$_GET['ctmcomcode']=="RRC"||$_GET['ctmcomcode']=="RCC"||$_GET['ctmcomcode']=="RATC"){
                                $explodes = explode('(', $THAINAME);
                                $THAINAME = $explodes[0];
                                $field="THAINAME = '$THAINAME'";
                            }else{
                                $field="VEHICLEREGISNUMBER = '$VEHICLEREGISNUMBER'";
                            }

                            if($CTM_GROUP=="cusout"){
                                // $sql_mileage = "SELECT TOP 1 MILEAGENUMBER AS MAXMILEAGENUMBER,VEHICLEREGISNUMBER,CREATEDATE FROM TEST_MILEAGE_PM WHERE ACTIVESTATUS = 1  AND MILEAGENUMBER <> '0' AND VEHICLEREGISNUMBER = '".$result_vehicleinfo['VEHICLEREGISNUMBER']."' ORDER BY CREATEDATE DESC ";
                                $sql_mileage = "SELECT TOP 1 MILEAGENUMBER AS MAXMILEAGENUMBER,VEHICLEREGISNUMBER,CREATEDATE FROM TEST_MILEAGE_PM WHERE ACTIVESTATUS = 1  AND MILEAGENUMBER <> '0' AND VEHICLEREGISNUMBER = '".$result_vehicleinfo['VEHICLEREGISNUMBER']."' ORDER BY MILEAGEID DESC ";
                                $params_mileage = array();
                                $query_mileage = sqlsrv_query($conn, $sql_mileage, $params_mileage);
                                $result_mileage = sqlsrv_fetch_array($query_mileage, SQLSRV_FETCH_ASSOC); 
                            }else{
                                // $sql_mileage = "SELECT TOP 1 * FROM vwMILEAGE WHERE VEHICLEREGISNUMBER = '".$result_vehicleinfo['VEHICLEREGISNUMBER']."' ORDER BY CREATEDATE DESC ";
                                $sql_mileage = "SELECT TOP 1 * FROM vwMILEAGE WHERE $field ORDER BY MILEAGEID DESC ";
                                $params_mileage = array();
                                $query_mileage = sqlsrv_query($conn, $sql_mileage, $params_mileage);
                                $result_mileage = sqlsrv_fetch_array($query_mileage, SQLSRV_FETCH_ASSOC); 
                            }
                            // $VHCTMG_LINEOFWORK = $result_vehicleinfo['VHCTMG_LINEOFWORK'];
                            $VHCTMG_LINEOFWORK = $result_vehicleinfo['VHCCT_PM'];
                            if(isset($result_mileage['MAXMILEAGENUMBER'])){
                                if($result_mileage['MAXMILEAGENUMBER']>1000000){
                                    $MAXMILEAGENUMBER = $result_mileage['MAXMILEAGENUMBER']-1000000;
                                }else{
                                    $MAXMILEAGENUMBER = $result_mileage['MAXMILEAGENUMBER'];
                                }
                            }else{
                                $MAXMILEAGENUMBER = 0;
                            }
                            
                            if(($MAXMILEAGENUMBER >= '0') && ($MAXMILEAGENUMBER <= '1000000')){
                                $fildsfind="MLPM_MILEAGE_10K1M";
                            }else if(($MAXMILEAGENUMBER >= '1000001') && ($MAXMILEAGENUMBER <= '2000000')){
                                $fildsfind="MLPM_MILEAGE_1M2M";
                            }else if(($MAXMILEAGENUMBER >= '2000001') && ($MAXMILEAGENUMBER <= '3000000')){
                                $fildsfind="MLPM_MILEAGE_2M3M";
                            }

                            // $sql_rankpm = "SELECT TOP 1 * FROM MILEAGESETPM WHERE MLPM_LINEOFWORK = '$VHCTMG_LINEOFWORK' AND $fildsfind > '".$MAXMILEAGENUMBER."' AND MLPM_AREA = '$SESSION_AREA' ORDER BY $fildsfind ASC";
                            $sql_rankpm = "SELECT TOP 1 * FROM MILEAGESETPM WHERE MLPM_LINEOFWORK = '$VHCTMG_LINEOFWORK' AND $fildsfind > '".$MAXMILEAGENUMBER."' ORDER BY $fildsfind ASC";
                            $params_rankpm = array();
                            $query_rankpm = sqlsrv_query($conn, $sql_rankpm, $params_rankpm);
                            $result_rankpm = sqlsrv_fetch_array($query_rankpm, SQLSRV_FETCH_ASSOC);        
                            $MLPM_MILEAGE=$result_rankpm[$fildsfind];

                            if(($MLPM_MILEAGE > '0') && ($MLPM_MILEAGE < '1000000')){
                                $fildsfindnext="MLPM_MILEAGE_10K1M";
                            }else if(($MLPM_MILEAGE >= '1000000') && ($MLPM_MILEAGE < '2000000')){
                                $fildsfindnext="MLPM_MILEAGE_1M2M";
                            }else if(($MLPM_MILEAGE >= '2000000') && ($MLPM_MILEAGE < '3000000')){
                                $fildsfindnext="MLPM_MILEAGE_2M3M";
                            }
                            // $sql_ranknextpm = "SELECT TOP 1 * FROM MILEAGESETPM WHERE MLPM_LINEOFWORK = '$VHCTMG_LINEOFWORK' AND $fildsfindnext > '$MLPM_MILEAGE' AND MLPM_AREA = '$SESSION_AREA' ORDER BY $fildsfindnext ASC";
                            $sql_ranknextpm = "SELECT TOP 1 * FROM MILEAGESETPM WHERE MLPM_LINEOFWORK = '$VHCTMG_LINEOFWORK' AND $fildsfindnext > '$MLPM_MILEAGE' ORDER BY $fildsfindnext ASC";
                            $params_ranknextpm = array();
                            $query_ranknextpm = sqlsrv_query($conn, $sql_ranknextpm, $params_ranknextpm);
                            $result_ranknextpm = sqlsrv_fetch_array($query_ranknextpm, SQLSRV_FETCH_ASSOC);
                            $MLNEXTPM_MILEAGE=$result_ranknextpm[$fildsfindnext];
                            
                            if($_GET['ctmcomcode']=="AMT"){
                                $GETCUSAREA="AND RPRQ_LINEOFWORK IN('RKL','RKR','RKS')";
                                // $GETCUSAREA="'RKL','RKR','RKS'";
                            }else if($_GET['ctmcomcode']=="GW"){
                                $GETCUSAREA="AND RPRQ_LINEOFWORK IN('RATC','RCC','RRC')";
                                // $GETCUSAREA="'RATC','RCC','RRC'";
                            }else if($_GET['ctmcomcode']=="cusout"){
                                $GETCUSAREA="";
                            }else{
                                $GETCUSAREA="AND RPRQ_LINEOFWORK IN('$GETCUS')";
                                // $GETCUSAREA="'$GETCUS'";
                            }
                            $sql_rprq_check = "SELECT DISTINCT REPAIRREQUEST.RPRQ_CODE,RPRQ_WORKTYPE,RPRQ_REGISHEAD,RPRQ_REGISTAIL,RPRQ_STATUSREQUEST,RPRQ_REQUESTCARDATE,RPRQ_REQUESTCARTIME,RPC_INCARDATE,RPC_INCARTIME
                            FROM REPAIRREQUEST LEFT JOIN REPAIRCAUSE ON REPAIRCAUSE.RPRQ_CODE = REPAIRREQUEST.RPRQ_CODE
                            WHERE RPRQ_STATUS = 'Y' $GETCUSAREA AND RPRQ_REGISHEAD = '$VEHICLEREGISNUMBER' AND NOT RPRQ_STATUSREQUEST IN('ไม่อนุมัติ','ซ่อมเสร็จสิ้น') ORDER BY RPRQ_WORKTYPE DESC";
                            // WHERE RPRQ_STATUS = 'Y' AND RPRQ_LINEOFWORK IN($GETCUSAREA) AND RPRQ_REGISHEAD = '$VEHICLEREGISNUMBER' AND NOT RPRQ_STATUSREQUEST IN('ไม่อนุมัติ','ซ่อมเสร็จสิ้น') ORDER BY RPRQ_WORKTYPE DESC";
                            $query_rprq_check = sqlsrv_query($conn, $sql_rprq_check);
                            $result_rprq_check = sqlsrv_fetch_array($query_rprq_check, SQLSRV_FETCH_ASSOC);

                            $RPRQ_WORKTYPE=$result_rprq_check['RPRQ_WORKTYPE'];
                            $RPRQ_STATUSREQUEST=$result_rprq_check['RPRQ_STATUSREQUEST'];
                            $RPRQ_REGISHEAD=$result_rprq_check['RPRQ_REGISHEAD'];
                            $RPRQ_CODE=$result_rprq_check['RPRQ_CODE'];
                            $RPRQ_REQUESTCARDATE=$result_rprq_check['RPRQ_REQUESTCARDATE'];
                            $RPRQ_REQUESTCARTIME=$result_rprq_check['RPRQ_REQUESTCARTIME'];
                            $RPC_INCARDATE=$result_rprq_check['RPC_INCARDATE'];
                            $RPC_INCARTIME=$result_rprq_check['RPC_INCARTIME'];

                            if(($RPRQ_STATUSREQUEST=='รอตรวจสอบ')&&($RPRQ_WORKTYPE=='PM')){
                                $class_tr='class="info"';
                                $font='white';
                            }else if(($RPRQ_STATUSREQUEST=='รอจ่ายงาน')&&($RPRQ_WORKTYPE=='PM')){
                                $class_tr='class="info"';
                                $font='black';
                            }else if(($RPRQ_STATUSREQUEST=='รอคิวซ่อม')&&($RPRQ_WORKTYPE=='PM')){
                                $class_tr='class="info"';
                                $font='black';
                            }else if(($RPRQ_STATUSREQUEST=='กำลังซ่อม')&&($RPRQ_WORKTYPE=='PM')){
                                $class_tr='class="info"';
                                $font='black';
                            }else if($RPRQ_WORKTYPE=='BM'){
                                $class_tr='class="second"';
                                $font='black';
                            }else{
                                $class_tr='';
                                $font='black';
                            }
                            
                            if($result_rankpm['MLPM_NAME']=="PMoRS-1"){
                                $fildsfindETM = "ETM_PM1";
                            }else if($result_rankpm['MLPM_NAME']=="PMoRS-2"){
                                $fildsfindETM = "ETM_PM2";
                            }else if($result_rankpm['MLPM_NAME']=="PMoRS-3"){
                                $fildsfindETM = "ETM_PM3";
                            }else if($result_rankpm['MLPM_NAME']=="PMoRS-4"){
                                $fildsfindETM = "ETM_PM4";
                            }else if($result_rankpm['MLPM_NAME']=="PMoRS-5"){
                                $fildsfindETM = "ETM_PM5";
                            }else if($result_rankpm['MLPM_NAME']=="PMoRS-6"){
                                $fildsfindETM = "ETM_PM6";
                            }else if($result_rankpm['MLPM_NAME']=="PMoRS-7"){
                                $fildsfindETM = "ETM_PM7";
                            }else if($result_rankpm['MLPM_NAME']=="PMoRS-8"){
                                $fildsfindETM = "ETM_PM8";
                            }else if($result_rankpm['MLPM_NAME']=="PMoRS-9"){
                                $fildsfindETM = "ETM_PM9";
                            }else if($result_rankpm['MLPM_NAME']=="PMoRS-10"){
                                $fildsfindETM = "ETM_PM10";
                            }else if($result_rankpm['MLPM_NAME']=="PMoRS-11"){
                                $fildsfindETM = "ETM_PM11";
                            }else if($result_rankpm['MLPM_NAME']=="PMoRS-12"){
                                $fildsfindETM = "ETM_PM12";
                            }else{
                                $fildsfindETM = "";
                            }
                            $VHCCT_ID = $result_vehicleinfo["VHCCT_ID"];
                            $sql_selhour = "SELECT SUM(CAST($fildsfindETM AS DECIMAL(10,2))) AS SUMETM FROM ESTIMATE
                            WHERE VHCCT_ID = '$VHCCT_ID' AND ETM_NUM = '6' AND NOT ETM_TYPE = 'รวม'";
                            $params_selhour = array();
                            $query_selhour = sqlsrv_query($conn, $sql_selhour, $params_selhour);
                            $result_selhour = sqlsrv_fetch_array($query_selhour, SQLSRV_FETCH_ASSOC); 
                            $ETM_PM=$result_selhour['SUMETM'];
                            $CALETM_PM=$ETM_PM*60;
                            $ROUND_CALETM_PM=round($CALETM_PM);
                            $HOUR_CALETM_PM = floor($ROUND_CALETM_PM/60);
                            $MINUIT_CALETM_PM = $ROUND_CALETM_PM - ($HOUR_CALETM_PM * 60);
                            $HOUR_ROUND_CALETM_PM = $HOUR_CALETM_PM.':'.$MINUIT_CALETM_PM;
                            // $HOUR_ROUND_CALETM_PM = floor($ROUND_CALETM_PM/60).':'.($ROUND_CALETM_PM-floor($ROUND_CALETM_PM/60)*60);

                    ?>
                    <tr height="25px" align="center" id="<?php print $result_vehicleinfo['VEHICLEINFOID']; ?>" <?=$class_tr?>>
                        <!-- onDblClick="javascript:ajaxPopup2('<?=$path?>views_amt/customer_manage/customer_car_detail.php','','id=<?php print $result_vehicleinfo['VEHICLEINFOID']; ?>','1300','480','รายละเอียด')" -->
                        <td ><?php print "$no.";?></td>
                        <td >
                            <?php if(($RPRQ_STATUSREQUEST=='รอตรวจสอบ')&&($RPRQ_WORKTYPE=='PM')){ ?>
                                <a href="javascript:void(0);" onclick="swaldelete_requestrepair('<?php print $RPRQ_CODE; ?>','<?php print $result_vehicleinfo['VEHICLEREGISNUMBER'];?>','<?php print $_GET['ctmcomcode'];?>')"><img src="https://img2.pic.in.th/pic/delete-icon24.png" width="24" height="24"></a>    
                                <!-- <a href="javascript:void(0);" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_pm_sent.php','edit','<?php print $RPRQ_CODE; ?>&ctmgroup=<?php print $CTM_GROUP; ?>','1=1','1300','400','แก้ไขแผน PM');"><img src="https://img2.pic.in.th/pic/Process-Info-icon24.png" width="24" height="24"></a>     -->
                            <?php }else if(($RPRQ_STATUSREQUEST=='รอจ่ายงาน')&&($RPRQ_WORKTYPE=='PM')){ ?>
                                <a href="javascript:void(0);" onclick="swaldelete_requestrepair('<?php print $RPRQ_CODE; ?>','<?php print $result_vehicleinfo['VEHICLEREGISNUMBER'];?>','<?php print $_GET['ctmcomcode'];?>')"><img src="https://img2.pic.in.th/pic/delete-icon24.png" width="24" height="24"></a>    
                                <!-- <a href="javascript:void(0);" onclick="swaldelete_requestrepair('<?php print $RPRQ_CODE; ?>','<?php print $result_vehicleinfo['VEHICLEREGISNUMBER'];?>','<?php print $_GET['ctmcomcode'];?>')"><img src="https://img2.pic.in.th/pic/delete-icon24.png" width="24" height="24"></a>     -->
                            <?php }else if(($RPRQ_STATUSREQUEST=='รอคิวซ่อม')&&($RPRQ_WORKTYPE=='PM')){ ?>
                                <a href="#"></a>
                            <?php }else if(($RPRQ_STATUSREQUEST=='กำลังซ่อม')&&($RPRQ_WORKTYPE=='PM')){ ?>
                                <a href="#"></a>
                            <!-- < ?php }else if($RPRQ_WORKTYPE=='BM'){ ?> -->
                                <!-- <a href="#"></a> -->
                            <?php }else{ ?>
                                <a href="javascript:void(0);" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_pm_sent.php','add','<?php print $result_vehicleinfo['VEHICLEINFOID']; ?>&ctmgroup=<?php print $CTM_GROUP; ?>','1=1','800','400','เพิ่มแผน PM');"><img src="https://img2.pic.in.th/pic/Process-Info-icon24.png" width="24" height="24"></a>    
                            <?php } ?>                            
                        </td>
                        <!-- <td >
                            <a href="javascript:void(0);" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_pm_sent.php','edit','<?php print $result_vehicleinfo['VEHICLEINFOID']; ?>&ctmgroup=<?php print $CTM_GROUP; ?>','1=1','1300','400','เพิ่มแผน PM');"><img src="https://img2.pic.in.th/pic/Document-Write-icon24.png" width="24" height="24"></a>    
                        </td> -->
                        <td align="center"><font color="<?=$font?>"><?= $result_vehicleinfo['VEHICLEREGISNUMBER'] ?></font></td>
                        <td align="left"><font color="<?=$font?>"><?= $result_vehicleinfo['THAINAME'] ?></font></td>
                        
                        <?php if($CTM_GROUP=="cusout"){ ?>   
                            <td align="left"><font color="<?=$font?>">
                            <a href="javascript:void(0);" onclick="javascript:loadViewdetail('<?=$path?>views_amt/customer_manage/customer_car.php?ctm_comcode=<?php print $result_vehicleinfo['CTM_COMCODE']; ?>&from=pm',);"><img src="https://img2.pic.in.th/pic/add-icon16.png" width="16" height="16"></a> - 
                            <?=$result_vehicleinfo['CTM_NAMETH']; ?></font></td>
                            <!-- <td ><font color="<?=$font?>"></font></td> -->
                            <!-- <td ><font color="<?=$font?>"></font></td> -->
                        <?php } ?>  
                            <td align="right">
                                <font color="<?=$font?>">
                                    <?php 
                                        if($CTM_GROUP!="cusout"){ 
                                            echo number_format($MAXMILEAGENUMBER); 
                                        }else{ ?>
                                            <input type="text" name="MILEAGENUMBER" id="MILEAGENUMBER" value="<?=$MAXMILEAGENUMBER?>" style="text-align:right;width:100%;" onchange="save_mileagemax('<?= $result_vehicleinfo['VEHICLEREGISNUMBER'] ?>','<?=$GETCUS;?>',this.value)" autocomplete="off">     
                                    <?php } ?>           
                                </font>
                            </td>                        
                        <?php if($CTM_GROUP!="cusout"){ ?>
                            <td align="right"><font color="<?=$font?>"><?= number_format($MLPM_MILEAGE - $MAXMILEAGENUMBER) ?></font></td>
                            <td align="right"><font color="<?=$font?>"><?= number_format($result_vehicleinfo['VHCCT_KILOFORDAY']) ?></font></td>
                            <td align="right"><font color="<?=$font?>"><?= number_format(($MLPM_MILEAGE - $MAXMILEAGENUMBER) / $result_vehicleinfo['VHCCT_KILOFORDAY']) ?></font></td>
                        <?php } ?>           
                        <td align="right"><font color="<?=$font?>"><?= number_format($MLPM_MILEAGE) ?></font></td>
                        <td align="center"><font color="<?=$font?>"><?= $result_rankpm['MLPM_NAME'] ?></font></td>
                        <td align="right"><font color="<?=$font?>"><?= number_format($MLNEXTPM_MILEAGE) ?></font></td>
                        <td align="center"><font color="<?=$font?>"><?= $result_ranknextpm['MLPM_NAME'] ?></font></td>
                        <td align="right"><font color="<?=$font?>"><?= $HOUR_ROUND_CALETM_PM;?></font></td>
                        <td align="right"><font color="<?=$font?>"><?= $RPC_INCARDATE ?> <?= $RPC_INCARTIME ?></font></td>
                    </tr>
                    <?php }; ?>
                </tbody>
            </table>
        </form>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
		<center>
			<input type="button" class="button_gray" value="อัพเดท" onclick="selectcustomer()">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>