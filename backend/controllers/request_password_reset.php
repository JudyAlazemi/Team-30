<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

$email = trim($_POST["email"] ?? "");
if ($email === "") {
  header("Location: ../../forgot_password.php?error=missing");
  exit;
}

// Always respond the same to avoid user enumeration
$genericRedirect = "Location: ../../forgot_password.php?sent=1";

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows !== 1) {
  header($genericRedirect);
  exit;
}

$user = $res->fetch_assoc();
$userId = (int)$user["id"];

// Create token + hash
$token = bin2hex(random_bytes(32)); // 64-char
$tokenHash = password_hash($token, PASSWORD_DEFAULT);

// Expiry (e.g., 30 minutes)
$expiresAt = (new DateTime("+30 minutes"))->format("Y-m-d H:i:s");

$upd = $conn->prepare("UPDATE users SET reset_token_hash = ?, reset_token_expires = ? WHERE id = ?");
$upd->bind_param("ssi", $tokenHash, $expiresAt, $userId);
$upd->execute();

// Build reset URL
// IMPORTANT: Adjust base URL to your setup (localhost / your domain)
$baseUrl = "http://localhost/Team-30"; // change if needed
$resetUrl = $baseUrl . "/reset_password.php?token=" . urlencode($token) . "&email=" . urlencode($email);

/*
  In a real site: email $resetUrl to the user.
  For coursework/demo: you can temporarily show it on-screen or log it.
*/

// Demo option 1: store link in session and show on a "sent" page
$_SESSION["reset_demo_link"] = $resetUrl;

header($genericRedirect);
exit;