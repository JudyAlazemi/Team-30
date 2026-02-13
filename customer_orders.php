<?php
session_start();
require_once __DIR__ . "/config/db.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

$userId = (int)$_SESSION['user_id'];
$orders = [];

try {
  $stmt = $conn->prepare("SELECT id, total_amount, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>My Orders</title>
  <link rel="stylesheet" href="customer_dashboard.css">
</head>
<body>

<div class="simplewrap">
  <div class="simpletop">
    <a class="btn" href="customer_dashboard.php">← Back</a>
    <h2>My Orders</h2>
    <a class="btn danger" href="logout.php">Logout</a>
  </div>

  <div class="card">
    <div class="tablewrap">
      <table>
        <thead>
          <tr>
            <th>Order</th><th>Total</th><th>Status</th><th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($orders)): ?>
            <tr><td colspan="4">No orders found.</td></tr>
          <?php else: ?>
            <?php foreach ($orders as $o): ?>
              <tr>
                <td>#<?= htmlspecialchars($o['id']) ?></td>
                <td>£<?= number_format((float)$o['total_amount'], 2) ?></td>
                <td><span class="badge"><?= htmlspecialchars($o['status']) ?></span></td>
                <td><?= htmlspecialchars($o['created_at']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
