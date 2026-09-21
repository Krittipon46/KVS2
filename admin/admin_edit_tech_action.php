<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $technicianId = $_POST['technicianId'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $technicianStatus = $_POST['technicianStatus'];

    // อัปเดตข้อมูลลงตาราง Technician
    $stmt = $conn->prepare("UPDATE Technician SET name=?, email=?, phone=?, technicianStatus=? WHERE technicianId=?");
    
    if ($stmt === false) {
        die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error);
    }

    $stmt->bind_param("ssssi", $name, $email, $phone, $technicianStatus, $technicianId);

    if ($stmt->execute()) {
        echo "<script>alert('อัปเดตข้อมูลช่างเรียบร้อยแล้ว!'); window.location.href='admin_users.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดต (อีเมลอาจซ้ำ)'); window.history.back();</script>";
    }
    
    $stmt->close();
}
$conn->close();
?>