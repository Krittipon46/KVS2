<?php
ob_start();
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $phone = trim($_POST['password']); 

    $stmt = $conn->prepare("SELECT * FROM Technician WHERE email = ? AND phone = ?");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $tech = $result->fetch_assoc();
        
        // ใช้ตัวแปรเฉพาะสำหรับช่าง ป้องกันการชนกับระบบแอดมิน
        $_SESSION['tech_id'] = $tech['technicianId'];
        $_SESSION['tech_name'] = $tech['name'];
        $_SESSION['tech_role'] = 'technician';
        
        echo "<script>
                alert('เข้าสู่ระบบสำเร็จ! ยินดีต้อนรับช่าง " . $tech['name'] . "'); 
                window.location.href='tech_dashboard.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('อีเมล หรือ เบอร์โทรศัพท์ ไม่ถูกต้อง'); window.history.back();</script>";
        exit();
    }
    $stmt->close();
}
$conn->close();
?>