<?php
require('database.php');
mysqli_set_charset($conn, 'utf8mb4');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputData = file_get_contents("php://input");
    
    // Decode the JSON data
    $data = json_decode($inputData, true);
    $pid = $data['commentbtn'];
    $stmt = $conn->prepare("SELECT users.uid, users.ufname, users.ulname, users.uimage, comments.text, comments.ctime 
        FROM 
            users
        JOIN
            comments
        ON
            users.uid = comments.cuser
        WHERE
            comments.cpost = ?
        ORDER BY
            comments.ctime DESC");
    $stmt->bind_param("i", $pid);
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }

    $result = $stmt->get_result();
    $comments = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt->close();
    
     foreach ($comments as &$comment) {
         foreach ($comment as $key => $value) {
            $comment[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            
         }
     }

    $response = [
        'comments' => $comments,
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
