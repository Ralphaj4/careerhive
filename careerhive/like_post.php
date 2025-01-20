<?php
require('database.php');
require_once('functions.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $postid = $input['postid'] ?? null;
    $userid = base64_decode($_COOKIE['id']) ?? null;

    if (!$postid || !$userid) {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
        exit;
    }

    if(!CheckIfLiked($userid, $postid)){
        $stmt = $conn->prepare("INSERT INTO likes (lpost, luser) VALUES (?, ?)");
    }
    else{
        $stmt = $conn->prepare("DELETE FROM likes WHERE lpost = ? AND luser = ?");
    }
    
    $stmt->bind_param('ii', $postid, $userid);
    $stmt->execute();

    echo json_encode(['success' => $stmt->affected_rows > 0]);
    $stmt->close();
    $conn->close();

}
