<?php
header("Content-Type: application/json");
session_start();
require_once "../config/db.php";

$user_id = $_SESSION['user_id'] ?? ($_POST['user_id'] ?? null);
$cart = $_SESSION['cart'] ?? [];

if (!$user_id || empty($cart)) {
    echo json_encode(["error" => "Cart empty or user not logged in"]);
    exit;
}

$conn->begin_transaction();

try {
    // 1. Create order
    $total = array_sum(array_column($cart, 'subtotal'));
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Pending')");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $conn->insert_id;

    // 2. Insert order items
    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $item) {
        $stmt_items->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt_items->execute();

        // 3. Decrease product stock
        $update = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $update->bind_param("ii", $item['quantity'], $item['product_id']);
        $update->execute();
    }

    $conn->commit();
    unset($_SESSION['cart']);

    echo json_encode(["success" => true, "order_id" => $order_id, "total" => $total]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["error" => "Checkout failed: " . $e->getMessage()]);
}

$conn->close();
?>
