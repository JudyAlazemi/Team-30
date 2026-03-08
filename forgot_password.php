<?php
$error = $_GET["error"] ?? "";
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
  <p>Enter your email address to continue.</p>

  <?php if ($error === "missing"): ?>
    <p style="color:red;">Please enter your email.</p>
  <?php elseif ($error === "invalid"): ?>
    <p style="color:red;">No account was found with that email.</p>
  <?php elseif ($error === "not_setup"): ?>
    <p style="color:red;">This account does not have a security question set yet.</p>
  <?php endif; ?>

  <form method="post" action="backend/controllers/request_password_reset.php">
    <label for="email">Email</label><br>
    <input type="email" id="email" name="email" required><br><br>
    <button type="submit">Continue</button>
  </form>

  <p><a href="login.html">Back to login</a></p>

</body>
</html>