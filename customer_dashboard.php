<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    require_once __DIR__ . "/backend/config/session.php";
}

// Include account layout
require_once __DIR__ . "/includes/account_layout.php";
$active = "dashboard";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Set user data from session with proper fallbacks
$userId = (int)($_SESSION['user_id'] ?? 0);
$userName = $_SESSION['user_name'] ?? 'Customer';
$userEmail = $_SESSION['user_email'] ?? '';
$userPhone = $_SESSION['user_phone'] ?? 'Not added';

// Initialize variables
$totalOrders = 0;
$pendingOrders = 0;
$totalSpent = 0.0;
$favCount = 0;
$messageCount = 0;
$reviewCount = 0;
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

    // Get favourites count
    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM favourites WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $favCount = (int)($result->fetch_assoc()['c'] ?? 0);
        $stmt->close();
    }

    // Get customer messages count
    $checkMessagesTable = $conn->query("SHOW TABLES LIKE 'customer_messages'");
    if ($checkMessagesTable && $checkMessagesTable->num_rows > 0) {
        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM customer_messages WHERE user_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $messageCount = (int)($result->fetch_assoc()['c'] ?? 0);
            $stmt->close();
        }
    }

    // Get total reviews count = product reviews + site reviews
    $productReviewCount = 0;
    $siteReviewCount = 0;

    $checkReviewsTable = $conn->query("SHOW TABLES LIKE 'reviews'");
    if ($checkReviewsTable && $checkReviewsTable->num_rows > 0) {
        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM reviews WHERE user_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $productReviewCount = (int)($result->fetch_assoc()['c'] ?? 0);
            $stmt->close();
        }
    }

    $checkSiteReviewsTable = $conn->query("SHOW TABLES LIKE 'site_reviews'");
    if ($checkSiteReviewsTable && $checkSiteReviewsTable->num_rows > 0) {
        $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM site_reviews WHERE user_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $siteReviewCount = (int)($result->fetch_assoc()['c'] ?? 0);
            $stmt->close();
        }
    }

    $reviewCount = $productReviewCount + $siteReviewCount;

    // Get recent orders
    $stmt = $conn->prepare("
        SELECT id, total_amount, status, created_at
        FROM orders
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 5
    ");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $recentOrders = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

} catch (Exception $e) {
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
  <link rel="stylesheet" href="assets/css/darkmode.css">

  <script defer src="assets/js/nav.js"></script>

  <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="account-page">

<?php include __DIR__ . "/partials/navigation.php"; ?>

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

          <a class="dash-link" href="customer_messages.php">
            <span class="dash-ico"><img src="assets/images/message.png" alt=""></span>
            <span>Messages</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="customer_review.php">
            <span class="dash-ico"><img src="assets/images/reviews.png" alt=""></span>
            <span>Reviews</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="customer_security.php">
            <span class="dash-ico">
              <img src="assets/images/security-icon.png" alt="">
            </span>
            <span>Login &amp; Security</span>
            <span class="dash-arrow" aria-hidden="true">›</span>
          </a>

          <a class="dash-link" href="customer_logout.php">
            <span class="dash-ico">
              <img src="assets/images/settings.png" alt="">
            </span>
            <span>Logout</span>
            <span class="dash-arrow">›</span>
          </a>

        </nav>

      </aside>

      <!-- RIGHT COLUMN -->
      <main class="dash-right">

        <div class="dash-rule"></div>

        <!-- Stats row -->
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

          <div class="dash-bar">
            <div class="dash-bar-label">Messages</div>
            <div class="dash-bar-value"><?= (int)$messageCount ?></div>
          </div>

          <div class="dash-bar">
            <div class="dash-bar-label">Reviews</div>
            <div class="dash-bar-value"><?= (int)$reviewCount ?></div>
          </div>
        </div>

        <!-- Total spent -->
        <div class="dash-long">
          <div class="dash-long-label">Total Spent</div>
          <div class="dash-long-value">£<?= number_format((float)$totalSpent, 2) ?></div>
        </div>

        <!-- Email + phone -->
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

        <!-- Recent Orders -->
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
document.addEventListener('DOMContentLoaded', function() {
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