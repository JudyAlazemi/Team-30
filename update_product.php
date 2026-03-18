<?php
session_start();
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$productId = (int)($_POST['product_id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$stock = (int)($_POST['stock'] ?? 0);
$imageUrl = trim($_POST['image_url'] ?? '');
$categoryId = (int)($_POST['category_id'] ?? 0);

if ($productId <= 0 || $name === '' || $description === '' || $price < 0 || $stock < 0 || $imageUrl === '' || $categoryId <= 0) {
    header("Location: admin_products.php?msg=error");
    exit;
}

$stmt = $conn->prepare("
    UPDATE products
    SET name = ?, description = ?, price = ?, stock = ?, image_url = ?, category_id = ?
    WHERE id = ?
");
$stmt->bind_param("ssdisii", $name, $description, $price, $stock, $imageUrl, $categoryId, $productId);
$stmt->execute();
$stmt->close();

header("Location: admin_products.php?msg=updated");
exit;