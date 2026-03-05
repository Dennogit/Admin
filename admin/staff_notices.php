<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Staff Notices</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <!-- Google Fonts: Poppins and Roboto -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', 'Roboto', Arial, sans-serif;
      margin: 0;
      background: #f5f5f5;
      padding: 20px;
    }

    header {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
    }

    h2 {
      margin: 0;
      font-family: 'Poppins', Arial, sans-serif;
      font-weight: 600;
      letter-spacing: 1px;
    }

    .search-bar {
      display: flex;
      align-items: center;
      background: #e0e0e0;
      padding: 8px 12px;
      border-radius: 20px;
      font-family: 'Roboto', Arial, sans-serif;
    }

    .search-bar input {
      border: none;
      background: transparent;
      outline: none;
      padding: 6px;
      width: 180px;
      font-family: 'Roboto', Arial, sans-serif;
      font-size: 15px;
    }

    .search-bar button {
      background: none;
      border: none;
      font-size: 18px;
      cursor: pointer;
      color: #555;
    }

    .icon-btn {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      color: #555;
    }

    .categories {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 20px;
    }

    .category-btn {
      background: #d3d3d3;
      padding: 6px 14px;
      border-radius: 20px;
      border: none;
      cursor: pointer;
      transition: background 0.3s;
      font-family: 'Poppins', Arial, sans-serif;
      font-weight: 500;
      letter-spacing: 0.5px;
    }

    .category-btn:hover {
      background: #c0c0c0;
    }

    .notices {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .notice-card {
      background: #e0e0e0;
      padding: 15px;
      border-radius: 10px;
      cursor: pointer;
      transition: transform 0.2s;
      font-family: 'Roboto', Arial, sans-serif;
      box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .notice-card:hover {
      transform: translateY(-5px) scale(1.03);
      box-shadow: 0 6px 16px rgba(0,0,0,0.10);
    }

    .notice-card h4 {
      margin: 0 0 8px;
      font-family: 'Poppins', Arial, sans-serif;
      font-weight: 600;
      font-size: 18px;
    }

    .notice-card p {
      margin: 4px 0;
      font-size: 14px;
      font-family: 'Roboto', Arial, sans-serif;
    }

    .date {
      text-align: right;
      font-size: 12px;
      color: #555;
      font-family: 'Roboto', Arial, sans-serif;
    }

    .modal, .notification-panel {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
      z-index: 999;
      font-family: 'Poppins', Arial, sans-serif;
    }

    .modal-content, .notification-content {
      background: white;
      padding: 20px;
      border-radius: 10px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      position: relative;
      font-family: 'Roboto', Arial, sans-serif;
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 18px;
      cursor: pointer;
      font-family: 'Poppins', Arial, sans-serif;
    }

    @media (max-width: 600px) {
      .search-bar input {
        width: 100px;
      }
    }
  </style>
</head>

<body>

  <header>
    <h2>All notices</h2>

    <div style="display: flex; align-items: center; gap: 10px;">
      <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search..." oninput="searchNotices()">
        <button><i class="fas fa-search"></i></button>
      </div>
      <button class="icon-btn" onclick="openNotifications()">
        <i class="fas fa-bell"></i>
      </button>
    </div>

     <div class="categories">
    <button class="category-btn" onclick="filterCategory('all')">All</button>
    <button class="category-btn" onclick="filterCategory('Academic')">Academic</button>
    <button class="category-btn" onclick="filterCategory('Sports')">Sports</button>
    <button class="category-btn" onclick="filterCategory('News')">News & Events</button>
    <button class="category-btn" onclick="filterCategory('Lost')">Lost & Found</button>
    <button class="category-btn" onclick="filterCategory('Starred')">Starred</button>
    <button class="category-btn" onclick="filterCategory('Announcements')">Announcements</button>
  </div>
  </header>

<style>
  @media (min-width: 900px) {
    header {
      position: sticky;
      top: 0;
      background: #f5f5f5;
      z-index: 10;
      padding-top: 20px;
      padding-bottom: 10px;
    }
    .notices {
      max-height: 620px;
      overflow-y: auto;
      margin-top: 0;
    }
    body {
      padding-top: 0;
    }
  }
</style>

  <section class="notices" id="noticeList" style="max-height: 620px; overflow-y: auto;">
    
      <?php
    include 'db.php';

    $sql = "SELECT title, meta, audience, image_path, created_at FROM notices WHERE audience = 'Staff' OR audience = 'both' ORDER BY created_at DESC";
    $result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $title = htmlspecialchars($row['title']);
        $meta = htmlspecialchars($row['meta']);
        $category = htmlspecialchars($row['audience']); // ✅ Now using 'category'
        $image = !empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'https://via.placeholder.com/400x120?text=No+Image';
        $createdAt = date('d/m/Y h:i a', strtotime($row['created_at']));

        echo "
        <div class='notice-card' data-category='$category' onclick=\"openNotice('$title', '$meta', '$createdAt', '$image')\">
          <img src='$image' alt='Notice Image' style='width:100%;height:120px;object-fit:cover;border-radius:8px 8px 0 0;margin-bottom:10px;'>
          <h4><b>Title:</b> $title</h4>
          <p><b>By:</b> $meta</p>
          <p class='date'>$createdAt</p>
        </div>
        ";
    }
} else {
    echo "<p>No staff notices found.</p>";
}

