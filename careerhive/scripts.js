let profileMenu = document.getElementById("profileMenu");

function toggleMenu(){
    profileMenu.classList.toggle("open-menu");
}


document.addEventListener('DOMContentLoaded', function () {
    const submitButton = document.getElementById('submitBtn');
    const responseDiv = document.getElementById('response');
    const userInputField = document.getElementById('userInput');
  
    submitButton.addEventListener('click', function () {
      // Get the value from the textarea
      const userInput = userInputField.value;
  
      // Perform an AJAX POST request using Fetch API
      fetch('insert.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `userInput=${encodeURIComponent(userInput)}`,
      })
      
        .then((response) => response.text())
        .then((data) => {
            // Display the response from the server
            setTimeout(() => {
                window.location.href = window.location.href; // Reloads the page
              }, 100);
        })
        .catch((error) => {
          // Handle errors
          alert(data);
          setTimeout(() => {
            window.location.href = window.location.href; // Reloads the page
          }, 100);
        });
    });
  });
  