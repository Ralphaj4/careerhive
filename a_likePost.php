<?php
require('database.php');
require_once('functions.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $postid = $input['postid'] ?? null;

    //Get Post Author
    $stmt_pauthor = $conn->prepare("SELECT pauthor FROM posts WHERE pid = ?");
    $stmt_pauthor->bind_param('i', $postid);
    $stmt_pauthor->execute();
    $stmt_pauthor->bind_result($pauthor);
    $stmt_pauthor->fetch();
    $stmt_pauthor->close();

    $userid = base64_decode($_COOKIE['id']) ?? null;
    $type = 'like';
    if (!$postid || !$userid) {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
        exit;
    }

    if(!CheckIfLiked($userid, $postid)){
        InsertNotification($userid, $pauthor, $type, $postid); 
        $stmt = $conn->prepare("INSERT INTO likes (lpost, luser) VALUES (?, ?)");
        $status = 'liked';
    }
    else{
        $stmt = $conn->prepare("DELETE FROM likes WHERE lpost = ? AND luser = ?");
        $status = 'unliked';
    }
    
    $stmt->bind_param('ii', $postid, $userid);
    $stmt->execute();
    
    $response = [
        'success' => $stmt->affected_rows > 0,
        'status' => $status
    ];
    echo json_encode($response);
    $stmt->close();
    $conn->close();

}
