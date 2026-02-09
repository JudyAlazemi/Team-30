<?php
session_start();

if (empty($_SESSION['user_id'])) {
header("Location: login.html");
exit;
}

?>
<!doctype html>
<html lange="en">
<head>
    <meta charset="utf-8">
    <meta name="viewpoint"content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <link rel="stylesheet" href="assets/css/style.css">


</head>
<body>

<h1>Hi, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h1>

<section>
    <h2>Account details</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($_SESSION['user_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['user_email'] ?? 'Not available') ?></p>

</section>

<section>
    <h2>My account</h2>
    <ul>
        <li><a href="orders.php">My Orders</a></li>
      <li><a href="favourites.php">My Favourites</a></li>

    </ul>
</section>
<section>
    <a href="logout.php">Sign out</a>
</section>


</body>
</html>