<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['userId'];
    $profileId = $_GET['profileId'];

    if (!$userId || !$profileId) {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
        exit;
    }

    // Your connection logic here (replace this with actual SQL query)
    $stmt = $conn->prepare("SELECT cstatus FROM connections WHERE (csender = ? AND creceiver = ?) OR (csender = ? AND creceiver = ?)");
    $stmt->bind_param('iiii', $userId, $profileId, $profileId, $userId);
    $stmt->execute();
    $stmt->bind_result($cstatus);
    $stmt->fetch();

    $response = [
        'success' => true,
        'cstatus' => $cstatus ? $cstatus : 'none'  // Handle no status found
    ];

    echo json_encode($response);
    $stmt->close();
    $conn->close();
}
?>
