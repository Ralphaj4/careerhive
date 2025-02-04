<?php
// Start the session
session_start();

// Include database and functions
require('database.php');
require('functions.php');

// Retrieve the user ID from the cookie
$userId = base64_decode($_COOKIE["id"]); // Decode the ID from the cookie

$user = getUserData(intval($userId)); 

$query = "SELECT udescription FROM users WHERE uid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);  // 'i' is for integer (uid)
$stmt->execute();
$stmt->bind_result($user_description);
$stmt->fetch();
$stmt->close();

// Close the database connection
$conn->close();

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
<body>
    
<?php include('navbar.php') ?>

<div class="container">
    <div class="profile-main">
        <div class="profile-container">
            <img src="images/cover-pic.png" width="100%">
            <div class="profile-container-inner">
                <!-- Display the user's profile image dynamically -->
                <?php echo '<img src="' . $user['uimage'] . '" alt="Profile Image" class="profile-pic">'; ?>
                <?php echo '<h1>' . $_SESSION["fname"] . " " . $_SESSION["lname"] . '</h1>'; ?>
                <?php echo '<b>' . $_SESSION["title"] . '</b>'; ?>
                <div class="mutual-connections">
                    <img src="images/user-2.png">
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
            <h2>About</h2>
            <div class="textarea-container">
                <textarea id="description" name="description" rows="5" placeholder="Write about yourself..."><?php echo htmlspecialchars($user_description); ?></textarea>
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
