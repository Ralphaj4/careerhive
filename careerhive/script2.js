document.addEventListener('DOMContentLoaded', function () {
  
  fetch('fetchpost.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }
  })
  .then(response => response.json())
  .then(data => {
          const postsContainer = document.getElementById('posts-container');
          postsContainer.innerHTML = '';

          data.posts.forEach((post, index) => {
              const postElement = document.createElement('div');
              postElement.classList.add('post');

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
                  ${post.pimage ? `<img src="${post.pimage}" alt="" width="100%" />` : ''}
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
                          <img src="${post.user_image}" alt="" class="post-activity-user-icon">
                          <img src="images/down-arrow.png" class="post-activity-arrow-icon">
                      </div>
                      <div class="post-activity-link">
                          <button class="like" data-postid="${post.pid}">
                              <img src="images/${post.liked ? 'liked' : 'like'}.png" id="toggleImage${post.pid}">
                              <span>Like</span>
                          </button>
                      </div>
                      <div class="post-activity-link">
                          <button class="comment-post" data-postid="${post.pid}" onclick="showComments(${index + 1}, ${post.pid})">
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
                  </div>
              `;

              postsContainer.appendChild(postElement);
          });
  })
  .catch(error => console.error('Error fetching posts:', error));
});


// Helper function to sanitize text (prevents XSS)
function sanitize(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}
