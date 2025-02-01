<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputData = file_get_contents("php://input");
    $data = json_decode($inputData, true);

    $userInput = $data['userComment'];
    $pid = $data['postId'];
    $decoded_Cookie = base64_decode($_COOKIE["id"]);
    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO comments (cpost, cuser, text) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $pid, $decoded_Cookie, $userInput);

    // Execute the statement
    if ($stmt->execute()) {
        
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>