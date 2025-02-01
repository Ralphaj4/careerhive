let profileMenu = document.getElementById("profileMenu");

function toggleMenu(){
    profileMenu.classList.toggle("open-menu");
}


document.addEventListener('DOMContentLoaded', function () {
  const submitButton = document.getElementById('submitBtn');
  const userInputField = document.getElementById('userInput');
  const photoInput = document.getElementById('photoInput');

  submitButton.addEventListener('click', function (event) {
      event.preventDefault(); // Prevent default form submission

      const userInput = userInputField.value;
      const file = photoInput.files[0]; // Get selected image file
      const formData = new FormData();

      formData.append('userInput', userInput);
      if(file) {
          formData.append('photoInput', file);
      }

      fetch('a_uploadPost.php', {
          method: 'POST',
          body: formData, // Send FormData with text and image
      })
      .then((response) => response.text())
      .then((data) => {
          console.log("Upload successful:", data);
          setTimeout(() => {
              window.location.reload(); // Reload the page
          }, 100);
      })
      .catch((error) => {
          console.error("Error:", error);
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

        fetch('a_likePost.php', {
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

const whiteBox = document.getElementById('whiteBox');
const overlay = document.getElementById('overlay');

// function showComments(id){
//     // Get the white box and overlay element
    
//     getComments(id);
//     // Function to show or hide the white box and overlay with transition
//     const toggleVisibility = () => {
//         if (whiteBox.style.display === 'none' || whiteBox.style.display === '') {
//             // Show the white box and overlay with transition
//             whiteBox.style.display = 'block'; // Make the box visible
//             overlay.style.display = 'block';  // Make the overlay visible
  
//             // Trigger the transition by setting opacity and transform
//             setTimeout(() => {
//                 whiteBox.style.opacity = '1';
//                 whiteBox.style.transform = 'translateX(-50%) translateY(0)'; // Slide into position
//                 overlay.style.opacity = '1'; // Fade in the overlay
//             }, 10); // Delay to allow the box to be shown first
//         } else {
//             // Hide the white box and overlay with transition
//             whiteBox.style.opacity = '0';
//             whiteBox.style.transform = 'translateX(-50%) translateY(-30px)'; // Slide out of view
//             overlay.style.opacity = '0'; // Fade out the overlay
  
//             // After the transition ends, hide the box and overlay
//             setTimeout(() => {
//                 whiteBox.style.display = 'none'; // Hide the white box
//                 overlay.style.display = 'none';  // Hide the overlay
//             }, 300); // Match the duration of the transition
//         }
//     };
  
//     // Add click event listener to toggle visibility of the white box and overlay
//     document.addEventListener('click', function(event) {
//         // Check if the click was outside the white box and overlay
//         if (!whiteBox.contains(event.target) && !overlay.contains(event.target)) {
//             toggleVisibility();
//         }
//     });
  
//     // Prevent the white box from hiding when clicking inside it
//     whiteBox.addEventListener('click', function(event) {
//         event.stopPropagation(); // Prevent the click event from bubbling up to the document
//     });
//   }
  

  function getComments(id) {
    const button = document.getElementById('showCommentBox' + id);
    const postId = button.dataset.postid;

    fetch('a_retrieveComments.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ commentbtn: postId }),
    })
        .then(response => response.json())
        .then(data => {
            const comments = data.comments;
            const commentsContainer = document.querySelector('.comments-container');
            const userCommentSection = document.getElementById('whiteBox');

            // Clear the container before rendering new comments
            commentsContainer.innerHTML = '';

            if (Array.isArray(comments) && comments.length > 0) {
                comments.forEach(comment => {
                    // Create the comment element
                    const commentDiv = document.createElement('div');
                    commentDiv.classList.add('comment');
            
                    // Create the comment header (image, name, and time)
                    const commentHeader = document.createElement('div');
                    commentHeader.classList.add('comment-header');
            
                    // Create the image element
                    const img = document.createElement('img');
                    img.src = `data:image/jpeg;base64,${comment.uimage}`;
                    img.alt = 'User Image';
            
                    // Create the div for name and time
                    const nameTimeDiv = document.createElement('div');
                    nameTimeDiv.innerHTML = `
                        <strong>${comment.ufname} ${comment.ulname}</strong>
                        <small>${new Date(comment.ctime).toLocaleString()}</small>
                    `;
            
                    // Append image and name/time to the header
                    commentHeader.appendChild(img);
                    commentHeader.appendChild(nameTimeDiv);
            
                    // Create the paragraph for comment text
                    const commentText = document.createElement('p');
                    commentText.textContent = comment.text;
            
                    // Append the header and comment text to the comment div
                    commentDiv.appendChild(commentHeader);
                    commentDiv.appendChild(commentText);
            
                    // Append the comment to the container
                    commentsContainer.appendChild(commentDiv);
                });
            }
             else {
                // Display a message if no comments are available
                commentsContainer.innerHTML = '<p>No comments yet.</p>';
            }

            // Ensure the comments section is visible
            userCommentSection.style.display = 'block';
        })
        .catch(error => console.error('Error fetching comments:', error));
}


// document.getElementById('close-Comments').onclick = function() {
//     document.getElementById('whiteBox').style.display = 'none';
//     document.getElementById('overlay').style.display = 'none';
//   };



function postComment(id){
    const postComment = document.getElementById("sendComment" + id);
    console.log(id); 
    const userInput = document.getElementById('userComment');
    const userComment = userInput.value
    fetch('a_postComment.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ userComment: userComment,
                             postId: id
       }),
    })
    
      .then((response) => response.text())
      .then((data) => {
          console.log("done");
      })
      .catch((error) => {
        console.log("error");
      });
};


document.getElementById("photoButton").addEventListener("click", function() {
  document.getElementById("photoInput").click(); // Opens the file explorer
});

document.getElementById("photoInput").addEventListener("change", function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const mediaPreview = document.getElementById("mediaPreview");
            mediaPreview.innerHTML = "";
            const imgPreview = document.createElement("img");
            imgPreview.src = e.target.result;
            imgPreview.style.borderRadius = 0;
            imgPreview.style.maxWidth = "150px"; // Adjust size
            mediaPreview.appendChild(imgPreview);
        };
        reader.readAsDataURL(file);
    }
});



// Function to show or hide the white box and overlay with transition
function toggleVisibility(show, id) {
  if (show) {
    getComments(id)
      whiteBox.style.display = 'block';
      overlay.style.display = 'block';
      setTimeout(() => {
          whiteBox.style.opacity = '1';
          whiteBox.style.transform = 'translateX(-50%) translateY(0)';
          overlay.style.opacity = '1';
      }, 0);
  } else {
      whiteBox.style.opacity = '0';
      whiteBox.style.transform = 'translateX(-50%) translateY(-30px)';
      overlay.style.opacity = '0';
      setTimeout(() => {
          whiteBox.style.display = 'none';
          overlay.style.display = 'none';
      }, 0);
  }
}

// Function to show comments
function showComments(id) {
  toggleVisibility(true, id);
}

// Add click event listener **once** to close when clicking outside
document.addEventListener('click', function (event) {
  if (whiteBox.style.display === 'block' && 
      !whiteBox.contains(event.target) && 
      !event.target.classList.contains('comment-button')) { 
      toggleVisibility(false);
  }
});

// Prevent clicks inside the white box from closing it
whiteBox.addEventListener('click', function (event) {
  event.stopPropagation();
});

// Close button functionality
document.getElementById('close-Comments').onclick = function () {
  toggleVisibility(false);
};


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
    //code before the pause
    
    window.location.href = `search.php`;
    
})
  .catch(console.error);
});