<?php
class User {
    public function register($conn, $name, $email, $password){
        // check if the email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();                         //if the same email exists it stops and returs a message
        $check->store_result();
        if ($check->num_rows > 0) {
            return "Email already registered.";
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed);       // creates a new SQL command to intsert a new record into the 'users' table

if ($stmt->execute()) {

return "success";
} else {
    return "Register failed:" . $conn->error;
}
    }

}
?>