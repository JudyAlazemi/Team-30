<?php
require_once "/../config/db.php";


$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$securityQuestion = trim($_POST["security_question"] ?? "");
$securityAnswer = trim($_POST["security_answer"] ?? "");

if (!$name || !$email || !$password || !$securityQuestion || !$securityAnswer) {
    echo "All fields are required.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit;
}

if (
    strlen($password) < 8 ||
    !preg_match('/[A-Za-z]/', $password) ||
    !preg_match('/[0-9]/', $password)
) {
    echo "Password must be at least 8 characters and include at least one letter and one number.";
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

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$securityAnswerHash = password_hash($securityAnswer, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO users (name, email, password, security_question, security_answer_hash)
    VALUES (?, ?, ?, ?, ?)
");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

if (!$stmt->bind_param("sssss", $name, $email, $passwordHash, $securityQuestion, $securityAnswerHash)) {
    die("Bind failed: " . $stmt->error);
}

if ($stmt->execute()) {
    header("Location: ../../login.html");
    exit;
} else {
    echo "Execute failed: " . $stmt->error;
}
?>