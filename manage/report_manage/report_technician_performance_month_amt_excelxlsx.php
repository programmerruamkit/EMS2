<?php
    require '../../vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Style\Border;

    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');
    $SESSION_AREA = $_SESSION["AD_AREA"];
    $txt_datestart=$_GET['txt_datestart'];
    $txt_dateend=$_GET['txt_dateend'];

// แปลงวันที่
    $start = DateTime::createFromFormat('d/m/Y', $txt_datestart);
    $end = DateTime::createFromFormat('d/m/Y', $txt_dateend);
    $sql_datestart = $start->format('Y-m-d');
    $sql_dateend = $end->format('Y-m-d');
    $interval = $start->diff($end);
    $days = $interval->days + 1;

// หาชื่อเดือน/ปีไทย
    $thai_months = [
        1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
        7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
    ];
    $month_th = $thai_months[(int)$start->format('m')];
    $year_th = (int)$start->format('Y') + 543;

// เตรียมไฟล์ Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

// หัวรายงาน
    $title = "รายงานประสิทธิภาพการทำงานช่างรายบุคคล ($SESSION_AREA) ประจำเดือน $month_th $year_th ช่วงวันที่ $txt_datestart ถึง $txt_dateend ($days วัน)";
    $sheet->mergeCells('A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(8+$days) . '1');
    $sheet->setCellValue('A1', $title);
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// หัวตาราง
    $col = 1;
    $sheet->setCellValueByColumnAndRow($col++, 2, 'ลำดับ');
    $sheet->setCellValueByColumnAndRow($col++, 2, 'รายชื่อ');
    $sheet->setCellValueByColumnAndRow($col++, 2, 'หน่วยงาน');
    $sheet->setCellValueByColumnAndRow($col++, 2, 'Cap(รวม)');
    $sheet->setCellValueByColumnAndRow($col++, 2, 'Job(รวม)');

// Merge cell สำหรับหัวข้อ "ชม.การทำงาน/Job ประจำเดือน ..."
    $mergeStart = $col;
    $sheet->setCellValueByColumnAndRow($col, 2, "ชม.การทำงาน/Job ประจำเดือน $month_th $year_th");
    $sheet->mergeCellsByColumnAndRow($mergeStart, 2, $mergeStart+$days-1, 2);
    for ($i = 1; $i <= $days; $i++) {
        $sheet->setCellValueByColumnAndRow($mergeStart+$i-1, 3, $i);
    }
    $col += $days;

    $sheet->setCellValueByColumnAndRow($col++, 2, 'ชม.รวม');
    $sheet->setCellValueByColumnAndRow($col++, 2, 'ชม./Job');
    $sheet->setCellValueByColumnAndRow($col++, 2, 'ชม.งาน/Cap');

// Merge cell หัวตารางแนวตั้ง
    $sheet->mergeCells('A2:A3');
    $sheet->mergeCells('B2:B3');
    $sheet->mergeCells('C2:C3');
    $sheet->mergeCells('D2:D3');
    $sheet->mergeCells('E2:E3');
    $sheet->mergeCellsByColumnAndRow($mergeStart+$days, 2, $mergeStart+$days, 3);
    $sheet->mergeCellsByColumnAndRow($mergeStart+$days+1, 2, $mergeStart+$days+1, 3);
    $sheet->mergeCellsByColumnAndRow($mergeStart+$days+2, 2, $mergeStart+$days+2, 3);

// สไตล์หัวตาราง
    $lastCol = $mergeStart+$days+2;
    $sheet->getStyle('A2:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol) . '3')
        ->getFont()->setBold(true);
    $sheet->getStyle('A2:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol) . '3')
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A2:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol) . '3')
        ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('e6f1fa');
    $sheet->getRowDimension(2)->setRowHeight(22);
    $sheet->getRowDimension(3)->setRowHeight(18);

// กำหนดความกว้าง
    for ($i = 1; $i <= $lastCol; $i++) {
        $sheet->getColumnDimensionByColumn($i)->setWidth(13);
    }
    $sheet->getColumnDimension('A')->setWidth(7);
    $sheet->getColumnDimension('B')->setWidth(30);
    $sheet->getColumnDimension('C')->setWidth(20);

// เตรียมตัวแปรรวม
    $sum_days = array_fill(1, $days, 0);
    $TOTAL_CAPDAY = 0;
    $TOTAL_RPME_COUNT = 0;

// --- ดึงข้อมูลและวนลูป ---
    $sql_days = '';
    for ($i = 0; $i < $days; $i++) {
        $date = clone $start;
        $date->modify("+$i day");
        $date_str = $date->format('d/m/Y');
        $sql_days .= "SUM(CASE WHEN C.RPC_INCARDATE = '$date_str' THEN 
            DATEDIFF(MINUTE, 
                CASE WHEN ISDATE(B.RPC_INCARTIME) = 1 THEN CONVERT(DATETIME, B.RPC_INCARTIME) ELSE NULL END,
                CASE WHEN ISDATE(B.RPC_OUTCARTIME) = 1 THEN CONVERT(DATETIME, B.RPC_OUTCARTIME) ELSE NULL END
            ) ELSE 0 END) AS D" . ($i+1) . ",\n";
    }
    $sql_month = "SELECT 
            A.PersonCode, 
            A.nameT, 
            A.RPM_NATUREREPAIR,
            (SELECT COUNT(*) 
            FROM REPAIRMANEMP RPM
            LEFT JOIN REPAIRREQUEST RPRQ ON RPRQ.RPRQ_CODE = RPM.RPRQ_CODE
            WHERE RPM.RPME_CODE COLLATE Thai_CI_AS = A.PersonCode COLLATE Thai_CI_AS 
            AND CONVERT(date, RPM.RPC_INCARDATE, 103) BETWEEN '$sql_datestart' AND '$sql_dateend'
            AND RPRQ.RPRQ_STATUS = 'Y') AS RPME_COUNT,
            $sql_days
            C.RPME_CODE
        FROM dbo.vwREPAIRMAN AS A
        LEFT JOIN dbo.REPAIRMANEMP AS C ON C.RPME_CODE COLLATE Thai_CI_AS = A.PersonCode COLLATE Thai_CI_AS
        LEFT JOIN dbo.REPAIRCAUSE AS B ON B.RPRQ_CODE = C.RPRQ_CODE AND B.RPC_INCARDATE COLLATE Thai_CI_AS = C.RPC_INCARDATE COLLATE Thai_CI_AS AND B.RPC_SUBJECT = A.RPM_NATUREREPAIR
        LEFT JOIN dbo.REPAIRREQUEST AS D ON D.RPRQ_CODE = B.RPRQ_CODE 
        WHERE A.RPM_STATUS = 'Y' 
            AND A.RPM_AREA = '$SESSION_AREA' 
            AND D.RPRQ_STATUS = 'Y' 
            AND A.RPM_NATUREREPAIR IS NOT NULL 
            AND LTRIM(RTRIM(A.RPM_NATUREREPAIR)) <> ''
        GROUP BY A.PersonCode, A.nameT, A.RPM_NATUREREPAIR, C.RPME_CODE
        ORDER BY A.PersonCode ASC";
    $query_month = sqlsrv_query($conn, $sql_month);

    $row = 4;
    $no = 0;
    while($result_repairman = sqlsrv_fetch_array($query_month, SQLSRV_FETCH_ASSOC)){	
        $no++;
        $RPM_PERSONNAME = $result_repairman['nameT'];
        $RPMNATUREREPAIR = $result_repairman['RPM_NATUREREPAIR'];
        if($RPMNATUREREPAIR=="EL"){
            $RPM_NATUREREPAIR='ระบบไฟ';
        }else if($RPMNATUREREPAIR=="TU"){
            $RPM_NATUREREPAIR='ยาง ช่วงล่าง';
        }else if($RPMNATUREREPAIR=="BD"){
            $RPM_NATUREREPAIR='โครงสร้าง';
        }else if($RPMNATUREREPAIR=="EG"){
            $RPM_NATUREREPAIR='เครื่องยนต์';
        }else if($RPMNATUREREPAIR=="AC"){
            $RPM_NATUREREPAIR='อุปกรณ์ประจำรถ';
        }else{
            $RPM_NATUREREPAIR='-';
        }
        $CapDay = 6.75; 
        $RPME_COUNT = $result_repairman['RPME_COUNT'];

        // นับวันที่มีค่า
        $count_has_value = 0;
        for ($i = 1; $i <= $days; $i++) {
            $dkey = "D$i";
            $val = isset($result_repairman[$dkey]) ? $result_repairman[$dkey] : 0;
            $hour = $val > 0 ? $val / 60 : 0;
            if ($hour > 0) $count_has_value++;
        }
        $count_value_times_capday = $count_has_value * $CapDay;

        // วนลูปแสดงผลรายวัน และเก็บผลรวมแต่ละวัน
        $total_person_hour = 0;
        for ($i = 1; $i <= $days; $i++) {
            $dkey = "D$i";
            $val = isset($result_repairman[$dkey]) ? $result_repairman[$dkey] : 0;
            $sum_days[$i] += $val;
            $hour = $val > 0 ? $val / 60 : 0;
            $total_person_hour += $hour;
            $person_hours[$i] = $hour;
        }
        $person_per_job = ($RPME_COUNT > 0) ? $total_person_hour / $RPME_COUNT : 0;
        $person_per_cap = ($count_value_times_capday > 0) ? $total_person_hour / $count_value_times_capday : 0;
        $person_percent = ($count_value_times_capday > 0) ? ($total_person_hour / $count_value_times_capday) * 100 : 0;

        $TOTAL_CAPDAY += $count_value_times_capday;
        $TOTAL_RPME_COUNT += $RPME_COUNT;

        // ใส่ข้อมูลลงแถว
        $col = 1;
        $sheet->setCellValueByColumnAndRow($col++, $row, $no);
        $sheet->setCellValueByColumnAndRow($col++, $row, $RPM_PERSONNAME);
        $sheet->setCellValueByColumnAndRow($col++, $row, $RPM_NATUREREPAIR);
        $sheet->setCellValueByColumnAndRow($col++, $row, $count_value_times_capday);
        $sheet->setCellValueByColumnAndRow($col++, $row, $RPME_COUNT);
        for ($i = 1; $i <= $days; $i++) {
            $value = ($person_hours[$i] > 0) ? $person_hours[$i] : '';
            $sheet->setCellValueByColumnAndRow($col, $row, $value);
            if ($value !== '') {
                $sheet->getStyleByColumnAndRow($col, $row)->getNumberFormat()->setFormatCode('0.00'); // รูปแบบตัวเลขทศนิยม 2 ตำแหน่ง
            }
            $col++;
        }
        $sheet->setCellValueByColumnAndRow($col++, $row, $total_person_hour);
        $sheet->setCellValueByColumnAndRow($col++, $row, $person_per_job);

        $lastBoldCol1 = $col-0; // 3 คอลัมน์สุดท้าย
        $lastBoldCol2 = $col-1; // 3 คอลัมน์สุดท้าย
        $lastBoldCol3 = $col-2; // 3 คอลัมน์สุดท้าย
        $sheet->setCellValueByColumnAndRow($lastBoldCol1, $row, number_format($person_percent, 0) . '%'); // ใส่เป็นเปอร์เซ็นต์    
        // ทำให้คอลัมน์เปอร์เซ็นต์เป็นตัวหนา
        $sheet->getStyleByColumnAndRow($lastBoldCol1, $row)->getFont()->setBold(true);
        $sheet->getStyleByColumnAndRow($lastBoldCol2, $row)->getFont()->setBold(true);
        $sheet->getStyleByColumnAndRow($lastBoldCol3, $row)->getFont()->setBold(true);
        // กำหนดสีตามเงื่อนไข
        if (round($person_percent) == 100) {
            $sheet->getStyleByColumnAndRow($lastBoldCol1, $row)->getFont()->getColor()->setRGB('0000FF'); // น้ำเงิน
        } elseif (round($person_percent) > 100) {
            $sheet->getStyleByColumnAndRow($lastBoldCol1, $row)->getFont()->getColor()->setRGB('008000'); // เขียว
        } else {
            $sheet->getStyleByColumnAndRow($lastBoldCol1, $row)->getFont()->getColor()->setRGB('FF0000'); // แดง
        }
        // รูปแบบตัวเลข
        $sheet->getStyleByColumnAndRow($lastBoldCol1, $row)->getNumberFormat()->setFormatCode('0.00'); // รูปแบบตัวเลขทศนิยม 2 ตำแหน่ง
        $sheet->getStyleByColumnAndRow($lastBoldCol2, $row)->getNumberFormat()->setFormatCode('0.00'); // รูปแบบตัวเลขทศนิยม 2 ตำแหน่ง
        $sheet->getStyleByColumnAndRow($lastBoldCol3, $row)->getNumberFormat()->setFormatCode('0.00'); // รูปแบบตัวเลขทศนิยม 2 ตำแหน่ง

        $row++;
    }

