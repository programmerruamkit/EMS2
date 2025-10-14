<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');
    $SESSION_AREA = $_SESSION["AD_AREA"];
    if($SESSION_AREA=="AMT"){
        $HREF_EXCEL = "../views_project/report_manage/report_vehicle_summary_amt_excel.php?rg='+rg+'&spareparts='+spareparts+'&status='+status+'";
    }else{
        $HREF_EXCEL = "../views_project/report_manage/report_vehicle_summary_gw_excel.php?rg='+rg+'&spareparts='+spareparts+'&status='+status+'";
    }
    function autocomplete_regishead($CONDI) {
        $path = "../";   	
        require($path.'../include/connect.php');
        $data = "";
        $sql = "SELECT VEHICLEREGISNUMBER,THAINAME FROM vwVEHICLEINFO  WHERE $CONDI
        UNION	
        SELECT VEHICLEREGISNUMBER COLLATE Thai_CI_AI,THAINAME COLLATE Thai_CI_AI FROM vwVEHICLEINFO_OUT WHERE $CONDI";
        $query = sqlsrv_query($conn, $sql);
        while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $data .= "'".$result['VEHICLEREGISNUMBER']." / ".$result['THAINAME']."',";
        }
        return rtrim($data, ",");
    }
    $rqrp_regishead = autocomplete_regishead("ACTIVESTATUS = '1' AND VEHICLEGROUPDESC = 'Transport'");
?>
<html>
<head>
    <script type="text/javascript">
        $(document).ready(function(e) {
            $('.datepic').datetimepicker({
                timepicker:false,
                lang:'th',
                format:'d/m/Y',
                closeOnDateSelect: true
            });
        });
        function date1todate2(){
            document.getElementById('dateEnd').value = document.getElementById('dateStart').value;
        }
        function excel_reporthdms() {
            var rg = document.getElementById('VHCRGNM').value;
            var spareparts = document.getElementById('spareparts').value;
            var status = document.getElementById('status').value;

            // ตรวจสอบว่าเลือกครบทุกเงื่อนไข
            if(rg == null || rg == ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาระบุทะเบียนรถ/ชื่อรถ',
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            if(spareparts == null || spareparts == ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกอะไหล่ที่ต้องการค้นหา',
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            if(status == null || status == ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกสถานะ',
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }

            var href = '<?= $HREF_EXCEL ?>';
            href = href.replace("'+rg+'", rg);
            href = href.replace("'+spareparts+'", spareparts);
            href = href.replace("'+status+'", status);
            window.open(href, '_blank');
        }

        var source1 = [<?= $rqrp_regishead ?>];
        AutoCompleteNormal("VHCRGNM", source1); 
    </script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25" valign="middle" class=""><img src="https://img2.pic.in.th/pic/reports-icon48.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;รายงานสรุปรายคัน</h3></td>
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
                <tr align="center">
                    <td width="10%" align="right"><strong>เงื่อนไขการค้นหา :</strong></td>
                    <td width="1%" align="right">&nbsp;</td>    
                    <td width="16%" align="left">
                        <div class="row input-control">ทะเบียนรถ/ชื่อรถ
                            <input type="text" name="VHCRGNM" id="VHCRGNM" placeholder="ระบุทะเบียนรถ/ชื่อรถ" value="<?php if(isset($rg)){echo $rg;}?>" class="time" autocomplete="off">
                        </div>
                    </td>
                    <td width="1%" align="right">&nbsp;</td>   
                    <td width="15%" align="left">
                        <div class="input-control select">เลือกอะไหล่ที่ต้องการค้นหา   
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="spareparts" id="spareparts" required>
                                <option disabled selected value="">โปรดเลือก</option>
                                <option value="ALL" <?php if(isset($spareparts)){if($spareparts=='ALL'){echo 'selected';}}?>>ทั้งหมด</option>
                                <?php
                                    $AREA = $_SESSION["AD_AREA"];
                                    $sql_sparepart = "SELECT PJSPP_ID, PJSPP_CODENAME, PJSPP_CODE, PJSPP_NAME FROM PROJECT_SPAREPART WHERE PJSPP_AREA = '$AREA' AND PJSPP_STATUS <> 'D' ORDER BY PJSPP_CODENAME ASC";
                                    $query_sparepart = sqlsrv_query($conn, $sql_sparepart);
                                    while($result_sparepart = sqlsrv_fetch_array($query_sparepart, SQLSRV_FETCH_ASSOC)){
                                        $selected = (isset($spareparts) && $spareparts == $result_sparepart['PJSPP_CODENAME']) ? 'selected' : '';
                                        echo '<option value="'.$result_sparepart['PJSPP_CODENAME'].'" '.$selected.'>'.$result_sparepart['PJSPP_NAME'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </td>
                    <td width="1%" align="right">&nbsp;</td>   
                    <td width="10%" align="left">
                        <div class="input-control select">เลือกสถานะ   
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="status" id="status" required>
                                <option disabled selected value="">โปรดเลือก</option>
                                <option value="due_soon" <?php if(isset($status)){if($status=='due_soon'){echo 'selected';}}?>>ถึงกำหนดเปลี่ยน</option>
                                <option value="approaching" <?php if(isset($status)){if($status=='approaching'){echo 'selected';}}?>>ใกล้ถึงกำหนด</option>
                                <option value="overdue" <?php if(isset($status)){if($status=='overdue'){echo 'selected';}}?>>เกินกำหนด</option>
                            </select>
                        </div>
                    </td>
                    <td width="1%" align="right">&nbsp;</td>   
                    <td width="25%" align="left">
                        <div class="row input-control"><br>
                            <button title="Excel" type="button" class="bg-color-green big" onclick="excel_reporthdms()">
                                <font color="white" size="4"><i class="icon-file-excel icon-large"></i> Excel</font>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table> 
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
        <center>
            <input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?= $path ?>views_project/report_manage/report_vehicle_summary.php');">
        </center>
    </td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>