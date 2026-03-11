<?php
session_start();
include './db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// 1. ดึงออเดอร์ทั้งหมดของลูกค้าคนนี้
$sql_orders = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC, id DESC";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$orders_result = $stmt_orders->get_result();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <?php include './style.php'; ?>
    <title>ประวัติการสั่งซื้อ - My Store</title>
</head>
<?php include "./components/navbar.php" ?>

<body class="bg-slate-50 font-['Prompt'] text-slate-800">
    <div class="max-w-4xl mx-auto my-12 px-4">
        <h1 class="text-3xl font-black uppercase tracking-tighter mb-10">คำสั่งซื้อ</h1>

        <?php if ($orders_result->num_rows > 0): ?>
            <div class="space-y-10">
                <?php while ($order = $orders_result->fetch_assoc()):
                    $order_id = $order['id'];
                ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-50 flex flex-wrap justify-between items-center bg-slate-50/30 gap-4">
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-bold uppercase text-slate-400">Order #<?php echo str_pad($order_id, 5, '0', STR_PAD_LEFT); ?></span>
                                <span class="text-xs text-slate-400"><?php echo date('d M Y', strtotime($order['order_date'])); ?></span>
                            </div>
                            <div class="flex gap-2">
                                <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[10px] font-bold uppercase">
                                    <?php echo $order['payment']; ?>
                                </span>
                                <span class="px-3 py-1 <?php echo (strtolower($order['pay_status']) == 'paid') ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500'; ?> rounded-full text-[10px] font-bold uppercase">
                                    <?php echo $order['pay_status']; ?>
                                </span>
                                <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-[10px] font-bold uppercase">
                                    <?php echo $order['status']; ?>
                                </span>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            <?php
                            $sql_items = "SELECT od.*, p.name, p.price, p.img_files 
                                      FROM order_details od 
                                      JOIN products p ON od.product_id = p.id 
                                      WHERE od.order_id = ?";
                            $stmt_items = $conn->prepare($sql_items);
                            $stmt_items->bind_param("i", $order_id);
                            $stmt_items->execute();
                            $items_result = $stmt_items->get_result();

                            $order_total = 0;
                            while ($item = $items_result->fetch_assoc()):
                                $subtotal = $item['price'] * $item['quantity'];
                                $order_total += $subtotal;
                                $imgs = explode(',', $item['img_files']);
                            ?>
                                <div class="flex items-center gap-6">
                                    <img src="./prod_images/<?php echo $imgs[0]; ?>" class="w-16 h-16 object-contain rounded-lg bg-slate-50 p-2">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-800 text-sm uppercase"><?php echo $item['name']; ?></h4>
                                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider">Qty: <?php echo $item['quantity']; ?> × ฿<?php echo number_format($item['price'], 2); ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-slate-700 text-sm">฿<?php echo number_format($subtotal, 2); ?></p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-between items-end">
                            <div class="max-w-[60%]">
                                <span class="text-[10px] font-bold text-slate-300 uppercase block mb-1">Shipping to:</span>
                                <p class="text-xs text-slate-500 italic truncate"><?php echo $order['delivery']; ?></p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-slate-300 uppercase block mb-1">Total Amount</span>
                                <span class="text-xl font-black italic text-slate-900">฿<?php echo number_format($order_total, 2); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-slate-200">
                <p class="text-slate-400 uppercase font-bold tracking-widest">คุณยังไม่มีคำสั่งซื้อ</p>
                <a href="index.php" class="inline-block mt-4 text-sm text-blue-500 hover:underline">เริ่มช้อปปิ้งเลย →</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>