// แถวรวม
    $total_hours = 0;
    $col = 1;
    $sheet->setCellValueByColumnAndRow($col++, $row, 'รวม');
    $sheet->mergeCellsByColumnAndRow(1, $row, 3, $row);
    $sheet->setCellValueByColumnAndRow($col++, $row, '');
    $sheet->setCellValueByColumnAndRow($col++, $row, '');
    $sheet->setCellValueByColumnAndRow($col++, $row, number_format($TOTAL_CAPDAY, 2));
    $sheet->setCellValueByColumnAndRow($col++, $row, number_format($TOTAL_RPME_COUNT, 2));
    for ($ii = 1; $ii <= $days; $ii++) {
        $hour = $sum_days[$ii] > 0 ? $sum_days[$ii] / 60 : 0;
        $total_hours += $hour;
        $sheet->setCellValueByColumnAndRow($col++, $row, $hour);
        $sheet->getStyleByColumnAndRow($col-1, $row)->getNumberFormat()->setFormatCode('0.00'); // รูปแบบตัวเลขทศนิยม 2 ตำแหน่ง
    }
    $sum_total_hours = $total_hours;
    $sum_per_job = ($TOTAL_RPME_COUNT > 0) ? $sum_total_hours / $TOTAL_RPME_COUNT : 0;
    $sum_per_cap = ($TOTAL_CAPDAY > 0) ? $sum_total_hours / $TOTAL_CAPDAY : 0;
    $percent = ($TOTAL_CAPDAY > 0) ? ($sum_total_hours / $TOTAL_CAPDAY) * 100 : 0;
    $sheet->setCellValueByColumnAndRow($col++, $row, number_format($sum_total_hours, 2));
    $sheet->setCellValueByColumnAndRow($col++, $row, number_format($sum_per_job, 2));
    $sheet->setCellValueByColumnAndRow($col++, $row, number_format($percent, 0).'%'); // ใส่เป็นเปอร์เซ็นต์

