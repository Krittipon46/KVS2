<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการหลังบ้าน (Admin) - KVS Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-md my-8 border-t-8 border-gray-900">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-900">KVS Shop</h2>
            <p class="text-gray-500 mt-2">สำหรับผู้ดูแลระบบ (Admin Only)</p>
        </div>
        
        <form action="admin_login_action.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">อีเมลผู้ดูแลระบบ</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">รหัสผ่าน</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50">
            </div>
            <button type="submit" class="w-full bg-gray-900 text-white font-bold py-3 rounded-lg hover:bg-black transition duration-300">
                เข้าสู่ระบบผู้ดูแล
            </button>
        </form>
    </div>
</body>
</html>