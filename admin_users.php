<?php
session_start();
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$adminName = $_SESSION["admin_name"] ?? "Admin";
$search = trim($_GET['search'] ?? '');
$message = $_GET['msg'] ?? '';
$customers = [];

try {
    if ($search !== '') {
        $stmt = $conn->prepare("
            SELECT id, name, email, created_at
            FROM users
            WHERE name LIKE CONCAT('%', ?, '%')
               OR email LIKE CONCAT('%', ?, '%')
            ORDER BY id DESC
        ");
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }

        $stmt->close();
    } else {
        $result = $conn->query("
            SELECT id, name, email, created_at
            FROM users
            ORDER BY id DESC
        ");

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $customers[] = $row;
            }
        }
    }
} catch (Exception $e) {
    $customers = [];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manage Customers</title>

  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/admin_dashboard.css?v=<?= time() ?>">
  <link rel="stylesheet" href="assets/css/darkmode.css">
  <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="account-page">

<?php include __DIR__ . "/partials/navigation.php"; ?>

<main class="dash-page">
  <div class="dash-frame">
    <div class="dash-grid">

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
            <span class="dash-ico"><img src="/images/message.png" alt=""></span>
            <span>Customer Messages</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_orders.php">
            <span class="dash-ico"><img src="assets/images/processorder.png" alt=""></span>
            <span>Process Orders</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link is-active" href="admin_users.php">
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

      <section class="dash-right">
        <h1 class="dash-title">Customers</h1>
        <hr class="dash-rule">

        <div class="dash-section">
          <form method="GET" class="admin-search-form">
            <input
              type="text"
              name="search"
              class="admin-search-input"
              placeholder="Search by name or email"
              value="<?= htmlspecialchars($search) ?>"
            >
            <button type="submit" class="dash-back">Search</button>
            <a href="admin_users.php" class="dash-secondary-btn">Reset</a>
          </form>
        </div>

        <?php if ($message === 'deleted'): ?>
            <div class="admin-alert success-alert">Customer deleted successfully.</div>
        <?php elseif ($message === 'has_orders'): ?>
            <div class="admin-alert error-alert">This customer cannot be deleted because they already have orders.</div>
        <?php elseif ($message === 'invalid'): ?>
            <div class="admin-alert error-alert">Invalid customer selected.</div>
        <?php elseif ($message === 'error'): ?>
            <div class="admin-alert error-alert">Something went wrong while deleting the customer.</div>
        <?php endif; ?>

        <div class="dash-section">
          <h2 class="dash-section-title">All Customers</h2>

          <?php if (empty($customers)): ?>
            <p class="dash-empty">No customers found.</p>
          <?php else: ?>
            <div class="dash-table-wrap">
              <table class="dash-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($customers as $customer): ?>
                    <tr>
                      <td>#<?= (int)$customer['id'] ?></td>
                      <td><?= htmlspecialchars($customer['name']) ?></td>
                      <td><?= htmlspecialchars($customer['email']) ?></td>
                      <td><?= htmlspecialchars($customer['created_at']) ?></td>
                      <td class="dash-actions">
                        <a href="edit_customer.php?id=<?= (int)$customer['id'] ?>" class="dash-back">Edit</a>

                        <form action="delete_customer.php" method="POST" onsubmit="return confirm('Delete this customer?');">
                          <input type="hidden" name="user_id" value="<?= (int)$customer['id'] ?>">
                          <button type="submit" class="dash-secondary-btn">Delete</button>
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