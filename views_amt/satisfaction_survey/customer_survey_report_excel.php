<?php
session_name("EMS"); 
session_start();
$path = "../";   	
require($path.'../include/connect.php');
$SESSION_AREA = $_SESSION["AD_AREA"];

// รับค่าจากฟอร์มค้นหา
$customer = isset($_GET['customer']) ? $_GET['customer'] : '';
$getsurvey = isset($_GET['survey']) ? $_GET['survey'] : '';
$getselectdaystart = isset($_GET['dateStart']) ? $_GET['dateStart'] : '';
$getselectdayend = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : '';
$level = isset($_GET['satisfaction_level']) ? $_GET['satisfaction_level'] : '';

// echo "<pre>";
// print_r($_GET);
// echo "</pre>";
// exit;

// ดึงข้อมูลแบบประเมินที่เลือก
$survey_info = null;
if ($getsurvey) {
    $stmt_survey = "SELECT SM_ID, SM_CODE, SM_NAME, SM_TARGET_GROUP FROM SURVEY_MAIN WHERE SM_ID = ? AND SM_AREA = ? AND SM_STATUS = 'Y'";
    $params_survey = array($getsurvey, $SESSION_AREA);
    $query_survey = sqlsrv_query($conn, $stmt_survey, $params_survey);
    if ($query_survey) {
        $survey_info = sqlsrv_fetch_array($query_survey, SQLSRV_FETCH_ASSOC);
    }
}

// ดึงข้อมูลการประเมินตามเงื่อนไข
$stmt = "SELECT 
    sr.SR_ID, sr.SM_ID, sr.SR_CODE, sr.SR_REPAIR_ID, sr.SR_SURVEY_DATE, 
    sr.SR_ADDITIONAL_COMMENTS, sr.SR_CREATED_DATE, sr.SR_STATUS,
    sm.SM_TARGET_GROUP, sm.SM_CODE as SURVEY_CODE,
    rprq.RPRQ_COMPANYCASH
FROM SURVEY_RESPONSES sr
LEFT JOIN SURVEY_MAIN sm ON sm.SM_ID = sr.SM_ID
LEFT JOIN REPAIRREQUEST rprq ON rprq.RPRQ_ID = sr.SR_REPAIR_ID
WHERE sm.SM_AREA = '$SESSION_AREA' ";

// เพิ่มเงื่อนไขการค้นหาตามฟอร์ม
$params = array();
if (!empty($customer)) {
    $stmt .= " AND rprq.RPRQ_COMPANYCASH = ? ";
    $params[] = $customer;
}
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
    }
}
if (!empty($getselectdayend)) {
    $dateEnd = DateTime::createFromFormat('d/m/Y', $getselectdayend);
    if ($dateEnd) {
        $formattedDateEnd = $dateEnd->format('Y-m-d');
        $stmt .= " AND sr.SR_SURVEY_DATE <= ? ";
        $params[] = $formattedDateEnd;
    }
}

$stmt .= " ORDER BY sr.SR_CREATED_DATE DESC";
$query = sqlsrv_query($conn, $stmt, $params);

// สร้างข้อมูลสำหรับการแสดงผล
$survey_data = array();
$total_responses = 0;

if ($query) {
    while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $total_responses++;
        $SR_ID = $result["SR_ID"];
        
        // ดึงคำตอบแต่ละข้อของใบประเมินนี้
        $stmt_answers = "SELECT 
            sa.SA_ID, sa.SQ_ID, sa.SA_RATING, sa.SA_COMMENT,
            sq.SQ_QUESTION, sq.SQ_ORDER, sc.SC_NAME
        FROM SURVEY_ANSWERS sa
        LEFT JOIN SURVEY_QUESTION sq ON sa.SQ_ID = sq.SQ_ID
        LEFT JOIN SURVEY_CATEGORY sc ON sq.SC_CODE = sc.SC_CODE
        WHERE sa.SR_ID = ? AND sa.SA_STATUS = '1'
        ORDER BY sc.SC_ORDER, sq.SQ_ORDER";
        
        $params_answers = array($SR_ID);
        $query_answers = sqlsrv_query($conn, $stmt_answers, $params_answers);
        
        $survey_data[$SR_ID] = array(
            'info' => $result,
            'answers' => array()
        );
        
        while($answer = sqlsrv_fetch_array($query_answers, SQLSRV_FETCH_ASSOC)) {
            $survey_data[$SR_ID]['answers'][] = $answer;
        }
    }
}

