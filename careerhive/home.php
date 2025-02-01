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
$posts = RetrievePosts(base64_decode($_COOKIE['id']));
$user_image = getUserImage(base64_decode($_COOKIE['id']));

?>


<div class="container">
    <div class="left-sidebar">
        <div class="sidebar-profile-box">
            <img src="images/cover-pic.png" width="100%"> <!---- hon fina n7ot l cover pic lal user l howe bina2iha---->
            <div class="sidebar-profile-info">
                <?php echo '<img src="data:image/jpeg;base64,' . base64_encode($user_image) . '" alt="" />'; ?>    <!---- hon fina n7ot soret l user l 3amel login ---->
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
            <?php echo '<img src="data:image/jpeg;base64,' . base64_encode($user_image) . '" alt="" />'; ?>
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
        
        <?php 
        if(!empty($posts)){
            $count = 1;
            foreach ($posts as $post) {
                if(CheckIfLiked(base64_decode($_COOKIE['id']), $post['pid'])){
                    $likeButton = 'liked';
                }
                else{
                    $likeButton = 'like';
                }
                echo '<div class="post">';
                echo '<div class="post-author">';
                    echo '<a href="profile.php?id=' . urlencode(base64_encode($post['uid'])) . '">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($post['uimage']) . '" alt="">';
                    echo '</a>';
                    echo '<div>';
                        echo '<a href="profile.php?id=' . urlencode(base64_encode($post['uid'])) . '" style="text-decoration: none; color: inherit;">';
                            echo '<h1>' . htmlspecialchars($post['ufname']) . ' ' . htmlspecialchars($post['ulname']) . '</h1>';
                        echo '</a>';
                    echo '<small>' . htmlspecialchars($post['utitle']) . '</small>';
                    echo '<small>' . htmlspecialchars($post['pcreation']) . '</small>';
                echo '</div>';
            echo '</div>';
                    echo '<p>'. htmlspecialchars($post['ptext']). '</p>';
                    #HERE IS THE MEDIA OF THE POST
                    if(!empty($post['pimage'])){
                echo '<img src="data:image/jpeg;base64,' . base64_encode($post['pimage']) . '" alt="" width="100%" />';
                    }
                echo '<div class="post-stats">
                <div>
                    <img src="images/thumbsup.png">
                    <img src="images/love.png">
                    <img src="images/clap.png">
                    <span class="liked-users">' . $post['like_count'] . ' likes</span>  <!---- hon l name of user howe esem 7ayala user 3ml like w l x hiye l total number of people l 3mlo ---->
                </div>
                <div>
                    <span>'. (string)((int)$post['comment_count'] == 1 ? (int)$post['comment_count'] : (int)($post['comment_count']/2)).' comments &middot; y shares</span> <!---- kamen hon l number of comments w shares howe l number of elements l bel db ---->
                </div>
            </div>
            <div class="post-activity">
                <div>
                    <img src="data:image/jpeg;base64,' . base64_encode($user_image) . '" alt="" class="post-activity-user-icon">
                    <img src="images/down-arrow.png" class="post-activity-arrow-icon">
                </div>
                <div class="post-activity-link">
                    <button id="like" class="like" onClick="toggleImage('.$post['pid'].')" data-postid="'. $post['pid'] .'"><img src="images/'. $likeButton .'.png" id="toggleImage'.$post['pid'].'">
                    <span>Like</span></button>
                </div>
                <div class="post-activity-link">
                    <button id="showCommentBox'.$count.'" class="comment-post" onClick="showComments('. $count .','.$post['pid'].')" data-postid="'. $post['pid'] .'"><img src="images/comment.png">
                    <span>Comment</span></button>
                </div>
                <div class="post-activity-link">
                    <button><img src="images/share.png">
                    <span>Share</span></button>
                </div>
                <div class="post-activity-link">
                    <button><img src="images/send.png">
                    <span>Send</span></button>
                </div>
                <div id="overlay" class="overlay" style="display: none;"></div>
                <div id="whiteBox" class="white-box" style="display: none;">
                    <div>
                        <button id="close-Comments" class="close-Comments">
                            <p>X</p>    
                        </button>
                    </div>
                    <div class="comments-container">
                        <p>Comments will appear here...</p>
                    </div>
                    <div>
                    <textarea id="userComment" name="userComment" rows="1" placeholder="Type a comment"></textarea>
                    <button id="sendComment'. $post['pid'] .'" class="sendComment" onClick="postComment('.$post['pid'].')"><img src="images/send.png"></button>
                    </div>
                </div>
            </div>';

                echo '</div>';
                $count++;
            }
            
        }
        else{
            echo '<div style="display:flex; align-items:center;justify-content: center;height: 70vh;"><h2>No Posts Found</h2></div>';
        }
        ?>        
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