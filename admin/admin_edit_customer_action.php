<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerId = $_POST['customerId'];
    $title = trim($_POST['title']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $addressDetail = trim($_POST['addressDetail']);
    $district = trim($_POST['district']);
    $province = trim($_POST['province']);
    $postalCode = trim($_POST['postalCode']);

    $stmt = $conn->prepare("UPDATE Customer SET title=?, first_name=?, last_name=?, email=?, phone=?, addressDetail=?, district=?, province=?, postalCode=? WHERE customerId=?");
    
    if ($stmt === false) {
        die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error);
    }

    $stmt->bind_param("sssssssssi", $title, $first_name, $last_name, $email, $phone, $addressDetail, $district, $province, $postalCode, $customerId);

    if ($stmt->execute()) {
        echo "<script>alert('อัปเดตข้อมูลลูกค้าเรียบร้อยแล้ว!'); window.location.href='admin_users.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดต'); window.history.back();</script>";
    }
    
    $stmt->close();
}
$conn->close();
?>