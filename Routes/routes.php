<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
require_once '../controllers/Auth/auth.php';
if(count($_POST) == 0 && count($_GET) == 0 ){

$json_data = file_get_contents('php://input');
$_POST = json_decode($json_data, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        http_response_code(200);
        echo json_encode(loginUser($username, $password));
    } else {
        handleError("Username and password are required.");
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['mobile']) && isset($_POST['name'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $mobile = $_POST['mobile'];
        $name = $_POST['name'];
        http_response_code(200);
        echo json_encode(registerUser($username, $password, $mobile , $name));
    } else {
        handleError("Username, password, and mobile , name are required.");
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'fileupload') {
    if (isset($_POST['token']) && isset($_FILES['file']) ) {
        return fileSave($_POST['token'], $_FILES['file']);
    }
}
