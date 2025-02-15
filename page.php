<?php 
if(!isset($_COOKIE['id'])){
    header("Location: index.php");
    exit;
}
if(isset($_COOKIE['inst'])){
    header("Location: index.php");
    exit;
}
if (isset($_GET['id'])) {
    require('functions.php');
    $user = getInstData(base64_decode($_GET['id']));
    $employees = getEmployeeCount(base64_decode($_GET["id"]));
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
session_start();
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
                    <span>' . htmlspecialchars($employees) . ' employees</span>
                </div>'; 
?>

<?php if (!isset($_COOKIE['inst'])) { ?>
    <div class="profile-btn">
        <button class="primary-btn" id="primary-btn" onclick="followPage()">
            <img src="images/connect.png" alt="Connect Icon" id="follow-img">
            <span>Follow</span>
        </button>
    </div>
<?php } ?>


    </div>
        </div>
        <h2 style="margin-top: 20px; margin-left:10px;">Jobs</h2>

    <div class="jobs-container">
        <div class="jobs-wrapper" id="jobs-container" style="flex-basis: 100%; overflow-x: hidden;">
            <div class="spinner-container">
                <div class="spinner"></div>
            </div>
        </div>
    </div>

    <!-- Modal or Apply Form (optional) -->
    <div id="apply-form-container" style="display:none;">
        <!-- This container can hold a global form for applications if needed -->
    </div>
        <div class="profile-sidebar"></div>

    </div>
    
    <div class="profile-sidebar">
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
