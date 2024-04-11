<?php

require_once 'db.php';
require_once 'error.php';

function loginUser($username, $password) {
    global $db;

    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        return $user;
    } else {
        handleError("Invalid username or password.");
    }
}

function registerUser($username, $password, $email) {
    global $db;

    // You can add validation here

    $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        return "User registered successfully.";
    } else {
        handleError("Registration failed.");
    }
}
