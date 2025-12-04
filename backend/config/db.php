<?php
$servername = "localhost";
$username = "cs2team30"; 
$password = "L9xIFwamWEvGPOXogAPTOzV75";
$dbname = "cs2team30_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>