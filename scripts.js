function formatTimeAgo(dateString) {
  const date = new Date(dateString);
  const now = new Date();
  const diffMs = now - date;
  const diffSeconds = Math.floor(diffMs / 1000);
  const diffMinutes = Math.floor(diffSeconds / 60);
  const diffHours = Math.floor(diffMinutes / 60);

  if (diffSeconds < 60) {
      return `${diffSeconds} seconds ago`;
  } else if (diffMinutes < 60) {
      return `${diffMinutes} minutes ago`;
  } else if (diffHours < 24) {
      return `${diffHours} hours ago`;
  }

  const options = { month: "long", day: "numeric", hour: "2-digit", minute: "2-digit" };
  return date.toLocaleString("en-US", options).replace(",", " at");
}



document.addEventListener('DOMContentLoaded', function () {
  const submitButton = document.getElementById('submitBtn');
  const userInputField = document.getElementById('userInput');
  const photoInput = document.getElementById('photoInput');
  const pageType = document.body.getAttribute('data-page');

  if (pageType === 'home') {
    document.getElementById('photoButton').addEventListener("click", function() {
      document.getElementById('photoInput').click(); // Opens the file explorer
    });
 
    document.getElementById('photoInput').addEventListener("change", function(event) {
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
                const postElement = document.createElement("div");
                postElement.classList.add("post");
                postElement.innerHTML = `
                    <div class="post-author">
                        <a href="profile.php?id=${encodeURIComponent(btoa(post.uid))}">
                            <img src="${post.uimage}" alt="">
                        </a>
                        <div>
                            <a href="profile.php?id=${encodeURIComponent(btoa(post.uid))}" style="text-decoration: none; color: inherit;">
                                <h1>${post.ufname} ${post.ulname}</h1>
                            </a>
                            <small>${post.utitle}</small>
                            <small>${formatTimeAgo(post.pcreation)}</small>
                        </div>
                    </div>
                    <p>${post.ptext}</p>
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
                                <img src="images/${post.is_liked}.png" id="toggleImage${post.pid}">
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
                            <button><img src="images/${post.is_saved}.png"><span>Save</span></button>
                        </div>
                        
                        <div id="overlay${count}" class="overlay" style="display: none;"></div>
                        <div id="whiteBox${count}" class="white-box" style="display: none;">
                            <div>
                                <button id="close-Comments" class="close-Comments" onClick="toggleVisibility(false, ${count}, 0)"><p>X</p></button>
                            </div>
                            <div class="comments-container" id="comments-container${count}">
                                <p>Comments will appear here...</p>
                            </div>
                            <div>
                                <textarea id="userComment${count}" class="userComment" name="userComment" rows="1" placeholder="Type a comment"></textarea>
                                <button id="sendComment${post.pid}" class="sendComment" onClick="postComment(${count},${post.pid})"><img src="images/send.png"></button>
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
      newsContainer.innerHTML = '';

      const title = document.createElement('h3');
      title.textContent = "Trending News";
      newsContainer.appendChild(title);
      if (data.articles && data.articles.length > 0) {
          for (let i = 0; i < 4; i++) { 
              const article = data.articles[i];
              console.log(data.articles[i]);

              const newsItem = document.createElement("a");
              newsItem.href = article.link;
              newsItem.innerHTML = `<p>${article.title}</p>`;

              const newsMeta = document.createElement("small");
              newsMeta.innerHTML = `${article.pubDate} <span>${article.source_id}</span>`;

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
  }

  if (pageType === 'profile') {
    const urlParams = new URLSearchParams(window.location.search);
    const profileUserId = urlParams.get("id"); // Profile user ID from the URL
    const currentUserId = getCookie("id"); // Get current user ID from cookie

    if (profileUserId && currentUserId) {
        fetch(`a_getConnectionStatus.php?userId=${currentUserId}&profileId=${profileUserId}`)
            .then(response => response.json())
            .then(data => {
                const connectionStatus = data.cstatus;
                const connectButton = document.getElementById("primary-btn");
                const connectImg = connectButton.querySelector("#connect-img"); // Select the image inside the button

                // Update the button based on the connection status
                if (connectionStatus === 'pending') {
                    connectImg.src = "images/pending.png"; // Update image source to pending
                    connectButton.querySelector("span").textContent = "Pending"; // Update the text to "Pending"
                    connectButton.setAttribute('onclick', ''); // Disable click action
                } else if (connectionStatus === 'accepted') {
                    connectImg.src = "images/connected.png"; // Update image source to connected
                    connectButton.querySelector("span").textContent = "Connected"; // Update the text to "Connected"
                    connectButton.setAttribute('onclick', ''); // Disable click action
                } else {
                    connectImg.src = "images/connect.png"; // Default connect image
                    connectButton.querySelector("span").textContent = "Connect"; // Update the text to "Connect"
                    connectButton.setAttribute('onclick', 'sendConnectionInProfile()'); // Enable send connection
                }
            })
            .catch(error => console.error("Error fetching connection status:", error));
    }
}


  if(pageType === "myitems"){
    fetch('a_fetchMyItems.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }
  }).then(response => response.json())
    .then(data => {
      const postsContainer = document.getElementById('posts-container');
          postsContainer.innerHTML = '';

          if (data.posts.length > 0) {
            let count = 1;
        
            data.posts.forEach((post) => {
                const postElement = document.createElement("div");
                postElement.classList.add("post");
                postElement.innerHTML = `
                    <div class="post-author">
                        <a href="profile.php?id=${encodeURIComponent(btoa(post.uid))}">
                            <img src="${post.uimage}" alt="">
                        </a>
                        <div>
                            <a href="profile.php?id=${encodeURIComponent(btoa(post.uid))}" style="text-decoration: none; color: inherit;">
                                <h1>${post.ufname} ${post.ulname}</h1>
                            </a>
                            <small>${post.utitle}</small>
                            <small>${formatTimeAgo(post.pcreation)}</small>
                        </div>
                    </div>
                    <p>${post.ptext}</p>
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
                                <img src="images/${post.is_liked}.png" id="toggleImage${post.pid}">
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
                            <button><img src="images/${post.is_saved}.png"><span>Save</span></button>
                        </div>
                        
                        <div id="overlay${count}" class="overlay" style="display: none;"></div>
                        <div id="whiteBox${count}" class="white-box" style="display: none;">
                            <div>
                                <button id="close-Comments" class="close-Comments" onClick="toggleVisibility(false, ${count}, 0)"><p>X</p></button>
                            </div>
                            <div class="comments-container" id="comments-container${count}">
                                <p>Comments will appear here...</p>
                            </div>
                            <div>
                                <textarea id="userComment${count}" class="userComment" name="userComment" rows="1" placeholder="Type a comment"></textarea>
                                <button id="sendComment${post.pid}" class="sendComment" onClick="postComment(${count},${post.pid})"><img src="images/send.png"></button>
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
    .catch(error => console.error('Error fetching news:', error));
  }


  if(pageType === "myprofile"){
    fetch('a_fetchMyProfile.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }
  }).then(response => response.json())
    .then(data => {
        const myimage = document.getElementById('profile-image');
        const mycover = document.getElementById('cover-image');
        const mytitle = document.getElementById('currentTitle');
        const mydesc = document.getElementById('description');
        myimage.src = data.user[0].uimage;
        mycover.src = data.user[0].ucover;
        mytitle.innerHTML = data.user[0].utitle;
        mydesc.innerHTML = data.user[0].udescription;
    })
    .catch(error => console.error('Error fetching news:', error));
  }


  if(pageType === "network"){
    fetch('a_fetchNetwork.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }
  }).then(response => response.json())
    .then(data => {
        const myimage = document.getElementById('profile-image');
        const mycover = document.getElementById('cover-image');
        const mytitle = document.getElementById('currentTitle');
        const mydesc = document.getElementById('description');
        myimage.src = data.user[0].uimage;
        mycover.src = data.user[0].ucover;
        mytitle.innerHTML = data.user[0].utitle;
        mydesc.innerHTML = data.user[0].udescription;
    })
    .catch(error => console.error('Error fetching news:', error));
  }


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
          //console.log("clicked");
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
          const commentsContainer = document.getElementById('comments-container' + id);
          const userCommentSection = document.getElementById('whiteBox' + id);

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
                      <small>${formatTimeAgo(comment.ctime)}</small>
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



function postComment(count, id){
    const postComment = document.getElementById("sendComment" + id);
    console.log(id); 
    const userInput = document.getElementById('userComment' + count);
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

function showComments(id, pid) {
  toggleVisibility(true, id, pid);
}

// Function to show or hide the white box and overlay with transition
function toggleVisibility(show, id, pid) {
  const whiteBox = document.getElementById('whiteBox' + id);
  const overlay = document.getElementById('overlay' + id);
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
      toggleVisibility(false, 0, 0);
  }
});

// Prevent clicks inside the white box from closing it
whiteBox.addEventListener('click', function (event) {
  event.stopPropagation();
});

// closeComments = document.getElementById("close-Comments");
// closeComments.addEventListener("click", function(){
//   toggleVisibility(false, 0, 0);
// });

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



function sendConnectionInProfile() {
    var id = getCookie("id");
    const urlParams = new URLSearchParams(window.location.search);
    const encodedId = urlParams.get("id");
    if (encodedId) {
        const decodedId = atob(encodedId);
        fetch('a_sendConnection.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                senderId: id,
                receiverId: decodedId
            }),
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                console.log("Connection sent!");
                // Update the button or UI accordingly (e.g., change text to 'Pending')
            } else {
                if (data.error === 'Connection already exists') {
                    console.log("You have already sent a connection request to this user.");
                    // Update UI to reflect that the connection request has already been sent.
                } else {
                    console.log("An error occurred: " + data.error);
                }
            }
        })
        .catch((error) => {
            console.log("Error:", error);
        });
    }
}



