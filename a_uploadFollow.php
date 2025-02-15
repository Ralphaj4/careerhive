<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $type = $data['type'];
    $page = $data['pid'];
    $id = base64_decode($_COOKIE['id']);
    if($type){
        $stmt = $conn->prepare("INSERT INTO follow(finstitutions, fusers) VALUES(?, ?)");
    }else if (!$type){
        $stmt = $conn->prepare("DELETE FROM follow WHERE finstitutions = ? AND fusers = ?");
    }
    
    $stmt->bind_param("ii", $page, $id); // 'si' means string and integer

    // Execute the statement
    if ($stmt->execute()) {
        
    }

    $stmt->close();
    $conn->close();
} 
?>
