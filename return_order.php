<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$userId  = (int)($_SESSION['user_id'] ?? 0);
$orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;

if ($orderId <= 0) {
    header("Location: customer_orders.php");
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT id, status
        FROM orders
        WHERE id = ? AND user_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();

    if ($order) {
        $status = strtolower(trim($order['status']));

        if (in_array($status, ['processing', 'delivered'])) {
            $stmt = $conn->prepare("
                UPDATE orders
                SET status = 'return_pending', updated_at = NOW()
                WHERE id = ? AND user_id = ?
                LIMIT 1
            ");
            $stmt->bind_param("ii", $orderId, $userId);
            $stmt->execute();
            $stmt->close();
        }
    }
} catch (Exception $e) {
    error_log("Return order error: " . $e->getMessage());
}

header("Location: customer_orders.php");
exit;