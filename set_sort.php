<?php
session_start();
if (isset($_GET['sort'])) {
    $_SESSION['sort'] = $_GET['sort'];
}
?>