<?php
ob_start();
session_start();
require '../config/db.php';

// เปลี่ยนมาเช็คสิทธิ์ด้วย tech_role แทน
if (!isset($_SESSION['tech_id']) || !isset($_SESSION['tech_role']) || $_SESSION['tech_role'] !== 'technician') {
    echo "<script>alert('กรุณาเข้าสู่ระบบช่าง'); window.location.href='tech_login.php';</script>";
    exit();
}

$tech_id = $_SESSION['tech_id'];
// หากมีการกดปุ่มอัปเดตสถานะ
if (isset($_POST['update_status'])) {
    $new_status = $_POST['new_status'];
    $update_stmt = $conn->prepare("UPDATE Technician SET technicianStatus = ? WHERE technicianId = ?");
    $update_stmt->bind_param("si", $new_status, $tech_id);
    if($update_stmt->execute()){
        header("Location: tech_dashboard.php");
        exit();
    }
}

// ดึงข้อมูลสถานะล่าสุดของช่างคนนี้
$stmt = $conn->prepare("SELECT * FROM Technician WHERE technicianId = ?");
$stmt->bind_param("i", $tech_id);
$stmt->execute();
$tech = $stmt->get_result()->fetch_assoc();

// กำหนดสีและไอคอนสำหรับแต่ละสถานะ
$status_settings = [
    'ว่าง' => ['color' => 'green', 'icon' => 'fa-check-circle', 'text' => 'text-green-600', 'bg' => 'bg-green-50', 'border' => 'border-l-green-500'],
    'กำลังเดินทาง' => ['color' => 'blue', 'icon' => 'fa-car', 'text' => 'text-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-l-blue-500'],
    'ถึงหน้างาน' => ['color' => 'purple', 'icon' => 'fa-map-marker-alt', 'text' => 'text-purple-600', 'bg' => 'bg-purple-50', 'border' => 'border-l-purple-500'],
    'เริ่มปฏิบัติงาน' => ['color' => 'orange', 'icon' => 'fa-tools', 'text' => 'text-orange-500', 'bg' => 'bg-orange-50', 'border' => 'border-l-orange-500'],
    'งานเสร็จสิ้น' => ['color' => 'teal', 'icon' => 'fa-check-double', 'text' => 'text-teal-600', 'bg' => 'bg-teal-50', 'border' => 'border-l-teal-500']
];

