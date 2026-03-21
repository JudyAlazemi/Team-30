<?php
require_once __DIR__ . "/backend/config/session.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sabil - Our Collection</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <script defer src="assets/js/nav.js"></script>
    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>
    <link rel="stylesheet" href="assets/css/globals.css">
    <link rel="stylesheet" href="assets/css/product.css">
</head>
<body class="page-product">

 <?php include __DIR__ . "/partials/navigation.php"; ?>

  <!--fonts -->
  <link rel="preload" href="fonts/MeshedDisplay-Regular.ttf" as="font" type="font/ttf" crossorigin>
  <link rel="preload" href="fonts/MeshedDisplay-Bold.ttf" as="font" type="font/ttf" crossorigin>

  <main>
      <section class="products-hero">
          <div class="hero-content">
              <h1>Our Collection</h1>
              <p>Discover the essence of luxury</p>
          </div>
      </section>

      <!-- Category Filter -->
      <section class="category-section">
          <div class="container">
              <div class="category-filters">
                  <button class="category-btn active" data-category="all">All Products</button>
                  <button class="category-btn" data-category="perfume">Perfumes</button>
                  <button class="category-btn" data-category="car-perfume">Car Perfumes</button>
                  <button class="category-btn" data-category="candle">Candles</button>
                  <button class="category-btn" data-category="home-spray">Home Sprays</button>
                  <button class="category-btn" data-category="body-wash">Body Wash</button>
              </div>
          </div>
      </section>

      <section class="products-section">
        <div class="container">
          <div id="products-list" class="products-grid">
            <!-- PERFUMES (3 products) -->
            <div class="product-card" data-category="perfume" data-id="1">
              <div class="product-image">
                <img src="assets/images/oceanmist.png" alt="Ocean Breeze">
                <div class="product-overlay">
                  <a href="productdetails.php?id=1" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Ocean Breeze</h3>
                <p class="product-description">Fresh aquatic fragrance with marine notes</p>
                <p class="product-price">£59.99</p>
                <button class="hero-btn add-to-cart" data-id="1">Add to Cart</button>
              </div>
            </div>

            <div class="product-card" data-category="perfume" data-id="2">
              <div class="product-image">
                <img src="assets/images/midnightoud.png" alt="Midnight Oud">
                <div class="product-overlay">
                  <a href="productdetails.php?id=2" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Midnight Oud</h3>
                <p class="product-description">Deep and mysterious oriental fragrance</p>
                <p class="product-price">£69.99</p>
                <button class="hero-btn add-to-cart" data-id="2">Add to Cart</button>
              </div>
            </div>

            <div class="product-card" data-category="perfume" data-id="3">
              <div class="product-image">
                <img src="assets/images/velvetmusk.jpeg" alt="Velvet Rose">
                <div class="product-overlay">
                  <a href="productdetails.php?id=3" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Velvet Rose</h3>
                <p class="product-description">Luxurious rose with velvety undertones</p>
                <p class="product-price">£64.99</p>
                <button class="hero-btn add-to-cart" data-id="3">Add to Cart</button>
              </div>
            </div>

            <!-- CAR PERFUMES (3 products) -->
            <div class="product-card" data-category="car-perfume" data-id="4">
              <div class="product-image">
                <img src="assets/images/carperfdark.jpeg" alt="Unleaded Petrol">
                <div class="product-overlay">
                  <a href="productdetails.php?id=4" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Unleaded Petrol</h3>
                <p class="product-description">Energetic and bold car fragrance</p>
                <p class="product-price">£16.99</p>
                <button class="hero-btn add-to-cart" data-id="4">Add to Cart</button>
              </div>
            </div>

            <div class="product-card" data-category="car-perfume" data-id="5">
              <div class="product-image">
                <img src="assets/images/carperflight.jpeg" alt="Ionix Fresh">
                <div class="product-overlay">
                  <a href="productdetails.php?id=5" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Ionix Fresh</h3>
                <p class="product-description">Air-purifying fresh car scent</p>
                <p class="product-price">£14.99</p>
                <button class="hero-btn add-to-cart" data-id="5">Add to Cart</button>
              </div>
            </div>

            <div class="product-card" data-category="car-perfume" data-id="6">
              <div class="product-image">
                <img src="assets/images/carperfmed.png" alt="Lavender Cruise">
                <div class="product-overlay">
                  <a href="productdetails.php?id=6" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Lavender Cruise</h3>
                <p class="product-description">Calming lavender for relaxed drives</p>
                <p class="product-price">£18.99</p>
                <button class="hero-btn add-to-cart" data-id="6">Add to Cart</button>
              </div>
            </div>

            <!-- CANDLES (3 products) -->
            <div class="product-card" data-category="candle" data-id="7">
              <div class="product-image">
                <img src="assets/images/candle.png" alt="Vanilla Dream Candle">
                <div class="product-overlay">
                  <a href="productdetails.php?id=7" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Vanilla Dream Candle</h3>
                <p class="product-description">Warm vanilla and cream scented candle</p>
                <p class="product-price">£12.99</p>
                <button class="hero-btn add-to-cart" data-id="7">Add to Cart</button>
              </div>
            </div>

            <div class="product-card" data-category="candle" data-id="8">
              <div class="product-image">
                <img src="assets/images/candle.png" alt="Amber Woods Candle">
                <div class="product-overlay">
                  <a href="productdetails.php?id=8" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Amber Woods Candle</h3>
                <p class="product-description">Earthy amber with woody undertones</p>
                <p class="product-price">£16.99</p>
                <button class="hero-btn add-to-cart" data-id="8">Add to Cart</button>
              </div>
            </div>

            <div class="product-card" data-category="candle" data-id="9">
              <div class="product-image">
                <img src="assets/images/candle.png" alt="Cherry Blossom Candle">
                <div class="product-overlay">
                  <a href="productdetails.php?id=9" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Cherry Blossom Candle</h3>
                <p class="product-description">Delicate floral cherry blossom scent</p>
                <p class="product-price">£14.99</p>
                <button class="hero-btn add-to-cart" data-id="9">Add to Cart</button>
              </div>
            </div>

            <!-- HOME SPRAYS (3 products) -->
            <div class="product-card" data-category="home-spray" data-id="10">
              <div class="product-image">
                <img src="assets/images/homespraysilver.png" alt="Lavender Cloud Spray">
                <div class="product-overlay">
                  <a href="productdetails.php?id=10" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Lavender Cloud Spray</h3>
                <p class="product-description">Soothing lavender mist for relaxation</p>
                <p class="product-price">£17.99</p>
                <button class="hero-btn add-to-cart" data-id="10">Add to Cart</button>
              </div>
            </div>

            <div class="product-card" data-category="home-spray" data-id="11">
              <div class="product-image">
                <img src="assets/images/homespraygold.jpeg" alt="Jasmine Home Spray">
                <div class="product-overlay">
                  <a href="productdetails.php?id=11" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Jasmine Home Spray</h3>
                <p class="product-description">Exotic jasmine room freshener</p>
                <p class="product-price">£19.99</p>
                <button class="hero-btn add-to-cart" data-id="11">Add to Cart</button>
              </div>
            </div>

            <div class="product-card" data-category="home-spray" data-id="12">
              <div class="product-image">
                <img src="assets/images/homesprayblue.jpeg" alt="Ocean Breeze Spray">
                <div class="product-overlay">
                  <a href="productdetails.php?id=12" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Ocean Breeze Spray</h3>
                <p class="product-description">Fresh coastal air room spray</p>
                <p class="product-price">£16.99</p>
                <button class="hero-btn add-to-cart" data-id="12">Add to Cart</button>
              </div>
            </div>

            <!-- BODY WASH (3 products) -->
            <div class="product-card" data-category="body-wash" data-id="13">
              <div class="product-image">
                <img src="assets/images/tropicalbreeze.jpeg" alt="Tropical Breeze Body Wash">
                <div class="product-overlay">
                  <a href="productdetails.php?id=13" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Tropical Breeze Body Wash</h3>
                <p class="product-description">Exotic tropical fruit cleansing wash</p>
                <p class="product-price">£8.99</p>
                <button class="hero-btn add-to-cart" data-id="13">Add to Cart</button>
              </div>
            </div>

            <div class="product-card" data-category="body-wash" data-id="14">
              <div class="product-image">
                <img src="assets/images/strawbsilk.jpeg" alt="Strawberry Silk Body Wash">
                <div class="product-overlay">
                  <a href="productdetails.php?id=14" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Strawberry Silk Body Wash</h3>
                <p class="product-description">Sweet strawberry with silk proteins</p>
                <p class="product-price">£9.99</p>
                <button class="hero-btn add-to-cart" data-id="14">Add to Cart</button>
              </div>
            </div>

            <div class="product-card" data-category="body-wash" data-id="15">
              <div class="product-image">
                <img src="assets/images/ultrafresh.jpeg" alt="Ultra Fresh Body Wash">
                <div class="product-overlay">
                  <a href="productdetails.php?id=15" class="hero-btn">View Details</a>
                </div>
              </div>
              <div class="product-info">
                <h3>Ultra Fresh Body Wash</h3>
                <p class="product-description">Deep cleansing with mint freshness</p>
                <p class="product-price">£7.99</p>
                <button class="hero-btn add-to-cart" data-id="15">Add to Cart</button>
              </div>
            </div>
          </div>
        </div>
      </section>
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
      <p>© 2025 Sabil. All rights reserved.</p>
    </div>
  </footer>
  
    <script src="assets/js/product.js"></script>

  <script>
      function money(n) { return "£" + Number(n || 0).toFixed(2); }

      async function updateNavbar() {
        const slot = document.getElementById('accountNavSlot');
        if (!slot) return;

        try {
          const res = await fetch('check_login.php', {
            cache: 'no-store',
            credentials: 'same-origin'
          });

          if (!res.ok) throw new Error('Network response was not ok');

          const data = await res.json();

          if (data.loggedIn) {
            slot.innerHTML = `
              <a class="action account" href="customer_dashboard.php" role="button" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; margin-right: 8px;">
                <img class="icon" src="assets/images/user.png" alt="My Account" style="width: 24px; height: 24px;" />
                <span class="action-text" style="color: var(--text-dark);">My Account</span>
              </a>
              <a class="action" href="logout.php" role="button" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                <span class="action-text" style="color: var(--text-dark);">Logout</span>
              </a>
            `;
          } else {
            slot.innerHTML = `
              <a class="action account" href="login.html" role="button" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                <img class="icon" src="assets/images/sign-in.png" alt="Sign in" style="width: 24px; height: 24px;" />
                <span class="action-text" style="color: var(--text-dark);">Sign in</span>
              </a>
            `;
          }
        } catch (e) {
          console.error("Navbar update failed:", e);
          slot.innerHTML = `
            <a class="action account" href="login.html" role="button" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
              <img class="icon" src="assets/images/user.png" alt="Sign in" style="width: 24px; height: 24px;" />
              <span class="action-text" style="color: var(--text-dark);">Sign in</span>
            </a>
          `;
        }
      }

      document.addEventListener('DOMContentLoaded', updateNavbar);
      window.addEventListener('pageshow', updateNavbar);
      setInterval(updateNavbar, 30000);
  </script>
</body>
</html>