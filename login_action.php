<?php
session_start();
require 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // เปลี่ยนมาดึงข้อมูล title, first_name, last_name แทนช่อง name เดิม
    $stmt = $conn->prepare("SELECT customerId, title, first_name, last_name, password FROM Customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // ตรวจสอบรหัสผ่านว่าตรงกับที่ถูกเข้ารหัสไว้หรือไม่
        if (password_verify($password, $row['password'])) {
            
            // เก็บข้อมูลลง Session เพื่อเอาไปใช้หน้าอื่น
            $_SESSION['user_id'] = $row['customerId'];
            
            // จับคำนำหน้า ชื่อ และนามสกุล มาต่อกันเพื่อใช้แสดงผลบนหน้าเว็บ
            $fullName = $row['title'] . $row['first_name'] . ' ' . $row['last_name'];
            $_SESSION['user_name'] = $fullName; 
            
            $_SESSION['role'] = 'customer'; 
            
            echo "<script>alert('เข้าสู่ระบบสำเร็จ ยินดีต้อนรับคุณ " . $fullName . "'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('ไม่พบอีเมลนี้ในระบบ'); window.history.back();</script>";
    }
    $stmt->close();
}
$conn->close();
?>