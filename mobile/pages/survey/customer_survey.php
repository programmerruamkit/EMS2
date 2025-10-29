<?php 
    include('../../include/db.php');
    session_name("EMS"); // session_start();
    error_reporting(E_ALL); //E_ALL แสดง error ทั้งหมด | ใส่ 0 ปิดแสดง error ทั้งหมด
    date_default_timezone_set('Asia/Bangkok');
        $serverName = $HOST;
        $userName = $USERNAME;
        $userPassword = $PASSWORD;
        $dbName = $DATABASE;

    $connectionInfo = array("Database"=>$dbName, "UID"=>$userName, "PWD"=>$userPassword, "MultipleActiveResultSets"=>true,"CharacterSet"  => 'UTF-8');
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    if($conn)
    {
        // echo "Database Connected.";
    }else{
        // die( print_r( sqlsrv_errors(), true));
    }
	###########################################################################################################

    $path = "../../";   
	$title="E-Maintenance";	
	$iconimage="https://img2.pic.in.th/pic/car_repair.png";	
    
	###########################################################################################################

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
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.4;
        }
        
        .container {
            max-width: 1200px;
            margin: 10px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        /* Header Responsive */
        .header {
            background: white;
            color: black;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .header-left, .header-right {
            flex: 0 0 auto;
        }
        
        .header-center {
            flex: 1;
            text-align: center;
            min-width: 250px;
        }
        
        .header-logo {
            max-width: 70px;
            max-height: 70px;
            width: auto;
            height: auto;
        }
        
        .header h1 {
            font-size: clamp(16px, 4vw, 24px);
            margin-bottom: 8px;
            line-height: 1.2;
        }
        
        .header p {
            font-size: clamp(12px, 2.5vw, 16px);
            opacity: 0.9;
        }
        
        /* Form Section Responsive */
        .form-section {
            padding: 15px 20px;
        }
        
        .customer-info {
            border: 2px solid #333;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 15px;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .info-column {
            display: flex;
            align-items: center;
            flex: 1;
            min-width: 280px;
            gap: 10px;
        }
        
        .info-label {
            min-width: 100px;
            font-weight: bold;
            font-size: clamp(14px, 2vw, 16px);
            flex-shrink: 0;
        }
        
        .info-input {
            flex: 1;
            padding: 8px 10px;
            border: none;
            border-bottom: 1px solid #333;
            font-size: clamp(14px, 2vw, 16px);
            min-width: 0;
        }
        
        .info-text {
            font-weight: bold;
            font-size: clamp(14px, 2vw, 16px);
        }
        
        .checkbox-group {
            display: flex;
            gap: 15px;
            margin-left: 100px;
            flex-wrap: wrap;
        }
        
        /* Table Responsive Styles */
        .evaluation-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #333;
            margin-bottom: 20px;
            font-size: clamp(12px, 1.8vw, 15px);
            table-layout: auto;
        }
        
        .evaluation-table th,
        .evaluation-table td {
            border: 1px solid #333;
            padding: 8px 6px;
            text-align: center;
            word-wrap: break-word;
            vertical-align: middle;
        }
        
        .evaluation-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: clamp(12px, 2vw, 14px);
            line-height: 1.3;
        }
        
        .question-cell {
            text-align: left !important;
            padding: 12px 10px !important;
            min-width: 200px;
            max-width: none;
            word-break: break-word;
            hyphens: auto;
        }
        
        .evaluation-table td.question-text {
            text-align: left !important;
            min-width: 200px;
        }
        
        .rating-cell {
            width: 60px;
            min-width: 50px;
            max-width: 80px;
            padding: 10px 5px !important;
        }
        
        .rating-input {
            transform: scale(1.8);
            cursor: pointer;
            margin: 0 auto;
            display: block;
            min-width: 18px;
            min-height: 18px;
        }
        
        .rating-input:hover {
            transform: scale(2.0);
            transition: transform 0.2s ease;
        }
        
        .rating-input:checked {
            accent-color: #2c5aa0;
        }
        
        .comment-cell {
            min-width: 120px;
            max-width: 200px;
            padding: 2px !important;
            vertical-align: middle;
        }
        
        .comment-input {
            width: 100%;
            height: 100%;
            border: none;
            padding: 8px 6px;
            font-size: clamp(12px, 1.8vw, 14px);
            font-family: inherit;
            outline: none;
            resize: none;
            background: transparent;
            box-sizing: border-box;
            min-height: 40px;
        }
        
        /* Comments Section */
        .comments-section {
            margin-top: 20px;
        }
        
        .comments-label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
            font-size: clamp(14px, 2vw, 16px);
        }
        
        .comments-textarea {
            width: 100%;
            min-height: 80px;
            padding: 12px;
            border: 2px solid #333;
            resize: vertical;
            font-family: inherit;
            font-size: clamp(14px, 2vw, 16px);
            border-radius: 4px;
        }
        
        /* Submit Section */
        .submit-section {
            text-align: center;
            padding: 20px;
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .submit-btn, .reset-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: clamp(14px, 2vw, 16px);
            cursor: pointer;
            font-weight: bold;
            min-width: 140px;
            transition: all 0.3s ease;
        }
        
        .submit-btn {
            background-color: #2c5aa0;
            color: white;
        }
        
        .submit-btn:hover {
            background-color: #1e3d6f;
            transform: translateY(-1px);
        }
        
        .reset-btn {
            background-color: #6c757d;
            color: white;
        }
        
        .reset-btn:hover {
            background-color: #545b62;
            transform: translateY(-1px);
        }
        
        /* Tablet Styles (768px and below) */
        @media (max-width: 768px) {
            .container {
                margin: 5px;
                border-radius: 0;
            }
            
            .header {
                flex-direction: column;
                text-align: center;
                padding: 15px;
                gap: 10px;
            }
            
            .header-left, .header-right {
                order: 1;
            }
            
            .header-center {
                order: 2;
                width: 100%;
                min-width: auto;
            }
            
            .header-logo {
                max-width: 60px;
                max-height: 60px;
            }
            
            .form-section {
                padding: 15px;
            }
            
            .info-column {
                min-width: 100%;
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .info-label {
                min-width: auto;
                width: 100%;
            }
            
            .info-input {
                width: 100%;
            }
            
            .checkbox-group {
                margin-left: 0;
                justify-content: flex-start;
            }
            
            /* Mobile Table Layout - Card Style */
            .evaluation-table {
                font-size: 12px;
                display: block;
                overflow-x: auto;
                border: none;
            }
            
            .evaluation-table thead {
                display: none;
            }
            
            .evaluation-table tbody {
                display: block;
            }
            
            .evaluation-table tr {
                display: block;
                border: 2px solid #333;
                margin-bottom: 15px;
                padding: 15px;
                background: #f9f9f9;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            
            .evaluation-table tr:empty {
                display: none;
            }
            
            .evaluation-table td {
                display: block;
                border: none;
                padding: 8px 0;
                text-align: left !important;
                position: relative;
            }
            
            /* Category headers */
            .evaluation-table tr:has(.question-cell b) {
                background: #e3f2fd;
                border-color: #2196f3;
                margin-bottom: 10px;
                padding: 10px 15px;
            }
            
            .evaluation-table tr:has(.question-cell b) .question-cell {
                text-align: center !important;
                font-size: 16px;
                color: #1976d2;
                margin: 0;
                padding: 0;
            }
            
            /* Question rows */
            .question-cell {
                background: #fff;
                padding: 10px !important;
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-weight: 500;
                line-height: 1.4;
            }
            
            .rating-cell {
                width: auto;
                min-width: auto;
                max-width: none;
                padding: 10px 0 !important;
                display: flex;
                align-items: center;
                justify-content: flex-start;
            }
            
            .rating-cell:nth-child(2):after { content: " 🌟 ดีมาก"; }
            .rating-cell:nth-child(3):after { content: " 👍 ดี"; }
            .rating-cell:nth-child(4):after { content: " ⚠️ ปรับปรุง"; }
            
            .rating-cell:after {
                font-weight: bold;
                color: #333;
                font-size: 14px;
                margin-left: 8px;
            }
            
            .rating-input {
                transform: scale(1.5);
                margin-right: auto;
                position: relative;
                float: left;
            }
            
            .comment-cell {
                min-width: auto;
                max-width: none;
                padding: 10px 0 !important;
            }
            
            .comment-cell:before {
                content: "💬 ความคิดเห็น: ";
                font-weight: bold;
                color: #333;
                display: block;
                margin-bottom: 8px;
            }
            
            .comment-input {
                width: 100%;
                min-height: 50px;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background: white;
                font-size: 14px;
            }
            
            .submit-section {
                flex-direction: column;
                align-items: center;
            }
            
            .submit-btn, .reset-btn {
                width: 100%;
                max-width: 300px;
                margin-bottom: 10px;
            }
        }
        
        /* Mobile Styles (480px and below) */
        @media (max-width: 480px) {
            .container {
                margin: 0;
            }
            
            .header {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 16px;
                line-height: 1.3;
            }
            
            .header p {
                font-size: 12px;
            }
            
            .form-section {
                padding: 10px;
            }
            
            .customer-info {
                padding: 10px;
            }
            
            .info-row {
                gap: 10px;
                margin-bottom: 10px;
            }
            
            .evaluation-table tr {
                padding: 12px;
                margin-bottom: 12px;
            }
            
            .rating-input {
                transform: scale(1.3);
            }
            
            .comment-input {
                font-size: 13px;
                min-height: 40px;
            }
            
            .comments-textarea {
                min-height: 60px;
                padding: 8px;
            }
            
            .submit-section {
                padding: 15px 10px;
            }
        }
        
        /* Very Small Screens (320px and below) */
        @media (max-width: 320px) {
            .header h1 {
                font-size: 14px;
            }
            
            .header p {
                font-size: 11px;
            }
            
            .evaluation-table {
                font-size: 11px;
            }
            
            .info-label {
                font-size: 13px;
            }
            
            .info-input {
                font-size: 13px;
                padding: 6px 8px;
            }
            
            .submit-btn, .reset-btn {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
        
        /* Landscape Mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .header {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                padding: 10px 15px;
            }
            
            .header-center {
                order: 0;
            }
            
            .header-left, .header-right {
                order: 1;
            }
            
            .evaluation-table {
                display: table;
                font-size: 11px;
            }
            
            .evaluation-table thead {
                display: table-header-group;
            }
            
            .evaluation-table tbody {
                display: table-row-group;
            }
            
            .evaluation-table tr {
                display: table-row;
                border: none;
                margin: 0;
                padding: 0;
                background: transparent;
                box-shadow: none;
            }
            
            .evaluation-table td {
                display: table-cell;
                border: 1px solid #333;
                padding: 5px 3px;
            }
            
            .evaluation-table td:before {
                content: none;
            }
            
            .question-cell {
                text-align: left !important;
                background: transparent;
                border: 1px solid #333;
                margin: 0;
            }
            
            .rating-cell {
                display: table-cell;
                text-align: center;
            }
            
            .rating-input {
                transform: scale(1.2);
                margin: 0 auto;
                float: none;
            }
            
            .comment-input {
                border: none;
                background: transparent;
                min-height: 30px;
            }
        }
        
        /* Print Styles */
        @media print {
            .submit-section {
                display: none;
            }
            
            .container {
                box-shadow: none;
                margin: 0;
            }
            
            .evaluation-table {
                font-size: 12px;
            }
            
            .header {
                border-bottom: 2px solid #333;
            }
        }
        
        /* High DPI Screens */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .rating-input {
                transform: scale(1.6);
            }
            
            .evaluation-table {
                border-width: 1px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container"> 
        <div class="header">
            <div class="header-left">
                <img src="../../../img/logonew.png" alt="Logo" class="header-logo">
            </div>
            <div class="header-center">
                <h1>บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด</h1>
                <p>RUAMKIT RUNGRUENG TRUCK DETAILS CO.,LTD.</p>
            </div>
            <div class="header-right">
                <img src="../../../img/hino.png" alt="Logo" class="header-logo">
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