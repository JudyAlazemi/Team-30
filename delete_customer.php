<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$userId = (int)($_POST['user_id'] ?? 0);

if ($userId <= 0) {
    header("Location: admin_users.php?msg=invalid");
    exit;
}

try {
    // Check if customer has any orders
    $checkStmt = $conn->prepare("SELECT COUNT(*) AS total FROM orders WHERE user_id = ?");
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();
    $orderCount = (int)($row['total'] ?? 0);
    $checkStmt->close();

    if ($orderCount > 0) {
        header("Location: admin_users.php?msg=has_orders");
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_users.php?msg=deleted");
    exit;

} catch (Exception $e) {
    header("Location: admin_users.php?msg=error");
    exit;
}