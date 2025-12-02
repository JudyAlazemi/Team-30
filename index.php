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
    <title>Sabil</title>
    <link rel="stylesheet" href="/Team-30/assets/css/style.css" />

    <script defer src="assets/js/nav.js"></script>
    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>

    <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="page-home">

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

<!-- HERO SECTION -->
<section class="hero">
  <video class="hero-video" autoplay muted loop playsinline>
    <source src="assets/images/welcome.mp4" type="video/mp4">
  </video>

  <div class="hero-overlay"></div>

  <div class="hero-content">
    <h1>Let your scent<br>guide your journey.</h1>
    <a href="products.html" class="hero-btn">Shop now</a>
  </div>
</section>

<section class="new-collection">
  <div class="collection-header">
    <h2>New Collection</h2>
    <a href="products.html" class="view-more-btn">View More</a>
  </div>

  <!-- Track: grid on desktop, carousel on mobile -->
  <div class="collection-track" id="collectionTrack">
    <article class="product-card">
      <img src="assets/images/midnightoud.png" alt="Midnight Oud">
      <h3 class="card-title">Midnight Oud</h3>
    </article>
    <article class="product-card">
      <img src="assets/images/oceanmist.png" alt="Ocean Breeze">
      <h3 class="card-title">Ocean Breeze</h3>
    </article>
    <article class="product-card">
      <img src="assets/images/velvetmusk.JPEG" alt="Velvet Rose">
      <h3 class="card-title">Velvet Rose</h3>
    </article>

  </div>

  <!-- arrows OUTSIDE the track -->
  <div class="carousel-arrows">
    <button class="arrow-btn prev" aria-label="Previous perfume">‹</button>
    <button class="arrow-btn next" aria-label="Next perfume">›</button>
  </div>
</section>

<!-- Full-width image section -->
<section class="hero-image">
  <img src="assets/images/pic.png" alt="Shadow aesthetic">
</section>

<section class="signature-collection">
  <div class="collection-header">
    <h2>Signature Collection</h2>
    <a href="products.html" class="view-more-btn">View More</a>
  </div>

  <!-- Track: grid on desktop, carousel on mobile -->
  <div class="collection-track" id="signatureTrack">
    <article class="product-card">
      <img src="assets/images/candle.png" alt="Vanilla Dream">
      <h3 class="card-title">Vanilla Dream</h3>
    </article>

    <article class="product-card">
      <img src="assets/images/candle.png" alt="Amber Woods">
      <h3 class="card-title">Amber Woods</h3>
    </article>

    <article class="product-card">
      <img src="assets/images/candle.png" alt="Cherry Blossom">
      <h3 class="card-title">Cherry Blossom</h3>
    </article>

   

  <!-- Mobile arrows -->
  <div class="carousel-arrows signature-arrows">
    <button class="arrow-btn prev" aria-label="Previous perfume">‹</button>
    <button class="arrow-btn next" aria-label="Next perfume">›</button>
  </div>
</section>

<!-- FIND YOUR FRAGRANCE (banner CTA) -->
<section class="cta-quiz">
  <div class="cta-quiz__bg"></div>
  <div class="cta-quiz__content">
    <h2>find your fragrance</h2>
    <a href="#" class="cta-quiz__btn">Quiz Now</a>
  </div>
</section>

<!-- QUIZ POPUP -->
<div id="quizModal" class="quiz-modal" aria-hidden="true">
  <div class="quiz-backdrop" data-close-quiz></div>

  <div class="quiz-box" role="dialog" aria-modal="true" aria-label="Fragrance quiz">
    <button class="quiz-close" data-close-quiz aria-label="Close">×</button>

    <div class="quiz-step active" data-step="1">
      <h2>Find your fragrance</h2>

      <p>What vibe are you drawn to most?</p>
      <label><input type="radio" name="q1" value="fresh"> Fresh & airy</label>
      <label><input type="radio" name="q1" value="floral"> Soft & floral</label>
      <label><input type="radio" name="q1" value="woody"> Warm & woody</label>
      <label><input type="radio" name="q1" value="spicy"> Bold & spicy</label>

      <button class="quiz-next">Next</button>
    </div>

    <div class="quiz-step" data-step="2">
      <p>Ideal time to wear it?</p>
      <label><input type="radio" name="q2" value="day"> Daytime</label>
      <label><input type="radio" name="q2" value="night"> Night out</label>
      <div class="row">
        <button class="quiz-back">Back</button>
        <button class="quiz-next">Next</button>
      </div>
    </div>

    <div class="quiz-step" data-step="3">
      <p>Pick a moodboard color family</p>
      <label><input type="radio" name="q3" value="green"> Green / Herbal</label>
      <label><input type="radio" name="q3" value="pink"> Pink / Petals</label>
      <label><input type="radio" name="q3" value="amber"> Amber / Resin</label>
      <label><input type="radio" name="q3" value="blue"> Blue / Marine</label>
      <div class="row">
        <button class="quiz-back">Back</button>
        <button class="quiz-next">Next</button>
      </div>
    </div>

    <div class="quiz-step" data-step="4">
      <p>Where will you wear it most?</p>
      <label><input type="radio" name="q4" value="work"> Work / Uni</label>
      <label><input type="radio" name="q4" value="evenings"> Dates / Evenings</label>
      <label><input type="radio" name="q4" value="travel"> Travel / Outdoors</label>
      <label><input type="radio" name="q4" value="home"> Everyday / At home</label>
      <div class="row">
        <button class="quiz-back">Back</button>
        <button class="quiz-finish">Finish</button>
      </div>
    </div>

    <div class="quiz-step" data-step="result">
      <h2>Your fragrance match</h2>
      <p id="quizResultText">We think you’ll love Ocean Mist.</p>
      <a href="products.html" class="quiz-shop-btn">Shop Now</a>
    </div>
  </div>
</div>

<footer class="site-footer">
  <div class="footer-container">

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
    <p>© 2025 Sabil. All rights reserved.</p>
  </div>
</footer>

<script>
document.getElementById('searchForm').addEventListener('submit', function (e) {
  e.preventDefault(); // stop the default reload
  const query = document.getElementById('searchInput').value.trim();
  if (query) {
    // redirect to your search results page
    window.location.href = `/search.html?q=${encodeURIComponent(query)}`;
  }
});
</script>


</body>
</html>
