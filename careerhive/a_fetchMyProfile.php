<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = base64_decode($_COOKIE['id']);
    $stmt_posts = $conn->prepare("SELECT * from posts WHERE pauthor = ? ");
    

    $stmt_posts->bind_param("i", $id);
    if (!$stmt_posts->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }

    $result_posts = $stmt_posts->get_result();
    $posts = $result_posts->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_posts->close();


    $stmt_user = $conn->prepare("SELECT uimage, ucover, udescription, utitle from users WHERE uid = ? ");
    $stmt_user->bind_param("i", $id);
    if (!$stmt_user->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result_user = $stmt_user->get_result();
    $user = $result_user->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_user->close();

    $response = [
        'posts' => $posts,
        'user' => $user
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
