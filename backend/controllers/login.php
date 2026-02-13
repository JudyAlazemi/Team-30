<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$email = trim($_POST["email"] ?? '');
$password = $_POST["password"] ?? '';

if ($email === '' || $password === '') {
    header("Location: ../../login.html?error=missing");
    exit;
}

$stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: ../../login.html?error=invalid");
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    header("Location: ../../login.html?error=invalid");
    exit;
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['user_name'] = $user['name'];

header("Location: ../../profile.php");
exit;

