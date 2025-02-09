<?php
if (!isset($_COOKIE['id'])) {
    header("Location: index.php");
    exit;
}
require_once('functions.php');
$user_image = getUserImage(base64_decode($_COOKIE['id']));

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
            <li><a href="messaging.php" class="<?= $current_page == 'messaging.php' ? 'active-link' : '' ?>">
                <img src="images/message.png"><span>Messaging</span></a></li>
            <li><a href="notifications.php" class="<?= $current_page == 'notifications.php' ? 'active-link' : '' ?>">
                <img src="images/notification.png"><span>Notifications</span></a></li>
        </ul>
    </div>

    <div class="navbar-right">
        <div class="online">
            <?php echo '<img class="nav-profile-img" onclick="toggleDropMenu()" src="' . $_SESSION['uimage'] . '" alt="" />'; ?>
        </div>
    </div>

    <!------- Profile Drop-down Menu ------>
    <div class="profile-menu-wrap" id="profileMenu">
        <div class="profile-menu">
            <div class="user-info">
                <?php echo '<img src="' . $_SESSION['uimage'] . '" alt="" />'; ?>
                <div>
                    <?php echo '<h4>' . $_SESSION["fname"] . " " . $_SESSION["lname"] . '</h4>' ?>
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
            <a href="mailto: ralphaj4@gmail.com" class="profile-menu-link">
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
function toggleDropMenu(){
        profileMenu.classList.toggle("open-menu");
    }
    function retrieveProfiles(){
  const searchbox = document.getElementById('search');
  searchInput = document.getElementById('searchInput')
  const userInput = searchInput.value;
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
    sessionStorage.removeItem("searchResults");
    sessionStorage.setItem("searchResults", JSON.stringify(data));
    //code before the pause
    
    window.location.href = `search.php`;
    
})
  .catch(console.error);
}

</script>
</body>
</html>
