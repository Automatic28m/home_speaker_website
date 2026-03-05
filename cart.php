<?php
include "./components/navbar.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include './db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// 1. ตรรกะการลบสินค้า (Delete)
if (isset($_POST['delete_id'])) {
    $pid = $_POST['delete_id'];
    $sql = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $pid";
    $conn->query($sql);
}

// 2. ตรรกะการอัปเดตจำนวน (Recalculate)
if (isset($_POST['qty'])) {
    $count = count($_POST['qty']);
    for ($i = 0; $i < $count; $i++) {
        $qty = (int)$_POST['qty'][$i];
        $pid = (int)$_POST['pid'][$i];
        $sql = "UPDATE cart SET quantity = $qty WHERE user_id = $user_id AND product_id = $pid";
        $conn->query($sql);
    }
}

// 3. อ่านข้อมูลจากตาราง cart + products
$sql = "SELECT c.quantity, p.* FROM cart c 
        LEFT JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>ตะกร้าสินค้า</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script>
        $(function() {
            $('.btn-delete').click(function() {
                if (confirm('ยืนยันการลบสินค้ารายการนี้ออกจากรถเข็น?')) {
                    $('#delete-id').val($(this).data('id'));
                    $('#form-delete').submit();
                }
            });
        });
    </script>
</head>

<body class="bg-slate-50 font-['Prompt']">
    <div class="max-w-4xl mx-auto my-12 px-4 font-['Prompt',_sans-serif]">
        <h2 class="text-2xl font-bold text-slate-800 mb-8 text-center">รายการสินค้าในรถเข็น</h2>

        <?php if ($result->num_rows == 0): ?>
            <div class="bg-white p-12 rounded-xl shadow-sm text-center border border-slate-100">
                <p class="text-slate-400 mb-6">ยังไม่มีสินค้าในรถเข็นของคุณ</p>
                <a href="index.php" class="text-blue-600 hover:underline">← กลับไปเลือกซื้อสินค้า</a>
            </div>
        <?php else: ?>
            <form method="POST" id="form-cart">
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                            <tr>
                                <th class="p-4">สินค้า</th>
                                <th class="p-4 text-center">ราคา</th>
                                <th class="p-4 text-center">จำนวน</th>
                                <th class="p-4 text-right">รวม</th>
                                <th class="p-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php
                            $grand_total = 0;
                            while ($p = $result->fetch_assoc()):
                                $subtotal = $p['price'] * $p['quantity'];
                                $grand_total += $subtotal;
                                $imgs = explode(',', $p['img_files']);
                            ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 flex items-center gap-4">
                                        <img src="./prod_images/<?php echo $imgs[0]; ?>" class="w-16 h-16 object-cover">
                                        <span class="font-medium text-slate-800 uppercase text-sm"><?php echo $p['name']; ?></span>
                                    </td>
                                    <td class="p-4 text-center text-sm">฿<?php echo number_format($p['price'], 2); ?></td>
                                    <td class="p-4 text-center">
                                        <input type="number" name="qty[]" value="<?php echo $p['quantity']; ?>" min="1" max="<?php echo $p['remain']; ?>"
                                            class="w-16 text-center border border-slate-200 rounded py-1 outline-none focus:ring-2 focus:ring-blue-500">
                                        <input type="hidden" name="pid[]" value="<?php echo $p['id']; ?>">
                                    </td>
                                    <td class="p-4 text-right font-bold text-slate-800">฿<?php echo number_format($subtotal, 2); ?></td>
                                    <td class="p-4 text-center">
                                        <button type="button" class="btn-delete text-red-400 hover:text-red-600" data-id="<?php echo $p['id']; ?>">
                                            ลบ
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <div class="bg-slate-50 p-6 space-y-2 border-t border-slate-100">
                        <div class="flex justify-between text-slate-500">
                            <span>ยอดรวมสินค้า:</span>
                            <span>฿<?php echo number_format($grand_total, 2); ?></span>
                        </div>
                        <div class="flex justify-between text-slate-500 border-b pb-2">
                            <span>ค่าจัดส่งรวม:</span>
                            ฟรี
                        </div>
                        <div class="flex justify-between text-xl font-bold text-slate-900 pt-2">
                            <span>รวมทั้งสิ้น:</span>
                            <span class="text-blue-600">฿<?php echo number_format($grand_total, 2); ?></span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <a href="index.php" class="text-slate-500 hover:underline text-sm">← เลือกสินค้าเพิ่มเติม</a>
                    <div class="flex gap-4">
                        <button type="submit" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-2 rounded-lg font-semibold transition">
                            คำนวณใหม่
                        </button>
                        <a href="checkout.php" class="bg-slate-800 hover:bg-slate-900 text-white px-8 py-2 rounded-lg font-semibold transition">
                            สั่งซื้อสินค้า →
                        </a>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <form id="form-delete" method="POST">
        <input type="hidden" name="delete_id" id="delete-id">
    </form>
</body>

</html>