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
  <title>Contact | Sabil</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/static.css" />
    <script defer src="assets/js/nav.js"></script>
    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>
 
      <link rel="icon" type="image/png" href="images/logo.png">

</head>
<body class="page-static">

<?php include __DIR__ . "/partials/navigation.php"; ?>


  <!-- Page title band -->
  <header class="static-hero">
    <div class="static-wrap">
      <h1>Contact</h1>
    </div>
  </header>

  <!-- Two-column layout -->
  <main class="static-grid">
    <!-- Left: sidebar links -->
    <aside class="static-sidebar" aria-label="Static page navigation">
      <nav>
        <ul class="static-menu">
          <li><a href="contactus.php" aria-current="page">Contact us</a></li>
          <li><a href="faq.php">FAQ</a></li>
          <li><a href="aboutus.php">About us</a></li>
          <li><a href="terms.php">Terms & Conditions</a></li>
          <li><a href="privacypolicy.php">Privacy Policy</a></li>
        
        </ul>
      </nav>
    </aside>

    <!-- Middle: main content -->
    <section class="static-content">
      <p class="lead">
        For general enquiries and customer service questions — 
        <a href="mailto:info@sabilfragrance.com">info@sabilfragrance.com</a>
      </p>

      <p>
        For wholesale enquiries — 
        <a href="mailto:wholesale@sabilfragrance.com">wholesale@sabilfragrance.com</a>
      </p>

      <h2>Visit our store</h2>
      <p>Part perfumery, part elegant retail space.</p>
      <address class="static-address">
        Sabil Studio<br/>
        Aston Triangle<br/>
        Aston Triangle, Birmingham, B4 7ET<br/>
        +44 0121 204 3000
      </address>

      <p><strong>Hours</strong><br> Mon-Thu:   10:00–16:00 &nbsp;&nbsp; <br> Fri–Sat:   10:00–14:00</p>

      <!-- CONTACT FORM -->
<section class="contact-form-section">

  <div class="contact-form-header">
    <h2>Contact us</h2>
    <p>Got any questions or suggestions?<br>Fill out this form to reach out</p>
  </div>

  <form class="contact-form">

    <div class="contact-row">
      <input 
        type="text"
        name="name"
        placeholder="Enter your name"
        required
      >

      <input 
        type="email"
        name="email"
        placeholder="Enter your email"
        required
      >
    </div>

    <textarea
      name="message"
      placeholder="Enter your message"
      rows="6"
      required
    ></textarea>

    <button type="submit" class="contact-send-btn">
      Send
    </button>

    <p class="contact-note">
      You can email us directly at
      <a href="mailto:info@sabilfragrance.com">info@sabilfragrance.com</a>
    </p>

  </form>

</section>  
    </section>
    <!-- Right: optional large image (hides on small screens) -->
    <aside class="static-media">
      <img src="assets/images/flower.png" alt="Sabil studio" />
    </aside>
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
