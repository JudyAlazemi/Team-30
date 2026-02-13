<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<nav>
    <a href="index.php">Home</a>
    <a href="products.php">Products</a>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
    <?php else: ?>
        <!-- 🔥 IMPORTANT: point to customer_dashboard -->
        <a href="customer_dashboard.php">My Dashboard</a>
        <a href="logout.php">Logout</a>
    <?php endif; ?>
</nav>
