<?php
    // ป้องกัน output buffer และ error
    ob_start();
    // error_reporting(0);
    // ini_set('display_errors', 0);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ini_set('memory_limit', '512M'); // หรือมากกว่านี้ถ้าเครื่องไหว
    set_time_limit(300); // 5 นาที

    require '../../vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Style\Border;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');
    $SESSION_AREA = $_SESSION["AD_AREA"];
    
    // รับค่า group จาก parameter
    $group = isset($_GET['group']) ? $_GET['group'] : '';
    
    // กำหนดเงื่อนไขการค้นหาตาม group
    $where_condition = "";
    $group_name = "";
    $result_customer = null;
    
    if ($group == 'AMT') {
        $where_condition = "AND AFFCOMPANY IN('RKL','RKR','RKS')";
        $group_name = "AMT";
    } elseif ($group == 'GW') {
        $where_condition = "AND AFFCOMPANY IN('RATC','RCC','RRC')";
        $group_name = "GW";
    } else {
        // ถ้าเป็น ID ของลูกค้า
        $sql_customer = "SELECT CTM_COMCODE, CTM_NAMETH FROM CUSTOMER WHERE CTM_ID = ?";
        $params = array($group);
        $query_customer = sqlsrv_query($conn, $sql_customer, $params);
        if ($query_customer) {
            $result_customer = sqlsrv_fetch_array($query_customer, SQLSRV_FETCH_ASSOC);
            if ($result_customer) {
                $where_condition = "AND AFFCOMPANY = ?";
                $group_name = $result_customer['CTM_COMCODE'] . " - " . $result_customer['CTM_NAMETH'];
            }
        }
    }

    $spreadsheet = new Spreadsheet();
    
    // ดึงรายการอะไหล่ทั้งหมด
    $stmt_spareparts = "SELECT DISTINCT * FROM [dbo].[PROJECT_SPAREPART] WHERE PJSPP_AREA = ? ORDER BY PJSPP_ID ASC";
    $params_spareparts = array($SESSION_AREA);
    $query_spareparts = sqlsrv_query($conn, $stmt_spareparts, $params_spareparts);
    
    $sheet_index = 0;
    $has_data = false;
    
    while($sparepart = sqlsrv_fetch_array($query_spareparts, SQLSRV_FETCH_ASSOC)) {
        $PJSPP_NAME = $sparepart['PJSPP_NAME'];
        $PJSPP_CODENAME = $sparepart['PJSPP_CODENAME'];
        $PJSPP_EXPIRE_YEAR = $sparepart['PJSPP_EXPIRE_YEAR'];
        
        // สร้าง sheet ใหม่สำหรับแต่ละอะไหล่
        if ($sheet_index == 0) {
            $sheet = $spreadsheet->getActiveSheet();
        } else {
            $sheet = $spreadsheet->createSheet();
        }
        
        // ตั้งชื่อ sheet แบบเรียบง่าย
        // $sheet_title = "Part_" . $PJSPP_CODENAME;
        $sheet_title = $PJSPP_NAME;
        
        try {
            $sheet->setTitle($sheet_title);
        } catch (Exception $e) {
            $sheet->setTitle("Sheet_" . ($sheet_index + 1));
        }
        
        // หัวตาราง
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', $PJSPP_NAME);
        // $sheet->setCellValue('A1', 'รายงานข้อมูลแผนอุปกรณ์ตามอายุการใช้งาน - '.$PJSPP_NAME.' ('.$group_name.')');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension('1')->setRowHeight(30);
        
        // หัวคอลัมน์
        $sheet->setCellValue('A2', 'ลำดับ');
        $sheet->setCellValue('B2', 'ทะเบียน');
        $sheet->setCellValue('C2', 'ชื่อรถ');
        $sheet->setCellValue('D2', 'ประเภท');
        $sheet->setCellValue('E2', 'บริษัท');
        $sheet->setCellValue('F2', 'มาตรฐานกำหนดเปลี่ยน (ปี)');
        $sheet->setCellValue('G2', 'วันที่เปลี่ยนล่าสุด');
        $sheet->setCellValue('H2', 'กำหนดเปลี่ยนครั้งถัดไป');
        $sheet->setCellValue('I2', 'ถึงกำหนด/เกินระยะ (วัน)');
        $sheet->setCellValue('J2', 'หมายเหตุ');
        
        // กำหนดความกว้างคอลัมน์
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(18);
        $sheet->getColumnDimension('J')->setWidth(25);
        
        // สไตล์หัวตาราง
        $sheet->getStyle('A2:J2')->getFont()->setBold(true);
        $sheet->getStyle('A2:J2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:J2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:J2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('e6f1fa');
        $sheet->getStyle('A1:J2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // ดึงข้อมูลรถสำหรับอะไหล่นี้
        $params_vehicles = array();
        
        if ($group == 'AMT' || $group == 'GW') {
                if ($group == 'AMT') {
                    $where_condition_area = "'RKL','RKR','RKS'";
                } elseif ($group == 'GW') {
                    $where_condition_area = "'RATC','RCC','RRC'";
                }
            $sql_vehicles = "SELECT DISTINCT vi.*
                            FROM vwVEHICLEINFO vi
                            LEFT JOIN VEHICLECARTYPEMATCHGROUP vctmg ON vctmg.VHCTMG_VEHICLEREGISNUMBER = vi.VEHICLEREGISNUMBER COLLATE Thai_CI_AI
                            LEFT JOIN VEHICLECARTYPE vct ON vct.VHCCT_ID = vctmg.VHCCT_ID
                            WHERE vi.VEHICLEGROUPDESC = 'Transport' 
                            AND NOT vi.VEHICLEGROUPCODE = 'VG-1403-0755' 
                            AND vi.ACTIVESTATUS = '1'
                            AND AFFCOMPANY IN($where_condition_area)
                            ORDER BY vi.REGISTYPE ASC, vi.AFFCOMPANY ASC, vi.VEHICLEREGISNUMBER ASC";
        } else {
            if ($result_customer) {
                $sql_vehicles = "SELECT DISTINCT vi.*
                                FROM vwVEHICLEINFO vi
                                LEFT JOIN VEHICLECARTYPEMATCHGROUP vctmg ON vctmg.VHCTMG_VEHICLEREGISNUMBER = vi.VEHICLEREGISNUMBER COLLATE Thai_CI_AI
                                LEFT JOIN VEHICLECARTYPE vct ON vct.VHCCT_ID = vctmg.VHCCT_ID
                                WHERE vi.VEHICLEGROUPDESC = 'Transport' 
                                AND NOT vi.VEHICLEGROUPCODE = 'VG-1403-0755' 
                                AND vi.ACTIVESTATUS = '1'
                                AND AFFCOMPANY = ?
                                ORDER BY vi.REGISTYPE ASC, vi.AFFCOMPANY ASC, vi.VEHICLEREGISNUMBER ASC";
                $params_vehicles = array($result_customer['CTM_COMCODE']);
            } else {
                continue; // ข้ามถ้าไม่มีข้อมูลลูกค้า
            }
        }
                        
        $query_vehicles = sqlsrv_query($conn, $sql_vehicles, $params_vehicles);
        
        $row = 3;
        $no = 0;
        $sheet_has_data = false; // เพิ่มตัวแปรนี้เพื่อตรวจสอบข้อมูลใน sheet นี้
        
        while($vehicle = sqlsrv_fetch_array($query_vehicles, SQLSRV_FETCH_ASSOC)) {
            $no++;
            $has_data = true; // มีข้อมูลรวม
            $sheet_has_data = true; // มีข้อมูลใน sheet นี้
            
            // แทนที่ null coalescing operator สำหรับ PHP 5
            $VEHICLEREGISNUMBER = isset($vehicle['VEHICLEREGISNUMBER']) ? $vehicle['VEHICLEREGISNUMBER'] : '';
            $THAINAME = isset($vehicle['THAINAME']) ? $vehicle['THAINAME'] : '';
            $REGISTYPE = isset($vehicle['REGISTYPE']) ? $vehicle['REGISTYPE'] : '';
            $AFFCOMPANY = isset($vehicle['AFFCOMPANY']) ? $vehicle['AFFCOMPANY'] : '';
            
            // ตรวจสอบและดึงข้อมูลจากตารางอะไหล่
            $lactchangedate_display = '';
            $expire_date_display = '';
            $day_diff_display = '';
            $remarkdisplay = '';
            $font_color = '000000'; // สีดำ default
            
            $valid_codes = array("01","02","03","04","05","06","07","08");
            if (in_array($PJSPP_CODENAME, $valid_codes)) {
                $table = "PROJECT_SPAREPART_".$PJSPP_CODENAME;
                $CODENAME = "PJSPP".$PJSPP_CODENAME."_CODENAME"; 
                $VHCRG = "PJSPP".$PJSPP_CODENAME."_VHCRG"; 
                $VHCRGNM = "PJSPP".$PJSPP_CODENAME."_VHCRGNM"; 
                $CREATEDATE = "PJSPP".$PJSPP_CODENAME."_CREATEDATE"; 
                $REMARK = "PJSPP".$PJSPP_CODENAME."_REMARK";
                $LCD = "PJSPP".$PJSPP_CODENAME."_LCD";
                
                // ดึงข้อมูลการเปลี่ยนอะไหล่ล่าสุด
                $sql_sparepart_data = "SELECT TOP 1 * FROM [dbo].[$table] WHERE $CODENAME = ? AND ($VHCRG = ? OR $VHCRGNM = ?) ORDER BY $CREATEDATE DESC";
                $params_sparepart = array($PJSPP_CODENAME, $VEHICLEREGISNUMBER, $THAINAME);
                $query_sparepart_data = sqlsrv_query($conn, $sql_sparepart_data, $params_sparepart);
                
                if ($query_sparepart_data) {
                    $sparepart_data = sqlsrv_fetch_array($query_sparepart_data, SQLSRV_FETCH_ASSOC);
                    
                    // คำนวณวันที่และสถานะ
                    if (isset($sparepart_data[$LCD]) && $sparepart_data[$LCD] != null) {
                        $lactchangedate = $sparepart_data[$LCD];
                        if (is_object($lactchangedate)) {
                            $lactchangedate_display = $lactchangedate->format('d/m/Y');
                            $lactchangedate_str = $lactchangedate->format('Y-m-d');
                        } else {
                            $lactchangedate_display = date("d/m/Y", strtotime($lactchangedate));
                            $lactchangedate_str = date("Y-m-d", strtotime($lactchangedate));
                        }
                        
                        // คำนวณวันที่ครบกำหนด
                        $expire_date_str = date("Y-m-d", strtotime("+$PJSPP_EXPIRE_YEAR year", strtotime($lactchangedate_str)));
                        $expire_date_display = date("d/m/Y", strtotime($expire_date_str));
                        
                        // คำนวณจำนวนวันที่เหลือ
                        $date1 = new DateTime(date("Y-m-d"));
                        $date2 = new DateTime($expire_date_str);
                        $diff = $date1->diff($date2);
                        
                        // แปลงเป็นจำนวนวัน
                        $day_diff = $diff->days;
                        if ($date1 > $date2) {
                            $day_diff = -$day_diff;
                        }
                        
                        if ($day_diff < 0) {
                            $day_diff_display = "เกิน " . abs($day_diff) . " วัน";
                            $font_color = 'FF0000'; // สีแดง
                        } elseif ($day_diff == 0) {
                            $day_diff_display = "ครบกำหนดวันนี้";
                            $font_color = 'FF8C00'; // สีส้ม
                        } elseif ($day_diff <= 30) {
                            $day_diff_display = "เหลือ " . $day_diff . " วัน";
                            $font_color = 'FFA500'; // สีส้มอ่อน
                        } else {
                            $day_diff_display = "เหลือ " . $day_diff . " วัน";
                            // $font_color = '008000'; // สีเขียว
                            $font_color = '000000'; // สีดำเป็นค่า default
                        }
                        
                        $remarkdisplay = isset($sparepart_data[$REMARK]) ? $sparepart_data[$REMARK] : '';
                    }
                }
            }
            
            // ใส่ข้อมูลลงใน Excel
            $sheet->setCellValue('A'.$row, $no);
            $sheet->setCellValue('B'.$row, $VEHICLEREGISNUMBER);
            $sheet->setCellValue('C'.$row, $THAINAME);
            $sheet->setCellValue('D'.$row, $REGISTYPE);
            $sheet->setCellValue('E'.$row, $AFFCOMPANY);
            $sheet->setCellValue('F'.$row, $PJSPP_EXPIRE_YEAR);
            $sheet->setCellValue('G'.$row, $lactchangedate_display);
            $sheet->setCellValue('H'.$row, $expire_date_display);
            $sheet->setCellValue('I'.$row, $day_diff_display);
            $sheet->setCellValue('J'.$row, $remarkdisplay);
            
            // กำหนดสีตัวอักษรตามสถานะ
            $sheet->getStyle('A'.$row.':J'.$row)->getFont()->getColor()->setRGB($font_color);
            
            // จัดตำแหน่งข้อความ
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('D'.$row.':F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G'.$row.':I'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
            // ใส่เส้นขอบ
            $sheet->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            
            $row++;
        }
        
        // ถ้าไม่มีข้อมูลใน sheet นี้ ให้แสดงข้อความ
        if (!$sheet_has_data) {
            $sheet->setCellValue('A3', 'ไม่มีข้อมูลรถสำหรับอะไหล่นี้');
            $sheet->mergeCells('A3:J3');
            $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A3')->getFont()->setItalic(true);
            $sheet->getStyle('A3')->getFont()->getColor()->setRGB('666666');
        }
        
        $sheet_index++;
    }
    
    // ตรวจสอบว่ามีข้อมูลหรือไม่ (กรณีไม่มี sheet ใดเลย)
    if (!$has_data && $sheet_index == 0) {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('No Data');
        $sheet->setCellValue('A1', 'ไม่พบข้อมูลสำหรับเงื่อนไขที่เลือก');
    }
    
    // ล้าง output buffer
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // ตั้งชื่อไฟล์
    $safe_group_name = preg_replace('/[^\w\s-]/', '', $group_name);
    $filename = "Equipment_Plans_Report_".str_replace(array(' ', '-', '/'), '', $safe_group_name); // กำหนดชื่อไฟล์ให้ปลอดภัยสำหรับ URL
    // $filename = "Equipment_Plans_Report_" . str_replace(array(' ', '-', '/'), '_', $safe_group_name) . "_" . date('Y-m-d'); // กำหนดชื่อไฟล์ให้ปลอดภัยสำหรับ URL
    
    // ตั้งค่า headers
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
?>