<?php
session_start();
require_once __DIR__ . "/config/db.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

$userId = (int)$_SESSION['user_id'];
$userName = $_SESSION['user_name'] ?? "Customer";
$email = "Not available";

$stmt = $conn->prepare("SELECT email, name FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
  $email = $row['email'] ?? $email;
  if (!empty($row['name'])) {
    $userName = $row['name'];
    $_SESSION['user_name'] = $userName; // keep session updated
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>My Account</title>

  <!-- your existing site CSS -->
  <link rel="stylesheet" href="style.css">

  <!-- dashboard add-on -->
  <link rel="stylesheet" href="account-dashboard.css">
</head>

<body class="account-page">

<!-- ✅ Keep your normal topbar if this page already has it -->
<!-- If you already include a topbar via PHP include, leave it as-is. -->

<div class="account-shell">

  <!-- Sidebar -->
  <aside class="account-side">
    <h3 class="side-title">Sabil Account</h3>
    <p class="side-sub">Welcome, <?= htmlspecialchars($userName) ?></p>

    <nav class="side-nav">
      <a class="side-link active" href="profile.php">📌 Dashboard</a>
      <a class="side-link" href="my_orders.php">🧾 My Orders</a>
      <a class="side-link" href="favourites.php">🤍 Favourites</a>
      <a class="side-link logout" href="logout.php">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main content -->
  <main class="account-main">

    <section class="panel">
      <h1 class="panel-title">Hi, <?= htmlspecialchars($userName) ?></h1>

      <div class="info-grid">
        <div class="kv"><b>Name:</b> <?= htmlspecialchars($userName) ?></div>
        <div class="kv"><b>Email:</b> <?= htmlspecialchars($email) ?></div>
      </div>

      <div class="action-row">
        <a class="btn-sabil btn-outline" href="index.php">Back to Home</a>
        <a class="btn-sabil" href="my_orders.php">My Orders</a>
        <a class="btn-sabil" href="favourites.php">Favourites</a>
        <a class="btn-sabil btn-outline" href="logout.php">Sign out</a>
      </div>
    </section>

  </main>

</div>

</body>
</html>
