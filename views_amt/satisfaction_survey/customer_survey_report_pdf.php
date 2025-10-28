<?php
session_name("EMS"); 
session_start();
$path = "../";   	
require($path.'../include/connect.php');
$SESSION_AREA = $_SESSION["AD_AREA"];

// รับค่าจากฟอร์มค้นหา
$getsurvey = isset($_GET['survey']) ? $_GET['survey'] : '';
$getselectdaystart = isset($_GET['dateStart']) ? $_GET['dateStart'] : '';
$getselectdayend = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : '';
$level = isset($_GET['satisfaction_level']) ? $_GET['satisfaction_level'] : '';

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
    sm.SM_TARGET_GROUP, sm.SM_CODE as SURVEY_CODE
FROM SURVEY_RESPONSES sr
LEFT JOIN SURVEY_MAIN sm ON sm.SM_ID = sr.SM_ID
WHERE sm.SM_AREA = '$SESSION_AREA' ";

// เพิ่มเงื่อนไขการค้นหาตามฟอร์ม
$params = array();
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

// ลบข้อความซ้ำ
$unique_suggestions = array();
foreach ($suggestions as $suggestion) {
    $key = $suggestion['comment'] . '|' . $suggestion['customer_name'];
    if (!isset($unique_suggestions[$key])) {
        $unique_suggestions[$key] = $suggestion;
    }
}
$suggestions = array_values($unique_suggestions);

// ใช้ mPDF สำหรับสร้าง PDF
require_once($path.'../include/Classes/PDF/vendor/autoload.php');

// สร้าง mPDF instance
$mpdf = new mPDF('th', 'A4-L', '0', '');
$mpdf->SetDisplayMode('fullpage');

