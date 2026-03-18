<?php
header('Content-Type: application/javascript; charset=UTF-8');
require_once __DIR__ . "/backend/config/db.php";

$products = [];

try {
    $sql = "
        SELECT 
            p.id,
            p.name,
            p.description,
            p.price,
            p.stock,
            p.image_url,
            c.name AS category
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.id ASC
    ";

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products[] = [
                "id" => (int)$row["id"],
                "name" => $row["name"],
                "description" => $row["description"],
                "price" => (float)$row["price"],
                "image" => $row["image_url"],
                "category" => $row["category"] ?? "General",
                "stock" => (int)$row["stock"]
            ];
        }
    }
} catch (Exception $e) {
    $products = [];
}
?>

window.productsData = <?= json_encode($products, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>;