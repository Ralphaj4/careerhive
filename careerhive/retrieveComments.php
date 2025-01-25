<?php
require('database.php');
require_once('functions.php');
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
            comments.cpost = ?");
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
            $image = getUserImage($comment['uid']);
            $comment['image'] = base64_encode($image);
         }
     }

    $image = getUserImage($comment['uid']);
     
    //  if (!empty($comments['uimage'])) {
    //     $image = $comments['uimage'];
    // }
    //'image' => $image_base64,
    $response = [
        'comments' => $comments,
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    // echo json_encode([
    //     'uid' => $comments[0],
    //     'message' => json_last_error_msg()
    // ]);

    // Encode to JSON with error handling
    // $json = json_encode($comments ?: []);
    // if ($json === false) {
    //     // Handle JSON encoding error
    //     echo json_encode([
    //         'error' => 'JSON encoding error',
    //         'message' => json_last_error_msg()
    //     ]);
    //     exit;
    // }

    //echo $json; // Output the JSON response
// } else {
//     header('Content-Type: application/json; charset=utf-8');
//     echo json_encode([]); // Return empty array for invalid requests
}
?>
