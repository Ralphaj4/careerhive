<?php
session_start();

// Include database and functions
require('database.php');
require('functions.php');

// Retrieve the user ID from the cookie
$userId = base64_decode($_COOKIE["id"]); // Decode the ID from the cookie
$connections = getConnectionCount($userId); 
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
<body data-page="myprofile">
    
<?php include('navbar.php') ?>

<div class="container">
    <div class="profile-main">
        <div class="profile-container">
            <img id="cover-image" width="100%">
            <div class="profile-container-inner">
                <!-- Display the user's profile image dynamically -->
                <img id="profile-image" alt="Profile Image" class="profile-pic">
                <?php echo '<h1>' . $_SESSION["fname"] . " " . $_SESSION["lname"] . '</h1>'; ?>
                <div class="profile-container-inner">
                    <b id="currentTitle"></b>
                        <button class="title-edit" id="editTitleBtn" onClick="editTitle()">
                            <img src="images/pencil.png" alt="Edit title" />
                        </button>
                        
                    <!-- Hidden input field that becomes visible when the button is clicked -->
                    <input type="text" id="titleInput" class="title-input" value="Current Title" style="display:none;" />
                </div>

                <!-- Hidden input field for editing -->
                <input type="text" id="title-input" value="<?php echo $utitle; ?>" style="display: none;">
                <button id="save-title-btn" style="display: none;" onclick="saveTitle()">Save</button>

                <div class="mutual-connections">
                    <img src="images/user-1.png">
                    <span><?php echo $connections ?> connections</span>
                </div>
                <div class="profile-btn">
                    <button class="primary-btn">
                        <img src="images/connect.png" alt="Connect Icon"> Connect
                    </button>
                    <button>
                        <img src="images/chat.png" alt="Chat Icon"> Message
                    </button>
                </div>
            </div>
        </div>

        <div class="profile-description">
            <br>
            <h2>About</h2>
            <div class="textarea-container">
                <textarea id="description" name="description" rows="5" placeholder="Write about yourself..."></textarea>
                <?php 
                    echo'<button type="button" id="submit-btn" class="save-btn" onclick="uploadDescription('.$userId.')">Save</button>'
                ?>
            </div>
        </div>

    <div class="profile-sidebar"></div>
</div>

<script src="scripts.js"></script>

</body>
</html>
