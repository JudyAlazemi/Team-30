<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$adminName  = $_SESSION["admin_name"] ?? "Admin";
$adminEmail = $_SESSION["admin_email"] ?? "";
$orders = [];

try {
    $sql = "
        SELECT 
            o.id,
            o.user_id,
            o.total_amount,
            o.status,
            o.created_at,
            o.updated_at,
            u.name AS customer_name,
            u.email AS customer_email
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
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
  <title>Process Orders</title>

  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/admin_dashboard.css?v=<?= time() ?>">

  <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="account-page">
  
<?php include __DIR__ . "/partials/navigation.php"; ?>

<main class="dash-page">
  <div class="dash-frame">
    <div class="dash-grid">

      <aside class="dash-left">
        <div class="dash-hello">
          <h3>Hello <?= htmlspecialchars($adminName) ?>,</h3>
          <p>Welcome back!</p>
        </div>

        <nav class="dash-menu">
          <a class="dash-link" href="admin_dashboard.php">
            <span class="dash-ico"><img src="assets/images/user.png" alt=""></span>
            <span>Dashboard</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_messages.php">
            <span class="dash-ico"><img src="/images/message.png" alt=""></span>
            <span>Customer Messages</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link is-active" href="admin_orders.php">
            <span class="dash-ico"><img src="assets/images/processorder.png" alt=""></span>
            <span>Process Orders</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_users.php">
            <span class="dash-ico"><img src="assets/images/sign-in.png" alt=""></span>
            <span>Customers</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_products.php">
            <span class="dash-ico"><img src="assets/images/inventory.png" alt=""></span>
            <span>Inventory</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="incoming_orders.php">
            <span class="dash-ico"><img src="assets/images/incoming-order.png" alt=""></span>
            <span>Incoming Orders</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_returns.php">
            <span class="dash-ico"><img src="assets/images/return.png" alt=""></span>
            <span>Return Requests</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_review.php">
            <span class="dash-ico"><img src="/images/reviews.png" alt=""></span>
            <span>Manage Reviews</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_logout.php">
            <span class="dash-ico"><img src="assets/images/settings.png" alt=""></span>
            <span>Logout</span>
            <span class="dash-arrow">›</span>
          </a>
        </nav>
      </aside>

      <section class="dash-right">
        <h1 class="dash-title">Process Orders</h1>
        <hr class="dash-rule">

        <div class="dash-long">
          <div class="dash-long-label">Admin email</div>
          <div class="dash-long-value"><?= htmlspecialchars($adminEmail) ?></div>
        </div>

        <div class="dash-section">
          <h2 class="dash-section-title">All Orders</h2>

          <?php if (empty($orders)): ?>
            <p class="dash-empty">No orders found.</p>
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
                    <th>Update</th>
                    <th>Created</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($orders as $order): ?>
                    <tr>
                      <td>#<?= (int)$order["id"] ?></td>
                      <td><?= htmlspecialchars($order["customer_name"] ?? "Customer") ?></td>
                      <td><?= htmlspecialchars($order["customer_email"] ?? "") ?></td>
                      <td>£<?= number_format((float)$order["total_amount"], 2) ?></td>
                      <td><?= htmlspecialchars($order["status"]) ?></td>
                      <td>
                        <form action="update_order_status.php" method="POST" class="dash-actions">
                          <input type="hidden" name="order_id" value="<?= (int)$order["id"] ?>">

                          <select name="status" class="order-status-select" required>
                            <option value="pending" <?= $order["status"] === "pending" ? "selected" : "" ?>>Pending</option>
                            <option value="processing" <?= $order["status"] === "processing" ? "selected" : "" ?>>Processing</option>
                            <option value="shipped" <?= $order["status"] === "shipped" ? "selected" : "" ?>>Shipped</option>
                            <option value="delivered" <?= $order["status"] === "delivered" ? "selected" : "" ?>>Delivered</option>
                            <option value="cancelled" <?= $order["status"] === "cancelled" ? "selected" : "" ?>>Cancelled</option>
                            <option value="return_pending" <?= $order["status"] === "return_pending" ? "selected" : "" ?>>Return Pending</option>
                            <option value="returned" <?= $order["status"] === "returned" ? "selected" : "" ?>>Returned</option>
                          </select>

                          <button type="submit" class="dash-back">Save</button>
                        </form>
                      </td>
                      <td><?= htmlspecialchars($order["created_at"]) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </div>
</main>

</body>
</html>
