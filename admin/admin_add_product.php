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
    <title>เพิ่มสินค้าใหม่ - KVS Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- เพิ่ม JavaScript สำหรับซ่อน/แสดงช่องราคา -->
    <script>
        function togglePriceField() {
            const serviceType = document.getElementById('require_service').value;
            const priceWrapper = document.getElementById('priceWrapper');
            const priceInput = document.getElementById('priceInput');

            if (serviceType === '1') {
                // ถ้าเป็นงานช่าง: ซ่อนกล่องราคา และเอา required ออก
                priceWrapper.classList.add('hidden');
                priceInput.required = false;
                priceInput.value = 0; // บังคับค่าเป็น 0
            } else {
                // ถ้าเป็นสินค้าทั่วไป: แสดงกล่องราคา และบังคับกรอก
                priceWrapper.classList.remove('hidden');
                priceInput.required = true;
                if(priceInput.value == 0) priceInput.value = ''; // เคลียร์ค่า 0 ออกให้กรอกใหม่
            }
        }
    </script>
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
            <a href="admin_users.php" class="flex items-center text-gray-400 hover:text-white hover:bg-gray-800 py-4 pl-6 nav-item">
                <i class="fas fa-users mr-3 w-5 text-center"></i> จัดการข้อมูลผู้ใช้งาน
            </a>
            <a href="admin_products.php" class="flex items-center active-nav-link text-white py-4 pl-6 nav-item bg-gray-800 border-l-4 border-blue-500">
                <i class="fas fa-box-open mr-3 w-5 text-center"></i> จัดการสินค้าและสต๊อก
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="w-full h-screen overflow-y-auto ml-64 p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">เพิ่มสินค้าใหม่</h2>
            <a href="admin_products.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow flex items-center transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> กลับไปหน้ารายการสินค้า
            </a>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-md max-w-3xl">
            <form action="admin_add_product_action.php" method="POST" enctype="multipart/form-data">
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">ชื่อสินค้า <span class="text-red-500">*</span></label>
                    <input type="text" name="productName" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">รายละเอียดสินค้า</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">สีของสินค้า <span class="text-red-500">*</span></label>
                    <select name="color" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="ธรรมดา">ธรรมดา</option>
                        <option value="สีดำ">สีดำ</option>
                        <option value="สีอบขาว">สีอบขาว</option>
                    </select>
                </div>

                <!-- ย้ายประเภทสินค้ามาไว้ก่อนราคา และเพิ่ม onchange event -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">ประเภทสินค้า (การติดตั้ง) <span class="text-red-500">*</span></label>
                    <select name="require_service" id="require_service" onchange="togglePriceField()" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="0">สินค้าทั่วไป (ลูกค้าติดตั้งเอง / ส่งพัสดุ)</option>
                        <option value="1">ต้องใช้ช่างของร้านในการติดตั้ง (ไม่ต้องระบุราคา)</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <!-- เพิ่ม id="priceWrapper" ให้กล่องนี้ เพื่อให้ JS สั่งซ่อนได้ -->
                    <div id="priceWrapper">
                        <label class="block text-gray-700 font-medium mb-2">ราคา (บาท) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" id="priceInput" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="เช่น 1500.50">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">จำนวนสต๊อกตั้งต้น (ชิ้น) <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="0">
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 font-medium mb-2">รูปภาพสินค้า</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 cursor-pointer">
                    <p class="text-sm text-gray-500 mt-1">* รองรับไฟล์ .jpg, .png (หากไม่แนบ ระบบจะใช้รูปภาพพื้นฐาน)</p>
                </div>

                <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 rounded-lg hover:bg-green-600 transition duration-300 text-lg shadow-md">
                    <i class="fas fa-save mr-2"></i> บันทึกข้อมูลสินค้า
                </button>
            </form>
        </div>
    </div>
</body>
</html>