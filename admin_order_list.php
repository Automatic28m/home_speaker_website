<?php
session_start();
include './db.php';

// 1. เช็คสิทธิ์ Admin
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager') {
    header("Location: index.php");
    exit();
}

// 2. Logic: ยืนยันออเดอร์
if (isset($_POST['confirm_id'])) {
    $confirm_id = $_POST['confirm_id'];
    $sql_upd = "UPDATE orders SET pay_status = 'Paid', status = 'completed' WHERE id = ?";
    $stmt_upd = $conn->prepare($sql_upd);
    $stmt_upd->bind_param("i", $confirm_id);
    $stmt_upd->execute();
}

// 3. Logic: ปฏิเสธและคืนสต็อก
if (isset($_POST['cancel_id'])) {
    $cancel_id = $_POST['cancel_id'];
    $conn->begin_transaction();
    try {
        $conn->query("UPDATE orders SET status = 'cancelled' WHERE id = $cancel_id");
        // คืนสต็อก
        $res_items = $conn->query("SELECT * FROM order_details WHERE order_id = $cancel_id");
        while ($item = $res_items->fetch_assoc()) {
            $conn->query("UPDATE products SET remain = remain + {$item['quantity']} WHERE id = {$item['product_id']}");
        }
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
    }
}

// 4. ดึงข้อมูลออเดอร์
$order_result = $conn->query("SELECT o.*, u.first_name, u.last_name, 
        (SELECT SUM(od.quantity * p.price) FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = o.id) as total_amount 
        FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.order_date DESC");
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Admin Dashboard</title>
</head>
<?php include "./components/navbar.php" ?>

<body class="bg-slate-50 font-['Prompt'] text-slate-800">
    <div class="max-w-6xl mx-auto my-12 px-4">
        <h1 class="text-2xl font-black uppercase mb-8 tracking-tighter">คำสั่งซื้อจากลูกค้า</h1>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400">
                    <tr>
                        <th class="p-4">Order ID</th>
                        <th class="p-4">ลูกค้า</th>
                        <th class="p-4 text-right">ยอดรวม</th>
                        <th class="p-4 text-center">สถานะ</th>
                        <th class="p-4 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php while ($row = $order_result->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 font-mono text-[10px]">#<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></td>
                            <td class="p-4 text-sm font-semibold"><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                            <td class="p-4 font-bold text-sm">฿<?php echo number_format($row['total_amount'], 2); ?></td>
                            <td class="p-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase 
                                <?php echo ($row['status'] == 'completed') ? 'bg-blue-50 text-blue-600' : (($row['status'] == 'cancelled') ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600'); ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <?php if ($row['status'] == 'pending') { ?>
                                <td class="p-4 text-right flex gap-1">
                                    <?php if ($row['status'] !== 'completed' && $row['status'] !== 'cancelled'): ?>
                                        <form method="POST">
                                            <input type="hidden" name="confirm_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="bg-blue-600 text-white text-[10px] px-2 py-1 rounded font-bold">ยืนยัน</button>
                                        </form>
                                        <form method="POST">
                                            <input type="hidden" name="cancel_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="bg-red-500 text-white text-[10px] px-2 py-1 rounded font-bold">ปฏิเสธ</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>