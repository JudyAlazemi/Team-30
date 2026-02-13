<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: /login.html");
  exit;
}

$userId = (int)$_SESSION['user_id'];
$userName = $_SESSION['user_name'] ?? "Customer";
$email = "Not available";

$stmt = $conn->prepare("SELECT email, name FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
  $email = $row['email'] ?? $email;
  if (!empty($row['name'])) {
    $userName = $row['name'];
    $_SESSION['user_name'] = $userName;
  }
}

$orderCount = 0;
$favCount = 0;

try {
  $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM orders WHERE user_id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $orderCount = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);

  $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM favourites WHERE user_id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $favCount = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);
} catch (Exception $e) {}

function sideLink($href, $label, $active, $badge = null) {
  $cls = "side-link" . ($active ? " active" : "");
  $b = "";
  if ($badge !== null) $b = '<span class="side-badge">'.htmlspecialchars((string)$badge).'</span>';
  return '<a class="'.$cls.'" href="'.$href.'"><span class="side-text">'.$label.'</span>'.$b.'</a>';
}
