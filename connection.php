<?php
require 'db.php'; // or db.php (the file that creates $conn)

header("Content-Type: application/json");

if (!isset($conn)) {
    echo json_encode([
        "status" => "error",
        "message" => "Connection variable not found."
    ]);
    exit();
}

if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Database Not Connected"
    ]);
} else {
    echo json_encode([
        "status" => "success",
        "message" => "Database Connected"
    ]);
}
?>