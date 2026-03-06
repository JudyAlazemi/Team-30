<?php
$error = $_GET["error"] ?? "";
$sent  = $_GET["sent"] ?? "";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Forgot Password | Sabil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

  <h1>Reset your password</h1>
  <p>Enter your email address and we’ll send you a reset link.</p>

  <?php if ($error === "missing"): ?>
    <p style="color:red;">Please enter your email.</p>
  <?php endif; ?>

  <?php if ($sent === "1"): ?>
    <p style="color:green;">
      If that email exists, a reset link has been generated.
    </p>
  <?php endif; ?>

  <form method="post" action="backend/controllers/request_password_reset.php">
    <label>Email</label><br>
    <input type="email" name="email" required><br><br>
    <button type="submit">Send reset link</button>
  </form>

  <p><a href="login.html">Back to login</a></p>

</body>
</html>