<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_products.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Product WHERE productId = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<script>alert('ไม่พบสินค้านี้'); window.location.href='admin_products.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขสินค้า - KVS Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen py-10">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-2xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">แก้ไขสินค้า: <?php echo $product['productName']; ?></h2>
        
        <form action="admin_edit_product_action.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="productId" value="<?php echo $product['productId']; ?>">
            <input type="hidden" name="old_image" value="<?php echo $product['image']; ?>">

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">ชื่อสินค้า</label>
                <input type="text" name="productName" value="<?php echo $product['productName']; ?>" required class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">รายละเอียดสินค้า</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg"><?php echo $product['description']; ?></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">สีของสินค้า</label>
                <select name="color" class="w-full px-4 py-2 border rounded-lg bg-white">
                    <option value="ธรรมดา" <?php echo ($product['color'] == 'ธรรมดา') ? 'selected' : ''; ?>>ธรรมดา</option>
                    <option value="สีดำ" <?php echo ($product['color'] == 'สีดำ') ? 'selected' : ''; ?>>สีดำ</option>
                    <option value="สีอบขาว" <?php echo ($product['color'] == 'สีอบขาว') ? 'selected' : ''; ?>>สีอบขาว</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">ราคา (บาท)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">สต๊อก</label>
                    <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">ประเภทการติดตั้ง</label>
                <select name="require_service" class="w-full px-4 py-2 border rounded-lg bg-white">
                    <option value="0" <?php echo ($product['require_service'] == '0') ? 'selected' : ''; ?>>สินค้าทั่วไป</option>
                    <option value="1" <?php echo ($product['require_service'] == '1') ? 'selected' : ''; ?>>ต้องใช้ช่างติดตั้ง</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">อัปเดตรูปภาพใหม่ (ปล่อยว่างถ้าใช้รูปเดิม)</label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
                <?php if($product['image'] && $product['image'] !== 'default.png'): ?>
                    <p class="text-sm text-green-600 mt-2">มีรูปภาพปัจจุบันในระบบแล้ว</p>
                <?php endif; ?>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="w-1/2 bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700">บันทึกการแก้ไข</button>
                <a href="admin_products.php" class="w-1/2 bg-gray-400 text-white text-center font-bold py-3 rounded-lg hover:bg-gray-500">ยกเลิก</a>
            </div>
        </form>
    </div>
</body>
</html>