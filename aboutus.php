<?php
if (file_exists(__DIR__ . '/db.php')) {
    require_once __DIR__ . '/db.php';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>About | Sabil</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/static.css" />
    <script defer src="assets/js/nav.js"></script>
    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>
 
      <link rel="icon" type="image/png" href="images/logo.png">

</head>
<body class="page-static">

<header class="topbar">
  <div class="topbar-inner">

    <!-- Left: Menu button -->
    <button class="icon-btn menu-toggle" aria-label="Open menu" aria-expanded="false">
      <img class="icon icon--menu" src="assets/images/menu.png" alt="" />
      <img class="icon icon--close" src="assets/images/close.png" alt="" />
    </button>

    <!-- Center: Logo -->
    <a class="brand" href="index.php">
      <img class="brand-logo" src="assets/images/logo.png" alt="Sabil" />
    </a>

    <!-- Right: actions -->
    <nav class="actions" aria-label="Account & tools">

      <!-- USER -->
      <a id="userBtn" class="action" href="login.html" role="button" aria-pressed="false">
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

      <!-- SEARCH GROUP (one flex item) -->
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

      <!-- FAVOURITE -->
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

      <!-- BAG -->
      <a id="bagBtn" class="action" href="cart.html" role="button" aria-pressed="false">
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

<!-- MENU DRAWER (left-side) -->
<div id="menuDrawer" class="drawer" aria-hidden="true">
  <div class="drawer__backdrop" data-close-drawer></div>

  <aside class="drawer__panel" role="dialog" aria-modal="true" aria-label="Site menu">
    <nav class="drawer__nav">
      <a href="products.html">Shop all</a>
      <a href="cart.html">Cart</a>
      <a href="favourites.php">Favourites</a>
      <a href="contactus.php">Contact us</a>
      <a href="faq.php">FAQ</a>
      <a href="aboutus.php">About us</a>
      <a href="terms.php">Terms</a>
      <a href="privacypolicy.php">Privacy Policy</a>
  
    </nav>
  </aside>
</div>

 <!-- Page title band -->
  <header class="static-hero">
    <div class="static-wrap">
      <h1>About us</h1>
    </div>
  </header>

  <!-- Two-column layout -->
  <main class="static-grid">
    <!-- Left: sidebar links -->
    <aside class="static-sidebar" aria-label="Static page navigation">
      <nav>
        <ul class="static-menu">
          <li><a href="contactus.php">Contact us</a></li>
          <li><a href="faq.php">FAQ</a></li>
          <li><a href="aboutus.php"aria-current="page">About us</a></li>
          <li><a href="terms.php">Terms & Conditions</a></li>
          <li><a href="privacypolicy.php">Privacy Policy</a></li>
        
        </ul>
      </nav>
    </aside>

    <!-- Right: main content -->
    <article class="static-content">
      <h2>Our Story</h2>
      <p>
        Sabil was founded with the mission to provide high-quality products that enhance everyday life. Our journey began with a simple idea: to create a shopping experience that is both enjoyable and reliable. Over the years, we have grown into a trusted brand, known for our commitment to customer satisfaction and excellence.
      </p>

      <h2>Our Values</h2>
      <p>
        At Sabil, we believe in integrity, innovation, and community. We strive to uphold these values in everything we do, from sourcing our products to delivering exceptional customer service. Our team is dedicated to making a positive impact, not only for our customers but also for the communities we serve.
      </p>

      <h2>Our Team</h2>
      <p>
        Our team is made up of passionate individuals who are experts in their fields. From product development to customer support, each member of our team plays a crucial role in ensuring that Sabil remains a leader in the industry. We are proud of our diverse and talented workforce, which drives our success and growth.
      </p>

      <h2>Contact Us</h2>
      <p>
        We would love to hear from you! Whether you have questions about our products, need assistance with an order, or simply want to share your feedback, our customer service team is here to help. Please visit our <a href="contactus.php">Contact Us</a> page for more information.
      </p>


<footer class="site-footer">
  <div class="footer-container">

    <!-- Newsletter -->
    <div class="footer-newsletter">
      <h3>Join our newsletter</h3>
      <p>Get personalized updates, launch news, and exclusive access just for you.</p>

      <form id="newsletterForm">
        <input 
          type="email"
          id="newsletterEmail"
          name="email"
          placeholder="Enter your email"
          required
        >
        <button type="submit">Subscribe</button>
        <p id="newsletterMsg" style="margin-top:10px;"></p>
      </form>
    </div>

    <!-- Column 1 -->
    <div class="footer-links">
      <a href="contactus.php">Contact Us</a>
      <a href="faq.php">FAQ</a>
      <a href="aboutus.php">About Us</a>
      <a href="terms.php">Terms</a>
      <a href="privacypolicy.php">Privacy Policy</a>
    </div>

    <!-- Column 2 -->
    <div class="footer-links">
      <a href="products.html">Order</a>
      <a href="cart.html">Shopping Cart</a>
      <a href="favourites.php">Favourites</a>
    </div>
  </div>

  <div class="footer-bottom">
    <p>© <?php echo date('Y'); ?> Sabil. All rights reserved.</p>
  </div>
</footer>
</body>
</html>