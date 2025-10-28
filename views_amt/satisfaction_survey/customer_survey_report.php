<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	$SESSION_AREA=$_SESSION["AD_AREA"];

    // รับค่าจากฟอร์มค้นหา
        $getsurvey = isset($_GET['survey']) ? $_GET['survey'] : '';
        $getselectdaystart = isset($_GET['dateStart']) ? $_GET['dateStart'] : '';
        $getselectdayend = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : '';
        $level = isset($_GET['satisfaction_level']) ? $_GET['satisfaction_level'] : '';
        // echo "Survey: " . $getsurvey . ", Date Start: " . $getselectdaystart . ", Date End: " . $getselectdayend . ", Level: " . $level . "<br>";

	// ดึงสถิติโดยรวม
        $stmt_stats = "SELECT 
            COUNT(*) as total_responses,
            COUNT(DISTINCT SR_REPAIR_ID) as unique_repairs,
            AVG(CAST(sa.SA_RATING as FLOAT)) as avg_rating,
            MIN(sr.SR_CREATED_DATE) as first_response,
            MAX(sr.SR_CREATED_DATE) as last_response
        FROM SURVEY_RESPONSES sr
        LEFT JOIN SURVEY_ANSWERS sa ON sr.SR_ID = sa.SR_ID AND sa.SA_STATUS = '1'
        LEFT JOIN SURVEY_MAIN sm ON sm.SM_ID = sr.SM_ID
        WHERE sm.SM_AREA = '$SESSION_AREA' ";
        // เพิ่มเงื่อนไขการค้นหาตามฟอร์ม
        if (!empty($getsurvey)) {
            $stmt_stats .= " AND sr.SM_ID = ? ";
            $params_stats[] = $getsurvey;
        }
        if (!empty($getselectdaystart)) {
            $dateStart = DateTime::createFromFormat('d/m/Y', $getselectdaystart);
            if ($dateStart) {
                $formattedDateStart = $dateStart->format('Y-m-d');
                $stmt_stats .= " AND sr.SR_SURVEY_DATE >= ? ";
                $params_stats[] = $formattedDateStart;
            }
        }
        if (!empty($getselectdayend)) {
            $dateEnd = DateTime::createFromFormat('d/m/Y', $getselectdayend);
            if ($dateEnd) {
                $formattedDateEnd = $dateEnd->format('Y-m-d');
                $stmt_stats .= " AND sr.SR_SURVEY_DATE <= ? ";
                $params_stats[] = $formattedDateEnd;
            }
        }
        $query_stats = sqlsrv_query($conn, $stmt_stats, isset($params_stats) ? $params_stats : array());
        $stats = sqlsrv_fetch_array($query_stats, SQLSRV_FETCH_ASSOC);    

    
    // ดึงสถิติการให้คะแนน
        $stmt_rating = "SELECT 
            sa.SA_RATING,
            COUNT(*) as count_rating
        FROM SURVEY_ANSWERS sa
        LEFT JOIN SURVEY_RESPONSES sr ON sr.SR_ID = sa.SR_ID AND sa.SA_STATUS = '1'						
        LEFT JOIN SURVEY_MAIN sm ON sm.SM_ID = sr.SM_ID
        WHERE sa.SA_STATUS = '1' AND sm.SM_AREA = '$SESSION_AREA' ";
        // เพิ่มเงื่อนไขการค้นหาตามฟอร์ม
        if (!empty($getsurvey)) {
            $stmt_rating .= " AND sr.SM_ID = ? ";
            $params_rating[] = $getsurvey;
        }
        if (!empty($getselectdaystart)) {
            $dateStart = DateTime::createFromFormat('d/m/Y', $getselectdaystart);
            if ($dateStart) {
                $formattedDateStart = $dateStart->format('Y-m-d');
                $stmt_rating .= " AND sr.SR_SURVEY_DATE >= ? ";
                $params_rating[] = $formattedDateStart;
            }
        }
        if (!empty($getselectdayend)) {
            $dateEnd = DateTime::createFromFormat('d/m/Y', $getselectdayend);
            if ($dateEnd) {
                $formattedDateEnd = $dateEnd->format('Y-m-d');
                $stmt_rating .= " AND sr.SR_SURVEY_DATE <= ? ";
                $params_rating[] = $formattedDateEnd;
            }
        }
        $stmt_rating .= " GROUP BY sa.SA_RATING ORDER BY sa.SA_RATING DESC";
        $query_rating = sqlsrv_query($conn, $stmt_rating, isset($params_rating) ? $params_rating : array());
        $rating_stats = array(5 => 0, 4 => 0, 1 => 0);
        $total_ratings = 0;
        while ($row_rating = sqlsrv_fetch_array($query_rating, SQLSRV_FETCH_ASSOC)) {
            $rating = $row_rating['SA_RATING'];
            $count = $row_rating['count_rating'];
            $rating_stats[$rating] = $count;
            $total_ratings += $count;
        }

    // ดึงรายการการประเมินทั้งหมด
        $stmt = "SELECT 
            sr.SR_ID, sr.SM_ID, sr.SR_CODE, sr.SR_REPAIR_ID, sr.SR_SURVEY_DATE, 
            sr.SR_ADDITIONAL_COMMENTS, sr.SR_CREATED_DATE, sr.SR_STATUS,
            AVG(CAST(sa.SA_RATING as FLOAT)) as avg_rating,
            COUNT(sa.SA_ID) as total_answers
        FROM SURVEY_RESPONSES sr
        LEFT JOIN SURVEY_ANSWERS sa ON sr.SR_ID = sa.SR_ID AND sa.SA_STATUS = '1'
        LEFT JOIN SURVEY_MAIN sm ON sm.SM_ID = sr.SM_ID
        WHERE sm.SM_AREA = '$SESSION_AREA' ";
        // เพิ่มเงื่อนไขการค้นหาตามฟอร์ม
        if (!empty($getsurvey)) {
            $stmt .= " AND sr.SM_ID = ? ";
            $params[] = $getsurvey;
        }
        if (!empty($getselectdaystart)) {
            $dateStart = DateTime::createFromFormat('d/m/Y', $getselectdaystart);
            if ($dateStart) {
                $formattedDateStart = $dateStart->format('Y-m-d');
                $stmt .= " AND sr.SR_SURVEY_DATE >= ? ";
                $params[] = $formattedDateStart;
                // echo "Formatted Start Date: " . $formattedDateStart . "\n";
            }
        }
        if (!empty($getselectdayend)) {
            $dateEnd = DateTime::createFromFormat('d/m/Y', $getselectdayend);
            if ($dateEnd) {
                $formattedDateEnd = $dateEnd->format('Y-m-d');
                $stmt .= " AND sr.SR_SURVEY_DATE <= ? ";
                $params[] = $formattedDateEnd;
                // echo "Formatted End Date: " . $formattedDateEnd . "\n";
            }
        }
        $stmt .= " GROUP BY sr.SR_ID, sr.SM_ID, sr.SR_CODE, sr.SR_REPAIR_ID, sr.SR_SURVEY_DATE, 
                sr.SR_ADDITIONAL_COMMENTS, sr.SR_CREATED_DATE, sr.SR_STATUS";
        if (!empty($level)) {
            if ($level == 'verygood') {
                $stmt .= " HAVING AVG(CAST(sa.SA_RATING as FLOAT)) >= 4.5 ";
            } elseif ($level == 'good') {
                $stmt .= " HAVING AVG(CAST(sa.SA_RATING as FLOAT)) >= 3.5 AND AVG(CAST(sa.SA_RATING as FLOAT)) < 4.5 ";
            } elseif ($level == 'improve') {
                $stmt .= " HAVING AVG(CAST(sa.SA_RATING as FLOAT)) < 3.5 ";
            }
        }
        $stmt .= " 
            ORDER BY sr.SR_CREATED_DATE DESC";
        $query = sqlsrv_query($conn, $stmt, isset($params) ? $params : array());
        $number = 0;
        // echo "SQL Query: " . $stmt . "\n";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>รายงานการประเมินความพึงพอใจลูกค้า</title>
