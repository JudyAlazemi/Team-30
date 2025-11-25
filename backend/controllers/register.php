<?php
require_once "../config/db.php";

$name = $_POST["name"] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$name || !$email || !$password) {
    echo "All fields are required.";
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Email already exists.";
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

if (!$stmt->bind_param("sss", $name, $email, $hashed)) {
    die("Bind failed: " . $stmt->error);
}

if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    echo "Execute failed: " . $stmt->error;
}
?>
