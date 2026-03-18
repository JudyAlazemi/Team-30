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
$totalCustomers = 0;
$totalProducts  = 0;
$totalOrders    = 0;
$lowStockCount  = 0;
$pendingAdminCount = 0;

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
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        $pendingAdminCount = (int)$row['total'];
    }
    $stmt->close();
} catch (Exception $e) {}

/* ---------------------------
   Pending admin approvals
--------------------------- */
$pendingAdmins = [];
try {
    $stmt = $conn->prepare("
        SELECT id, name, email, created_at
        FROM admins
        WHERE approval_status = 'pending'
        ORDER BY created_at ASC
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pendingAdmins[] = $row;
    }
    $stmt->close();
} catch (Exception $e) {}

/* ---------------------------
   Recent customer orders
--------------------------- */
$recentOrders = [];
try {
    $sql = "
        SELECT o.id, o.total_amount, o.order_status, o.created_at,
               u.name AS customer_name
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC
        LIMIT 5
    ";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $recentOrders[] = $row;
        }
    }
} catch (Exception $e) {}

/* ---------------------------
   Customers
--------------------------- */
$customers = [];
try {
    $sql = "
        SELECT id, name, email, created_at
        FROM users
        ORDER BY id DESC
        LIMIT 5
    ";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
    }
} catch (Exception $e) {}

/* ---------------------------
   Products
--------------------------- */
$products = [];
try {
    $sql = "
        SELECT id, name, price, stock
        FROM products
        ORDER BY id DESC
        LIMIT 5
    ";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
} catch (Exception $e) {}

/* ---------------------------
   Low stock alerts
--------------------------- */
$lowStockProducts = [];
try {
    $sql = "
        SELECT id, name, stock
        FROM products
        WHERE stock <= 5
        ORDER BY stock ASC, name ASC
        LIMIT 5
    ";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $lowStockProducts[] = $row;
        }
    }
} catch (Exception $e) {}

/* ---------------------------
   Return requests
--------------------------- */
$returnRequests = [];
try {
    $sql = "
        SELECT o.id, o.total_amount, o.created_at, o.order_status,
               u.name AS customer_name
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.order_status = 'return_requested'
        ORDER BY o.created_at DESC
        LIMIT 5
    ";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $returnRequests[] = $row;
        }
    }
} catch (Exception $e) {}

