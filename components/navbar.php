<?php
// Note: session_start() should only be called once. 
// If it's already in your main files, you can remove it from here.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
$role = $_SESSION['role'] ?? null;

if ($user_id) {
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
    $row_user = $result->fetch_assoc();
}

$initial_cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark font-['Prompt',_sans-serif]">
    <div class="container-fluid">
        <a class="navbar-brand font-bold tracking-tighter" href="index.php">LUXOUND</a>

        <div id="navbarNav" class="flex justify-between w-full">
            <ul class="navbar-nav flex flex-row items-center gap-2">
                <li class="nav-item"><a class="nav-link" href="index.php">หน้าหลัก</a></li>
                <li class="nav-item"><a class="nav-link" href="order_detail.php">คำสั่งซื้อของคุณ</a></li>

                <?php if ($role == 'admin') { ?>
                    <li class="nav-item"><span class="nav-link">|</span></li>
                    <li class="nav-item"><a class="nav-link" href="admin_order_list.php">รายการสั่งซื้อ</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_show_users.php">สมาชิก</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_show_products.php">สินค้า</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_show_categories.php">หมวดหมู่</a></li>
                <?php } ?>
            </ul>

            <ul class="navbar-nav flex flex-row items-center gap-4">
                <li class="nav-item relative">
                    <a class="nav-link flex items-center p-2" href="cart.php">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span id="cart-badge"
                            class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center <?php echo $initial_cart_count > 0 ? '' : 'hidden'; ?>">
                            <?php echo $initial_cart_count; ?>
                        </span>
                    </a>
                </li>

                <?php if ($user_id) { ?>
                    <li class="nav-item border-l border-slate-700 pl-3">
                        <span class="nav-link text-blue-400 font-medium"><?php echo htmlspecialchars($row_user['username']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-slate-400 hover:text-white" href="logout.php">ออกจากระบบ</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">เข้าสู่ระบบ</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>