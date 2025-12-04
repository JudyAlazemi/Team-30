<?php
session_start();
require_once __DIR__ . "/backend/config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    header("Content-Type: application/json");

    $action     = $_POST["action"];
    $userId     = $_SESSION["user_id"] ?? null;
    $sessionId  = session_id();

    // Determine WHERE clause
    if ($userId) {
        $where  = "user_id = ?";
        $types  = "i";
        $params = [$userId];
    } else {
        $where  = "session_id = ?";
        $types  = "s";
        $params = [$sessionId];
    }

    // 1️⃣ LIST favourites
    if ($action === "list") {
        $stmt = $conn->prepare("SELECT product_id FROM favourites WHERE $where");
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $res = $stmt->get_result();

        $ids = [];
        while ($row = $res->fetch_assoc()) {
            $ids[] = (int)$row["product_id"];
        }

        echo json_encode([
            "status" => "success",
            "favourites" => $ids
        ]);
        exit;
    }

    // 2️⃣ TOGGLE favourite
    if ($action === "toggle") {
        $productId = (int)($_POST["product_id"] ?? 0);
        if ($productId <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid product"]);
            exit;
        }

        // Check if exists
        $stmt = $conn->prepare("SELECT id FROM favourites WHERE product_id = ? AND $where LIMIT 1");
        $stmt->bind_param("i" . $types, $productId, ...$params);
        $stmt->execute();
        $stmt->store_result();

        // Remove
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($favId);
            $stmt->fetch();
            $stmt->close();

            $del = $conn->prepare("DELETE FROM favourites WHERE id = ?");
            $del->bind_param("i", $favId);
            $del->execute();

            echo json_encode(["status" => "success", "favourited" => false]);
            exit;
        }

        // Add
        $stmt->close();

        if ($userId) {
            $ins = $conn->prepare("INSERT INTO favourites (user_id, product_id) VALUES (?, ?)");
            $ins->bind_param("ii", $userId, $productId);
        } else {
            $ins = $conn->prepare("INSERT INTO favourites (session_id, product_id) VALUES (?, ?)");
            $ins->bind_param("si", $sessionId, $productId);
        }
        $ins->execute();

        echo json_encode(["status" => "success", "favourited" => true]);
        exit;
    }

    echo json_encode(["status" => "error", "message" => "Unknown action"]);
    exit;
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Favourites | Sabil</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/static.css" /> 
     <script defer src="assets/js/nav.js"></script>
    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>

    <link rel="icon" type="image/png" href="images/logo.png">
</head>
<body class="page-favourites">

  <!-- TOP BAR -->

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




  <!-- MAIN FAVOURITES CONTENT -->
<main class="favourites-main" style="text-align:center;">
    <section class="favourites-header">
      <h1>Your Favourites</h1>
      <p>Perfumes you have saved.</p>
    </section>

    <!-- Empty message -->
    <section id="empty-state" class="favourites-empty">
      <p>You don’t have any favourites yet.</p>
    </section>

    <!-- Grid where fav.js will inject cards -->
    <section id="favourites-grid" class="favourites-grid">
      <!-- JS injects cards here -->
    </section>

  </main>

  <!-- FOOTER -->
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

  <script src="assets/js/fav.js" defer></script>
  <script src="assets/js/newsletter.js" defer></script>
  <script src="assets/js/products-data.js"></script>
  <script src="assets/js/productdetails.js"></script>

</body>
</html>