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


    // Encode images and ensure UTF-8 encoding as needed
    foreach ($posts as &$post) {
        foreach ($post as $key => $value) {
            //$post[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }
    }


    function fetchNews(){
        require_once 'vendor/autoload.php';
    
        $key = "pub_677278084f8d62edf13a3930c0ac6000b98a7";
        $newsdataApiObj = new NewsdataApi($key);
    
        $topics = ["technology", "politics", "sports", "science", "movies", "health", "ai", "programming", "music", "forex", "economics", "crypto"];
        $countries = ["us"];
    
        $randomTopic = $topics[array_rand($topics)];
        $randomCountry = $countries[array_rand($countries)];
    
        $data = array("q" => $randomTopic, "country" => $randomCountry);
    
        $response = $newsdataApiObj->get_latest_news($data);
    
        $newsArray = json_decode(json_encode($response), true);
    
        if (isset($newsArray['results']) && is_array($newsArray['results'])) {
            shuffle($newsArray['results']);
            $randomNews = array_slice($newsArray['results'], 0, 4);
            
            $news = json_encode([
                "search_query" => $randomTopic,
                "country" => $randomCountry,
                "articles" => $randomNews
            ], JSON_PRETTY_PRINT);
    
            $fourNews = json_decode($news, true);
            return $fourNews;
            //['articles']['3']['title'];
        }
    
        else {
            echo json_encode(["error" => "No news found for '$randomTopic' in '$randomCountry'"], JSON_PRETTY_PRINT);
        }
    }

    $x = fetchNews();


    $response = [
        'posts' => $posts,
        'news' => $x
    ];

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>