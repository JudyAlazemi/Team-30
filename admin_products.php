<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$adminName = $_SESSION["admin_name"] ?? "Admin";
$search = trim($_GET['search'] ?? '');
$message = $_GET['msg'] ?? '';

$products = [];

try {
    if ($search !== '') {
        $stmt = $conn->prepare("
            SELECT p.id, p.name, p.description, p.price, p.stock, p.image_url, p.category_id,
                   c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.name LIKE CONCAT('%', ?, '%')
               OR c.name LIKE CONCAT('%', ?, '%')
            ORDER BY p.id DESC
        ");
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $stmt->close();
    } else {
        $result = $conn->query("
            SELECT p.id, p.name, p.description, p.price, p.stock, p.image_url, p.category_id,
                   c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.id DESC
        ");

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
    }
} catch (Exception $e) {
    $products = [];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manage Inventory</title>

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
          <h3>Hello,</h3>
          <p><?= htmlspecialchars($adminName) ?></p>
        </div>


        <nav class="dash-menu">

          <a class="dash-link" href="admin_dashboard.php">
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

          <a class="dash-link is-active" href="admin_products.php">
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
      <section class="dash-right">
        <h1 class="dash-title">Inventory</h1>
        <hr class="dash-rule">

        <?php if ($message === 'added'): ?>
          <div class="admin-alert success-alert">Product added successfully.</div>
        <?php elseif ($message === 'updated'): ?>
          <div class="admin-alert success-alert">Product updated successfully.</div>
        <?php elseif ($message === 'deleted'): ?>
          <div class="admin-alert success-alert">Product deleted successfully.</div>
        <?php elseif ($message === 'has_orders'): ?>
          <div class="admin-alert error-alert">This product cannot be deleted because it already appears in order items.</div>
        <?php elseif ($message === 'error'): ?>
          <div class="admin-alert error-alert">Something went wrong.</div>
        <?php endif; ?>

        <div class="dash-section">
          <form method="GET" class="admin-search-form">
            <input
              type="text"
              name="search"
              class="admin-search-input"
              placeholder="Search by product or category"
              value="<?= htmlspecialchars($search) ?>"
            >
            <button type="submit" class="dash-back">Search</button>
            <a href="admin_products.php" class="dash-secondary-btn">Reset</a>
            <a href="add_product.php" class="dash-back">Add Product</a>
          </form>
        </div>

        <div class="dash-section">
          <h2 class="dash-section-title">All Products</h2>

          <?php if (empty($products)): ?>
            <p class="dash-empty">No products found.</p>
          <?php else: ?>
            <div class="dash-table-wrap">
              <table class="dash-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($products as $product): ?>
                    <tr>
                      <td>#<?= (int)$product['id'] ?></td>
                      <td>
                        <img src="<?= htmlspecialchars($product['image_url']) ?>"
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             class="admin-product-thumb">
                      </td>
                      <td><?= htmlspecialchars($product['name']) ?></td>
                      <td><?= htmlspecialchars($product['category_name'] ?? 'Uncategorised') ?></td>
                      <td>£<?= number_format((float)$product['price'], 2) ?></td>
                      <td><?= (int)$product['stock'] ?></td>
                      <td class="dash-actions">
                        <a href="edit_product.php?id=<?= (int)$product['id'] ?>" class="dash-back">Edit</a>

                        <form action="delete_product.php" method="POST" onsubmit="return confirm('Delete this product?');">
                          <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                          <button type="submit" class="dash-secondary-btn">Delete</button>
                        </form>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td colspan="6" class="admin-product-desc">
                        <?= htmlspecialchars($product['description']) ?>
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