// กำหนดสีตัวหนังสือสีแดงในคอมลัมน์เปอร์เซ็นต์อันสุดท้าย
    $sheet->getStyleByColumnAndRow($lastBoldCol1, $row)->getFont()->setBold(true);
    if (round($person_percent) == 100) {
        $sheet->getStyleByColumnAndRow($lastBoldCol1, $row)->getFont()->getColor()->setRGB('0000FF'); // น้ำเงิน
    } elseif (round($person_percent) > 100) {
        $sheet->getStyleByColumnAndRow($lastBoldCol1, $row)->getFont()->getColor()->setRGB('008000'); // เขียว
    } else {
        $sheet->getStyleByColumnAndRow($lastBoldCol1, $row)->getFont()->getColor()->setRGB('FF0000'); // แดง
    }

// สไตล์แถวรวม
    $sheet->getStyle('A'.$row.':' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol) . $row)
        ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('e6f1fa');
    $sheet->getStyle('A'.$row.':' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol) . $row)
        ->getFont()->setBold(true);

// ใส่เส้นขอบทั้งตาราง
    $sheet->getStyle('A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol) . $row)
        ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    

// จัดตำแหน่ง
    $sheet->getStyle('A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol) . $row)
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('B4:B'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('C4:C'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D4:E'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F4:'.$sheet->getHighestColumn().$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// บันทึกไฟล์ Excel
    $writer = new Xlsx($spreadsheet);
    $filename = "รายงานประสิทธิภาพการทำงานช่างรายบุคคล($SESSION_AREA) $txt_datestart ถึง $txt_dateend.xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit;