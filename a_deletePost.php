<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $pid = $input['postId'];
    $stmt = $conn->prepare("DELETE FROM posts WHERE pid = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    exit;
}
?>
