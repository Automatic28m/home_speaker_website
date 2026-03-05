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

// 2. Handle Deleting Category
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM categories WHERE id = $id");
    header("Location: admin_show_categories.php");
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>จัดการหมวดหมู่</title>
</head>
<?php include './components/navbar.php' ?>

<body class="bg-slate-50">
    <div class="max-w-4xl mx-auto my-5 space-y-8">

        <div class="bg-white shadow-md rounded p-6 border-1">
            <h2 class="text-xl font-bold text-slate-800 mb-4">เพิ่มหมวดหมู่ใหม่</h2>
            <form method="POST" class="flex gap-4">
                <input type="text" name="name" placeholder="ชื่อหมวดหมู่" required
                    class="flex-1 px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" name="add_category"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                    บันทึก
                </button>
            </form>
        </div>

        <div class="bg-white shadow-md rounded p-6 border-1">
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
                                    <td class="p-4 border-b text-center">
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
            <a href="../products/insert.php" class="text-blue-600 hover:underline text-sm">← กลับไปที่หน้าเพิ่มสินค้า</a>
        </div>
    </div>
</body>

</html>