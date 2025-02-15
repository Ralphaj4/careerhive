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
    const searchResults = JSON.parse(sessionStorage.getItem("searchResults"));

        if (searchResults && searchResults.profiles) {
            const container = document.getElementById("resultsContainer");

            searchResults.profiles.forEach(profile => {
                const profileDiv = document.createElement("div");
                profileDiv.classList.add("profile-card"); // Optional: Add CSS class

                // Create an image element if the profile has an image
                let imageTag = "";
                if (profile.image) {
                    imageTag = `<img src="data:image/png;base64,${profile.image}" alt="User Image" class="profile-img">`;
                }

                // Fill the div with user info
                profileDiv.innerHTML = `
                    ${imageTag}
                    <h3>${profile.ufname} ${profile.ulname}</h3>
                    <p>Email: ${profile.uemail}</p>
                    <p>Location: ${profile.ulocation || 'Unknown'}</p>
                `;

                container.appendChild(profileDiv);
            });
        } else {
            document.getElementById("resultsContainer").innerHTML = "<p>No search results found.</p>";
        }
})
  .catch(console.error);
});