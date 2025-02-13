<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;
    $stmt = $conn->prepare("SELECT 
    posts.ptext,
    posts.pimage,
    posts.pauthor,
    posts.pid,
    posts.pcreation,
    institutions.iimage, 
    institutions.iname,
    institutions.itype,
    institutions.iid,
    
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
    institutions ON posts.pauthor = institutions.iid
WHERE 
    institutions.iid = ?
GROUP BY 
    posts.pid, institutions.iid
ORDER BY
    posts.pcreation DESC;
");

    $stmt->bind_param("iii", $id, $id, $id);
    if (!$stmt->execute()) {
        echo "Error executing query: " . $stmt->error;
        exit;
    }
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    $stmt_institutions = $conn->prepare("SELECT institutions.iname,institutions.iimage, institutions.icover,institutions.itype, institutions.iid                                                     
    FROM institutions WHERE iid = ?");
    $stmt_institutions->bind_param("i", $id);
    if (!$stmt_institutions->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result_institutions = $stmt_institutions->get_result();
    $institutions = $result_institutions->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_institutions->close();


    $response = [
        'posts' => $posts,
        'institution' => $institutions,
        
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
