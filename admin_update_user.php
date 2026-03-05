<?php
session_start();
require_once 'db.php';
$id = $_POST['id'];
$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$age = $_POST['age'];
$province = $_POST['province'];

$stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, age=?, province=? WHERE id=?");
$stmt->bind_param("ssisi", $fname, $lname, $age, $province, $id);
if($stmt->execute()) {
    header("Location: showUsers.php");
}
?>