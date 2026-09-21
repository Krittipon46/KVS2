<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('ไม่มีสิทธิ์เข้าถึง'); window.location.href='admin_login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $raw_password = $_POST['password'];

    $password = password_hash($raw_password, PASSWORD_DEFAULT); 
    $status = 'ว่าง';

    // เปลี่ยนจาก status เป็น technicianStatus ให้ตรงกับตารางจริง
    $stmt = $conn->prepare("INSERT INTO Technician (name, email, phone, password, technicianStatus) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error);
    }

    $stmt->bind_param("sssss", $name, $email, $phone, $password, $status);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มข้อมูลช่างสำเร็จ!'); window.location.href='admin_users.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: อีเมลนี้อาจถูกใช้งานไปแล้ว'); window.history.back();</script>";
    }
    
    $stmt->close();
}
$conn->close();
?>