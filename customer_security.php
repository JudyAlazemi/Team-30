<?php


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . "/includes/account_layout.php";
$active = "security";

// ✅ must be logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

// basic session values
$userId    = (int)($_SESSION['user_id'] ?? 0);
$userName  = $_SESSION['user_name'] ?? 'Customer';
$userEmail = $_SESSION['user_email'] ?? '';

// messages
$successMsg = "";
$errorMsg   = "";

// safe post
function post($key, $default = "") {
  return isset($_POST[$key]) ? trim((string)$_POST[$key]) : $default;
}

// detect column exists (for password vs password_hash)
function columnExists($conn, $table, $column) {
  $sql = "SELECT 1
          FROM INFORMATION_SCHEMA.COLUMNS
          WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = ?
            AND COLUMN_NAME = ?
          LIMIT 1";
  $stmt = $conn->prepare($sql);
  if (!$stmt) return false;
  $stmt->bind_param("ss", $table, $column);
  $stmt->execute();
  $res = $stmt->get_result();
  $ok = ($res && $res->num_rows > 0);
  $stmt->close();
  return $ok;
}

// fetch user email + password hash
function fetchUserRow($conn, $userId) {
  $hasPass  = columnExists($conn, 'users', 'password');
  $hasPassH = columnExists($conn, 'users', 'password_hash');

  $passCol = $hasPass ? 'password' : ($hasPassH ? 'password_hash' : null);
  if (!$passCol) return null;

  $sql = "SELECT email, {$passCol} AS pass_hash FROM users WHERE id = ? LIMIT 1";
  $stmt = $conn->prepare($sql);
  if (!$stmt) return null;

  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  return $row ?: null;
}

// update password hash
function updatePassword($conn, $userId, $newHash) {
  if (columnExists($conn, 'users', 'password')) {
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("si", $newHash, $userId);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
  }

  if (columnExists($conn, 'users', 'password_hash')) {
    $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("si", $newHash, $userId);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
  }

  return false;
}

// update email
function updateEmail($conn, $userId, $newEmail) {
  $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ? LIMIT 1");
  if (!$stmt) return false;
  $stmt->bind_param("si", $newEmail, $userId);
  $ok = $stmt->execute();
  $stmt->close();
  return $ok;
}

// load latest email from DB
if (isset($conn) && !$conn->connect_error && $userId > 0) {
  $row = fetchUserRow($conn, $userId);
  if ($row && !empty($row['email'])) {
    $userEmail = $row['email'];
    $_SESSION['user_email'] = $userEmail;
  }
}

