<?php
require_once __DIR__ . "/backend/config/session.php";
if (file_exists(__DIR__ . "/backend/config/db.php")) {
    require_once __DIR__ . "/backend/config/db.php";
}
$productId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$reviews = [];
$reviewCount = 0;
$avgRating = 0;

if ($productId > 0) {
    // Check first if reviews table exists
    $tableExists = false;
    $checkTable = $conn->query("SHOW TABLES LIKE 'reviews'");

    if ($checkTable && $checkTable->num_rows > 0) {
        $tableExists = true;
    }

    if ($tableExists) {
        // Get reviews
        $sql = "
            SELECT r.rating, r.comment, r.created_at, u.name
            FROM `reviews` r
            INNER JOIN `users` u ON r.user_id = u.id
            WHERE r.product_id = ?
            ORDER BY r.created_at DESC
        ";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }

            $stmt->close();
        }

        // Get review count and average rating
        $avgSql = "
            SELECT COUNT(*) AS total_reviews, AVG(rating) AS avg_rating
            FROM `reviews`
            WHERE product_id = ?
        ";

        $avgStmt = $conn->prepare($avgSql);

        if ($avgStmt) {
            $avgStmt->bind_param("i", $productId);
            $avgStmt->execute();
            $avgData = $avgStmt->get_result()->fetch_assoc();

            $reviewCount = (int)($avgData['total_reviews'] ?? 0);
            $avgRating = !empty($avgData['avg_rating']) ? round($avgData['avg_rating']) : 0;

            $avgStmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details | SABIL Perfumes</title>
     <link rel="stylesheet" href="assets/css/style.css">
    <link rel ="icon" type="image/png" href="assets/images/logo.png">
    <script defer src ="assets/js/nav.js"></script>
    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>
    <link rel="stylesheet" href="assets/css/globals.css">
    <link rel="stylesheet" href="assets/css/product.css">
</head>

<body class="page-product-details" data-logged-in="<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>">
<?php include __DIR__ . "/partials/navigation.php"; ?>



        <main>
        <section class="product-details-section">
            <div class="container">
                <a href="products.php" class="back-link">&larr; Back to Products</a>
                
                <div class="product-details-grid">
                    <div class="product-images">
                        <div class="main-image">
                            <img id="mainProductImage" src="assets/images/perfume9.jpg" alt="Product Image">
                        </div>
                        
                    </div>
                    <div class="product-details-content">

                    

                
                    
                        <a class="product-rating-link" href="#reviews">
                              <span class="rating-stars" aria-label="<?= $avgRating ?> out of 5 stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                  <?= $i <= $avgRating ? '★' : '☆' ?>
                                <?php endfor; ?>
                              </span>
                              <span class="rating-count">(<?= $reviewCount ?> reviews)</span>
                            </a>                
                       <p class="product-price-large" id="productPrice">£59.99</p>
                        
                        <div class="product-description-section">
                            <h3>Description</h3>
                            <p id="productDescription">
                                A clean, refreshing scent recalls ocean waves and fresh coastal air. It's a light, everyday fragrance that will make you feel refreshed.
                            </p>
                        </div>

                        <!-- Fragrance notes section -->
                        <div class="product-notes">
                            <h3>Fragrance Notes</h3>
                            <div class="notes-grid">
                                <div class="note-item">
                                    <strong>Top Notes:</strong>
                                    <p data-note="top">Sea Salt, Bergamot, Marine Notes</p>
                                </div>
                                <div class="note-item">
                                    <strong>Heart Notes:</strong>
                                    <p data-note="heart">Lavender, Sage, Ocean Breeze</p>
                                </div>
                                <div class="note-item">
                                    <strong>Base Notes:</strong>
                                    <p data-note="base">Amber, Musk, Driftwood</p>
                                </div>
                            </div>
                        </div>

                        <!-- Product specs section -->
                        <div class="product-specs">
                            <h3>Product Information</h3>
                            <ul>
                                <li><strong>Size:</strong> <span data-spec="size">100ml / 3.4 fl oz</span></li>
                                <li><strong>Concentration:</strong> <span data-spec="concentration">Eau de Toilette</span></li>
                                <li><strong>Gender:</strong> <span data-spec="gender">Unisex</span></li>
                                <li><strong>Longevity:</strong> <span data-spec="longevity">6-8 hours</span></li>
                                <li><strong>Sillage:</strong> <span data-spec="sillage">Moderate</span></li>
                            </ul>
                        </div>

                        <div class="product-actions">
                            <div class="quantity-selector">
                                <label for="quantity">Quantity:</label>
                                <div class="quantity-controls">
                                    <button class="qty-btn" onclick="decreaseQuantity()">-</button>
                                    <input type="number" id="quantity" value="1" min="1" max="10">
                                    <button class="qty-btn" onclick="increaseQuantity()">+</button>
                                    <button class="hero-btn favourite-btn" id="favBtnDetails">♡ Favourite</button>

                                </div>
                            </div>
                            <button class="hero-btn add-to-cart-btn" onclick="addToCart()">Add to Cart</button>
                            <button class="hero-btn buy-now-btn" onclick="buyNow()">Buy Now</button>
                        </div>

                        <div class="product-features">
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Free shipping on orders over £150</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>30-day money-back guarantee</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Authentic & cruelty-free</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Products Section -->
                <div class="related-products">
                    <h2>You May Also Like</h2>
                    <div class="related-products-grid">
                        <div class="product-card-small">
                            <img src="assets/images/midnightoud.png" alt="Midnight Oud">
                            <h4>Midnight Oud</h4>
                            <p>£69.99</p>
                            <a href="productdetails.php?id=2" class="hero-btn">View Details</a>
                        </div>
                        <div class="product-card-small">
                            <img src="assets/images/velvetmusk.jpeg" alt="Velvet Rose">
                            <h4>Velvet Rose</h4>
                            <p>£64.99</p>
                            <a href="productdetails.php?id=3" class="hero-btn">View Details</a>
                        </div>
                        <div class="product-card-small">
                            <img src="assets/images/carperfmed.png" alt="Lavender Cruise">
                            <h4>Lavender Cruise</h4>
                            <p>£18.99</p>
                            <a href="productdetails.php?id=6" class="hero-btn">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<!-- REVIEWS SECTION -->
<section class="reviews-section" id="reviews">
  <div class="container">

    <div class="reviews-header">
      <div class="reviews-title">
        <h2>Customer Reviews</h2>
        <p>See what customers think about this fragrance.</p>
      </div>

      <button type="button" class="add-review-btn" id="openReviewForm" aria-label="Add a review">
        +
      </button>
    </div>

    <div class="review-form-wrapper" id="reviewFormWrapper" aria-hidden="true">
      <form class="review-form" action="submit_review.php" method="POST">
        <input type="hidden" name="product_id" value="<?= $productId ?>">

        <div class="review-form-grid">
          <div class="form-row">
            <label for="reviewName">Name</label>
            <input
              id="reviewName"
              type="text"
              value="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>"
              placeholder="Enter your name"
            >
          </div>

          <div class="form-row">
            <label>Rating</label>
            <div class="star-input" aria-label="Select a rating">
              <input type="radio" id="star5" name="rating" value="5" required>
              <label for="star5" title="5 stars">★</label>

              <input type="radio" id="star4" name="rating" value="4">
              <label for="star4" title="4 stars">★</label>

              <input type="radio" id="star3" name="rating" value="3">
              <label for="star3" title="3 stars">★</label>

              <input type="radio" id="star2" name="rating" value="2">
              <label for="star2" title="2 stars">★</label>

              <input type="radio" id="star1" name="rating" value="1">
              <label for="star1" title="1 star">★</label>
            </div>
          </div>

          <div class="form-row form-row--full">
            <label for="reviewText">Your Review</label>
            <textarea
              id="reviewText"
              name="comment"
              rows="4"
              placeholder="Write your review..."
              required
            ></textarea>
          </div>
        </div>

        <div class="review-form-actions">
          <button type="submit" class="hero-btn submit-review-btn">Submit Review</button>
        </div>
      </form>
    </div>

    <div class="reviews-grid">
      <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
          <article class="review-card">
            <div class="review-card-head">
              <h3 class="review-name"><?= htmlspecialchars($review['name']) ?></h3>
              <span class="review-date"><?= date("d M Y", strtotime($review['created_at'])) ?></span>
            </div>

            <div class="review-stars" aria-label="<?= (int)$review['rating'] ?> out of 5 stars">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <?= $i <= (int)$review['rating'] ? '★' : '☆' ?>
              <?php endfor; ?>
            </div>

            <p class="review-text"><?= htmlspecialchars($review['comment']) ?></p>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="no-reviews">No reviews yet. Be the first to review this product.</p>
      <?php endif; ?>
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

<script>
const openBtn = document.getElementById("openReviewForm");
const wrap = document.getElementById("reviewFormWrapper");
const reviewsSection = document.getElementById("reviews");

function openFormAndScroll() {
  if (!wrap || !reviewsSection) return;

  wrap.classList.add("active");
  wrap.setAttribute("aria-hidden", "false");
  reviewsSection.scrollIntoView({ behavior: "smooth", block: "start" });

  setTimeout(() => {
    const firstInput = wrap.querySelector("input, textarea");
    if (firstInput) firstInput.focus();
  }, 400);
}

if (openBtn) {
  openBtn.addEventListener("click", openFormAndScroll);
}


</script>

<script src="product-data.php"></script>
<script src="assets/js/productdetails.js"></script>


<script>
async function updateNavbar() {
  const userBtn = document.getElementById('userBtn');
  const userText = document.getElementById('userText');
  const userIcon = document.getElementById('userIcon');

  if (!userBtn || !userText || !userIcon) return;

  try {
    const res = await fetch('check_login.php', {
      cache: 'no-store',
      credentials: 'same-origin'
    });

    if (!res.ok) throw new Error('Network response was not ok');

    const data = await res.json();

    if (data.loggedIn) {
      userBtn.href = 'customer_dashboard.php';
      userText.textContent = 'My Account';
      userIcon.src = 'assets/images/user.png';
      userIcon.alt = 'My Account';
    } else {
      userBtn.href = 'login.html';
      userText.textContent = 'Sign in';
      userIcon.src = 'assets/images/sign-in.png';
      userIcon.alt = 'Sign in';
    }
  } catch (e) {
    console.error('Navbar update failed:', e);
  }
}

document.addEventListener('DOMContentLoaded', updateNavbar);
window.addEventListener('pageshow', updateNavbar);
</script>


  </body>
</html> 