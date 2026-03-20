<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$adminId    = (int)($_SESSION["admin_id"] ?? 0);
$adminName  = $_SESSION["admin_name"] ?? "Admin";
$adminEmail = $_SESSION["admin_email"] ?? "";

/* ---------------------------
   Dashboard counts
--------------------------- */
$totalCustomers       = 0;
$totalProducts        = 0;
$totalOrders          = 0;
$lowStockCount        = 0;
$pendingAdminCount    = 0;
$messageCount         = 0;
$reviewCount          = 0;
$incomingOrdersCount  = 0;
$returnRequestsCount  = 0;

try {
    $result = $conn->query("SELECT COUNT(*) AS total FROM users");
    if ($result && $row = $result->fetch_assoc()) {
        $totalCustomers = (int)$row['total'];
    }
} catch (Exception $e) {}

try {
    $result = $conn->query("SELECT COUNT(*) AS total FROM products");
    if ($result && $row = $result->fetch_assoc()) {
        $totalProducts = (int)$row['total'];
    }
} catch (Exception $e) {}

try {
    $result = $conn->query("SELECT COUNT(*) AS total FROM orders");
    if ($result && $row = $result->fetch_assoc()) {
        $totalOrders = (int)$row['total'];
    }
} catch (Exception $e) {}

try {
    $result = $conn->query("SELECT COUNT(*) AS total FROM products WHERE stock <= 5");
    if ($result && $row = $result->fetch_assoc()) {
        $lowStockCount = (int)$row['total'];
    }
} catch (Exception $e) {}

try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM admins WHERE approval_status = 'pending'");
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            $pendingAdminCount = (int)$row['total'];
        }
        $stmt->close();
    }
} catch (Exception $e) {}

/* ---------------------------
   Customer messages count
--------------------------- */
try {
    $checkMessagesTable = $conn->query("SHOW TABLES LIKE 'customer_messages'");
    if ($checkMessagesTable && $checkMessagesTable->num_rows > 0) {
        $result = $conn->query("SELECT COUNT(*) AS total FROM customer_messages");
        if ($result && $row = $result->fetch_assoc()) {
            $messageCount = (int)$row['total'];
        }
    }
} catch (Exception $e) {}

/* ---------------------------
   Reviews count
--------------------------- */
try {
    $productReviewCount = 0;
    $siteReviewCount = 0;

    $checkReviewsTable = $conn->query("SHOW TABLES LIKE 'reviews'");
    if ($checkReviewsTable && $checkReviewsTable->num_rows > 0) {
        $result = $conn->query("SELECT COUNT(*) AS total FROM reviews");
        if ($result && $row = $result->fetch_assoc()) {
            $productReviewCount = (int)$row['total'];
        }
    }

    $checkSiteReviewsTable = $conn->query("SHOW TABLES LIKE 'site_reviews'");
    if ($checkSiteReviewsTable && $checkSiteReviewsTable->num_rows > 0) {
        $result = $conn->query("SELECT COUNT(*) AS total FROM site_reviews");
        if ($result && $row = $result->fetch_assoc()) {
            $siteReviewCount = (int)$row['total'];
        }
    }

    $reviewCount = $productReviewCount + $siteReviewCount;
} catch (Exception $e) {}

/* ---------------------------
   Incoming orders count
--------------------------- */
try {
    $checkIncomingTable = $conn->query("SHOW TABLES LIKE 'incoming_orders'");
    if ($checkIncomingTable && $checkIncomingTable->num_rows > 0) {
        $result = $conn->query("SELECT COUNT(*) AS total FROM incoming_orders");
        if ($result && $row = $result->fetch_assoc()) {
            $incomingOrdersCount = (int)$row['total'];
        }
    }
} catch (Exception $e) {}

/* ---------------------------
   Return requests count
--------------------------- */
try {
    $result = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE order_status = 'return_requested'");
    if ($result && $row = $result->fetch_assoc()) {
        $returnRequestsCount = (int)$row['total'];
    }
} catch (Exception $e) {}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard</title>

  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/admin_dashboard.css?v=<?= time() ?>">
  <link rel="stylesheet" href="assets/css/darkmode.css">
  <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="account-page">

<?php
$forceIsAdmin = true;
$forceIsUser = false;
include __DIR__ . "/partials/navigation.php";
?>

<main class="dash-page">
  <div class="dash-frame">
    <div class="dash-grid">

      <!-- LEFT -->
      <aside class="dash-left">
        <div class="dash-hello">
          <h3>Hello <?= htmlspecialchars($adminName) ?>,</h3>
          <p>Welcome back!</p>
        </div>

        <nav class="dash-menu">
          <a class="dash-link is-active" href="admin_dashboard.php">
            <span class="dash-ico"><img src="assets/images/user.png" alt=""></span>
            <span>Dashboard</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_messages.php">
            <span class="dash-ico"><img src="/images/message.png" alt=""></span>
            <span>Customer Messages</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_orders.php">
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

      <!-- RIGHT -->
      <section class="dash-right">
        <h1 class="dash-title">Admin Dashboard</h1>
        <hr class="dash-rule">

        <div class="dash-bars">
          <div class="dash-bar">
            <div class="dash-bar-label">Customers</div>
            <div class="dash-bar-value"><?= $totalCustomers ?></div>
          </div>

          <div class="dash-bar">
            <div class="dash-bar-label">Products</div>
            <div class="dash-bar-value"><?= $totalProducts ?></div>
          </div>

          <div class="dash-bar">
            <div class="dash-bar-label">Orders</div>
            <div class="dash-bar-value"><?= $totalOrders ?></div>
          </div>

          <div class="dash-bar">
            <div class="dash-bar-label">Messages</div>
            <div class="dash-bar-value"><?= $messageCount ?></div>
          </div>

          <div class="dash-bar">
            <div class="dash-bar-label">Reviews</div>
            <div class="dash-bar-value"><?= $reviewCount ?></div>
          </div>

          <div class="dash-bar">
            <div class="dash-bar-label">Incoming Orders</div>
            <div class="dash-bar-value"><?= $incomingOrdersCount ?></div>
          </div>
        </div>

        <div class="dash-long">
          <div class="dash-long-label">Admin Email</div>
          <div class="dash-long-value"><?= htmlspecialchars($adminEmail) ?></div>
        </div>

        <div class="dash-two">
          <div class="dash-field">
            <div class="dash-field-label">Low Stock Alerts</div>
            <div class="dash-field-value"><?= $lowStockCount ?></div>
          </div>

          <div class="dash-field">
            <div class="dash-field-label">Pending Admin Approvals</div>
            <div class="dash-field-value"><?= $pendingAdminCount ?></div>
          </div>

          <div class="dash-field">
            <div class="dash-field-label">Return Requests</div>
            <div class="dash-field-value"><?= $returnRequestsCount ?></div>
          </div>
        </div>

      </section>
    </div>
  </div>
</main>

</body>
</html>