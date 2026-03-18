<?php

require_once __DIR__ . "/backend/config/session.php";
if (file_exists(__DIR__ . "/backend/config/db.php")) {
    require_once __DIR__ . "/backend/config/db.php";
}


$siteReviews = [];


$siteStmt = $conn->prepare("
    SELECT sr.rating, sr.comment, sr.created_at, sr.display_name
    FROM site_reviews sr
    ORDER BY sr.created_at DESC
    LIMIT 6
");



if ($siteStmt) {
    $siteStmt->execute();
    $siteResult = $siteStmt->get_result();

    while ($row = $siteResult->fetch_assoc()) {
        $siteReviews[] = $row;
    }
}



?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Sabil</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/darkmode.css">

    <script defer src="assets/js/nav.js"></script>
    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>

    <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>





<body class="page-home">

<?php include __DIR__ . "/partials/navigation.php"; ?>


<!-- HERO SECTION -->
<section class="hero">
  <video class="hero-video" autoplay muted loop playsinline>
    <source src="assets/images/welcome.mp4" type="video/mp4">
  </video>

  <div class="hero-overlay"></div>

  <div class="hero-content">
    <h1>Let your scent<br>guide your journey.</h1>
    <a href="products.php" class="hero-btn">Shop now</a>
  </div>
</section>



<section class="new-collection">
  <div class="collection-header">
    <h2>Fragrances</h2>
    <a href="products.php" class="view-more-btn">View More</a>
  </div>

  <!-- Track: grid on desktop, carousel on mobile -->
  <div class="collection-track" id="collectionTrack">
<article class="product-card" data-id="2">
  <a href="productdetails.php?id=2" class="card-link">
    <img src="assets/images/midnightoud.png" alt="Midnight Oud">
    <h3 class="card-title">Midnight Oud</h3>
  </a>
</article>

<article class="product-card" data-id="1">
  <a href="productdetails.php?id=1" class="card-link">
    <img src="assets/images/oceanmist.png" alt="Ocean Breeze">
    <h3 class="card-title">Ocean Breeze</h3>
  </a>
</article>

<article class="product-card" data-id="3">
  <a href="productdetails.php?id=3" class="card-link">
    <img src="assets/images/velvetmusk.jpeg" alt="Velvet Rose">
    <h3 class="card-title">Velvet Rose</h3>
  </a>
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
    <h2>Candles</h2>
    <a href="products.php" class="view-more-btn">View More</a>
  </div>

  <!-- Track: grid on desktop, carousel on mobile -->
  <div class="collection-track" id="signatureTrack">
  <article class="product-card" data-id="7">
  <a href="productdetails.php?id=7" class="card-link">
    <img src="assets/images/candle.png" alt="Vanilla Dream">
    <h3 class="card-title">Vanilla Dream Candle</h3>
  </a>
</article>

<article class="product-card" data-id="8">
  <a href="productdetails.php?id=8" class="card-link">
    <img src="assets/images/candle.png" alt="Amber Woods">
    <h3 class="card-title">Amber Woods Candle</h3>
  </a>
</article>

<article class="product-card" data-id="9">
  <a href="productdetails.php?id=9" class="card-link">
    <img src="assets/images/candle.png" alt="Cherry Blossom">
    <h3 class="card-title">Cherry Blossom Candle</h3>
  </a>
</article>


</div>

   

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
      <p id="quizResultText">We think you’ll love Ocean Breeze.</p>
      <a href="products.php" class="quiz-shop-btn">Shop Now</a>
    </div>
  </div>
</div>


<!-- REVIEWS SECTION -->
<section class="home-reviews-section" id="reviews">
  <div class="container">

    <div class="home-reviews-header">
      <div class="home-reviews-title">
        <h2>Customer Reviews</h2>
        <p>See what customers think about Sabil.</p>
      </div>

      <button type="button" class="home-add-review-btn" id="openReviewForm" aria-label="Add a review">
        +
      </button>
    </div>

    <div class="home-review-form-wrapper" id="reviewFormWrapper" aria-hidden="true">
<form class="home-review-form" action="submit_site_review.php" method="POST">         <div class="home-review-form-grid">

          <div class="home-form-row">
            <label for="reviewName">Name</label>
            <input
            id="reviewName"
            name="display_name"
            type="text"
            value="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>"
            placeholder="Enter your name"
            >
          </div>

          <div class="home-form-row">
            <label>Rating</label>
            <div class="home-star-input" aria-label="Select a rating">
<input type="radio" id="homeStar5" name="rating" value="5" required>
              <label for="homeStar5" title="5 stars">★</label>

<input type="radio" id="homeStar4" name="rating" value="4" >
              <label for="homeStar4" title="4 stars">★</label>

<input type="radio" id="homeStar3" name="rating" value="3">
              <label for="homeStar3" title="3 stars">★</label>

<input type="radio" id="homeStar2" name="rating" value="2">
              <label for="homeStar2" title="2 stars">★</label>

<input type="radio" id="homeStar1" name="rating" value="1">
              <label for="homeStar1" title="1 star">★</label>
            </div>
          </div>

          <div class="home-form-row home-form-row--full">
            <label for="reviewText">Your Review</label>
            <textarea id="reviewText" name="comment" rows="4" placeholder="Write your review..." required></textarea>
          </div>

        </div>

        <div class="home-review-form-actions">
<button type="submit" class="hero-btn home-submit-review-btn">Submit Review</button>        </div>
      </form>
    </div>

    <div class="home-reviews-carousel">
  <button class="review-arrow review-arrow-prev" id="reviewPrevBtn" aria-label="Previous review">‹</button>

  <div class="home-reviews-track" id="homeReviewsTrack">
    <?php if (!empty($siteReviews)): ?>
      <?php foreach ($siteReviews as $review): ?>
        <article class="home-review-card">
          <h3 class="home-review-name"><?= htmlspecialchars($review['display_name']) ?></h3>

          <span class="home-review-date">
            <?= date("d M Y", strtotime($review['created_at'])) ?>
          </span>

          <div class="home-review-stars" aria-label="<?= (int)$review['rating'] ?> out of 5 stars">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <?= $i <= (int)$review['rating'] ? '★' : '☆' ?>
            <?php endfor; ?>
          </div>

          <p class="home-review-text">
            <?= htmlspecialchars($review['comment']) ?>
          </p>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <article class="home-review-card">
        <h3 class="home-review-name">Sarah M.</h3>
        <span class="home-review-date">04 Mar 2026</span>
        <div class="home-review-stars">★★★★★</div>
        <p class="home-review-text">
          Beautiful experience from start to finish. The website was elegant and very easy to use.
        </p>
      </article>

      <article class="home-review-card">
        <h3 class="home-review-name">Layla A.</h3>
        <span class="home-review-date">02 Mar 2026</span>
        <div class="home-review-stars">★★★★☆</div>
        <p class="home-review-text">
          I loved the overall shopping experience and the design felt very premium.
        </p>
      </article>

      <article class="home-review-card">
        <h3 class="home-review-name">Huda K.</h3>
        <span class="home-review-date">28 Feb 2026</span>
        <div class="home-review-stars">★★★★★</div>
        <p class="home-review-text">
          Very smooth website and the brand presentation looks luxurious and professional.
        </p>
      </article>
    <?php endif; ?>
  </div>

  <button class="review-arrow review-arrow-next" id="reviewNextBtn" aria-label="Next review">›</button>
</div>
</section>




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



<script src="assets/js/products-data.js"></script>
<script src="assets/js/wire-products-lite.js"></script>
<script src="assets/js/render-products.js"></script>

<script>
document.addEventListener('DOMContentLoaded', ()=>{
  const nameToId = {
    'Midnight Oud': 2,
    'Ocean Breeze': 1,
    'Velvet Rose': 3,
    'Vanilla Dream': 7,
    'Amber Woods': 8,
    'Cherry Blossom': 9,
  };

  document.querySelectorAll('.collection-track .product-card').forEach(card=>{
    const name = card.querySelector('.card-title')?.textContent.trim();
    const id = nameToId[name] 
      || (window.productsData||[]).find(p => p.name.toLowerCase()===name?.toLowerCase())?.id;
    if(!id) return;

    card.dataset.id = id;
    card.style.cursor = 'pointer';
    card.addEventListener('click', (e)=>{
      if (e.target.closest('a,button')) return; // let real buttons/links work
      location.href = `productdetails.php?id=${id}`;
    });
  });
});
</script>

<script>
const homeOpenBtn = document.getElementById("openReviewForm");
const homeFormWrap = document.getElementById("reviewFormWrapper");

if (homeOpenBtn && homeFormWrap) {
  homeOpenBtn.addEventListener("click", () => {
    homeFormWrap.classList.toggle("active");
    homeFormWrap.setAttribute(
      "aria-hidden",
      homeFormWrap.classList.contains("active") ? "false" : "true"
    );
  });
}
</script>

<?php include __DIR__ . "/chatbot.php"; ?>
</body>
</html>