</head>
<script>    
    function querySurvey(){
        var company = $("#survey").val();
        var ds = $("#dateStart").val();
        var de = $("#dateEnd").val();
        var important = $("#satisfaction_level").val();
        var getsel = "?survey="+encodeURIComponent(company)+"&dateStart="+encodeURIComponent(ds)+"&dateEnd="+encodeURIComponent(de)+"&satisfaction_level="+encodeURIComponent(important);
        loadViewdetail('<?=$path?>views_amt/satisfaction_survey/customer_survey_report.php'+getsel);
    }
    
    function excel_survey_report(){
        var company = $("#survey").val();
        var ds = $("#dateStart").val();
        var de = $("#dateEnd").val();
        var important = $("#satisfaction_level").val();
        var getsel = "?survey="+encodeURIComponent(company)+"&dateStart="+encodeURIComponent(ds)+"&dateEnd="+encodeURIComponent(de)+"&satisfaction_level="+encodeURIComponent(important);
        window.open('<?=$path?>views_amt/satisfaction_survey/customer_survey_report_excel.php'+getsel, '_blank');
    }
    
    function pdf_survey_report(){
        var company = $("#survey").val();
        var ds = $("#dateStart").val();
        var de = $("#dateEnd").val();
        var important = $("#satisfaction_level").val();
        var getsel = "?survey="+encodeURIComponent(company)+"&dateStart="+encodeURIComponent(ds)+"&dateEnd="+encodeURIComponent(de)+"&satisfaction_level="+encodeURIComponent(important);
        window.open('<?=$path?>views_amt/satisfaction_survey/customer_survey_report_pdf.php'+getsel, '_blank');
    }
    
    function chart_survey_report(){
        var company = $("#survey").val();
        var ds = $("#dateStart").val();
        var de = $("#dateEnd").val();
        var important = $("#satisfaction_level").val();
        var getsel = "?survey="+encodeURIComponent(company)+"&dateStart="+encodeURIComponent(ds)+"&dateEnd="+encodeURIComponent(de)+"&satisfaction_level="+encodeURIComponent(important);
        window.open('<?=$path?>views_amt/satisfaction_survey/customer_survey_chart.php'+getsel, '_blank');
    }
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
</script>
<body>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
        <tr class="TOP">
            <td class="LEFT"></td>
            <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="25" valign="middle" class=""><img src="../images/report-icon128.png" width="48" height="48"></td>
                <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;รายงานการประเมินความพึงพอใจลูกค้า</h3></td>
                <td width="617" align="right" valign="bottom" class="" nowrap>
                    <div class="toolbar">
                        <!-- Toolbar buttons -->
                    </div>
                </td>
                </tr>
            </table></td>
            <td class="RIGHT"></td>
        </tr>
        <tr class="CENTER">
            <td class="LEFT"></td>
            <td class="CENTER" align="center">

                <!-- ฟอร์มค้นหา -->
                <form method="get" action="" onsubmit="querySurvey();return false;">
                    <table width="100%" cellpadding="5" cellspacing="0" border="0" class="default" style="margin-bottom: 15px;">
                        <thead>
                            <tr height="30" class="bg-color-blue">
                                <th colspan="4"><font color="#FFFFFF"><i class="fa fa-search"></i> ค้นหาข้อมูลการประเมินความพึงพอใจลูกค้า</font></th>
                            </tr>
                        </thead>
                    </table>
                    <table width="100%" cellpadding="5" cellspacing="0" border="0" class="default" style="margin-bottom: 15px;">
                        <tbody>
                            <tr align="center">
                                <!-- <td width="1%" align="right">&nbsp;</td> -->
                                <td width="15%" align="left">                                
                                    <div class="input-control select">กลุ่มแบบประเมิน     
                                        <?php
                                            // ดึงข้อมูลกลุ่มแบบสอบถามที่ active
                                            $stmt_surveys = "SELECT SM_ID, SM_CODE, SM_NAME, SM_TARGET_GROUP FROM SURVEY_MAIN WHERE SM_STATUS = 'Y' AND SM_AREA = '$SESSION_AREA' ORDER BY SM_ID ASC";
                                            $query_surveys = sqlsrv_query($conn, $stmt_surveys);
                                        ?>
                                        <select class="time" onFocus="$(this).select();" style="width: 100%;" name="survey" id="survey" required>
                                            <option value disabled selected>-------เลือกบริษัท-------</option>
                                            <option value="" <?php if($getsurvey==''){echo "selected";} ?>>-- แสดงทั้งหมด --</option>
                                            <?php while($survey = sqlsrv_fetch_array($query_surveys, SQLSRV_FETCH_ASSOC)) {
                                                $SM_ID = $survey['SM_ID'];
                                                $SM_NAME = $survey['SM_NAME'];
                                                $SM_TARGET_GROUP = $survey['SM_TARGET_GROUP'];
                                                
                                                // ตัดชื่อให้สั้นลงถ้ายาวเกินไป
                                                $button_text = $SM_NAME;
                                                if (mb_strlen($button_text) > 20) {
                                                    $button_text = mb_substr($button_text, 0, 17) . '...';
                                                }                                                       
                                            ?>
                                                <option value="<?= $SM_ID ?>" <?php if($SM_ID==$getsurvey){echo "selected";} ?>><?= htmlspecialchars($SM_TARGET_GROUP) ?></option>
                                                <?php } ?>   
                                        </select>
                                    </div>
                                </td>
                                <!-- <td width="1%" align="right">&nbsp;</td> -->
                                <td width="15%" align="left">
                                    <div class="row input-control">วันที่เริ่มต้น
                                        <input type="text" name="dateStart" id="dateStart" class="datepic time" placeholder="วันที่เริ่มต้น" autocomplete="off" value="<?=$getselectdaystart;?>" onchange="date1todate2()">
                                    </div>
                                </td>
                                <!-- <td width="1%" align="right">&nbsp;</td> -->
                                <td width="15%" align="left">
                                    <div class="row input-control">วันที่สิ้นสุด
                                        <input type="text" name="dateEnd" id="dateEnd" class="datepic time" placeholder="วันที่สิ้นสุด" autocomplete="off" value="<?=$getselectdayend;?>">
                                    </div>
                                </td>
                                <!-- <td width="1%" align="right">&nbsp;</td> -->
                                <td width="15%" align="left">
                                    <div class="row input-control">ระดับความพึงพอใจ
                                        <select class="time" onFocus="$(this).select();" style="width: 100%;" name="satisfaction_level" id="satisfaction_level">
                                            <option value="" selected>-- เลือกระดับความพึงพอใจ --</option>
                                            <option value="verygood" <?php if($level=='verygood'){echo "selected";} ?>>ดีมาก</option>
                                            <option value="good" <?php if($level=='good'){echo "selected";} ?>>ดี</option>
                                            <option value="improve" <?php if($level=='improve'){echo "selected";} ?>>ปรับปรุง</option>
                                        </select>
                                    </div>
                                </td>
                                <!-- <td width="1%" align="right">&nbsp;</td> -->
                                <td width="10%" align="center"><br>
                                    <button class="bg-color-blue" onclick="querySurvey();return false;"><font color="white"><i class="icon-search"></i> ค้นหา</font></button>
                                </td>
                                <!-- <td width="1%" align="right">&nbsp;</td> -->
                                <td width="10%" align="center"><br>
                                    <button title="Excel" type="button" class="bg-color-green" onclick="excel_survey_report()"><font color="white" size="2"><i class="icon-file-excel icon-large"></i> พิม Excel</font></button>
                                </td>
                                <!-- <td width="1%" align="right">&nbsp;</td> -->
                                <td width="10%" align="center"><br>
                                    <button title="PDF" type="button" class="bg-color-red" onclick="pdf_survey_report()"><font color="white" size="2"><i class="icon-file-pdf icon-large"></i> พิม PDF</font></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>

                <!-- แสดงข้อมูลที่ใช้ค้นหา -->
                <h4>ผลการค้นหา: 
                    <?php
                        // แสดงชื่อกลุ่มแบบประเมินที่เลือก
                        if ($getsurvey) {
                            $stmt_survey_name = "SELECT SM_TARGET_GROUP FROM SURVEY_MAIN WHERE SM_ID = ? AND SM_AREA = '$SESSION_AREA'";
                            $params_survey_name = array($getsurvey);
                            $query_survey_name = sqlsrv_query($conn, $stmt_survey_name, $params_survey_name);
                            $survey_name_row = sqlsrv_fetch_array($query_survey_name, SQLSRV_FETCH_ASSOC);
                            $survey_name = $survey_name_row ? $survey_name_row['SM_TARGET_GROUP'] : 'ไม่ระบุ';
                            echo "กลุ่มแบบประเมิน: <strong>" . htmlspecialchars($survey_name) . "</strong> ";
                        } else {
                            echo "กลุ่มแบบประเมิน: <strong>ทั้งหมด</strong> ";
                        }

                        // แสดงช่วงวันที่ที่เลือก
                        if ($getselectdaystart) {
                            echo " | วันที่เริ่มต้น: <strong>" . htmlspecialchars($getselectdaystart) . "</strong>";
                        } else {
                            echo " | วันที่เริ่มต้น: <strong>ไม่ระบุ</strong>";
                        }

                        if ($getselectdayend) {
                            echo " | วันที่สิ้นสุด: <strong>" . htmlspecialchars($getselectdayend) . "</strong>";
                        } else {
                            echo " | วันที่สิ้นสุด: <strong>ไม่ระบุ</strong>";
                        }

                        if ($level) {
                            $level_text = '';
                            if ($level == 'verygood') {
                                $level_text = 'ดีมาก';
                            } elseif ($level == 'good') {
                                $level_text = 'ดี';
                            } elseif ($level == 'improve') {
                                $level_text = 'ปรับปรุง';
                            }
                            echo " | ระดับความพึงพอใจ: <strong>" . htmlspecialchars($level_text) . "</strong>";
                        } else {
                            echo " | ระดับความพึงพอใจ: <strong>ไม่ระบุ</strong>";
                        }
                    ?>
                </h4>

                <!-- ปุ่มดูกราฟ -->
                <table width="100%" cellpadding="5" cellspacing="0" border="0" class="default" style="margin-bottom: 15px;">
                            <td width="10%" align="center">
                                <button title="GRAPH" type="button" class="bg-color-yellow" onclick="chart_survey_report()"><font color="black" size="2">📊 ดูกราฟความพึงพอใจ</font></button>
                            </td>
                </table>

                <!-- สถิติโดยรวม -->
                <table width="100%" cellpadding="5" cellspacing="0" border="0" class="default" style="margin-bottom: 15px;">
                    <thead>
                        <tr height="30" class="bg-color-blue">
                            <th colspan="4"><font color="#FFFFFF"><i class="fa fa-bar-chart"></i> สถิติโดยรวม</font></th>
                        </tr>
                    </thead>
                    <tbody>	
                        <tr height="35" class="bg-color-lightblue">
                            <td width="25%" align="center"><strong>จำนวนการประเมินทั้งหมด</strong></td>
                            <td width="25%" align="center"><strong>การแจ้งซ่อมที่ประเมิน</strong></td>
                            <td width="25%" align="center"><strong>คะแนนเฉลี่ย</strong></td>
                            <td width="25%" align="center"><strong>การประเมินล่าสุด</strong></td>
                        </tr>
                        <tr height="40" align="center">
                            <td><font size="5" color="#2c5aa0"><strong><?= number_format(isset($stats['total_responses']) ? $stats['total_responses'] : 0) ?></strong></font> รายการ</td>
                            <td><font size="5" color="#2c5aa0"><strong><?= number_format(isset($stats['unique_repairs']) ? $stats['unique_repairs'] : 0) ?></strong></font> รายการ</td>
                            <td><font size="5" color="#2c5aa0"><strong><?= number_format(isset($stats['avg_rating']) ? $stats['avg_rating'] : 0, 2) ?></strong></font> / 5.00</td>
                            <td><font size="4" color="#2c5aa0"><strong><?= (isset($stats['last_response']) && $stats['last_response']) ? $stats['last_response']->format('d/m/Y H:i') : '-' ?></strong></font></td>
                        </tr>
                    </tbody>
                </table>

                <!-- สถิติการให้คะแนน -->
                <table width="100%" cellpadding="5" cellspacing="0" border="0" class="default" style="margin-top: 15px;">
                    <thead>
                        <tr height="30" class="bg-color-blue">
                            <th colspan="4"><font color="#FFFFFF"><i class="fa fa-pie-chart"></i> สถิติการให้คะแนน</font></th>
                        </tr>
                    </thead>
                    <tbody>	
                        <tr height="35" class="bg-color-lightblue">
                            <td width="25%" align="center"><strong>ดีมาก (5 คะแนน)</strong></td>
                            <td width="25%" align="center"><strong>ดี (4 คะแนน)</strong></td>
                            <td width="25%" align="center"><strong>ปรับปรุง (1 คะแนน)</strong></td>
                            <td width="25%" align="center"><strong>รวมทั้งหมด</strong></td>
                        </tr>
                        <?php
                            
                            while ($row = sqlsrv_fetch_array($query_rating, SQLSRV_FETCH_ASSOC)) {
                                $rating = intval($row['SA_RATING']);
                                $count = intval($row['count_rating']);
                                $rating_stats[$rating] = $count;
                                $total_ratings += $count;
                            }
                        ?>
                        <tr height="40" align="center">
                            <td>
                                <font size="4" color="#27ae60"><strong><?= number_format($rating_stats[5]) ?></strong></font><br>
                                <small>(<?= $total_ratings > 0 ? number_format(($rating_stats[5] / $total_ratings) * 100, 1) : 0 ?>%)</small>
                            </td>
                            <td>
                                <font size="4" color="#f39c12"><strong><?= number_format($rating_stats[4]) ?></strong></font><br>
                                <small>(<?= $total_ratings > 0 ? number_format(($rating_stats[4] / $total_ratings) * 100, 1) : 0 ?>%)</small>
                            </td>
                            <td>
                                <font size="4" color="#e74c3c"><strong><?= number_format($rating_stats[1]) ?></strong></font><br>
                                <small>(<?= $total_ratings > 0 ? number_format(($rating_stats[1] / $total_ratings) * 100, 1) : 0 ?>%)</small>
                            </td>
                            <td>
                                <font size="4" color="#2c5aa0"><strong><?= number_format($total_ratings) ?></strong></font><br>
                                <small>(100%)</small>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- ตารางรายงาน -->
                <form id="form1" name="form1" method="post" action="#">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
                        <thead>
                            <tr height="30">
                                <th width="5%">ลำดับ</th>
                                <th width="15%">รหัสใบประเมิน</th>
                                <th width="15%">กลุ่มเป้าหมาย</th>
                                <th width="15%">รหัสแจ้งซ่อม</th>
                                <th width="15%">วันที่ประเมิน</th>
                                <th width="15%">คะแนนเฉลี่ย</th>
                                <th width="15%">จำนวนคำถาม</th>
                                <th width="10%">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                                    $number++;
                                    $SR_ID = $result["SR_ID"];
                                    $SM_ID = $result["SM_ID"];
                                    $SR_CODE = $result["SR_CODE"];
                                    $SR_REPAIR_ID = $result["SR_REPAIR_ID"];
                                    $SR_SURVEY_DATE = $result["SR_SURVEY_DATE"];
                                    $SR_CREATED_DATE = $result["SR_CREATED_DATE"];
                                    $SR_STATUS = $result["SR_STATUS"];
                                    $avg_rating = $result["avg_rating"];
                                    $total_answers = $result["total_answers"];

                                    // แปลงคะแนนเป็นข้อความ
                                    $rating_text = "ไม่ระบุ";
                                    $rating_color = "#999999";
                                    if ($avg_rating >= 4.5) {
                                        $rating_text = "ดีมาก";
                                        $rating_color = "#27ae60";
                                    } elseif ($avg_rating >= 3.5) {
                                        $rating_text = "ดี";
                                        $rating_color = "#f39c12";
                                    } elseif ($avg_rating >= 1.0) {
                                        $rating_text = "ปรับปรุง";
                                        $rating_color = "#e74c3c";
                                    }
                            ?>
                            <tr id="<?php print $SR_CODE; ?>" height="25px" align="center">
                                <td><?php print "$number.";?></td>
                                <td align="left">&nbsp;<?php print $SR_CODE; ?></td>
                                <td align="left">&nbsp;
                                    <?php
                                        // ดึงชื่อกลุ่มเป้าหมาย
                                        $stmt_target_group = "SELECT SM_TARGET_GROUP FROM SURVEY_MAIN WHERE SM_ID = ? AND SM_AREA = '$SESSION_AREA'";
                                        $params_target_group = array($SM_ID);
                                        $query_target_group = sqlsrv_query($conn, $stmt_target_group, $params_target_group);
                                        $target_group_row = sqlsrv_fetch_array($query_target_group, SQLSRV_FETCH_ASSOC);
                                        $target_group = $target_group_row ? $target_group_row['SM_TARGET_GROUP'] : 'ไม่ระบุ';
                                        echo htmlspecialchars($target_group);
                                    ?>
                                </td>
                                <td align="center">&nbsp;<?php print $SR_REPAIR_ID; ?></td>
                                <td align="center">&nbsp;<?php print $SR_CREATED_DATE ? $SR_CREATED_DATE->format('d/m/Y') : '-'; ?></td>
                                <td align="center">
                                    <span style="color: <?=$rating_color?>; font-weight: bold;">
                                        <?php print number_format($avg_rating, 2); ?> (<?=$rating_text?>)
                                    </span>
                                </td>
                                <td align="center">&nbsp;<?php print number_format($total_answers); ?> ข้อ</td>
                                <td align="center">
                                    <button type="button" title="ดูรายละเอียดการประเมิน" onclick="window.open('../survey/customer_survey.php?sm=<?=$SM_ID?>&rp=<?=$SR_REPAIR_ID; ?>&code=<?=$SR_CODE; ?>', '_blank');"
                                            style="
                                                background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                                                border: none;
                                                border-radius: 6px;
                                                padding: 8px 12px;
                                                color: white;
                                                font-size: 12px;
                                                cursor: pointer;
                                                box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
                                                transition: all 0.3s ease;
                                                min-width: 40px;
                                            "
                                            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 15px rgba(52, 152, 219, 0.4)';"
                                            onmouseout="this.style.transform='translateY(0px)'; this.style.boxShadow='0 3px 10px rgba(52, 152, 219, 0.3)';">
                                        <i class='icon-search'></i>
                                    </button>
                                </td>
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
                    <input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>views_amt/satisfaction_survey/customer_survey_report.php');">
                    &nbsp;&nbsp;
                    <?php
                        // ดึงข้อมูลกลุ่มแบบสอบถามที่ active
                        $stmt_surveys_button = "SELECT SM_ID, SM_CODE, SM_NAME, SM_TARGET_GROUP FROM SURVEY_MAIN WHERE SM_STATUS = 'Y' AND SM_AREA = '$SESSION_AREA' ORDER BY SM_ID ASC";
                        $query_surveys_button = sqlsrv_query($conn, $stmt_surveys_button);
                        
                        while($surveys_button = sqlsrv_fetch_array($query_surveys_button, SQLSRV_FETCH_ASSOC)) {
                            $SM_ID = $surveys_button['SM_ID'];
                            $SM_NAME = $surveys_button['SM_NAME'];
                            $SM_TARGET_GROUP = $surveys_button['SM_TARGET_GROUP'];
                            
                            // ตัดชื่อให้สั้นลงถ้ายาวเกินไป
                            $button_text = $SM_NAME;
                            if (mb_strlen($button_text) > 20) {
                                $button_text = mb_substr($button_text, 0, 17) . '...';
                            }
                    ?>
                        <!-- <input type="button" class="button_blue" value="ไปยังแบบประเมิน - <?= htmlspecialchars($SM_TARGET_GROUP) ?>" 
                            onclick="window.open('../survey/customer_survey.php?sm=<?= $SM_ID ?>', '_blank');"
                            title="<?= htmlspecialchars($SM_NAME . ' (' . $SM_TARGET_GROUP . ')') ?>"> -->
                        &nbsp;&nbsp;
                    <?php } ?>
                </center>
            </td>
            <td class="RIGHT">&nbsp;</td>
        </tr>
    </table>

</body>
</html>