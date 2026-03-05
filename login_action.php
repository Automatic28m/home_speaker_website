<?php
session_start();
require_once 'db.php';

$user = $_POST['username'];
$pass = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];
    header("Location: index.php");
} else {
    echo "<script>alert('Username หรือ Password ไม่ถูกต้อง!'); window.location='index.html';</script>";
}
