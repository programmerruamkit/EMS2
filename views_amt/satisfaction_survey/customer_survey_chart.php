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

    // ดึงสถิติการให้คะแนน
    $stmt_rating = "SELECT 
        sa.SA_RATING,
        COUNT(*) as count_rating
    FROM SURVEY_ANSWERS sa
    LEFT JOIN SURVEY_RESPONSES sr ON sr.SR_ID = sa.SR_ID AND sa.SA_STATUS = '1'						
    LEFT JOIN SURVEY_MAIN sm ON sm.SM_ID = sr.SM_ID
    WHERE sa.SA_STATUS = '1' AND sm.SM_AREA = '$SESSION_AREA' ";
    
    $params_rating = array();
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
    $query_rating = sqlsrv_query($conn, $stmt_rating, $params_rating);
    $rating_stats = array(5 => 0, 4 => 0, 1 => 0);
    $total_ratings = 0;
    while ($row_rating = sqlsrv_fetch_array($query_rating, SQLSRV_FETCH_ASSOC)) {
        $rating = $row_rating['SA_RATING'];
        $count = $row_rating['count_rating'];
        $rating_stats[$rating] = $count;
        $total_ratings += $count;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>กราฟการประเมินความพึงพอใจลูกค้า</title>
    <style>
        body {
            font-family: 'Sarabun', Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        .chart-section {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fafafa;
        }
        .chart-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .criteria-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #2196f3;
        }
        .btn-back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-back:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            color: white;
            text-decoration: none;
        }
        .stats-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .stat-card {
            flex: 1;
            min-width: 200px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            border-top: 4px solid #667eea;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- แสดงเงื่อนไขการค้นหา -->
        <div class="criteria-info">
            <h4><i class="fa fa-filter"></i> เงื่อนไขการค้นหา:</h4>
            <?php
                // แสดงชื่อกลุ่มแบบประเมินที่เลือก
                if ($getsurvey) {
                    $stmt_survey_name = "SELECT SM_TARGET_GROUP FROM SURVEY_MAIN WHERE SM_ID = ? AND SM_AREA = '$SESSION_AREA'";
                    $params_survey_name = array($getsurvey);
                    $query_survey_name = sqlsrv_query($conn, $stmt_survey_name, $params_survey_name);
                    $survey_name_row = sqlsrv_fetch_array($query_survey_name, SQLSRV_FETCH_ASSOC);
                    $survey_name = $survey_name_row ? $survey_name_row['SM_TARGET_GROUP'] : 'ไม่ระบุ';
                    echo "<strong>กลุ่มแบบประเมิน:</strong> " . htmlspecialchars($survey_name) . " | ";
                } else {
                    echo "<strong>กลุ่มแบบประเมิน:</strong> ทั้งหมด | ";
                }

                // แสดงช่วงวันที่ที่เลือก
                if ($getselectdaystart) {
                    echo "<strong>วันที่เริ่มต้น:</strong> " . htmlspecialchars($getselectdaystart) . " | ";
                } else {
                    echo "<strong>วันที่เริ่มต้น:</strong> ไม่ระบุ | ";
                }

                if ($getselectdayend) {
                    echo "<strong>วันที่สิ้นสุด:</strong> " . htmlspecialchars($getselectdayend) . " | ";
                } else {
                    echo "<strong>วันที่สิ้นสุด:</strong> ไม่ระบุ | ";
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
                    echo "<strong>ระดับความพึงพอใจ:</strong> " . htmlspecialchars($level_text);
                } else {
                    echo "<strong>ระดับความพึงพอใจ:</strong> ไม่ระบุ";
                }
            ?>
        </div>

        <!-- สถิติโดยรวม -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= number_format($rating_stats[5]) ?></div>
                <div class="stat-label">ดีมาก (5 คะแนน)<br><?= $total_ratings > 0 ? number_format(($rating_stats[5] / $total_ratings) * 100, 1) : 0 ?>%</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($rating_stats[4]) ?></div>
                <div class="stat-label">ดี (4 คะแนน)<br><?= $total_ratings > 0 ? number_format(($rating_stats[4] / $total_ratings) * 100, 1) : 0 ?>%</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($rating_stats[1]) ?></div>
                <div class="stat-label">ปรับปรุง (1 คะแนน)<br><?= $total_ratings > 0 ? number_format(($rating_stats[1] / $total_ratings) * 100, 1) : 0 ?>%</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($total_ratings) ?></div>
                <div class="stat-label">รวมทั้งหมด<br>100%</div>
            </div>
        </div>

        <!-- กราฟคะแนนเฉลี่ยแต่ละหัวข้อ -->
        <div class="chart-section">
            <div class="chart-title">
                <i class="fa fa-bar-chart"></i> คะแนนเฉลี่ยแต่ละหัวข้อความพึงพอใจ
            </div>
            <div id="survey_rating_chart" style="width: 100%; height: 500px;"></div>
        </div>

        <!-- กราฟการแจกแจงคะแนน -->
        <div class="chart-section">
            <div class="chart-title">
                <i class="fa fa-pie-chart"></i> การแจกแจงคะแนนความพึงพอใจ
            </div>
            <div id="question_average_chart" style="width: 100%; height: 400px;"></div>
        </div>
    </div>

    <!-- เพิ่ม Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // โหลด Google Charts
        google.charts.load('current', {'packages':['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            try {
                drawRatingChart();
                drawQuestionChart();
            } catch (error) {
                console.error('Error drawing charts:', error);
            }
        }

        // กราฟคะแนนเฉลี่ยแต่ละคำถาม (Column Chart - แนวตั้ง)
        function drawRatingChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'หัวข้อความพึงพอใจ');
            data.addColumn('number', 'คะแนนเฉลี่ย');
            data.addColumn({type: 'string', role: 'annotation'});
            data.addColumn('number', 'เป้าหมาย');
            
            data.addRows([
                <?php
                // ดึงข้อมูลคะแนนเฉลี่ยแต่ละคำถาม สำหรับกราฟแรก
                $chart_data_1 = array();
                
                // รีเซ็ต pointer ของ query
                $stmt_questions_chart = "SELECT sq.SQ_ID, sq.SQ_ORDER, sq.SQ_QUESTION, sc.SC_ORDER, sc.SC_NAME, 
                AVG(CAST(sa.SA_RATING as FLOAT)) as avg_rating, COUNT(sa.SA_RATING) as total_answers 
                FROM SURVEY_QUESTION sq 
                LEFT JOIN SURVEY_CATEGORY sc ON sq.SC_CODE = sc.SC_CODE 
                LEFT JOIN SURVEY_ANSWERS sa ON sq.SQ_ID = sa.SQ_ID AND sa.SA_STATUS = '1' 
                LEFT JOIN SURVEY_RESPONSES sr ON sa.SR_ID = sr.SR_ID 
                LEFT JOIN SURVEY_MAIN sm ON sr.SM_ID = sm.SM_ID 
                WHERE sq.SQ_STATUS = 'Y' AND sm.SM_AREA = '$SESSION_AREA'";
                
                // เพิ่มเงื่อนไขการค้นหา
                $params_chart = array();
                if (!empty($getsurvey)) {
                    $stmt_questions_chart .= " AND sr.SM_ID = ?";
                    $params_chart[] = $getsurvey;
                }
                if (!empty($getselectdaystart)) {
                    $dateStart = DateTime::createFromFormat('d/m/Y', $getselectdaystart);
                    if ($dateStart) {
                        $formattedDateStart = $dateStart->format('Y-m-d');
                        $stmt_questions_chart .= " AND sr.SR_SURVEY_DATE >= ?";
                        $params_chart[] = $formattedDateStart;
                    }
                }
                if (!empty($getselectdayend)) {
                    $dateEnd = DateTime::createFromFormat('d/m/Y', $getselectdayend);
                    if ($dateEnd) {
                        $formattedDateEnd = $dateEnd->format('Y-m-d');
                        $stmt_questions_chart .= " AND sr.SR_SURVEY_DATE <= ?";
                        $params_chart[] = $formattedDateEnd;
                    }
                }
                
                $stmt_questions_chart .= " GROUP BY sq.SQ_ID, sq.SQ_ORDER, sq.SQ_QUESTION, sc.SC_ORDER, sc.SC_NAME 
                                    ORDER BY sc.SC_ORDER, sq.SQ_ORDER";
                
                $query_questions_chart = sqlsrv_query($conn, $stmt_questions_chart, $params_chart);
                
                $total_sum = 0;
                $total_count = 0;
                
                if ($query_questions_chart) {
                    $question_counter = 1; // เริ่มนับจาก 1
                    while($row = sqlsrv_fetch_array($query_questions_chart, SQLSRV_FETCH_ASSOC)) {
                        $question_text = $question_counter . '.) ' . mb_substr($row['SQ_QUESTION'], 0, 30) . '...';
                        $avg_rating = $row['avg_rating'] ? round($row['avg_rating'], 2) : 0;
                        $chart_data_1[] = "['" . addslashes($question_text) . "', " . $avg_rating . ", '" . $avg_rating . "', 5]";
                        
                        // เก็บผลรวมสำหรับคำนวณค่าเฉลี่ยรวม
                        if ($avg_rating > 0) {
                            $total_sum += $avg_rating;
                            $total_count++;
                        }
                        
                        $question_counter++; // เพิ่มลำดับ
                    }
                }
                
                // เพิ่มแถวสรุปรวม
                if ($total_count > 0) {
                    $overall_average = round($total_sum / $total_count, 2);
                    $chart_data_1[] = "['สรุปรวมทั้งหมด', " . $overall_average . ", '" . $overall_average . "', 5]";
                }
                
                echo implode(",\n", $chart_data_1);
                ?>
            ]);

            var options = {
                title: 'คะแนนเฉลี่ยแต่ละหัวข้อ',
                titleTextStyle: {
                    fontSize: 18,
                    bold: true,
                    color: '#333'
                },
                chartArea: {
                    left: 80,
                    top: 80,
                    width: '90%',
                    height: '60%'
                },
                hAxis: {
                    title: '',
                    titleTextStyle: {fontSize: 14, bold: true},
                    textStyle: {fontSize: 10},
                    slantedText: true,
                    slantedTextAngle: 45
                },
                vAxis: {
                    title: 'คะแนนเฉลี่ย (1-5)',
                    titleTextStyle: {fontSize: 14, bold: true},
                    minValue: 0,
                    maxValue: 5,
                    textStyle: {fontSize: 11}
                },
                series: {
                    0: {
                        type: 'columns',
                        color: '#667eea'
                    },
                    1: {
                        type: 'line',
                        color: '#ff4444',
                        lineWidth: 3,
                        pointSize: 8
                    }
                },
                legend: {
                    position: 'bottom',
                    alignment: 'center',
                    textStyle: {fontSize: 12}
                },
                backgroundColor: 'white',
                titlePosition: 'out',
                annotations: {
                    alwaysOutside: true,
                    textStyle: {
                        fontSize: 12,
                        color: '#000',
                        auraColor: 'none',
                        bold: true
                    }
                }
            };

            try {
                var chart = new google.visualization.ColumnChart(document.getElementById('survey_rating_chart'));
                chart.draw(data, options);
                console.log('Rating chart drawn successfully');
            } catch (error) {
                console.error('Error drawing rating chart:', error);
                // ลองใช้ ColumnChart แทน
                try {
                    var fallbackChart = new google.visualization.ColumnChart(document.getElementById('survey_rating_chart'));
                    fallbackChart.draw(data, {
                        title: 'คะแนนเฉลี่ยแต่ละหัวข้อ',
                        titleTextStyle: { fontSize: 18, bold: true, color: '#333' },
                        chartArea: { left: 80, top: 80, width: '90%', height: '70%' },
                        hAxis: {
                            title: 'หัวข้อความพึงพอใจ',
                            titleTextStyle: {fontSize: 14, bold: true},
                            textStyle: {fontSize: 10},
                            slantedText: true,
                            slantedTextAngle: 45
                        },
                        vAxis: {
                            title: 'คะแนนเฉลี่ย (1-5)',
                            titleTextStyle: {fontSize: 14, bold: true},
                            minValue: 0,
                            maxValue: 5.5,
                            textStyle: {fontSize: 11}
                        },
                        colors: ['#667eea'],
                        bar: {groupWidth: '60%'},
                        legend: {position: 'none'},
                        backgroundColor: 'white',
                        annotations: {
                            alwaysOutside: true,
                            textStyle: { fontSize: 12, color: '#000', auraColor: 'none', bold: true }
                        }
                    });
                } catch (fallbackError) {
                    console.error('Fallback chart also failed:', fallbackError);
                }
            }
        }

        // กราฟการแจกแจงคะแนนความพึงพอใจ
        function drawQuestionChart() {
            var data = google.visualization.arrayToDataTable([
                ['ระดับคะแนน', 'จำนวนคน', {type: 'string', role: 'annotation'}],
                ['ดีมาก (5 คะแนน)', <?= $rating_stats[5] ?>, '<?= $rating_stats[5] ?> คน'],
                ['ดี (4 คะแนน)', <?= $rating_stats[4] ?>, '<?= $rating_stats[4] ?> คน'],
                ['ปรับปรุง (1 คะแนน)', <?= $rating_stats[1] ?>, '<?= $rating_stats[1] ?> คน']
            ]);

            var options = {
                title: 'การแจกแจงคะแนนความพึงพอใจ',
                titleTextStyle: {
                    fontSize: 18,
                    bold: true,
                    color: '#333'
                },
                chartArea: {
                    left: 120,
                    top: 80,
                    width: '70%',
                    height: '75%'
                },
                hAxis: {
                    title: 'จำนวนคน',
                    titleTextStyle: {fontSize: 14, bold: true},
                    minValue: 0,
                    textStyle: {fontSize: 11}
                },
                vAxis: {
                    title: 'ระดับความพึงพอใจ',
                    titleTextStyle: {fontSize: 14, bold: true},
                    textStyle: {fontSize: 12}
                },
                colors: ['#764ba2'],
                bar: {groupWidth: '70%'},
                legend: {position: 'none'},
                backgroundColor: 'white',
                annotations: {
                    alwaysOutside: true,
                    textStyle: {
                        fontSize: 12,
                        color: '#000',
                        auraColor: 'none',
                        bold: true
                    }
                }
            };

            try {
                var chart = new google.visualization.BarChart(document.getElementById('question_average_chart'));
                chart.draw(data, options);
                console.log('Question chart drawn successfully');
            } catch (error) {
                console.error('Error drawing question chart:', error);
            }
        }

        // ปรับขนาดกราฟเมื่อหน้าต่างเปลี่ยนขนาด
        window.addEventListener('resize', function() {
            drawCharts();
        });
    </script>
</body>
</html>