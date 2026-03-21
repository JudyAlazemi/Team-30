<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$userId   = (int) $_SESSION["user_id"];
$userName = $_SESSION["user_name"] ?? "Customer";
$orderId  = (int) ($_GET["id"] ?? 0);

if ($orderId <= 0) {
    die("Invalid order ID.");
}

$order = null;
$orderItems = [];

try {
    // Get the order and make sure it belongs to the logged-in user
    $stmt = $conn->prepare("
        SELECT id, user_id, total_amount, status, created_at, updated_at
        FROM orders
        WHERE id = ? AND user_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();

    if (!$order) {
        die("Order not found or you do not have permission to view it.");
    }

    // Get all items in this order
    $stmt = $conn->prepare("
        SELECT 
            oi.product_id,
            oi.quantity,
            oi.price,
            p.name,
            p.description,
            p.image_url
        FROM order_items oi
        JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = ?
        ORDER BY oi.id ASC
    ");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $orderItems[] = $row;
    }

    $stmt->close();
} catch (Exception $e) {
    die("Something went wrong while loading the order details.");
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Order #<?= (int)$order["id"] ?></title>

  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/customer_dashboard.css">
  <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="account-page">

<?php include __DIR__ . "/partials/navigation.php"; ?>

<div class="dash-page">
  <div class="dash-frame">
    <div class="dash-grid">

      <aside class="dash-left">
        <div class="dash-hello">
          <h3>Hello <?= htmlspecialchars($userName) ?>,</h3>
          <p>Welcome back!</p>
        </div>

        <nav class="dash-menu">
          <a class="dash-link" href="customer_dashboard.php">
            <span class="dash-ico"><img src="assets/images/user.png" alt=""></span>
            <span>My Account</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link is-active" href="customer_orders.php">
            <span class="dash-ico"><img src="assets/images/shopping-bag-filled.png" alt=""></span>
            <span>Orders</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="customer_favourites.php">
            <span class="dash-ico"><img src="assets/images/favorite-shaded.png" alt=""></span>
            <span>Favourites</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="customer_messages.php">
            <span class="dash-ico"><img src="/images/message.png" alt=""></span>
            <span>Messages</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="customer_review.php">
            <span class="dash-ico"><img src="/images/reviews.png" alt=""></span>
            <span>Reviews</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="customer_security.php">
            <span class="dash-ico"><img src="assets/images/security-icon.png" alt=""></span>
            <span>Login &amp; Security</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="customer_logout.php">
            <span class="dash-ico"><img src="assets/images/settings.png" alt=""></span>
            <span>Logout</span>
            <span class="dash-arrow">›</span>
          </a>
        </nav>
      </aside>

      <main class="dash-right">

        <h1 class="dash-title">Order #<?= (int)$order["id"] ?></h1>
        <div class="dash-rule"></div>

        <section class="dash-recent">
          <div class="dash-recent-head">
            <div>
              <div class="dash-recent-title">Order Information</div>
              <div class="dash-recent-sub">Summary of your selected order</div>
            </div>
          </div>

          <div class="dash-recent-body">
            <div class="dash-row" style="grid-template-columns: 160px 160px 220px 1fr; align-items:center;">
              <div class="dash-cell">
                <strong>Order ID</strong><br>
                #<?= (int)$order["id"] ?>
              </div>

              <div class="dash-cell">
                <strong>Status</strong><br>
                <span class="dash-badge"><?= htmlspecialchars($order["status"]) ?></span>
              </div>

              <div class="dash-cell">
                <strong>Date</strong><br>
                <?= htmlspecialchars($order["created_at"]) ?>
              </div>

              <div class="dash-cell">
                <strong>Total</strong><br>
                £<?= number_format((float)$order["total_amount"], 2) ?>
              </div>
            </div>
          </div>
        </section>

        <section class="dash-recent" style="margin-top:20px;">
          <div class="dash-recent-head">
  <div style="width:100%;">
    <div class="dash-recent-title">Items in this Order</div>

    <div class="dash-row" style="grid-template-columns: 1fr 120px 100px 120px; align-items:start; font-weight:500; opacity:.75;">
      <div class="dash-cell">Product</div>
      <div class="dash-cell">Price</div>
      <div class="dash-cell">Quantity</div>
      <div class="dash-cell">Subtotal</div>
    </div>
  </div>
</div>

          <?php if (empty($orderItems)): ?>
            <div class="dash-empty">
              <p class="dash-muted">No items found for this order.</p>
            </div>
          <?php else: ?>
            <div class="dash-recent-body">
              <?php foreach ($orderItems as $item): ?>
<div class="dash-row" style="grid-template-columns: 1fr 120px 100px 120px; align-items:start;">                  <div class="dash-cell">
                    <strong><?= htmlspecialchars($item["name"]) ?></strong><br>
                    <span style="opacity:.8;font-size:11px;">
                      <?= htmlspecialchars($item["description"] ?? "") ?>
                    </span>
                  </div>

                  <div class="dash-cell">
                    £<?= number_format((float)$item["price"], 2) ?>
                  </div>

                  <div class="dash-cell">
                    <?= (int)$item["quantity"] ?>
                  </div>

                  <div class="dash-cell">
                    £<?= number_format((float)$item["price"] * (int)$item["quantity"], 2) ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </section>

        <div class="dash-bottom">
          <a class="dash-back" href="customer_orders.php">Back to orders</a>
          <a class="dash-back dash-secondary" href="products.php">Shop again</a>
        </div>

      </main>

    </div>
  </div>
</div>

</body>
</html>