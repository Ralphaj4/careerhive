<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("SELECT skillname FROM skills");
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result = $stmt->get_result();
    $skills = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt->close();

    $response = [
        'skills' => $skills,
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
