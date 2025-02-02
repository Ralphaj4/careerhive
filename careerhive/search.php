<?php 
session_start();
require('navbar.php'); ?>

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

    <h2>Search Results</h2>
    <div id="resultsContainer"></div> <!-- Placeholder for search results -->

    <script>
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

        searchResults.profiles.forEach(profile => {
            const profileDiv = document.createElement("div");
            profileDiv.classList.add("profile-card"); // Optional: Add CSS class

            // Create an image element if the profile has an image
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
        });
    } else {
        document.getElementById("resultsContainer").innerHTML = "<p>No search results found.</p>";
    }
</script>

    <style>
        /* Basic styling for results */
        .profile-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</body>
</html>
