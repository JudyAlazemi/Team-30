<?php
session_start();
require_once __DIR__ . "/config/db.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

$userId = (int)$_SESSION['user_id'];
$userName = $_SESSION['user_name'] ?? "Customer";

$totalOrders = 0;
$pendingOrders = 0;
$totalSpent = 0.0;
$favCount = 0;
$recentOrders = [];

try {
  $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM orders WHERE user_id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $totalOrders = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);

  $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM orders WHERE user_id = ? AND status IN ('pending','processing')");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $pendingOrders = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);

  $stmt = $conn->prepare("SELECT COALESCE(SUM(total_amount),0) AS s FROM orders WHERE user_id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $totalSpent = (float)($stmt->get_result()->fetch_assoc()['s'] ?? 0);

  $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM favourites WHERE user_id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $favCount = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);

  $stmt = $conn->prepare("SELECT id, total_amount, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $recentOrders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Customer Dashboard</title>
  <link rel="stylesheet" href="customer_dashboard.css">
</head>
<body>

<div class="layout">
  <aside class="sidebar">
    <div class="brand">SABIL</div>

    <div class="userbox">
      <div class="avatar"><?= strtoupper(substr($userName,0,1)) ?></div>
      <div>
        <div class="name"><?= htmlspecialchars($userName) ?></div>
        <div class="role">Customer</div>
      </div>
    </div>

    <nav class="nav">
      <a class="navlink active" href="customer_dashboard.php">Dashboard</a>
      <a class="navlink" href="customer_orders.php">My Orders</a>
      <a class="navlink" href="logout.php">Logout</a>
    </nav>
  </aside>

  <main class="content">
    <div class="topbar">
      <div class="title">Welcome, <?= htmlspecialchars($userName) ?></div>
      <div class="small">This is your customer dashboard</div>
    </div>

    <div class="grid">
      <div class="card">
        <div class="label">Total Orders</div>
        <div class="big"><?= $totalOrders ?></div>
      </div>

      <div class="card">
        <div class="label">Pending Orders</div>
        <div class="big"><?= $pendingOrders ?></div>
      </div>

      <div class="card">
        <div class="label">Favourites</div>
        <div class="big"><?= $favCount ?></div>
      </div>

      <div class="card wide">
        <div class="label">Total Spent</div>
        <div class="big">£<?= number_format($totalSpent, 2) ?></div>
      </div>
    </div>

    <div class="card">
      <div class="cardhead">
        <div class="h">Recent Orders</div>
        <a class="btn" href="customer_orders.php">View all</a>
      </div>

      <div class="tablewrap">
        <table>
          <thead>
            <tr>
              <th>Order</th>
              <th>Total</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($recentOrders)): ?>
              <tr><td colspan="4">No orders yet.</td></tr>
            <?php else: ?>
              <?php foreach ($recentOrders as $o): ?>
                <tr>
                  <td>#<?= htmlspecialchars($o['id']) ?></td>
                  <td>£<?= number_format((float)$o['total_amount'],2) ?></td>
                  <td><span class="badge"><?= htmlspecialchars($o['status']) ?></span></td>
                  <td><?= htmlspecialchars($o['created_at']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>
</div>

</body>
</html>
