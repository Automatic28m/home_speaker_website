<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include './db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name        = $_POST['name'];
    $detail      = $_POST['detail'];
    $price       = $_POST['price'];
    $remain      = $_POST['remain'];
    $category_id = $_POST['category_id'];

    $stmt = $conn->prepare("INSERT INTO products (name, detail, price, remain, category_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", $name, $detail, $price, $remain, $category_id);

    if ($stmt->execute()) {
        $product_id = $conn->insert_id;
        $image_names = [];

        $upload_dir = "./prod_images/";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (!empty($_FILES['img_files']['name'][0])) {
            foreach ($_FILES['img_files']['tmp_name'] as $key => $tmp_name) {
                $order = $key + 1;
                $ext = pathinfo($_FILES['img_files']['name'][$key], PATHINFO_EXTENSION);
                $new_filename = $product_id . "-" . $order . "." . $ext;

                if (move_uploaded_file($tmp_name, $upload_dir . $new_filename)) {
                    $image_names[] = $new_filename;
                } else {
                    echo "Failed to upload image: " . $_FILES['img_files']['name'][$key];
                }
            }
        }

        if (!empty($image_names)) {
            $img_string = implode(",", $image_names);
            $update_stmt = $conn->prepare("UPDATE products SET img_files = ? WHERE id = ?");
            $update_stmt->bind_param("si", $img_string, $product_id);
            $update_stmt->execute();
        }

        echo "<script>alert('Product Added Successfully!'); window.location='admin_show_products.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>เพิ่มสินค้าใหม่</title>
</head>
<?php include "./components/navbar.php" ?>

<body class="bg-slate-100 min-h-screen">
    <div class="max-w-xl mx-auto bg-white rounded-xl shadow-lg p-8 my-5">
        <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">เพิ่มสินค้าใหม่</h2>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">ชื่อสินค้า:</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">รายละเอียด:</label>
                <textarea name="detail" required class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">ราคา:</label>
                    <input type="number" step="0.01" name="price" required class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">จำนวนคงเหลือ:</label>
                    <input type="number" name="remain" required class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">หมวดหมู่:</label>
                <select name="category_id" required class="w-full px-4 py-2 border rounded-lg bg-white">
                    <option value="">-- เลือกหมวดหมู่ --</option>
                    <?php
                    $cat_res = $conn->query("SELECT * FROM categories");
                    while ($cat = $cat_res->fetch_assoc()) {
                        echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">รูปภาพสินค้า (เลือกได้หลายรูป):</label>
                <input type="file" name="img_files[]" multiple required
                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
                บันทึกสินค้า
            </button>
            <a href="admin_show_products.php" class="block text-center text-slate-500 text-sm mt-4 hover:underline">กลับหน้าจัดการสินค้า</a>
        </form>
    </div>
</body>

</html>