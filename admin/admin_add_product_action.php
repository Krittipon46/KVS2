<?php
session_start();
require '../config/db.php';

// เช็คสิทธิ์
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('ไม่มีสิทธิ์เข้าถึง'); window.location.href='admin_login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = trim($_POST['productName']);
    $description = trim($_POST['description']);
    $color = $_POST['color'];
    $stock = $_POST['stock'];
    $require_service = $_POST['require_service'];

    // เช็คว่าถ้าเป็นงานช่าง บังคับราคาให้เป็น 0 ทันที
    if ($require_service == '1') {
        $price = 0;
    } else {
        $price = isset($_POST['price']) && $_POST['price'] !== '' ? $_POST['price'] : 0;
    }

    $imageName = 'default.png'; 

    // --- ส่วนจัดการอัปโหลดรูปภาพ ---
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $imageName = uniqid() . '.' . $file_extension; 
        $target_file = $target_dir . $imageName;

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "<script>alert('เกิดข้อผิดพลาด: ไม่สามารถอัปโหลดไฟล์รูปภาพได้'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ กรุณาเลือกไฟล์ใหม่'); window.history.back();</script>";
            exit();
        }
    }

    // --- ส่วนบันทึกลงฐานข้อมูล ---
    $stmt = $conn->prepare("INSERT INTO Product (productName, description, color, price, stock, image, require_service) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error);
    }

    $stmt->bind_param("sssdiss", $productName, $description, $color, $price, $stock, $imageName, $require_service);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มสินค้าใหม่เรียบร้อยแล้ว!'); window.location.href='admin_products.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error . "'); window.history.back();</script>";
    }
    
    $stmt->close();
}
$conn->close();
?>