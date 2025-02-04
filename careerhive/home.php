<?php 
if(!isset($_COOKIE['id'])){
    header("Location: index.php");
    exit;
}
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
<body>
   
<?php 
require('functions.php');
session_start();
storeInSession(base64_decode($_COOKIE["id"]));
require('navbar.php');
require('database.php');
$connections = getConnectionCount(base64_decode($_COOKIE["id"]));
// $news = fetchNews();
// echo $news['articles']['3']['title'];
?>


<div class="container">
    <div class="left-sidebar">
        <div class="sidebar-profile-box">
            <img src="images/cover-pic.png" width="100%"> <!---- hon fina n7ot l cover pic lal user l howe bina2iha---->
            <div class="sidebar-profile-info">
                <?php echo '<img src="' . $_SESSION['uimage'] . '" alt="" />'; ?>    <!---- hon fina n7ot soret l user l 3amel login ---->
                <?php echo '<a href="myprofile.php" style="text-decoration: none; color: inherit;"><h1>'.$_SESSION["fname"]. " ".$_SESSION["lname"].'</h1></a>'?>
                <?php echo '<h3>'.$_SESSION["title"].'</h3>'?>
                <ul>
                    <li>Your profile views<span>52</span></li>  <!---- l number of views badna nshuf eza 7a n3mlo w kif--->
                    <li>Your post views<span>120</span></li>    <!---- nfs l fkra --->
                    <?php echo '<li> Your connections<span>'. $connections . '</span></li>' ?>   <!---- w hay kamen --->
                </ul>
            </div>
            <div class="sidebar-profile-link">
                <a href="#"><img src="images/items.png">My items</a>
                <a href="#"><img src="images/premium.png">Post ad</a> <!---- hon hiye l panel l 3al yamin l 5asa bel ads mn5aliha eza 7ada 3ml add ad byn7at sorto 3al yamin w sho bado y3ml post ----->
            </div>
        </div>
        <div class="sidebar-activity">
            <h3>RECENT</h3>
            <a href="#"><img src="images/recent.png">First Recent</a>  <!----kl ho l recent kamen badna nshuf l db eza bdna na3malon aw la2 ----->
            <a href="#"><img src="images/recent.png">Second Recent</a>
            <a href="#"><img src="images/recent.png">Third Recent</a>
            <a href="#"><img src="images/recent.png">Fourth Recent</a>
            <h3>Groups</h3>
            <a href="#"><img src="images/group.png">First Group</a>  <!----- hon nfs fkrt l recents kamen badna nshuf eza mn3mila 7asab l ds ---->
            <a href="#"><img src="images/group.png">Second Group</a>
            <a href="#"><img src="images/group.png">Third Group</a>
            <a href="#"><img src="images/group.png">Fourth Group</a>
            <div class="discover-more-link">
            <h3><a href="#">Discover More</a></h3>
            </div>
        </div>
    </div>

    <!------- posts ------->
    <div class="main-content">
    <form id="textForm" method="post" enctype="multipart/form-data">
    <div class="create-post">
        <div class="create-post-input">
            <?php echo '<img src="' . $_SESSION['uimage'] . '" alt="" />'; ?>
            <textarea id="userInput" name="userInput" rows="3" placeholder="What's on your mind"></textarea>
            <div id="mediaPreview"></div>
        </div>
        <div class="create-post-links">
            <li id="photoButton"><img src="images/photo.png"> Photo</li>
            <li><img src="images/video.png"> Video</li>
            <li><img src="images/event.png"> Event</li>
            <button type="submit" id="submitBtn"><li>Post</li></button>
        </div>
        <!-- Hidden File Input -->
        <input type="file" id="photoInput" name="photoInput" accept="image/*" style="display: none;">
    </div>
</form>

        

        <div class="sort-by">
            <hr>
            <p>Sort by: <span>top <img src="images/down-arrow.png"></span></p>
        </div>
        
        <div id="posts-container">
        <div class="spinner-container">
            <div class="spinner"></div>
        </div>
        </div>

               
    </div>

    <!------- right sidebar ------>
    <div class="right-sidebar">
        <div class="sidebar-news" id="sidebar-news">
            <h3>Trending News</h3>
        </div>


        <div class="sidebar-ad">
            <small>Ad &middot; &middot; &middot;</small>
            <p>First Ad</p> <!---- l text l bado ye l user l 3ml add ad ---->
            <div>
                <img src="images/user-1.png"> <!---- nfs l sora te3 l pfp ---->
                <img src="images/mi-logo.png"> <!---- l image l bado y3mila upload l user , eza ma bado sora mn5aliha fadye bala ma yn3amala display ---->
            </div>
            <b>Brand and Demand in Xiaomi</b>
            <a href="#" class="ad-link">Learn More</a>
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