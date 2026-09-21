<?php
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ค้นหาข้อมูลจากตาราง Admin
    $stmt = $conn->prepare("SELECT adminId, name, password FROM Admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $row['password'])) {
            
            // เก็บข้อมูลลง Session
            $_SESSION['user_id'] = $row['adminId'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['role'] = 'admin'; // ระบุสิทธิ์ว่าเป็นแอดมิน
            
            echo "<script>alert('เข้าสู่ระบบ Admin สำเร็จ'); window.location.href='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('ไม่พบอีเมลผู้ดูแลระบบนี้'); window.history.back();</script>";
    }
    $stmt->close();
}
$conn->close();
?>