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
  <title>Privacy | Sabil</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/static.css" />

    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>
    <script defer src="assets/js/nav.js"></script>

 
      <link rel="icon" type="image/png" href="images/logo.png">

</head>
<body class="page-static">


<?php include __DIR__ . "/partials/navigation.php"; ?>



 <!-- Page title band -->
  <header class="static-hero">
    <div class="static-wrap">
      <h1>Privacy Policy</h1>
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
          <li><a href="aboutus.php">About us</a></li>
          <li><a href="terms.php">Terms & Conditions</a></li>
          <li><a href="privacypolicy.php" aria-current="page">Privacy Policy</a></li>
        
        </ul>
      </nav>
    </aside>

    <!-- Right: main content -->
    <article class="static-content">
      <h2>Privacy Policy</h2>
      <p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect your personal information when you use our website.</p>

      <h3>Information We Collect</h3>
      <ul>
        <li><strong>Personal Information:</strong> We may collect personal information such as your name, email address, phone number, and payment details when you create an account or make a purchase.</li>
        <li><strong>Usage Data:</strong> We may collect information about how you use our website, including your IP address, browser type, pages visited, and time spent on the site.</li>
      </ul>

      <h3>How We Use Your Information</h3>
      <ul>
        <li>To process and fulfill your orders.</li>
        <li>To communicate with you about your account and orders.</li>
        <li>To improve our website and services.</li>
        <li>To send you promotional emails (you can opt-out at any time).</li>
      </ul>

      <h3>Data Security</h3>
      <p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.</p>

      <h3>Your Rights</h3>
      <p>You have the right to access, correct, or delete your personal information. You can also opt-out of receiving promotional emails at any time by following the unsubscribe link in the email.</p>

      <h3>Changes to This Policy</h3>
      <p>We may update this privacy policy from time to time. Any changes will be posted on this page with an updated revision date.</p>

      <h3>Contact Us</h3>
      <p> Please visit our <a href="contactus.php">Contact Us</a> page for more information.
</p>
    </article>
  </main> 

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
      <a href="products.php">Order</a>
      <a href="cart.php">Shopping Cart</a>
      <a href="favourites.php">Favourites</a>
    </div>
  </div>

  <div class="footer-bottom">
    <p>© <?php echo date('Y'); ?> Sabil. All rights reserved.</p>
  </div>
</footer>
