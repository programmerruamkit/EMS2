<?php
    session_name("EMS");
    session_start();
    $path = "../";
    require($path . '../include/connect.php');
    // echo"<pre>";
    // print_r($_GET);
    // echo"</pre>";
    // exit();
    $SESSION_AREA = $_SESSION["AD_AREA"];
    if ($SESSION_AREA == "AMT") {
        $HREF = "../views_project/report_manage/report_spareparts_classification_amt_excel.php?group='+group+'&spareparts='+spareparts+'&status='+status+'";
    } else {
        $HREF = "../views_project/report_manage/report_spareparts_classification_gw_excel.php?group='+group+'&spareparts='+spareparts+'&status='+status+'";
    }
    $getday = $GETDAYEN;

    ?>
    <html>

    <head>
        <meta charset="utf-8">
        <script type="text/javascript">
            $(document).ready(function(e) {
                $('.datepic').datetimepicker({
                    timepicker: false,
                    lang: 'th',
                    format: 'd/m/Y',
                    closeOnDateSelect: true
                });
            });

            function excel_equipment() {
                var group = document.getElementById('GROUP').value;
                var spareparts = document.getElementById('spareparts').value;
                var status = document.getElementById('status').value;
                
                // ตรวจสอบว่าเลือกทั้งสองค่าแล้ว
                if(group == null || group == ''){
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณาเลือกบริษัท',
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

                var href = '<?= $HREF ?>';
                href = href.replace("'+group+'", group);
                href = href.replace("'+spareparts+'", spareparts);
                href = href.replace("'+status+'", status);
                window.open(href, '_blank');
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
                            <td width="25" valign="middle" class=""><img src="https://img2.pic.in.th/pic/reports-icon48.png" width="48" height="48"></td>
                            <td width="419" height="10%" valign="bottom" class="">
                                <h3>&nbsp;&nbsp;รายงานแยกประเภทอะไหล่ (<?= $SESSION_AREA ?>)</h3>
                            </td>
                            <td width="617" align="right" valign="bottom" class="" nowrap>
                                <div class="toolbar">
                                    <!-- <button class="bg-color-blue" style="padding-top:8px;" title="New" id="button_new"><i class='icon-plus icon-large'></i></button> -->
                                    <!-- <button class="bg-color-yellow" style="padding-top:8px;" title="Edit" id="button_edit"><i class='icon-pencil icon-large'></i></button> -->
                                    <!-- <button class="bg-color-red" style="padding-top:8px;" title="Del" id="button_delete"><i class="icon-cancel icon-large"></i></button> -->
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="RIGHT"></td>
            </tr>
            <tr class="CENTER">
                <td class="LEFT"></td>
                <td class="CENTER" align="center">
                    <br>
                    <!-- <h3>รายงาน รายละเอียดประมาณการรายได้</h3> -->
                    <table>
                        <tbody>
                            <tr align="center">
                                <td width="10%" align="right"><strong>เงื่อนไขการค้นหา :</strong></td>
                                <td width="1%" align="right">&nbsp;</td>   
                                <td width="25%" align="left">
                                    <div class="input-control select">เลือกบริษัท       
                                        <select class="time" onFocus="$(this).select();" style="width: 100%;" name="GROUP" id="GROUP" required>
                                            <option disabled selected value="">โปรดเลือก</option>
                                            <option value="AMT" <?php if(isset($group)){if($group=='AMT'){echo 'selected';}}?>>AMT - พื้นที่อมตะ</option>
                                            <option value="GW" <?php if(isset($group)){if($group=='GW'){echo 'selected';}}?>>GW - พื้นที่เกตเวย์</option>
                                            <?php
                                                $sql_customer = "SELECT *,(SELECT COUNT(VEHICLEINFOID) COUNTCTMID FROM vwVEHICLEINFO WHERE AFFCOMPANY COLLATE Thai_CI_AI LIKE '%'+CUSTOMER.CTM_COMCODE+'%' AND NOT VEHICLEGROUPDESC = 'Car Pool' AND ACTIVESTATUS = '1') 
                                                FROM CUSTOMER WHERE NOT CTM_STATUS='D' AND CTM_GROUP='cusin'
                                                AND (SELECT COUNT(VEHICLEINFOID) COUNTCTMID FROM vwVEHICLEINFO WHERE AFFCOMPANY COLLATE Thai_CI_AI LIKE '%'+CUSTOMER.CTM_COMCODE+'%' AND NOT VEHICLEGROUPDESC = 'Car Pool' AND ACTIVESTATUS = '1') > 0";
                                                $query_customer = sqlsrv_query($conn, $sql_customer);
                                                while($result_customer = sqlsrv_fetch_array($query_customer, SQLSRV_FETCH_ASSOC)){ 
                                            ?>
                                                <option value="<?= $result_customer['CTM_ID'] ?>" <?php if(isset($group)){if($group==$result_customer['CTM_ID']){echo 'selected';}}?>><?= $result_customer['CTM_COMCODE'] ?> - <?= $result_customer['CTM_NAMETH'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                <td width="1%" align="right">&nbsp;</td>   
                                <td width="15%" align="left">
                                    <div class="input-control select">เลือกอะไหล่ที่ต้องการค้นหา   
                                        <select class="time" onFocus="$(this).select();" style="width: 100%;" name="spareparts" id="spareparts" required>
                                            <option disabled selected value="">โปรดเลือก</option>
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
                                        <button title="Excel" type="button" class="bg-color-green big" onclick="excel_equipment()">
                                            <font color="white" size="4"><i class="icon-file-excel icon-large"></i> Excel</font>
                                        </button>
                                    </div>
                                </td>
                                <td align="center"></td>
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
                        <input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?= $path ?>views_project/report_manage/report_spareparts_classification.php');">
                    </center>
                </td>
                <td class="RIGHT">&nbsp;</td>
            </tr>
        </table>
    </body>

    </html>