<?php

class User {

    public function register($conn, $name, $email, $password) {

        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");   // chekcs if email already exists
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            return "Email already registered.";
        }

        //inserts a new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "Registration failed.";
        }
    }
    // gets user by email
    public function login($conn, $email, $password) {
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();


        // verify password

        if ($user && password_verify($password, $user["password"])) {
            return $user;
        }
        return false;
    }
    }