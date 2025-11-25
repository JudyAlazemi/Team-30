<?php
session_start();
require_once"../config/db.php";

$email = $_POST["email"] ?? '';
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    echo "Please fill in all fields.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resul = $stmt->get_result();

if ($resul->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        echo "Login seccessful! Welcome, " . $user['name'];
        exit;
    } else {    
        echo "Incorrect password.";
        exit;
    }
    } else {
        echo "Email not found.";
        exit;

    }
    ?>