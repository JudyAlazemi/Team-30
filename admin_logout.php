<?php
require_once __DIR__ . "/backend/config/session.php";

$_SESSION = [];
session_unset();
session_destroy();

header("Location: admin_login.php");
exit;