<?php
session_start();
include './db.php';

header('Content-Type: application/json');

// Get the AJAX data
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อน!']);
    exit;
}

if (isset($data['product_id']) && isset($data['qty'])) {
    $p_id = (int)$data['product_id'];
    $qty = (int)$data['qty'];

    $sql = "INSERT INTO cart (user_id, product_id, quantity) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE quantity = quantity + ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $user_id, $p_id, $qty, $qty);

    if ($stmt->execute()) {
        // Calculate new total for the navbar badge
        $res = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id");
        $row = $res->fetch_assoc();

        echo json_encode([
            'status' => 'success',
            'message' => 'เพิ่มลงตะกร้าเรียบร้อยแล้ว!',
            'cart_count' => $row['total'] ?? 0
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
}
