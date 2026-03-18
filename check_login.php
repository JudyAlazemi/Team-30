<?php
require_once __DIR__ . "/backend/config/session.php";

header('Content-Type: application/json');

if (isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] === true) {
    echo json_encode([
        "loggedIn" => true,
        "role" => "admin"
    ]);
    exit;
}

if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
    echo json_encode([
        "loggedIn" => true,
        "role" => "customer"
    ]);
    exit;
}

echo json_encode([
    "loggedIn" => false,
    "role" => "guest"
]);
exit;