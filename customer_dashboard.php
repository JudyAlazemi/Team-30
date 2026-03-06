<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include account layout - FIXED PATH
require_once __DIR__ . "/includes/account_layout.php";
$active = "dashboard";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Set user data from session with proper fallbacks
$userId = $_SESSION['user_id'] ?? 0;
$userName = $_SESSION['user_name'] ?? 'Customer';
$userEmail = $_SESSION['user_email'] ?? ''; // Get email from session
$userPhone = $_SESSION['user_phone'] ?? 'Not added';

// Initialize variables
$totalOrders = 0;
$pendingOrders = 0;
$totalSpent = 0.0;
$favCount = 0; // Initialize favorites count
$recentOrders = [];

try {
    // Check if database connection exists
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Database connection failed");
    }

    // Get total orders count
    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM orders WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $totalOrders = (int)($result->fetch_assoc()['c'] ?? 0);
        $stmt->close();
    }

    // Get pending orders count
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS c 
        FROM orders 
        WHERE user_id = ? 
          AND LOWER(status) IN ('pending','processing')
    ");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $pendingOrders = (int)($result->fetch_assoc()['c'] ?? 0);
        $stmt->close();
    }

    // Get total spent
    $stmt = $conn->prepare("SELECT COALESCE(SUM(total_amount),0) AS s FROM orders WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $totalSpent = (float)($result->fetch_assoc()['s'] ?? 0);
        $stmt->close();
    }

    // ✅ Get favourites count (table name is `favourites`)
    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM favourites WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $favCount = (int)($result->fetch_assoc()['c'] ?? 0);
        $stmt->close();
    }

    // Get recent orders
    $stmt = $conn->prepare("SELECT id, total_amount, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $recentOrders = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

} catch (Exception $e) {
    // Log error for debugging
    error_log("Dashboard error: " . $e->getMessage());
}

// Final fallbacks for email and phone
$userEmail = !empty($userEmail) ? $userEmail : ($_SESSION['user_email'] ?? '');
$userPhone = !empty($userPhone) ? $userPhone : ($_SESSION['user_phone'] ?? 'Not added');

