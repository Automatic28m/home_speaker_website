<?php include './components/navbar.php' ?>
<?php

// session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>ข้อมูลสมาชิก</title>
</head>



<body class="bg-slate-100 min-h-screen">

    <div class="my-8 max-w-6xl mx-auto bg-white rounded-2xl p-6 md:p-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">ข้อมูลสมาชิก</h2>
                <p class="text-slate-500 mt-1 text-sm">
                    ยินดีต้อนรับคุณ <span class="font-semibold text-blue-600"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    (สถานะ: <span class="uppercase tracking-wider text-xs font-semibold px-2 py-1 bg-slate-100 rounded-md"><?php echo htmlspecialchars($role); ?></span>)
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <?php if ($role == 'admin'): ?>
                    <a href="regis.php">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors shadow-sm flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            เพิ่มบัญชีสมาชิก
                        </button>
                    </a>
                <?php endif; ?>

                <a href="logout.php">
                    <button class="bg-white border border-slate-300 hover:bg-red-50 hover:text-red-600 hover:border-red-200 text-slate-700 font-medium py-2 px-4 rounded-lg transition-colors shadow-sm text-sm">
                        ออกจากระบบ
                    </button>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-slate-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold tracking-wider">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">ชื่อ-นามสกุล</th>
                        <th class="px-6 py-4">เพศ</th>
                        <th class="px-6 py-4">อายุ</th>
                        <th class="px-6 py-4">จังหวัด</th>
                        <th class="px-6 py-4">อีเมล</th>
                        <th class="px-6 py-4">บทบาท</th>
                        <th class="px-6 py-4 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    <?php
                    $sql = "SELECT * FROM users ORDER BY id DESC";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-slate-500"><?php echo htmlspecialchars($row['id']); ?></td>
                                <td class="px-6 py-4 font-medium text-slate-900"><?php echo htmlspecialchars($row['username']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['age']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['province']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium uppercase">
                                        <?php echo htmlspecialchars($row['role']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-medium">
                                    <a href="admin_edit_user.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800 transition-colors mr-3">แก้ไข</a>
                                    <?php if ($role == 'admin' && $row['id'] != $_SESSION['user_id']) { ?>
                                        <a href="deleteUser.php?id=<?php echo $row['id']; ?>" onclick="return confirm('แน่ใจหรือไม่ที่จะลบบัญชีนี้? ข้อมูลจะไม่สามารถกู้คืนได้')" class="text-red-600 hover:text-red-800 transition-colors">ลบ</a>
                                    <?php } ?>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='9' class='px-6 py-8 text-center text-slate-500'>ไม่พบข้อมูลสมาชิก</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>