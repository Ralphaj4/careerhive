<?php
require('database.php');
require_once('functions.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $status = 'pending';
    $senderId = $input['senderId'] ?? null;
    $receiverId = $input['receiverId'] ?? null;
    $type = 'connection';
    $postid = 0;

    if (!$senderId || !$receiverId) {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
        exit;
    }

    // Check if a connection already exists between the sender and receiver
    $stmt = $conn->prepare("SELECT * FROM connections WHERE (csender = ? AND creceiver = ?) OR (csender = ? AND creceiver = ?)");
    $stmt->bind_param('iiii', $senderId, $receiverId, $receiverId, $senderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Connection already exists
        echo json_encode(['success' => false, 'error' => 'Connection already exists']);
        $stmt->close();
        $conn->close();
        exit;
    }

    // Insert new connection if not already existing
    InsertNotification($senderId, $receiverId, $type, $postid);
    $stmt = $conn->prepare("INSERT INTO connections (csender, creceiver, cstatus) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $senderId, $receiverId, $status);
    $stmt->execute();

    $response = [
        'success' => $stmt->affected_rows > 0,
    ];
    echo json_encode($response);
    $stmt->close();
    $conn->close();
}
?>
