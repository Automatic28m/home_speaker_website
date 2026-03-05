<?php
session_start();
require_once 'db.php';
if ($_SESSION['role'] == 'admin') {
    $id = $_GET['id'];
    $conn->query("DELETE FROM users WHERE id = $id");
}
header("Location: showdata.php");
