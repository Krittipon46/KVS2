<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_users.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Customer WHERE customerId = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

if (!$customer) {
    echo "<script>alert('ไม่พบข้อมูลลูกค้า'); window.location.href='admin_users.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดลูกค้า - KVS Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen py-10">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-address-card text-blue-500 mr-2"></i> ข้อมูลลูกค้ารหัส #<?php echo $customer['customerId']; ?></h2>
            <a href="admin_users.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">กลับหน้าจัดการ</a>
        </div>
        
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-500">ชื่อ-นามสกุล</p>
                <p class="font-bold text-lg"><?php echo $customer['title'] . $customer['first_name'] . ' ' . $customer['last_name']; ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500">เบอร์โทรศัพท์</p>
                <p class="font-bold text-lg"><?php echo $customer['phone']; ?></p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-500">อีเมล</p>
                <p class="font-bold text-lg"><?php echo $customer['email']; ?></p>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border">
            <p class="text-sm text-gray-500 mb-2 font-bold"><i class="fas fa-map-marker-alt text-red-500 mr-1"></i> ที่อยู่สำหรับจัดส่ง/นัดช่าง</p>
            <p class="text-gray-800">
                <?php 
                echo !empty($customer['addressDetail']) ? $customer['addressDetail'] . "<br>" : "ยังไม่มีข้อมูลที่อยู่<br>";
                if(!empty($customer['district'])) echo "เขต/อำเภอ: " . $customer['district'] . "<br>";
                if(!empty($customer['province'])) echo "จังหวัด: " . $customer['province'] . " ";
                if(!empty($customer['postalCode'])) echo $customer['postalCode'];
                ?>
            </p>
        </div>
        
        <div class="mt-6 flex justify-end">
            <a href="admin_edit_customer.php?id=<?php echo $customer['customerId']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition"><i class="fas fa-edit mr-2"></i> แก้ไขข้อมูล</a>
        </div>
    </div>
</body>
</html>