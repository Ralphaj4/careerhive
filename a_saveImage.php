<?php
// save_image.php
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

        if ($type === 'profile') {
            $stmt = $conn->prepare("UPDATE users SET uimage = ? WHERE uid = ?");
        } else {
            $stmt = $conn->prepare("UPDATE users SET ucover = ? WHERE uid = ?");
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
