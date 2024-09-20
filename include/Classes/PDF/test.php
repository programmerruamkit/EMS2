<?php
// โหลด mPDF อัตโนมัติ
require_once __DIR__ . '/vendor/autoload.php';

// สรา้งคลาส
// $mpdf = new \Mpdf\Mpdf(); // ของเดิมใช้ไม่ได้
$mpdf = new mPDF();

// เขียนโค้ด HTML
$mpdf->WriteHTML('<h1>Hello World</h1>');

// ส่งออกไฟล์ PDF ไปยังเบราว์เซอร์โดยตรง
$mpdf->Output();