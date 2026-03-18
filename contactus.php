<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

$successMsg = "";
$errorMsg = "";

$isLoggedIn = isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0;
$userId = (int)($_SESSION['user_id'] ?? 0);
$userName = $_SESSION['user_name'] ?? '';
$userEmail = $_SESSION['user_email'] ?? '';

// If logged in but name/email missing in session, fetch from DB
if ($isLoggedIn && ($userName === '' || $userEmail === '')) {
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $userName = $row['name'] ?? '';
            $userEmail = $row['email'] ?? '';
            $_SESSION['user_name'] = $userName;
            $_SESSION['user_email'] = $userEmail;
        }
        $stmt->close();
    }
}

// Clear stale contact error if already logged in
if ($isLoggedIn && isset($_SESSION['contact_error'])) {
    unset($_SESSION['contact_error']);
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Re-check live login state at submit time
    $isLoggedIn = isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0;
    $userId = (int)($_SESSION['user_id'] ?? 0);

    if (!$isLoggedIn) {
        $_SESSION['redirect_after_login'] = 'contactus.php';
        $_SESSION['contact_error'] = 'Please login first to send a message.';
        header("Location: login.html");
        exit;
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $errorMsg = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Please enter a valid email address.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO customer_messages (user_id, name, email, subject, message, status)
            VALUES (?, ?, ?, ?, ?, 'sent')
        ");

        if ($stmt) {
            $stmt->bind_param("issss", $userId, $name, $email, $subject, $message);

            if ($stmt->execute()) {
                $successMsg = "Your message has been sent successfully.";

                // Clear old form values after success
                $_POST = [];

                // Optional refresh session values
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
            } else {
                $errorMsg = "Something went wrong. Please try again.";
            }

            $stmt->close();
        } else {
            $errorMsg = "Unable to prepare message request.";
        }
    }
}

// Show session error only if user is NOT logged in
if (!$isLoggedIn && isset($_SESSION['contact_error'])) {
    $errorMsg = $_SESSION['contact_error'];
    unset($_SESSION['contact_error']);
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

<header class="static-hero">
  <div class="static-wrap">
    <h1>Contact</h1>
  </div>
</header>

<main class="static-grid">
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

    <p><strong>Hours</strong><br> Mon-Thu: 10:00–16:00 <br> Fri–Sat: 10:00–14:00</p>

    <section class="contact-form-section">
      <div class="contact-form-header">
        <h2>Contact us</h2>
        <p>Got any questions or suggestions?<br>Fill out this form to reach out</p>
      </div>

      <?php if ($successMsg !== ''): ?>
        <div style="padding:12px 14px;margin-bottom:15px;border-radius:8px;background:#e8f5e9;color:#256029;">
          <?= htmlspecialchars($successMsg) ?>
        </div>
      <?php endif; ?>

      <?php if ($errorMsg !== ''): ?>
        <div style="padding:12px 14px;margin-bottom:15px;border-radius:8px;background:#fdecea;color:#b42318;">
          <?= htmlspecialchars($errorMsg) ?>
        </div>
      <?php endif; ?>

      <form class="contact-form" method="POST" action="contactus.php">
        <div class="contact-row">
          <input
            type="text"
            name="name"
            placeholder="Enter your name"
            required
            value="<?= htmlspecialchars($_POST['name'] ?? $userName) ?>"
          >

          <input
            type="email"
            name="email"
            placeholder="Enter your email"
            required
            value="<?= htmlspecialchars($_POST['email'] ?? $userEmail) ?>"
          >
        </div>

        <input
          type="text"
          name="subject"
          placeholder="Enter subject (optional)"
          style="width:100%;margin-bottom:16px;padding:14px;border:1px solid #ddd;border-radius:8px;"
          value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>"
        >

        <textarea
          name="message"
          placeholder="Enter your message"
          rows="6"
          required
        ><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

        <button type="submit" class="contact-send-btn">
          Send
        </button>

        <?php if (!$isLoggedIn): ?>
          <p class="contact-note" style="color:#b42318;">
            You must login first before sending a message.
          </p>
        <?php else: ?>
          <p class="contact-note">
            You can also view your sent messages in your dashboard.
          </p>
        <?php endif; ?>
      </form>
    </section>
  </section>

  <aside class="static-media">
    <img src="assets/images/flower.png" alt="Sabil studio" />
  </aside>
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
    <p>© <?= date('Y'); ?> Sabil. All rights reserved.</p>
  </div>
</footer>

</body>
</html>