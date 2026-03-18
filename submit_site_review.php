<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$userId = (int) $_SESSION['user_id'];
$rating = isset($_POST['rating']) ? (int) $_POST['rating'] : 0;
$comment = trim($_POST['comment'] ?? '');
$displayName = trim($_POST['display_name'] ?? '');

if ($displayName === '') {
    $displayName = $_SESSION['name'] ?? 'Anonymous';
}

if ($rating < 1 || $rating > 5 || $comment === '') {
    header("Location: index.php?site_review_error=1#reviews");
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO site_reviews (user_id, rating, comment, display_name)
    VALUES (?, ?, ?, ?)
");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("iiss", $userId, $rating, $comment, $displayName);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

header("Location: index.php?site_review_success=1#reviews");
exit;
?>