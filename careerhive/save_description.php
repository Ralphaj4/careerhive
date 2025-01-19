<?php
// Start the session
session_start();

// Include the database connection
require('database.php'); // Make sure this file includes the MySQLi connection

// Get the logged-in user's ID
$user_id = $_SESSION['userid'];

// Handle form submission and update description in the database
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['description'])) {
    $description = trim($_POST['description']);

    // Update the user's description in the database
    $stmt = $conn->prepare("UPDATE user SET description = ? WHERE userid = ?");
    $stmt->bind_param('si', $description, $userid); // 's' for string, 'i' for integer
    if ($stmt->execute()) {
        header("Location: myprofile.php?status=success");
        exit;
    } else {
        echo "<p>Error updating description: " . $stmt->error . "</p>";
    }
}
?>
