<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = base64_decode($_COOKIE['id']);
    $stmt = $conn->prepare("SELECT
    posts.ptext,
    posts.pimage,
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

    -- Check if the user liked the post
    CASE 
        WHEN EXISTS (
            SELECT 1 
            FROM likes 
            WHERE likes.lpost = posts.pid AND likes.luser = ?
        ) THEN 'liked'
        ELSE 'like'
    END AS is_liked,

    -- Since the posts are from saveditems, they are all saved
    'saved' AS is_saved

FROM saveditems
JOIN posts ON saveditems.pid = posts.pid
JOIN users ON posts.pauthor = users.uid
WHERE saveditems.uid = ?
ORDER BY posts.pcreation DESC;
");

    $stmt->bind_param("ii", $userId, $userId);
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }

    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt->close();
    


    $response = [
        'posts' => $posts,
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
