<?php
require 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // ดักจับรหัสผ่านว่าตรงกันหรือไม่
    $raw_password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    if ($raw_password !== $confirmPassword) {
        echo "<script>alert('เกิดข้อผิดพลาด: รหัสผ่านไม่ตรงกัน'); window.history.back();</script>";
        exit(); 
    }

    // รับค่าแยกกัน 3 ตัวแปร
    $title = trim($_POST['title']);
    $first_name = trim($_POST['firstName']);
    $last_name = trim($_POST['lastName']);
    
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($raw_password, PASSWORD_DEFAULT); 
    
    $addressDetail = $_POST['addressDetail'];
    $province = $_POST['province'];
    $district = $_POST['district'];
    $postalCode = $_POST['postalCode'];

    // อัปเดตคำสั่ง SQL ให้บันทึกแยกลง 3 คอลัมน์ (title, first_name, last_name)
    $stmt = $conn->prepare("INSERT INTO Customer (title, first_name, last_name, email, phone, password, addressDetail, province, district, postalCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    // มีเครื่องหมาย ? 10 ตัว ก็ต้องผูกค่า 10 ตัว ("ssssssssss")
    $stmt->bind_param("ssssssssss", $title, $first_name, $last_name, $email, $phone, $password, $addressDetail, $province, $district, $postalCode);

    if ($stmt->execute()) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: อีเมลหรือเบอร์โทรนี้อาจมีในระบบแล้ว'); window.history.back();</script>";
    }
    $stmt->close();
}
$conn->close();
?>