<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirmPassword = trim($_POST["confirm_password"] ?? "");

    if ($name === "" || $email === "" || $password === "" || $confirmPassword === "") {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        try {
            $check = $conn->prepare("SELECT id FROM admins WHERE email = ? LIMIT 1");
            $check->bind_param("s", $email);
            $check->execute();
            $result = $check->get_result();
            $exists = $result->fetch_assoc();
            $check->close();

            if ($exists) {
                $error = "An admin account with this email already exists.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("
                    INSERT INTO admins (name, email, password, must_change_password, approval_status)
                    VALUES (?, ?, ?, 1, 'pending')
                ");
                $stmt->bind_param("sss", $name, $email, $hashedPassword);
                $stmt->execute();
                $stmt->close();

                $success = "Your admin registration request has been submitted. You can only log in after approval by an existing admin.";
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
    <title>Admin Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="assets/css/registerlogin.css?v=3">
    <script src="assets/js/auth.js" defer></script>
    <link rel="stylesheet" href="assets/css/style.css" />

    <script defer src="assets/js/nav.js"></script>
    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>

    <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="page-register">

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
    <h1>Admin registration</h1>
    <p class="subtitle">Request access to the admin dashboard</p>

    <?php if ($error): ?>
        <p class="error" style="display:block;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success-msg" style="display:block;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form class="auth-form" method="POST" action="">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Admin Name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="admin@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="password-wrapper">
                <input id="adminPassword" type="password" name="password" placeholder="••••••••" required>
                <button type="button" class="toggle-password" data-target="adminPassword">
                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg"
                         width="20" height="20" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <div class="password-wrapper">
                <input id="adminConfirmPassword" type="password" name="confirm_password" placeholder="••••••••" required>
                <button type="button" class="toggle-password" data-target="adminConfirmPassword">
                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg"
                         width="20" height="20" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <button class="btn-primary" type="submit">Request Admin Access</button>

        <p class="signin">
            Already have an admin account?
            <a href="admin_login.php">Sign in</a>
        </p>

        <p class="admin-login-option">
            Customer account?
            <a href="register.html">Register as Customer</a>
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