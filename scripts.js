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

function formatDateRange(start, end) {
    let startYear = start.split('-')[0];
    let endYear = end === null ? "Present" : end.split('-')[0];
    return `${startYear} - ${endYear}`;
}

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
                            <span>${post.comment_count} comments &middot; ${post.saved_count} saves</span>
                        </div>
                    </div>
                    
                    <div class="post-activity">
                        <div>
                            <img src="${post.uimage}" alt="" class="post-activity-user-icon">
                            <img src="images/down-arrow.png" class="post-activity-arrow-icon">
                        </div>
                        <div class="post-activity-link">
                            <button id="like" class="like" onClick="toggleImage(0, ${post.pid})" data-postid="${post.pid}">
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
                            <button onClick=toggleImage(1,${post.pid})><img src="images/${post.is_saved}.png" id="togglesave${post.pid}"><span>Save</span></button>
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

  if (pageType === "profile") {
    const urlParams = new URLSearchParams(window.location.search);
    const profileUserId = atob(urlParams.get("id")); // Profile user ID from the URL
    const rejectButton = document.getElementById("reject");

    if (profileUserId) {
        fetch(`a_getConnectionStatus.php?profileId=${profileUserId}`)
            .then(response => response.json())
            .then(data => {
                const connectionStatus = data.cstatus;
                const connectButton = document.getElementById("primary-btn");
                const rejectButton = document.getElementById("reject");
                const connectImg = connectButton.querySelector("#connect-img"); // Select the image inside the button

                // Update the button based on the connection status
                if (connectionStatus === 'pending') {
                    connectImg.src = "images/pending.png"; // Update image source to pending
                    connectButton.querySelector("span").textContent = "Pending"; // Update the text to "Pending"
                    connectButton.setAttribute('onclick', 'sendConnectionInProfile(0)'); // Disable click action
                } else if (connectionStatus === 'accepted') {
                    connectImg.src = "images/connected.png"; // Update image source to connected
                    connectButton.querySelector("span").textContent = "Connected"; // Update the text to "Connected"
                    connectButton.setAttribute('onclick', 'sendConnectionInProfile(0)'); // Disable click action
                }else if (connectionStatus === 'accept') {
                    connectImg.src = "images/connected.png"; // Update image source to connected
                    connectButton.querySelector("span").textContent = "Accept"; // Update the text to "Connected"
                    connectButton.setAttribute('onclick', 'sendConnectionInProfile(2)'); // Disable click action
                    rejectButton.style.display = "inline-flex";
                    rejectButton.querySelector("span").textContent = "Reject";
                } else {
                    connectImg.src = "images/connect.png"; // Default connect image
                    connectButton.querySelector("span").textContent = "Connect"; // Update the text to "Connect"
                    connectButton.setAttribute('onclick', 'sendConnectionInProfile(1)'); // Enable send connection
                }
            })
            .catch(error => console.error("Error fetching connection status:", error));
            fetch('a_fetchProfile.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({id: profileUserId})
            }).then(response => response.json())
            .then(data => {
                const educationContainer = document.querySelector(".profile-education-container");
                educationContainer.innerHTML = `<h2>Education</h2>`;
                const experienceContainer = document.querySelector(".profile-experience-container");
                experienceContainer.innerHTML = `<h2>Experience</h2>`;
                const skillsContainer = document.querySelector(".profile-skills-container");
                skillsContainer.innerHTML = `<h2>Skills</h2>`;
                const languagesContainer = document.querySelector(".profile-language-container");
                languagesContainer.innerHTML = `<h2>Languages</h2>`;
                const postsContainer = document.querySelector(".profile-post-container");
                postsContainer.innerHTML = `<h2>Posts</h2>`;

                // Display Education
                if (!data.education || data.education.length === 0) {
                    // Show message if there is no education data
                    educationContainer.innerHTML += `<p>Nothing to display.</p>`;
                } else {
                    data.education.forEach(edu => {
                        const educationDiv = document.createElement("div");
                        educationDiv.classList.add("profile-education");
            
                        educationDiv.innerHTML = `
                            <img src="${edu.iimage}" alt="University Logo">
                            <div>
                                <h3>${edu.iname}</h3>
                                <b>${edu.mtype}, ${edu.mname}</b>
                                <b>${formatDateRange(edu.mstart, edu.mend)}</b>
                                <hr>
                            </div>
                        `;
                        educationContainer.appendChild(educationDiv);
                    });
                }
                if(!data.experience || data.experience.length === 0){
                    experienceContainer.innerHTML += `<p>Nothing to display.</p>`
                }
                else{
                    data.experience.forEach(exp => {
                    const experienceDiv = document.createElement("div");
                    experienceDiv.classList.add("profile-experience");

                    experienceDiv.innerHTML = `
                        <img src="${exp.iimage}" alt="Company Logo">
                        <div>
                            <h3>${exp.title}</h3>
                            <b>${exp.iname}</b>
                            <b>${formatDateRange(exp.wstarted, exp.wended)}</b>
                            <hr>
                        </div>
                    `;
                    experienceContainer.appendChild(experienceDiv);
                    });
                }
                if(!data.skills || data.skills.length=== 0){
                    skillsContainer.innerHTML += `<p>Nothing to display.</p>`;
                }else{
                    data.skills.forEach(skill => {
                        const skillp = document.createElement("p");
                        skillp.classList.add("skill");
            
                        skillp.innerHTML = `${skill.skillname}`;
                        skillsContainer.appendChild(skillp);
                    });
                }

                if(!data.languages || data.languages.length === 0){
                    languagesContainer.innerHTML += `<p>Nothing to display.</p>`;
                }else{
                    data.languages.forEach(language => {
                        const experiencep = document.createElement("p");
                        experiencep.classList.add("language");
            
                        experiencep.innerHTML = `${language.lname}`;
                        languagesContainer.appendChild(experiencep);
                    });
                }
                if(!data.posts || data.posts.length === 0){
                    postsContainer.innerHTML += `<p>No Posts</p>`;
                }else{
                    // ADD MY POSTS
                    

                    if (data.posts.length > 0) {
                        let count = 1;
                    
                        data.posts.forEach((post) => {
                            const postElement = document.createElement("div");
                            postElement.classList.add("post");
                            postElement.innerHTML = `
                            <div class="post-header">
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
                                        <span>${post.comment_count} comments &middot; ${post.saved_count} saves</span>
                                    </div>
                                </div>
                                
                                <div class="post-activity">
                                    <div>
                                        <img src="${post.uimage}" alt="" class="post-activity-user-icon">
                                        <img src="images/down-arrow.png" class="post-activity-arrow-icon">
                                    </div>
                                    <div class="post-activity-link">
                                        <button id="like" class="like" onClick="toggleImage(0, ${post.pid})" data-postid="${post.pid}">
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
                                        <button onClick=toggleImage(1,${post.pid})><img src="images/${post.is_saved}.png" id="togglesave${post.pid}"><span>Save</span></button>
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
                }
            })
            ;
            fetch('a_fetchNews.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            }).then(response => response.json())
              .then(data => {
                console.log('fetched' + data);
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
                            <span>${post.comment_count} comments &middot; ${post.saved_count} saves</span>
                        </div>
                    </div>
                    
                    <div class="post-activity">
                        <div>
                            <img src="${post.uimage}" alt="" class="post-activity-user-icon">
                            <img src="images/down-arrow.png" class="post-activity-arrow-icon">
                        </div>
                        <div class="post-activity-link">
                            <button id="like" class="like" onClick="toggleImage(0, ${post.pid})" data-postid="${post.pid}">
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
                            <button onClick="toggleImage(1, ${post.pid})"><img src="images/${post.is_saved}.png" id="togglesave${post.pid}"><span>Save</span></button>
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

  if(pageType === "jobs"){
    fetch('a_fetchJobs.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
  }

  if (pageType === "navbarInst") {
    let iid = getCookie("id");

    fetch('a_fetchMyPage.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: iid })
    })
    .then(response => response.json())
    .then(data => {
        const myname = document.getElementById('name');
        myname.innerHTML = data.institution[0].iname;
})}

  if (pageType === "mypage") {
    let iid = getCookie("id");

    document.getElementById('submitBtn').addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default form submission

        const jobTitle = document.getElementById("jobtitle").value;
        const jobDescription = document.getElementById("jobdesc").value;
        fetch('a_uploadJob.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ title: jobTitle, desc: jobDescription })
        }).then((response) => response.text())
        .then((data) => {
            setTimeout(() => {
                window.location.reload(); // Reload the page
            }, 100);
        });
    });

    fetch('a_fetchMyPage.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: iid })
    })
    .then(response => response.json())
    .then(data => {
        const myimage = document.getElementById('profile-image');
        const myimagepost = document.getElementById('profile-image-post');
        const mycover = document.getElementById('cover-image');
        const mytitle = document.getElementById('currentTitle');
        const mydesc = document.getElementById('description');
        const myname = document.querySelector('.profile-name');
        myname.textContent = data.institution[0].iname;

        // Set profile details
        myimage.src = data.institution[0].iimage;
        myimagepost.src = data.institution[0].iimage;
        mycover.src = data.institution[0].icover;
        mytitle.innerHTML = String(data.institution[0].itype).charAt(0).toUpperCase() + String(data.institution[0].itype).slice(1);
        mydesc.innerHTML = data.institution[0].udescription;
        //myname.innerHTML = data.institution[0].iname;

       
        // ADD MY POSTS
        const postsContainer = document.querySelector(".profile-post-container");
        postsContainer.innerHTML = `<h2 style="margin-top: 20px;">Posts</h2>`;


        if (data.posts.length > 0) {
            let count = 1;
        
            data.posts.forEach((post) => {
                const postElement = document.createElement("div");
                postElement.classList.add("post");
                postElement.innerHTML = `
                <div class="post-header">
                    <div class="post-author">
                        <a href="profile.php?id=${encodeURIComponent(btoa(post.iid))}">
                            <img src="${post.uimage}" alt="">
                        </a>
                        <div>
                            <a href="profile.php?id=${encodeURIComponent(btoa(post.iid))}" style="text-decoration: none; color: inherit;">
                                <h1>${post.iname}</h1>
                            </a>
                            <small>${post.itype}</small>
                            <small>${formatTimeAgo(post.pcreation)}</small>
                        </div>
                    </div>
                    <button class="delete-post" data-postid="${post.pid}">
                        <img src="images/delete.png" alt="Delete">
                    </button>
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
                            <span>${post.comment_count} comments &middot; ${post.saved_count} saves</span>
                        </div>
                    </div>
                    
                    <div class="post-activity">
                        <div>
                            <img src="${post.iimage}" alt="" class="post-activity-user-icon">
                            <img src="images/down-arrow.png" class="post-activity-arrow-icon">
                        </div>
                        <div class="post-activity-link">
                            <button id="like" class="like" onClick="toggleImage(0, ${post.pid})" data-postid="${post.pid}">
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
                            <button onClick=toggleImage(1,${post.pid})><img src="images/${post.is_saved}.png" id="togglesave${post.pid}"><span>Save</span></button>
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

            // Add event listener for deleting posts
            document.querySelectorAll(".delete-post").forEach((btn) => {
                btn.addEventListener("click", function () {
                    const postId = this.getAttribute("data-postid");
                    console.log(postId);
                    fetch('a_deletePost.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({postId: postId})     
                    })
                });
            });
        } else {
            postsContainer.innerHTML = '<div style="display:flex; align-items:center;justify-content: center;height: 70vh;"><h2>No Posts Found</h2></div>';
        }
        

    })
    .catch(error => console.error('Error fetching profile:', error));

    // document.body.addEventListener("click", (event) => {
    //     if (event.target.id === "submit-language") {
    //         const language = document.getElementById('language-name');
    //         if (language.value) {
    //             let lang = language.value;

    //             fetch('a_uploadLanguage.php', {
    //                 method: 'POST',
    //                 headers: { 'Content-Type': 'application/json' },
    //                 body: JSON.stringify({language: lang})
    //             });
    //             const newLanguage = {
    //                 lname: lang
    //             };
    //             const langContainer = document.querySelector(".profile-language-container");
    //             const popupContainerLang = document.querySelector(".language-popup-container");
    //             const langp = document.createElement("p");
    //             langp.classList.add("language");
    //             langp.textContent = newLanguage.lname;
    //             langContainer.appendChild(langp);
    //             popupContainerLang.style.display = "none";
    //         }
    //     }
    // });

    // document.body.addEventListener("click", (event) => {
    //     if (event.target.id === "submit-skill") {
    //         const skill = document.getElementById('skill-name');
    //         if (skill.value) {
    //             let sk = skill.value;
    //             fetch('a_uploadSkill.php', {
    //                 method: 'POST',
    //                 headers: { 'Content-Type': 'application/json' },
    //                 body: JSON.stringify({ type: 1, skill: sk})
    //             });

    //             const newEducation = {
    //                 skillname: sk
    //             };
    //             const skillsContainer = document.querySelector(".profile-skills-container");
    //             const popupContainerSkill = document.querySelector(".skill-popup-container");
    //             const skillp = document.createElement("p");
    //             skillp.classList.add("skill");
    //             skillp.textContent = newEducation.skillname;
    //             skillsContainer.appendChild(skillp);
    //             popupContainerSkill.style.display = "none";
    //     }
    //     }

    //     // Handle skill deletion
    //     if (event.target.classList.contains("delete-skill")) {
    //         const skillElement = event.target.closest(".skill");
    //         const skillName = skillElement.dataset.skillname;
    //         //console.log(skillName);
    //         fetch("a_uploadSkill.php", {
    //             method: "POST",
    //             headers: { "Content-Type": "application/json" },
    //             body: JSON.stringify({ type: 0, skill: skillName })
    //         });

    //         skillElement.remove();
    //     }

    //     // Handle language deletion
    //     if (event.target.classList.contains("delete-language")) {
    //         const langElement = event.target.closest(".language");
    //         const lname = langElement.dataset.lname;
    //         //console.log(skillName);
    //         fetch("a_uploadLanguage.php", {
    //             method: "POST",
    //             headers: { "Content-Type": "application/json" },
    //             body: JSON.stringify({ type: 0, language: lname })
    //         });

    //         langElement.remove();
    //     }
    // });
    
    
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

  if (pageType === "myprofile") {
    let uid = getCookie("id");

    fetch('a_fetchProfile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: uid })
    })
    .then(response => response.json())
    .then(data => {
        const myimage = document.getElementById('profile-image');
        const mycover = document.getElementById('cover-image');
        const mytitle = document.getElementById('currentTitle');
        const mydesc = document.getElementById('description');

        // Set profile details
        myimage.src = data.user[0].uimage;
        mycover.src = data.user[0].ucover;
        mytitle.innerHTML = data.user[0].utitle;
        mydesc.innerHTML = data.user[0].udescription;

        // Display Education
        const educationContainer = document.querySelector(".profile-education-container");
        educationContainer.innerHTML = `<h2>Education <button class="add-education-btn" id="add-education-btn">+</button></h2>`;

        data.education.forEach(edu => {
            const educationDiv = document.createElement("div");
            educationDiv.classList.add("profile-education");

            educationDiv.innerHTML = `
                <img src="${edu.iimage}" alt="University Logo">
                <div>
                    <h3>${edu.iname}</h3>
                    <b>${edu.mtype}, ${edu.mname}</b>
                    <b>${formatDateRange(edu.mstart, edu.mend)}</b>
                    <hr>
                </div>
            `;
            educationContainer.appendChild(educationDiv);
        });

        const addEducationBtn = document.getElementById("add-education-btn");
        const popupContainer = document.createElement("div");
        popupContainer.classList.add("education-popup-container");
        popupContainer.innerHTML = `
            <div class="education-popup">
                <h3>Add Education</h3>
                <label>School Name: <input type="text" id="school-name" list="school-list"></label>
                <datalist id="school-list"></datalist>
                <div class="year-container">
                    <label>Start Year: <input type="number" id="start-year" min="1900" max="2099"></label>
                    <label>End Year: <input type="number" id="end-year" min="1900" max="2099"></label>
                </div>
                <label>Major: <input type="text" id="major-name" list="major-list"></label>
                <datalist id="major-list"></datalist>
                <label>Major Type:
                    <select id="major-type">
                        <option value="Bachelor">Bachelor</option>
                        <option value="Master">Master</option>
                        <option value="PhD">PhD</option>
                    </select>
                </label>
                <button id="submit-education">Submit</button>
                <button id="close-popup">Cancel</button>
            </div>
        `;
        document.body.appendChild(popupContainer);

            // Fetch school data from PHP file
            fetch("a_fetchSchools&Majors.php", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
            })
            .then(response => response.json())
            .then(data => {
                const schoolList = document.getElementById("school-list");
                const majorList = document.getElementById("major-list");

                data.schools.forEach(school => {
                    const option = document.createElement("option");
                    option.value = school.iname;
                    schoolList.appendChild(option);
                });

                data.majors.forEach(major => {
                    const option = document.createElement("option");
                    option.value = major.mname;
                    majorList.appendChild(option);
                });
            })
            .catch(error => console.error("Error fetching schools:", error));

        addEducationBtn.addEventListener("click", () => {
            popupContainer.style.display = "flex";
        });

        document.getElementById("close-popup").addEventListener("click", () => {
            popupContainer.style.display = "none";
            document.getElementById("school-name").value = "";
            document.getElementById("start-year").value = "";
            document.getElementById("end-year").value = "";
            document.getElementById("major-name").value = "";
        });

        document.getElementById("submit-education").addEventListener("click", () => {
            const school = document.getElementById("school-name").value;
            const start = document.getElementById("start-year").value;
            const end = document.getElementById("end-year").value;
            const major = document.getElementById("major-name").value;
            const type = document.getElementById("major-type").value;

            if (school && start && end && major) {

                fetch("a_uploadMajor.php", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        school: school,
                        start: start,
                        end: end,
                        major: major,
                        type: type
                    })
                });

                const newEducation = {
                    iname: school,
                    mtype: type,
                    mname: major,
                    mstart: start,
                    mend: end
                };
                data.education.push(newEducation);

                const educationDiv = document.createElement("div");
                educationDiv.classList.add("profile-education");
                educationDiv.innerHTML = `
                    <img src="images/university.png" alt="University Logo">
                    <div>
                        <h3>${newEducation.iname}</h3>
                        <b>${newEducation.mtype}, ${newEducation.mname}</b>
                        <b>${formatDateRange(newEducation.mstart, newEducation.mend)}</b>
                        <hr>
                    </div>
                `;
                educationContainer.appendChild(educationDiv);
                popupContainer.style.display = "none";
            }
        });






        // Display Experience
        const experienceContainer = document.querySelector(".profile-experience-container");
        experienceContainer.innerHTML = `<h2>Experience <button class="add-experience-btn" id="add-experience-btn">+</button></h2>`;

        data.experience.forEach(exp => {
            const experienceDiv = document.createElement("div");
            experienceDiv.classList.add("profile-experience");

            experienceDiv.innerHTML = `
                <img src="${exp.iimage}" alt="Company Logo">
                <div>
                    <h3>${exp.title}</h3>
                    <b>${exp.iname}</b>
                    <b>${formatDateRange(exp.wstarted, exp.wended)}</b>
                    <hr>
                </div>
            `;
            experienceContainer.appendChild(experienceDiv);
        });
        const addExperienceBtn = document.getElementById("add-experience-btn");
        const popupContainerExp = document.createElement("div");
        popupContainerExp.classList.add("experience-popup-container");
        popupContainerExp.innerHTML = `
            <div class="experience-popup">
                <h3>Add Experience</h3>
                <label>Company Name: <input type="text" id="experience-name" list="experience-list"></label>
                <datalist id="experience-list"></datalist>
                <label>Job Title <input type="text" id="job-title"></label>
                <div class="year-container">
                    <label>Start Year: <input type="number" id="start-year" min="1900" max="2099"></label>
                    <label>End Year: <input type="number" id="end-year" min="1900" max="2099"></label>
                </div>
                <button id="submit-experience">Submit</button>
                <button id="close-popupExp">Cancel</button>
            </div>
        `;
        document.body.appendChild(popupContainerExp);

            // Fetch school data from PHP file
            fetch("a_fetchInstitutions.php", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
            })
            .then(response => response.json())
            .then(data => {
                //console.log(data);
                const ExperienceList = document.getElementById("experience-list");
                data.companies.forEach(company => {
                    const option = document.createElement("option");
                    option.value = company.iname;
                    ExperienceList.appendChild(option);
                });
            })
            .catch(error => console.error("Error fetching companies:", error));

            addExperienceBtn.addEventListener("click", () => {
                popupContainerExp.style.display = "flex";
        });

        document.getElementById("close-popupExp").addEventListener("click", () => {
            popupContainerExp.style.display = "none";
        });

        // document.getElementById("submit-experience").addEventListener("click", () => {
        //     const school = document.getElementById("school-name").value;
        //     const start = document.getElementById("start-year").value;
        //     const end = document.getElementById("end-year").value;
        //     const major = document.getElementById("major-name").value;
        //     const type = document.getElementById("major-type").value;

        //     if (school && start && end && major) {
        //         const newEducation = {
        //             iname: school,
        //             mtype: type,
        //             mname: major,
        //             mstart: start,
        //             mend: end
        //         };
        //         data.education.push(newEducation);

        //         const educationDiv = document.createElement("div");
        //         educationDiv.classList.add("profile-education");
        //         educationDiv.innerHTML = `
        //             <img src="images/university.png" alt="University Logo">
        //             <div>
        //                 <h3>${newEducation.iname}</h3>
        //                 <b>${newEducation.mtype}, ${newEducation.mname}</b>
        //                 <b>${formatDateRange(newEducation.mstart, newEducation.mend)}</b>
        //                 <hr>
        //             </div>
        //         `;
        //         educationContainer.appendChild(educationDiv);
        //         popupContainer.style.display = "none";
        //     }
        // });





        
        // Add "Show All Experiences" Link
        const showAllLink = document.createElement("a");
        showAllLink.href = "#";
        showAllLink.classList.add("experience-link");
        showAllLink.innerHTML = `Show all (${data.experience.length}) experiences <img src="images/right-arrow.png">`;
        experienceContainer.appendChild(showAllLink);

        // Display Skills
        const skillsContainer = document.querySelector(".profile-skills-container");
        skillsContainer.innerHTML = `<h2>Skills <button class="add-skill-btn" id="add-skill-btn">+</button></h2>`;

        data.skills.forEach(skill => {
            const skillp = document.createElement("p");
            skillp.classList.add("skill");
            skillp.dataset.skillname = skill.skillname;
            skillp.innerHTML = `${skill.skillname}<button class="delete-skill">X</button>`;
            skillsContainer.appendChild(skillp);
        });
        const addSkillBtn = document.getElementById("add-skill-btn");
        const popupContainerSkill = document.createElement("div");
        popupContainerSkill.classList.add("skill-popup-container");
        popupContainerSkill.innerHTML = `
            <div class="skill-popup">
                <h3>Add Skill</h3>
                <label>Skill: <input type="text" id="skill-name" list="skill-list"></label>
                <datalist id="skill-list"></datalist>
                <button id="submit-skill">Submit</button>
                <button id="close-popupSkill">Cancel</button>
            </div>
        `;
        document.body.appendChild(popupContainerSkill);

            // Fetch school data from PHP file
            fetch("a_fetchSkills.php", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
            })
            .then(response => response.json())
            .then(data => {
                const skillList = document.getElementById("skill-list");
                data.skills.forEach(skill => {
                    const option = document.createElement("option");
                    option.value = skill.skillname;
                    skillList.appendChild(option);
                });
            })
            .catch(error => console.error("Error fetching skills:", error));

            addSkillBtn.addEventListener("click", () => {
                popupContainerSkill.style.display = "flex";
        });

        document.getElementById("close-popupSkill").addEventListener("click", () => {
            popupContainerSkill.style.display = "none";
            document.getElementById("skill-name").value = "";
        });

        // Display Languages
        const languagesContainer = document.querySelector(".profile-language-container");
        languagesContainer.innerHTML = `<h2>Languages <button class="add-language-btn" id="add-language-btn">+</button></h2>`;

        data.languages.forEach(language => {
            const languagep = document.createElement("p");
            languagep.classList.add("language");
            languagep.dataset.lname = language.lname;
            languagep.innerHTML = `${language.lname}<button class="delete-language">X</button>`;
            languagesContainer.appendChild(languagep);
        });
        const addLanguageBtn = document.getElementById("add-language-btn");
        const popupContainerLang = document.createElement("div");
        popupContainerLang.classList.add("language-popup-container");
        popupContainerLang.innerHTML = `
            <div class="language-popup">
                <h3>Add Language</h3>
                <label>Language: <input type="text" id="language-name" list="language-list"></label>
                <datalist id="language-list"></datalist>
                <button id="submit-language">Submit</button>
                <button id="close-popupLang">Cancel</button>
            </div>
        `;
        document.body.appendChild(popupContainerLang);

            // Fetch school data from PHP file
            fetch("a_fetchLanguages.php", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
            })
            .then(response => response.json())
            .then(data => {
                const languageList = document.getElementById("language-list");
                data.languages.forEach(language => {
                    const option = document.createElement("option");
                    option.value = language.lname;
                    languageList.appendChild(option);
                });
            })
            .catch(error => console.error("Error fetching companies:", error));

            addLanguageBtn.addEventListener("click", () => {
                popupContainerLang.style.display = "flex";
        });

        document.getElementById("close-popupLang").addEventListener("click", () => {
            popupContainerLang.style.display = "none";
            document.getElementById("language-name").value = "";
        });
        // ADD MY POSTS
        const postsContainer = document.querySelector(".profile-post-container");
        postsContainer.innerHTML = `<h2>Posts</h2>`;

        if (data.posts.length > 0) {
            let count = 1;
        
            data.posts.forEach((post) => {
                const postElement = document.createElement("div");
                postElement.classList.add("post");
                postElement.innerHTML = `
                <div class="post-header">
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
                    <button class="delete-post" data-postid="${post.pid}">
                        <img src="images/delete.png" alt="Delete">
                    </button>
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
                            <span>${post.comment_count} comments &middot; ${post.saved_count} saves</span>
                        </div>
                    </div>
                    
                    <div class="post-activity">
                        <div>
                            <img src="${post.uimage}" alt="" class="post-activity-user-icon">
                            <img src="images/down-arrow.png" class="post-activity-arrow-icon">
                        </div>
                        <div class="post-activity-link">
                            <button id="like" class="like" onClick="toggleImage(0, ${post.pid})" data-postid="${post.pid}">
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
                            <button onClick=toggleImage(1,${post.pid})><img src="images/${post.is_saved}.png" id="togglesave${post.pid}"><span>Save</span></button>
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

            // Add event listener for deleting posts
            document.querySelectorAll(".delete-post").forEach((btn) => {
                btn.addEventListener("click", function () {
                    const postId = this.getAttribute("data-postid");
                    console.log(postId);
                    fetch('a_deletePost.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({postId: postId})     
                    })
                });
            });
        } else {
            postsContainer.innerHTML = '<div style="display:flex; align-items:center;justify-content: center;height: 70vh;"><h2>No Posts Found</h2></div>';
        }
        

    })
    .catch(error => console.error('Error fetching profile:', error));

    document.body.addEventListener("click", (event) => {
        if (event.target.id === "submit-language") {
            const language = document.getElementById('language-name');
            if (language.value) {
                let lang = language.value;

                fetch('a_uploadLanguage.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({language: lang})
                });
                const newLanguage = {
                    lname: lang
                };
                const langContainer = document.querySelector(".profile-language-container");
                const popupContainerLang = document.querySelector(".language-popup-container");
                const langp = document.createElement("p");
                langp.classList.add("language");
                langp.textContent = newLanguage.lname;
                langContainer.appendChild(langp);
                popupContainerLang.style.display = "none";
            }
        }
    });

    document.body.addEventListener("click", (event) => {
        if (event.target.id === "submit-skill") {
            const skill = document.getElementById('skill-name');
            if (skill.value) {
                let sk = skill.value;
                fetch('a_uploadSkill.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ type: 1, skill: sk})
                });

                const newEducation = {
                    skillname: sk
                };
                const skillsContainer = document.querySelector(".profile-skills-container");
                const popupContainerSkill = document.querySelector(".skill-popup-container");
                const skillp = document.createElement("p");
                skillp.classList.add("skill");
                skillp.textContent = newEducation.skillname;
                skillsContainer.appendChild(skillp);
                popupContainerSkill.style.display = "none";
        }
        }

        // Handle skill deletion
        if (event.target.classList.contains("delete-skill")) {
            const skillElement = event.target.closest(".skill");
            const skillName = skillElement.dataset.skillname;
            //console.log(skillName);
            fetch("a_uploadSkill.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ type: 0, skill: skillName })
            });

            skillElement.remove();
        }

        // Handle language deletion
        if (event.target.classList.contains("delete-language")) {
            const langElement = event.target.closest(".language");
            const lname = langElement.dataset.lname;
            //console.log(skillName);
            fetch("a_uploadLanguage.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ type: 0, language: lname })
            });

            langElement.remove();
        }
    });
    
    
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


  if(pageType === "network"){
    fetch('a_fetchNetwork.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }
  }).then(response => response.json())
    .then(data => {
        const profiles = data.network;
        const incomings = data.incoming;
        const seconds = data.seconds;
        const container = document.getElementById("resultsContainer");
        const incomingConn = document.getElementById("incomingConn");

        if (Array.isArray(profiles) && profiles.length > 0) {
            container.innerHTML = "";
        
            profiles.forEach(profile => {
                const profileDiv = document.createElement("div");
                profileDiv.classList.add("profile-card");
        
                const profileLink = document.createElement("a");
                profileLink.href = `profile.php?id=${profile.uid}`;
                profileLink.classList.add("profile-link");
        
                if (profile.uimage) {
                    const imageTag = document.createElement("img");
                    imageTag.src = profile.uimage;
                    imageTag.alt = "User Image";
                    imageTag.classList.add("profile-img");
                    profileDiv.appendChild(imageTag);
                }
        
                const name = document.createElement("h3");
                name.textContent = `${profile.ufname} ${profile.ulname}`;
        
                const title = document.createElement("p");
                title.textContent = `Title: ${profile.utitle || 'No Title'}`;
        
                const about = document.createElement("p");
                about.textContent = `About : ${profile.udescription || 'No about'}`;
                
                profileDiv.appendChild(name);
                profileDiv.appendChild(title);
                profileDiv.appendChild(about);
                profileLink.appendChild(profileDiv);
                container.appendChild(profileLink);
            });
        
            const breakline = document.createElement("br");
            container.appendChild(breakline);
        }
        
        // **Check if `seconds` has elements before adding the header**
        if (Array.isArray(seconds) && seconds.length > 0) {
            const header2 = document.createElement("h1");
            header2.textContent = "People you may know";
            container.appendChild(header2);
        
            seconds.forEach(second => {
                // Create the profile card div
                const profileDiv = document.createElement("div");
                profileDiv.classList.add("profile-card");
        
                // Create the anchor element
                const profileLink = document.createElement("a");
                profileLink.href = `profile.php?id=${second.uid}`;
                profileLink.classList.add("profile-link"); // Optional CSS class
        
                // Create an image element if the profile has an image
                if (second.uimage) {
                    const imageTag = document.createElement("img");
                    imageTag.src = second.uimage;
                    imageTag.alt = "User Image";
                    imageTag.classList.add("profile-img");
                    profileDiv.appendChild(imageTag);
                }
        
                // Add user info
                const name = document.createElement("h3");
                name.textContent = `${second.ufname} ${second.ulname}`;
        
                const title = document.createElement("p");
                title.textContent = `Title: ${second.utitle || 'No Title'}`;
        
                const about = document.createElement("p");
                about.textContent = `About: ${second.udescription || 'No about'}`;
        
                // Append elements to profileDiv
                profileDiv.appendChild(name);
                profileDiv.appendChild(title);
                profileDiv.appendChild(about);
        
                // Append profileDiv inside the anchor tag
                profileLink.appendChild(profileDiv);
        
                // Append the anchor to the container
                container.appendChild(profileLink);
            });
        }        

        if (Array.isArray(incomings) && incomings.length > 0) {
            incomings.forEach(incoming => {
                const profileDiv = document.createElement("div");
                profileDiv.classList.add("profile-card");
        
                // Create an anchor element
                const profileLink = document.createElement("a");
                profileLink.href = `profile.php?id=${incoming.uid}`;
                profileLink.classList.add("profile-link"); // Optional CSS class
        
                // Create an image element if the profile has an image
                if (incoming.uimage) {
                    const imageTag = document.createElement("img");
                    imageTag.src = incoming.uimage;
                    imageTag.alt = "User Image";
                    imageTag.classList.add("profile-img");
                    profileDiv.appendChild(imageTag);
                }
        
                // Add user info
                const name = document.createElement("h3");
                name.textContent = `${incoming.ufname} ${incoming.ulname}`;
        
                const title = document.createElement("p");
                title.textContent = `Title: ${incoming.utitle || 'No Title'}`;
        
                const about = document.createElement("p");
                about.textContent = `About: ${incoming.udescription || 'No about'}`;
        
                // Append elements to profileDiv
                profileDiv.appendChild(name);
                profileDiv.appendChild(title);
                profileDiv.appendChild(about);
        
                // Append profileDiv inside the anchor tag
                profileLink.appendChild(profileDiv);
        
                // Append the anchor to the container
                incomingConn.appendChild(profileLink);
            });
        } else {
            incomingConn.innerHTML = "<h3>Incoming</h3><br><p>No incoming Connections.</p>";
        }
        

    })
    .catch(error => console.error('Error fetching network:', error));

    
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
          //console.log("Upload successful:", data);
          setTimeout(() => {
              window.location.reload(); // Reload the page
          }, 100);
      })
      .catch((error) => {
          console.error("Error:", error);
      });
  });
});

  function toggleImage(type, pid) {
    if(type == 0){
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
    }if(type == 1){
        var img = document.getElementById('togglesave' + pid);
        img.style.opacity = 0; // Fade out the image
        
        // Wait until the image fades out before switching the source
        setTimeout(function() {
        if (img.src.includes("saved")) {
            fetch('a_savePost.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: 0, postid: pid })
            })
            img.src = "images/save.png";  // Change to the "liked" image
        } else {
            fetch('a_savePost.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: 1, postid: pid })
            })
            img.src = "images/saved.png";        
        }
        
        // Fade the image back in after switching the source
        img.style.opacity = 1;
        }, 150);   
    }
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
    //console.log(id); 
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
            //console.log("Server response:", data);

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



function sendConnectionInProfile(type) {
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
                receiverId: decodedId,
                type: type
            }),
        })
        .then((response) => response.json())
        .then((data) => {
                setTimeout(() => {
                    window.location.reload();
                }, 100);
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

document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll(".navbar-center ul li a");
        //console.log(navLinks);
    navLinks.forEach(link => {
        link.addEventListener("click", function () {
            // Remove 'active-link' from all links
            navLinks.forEach(nav => nav.classList.remove("active-link"));

            // Add 'active-link' to the clicked link
            this.classList.add("active-link");
        });
    });
});

