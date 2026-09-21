<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ดึงชื่อไฟล์รูปภาพออกมาก่อนลบ
    $stmt = $conn->prepare("SELECT image FROM Product WHERE productId = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // ลบไฟล์รูปจริงออกจากโฟลเดอร์ (ถ้าไม่ใช่รูป default)
        if (!empty($row['image']) && $row['image'] !== 'default.png') {
            $filePath = "uploads/" . $row['image'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }
    $stmt->close();

    // ลบข้อมูลออกจากฐานข้อมูล
    $stmt_del = $conn->prepare("DELETE FROM Product WHERE productId = ?");
    $stmt_del->bind_param("i", $id);

    if ($stmt_del->execute()) {
        echo "<script>alert('ลบสินค้าเรียบร้อยแล้ว'); window.location.href='admin_products.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบ'); window.location.href='admin_products.php';</script>";
    }
    $stmt_del->close();
} else {
    header("Location: admin_products.php");
}
$conn->close();
?>