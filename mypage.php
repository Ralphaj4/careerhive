<?php
session_start();

// Include database and functions
require('database.php');
require('functions.php');

// Retrieve the user ID from the cookie
$userId = base64_decode($_COOKIE["id"]); // Decode the ID from the cookie
$employees = getEmployeeCount($userId); 

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
<body data-page="mypage">
    
<?php include("navbarInst.php")?>

<div class="container">
    <div class="profile-main">
        <div class="profile-container">
            <img id="cover-image" class="cover-image" src="default-cover.jpg" alt="Cover Image">
            <div class="profile-container-inner">
                <!-- Display the user's profile image dynamically -->
                <div class="container-pfp">
                    <img id="profile-image" alt="Profile Image" class="profile-pic" onClick="viewFullImage()" style="cursor: pointer;">
                    <img src="images/camera.png" class="camera" onClick="toggleMenu()" style="cursor: pointer;">
                    
                    <!-- The pop-up menu that slides out from the camera icon -->
                    <div id="camera-menu" class="camera-menu">
                        <div class="menu-item" id="cover-image2" onClick="editImage('cover')">Cover Image</div>
                        <div class="menu-item" id="profile-pic" onClick="editImage('profile')">Profile Picture</div>
                    </div>
                </div>
                    <input type="file" id="cover-image-input" accept="image/*" style="display: none;">
                    <input type="file" id="profile-image-input" accept="image/*" style="display: none;">
                <h1 id="name2"></h1>
                <div id="imageModal" class="modal" style="display: none;">
                    <span id="closeModal" class="close">&times;</span>
                    <img id="fullImage" alt="Full Profile Image" style="width: 100%; height: auto;">
                </div>
                <div class="profile-container-inner">
                    <b id="currentTitle"></b>

                <div class="mutual-connections">
                    <img src="images/user-1.png">
                    <span><?php echo $employees == 1 ? "1 employee" : "$employees employees"; ?> </span>

                </div>
                
            </div>
        </div>
    </div>

    <div class="profile-description ">
        <br>
        <h2>About</h2>
        <div class="textarea-container">
            <?php 
                // Fetch user's saved description from the database
                $savedDescription = isset($_SESSION['udescription']) ? $_SESSION['udescription'] : "";
                $maxLength = 600;
                $remaining = $maxLength - strlen($savedDescription);
            ?>
            
            <textarea id="description" name="description" rows="5" maxlength="250"
                placeholder="Write about yourself..."><?php echo htmlspecialchars($savedDescription); ?></textarea>
            
            <div class="char-count-btn-container">
                <p id="charCount"><?php echo $remaining; ?> characters remaining</p>
                <button type="button" id="submit-btn" class="save-btn" onclick="uploadDescription(<?php echo $userId; ?>)">Save</button>
            </div>
        </div>
    </div>
    
    <h2 style="margin-bottom: 25px;">Posts</h2>
    <form id="textForm" method="post" enctype="multipart/form-data">
    <div class="create-post">
        <div class="create-post-input">
            <img id="profile-image-post" alt="Profile Image" class="profile-pic" style="margin: 0 ; margin-right :15px;" >
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

    <div class="profile-post-container">
        </div>
</div>

<div class="profile-sidebar">
        <div class="sidebar-news" id="sidebar-news">
            <h3>Trending News</h3>
        </div>
    </div>
    
</div>

    
              
</body>
<script src="scripts.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var textarea = document.getElementById("description");
    var countDisplay = document.getElementById("charCount");
    var maxLength = 600;

    // Get the saved description text from the textarea (content already in the field)
    var savedDescription = textarea.value;
    var remaining = maxLength - savedDescription.length;
    
    // Update the character count display based on the saved description
    countDisplay.textContent = remaining + " characters remaining";

    // Update count on input (as user types)
    textarea.addEventListener("input", function() {
        // Remove any line breaks (newlines, carriage returns)
        textarea.value = textarea.value.replace(/[\n\r]+/g, ''); 

        // Recalculate remaining characters after removing line breaks
        var remaining = maxLength - textarea.value.length;
        countDisplay.textContent = remaining + " characters remaining";
    });
});


</script>
</html>
