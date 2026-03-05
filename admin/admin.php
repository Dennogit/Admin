<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Logins/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="admin.css">
  <style>
    
  </style>
</head>
<body>
  <button class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>

  <div class="backdrop" id="backdrop" onclick="hideSidebar()"></div>

  <div class="sidebar hidden-mobile" id="sidebar">
    <div class="header">
      <img src="GLWq2ukXoAAVaNu.jpeg" alt="Profile Photo" style="width:62px;height:62px;border-radius:50%;vertical-align:middle;"> <br>
      <span class="username">Admin</span>
    </div>
    <button onclick="showContent('dashboard')"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></button>
    <button onclick="showContent('notices')"><i class="fas fa-bullhorn"></i> <span>Manage Notices</span></button> 
    <button onclick="showContent('users')"><i class="fas fa-users"></i> <span>Manage Users</span></button>

    <!-- <button onclick="showContent('images')"><i class="fas fa-images"></i> <span>Images</span></button> -->
    <button onclick="showContent('settings')"><i class="fas fa-cogs"></i> <span>Settings</span></button>
  
    <button onclick="showContent('logout')"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></button>
  </div>

  <div class="main-content">
    <div id="dashboard">
        <h1>Welcome to Admin Dashboard</h1> 

        <div class="dashboard-cards">
            <!-- Students Card -->
            <div class="card green" onclick="showContent('users')">
              <div class="card-icon"><i class="fas fa-users"></i></div>
              <div class="card-info">
                <div class="card-value"><span id="student-count">.</span></div>
                <div class="card-title">Students</div>

                <div class="card-desc"><a href="#users"  style="text-decoration: none; color: inherit;">Manage Students</a></div>
              </div>
            </div>
            <!-- Staff Card -->
            <div class="card pink" onclick="showContent('users')">
              <div class="card-icon"><i class="fas fa-user-friends"></i></div>
              <div class="card-info">
                <div class="card-value"><span id="stuff-count">.</span></div>
                <div class="card-title">Stuff</div>


                <div class="card-desc"><a href="#users.stuff" style="text-decoration: none; color: inherit;">Manage Stuff</a></div>
                
              </div>
            </div>
            <!-- Notices Card -->
           <div class="card gold" onclick="showContent('notices')">
              <div class="card-icon"><i class="fas fa-comment"></i></div>
              <div class="card-info">
                <div class="card-value"><span id="notice-count">.</span></div>
                <div class="card-title">Notices</div>

                <div class="card-desc"><a href="#notices" style="text-decoration: none; color: inherit;">Manage Notices</a></div>
              </div>
            </div>
          </div>
          
    
            <div class="recent-wrapper">
              <iframe src="recentnotices.php" style="width: 100%; height: 53vh; border: none;"></iframe>
            </div>
          
    </div>

    <div id="notices" class="hidden">
      <iframe src="notic.php" style="width: 100%; height: 90vh; border: none;" ></iframe>
    </div>
    <div id="users" class="hidden">
    
      <iframe src="manage2.php" style="width: 100%; height: 90vh; border: none;"></iframe>
    </div>

  
    
    <!-- <div id="images" class="hidden">
      <iframe src="../index.php" style="width: 100%; height: 90vh; border: none;" ></iframe>
    </div> -->
    <div id="settings" class="hidden">
      <iframe src="settings.php" style="width: 100%; height: 90vh; border: none;" ></iframe>
    </div>
   <div id="logout" class="hidden" style="text-align: center; margin-top: 40px;">
  <button onclick="window.location.href='../logout.php'" style="
    padding: 12px 24px;
    background-color: #e53935;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: background-color 0.3s ease;">
    <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i> Logout
  </button>
</div>

  </div>

  <script>

