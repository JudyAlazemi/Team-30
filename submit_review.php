<?php
session_start();
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
$productId = (int) ($_POST['product_id'] ?? 0);
$rating = (int) ($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if ($productId <= 0 || $rating < 1 || $rating > 5 || $comment === '') {
    header("Location: productdetails.php?id=" . $productId . "&review_error=1#reviews");
    exit;
}

$check = $conn->prepare("SELECT id FROM reviews WHERE user_id = ? AND product_id = ?");
$check->bind_param("ii", $userId, $productId);
$check->execute();
$existing = $check->get_result()->fetch_assoc();

if ($existing) {
    $stmt = $conn->prepare("
        UPDATE reviews
        SET rating = ?, comment = ?, created_at = CURRENT_TIMESTAMP
        WHERE user_id = ? AND product_id = ?
    ");
    $stmt->bind_param("isii", $rating, $comment, $userId, $productId);
} else {
    $stmt = $conn->prepare("
        INSERT INTO reviews (product_id, user_id, rating, comment)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("iiis", $productId, $userId, $rating, $comment);
}

$stmt->execute();

header("Location: productdetails.php?id=" . $productId . "&review_success=1#reviews");
exit;
?>