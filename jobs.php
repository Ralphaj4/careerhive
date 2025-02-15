<?php if(isset($_COOKIE['inst'])){
    header("refresh:3;mypage.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/logo.ico">
    <title>CareerHive</title>
</head>
<body data-page="jobs">
    <?php 
    session_start();
    require("functions.php");
    require('navbar.php');
    $connections = getConnectionCount(base64_decode($_COOKIE["id"]));
    $applications = getApplicationCount(base64_decode($_COOKIE["id"]));
    $following = getFollowingCount(base64_decode($_COOKIE["id"]));
?>
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
    <div class="main-content" style="flex-basis: 73%; overflow-x: hidden; width: 40%">
        <h2 style="margin-top: 20px; margin-left:10px;">Available Jobs</h2>
        <div class="network-container" >
            <div class="jobs-container" style="flex-basis: 120%;">
                <!-- Jobs section (left) -->
                <div class="jobs-wrapper my-div" id="jobs-container" style="overflow-x: hidden; overflow: auto; ">
                    <div class="spinner-container">
                        <div class="spinner"></div>
                    </div>
                </div>
                
                <!-- Vertical line -->
                <div class="vertical-line"></div>
                
                <!-- Pages section (right) -->
                <div class="pages-wrapper" id="pages-container">
                    <div class="spinner-container">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>

            <!-- Modal or Apply Form (optional) -->
            <div id="apply-form-container" style="display:none;">
                <!-- This container can hold a global form for applications if needed -->
            </div>
        </div>
    </div>
</div>
</body>

<script src="scripts.js"></script>
</html>
