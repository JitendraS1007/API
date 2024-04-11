<?php

require_once '../database/db.php';
require_once '../exception/error.php';
require_once '../SucessResponce/sucess.php';
function loginUser($username, $password) {
    global $db;

    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        if (verifyPassword($password, $user['password'])) {
            
            $token = generateToken();
            $query = "UPDATE users SET token = :token WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':id', $user['id']);
            $stmt->execute();
            $user['token'] = $token;
            unset($user['password_hash']);
            return $user;
        } else {
            handleError( "Invalid username or password.");
        }
    } 
    else {
        handleError("Invalid username or password.");
    }
}

function registerUser($username, $password, $mobile,$name) {
    global $db;
    $query = "INSERT INTO users (username, password, mobile,name) VALUES (:username, :password, :mobile,:name)";
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':mobile', $mobile);
    $stmt->bindParam(':name', $name);
    if ($stmt->execute()) {
        return "User registered successfully.";
    } else {
        handleError("Registration failed.");
    }
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

function generateToken() {
    return bin2hex(random_bytes(32)); // Generate a random token
}
function fileSave($token , $file){
     global $db;
    $uploadDir = 'uploads/'; // Directory where files will be uploaded
    $uploadedFile = $uploadDir . basename($file['name']);
    $fileName = basename($_FILES['file']['name']);
    $filePath = $uploadedFile;
    // Check if file already exists
    if (file_exists($uploadedFile)) {
        handleError("File already exists.");
    }

    // Move uploaded file to destination directory
    if (!move_uploaded_file($file['tmp_name'], $uploadedFile)) {
        handleError("Failed to upload file.");
    }else{
        $query = "INSERT INTO files (fileName, filePath) VALUES (:fileName, :filePath)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':fileName', $fileName);
        $stmt->bindParam(':filePath', $filePath);
        if ($stmt->execute()) {
            handleSucess("Suceffuly Image Upload");
        } else {
            handleError("Failed to save file details.");
        }
    }

    return $uploadedFile;
}