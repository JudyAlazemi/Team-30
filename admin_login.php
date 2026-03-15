<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($email === "" || $password === "") {
        $error = "Please fill in all fields.";
    } else {
        try {
            $stmt = $conn->prepare("
                SELECT id, name, email, password, must_change_password, approval_status
                FROM admins
                WHERE email = ?
                LIMIT 1
            ");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();
            $stmt->close();

            if (!$admin || !password_verify($password, $admin["password"])) {
                $error = "Invalid email or password.";
            } elseif ($admin["approval_status"] === "pending") {
                $error = "Your admin account is still pending approval.";
            } elseif ($admin["approval_status"] === "rejected") {
                $error = "Your admin registration has been rejected.";
            } else {
                $_SESSION["admin_id"] = (int)$admin["id"];
                $_SESSION["admin_name"] = $admin["name"];
                $_SESSION["admin_email"] = $admin["email"];
                $_SESSION["admin_logged_in"] = true;

                if ((int)$admin["must_change_password"] === 1) {
                    header("Location: admin_dashboard.php");
                    exit;
                }

                header("Location: admin_dashboard.php");
                exit;
            }
        } catch (Exception $e) {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="assets/css/registerlogin.css?v=3">
    <script src="assets/js/auth.js" defer></script>
    <link rel="stylesheet" href="assets/css/style.css" />

    <script defer src="assets/js/nav.js"></script>
    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>

    <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="page-home">
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
      <a id="userBtn" class="action" href="admin_login.php" role="button">
        <img
          id="userIcon"
          class="icon"
          src="assets/images/sign-in.png"
          alt="User"
        />
        <span id="userText" class="action-text">Admin Sign in</span>
      </a>

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
    </nav>
  </div>
</header>

<div id="menuDrawer" class="drawer" aria-hidden="true">
  <div class="drawer__backdrop" data-close-drawer></div>

  <aside class="drawer__panel" role="dialog" aria-modal="true" aria-label="Site menu">
    <nav class="drawer__nav">
      <a href="index.php">Home</a>
      <a href="products.php">Shop all</a>
      <a href="contactus.php">Contact us</a>
      <a href="faq.php">FAQ</a>
      <a href="aboutus.php">About us</a>
      <a href="terms.php">Terms</a>
      <a href="privacypolicy.php">Privacy Policy</a>
    </nav>
  </aside>
</div>

<div id="overlay" class="overlay"></div>

<main class="content">
    <h1>Welcome back, Admin</h1>
    <p class="subtitle">Log in to access the admin dashboard</p>

    <?php if ($error): ?>
        <p class="error" style="display:block;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form class="auth-form" method="POST" action="">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="admin@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <div class="flex-row-between">
                <label>Password</label>
            </div>

            <div class="password-wrapper">
                <input id="password" type="password" name="password" placeholder="••••••••" required>
                <button type="button" class="toggle-password" data-target="password">
                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg"
                         width="20" height="20" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-primary">Log In</button>

        <p class="signin">
            Don’t have admin access?
            <a href="admin_register.php">Request it here</a>
        </p>

        <p class="admin-login-option">
            Customer login?
            <a href="login.html">Sign in as Customer</a>
        </p>
    </form>
</main>

<footer class="site-footer">
  <div class="footer-container">
    <div class="footer-bottom">
      <p>© 2025 Sabil. All rights reserved.</p>
    </div>
  </div>
</footer>

</body>
</html>