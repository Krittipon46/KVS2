<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['productId'];
    $productName = trim($_POST['productName']);
    $description = trim($_POST['description']);
    $color = $_POST['color'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $require_service = $_POST['require_service'];
    
    // ตั้งค่ารูปภาพตั้งต้นเป็นรูปเดิมก่อน
    $imageName = $_POST['old_image']; 

    // เช็คว่ามีการอัปโหลดไฟล์รูปใหม่มาด้วยหรือไม่
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $newImageName = uniqid() . '.' . $file_extension; 
        $target_file = $target_dir . $newImageName;

        if (getimagesize($_FILES["image"]["tmp_name"]) !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                
                // ลบรูปภาพเก่าทิ้งเพื่อประหยัดพื้นที่ (ถ้าไม่ใช่ default)
                if ($imageName !== 'default.png' && file_exists($target_dir . $imageName)) {
                    unlink($target_dir . $imageName);
                }
                $imageName = $newImageName; // เปลี่ยนไปใช้ชื่อรูปใหม่
                
            }
        }
    }

    // อัปเดตข้อมูลลงฐานข้อมูล
    $stmt = $conn->prepare("UPDATE Product SET productName=?, description=?, color=?, price=?, stock=?, image=?, require_service=? WHERE productId=?");
    $stmt->bind_param("sssdissi", $productName, $description, $color, $price, $stock, $imageName, $require_service, $productId);

    if ($stmt->execute()) {
        echo "<script>alert('อัปเดตข้อมูลสินค้าเรียบร้อยแล้ว!'); window.location.href='admin_products.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดต'); window.history.back();</script>";
    }
    
    $stmt->close();
}
$conn->close();
?>