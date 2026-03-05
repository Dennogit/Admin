<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Logins/login.php");
    exit;
}
 

include 'db.php'; // make sure this is correct and points to your database connection

// Get student notices ordered by latest
$sql = "SELECT title, meta, image_path, created_at FROM notices WHERE audience = 'Students' ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: #f0f0f0;
      padding: 20px;
      display: flex;
      gap: 20px;
    }

    .main-container {
      background: #fff;
      flex: 2;
      padding: 20px;
      border-radius: 12px;
      height: 95vh;
      width: 90vh;
      margin-right: 260px;
    }

    .sidebar {
      background: #fff;
      flex: 1;
      padding: 20px;
      border-radius: 12px;
      height: 100%;
    }

    .search-bar {
      position: relative;
      margin-bottom: 20px;
    }

    .search-bar input {
      width: 100%;
      padding: 12px 40px 12px 20px;
      border-radius: 30px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    .search-bar i {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #888;
    }

    h2 {
      font-size: 24px;
      margin-bottom: 20px;
    }

    .popular-section {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .card {
      width: 47%;
      background: #eee;
      padding: 10px;
      border-radius: 12px;
    }

    .card img {
      width: 100%;
      border-radius: 8px;
    }

    .card .title {
      font-weight: bold;
      margin-top: 10px;
    }

    .card .desc {
      font-size: 14px;
      color: #555;
    }

    .card a {
      text-decoration: none;
      color: #4a90e2;
      font-size: 14px;
    }

    .sidebar h3 {
      margin-bottom: 10px;
      font-size: 20px;
    }

    .events, .calendar {
      margin-bottom: 30px;
    }

    .event-item {
      font-size: 14px;
      padding: 5px 0;
    }

    .calendar select, .calendar input[type=date] {
      width: 100%;
      margin: 5px 0;
      padding: 8px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .calendar button {
      width: 100%;
      padding: 10px;
      background: #7864f7;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }
      .main-container {
        width: 100%;
        margin-bottom: 30px;
        margin-right: 0;
      }
      .card {
        width: 100%;
      }
      .sidebar {
        position: static !important;
        width: 100% !important;
        height: auto !important;
        margin-top: 0;
        margin-right: 0;
        z-index: auto;
      }
    }
  </style>
</head>
<body>
  <?php
    
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Student';
  ?>
  <div class="main-container">
    <div class="top-bar">
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search notices" onkeyup="filterNotices()">
            <i class="fas fa-search"></i>
        </div>
        <script>
          function filterNotices() {
            const input = document.getElementById('search-input').value.toLowerCase();
            const cards = document.querySelectorAll('.popular-section .card');
            cards.forEach(card => {
              const title = card.querySelector('.title').textContent.toLowerCase();
              const desc = card.querySelector('.desc').textContent.toLowerCase();
              if (title.includes(input) || desc.includes(input)) {
            card.style.display = '';
              } else {
            card.style.display = 'none';
              }
            });
          }
        </script>
        <h2>Welcome back, <?php echo htmlspecialchars($username); ?></h2>
        <h3>Popular this week <a href="../admin/all_students_notices.php" style="float:right; font-size:14px">View all</a></h3>

    </div>
    
      <div class="popular-section" style="max-height: 60vh; overflow-y: auto;">
          <?php
            require 'db.php'; // Ensure this file contains the $conn connection

            $sql = "SELECT title, meta, image_path FROM notices WHERE audience = 'Students' OR audience = 'both' ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $title = htmlspecialchars($row['title']);
                $desc = htmlspecialchars($row['meta']);
                $img = !empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'https://via.placeholder.com/300x200';

                echo "
                  <div class='card' onclick=\"showNoticeDetails('$title', '$desc', '$img')\">
                    <img src='$img' alt='Notice Image'>
                    <div class='title'>$title</div>
                    <div class='desc'>$desc</div>
                    <a href='javascript:void(0)'>View details</a>
                  </div>
                ";
              }
            } else {
              echo "<div>No staff notices available.</div>";
            }

            $conn->close();
            ?>
</div>

    </div>
    <style>
      .card {
      transition: box-shadow 0.2s, transform 0.2s;
      cursor: pointer;
      }
      .card:hover {
      box-shadow: 0 6px 24px rgba(120,100,247,0.15), 0 1.5px 6px rgba(0,0,0,0.08);
      transform: translateY(-4px) scale(1.03);
      background: #f8f6ff;
      }
      .notice-modal{
        height: 60vh;
      }
    </style>

    <!-- Notice Details Modal -->
    <div id="notice-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100%; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center; overflow:auto;">
      <div style="background:#fff; border-radius:12px; padding:24px; max-width:400px; height:100vh; margin:auto; position:relative;">
      <span onclick="closeNoticeModal()" style="position:absolute; top:10px; right:18px; font-size:22px; cursor:pointer;">&times;</span>
      <img id="modal-img" src="" alt="Notice" style="width:100%; border-radius:8px; margin-bottom:12px;">
      <h3 id="modal-title"></h3>
      <p id="modal-desc"></p>
      <hr style="margin:16px 0;">
      <div>
        <h4 style="margin-bottom:8px;">Add Comment</h4>
        <textarea id="comment-input" rows="3" style="width:100%; border-radius:6px; border:1px solid #ccc; padding:8px;"></textarea>
        <button onclick="addComment()" style="margin-top:8px; background:#7864f7; color:#fff; border:none; border-radius:6px; padding:8px 16px; cursor:pointer;">Post</button>
        <div id="comments-section" style="margin-top:16px;"></div>
      </div>
      </div>
    </div>
    <script>
      function showNoticeDetails(title, desc, img) {
      document.getElementById('modal-title').textContent = title;
      document.getElementById('modal-desc').textContent = desc;
      document.getElementById('modal-img').src = img;
      document.getElementById('notice-modal').style.display = 'flex';
      document.getElementById('comments-section').innerHTML = '';
      document.getElementById('comment-input').value = '';
      }
      function closeNoticeModal() {
      document.getElementById('notice-modal').style.display = 'none';
      }
      function addComment() {
      const input = document.getElementById('comment-input');
      const comment = input.value.trim();
      if (comment) {
        const div = document.createElement('div');
        div.style.margin = '8px 0';
        div.style.background = '#f0f0f0';
        div.style.padding = '8px';
        div.style.borderRadius = '6px';
        div.textContent = comment;
        document.getElementById('comments-section').appendChild(div);
        input.value = '';
      }
      }
      // Close modal on outside click
      document.getElementById('notice-modal').addEventListener('click', function(e) {
      if (e.target === this) closeNoticeModal();
      });
    </script>

<div class="sidebar" style="position:fixed; right:20px; top:20px; width:250px; height:95vh; z-index:10;">
      <?php
        include 'db.php';
        // Fetch upcoming events from the database  
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch all upcoming events ordered by date (ascending)
        $sql = "SELECT event_name, event_date FROM upcoming_events ORDER BY event_date ASC";
        $result = $conn->query($sql);
      ?>
   
   <div class="events">
    <h3><i class="fas fa-bullhorn"></i> Upcoming events.</h3>

    <?php
    if ($result && $result->num_rows > 0) {
        // Loop through each event and display
        while ($row = $result->fetch_assoc()) {
            // Format the date (optional)
            $formattedDate = date("d-m-Y", strtotime($row['event_date']));
            echo '<div class="event-item">' . htmlspecialchars($row['event_name']) . ' - ' . $formattedDate . '</div>';
        }
    } else {
        echo '<div class="event-item">No upcoming events found.</div>';
    }

    // Close connection
    $conn->close();
    ?>






    <div class="calendar">
        <h3><i class="fas fa-calendar-alt"></i> Calendar</h3>

        <form action="addupcomingevents.php" method="post"> 
          <label for="date">Add events</label>
          <input type="text" id="event-title" placeholder="Title" name="event_name">
          <input type="date" id="event-date" name="event_date">
          <button onclick="addEvent()" type="submit">Add</button>
        </form>
        
    </div>
  </div>
</div>

  <script>
    function addEvent() {
      const title = document.getElementById('event-title').value;
      const date = document.getElementById('event-date').value;
      if (title && date) {
        const div = document.createElement('div');
        div.className = 'event-item';
        div.textContent = `${title} - ${date}`;
        document.querySelector('.events').appendChild(div);
      }
    }
  </script>
</body>
</html>
