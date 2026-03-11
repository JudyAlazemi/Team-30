<?php
session_start();
require_once __DIR__ . "/backend/config/db.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$currentAdminId = (int)($_SESSION["admin_id"] ?? 0);
$adminIdToApprove = (int)($_POST["admin_id"] ?? 0);

if ($currentAdminId <= 0 || $adminIdToApprove <= 0) {
    header("Location: admin_dashboard.php");
    exit;
}

/* optional: stop admin approving themselves */
if ($currentAdminId === $adminIdToApprove) {
    header("Location: admin_dashboard.php");
    exit;
}

$stmt = $conn->prepare("
    UPDATE admins
    SET approval_status = 'approved',
        approved_by = ?,
        approved_at = NOW()
    WHERE id = ? AND approval_status = 'pending'
");
$stmt->bind_param("ii", $currentAdminId, $adminIdToApprove);
$stmt->execute();
$stmt->close();

header("Location: admin_dashboard.php");
exit;