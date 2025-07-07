<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
    
    $SESSION_AREA = $_SESSION["AD_AREA"];
    
    $stmt_selsparename1 = "SELECT DISTINCT * FROM [dbo].[PROJECT_SPAREPART] a WHERE a.PJSPP_AREA = '$SESSION_AREA' AND a.PJSPP_NAME='".$_GET['pjsppname']."' ORDER BY a.PJSPP_ID ASC";
    $query_selsparename1= sqlsrv_query($conn, $stmt_selsparename1);
    $result_selsparename1 = sqlsrv_fetch_array($query_selsparename1, SQLSRV_FETCH_ASSOC);   

    if(isset($_GET['pjsppname'])){
        $PJSPP_NAME=$_GET['pjsppname'];
        $PJSPP_CODENAME = $result_selsparename1['PJSPP_CODENAME'];
        $PJSPP_EXPIRE_YEAR = $result_selsparename1['PJSPP_EXPIRE_YEAR'];
    }else{
        $PJSPP_NAME='-- เลือกหมวดหมู่ --'; 
        $PJSPP_CODENAME = '';
        $PJSPP_EXPIRE_YEAR = '';
    }
?>
<html>
<head>
    <script>        
        function selectcustomer(){
            var pjsppname = $("#PJSPP_NAME").val();
            var ctmcomcode = $("#CTM_COMCODE").val();
            if(pjsppname == null || pjsppname == ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกหมวดหมู่อะไหล่',
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            if(ctmcomcode == null || ctmcomcode == ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกบริษัท',
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            var getpjsppname = "?pjsppname="+pjsppname+"&ctmcomcode="+ctmcomcode;
            loadViewdetail('<?=$path?>views_project/request_repair/request_repair_sparepart_form.php'+getpjsppname);
        }
        $(document).ready(function(e) {	   
            $("#button_new").click(function(){
                ajaxPopup2("<?=$path?>views_project/request_repair/request_repair_sparepart_form.php","add","1=1","1350","670","เพิ่มใบแจ้งซ่อม");
            });
        });        

        function sendMultiple() {
            var selectedVehicles = [];
            var selectedVehicleData = [];
            var checkboxes = document.querySelectorAll('.vehicle-checkbox:checked');
            
            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกรถที่ต้องการบันทึกข้อมูล',
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            
            checkboxes.forEach(function(checkbox) {
                var vehicleId = checkbox.value;
                var row = checkbox.closest('tr');
                var vehicleRegis = row.cells[2].textContent.trim();
                var vehicleName = row.cells[3].textContent.trim();
                var vehicleType = row.cells[4].textContent.trim();
                
                selectedVehicles.push(vehicleId);
                selectedVehicleData.push({
                    id: vehicleId,
                    regis: vehicleRegis,
                    name: vehicleName,
                    type: vehicleType
                });
            });
            
            showSendForm(selectedVehicleData);
        }
        
        function showSendForm(selectedVehicleData) {
            var pjsppname = '<?php echo isset($_GET["pjsppname"]) ? $_GET["pjsppname"] : ""; ?>';
            var pjsppcodename = '<?php echo isset($PJSPP_CODENAME) ? $PJSPP_CODENAME : ""; ?>';
            var ctmcomcode = '<?php echo isset($_GET["ctmcomcode"]) ? $_GET["ctmcomcode"] : ""; ?>';
            
            var url = '<?=$path?>views_project/request_repair/request_repair_pm_multiple_form.php';
            var params = '?vehicle_data=' + encodeURIComponent(JSON.stringify(selectedVehicleData)) + 
                        '&pjsppname=' + encodeURIComponent(pjsppname) + 
                        '&ctmcomcode=' + encodeURIComponent(ctmcomcode) +
                        '&pjsppcodename=' + encodeURIComponent(pjsppcodename) +
                        '&proc=add_multiple';
            
            ajaxPopup2(url + params, "add", "1=1", "900", "500", "บันทึกข้อมูลการเปลี่ยนอะไหล่");
        }
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
                    <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;บันทึกเปลี่ยนอะไหล่</h3></td>
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
                                <select class="time" onFocus="$(this).select();" style="width: 100%;" name="PJSPP_NAME" id="PJSPP_NAME" required>
                                    <option value disabled selected>-------เลือกหมวดหมู่-------</option>
                                    <?php   $stmt_selsparename = "SELECT DISTINCT * FROM [dbo].[PROJECT_SPAREPART] a WHERE a.PJSPP_AREA = '$SESSION_AREA' ORDER BY a.PJSPP_ID ASC";
                                            $query_selsparename = sqlsrv_query($conn, $stmt_selsparename);
                                            while($result_selsparename = sqlsrv_fetch_array($query_selsparename)): ?>
                                        <option value="<?=$result_selsparename['PJSPP_NAME']?>" <?php if($_GET['pjsppname']==$result_selsparename['PJSPP_NAME']){echo "selected";} ?>><?=$result_selsparename['PJSPP_NAME']?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </td>   
                        <td width="5%" align="center">|</td>         
                        <td width="30%" align="center">
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
                        <td align="center"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>         
            <form id="form1" name="form1" method="post" action="#">
                <h3>ชื่อรายการอะไหล่: <?php if(isset($_GET['pjsppname'])){echo $_GET['pjsppname'];}else{echo $PJSPP_NAME;} ?></h3>
                <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
                    <thead>
                        <tr height="30">
                            <th rowspan="2" align="center" width="5%">ลำดับ</th>
                            <th rowspan="2" align="center" width="10%">จัดการ</th>
                            <th colspan="3" align="center" width="25%" class="ui-state-default">ข้อมูลรถ</th>
                            <th colspan="1" align="center" width="15%" class="ui-state-default">มาตราฐานกำหนดเปลี่ยน</th>
                            <th rowspan="2" align="center" width="15%">วันที่เปลี่ยนล่าสุด</th>
                            <th rowspan="2" align="center" width="15%">กำหนดเปลี่ยนครั้งถัดไป</th>
                            <th rowspan="2" align="center" width="15%">ถึงกำหนด / <br>เกินระยะ(วัน)</th>
                        </tr>
                        <tr height="30">
                            <th align="center">ทะเบียน</th>
                            <th align="center">ชื่อรถ</th>  
                            <th align="center">ประเภท</th>  
                            <th align="center">ปี</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($_GET['ctmcomcode'])){
                            $wh="AND ACTIVESTATUS = '1' AND AFFCOMPANY LIKE '%".$_GET['ctmcomcode']."%'";
                        }else{
                            $wh="null";
                        }
                            $sql_vehicleinfo = "SELECT * FROM vwVEHICLEINFO 
                                LEFT JOIN VEHICLECARTYPEMATCHGROUP ON VHCTMG_VEHICLEREGISNUMBER = VEHICLEREGISNUMBER COLLATE Thai_CI_AI
                                LEFT JOIN VEHICLECARTYPE ON VEHICLECARTYPE.VHCCT_ID = VEHICLECARTYPEMATCHGROUP.VHCCT_ID
                                WHERE VEHICLEGROUPDESC = 'Transport' AND NOT VEHICLEGROUPCODE = 'VG-1403-0755' ".$wh." ORDER BY REGISTYPE ASC, VEHICLEREGISNUMBER ASC";
                            $query_vehicleinfo = sqlsrv_query($conn, $sql_vehicleinfo);
                            $no=0;
                            while($result_vehicleinfo = sqlsrv_fetch_array($query_vehicleinfo, SQLSRV_FETCH_ASSOC)){	
                                $no++;

                                $VEHICLEREGISNUMBER=$result_vehicleinfo['VEHICLEREGISNUMBER'];
                                $THAINAME=$result_vehicleinfo['THAINAME'];
                                $REGISTYPE=$result_vehicleinfo['REGISTYPE'];

                                $valid_codes = ["01","02","03","04","05","06","07","08"];
                                if (in_array($PJSPP_CODENAME, $valid_codes)) {
                                    $table = "PROJECT_SPAREPART_".$PJSPP_CODENAME;
                                    $CODENAME = "PJSPP".$PJSPP_CODENAME."_CODENAME"; 
                                    $VHCRG = "PJSPP".$PJSPP_CODENAME."_VHCRG"; 
                                    $VHCRGNM = "PJSPP".$PJSPP_CODENAME."_VHCRGNM"; 
                                    $CREATEDATE = "PJSPP".$PJSPP_CODENAME."_CREATEDATE"; 
                                } else {
                                    $table = null; 
                                    $CODENAME = null;
                                    $VHCRG = null;
                                    $VHCRGNM = null;
                                    $CREATEDATE = null;
                                }

                                $sql_sparepart = "SELECT TOP 1 * FROM [dbo].[$table] WHERE $CODENAME = '$PJSPP_CODENAME' AND ($VHCRG = '$VEHICLEREGISNUMBER' OR $VHCRGNM = '$THAINAME') ORDER BY $CREATEDATE DESC";
                                $query_sparepart = sqlsrv_query($conn, $sql_sparepart);
                                $result_sparepart = sqlsrv_fetch_array($query_sparepart, SQLSRV_FETCH_ASSOC);

                                $font = "black";

                                if(isset($result_sparepart['PJSPP'.$PJSPP_CODENAME.'_LCD']) && $result_sparepart['PJSPP'.$PJSPP_CODENAME.'_LCD'] != null){
                                    $lactchangedate = $result_sparepart['PJSPP'.$PJSPP_CODENAME.'_LCD'];  
                                } else {
                                    $lactchangedate = '';
                                }

                                $lactchangedate_display = date("d/m/Y", strtotime($lactchangedate));
                                $expire_date_str = date("Y-m-d", strtotime("+$PJSPP_EXPIRE_YEAR year", strtotime($lactchangedate)));
                                $PJSPP_EXPIRE_DATE = date("d/m/Y", strtotime($expire_date_str));
                                $date1 = date_create(date("Y-m-d")); 
                                $date2 = date_create($expire_date_str);
                                $diff = date_diff($date1, $date2);
                                $day_diff = (int)$diff->format("%R%a");
                                
                                if ($day_diff < 0) {
                                    $font = "red"; 
                                } else {
                                    $font = "black";
                                }                            
                        ?>
                        <tr height="25px" align="center" id="<?php print $result_vehicleinfo['VEHICLEINFOID']; ?>" <?=$class_tr?>>
                            <td ><?php print "$no.";?></td>
                            <td >
                                <input type="checkbox" name="selected_vehicles[]" value="<?php print $result_vehicleinfo['VEHICLEINFOID']; ?>" class="vehicle-checkbox">
                            </td>
                            <td align="center"><font color="<?=$font?>"><?= $VEHICLEREGISNUMBER ?></font></td>
                            <td align="left"><font color="<?=$font?>"><?= $THAINAME ?></font></td>
                            <td align="center"><font color="<?=$font?>"><?= $REGISTYPE ?></font></td>
                            <td align="center"><font color="<?=$font?>"><?= $PJSPP_EXPIRE_YEAR ?></font></td>
                            <td align="center"><font color="<?=$font?>"><?= $lactchangedate_display ?></font></td>
                            <td align="center"><font color="<?=$font?>"><?= $PJSPP_EXPIRE_DATE ?></font></td>
                            <td align="center"><font color="<?=$font?>"><?= $day_diff ?></font></td>
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
                <input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>views_project/request_repair/request_repair_sparepart_form.php');">
                <input type="button" class="bg-color-green font-white" value="บันทึกข้อมูล" onclick="sendMultiple()" style="margin-left: 10px;">
            </center>
        </td>
        <td class="RIGHT">&nbsp;</td>
    </tr>
</table>
</body>
</html>