<?php
require('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $newImage = $data['newImage'];
    $userId = base64_decode($_COOKIE['id']);
    $type = $data['type']; 

    if ($newImage && $userId) {
        $imageData = explode(',', $newImage);
        $imageData = base64_decode($imageData[1]);
        $imageName = uniqid() . '.png'; 
        $imagePath = 'media/' . $imageName;

        file_put_contents($imagePath, $imageData);

        // Check if ID belongs to a user or an institution
        $stmt = $conn->prepare("SELECT uid FROM users WHERE uid = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $isUser = $result->num_rows > 0;
        $stmt->close();

        if ($isUser) {
            // Update user profile/cover image
            if ($type === 'profile') {
                $stmt = $conn->prepare("UPDATE users SET uimage = ? WHERE uid = ?");
            } else {
                $stmt = $conn->prepare("UPDATE users SET ucover = ? WHERE uid = ?");
            }
        } else {
            // Update institution profile/cover image
            if ($type === 'profile') {
                $stmt = $conn->prepare("UPDATE institutions SET iimage = ? WHERE iid = ?");
            } else {
                $stmt = $conn->prepare("UPDATE institutions SET icover = ? WHERE iid = ?");
            }
        }

        $stmt->bind_param("si", $imagePath, $userId);
        $stmt->execute();
        $stmt->close();

        $response = [
            'status' => 'success',
            'imagePath' => $imagePath
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

$conn->close();
?>
