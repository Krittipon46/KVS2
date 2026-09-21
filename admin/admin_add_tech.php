<?php
session_start();
// เช็คสิทธิ์แอดมิน
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลช่าง - KVS Shop</title>
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
            <a href="admin_dashboard.php" class="flex items-center text-gray-400 hover:text-white hover:bg-gray-800 py-4 pl-6 nav-item">
                <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i> Dashboard
            </a>
            <a href="admin_users.php" class="flex items-center active-nav-link text-white py-4 pl-6 nav-item bg-gray-800 border-l-4 border-blue-500">
                <i class="fas fa-users mr-3 w-5 text-center"></i> จัดการข้อมูลผู้ใช้งาน
            </a>
            <a href="#" class="flex items-center text-gray-400 hover:text-white hover:bg-gray-800 py-4 pl-6 nav-item">
                <i class="fas fa-box-open mr-3 w-5 text-center"></i> จัดการสินค้าและสต๊อก
            </a>
            <a href="#" class="flex items-center text-gray-400 hover:text-white hover:bg-gray-800 py-4 pl-6 nav-item">
                <i class="fas fa-shopping-cart mr-3 w-5 text-center"></i> คำสั่งซื้อ & ชำระเงิน
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="w-full h-screen overflow-y-auto ml-64 p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">เพิ่มรายชื่อช่างใหม่ (Technician)</h2>
            <a href="admin_users.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> กลับไปหน้าจัดการผู้ใช้
            </a>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-md max-w-2xl">
            <form action="admin_add_tech_action.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">ชื่อ-นามสกุล (ช่าง)</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">อีเมล (สำหรับเข้าสู่ระบบ)</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">เบอร์โทรศัพท์ติดต่อ</label>
                        <input type="tel" name="phone" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">ตั้งรหัสผ่านชั่วคราว</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">* ระบบจะทำการเข้ารหัสผ่านก่อนบันทึกลงฐานข้อมูลเพื่อความปลอดภัย</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-save mr-2"></i> บันทึกข้อมูลช่าง
                </button>
            </form>
        </div>
    </div>
</body>
</html>