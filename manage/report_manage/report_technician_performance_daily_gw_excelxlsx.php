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
    $start = DateTime::createFromFormat('d/m/Y', $txt_datestart);
    $sql_datestart = $start->format('Y-m-d');

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

// แถวแรกของหัวตารางใหญ่
    $sheet->mergeCells('A1:J1');
    $sheet->setCellValue('A1', 'รายงานประสิทธิภาพการทำงานช่างรายบุคคล ' . $SESSION_AREA . ' (ประจำวันที่ ' . $txt_datestart . ')');
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getRowDimension('1')->setRowHeight(30);

// แถวที่ 2: หัวคอลัมน์หลัก
    $sheet->mergeCells('A2:A3')->setCellValue('A2', 'ลำดับ');
    $sheet->mergeCells('B2:B3')->setCellValue('B2', 'รายชื่อ');
    $sheet->mergeCells('C2:C3')->setCellValue('C2', 'หน่วยงาน');
    $sheet->mergeCells('D2:D3')->setCellValue('D2', 'Cap/วัน');
    $sheet->mergeCells('E2:E3')->setCellValue('E2', 'Job/วัน');
    $sheet->mergeCells('F2:F3')->setCellValue('F2', 'ชม./Job');
    $sheet->mergeCells('G2:J2')->setCellValue('G2', 'ชม.ทำงานรวม/วัน');

// แถวที่ 3: หัวข้อย่อยในกลุ่ม "ชม.ทำงานรวม/วัน"
    $sheet->setCellValue('G3', 'Plan');
    $sheet->setCellValue('H3', '%Plan/Cap');
    $sheet->setCellValue('I3', 'Act.');
    $sheet->setCellValue('J3', '%Act./Cap');

// กำหนดความกว้างแต่ละคอลัมน์
    $sheet->getColumnDimension('A')->setWidth(7);   // ลำดับ
    $sheet->getColumnDimension('B')->setWidth(30);  // รายชื่อ
    $sheet->getColumnDimension('C')->setWidth(25);  // หน่วยงาน
    $sheet->getColumnDimension('D')->setWidth(15);  // Cap/วัน
    $sheet->getColumnDimension('E')->setWidth(15);  // Job/วัน
    $sheet->getColumnDimension('F')->setWidth(15);  // ชม./Job
    $sheet->getColumnDimension('G')->setWidth(15);  // Plan
    $sheet->getColumnDimension('H')->setWidth(15);  // %Plan/Cap
    $sheet->getColumnDimension('I')->setWidth(15);  // Act.
    $sheet->getColumnDimension('J')->setWidth(15);  // %Act./Cap

