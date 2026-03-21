<?php
$error = $_GET["error"] ?? "";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Forgot Password | Sabil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/auth.css" />
</head>
<body class="auth-page">

<?php include __DIR__ . "/partials/navigation.php"; ?>

<main class="auth-layout">
  <section class="auth-section">
    <h1>Password Reset</h1>
    <p class="auth-subtext">Provide the email address associated with your account to recover your password.</p>

    <?php if ($error === "missing"): ?>
      <p class="auth-error">Please enter your email.</p>
    <?php elseif ($error === "invalid"): ?>
      <p class="auth-error">No account was found with that email.</p>
    <?php elseif ($error === "not_setup"): ?>
      <p class="auth-error">This account does not have a security question set yet.</p>
    <?php endif; ?>

    <form class="auth-form" method="post" action="backend/controllers/request_password_reset.php">
      <label for="email">Email <span class="required-star">*</span></label>
      <input type="email" id="email" name="email" placeholder="name@example.com" required>

      <button type="submit" class="auth-submit-btn">Reset Password</button>
    </form>

 <div class="auth-links">
   <p class="login">
      Back to <a href="login.html">Login</a> 
    </p>
   <p class="signup">
     Don't have an account? <a href="register.html">Sign up</a>
   </p>

</div>

  </section>
</main>

 <div class="footer-bottom">
    <p>© <?php echo date('Y'); ?> Sabil. All rights reserved.</p>
  </div>
  
  <script defer src="assets/js/nav.js"></script>

</body>
</html>