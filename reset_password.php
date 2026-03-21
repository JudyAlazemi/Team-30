<?php
require_once __DIR__ . "/backend/config/session.php";

if (
    !isset($_SESSION["reset_user_id"]) ||
    !isset($_SESSION["reset_email"]) ||
    !isset($_SESSION["reset_question"]) ||
    !isset($_SESSION["reset_token"])
) {
    header("Location: forgot_password.php");
    exit;
}

$error = $_GET["error"] ?? "";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Reset Password | Sabil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="assets/css/style.css" />

</head>
<body>

  <h1>Reset your password</h1>
  <p>Please answer your security question and choose a new password.</p>

  <?php if ($error === "missing"): ?>
    <p style="color:red;">Please fill in all fields.</p>
  <?php elseif ($error === "wrong_answer"): ?>
    <p style="color:red;">Security answer is incorrect.</p>
  <?php elseif ($error === "mismatch"): ?>
    <p style="color:red;">Passwords do not match.</p>
  <?php elseif ($error === "weak"): ?>
    <p style="color:red;">Password must be at least 8 characters and include at least one letter and one number.</p>
  <?php elseif ($error === "expired"): ?>
    <p style="color:red;">Your reset session has expired. Please start again.</p>
  <?php elseif ($error === "invalid"): ?>
    <p style="color:red;">Invalid reset request. Please start again.</p>
  <?php endif; ?>

  <form method="post" action="backend/controllers/process_reset_password.php">
    <p>
      <strong>Security Question:</strong>
      <?= htmlspecialchars($_SESSION["reset_question"]) ?>
    </p>

    <label for="security_answer">Security Answer</label><br>
    <input type="text" id="security_answer" name="security_answer" required><br><br>

    <form method="post" action="backend/controllers/process_reset_password.php">
  <p>
    <strong>Security Question:</strong>
    <?= htmlspecialchars($_SESSION["reset_question"]) ?>
  </p>

  <label for="security_answer">Security Answer</label><br>
  <input type="text" id="security_answer" name="security_answer" required><br><br>

  <label for="new_password">New Password</label><br>
  <input
    type="password"
    id="new_password"
    name="new_password"
    required
    pattern="(?=.*[A-Za-z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}"
    title="Minimum 8 characters, 1 number, and 1 special character"
  ><br>

  <small style="display:block; margin-top:6px; color:#666;">
    Password must contain:
    <ul style="margin:6px 0 0 18px; padding:0;">
      <li>Minimum 8 characters</li>
      <li>At least one number</li>
      <li>At least one special character</li>
    </ul>
  </small><br>

    <label for="confirm_password">Confirm New Password</label><br>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Reset Password</button>
  </form>

  <p><a href="login.html">Back to login</a></p>

</body>
</html>