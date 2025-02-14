<?php 
if(!isset($_COOKIE['id'])){
    header("Location: index.php");
    exit;
}
if (isset($_GET['id'])) {
    require('functions.php');
    $user = getInstData(base64_decode($_GET['id']));
    $connections = getEmployeeCount(base64_decode($_GET["id"]));
}
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
<body data-page="page">
    
<?php 
if(!isset($_COOKIE['inst'])){
    include('navbar.php');
}
else{
    include('navbarInst.php');
}

echo '
<div class="container">
    <div class="profile-main">
        <div class="profile-container">
            <img src="images/cover-pic.png" alt="Cover Picture" width="100%">
            <div class="profile-container-inner">
                <img src="' . htmlspecialchars($user['iimage']) . '" alt="Profile Image" class="profile-pic">
                <h1>' . htmlspecialchars($user['iname']) . '</h1>
                <b>' . htmlspecialchars($user['itype']) . '</b>
                <div class="mutual-connections">
                    <img src="' . htmlspecialchars($user['iimage']) . '" alt="Profile Image">
                    <span>' . htmlspecialchars($connections) . ' connections</span>
                </div>'; 
?>

<?php if (!isset($_COOKIE['inst'])) { ?>
    <div class="profile-btn">
        <button class="primary-btn" id="primary-btn" onclick="sendConnectionInProfile()">
            <img src="images/connect.png" alt="Connect Icon" id="connect-img">
            <span>Reject</span>
        </button>
        <button class="primary-btn" id="reject" onclick="sendConnectionInProfile(0)" style="display: none;">
            <img src="images/x.png" alt="Connect Icon" id="reject-img">
            <span>Connect</span>
        </button>
        <button>
            <img src="images/chat.png" alt="Chat Icon"> Message
        </button>
    </div>
<?php } ?>

<?php
echo '
            </div>
        </div>
        
        <div class="profile-experience-container"></div>
        <div class="profile-education-container"></div>
        <div class="profile-skills-container"></div>
        <div class="profile-language-container"></div>
        <div class="profile-post-container"></div>
        <div class="profile-sidebar"></div>

    </div>
    
    <div class="profile-sidebar">
        <div class="sidebar-news" id="sidebar-news">
            <h3>Trending News</h3>
        </div>
    </div>
</div>

<script src="scripts.js"></script>
';
?>
