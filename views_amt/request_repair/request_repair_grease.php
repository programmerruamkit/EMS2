<!-- /*
===== ขั้นตอนการทำงานของระบบบันทึกอัดจารบี =====

1. การเริ่มต้นระบบ:
    - เริ่ม session และเชื่อมต่อฐานข้อมูล
    - รับค่า SESSION_AREA จาก session

2. การเลือกบริษัท:
    - แสดง dropdown เลือกบริษัท (RKR, RKS, RKL, RCC, RRC, RATC)
    - มี checkbox เลือกแบบกลุ่ม AMT (RKR,RKS,RKL) หรือ GW (RCC,RRC,RATC)
    - ฟังก์ชัน selectcustomer() จะรีเฟรชข้อมูลตามบริษัทที่เลือก

3. การแสดงข้อมูลรถ:
    - ดึงข้อมูลรถจาก vwVEHICLEINFO (เฉพาะทะเบียนหัว, Transport)
    - แสดงข้อมูล: ทะเบียน, ชื่อรถ, กม./วัน, เลขไมล์ล่าสุด
    - ดึงข้อมูลการอัดจารบีล่าสุดจาก REPAIR_GREASE
    - คำนวณรอบการอัดจารบี (1000กม./รอบ, รอบที่5=5000กม.)

4. การคำนวณกำหนดอัดจารบี:
    - คำนวณระยะที่ใช้ไป = เลขไมล์ปัจจุบัน - เลขไมล์อัดครั้งล่าสุด
    - คำนวณระยะที่เหลือ = รอบถัดไป - ระยะที่ใช้ไป
    - คำนวณจำนวนวันที่เหลือ = ระยะที่เหลือ / กม.ต่อวัน
    - แสดงสถานะ: อีกกี่วัน / เกินกี่วัน / ควรอัดทันที

5. การเลือกและบันทึกข้อมูล:
    - ผู้ใช้เลือกรถผ่าน checkbox (เลือกได้ไม่เกิน 20 คัน)
    - คลิกแถวเพื่อเลือก/ยกเลิก checkbox
    - ฟังก์ชัน sendMultiple() เก็บข้อมูลรถที่เลือก
    - ส่งข้อมูลไป request_repair_grease_form.php เพื่อบันทึก

6. การแสดงผลแบบสี:
    - สีดำ: ปกติ
    - สีแดง: เกินกำหนดหรือควรอัดทันที

7. ฟังก์ชันหลัก:
    - selectcustomer(): เลือกบริษัทและรีเฟรชข้อมูล
    - sendMultiple(): เก็บข้อมูลรถที่เลือกและส่งไปบันทึก
    - showSendForm(): แสดงฟอร์มบันทึกข้อมูล
    - Event listeners: จัดการการคลิกแถวเพื่อเลือก checkbox
*/ -->
<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
    
    // echo "<pre>";
    // print_r($_GET);
    // print_r($CTM_GROUP);
    // echo "</pre>";

    $SESSION_AREA = $_SESSION["AD_AREA"];
