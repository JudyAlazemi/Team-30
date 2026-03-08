<?php
header('Content-Type: application/json');
require_once "../config/db.php";  // connect to your existing DB

// Read quiz answers from JS fetch()
$answer1 = $_POST['answer1'] ?? '';
$answer2 = $_POST['answer2'] ?? '';
$answer3 = $_POST['answer3'] ?? '';
$answer4 = $_POST['answer4'] ?? '';
$suggestion = $_POST['suggestion'] ?? '';

if ($answer1 == '' || $answer2 == '' || $answer3 == '' || $answer4 == '' || $suggestion == '') {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO quiz_results (answer1, answer2, answer3, answer4, suggestion)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("sssss", $answer1, $answer2, $answer3, $answer4, $suggestion);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
