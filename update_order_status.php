<?php
session_start();
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$orderId = (int)($_POST["order_id"] ?? 0);
$newStatus = trim($_POST["status"] ?? "");

$allowedStatuses = [
    'pending',
    'processing',
    'shipped',
    'delivered',
    'cancelled',
    'return_pending',
    'returned'
];

if ($orderId <= 0 || !in_array($newStatus, $allowedStatuses, true)) {
    header("Location: admin_orders.php");
    exit;
}

$stmt = $conn->prepare("
    UPDATE orders
    SET status = ?, updated_at = NOW()
    WHERE id = ?
");
$stmt->bind_param("si", $newStatus, $orderId);
$stmt->execute();
$stmt->close();

header("Location: admin_orders.php");
exit;