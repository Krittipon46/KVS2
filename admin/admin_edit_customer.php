<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Customer WHERE customerId = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขลูกค้า - KVS Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen py-10">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-2xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">แก้ไขข้อมูลลูกค้า</h2>
        
        <form action="admin_edit_customer_action.php" method="POST">
            <input type="hidden" name="customerId" value="<?php echo $customer['customerId']; ?>">
            
            <div class="grid grid-cols-12 gap-4 mb-4">
                <div class="col-span-3">
                    <label class="block text-gray-700 font-medium mb-2">คำนำหน้า</label>
                    <select name="title" required class="w-full px-4 py-2 border rounded-lg bg-white">
                        <option value="นาย" <?php echo ($customer['title']=='นาย')?'selected':''; ?>>นาย</option>
                        <option value="นาง" <?php echo ($customer['title']=='นาง')?'selected':''; ?>>นาง</option>
                        <option value="นางสาว" <?php echo ($customer['title']=='นางสาว')?'selected':''; ?>>นางสาว</option>
                    </select>
                </div>
                <div class="col-span-4">
                    <label class="block text-gray-700 font-medium mb-2">ชื่อจริง</label>
                    <input type="text" name="first_name" value="<?php echo $customer['first_name']; ?>" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="col-span-5">
                    <label class="block text-gray-700 font-medium mb-2">นามสกุล</label>
                    <input type="text" name="last_name" value="<?php echo $customer['last_name']; ?>" required class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">อีเมล</label>
                    <input type="email" name="email" value="<?php echo $customer['email']; ?>" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">เบอร์โทรศัพท์</label>
                    <input type="tel" name="phone" value="<?php echo $customer['phone']; ?>" required class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-700 mt-6 mb-4 border-b pb-2">ข้อมูลที่อยู่</h3>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">รายละเอียดที่อยู่</label>
                <textarea name="addressDetail" rows="2" class="w-full px-4 py-2 border rounded-lg"><?php echo $customer['addressDetail']; ?></textarea>
            </div>
            
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">เขต/อำเภอ</label>
                    <input type="text" name="district" value="<?php echo $customer['district']; ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">จังหวัด</label>
                    <input type="text" name="province" value="<?php echo $customer['province']; ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">รหัสไปรษณีย์</label>
                    <input type="text" name="postalCode" value="<?php echo $customer['postalCode']; ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="w-1/2 bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700">บันทึกการแก้ไข</button>
                <a href="admin_users.php" class="w-1/2 bg-gray-400 text-white text-center font-bold py-3 rounded-lg hover:bg-gray-500">ยกเลิก</a>
            </div>
        </form>
    </div>
</body>
</html>