// Get the elements from the DOM
// Get the elements using getElementById
function editTitle(){
    const titleInput = document.getElementById('titleInput');
    const currentTitle = document.getElementById('currentTitle');

    // Toggle input visibility
    titleInput.style.display = 'block';
    currentTitle.style.display = 'none';
    
    // Set input value to the current title text
    titleInput.value = currentTitle.textContent;
  
  document.getElementById("titleInput").addEventListener("blur", function() {
    const newTitle = this.value;
  
    // Send the updated title to the server if the title changed
    if (newTitle !== document.getElementById('currentTitle').textContent) {
      // Make the fetch request to save the title
      fetch('a_saveTitle.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          newTitle: newTitle
        }),
      })
      .then((response) => response.json())
      .then((data) => {
          setTimeout(() => {
            window.location.reload();
        }, 100);
      })
      .catch((error) => {
        console.error('Error:', error);
      });
    }
    
    // Hide the input field and show the updated title
    this.style.display = 'none';
    document.getElementById('currentTitle').style.display = 'block';
  });
}



function editImage(type) {
    let imageInput;

    if (type === 'profile') {
        imageInput = document.getElementById("profile-image-input");
    } else if (type === 'cover') {
        imageInput = document.getElementById("cover-image-input");
    }

    imageInput.click();

    imageInput.addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const newImage = e.target.result;
                if (type === 'profile') {
                    document.getElementById("profile-image").src = newImage;
                } else if (type === 'cover') {
                    document.getElementById("cover-image2").src = newImage;
                }

                fetch('a_saveImage.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        newImage: newImage,
                        type: type
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);

                    // Reload the page after the image is set
                    location.reload();  // This will reload the page
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            };

            reader.readAsDataURL(file);
        }

    });
}






