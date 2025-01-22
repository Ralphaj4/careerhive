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
      fetch('uploadPost.php', {
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
              }, 1);
        })
        .catch((error) => {
          // Handle errors
          //alert(data);
          setTimeout(() => {
            window.location.href = window.location.href; // Reloads the page
          }, 100);
        });
    });
  });

  function toggleImage(x) {
    var img = document.getElementById('toggleImage' + x);
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



document.querySelectorAll('.like').forEach(button => {
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

function showComments(id){
    // Get the white box and overlay element
    const whiteBox = document.getElementById('whiteBox');
    const overlay = document.getElementById('overlay');
    getComments(id);
    // Function to show or hide the white box and overlay with transition
    const toggleVisibility = () => {
        if (whiteBox.style.display === 'none' || whiteBox.style.display === '') {
            // Show the white box and overlay with transition
            whiteBox.style.display = 'block'; // Make the box visible
            overlay.style.display = 'block';  // Make the overlay visible
  
            // Trigger the transition by setting opacity and transform
            setTimeout(() => {
                whiteBox.style.opacity = '1';
                whiteBox.style.transform = 'translateX(-50%) translateY(0)'; // Slide into position
                overlay.style.opacity = '1'; // Fade in the overlay
            }, 10); // Delay to allow the box to be shown first
        } else {
            // Hide the white box and overlay with transition
            whiteBox.style.opacity = '0';
            whiteBox.style.transform = 'translateX(-50%) translateY(-30px)'; // Slide out of view
            overlay.style.opacity = '0'; // Fade out the overlay
  
            // After the transition ends, hide the box and overlay
            setTimeout(() => {
                whiteBox.style.display = 'none'; // Hide the white box
                overlay.style.display = 'none';  // Hide the overlay
            }, 300); // Match the duration of the transition
        }
    };
  
    // Add click event listener to toggle visibility of the white box and overlay
    document.addEventListener('click', function(event) {
        // Check if the click was outside the white box and overlay
        if (!whiteBox.contains(event.target) && !overlay.contains(event.target)) {
            toggleVisibility();
        }
    });
  
    // Prevent the white box from hiding when clicking inside it
    whiteBox.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent the click event from bubbling up to the document
    });
  }
  

function getComments(id){
    button = document.getElementById('showCommentBox' + id);
    const postId = button.dataset.postid;

        console.log(`Sending request for post ID: ${postId}`); // Debug the postId

        fetch('retrieveComments.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ commentbtn: postId }) 
        })
        .then(response => response.json())
    .then(data => {
        console.log(data);  // Log the response to inspect its structure

        const comments = data.comments;  // Access the comments array directly
        const commentsContainer = document.querySelector('.comments-container');
        const userCommentSection = document.getElementById('whiteBox');
        
        // Clear the "Comments will appear here..." message if needed
        commentsContainer.innerHTML = '';  // Clear the comments container first

        // If comments are available, add them to the container
        if (Array.isArray(comments) && comments.length > 0) {
            comments.forEach(comment => {
                const commentHTML = `
                    <div class="comment">
                        <div>
                            <strong>${comment.ufname} ${comment.ulname}</strong>
                            <p>${comment.text}</p>
                            <small>${new Date(comment.ctime).toLocaleString()}</small>
                        </div>
                    </div>
                `;
                commentsContainer.innerHTML += commentHTML;
            });
        } else {
            commentsContainer.innerHTML = '<p>No comments yet.</p>';  // Optionally show a message if no comments are found
        }

        // Make sure the white box is visible
        userCommentSection.style.display = 'block';
    })
    .catch(error => console.error('Error fetching comments:', error));

}