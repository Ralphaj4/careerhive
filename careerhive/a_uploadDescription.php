<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the raw POST data
    $inputData = file_get_contents("php://input");
    $data = json_decode($inputData, true); // Decode JSON into an associative array

    // Retrieve the description and userId (uid) from the JSON
    $userInput = $data['description'] ?? '';  // Default to empty string if not provided
    $userId = $data['uid'] ?? null;

    // Check if userId is valid
    if ($userId !== null) {
        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("UPDATE users SET udescription = ? WHERE uid = ?");
        $stmt->bind_param("si", $userInput, $userId); // 'si' means string and integer

        // Execute the statement
        if ($stmt->execute()) {
            echo "Data saved successfully!";  // Success message
        } else {
            echo "Error saving data: " . $stmt->error;  // If something went wrong
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Invalid user ID.";
    }

    // Close the database connection
    $conn->close();
}
?>
