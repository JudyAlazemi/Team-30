<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php#reviews");
    exit;
}

$userId = (int)($_SESSION['user_id'] ?? 0);
$rating = (int)($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');
$displayName = trim($_POST['display_name'] ?? ($_SESSION['name'] ?? ''));

if ($displayName === '') {
    $displayName = $_SESSION['name'] ?? 'Anonymous';
}

if ($rating < 1 || $rating > 5 || $comment === '') {
    header("Location: index.php?site_review_error=1#reviews");
    exit;
}

try {
    $stmt = $conn->prepare("
        INSERT INTO site_reviews (user_id, display_name, rating, comment)
        VALUES (?, ?, ?, ?)
    ");

    if (!$stmt) {
        header("Location: index.php?review_error=prepare#reviews");
        exit;
    }

    $stmt->bind_param("isis", $userId, $displayName, $rating, $comment);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?review_success=1#reviews");
    exit;

} catch (Exception $e) {
    header("Location: index.php?review_error=server#reviews");
    exit;
}