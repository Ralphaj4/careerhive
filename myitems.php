<?php 
require('functions.php');
session_start();
require('navbar.php');
require('database.php');
$connections = getConnectionCount(base64_decode($_COOKIE["id"]));
$applications = getApplicationCount(base64_decode($_COOKIE["id"]));
$following = getFollowingCount(base64_decode($_COOKIE["id"]));
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
<body data-page="myitems">
<div class="container">
    <div class="left-sidebar">
        <div class="sidebar-profile-box">
         <?php echo '<img src="' . $_SESSION['ucover'] . '" alt="" width="100%" class="cover-home"> ' ?>
            <div class="sidebar-profile-info">
                <?php echo '<img src="' . $_SESSION['uimage'] . '" alt="" />'; ?>
                <?php echo '<a href="myprofile.php" style="text-decoration: none; color: inherit;"><h1>'.$_SESSION["fname"]. " ".$_SESSION["lname"].'</h1></a>'?>
                <?php echo '<h3>'.$_SESSION["title"].'</h3>'?>
                <ul>
                    <?php echo '<li>Your followings<span>'.$following.'</span></li>' ?>
                    <?php echo '<li>Your applications<span>'.$applications.'</span></li>' ?>
                    <?php echo '<li> Your connections<span>'. $connections . '</span></li>' ?>
                </ul>
            </div>
            <div class="sidebar-profile-link">
                <a href="myitems.php"><img src="images/items.png">My items</a>
                <a href="#"><img src="images/premium.png">Post ad</a>
            </div>
        </div>

    </div>
    <div class="main-content">

        
        <div id="posts-container">
            <div class="spinner-container">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
    <div class="right-sidebar">
    <div class="sidebar-news" id="sidebar-news">
            <h3>Trending News</h3>
            <div class="spinner-container">
                <div class="spinner"></div>
            </div>
        </div>
        <div class="sidebar-useful-links">
            <div class="copyright-msg">
            <img src="images/logo.png">
            <p>CareerHive &#169; 2025. All rights reserved</p>
            </div>
        </div>
    </div>
</div>
<script src="scripts.js"></script>
</body>
</html>