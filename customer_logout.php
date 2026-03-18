<?php
require_once __DIR__ . "/backend/config/session.php";


$_SESSION = [];

session_unset();
session_destroy();


header("Location: login.html");
exit;