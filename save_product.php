<?php
session_start();
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$stock = (int)($_POST['stock'] ?? 0);
$imageUrl = trim($_POST['image_url'] ?? '');
$categoryId = (int)($_POST['category_id'] ?? 0);

if ($name === '' || $description === '' || $price < 0 || $stock < 0 || $imageUrl === '' || $categoryId <= 0) {
    header("Location: admin_products.php?msg=error");
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO products (name, description, price, stock, image_url, category_id)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("ssdisi", $name, $description, $price, $stock, $imageUrl, $categoryId);
$stmt->execute();
$stmt->close();

header("Location: admin_products.php?msg=added");
exit;