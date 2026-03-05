<?php
include '../db.php';
$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>แก้ไขสินค้า</title>
</head>
<?php include './components/navbar.php' ?>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">แก้ไขสินค้า</h2>

        <form action="update_action.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div>
                <label class="block text-sm font-medium text-slate-700">ชื่อสินค้า:</label>
                <input type="text" name="name" value="<?php echo $row['name']; ?>" required class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">ราคา:</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>" required class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">คงเหลือ:</label>
                    <input type="number" name="remain" value="<?php echo $row['remain']; ?>" required class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition shadow-md">อัปเดตข้อมูล</button>
            <a href="showdata.php" class="block text-center text-slate-500 text-sm mt-4 hover:underline">ยกเลิก</a>
        </form>
    </div>
</body>

</html>