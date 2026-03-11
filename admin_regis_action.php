<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'db.php';
session_start();

if ($_SESSION['role'] == 'customer') {
    header("Location: index.php");
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];
$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$gender = $_POST['gender'];
$age = $_POST['age'];
$province = $_POST['province'];
$email = $_POST['email'];
$role = $_POST['role'];

$password_pattern = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/";
// Perl Regular Expression Group Match
if (!preg_match($password_pattern, $password)) {
    die("<script>alert('รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร ประกอบด้วยตัวเลข ตัวพิมพ์เล็ก และตัวพิมพ์ใหญ่อย่างน้อย 1 ตัว'); window.history.back();</script>");
}

// ตรวจสอบอีเมลซ้ำ
$check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
if ($check_email->get_result()->num_rows > 0) {
    die("<script>alert('อีเมลนี้ถูกใช้งานแล้ว!'); window.history.back();</script>");
}

// ตรวจสอบ username ซ้ำ
$check_user = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check_user->bind_param("s", $username);
$check_user->execute();
if ($check_user->get_result()->num_rows > 0) {
    die("<script>alert('Username นี้ถูกใช้งานแล้ว!'); window.history.back();</script>");
}

// บันทึกข้อมูล
$stmt = $conn->prepare("INSERT INTO users (username, password, first_name, last_name, gender, age, province, email, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssisss", $username, $password, $fname, $lname, $gender, $age, $province, $email, $role);

if ($stmt->execute()) {
    echo "<script>alert('ลงทะเบียนสำเร็จ!'); window.location='admin_show_users.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}
