<?php
session_start();
if(empty($_SESSION['user_id'])){
    header("Location: login.html");
    exit;
}
?>
<h1>My Orders</h1>
<p>No orders yet.</p>
<a href="profile.php">Back to profile</a>