// echo "<pre>";
// print_r($stmt);
// print_r($params);
// echo "</pre>";

// คำนวณสถิติต่างๆ
$question_stats = array(); // เก็บสถิติของแต่ละคำถาม
$category_stats = array(); // เก็บสถิติของแต่ละหมวดหมู่
$overall_stats = array(5 => 0, 4 => 0, 1 => 0); // การแจกแจงคะแนนรวม

foreach ($survey_data as $survey) {
    foreach ($survey['answers'] as $answer) {
        $sq_id = $answer['SQ_ID'];
        $rating = intval($answer['SA_RATING']);
        $category_name = $answer['SC_NAME'];
        
        // สถิติของแต่ละคำถาม
        if (!isset($question_stats[$sq_id])) {
            $question_stats[$sq_id] = array(
                'question' => $answer['SQ_QUESTION'],
                'order' => $answer['SQ_ORDER'],
                'category' => $category_name,
                'ratings' => array(5 => 0, 4 => 0, 1 => 0),
                'total' => 0,
                'sum' => 0
            );
        }
        
        if (isset($question_stats[$sq_id]['ratings'][$rating])) {
            $question_stats[$sq_id]['ratings'][$rating]++;
        }
        $question_stats[$sq_id]['total']++;
        $question_stats[$sq_id]['sum'] += $rating;
        
        // สถิติของแต่ละหมวดหมู่
        if (!isset($category_stats[$category_name])) {
            $category_stats[$category_name] = array(
                'total' => 0,
                'sum' => 0,
                'ratings' => array(5 => 0, 4 => 0, 1 => 0)
            );
        }
        $category_stats[$category_name]['total']++;
        $category_stats[$category_name]['sum'] += $rating;
        if (isset($category_stats[$category_name]['ratings'][$rating])) {
            $category_stats[$category_name]['ratings'][$rating]++;
        }
        
        // นับการแจกแจงคะแนนรวม
        if (isset($overall_stats[$rating])) {
            $overall_stats[$rating]++;
        }
    }
}

// เรียงลำดับคำถามตาม SQ_ORDER และหมวดหมู่
uasort($question_stats, function($a, $b) {
    if ($a['category'] === $b['category']) {
        return $a['order'] - $b['order'];
    }
    return strcmp($a['category'], $b['category']);
});

