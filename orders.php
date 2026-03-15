<?php
require_once __DIR__ . "/backend/config/session.php";

if (empty($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

require_once __DIR__ . "/db.php"; // IMPORTANT: always use the same DB file

$userId = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("
  SELECT id, total_amount, status, created_at
  FROM orders
  WHERE user_id = ?
  ORDER BY created_at DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$ordersRes = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Orders</title>
  <style>
    body{font-family:Arial, sans-serif; padding:20px; background:#fafafa;}
    .order{background:#fff; border:1px solid #ddd; border-radius:10px; padding:14px; margin:12px 0;}
    .meta{display:flex; gap:14px; flex-wrap:wrap; color:#333; margin-bottom:10px;}
    .items{margin-top:10px; padding-left:18px;}
    .badge{display:inline-block; padding:4px 10px; border-radius:999px; background:#eee;}
  </style>
</head>
<body>

<h1>My Orders</h1>

<?php if ($ordersRes->num_rows === 0): ?>
  <p>No orders yet.</p>
<?php else: ?>
  <?php while ($o = $ordersRes->fetch_assoc()): ?>
    <div class="order">
      <div class="meta">
        <div><strong>Order #<?= (int)$o['id'] ?></strong></div>
        <div class="badge"><?= htmlspecialchars($o['status']) ?></div>
        <div>Total: <strong>£<?= number_format((float)$o['total_amount'], 2) ?></strong></div>
        <div>Date: <?= htmlspecialchars($o['created_at']) ?></div>
      </div>

      <?php
        $orderId = (int)$o['id'];
        $stmt2 = $conn->prepare("
          SELECT oi.quantity, oi.price, p.name
          FROM order_items oi
          JOIN products p ON p.id = oi.product_id
          WHERE oi.order_id = ?
        ");
        $stmt2->bind_param("i", $orderId);
        $stmt2->execute();
        $itemsRes = $stmt2->get_result();
      ?>

      <div><strong>Items:</strong></div>
      <ul class="items">
        <?php while ($it = $itemsRes->fetch_assoc()): ?>
          <li>
            <?= htmlspecialchars($it['name']) ?> —
            Qty: <?= (int)$it['quantity'] ?> —
            £<?= number_format((float)$it['price'], 2) ?>
          </li>
        <?php endwhile; ?>
      </ul>
    </div>
  <?php endwhile; ?>
<?php endif; ?>

<p><a href="customer_dashboard.php">Back to Dashboard</a></p>

</body>
</html>