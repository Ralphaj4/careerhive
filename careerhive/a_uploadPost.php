<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = $_POST['userInput'];
    $decoded_Cookie = base64_decode($_COOKIE["id"]);
    $imageData = null;

    // Handle file upload as BLOB
    if (isset($_FILES['photoInput']) && $_FILES['photoInput']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES["photoInput"]["tmp_name"]); // Read binary data
    }

    // Prepare the SQL query
    if ($imageData) {
        $stmt = $conn->prepare("INSERT INTO posts (pauthor, ptext, pimage) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $decoded_Cookie, $userInput, $imageData);
    } else {
        $stmt = $conn->prepare("INSERT INTO posts (pauthor, ptext) VALUES (?, ?)");
        $stmt->bind_param("is", $decoded_Cookie, $userInput);
    }

    // Execute the statement
    if ($stmt->execute()) {
        echo "Post uploaded successfully!";
    } else {
        echo "Error uploading post.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
