<?php
session_start(); // Start the session to access session variables

if (!isset($_COOKIE['id'])) {
    header("Location: index.php");
    exit;
}

require_once('functions.php');

// Check if session variables exist before using them
$user_image = isset($_SESSION['uimage']) ? $_SESSION['uimage'] : 'images/default-profile.png';
$fname = isset($_SESSION['fname']) ? $_SESSION['fname'] : 'User';
$lname = isset($_SESSION['lname']) ? $_SESSION['lname'] : '';

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="images/logo.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<nav class="navbar">
    <div class="navbar-left">
        <a href="home.php" class="logo"><img src="images/logo.png"></a>

        <div class="search-box">
            <input type="text" placeholder="Search" id="searchInput">
            <span><button id="search" class="search" style="border: none; cursor: pointer;" onClick="retrieveProfiles()">
                <img src="images/search.png"></button></span>
        </div>
    </div>
    
    <div class="navbar-center">
        <ul>
            <li><a href="home.php" class="<?= $current_page == 'home.php' ? 'active-link' : '' ?>">
                <img src="images/home.png"><span>Home</span></a></li>
            <li><a href="network.php" class="<?= $current_page == 'network.php' ? 'active-link' : '' ?>">
                <img src="images/network.png"><span>My Network</span></a></li>
            <li><a href="jobs.php" class="<?= $current_page == 'jobs.php' ? 'active-link' : '' ?>">
                <img src="images/jobs.png"><span>Jobs</span></a></li>
        </ul>
    </div>

    <div class="navbar-right">
        <div class="online">
            <img class="nav-profile-img" onclick="toggleDropMenu()" src="<?= $user_image ?>" alt="Profile Image" />
        </div>
    </div>

    <!------- Profile Drop-down Menu ------>
    <div class="profile-menu-wrap" id="profileMenu">
        <div class="profile-menu">
            <div class="user-info">
                <img src="<?= $user_image ?>" alt="Profile Image" />
                <div>
                    <h4><?= htmlspecialchars($fname . " " . $lname) ?></h4>
                    <a href="myprofile.php">See your profile</a>
                </div>
            </div>
            <hr>
            <a href="#" class="profile-menu-link">
                <img src="images/feedback.png">
                <p>Give Feedback</p>
                <span>></span>
            </a>
            <a href="#" class="profile-menu-link">
                <img src="images/setting.png">
                <p>Settings & Privacy</p>
                <span>></span>
            </a>
            <a href="mailto:ralphaj4@gmail.com" class="profile-menu-link">
                <img src="images/help.png">
                <p>Help & Support</p>
                <span>></span>
            </a>
            <a href="logout.php" class="profile-menu-link">
                <img src="images/logout.png">
                <p>Logout</p>
                <span>></span>
            </a>
        </div>
    </div>

</nav>

<script>
    let profileMenu = document.getElementById("profileMenu");

    function toggleDropMenu() {
        profileMenu.classList.toggle("open-menu");
    }

    function retrieveProfiles() {
        const searchInput = document.getElementById('searchInput');
        const userInput = searchInput.value.trim();
        const parts = userInput.split(/\s+/); // Splits by one or more spaces
        let requestData = { fname: "", lname: "" };

        if (parts.length >= 2) {
            requestData.fname = parts[0]; // First name
            requestData.lname = parts.slice(1).join(" "); // Remaining words as last name
        } else {
            requestData.fname = userInput; // If only one word, it's the first name
        }

        fetch('a_retrieveProfiles.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            sessionStorage.setItem("searchResults", JSON.stringify(data));
            window.location.href = `search.php`;
        })
        .catch(console.error);
    }
</script>

</body>
</html>
