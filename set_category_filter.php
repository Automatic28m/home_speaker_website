<?php
session_start();
if (isset($_GET['category'])) {
    $_SESSION['category'] = $_GET['category'];
}
?>