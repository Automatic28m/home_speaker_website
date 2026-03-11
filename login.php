<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>เข้าสู่ระบบ</title>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 border border-slate-100">

        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-800">เข้าสู่ระบบ</h2>
            <p class="text-slate-500 mt-2 text-sm">ระบบจัดการข้อมูลสมาชิก Back-end</p>
        </div>

        <form action="login_action.php" method="POST" class="space-y-5">

            <div>
                <label for="username" class="block text-sm font-medium text-slate-700 mb-1">ชื่อบัญชี (Username)</label>
                <input type="text" id="username" name="username" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                    placeholder="กรอกชื่อบัญชีผู้ใช้">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">รหัสผ่าน (Password)</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                    placeholder="••••••••">
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors shadow-sm mb-4">
                    เข้าสู่ระบบ
                </button>

                <div class="relative flex py-2 items-center mb-4">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink-0 mx-4 text-slate-400 text-sm">หรือ</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <a href="regis.php" class="block w-full">
                    <button type="button"
                        class="w-full bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold py-2.5 px-4 rounded-lg transition-colors shadow-sm">
                        สมัครสมาชิกใหม่
                    </button>
                </a>
            </div>
        </form>
    </div>

</body>

</html>