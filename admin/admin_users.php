<?php
session_start();
require '../config/db.php';

// เช็คสิทธิ์แอดมิน
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('กรุณาเข้าสู่ระบบ'); window.location.href='admin_login.php';</script>";
    exit();
}

// ดึงข้อมูลลูกค้า
$sql_customers = "SELECT * FROM Customer ORDER BY customerId DESC";
$result_customers = $conn->query($sql_customers);

// ดึงข้อมูลช่าง
$sql_techs = "SELECT * FROM Technician ORDER BY technicianId DESC";
$result_techs = $conn->query($sql_techs);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้งาน - KVS Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        // ฟังก์ชันสลับ Tab และปุ่มเพิ่มข้อมูล
        function switchTab(tabName) {
            // ซ่อนตารางทั้ง 2 อันก่อน
            document.getElementById('customerTab').classList.add('hidden');
            document.getElementById('techTab').classList.add('hidden');
            
            // รีเซ็ตสีแท็บ
            document.getElementById('btnCustomer').classList.remove('border-blue-500', 'text-blue-600');
            document.getElementById('btnTech').classList.remove('border-blue-500', 'text-blue-600');
            
            if(tabName === 'customer') {
                // แสดงตารางลูกค้า และเปลี่ยนสีแท็บ
                document.getElementById('customerTab').classList.remove('hidden');
                document.getElementById('btnCustomer').classList.add('border-blue-500', 'text-blue-600');
                
                // สลับปุ่มเป็น "เพิ่มลูกค้าใหม่"
                document.getElementById('btnAddCustomer').classList.remove('hidden');
                document.getElementById('btnAddTech').classList.add('hidden');
            } else {
                // แสดงตารางช่าง และเปลี่ยนสีแท็บ
                document.getElementById('techTab').classList.remove('hidden');
                document.getElementById('btnTech').classList.add('border-blue-500', 'text-blue-600');
                
                // สลับปุ่มเป็น "เพิ่มช่างใหม่"
                document.getElementById('btnAddTech').classList.remove('hidden');
                document.getElementById('btnAddCustomer').classList.add('hidden');
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
            <h2 class="text-2xl font-bold text-gray-800">จัดการข้อมูลผู้ใช้งาน</h2>
            
            <!-- โซนปุ่มเพิ่มข้อมูล (จะแสดงสลับกันตามการกด Tab) -->
            <div>
                <!-- ปุ่มเพิ่มลูกค้า (โชว์เป็นค่าเริ่มต้น) -->
                <a href="admin_add_customer.php" id="btnAddCustomer" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow flex items-center transition duration-300">
                    <i class="fas fa-user-plus mr-2"></i> เพิ่มลูกค้าใหม่
                </a>
                
                <!-- ปุ่มเพิ่มช่าง (ซ่อนไว้เป็นค่าเริ่มต้นด้วย class 'hidden') -->
                <a href="admin_add_tech.php" id="btnAddTech" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow flex items-center transition duration-300 hidden">
                    <i class="fas fa-tools mr-2"></i> เพิ่มช่างใหม่
                </a>
            </div>
        </div>

        <!-- ระบบ Tabs -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="flex border-b border-gray-200">
                <button id="btnCustomer" onclick="switchTab('customer')" class="flex-1 py-4 px-6 text-center font-medium border-b-2 border-blue-500 text-blue-600 focus:outline-none">
                    <i class="fas fa-user mr-2"></i> ข้อมูลลูกค้า
                </button>
                <button id="btnTech" onclick="switchTab('tech')" class="flex-1 py-4 px-6 text-center font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-tools mr-2"></i> ข้อมูลช่าง
                </button>
            </div>

            <!-- Tab: ตารางลูกค้า -->
            <div id="customerTab" class="p-6">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <tr>
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">ชื่อ-นามสกุล</th>
                            <th class="py-3 px-6 text-left">อีเมล</th>
                            <th class="py-3 px-6 text-center">เบอร์โทร</th>
                            <th class="py-3 px-6 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php if($result_customers && $result_customers->num_rows > 0): ?>
                            <?php while($row = $result_customers->fetch_assoc()): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left whitespace-nowrap">#<?php echo $row['customerId']; ?></td>
                                <td class="py-3 px-6 text-left">
                                    <?php 
                                        if(isset($row['first_name'])) {
                                            echo $row['title'] . $row['first_name'] . ' ' . $row['last_name'];
                                        } else {
                                            echo $row['name'];
                                        }
                                    ?>
                                </td>
                                <td class="py-3 px-6 text-left"><?php echo $row['email']; ?></td>
                                <td class="py-3 px-6 text-center"><?php echo $row['phone']; ?></td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- ปุ่มดูข้อมูลลูกค้า -->
                                        <a href="admin_view_customer.php?id=<?php echo $row['customerId']; ?>" 
                                           class="text-purple-500 hover:text-purple-700 transform hover:scale-110 transition duration-300 cursor-pointer p-2" title="ดูข้อมูล">
                                            <i class="fas fa-eye fa-lg"></i>
                                        </a>
                                        <!-- ปุ่มแก้ไขข้อมูลลูกค้า -->
                                        <a href="admin_edit_customer.php?id=<?php echo $row['customerId']; ?>" 
                                           class="text-blue-500 hover:text-blue-700 transform hover:scale-110 transition duration-300 cursor-pointer p-2" title="แก้ไขข้อมูล">
                                            <i class="fas fa-edit fa-lg"></i>
                                        </a>
                                        <!-- ปุ่มลบข้อมูลลูกค้า -->
                                        <a href="delete_customer.php?id=<?php echo $row['customerId']; ?>" 
                                           onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบลูกค้ารายนี้? ข้อมูลจะไม่สามารถกู้คืนได้');" 
                                           class="text-red-500 hover:text-red-700 transform hover:scale-110 transition duration-300 cursor-pointer p-2" title="ลบข้อมูล">
                                            <i class="fas fa-trash-alt fa-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="py-4 text-center text-red-500">ยังไม่มีข้อมูลลูกค้าในระบบ</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tab: ตารางช่าง -->
            <div id="techTab" class="p-6 hidden">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <tr>
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">ชื่อช่าง</th>
                            <th class="py-3 px-6 text-left">อีเมล</th>
                            <th class="py-3 px-6 text-center">สถานะ</th>
                            <th class="py-3 px-6 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php if($result_techs && $result_techs->num_rows > 0): ?>
                            <?php while($row = $result_techs->fetch_assoc()): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left whitespace-nowrap">#<?php echo $row['technicianId']; ?></td>
                                <td class="py-3 px-6 text-left font-medium"><?php echo $row['name']; ?></td>
                                <td class="py-3 px-6 text-left"><?php echo $row['email']; ?></td>
                                <td class="py-3 px-6 text-center">
                                    <?php if($row['technicianStatus'] == 'ว่าง'): ?>
                                        <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">ว่าง</span>
                                    <?php else: ?>
                                        <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">ติดงาน</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- ปุ่มดูข้อมูลช่าง (อัปเดตลิงก์แล้ว) -->
                                        <a href="admin_view_tech.php?id=<?php echo $row['technicianId']; ?>" 
                                           class="text-purple-500 hover:text-purple-700 transform hover:scale-110 transition duration-300 cursor-pointer p-2" title="ดูข้อมูล">
                                            <i class="fas fa-eye fa-lg"></i>
                                        </a>
                                        <!-- ปุ่มแก้ไขข้อมูลช่าง (อัปเดตลิงก์แล้ว) -->
                                        <a href="admin_edit_tech.php?id=<?php echo $row['technicianId']; ?>" 
                                           class="text-blue-500 hover:text-blue-700 transform hover:scale-110 transition duration-300 cursor-pointer p-2" title="แก้ไขข้อมูล">
                                            <i class="fas fa-edit fa-lg"></i>
                                        </a>
                                        <!-- ปุ่มลบข้อมูลช่าง -->
                                        <a href="delete_tech.php?id=<?php echo $row['technicianId']; ?>" 
                                           onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบช่างรายนี้?');" 
                                           class="text-red-500 hover:text-red-700 transform hover:scale-110 transition duration-300 cursor-pointer p-2" title="ลบข้อมูล">
                                            <i class="fas fa-trash-alt fa-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="py-4 text-center text-red-500">ยังไม่มีข้อมูลช่างในระบบ</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>