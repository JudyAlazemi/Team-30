<?php
require_once __DIR__ . "/includes/account_layout.php";
$active = "profile";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Profile</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="customer_dashboard.css">
</head>
<body class="account-page">

<div class="account-shell">

  <aside class="account-side">
    <h3 class="side-title">Sabil Account</h3>
    <p class="side-sub">Welcome, <?= htmlspecialchars($userName) ?></p>

    <nav class="side-nav">
      <?= sideLink("customer_dashboard.php", "📌 Dashboard", false) ?>
      <?= sideLink("my_orders.php", "🧾 My Orders", false, $orderCount) ?>
      <?= sideLink("favourites.php", "🤍 Favourites", false, $favCount) ?>
      <?= sideLink("messages.php", "💬 Messages", false) ?>
      <?= sideLink("profile.php", "⚙️ Profile Settings", true) ?>
      <a class="side-link logout" href="logout.php"><span class="side-text">🚪 Logout</span></a>
    </nav>
  </aside>

  <main class="account-main">
    <section class="panel">
      <h1 class="panel-title">Account Details</h1>
      <div class="info-grid">
        <div class="kv"><b>Name:</b> <?= htmlspecialchars($userName) ?></div>
        <div class="kv"><b>Email:</b> <?= htmlspecialchars($email) ?></div>
      </div>
      <p style="margin-top:12px;">Next we can add: change password, edit name, update email.</p>
    </section>
  </main>

</div>

</body>
</html>
