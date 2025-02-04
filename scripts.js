function sanitize(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}


document.addEventListener('DOMContentLoaded', function () {
  const submitButton = document.getElementById('submitBtn');
  const userInputField = document.getElementById('userInput');
  const photoInput = document.getElementById('photoInput');


    fetch('a_fetchPost.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }
  })
  .then(response => response.json())
  .then(data => {
          const postsContainer = document.getElementById('posts-container');
          postsContainer.innerHTML = '';

          if (data.posts.length > 0) {
            let count = 1;
        
            data.posts.forEach((post) => {
                let likeButton = post.is_liked === "liked" ? "liked" : "like";
        
                const postElement = document.createElement("div");
                postElement.classList.add("post");
        
                postElement.innerHTML = `
                    <div class="post-author">
                        <a href="profile.php?id=${encodeURIComponent(btoa(post.uid))}">
                            <img src="${post.uimage}" alt="">
                        </a>
                        <div>
                            <a href="profile.php?id=${encodeURIComponent(btoa(post.uid))}" style="text-decoration: none; color: inherit;">
                                <h1>${sanitize(post.ufname)} ${sanitize(post.ulname)}</h1>
                            </a>
                            <small>${sanitize(post.utitle)}</small>
                            <small>${sanitize(post.pcreation)}</small>
                        </div>
                    </div>
                    <p>${sanitize(post.ptext)}</p>
                    ${post.pimage ? `<img src="${post.pimage}" alt="" width="100%" />` : ""}
                    
                    <div class="post-stats">
                        <div>
                            <img src="images/thumbsup.png">
                            <img src="images/love.png">
                            <img src="images/clap.png">
                            <span class="liked-users">${post.like_count} likes</span>
                        </div>
                        <div>
                            <span>${post.comment_count} comments &middot; y shares</span>
                        </div>
                    </div>
                    
                    <div class="post-activity">
                        <div>
                            <img src="${post.uimage}" alt="" class="post-activity-user-icon">
                            <img src="images/down-arrow.png" class="post-activity-arrow-icon">
                        </div>
                        <div class="post-activity-link">
                            <button id="like" class="like" onClick="toggleImage(${post.pid})" data-postid="${post.pid}">
                                <img src="images/${likeButton}.png" id="toggleImage${post.pid}">
                                <span>Like</span>
                            </button>
                        </div>
                        <div class="post-activity-link">
                            <button id="showCommentBox${count}" class="comment-post" onClick="showComments(${count},${post.pid})" data-postid="${post.pid}">
                                <img src="images/comment.png">
                                <span>Comment</span>
                            </button>
                        </div>
                        <div class="post-activity-link">
                            <button><img src="images/share.png"><span>Share</span></button>
                        </div>
                        <div class="post-activity-link">
                            <button><img src="images/send.png"><span>Send</span></button>
                        </div>
                        
                        <div id="overlay" class="overlay" style="display: none;"></div>
                        <div id="whiteBox" class="white-box" style="display: none;">
                            <div>
                                <button id="close-Comments" class="close-Comments" onClick="toggleVisibility(false, 0, 0)"><p>X</p></button>
                            </div>
                            <div class="comments-container">
                                <p>Comments will appear here...</p>
                            </div>
                            <div>
                                <textarea id="userComment" name="userComment" rows="1" placeholder="Type a comment"></textarea>
                                <button id="sendComment${post.pid}" class="sendComment" onClick="postComment(${post.pid})"><img src="images/send.png"></button>
                            </div>
                        </div>
                    </div>
                `;
        
                postsContainer.appendChild(postElement);
                count++;
            });
        } else {
            postsContainer.innerHTML = '<div style="display:flex; align-items:center;justify-content: center;height: 70vh;"><h2>No Posts Found</h2></div>';
        }
  })
  .catch(error => console.error('Error fetching posts:', error));


  fetch('a_fetchNews.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' }
}).then(response => response.json())
  .then(data => {
      const newsContainer = document.getElementById('sidebar-news');
      newsContainer.innerHTML = ''; // Clear existing content

      const title = document.createElement('h3');
      title.textContent = "Trending News";
      newsContainer.appendChild(title);
      if (data.articles && data.articles.length > 0) {
          for (let i = 0; i < 4; i++) { // Ensure we only take up to 4 articles
              const article = data.articles[i];
              console.log(data.articles[i]);
              // Create the anchor element
              const newsItem = document.createElement("a");
              newsItem.href = article.link;
              newsItem.innerHTML = `<p>${article.title}</p>`;

              // Create the small tag for publication date and source
              const newsMeta = document.createElement("small");
              newsMeta.innerHTML = `${article.pubDate} <span>${article.source_id}</span>`;

              // Append to the container
              newsContainer.appendChild(newsItem);
              newsContainer.appendChild(newsMeta);
          }

      } else {
          newsContainer.innerHTML = `<div style="display:flex; align-items:center;justify-content: center;height: 70vh;">
              <h2>No News Found</h2>
          </div>`;
      }
  })
  .catch(error => console.error('Error fetching news:', error));


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

  function toggleImage(pid) {
    var img = document.getElementById('toggleImage' + pid);
    img.style.opacity = 0; // Fade out the image
    
    // Wait until the image fades out before switching the source
    setTimeout(function() {
      if (img.src.includes("like.png")) {
        img.src = "images/liked.png";  // Change to the "liked" image
      } else {
        img.src = "images/like.png";        }
      
      // Fade the image back in after switching the source
      img.style.opacity = 1;
    }, 150);    
    likePost(pid);
  }


