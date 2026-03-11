<?php
session_start();
if ($_SESSION['role'] == 'customer') {
    header("Location: index.php");
    exit();
}
require_once 'db.php';
$id = $_POST['id'];
$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$age = $_POST['age'];
$province = $_POST['province'];
$role = $_POST['role'];

$stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, age=?, province=?, role=? WHERE id=?");
$stmt->bind_param("ssissi", $fname, $lname, $age, $province, $role, $id);
if ($stmt->execute()) {
    header("Location: admin_show_users.php");
}
