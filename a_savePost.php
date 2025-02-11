<?php
// save_image.php
require('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $status = $data['status'];
    $userId = base64_decode($_COOKIE['id']);
    $postid = $data['postid'];
    if($status == 0){
        $stmt = $conn->prepare("DELETE FROM saveditems WHERE pid = ? AND uid = ?");
    }
    else if($status == 1){
        $stmt = $conn->prepare("INSERT INTO saveditems(pid, uid) VALUES (?, ?)");
    }
    $stmt->bind_param("ii", $postid, $userId);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    exit;
}
?>