function likePost(pid){
        fetch('a_likePost.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ postid: pid })
        })
        .then(response => response.json())
        .then(data => {
          console.log("clicked");
        })
        .catch(console.error);
}
 

function getComments(id, pid) {
  const button = document.getElementById('showCommentBox' + id);

  fetch('a_retrieveComments.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ commentbtn: pid }),
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
                  img.src = `${comment.uimage}`;
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
          userInput.value = "";
      })
      .catch((error) => {
        console.log("error");
      });
};


document.getElementById("description").addEventListener("input", function() {
    const submitBtn = document.getElementById("submit-btn");
    const textarea = this;
    
    // Show the submit button when the user starts typing
    if (textarea.value.trim() !== "") {
        submitBtn.style.display = "block"; // Show the button
        submitBtn.style.backgroundColor = "#0073b1"; // LinkedIn blue when there's text
        submitBtn.style.color = "#fff"; // White text
    } else {
        submitBtn.style.display = "none"; // Hide the button when the textarea is empty
    }
});

function uploadDescription(id) {
    const userInput = document.getElementById('description').value;
    const userId = id; // Assuming `id` is available from your context

    // If the description is empty, send an empty string instead
    const description = userInput.trim() === '' ? '' : userInput;

    fetch('a_uploadDescription.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ description: description, uid: userId }),
    })
        .then(response => response.text())  // Get the text response from the server
        .then(data => {
            console.log("Server response:", data);

            // Check if the description was saved successfully by the server
            if (data === "Data saved successfully!") {
                location.reload(); // Reload the page after success
            } else {
                // If not successful, display an error
                alert("Failed to update description. Please try again.");
            }
        })
        .catch(error => {
            console.log("Error:", error);
            alert("An error occurred. Please try again.");
        });
}





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

function showComments(id, pid) {
  toggleVisibility(true, id, pid);
}


// Function to show or hide the white box and overlay with transition
function toggleVisibility(show, id, pid) {
  const whiteBox = document.getElementById('whiteBox');
  const overlay = document.getElementById('overlay');
  if (show) {
    getComments(id, pid);
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

// Add click event listener **once** to close when clicking outside
document.addEventListener('click', function (event) {
  if (whiteBox.style.display === 'block' && 
      !whiteBox.contains(event.target) && 
      !event.target.classList.contains('comment-button')) { 
      toggleVisibility(false, 0);
  }
});

// Prevent clicks inside the white box from closing it
whiteBox.addEventListener('click', function (event) {
  event.stopPropagation();
});

closeComments = document.getElementById("close-Comments");
closeComments.addEventListener("click", function(){
  toggleVisibility(false, 0, 0);
});

function getCookie(name) {
  const cookie = document.cookie
      .split('; ')
      .map(cookie => cookie.split('='))
      .find(([key]) => key === name)?.[1];

  if (!cookie) return null;

  try {
    return atob(decodeURIComponent(cookie));
  } catch (error) {
    console.error("Invalid Base64 cookie:", error);
    return null;
  }
}

function sendConnectionInProfile(){
  var id = getCookie("id");
  const urlParams = new URLSearchParams(window.location.search);
  const encodedId = urlParams.get("id");
  if (encodedId) {
      const decodedId = atob(encodedId);
      fetch('a_sendConnection.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ senderId: id,
                               receiverId: decodedId }),
      })
      
      .then((response) => response.text())
      .then((data) => {
          console.log("connection sent!");
      })
      .catch((error) => {
        console.log("error");
      });
  }
  
}

