<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<nav class="navbar">
    <div class="navbar-left">
        <a href="index.php" class="logo"><img src="images/logo.png"></a>

        <div class="search-box">
            <img src="images/search.png" >
            <input type="text" placeholder="Search">
        </div>

    </div>
    <div class="navbar-center">
        <ul>
            <li><a href="index.php" class="active-link"><img src="images/home.png"><span>Home</span></a></li>
            <li><a href="#"><img src="images/network.png"><span>My Network</span></a></li>
            <li><a href="#"><img src="images/jobs.png"><span>Jobs</span></a></li>
            <li><a href="#"><img src="images/message.png"><span>Messaging</span></a></li>
            <li><a href="#"><img src="images/notification.png"><span>Notifications</span></a></li>
        </ul>
    </div>
    <div class="navbar-right">
        <div class="online">
        <img src="images/user-1.png" class="nav-profile-img" onclick="toggleMenu()">
        </div>
    </div>
<!------- profile drop down menu ------>

    <div class="profile-menu-wrap" id="profileMenu">
        <div class="profile-menu">
            <div class="user-info">
                <img src="images/user-1.png">
                <div>
                    <h3>Username</h3>
                    <a href="profile.php">See your profile</a>
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
            <a href="#" class="profile-menu-link">
                <img src="images/help.png">
                <p>Help & Support</p>
                <span>></span>
            </a>
            <a href="login.php" class="profile-menu-link">
                <img src="images/logout.png">
                <p>Logout</p>
                <span>></span>
            </a>
        </div>
    </div>

</nav>
</body>
</html>