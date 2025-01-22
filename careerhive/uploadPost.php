<?php
require('database.php');

// Capture the textarea value
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = $_POST['userInput'];
    $decoded_Cookie = base64_decode($_COOKIE["id"]);
    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO posts (pauthor, ptext) VALUES (?, ?)");
    $stmt->bind_param("is", $decoded_Cookie, $userInput);

    // Execute the statement
    if ($stmt->execute()) {
        
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
