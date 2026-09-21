<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Technician WHERE technicianId = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$tech = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลช่าง - KVS Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen py-10">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-2xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">แก้ไขข้อมูลช่าง: <?php echo $tech['name']; ?></h2>
        
        <form action="admin_edit_tech_action.php" method="POST">
            <input type="hidden" name="technicianId" value="<?php echo $tech['technicianId']; ?>">
            
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">ชื่อ-นามสกุล (ช่าง)</label>
                <input type="text" name="name" value="<?php echo $tech['name']; ?>" required class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">อีเมล</label>
                    <input type="email" name="email" value="<?php echo $tech['email']; ?>" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">เบอร์โทรศัพท์</label>
                    <input type="tel" name="phone" value="<?php echo $tech['phone']; ?>" required class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 font-medium mb-2">สถานะการทำงาน</label>
                <select name="technicianStatus" class="w-full px-4 py-2 border rounded-lg bg-white">
                    <option value="ว่าง" <?php echo ($tech['technicianStatus'] == 'ว่าง') ? 'selected' : ''; ?>>ว่าง</option>
                    <option value="ติดงาน" <?php echo ($tech['technicianStatus'] == 'ติดงาน') ? 'selected' : ''; ?>>ติดงาน</option>
                </select>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="w-1/2 bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 shadow-md">บันทึกการแก้ไข</button>
                <a href="admin_users.php" class="w-1/2 bg-gray-400 text-white text-center font-bold py-3 rounded-lg hover:bg-gray-500 shadow-md">ยกเลิก</a>
            </div>
        </form>
    </div>
</body>
</html>