<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'db.php';
session_start();
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if($user_id) {
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
    $row_user = $result->fetch_assoc();
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">My Store</a>

            <div id="navbarNav">
                <ul class="navbar-nav flex flex-row items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>

                    <?php if ($role == 'admin') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin_show_users.php">Users List</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="admin_show_products.php">Products</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="admin_show_categories.php">Category</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="regis.php">Register</a>
                        </li>
                    <?php } ?>
                    <?php if ($user_id) { ?>
                        <li class="nav-item">
                            <span class="nav-link"><?php echo $row_user['username'] ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</div>