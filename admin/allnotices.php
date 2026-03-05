<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>All Notices</title>
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
    <h2>Student`s notices</h2>

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
    <!-- Notices will be dynamically loaded here -->

  </section>

  <!-- Notice Modal -->
  <div id="noticeModal" class="modal">
    <div class="modal-content">
      <span class="close-btn" onclick="closeModal('noticeModal')">&times;</span>
      <h3 id="noticeTitle"></h3>
      <p id="noticeAuthor"></p>
      <p id="noticeDate"></p>
    </div>
  </div>

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

//     window.onload = function () {
//   fetch('fetch_notices.php')
//     .then(res => res.json())
//     .then(data => {
//       if (data.status === 'success') {
//         renderNotices(data.data);
//       } else {
//         document.getElementById('noticeList').innerHTML = '<p>Error loading notices.</p>';
//       }
//     })
//     .catch(err => {
//       console.error(err);
//       document.getElementById('noticeList').innerHTML = '<p>Error loading notices.</p>';
//     });
// };


function renderNotices(notices) {
  const container = document.getElementById('noticeList');
  container.innerHTML = '';

  notices.forEach(notice => {
    const card = document.createElement('div');
    card.className = 'notice-card';
    card.dataset.category = notice.category;

    const imgSrc = notice.image_path || 'https://via.placeholder.com/400x150';

    card.innerHTML = `
      <img src="${imgSrc}" alt="${notice.title}" style="width:100%;height:120px;object-fit:cover;border-radius:8px 8px 0 0;margin-bottom:10px;">
      <h4><b>Title:</b> ${notice.title}</h4>
      <p class="date">${notice.created_at}</p>
    `;

    card.onclick = () => openNotice(notice.title, notice.created_at);
    container.appendChild(card);
  });
}

function openNotice(title, date) {
  document.getElementById('noticeTitle').innerText = title;
  document.getElementById('noticeDate').innerText = date;
  document.getElementById('noticeModal').style.display = 'flex';
}


document.addEventListener("DOMContentLoaded", () => {
  fetch('fetch_notices.php')
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        renderNotices(data.data);
      } else {
        document.getElementById('noticeList').innerHTML = '<p>Error loading notices.</p>';
      }
    })
    .catch(err => {
      console.error(err);
      document.getElementById('noticeList').innerHTML = '<p>Error loading notices.</p>';
    });
});


  </script>

</body>
</html>