$conn->close();
    ?>

  </section>

  <!-- Notice Modal -->
  <div id="noticeModal" class="modal">
    <div class="modal-content">

      <span class="close-btn" onclick="closeModal('noticeModal')">&times;</span>
      <img id="modalNoticeImage" src="" alt="Notice Image" style="width:100%;height:120px;object-fit:cover;border-radius:8px 8px 0 0;margin-bottom:10px;display:none;">
      <h3 id="noticeTitle"></h3>
      <p id="noticeAuthor"></p>
      <p id="noticeDate"></p>

      <!-- Add Comment Section -->
      <div id="commentSection" style="margin-top:20px;text-align:left;">
        <h4 style="margin-bottom:8px;">Add a Comment</h4>
        <textarea id="commentInput" rows="3" style="width:100%;border-radius:6px;border:1px solid #ccc;padding:8px;font-family:'Roboto',Arial,sans-serif;font-size:14px;resize:vertical;" placeholder="Write your comment..."></textarea>
        <button onclick="addComment()" style="margin-top:8px;padding:6px 16px;border:none;border-radius:20px;background:#1976d2;color:white;font-family:'Poppins',Arial,sans-serif;cursor:pointer;">Post</button>
        <div id="commentsList" style="margin-top:16px;">
          <!-- Comments will appear here -->
        </div>
      </div>
      
    </div>
  </div>
  <script>
    // Add Comment functionality
    function addComment() {
      const input = document.getElementById('commentInput');
      const comment = input.value.trim();
      if (comment) {
        const commentsList = document.getElementById('commentsList');
        const commentDiv = document.createElement('div');
        commentDiv.style.marginBottom = '10px';
        commentDiv.style.padding = '8px 10px';
        commentDiv.style.background = '#f0f0f0';
        commentDiv.style.borderRadius = '6px';
        commentDiv.style.fontSize = '14px';
        commentDiv.textContent = comment;
        commentsList.prepend(commentDiv);
        input.value = '';
      }
    }
  </script>
  

  <!-- Notifications -->
  <div id="notificationPanel" class="notification-panel">
    <div class="notification-content">
      <span class="close-btn" onclick="closeModal('notificationPanel')">&times;</span>
      <h3>Notifications</h3>
      <p>No new notifications.</p>
    </div>
  </div>

  <script>
    function openNotice(title, author, date) {
      document.getElementById('noticeTitle').innerText = title;
      document.getElementById('noticeAuthor').innerText = "By: " + author;
      document.getElementById('noticeDate').innerText = date;
      document.getElementById('noticeModal').style.display = 'flex';
    }

    function closeModal(id) {
      document.getElementById(id).style.display = 'none';
    }

    function openNotifications() {
      document.getElementById('notificationPanel').style.display = 'flex';
    }

    function searchNotices() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const cards = document.querySelectorAll('.notice-card');
      cards.forEach(card => {
        card.style.display = card.textContent.toLowerCase().includes(input) ? "block" : "none";
      });
    }

    function filterCategory(category) {
      const cards = document.querySelectorAll('.notice-card');
      cards.forEach(card => {
        const cat = card.dataset.category || "";
        if (category === 'all' || cat.includes(category)) {
          card.style.display = "block";
        } else {
          card.style.display = "none";
        }
      });
    }

    
function openNotice(title, author, date, image = '') {
  document.getElementById('noticeTitle').innerText = title;
  document.getElementById('noticeAuthor').innerText = "By: " + author;
  document.getElementById('noticeDate').innerText = date;
  const img = document.getElementById('modalNoticeImage');
  if (image) {
    img.src = image;
    img.style.display = 'block';
  } else {
    img.style.display = 'none';
  }
  document.getElementById('noticeModal').style.display = 'flex';
}


  </script>


</body>
</html>