// เริ่มต้น HTML
$html = '
<style>
    body { 
        font-family: "Garuda", Arial, sans-serif; 
        font-size: 11px; 
        line-height: 1.3;
        margin: 0;
        padding: 5px;
    }
    table { 
        border-collapse: collapse; 
        width: 100%; 
        margin-bottom: 10px; 
    }
    th, td { 
        border: 1px solid #000; 
        padding: 4px 3px; 
        vertical-align: middle; 
    }
    th { 
        background-color: #d0d0d0; 
        font-weight: bold; 
        text-align: center; 
        font-size: 10px;
    }
    .header-info {
        border: none;
        padding: 2px 0;
        font-weight: bold;
        font-size: 12px;
    }
    .category-row { 
        background-color: #f8f9fa; 
        font-weight: bold; 
        font-size: 11px;
    }
    .summary-row { 
        background-color: #d0d0d0; 
        font-weight: bold; 
    }
    .center { text-align: center; }
    .right { text-align: right; }
    .left { text-align: left; }
    .rating-excellent { color: #27ae60; font-weight: bold; }
    .rating-good { color: #2ecc71; font-weight: bold; }
    .rating-average { color: #f39c12; font-weight: bold; }
    .rating-fair { color: #e67e22; font-weight: bold; }
    .rating-improve { color: #e74c3c; font-weight: bold; }
    .suggestion-header { 
        background-color: #d0d0d0; 
        font-weight: bold; 
        text-align: center; 
    }
</style>

<!-- ข้อมูลสรุปหัว -->
<table style="border: none; margin-bottom: 10px;">
    <tr>
        <td class="header-info">รายการสำรวจความพึงพอใจของลูกค้า (' . ($survey_info ? htmlspecialchars($survey_info['SM_TARGET_GROUP']) : 'ไม่ระบุ') . ') ประจำวันที่ <u>&nbsp;&nbsp;&nbsp;' . ($getselectdaystart ? $getselectdaystart : '') . '&nbsp;&nbsp;&nbsp;</u> ถึง <u>&nbsp;&nbsp;&nbsp;' . ($getselectdayend ? $getselectdayend : '') . '&nbsp;&nbsp;&nbsp;</u></td>
    </tr>
    <tr>
        <td class="header-info">รายงานสรุปผลการประเมิน <u>&nbsp;' . number_format($total_responses) . '&nbsp;</u> รายการ</td>
    </tr>
</table>

<!-- ตารางรายละเอียดความพึงพอใจ -->
<table>
    <thead>
        <tr class="summary-row">
            <th width="45%">หัวข้อความพึงพอใจ</th>
            <th width="12%">คะแนนเฉลี่ย</th>
            <th width="18%">ระดับความพึงพอใจ<br><small>(ดีมาก/ดี/ปานกลาง/พอใช้/ปรับปรุง)</small></th>
            <th width="25%">ความคิดเห็น</th>
        </tr>
    </thead>
    <tbody>';

// ดึงหมวดหมู่และคำถาม
if ($survey_info) {
    $stmt_categories = "SELECT SC_ID, SC_CODE, SC_NAME FROM SURVEY_CATEGORY WHERE SM_CODE = ? AND SC_STATUS = 'Y' ORDER BY SC_ORDER";
    $params_categories = array($survey_info['SM_CODE']);
    $query_categories = sqlsrv_query($conn, $stmt_categories, $params_categories);
    
    while($category = sqlsrv_fetch_array($query_categories, SQLSRV_FETCH_ASSOC)) {
        $html .= '<tr class="category-row">
            <td colspan="4"><b>' . htmlspecialchars($category['SC_NAME']) . '</b></td>
        </tr>';
        
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
            
            // ดึงความคิดเห็นของคำถามนี้
            $comments = array();
            foreach ($survey_data as $survey) {
                foreach ($survey['answers'] as $answer) {
                    if ($answer['SQ_ID'] == $sq_id && !empty($answer['SA_COMMENT'])) {
                        $comments[] = htmlspecialchars($answer['SA_COMMENT']);
                    }
                }
            }
            $comments_display = implode(", ", array_unique($comments));
            
            $html .= '<tr>
                <td class="left">' . $question['SQ_ORDER'] . '.) ' . htmlspecialchars($question['SQ_QUESTION']) . '</td>
                <td class="center">' . ($question_stat ? number_format($avg_score, 2) : '-') . '</td>
                <td class="center">' . $level_text . '</td>
                <td class="left">' . $comments_display . '</td>
            </tr>';
        }
    }
    
    // สรุปรวมท้ายสุด
    $total_questions = count($question_stats);
    $total_ratings = array_sum($overall_stats);
    $overall_avg = $total_ratings > 0 ? array_sum(array_map(function($rating, $count) { return $rating * $count; }, array_keys($overall_stats), $overall_stats)) / $total_ratings : 0;
    
    if ($total_questions > 0) {
        $overall_level = '';
        $overall_class = '';
        if ($overall_avg >= 4.5) {
            $overall_level = 'ดีมาก';
            $overall_class = 'rating-excellent';
        } elseif ($overall_avg >= 3.5) {
            $overall_level = 'ดี';
            $overall_class = 'rating-good';
        } elseif ($overall_avg >= 2.5) {
            $overall_level = 'ปานกลาง';
            $overall_class = 'rating-average';
        } elseif ($overall_avg >= 1.5) {
            $overall_level = 'พอใช้';
            $overall_class = 'rating-fair';
        } else {
            $overall_level = 'ปรับปรุง';
            $overall_class = 'rating-improve';
        }
        
        $html .= '<tr class="summary-row">
            <td class="right"><b>สรุปรวมทั้งหมด</b></td>
            <td class="center"><b>' . number_format($overall_avg, 2) . '</b></td>
            <td class="center"><b>' . $overall_level . '</b></td>
            <td class="center"><b></b></td>
        </tr>';
    }
}

$html .= '
    </tbody>
</table>

<br>

<!-- ตารางข้อเสนอแนะ -->
<table>
    <tr class="suggestion-header">
        <td>อาการของรถที่ผิดปกติ/สิ่งที่ต้องปรับปรุง</td>
    </tr>
    <tbody>';

if (count($suggestions) > 0) {
    foreach ($suggestions as $idx => $suggestion) {
        $html .= '<tr>
            <td>' . ($idx + 1) . '.) ' . htmlspecialchars($suggestion['comment']) . ' - จากลูกค้า: ' . htmlspecialchars($suggestion['customer_name']) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td class="center" style="color: #888;">- ไม่มีข้อเสนอแนะ -</td></tr>';
}

$html .= '
    </tbody>
</table>

<div style="text-align: right; margin-top: 15px; font-size: 10px;">
    <b>ออกรายงาน:</b> ' . date('d/m/Y H:i:s') . '<br>
    <b>จำนวนใบประเมินทั้งหมด:</b> ' . number_format($total_responses) . ' ใบ<br>
    <b>จำนวนคำถาม:</b> ' . count($question_stats) . ' ข้อ<br>
    <b>คะแนนเฉลี่ยรวม:</b> ' . (isset($overall_avg) ? number_format($overall_avg, 2) : '0.00') . ' / 5.00
</div>
';

// สร้าง PDF
$mpdf->WriteHTML($html);

// กำหนดชื่อไฟล์ PDF
$filename = "รายงานการประเมินความพึงพอใจลูกค้า_" . date('Y-m-d_H-i-s') . ".pdf";

// แสดง PDF ในเบราว์เซอร์
$mpdf->Output($filename, 'I');
?>