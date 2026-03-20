<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$adminId = (int)($_SESSION["admin_id"] ?? 0);
$adminName = $_SESSION["admin_name"] ?? "Admin";

$successMsg = "";
$errorMsg = "";
$messages = [];

/* Handle admin reply */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $messageId = (int)($_POST["message_id"] ?? 0);
    $adminReply = trim($_POST["admin_reply"] ?? "");

    if ($messageId <= 0 || $adminReply === "") {
        $errorMsg = "Please write a reply before sending.";
    } else {
        $stmt = $conn->prepare("
            UPDATE customer_messages
            SET admin_reply = ?, status = 'replied', replied_at = NOW(), replied_by = ?
            WHERE id = ?
        ");

        if ($stmt) {
            $stmt->bind_param("sii", $adminReply, $adminId, $messageId);

            if ($stmt->execute()) {
                $successMsg = "Reply sent successfully.";
            } else {
                $errorMsg = "Failed to send reply. Please try again.";
            }

            $stmt->close();
        } else {
            $errorMsg = "Unable to prepare reply request.";
        }
    }
}

/* Fetch all customer messages */
$stmt = $conn->prepare("
    SELECT 
        cm.id,
        cm.user_id,
        cm.name,
        cm.email,
        cm.subject,
        cm.message,
        cm.admin_reply,
        cm.status,
        cm.created_at,
        cm.replied_at,
        u.name AS customer_name
    FROM customer_messages cm
    LEFT JOIN users u ON cm.user_id = u.id
    ORDER BY cm.created_at DESC
");

if ($stmt) {
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
  <title>Admin Messages</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/admin_dashboard.css?v=<?= time() ?>">
  <link rel="stylesheet" href="assets/css/darkmode.css">
  <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>
<body class="account-page admin-messages-page">

<?php
$forceIsAdmin = true;
$forceIsUser = false;
include __DIR__ . "/partials/navigation.php";
?>

<div class="dash-page">
  <div class="dash-frame">
    <div class="dash-grid">

      <aside class="dash-left">
        <div class="dash-hello">
          <h3>Hello <?= htmlspecialchars($adminName) ?>,</h3>
          <p>Welcome back!</p>
        </div>

        <nav class="dash-menu">
          <a class="dash-link" href="admin_dashboard.php">
            <span class="dash-ico"><img src="assets/images/user.png" alt=""></span>
            <span>Dashboard</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link is-active" href="admin_messages.php">
            <span class="dash-ico"><img src="/images/message.png" alt=""></span>
            <span>Customer Messages</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_orders.php">
            <span class="dash-ico"><img src="assets/images/processorder.png" alt=""></span>
            <span>Process Orders</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_users.php">
            <span class="dash-ico"><img src="assets/images/sign-in.png" alt=""></span>
            <span>Customers</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_products.php">
            <span class="dash-ico"><img src="assets/images/inventory.png" alt=""></span>
            <span>Inventory</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="incoming_orders.php">
            <span class="dash-ico"><img src="assets/images/incoming-order.png" alt=""></span>
            <span>Incoming Orders</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_returns.php">
            <span class="dash-ico"><img src="assets/images/return.png" alt=""></span>
            <span>Return Requests</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_review.php">
            <span class="dash-ico"><img src="/images/reviews.png" alt=""></span>
            <span>Manage Reviews</span>
            <span class="dash-arrow">›</span>
          </a>

          <a class="dash-link" href="admin_logout.php">
            <span class="dash-ico"><img src="assets/images/settings.png" alt=""></span>
            <span>Logout</span>
            <span class="dash-arrow">›</span>
          </a>
        </nav>
      </aside>

      <main class="dash-right">
        <h1 class="dash-title">Admin Messages</h1>
        <div class="dash-rule"></div>

        <?php if ($successMsg !== ''): ?>
          <div class="admin-msg-success">
            <?= htmlspecialchars($successMsg) ?>
          </div>
        <?php endif; ?>

        <?php if ($errorMsg !== ''): ?>
          <div class="admin-msg-error">
            <?= htmlspecialchars($errorMsg) ?>
          </div>
        <?php endif; ?>

        <?php if (empty($messages)): ?>
          <div class="admin-message-box">
            <p class="admin-empty">No customer messages found yet.</p>
          </div>
        <?php else: ?>
          <div class="admin-message-list">
            <?php foreach ($messages as $msg): ?>
              <article class="admin-message-card">
                <div class="admin-message-top">
                  <div class="admin-message-user">
                    <h3><?= htmlspecialchars($msg['customer_name'] ?: $msg['name'] ?: 'Customer') ?></h3>
                    <p><?= htmlspecialchars($msg['email']) ?></p>
                  </div>

                  <span class="admin-status <?= strtolower($msg['status']) === 'replied' ? 'replied' : 'sent' ?>">
                    <?= htmlspecialchars(ucfirst($msg['status'])) ?>
                  </span>
                </div>

                <div class="admin-message-grid">
                  <div class="admin-message-field admin-message-subject-wrap">
                    <div class="admin-label">Subject</div>
                    <div class="admin-value">
                      <?= htmlspecialchars($msg['subject'] ?: 'No subject') ?>
                    </div>
                  </div>

                  <div class="admin-message-field">
                    <div class="admin-label">Sent</div>
                    <div class="admin-value">
                      <?= htmlspecialchars($msg['created_at']) ?>
                    </div>
                  </div>
                </div>

                <div class="admin-message-field">
                  <div class="admin-label">Customer message</div>
                  <div class="admin-message-text">
                    <?= nl2br(htmlspecialchars($msg['message'])) ?>
                  </div>
                </div>

                <?php if (!empty($msg['admin_reply'])): ?>
                  <div class="admin-reply-box">
                    <div class="admin-label">Admin reply</div>
                    <div class="admin-message-text">
                      <?= nl2br(htmlspecialchars($msg['admin_reply'])) ?>
                    </div>
                    <div class="admin-reply-time">
                      Replied at: <?= htmlspecialchars($msg['replied_at']) ?>
                    </div>
                  </div>
                <?php else: ?>
                  <form method="POST" action="admin_messages.php" class="admin-reply-form">
                    <input type="hidden" name="message_id" value="<?= (int)$msg['id'] ?>">

                    <label class="admin-label" for="reply_<?= (int)$msg['id'] ?>">
                      Write reply
                    </label>

                    <textarea
                      id="reply_<?= (int)$msg['id'] ?>"
                      name="admin_reply"
                      rows="5"
                      required
                      placeholder="Write your reply here..."
                      class="admin-reply-textarea"
                    ></textarea>

                    <button type="submit" class="dash-btn-outline">
                      Send Reply
                    </button>
                  </form>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="dash-bottom">
          <a class="dash-back" href="admin_dashboard.php">Back to dashboard</a>
        </div>
      </main>

    </div>
  </div>
</div>

</body>
</html>