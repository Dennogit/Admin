<?php
// session_start();
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../Logins/login.php");
//     exit;
// }
// Connect to DB
$db = new mysqli('localhost', 'root', '', 'university_portal');
$notices = [];

if (!$db->connect_errno) {
    $res = $db->query("SELECT title, meta, image_path, audience, created_at FROM notices ORDER BY created_at DESC");
    while ($row = $res->fetch_assoc()) {
        $notices[] = $row;
    }
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recent Updates - E-Notice Board</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    body {
  margin: 0;
  font-family: 'Poppins', Arial, sans-serif;
  background: linear-gradient(135deg, #eeebc1ff, #c8afe0ff);
  min-height: 100vh;
  padding: 20px;
}

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .search-bar {
      display: flex;
      align-items: center;
      background: #fff;
      padding: 4px 8px;
      border-radius: 20px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }

    .search-bar input {
      border: none;
      outline: none;
      padding: 8px;
      width: 200px;
    }

    .search-bar button {
      background: none;
      border: none;
      cursor: pointer;
      font-size: 18px;
      margin-left: 5px;
    }

    .icon-btn {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      margin-left: 0px;
    }

    .notices {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .notice-card {
  background: rgba(223, 219, 219, 0.25);
  backdrop-filter: blur(15px);
  -webkit-backdrop-filter: blur(15px);
  border-radius: 16px;
  padding: 15px;
  cursor: pointer;
  transition: all 0.3s ease;
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
  overflow: hidden;
}


.notice-card::before {
  content: "";
  position: absolute;
  inset: 0;
  border-radius: 16px;
  padding: 1px;
  background: linear-gradient(135deg, rgba(255,255,255,0.6), rgba(255,255,255,0.1));
  -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
}

.notice-card:hover {
  transform: translateY(-6px) scale(1.02);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
  background: rgba(255, 255, 255, 0.35);
}

    .notice-card h4 {
      margin: 0;
      margin-bottom: 8px;
    }

    .notice-card p {
      margin: 4px 0;
      font-size: 14px;
    }

    .notice-card .date {
      text-align: right;
      font-size: 12px;
      color: #555;
      margin-top: 10px;
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
    }

    .modal-content, .notification-content {
      background: white;
      padding: 20px;
      border-radius: 10px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      position: relative;
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 18px;
      cursor: pointer;
    }

    @media (max-width: 600px) {
      .search-bar input {
        width: 120px;
      }
    }

    .main-title {
    position: sticky;       /* sticks to the top of scroll container */
    top: 15;                 /* offset from top */
    z-index: 10;            /* above notice cards */
    margin: 0;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.25); /* semi-transparent white */
    backdrop-filter: blur(10px);           /* glass effect */
    -webkit-backdrop-filter: blur(10px);   /* Safari support */
    border-radius: 8px;
    font-size: 1.8rem;
    font-weight: 600;
    color: #742c2c;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Responsive font sizes */
@media (max-width: 768px) {
    .main-title {
        font-size: 1.5rem;
        padding: 10px 12px;
    }
}

@media (max-width: 480px) {
    .main-title {
        font-size: 1.3rem;
        padding: 8px 10px;
    }
}

@media(max-width:480px){
    .posts-grid{
        grid-template-columns:1fr;
    }

    .post-card img{
        height:200px;
    }
}
  </style>
</head>

<body>

  <header class="main-title" style="position: fixed; top: 0; left: 10; width: 94%;  z-index: 1000; box-shadow: 0 2px 16px rgba(0,0,0,0.05); padding: 10px;">
    <h2 style="margin: 0;">Recent updates</h2>

    <div style="display: flex; align-items: center;">
      <button class="icon-btn" onclick="openNotifications()"><i class="fas fa-bell"></i></button>
      <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchNotices()">
        <button onclick="searchNotices()"><i class="fas fa-search"></i></button>
      </div>
    </div>
  </header>
  <div style="height: 90px;"></div>

  <section class="notices" id="noticeList">
    <?php if (count($notices) === 0): ?>
      <p>No recent notices found.</p>
    <?php else: ?>
      <?php foreach($notices as $notice): ?>
        <div class="notice-card" onclick="openNotice('<?= htmlspecialchars(addslashes($notice['title'])) ?>', '<?= htmlspecialchars(addslashes($notice['meta'])) ?>', '<?= htmlspecialchars(date("d/m/Y h:ia", strtotime($notice['created_at']))) ?>')">
<img src="<?= !empty($notice['image_path']) ? htmlspecialchars($notice['image_path']) : 'https://via.placeholder.com/400x120' ?>" 
     alt="Notice Image" 
     style="width:100%;height:120px;object-fit:cover;border-radius:12px;margin-bottom:12px;">          <h4><b>Title:</b> <?= htmlspecialchars($notice['title']) ?></h4>
          <p><b>About:</b> <?= htmlspecialchars($notice['meta']) ?></p>
          <p class="date"><?= htmlspecialchars(date("d/m/Y h:ia", strtotime($notice['created_at']))) ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>

  <!-- Modal for Notice Details -->
  <div id="noticeModal" class="modal">
    <div class="modal-content">
      <span class="close-btn" onclick="closeModal('noticeModal')">&times;</span>
      <h3 id="noticeTitle"></h3>
      <p id="noticeAuthor"></p>
      <p id="noticeDate"></p>
    </div>
  </div>

  <!-- Notification Panel -->
  <div id="notificationPanel" class="notification-panel">
    <div class="notification-content">
      <span class="close-btn" onclick="closeModal('notificationPanel')">&times;</span>
      <h3>Notifications</h3>
      <p>No new notifications at this time.</p>
    </div>
  </div>

  <script>
    function openNotice(title, author, date) {
      document.getElementById('noticeTitle').innerText = title;
      document.getElementById('noticeAuthor').innerText = "About: " + author;
      document.getElementById('noticeDate').innerText = date;
      document.getElementById('noticeModal').style.display = 'flex';
    }

    function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
    }

    function searchNotices() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const notices = document.querySelectorAll('.notice-card');

      notices.forEach(notice => {
        const text = notice.textContent.toLowerCase();
        notice.style.display = text.includes(input) ? "block" : "none";
      });
    }

    function openNotifications() {
      document.getElementById('notificationPanel').style.display = 'flex';
    }
  </script>

</body>
</html>
