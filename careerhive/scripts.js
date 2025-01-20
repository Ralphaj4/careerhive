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

  function toggleImage() {
    var img = document.getElementById("toggleImage");
    img.style.opacity = 0; // Fade out the image
    
    // Wait until the image fades out before switching the source
    setTimeout(function() {
      if (img.src.includes("like.png")) {
        img.src = "images/liked.png";  // Change to the "liked" image
      } else {
        img.src = "images/like.png";        }
      
      // Fade the image back in after switching the source
      img.style.opacity = 1;
    }, 150);    }


document.querySelectorAll('.like_btn').forEach(button => {
    button.addEventListener('click', function () {
        const postId = this.dataset.postid;

        fetch('like_post.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ postid: postId })
        })
        .then(response => response.json())
        .then(data => {
            
        })
        .catch(console.error);
    });
});