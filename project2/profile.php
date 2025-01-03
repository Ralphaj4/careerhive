<?php
// Start the session
session_start();

// Include the database connection
require 'database.php'; // This file contains the MySQLi connection

// Check if the user is logged in (adjust as per your session structure)


// Get the logged-in user's ID
$userid = $_SESSION['userid'];

// Retrieve the current description from the database to display it
$stmt = $conn->prepare("SELECT description FROM user WHERE userid = ?");
$stmt->bind_param('i', $userid); // 'i' for integer
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If the user exists, display the form with their current description
if ($user) {
    $current_description = htmlspecialchars($user['description']);
} else {
    $current_description = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <title>AssociatedWith</title>
</head>
<body>
    
<?php include('navbar.php') ?>

<div class="container">
    <div class="profile-main">
        <div class="profile-container">
            <img src="images/cover-pic.png" width="100%"> <!----- mn l db ---->
            <div class="profile-container-inner">
                <img src="images/user-1.png" class="profile-pic"> <!----- mn l db ---->
                <h1>Username</h1> <!----- mn l db ---->
                <b>Title</b> <!----- mn l db ---->
                <div class="mutual-connections">
                    <img src="images/user-2.png">  <!------ mn l db ------>
                    <span>X mutual connections</span>
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