$current_status = $tech['technicianStatus'];
// ถ้าสถานะว่างเปล่าหรือไม่ตรงกับเงื่อนไข ให้ตั้งค่าเริ่มต้นเป็น 'ว่าง'
if (!array_key_exists($current_status, $status_settings)) {
    $current_status = 'ว่าง';
}
$ui = $status_settings[$current_status];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>พื้นที่ปฏิบัติงานช่าง - KVS Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#f8fafc] font-sans flex text-gray-800">

    <!-- Sidebar (แถบเมนูด้านซ้าย) -->
    <div class="bg-gray-900 shadow-xl h-screen w-64 fixed left-0 top-0 overflow-y-auto z-50">
        <div class="p-6">
            <h1 class="text-white text-2xl font-bold flex items-center">
                <i class="fas fa-tools text-green-400 mr-2"></i> KVS Shop
            </h1>
            <span class="text-green-400 text-xs tracking-wider font-bold block mt-1 uppercase">Technician Portal</span>
        </div>
        
        <div class="px-6 py-4 text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">เมนูการทำงาน</div>
        <nav class="text-white text-sm font-medium">
            <a href="tech_dashboard.php" class="flex items-center text-white bg-gray-800 border-l-4 border-green-500 py-3 pl-6 transition">
                <i class="fas fa-clipboard-list w-5 text-center mr-3"></i> ตารางงานวันนี้
            </a>
            <a href="#" class="flex items-center text-gray-400 hover:text-white hover:bg-gray-800 py-3 pl-6 transition">
                <i class="fas fa-history w-5 text-center mr-3"></i> ประวัติการรับงาน
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="w-full h-screen overflow-y-auto ml-64 bg-[#f8fafc]">
        
        <!-- Header -->
        <div class="bg-white border-b px-8 py-4 flex justify-between items-center sticky top-0 z-40">
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">KVS Operations • พื้นที่ปฏิบัติงานช่าง</div>
                <h2 class="text-xl font-bold text-gray-800">ตารางงาน & สถานะการปฏิบัติงาน</h2>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex flex-col text-right">
                    <span class="text-sm font-bold text-gray-800">ทีมช่าง <?php echo $tech['name']; ?></span>
                    <span class="text-xs text-gray-500">ID: #<?php echo str_pad($tech['technicianId'], 4, '0', STR_PAD_LEFT); ?></span>
                </div>
                <div class="h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center border text-gray-500">
                    <i class="fas fa-user-hard-hat"></i>
                </div>
                <div class="h-8 w-px bg-gray-300 mx-1"></div>
                <a href="logout.php" class="text-sm font-medium text-red-500 hover:text-red-700 transition flex items-center">
                    <i class="fas fa-sign-out-alt mr-1"></i> ออกจากระบบ
                </a>
            </div>
        </div>

        <div class="p-8 max-w-7xl mx-auto">
            
            <!-- สถิติ 3 กล่อง (เอาคะแนนประเมินออก) -->
            <div class="grid grid-cols-3 gap-6 mb-6">
                <!-- กล่องสถานะปัจจุบัน (เปลี่ยนสีตามสถานะ) -->
                <div class="bg-white rounded-lg border p-5 shadow-sm border-l-4 <?php echo $ui['border']; ?>">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs text-gray-500 font-bold mb-1 uppercase">สถานะปัจจุบัน</p>
                            <h3 class="text-2xl font-bold <?php echo $ui['text']; ?>"><?php echo $current_status; ?></h3>
                        </div>
                        <div class="<?php echo $ui['bg']; ?> <?php echo $ui['text']; ?> p-3 rounded-md text-xl">
                            <i class="fas <?php echo $ui['icon']; ?>"></i>
                        </div>
                    </div>
                </div>
                
                <!-- กล่องงานวันนี้ -->
                <div class="bg-white rounded-lg border p-5 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs text-gray-500 font-bold mb-1 uppercase">งานที่ได้รับมอบหมายวันนี้</p>
                            <h3 class="text-2xl font-bold text-gray-800"><?php echo ($current_status == 'ว่าง') ? '0' : '1'; ?> <span class="text-sm font-normal text-gray-500">งาน</span></h3>
                        </div>
                        <div class="bg-blue-50 text-blue-500 p-3 rounded-md text-xl"><i class="fas fa-clipboard-list"></i></div>
                    </div>
                </div>
                
                <!-- กล่องงานที่เสร็จแล้ว -->
                <div class="bg-white rounded-lg border p-5 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs text-gray-500 font-bold mb-1 uppercase">งานที่สำเร็จ (เดือนนี้)</p>
                            <h3 class="text-2xl font-bold text-gray-800">12 <span class="text-sm font-normal text-gray-500">งาน</span></h3>
                        </div>
                        <div class="bg-indigo-50 text-indigo-500 p-3 rounded-md text-xl"><i class="fas fa-check-double"></i></div>
                    </div>
                </div>
            </div>

            <!-- ส่วนหลักแบ่งเป็น 2 ฝั่ง -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- ฝั่งซ้าย: ไทม์ไลน์งานช่าง (Job Board) -->
                <div class="lg:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-calendar-day text-blue-500 mr-2"></i> งานที่กำลังดำเนินการ (Current Job)</h3>
                        <span class="bg-gray-800 text-white text-xs px-3 py-1 rounded-full"><?php echo date('d / m / Y'); ?></span>
                    </div>

                    <div class="space-y-4">
                        <?php if(in_array($current_status, ['กำลังเดินทาง', 'ถึงหน้างาน', 'เริ่มปฏิบัติงาน'])): ?>
                            <!-- การ์ดงานกำลังดำเนินการ -->
                            <div class="bg-white border rounded-lg p-5 shadow-sm relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-<?php echo $ui['color']; ?>-500"></div>
                                
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="<?php echo $ui['bg']; ?> <?php echo $ui['text']; ?> text-[10px] px-2 py-0.5 rounded-full uppercase tracking-wider font-bold">
                                                <i class="fas <?php echo $ui['icon']; ?> mr-1"></i> <?php echo $current_status; ?>
                                            </span>
                                            <span class="text-sm font-bold text-gray-600">#ORD-2026-001</span>
                                        </div>
                                        <h4 class="font-bold text-gray-800 text-lg">งานติดตั้งประตูกระจกบานเลื่อน กรอบอลูมิเนียมดำ</h4>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs text-gray-500 block mb-1">เวลานัดหมาย</span>
                                        <span class="font-bold text-gray-800 bg-gray-100 px-2 py-1 rounded">10:00 - 14:00 น.</span>
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-4 rounded border text-sm grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 font-bold mb-1 uppercase tracking-wider">ข้อมูลลูกค้า</p>
                                        <p class="font-medium text-gray-800"><i class="fas fa-user text-gray-400 w-4"></i> คุณสมชาย ลูกค้า</p>
                                        <p class="text-gray-600 mt-1"><i class="fas fa-phone-alt text-gray-400 w-4"></i> 089-123-4567</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-bold mb-1 uppercase tracking-wider">สถานที่ติดตั้ง</p>
                                        <p class="text-gray-700"><i class="fas fa-map-marker-alt text-red-400 w-4"></i> บ้านเลขที่ 123/45 หมู่บ้าน ABC เขตดินแดง กรุงเทพมหานคร 10400</p>
                                    </div>
                                </div>

                                <div class="mt-4 flex gap-3">
                                    <button class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded text-sm font-bold transition flex items-center shadow-sm">
                                        <i class="fas fa-phone-alt text-green-500 mr-2"></i> โทรหาลูกค้า
                                    </button>
                                    <button class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded text-sm font-bold transition flex items-center shadow-sm">
                                        <i class="fas fa-map-marked-alt text-blue-500 mr-2"></i> เปิดแผนที่นำทาง
                                    </button>
                                </div>
                            </div>
                        
                        <?php elseif($current_status == 'งานเสร็จสิ้น'): ?>
                            <!-- การ์ดงานเสร็จสิ้น -->
                            <div class="bg-teal-50 border border-teal-200 rounded-lg p-8 text-center flex flex-col items-center justify-center">
                                <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center text-teal-500 mb-4 shadow-sm">
                                    <i class="fas fa-check-double text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-bold text-teal-800 mb-1">ปฏิบัติงานเสร็จสิ้นเรียบร้อย</h4>
                                <p class="text-teal-600 text-sm">#ORD-2026-001 (ติดตั้งประตูกระจกบานเลื่อน)</p>
                                <p class="text-gray-500 text-sm mt-3">กรุณาเปลี่ยนสถานะเป็น "ว่าง" เพื่อรอรับการมอบหมายงานต่อไป</p>
                            </div>

                        <?php else: ?>
                            <!-- กรณีไม่มีงาน / สถานะว่าง -->
                            <div class="bg-white rounded-lg border border-dashed border-gray-300 p-10 text-center flex flex-col items-center justify-center min-h-[250px]">
                                <div class="h-16 w-16 bg-green-50 rounded-full flex items-center justify-center text-green-500 mb-4">
                                    <i class="fas fa-mug-hot text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-1">ขณะนี้คุณยังไม่มีคิวงาน</h4>
                                <p class="text-gray-500 text-sm">สถานะของคุณคือ "ว่าง" กรุณารอศูนย์ควบคุมมอบหมายงาน</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ฝั่งขวา: ฟอร์มอัปเดตสถานะ (Status Update) -->
                <div class="lg:col-span-1">
                    <div class="bg-white border rounded-lg shadow-sm sticky top-24">
                        <div class="bg-gray-800 text-white p-4 rounded-t-lg flex items-center">
                            <i class="fas fa-tasks mr-2"></i>
                            <h3 class="font-bold">รายงานความคืบหน้า (Status)</h3>
                        </div>
                        
                        <div class="p-5">
                            <form action="" method="POST">
                                <input type="hidden" name="update_status" value="1">
                                
                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-3">อัปเดตขั้นตอนการทำงานปัจจุบัน</label>
                                    
                                    <div class="space-y-2">
                                        <!-- 1. ว่าง -->
                                        <label class="flex items-center p-3 border rounded-md cursor-pointer transition hover:bg-gray-50 <?php echo ($current_status == 'ว่าง') ? 'border-green-500 bg-green-50 ring-1 ring-green-500' : 'border-gray-200'; ?>">
                                            <input type="radio" name="new_status" value="ว่าง" class="h-4 w-4 text-green-600 focus:ring-green-500" <?php echo ($current_status == 'ว่าง') ? 'checked' : ''; ?>>
                                            <div class="ml-3">
                                                <span class="block text-sm font-bold <?php echo ($current_status == 'ว่าง') ? 'text-green-700' : 'text-gray-700'; ?>">ว่าง (พร้อมรับงาน)</span>
                                            </div>
                                        </label>

                                        <!-- 2. กำลังเดินทาง -->
                                        <label class="flex items-center p-3 border rounded-md cursor-pointer transition hover:bg-gray-50 <?php echo ($current_status == 'กำลังเดินทาง') ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-200'; ?>">
                                            <input type="radio" name="new_status" value="กำลังเดินทาง" class="h-4 w-4 text-blue-600 focus:ring-blue-500" <?php echo ($current_status == 'กำลังเดินทาง') ? 'checked' : ''; ?>>
                                            <div class="ml-3">
                                                <span class="block text-sm font-bold <?php echo ($current_status == 'กำลังเดินทาง') ? 'text-blue-700' : 'text-gray-700'; ?>">กำลังเดินทาง</span>
                                            </div>
                                        </label>

                                        <!-- 3. ถึงหน้างาน -->
                                        <label class="flex items-center p-3 border rounded-md cursor-pointer transition hover:bg-gray-50 <?php echo ($current_status == 'ถึงหน้างาน') ? 'border-purple-500 bg-purple-50 ring-1 ring-purple-500' : 'border-gray-200'; ?>">
                                            <input type="radio" name="new_status" value="ถึงหน้างาน" class="h-4 w-4 text-purple-600 focus:ring-purple-500" <?php echo ($current_status == 'ถึงหน้างาน') ? 'checked' : ''; ?>>
                                            <div class="ml-3">
                                                <span class="block text-sm font-bold <?php echo ($current_status == 'ถึงหน้างาน') ? 'text-purple-700' : 'text-gray-700'; ?>">ถึงหน้างานแล้ว</span>
                                            </div>
                                        </label>

                                        <!-- 4. เริ่มปฏิบัติงาน -->
                                        <label class="flex items-center p-3 border rounded-md cursor-pointer transition hover:bg-gray-50 <?php echo ($current_status == 'เริ่มปฏิบัติงาน') ? 'border-orange-500 bg-orange-50 ring-1 ring-orange-500' : 'border-gray-200'; ?>">
                                            <input type="radio" name="new_status" value="เริ่มปฏิบัติงาน" class="h-4 w-4 text-orange-600 focus:ring-orange-500" <?php echo ($current_status == 'เริ่มปฏิบัติงาน') ? 'checked' : ''; ?>>
                                            <div class="ml-3">
                                                <span class="block text-sm font-bold <?php echo ($current_status == 'เริ่มปฏิบัติงาน') ? 'text-orange-700' : 'text-gray-700'; ?>">เริ่มปฏิบัติงาน</span>
                                            </div>
                                        </label>

                                        <!-- 5. งานเสร็จสิ้น -->
                                        <label class="flex items-center p-3 border rounded-md cursor-pointer transition hover:bg-gray-50 <?php echo ($current_status == 'งานเสร็จสิ้น') ? 'border-teal-500 bg-teal-50 ring-1 ring-teal-500' : 'border-gray-200'; ?>">
                                            <input type="radio" name="new_status" value="งานเสร็จสิ้น" class="h-4 w-4 text-teal-600 focus:ring-teal-500" <?php echo ($current_status == 'งานเสร็จสิ้น') ? 'checked' : ''; ?>>
                                            <div class="ml-3">
                                                <span class="block text-sm font-bold <?php echo ($current_status == 'งานเสร็จสิ้น') ? 'text-teal-700' : 'text-gray-700'; ?>">งานเสร็จสิ้น</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md shadow-md transition flex items-center justify-center">
                                    <i class="fas fa-sync-alt mr-2"></i> บันทึกสถานะ
                                </button>
                            </form>
                            
                            <?php if($current_status == 'งานเสร็จสิ้น'): ?>
                            <hr class="my-5 border-gray-100">
                            <!-- โซนสำหรับปิดงาน (ฟังก์ชันจำลอง) -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">อัปโหลดรูปปิดงาน (ส่งรายงาน)</label>
                                <button type="button" class="w-full bg-white border-2 border-dashed border-blue-300 hover:border-blue-500 hover:bg-blue-50 text-blue-600 font-bold py-4 px-4 rounded-md flex flex-col items-center justify-center transition cursor-pointer">
                                    <i class="fas fa-camera text-2xl mb-2"></i>
                                    <span>ถ่ายรูป / อัปโหลดผลงาน</span>
                                </button>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>