// ตั้งค่า header สำหรับ Excel
$filename = "รายงานการประเมินความพึงพอใจลูกค้า_" . date('Y-m-d_H-i-s') . ".xls";
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// เริ่มต้น HTML สำหรับ Excel
echo "\xEF\xBB\xBF"; // BOM for UTF-8
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>รายงานการประเมินความพึงพอใจลูกค้า</title>
    <style>
        body { 
            font-family: 'Sarabun', Arial, sans-serif; 
            font-size: 12px; 
            line-height: 1.4;
            margin: 0;
            padding: 10px;
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-bottom: 15px; 
        }
        tr {            
            height: 40px;
        }
        th, td { 
            border: 1px solid #000; 
            padding: 8px 5px; 
            vertical-align: middle; 
            line-height: 1.3;
            min-height: 25px;
        }
        th { 
            background-color: #d0d0d0; 
            font-weight: bold; 
            text-align: center; 
            padding: 10px 5px;
            height: 35px;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .header-blue { 
            background-color: #d0d0d0; 
            color: black; 
            font-weight: bold; 
            text-align: center; 
            padding: 12px 5px;
            height: 40px;
        }
        .category-row { 
            background-color: #f8f9fa; 
            font-weight: bold; 
        }
        .category-row td {
            padding: 10px 5px;
        }
        .summary-row { 
            background-color: #d0d0d0; 
            font-weight: bold; 
            height: 35px;
        }
        .summary-row td {
            padding: 10px 5px;
        }
        .question-row td {
            padding: 8px 5px;
            height: 28px;
            line-height: 1.4;
        }
        .rating-excellent { background-color: #27ae60; color: white; }
        .rating-good { background-color: #2ecc71; color: white; }
        .rating-average { background-color: #f39c12; color: white; }
        .rating-fair { background-color: #e67e22; color: white; }
        .rating-improve { background-color: #e74c3c; color: white; }
        
        /* ปรับระยะห่างสำหรับข้อความยาวๆ */
        .question-text {
            line-height: 1.4;
            word-wrap: break-word;
        }
        
        /* ปรับระยะห่างสำหรับตารางหัว */
        .header-info {
            border: none;
            padding: 5px 0;
            line-height: 1.5;
        }
        
        /* ปรับระยะห่างสำหรับข้อเสนอแนะ */
        .suggestion-row td {
            padding: 8px 5px;
            line-height: 1.4;
            min-height: 25px;
        }
    </style>
</head>
<body>
    <!-- ข้อมูลสรุปหัว -->
    <table style="margin-bottom: 15px; border: none;">
        <tr>
            <td colspan="8" style="border: none; font-weight: bold;">รายการสำรวจความพึงพอใจของลูกค้า (<?= $survey_info ? htmlspecialchars($survey_info['SM_TARGET_GROUP']) : 'ไม่ระบุ' ?>) ประจำวันที่ <font style="text-decoration: underline;">&emsp;&emsp;<?= $getselectdaystart ? $getselectdaystart : '' ?>&emsp;&emsp;</font> ถึง <font style="text-decoration: underline;">&emsp;&emsp;<?= $getselectdayend ? $getselectdayend : '' ?>&emsp;&emsp;</font></td>
        </tr>
        <tr>
            <td colspan="4" style="border: none; font-weight: bold;">รายงานสรุปผลการประเมิน <font style="text-decoration: underline;">&emsp;<?= number_format($total_responses) ?>&emsp;</font> รายการ <?= $customer ? '(ลูกค้า: ' . htmlspecialchars($customer) . ')' : '' ?></td>
        </tr>
        <tr></tr>
    </table>

    
    <!-- ตารางรายละเอียดความพึงพอใจ -->
    <table class="evaluation-table">
        <thead>
            <tr class="summary-row">
                <th colspan="5" width="50%">หัวข้อความพึงพอใจ</th>
                <th width="10%">คะแนนเฉลี่ย</th>
                <th width="15%">ระดับความพึงพอใจ<br><small>(ดีมาก/ดี/ปานกลาง/พอใช้/ปรับปรุง)</small></th>
                <th width="25%">ความคิดเห็น</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // ดึงหมวดหมู่และคำถาม
                $stmt_categories = "SELECT SC_ID, SC_CODE, SC_NAME FROM SURVEY_CATEGORY WHERE SM_CODE = ? AND SC_STATUS = 'Y' ORDER BY SC_ORDER";
                $params_categories = array($survey_info['SM_CODE']);
                $query_categories = sqlsrv_query($conn, $stmt_categories, $params_categories);
                
                $index = 0;
                $current_category = '';
                
                while($category = sqlsrv_fetch_array($query_categories, SQLSRV_FETCH_ASSOC)) {
                    $current_category = $category['SC_NAME'];
                ?>
                <tr class="category-row">
                    <td colspan="8"><b><?= htmlspecialchars($category['SC_NAME']) ?></b></td>
                </tr>
                <?php
                    // ดึงคำถามในแต่ละหมวดหมู่
                    $stmt_questions = "SELECT SQ_ID,SQ_ORDER,SQ_QUESTION FROM SURVEY_QUESTION WHERE SC_CODE = ? AND SQ_STATUS = 'Y' ORDER BY SQ_ORDER";
                    $params_questions = array($category['SC_CODE']);
                    $query_questions = sqlsrv_query($conn, $stmt_questions, $params_questions);
                    
                    while($question = sqlsrv_fetch_array($query_questions, SQLSRV_FETCH_ASSOC)) {
                        $sq_id = $question['SQ_ID'];
                        $question_stat = isset($question_stats[$sq_id]) ? $question_stats[$sq_id] : null;
                        
                        // คำนวณข้อมูลสถิติ
                        $avg_score = 0;
                        $level_text = '-';
                        $level_class = '';
                        $comments_text = '';
                        
                        if ($question_stat && $question_stat['total'] > 0) {
                            $avg_score = $question_stat['sum'] / $question_stat['total'];
                            
                            // กำหนดระดับความพึงพอใจ (5 ระดับ)
                            if ($avg_score >= 4.5) {
                                $level_text = 'ดีมาก';
                                $level_class = 'rating-excellent';
                            } elseif ($avg_score >= 3.5) {
                                $level_text = 'ดี';
                                $level_class = 'rating-good';
                            } elseif ($avg_score >= 2.5) {
                                $level_text = 'ปานกลาง';
                                $level_class = 'rating-average';
                            } elseif ($avg_score >= 1.5) {
                                $level_text = 'พอใช้';
                                $level_class = 'rating-fair';
                            } else {
                                $level_text = 'ปรับปรุง';
                                $level_class = 'rating-improve';
                            }
                            
                            // แสดงการแจกแจงคะแนน
                            $distribution = array();
                            if ($question_stat['ratings'][5] > 0) {
                                $distribution[] = "ดีมาก: " . $question_stat['ratings'][5] . " คน";
                            }
                            if ($question_stat['ratings'][4] > 0) {
                                $distribution[] = "ดี: " . $question_stat['ratings'][4] . " คน";
                            }
                            if ($question_stat['ratings'][1] > 0) {
                                $distribution[] = "ปรับปรุง: " . $question_stat['ratings'][1] . " คน";
                            }
                            $comments_text = implode(", ", $distribution);
                        }
                ?>
                <tr>
                    <td colspan="5"><?= $question['SQ_ORDER'].'.)' ?> <?= htmlspecialchars($question['SQ_QUESTION']) ?></td>
                    <td class="center"><?= $question_stat ? number_format($avg_score, 2) : '-' ?></td>
                    <td class="center"><?= $level_text ?></td>
                    <td align="left">
                        <?php
                            $comments = [];
                            foreach ($survey_data as $survey) {
                                foreach ($survey['answers'] as $answer) {
                                    if ($answer['SQ_ID'] == $sq_id && !empty($answer['SA_COMMENT'])) {
                                        $comments[] = htmlspecialchars($answer['SA_COMMENT']);
                                    }
                                }
                            }
                            echo implode(", ", array_unique($comments));
                        ?>
                    </td>
                </tr>
                <?php 
                        $index++;
                    }
                } 
                
                // สรุปรวมท้ายสุด
                $total_questions = count($question_stats);
                $total_ratings = array_sum($overall_stats);
                $overall_avg = $total_ratings > 0 ? array_sum(array_map(function($rating, $count) { return $rating * $count; }, array_keys($overall_stats), $overall_stats)) / $total_ratings : 0;
                
                if ($total_questions > 0) {
                    $overall_level = '';
                    if ($overall_avg >= 4.5) {
                        $overall_level = 'ดีมาก';
                    } elseif ($overall_avg >= 3.5) {
                        $overall_level = 'ดี';
                    } elseif ($overall_avg >= 2.5) {
                        $overall_level = 'ปานกลาง';
                    } elseif ($overall_avg >= 1.5) {
                        $overall_level = 'พอใช้';
                    } else {
                        $overall_level = 'ปรับปรุง';
                    }
                ?>
                <tr class="summary-row">
                    <td colspan="5" align="right"><b>สรุปรวมทั้งหมด</b></td>
                    <td class="center"><b><?= number_format($overall_avg, 2) ?></b></td>
                    <td class="center"><b><?= $overall_level ?></b></td>
                    <td class="center"><b></b></td>
                </tr>
                <?php } ?>
        </tbody>
    </table>
    <!-- ตารางรายการข้อเสนอแนะ/ข้อที่ต้องปรับปรุง -->
    <?php 
        // เก็บข้อเสนอแนะจากการประเมิน พร้อมข้อมูลลูกค้า
        $suggestions = array();
        foreach ($survey_data as $survey) {
            if (!empty($survey['info']['SR_ADDITIONAL_COMMENTS'])) {
                $repair_id = $survey['info']['SR_REPAIR_ID'];
                $customer_name = '';
                $repair_code = '';
                
                // ดึงข้อมูลจากตาราง vwREPAIRREQUEST
                if (!empty($repair_id)) {
                    $stmt_repair = "SELECT RPRQ_ID,RPRQ_LINEOFWORK,RPRQ_COMPANYCASH,RPRQ_AREA FROM vwREPAIRREQUEST WHERE RPRQ_ID = ?";
                    $params_repair = array($repair_id);
                    $query_repair = sqlsrv_query($conn, $stmt_repair, $params_repair);
                    
                    if ($query_repair && $repair_data = sqlsrv_fetch_array($query_repair, SQLSRV_FETCH_ASSOC)) {
                        $customer_name = $repair_data['RPRQ_LINEOFWORK'] ? $repair_data['RPRQ_LINEOFWORK'] : 'ไม่ระบุ';
                        $repair_code = $repair_data['RPRQ_ID'] ? $repair_data['RPRQ_ID'] : '';
                        $area = $repair_data['RPRQ_AREA'] ? $repair_data['RPRQ_AREA'] : '';
                        $dept = $repair_data['RPRQ_COMPANYCASH'] ? $repair_data['RPRQ_COMPANYCASH'] : '';
                    } else {
                        $customer_name = 'ไม่พบข้อมูล';
                        $repair_code = $repair_id;
                    }
                } else {
                    $customer_name = 'ไม่ระบุ';
                    $repair_code = 'ไม่ระบุ';
                }
                
                $suggestions[] = array(
                    'comment' => trim($survey['info']['SR_ADDITIONAL_COMMENTS']),
                    'customer_name' => $customer_name,
                    'repair_code' => $repair_code,
                    'area' => isset($area) ? $area : '',
                    'dept' => isset($dept) ? $dept : '',
                    'survey_date' => $survey['info']['SR_SURVEY_DATE']
                );
            }
        }
        
        // ลบข้อความซ้ำ (เปรียบเทียบทั้ง comment และ customer)
        $unique_suggestions = array();
        foreach ($suggestions as $suggestion) {
            $key = $suggestion['comment'] . '|' . $suggestion['customer_name'];
            if (!isset($unique_suggestions[$key])) {
                $unique_suggestions[$key] = $suggestion;
            }
        }
        $suggestions = array_values($unique_suggestions);
    ?>
    <table style="margin-top: 20px;">
        <tr></tr>
        <tr class="header-blue">
            <td colspan="8">อาการของรถที่ผิดปกติ/สิ่งที่ต้องปรับปรุง</td>
        </tr>
        <?php if (count($suggestions) > 0): ?>
            <?php foreach ($suggestions as $idx => $suggestion): ?>
            <tr>
                <td colspan="8" style="width: 5%;"><?= $idx + 1 .".)" ?> <?= htmlspecialchars($suggestion['comment']) ?> - จากลูกค้า: <?= htmlspecialchars($suggestion['customer_name']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" style="text-align:center; color:#888;">- ไม่มีข้อเสนอแนะ -</td>
            </tr>
        <?php endif; ?>
    </table>
<br><br><br>
    <!-- หมายเหตุและข้อมูลเพิ่มเติม -->
    <p style="text-align: left; margin-top: 20px; font-size: 14px;">
        <strong>คำแนะนำในการใช้งาน Excel:</strong><br>
        สามารถสร้างกราฟจากข้อมูลคะแนนได้โดยเลือกข้อมูลแล้วเลือก Insert → Chart<br>
        ใช้ฟังก์ชั่น Filter เพื่อกรองข้อมูลตามหมวดหมู่ที่ต้องการ<br>
        สามารถคำนวณสถิติเพิ่มเติมโดยใช้ฟังก์ชั่น AVERAGE, COUNT, SUM
    </p>

    <!-- ข้อมูลเพิ่มเติม -->
    <p style="text-align: right; margin-top: 20px; font-size: 14px;">
        <strong>ออกรายงาน:</strong> <?= date('d/m/Y H:i:s') ?><br>
        <strong>จำนวนใบประเมินทั้งหมด:</strong> <?= number_format($total_responses) ?> ใบ<br>
        <strong>จำนวนคำถาม:</strong> <?= count($question_stats) ?> ข้อ<br>
        <strong>คะแนนเฉลี่ยรวม:</strong> <?= isset($overall_avg) ? number_format($overall_avg, 2) : '0.00' ?> / 5.00
    </p>
</body>
</html>