// handle forms
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($conn) || $conn->connect_error) {
    $errorMsg = "Database connection failed.";
  } else {
    $action = post('action');

    // ✅ update email
    if ($action === 'update_email') {
      $newEmail = post('email');

      if ($newEmail === "") {
        $errorMsg = "Email cannot be empty.";
      } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Please enter a valid email.";
      } else {
        if (updateEmail($conn, $userId, $newEmail)) {
          $successMsg = "Email updated successfully.";
          $userEmail = $newEmail;
          $_SESSION['user_email'] = $userEmail;
        } else {
          $errorMsg = "Could not update email.";
        }
      }
    }

    // ✅ change password
    if ($action === 'change_password') {
      $current = post('current_password');
      $new     = post('new_password');
      $confirm = post('confirm_password');

      if ($current === "" || $new === "" || $confirm === "") {
        $errorMsg = "Please fill all password fields.";
      } elseif (strlen($new) < 8) {
        $errorMsg = "New password must be at least 8 characters.";
      } elseif ($new !== $confirm) {
        $errorMsg = "New password and confirmation do not match.";
      } else {
        $row = fetchUserRow($conn, $userId);

        if (!$row || empty($row['pass_hash'])) {
          $errorMsg = "Password column not found in database.";
        } elseif (!password_verify($current, $row['pass_hash'])) {
          $errorMsg = "Current password is incorrect.";
        } else {
          $newHash = password_hash($new, PASSWORD_DEFAULT);
          if (updatePassword($conn, $userId, $newHash)) {
            $successMsg = "Password updated successfully.";
          } else {
            $errorMsg = "Could not update password.";
          }
        }
      }
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login & Security</title>

  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/customer_dashboard.css?v=<?= time() ?>">

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
      <?php include __DIR__ . "/partials/navbar.php"; ?>

      <div class="search-group">
        <a id="searchBtn" class="action" href="#">
          <img class="icon" src="assets/images/search.png" alt="Search" />
        </a>
        <input type="text" id="navSearchInput" class="nav-search-input" placeholder="Search..." />
      </div>

      <a id="favBtn" class="action" href="customer_favourites.php">
        <img id="favIcon" class="icon" src="assets/images/favorite.png" alt="Favourite" />
      </a>

      <a id="bagBtn" class="action" href="cart.php">
        <img id="bagIcon" class="icon" src="assets/images/shopping-bag.png" alt="Shopping bag" />
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

<div class="dash-page profile-page">
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

          <a class="dash-link" href="customer_orders.php">
            <span class="dash-ico"><img src="assets/images/shopping-bag-filled.png" alt=""></span>
            <span>Orders</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="customer_favourites.php">
            <span class="dash-ico"><img src="assets/images/favorite-shaded.png" alt=""></span>
            <span>Favourites</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link is-active" href="customer_security.php">
            <span class="dash-ico"><img src="assets/images/security-icon.png" alt=""></span>
            <span>Login &amp; Security</span>
            <span class="dash-arrow">›</span>
          </a>
        </nav>
      </aside>

      <main class="dash-right">

        <h1 class="dash-title">Login &amp; Security</h1>
        <div class="dash-rule"></div>

        <?php if ($successMsg): ?>
          <div class="dash-alert dash-alert--success"><?= htmlspecialchars($successMsg) ?></div>
        <?php endif; ?>

        <?php if ($errorMsg): ?>
          <div class="dash-alert dash-alert--error"><?= htmlspecialchars($errorMsg) ?></div>
        <?php endif; ?>

        <!-- EMAIL -->
        <section class="dash-recent profile-card">
          <div class="dash-recent-head">
            <div>
              <div class="dash-recent-title">Email</div>
              <div class="dash-recent-sub">Update your login email</div>
            </div>
          </div>

          <div class="dash-recent-body">
            <form method="post" class="profile-form">
              <input type="hidden" name="action" value="update_email">

              <label class="profile-field">
                <span>Email</span>
                <input name="email" type="email" value="<?= htmlspecialchars($userEmail) ?>" required>
              </label>

              <div class="profile-actions">
                <button class="dash-back" type="submit">Save Email</button>
              </div>
            </form>
          </div>
        </section>

        <!-- PASSWORD -->
        <section class="dash-recent profile-card">
          <div class="dash-recent-head">
            <div>
              <div class="dash-recent-title">Password</div>
              <div class="dash-recent-sub">Change your password</div>
            </div>
          </div>

          <div class="dash-recent-body">
            <form method="post" class="profile-form">
              <input type="hidden" name="action" value="change_password">

              <label class="profile-field">
                <span>Current Password</span>
                <input name="current_password" type="password" required>
              </label>

              <label class="profile-field">
                <span>New Password</span>
                <input name="new_password" type="password" minlength="8" required>
              </label>

              <label class="profile-field">
                <span>Confirm New Password</span>
                <input name="confirm_password" type="password" minlength="8" required>
              </label>

              <div class="profile-actions">
                <button class="dash-back" type="submit">Update Password</button>
                <a class="dash-back dash-secondary" href="logout.php">Logout</a>
              </div>

              <p class="profile-tip">Tip: use a mix of letters, numbers, and symbols (8+ characters).</p>
            </form>
          </div>
        </section>

        <div class="dash-bottom">
          <a class="dash-back" href="customer_dashboard.php">Back to dashboard</a>
        </div>

      </main>

    </div>
  </div>
</div>

</body>
</html>