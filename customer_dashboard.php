<?php
require_once __DIR__ . "/includes/account_layout.php";
$active = "dashboard";

$totalOrders = 0;
$pendingOrders = 0;
$totalSpent = 0.0;

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

  $stmt = $conn->prepare("SELECT id,total_amount,status,created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
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
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="customer_dashboard.css">
</head>
<body class="account-page">

<div class="account-shell">

  <aside class="account-side">
    <h3 class="side-title">Sabil Account</h3>
    <p class="side-sub">Welcome, <?= htmlspecialchars($userName) ?></p>

    <nav class="side-nav">
      <?= sideLink("customer_dashboard.php", "📌 Dashboard", true) ?>
      <?= sideLink("my_orders.php", "🧾 My Orders", false, $orderCount) ?>
      <?= sideLink("favourites.php", "🤍 Favourites", false, $favCount) ?>
      <?= sideLink("messages.php", "💬 Messages", false) ?>
      <?= sideLink("profile.php", "⚙️ Profile Settings", false) ?>
      <a class="side-link logout" href="logout.php"><span class="side-text">🚪 Logout</span></a>
    </nav>
  </aside>

  <main class="account-main">

    <section class="panel">
      <h1 class="panel-title">Hi, <?= htmlspecialchars($userName) ?></h1>

      <div class="dash-cards">
        <div class="dash-card">
          <div class="label">Total Orders</div>
          <div class="big"><?= $totalOrders ?></div>
        </div>
        <div class="dash-card">
          <div class="label">Pending Orders</div>
          <div class="big"><?= $pendingOrders ?></div>
        </div>
        <div class="dash-card">
          <div class="label">Favourites</div>
          <div class="big"><?= $favCount ?></div>
        </div>
        <div class="dash-card wide">
          <div class="label">Total Spent</div>
          <div class="big">£<?= number_format($totalSpent, 2) ?></div>
        </div>
      </div>
    </section>

    <section class="panel">
      <div class="panel-head">
        <h2 class="panel-h2">Recent Orders</h2>
        <a class="btn-sabil" href="my_orders.php">View all</a>
      </div>

      <div class="tablewrap">
        <table>
          <thead>
            <tr>
              <th>Order</th><th>Total</th><th>Status</th><th>Date</th>
            </tr>
          </thead>
          <tbody>
          <?php if (empty($recentOrders)): ?>
            <tr><td colspan="4">No orders yet.</td></tr>
          <?php else: ?>
            <?php foreach ($recentOrders as $o): ?>
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
    </section>

  </main>
</div>

</body>
</html>
