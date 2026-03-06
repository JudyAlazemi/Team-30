<?php
// backend/config/db.php

// Enable MySQL error reporting (helps debugging)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";        // XAMPP default
$username = "root";         // XAMPP default user
$password = "";             // XAMPP default password is empty
$database = "cs2team30_db"; // Your database name

try {
    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    // Set character encoding
    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {
    // If connection fails
    http_response_code(500);
    die("Database connection failed: " . $e->getMessage());
}
