<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $input = json_decode(file_get_contents('php://input'), true);
    $userId = $input['id'] ?? null;

    if (!$userId) {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM
    
    ");

    $stmt->bind_param('iiii', $userId, $profileId, $profileId, $userId);
    $stmt->execute();
    $stmt->bind_result($csender, $creceiver, $cstatus);
    $stmt->fetch();
    $stmt->close();

    if ($cstatus === 'accepted') {
        $cstatus = 'accepted';
    } elseif ($cstatus === 'pending') {
        if ($csender == $userId) {
            $cstatus = 'pending';
        } else {
            $cstatus = 'accept';
        }
    } else {
        $cstatus = 'connect';
    }


    $response = [
        'success' => true,
        'cstatus' => $cstatus ? $cstatus : 'none'  // Handle no status found
    ];

    echo json_encode($response);
    
    $conn->close();
}
?>
