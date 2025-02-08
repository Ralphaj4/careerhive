<?php 
if(!isset($_COOKIE['id'])){
    header("Location: index.php");
    exit;
}
if (isset($_GET['id'])) {
    require('functions.php');
    session_start();
    $user = getUserData(intval(base64_decode($_GET['id'])));
}
$connections = getConnectionCount(base64_decode($_GET["id"]));


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
<body data-page="profile">
    
<?php include('navbar.php');
echo '
<div class="container">
    <div class="profile-main">
        <div class="profile-container">
            <img src="images/cover-pic.png" alt="Cover Picture" width="100%">
            <div class="profile-container-inner">
                <img src="' . $user['uimage'] . '" alt="Profile Image" class="profile-pic">
                <h1>' . htmlspecialchars($user['ufname']) . ' ' . htmlspecialchars($user['ulname']) . '</h1>
                <b>' . htmlspecialchars($user['utitle']) . '</b>
                <div class="mutual-connections">
                    <img src="' . $user['uimage'] . '" alt="Profile Image">
                    <span>' . htmlspecialchars($connections) . ' connections</span>
                </div>
                <div class="profile-btn">
                    <button class="primary-btn" id="primary-btn" onclick="sendConnectionInProfile()">
                        <img src="images/connect.png" alt="Connect Icon" id="connect-img">
                        <span>Connect</span> <!-- Ensure this span is inside the button -->
                    </button>

                    <button>
                        <img src="images/chat.png" alt="Chat Icon"> Message
                    </buttons>
                </div>
            </div>
        </div>

        <div class="profile-description">
        <br>
            <h2>About</h2>
            <div class="textarea-container">
                <textarea id="description" name="description" rows="5" placeholder="No About" disabled>'.$user['udescription'].'</textarea>
            </div>
        </div>

        <div class="profile-sidebar"></div>
    </div>
</div>
<script src="scripts.js"></script>
';
?>