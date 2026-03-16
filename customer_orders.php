<?php
require_once __DIR__ . "/includes/account_layout.php";
$active = "orders";

/* Fetch orders for this user */
$orders = [];
try {
  $stmt = $conn->prepare("
    SELECT id, total_amount, status, created_at
    FROM orders
    WHERE user_id = ?
    ORDER BY created_at DESC
  ");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
} catch (Exception $e) {}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>My Orders</title>

  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/customer_dashboard.css?v=<?= time() ?>">
  <link rel="stylesheet" href="assets/css/darkmode.css">
  <script defer src="assets/js/nav.js"></script>
  <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="account-page">

<header class="topbar">
  <div class="topbar-inner">

    <button class="icon-btn menu-toggle" aria-label="Open menu" aria-expanded="false">
      <img class="icon icon--menu" src="assets/images/menu.png" alt="" />
      <img class="icon icon--close" src="assets/images/close.png" alt="" />
    </button>

    <a class="brand" href="index.php">
      <img class="brand-logo" src="assets/images/logo.png" alt="Sabil" />
    </a>

    <nav class="actions" aria-label="Account & tools">
      <a href="customer_dashboard.php" class="my-account-link">
        <img class="icon" src="assets/images/user.png" alt="My Account">
        <span>My Account</span>
      </a>

      <a href="logout.php">Logout</a>

      <a id="searchBtn" class="action" href="#">
        <img class="icon" src="assets/images/search.png" alt="Search">
      </a>

      <a id="favBtn" class="action" href="customer_favourites.php">
        <img class="icon" src="assets/images/favorite.png" alt="Favourites">
      </a>

      <a id="bagBtn" class="action" href="basket.php">
        <img class="icon" src="assets/images/shopping-bag.png" alt="Bag">
      </a>
    </nav>
  </div>
</header>

<div id="menuDrawer" class="drawer" aria-hidden="true">
  <div class="drawer__backdrop" data-close-drawer></div>
  <aside class="drawer__panel" role="dialog" aria-modal="true" aria-label="Site menu">
    <nav class="drawer__nav">
      <a href="products.php">Shop all</a>
      <a href="cart.php">Cart</a>
      <a href="customer_favourites.php">Favourites</a>
      <a href="contactus.php">Contact us</a>
      <a href="faq.php">FAQ</a>
      <a href="aboutus.php">About us</a>
      <a href="terms.php">Terms</a>
      <a href="privacypolicy.php">Privacy Policy</a>
    </nav>
  </aside>
</div>

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

          <a class="dash-link" href="customer_security.php">
            <span class="dash-ico"><img src="assets/images/security-icon.png" alt=""></span>
            <span>Login &amp; Security</span>
            <span class="dash-arrow">›</span>
          </a>
        </nav>
      </aside>

      <main class="dash-right">

        <h1 class="dash-title">My Orders</h1>
        <div class="dash-rule"></div>

        <section class="dash-recent">
          <div class="dash-recent-head">
            <div>
              <div class="dash-recent-title">Order History</div>
              <div class="dash-recent-sub">Order&nbsp;&nbsp;Total&nbsp;&nbsp;Status&nbsp;&nbsp;Date&nbsp;&nbsp;Action</div>
            </div>
          </div>

          <?php if (empty($orders)): ?>
            <div class="dash-empty">
              <p class="dash-muted">You haven’t placed any orders yet.</p>
              <a href="cart.php" class="dash-place-btn">Place an order</a>
            </div>
          <?php else: ?>
            <div class="dash-recent-body">
              <?php foreach ($orders as $o): ?>
                <?php $status = strtolower(trim($o['status'])); ?>

                <div class="dash-row" style="grid-template-columns: 100px 140px 150px 210px 160px; align-items:center;">
                  
                  <div class="dash-cell">
                    #<?= htmlspecialchars($o['id']) ?>
                  </div>

                  <div class="dash-cell">
                    £<?= number_format((float)$o['total_amount'], 2) ?>
                  </div>

                  <div class="dash-cell">
                    <span class="dash-badge"><?= htmlspecialchars($o['status']) ?></span>
                  </div>

                  <div class="dash-cell">
                    <?= htmlspecialchars($o['created_at']) ?>
                  </div>

                  <div class="dash-cell">
                    <?php if (in_array($status, ['processing', 'delivered'])): ?>
                      <form method="POST" action="return_order.php" style="margin:0;">
                        <input type="hidden" name="order_id" value="<?= (int)$o['id'] ?>">
                        <button type="submit" class="dash-back dash-secondary">Return</button>
                      </form>

                    <?php elseif ($status === 'return_pending'): ?>
                      <span class="dash-muted">Return Applied</span>

                    <?php elseif ($status === 'returned'): ?>
                      <span class="dash-muted">Returned</span>

                    <?php else: ?>
                      <span class="dash-muted">-</span>
                    <?php endif; ?>
                  </div>

                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </section>

        <div class="dash-bottom">
          <a class="dash-back" href="customer_dashboard.php">Back to dashboard</a>
          <a class="dash-back dash-secondary" href="products.php">Place More Orders</a>
        </div>

      </main>

    </div>
  </div>
</div>

</body>
</html>