<?php
// require_once 'vendor/autoload.php';

// use NewsdataIO\NewsdataApi;

// $key = "pub_677278084f8d62edf13a3930c0ac6000b98a7";
// $newsdataApiObj = new NewsdataApi($key);

// // Define an array of random topics and countries
// $topics = ["technology", "politics", "sports", "science", "movies", "health"];
// $countries = ["us"];

// // Pick a random topic and country
// $randomTopic = $topics[array_rand($topics)];
// $randomCountry = $countries[array_rand($countries)];

// // Define search criteria with random values
// $data = array("q" => $randomTopic, "country" => $randomCountry);

// // Fetch news data
// $response = $newsdataApiObj->get_latest_news($data);

// // Convert response to an associative array
// $newsArray = json_decode(json_encode($response), true);

// // Check if articles exist
// if (isset($newsArray['results']) && is_array($newsArray['results'])) {
//     // Shuffle the articles randomly
//     shuffle($newsArray['results']);

//     // Select 4 or 5 random articles
//     $randomNews = array_slice($newsArray['results'], 0, rand(4, 5));

//     // Output the random articles as JSON
    
//     $news = json_encode([
//         "search_query" => $randomTopic,
//         "country" => $randomCountry,
//         "articles" => $randomNews
//     ], JSON_PRETTY_PRINT);
//     $test = json_decode($news, true);
//     echo $test['articles']['3']['title'];
// }

// else {
//     echo json_encode(["error" => "No news found for '$randomTopic' in '$randomCountry'"], JSON_PRETTY_PRINT);
// }

require_once('functions.php');
fetchNews();

?>
