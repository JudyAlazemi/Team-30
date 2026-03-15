<?php
require_once __DIR__ . "/backend/config/session.php";
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        "loggedIn" => true,
        "name" => $_SESSION['user_name'] ?? "User"
    ]);
} else {
    echo json_encode([
        "loggedIn" => false
    ]);
}