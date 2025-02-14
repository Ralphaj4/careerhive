<?php 
require('navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/logo.ico">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <title>CareerHive</title>
</head>
<body data-page="network">
    <div class="main-content">
    <div class="sort-by">
            <hr>
            <p>Sort by: <span>top <img src="images/down-arrow.png"></span></p>
        </div>
        
        <div id="posts-container">
            <h1>My Network</h1>
            <div class="network-container">
                <div id="resultsContainer">
                    <div class="spinner-container">
                        <div class="spinner"></div>
                    </div>
                </div>
                <div class="incomingConn" id="incomingConn">
                    <h3>Incoming</h3>
                </div>
            </div>
        </div>
    </div>
<script src="scripts.js"></script>
</body>
</html>