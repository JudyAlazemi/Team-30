<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$userId   = (int)($_SESSION['user_id'] ?? 0);
$userName = $_SESSION['user_name'] ?? $_SESSION['name'] ?? 'Customer';

$productReviews = [];
$siteReviews = [];

/* -----------------------------
   Get customer's product reviews
------------------------------*/
try {
    $checkReviewsTable = $conn->query("SHOW TABLES LIKE 'reviews'");
    if ($checkReviewsTable && $checkReviewsTable->num_rows > 0) {
        $sql = "
            SELECT 
                r.id,
                r.rating,
                r.comment,
                r.created_at,
                r.product_id,
                p.name AS product_name
            FROM reviews r
            LEFT JOIN products p ON r.product_id = p.id
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
        ";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $productReviews[] = $row;
            }

            $stmt->close();
        }
    }
} catch (Exception $e) {
    $productReviews = [];
}

/* --------------------------
   Get customer's site reviews
---------------------------*/
try {
    $checkSiteReviewsTable = $conn->query("SHOW TABLES LIKE 'site_reviews'");
    if ($checkSiteReviewsTable && $checkSiteReviewsTable->num_rows > 0) {
        $sql = "
            SELECT 
                id,
                rating,
                comment,
                display_name,
                created_at
            FROM site_reviews
            WHERE user_id = ?
            ORDER BY created_at DESC
        ";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $siteReviews[] = $row;
            }

            $stmt->close();
        }
    }
} catch (Exception $e) {
    $siteReviews = [];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Reviews | Sabil</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/customer_dashboard.css?v=<?= time() ?>">
  
    <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>
<body class="account-page reviews-page">

<?php include __DIR__ . "/partials/navigation.php"; ?>

<main class="dash-page">
    <div class="dash-frame">
        <div class="dash-grid">

            <!-- LEFT SIDEBAR -->
            <aside class="dash-left">
                <div class="dash-hello">
                <h3>Hello <?= htmlspecialchars($userName) ?>,</h3>
                <p>Welcome back!</p>
                </div>

                <nav class="dash-menu">
                <a class="dash-link" href="customer_dashboard.php">
                    <span class="dash-ico"><img src="assets/images/user.png" alt=""></span>
                    <span>My Account</span>
                    <span class="dash-arrow">›</span>
                </a>

                <a class="dash-link" href="customer_orders.php">
                    <span class="dash-ico"><img src="assets/images/shopping-bag-filled.png" alt=""></span>
                    <span>Orders</span>
                    <span class="dash-arrow">›</span>
                </a>

                <a class="dash-link" href="customer_favourites.php">
                    <span class="dash-ico"><img src="assets/images/favorite-shaded.png" alt=""></span>
                    <span>Favourites</span>
                    <span class="dash-arrow">›</span>
                </a>

                <a class="dash-link" href="customer_messages.php">
                    <span class="dash-ico"><img src="/images/message.png" alt=""></span>
                    <span> Messages</span>
                    <span class="dash-arrow">›</span>
                </a>

                <a class="dash-link is-active" href="customer_review.php">
                    <span class="dash-ico"><img src="/images/reviews.png"></span>
                    <span> Reviews</span>
                    <span class="dash-arrow">›</span>
                </a>

                <a class="dash-link" href="customer_security.php">
                    <span class="dash-ico"><img src="assets/images/security-icon.png" alt=""></span>
                    <span>Login &amp; Security</span>
                    <span class="dash-arrow">›</span>
                </a>

                <a class="dash-link" href="customer_logout.php">
                    <span class="dash-ico">
                        <img src="assets/images/settings.png" alt="">
                    </span>
                    <span>Logout</span>
                    <span class="dash-arrow">›</span>
                </a>

                </nav>
            </aside>

            <!-- RIGHT SIDE -->
            <section class="dash-right">
                <h1 class="dash-title">My Reviews</h1>
                <hr class="dash-rule">

                <!-- PRODUCT REVIEWS -->
                <div class="dash-recent">
                    <div class="dash-recent-head">
                        <div>
                            <div class="dash-recent-title">Product Reviews</div>
                            <div class="dash-recent-sub">Your submitted product reviews</div>
                        </div>
                    </div>

                    <div class="dash-recent-body">
                        <?php if (!empty($productReviews)): ?>
                            <?php foreach ($productReviews as $review): ?>
                                <div class="dash-row">
                                    <div class="dash-cell review-item-title">
                                        <?= htmlspecialchars($review['product_name'] ?? ('Product #' . (int)$review['product_id'])) ?>
                                        <span class="review-date">
                                            <?= !empty($review['created_at']) ? date("d M Y", strtotime($review['created_at'])) : '' ?>
                                        </span>
                                    </div>

                                    <div class="dash-cell review-stars" aria-label="<?= (int)$review['rating'] ?> out of 5 stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?= $i <= (int)$review['rating'] ? '★' : '☆' ?>
                                        <?php endfor; ?>
                                    </div>

                                    <div class="dash-cell review-comment">
                                        <?= htmlspecialchars($review['comment']) ?>
                                    </div>

                                    <div class="dash-cell">
                                        <a href="productdetails.php?id=<?= (int)$review['product_id'] ?>#reviews" class="dash-back">
                                            View
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="dash-muted">No product reviews yet.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- SITE REVIEWS -->
                <div class="dash-recent" style="margin-top:20px;">
                    <div class="dash-recent-head">
                        <div>
                            <div class="dash-recent-title">Site Reviews</div>
                            <div class="dash-recent-sub">Your website feedback</div>
                        </div>
                    </div>

                    <div class="dash-recent-body">
                        <?php if (!empty($siteReviews)): ?>
                            <?php foreach ($siteReviews as $review): ?>
                                <div class="dash-row">
                                    <div class="dash-cell review-item-title">
                                        <?= htmlspecialchars(!empty($review['display_name']) ? $review['display_name'] : $userName) ?>
                                        <span class="review-date">
                                            <?= !empty($review['created_at']) ? date("d M Y", strtotime($review['created_at'])) : '' ?>
                                        </span>
                                    </div>

                                    <div class="dash-cell review-stars" aria-label="<?= (int)$review['rating'] ?> out of 5 stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?= $i <= (int)$review['rating'] ? '★' : '☆' ?>
                                        <?php endfor; ?>
                                    </div>

                                    <div class="dash-cell review-comment">
                                        <?= htmlspecialchars($review['comment']) ?>
                                    </div>

                                    <div class="dash-cell">
                                        <a href="index.php#reviews" class="dash-back">
                                            View
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="dash-muted">No site reviews yet.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dash-bottom">
                    <a href="products.php" class="dash-back">Browse Products</a>
                    <a href="customer_dashboard.php" class="dash-back">Back to Dashboard</a>
                </div>
            </section>

        </div>
    </div>
</main>

<footer class="site-footer">
    <div class="footer-container">
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

        <div class="footer-links">
            <a href="contactus.php">Contact Us</a>
            <a href="faq.php">FAQ</a>
            <a href="aboutus.php">About Us</a>
            <a href="terms.php">Terms</a>
            <a href="privacypolicy.php">Privacy Policy</a>
        </div>

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

<script defer src="assets/js/newsletter.js"></script>
<?php include __DIR__ . "/chatbot.php"; ?>

</body>
</html>