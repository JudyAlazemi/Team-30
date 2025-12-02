<?php
header("Content-Type: application/json");
require_once "../config/db.php";

// Base query
$query = "SELECT * FROM products";
$conditions = [];
$params = [];
$types = "";

// Optional filters
if (isset($_GET['id'])) {
    $conditions[] = "id = ?";
    $params[] = $_GET['id'];
    $types .= "i";
}
if (isset($_GET['category'])) {
    $conditions[] = "category_id = ?";
    $params[] = $_GET['category'];
    $types .= "i";
}
if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
    $conditions[] = "price BETWEEN ? AND ?";
    $params[] = $_GET['min_price'];
    $params[] = $_GET['max_price'];
    $types .= "dd";
}

// Combine filters
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
$conn->close();
?>
