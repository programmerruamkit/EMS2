<?php
    // echo"<pre>";
    // print_r($_POST);
    // echo"</pre>";
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');

    $PROCESS_BY = $_SESSION["AD_PERSONCODE"];
    $PROCESS_DATE = date("Y-m-d H:i:s");	

    // ฟังก์ชันแปลงวันที่ไทย d/m/Y หรือ d/m/y เป็น Y-m-d H:i:s
    function convertThaiDate($dateStr) {
        if (empty($dateStr) || trim($dateStr) == '') {
            return NULL;
        }
        
        $dateStr = trim($dateStr);
        $parts = explode('/', $dateStr);
        
        if (count($parts) != 3) {
            return NULL;
        }
        
        $day = intval($parts[0]);
        $month = intval($parts[1]);
        $year = intval($parts[2]);
        
        // แปลงปี 2 หลักเป็น 4 หลัก
        if ($year < 100) {
            if ($year < 50) {
                $year += 2000; // 00-49 = 2000-2049
            } else {
                $year += 1900; // 50-99 = 1950-1999
            }
        }
        
        // ตรวจสอบความถูกต้องของวันที่
        if (!checkdate($month, $day, $year)) {
            return NULL;
        }
        
        return sprintf('%04d-%02d-%02d 00:00:00', $year, $month, $day);
    }

    if(!empty($_FILES["file"]["name"])){
        $count = 0;

        $sqldel = "DELETE FROM [dbo].[IMPORT_OUTER_GARAGE_TEMP]";
        $paramsdel = array();
        $stmtdel = sqlsrv_query( $conn, $sqldel, $paramsdel);

        $fileMimes = array(
            'text/x-comma-separated-values',
            'text/comma-separated-values',
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'text/plain'
        );
        
        if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)){

                $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

                fgetcsv($csvFile);

                while (($getData = fgetcsv($csvFile, 10000, ",")) !== FALSE){
                    // ข้ามคอลัมน์ No (index 0) และเริ่มอ่านจากคอลัมน์ Truck (index 1)
                    $temp0 = isset($getData[1]) ? $getData[1] : '';   // Truck
                    $temp1 = isset($getData[2]) ? $getData[2] : '';   // Section
                    $temp2 = isset($getData[3]) ? $getData[3] : '';   // Problems
                    $temp3 = isset($getData[4]) ? $getData[4] : '';   // Cause
                    $temp4 = isset($getData[5]) ? $getData[5] : '';   // RepairMethod
                    
                    // แปลงรูปแบบวันที่ด้วยฟังก์ชันใหม่
                    $temp5 = convertThaiDate(isset($getData[6]) ? $getData[6] : ''); // RepairDate
                    $temp6 = convertThaiDate(isset($getData[7]) ? $getData[7] : ''); // CompleteDate
                    
                    $temp7 = isset($getData[8]) ? $getData[8] : '';   // Garage
                    $temp8 = isset($getData[9]) ? $getData[9] : '';   // RepairPrice
                    $temp9 = isset($getData[10]) ? $getData[10] : ''; // Remark
                    $temp10 = $PROCESS_BY;                            // CREATEBY
                    $temp11 = $PROCESS_DATE;                          // CREATEDATE
                    
                    // กรองเฉพาะฟิลด์ที่ไม่เป็น NULL
                    $sql_fields = array();
                    $sql_values = array();
                    $params = array();
                    
                    // เพิ่มฟิลด์ที่จำเป็น
                    $sql_fields[] = 'Truck'; $sql_values[] = '?'; $params[] = $temp0;
                    $sql_fields[] = 'Section'; $sql_values[] = '?'; $params[] = $temp1;
                    $sql_fields[] = 'Problems'; $sql_values[] = '?'; $params[] = $temp2;
                    $sql_fields[] = 'Cause'; $sql_values[] = '?'; $params[] = $temp3;
                    $sql_fields[] = 'RepairMethod'; $sql_values[] = '?'; $params[] = $temp4;
                    
                    // วันที่ - เพิ่มเฉพาะถ้าไม่เป็น NULL
                    if ($temp5 !== NULL) {
                        $sql_fields[] = 'RepairDate'; $sql_values[] = '?'; $params[] = $temp5;
                    }
                    if ($temp6 !== NULL) {
                        $sql_fields[] = 'CompleteDate'; $sql_values[] = '?'; $params[] = $temp6;
                    }
                    
                    // เพิ่มฟิลด์อื่นๆ
                    $sql_fields[] = 'Garage'; $sql_values[] = '?'; $params[] = $temp7;
                    $sql_fields[] = 'RepairPrice'; $sql_values[] = '?'; $params[] = $temp8;
                    $sql_fields[] = 'Remark'; $sql_values[] = '?'; $params[] = $temp9;
                    $sql_fields[] = 'CREATEBY'; $sql_values[] = '?'; $params[] = $temp10;
                    $sql_fields[] = 'CREATEDATE'; $sql_values[] = '?'; $params[] = $temp11;
                    
                    $sql = "INSERT INTO IMPORT_OUTER_GARAGE_TEMP (" . implode(', ', $sql_fields) . ") VALUES (" . implode(', ', $sql_values) . ")";
                    
                    $stmt = sqlsrv_query($conn, $sql, $params);
                    
                    if (!$stmt) {
                        echo "Error occurred during insert: ";
                        echo "<pre>";
                        print_r(sqlsrv_errors());
                        echo "</pre>";
                        echo "<br>Data being inserted: ";
                        echo "<pre>";
                        print_r($params);
                        echo "</pre>";
                        die();
                    }
                    
                    $count++;
                }
                fclose($csvFile);

                echo $count;			
        }
    }
?>