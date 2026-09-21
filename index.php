<?php
session_start();
require 'config/db.php';

// 1. บังคับเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนเข้าชมสินค้า'); window.location.href='login.php';</script>";
    exit();
}

// 2. นับจำนวนสินค้าในตะกร้า
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}

// 3. รับค่าจากฟอร์มค้นหาและตัวกรอง
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// 4. สร้างคำสั่ง SQL 
$sql = "SELECT * FROM Product WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $sql .= " AND productName LIKE ?";
    $params[] = "%" . $search . "%";
    $types .= "s";
}

if ($category === 'general') {
    $sql .= " AND require_service = '0'";
} elseif ($category === 'tech') {
    $sql .= " AND require_service = '1'";
}

$sql .= " ORDER BY productId DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KVS Shop - หน้าหลัก</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1513694203232-719a280e022f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center">
                    <a href="index.php" class="text-2xl font-bold text-blue-700">
                        <i class="fas fa-tools mr-2"></i>KVS Shop
                    </a>
                </div>
                
                <div class="hidden md:flex space-x-8">
                    <a href="index.php" class="text-blue-600 font-medium">หน้าหลัก</a>
                    <a href="#products" class="text-gray-600 hover:text-blue-600 transition">สินค้าทั้งหมด</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition">บริการช่างของเรา</a>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- ไอคอนตะกร้าสินค้าลิงก์ไปหน้า cart.php -->
                    <a href="cart.php" class="text-gray-600 hover:text-blue-600 relative mt-1 mr-4">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <?php if($cart_count > 0): ?>
                            <span class="absolute -top-3 -right-3 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center shadow"><?php echo $cart_count; ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="text-sm text-gray-700 border-l pl-4">
                        สวัสดี, <span class="font-bold"><?php echo $_SESSION['user_name'] ?? 'ลูกค้า'; ?></span>
                    </div>
                    <a href="logout.php" class="text-sm bg-red-50 text-red-500 hover:bg-red-100 px-3 py-1 rounded transition">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Banner -->
    <div class="hero-bg h-96 flex items-center justify-center text-center">
        <div class="px-4">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 shadow-sm">ศูนย์รวมอุปกรณ์ประตูและมือจับคุณภาพ</h1>
            <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl mx-auto">ครบจบในที่เดียว ทั้งสินค้าพร้อมส่งและบริการติดตั้งโดยช่างผู้เชี่ยวชาญจาก KVS Shop</p>
            <a href="#products" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition transform hover:scale-105 text-lg">
                เลือกชมสินค้าเลย <i class="fas fa-arrow-down ml-2"></i>
            </a>
        </div>
    </div>

    <!-- ส่วนแสดงรายการสินค้า -->
    <div id="products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 border-b-4 border-blue-500 inline-block pb-2">รายการสินค้าของเรา</h2>
        </div>

        <!-- ฟอร์มค้นหา และ กรองประเภทสินค้า -->
        <form method="GET" action="index.php#products" class="max-w-4xl mx-auto mb-12 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="ค้นหาชื่อสินค้า..." class="w-full pl-11 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-gray-50 hover:bg-white transition">
                    </div>
                </div>

                <!-- เพิ่ม onchange="this.form.submit()" เพื่อให้หน้าเว็บโหลดใหม่ทันทีที่เลือกประเภท -->
                <div class="w-full md:w-64">
                    <select name="category" onchange="this.form.submit()" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-gray-50 hover:bg-white transition cursor-pointer">
                        <option value="">-- ทุกประเภท --</option>
                        <option value="general" <?php echo ($category === 'general') ? 'selected' : ''; ?>>สินค้าทั่วไป (ส่งพัสดุ)</option>
                        <option value="tech" <?php echo ($category === 'tech') ? 'selected' : ''; ?>>ต้องใช้ช่างติดตั้ง</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold transition shadow-sm">ค้นหา</button>
                    <?php if($search !== '' || $category !== ''): ?>
                        <a href="index.php#products" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-bold transition flex items-center justify-center shadow-sm" title="ล้างการค้นหา"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <!-- Grid แสดงสินค้า -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php if($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                
                <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col group">
                    <div class="h-56 overflow-hidden relative bg-gray-50 flex items-center justify-center">
                        <?php if(!empty($row['image']) && $row['image'] !== 'default.png'): ?>
                            <img src="uploads/<?php echo $row['image']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <i class="fas fa-image text-gray-300 text-5xl"></i>
                        <?php endif; ?>
                        
                        <div class="absolute top-2 left-2 bg-gray-900 text-white text-xs px-2 py-1 rounded bg-opacity-70">
                            สี: <?php echo $row['color']; ?>
                        </div>
                    </div>

                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="mb-2">
                                <?php if($row['require_service'] == '1'): ?>
                                    <span class="inline-block bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded-full font-medium"><i class="fas fa-tools mr-1"></i>ต้องใช้ช่างติดตั้ง</span>
                                <?php else: ?>
                                    <span class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-medium"><i class="fas fa-box-open mr-1"></i>สินค้าทั่วไป</span>
                                <?php endif; ?>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-1 line-clamp-2"><?php echo $row['productName']; ?></h3>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <?php if($row['require_service'] == '1'): ?>
                                <span class="text-gray-500 font-bold text-sm">ประเมินหน้างาน</span>
                            <?php else: ?>
                                <span class="text-green-600 font-bold text-xl">฿<?php echo number_format($row['price'], 2); ?></span>
                            <?php endif; ?>
                            
                            <!-- ปุ่มเพิ่มลงตะกร้า -->
                            <?php if($row['stock'] > 0 || $row['require_service'] == '1'): ?>
                                <a href="cart.php?action=add&id=<?php echo $row['productId']; ?>" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white h-10 w-10 rounded-full flex items-center justify-center transition shadow-sm" title="เพิ่มลงตะกร้า">
                                    <i class="fas fa-cart-plus"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-red-500 font-bold text-sm">สินค้าหมด</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                    <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg font-medium">ไม่พบสินค้าที่คุณค้นหา</p>
                    <a href="index.php#products" class="text-blue-500 hover:underline mt-2 inline-block">ดูสินค้าทั้งหมด</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>