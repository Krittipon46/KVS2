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
$stmt = $conn->prepare("SELECT * FROM Technician WHERE technicianId = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$tech = $stmt->get_result()->fetch_assoc();

if (!$tech) {
    echo "<script>alert('ไม่พบข้อมูลช่าง'); window.location.href='admin_users.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดช่าง - KVS Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen py-10">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-tools text-green-500 mr-2"></i> ข้อมูลช่างรหัส #<?php echo $tech['technicianId']; ?></h2>
            <a href="admin_users.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">กลับหน้าจัดการ</a>
        </div>
        
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-500">ชื่อ-นามสกุล (ช่าง)</p>
                <p class="font-bold text-lg"><?php echo $tech['name']; ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500">สถานะการทำงาน</p>
                <p class="font-bold text-lg mt-1">
                    <?php if($tech['technicianStatus'] == 'ว่าง'): ?>
                        <span class="bg-green-200 text-green-700 py-1 px-3 rounded-full text-sm">ว่างพร้อมรับงาน</span>
                    <?php else: ?>
                        <span class="bg-red-200 text-red-700 py-1 px-3 rounded-full text-sm">ติดงาน</span>
                    <?php endif; ?>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">เบอร์โทรศัพท์ติดต่อ</p>
                <p class="font-bold text-lg"><?php echo $tech['phone']; ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500">อีเมล (สำหรับเข้าสู่ระบบ)</p>
                <p class="font-bold text-lg"><?php echo $tech['email']; ?></p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-500">วันที่เพิ่มเข้าระบบ</p>
                <p class="font-bold text-lg"><?php echo date("d/m/Y H:i", strtotime($tech['created_at'])); ?></p>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end border-t pt-6">
            <a href="admin_edit_tech.php?id=<?php echo $tech['technicianId']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition"><i class="fas fa-edit mr-2"></i> แก้ไขข้อมูลช่าง</a>
        </div>
    </div>
</body>
</html>