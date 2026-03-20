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
  <title>Terms | Sabil</title>
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
      <h1>Terms & Conditions</h1>
    </div>
  </header>

</div>

  <!-- Two-column layout -->
  <main class="static-grid">
    <!-- Left: sidebar links -->
    <aside class="static-sidebar" aria-label="Static page navigation">
      <nav>
        <ul class="static-menu">
          <li><a href="contactus.php" >Contact us</a></li>
          <li><a href="faq.php">FAQ</a></li>
          <li><a href="aboutus.php">About us</a></li>
          <li><a href="terms.php"aria-current="page">Terms & Conditions</a></li>
          <li><a href="privacypolicy.php">Privacy Policy</a></li>
        
        </ul>
      </nav>
    </aside>

    <!-- Right: main content -->
    <article class="static-content">
      <h2>Terms and Conditions</h2>
      <p>Welcome to Sabil! These terms and conditions outline the rules and regulations for the use of Sabil's Website.</p>

      <h3>1. Acceptance of Terms</h3>
      <p>By accessing this website we assume you accept these terms and conditions. Do not continue to use Sabil if you do not agree to take all of the terms and conditions stated on this page.</p>

      <h3>2. Intellectual Property Rights</h3>
      <p>Other than the content you own, under these Terms, Sabil and/or its licensors own all the intellectual property rights and materials contained in this Website.</p>

      <h3>3. User Responsibilities</h3>
      <p>You are responsible for your use of the website, for any content you post to the website, and for any consequences thereof. The content you submit must not be illegal or violate any third-party rights.</p>

      <h3>4. Limitation of Liability</h3>
      <p>In no event shall Sabil, nor any of its officers, directors and employees, be liable to you for anything arising out of or in any way connected with your use of this Website.</p>

      <h3>5. Governing Law</h3>
      <p>These Terms shall be governed by and construed in accordance with the laws of [Your Country], and you submit to the exclusive jurisdiction of the courts located in [Your Country] for the resolution of any disputes.</p>

      <p> Please visit our <a href="contactus.php">Contact Us</a> page for more information. </p>
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
</body>
</html>


