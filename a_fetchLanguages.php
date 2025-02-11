<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("SELECT lname FROM languages");
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result = $stmt->get_result();
    $languages = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt->close();

    $response = [
        'languages' => $languages,
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
