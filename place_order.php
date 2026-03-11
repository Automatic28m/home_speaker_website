<?php
session_start();
include './db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$payment = $_POST['payment'];
$delivery = $_POST['delivery'];
$order_date = date('Y-m-d');
$status = "pending"; 
$pay_status = "unpaid";

$conn->begin_transaction();

try {
    $sql_order = "INSERT INTO orders (user_id, payment, pay_status, order_date, status, delivery) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("isssss", $user_id, $payment, $pay_status, $order_date, $status, $delivery);
    $stmt_order->execute();
    $order_id = $conn->insert_id;

    $sql_cart = "SELECT c.*, p.remain FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
    $stmt_cart = $conn->prepare($sql_cart);
    $stmt_cart->bind_param("i", $user_id);
    $stmt_cart->execute();
    $cart_result = $stmt_cart->get_result();

    while ($item = $cart_result->fetch_assoc()) {
        $p_id = $item['product_id'];
        $qty = $item['quantity'];
        $current_stock = $item['remain'];

        if ($current_stock < $qty) {
            throw new Exception("สินค้าในสต็อกไม่เพียงพอสำหรับการสั่งซื้อ");
        }

        $sql_detail = "INSERT INTO order_details (order_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt_detail = $conn->prepare($sql_detail);
        $stmt_detail->bind_param("iii", $order_id, $p_id, $qty);
        $stmt_detail->execute();

        $new_remain = $current_stock - $qty;
        $sql_update_stock = "UPDATE products SET remain = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update_stock);
        $stmt_update->bind_param("ii", $new_remain, $p_id);
        $stmt_update->execute();
    }

    $sql_clear_cart = "DELETE FROM cart WHERE user_id = ?";
    $stmt_clear = $conn->prepare($sql_clear_cart);
    $stmt_clear->bind_param("i", $user_id);
    $stmt_clear->execute();

    $conn->commit();
    header("Location: order_success.php?order_id=" . $order_id);

} catch (Exception $e) {
    $conn->rollback();
    die("Error: " . $e->getMessage());
}
?>