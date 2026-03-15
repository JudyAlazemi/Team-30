<?php
require_once __DIR__ . "/../config/session.php";
require_once __DIR__ . "/../config/db.php";


$email = trim($_POST["email"] ?? "");

if ($email === "") {
    header("Location: ../../forgot_password.php?error=missing");
    exit;
}

$stmt = $conn->prepare("
    SELECT id, email, security_question
    FROM users
    WHERE email = ?
    LIMIT 1
");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows !== 1) {
    header("Location: ../../forgot_password.php?error=invalid");
    exit;
}

$user = $res->fetch_assoc();
$userId = (int)$user["id"];
$securityQuestion = trim($user["security_question"] ?? "");

if ($securityQuestion === "") {
    header("Location: ../../forgot_password.php?error=not_setup");
    exit;
}

// Create reset token
$token = bin2hex(random_bytes(32));
$tokenHash = password_hash($token, PASSWORD_DEFAULT);
$expiresAt = (new DateTime("+15 minutes"))->format("Y-m-d H:i:s");

// Save token hash + expiry to DB
$upd = $conn->prepare("
    UPDATE users
    SET reset_token_hash = ?, reset_token_expires = ?
    WHERE id = ?
");
$upd->bind_param("ssi", $tokenHash, $expiresAt, $userId);
$upd->execute();

// Store reset flow info in session
session_regenerate_id(true);
$_SESSION["reset_user_id"] = $userId;
$_SESSION["reset_email"] = $email;
$_SESSION["reset_question"] = $securityQuestion;
$_SESSION["reset_token"] = $token;

// Go to the reset form page
header("Location: ../../reset_password.php");
exit;
?>