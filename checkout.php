<?php
session_start();
include './db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT c.quantity, p.name, p.price, u.first_name, u.last_name, u.province 
        FROM cart c 
        JOIN products p ON c.product_id = p.id
        JOIN users u ON c.user_id = u.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
$user_info = null;
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    if (!$user_info) $user_info = $row; // เก็บข้อมูลผู้ซื้อจากแถวแรก
}

if (count($items) == 0) {
    header("Location: index.php");
    exit();
}

$grand_total = 0;
// สร้างที่อยู่เริ่มต้นจากข้อมูลในฐานข้อมูล
$default_address = $user_info['first_name'] . " " . $user_info['last_name'] . " จังหวัด " . $user_info['province'];
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <?php include './style.php'; ?>
    <title>Checkout - ยืนยันการสั่งซื้อ</title>
</head>
<?php include "./components/navbar.php" ?>

<body class="bg-slate-50 font-['Prompt']">
    <div class="max-w-6xl mx-auto my-12 px-4 grid grid-cols-1 lg:grid-cols-2 gap-12">

        <div class="space-y-6">
            <h2 class="text-2xl font-bold text-slate-800 uppercase italic">Your Order</h2>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 space-y-4">
                    <?php foreach ($items as $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $grand_total += $subtotal;
                    ?>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600"><?php echo $item['name']; ?> (x<?php echo $item['quantity']; ?>)</span>
                            <span class="font-semibold text-slate-800">฿<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="bg-slate-50 p-6 border-t border-slate-100">
                    <div class="flex justify-between text-xl font-bold text-slate-900">
                        <span>ยอดชำระสุทธิ:</span>
                        <span class="text-blue-600">฿<?php echo number_format($grand_total, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <form action="place_order.php" method="POST" class="bg-white p-8 rounded-xl shadow-sm border border-slate-100 space-y-6">
            <h2 class="text-2xl font-bold text-slate-800 mb-4">ข้อมูลการจัดส่งและการชำระเงิน</h2>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">ที่อยู่จัดส่ง (Delivery)</label>
                <textarea name="delivery" required class="w-full p-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none h-32"><?php echo htmlspecialchars($default_address); ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">วิธีชำระเงิน</label>
                <select name="payment" class="w-full p-3 border border-slate-200 rounded-lg outline-none bg-slate-50">
                    <option value="Bank Transfer">โอนเงินผ่านธนาคาร</option>
                    <option value="Credit Card">บัตรเครดิต</option>
                    <option value="COD">เก็บเงินปลายทาง</option>
                </select>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-slate-800 text-white py-4 rounded-xl font-bold text-lg hover:bg-slate-900 transition-all shadow-lg shadow-slate-200 uppercase tracking-wider">
                    Place Order
                </button>
                <p class="text-center text-slate-400 text-xs mt-4 italic">
                    ข้อมูลส่วนตัวของคุณจะถูกนำไปใช้เพื่อประมวลผลคำสั่งซื้อและจัดส่งสินค้าเท่านั้น
                </p>
            </div>
        </form>
    </div>
</body>

</html>