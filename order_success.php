<?php
session_start();
include './db.php';

$user_id = $_SESSION['user_id'] ?? null;
$order_id = $_GET['order_id'] ?? null;

if (!$user_id || !$order_id) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT o.*, d.quantity, p.name, p.price, p.img_files 
        FROM orders o 
        JOIN order_details d ON o.id = d.order_id 
        JOIN products p ON d.product_id = p.id 
        WHERE o.id = ? AND o.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
$order_info = null;
$grand_total = 0;

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    if (!$order_info) $order_info = $row;
    $grand_total += ($row['price'] * $row['quantity']);
}

if (!$order_info) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <?php include './style.php'; ?>
    <title>สั่งซื้อสำเร็จ - My Store</title>
</head>
<?php include "./components/navbar.php" ?>

<body class="bg-slate-50 font-['Prompt'] text-slate-800">
    <div class="max-w-3xl mx-auto my-16 px-4">
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 text-green-600 rounded-full mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold uppercase tracking-tighter">Order Successfully Placed</h1>
            <p class="text-slate-500 mt-2">ขอบคุณสำหรับการสั่งซื้อ ระบบได้รับคำสั่งซื้อของคุณเรียบร้อยแล้ว</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-slate-400 block uppercase text-xs font-bold mb-1">Order Number</span>
                    <span class="font-medium">#<?php echo str_pad($order_info['id'], 5, '0', STR_PAD_LEFT); ?></span>
                </div>
                <div class="text-right">
                    <span class="text-slate-400 block uppercase text-xs font-bold mb-1">Date</span>
                    <span class="font-medium"><?php echo date('d M Y', strtotime($order_info['order_date'])); ?></span>
                </div>
                <div>
                    <span class="text-slate-400 block uppercase text-xs font-bold mb-1">Payment Method</span>
                    <span class="font-medium"><?php echo $order_info['payment']; ?></span>
                </div>
                <div class="text-right">
                    <span class="text-slate-400 block uppercase text-xs font-bold mb-1">Status</span>
                    <span class="inline-block px-2 py-0.5 rounded bg-blue-50 text-blue-600 text-xs font-bold uppercase">
                        <?php echo $order_info['status']; ?>
                    </span>
                </div>
            </div>

            <div class="p-8 space-y-6">
                <h3 class="font-bold text-lg border-b pb-4 border-slate-50">รายการสินค้า</h3>
                <?php foreach ($items as $item):
                    $imgs = explode(',', $item['img_files']);
                ?>
                    <div class="flex items-center gap-4">
                        <img src="./prod_images/<?php echo $imgs[0]; ?>" class="w-16 h-16 object-contain rounded bg-slate-50 p-1">
                        <div class="flex-1">
                            <h4 class="font-medium text-sm uppercase"><?php echo $item['name']; ?></h4>
                            <span class="text-xs text-slate-400">จำนวน: <?php echo $item['quantity']; ?></span>
                        </div>
                        <span class="font-bold">฿<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="bg-slate-900 text-white p-8 flex justify-between items-center">
                <div>
                    <span class="text-slate-400 block text-xs uppercase font-bold">Total Amount</span>
                    <span class="text-3xl font-black italic">฿<?php echo number_format($grand_total, 2); ?></span>
                </div>
                <div class="text-right text-xs text-slate-400">
                    <p>ที่อยู่จัดส่ง:</p>
                    <p class="text-white font-medium mt-1 italic"><?php echo $order_info['delivery']; ?></p>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center flex flex-col sm:flex-row gap-4 justify-center">
            <a href="index.php" class="px-8 py-3 bg-white border border-slate-200 rounded-xl font-bold hover:bg-slate-50 transition-colors">
                กลับหน้าหลัก
            </a>
            <button onclick="window.print()" class="px-8 py-3 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-900 transition-shadow shadow-lg shadow-slate-200">
                พิมพ์ใบเสร็จ
            </button>
        </div>
    </div>
</body>

</html>