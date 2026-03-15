<?php
header("Content-Type: application/json");
require_once __DIR__ . "/backend/config/session.php";

// support different session keys (because different pages may set different names)
$userId =
    $_SESSION['user_id'] ??
    $_SESSION['customer_id'] ??
    $_SESSION['id'] ??
    null;

echo json_encode([
  "loggedIn" => $userId ? true : false,
  "user_id"  => $userId
]);