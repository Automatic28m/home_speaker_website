<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "./db.php";

if (isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    header("Location: admin_show_categories.php");
}

if (isset($_POST['update_category'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    header("Location: admin_show_categories.php");
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM categories WHERE id = $id");
    header("Location: admin_show_categories.php");
}

$edit_row = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $res = $conn->query("SELECT * FROM categories WHERE id = $id");
    $edit_row = $res->fetch_assoc();
}

include './components/navbar.php';

if ($_SESSION['role'] == 'customer') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>จัดการหมวดหมู่</title>
</head>

<body class="bg-slate-50">
    <div class="max-w-4xl mx-auto my-5 space-y-8">

        <div class="bg-white  rounded p-6">
            <h2 class="text-xl font-bold text-slate-800 mb-4">
                <?php echo $edit_row ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่ใหม่'; ?>
            </h2>
            <form method="POST" class="flex gap-4">
                <?php if ($edit_row): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_row['id']; ?>">
                <?php endif; ?>

                <input type="text" name="name" placeholder="ชื่อหมวดหมู่" required
                    value="<?php echo $edit_row ? htmlspecialchars($edit_row['name']) : ''; ?>"
                    class="flex-1 px-4 py-2 border rounded outline-none focus:ring-2 focus:ring-blue-500">

                <?php if ($edit_row): ?>
                    <button type="submit" name="update_category"
                        class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2 rounded font-semibold transition">
                        บันทึกการแก้ไข
                    </button>
                    <a href="admin_show_categories.php" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-2 rounded font-semibold transition flex items-center">
                        ยกเลิก
                    </a>
                <?php else: ?>
                    <button type="submit" name="add_category"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold transition">
                        บันทึก
                    </button>
                <?php endif; ?>
            </form>
        </div>

        <div class="bg-white rounded p-6">
            <h2 class="text-xl font-bold text-slate-800 mb-4">หมวดหมู่ที่มีอยู่</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 uppercase text-xs">
                            <th class="p-4 border-b">ID</th>
                            <th class="p-4 border-b">ชื่อหมวดหมู่</th>
                            <th class="p-4 border-b text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        <?php
                        $result = $conn->query("SELECT * FROM categories ORDER BY id DESC");
                        if ($result->num_rows > 0):
                            while ($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 border-b"><?php echo $row['id']; ?></td>
                                    <td class="p-4 border-b font-medium"><?php echo $row['name']; ?></td>
                                    <td class="p-4 border-b text-center space-x-3">
                                        <a href="?edit_id=<?php echo $row['id']; ?>"
                                            class="text-amber-600 hover:underline text-sm font-semibold">แก้ไข</a>
                                        <a href="?delete_id=<?php echo $row['id']; ?>"
                                            onclick="return confirm('คุณแน่ใจหรือไม่?')"
                                            class="text-red-600 hover:underline text-sm font-semibold">ลบ</a>
                                    </td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="3" class="p-8 text-center text-slate-400">ยังไม่มีหมวดหมู่ กรุณาเพิ่มจากฟอร์มด้านบน</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center">
            <a href="admin_show_products.php" class="text-blue-600 hover:underline text-sm">← กลับไปที่หน้าจัดการสินค้า</a>
        </div>
    </div>
</body>

</html>