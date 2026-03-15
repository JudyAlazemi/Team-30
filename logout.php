<?php
require_once __DIR__ . "/backend/config/session.php";
session_unset();
session_destroy();
header("Location: login.html");
exit;