// กำหนดสไตล์หัวตาราง
    $sheet->getStyle('A2:J3')->getFont()->setBold(true);
    $sheet->getStyle('A2:J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A2:J3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// กำหนดสีพื้นหลังหัวตาราง
    $sheet->getStyle('A1:J3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('e6f1fa');

// กำหนดการจัดตำแหน่งแต่ละคอลัมน์
    $sheet->getStyle('A:C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); 
    $sheet->getStyle('B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);     
    $sheet->getStyle('D:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); 

// เริ่มแถวที่ 4 สำหรับข้อมูล
    $row = 4; 
    $sql_menu = "SELECT 
            A.PersonCode, 
            A.nameT, 
            A.RPM_NATUREREPAIR, 
            ISNULL((
                SELECT COUNT(*) 
                FROM REPAIRMANEMP RPM 
                LEFT JOIN REPAIRREQUEST RPRQ 
                    ON RPRQ.RPRQ_CODE = RPM.RPRQ_CODE 
                WHERE RPM.RPME_CODE COLLATE Thai_CI_AS = A.PersonCode COLLATE Thai_CI_AS 
                AND CONVERT(date, RPM.RPC_INCARDATE, 103) = '$sql_datestart' 
                AND RPRQ.RPRQ_STATUS = 'Y'
            ), 0) AS RPME_COUNT, 
            SUM(
                DATEDIFF(MINUTE, 
                    CASE WHEN ISDATE(B.RPC_INCARTIME) = 1 THEN CONVERT(DATETIME, B.RPC_INCARTIME) ELSE NULL END, 
                    CASE WHEN ISDATE(B.RPC_OUTCARTIME) = 1 THEN CONVERT(DATETIME, B.RPC_OUTCARTIME) ELSE NULL END
                )
            ) AS TOTAL_MINUTE, 
            SUM(ISNULL(RAT.RPATTM_TOTAL_INT, 0)) AS ACTUAL_TOTAL_MINUTE 

        FROM dbo.vwREPAIRMAN AS A 
        LEFT JOIN dbo.REPAIRMANEMP AS C ON C.RPME_CODE COLLATE Thai_CI_AS = A.PersonCode COLLATE Thai_CI_AS AND CONVERT(date, C.RPC_INCARDATE, 103) = '$sql_datestart' 
        LEFT JOIN dbo.REPAIRCAUSE AS B ON B.RPRQ_CODE = C.RPRQ_CODE AND CONVERT(date, B.RPC_INCARDATE, 103) = '$sql_datestart' AND B.RPC_SUBJECT = A.RPM_NATUREREPAIR
        LEFT JOIN dbo.REPAIRREQUEST AS D ON D.RPRQ_CODE = B.RPRQ_CODE AND D.RPRQ_STATUS = 'Y'
        OUTER APPLY ( 
            SELECT SUM(CAST(REPLACE(RPATTM_TOTAL, ',', '') AS INT)) AS RPATTM_TOTAL_INT 
            FROM dbo.REPAIRACTUAL_TIME 
            WHERE RPRQ_CODE = D.RPRQ_CODE 
            AND RPC_SUBJECT = B.RPC_SUBJECT 
        ) AS RAT 
        WHERE A.RPM_STATUS = 'Y' 
        AND A.RPM_AREA = '$SESSION_AREA' 
        AND A.RPM_NATUREREPAIR IS NOT NULL 
        AND LTRIM(RTRIM(A.RPM_NATUREREPAIR)) <> ''
        GROUP BY A.PersonCode, A.nameT, A.RPM_NATUREREPAIR 
        ORDER BY A.PersonCode ASC";
    $query_menu = sqlsrv_query($conn, $sql_menu);
    
    $no=0;
    $sum_PLANHOUR1 = 0;
    $sum_PLANHOUR2 = 0;
    $sum_COUNT = 0;
    $sum_CapDay = 0;
    $sum_PLANCAP1 = 0;
    $sum_PLANCAP2 = 0;
    $sum_PLANHOURJOB = 0;
// วนลูปเพื่อดึงข้อมูลช่างซ่อม
    while($result_repairman = sqlsrv_fetch_array($query_menu, SQLSRV_FETCH_ASSOC)){	
        $no++;
        $RPM_PERSONNAME=$result_repairman['nameT'];
        $RPMNATUREREPAIR=$result_repairman['RPM_NATUREREPAIR'];
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
        $RPME_COUNT=$result_repairman['RPME_COUNT'];
        $PLANHOUR1 = ($result_repairman['TOTAL_MINUTE']/60); 
        $PLANHOUR2 = ($result_repairman['ACTUAL_TOTAL_MINUTE']/60); 
        $PLANCAP1 = $PLANHOUR1 / $CapDay;
        $PLANCAP2 = $PLANHOUR2 / $CapDay;
        $PLANHOUR2JOB = $RPME_COUNT > 0 ? $PLANHOUR2 / $RPME_COUNT : 0;

        $sum_PLANHOUR1 += $PLANHOUR1;
        $sum_PLANHOUR2 += $PLANHOUR2;
        $sum_COUNT += $RPME_COUNT;
        $sum_CapDay += $CapDay;
        $sum_PLANCAP1 += $PLANCAP1;
        $sum_PLANCAP2 += $PLANCAP2;
        $sum_PLANHOURJOB += $PLANHOUR2JOB;

        $sheet->setCellValue('A'.$row, $no);
        $sheet->setCellValue('B'.$row, $RPM_PERSONNAME);
        $sheet->setCellValue('C'.$row, $RPM_NATUREREPAIR);
        $sheet->setCellValue('D'.$row, $CapDay);
        $sheet->setCellValue('E'.$row, $RPME_COUNT);
        $sheet->setCellValue('F'.$row, $PLANHOUR2JOB);
        $sheet->setCellValue('G'.$row, $PLANHOUR1);

        // %Plan/Cap
        $planCapPercent = $PLANHOUR1 / $CapDay;
        $sheet->setCellValue('H'.$row, $planCapPercent);
        $sheet->getStyle('H'.$row)->getNumberFormat()->setFormatCode('0%');
        if (round($planCapPercent*100) == 100) {
            $sheet->getStyle('H'.$row)->getFont()->getColor()->setRGB('0000FF'); // น้ำเงิน
        } elseif (round($planCapPercent*100) > 100) {
            $sheet->getStyle('H'.$row)->getFont()->getColor()->setRGB('008000'); // เขียว
        } else {
            $sheet->getStyle('H'.$row)->getFont()->getColor()->setRGB('FF0000'); // แดง
        }

        // Act.
        $sheet->setCellValue('I'.$row, $PLANHOUR2);

        // %Act./Cap
        $actCapPercent = $PLANHOUR2 / $CapDay;
        $sheet->setCellValue('J'.$row, $actCapPercent);
        $sheet->getStyle('J'.$row)->getNumberFormat()->setFormatCode('0%');
        if (round($actCapPercent*100) == 100) {
            $sheet->getStyle('J'.$row)->getFont()->getColor()->setRGB('0000FF');
        } elseif (round($actCapPercent*100) > 100) {
            $sheet->getStyle('J'.$row)->getFont()->getColor()->setRGB('008000');
        } else {
            $sheet->getStyle('J'.$row)->getFont()->getColor()->setRGB('FF0000');
        }

        // ใส่เส้นขอบให้แต่ละแถว
        $sheet->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        // ตัวหนา
        $sheet->getStyle('G'.$row.':J'.$row)->getFont()->setBold(true);

        // ตัวอย่าง: จัดตำแหน่งซ้ายให้คอลัมน์รายชื่อ
        $sheet->getStyle('B'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $row++;
    }

// แถวรวม (ผสานเซลล์ A ถึง C)
    $sheet->setCellValue('A'.$row, 'รวม');
    $sheet->mergeCells('A'.$row.':C'.$row);

    $sheet->setCellValue('D'.$row, $sum_CapDay);
    $sheet->getStyle('D'.$row)->getNumberFormat()->setFormatCode('0.00');

    $sheet->setCellValue('E'.$row, $sum_COUNT);
    $sheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode('0.00');

    $sheet->setCellValue('F'.$row, $sum_PLANHOURJOB);
    $sheet->getStyle('F'.$row)->getNumberFormat()->setFormatCode('0.00');

    $sheet->setCellValue('G'.$row, $sum_PLANHOUR1);
    $sheet->getStyle('G'.$row)->getNumberFormat()->setFormatCode('0.00');

// %Plan/Cap (H)
    $planCapPercentSum = $sum_PLANHOUR1 / $sum_CapDay;
    $sheet->setCellValue('H'.$row, $planCapPercentSum);
    $sheet->getStyle('H'.$row)->getNumberFormat()->setFormatCode('0%');
    if (round($planCapPercentSum*100) == 100) {
        $sheet->getStyle('H'.$row)->getFont()->getColor()->setRGB('0000FF');
    } elseif (round($planCapPercentSum*100) > 100) {
        $sheet->getStyle('H'.$row)->getFont()->getColor()->setRGB('008000');
    } else {
        $sheet->getStyle('H'.$row)->getFont()->getColor()->setRGB('FF0000');
    }

// Act. (I)
    $sheet->setCellValue('I'.$row, $sum_PLANHOUR2);
    $sheet->getStyle('I'.$row)->getNumberFormat()->setFormatCode('0.00');

// %Act./Cap (J)
    $actCapPercentSum = $sum_PLANHOUR2 / $sum_CapDay;
    $sheet->setCellValue('J'.$row, $actCapPercentSum);
    $sheet->getStyle('J'.$row)->getNumberFormat()->setFormatCode('0%');
    if (round($actCapPercentSum*100) == 100) {
        $sheet->getStyle('J'.$row)->getFont()->getColor()->setRGB('0000FF');
    } elseif (round($actCapPercentSum*100) > 100) {
        $sheet->getStyle('J'.$row)->getFont()->getColor()->setRGB('008000');
    } else {
        $sheet->getStyle('J'.$row)->getFont()->getColor()->setRGB('FF0000');
    }

// ใส่เส้นขอบและสีพื้นหลังแถวรวม
    $sheet->getStyle('A'.$row.':J'.$row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('e6f1fa');
    $sheet->getStyle('A'.$row.':J'.$row)->getFont()->setBold(true);
    $sheet->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// ใส่เส้นขอบให้หัวตาราง
    $sheet->getStyle('A2:J3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// ใส่เส้นขอบให้หัวรายงาน
    $sheet->getStyle('A1:J1')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

// สมมติ $row คือแถวปัจจุบันที่ต้องการกำหนดรูปแบบ
    $sheet->getStyle('D'.$row.':G'.$row)->getNumberFormat()->setFormatCode('0.00');
    $sheet->getStyle('I'.$row)->getNumberFormat()->setFormatCode('0.00');
    $sheet->getStyle('F4:G'.$row)->getNumberFormat()->setFormatCode('0.00');
    $sheet->getStyle('I4:I'.$row)->getNumberFormat()->setFormatCode('0.00');

// ใส่เส้นขอบให้แถวรวม
    $sheet->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// ตั้งชื่อ Worksheet
    $sheet->setTitle('รายงานประสิทธิภาพช่าง');

// ส่งออกเป็น .xlsx
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="รายงานประสิทธิภาพการทำงานช่างรายบุคคล('.$SESSION_AREA.') '.$txt_datestart.'.xlsx"');
    header('Cache-Control: max-age=0');

// สร้าง Writer และบันทึกไฟล์
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
?>