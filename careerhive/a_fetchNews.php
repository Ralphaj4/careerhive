<?php
use NewsdataIO\NewsdataApi;
header('Content-Type: application/json');
//CHANGED TO GET => Change to POST when needed
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    require_once 'vendor/autoload.php';

    $key = "pub_677278084f8d62edf13a3930c0ac6000b98a7";
    $newsdataApiObj = new NewsdataApi($key);

    $topics = ["technology", "politics", "sports", "science", "movies", "health", "ai", "programming", "music", "forex", "economics", "crypto"];
    $countries = ["us"];

    $randomTopic = $topics[array_rand($topics)];
    $randomCountry = $countries[array_rand($countries)];

    $data = ["q" => $randomTopic, "country" => $randomCountry];

    $response = $newsdataApiObj->get_latest_news($data);

    $newsArray = json_decode(json_encode($response), true);
    
    $fourNews = []; // Ensure variable is always set

    if (isset($newsArray['results']) && is_array($newsArray['results'])) {
        shuffle($newsArray['results']);
        $fourNews = array_slice($newsArray['results'], 0, 4);
    }

    // Prepare the response
    $response = [
        "search_query" => $randomTopic,
        "country" => $randomCountry,
        "articles" => $fourNews
    ];

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

?>