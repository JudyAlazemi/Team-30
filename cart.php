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

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {


    case 'add':
        $product_id = $_POST['product_id'] ?? $_GET['product_id'] ?? null;
        $quantity   = $_POST['quantity'] ?? $_GET['quantity'] ?? 1;

        if (!$product_id) {
            echo json_encode(["error" => "Missing product_id"]);
            exit;
        }

        $stmt = $conn->prepare("SELECT id, name, price, stock FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if (!$product || $product['stock'] < $quantity) {
            echo json_encode(["error" => "Product unavailable or out of stock"]);
            exit;
        }

        $_SESSION['cart'][$product_id] = [
            "product_id" => $product['id'],
            "name"       => $product['name'],
            "price"      => $product['price'],
            "quantity"   => $quantity,
            "subtotal"   => $quantity * $product['price']
        ];

        echo json_encode(["success" => "Item added to cart", "cart" => $_SESSION['cart']]);
        break;

    case 'remove':
        $product_id = $_POST['product_id'] ?? $_GET['product_id'] ?? null;
        if (!$product_id) {
            echo json_encode(["error" => "Missing product_id"]);
            exit;
        }

        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            echo json_encode(["success" => "Item removed", "cart" => $_SESSION['cart']]);
        } else {
            echo json_encode(["error" => "Item not found in cart"]);
        }
        break;

    case 'update':
        $product_id = $_POST['product_id'] ?? $_GET['product_id'] ?? null;
        $quantity   = $_POST['quantity'] ?? $_GET['quantity'] ?? null;

        if (!$product_id || !$quantity) {
            echo json_encode(["error" => "Missing product_id or quantity"]);
            exit;
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            $_SESSION['cart'][$product_id]['subtotal'] =
                $_SESSION['cart'][$product_id]['price'] * $quantity;
            echo json_encode(["success" => "Quantity updated", "cart" => $_SESSION['cart']]);
        } else {
            echo json_encode(["error" => "Item not found in cart"]);
        }
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
