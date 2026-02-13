<?php
// dashboard.php (Customer Dashboard)

session_start();

// ✅ Login protection
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$userId = (int) $_SESSION['user_id'];

// ✅ Load DB connection
// Your login.php uses "../config/db.php" (so repo likely has config/db.php)
// But you uploaded db.php too. This supports BOTH.
$dbPath1 = __DIR__ . "/config/db.php";
$dbPath2 = __DIR__ . "/db.php";

if (file_exists($dbPath1)) {
    require_once $dbPath1;
} else {
    require_once $dbPath2;
}

// -------------------- Fetch user details --------------------
$userName = $_SESSION['user_name'] ?? "Customer";
$userEmail = "";

$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows === 1) {
    $row = $res->fetch_assoc();
    $userName = $row["name"];
    $userEmail = $row["email"];
}
$stmt->close();

// -------------------- Stats: orders count --------------------
$orderCount = 0;
$stmt = $conn->prepare("SELECT COUNT(*) AS c FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
if ($res) {
    $orderCount = (int)($res->fetch_assoc()["c"] ?? 0);
}
$stmt->close();

// -------------------- Stats: favourites count --------------------
$favCount = 0;
$stmt = $conn->prepare("SELECT COUNT(*) AS c FROM favourites WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
if ($res) {
    $favCount = (int)($res->fetch_assoc()["c"] ?? 0);
}
$stmt->close();

// -------------------- Recent Orders (last 5) --------------------
$recentOrders = [];
$stmt = $conn->prepare("
    SELECT id, total_amount, status, created_at
    FROM orders
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT 5
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $recentOrders[] = $r;
    }
}
$stmt->close();

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customer Dashboard</title>

  <style>
    body { margin:0; font-family: Arial, sans-serif; background:#f6f7fb; color:#111; }
    .wrap { max-width: 1100px; margin: 0 auto; padding: 22px; }
    .top { display:flex; justify-content:space-between; align-items:center; gap:14px; flex-wrap:wrap; }
    .card { background:#fff; border-radius:14px; padding:18px; box-shadow:0 8px 20px rgba(0,0,0,0.06); }
    .grid { display:grid; grid-template-columns: repeat(12, 1fr); gap:16px; margin-top:16px; }
    .col-4 { grid-column: span 4; }
    .col-6 { grid-column: span 6; }
    .col-12 { grid-column: span 12; }
    @media (max-width: 900px){
      .col-4, .col-6 { grid-column: span 12; }
    }
    .btn { display:inline-block; padding:10px 14px; border-radius:10px; text-decoration:none; background:#111; color:#fff; }
    .btn.secondary { background:#fff; color:#111; border:1px solid #ddd; }
    .muted { color:#666; font-size: 14px; margin-top:4px; }
    .links { display:flex; gap:10px; flex-wrap:wrap; margin-top:10px; }
    .stat { font-size:28px; font-weight:700; margin:8px 0 0; }
    table { width:100%; border-collapse:collapse; margin-top:10px; }
    th, td { text-align:left; padding:10px; border-bottom:1px solid #eee; font-size:14px; }
    .pill { display:inline-block; padding:4px 10px; border-radius:999px; background:#f0f0f0; font-size:12px; }
  </style>
</head>
<body>

<div class="wrap">
  <div class="top">
    <div>
      <h2 style="margin:0;">Welcome, <?= htmlspecialchars($userName) ?> 👋</h2>
      <div class="muted"><?= $userEmail ? htmlspecialchars($userEmail) : "Signed in" ?></div>
    </div>

    <div class="links" style="margin-top:0;">
      <a class="btn secondary" href="products.php">Shop</a>
      <a class="btn secondary" href="cart.php">Basket</a>
      <a class="btn" href="logout.php">Logout</a>
    </div>
  </div>

  <div class="grid">

    <div class="card col-4">
      <h3 style="margin-top:0;">My Orders</h3>
      <div class="muted">Total orders you’ve placed</div>
      <div class="stat"><?= (int)$orderCount ?></div>
      <div class="links">
        <a class="btn secondary" href="orders.php">View Orders</a>
        <a class="btn secondary" href="checkout.php">Checkout</a>
      </div>
    </div>

    <div class="card col-4">
      <h3 style="margin-top:0;">Favourites</h3>
      <div class="muted">Products you saved</div>
      <div class="stat"><?= (int)$favCount ?></div>
      <div class="links">
        <a class="btn secondary" href="favourites.php">View Favourites</a>
      </div>
    </div>

    <div class="card col-4">
      <h3 style="margin-top:0;">My Account</h3>
      <div class="muted">Profile details & settings</div>
      <div class="stat" style="font-size:18px; font-weight:600; margin-top:10px;">
        ID: <?= (int)$userId ?>
      </div>
      <div class="links">
        <a class="btn secondary" href="profile.php">Profile</a>
      </div>
    </div>

    <div class="card col-12">
      <h3 style="margin-top:0;">Recent Orders</h3>

      <?php if (count($recentOrders) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Total</th>
              <th>Status</th>
              <th>Placed</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentOrders as $o): ?>
              <tr>
                <td>#<?= htmlspecialchars($o["id"]) ?></td>
                <td><?= htmlspecialchars($o["total_amount"]) ?></td>
                <td><span class="pill"><?= htmlspecialchars($o["status"]) ?></span></td>
                <td><?= htmlspecialchars($o["created_at"]) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="muted">No orders found yet. Start shopping to place your first order.</p>
        <div class="links">
          <a class="btn secondary" href="products.php">Browse Products</a>
        </div>
      <?php endif; ?>
    </div>

    <div class="card col-12">
      <h3 style="margin-top:0;">Help & Info</h3>
      <div class="links">
        <a class="btn secondary" href="contactus.php">Contact Us</a>
        <a class="btn secondary" href="privacypolicy.php">Privacy Policy</a>
        <a class="btn secondary" href="terms.php">Terms</a>
      </div>
    </div>

  </div>
</div>

</body>
</html>
