<?php
include './db.php';

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: admin_show_products.php");
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>จัดการสินค้า</title>
</head>
<?php include "./components/navbar.php" ?>

<body class="bg-slate-50">
    <div class="my-8 w-auto mx-4 bg-white rounded shadow-xl p-6 md:p-8 border border-slate-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">รายการสินค้าทั้งหมด</h2>
            <a href="admin_add_product.php">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors shadow-sm flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    เพิ่มสินค้า
                </button>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 uppercase text-xs">
                        <th class="p-4 border-b">รูปภาพ</th>
                        <th class="p-4 border-b">รายละเอียด</th>
                        <th class="p-4 border-b">ชื่อสินค้า</th>
                        <th class="p-4 border-b text-center">ราคา</th>
                        <th class="p-4 border-b text-center">คงเหลือ</th>
                        <th class="p-4 border-b text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700">
                    <?php
                    $sql = "SELECT * FROM products";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 border-b">
                                <?php
                                if (!empty($row['img_files'])) {
                                    $images = explode(',', $row['img_files']);
                                    $first_image = $images[0];
                                    echo '<img src="./prod_images/' . $first_image . '" class="w-24 h-24 rounded object-cover border">';
                                } else {
                                    echo '<div class="w-12 h-12 rounded bg-slate-200 flex items-center justify-center text-[10px] text-slate-400">No Img</div>';
                                }
                                ?>
                            </td>
                            <td class="p-4 border-b font-medium"><?php echo $row['name']; ?></td>
                            <td class="p-4 border-b font-medium"><?php echo $row['detail']; ?></td>
                            <td class="p-4 border-b text-center">฿<?php echo number_format($row['price'], 2); ?></td>
                            <td class="p-4 border-b text-center"><?php echo $row['remain']; ?></td>
                            <td class="p-4 border-b text-center space-x-2">
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:underline text-sm">แก้ไข</a>
                                <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('ยืนยันการลบ?')" class="text-red-600 hover:underline text-sm">ลบ</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>