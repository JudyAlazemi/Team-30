<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin_review.php");
    exit;
}

$reviewId   = (int)($_POST["review_id"] ?? 0);
$reviewType = trim($_POST["review_type"] ?? "");

if ($reviewId <= 0 || ($reviewType !== "product" && $reviewType !== "site")) {
    header("Location: admin_review.php?error=1");
    exit;
}

try {
    if ($reviewType === "product") {
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ? LIMIT 1");
    } else {
        $stmt = $conn->prepare("DELETE FROM site_reviews WHERE id = ? LIMIT 1");
    }

    if (!$stmt) {
        header("Location: admin_review.php?error=1");
        exit;
    }

    $stmt->bind_param("i", $reviewId);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_review.php?deleted=1");
    exit;

} catch (Exception $e) {
    header("Location: admin_review.php?error=1");
    exit;
}