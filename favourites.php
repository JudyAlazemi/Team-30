<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    header("Content-Type: application/json");

    $action = $_POST["action"];
    $userId = $_SESSION["user_id"] ?? null;

    // Block guests from using favourites
    if (!$userId) {
        echo json_encode([
            "status" => "error",
            "requireLogin" => true,
            "message" => "You need to login to choose a favourite.",
            "loginUrl" => "login.html"
        ]);
        exit;
    }

    // LIST favourites for logged-in user only
    if ($action === "list") {
        $stmt = $conn->prepare("SELECT product_id FROM favourites WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
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

    // TOGGLE favourite for logged-in user only
    if ($action === "toggle") {
        $productId = (int)($_POST["product_id"] ?? 0);

        if ($productId <= 0) {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid product"
            ]);
            exit;
        }

        // Check if already exists
        $stmt = $conn->prepare("SELECT id FROM favourites WHERE user_id = ? AND product_id = ? LIMIT 1");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $stmt->store_result();

        // Remove favourite
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($favId);
            $stmt->fetch();
            $stmt->close();

            $del = $conn->prepare("DELETE FROM favourites WHERE id = ?");
            $del->bind_param("i", $favId);
            $del->execute();

            echo json_encode([
                "status" => "success",
                "favourited" => false
            ]);
            exit;
        }

        $stmt->close();

        // Add favourite
        $ins = $conn->prepare("INSERT INTO favourites (user_id, product_id) VALUES (?, ?)");
        $ins->bind_param("ii", $userId, $productId);
        $ins->execute();

        echo json_encode([
            "status" => "success",
            "favourited" => true
        ]);
        exit;
    }

    echo json_encode([
        "status" => "error",
        "message" => "Unknown action"
    ]);
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
    <script defer src="assets/js/home.js"></script>
    <script defer src="assets/js/newsletter.js"></script>

    <link rel="icon" type="image/png" href="images/logo.png">
</head>
<body class="page-favourites">

<?php include __DIR__ . "/partials/navigation.php"; ?>

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

<script>
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

    if (data.loggedIn && data.role === "admin") {
      slot.innerHTML = `
        <a class="action" href="admin_dashboard.php" role="button">
          <img class="icon" src="assets/images/user.png" alt="My Account" />
          <span class="action-text">My Account</span>
        </a>
        <a class="action" href="admin_logout.php" role="button">
          <span class="action-text">Logout</span>
        </a>
      `;
    } else if (data.loggedIn && data.role === "customer") {
      slot.innerHTML = `
        <a class="action" href="customer_dashboard.php" role="button">
          <img class="icon" src="assets/images/user.png" alt="My Account" />
          <span class="action-text">My Account</span>
        </a>
        <a class="action" href="logout.php" role="button">
          <span class="action-text">Logout</span>
        </a>
      `;
    } else {
      slot.innerHTML = `
        <a class="action" href="login.html?redirect=checkout.php" role="button">
          <img class="icon" src="assets/images/sign-in.png" alt="Sign in" />
          <span class="action-text">Sign in</span>
        </a>
      `;
    }
  } catch (e) {
    slot.innerHTML = `
      <a class="action" href="login.html?redirect=checkout.php" role="button">
        <img class="icon" src="assets/images/sign-in.png" alt="Sign in" />
        <span class="action-text">Sign in</span>
      </a>
    `;
  }
}
</script>


</body>
</html>