<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../config/db.php';

// เช็คสิทธิ์แอดมิน
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('กรุณาเข้าสู่ระบบ'); window.location.href='admin_login.php';</script>";
    exit();
}

// 1. ดึงจำนวนลูกค้าทั้งหมดในระบบ
$sql_customers = "SELECT COUNT(*) as total_customers FROM Customer";
$result_customers = $conn->query($sql_customers);
$total_customers = 0;
if ($result_customers && $row = $result_customers->fetch_assoc()) {
    $total_customers = $row['total_customers'];
}

// 2. ดึงจำนวนสินค้าทั้งหมด และแยกประเภท
$sql_products = "SELECT 
                    COUNT(*) as total_products,
                    SUM(CASE WHEN require_service = '0' THEN 1 ELSE 0 END) as total_general,
                    SUM(CASE WHEN require_service = '1' THEN 1 ELSE 0 END) as total_tech
                 FROM Product";
$result_products = $conn->query($sql_products);
$total_products = 0;
$total_general = 0;
$total_tech = 0;

if ($result_products && $row = $result_products->fetch_assoc()) {
    $total_products = $row['total_products'] ? $row['total_products'] : 0;
    $total_general = $row['total_general'] ? $row['total_general'] : 0;
    $total_tech = $row['total_tech'] ? $row['total_tech'] : 0;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - KVS Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans flex">

    <!-- Sidebar -->
    <div class="bg-gray-900 shadow-xl h-screen w-64 fixed left-0 top-0 overflow-y-auto">
        <div class="p-6">
            <h1 class="text-white text-2xl font-bold">KVS Shop <span class="text-blue-400 text-sm block mt-1">Admin Panel</span></h1>
        </div>
        <nav class="text-white text-base font-semibold pt-3">
            <a href="admin_dashboard.php" class="flex items-center active-nav-link text-white py-4 pl-6 nav-item bg-gray-800 border-l-4 border-blue-500">
                <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i> Dashboard
            </a>
            <a href="admin_users.php" class="flex items-center text-gray-400 hover:text-white hover:bg-gray-800 py-4 pl-6 nav-item">
                <i class="fas fa-users mr-3 w-5 text-center"></i> จัดการข้อมูลผู้ใช้งาน
            </a>
            <a href="admin_products.php" class="flex items-center text-gray-400 hover:text-white hover:bg-gray-800 py-4 pl-6 nav-item">
                <i class="fas fa-box-open mr-3 w-5 text-center"></i> จัดการสินค้าและสต๊อก
            </a>
            <a href="#" class="flex items-center text-gray-400 hover:text-white hover:bg-gray-800 py-4 pl-6 nav-item">
                <i class="fas fa-shopping-cart mr-3 w-5 text-center"></i> คำสั่งซื้อ & ชำระเงิน
            </a>
            <a href="#" class="flex items-center text-gray-400 hover:text-white hover:bg-gray-800 py-4 pl-6 nav-item">
                <i class="fas fa-file-invoice-dollar mr-3 w-5 text-center"></i> จัดการใบเสนอราคา
            </a>
            <a href="#" class="flex items-center text-gray-400 hover:text-white hover:bg-gray-800 py-4 pl-6 nav-item">
                <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i> จัดการตารางงานช่าง
            </a>
            <a href="#" class="flex items-center text-gray-400 hover:text-white hover:bg-gray-800 py-4 pl-6 nav-item">
                <i class="fas fa-clipboard-list mr-3 w-5 text-center"></i> ติดตามการดำเนินงาน
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="w-full h-screen overflow-y-auto ml-64 bg-gray-50">
        
        <!-- Topbar -->
        <div class="bg-white shadow-sm px-8 py-4 flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-700">ภาพรวมระบบ (Overview)</h2>
            <div class="flex items-center gap-4">
                <span class="text-gray-600">สวัสดี, Administrator</span>
                <a href="admin_logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-sign-out-alt mr-2"></i> ออกจากระบบ
                </a>
            </div>
        </div>

        <div class="p-8 pt-0">
            <!-- Grid สำหรับกล่องสถิติ 4 กล่อง -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                
                <!-- กล่อง 1: คำสั่งซื้อ -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 flex flex-col justify-between">
                    <h3 class="text-blue-500 text-2xl font-bold mb-2">คำสั่งซื้อรอดำเนินการ</h3>
                    <p class="text-4xl font-bold text-gray-800 mt-2">0 <span class="text-xl font-medium text-gray-500 ml-1">รายการ</span></p>
                </div>

                <!-- กล่อง 2: ใบเสนอราคา -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 flex flex-col justify-between">
                    <h3 class="text-yellow-500 text-2xl font-bold mb-2">ใบเสนอราคารออนุมัติ</h3>
                    <p class="text-4xl font-bold text-gray-800 mt-2">0 <span class="text-xl font-medium text-gray-500 ml-1">รายการ</span></p>
                </div>

                <!-- กล่อง 3: ลูกค้าในระบบ -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 flex flex-col justify-between">
                    <h3 class="text-purple-500 text-2xl font-bold mb-2">ลูกค้าในระบบ</h3>
                    <p class="text-4xl font-bold text-gray-800 mt-2"><?php echo $total_customers; ?> <span class="text-xl font-medium text-gray-500 ml-1">คน</span></p>
                </div>

                <!-- กล่อง 4: สินค้าในระบบ -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 flex flex-col justify-between">
                    <div>
                        <h3 class="text-green-500 text-2xl font-bold mb-2">สินค้าในระบบ</h3>
                        <p class="text-4xl font-bold text-gray-800 mt-2"><?php echo $total_products; ?> <span class="text-xl font-medium text-gray-500 ml-1">รายการ</span></p>
                    </div>
                    <!-- ส่วนแยกประเภทสินค้า -->
                    <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between text-base text-gray-600">
                        <span>ทั่วไป: <strong class="text-gray-800 text-lg"><?php echo $total_general; ?></strong></span>
                        <span>ต้องใช้ช่าง: <strong class="text-gray-800 text-lg"><?php echo $total_tech; ?></strong></span>
                    </div>
                </div>

            </div>

            <!-- กล่องเนื้อหาด้านล่าง -->
            <div class="bg-white rounded-lg shadow-md p-10 flex justify-center items-center h-64 border border-gray-100">
                <p class="text-gray-400">พื้นที่สำหรับแสดงกราฟหรือข้อมูลตารางล่าสุด</p>
            </div>
        </div>

    </div>

</body>
</html>