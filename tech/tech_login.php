<?php
session_start();
if (isset($_SESSION['tech_id'])) {
    header("Location: tech_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบช่าง - KVS Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border-t-4 border-green-500">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800"><i class="fas fa-tools text-green-500 mr-2"></i> KVS Shop</h2>
            <p class="text-gray-500 mt-2">ระบบจัดการงานสำหรับทีมช่าง</p>
        </div>
        
        <form action="tech_login_action.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">อีเมล (Email)</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                    <input type="email" name="email" required class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            
            <div class="mb-6">
               <label class="block text-gray-700 font-bold mb-2">รหัสผ่าน <span class="text-red-500 font-normal">(ใช้เบอร์โทรศัพท์ของคุณ)</span></label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                    <input type="password" name="password" required class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg shadow-md transition duration-300">
                เข้าสู่ระบบช่าง
            </button>
        </form>
    </div>
</body>
</html>