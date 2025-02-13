<?php 
require('database.php'); 
require_once('functions.php'); 

mysqli_set_charset($conn, 'utf8mb4'); 
header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {     
    if (!isset($_COOKIE['id'])) {
        echo json_encode(["error" => "User ID cookie is not set"]);
        exit;
    }

    $userId = base64_decode($_COOKIE['id']);
    
    if (!is_numeric($userId)) {
        echo json_encode(["error" => "Invalid user ID"]);
        exit;
    }


    // Debug: Check if user has posts
    $stmt = $conn->prepare("SELECT COUNT(*) FROM posts WHERE pauthor = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count == 0) {
        echo json_encode(["message" => "No posts found for this user"]);
        exit;
    }

    // Main query to fetch user posts
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
            (SELECT COUNT(*) FROM likes WHERE likes.lpost = posts.pid) AS like_count, 
            (SELECT COUNT(*) FROM comments WHERE comments.cpost = posts.pid) AS comment_count, 
            (SELECT COUNT(*) FROM saveditems WHERE saveditems.pid = posts.pid) AS saved_count, 
            CASE 
                WHEN EXISTS (SELECT 1 FROM likes WHERE likes.lpost = posts.pid AND likes.luser = ?) THEN 'liked' 
                ELSE 'like' 
            END AS is_liked,  
            CASE 
                WHEN EXISTS (SELECT 1 FROM saveditems WHERE saveditems.pid = posts.pid AND saveditems.uid = ?) THEN 'saved' 
                ELSE 'save' 
            END AS is_saved  
        FROM posts 
        JOIN users ON posts.pauthor = users.uid  
        WHERE users.uid = ? 
        ORDER BY posts.pcreation DESC
    ");      

    $stmt->bind_param("iii", $userId, $userId, $userId); 

    if (!$stmt->execute()) {         
        echo json_encode(["error" => "Error executing query: " . $stmt->error]);         
        exit;     
    }     

    $result = $stmt->get_result();     
    $posts = $result->fetch_all(MYSQLI_ASSOC);      

    $stmt->close();      

    if (empty($posts)) {         
        echo json_encode(["message" => "No posts found for this user"]);         
        exit;     
    } 

    echo json_encode($posts);
}  
?>