/* ---------------------------
   Incoming stock orders
--------------------------- */
$incomingOrders = [];
try {
    $sql = "
        SELECT io.id, io.product_name, io.quantity, io.status, io.created_at
        FROM incoming_orders io
        ORDER BY io.created_at DESC
        LIMIT 5
    ";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $incomingOrders[] = $row;
        }
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
          <h3>Hello,</h3>
          <p><?= htmlspecialchars($adminName) ?></p>
        </div>

        <nav class="dash-menu">
          <a class="dash-link is-active" href="admin_dashboard.php">
            <span class="dash-ico"><img src="assets/images/user.png" alt=""></span>
            <span>Dashboard</span>
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
        </div>

        <div class="dash-long">
          <div class="dash-long-label">Admin email</div>
          <div class="dash-long-value"><?= htmlspecialchars($adminEmail) ?></div>
        </div>

        <div class="dash-two">
          <div class="dash-field">
            <div class="dash-field-label">Low stock alerts</div>
            <div class="dash-field-value"><?= $lowStockCount ?></div>
          </div>

          <div class="dash-field">
            <div class="dash-field-label">Pending admin approvals</div>
            <div class="dash-field-value"><?= $pendingAdminCount ?></div>
          </div>
        </div>

        <div class="dash-section">
          <h2 class="dash-section-title">Pending Admin Approvals</h2>
          <?php if (empty($pendingAdmins)): ?>
            <p class="dash-empty">No pending admin approval requests.</p>
          <?php else: ?>
            <div class="dash-table-wrap">
              <table class="dash-table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Requested</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($pendingAdmins as $admin): ?>
                    <tr>
                      <td><?= htmlspecialchars($admin['name']) ?></td>
                      <td><?= htmlspecialchars($admin['email']) ?></td>
                      <td><?= htmlspecialchars($admin['created_at']) ?></td>
                      <td class="dash-actions">
                        <form action="approve_admin.php" method="POST">
                          <input type="hidden" name="admin_id" value="<?= (int)$admin['id'] ?>">
                          <button type="submit" class="dash-back">Approve</button>
                        </form>

                        <form action="reject_admin.php" method="POST">
                          <input type="hidden" name="admin_id" value="<?= (int)$admin['id'] ?>">
                          <button type="submit" class="dash-secondary-btn">Reject</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <div class="dash-cards-two">
          <div class="dash-section">
            <h2 class="dash-section-title">Recent Orders</h2>
            <?php if (empty($recentOrders)): ?>
              <p class="dash-empty">No recent orders found.</p>
            <?php else: ?>
              <div class="dash-table-wrap">
                <table class="dash-table">
                  <thead>
                    <tr>
                      <th>Order</th>
                      <th>Customer</th>
                      <th>Status</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                      <tr>
                        <td>#<?= (int)$order['id'] ?></td>
                        <td><?= htmlspecialchars($order['customer_name'] ?? 'Customer') ?></td>
                        <td><?= htmlspecialchars($order['order_status']) ?></td>
                        <td>£<?= number_format((float)$order['total_amount'], 2) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>

          <div class="dash-section">
            <h2 class="dash-section-title">Customer Details</h2>
            <?php if (empty($customers)): ?>
              <p class="dash-empty">No customers found.</p>
            <?php else: ?>
              <div class="dash-table-wrap">
                <table class="dash-table">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Joined</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($customers as $customer): ?>
                      <tr>
                        <td><?= htmlspecialchars($customer['name']) ?></td>
                        <td><?= htmlspecialchars($customer['email']) ?></td>
                        <td><?= htmlspecialchars($customer['created_at']) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="dash-cards-two">
          <div class="dash-section">
            <h2 class="dash-section-title">Inventory</h2>
            <?php if (empty($products)): ?>
              <p class="dash-empty">No products found.</p>
            <?php else: ?>
              <div class="dash-table-wrap">
                <table class="dash-table">
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th>Price</th>
                      <th>Stock</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($products as $product): ?>
                      <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>£<?= number_format((float)$product['price'], 2) ?></td>
                        <td><?= (int)$product['stock'] ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>

          <div class="dash-section">
            <h2 class="dash-section-title">Low Stock Alerts</h2>
            <?php if (empty($lowStockProducts)): ?>
              <p class="dash-empty">No low stock products right now.</p>
            <?php else: ?>
              <ul class="dash-alerts">
                <?php foreach ($lowStockProducts as $item): ?>
                  <li>
                    <span><?= htmlspecialchars($item['name']) ?></span>
                    <strong><?= (int)$item['stock'] ?> left</strong>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>

        <div class="dash-cards-two">
          <div class="dash-section">
            <h2 class="dash-section-title">Incoming Orders</h2>
            <?php if (empty($incomingOrders)): ?>
              <p class="dash-empty">No incoming stock orders found.</p>
            <?php else: ?>
              <div class="dash-table-wrap">
                <table class="dash-table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Product</th>
                      <th>Qty</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($incomingOrders as $incoming): ?>
                      <tr>
                        <td>#<?= (int)$incoming['id'] ?></td>
                        <td><?= htmlspecialchars($incoming['product_name']) ?></td>
                        <td><?= (int)$incoming['quantity'] ?></td>
                        <td><?= htmlspecialchars($incoming['status']) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>

          <div class="dash-section">
            <h2 class="dash-section-title">Return Requests</h2>
            <?php if (empty($returnRequests)): ?>
              <p class="dash-empty">No return requests found.</p>
            <?php else: ?>
              <div class="dash-table-wrap">
                <table class="dash-table">
                  <thead>
                    <tr>
                      <th>Order</th>
                      <th>Customer</th>
                      <th>Total</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($returnRequests as $return): ?>
                      <tr>
                        <td>#<?= (int)$return['id'] ?></td>
                        <td><?= htmlspecialchars($return['customer_name'] ?? 'Customer') ?></td>
                        <td>£<?= number_format((float)$return['total_amount'], 2) ?></td>
                        <td><?= htmlspecialchars($return['order_status']) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </section>
    </div>
  </div>
</main>
</body>
</html>