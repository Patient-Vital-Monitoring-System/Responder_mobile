<?php
session_start();
require 'db.php'; 

header("Content-Type: application/json");


$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "status" => "error",
        "message" => "No data received."
    ]);
    exit();
}

$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if ($username == "" || $password == "") {
    echo json_encode([
        "status" => "error",
        "message" => "Username and password are required."
    ]);
    exit();
}


$sql = "SELECT * FROM tbl_Users WHERE Username = ? LIMIT 1";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Database query error."
    ]);
    exit();
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();

   
    if ($user['status'] !== 'active') {
        echo json_encode([
            "status" => "error",
            "message" => "Account is inactive."
        ]);
        exit();
    }

    
    if (password_verify($password, $user['password_hash'])) {

    
        $_SESSION['user_id'] = $user['Users_id'];
        $_SESSION['username'] = $user['Username'];
        $_SESSION['role'] = $user['role'];

     
        $log = $conn->prepare("INSERT INTO tbl_users_logs (users_logs) VALUES (?)");
        if ($log) {
            $log->bind_param("i", $user['Users_id']);
            $log->execute();
        }

      
        $redirect = "dashboard_user.php";

        if ($user['role'] === 'admin') {
            $redirect = "dashboard_admin.php";
        } 
        elseif ($user['role'] === 'responders') {
            $redirect = "dashboard_responder.php";
        }

        echo json_encode([
            "status" => "success",
            "redirect" => $redirect,
            "role" => $user['role']
        ]);

    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid password."
        ]);
    }

} else {
    echo json_encode([
        "status" => "error",
        "message" => "User not found."
    ]);
}

$stmt->close();
$conn->close();
?>