<?php
session_start();
header("Content-Type: application/json");

require_once __DIR__ . "/backend/config/db.php";

/* ✅ Must be logged in (guest can't order) */
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["ok" => false, "error" => "Please login first"]);
    exit;
}
$userId = (int)$_SESSION['user_id'];

/* ✅ Read JSON body from checkout.php */
$raw = file_get_contents("php://input");
$payload = json_decode($raw, true);

if (!is_array($payload)) {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "Invalid JSON"]);
    exit;
}

/* ✅ checkout.php sends items[], not cart[] */
$items = $payload['items'] ?? [];
$shippingMethod = $payload['shipping_method'] ?? 'standard';

if (!is_array($items) || count($items) === 0) {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "Cart is empty"]);
    exit;
}

/* Build productId => qty from items */
$requested = [];
foreach ($items as $it) {
    $pid = (int)($it['id'] ?? $it['product_id'] ?? 0);
    $qty = (int)($it['qty'] ?? $it['quantity'] ?? 0);
    if ($pid <= 0 || $qty <= 0) continue;
    $requested[$pid] = ($requested[$pid] ?? 0) + $qty;
}

if (count($requested) === 0) {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "Invalid cart items format"]);
    exit;
}

try {
    $conn->begin_transaction();

    /* ✅ Validate products + lock rows + compute subtotal using DB prices */
    $cleanItems = [];
    $subtotal = 0.0;

    foreach ($requested as $pid => $qty) {
        $stmt = $conn->prepare("SELECT id, name, price, stock FROM products WHERE id = ? FOR UPDATE");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $p = $stmt->get_result()->fetch_assoc();

        if (!$p) throw new Exception("Product #$pid not found");
        if ((int)$p['stock'] < $qty) throw new Exception("Not enough stock for {$p['name']}");

        $price = (float)$p['price'];
        $subtotal += $price * $qty;

        $cleanItems[] = [
            "product_id" => (int)$p['id'],
            "qty" => $qty,
            "price" => $price
        ];
    }

    /* Shipping + tax rules (match your checkout.php logic) */
    $shipping = ($subtotal >= 150) ? 0.0 : 10.0;
    if ($shippingMethod === 'express') {
        $shipping = ($subtotal >= 150) ? 0.0 : 20.0;
    }

    $tax = round($subtotal * 0.08, 2);
    $total = round($subtotal + $shipping + $tax, 2);

    /* ✅ Insert order */
    $status = "processing";
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("ids", $userId, $total, $status);
    $stmt->execute();
    $orderId = $stmt->insert_id;

    /* ✅ Insert items + update stock */
    $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmtStock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

    foreach ($cleanItems as $it) {
        $pid = (int)$it['product_id'];
        $qty = (int)$it['qty'];
        $price = (float)$it['price'];

        $stmtItem->bind_param("iiid", $orderId, $pid, $qty, $price);
        $stmtItem->execute();

        $stmtStock->bind_param("ii", $qty, $pid);
        $stmtStock->execute();
    }

    $conn->commit();

    /* ✅ Clear server session cart (optional) */
    if (isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    echo json_encode(["ok" => true, "order_id" => $orderId, "total" => $total]);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["ok" => false, "error" => $e->getMessage()]);
}