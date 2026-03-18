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
    <link rel="stylesheet" href="assets/css/darkmode.css">

    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>

    <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="page-register">
    
<?php include __DIR__ . "/partials/navigation.php"; ?>

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