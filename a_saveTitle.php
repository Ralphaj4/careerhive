<?php
// save_title.php
require('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Retrieve the new title and userId from the data
    $newTitle = $data['newTitle'];
    $userId = base64_decode($_COOKIE['id']);

    // Prepare and execute the update query
    if ($newTitle && $userId) {
        $stmt = $conn->prepare("UPDATE users SET utitle = ? WHERE uid = ?");
        $stmt->bind_param("si", $newTitle, $userId);
        $stmt->execute();
        $stmt->close();
        $response = [
            'status' => 'success'
        ];

        // Send the JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
        
    }
}
$conn->close();
?>

