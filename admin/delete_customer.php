<?php
session_start();
require '../config/db.php';

// เช็คความปลอดภัย ต้องเป็น admin เท่านั้นถึงลบได้
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('ไม่มีสิทธิ์เข้าถึง'); window.location.href='admin_login.php';</script>";
    exit();
}

// รับค่า ID ลูกค้าที่ถูกส่งมาจาก URL
if (isset($_GET['id'])) {
    $customerId = $_GET['id'];

    // สั่งลบข้อมูลออกจากฐานข้อมูล
    $stmt = $conn->prepare("DELETE FROM Customer WHERE customerId = ?");
    $stmt->bind_param("i", $customerId);

    if ($stmt->execute()) {
        echo "<script>alert('ลบข้อมูลลูกค้าเรียบร้อยแล้ว'); window.location.href='admin_users.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล'); window.location.href='admin_users.php';</script>";
    }
    $stmt->close();
} else {
    header("Location: admin_users.php");
}
$conn->close();
?>