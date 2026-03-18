<?php
session_start();
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$categories = [];
$error = "";

try {
    $result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
} catch (Exception $e) {
    $categories = [];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Add Product</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/admin_dashboard.css?v=<?= time() ?>">
</head>
<body class="account-page">

<main class="dash-page">
  <div class="dash-frame">
    <div class="dash-grid">
      <aside class="dash-left">
        <div class="dash-hello">
          <h3>Hello,</h3>
          <p><?= htmlspecialchars($_SESSION["admin_name"] ?? "Admin") ?></p>
        </div>
      </aside>

      <section class="dash-right">
        <h1 class="dash-title">Add Product</h1>
        <hr class="dash-rule">

        <div class="dash-section">
          <form method="POST" action="save_product.php" class="admin-edit-form">
            <div class="admin-form-group">
              <label>Name</label>
              <input type="text" name="name" required>
            </div>

            <div class="admin-form-group">
              <label>Description</label>
              <textarea name="description" required></textarea>
            </div>

            <div class="admin-form-group">
              <label>Price</label>
              <input type="number" step="0.01" min="0" name="price" required>
            </div>

            <div class="admin-form-group">
              <label>Stock</label>
              <input type="number" min="0" name="stock" required>
            </div>

            <div class="admin-form-group">
              <label>Image URL</label>
              <input type="text" name="image_url" required>
            </div>

            <div class="admin-form-group">
              <label>Category</label>
              <select name="category_id" required>
                <option value="">Select category</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="dash-actions">
              <button type="submit" class="dash-back">Save Product</button>
              <a href="admin_products.php" class="dash-secondary-btn">Back</a>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</main>

</body>
</html>