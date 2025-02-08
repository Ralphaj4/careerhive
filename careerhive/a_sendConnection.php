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
    else{
        InsertNotification($senderId, $receiverId, $type, $postid);
        $stmt = $conn->prepare("INSERT INTO connections VALUES (?, ?, ?)");
    }
    
    $stmt->bind_param('iis', $senderId, $receiverId, $status);
    $stmt->execute();

    $response = [
        'success' => $stmt->affected_rows > 0,
    ];
    echo json_encode($response);
    $stmt->close();
    $conn->close();

}
