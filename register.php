<?php
require 'db.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data['username']);
$email = trim($data['email']);
$password = trim($data['password']);

if ($username == "" || $email == "" || $password == "") {
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required."
    ]);
    exit();
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$sql = "INSERT INTO tbl_Users (Username, Email, password_hash, role, status) 
        VALUES (?, ?, ?, 'users', 'active')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password_hash);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Registration successful"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Registration failed"
    ]);
}
?>