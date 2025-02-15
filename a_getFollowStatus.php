<?php
require('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = base64_decode($_COOKIE['id']);
    $pageId = $_GET['id'];

    if (!$userId || !$pageId) {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM follow WHERE finstitutions = ? AND fusers = ?");

    $stmt->bind_param('ii', $pageId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $isFollowing = $result->num_rows > 0;
    $stmt->close();


    $response = [
        'success' => true,
        'following' => $isFollowing ? $isFollowing : false  // Handle no status found
    ];

    echo json_encode($response);
    
    $conn->close();
}
?>
