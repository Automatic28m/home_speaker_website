<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>ลงทะเบียนสมาชิก</title>
    <script>
        function checkPasswordMatch() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            if (password != confirmPassword) {
                alert("รหัสผ่านไม่ตรงกัน กรุณากรอกใหม่!");
                return false;
            }
            return true;
        }
    </script>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-xl p-8 border border-slate-100 my-8">

        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-800">สมัครสมาชิกใหม่</h2>
            <p class="text-slate-500 mt-2 text-sm">กรอกข้อมูลด้านล่างเพื่อสร้างบัญชีผู้ใช้งาน</p>
        </div>

        <form action="regis_action.php" method="POST" onsubmit="return checkPasswordMatch();" class="space-y-5">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">
                    <label for="username" class="block text-sm font-medium text-slate-700 mb-1">ชื่อบัญชี
                        (Username):</label>
                    <input type="text" id="username" name="username" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">รหัสผ่าน
                        (Password):</label>
                    <input type="password" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                        title="ต้องมีอย่างน้อย 8 ตัวอักษร ประกอบด้วยตัวเลข ตัวพิมพ์เล็ก และตัวพิมพ์ใหญ่อย่างน้อย 1 ตัว"
                        required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    <p class="text-xs text-slate-500 mt-1">ขั้นต่ำ 8 ตัวอักษร (A-Z, a-z, 0-9)</p>
                </div>

                <div>
                    <label for="confirm_password"
                        class="block text-sm font-medium text-slate-700 mb-1">ยืนยันรหัสผ่าน:</label>
                    <input type="password" id="confirm_password" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label for="first_name" class="block text-sm font-medium text-slate-700 mb-1">ชื่อ:</label>
                    <input type="text" id="first_name" name="first_name" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-slate-700 mb-1">นามสกุล:</label>
                    <input type="text" id="last_name" name="last_name" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">เพศ:</label>
                    <div class="flex items-center gap-6 mt-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="gender" value="ชาย" required
                                class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-slate-700">ชาย</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="gender" value="หญิง" required
                                class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-slate-700">หญิง</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="age" class="block text-sm font-medium text-slate-700 mb-1">อายุ:</label>
                    <input type="number" id="age" name="age" min="1" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <label for="province" class="block text-sm font-medium text-slate-700 mb-1">จังหวัด:</label>
                    <select id="province" name="province" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white cursor-pointer">
                        <option value="">-- เลือกจังหวัด --</option>
                        <option value="กรุงเทพมหานคร">กรุงเทพมหานคร</option>
                        <option value="กระบี่">กระบี่</option>
                        <option value="กาญจนบุรี">กาญจนบุรี</option>
                        <option value="กาฬสินธุ์">กาฬสินธุ์</option>
                        <option value="กำแพงเพชร">กำแพงเพชร</option>
                        <option value="ขอนแก่น">ขอนแก่น</option>
                        <option value="จันทบุรี">จันทบุรี</option>
                        <option value="ฉะเชิงเทรา">ฉะเชิงเทรา</option>
                        <option value="ชลบุรี">ชลบุรี</option>
                        <option value="ชัยนาท">ชัยนาท</option>
                        <option value="ชัยภูมิ">ชัยภูมิ</option>
                        <option value="ชุมพร">ชุมพร</option>
                        <option value="เชียงราย">เชียงราย</option>
                        <option value="เชียงใหม่">เชียงใหม่</option>
                        <option value="ตรัง">ตรัง</option>
                        <option value="ตราด">ตราด</option>
                        <option value="ตาก">ตาก</option>
                        <option value="นครนายก">นครนายก</option>
                        <option value="นครปฐม">นครปฐม</option>
                        <option value="นครพนม">นครพนม</option>
                        <option value="นครราชสีมา">นครราชสีมา</option>
                        <option value="นครศรีธรรมราช">นครศรีธรรมราช</option>
                        <option value="นครสวรรค์">นครสวรรค์</option>
                        <option value="นนทบุรี">นนทบุรี</option>
                        <option value="นราธิวาส">นราธิวาส</option>
                        <option value="น่าน">น่าน</option>
                        <option value="บึงกาฬ">บึงกาฬ</option>
                        <option value="บุรีรัมย์">บุรีรัมย์</option>
                        <option value="ปทุมธานี">ปทุมธานี</option>
                        <option value="ประจวบคีรีขันธ์">ประจวบคีรีขันธ์</option>
                        <option value="ปราจีนบุรี">ปราจีนบุรี</option>
                        <option value="ปัตตานี">ปัตตานี</option>
                        <option value="พระนครศรีอยุธยา">พระนครศรีอยุธยา</option>
                        <option value="พะเยา">พะเยา</option>
                        <option value="พังงา">พังงา</option>
                        <option value="พัทลุง">พัทลุง</option>
                        <option value="พิจิตร">พิจิตร</option>
                        <option value="พิษณุโลก">พิษณุโลก</option>
                        <option value="เพชรบุรี">เพชรบุรี</option>
                        <option value="เพชรบูรณ์">เพชรบูรณ์</option>
                        <option value="แพร่">แพร่</option>
                        <option value="ภูเก็ต">ภูเก็ต</option>
                        <option value="มหาสารคาม">มหาสารคาม</option>
                        <option value="มุกดาหาร">มุกดาหาร</option>
                        <option value="แม่ฮ่องสอน">แม่ฮ่องสอน</option>
                        <option value="ยโสธร">ยโสธร</option>
                        <option value="ยะลา">ยะลา</option>
                        <option value="ร้อยเอ็ด">ร้อยเอ็ด</option>
                        <option value="ระนอง">ระนอง</option>
                        <option value="ระยอง">ระยอง</option>
                        <option value="ราชบุรี">ราชบุรี</option>
                        <option value="ลพบุรี">ลพบุรี</option>
                        <option value="ลำปาง">ลำปาง</option>
                        <option value="ลำพูน">ลำพูน</option>
                        <option value="เลย">เลย</option>
                        <option value="ศรีสะเกษ">ศรีสะเกษ</option>
                        <option value="สกลนคร">สกลนคร</option>
                        <option value="สงขลา">สงขลา</option>
                        <option value="สตูล">สตูล</option>
                        <option value="สมุทรปราการ">สมุทรปราการ</option>
                        <option value="สมุทรสงคราม">สมุทรสงคราม</option>
                        <option value="สมุทรสาคร">สมุทรสาคร</option>
                        <option value="สระแก้ว">สระแก้ว</option>
                        <option value="สระบุรี">สระบุรี</option>
                        <option value="สิงห์บุรี">สิงห์บุรี</option>
                        <option value="สุโขทัย">สุโขทัย</option>
                        <option value="สุพรรณบุรี">สุพรรณบุรี</option>
                        <option value="สุราษฎร์ธานี">สุราษฎร์ธานี</option>
                        <option value="สุรินทร์">สุรินทร์</option>
                        <option value="หนองคาย">หนองคาย</option>
                        <option value="หนองบัวลำภู">หนองบัวลำภู</option>
                        <option value="อ่างทอง">อ่างทอง</option>
                        <option value="อำนาจเจริญ">อำนาจเจริญ</option>
                        <option value="อุดรธานี">อุดรธานี</option>
                        <option value="อุตรดิตถ์">อุตรดิตถ์</option>
                        <option value="อุทัยธานี">อุทัยธานี</option>
                        <option value="อุบลราชธานี">อุบลราชธานี</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">อีเมล:</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

            </div>

            <div class="pt-6 mt-4 border-t border-slate-100">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors shadow-sm mb-4">
                    ลงทะเบียน
                </button>

                <a href="index.html" class="block w-full">
                    <button type="button"
                        class="w-full bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold py-2.5 px-4 rounded-lg transition-colors shadow-sm">
                        กลับไปหน้าล็อกอิน
                    </button>
                </a>
            </div>
        </form>
    </div>

</body>

</html>