// If email is still empty, try to fetch from database
if (empty($userEmail) && $userId > 0 && isset($conn) && !$conn->connect_error) {
    try {
        $stmt = $conn->prepare("SELECT email FROM users WHERE id = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $userEmail = $row['email'];
                // Update session for future requests
                $_SESSION['user_email'] = $userEmail;
            }
            $stmt->close();
        }
    } catch (Exception $e) {
        error_log("Error fetching user email: " . $e->getMessage());
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Customer Dashboard</title>

 
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/customer_dashboard.css?v=<?= time() ?>">


  <script defer src="assets/js/nav.js"></script>

  <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="account-page">

<header class="topbar">
  <div class="topbar-inner">

    <!-- Left: Menu -->
    <button class="icon-btn menu-toggle" aria-label="Open menu" aria-expanded="false">
      <img class="icon icon--menu" src="assets/images/menu.png" alt="" />
      <img class="icon icon--close" src="assets/images/close.png" alt="" />
    </button>

    <!-- Center: Logo -->
    <a class="brand" href="index.php">
      <img class="brand-logo" src="assets/images/logo.png" alt="Sabil" />
    </a>

    <!-- Right: actions -->
    <nav class="actions" aria-label="Account & tools">

      <!-- USER (Sign in / My Account etc.) -->
      <?php include __DIR__ . "/partials/navbar.php"; ?>

      <!-- Search -->
      <div class="search-group">
        <a id="searchBtn" class="action" href="#">
          <img class="icon" src="assets/images/search.png" alt="Search" />
        </a>
        <input
          type="text"
          id="navSearchInput"
          class="nav-search-input"
          placeholder="Search..."
        />
      </div>

      <!-- Favourite -->
      <a id="favBtn" class="action" href="customer_favourites.php">
        <img id="favIcon" class="icon" src="assets/images/favorite.png" alt="Favourite" />
      </a>

      <!-- Bag -->
      <a id="bagBtn" class="action" href="cart.php" role="button" aria-pressed="false">
        <img
          id="bagIcon"
          class="icon"
          src="assets/images/shopping-bag.png"
          alt="Shopping bag"
          data-src-inactive="assets/images/shopping-bag.png"
          data-src-active="assets/images/shopping-bag-filled.png"
        />
      </a>
    </nav>
  </div>
</header>

<!-- ✅ SAME MENU DRAWER -->
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

<!-- ✅ DASHBOARD (EXACT INSPO LAYOUT) -->
<div class="dash-page">

  <div class="dash-frame">

    <div class="dash-grid">

      <!-- LEFT COLUMN -->
      <aside class="dash-left">

        <div class="dash-hello">
          <h3>Hello <?= htmlspecialchars($userName) ?>,</h3>
          <p>Welcome back!</p>
        </div>

        <nav class="dash-menu">

          <a class="dash-link is-active" href="customer_dashboard.php">
            <span class="dash-ico">
              <img src="assets/images/user.png" alt="">
            </span>
            <span>My Account</span>
            <span class="dash-arrow" aria-hidden="true">›</span>
          </a>

          <a class="dash-link" href="customer_orders.php">
            <span class="dash-ico">
              <img src="assets/images/shopping-bag-filled.png" alt="">
            </span>
            <span>Orders</span>
            <span class="dash-arrow" aria-hidden="true">›</span>
          </a>

          <a class="dash-link" href="customer_favourites.php">
            <span class="dash-ico">
              <img src="assets/images/favorite-shaded.png" alt="">
            </span>
            <span>Favourites</span>
            <span class="dash-arrow" aria-hidden="true">›</span>
          </a>

          <a class="dash-link" href="customer_security.php">
            <span class="dash-ico">
              <img src="assets/images/security-icon.png" alt="">
            </span>
            <span>Login &amp; Security</span>
            <span class="dash-arrow" aria-hidden="true">›</span>
          </a>

        </nav>

      </aside>

      <!-- RIGHT COLUMN -->
      <main class="dash-right">

        <h1 class="dash-title">Hi <?= htmlspecialchars($userName) ?>,</h1>
        <div class="dash-rule"></div>

        <!-- Stats row (3 small bars) -->
        <div class="dash-bars">
          <div class="dash-bar">
            <div class="dash-bar-label">Total Orders</div>
            <div class="dash-bar-value"><?= (int)$totalOrders ?></div>
          </div>

          <div class="dash-bar">
            <div class="dash-bar-label">Pending Orders</div>
            <div class="dash-bar-value"><?= (int)$pendingOrders ?></div>
          </div>

          <div class="dash-bar">
            <div class="dash-bar-label">Favourites</div>
            <div class="dash-bar-value"><?= (int)$favCount ?></div>
          </div>
        </div>

        <!-- Total spent (long bar) -->
        <div class="dash-long">
          <div class="dash-long-label">Total Spent</div>
          <div class="dash-long-value">£<?= number_format((float)$totalSpent, 2) ?></div>
        </div>

        <!-- Email + phone bars -->
        <div class="dash-two">
          <div class="dash-field">
            <div class="dash-field-label">Email</div>
            <div class="dash-field-value"><?= htmlspecialchars($userEmail) ?></div>
          </div>

          <div class="dash-field">
            <div class="dash-field-label">Phone Number</div>
            <div class="dash-field-value"><?= htmlspecialchars($userPhone) ?></div>
          </div>
        </div>

        <!-- Recent Orders (exact look: title left + view all right, then table text style) -->
        <section class="dash-recent">
          <div class="dash-recent-head">
            <div>
              <div class="dash-recent-title">Recent Orders</div>
              <div class="dash-recent-sub">Order&nbsp;&nbsp;Total&nbsp;&nbsp;Status&nbsp;&nbsp;Date</div>
            </div>

            <a class="dash-viewall" href="customer_orders.php">View all</a>
          </div>

          <div class="dash-recent-body">
            <?php if (empty($recentOrders)): ?>
              <div class="dash-muted">No orders yet</div>
            <?php else: ?>
              <?php foreach ($recentOrders as $o): ?>
                <div class="dash-row">
                  <div class="dash-cell">#<?= htmlspecialchars($o['id']) ?></div>
                  <div class="dash-cell">£<?= number_format((float)$o['total_amount'], 2) ?></div>
                  <div class="dash-cell">
                    <span class="dash-badge"><?= htmlspecialchars($o['status']) ?></span>
                  </div>
                  <div class="dash-cell"><?= htmlspecialchars($o['created_at']) ?></div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </section>

        <div class="dash-bottom">
          <a class="dash-back" href="index.php">Back to home</a>
        </div>

      </main>
    </div>
  </div>
</div>


<script>
// Force the styles to apply by removing any inline conflicts
document.addEventListener('DOMContentLoaded', function() {
  // Log to confirm CSS is loaded
  console.log('Dashboard CSS loaded');
  
  // Add status colors to badges
  const badges = document.querySelectorAll('.dash-badge');
  badges.forEach(badge => {
    const status = badge.textContent.trim().toLowerCase();
    if (status === 'pending' || status === 'processing') {
      badge.style.background = '#fff3e0';
      badge.style.borderColor = '#e6b87e';
    } else if (status === 'completed' || status === 'delivered') {
      badge.style.background = '#e3f1e3';
      badge.style.borderColor = '#8fb08c';
    }
  });
});
</script>

</body>
</html>