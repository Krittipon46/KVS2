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
    <title>เพิ่มลูกค้าใหม่ - KVS Shop</title>
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
    <div class="w-full h-screen overflow-y-auto ml-64 p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">เพิ่มรายชื่อลูกค้าใหม่</h2>
            <a href="admin_users.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow flex items-center transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> กลับไปหน้าจัดการผู้ใช้
            </a>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-md max-w-3xl">
            <form action="admin_add_customer_action.php" method="POST">
                
                <!-- ส่วนที่ 1: ข้อมูลส่วนตัว -->
                <h3 class="text-lg font-bold text-blue-600 mb-4 border-b pb-2"><i class="fas fa-user-circle mr-2"></i> ข้อมูลส่วนตัว</h3>
                
                <div class="grid grid-cols-12 gap-4 mb-4">
                    <div class="col-span-3">
                        <label class="block text-gray-700 font-medium mb-2">คำนำหน้า</label>
                        <select name="title" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">เลือก</option>
                            <option value="นาย">นาย</option>
                            <option value="นาง">นาง</option>
                            <option value="นางสาว">นางสาว</option>
                        </select>
                    </div>
                    <div class="col-span-4">
                        <label class="block text-gray-700 font-medium mb-2">ชื่อจริง</label>
                        <input type="text" name="firstName" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-5">
                        <label class="block text-gray-700 font-medium mb-2">นามสกุล</label>
                        <input type="text" name="lastName" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">อีเมล (สำหรับเข้าสู่ระบบ)</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">เบอร์โทรศัพท์</label>
                        <input type="tel" name="phone" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">ตั้งรหัสผ่าน</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- ส่วนที่ 2: ข้อมูลที่อยู่ -->
                <h3 class="text-lg font-bold text-blue-600 mb-4 border-b pb-2 mt-8"><i class="fas fa-map-marker-alt mr-2"></i> ข้อมูลที่อยู่</h3>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">บ้านเลขที่ / หมู่บ้าน / ซอย</label>
                    <textarea name="addressDetail" rows="2" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">จังหวัด</label>
                        <input type="text" name="province" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="เช่น กรุงเทพมหานคร">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">เขต/อำเภอ</label>
                        <input type="text" name="district" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="เช่น ดินแดง">
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 font-medium mb-2">รหัสไปรษณีย์</label>
                    <input type="text" name="postalCode" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="เช่น 10400">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300 shadow-md">
                    <i class="fas fa-save mr-2"></i> บันทึกข้อมูลลูกค้า
                </button>
            </form>
        </div>
    </div>
</body>
</html>