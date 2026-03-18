<?php
require_once __DIR__ . "/includes/account_layout.php";
$active = "favourites";

$favs = [];
try {
  $stmt = $conn->prepare("
    SELECT p.id, p.name, p.description, p.price, p.image_url
    FROM favourites f
    JOIN products p ON p.id = f.product_id
    WHERE f.user_id = ?
    ORDER BY f.created_at DESC, f.id DESC
  ");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $favs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
} catch (Exception $e) {}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>My Favourites</title>

  <!-- SAME THEME AS DASHBOARD/ORDERS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/customer_dashboard.css?v=<?= time() ?>">

  <script defer src="assets/js/nav.js"></script>
  <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="account-page">

<?php include __DIR__ . "/partials/navigation.php"; ?>

<!-- PAGE BODY (same dashboard layout) -->
<div class="dash-page">
  <div class="dash-frame">
    <div class="dash-grid">

      <!-- LEFT MENU -->
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

          <a class="dash-link" href="customer_orders.php">
            <span class="dash-ico"><img src="assets/images/shopping-bag-filled.png" alt=""></span>
            <span>Orders</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link is-active" href="customer_favourites.php">
            <span class="dash-ico"><img src="assets/images/favorite-shaded.png" alt=""></span>
            <span>Favourites</span>
            <span class="dash-arrow">›</span>
          </a>
 
          <a class="dash-link" href="customer_messages.php">
            <span class="dash-ico"><img src="assets/images/message.png" alt=""></span>
            <span> Messages</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="customer_review.php">
            <span class="dash-ico"><img src="assets/images/reviews.png"></span>
            <span> Reviews</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="customer_security.php">
            <span class="dash-ico"><img src="assets/images/security-icon.png" alt=""></span>
            <span>Login &amp; Security</span>
            <span class="dash-arrow">›</span>
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

      <!-- RIGHT CONTENT -->
      <main class="dash-right">

        <h1 class="dash-title">My Favourites</h1>
        <div class="dash-rule"></div>

        <section class="dash-recent">
          <div class="dash-recent-head">
            <div>
              <div class="dash-recent-title">Saved Items</div>
              <div class="dash-recent-sub">Product&nbsp;&nbsp;Price&nbsp;&nbsp;Action</div>
            </div>
          </div>

          <?php if (empty($favs)): ?>
            <div class="dash-empty">
              <p class="dash-muted">No favourites yet.</p>
              <a href="products.php" class="dash-place-btn">Go to Products</a>
            </div>
          <?php else: ?>
            <div class="dash-recent-body">
              <?php foreach ($favs as $p): ?>
                <div class="dash-row" style="grid-template-columns: 1fr 120px 140px;">
                  <div class="dash-cell">
                    <strong><?= htmlspecialchars($p['name']) ?></strong><br>
                    <span style="opacity:.8;font-size:11px;">
                      <?= htmlspecialchars($p['description'] ?? '') ?>
                    </span>
                  </div>

                  <div class="dash-cell">
                    £<?= number_format((float)$p['price'], 2) ?>
                  </div>

                  <div class="dash-cell">
                    <a class="dash-back" href="productdetails.php?id=<?= (int)$p['id'] ?>">View</a>
                    <button class="dash-back dash-secondary js-remove-fav" data-product-id="<?= (int)$p['id'] ?>">
                      Remove
                    </button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

        </section>

        <div class="dash-bottom">
          <a class="dash-back" href="customer_dashboard.php">Back to dashboard</a>
          <a class="dash-back dash-secondary" href="products.php">Add more favourites</a>
        </div>

      </main>

    </div>
  </div>
</div>
<script>
  async function postFav(payload) {
    const res = await fetch('favourites.php', {   // ✅ correct backend endpoint
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(payload),
      credentials: 'same-origin'
    });

    const text = await res.text();
    try { return JSON.parse(text); }
    catch { return { status: "error", message: "Data is not valid" }; }
  }

  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.js-remove-fav');
    if (!btn) return;

    btn.disabled = true;
    try {
      const productId = btn.dataset.productId;
      const r = await postFav({ action: 'toggle', product_id: productId });

      if (r.requireLogin) {
        window.location.href = r.loginUrl || 'login.html';
        return;
      }
      if (r.status !== 'success') {
        alert(r.message || 'Could not update favourites');
        return;
      }
      window.location.reload();
    } finally {
      btn.disabled = false;
    }
  });
</script>
</body>
</html>