<?php
session_start();

if (empty($_SESSION['user_id'])) {
header("Location: login.html");
exit;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>

    <link rel="stylesheet" href="assets/css/style.css"/>
    <link rel="stylesheet" href="assets/css/registerlogin.css"/>

   <script defer src="assets/js/nav.js"></script>
   <script defer src="assets/js/home.js"></script>
   <script defer src="assets/js/newsletter.js"></script>

   <link rel="icon" type="image/png" href="assets/images/logo.png">

</head>

<body class="page-home">

<header class="topbar">
    <div class="topbar-inner">

    <button class="icon-btn menu-toggle" aria-label="Open menu" aria-expanded="false">
    <img class="icon icon--menu" src="assets/images/menu.png" alt="" />
    <img class="icon icon--close" src="assets/images/close.png" alt="" />
     
</button>
     <a class="brand" href="index.php">
     <img class="brand-logo" src="assets/images/logo.png" alt="Sabil" />
</a>

<nav class="actions" aria-label="Account & tools">
    <?php include __DIR__ . "/partials/navbar.php"; ?>

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
    <a id="favBtn" class="action" href="favourites.php">
        <img class="icon" src="assets/images/favorite.png" alt="Favourites" />

    </a>

     <a id="bagBtn" class="action" href="cart.html">
        <img class="icon" src="assets/images/shopping-bag.png" alt="Shopping bag" />
      </a>
   </nav>

   </div>
</header>



<main class="content profile-page">
    <h1>Hi, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h1>

<a href="index.php" class="btn-primary back-btn"> Back to Home</a>
<section>
    <h2>Account details</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['user_email'] ?? 'Not available') ?></p>

</section>

<section>
    <h2>My account</h2>
    
      <div class="profile-actions">
      <a href="orders.php" class="btn-primary">My Orders</a>
      <a href="favourites.php" class="btn-primary">Favourites</a>
     <a href="logout.php" class="btn-primary">Sign out</a>
    </div>
    
</section>


</main>

</body>
</html>