function viewFullImage() {
    const profileImage = document.getElementById("profile-image");
    const imageModal = document.getElementById("imageModal");
    const fullImage = document.getElementById("fullImage");
    const closeModal = document.getElementById("closeModal");

    // When the profile image is clicked, open the modal and display the full image
    profileImage.addEventListener("click", function() {
        // Set the full image source to the profile image source
        fullImage.src = profileImage.src;
        imageModal.style.display = "block";  // Show the modal
    });

    // When the close button (X) is clicked, close the modal
    closeModal.addEventListener("click", function() {
        imageModal.style.display = "none";  // Hide the modal
    });

    // When the user clicks anywhere outside of the modal, close it
    window.addEventListener("click", function(event) {
        if (event.target === imageModal) {
            imageModal.style.display = "none";  // Close the modal if clicked outside
        }
    });
}

function toggleMenu() {
    const menu = document.getElementById("camera-menu");

    // Toggle the "show" class to make the menu slide out
    menu.classList.toggle("show");
}



document.addEventListener("DOMContentLoaded", function() {
    const navLinks = document.querySelectorAll(".navbar-center ul li a");

    // Initially, ensure the first link has the 'active-link' class (if not set)
    if (!document.querySelector(".navbar-center ul li a.active-link")) {
        navLinks[0].classList.add("active-link");
    }

    // Add click event to each link
    navLinks.forEach(link => {
        link.addEventListener("click", function() {
            // Remove 'active-link' class from all links
            navLinks.forEach(link => {
                link.classList.remove("active-link");
                link.querySelector('::after').style.width = "0"; // Reset underline width to 0 for all items
            });

            // Add 'active-link' class to the clicked link
            this.classList.add("active-link");
        });
    });
});
