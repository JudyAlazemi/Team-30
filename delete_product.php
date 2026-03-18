<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$productId = (int)($_POST['product_id'] ?? 0);

if ($productId <= 0) {
    header("Location: admin_products.php?msg=error");
    exit;
}

try {
    $checkStmt = $conn->prepare("SELECT COUNT(*) AS total FROM order_items WHERE product_id = ?");
    $checkStmt->bind_param("i", $productId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();
    $count = (int)($row['total'] ?? 0);
    $checkStmt->close();

    if ($count > 0) {
        header("Location: admin_products.php?msg=has_orders");
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_products.php?msg=deleted");
    exit;
} catch (Exception $e) {
    header("Location: admin_products.php?msg=error");
    exit;
}