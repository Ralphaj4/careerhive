<?php 
session_start();
require('navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <title>CareerHive</title>
</head>
<body data-page="myitems">
    <div class="main-content">
    <div class="sort-by">
            <hr>
            <p>Sort by: <span>top <img src="images/down-arrow.png"></span></p>
        </div>
        
        <div id="posts-container">
        <div class="spinner-container">
            <div class="spinner"></div>
        </div>
    </div>
<script src="scripts.js"></script>
</body>
</html>