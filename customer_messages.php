<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$userId = (int)$_SESSION['user_id'];
$userName = $_SESSION['user_name'] ?? 'Customer';
$messages = [];

$stmt = $conn->prepare("
    SELECT id, subject, message, admin_reply, status, created_at, replied_at
    FROM customer_messages
    WHERE user_id = ?
    ORDER BY created_at DESC
");

if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    $stmt->close();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Customer Messages</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/customer_dashboard.css">

  <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>
<body class="account-page messages-page">

<?php include __DIR__ . "/partials/navigation.php"; ?>

<div class="dash-page">
  <div class="dash-frame">
    <div class="dash-grid">

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

          <a class="dash-link is-active" href="customer_messages.php">
            <span class="dash-ico"><img src="/images/message.png" alt=""></span>
            <span> Messages</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="customer_review.php">
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

      <main class="dash-right">
        <h1 class="dash-title">Customer Messages</h1>
        <div class="dash-rule"></div>

        <?php if (empty($messages)): ?>
          <div class="saved-items-box">
            <p class="saved-empty">No messages found yet.</p>

            <div class="message-action-center">
              <a href="contactus.php" class="dash-btn-outline">Send a message</a>
            </div>
          </div>
        <?php else: ?>
          <div class="saved-items-box message-table-box">
            <div class="saved-head message-head">
              <div>Subject</div>
              <div>Sent</div>
              <div>Status</div>
              <div>Reply</div>
            </div>

            <?php foreach ($messages as $msg): ?>
              <div class="saved-row message-row">
                <div class="message-col subject-col">
                  <div class="message-subject">
                    <?= htmlspecialchars($msg['subject'] ?: 'No subject') ?>
                  </div>
                  <div class="message-preview">
                    <?= nl2br(htmlspecialchars($msg['message'])) ?>
                  </div>
                </div>

                <div class="message-col sent-col">
                  <?= htmlspecialchars($msg['created_at']) ?>
                </div>

                <div class="message-col status-col">
                  <span class="msg-status <?= strtolower($msg['status']) === 'replied' ? 'replied' : 'sent' ?>">
                    <?= htmlspecialchars(ucfirst($msg['status'])) ?>
                  </span>
                </div>

                <div class="message-col action-col">
                  <?php if (!empty($msg['admin_reply'])): ?>
                    <div class="admin-reply-mini">
                      <strong>Admin reply:</strong>
                      <div><?= nl2br(htmlspecialchars($msg['admin_reply'])) ?></div>
                      <small>Replied at: <?= htmlspecialchars($msg['replied_at']) ?></small>
                    </div>
                  <?php else: ?>
                    <div class="no-reply-mini">No admin reply yet.</div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="dash-bottom">
          <a class="dash-back" href="customer_dashboard.php">Back to dashboard</a>

          <?php if (!empty($messages)): ?>
            <a class="dash-btn-outline" href="contactus.php">Send another message</a>
          <?php endif; ?>
        </div>
      </main>

    </div>
  </div>
</div>

</body>
</html>