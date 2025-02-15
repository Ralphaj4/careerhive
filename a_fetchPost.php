<?php
require('database.php');
require_once('functions.php');
mysqli_set_charset($conn, 'utf8mb4');
use NewsdataIO\NewsdataApi;
header('Content-Type: application/json'); // Ensure JSON response
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $posts = [];

    $userId = base64_decode($_COOKIE['id']);
    $stmt = $conn->prepare("SELECT 
    posts.ptext,
    posts.pimage,
    posts.pauthor,
    posts.pid,
    posts.pcreation,
    users.uimage, 
    users.ufname, 
    users.ulname, 
    users.utitle,
    users.uid,
    
    -- Accurate like and comment counts
    (SELECT COUNT(*) FROM likes WHERE likes.lpost = posts.pid) AS like_count,
    (SELECT COUNT(*) FROM comments WHERE comments.cpost = posts.pid) AS comment_count,
    (SELECT COUNT(*) FROM saveditems WHERE saveditems.pid = posts.pid) AS saved_count,
    -- Check if the user liked the post
    CASE
        WHEN EXISTS (
            SELECT 1 
            FROM likes 
            WHERE likes.lpost = posts.pid AND likes.luser = ?
        ) THEN 'liked'
        ELSE 'like'
    END AS is_liked,

    CASE
        WHEN EXISTS (
            SELECT 1 
            FROM saveditems 
            WHERE saveditems.pid = posts.pid AND saveditems.uid = ?
        ) THEN 'saved'
        ELSE 'save'
    END AS is_saved

FROM 
    posts
JOIN 
    users ON posts.pauthor = users.uid
JOIN 
    connections ON (connections.csender = users.uid OR connections.creceiver = users.uid) 
    AND connections.cstatus = 'accepted'
WHERE 
    users.uid != ? AND (connections.csender = ? OR connections.creceiver = ?)
GROUP BY 
    posts.pid, users.uid
ORDER BY
    posts.pcreation DESC;
");

    $stmt->bind_param("iiiii", $userId, $userId, $userId, $userId, $userId);
    if (!$stmt->execute()) {
        echo "Error executing query: " . $stmt->error;
        exit;
    }
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    if (empty($posts)) {
        echo json_encode(["message" => "No posts found for this user"]);
        exit;
    }


    $response = [
        'posts' => $posts
    ];

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>