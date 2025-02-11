<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("SELECT iname FROM institutions WHERE itype ='college'");
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result = $stmt->get_result();
    $schools = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt->close();

    $stmt_major = $conn->prepare("SELECT mname FROM majors");
    if (!$stmt_major->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt_major->error]);
        exit;
    }
    $result = $stmt_major->get_result();
    $majors = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_major->close();

    $response = [
        'schools' => $schools,
        'majors' => $majors
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
