<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $title = $data['title'];
    $desc = $data['desc'];
    $id = base64_decode($_COOKIE['id']);

    $stmt = $conn->prepare("INSERT INTO jobs (iid, jtitle, jdescription) VALUES(?, ?, ?)");
    $stmt->bind_param("iss", $id, $title, $desc); // 'si' means string and integer

    // Execute the statement
    if ($stmt->execute()) {
        
    }

    $stmt->close();
    $conn->close();
} 
?>
