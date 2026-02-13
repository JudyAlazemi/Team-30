<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<?php if (!empty($_SESSION['user_id'])): ?>
  <a class="action" href="profile.php" role="button">
    <img class="icon" src="assets/images/user.png" alt="Profile" />
    <span class="action-text"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
  </a>

  <a class="action" href="logout.php" role="button">
    <span class="action-text">Sign out</span>
  </a>

 <?php else: ?>
    <a class="action" href="login.html" role="button">
        <img class="icon" src="assets/images/sign-in.png" alt="Sign in" />
        <span class="action-text">Sign in</span>
    </a>

<?php endif; ?>
