<?php
require('database.php');
// Ensure that the user is authenticated (replace with your authentication logic)
session_start(); // Start the session
$userId = $_COOKIE['id']; // Get user ID from session

if ($userId === null) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the file was uploaded without errors
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $jobId = $_POST['job_id']; // Get job ID from the hidden field

        // Get the file details
        $file = $_FILES['document'];
        $fileName = basename($file['name']);
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        // Validate file type and size (PDF)
        if ($fileType !== 'application/pdf') {
            echo json_encode(['success' => false, 'message' => 'Please upload a PDF file.']);
            exit();
        }

        if ($fileSize > 5 * 1024 * 1024) { // Max file size 5MB
            echo json_encode(['success' => false, 'message' => 'File size exceeds the limit (5MB).']);
            exit();
        }

        // Ensure uploads directory exists
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
        }

        // Generate a unique file name to avoid conflicts
        $fileNewName = uniqid('', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $fileDestination = $uploadDir . $fileNewName;

        // Move the file to the uploads folder
        if (move_uploaded_file($fileTmpName, $fileDestination)) {
            // Insert file information into the database using mysqli
            $stmt = $conn->prepare("INSERT INTO applications (jid, uid, document) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $jobId, $userId, $fileNewName); // "iis" means integer, integer, string
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to insert into the database.']);
            }

            $stmt->close(); // Close the statement
        } else {
            echo json_encode(['success' => false, 'message' => 'There was an error uploading the file.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded or error during upload.']);
    }
}

// Close the connection
$conn->close();
?>
