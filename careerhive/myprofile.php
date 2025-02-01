<?php
// Start the session
session_start();

//Include database and functions
require('database.php');
require('functions.php');
storeInSession(base64_decode($_COOKIE["id"]));
$connections = getConnectionCount(base64_decode($_COOKIE["id"]));

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
    
<?php include('navbar.php') ?>

<div class="container">
    <div class="profile-main">
        <div class="profile-container">
            <img src="images/cover-pic.png" width="100%"> <!----- mn l db ---->
            <div class="profile-container-inner">
                <img src="images/user-1.png" class="profile-pic"> <!----- mn l db ---->
                <?php echo '<h1>'.$_SESSION["fname"]. " ".$_SESSION["lname"].'</h1>'?> <!----- mn l db ---->
                <?php echo '<b>'.$_SESSION["title"].'</b>'?> <!----- mn l db ---->
                <div class="mutual-connections">
                    <img src="images/user-2.png">  <!------ mn l db ------>
                    <span><?php echo $connections ?> connections</span>
                </div>
                <div class="profile-btn">
                    <a href="#" class="primary-btn"><img src ="images/connect.png">Connect</a>
                    <a href="#"><img src="images/chat.png">Message</a>
                </div>
            </div>
        </div>

        <div class="profile-description">
            <h2>About</h2>
            <form action="save_description.php" method="POST">
                <textarea name="description" rows="5" cols="40" placeholder="Write about yourself..."></textarea>
                <br>
                <button type="submit">Save</button>
            </form>
        
    </div>

<!--------- profile sidebar --------->

    <div class="profile-sidebar"></div>
</div>

<script src="scripts.js"></script>

</body>
</html>