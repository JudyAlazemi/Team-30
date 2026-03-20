<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$userId = (int)($_GET['id'] ?? 0);

if ($userId <= 0) {
    header("Location: admin_users.php");
    exit;
}

$customer = null;
$error = "";

$stmt = $conn->prepare("
    SELECT id, name, email
    FROM users
    WHERE id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$stmt->close();

if (!$customer) {
    header("Location: admin_users.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Edit Customer</title>

  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/admin_dashboard.css?v=<?= time() ?>">

  <link rel="icon" type="image/png" href="assets/images/logo.png">
</head>

<body class="account-page">

<?php include __DIR__ . "/partials/navigation.php"; ?>

<main class="dash-page">
  <div class="dash-frame">
    <div class="dash-grid">

      <aside class="dash-left">
        <div class="dash-hello">
          <h3>Hello,</h3>
          <p><?= htmlspecialchars($_SESSION["admin_name"] ?? "Admin") ?></p>
        </div>
      </aside>

      <section class="dash-right">
        <h1 class="dash-title">Edit Customer</h1>
        <hr class="dash-rule">

        <div class="dash-section">
          <form method="POST" action="update_customer.php" class="admin-edit-form">
            <input type="hidden" name="user_id" value="<?= (int)$customer['id'] ?>">

            <div class="admin-form-group">
              <label>Name</label>
              <input type="text" name="name" value="<?= htmlspecialchars($customer['name']) ?>" required>
            </div>

            <div class="admin-form-group">
              <label>Email</label>
              <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>
            </div>

            <div class="dash-actions">
              <button type="submit" class="dash-back">Update Customer</button>
              <a href="admin_users.php" class="dash-secondary-btn">Back</a>
            </div>
          </form>
        </div>
      </section>

    </div>
  </div>
</main>

</body>
</html>