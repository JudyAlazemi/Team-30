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

<h1>Profile</h1>
<p>Name: <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></p>
<p>User ID: <? (int)($_SESSION['user_id'] ?? 0) ?> </p>
<a href="logout.php">Sign out</a>
</body>
</html>