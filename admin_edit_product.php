<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include './db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: admin_show_products.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Product not found.");
}

// 3. Handle Update Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name        = $_POST['name'];
    $detail      = $_POST['detail'];
    $price       = $_POST['price'];
    $remain      = $_POST['remain'];
    $category_id = $_POST['category_id'];

    // Update main product info
    $update_sql = "UPDATE products SET name=?, detail=?, price=?, remain=?, category_id=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssdisi", $name, $detail, $price, $remain, $category_id, $id);

    if ($stmt->execute()) {
        // Handle Image Upload only if new files are selected
        if (!empty($_FILES['img_files']['name'][0])) {
            $image_names = [];
            $upload_dir = "./prod_images/";

            // Process and rename new images: [id]-[order].[ext]
            foreach ($_FILES['img_files']['tmp_name'] as $key => $tmp_name) {
                $order = $key + 1;
                $ext = pathinfo($_FILES['img_files']['name'][$key], PATHINFO_EXTENSION);
                $new_filename = $id . "-" . $order . "." . $ext;

                if (move_uploaded_file($tmp_name, $upload_dir . $new_filename)) {
                    $image_names[] = $new_filename;
                }
            }

            // Update database with new image string
            if (!empty($image_names)) {
                $img_string = implode(",", $image_names);
                $img_stmt = $conn->prepare("UPDATE products SET img_files = ? WHERE id = ?");
                $img_stmt->bind_param("si", $img_string, $id);
                $img_stmt->execute();
            }
        }

        echo "<script>alert('แก้ไขสินค้าสำเร็จ!'); window.location='admin_show_products.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>แก้ไขสินค้า - <?php echo $row['name']; ?></title>
</head>
<?php include "./components/navbar.php" ?>

<body class="bg-slate-100 min-h-screen">
    <div class="max-w-xl mx-auto bg-white rounded-xl shadow-lg p-8 my-10">
        <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">แก้ไขรายละเอียดสินค้า</h2>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">ชื่อสินค้า:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required
                    class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">รายละเอียด:</label>
                <textarea name="detail" required class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500 h-32"><?php echo htmlspecialchars($row['detail']); ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">ราคา:</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>" required
                        class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">จำนวนคงเหลือ:</label>
                    <input type="number" name="remain" value="<?php echo $row['remain']; ?>" required
                        class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">หมวดหมู่:</label>
                <select name="category_id" required class="w-full px-4 py-2 border rounded-lg bg-white">
                    <?php
                    $cat_res = $conn->query("SELECT * FROM categories");
                    while ($cat = $cat_res->fetch_assoc()) {
                        $selected = ($cat['id'] == $row['category_id']) ? "selected" : "";
                        echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">รูปภาพปัจจุบัน:</label>
                <div class="flex gap-2 my-2 overflow-x-auto p-2 border rounded-lg bg-slate-50">
                    <?php
                    $existing_imgs = explode(',', $row['img_files'] ?? '');
                    foreach ($existing_imgs as $img): if (!empty($img)): ?>
                            <img src="./prod_images/<?php echo $img; ?>" class="w-16 h-16 object-cover rounded border">
                    <?php endif;
                    endforeach; ?>
                </div>
                <label class="block text-xs text-slate-500 mb-1 mt-2">อัปโหลดรูปใหม่เพื่อแทนที่ (เลือกได้หลายรูป):</label>
                <input type="file" name="img_files[]" multiple
                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors mt-4">
                ยืนยันการแก้ไข
            </button>
            <a href="admin_show_products.php" class="block text-center text-slate-500 text-sm mt-4 hover:underline">ยกเลิก</a>
        </form>
    </div>
</body>

</html>