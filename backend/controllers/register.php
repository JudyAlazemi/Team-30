<?php
require_once "../config/db.php";          //imports db connection 
require_once "../models/User.php";

$user = new User();                      // creates a new User from model class

$name  = $_POST['name']          ?? '';
$email  = $_POST['email']        ?? '';
$password  = $_POST['password']  ?? '';

if (!empty($name) && !empty($email) && !empty($password)) {   // checks if the user filled all fields
    $result - $user->register($conn, $name, $email, $password);

    if ($result === "success") {
        echo "User registerd successfully!";

    } else {
        echo $result;
    }
} else {
    echo "Please fill in all fields";

}
?>