<?php
include './components/navbar.php';
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

if ($role == 'customer') {
    header("Location: index.php");
    exit();
}

$search_q = $_GET['q'] ?? '';
$role_filter = $_GET['role_filter'] ?? 'all';

$sql = "SELECT * FROM users WHERE 1=1";
$params = [];
$types = "";

if ($search_q !== '') {
    $sql .= " AND (username LIKE ? OR first_name LIKE ? OR last_name LIKE ?)";
    $search_param = "%$search_q%";
    array_push($params, $search_param, $search_param, $search_param);
    $types .= "sss";
}

if ($role_filter !== 'all') {
    $sql .= " AND role = ?";
    array_push($params, $role_filter);
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$users_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>จัดการสมาชิก - LUXOUND</title>
</head>

<body class="bg-slate-100 min-h-screen font-['Prompt']">
    <div class="my-8 max-w-6xl mx-auto bg-white rounded-2xl p-6 md:p-8 shadow-sm">

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tighter">สมาชิก</h2>
                <p class="text-slate-500 text-sm italic">ยินดีต้อนรับคุณ <span class="text-blue-600 font-bold"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
            </div>

            <?php if ($role == 'admin'): ?>
                <a href="admin_regis.php" class="bg-slate-900 hover:bg-black text-white text-xs font-bold py-2.5 px-5 rounded-lg transition-all uppercase tracking-widest shadow-lg shadow-slate-200">
                    + เพิ่มสมาชิก
                </a>
            <?php endif; ?>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-8 bg-slate-50 p-4 rounded-xl border border-slate-100">
            <div class="md:col-span-6">
                <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ค้นหาสมาชิก</label>
                <input type="text" name="q" value="<?php echo htmlspecialchars($search_q); ?>"
                    placeholder="Username, ชื่อ หรือ นามสกุล..."
                    class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
            <div class="md:col-span-4">
                <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">บทบาท (Role)</label>
                <select name="role_filter" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">ทุกบทบาท</option>
                    <option value="admin" <?php echo $role_filter == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="manager" <?php echo $role_filter == 'manager' ? 'selected' : ''; ?>>Manager</option>
                    <option value="customer" <?php echo $role_filter == 'customer' ? 'selected' : ''; ?>>Customer</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg text-sm transition-colors uppercase">
                    Search
                </button>
            </div>
        </form>

        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">User Details</th>
                        <th class="px-6 py-4">Info</th>
                        <th class="px-6 py-4">Location</th>
                        <th class="px-6 py-4">Role</th>
                        <?php if ($role == 'admin') { ?>
                            <th class="px-6 py-4 text-right">Actions</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    <?php if ($users_result->num_rows > 0): ?>
                        <?php while ($row = $users_result->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-300 font-mono text-xs">#<?php echo $row['id']; ?></td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800"><?php echo htmlspecialchars($row['username']); ?></div>
                                    <div class="text-[10px] text-slate-400"><?php echo htmlspecialchars($row['email']); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-700"><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></div>
                                    <div class="text-[10px] text-slate-400 italic"><?php echo $row['gender']; ?>, อายุ <?php echo $row['age']; ?> ปี</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-slate-500">จ. <?php echo htmlspecialchars($row['province']); ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase <?php
                                                                                                        echo $row['role'] == 'admin' ? 'bg-red-50 text-red-600' : ($row['role'] == 'manager' ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-500');
                                                                                                        ?>">
                                        <?php echo htmlspecialchars($row['role']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-3 items-center">
                                        <?php if ($role == 'admin' && $row['id'] != $_SESSION['user_id']): ?>
                                            <a href="admin_edit_user.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:underline font-bold text-xs uppercase">แก้ไข</a>
                                            <a href="deleteUser.php?id=<?php echo $row['id']; ?>"
                                                onclick="return confirm('ยืนยันการลบบัญชีนี้? ข้อมูลจะไม่สามารถกู้คืนได้')"
                                                class="text-red-400 hover:text-red-600 text-xs font-bold uppercase">ลบ</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">ไม่พบข้อมูลสมาชิกที่ตรงกับเงื่อนไขการค้นหา</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>