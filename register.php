<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - KVS Shop</title>
    <!-- โหลด Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- สคริปต์ตรวจสอบรหัสผ่านให้ตรงกันก่อนส่ง -->
    <script>
        function checkPassword(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                alert("รหัสผ่านไม่ตรงกัน กรุณากรอกใหม่อีกครั้งครับ");
                event.preventDefault(); // หยุดการส่งฟอร์ม
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-10">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">สมัครสมาชิก KVS Shop (สำหรับลูกค้า)</h2>
        
        <!-- เพิ่ม onsubmit เพื่อเรียกใช้สคริปต์ตรวจรหัสผ่าน -->
        <form action="register_action.php" method="POST" onsubmit="return checkPassword(event);">
            
            <!-- ส่วนที่ 1: ข้อมูลส่วนตัว -->
            <h3 class="text-lg font-bold text-gray-700 mb-4">ข้อมูลส่วนตัว</h3>
            
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">คำนำหน้า</label>
                    <select name="title" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">เลือก</option>
                        <option value="นาย">นาย</option>
                        <option value="นาง">นาง</option>
                        <option value="นางสาว">นางสาว</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">ชื่อ</label>
                    <input type="text" name="firstName" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">นามสกุล</label>
                    <input type="text" name="lastName" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">อีเมล</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">เบอร์โทรศัพท์</label>
                    <input type="tel" name="phone" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- แบ่งเป็น 2 ช่อง รหัสผ่าน และ ยืนยันรหัสผ่าน -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">รหัสผ่าน</label>
                    <input type="password" id="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">ยืนยันรหัสผ่าน</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- ส่วนที่ 2: ข้อมูลที่อยู่ -->
            <hr class="my-6 border-gray-300">
            <h3 class="text-lg font-bold text-gray-700 mb-4">ข้อมูลที่อยู่ (สำหรับการนัดหมายช่าง)</h3>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">บ้านเลขที่ / หมู่บ้าน / ซอย</label>
                <textarea name="addressDetail" rows="2" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">จังหวัด</label>
                    <input type="text" name="province" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="เช่น กรุงเทพมหานคร">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">เขต/อำเภอ</label>
                    <input type="text" name="district" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="เช่น ดินแดง">
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 font-medium mb-2">รหัสไปรษณีย์</label>
                <input type="text" name="postalCode" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="เช่น 10400">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                ยืนยันการสมัครสมาชิก
            </button>
        </form>

        <p class="text-center text-gray-500 mt-6">
            มีบัญชีอยู่แล้ว? <a href="login.php" class="text-blue-600 hover:underline">เข้าสู่ระบบที่นี่</a>
        </p>
    </div>

</body>
</html>