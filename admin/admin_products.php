<?php
session_start();
require '../config/db.php';

// เช็คสิทธิ์แอดมิน
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('กรุณาเข้าสู่ระบบ'); window.location.href='admin_login.php';</script>";
    exit();
}

// ดึงข้อมูลสินค้าทั้งหมด
$sql = "SELECT * FROM Product ORDER BY productId DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสินค้า - KVS Shop</title>
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
            <a href="admin_users.php" class="flex items-center text-gray-400 hover:text-white hover:bg-gray-800 py-4 pl-6 nav-item">
                <i class="fas fa-users mr-3 w-5 text-center"></i> จัดการข้อมูลผู้ใช้งาน
            </a>
            <a href="admin_products.php" class="flex items-center active-nav-link text-white py-4 pl-6 nav-item bg-gray-800 border-l-4 border-blue-500">
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
            <h2 class="text-2xl font-bold text-gray-800">จัดการสินค้าและสต๊อก</h2>
            <a href="admin_add_product.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow flex items-center transition duration-300">
                <i class="fas fa-plus mr-2"></i> เพิ่มสินค้าใหม่
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden p-6">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <tr>
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-center">ภาพ</th>
                        <th class="py-3 px-6 text-left">ชื่อสินค้า</th>
                        <th class="py-3 px-6 text-center">สี</th>
                        <th class="py-3 px-6 text-center">ประเภท</th>
                        <th class="py-3 px-6 text-right">ราคา (฿)</th>
                        <th class="py-3 px-6 text-center">สต๊อก</th>
                        <th class="py-3 px-6 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php if($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 text-left whitespace-nowrap">#<?php echo $row['productId']; ?></td>
                            
                            <!-- โชว์รูปภาพสินค้าจริง -->
                            <td class="py-3 px-6 text-center">
                                <?php if(!empty($row['image']) && $row['image'] !== 'default.png'): ?>
                                    <img src="uploads/<?php echo $row['image']; ?>" class="w-12 h-12 rounded object-cover shadow mx-auto border">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-500 mx-auto shadow">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            
                            <td class="py-3 px-6 text-left font-medium"><?php echo $row['productName']; ?></td>
                            
                            <!-- โชว์สี -->
                            <td class="py-3 px-6 text-center text-gray-500"><?php echo isset($row['color']) ? $row['color'] : '-'; ?></td>
                            
                            <td class="py-3 px-6 text-center">
                                <?php if($row['require_service'] == '1'): ?>
                                    <span class="bg-purple-100 text-purple-600 py-1 px-3 rounded-full text-xs">ต้องใช้ช่าง</span>
                                <?php else: ?>
                                    <span class="bg-blue-100 text-blue-600 py-1 px-3 rounded-full text-xs">สินค้าทั่วไป</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-6 text-right text-green-600 font-bold"><?php echo number_format($row['price'], 2); ?></td>
                            <td class="py-3 px-6 text-center">
                                <?php if($row['stock'] > 0): ?>
                                    <span class="font-bold"><?php echo $row['stock']; ?></span>
                                <?php else: ?>
                                    <span class="text-red-500 font-bold">หมด</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex items-center justify-center space-x-4">
                                    
                                    <!-- ปุ่มแก้ไข -->
                                    <a href="admin_edit_product.php?id=<?php echo $row['productId']; ?>" 
                                       class="text-blue-500 hover:text-blue-700 transform hover:scale-110 transition duration-300 cursor-pointer p-2" 
                                       title="แก้ไขข้อมูล">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </a>

                                    <!-- ปุ่มลบ -->
                                    <a href="delete_product.php?id=<?php echo $row['productId']; ?>" 
                                       onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?');" 
                                       class="text-red-500 hover:text-red-700 transform hover:scale-110 transition duration-300 cursor-pointer p-2" 
                                       title="ลบข้อมูล">
                                        <i class="fas fa-trash-alt fa-lg"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="py-4 text-center text-red-500">ยังไม่มีสินค้าในระบบ</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>