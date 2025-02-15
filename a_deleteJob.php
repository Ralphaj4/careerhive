<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $jid = $input['jobId'];
    $stmt = $conn->prepare("DELETE FROM jobs WHERE jid = ?");
    $stmt->bind_param("i", $jid);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    exit;
}
?>
