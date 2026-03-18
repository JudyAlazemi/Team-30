<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$adminName  = $_SESSION["admin_name"] ?? "Admin";
$adminEmail = $_SESSION["admin_email"] ?? "";

$productReviews = [];
$siteReviews = [];

/* ---------------------------
   Product reviews
--------------------------- */
try {
    $checkReviews = $conn->query("SHOW TABLES LIKE 'reviews'");
    if ($checkReviews && $checkReviews->num_rows > 0) {
        $sql = "
            SELECT 
                r.id,
                r.rating,
                r.comment,
                r.created_at,
                r.product_id,
                u.name AS customer_name,
                u.email AS customer_email,
                p.name AS product_name
            FROM reviews r
            LEFT JOIN users u ON r.user_id = u.id
            LEFT JOIN products p ON r.product_id = p.id
            ORDER BY r.created_at DESC
        ";

        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $productReviews[] = $row;
            }
        }
    }
} catch (Exception $e) {
    $productReviews = [];
}

/* ---------------------------
   Site reviews
--------------------------- */
try {
    $checkSiteReviews = $conn->query("SHOW TABLES LIKE 'site_reviews'");
    if ($checkSiteReviews && $checkSiteReviews->num_rows > 0) {
        $sql = "
            SELECT 
                sr.id,
                sr.display_name,
                sr.rating,
                sr.comment,
                sr.created_at,
                u.name AS customer_name,
                u.email AS customer_email
            FROM site_reviews sr
            LEFT JOIN users u ON sr.user_id = u.id
            ORDER BY sr.created_at DESC
        ";

        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $siteReviews[] = $row;
            }
        }
    }
} catch (Exception $e) {
    $siteReviews = [];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manage Reviews</title>

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
          <a class="dash-link" href="admin_dashboard.php">
            <span class="dash-ico"><img src="assets/images/user.png" alt=""></span>
            <span>Dashboard</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_messages.php">
            <span class="dash-ico"><img src="assets/images/message.png" alt=""></span>
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

          <a class="dash-link is-active" href="admin_review.php">
            <span class="dash-ico"><img src="assets/images/reviews.png" alt=""></span>
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
        <h1 class="dash-title">Manage Reviews</h1>
        <hr class="dash-rule">

        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == '1'): ?>
          <div class="dash-msg success">Review deleted successfully.</div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] == '1'): ?>
          <div class="dash-msg error">Could not delete review.</div>
        <?php endif; ?>

        <!-- PRODUCT REVIEWS -->
        <div class="dash-section">
          <h2 class="dash-section-title">Product Reviews</h2>

          <?php if (empty($productReviews)): ?>
            <p class="dash-empty">No product reviews found.</p>
          <?php else: ?>
            <div class="dash-table-wrap">
              <table class="dash-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($productReviews as $review): ?>
                    <tr>
                      <td>#<?= (int)$review['id'] ?></td>
                      <td><?= htmlspecialchars($review['customer_name'] ?? 'Customer') ?></td>
                      <td><?= htmlspecialchars($review['customer_email'] ?? 'No email') ?></td>
                      <td><?= htmlspecialchars($review['product_name'] ?? ('Product #' . (int)$review['product_id'])) ?></td>
                      <td>
                        <span class="review-stars">
                          <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= (int)$review['rating'] ? '★' : '☆' ?>
                          <?php endfor; ?>
                        </span>
                      </td>
                      <td class="review-comment"><?= htmlspecialchars($review['comment']) ?></td>
                      <td><?= htmlspecialchars($review['created_at']) ?></td>
                      <td class="dash-actions">
                        <form action="delete_review.php" method="POST" onsubmit="return confirm('Delete this product review?');">
                          <input type="hidden" name="review_id" value="<?= (int)$review['id'] ?>">
                          <input type="hidden" name="review_type" value="product">
                          <button type="submit" class="dash-danger-btn">Delete</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <!-- SITE REVIEWS -->
        <div class="dash-section">
          <h2 class="dash-section-title">Site Reviews</h2>

          <?php if (empty($siteReviews)): ?>
            <p class="dash-empty">No site reviews found.</p>
          <?php else: ?>
            <div class="dash-table-wrap">
              <table class="dash-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Display Name</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($siteReviews as $review): ?>
                    <tr>
                      <td>#<?= (int)$review['id'] ?></td>
                      <td><?= htmlspecialchars(!empty($review['display_name']) ? $review['display_name'] : 'Customer') ?></td>
                      <td><?= htmlspecialchars($review['customer_name'] ?? 'Customer') ?></td>
                      <td><?= htmlspecialchars($review['customer_email'] ?? 'No email') ?></td>
                      <td><span class="review-type-badge">Website</span></td>
                      <td>
                        <span class="review-stars">
                          <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= (int)$review['rating'] ? '★' : '☆' ?>
                          <?php endfor; ?>
                        </span>
                      </td>
                      <td class="review-comment"><?= htmlspecialchars($review['comment']) ?></td>
                      <td><?= htmlspecialchars($review['created_at']) ?></td>
                      <td class="dash-actions">
                        <form action="delete_review.php" method="POST" onsubmit="return confirm('Delete this site review?');">
                          <input type="hidden" name="review_id" value="<?= (int)$review['id'] ?>">
                          <input type="hidden" name="review_type" value="site">
                          <button type="submit" class="dash-danger-btn">Delete</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

      </section>
    </div>
  </div>
</main>

</body>
</html>