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

?>
<!------- navbar end------->

<div class="container">
    <!------- left sidebar ------->
    <div class="left-sidebar">
        <div class="sidebar-profile-box">
            <img src="images/cover-pic.png" width="100%"> <!---- hon fina n7ot l cover pic lal user l howe bina2iha---->
            <div class="sidebar-profile-info">
                <img src="images/user-1.png">    <!---- hon fina n7ot soret l user l 3amel login ---->
                <?php echo '<h1>'.$_SESSION["fname"]. " ".$_SESSION["lname"].'</h1>'?>
                <h3>Title</h3>  <!---- hon mn7ot l title mn l db---->
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

    <!------- main content ------->
    <div class="main-content">
        
        <div class="create-post">
            <div class="create-post-input">
                <img src="images/user-1.png">
                <textarea rows="2" placeholder="What's on your mind"></textarea>
            </div>
            <div class="create-post-links">
                <li><img src="images/photo.png">Photo</li>
                <li><img src="images/video.png">Video</li>
                <li><img src="images/event.png">Event</li>
                <li>Post</li>
            </div>
        </div>

        <div class="sort-by">
            <hr>
            <p>Sort by: <span>top <img src="images/down-arrow.png"></span></p>
        </div>
        
        <div class="post">
            <div class="post-author">
                <img src="images/ralph.jpg"> <!---- image l user mn l db ---->
                <div>
                    <h1>_@j</h1>  <!---- name mn l db ---->
                    <small>Founder and CEO of Pornhub | 9/11 Investor</small>  <!---- l title mn l db ---->
                    <small>Post time</small> <!---- post time mn l db ----> 
                </div>
            </div>
            <p>The post text that the user uploaded</p> <!------ mn l db ---->
            <img src="images/post-image-1.png" width="100%"> <!--- l image yale na2a ya3mila post l user aw l video --->

            <div class="post-stats">
                <div>
                    <img src="images/thumbsup.png">
                    <img src="images/love.png">
                    <img src="images/clap.png">
                    <span class="liked-users">Name of a user and x others</span>  <!---- hon l name of user howe esem 7ayala user 3ml like w l x hiye l total number of people l 3mlo ---->
                </div>
                <div>
                    <span>x comments &middot; y shares</span> <!---- kamen hon l number of comments w shares howe l number of elements l bel db ---->

                </div>
            </div>

            <div class="post-activity">
                <div>
                    <img src="images/user-1.png" class="post-activity-user-icon">
                    <img src="images/down-arrow.png" class="post-activity-arrow-icon">
                </div>
                <div class="post-activity-link">
                    <img src="images/like.png" >
                    <span>Like</span>
                </div>
                <div class="post-activity-link">
                    <img src="images/comment.png">
                    <span>Comment</span>
                </div>
                <div class="post-activity-link">
                    <img src="images/share.png">
                    <span>Share</span>
                </div>
                <div class="post-activity-link">
                    <img src="images/send.png">
                    <span>Send</span>
                </div>
            </div>

        </div>

        <div class="post">
            <div class="post-author">
                <img src="images/antonio.jpg"> <!---- image l user mn l db ---->
                <div>
                    <h1>Tiz l kalb</h1>
                    <small>Janitor at Lebanese University</small>  <!---- l title mn l db ---->
                    <small>Post time</small> <!---- post time mn l db ---->
                </div>
            </div>
            <p>The post text that the user uploaded</p>
            <img src="images/post-image-2.png" width="100%"> <!--- l image yale na2a ya3mila post l user aw l video --->

            <div class="post-stats">
                <div>
                    <img src="images/thumbsup.png">
                    <img src="images/love.png">
                    <img src="images/clap.png">
                    <span class="liked-users">Name of a user and x others</span>  <!---- hon l name of user howe esem 7ayala user 3ml like w l x hiye l total number of people l 3mlo ---->
                </div>
                <div>
                    <span>x comments &middot; y shares</span> <!---- kamen hon l number of comments w shares howe l number of elements l bel db ---->

                </div>
            </div>

            <div class="post-activity">
                <div>
                    <img src="images/user-1.png" class="post-activity-user-icon">
                    <img src="images/down-arrow.png" class="post-activity-arrow-icon">
                </div>
                <div class="post-activity-link">
                    <img src="images/like.png" >
                    <span>Like</span>
                </div>
                <div class="post-activity-link">
                    <img src="images/comment.png">
                    <span>Comment</span>
                </div>
                <div class="post-activity-link">
                    <img src="images/share.png">
                    <span>Share</span>
                </div>
                <div class="post-activity-link">
                    <img src="images/send.png">
                    <span>Send</span>
                </div>
            </div>

        </div> 
    </div>
    <!------- right sidebar ------>
    <div class="right-sidebar">
        <div class="sidebar-news">
            <img src="images/more.png" class="info-icon">
            <h3>Trending News</h3>
            <a href="#">Less work visa for US , more for UK</a>
            <span><?php echo "Uploaded :  " . date("h:i:sa"); ?></span>

            <a href="#">More hiring = higher confidence?</a>
            <span><?php echo "Uploaded :  " . date("h:i:sa"); ?></span>

            <a href="#">High demand for skilled manpower</a>
            <span><?php echo "Uploaded :  " . date("h:i:sa"); ?></span>

            <a href="#">Who is the world's richest?</a>
            <span><?php echo "Uploaded :  " . date("h:i:sa"); ?></span>

            <a href="#" class="read-more-link">Read More</a>
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