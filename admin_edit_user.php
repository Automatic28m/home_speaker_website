<?php
include './components/navbar.php';
// session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$role = $_SESSION['role'];

if ($role == 'customer') {
    header("Location: index.php");
    exit();
} 

if ($role == 'manager') {
    header("Location: admin_show_users.php");
    exit();
}

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM users WHERE id = $id");
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>แก้ไขข้อมูล</title>
</head>

<body class="bg-slate-100 min-h-screen items-center justify-center">

    <div class="max-w-5xl mx-auto w-full bg-white rounded-2xl shadow-xl p-8 border border-slate-100 m-8">

        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-800">แก้ไขข้อมูล</h2>
            <p class="text-slate-500 mt-2 text-sm">ปรับปรุงข้อมูลส่วนตัวของคุณ</p>
        </div>

        <form action="admin_update_user.php" method="POST" class="space-y-5">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">ชื่อบัญชี (Username): <span class="text-red-500 text-xs">* ไม่สามารถแก้ไขได้</span></label>
                <input type="text" value="<?php echo htmlspecialchars($row['username']); ?>" readonly disabled
                    class="w-full px-4 py-2.5 border border-slate-300 bg-slate-100 text-slate-500 rounded-lg cursor-not-allowed outline-none">
            </div>

            <div>
                <label for="first_name" class="block text-sm font-medium text-slate-700 mb-1">ชื่อ:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($row['first_name']); ?>" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-slate-700 mb-1">นามสกุล:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($row['last_name']); ?>" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>

            <div>
                <label for="age" class="block text-sm font-medium text-slate-700 mb-1">อายุ:</label>
                <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($row['age']); ?>" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>

            <div>
                <label for="province" class="block text-sm font-medium text-slate-700 mb-1">จังหวัด:</label>
                <select id="province" name="province" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white cursor-pointer">
                    <option value="">-- เลือกจังหวัด --</option>
                    <?php
                    $provinces = [
                        "กรุงเทพมหานคร",
                        "กระบี่",
                        "กาญจนบุรี",
                        "กาฬสินธุ์",
                        "กำแพงเพชร",
                        "ขอนแก่น",
                        "จันทบุรี",
                        "ฉะเชิงเทรา",
                        "ชลบุรี",
                        "ชัยนาท",
                        "ชัยภูมิ",
                        "ชุมพร",
                        "เชียงราย",
                        "เชียงใหม่",
                        "ตรัง",
                        "ตราด",
                        "ตาก",
                        "นครนายก",
                        "นครปฐม",
                        "นครพนม",
                        "นครราชสีมา",
                        "นครศรีธรรมราช",
                        "นครสวรรค์",
                        "นนทบุรี",
                        "นราธิวาส",
                        "น่าน",
                        "บึงกาฬ",
                        "บุรีรัมย์",
                        "ปทุมธานี",
                        "ประจวบคีรีขันธ์",
                        "ปราจีนบุรี",
                        "ปัตตานี",
                        "พระนครศรีอยุธยา",
                        "พะเยา",
                        "พังงา",
                        "พัทลุง",
                        "พิจิตร",
                        "พิษณุโลก",
                        "เพชรบุรี",
                        "เพชรบูรณ์",
                        "แพร่",
                        "ภูเก็ต",
                        "มหาสารคาม",
                        "มุกดาหาร",
                        "แม่ฮ่องสอน",
                        "ยโสธร",
                        "ยะลา",
                        "ร้อยเอ็ด",
                        "ระนอง",
                        "ระยอง",
                        "ราชบุรี",
                        "ลพบุรี",
                        "ลำปาง",
                        "ลำพูน",
                        "เลย",
                        "ศรีสะเกษ",
                        "สกลนคร",
                        "สงขลา",
                        "สตูล",
                        "สมุทรปราการ",
                        "สมุทรสงคราม",
                        "สมุทรสาคร",
                        "สระแก้ว",
                        "สระบุรี",
                        "สิงห์บุรี",
                        "สุโขทัย",
                        "สุพรรณบุรี",
                        "สุราษฎร์ธานี",
                        "สุรินทร์",
                        "หนองคาย",
                        "หนองบัวลำภู",
                        "อ่างทอง",
                        "อำนาจเจริญ",
                        "อุดรธานี",
                        "อุตรดิตถ์",
                        "อุทัยธานี",
                        "อุบลราชธานี"
                    ];
                    foreach ($provinces as $p) {
                        $selected = ($p === $row['province']) ? 'selected' : '';

                        echo "<option value=\"$p\" $selected>$p</option>\n";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-slate-700 mb-1">บทบาท:</label>
                <select name="role" id="" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white cursor-pointer">>
                    <option value="">-- เลือกบทบาท --</option>
                    <option value="admin" <?php echo $row['role'] == 'admin' ? "selected" : "" ?>>admin</option>
                    <option value="manager" <?php echo $row['role'] == 'manager' ? "selected" : "" ?>>manager</option>
                    <option value="customer" <?php echo $row['role'] == 'customer' ? "selected" : "" ?>>customer</option>
                </select>
            </div>

            <div class="pt-4 mt-2 border-t border-slate-100">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors shadow-sm mb-3">
                    บันทึกการเปลี่ยนแปลง
                </button>

                <a href="admin_show_users.php" class="block w-full text-center bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold py-2.5 px-4 rounded-lg transition-colors shadow-sm">
                    ยกเลิก
                </a>
            </div>
        </form>
    </div>

</body>

</html>