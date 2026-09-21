<?php
session_start();
require 'config/db.php';

// บังคับเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// สร้าง Session ตะกร้าสินค้าถ้ายังไม่มี
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// จัดการการเพิ่มสินค้าลงตะกร้า (Action = add)
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: cart.php");
    exit();
}

// จัดการการลบสินค้าออกจากตะกร้า (Action = remove)
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

// ดึงข้อมูลสินค้าที่อยู่ในตะกร้ามาแสดงผล
$cart_items = [];
$total_price = 0;
$has_service_item = false;

if (!empty($_SESSION['cart'])) {
    // ดึงเฉพาะ ID ที่อยู่ในตะกร้า
    $ids = array_keys($_SESSION['cart']);
    $ids_string = implode(',', array_map('intval', $ids));
    
    $sql = "SELECT * FROM Product WHERE productId IN ($ids_string)";
    $result = $conn->query($sql);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['quantity'] = $_SESSION['cart'][$row['productId']];
            
            // ถ้ารายการนี้เป็นช่าง ราคาจะเป็น 0 ไปก่อน (รอประเมิน)
            if ($row['require_service'] == '1') {
                $has_service_item = true;
                $row['subtotal'] = 0;
            } else {
                $row['subtotal'] = $row['price'] * $row['quantity'];
                $total_price += $row['subtotal'];
            }
            $cart_items[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า - KVS Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col">

    <!-- Navbar ย่อ -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="index.php" class="text-2xl font-bold text-blue-700">
                    <i class="fas fa-tools mr-2"></i>KVS Shop
                </a>
                <a href="index.php" class="text-gray-500 hover:text-blue-600 font-medium"><i class="fas fa-arrow-left mr-2"></i>กลับไปเลือกซื้อสินค้า</a>
            </div>
        </div>
    </nav>

    <!-- เนื้อหาตะกร้า -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow w-full">
        <h2 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-shopping-cart text-blue-500 mr-2"></i> ตะกร้าสินค้าของคุณ</h2>

        <?php if(empty($cart_items)): ?>
            <div class="bg-white rounded-xl shadow-sm p-16 text-center border border-gray-100">
                <i class="fas fa-shopping-basket text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-xl mb-6">ตะกร้าสินค้าของคุณยังว่างเปล่า</p>
                <a href="index.php#products" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full transition shadow">ไปเลือกซื้อสินค้ากันเลย</a>
            </div>
        <?php else: ?>
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- รายการสินค้าฝั่งซ้าย -->
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b border-gray-100 text-gray-600">
                                <tr>
                                    <th class="py-4 px-6 font-medium">สินค้า</th>
                                    <th class="py-4 px-6 font-medium text-center">ราคาต่อชิ้น</th>
                                    <th class="py-4 px-6 font-medium text-center">จำนวน</th>
                                    <th class="py-4 px-6 font-medium text-right">รวม</th>
                                    <th class="py-4 px-6 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach($cart_items as $item): ?>
                                <tr>
                                    <td class="py-4 px-6 flex items-center">
                                        <?php if(!empty($item['image']) && $item['image'] !== 'default.png'): ?>
                                            <img src="uploads/<?php echo $item['image']; ?>" class="w-16 h-16 rounded object-cover border mr-4">
                                        <?php else: ?>
                                            <div class="w-16 h-16 bg-gray-100 rounded border flex items-center justify-center mr-4 text-gray-400"><i class="fas fa-image"></i></div>
                                        <?php endif; ?>
                                        <div>
                                            <h4 class="font-bold text-gray-800"><?php echo $item['productName']; ?></h4>
                                            <p class="text-sm text-gray-500">สี: <?php echo $item['color']; ?></p>
                                            <?php if($item['require_service'] == '1'): ?>
                                                <span class="inline-block bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded mt-1"><i class="fas fa-tools mr-1"></i>รวมบริการช่าง</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center text-gray-600">
                                        <?php echo ($item['require_service'] == '1') ? '-' : '฿'.number_format($item['price'], 2); ?>
                                    </td>
                                    <td class="py-4 px-6 text-center font-bold text-gray-800">
                                        <?php echo $item['quantity']; ?>
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-blue-600">
                                        <?php echo ($item['require_service'] == '1') ? '<span class="text-sm text-gray-500">รอประเมิน</span>' : '฿'.number_format($item['subtotal'], 2); ?>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="cart.php?action=remove&id=<?php echo $item['productId']; ?>" class="text-red-400 hover:text-red-600 transition" title="ลบออกจากตะกร้า">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- สรุปยอดฝั่งขวา -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-800 border-b pb-4 mb-4">สรุปคำสั่งซื้อ</h3>
                        
                        <div class="flex justify-between mb-3 text-gray-600">
                            <span>ยอดรวมสินค้าทั่วไป</span>
                            <span class="font-bold text-gray-800">฿<?php echo number_format($total_price, 2); ?></span>
                        </div>
                        
                        <?php if($has_service_item): ?>
                            <div class="bg-purple-50 p-3 rounded text-sm text-purple-800 flex items-start mb-4 border border-purple-100">
                                <i class="fas fa-info-circle mt-1 mr-2"></i>
                                <p>ในคำสั่งซื้อนี้มี **งานช่างที่ต้องประเมินราคาหน้างาน** ทางแอดมินจะติดต่อกลับเพื่อแจ้งค่าบริการเพิ่มเติมครับ</p>
                            </div>
                        <?php endif; ?>

                        <div class="border-t pt-4 mb-6 flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-800">ยอดรวมสุทธิ</span>
                            <span class="text-2xl font-bold text-green-600">฿<?php echo number_format($total_price, 2); ?></span>
                        </div>

                        <!-- ลิงก์ไปหน้า Checkout (ยังไม่ได้สร้าง) -->
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-md transition flex justify-center items-center">
                            ดำเนินการชำระเงิน <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>