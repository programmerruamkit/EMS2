<?php
$path = "./";
require($path.'../include/connect.php');

$SESSION_AREA = $_SESSION["AD_AREA"];

// รับ survey_id จาก URL หรือใช้ default
$survey_id = isset($_GET['sm']) ? $_GET['sm'] : 1;

// ดึงข้อมูลแบบประเมิน
$stmt_survey = "SELECT SM_CODE,SM_NAME,SM_TARGET_GROUP FROM SURVEY_MAIN WHERE SM_ID = ? AND SM_STATUS = 'Y'";
$params_survey = array($survey_id);
$query_survey = sqlsrv_query($conn, $stmt_survey, $params_survey);
$survey_data = sqlsrv_fetch_array($query_survey, SQLSRV_FETCH_ASSOC);

// ดึงข้อมูลแจ้งซ่อม
$repair_data = null;
$existing_survey_data = null;
$existing_answers = array();

// ถ้ามี code parameter มาแสดงว่าต้องการแก้ไขข้อมูลเดิม
if (isset($_GET['code']) && !empty($_GET['code'])) {
    $survey_code = $_GET['code'];
    
    // ดึงข้อมูลการประเมินเดิม
    $stmt_existing = "SELECT sr.*, vr.* FROM SURVEY_RESPONSES sr 
                      LEFT JOIN vwREPAIRREQUEST vr ON sr.SR_REPAIR_ID = vr.RPRQ_ID 
                      WHERE sr.SR_CODE = ?";
    $params_existing = array($survey_code);
    $query_existing = sqlsrv_query($conn, $stmt_existing, $params_existing);
    if ($query_existing) {
        $existing_survey_data = sqlsrv_fetch_array($query_existing, SQLSRV_FETCH_ASSOC);
        if ($existing_survey_data) {
            $repair_data = $existing_survey_data; // ใช้ข้อมูลจาก existing survey
            
            // ดึงคำตอบเดิม
            $stmt_answers = "SELECT sa.SQ_ID, sa.SA_RATING, sa.SA_COMMENT 
                            FROM SURVEY_ANSWERS sa 
                            WHERE sa.SR_ID = ? AND sa.SA_STATUS = '1'";
            $params_answers = array($existing_survey_data['SR_ID']);
            $query_answers = sqlsrv_query($conn, $stmt_answers, $params_answers);
            while ($answer = sqlsrv_fetch_array($query_answers, SQLSRV_FETCH_ASSOC)) {
                $existing_answers[$answer['SQ_ID']] = array(
                    'rating' => $answer['SA_RATING'],
                    'comment' => $answer['SA_COMMENT']
                );
            }
        }
    }
}

if (isset($_GET['rp']) && !empty($_GET['rp'])) {
    $repair_id = $_GET['rp'];
    
    // ดึงข้อมูลจากตาราง MBCALL หรือตารางที่เก็บข้อมูลแจ้งซ่อม
    $stmt_repair = "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_ID = ?";
    $params_repair = array($repair_id);
    $query_repair = sqlsrv_query($conn, $stmt_repair, $params_repair);
    if ($query_repair) {
        $repair_data = sqlsrv_fetch_array($query_repair, SQLSRV_FETCH_ASSOC);
    }
}

