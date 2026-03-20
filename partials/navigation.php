<?php
require_once __DIR__ . "/../backend/config/session.php";

/* HARD PRIORITY */
if (!empty($forceIsAdmin)) {
    $isAdmin = true;
    $isUser = false;
} elseif (!empty($forceIsUser)) {
    $isAdmin = false;
    $isUser = true;
} else {
    $isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    $isUser  = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

    if ($isAdmin) {
        $isUser = false;
    }
}

if ($isAdmin) {
    $userHref = 'admin_dashboard.php';
    $userText = 'Admin Panel';
    $userRole = 'admin';
} elseif ($isUser) {
    $userHref = 'customer_dashboard.php';
    $userText = 'My Account';
    $userRole = 'customer';
} else {
    $userHref = $forceGuestHref ?? 'login.html';
    $userText = $forceGuestText ?? 'Sign in';
    $userRole = 'guest';
}
?>

<link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>" />
<script>
window.userData = {
  role: "<?= htmlspecialchars($userRole, ENT_QUOTES) ?>"
};
</script>
<script defer src="assets/js/nav.js?v=<?= time() ?>"></script>
<link rel="icon" type="image/png" href="assets/images/logo.png">

<header class="topbar">
  <div class="topbar-inner">

  <div class="left-controls">
    <button class="icon-btn menu-toggle" aria-label="Open menu" aria-expanded="false">
      <img class="icon icon--menu" src="assets/images/menu.png" alt="" />
      <img class="icon icon--close" src="assets/images/close.png" alt="" />
    </button>

     <button id="theme-switch" class="icon-btn" type="button" aria-label="Toggle theme" aria-pressed="false">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
          <path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
          <path d="M338.5-338.5Q280-397 280-480t58.5-141.5Q397-680 480-680t141.5 58.5Q680-563 680-480t-58.5 141.5Q563-280 480-280t-141.5-58.5ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Z"/>
        </svg>
      </button>
  </div>
  

    <a class="brand" href="index.php" aria-label="Sabil home">
      <img class="brand-logo brand-logo--default" src="assets/images/logo.png" alt="Sabil" />
      <img class="brand-logo brand-logo--light" src="assets/images/logo-light.png" alt="Sabil" />
    </a>

    <nav class="actions" aria-label="Account & tools">

      <a
        id="userBtn"
        class="action"
        href="<?= htmlspecialchars($userHref) ?>"
        role="button"
        aria-pressed="<?= ($isAdmin || $isUser) ? 'true' : 'false' ?>"
        data-role="<?= htmlspecialchars($userRole) ?>"
      >
        <img
          id="userIcon"
          class="icon"
          src="<?= ($isAdmin || $isUser) ? 'assets/images/user.png' : 'assets/images/sign-in.png' ?>"
          alt="User"
          data-src-inactive="assets/images/sign-in.png"
          data-src-active="assets/images/user.png"
        />
        <span id="userText" class="action-text"><?= htmlspecialchars($userText) ?></span>
      </a>

      <div class="search-group">
        <a id="searchBtn" class="action" href="#">
          <img class="icon" src="assets/images/search.png" alt="Search" />
        </a>
        <input type="text" id="navSearchInput" class="nav-search-input" placeholder="Search..." />
      </div>

      <a id="favBtn" class="action" href="favourites.php" role="button" aria-pressed="false">
        <img
          id="favIcon"
          class="icon"
          src="assets/images/favorite.png"
          alt="Favourite"
          data-src-inactive="assets/images/favorite.png"
          data-src-active="assets/images/favorite-shaded.png"
        />
      </a>

      <a id="bagBtn" class="action" href="cart.php" role="button" aria-pressed="false">
        <img
          id="bagIcon"
          class="icon"
          src="assets/images/shopping-bag.png"
          alt="Shopping bag"
          data-src-inactive="assets/images/shopping-bag.png"
          data-src-active="assets/images/shopping-bag-filled.png"
        />
      </a>
    </nav>
  </div>
</header>

<div id="menuDrawer" class="drawer" aria-hidden="true">
  <div class="drawer__backdrop" data-close-drawer></div>

  <aside class="drawer__panel" role="dialog" aria-modal="true" aria-label="Site menu">
    <nav class="drawer__nav">
      <a href="index.php">Home</a>
      <a href="products.php">Shop all</a>
      <a href="cart.php">Cart</a>
      <a href="favourites.php">Favourites</a>

      <?php if ($isAdmin): ?>
        <a href="admin_dashboard.php">Admin Panel</a>
        <a href="admin_logout.php">Logout</a>
      <?php elseif ($isUser): ?>
        <a href="customer_dashboard.php">My Account</a>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="<?= htmlspecialchars($userHref) ?>"><?= htmlspecialchars($userText) ?></a>
        <a href="register.html">Register</a>
      <?php endif; ?>

      <a href="contactus.php">Contact us</a>
      <a href="faq.php">FAQ</a>
      <a href="aboutus.php">About us</a>
      <a href="terms.php">Terms</a>
      <a href="privacypolicy.php">Privacy Policy</a>
    </nav>
  </aside>
</div>