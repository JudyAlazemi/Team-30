<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<?php if (!empty($_SESSION['user_id'])): ?>
  <a id="userBtn" class="action" href="logout.php" role="button">
    <img
      id="userIcon"
      class="icon"
      src="assets/images/user-shaded.png"
      alt="User"
      data-src-inactive="assets/images/sign-in.png"
      data-src-active="assets/images/user-shaded.png"
    />
    <span id="userText" class="action-text">
      Logout (<?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>)
    </span>
  </a>
<?php else: ?>
  <a id="userBtn" class="action" href="login.html" role="button">
    <img
      id="userIcon"
      class="icon"
      src="assets/images/sign-in.png"
      alt="User"
      data-src-inactive="assets/images/sign-in.png"
      data-src-active="assets/images/user-shaded.png"
    />
    <span id="userText" class="action-text">Sign in</span>
  </a>
<?php endif; ?>
