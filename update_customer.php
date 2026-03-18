<?php
require_once __DIR__ . "/backend/config/session.php";
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$userId = (int)($_POST['user_id'] ?? 0);
$name   = trim($_POST['name'] ?? '');
$email  = trim($_POST['email'] ?? '');

if ($userId <= 0 || $name === '' || $email === '') {
    header("Location: admin_users.php");
    exit;
}

$stmt = $conn->prepare("
    UPDATE users
    SET name = ?, email = ?
    WHERE id = ?
");
$stmt->bind_param("ssi", $name, $email, $userId);
$stmt->execute();
$stmt->close();

header("Location: admin_users.php");
exit;