if (!$survey_data || !$repair_data) {
?>
    <!DOCTYPE html>
    <html lang="th">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>ไม่พบแบบประเมิน</title>
            <link rel="shortcut icon" type="image/x-icon" href="<?=$iconimage;?>">
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: 'Sarabun', Arial, sans-serif;
                    background: linear-gradient(135deg, #F5F5F5 0%, #DCDCDC 100%);
                    /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #333;
                }
                .error-container {
                    background: white;
                    padding: 60px 40px;
                    border-radius: 20px;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                    text-align: center;
                    max-width: 700px;
                    width: 90%;
                    animation: fadeInUp 0.6s ease-out;
                }
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                .error-icon {
                    width: 100px;
                    height: 100px;
                    margin: 0 auto 30px;
                    background: linear-gradient(135deg, #ff6b6b, #ffa726);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 50px;
                    color: white;
                    animation: pulse 2s infinite;
                }
                @keyframes pulse {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                }
                .error-title {
                    font-size: 32px;
                    font-weight: bold;
                    color: #2c3e50;
                    margin-bottom: 20px;
                }
                .error-message {
                    font-size: 18px;
                    color: #7f8c8d;
                    margin-bottom: 30px;
                    line-height: 1.6;
                }
                .error-details {
                    background: #f8f9fa;
                    padding: 20px;
                    border-radius: 10px;
                    margin-bottom: 30px;
                    border-left: 4px solid #e74c3c;
                }
                .error-details h4 {
                    color: #e74c3c;
                    margin-bottom: 10px;
                    font-size: 16px;
                }
                .error-details p {
                    color: #6c757d;
                    font-size: 14px;
                    margin: 5px 0;
                }
                .back-btn {
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    color: white;
                    padding: 15px 30px;
                    border: none;
                    border-radius: 50px;
                    font-size: 16px;
                    font-weight: bold;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    text-decoration: none;
                    display: inline-block;
                    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
                }
                .back-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
                }
                .contact-info {
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 1px solid #ecf0f1;
                    font-size: 14px;
                    color: #95a5a6;
                }
                .search-section {
                    background: #f8f9fa;
                    padding: 20px;
                    border-radius: 10px;
                    margin-bottom: 30px;
                    border-left: 4px solid #3498db;
                }
                .search-form {
                    display: flex;
                    gap: 10px;
                    margin-bottom: 10px;
                    flex-wrap: wrap;
                }
                .search-input {
                    flex: 1;
                    min-width: 250px;
                    padding: 12px 15px;
                    border: 2px solid #3498db;
                    border-radius: 8px;
                    font-size: 16px;
                    outline: none;
                    transition: all 0.3s ease;
                }
                .search-input:focus {
                    border-color: #2980b9;
                    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
                }
                .search-btn {
                    background: linear-gradient(135deg, #3498db, #2980b9);
                    color: white;
                    padding: 12px 25px;
                    border: none;
                    border-radius: 8px;
                    font-size: 16px;
                    font-weight: bold;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
                    min-width: 100px;
                }
                .search-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
                }
                .search-btn:active {
                    transform: translateY(0);
                }
                @media (max-width: 600px) {
                    .search-form {
                        flex-direction: column;
                    }
                    .search-input {
                        min-width: 100%;
                    }
                }
            </style>
            <script>
                function searchRepair() {
                    const repairId = document.getElementById('repairId').value.trim();
                    
                    if (!repairId) {
                        alert('กรุณากรอกเลขที่แจ้งซ่อม');
                        return;
                    }
                    
                    // สร้าง URL ใหม่พร้อมพารามิเตอร์
                    const surveyId = <?= $survey_id ?>;
                    const url = `customer_survey.php?sm=${surveyId}&rp=${encodeURIComponent(repairId)}`;
                    
                    // เด้งไปหน้าใหม่
                    window.location.href = url;
                }
                
                // ให้สามารถกด Enter ในช่อง input ได้
                document.addEventListener('DOMContentLoaded', function() {
                    const input = document.getElementById('repairId');
                    if (input) {
                        input.addEventListener('keypress', function(e) {
                            if (e.key === 'Enter') {
                                searchRepair();
                            }
                        });
                    }
                });
            </script>
        </head>
        <body>
            <div class="error-container">
                <div class="error-icon">
                    📋
                </div>
                <h1 class="error-title">แบบประเมินความพึงพอใจ</h1>
                <p class="error-message">
                    เพื่อให้เราสามารถปรับปรุงและพัฒนาการบริการให้ดียิ่งขึ้น<br>
                    กรุณากรอกเลขที่แจ้งซ่อมเพื่อเริ่มทำแบบประเมิน
                </p>
                
                <div class="search-section">
                    <h4 style="color: #2c3e50; margin-bottom: 15px;">กรุณากรอกเลขที่แจ้งซ่อมของท่าน:</h4>
                    <div class="search-form">
                        <input type="number" id="repairId" inputmode="numeric" placeholder="กรอกเลขที่แจ้งซ่อม 6 หลัก เช่น 012345" class="search-input" autocomplete="off">
                        <button onclick="searchRepair()" class="search-btn">ค้นหา</button>
                    </div>
                    <p style="color: #7f8c8d; font-size: 14px; margin-top: 10px;">
                        * หากไม่ทราบเลขที่แจ้งซ่อม กรุณาติดต่อฝ่ายบริการลูกค้า
                    </p>
                </div>
                
                <div class="contact-info">
                    หากต้องการความช่วยเหลือ กรุณาติดต่อ<br>
                    <strong>บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด</strong><br>
                    <!-- โทร: 02-xxx-xxxx -->
                </div>
            </div>
        </body>
    </html>
<?php
    exit();
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$title;?></title>
	<link rel="shortcut icon" type="image/x-icon" href="<?=$iconimage;?>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Sarabun', Arial, sans-serif;
            /* background: linear-gradient(135deg, #F5F5F5 0%, #DCDCDC 100%); */
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: white;
            color: black;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header-left {
            flex: 0 0 100px;
        }
        .header-center {
            flex: 1;
            text-align: center;
        }
        .header-right {
            flex: 0 0 100px;
            text-align: right;
        }
        .header-logo {
            max-width: 80px;
            max-height: 80px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 16px;
        }
        .form-section {
            padding: 20px;
        }
        .customer-info {
            border: 2px solid #333;
            margin-bottom: 20px;
            padding: 15px;
        }
        .info-row {
            display: flex;
            margin-bottom: 15px;
            gap: 20px;
            flex-wrap: wrap;
        }
        .info-column {
            display: flex;
            align-items: center;
            flex: 1;
            min-width: 250px;
        }
        .info-label {
            min-width: 120px;
            font-weight: bold;
            margin-right: 10px;
        }
        .info-input {
            flex: 1;
            padding: 8px;
            border: none;
            border-bottom: 1px solid #333;
            font-size: 16px;
        }
        .info-text {
            font-weight: bold;
        }
        .checkbox-group {
            display: flex;
            gap: 20px;
            margin-left: 120px;
        }
        .evaluation-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #333;
            margin-bottom: 20px;
            table-layout: auto;
        }
        .evaluation-table th,
        .evaluation-table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
            word-wrap: break-word;
        }
        .evaluation-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .question-cell {
            text-align: left;
            padding: 15px 10px;
            width: 700px;
            /* height: 10px; */
        }
        .evaluation-table td.question-text {
            text-align: left;
            min-width: 300px;
            max-width: 1500px;
        }
        .comment-cell {
            min-width: 150px;
            max-width: 250px;
            padding: 0; /* เอา padding ออก */
            vertical-align: middle;
        }
        
        .comment-input {
            width: 100%;
            /* height: 100%; */
            border: none;
            /* padding: 10px; */
            font-size: 15px;
            font-family: inherit;
            outline: none;
            resize: none;
            background: transparent;
            box-sizing: border-box;
            /* min-height: 0px; */
        }
        .rating-cell {
            width: 80px;
            min-width: 80px;
            padding: 15px 10px;
        }
        .rating-input {
            transform: scale(2.0);
            cursor: pointer;
            margin: 0 auto;
            width: 15px;
            /* height: 20px; */
            display: block;
        }
        .rating-input:hover {
            transform: scale(2.2);
            transition: transform 0.2s ease;
        }
        .rating-input:checked {
            accent-color: #2c5aa0;
        }
        .comments-section {
            margin-top: 20px;
        }
        .comments-label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }
        .comments-textarea {
            width: 100%;
            min-height: 100px;
            padding: 10px;
            border: 2px solid #333;
            resize: vertical;
            font-family: inherit;
        }
        .submit-section {
            text-align: center;
            padding: 20px;
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
        }
        .submit-btn {
            background-color: #2c5aa0;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin: 0 10px;
        }
        .submit-btn:hover {
            background-color: #1e3d6f;
        }
        .reset-btn {
            background-color: #6c757d;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin: 0 10px;
        }
        .reset-btn:hover {
            background-color: #545b62;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container"> 
        <div class="header">
            <div class="header-left">
                <img src="../img/logonew.png" alt="Logo" class="header-logo">
            </div>
            <div class="header-center">
                <h1>บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด</h1>
                <p>RUAMKIT RUNGRUENG TRUCK DETAILS CO.,LTD.</p>
            </div>
            <div class="header-right">
                <img src="../img/hino.png" alt="Logo" class="header-logo">
            </div>
        </div>
        <hr>
        
        <form id="surveyForm" method="POST" action="customer_survey_proc.php">
            <input type="hidden" name="survey_id" value="<?= $survey_id ?>">
            <?php if ($repair_data): ?>
                <input type="hidden" name="repair_id" value="<?= htmlspecialchars($repair_data['RPRQ_ID']) ?>">
            <?php endif; ?>
            <?php if ($existing_survey_data): ?>
                <input type="hidden" name="existing_survey_code" value="<?= htmlspecialchars($existing_survey_data['SR_CODE']) ?>">
                <input type="hidden" name="is_edit_mode" value="1">
            <?php endif; ?>
            
            <div class="form-section">
                <div class="customer-info">
                    <!-- แถวที่ 1: 2 คอลัมน์ -->
                    <div class="info-row">
                        <div class="info-column">
                            <span class="info-label">ชื่อลูกค้า:</span>
                            <input type="text" name="customer_name" class="info-input" 
                                value="<?= $repair_data ? htmlspecialchars(!empty($repair_data['RPRQ_LINEOFWORK']) ? $repair_data['RPRQ_LINEOFWORK'] : $repair_data['RPRQ_COMPANYCASH']) : '' ?>" required readonly style="background-color: #f5f5f5;">
                        </div>
                        <div class="info-column">
                            <span class="info-label">วัน/เดือน/ปี:</span>
                            <input type="date" name="survey_date" class="info-input" value="<?= date('Y-m-d') ?>" required readonly style="background-color: #f5f5f5;">
                        </div>
                    </div>
                    
                    <!-- แถวที่ 2: 2 คอลัมน์ -->
                    <div class="info-row">
                        <div class="info-column">
                            <span class="info-label">รายการซ่อม:</span>
                            <input type="text" name="repair_detail" class="info-input" 
                                value="<?= $repair_data ? htmlspecialchars($repair_data['RPC_DETAIL']) : '' ?>" readonly style="background-color: #f5f5f5;">
                            <?php if ($repair_data): ?>
                                <input type="hidden" name="repair_code" value="<?= htmlspecialchars($repair_data['MC_CODE']) ?>" readonly style="background-color: #f5f5f5;">
                            <?php endif; ?>
                        </div>
                        <div class="info-column">
                            <span class="info-label">ทะเบียนรถ:</span>
                            <input type="text" name="vehicle_license" class="info-input" 
                                value="<?= $repair_data ? htmlspecialchars($repair_data['RPRQ_REGISHEAD']) : '' ?>" readonly style="background-color: #f5f5f5;">
                        </div>
                    </div>
                    
                    <?php if ($repair_data): ?>
                    <!-- แถวที่ 3: ข้อมูลเพิ่มเติมจากแจ้งซ่อม -->
                    <div class="info-row">
                        <div class="info-column">
                            <span class="info-label">รหัสแจ้งซ่อม:</span>
                            <input type="text" class="info-input" value="<?= htmlspecialchars($repair_data['RPRQ_ID']) ?>" readonly style="background-color: #f5f5f5;">
                        </div>
                        <div class="info-column">
                            <span class="info-label">วันที่แจ้งซ่อม:</span>
                            <input type="text" class="info-input" 
                                value="<?= $repair_data['RPRQ_CREATEDATE'] ? $repair_data['RPRQ_CREATEDATE']->format('d/m/Y') : '' ?>" 
                                readonly style="background-color: #f5f5f5;">
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- แถวระดับความพึงพอใจ -->
                    <div class="info-row">
                        <div class="info-column" style="flex: none; min-width: auto;">
                            <span class="info-text">ระดับความพึงพอใจ : (ดีมาก ดี ปรับปรุง)</span>
                        </div>
                    </div>
                </div>

                <table class="evaluation-table">
                    <thead>
                        <tr>
                            <th rowspan="2">หัวข้อความพึงพอใจ</th>
                            <th colspan="3">ระดับความพึงพอใจ</th>
                            <th rowspan="2">ความคิดเห็น</th>
                        </tr>
                        <tr>
                            <th>ดีมาก</th>
                            <th>ดี</th>
                            <th>*ปรับปรุง</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // ดึงหมวดหมู่และคำถาม
                        $stmt_categories = "SELECT SC_ID, SC_CODE, SC_NAME FROM SURVEY_CATEGORY WHERE SM_CODE = ? AND SC_STATUS = 'Y' ORDER BY SC_ORDER";
                        $params_categories = array($survey_data['SM_CODE']);
                        $query_categories = sqlsrv_query($conn, $stmt_categories, $params_categories);
                        
                        $index = 0;
                        while($category = sqlsrv_fetch_array($query_categories, SQLSRV_FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td class="question-text question-cell"><b><?= htmlspecialchars($category['SC_NAME']) ?></b></td>
                        </tr>
                        <?php
                            // ดึงคำถามในแต่ละหมวดหมู่
                            $stmt_questions = "SELECT SQ_ID,SQ_ORDER,SQ_QUESTION FROM SURVEY_QUESTION WHERE SC_CODE = ? AND SQ_STATUS = 'Y' ORDER BY SQ_ORDER";
                            $params_questions = array($category['SC_CODE']);
                            $query_questions = sqlsrv_query($conn, $stmt_questions, $params_questions);
                            
                            while($question = sqlsrv_fetch_array($query_questions, SQLSRV_FETCH_ASSOC)) {
                        ?>
                        <?php 
                            // ดึงข้อมูลคำตอบเดิมถ้ามี
                            $existing_rating = isset($existing_answers[$question['SQ_ID']]) ? $existing_answers[$question['SQ_ID']]['rating'] : '';
                            $existing_comment = isset($existing_answers[$question['SQ_ID']]) ? $existing_answers[$question['SQ_ID']]['comment'] : '';
                        ?>
                        <tr>
                            <td class="question-text question-cell"><?= $question['SQ_ORDER'].'.)' ?> <?= htmlspecialchars($question['SQ_QUESTION']) ?></td>
                            <td class="rating-cell">
                                <input type="radio" name="rating_<?= $index ?>" value="5" class="rating-input" required <?= ($existing_rating == '5') ? 'checked' : '' ?>>
                            </td>
                            <td class="rating-cell">
                                <input type="radio" name="rating_<?= $index ?>" value="4" class="rating-input" required <?= ($existing_rating == '4') ? 'checked' : '' ?>>
                            </td>
                            <td class="rating-cell">
                                <input type="radio" name="rating_<?= $index ?>" value="1" class="rating-input" required <?= ($existing_rating == '1') ? 'checked' : '' ?>>
                            </td>
                            <td class="comment-cell">
                                <input type="text" name="comment_<?= $index ?>" class="comment-input" autocomplete="off" value="<?= htmlspecialchars($existing_comment) ?>">
                                <input type="hidden" name="question_id_<?= $index ?>" value="<?= $question['SQ_ID'] ?>">
                            </td>
                        </tr>
                        <?php 
                                $index++;
                            }
                        } 
                        ?>
                        <input type="hidden" name="total_questions" value="<?= $index ?>">
                    </tbody>
                </table>

                <div class="comments-section">
                    <label class="comments-label">ความคิดเห็น สิ่งที่อยากปรับปรุง:</label>
                    <textarea name="additional_comments" class="comments-textarea" placeholder="กรุณาแสดงความคิดเห็นเพิ่มเติม..."><?= $existing_survey_data ? htmlspecialchars($existing_survey_data['SR_ADDITIONAL_COMMENTS']) : '' ?></textarea>
                </div>
            </div>

            <div class="submit-section">
                <?php
                // ตรวจสอบว่ามีข้อมูลการประเมินเก่าหรือไม่
                $has_previous_response = false;
                $is_edit_mode = ($existing_survey_data != null);
                
                if (!$is_edit_mode && isset($_GET['rp']) && !empty($_GET['rp'])) {
                    $repair_id = $_GET['rp'];
                    $stmt_check = "SELECT COUNT(*) as response_count FROM SURVEY_RESPONSES WHERE SR_REPAIR_ID = ?";
                    $params_check = array($repair_id);
                    $query_check = sqlsrv_query($conn, $stmt_check, $params_check);
                    if ($query_check) {
                        $check_result = sqlsrv_fetch_array($query_check, SQLSRV_FETCH_ASSOC);
                        $has_previous_response = ($check_result['response_count'] > 0);
                    }
                }
                
                if ($is_edit_mode) {
                    $submit_button_text = 'บันทึกการแก้ไข';
                } else {
                    $submit_button_text = $has_previous_response ? 'ส่งแบบประเมินอีกครั้ง' : 'ส่งแบบประเมิน';
                }
                ?>
                <button type="submit" class="submit-btn"><?= $submit_button_text ?></button>
                <button type="reset" class="reset-btn">ล้างข้อมูล</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('surveyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // ตรวจสอบข้อมูลพื้นฐาน
            const customerName = document.querySelector('input[name="customer_name"]').value;
            
            if (!customerName) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                    text: 'กรุณากรอกชื่อลูกค้า'
                });
                return;
            }

            // ตรวจสอบการให้คะแนน
            const totalQuestions = document.querySelector('input[name="total_questions"]').value;
            let allRated = true;
            for (let i = 0; i < totalQuestions; i++) {
                const rated = document.querySelector(`input[name="rating_${i}"]:checked`);
                if (!rated) {
                    allRated = false;
                    break;
                }
            }

            if (!allRated) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาประเมินให้ครบทุกข้อ',
                    text: 'กรุณาให้คะแนนความพึงพอใจในทุกหัวข้อ'
                });
                return;
            }

            // ถามยืนยันก่อนส่งข้อมูล
            const isEditMode = <?= $is_edit_mode ? 'true' : 'false' ?>;
            const isRepeatSurvey = <?= $has_previous_response ? 'true' : 'false' ?>;
            
            let confirmTitle, confirmText;
            if (isEditMode) {
                confirmTitle = 'ยืนยันการบันทึกการแก้ไข';
                confirmText = 'คุณต้องการบันทึกการแก้ไขแบบประเมินนี้หรือไม่?\nข้อมูลเดิมจะถูกอัพเดท';
            } else if (isRepeatSurvey) {
                confirmTitle = 'ยืนยันการส่งแบบประเมินอีกครั้ง';
                confirmText = 'คุณต้องการส่งแบบประเมินอีกครั้งหรือไม่?\nข้อมูลการประเมินครั้งก่อนจะยังคงอยู่';
            } else {
                confirmTitle = 'ยืนยันการส่งแบบประเมิน';
                confirmText = 'คุณต้องการส่งแบบประเมินนี้หรือไม่?\nกรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนส่ง';
            }

            Swal.fire({
                title: confirmTitle,
                html: confirmText.replace(/\n/g, '<br>'), // เปลี่ยนจาก text เป็น html และแปลง \n เป็น <br>
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2c5aa0',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ใช่, ส่งแบบประเมิน',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // แสดง loading
                    Swal.fire({
                        title: 'กำลังบันทึกข้อมูล...',
                        text: 'กรุณารอสักครู่',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // ส่งข้อมูล
                    const formData = new FormData(this);
                    
                    fetch('customer_survey_proc.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let successMessage, successTitle;
                            if (isEditMode) {
                                successTitle = 'บันทึกการแก้ไขเรียบร้อย!';
                                successMessage = 'ข้อมูลการประเมินของท่าน\nได้รับการอัพเดทเรียบร้อยแล้ว';
                            } else if (isRepeatSurvey) {
                                successTitle = 'ขอบคุณสำหรับการประเมิน!';
                                successMessage = 'ข้อมูลการประเมินอีกครั้งของท่าน\nได้รับการบันทึกเรียบร้อยแล้ว';
                            } else {
                                successTitle = 'ขอบคุณสำหรับการประเมิน!';
                                successMessage = 'ข้อมูลของท่าน\nได้รับการบันทึกเรียบร้อยแล้ว';
                            }
                            
                            Swal.fire({
                                icon: 'success',
                                title: successTitle,
                                html: successMessage.replace(/\n/g, '<br>'), // เปลี่ยนจาก text เป็น html
                                // confirmButtonText: 'ตกลง',
                                // confirmButtonColor: '#2c5aa0',
                                showConfirmButton: false,
                                timer: 5000,
                                timerProgressBar: true
                            }).then(() => {
                                // ไม่ล้างฟอร์มในโหมดแก้ไข
                                if (!isEditMode) {
                                    this.reset();
                                }
                            });
                        } else {
                            throw new Error(data.message || 'เกิดข้อผิดพลาด');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: error.message || 'ไม่สามารถบันทึกข้อมูลได้\nกรุณาลองใหม่อีกครั้ง',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#dc3545'
                        });
                    });
                }
            });
        });
    </script>
</body>
</html>