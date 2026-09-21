<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "kvs_db";        

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ตั้งค่าให้รองรับภาษาไทย
$conn->set_charset("utf8mb4");
?>