document.querySelectorAll('.card').forEach(card => {
  card.addEventListener('click', function(e) {
    let ripple = document.createElement('span');
    ripple.style.position = 'absolute';
    ripple.style.width = '100px';
    ripple.style.height = '100px';
    ripple.style.background = 'rgba(255,255,255,0.4)';
    ripple.style.borderRadius = '50%';
    ripple.style.top = e.offsetY + 'px';
    ripple.style.left = e.offsetX + 'px';
    ripple.style.transform = 'translate(-50%, -50%) scale(0)';
    ripple.style.animation = 'rippleEffect 0.6s linear';
    ripple.style.pointerEvents = 'none';

    this.appendChild(ripple);

    setTimeout(() => ripple.remove(), 600);
  });
});


//student coung

// Fetch student count from server and update the dashboard
// function fetchStudentCount() {
//   fetch('get_student_count.php')
//     .then(response => response.json())
//     .then(data => {
//       document.getElementById('student-count').textContent = data.count ?? '1';
//     })
//     .catch(() => {
//       document.getElementById('student-count').textContent = '1';
//     });
// }

// Fetch stuff count from server and update the dashboard
function fetchStuffCount() {
  fetch('get_stuff_count.php')
    .then(response => response.json())
    .then(data => {
      document.getElementById('stuff-count').textContent = data.count ?? '0';
    })
    .catch(() => {
      document.getElementById('stuff-count').textContent = '0';
    });
}

// Fetch notice count from server and update the dashboard
function fetchNoticeCount() {
  fetch('get_notice_count.php')
    .then(response => response.json())
    .then(data => {
      document.getElementById('notice-count').textContent = data.count ?? '0';
    })
    .catch(() => {
      document.getElementById('notice-count').textContent = '0';
    });
}

// Call on page load
fetchStudentCount();
fetchStuffCount();
fetchNoticeCount();

    function showContent(id) {
      const sections = ['dashboard', 'users', 'notices', 'settings', 'logout',];
      sections.forEach(section => {
        document.getElementById(section).classList.add('hidden');
      });
      document.getElementById(id).classList.remove('hidden');
      if (window.innerWidth <= 768) {
        hideSidebar();
      }
    }

    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const backdrop = document.getElementById('backdrop');
      const isHidden = sidebar.classList.contains('hidden-mobile');

      if (isHidden) {
        sidebar.classList.remove('hidden-mobile');
        backdrop.classList.add('show');
      } else {
        sidebar.classList.add('hidden-mobile');
        backdrop.classList.remove('show');
      }
    }

    function hideSidebar() {
      const sidebar = document.getElementById('sidebar');
      const backdrop = document.getElementById('backdrop');
      sidebar.classList.add('hidden-mobile');
      backdrop.classList.remove('show');
    }

    
    function loadPage(pageUrl) {
      fetch(pageUrl)
        .then(response => {
          if (!response.ok) throw new Error('Network response was not ok');
          return response.text();
        })
        .then(html => {
          document.getElementById('dashboard-content').innerHTML = html;
        })
        .catch(error => {
          document.getElementById('dashboard-content').innerHTML = '<p>Failed to load content.</p>';
          console.error('Error fetching page:', error);
        });
    }

    // Fetch student count from server and update the dashboard
    function fetchStudentCount() {
      fetch('get_student_count.php')
        .then(response => response.json())
        .then(data => {
          document.getElementById('student-count').textContent = data.count ?? '0';
        })
        .catch(() => {
          document.getElementById('student-count').textContent = '0';
        });
    }

    // Fetch stuff count from server and update the dashboard
    // function fetchStuffCount() {
    //   fetch('get_stuff_count.php')
    //     .then(response => response.json())
    //     .then(data => {
    //       document.getElementById('stuff-count').textContent = data.count ?? '0';
    //     })
    //     .catch(() => {
    //       document.getElementById('stuff-count').textContent = '0';
    //     });
    // }

    // Fetch notice count from server and update the dashboard
    // function fetchStudentCount() {
    //   fetch('get_notice_count.php')
    //     .then(response => response.json())
    //     .then(data => {
    //       document.getElementById('notice-count').textContent = data.count ?? '0';
    //     })
    //     .catch(() => {
    //       document.getElementById('notice-count').textContent = '0';
    //     });
    // }

    // Call on page load
    fetchNoticeCount();
  </script>
</body>
</html>
