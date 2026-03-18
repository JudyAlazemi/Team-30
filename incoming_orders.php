<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$adminName = $_SESSION["admin_name"] ?? "Admin";

$orders = [];

try {

    $sql = "
        SELECT 
            o.id,
            o.total_amount,
            o.status,
            o.created_at,
            u.name AS customer_name,
            u.email AS customer_email
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.status = 'pending'
        ORDER BY o.created_at DESC
    ";

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }

} catch (Exception $e) {
    $orders = [];
}
?>

<!doctype html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Incoming Orders</title>

<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/admin_dashboard.css?v=<?= time() ?>">
<link rel="stylesheet" href="assets/css/darkmode.css">

</head>

<body class="account-page">

<?php include __DIR__ . "/partials/navigation.php"; ?>

<main class="dash-page">

<div class="dash-frame">

<div class="dash-grid">


<aside class="dash-left">

<div class="dash-hello">
<h3>Hello</h3>
<p><?= htmlspecialchars($adminName) ?></p>
</div>


<nav class="dash-menu">

<a class="dash-link" href="admin_dashboard.php">
<span class="dash-ico"><img src="assets/images/user.png"></span>
<span>Dashboard</span>
<span class="dash-arrow">›</span>
</a>

<a class="dash-link" href="admin_orders.php">
<span class="dash-ico"><img src="assets/images/processorder.png"></span>
<span>Process Orders</span>
<span class="dash-arrow">›</span>
</a>

<a class="dash-link" href="admin_users.php">
<span class="dash-ico"><img src="assets/images/sign-in.png"></span>
<span>Customers</span>
<span class="dash-arrow">›</span>
</a>

<a class="dash-link" href="admin_products.php">
<span class="dash-ico"><img src="assets/images/inventory.png"></span>
<span>Inventory</span>
<span class="dash-arrow">›</span>
</a>

<a class="dash-link is-active" href="incoming_orders.php">
<span class="dash-ico"><img src="assets/images/incoming-order.png"></span>
<span>Incoming Orders</span>
<span class="dash-arrow">›</span>
</a>

<a class="dash-link" href="admin_returns.php">
<span class="dash-ico"><img src="assets/images/return.png"></span>
<span>Return Requests</span>
<span class="dash-arrow">›</span>
</a>

<a class="dash-link" href="admin_logout.php">
<span class="dash-ico"><img src="assets/images/settings.png"></span>
<span>Logout</span>
<span class="dash-arrow">›</span>
</a>

</nav>

</aside>


<section class="dash-right">

<h1 class="dash-title">Incoming Orders</h1>

<hr class="dash-rule">


<?php if (empty($orders)): ?>

<p class="dash-empty">No new orders.</p>

<?php else: ?>


<div class="dash-table-wrap">

<table class="dash-table">

<thead>

<tr>
<th>Order ID</th>
<th>Customer</th>
<th>Email</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>
<th>Date</th>
</tr>

</thead>

<tbody>

<?php foreach ($orders as $order): ?>

<tr>

<td>#<?= $order["id"] ?></td>

<td><?= htmlspecialchars($order["customer_name"] ?? "Customer") ?></td>

<td><?= htmlspecialchars($order["customer_email"] ?? "") ?></td>

<td>£<?= number_format($order["total_amount"],2) ?></td>

<td><?= htmlspecialchars($order["status"]) ?></td>

<td>

<form action="update_order_status.php" method="POST">

<input type="hidden" name="order_id" value="<?= $order["id"] ?>">

<select name="status">

<option value="processing">Accept</option>
<option value="cancelled">Reject</option>

</select>

<button type="submit" class="dash-back">Update</button>

</form>

</td>

<td><?= $order["created_at"] ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php endif; ?>

</section>

</div>

</div>

</main>

</body>
</html>
