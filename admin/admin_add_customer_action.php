<?php
session_start();
require '../config/db.php';

// เช็คความปลอดภัย
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('ไม่มีสิทธิ์เข้าถึง'); window.location.href='admin_login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าส่วนที่ 1: ข้อมูลส่วนตัว
    $title = $_POST['title'];
    $first_name = trim($_POST['firstName']);
    $last_name = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $raw_password = $_POST['password'];
    
    // รับค่าส่วนที่ 2: ข้อมูลที่อยู่
    $addressDetail = trim($_POST['addressDetail']);
    $province = trim($_POST['province']);
    $district = trim($_POST['district']);
    $postalCode = trim($_POST['postalCode']);

    // เข้ารหัสผ่าน
    $password = password_hash($raw_password, PASSWORD_DEFAULT); 

    // คำสั่ง SQL สำหรับบันทึกข้อมูล (เพิ่มฟิลด์ที่อยู่เข้าไป)
    $stmt = $conn->prepare("INSERT INTO Customer (title, first_name, last_name, email, phone, password, addressDetail, province, district, postalCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error);
    }

    // ตัวแปร 10 ตัว = ssssssssss
    $stmt->bind_param("ssssssssss", $title, $first_name, $last_name, $email, $phone, $password, $addressDetail, $province, $district, $postalCode);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มข้อมูลลูกค้าใหม่สำเร็จ!'); window.location.href='admin_users.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: อีเมลนี้อาจมีในระบบอยู่แล้ว'); window.history.back();</script>";
    }
    
    $stmt->close();
}
$conn->close();
?>