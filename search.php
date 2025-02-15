<?php 
session_start();
require('database.php');
require('functions.php');
require('navbar.php');

$connections = getConnectionCount(base64_decode($_COOKIE["id"]));
$applications = getApplicationCount(base64_decode($_COOKIE["id"]));
$following = getFollowingCount(base64_decode($_COOKIE["id"]));

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
<body>
<div class="container">
    <div class="left-sidebar">
        <div class="sidebar-profile-box">
         <?php echo '<img src="' . $_SESSION['ucover'] . '" alt="" width="100%" class="cover-home"> ' ?><!---- hon fina n7ot l cover pic lal user l howe bina2iha---->
            <div class="sidebar-profile-info">
                <?php echo '<img src="' . $_SESSION['uimage'] . '" alt="" />'; ?>    <!---- hon fina n7ot soret l user l 3amel login ---->
                <?php echo '<a href="myprofile.php" style="text-decoration: none; color: inherit;"><h1>'.$_SESSION["fname"]. " ".$_SESSION["lname"].'</h1></a>'?>
                <?php echo '<h3>'.$_SESSION["title"].'</h3>'?>
                <ul>
                    <?php echo '<li>Your followings<span>'.$following.'</span></li>' ?>
                    <?php echo '<li>Your applications<span>'.$applications.'</span></li>' ?>
                    <?php echo '<li> Your connections<span>'. $connections . '</span></li>' ?>
                </ul>
            </div>
            <div class="sidebar-profile-link">
                <a href="myitems.php"><img src="images/items.png">My items</a>
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
    <div class="main-content">
        <h2>Search Results</h2>
        <div id="resultsContainer" style="width: 100%;"></div> 
    </div>

    <div class="right-sidebar">
        <div class="sidebar-news" id="sidebar-news">
            <h3>Trending News</h3>
            <div class="spinner-container">
                <div class="spinner"></div>
            </div>
        </div>
        <div class="sidebar-useful-links">
            <div class="copyright-msg">
            <img src="images/logo.png">
            <p>CareerHive &#169; 2025. All rights reserved</p>
            </div>
        </div>
    </div>
</div>
    <script>
        function getCookie(name) {
            let cookies = document.cookie.split('; ');
            for (let cookie of cookies) {
                let [key, value] = cookie.split('=');
                if (key === name) {
                    return decodeURIComponent(value);
                }
            }
            return null;
        }

        function getDecodedCookie(name) {
            let encodedValue = getCookie(name);
            return encodedValue ? atob(encodedValue) : null;
        }
        const searchbox = document.getElementById('search');

        searchbox.addEventListener("click", function() {
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
            window.location.href = `search.php`;
            })
            .catch(console.error);
        });

        const searchResults = JSON.parse(sessionStorage.getItem("searchResults"));

        if (searchResults && searchResults.profiles) {
            const container = document.getElementById("resultsContainer");
            const cookieid = getDecodedCookie('id');
            searchResults.profiles.forEach(profile => {
                if (String(atob(decodeURIComponent(profile.uid))).trim() !== String(cookieid).trim()) { 
                const profileDiv = document.createElement("div");
                profileDiv.classList.add("profile-card");
                profileDiv.style.backgroundColor = "white";
                
                let imageTag = "";
                if (profile.uimage) {
                    imageTag = `<a href="profile.php?id=${profile.uid}"><img src="${profile.uimage}" alt="User Image" class="profile-img"></a>`;
                }

                // Fill the div with user info
                profileDiv.innerHTML = `
                    ${imageTag}
                    <h3><a href="profile.php?id=${profile.uid}">${profile.ufname} ${profile.ulname}<a></h3>
                    <p>About: ${profile.udescription|| 'No about'}</p>
                `;

                container.appendChild(profileDiv);
            }
            });
        } else {
            document.getElementById("resultsContainer").innerHTML = "<p>No search results found.</p>";
        }
</script>

</body>
</html>
