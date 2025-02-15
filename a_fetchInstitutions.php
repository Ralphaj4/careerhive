<?php
require('database.php');
header('Content-Type: application/json');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("SELECT iname FROM institutions WHERE itype='company'");
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result = $stmt->get_result();
    $companies = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt->close();

    $response = [
        'companies' => $companies,
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
