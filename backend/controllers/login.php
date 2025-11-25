<?php
session_start();                    // strats a session to to store the users login data
require_once "../config/db.php";   //imports db connection 

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// this checks if all fields are filled
if (empty($email) || empty($password)) {
    echo "Please fill in all fields";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");        //creates an SQL statement to search for the user by email
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

//checks if the email exists
if ($result->num_rows === 1){
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        echo "Login successful! Welcome, " . $_SESSION['user_name'];
        exit;
    } else {
        echo "Incorrect password.";
    }

} else {
echo "Email not found.";

}

<<<<<<< HEAD
?>
=======
?>

>>>>>>> 2db52b339aead7b3d74efc4764b63ed46808f9b2
