<?php
header("Content-Type: application/json");
session_start();
require_once "../config/db.php";

$user_id = $_SESSION['user_id'] ?? ($_POST['user_id'] ?? null);
$action  = $_GET['action'] ?? $_POST['action'] ?? '';

if (!$user_id) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

switch ($action) {

case 'add':
    $product_id = $_POST['product_id'];
    $quantity   = $_POST['quantity'] ?? 1;

    $stmt = $conn->prepare("SELECT price, stock FROM products WHERE id=?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if (!$product || $product['stock'] < $quantity) {
        echo json_encode(["error" => "Product unavailable or out of stock"]);
        exit;
    }

    $_SESSION['cart'][$product_id] = [
        "product_id" => $product_id,
        "price"      => $product['price'],
        "quantity"   => $quantity,
        "subtotal"   => $quantity * $product['price']
    ];
    echo json_encode(["success" => "Item added to cart"]);
    break;

case 'remove':
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    echo json_encode(["success" => "Item removed"]);
    break;

case 'update':
    $product_id = $_POST['product_id'];
    $quantity   = $_POST['quantity'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        $_SESSION['cart'][$product_id]['subtotal'] =
            $_SESSION['cart'][$product_id]['price'] * $quantity;
    }
    echo json_encode(["success" => "Quantity updated"]);
    break;

case 'get':
default:
    $cart = $_SESSION['cart'] ?? [];
    $subtotal = array_sum(array_column($cart, 'subtotal'));
    echo json_encode(["cart" => $cart, "subtotal" => $subtotal]);
    break;
}

$conn->close();
?>
