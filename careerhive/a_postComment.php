<?php
require('database.php');
require_once('functions.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputData = file_get_contents("php://input");
    $data = json_decode($inputData, true);
    $userInput = $data['userComment'];
    $pid = $data['postId'];
    
    $stmt_pauthor = $conn->prepare("SELECT pauthor FROM posts WHERE pid = ?");
    $stmt_pauthor->bind_param('i', $pid);
    $stmt_pauthor->execute();
    $stmt_pauthor->bind_result($pauthor);
    $stmt_pauthor->fetch();
    $stmt_pauthor->close();
    $type = 'comment';
    
    $decoded_Cookie = base64_decode($_COOKIE["id"]);
    
    InsertNotification($decoded_Cookie, $pauthor, $type, $pid);
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