<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = base64_decode($_COOKIE['id']);
    $profileId = $_GET['profileId'];

    if (!$userId || !$profileId) {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
        exit;
    }

    $stmt = $conn->prepare("SELECT 
    csender, creceiver, cstatus 
    FROM 
        connections 
    WHERE 
        (csender = ? AND creceiver = ?) OR (csender = ? AND creceiver = ?)");

    $stmt->bind_param('iiii', $userId, $profileId, $profileId, $userId);
    $stmt->execute();
    $stmt->bind_result($csender, $creceiver, $cstatus);
    $stmt->fetch();
    $stmt->close();

    if ($cstatus === 'accepted') {
        $cstatus = 'accepted';
    } elseif ($cstatus === 'pending') {
        if ($csender == $userId) {
            $cstatus = 'pending'; // You sent the request, waiting for acceptance
        } else {
            $cstatus = 'accept'; // You received the request, can accept it
        }
    } else {
        $cstatus = 'connect'; // No connection exists, default to pending
    }


    $response = [
        'success' => true,
        'cstatus' => $cstatus ? $cstatus : 'none'  // Handle no status found
    ];

    echo json_encode($response);
    
    $conn->close();
}
?>
