<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (
    !isset($_SESSION["reset_user_id"]) ||
    !isset($_SESSION["reset_email"]) ||
    !isset($_SESSION["reset_token"])
) {
    header("Location: ../../forgot_password.php");
    exit;
}

$userId = (int)$_SESSION["reset_user_id"];
$email = $_SESSION["reset_email"];
$token = $_SESSION["reset_token"];

$securityAnswer = trim($_POST["security_answer"] ?? "");
$newPassword = $_POST["new_password"] ?? "";
$confirmPassword = $_POST["confirm_password"] ?? "";

if ($securityAnswer === "" || $newPassword === "" || $confirmPassword === "") {
    header("Location: ../../reset_password.php?error=missing");
    exit;
}

if ($newPassword !== $confirmPassword) {
    header("Location: ../../reset_password.php?error=mismatch");
    exit;
}

if (
    strlen($newPassword) < 8 ||
    !preg_match('/[A-Za-z]/', $newPassword) ||
    !preg_match('/[0-9]/', $newPassword)
) {
    header("Location: ../../reset_password.php?error=weak");
    exit;
}

$stmt = $conn->prepare("
    SELECT security_answer_hash, reset_token_hash, reset_token_expires
    FROM users
    WHERE id = ? AND email = ?
    LIMIT 1
");
$stmt->bind_param("is", $userId, $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows !== 1) {
    header("Location: ../../reset_password.php?error=invalid");
    exit;
}

$user = $res->fetch_assoc();

if (
    empty($user["reset_token_hash"]) ||
    empty($user["reset_token_expires"])
) {
    header("Location: ../../reset_password.php?error=invalid");
    exit;
}

if (strtotime($user["reset_token_expires"]) < time()) {
    unset($_SESSION["reset_user_id"], $_SESSION["reset_email"], $_SESSION["reset_question"], $_SESSION["reset_token"]);
    header("Location: ../../reset_password.php?error=expired");
    exit;
}

if (!password_verify($token, $user["reset_token_hash"])) {
    header("Location: ../../reset_password.php?error=invalid");
    exit;
}

if (!password_verify($securityAnswer, $user["security_answer_hash"])) {
    header("Location: ../../reset_password.php?error=wrong_answer");
    exit;
}

$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

$upd = $conn->prepare("
    UPDATE users
    SET password = ?, reset_token_hash = NULL, reset_token_expires = NULL
    WHERE id = ?
");
$upd->bind_param("si", $newPasswordHash, $userId);

if (!$upd->execute()) {
    header("Location: ../../reset_password.php?error=invalid");
    exit;
}

unset($_SESSION["reset_user_id"], $_SESSION["reset_email"], $_SESSION["reset_question"], $_SESSION["reset_token"]);
session_regenerate_id(true);

header("Location: ../../login.html?reset=success");
exit;
?>