?>
<html>
<head>
    <style>
        .vehicle-checkbox {
            width: 13px;
            height: 13px;
            cursor: pointer;
            transform: scale(1.5);
        }
        
        /* หรือสามารถใช้วิธีนี้เพื่อขนาดที่ใหญ่กว่า */
        .vehicle-checkbox-large {
            width: 25px;
            height: 25px;
            cursor: pointer;
        }
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
    <script>                
        function selectcustomer(a1){
            if (a1=='AMT') {
                var ctmcomcode = a1;
            }else if(a1=='GW'){
                var ctmcomcode = a1;
            }else{
                var ctmcomcode = $("#CTM_COMCODE").val();
                if(!ctmcomcode){
                    ctmcomcode = $('input[type=checkbox][name=CTM_COMCODE]:checked').val();
                }
            }
            var getctmcomcode = "?ctmcomcode="+ctmcomcode;
            loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_grease.php'+getctmcomcode);
        }  

        function sendMultiple() {
            var table = $('#datatable').DataTable();
            var selectedVehicles = [];
            var selectedVehicleData = [];
            var checkboxes = table.$('input.vehicle-checkbox:checked'); // ดึงทุกหน้า

            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกรถที่ต้องการบันทึกข้อมูล',
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            if (checkboxes.length > 20) {
                Swal.fire({
                    icon: 'warning',
                    title: 'เลือกได้ไม่เกิน 20 คัน',
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }

            checkboxes.each(function() {
                var checkbox = this;
                var row = $(checkbox).closest('tr')[0];
                var vehicleId = checkbox.value;
                var vehicleRegis    = row.cells[1].textContent.trim();
                var vehicleName     = row.cells[2].textContent.trim();
                var vehicleKilo     = row.cells[3].textContent.trim();
                var vehicleMileage  = row.cells[4].textContent.trim().replace(/,/g, '');
                var count_display   = row.cells[8].textContent.trim();
                var count_parts     = count_display.split('/');
                var count_display_1 = count_parts[0];
                var count_display_2 = count_parts[1];

                selectedVehicles.push(vehicleId);
                selectedVehicleData.push({
                    id: vehicleId,
                    regis: vehicleRegis,
                    name: vehicleName,
                    kilo: vehicleKilo,
                    mileage: vehicleMileage,
                    count_display: count_display,
                    count_display_1: count_display_1,
                    count_display_2: count_display_2
                });
            });

            showSendForm(selectedVehicleData);
        }
        
        // function showSendForm(selectedVehicleData) {
        //     var ctmcomcode = '<?php echo isset($_GET["ctmcomcode"]) ? $_GET["ctmcomcode"] : ""; ?>';
            
        //     var url = '<?=$path?>views_amt/request_repair/request_repair_grease_form.php';
        //     var params = '?vehicle_data=' + encodeURIComponent(JSON.stringify(selectedVehicleData)) + 
        //                 '&ctmcomcode=' + encodeURIComponent(ctmcomcode) +
        //                 '&proc=add_multiple';
            
        //     ajaxPopup2(url + params, "add", "1=1", "700", "500", "บันทึกอัดจารบี");
        // }
        function showSendForm(selectedVehicleData) {
            var ctmcomcode = '<?php echo isset($_GET["ctmcomcode"]) ? $_GET["ctmcomcode"] : ""; ?>';
            var url = '<?=$path?>views_amt/request_repair/request_repair_grease_form.php';
            var data = {
                vehicle_data: JSON.stringify(selectedVehicleData),
                ctmcomcode: ctmcomcode,
                proc: 'add_multiple'
            };
            ajaxPopup2post(url, "add", data, "700", "500", "บันทึกอัดจารบี", "POST");
        }
        document.addEventListener('DOMContentLoaded', function() {
            var tbody = document.querySelector('#datatable tbody');
            if (tbody) {
                tbody.addEventListener('click', function(e) {
                    var tr = e.target.closest('tr');
                    if (!tr) return;
                    if (e.target.type === 'checkbox') return;
                    var checkbox = tr.querySelector('.vehicle-checkbox');
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                    }
                });
            }
        });
        $('#datatable tbody').on('click', 'tr', function(e) {
            if (e.target.type === 'checkbox') return;
            var checkbox = $(this).find('.vehicle-checkbox')[0];
            if (checkbox) checkbox.checked = !checkbox.checked;
        });
    </script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
    <tr class="TOP">
        <td class="LEFT"></td>
        <td class="CENTER">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="25" valign="middle" class=""><img src="../images/car_repair.png" width="48" height="48"></td>
                    <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;บันทึกอัดจารบี</h3></td>
                    <td width="617" align="right" valign="bottom" class="" nowrap></td>
                </tr>
            </table>
        </td>
        <td class="RIGHT"></td>
    </tr>
    <tr class="CENTER">
        <td class="LEFT"></td>
        <td class="CENTER" align="center">    
            <br>    
            <table>
                <tbody>
                    <tr style="cursor:pointer" height="40px" align="center">       
                        <td width="20%" align="center">
                            <div class="input-control select">                    
                                <?php
                                    $wh="AND CTM_COMCODE IN('RKR','RKS','RKL','RCC','RRC','RATC')";
                                    $stmt_selcus = "SELECT * FROM CUSTOMER WHERE NOT CTM_STATUS IN ('D','N') AND CTM_GROUP='cusin' $wh ORDER BY CTM_COMCODE ASC";
                                    $query_selcus = sqlsrv_query($conn, $stmt_selcus);
                                ?>
                                <select class="time" onFocus="$(this).select();" style="width: 100%;" name="CTM_COMCODE" id="CTM_COMCODE" required>
                                    <option value disabled selected>-------เลือกบริษัท-------</option>
                                    <?php while($result_selcus = sqlsrv_fetch_array($query_selcus)): 
                                        $CTM_COMCODE=$result_selcus['CTM_COMCODE'];
                                        $CTM_GROUP1=$result_selcus["CTM_GROUP"];
                                        
                                        $sql_count = "SELECT COUNT(VEHICLEINFOID) COUNTCTMID FROM vwVEHICLEINFO WHERE AFFCOMPANY = ? AND NOT VEHICLEGROUPDESC = 'Car Pool' AND ACTIVESTATUS = '1'";
                                        $params_count = array($CTM_COMCODE);	
                                        $query_count = sqlsrv_query( $conn, $sql_count, $params_count);	
                                        $result_count = sqlsrv_fetch_array($query_count, SQLSRV_FETCH_ASSOC);
                                        $COUNTCTMID=$result_count["COUNTCTMID"];
                                        if($COUNTCTMID>0){                                                                           
                                    ?>
                                        <option value="<?=$result_selcus['CTM_COMCODE']?>" <?php if($_GET['ctmcomcode']==$result_selcus['CTM_COMCODE']){echo "selected";} ?>><?=$result_selcus['CTM_COMCODE']?> - <?=$result_selcus['CTM_NAMETH']?></option>
                                    <?php  } endwhile; ?>
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
                            <label onclick="selectcustomer('AMT')" style="cursor:pointer;">
                                <input type="checkbox" name="CTM_COMCODE" id="CTM_COMCODE" class="largerCheckbox1" value="AMT" <?php if($_GET['ctmcomcode']=='AMT'){echo 'checked';}?> >&ensp;<font size="5">AMT</font>
                            </label>&emsp;&emsp;
                            <label onclick="selectcustomer('GW')" style="cursor:pointer;">
                                <input type="checkbox" name="CTM_COMCODE" id="CTM_COMCODE" class="largerCheckbox1" value="GW" <?php if($_GET['ctmcomcode']=='GW'){echo 'checked';}?> >&ensp;<font size="5">GW</font>
                            </label>
                        </td>
                        <td align="center"></td>
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
                            <!-- <th rowspan="2" align="center" width="5%">ลำดับ</th> -->
                            <th rowspan="2" align="center" width="5%">จัดการ</th>
                            <th colspan="4" align="center" width="35%" class="ui-state-default">ข้อมูลรถ</th>
                            <!-- <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th> -->
                            <!-- <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th> -->
                            <!-- <th colspan="1" align="center" width="10%" class="ui-state-default">มาตราฐานกำหนดอัดจารบี</th> -->
                            <th colspan="3" align="center" width="25%" class="ui-state-default">อัดจารบีล่าสุด</th>
                            <th colspan="2" align="center" width="25%" class="ui-state-default">อัดจารบีครั้งถัดไป</th>
                            <th rowspan="2" align="center" width="10%">ถึงกำหนด / <br>เกินระยะ(วัน)</th>
                            <!-- <th rowspan="2" align="center" width="15%">หมายเหตุ</th> -->
                        </tr>
                        <tr height="30">
                            <th align="center">ทะเบียน</th>
                            <th align="center">ชื่อรถ</th>  
                            <th align="center">กม./วัน</th>
                            <th align="center">เลขไมล์ล่าสุด</th>  
                            <!-- <th align="center">ประเภท</th>   -->
                            <!-- <th align="center">ทะเบียน</th> -->
                            <!-- <th align="center">ชื่อรถ</th>   -->
                            <th align="center">รอบ</th>  
                            <th align="center">วันที่</th>  
                            <th align="center">เลขไมล์</th>  
                            <th align="center">รอบ</th>  
                            <th align="center">วันที่</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($_GET['ctmcomcode'])){
                            if(($_GET['ctmcomcode']!="AMT")&&($_GET['ctmcomcode']!="GW")&&($_GET['ctmcomcode']!="")){
                                $wh="AND ACTIVESTATUS = '1' AND AFFCOMPANY LIKE '%".$_GET['ctmcomcode']."%'";
                                // echo "1";
                            }else if($_GET['ctmcomcode']=="AMT"){
                                $wh="AND ACTIVESTATUS = '1' AND AFFCOMPANY IN('RKR','RKS','RKL')";
                                // echo "2";
                            }else if(($_GET['ctmcomcode']=="GW")){
                                $wh="AND ACTIVESTATUS = '1' AND AFFCOMPANY IN('RCC','RRC','RATC')";
                                // echo "3";
                            }else {
                                $wh="";
                                // echo "4";
                            }
                        }else{
                            $wh="null";
                        }
                            $sql_vehicleinfo = "SELECT * FROM vwVEHICLEINFO 
                                LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
                                LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
                                WHERE VEHICLEGROUPDESC = 'Transport' AND NOT VEHICLEGROUPCODE = 'VG-1403-0755' ".$wh." AND REGISTYPE = 'ทะเบียนหัว' ORDER BY REGISTYPE ASC, VEHICLEREGISNUMBER ASC";
                            $query_vehicleinfo = sqlsrv_query($conn, $sql_vehicleinfo);
                            $no=0;
                            while($result_vehicleinfo = sqlsrv_fetch_array($query_vehicleinfo, SQLSRV_FETCH_ASSOC)){	
                                $no++;

                                $VEHICLEREGISNUMBER=$result_vehicleinfo['VEHICLEREGISNUMBER'];
                                $THAINAME=$result_vehicleinfo['THAINAME'];
                                $REGISTYPE=$result_vehicleinfo['REGISTYPE'];
                                $VHCCT_KILOFORDAY=$result_vehicleinfo['VHCCT_KILOFORDAY'];

                                // ดึงข้อมูลจากตาราง REPAIR_GREASE
                                $sql_grease = "SELECT TOP 1 * FROM [dbo].[REPAIR_GREASE] WHERE (RPG_VHCRG = '$VEHICLEREGISNUMBER' OR RPG_VHCRGNM = '$THAINAME') ORDER BY RPG_CREATEDATE DESC";
                                $query_grease = sqlsrv_query($conn, $sql_grease);
                                $result_grease = sqlsrv_fetch_array($query_grease, SQLSRV_FETCH_ASSOC);
                                    $RPG_GROUP = $result_grease['RPG_GROUP'];
                                    $RPG_VHCRG = $result_grease['RPG_VHCRG'];
                                    $RPG_VHCRGNM = $result_grease['RPG_VHCRGNM'];
                                    $RPG_CREATEDATE = $result_grease['RPG_CREATEDATE'];
                                    $RPG_COUNT = $result_grease['RPG_COUNT'];
                                    $RPG_LCD = $result_grease['RPG_LCD'];
                                    $RPG_LASTMILEAGE = $result_grease['RPG_LASTMILEAGE'];

                                if($_GET['ctmcomcode']=="AMT"||$_GET['ctmcomcode']=="RKS"||$_GET['ctmcomcode']=="RKR"||$_GET['ctmcomcode']=="RKL"){     
                                    $field="VEHICLEREGISNUMBER = '$VEHICLEREGISNUMBER'";
                                }else if($_GET['ctmcomcode']=="GW"||$_GET['ctmcomcode']=="RRC"||$_GET['ctmcomcode']=="RCC"||$_GET['ctmcomcode']=="RATC"){
                                    $explodes = explode('(', $THAINAME);
                                    $THAINAME = $explodes[0];
                                    $field="THAINAME = '$THAINAME'";
                                }else{
                                    $field="VEHICLEREGISNUMBER = '$VEHICLEREGISNUMBER'";
                                }
                                
                                $sql_mileage = "SELECT TOP 1 MAXMILEAGENUMBER,VEHICLEREGISNUMBER,THAINAME,CREATEDATE FROM TEMP_MILEAGE WHERE MAXMILEAGENUMBER > '0' AND $field ORDER BY CREATEDATE DESC";
                                $params_mileage = array();
                                $query_mileage = sqlsrv_query($conn, $sql_mileage, $params_mileage);
                                $result_mileage = sqlsrv_fetch_array($query_mileage, SQLSRV_FETCH_ASSOC); 
                                
                                if(isset($result_mileage['MAXMILEAGENUMBER'])){
                                    if($result_mileage['MAXMILEAGENUMBER']>1000000){
                                        $MAXMILEAGENUMBER = $result_mileage['MAXMILEAGENUMBER']-1000000;
                                    }else{
                                        $MAXMILEAGENUMBER = $result_mileage['MAXMILEAGENUMBER'];
                                    }
                                }else{
                                    $MAXMILEAGENUMBER = 0;
                                }
                                $MAXCUT = SUBSTR($MAXMILEAGENUMBER,-4);
                                if($MAXCUT > 2000){
                                    $MAXMILEAGENUMBER2000=$MAXMILEAGENUMBER;
                                }else{
                                    if($_GET['ctmcomcode']=="AMT"||$_GET['ctmcomcode']=="RKS"||$_GET['ctmcomcode']=="RKR"||$_GET['ctmcomcode']=="RKL"){     
                                        $MAXMILEAGENUMBER2000=$MAXMILEAGENUMBER-1000;
                                    }else if($_GET['ctmcomcode']=="GW"||$_GET['ctmcomcode']=="RRC"||$_GET['ctmcomcode']=="RCC"||$_GET['ctmcomcode']=="RATC"){
                                        $MAXMILEAGENUMBER2000=$MAXMILEAGENUMBER-1000;
                                    }
                                }

                                $font = "black";

                                // วันที่ปัจจุบัน
                                // $lactchangedate = date("Y-m-d");    
                                // แสดงผลข้อมูลอะไหล่ที่ดึงมา   
                                // echo '-'.$result_grease['RPG_LCD']."<br>";
                                if(isset($RPG_LCD) && $RPG_LCD != null){
                                    // ถ้ามีวันที่อัดจารบีล่าสุด
                                    $lactchangedate = $RPG_LCD;  
                                    // แปลงวันที่ไว้ใช้ในการแสดงผล                        
                                    $lactchangedate_display = date("d/m/Y", strtotime($lactchangedate));
                                } else {
                                    // ถ้าไม่มีวันที่อัดจารบีล่าสุด ให้ใช้วันที่ปัจจุบัน
                                    $lactchangedate = '';
                                    // แปลงวันที่ไว้ใช้ในการแสดงผล                        
                                    $lactchangedate_display = '';
                                }

                                // เลขไมล์ล่าสุดที่อัดจารบี
                                if(isset($RPG_LASTMILEAGE) && $RPG_LASTMILEAGE != null && $RPG_LASTMILEAGE > 0){
                                    $display_lastmileage_change = $RPG_LASTMILEAGE;
                                } else {
                                    $display_lastmileage_change = '';
                                }

                                // รอบล่าสุด
                                if(isset($RPG_COUNT) && $RPG_COUNT != null && $RPG_COUNT > 0 && isset($RPG_GROUP) && $RPG_GROUP != null && $RPG_GROUP != '') {
                                    $step_in_cycle = ($RPG_COUNT % 5 == 0) ? 5 : ($RPG_COUNT % 5);
                                    // แสดง 5/5000 เฉพาะรอบที่ 5 แต่ให้ใช้ 1000 ในการคำนวณ
                                    $grease_round_display = ($step_in_cycle == 5) ? 5000 : 1000;
                                    $grease_round_calc = 1000; // ใช้ 1000 ในการคำนวณทุกกรณี
                                    $count_display = $step_in_cycle . '/' . $grease_round_display;
                                } else {
                                    $count_display = '';
                                    $grease_round_calc = 1000;
                                }

                                // รอบถัดไป
                                if(isset($RPG_COUNT) && $RPG_COUNT != null && $RPG_COUNT > 0) {
                                    $next_count = $RPG_COUNT + 1;
                                    $next_step_in_cycle = ($next_count % 5 == 0) ? 5 : ($next_count % 5);
                                    $next_grease_round_display = ($next_step_in_cycle == 5) ? 5000 : 1000;
                                    $next_grease_round_calc = 1000; // ใช้ 1000 ในการคำนวณทุกกรณี
                                    $next_count_display = $next_step_in_cycle . '/' . $next_grease_round_display;
                                } else {
                                    $next_grease_round_calc = 1000;
                                    $next_count_display = '1/1000';
                                }

                                // กรณีไม่มีข้อมูลการอัดจารบีล่าสุด
                                if(
                                    !isset($display_lastmileage_change) || $display_lastmileage_change == '' || $display_lastmileage_change == 0
                                ) {
                                    $due_date_display = date("d/m/Y"); // วันปัจจุบัน
                                    $days_to_next_display = "<span style='color:red;'>ควรอัดจารบีทันที</span>";
                                    $font = "red";
                                } else if(
                                    isset($MAXMILEAGENUMBER) && $MAXMILEAGENUMBER > 0 &&
                                    isset($display_lastmileage_change) && $display_lastmileage_change > 0 &&
                                    isset($VHCCT_KILOFORDAY) && $VHCCT_KILOFORDAY > 0
                                ) {
                                    // ระยะที่ใช้ไปแล้ว
                                    $used_distance = $MAXMILEAGENUMBER - $display_lastmileage_change;
                                    // ระยะที่เหลือถึงรอบถัดไป (อาจติดลบ)
                                    $distance_to_next = $next_grease_round_calc - $used_distance;
                                    // จำนวนวันที่เหลือหรือเกิน
                                    $days_to_next = ceil(abs($distance_to_next) / $VHCCT_KILOFORDAY);

                                    // วันที่ครบกำหนด
                                    $today = date('Y-m-d');
                                    $due_date = date('Y-m-d', strtotime("+$days_to_next days", strtotime($today)));
                                    $due_date_display = date("d/m/Y", strtotime($due_date));

                                    // เช็คสถานะ
                                    if($distance_to_next > 0) {
                                        $days_to_next_display = "อีก $days_to_next วัน";
                                        $font = "black";
                                    } else if($distance_to_next == 0) {
                                        $days_to_next_display = "<span style='color:red;'>ควรอัดจารบีทันที</span>";
                                        $font = "red";
                                    } else {
                                        // ถ้าเกินกำหนด
                                        $days_to_next_display = "<span style='color:red;'>เกิน $days_to_next วัน</span>";
                                        $font = "red";
                                    }
                                } else {
                                    $due_date_display = '-';
                                    $days_to_next_display = '-';
                                }
                                
                                // แสดงหมายเหตุ
                                $remarkdisplay = $result_grease['RPG_REMARK'];  
                        ?>
                        <tr height="25px" align="center" id="<?php print $result_vehicleinfo['VEHICLEINFOID']; ?>" <?=$class_tr?>>
                            <!-- <td ><?php print "$no.";?></td> -->
                            <td >
                                <input type="checkbox" name="selected_vehicles[]" value="<?php print $result_vehicleinfo['VEHICLEINFOID']; ?>" class="vehicle-checkbox">
                            </td>
                            <td align="center"><font color="<?=$font?>"><?= $VEHICLEREGISNUMBER ?></font></td>
                            <td align="left"><font color="<?=$font?>"><?= $THAINAME ?></font></td>
                            <td align="center"><font color="<?=$font?>"><?= number_format($VHCCT_KILOFORDAY) ?></font></td>
                            <!-- <td align="center"><font color="<?=$font?>"><?= $REGISTYPE ?></font></td> -->
                            <td align="center"><font color="<?=$font?>"><?= number_format($MAXMILEAGENUMBER); ?></font></td>
                            <td align="center"><font color="<?=$font?>"><?= $count_display; ?></font></td>
                            <td align="center"><font color="<?=$font?>"><?= $lactchangedate_display ?></font></td>
                            <td align="center"><font color="<?=$font?>"><?= number_format($display_lastmileage_change); ?></font></td>
                            <td align="center"><font color="<?=$font?>"><?= $next_count_display; ?></font></td>
                            <td align="center"><font color="<?=$font?>"><?= $due_date_display ?></font></td>
                            <td align="center"><font color="<?=$font?>"><?= $days_to_next_display ?></font></td>
                            <!-- <td align="left"><font color="<?=$font?>"><?= $remarkdisplay ?></font></td> -->
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
                <input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_grease.php');">
                <input type="button" class="bg-color-green font-white" value="บันทึกข้อมูล" onclick="sendMultiple()" style="margin-left: 10px;width: 150px;">
            </center>
        </td>
        <td class="RIGHT">&nbsp;</td>
    </tr>
</table>
</body>
</html>