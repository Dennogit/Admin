<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Logins/login.php");
    exit;
}
// --- Handle AJAX delete request ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $username = $_POST['username'] ?? '';
    $user_type = $_POST['user_type'] ?? '';
    if ($username && $user_type) {
        $servername = "localhost";
        $username_db = "root";
        $password = "";
        $dbname = "university_portal";
        $conn = new mysqli($servername, $username_db, $password, $dbname);
        if ($conn->connect_error) {
            http_response_code(500);
            echo "DB connection failed";
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM users WHERE username=? AND user_type=?");
        $stmt->bind_param("ss", $username, $user_type);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        if ($success) {
            echo "success";
        } else {
            http_response_code(500);
            echo "Delete failed";
        }
    } else {
        http_response_code(400);
        echo "Invalid data";
    }
    exit;
}

// --- Handle AJAX add user request ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['name'] ?? '';
    $faculty = $_POST['faculty'] ?? '';
    $campus = $_POST['campus'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $user_type = $_POST['user_type'] ?? '';
    if ($username && $faculty && $campus && $email && $password && $user_type) {
        $servername = "localhost";
        $username_db = "root";
        $password_db = "";
        $dbname = "university_portal";
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);
        if ($conn->connect_error) {
            http_response_code(500);
            echo "DB connection failed";
            exit;
        }
        // Hash password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users 
        (username, faculty, campus, university_email, password, user_type, telephone) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
        $telephone = $_POST['telephone'] ?? '';
        $stmt->bind_param("sssssss", $username, $faculty, $campus, $email, $hashed_password, $user_type, $telephone);        // $stmt->bind_param("sssss", $username, $faculty, $campus, $email, $hashed_password, $user_type, $_POST['telephone']);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        if ($success) {
            echo "success";
        } else {
            http_response_code(500);
            echo "Insert failed";
        }
    } else {
        http_response_code(400);
        echo "Invalid data";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage users</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    body {
      font-family: 'Poppins', Arial, sans-serif;
      background: #222;
      margin: 0;
      padding: 0;
      color: #222;
    }
    .container {
      display: flex;
      width: 100vw;
      height: 100vh;
      background: #e0e0e0;
      box-sizing: border-box;
      /* padding: px; */ /* Remove this line if not needed */
      min-height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      overflow: hidden;
    }
    .sidebar {
      width: 150px;
      background: #f5f5f5;
      border-radius: 10px;
      margin-right: 5px;
      padding: 10px 0 10px 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-width: 180px;
      flex-shrink: 0;
    }
    .sidebar h2 {
      margin-bottom: 30px;
      color: #222;
      font-size: 22px;
      font-weight: bold;
    }
    .menu-btn {
      width: 85%;
      padding: 13px 0;
      margin-bottom: 15px;
      border: none;
      border-radius: 6px;
      background: #ddd;
      color: #222;
      font-size: 10px;
      cursor: pointer;
      text-align: left;
      transition: background 0.2s;
      display: flex;
      align-items: center;
      gap: 5px;
      justify-content: center;
    }
    .menu-btn.active, .menu-btn:hover {
      background: #742c2c;
      color: #fff;
    }

    .main-panel {
      flex: 1;
      background: #fff;
      border-radius: 10px;
      padding: 30px 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.07);
      display: flex;
      flex-direction: column;
      width: 900px;
      min-width: 0;
      position: relative;
      overflow: hidden;
      
    }
    .search-bar {
      display: flex;
      align-items: center;
      margin-bottom: 22px;
    }
    .search-bar input {
      flex: 1;
      padding: 8px 12px;
      border-radius: 20px 0 0 20px;
      border: 1px solid #ccc;
      outline: none;
      font-size: 15px;
    }
    .search-bar button {
      background: #742c2c;
      border: none;
      color: #fff;
      padding: 8px 15px;
      border-radius: 0 20px 20px 0;
      cursor: pointer;
      font-size: 17px;
    }
    .table-container {
      width: 100%;
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      min-width: 600px;
    }
    th, td {
      padding: 12px 10px;
      text-align: left;
      border-bottom: 1px solid #eee;
      font-size: 15px;
    }
    th {
      background: #f2f2f2;
      font-size: 15px;
    }
    tr:last-child td {
      border-bottom: none;
    }
    .toggle-btn {
      background: none;
      border: none;
      cursor: pointer;
      font-size: 18px;
      color: #742c2c;
      margin-right: 8px;
      transition: color 0.2s;
    }
    .toggle-btn.active {
      color: #28a745;
    }
    .toggle-btn.delete.active {
      color: #dc3545;
    }
    /* Floating Plus Button */
    .fab {
      position: fixed;
      right: 35px;
      bottom: 35px;
      width: 36px;
      height: 36px;
      background: #742c2c;
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      cursor: pointer;
      z-index: 1000;
      transition: background 0.2s;
      border: none;
    }
    .fab:hover {
      background:rgb(107, 23, 9);
    }
    /* Responsive Styles */
    @media (max-width: 700px) {
      .container {
        flex-direction: column;
        padding: 10px;
        min-height: 100vh;
      }
      .sidebar {
        width: 100%;
        margin-bottom: 20px;
        flex-direction: row;
        justify-content: center;
        align-items: flex-start;
        padding: 10px 0;
        border-radius: 10px;
        position: static;
      }
      .sidebar h2 {
        display: none;
      }
      .menu-btn {
        width: auto;
        min-width: 120px;
        margin: 0 10px 0 0;
        font-size: 15px;
        padding: 10px 10px;
      }
      .main-panel {
        padding: 15px 5px;
      }
      table {
        min-width: 500px;
        font-size: 13px;
      }
      th, td {
        padding: 10px 6px;
      }
    }
    @media (max-width: 600px) {
      .container {
        padding: 0;
      }
      .sidebar {
        flex-direction: row;
        padding: 5px 0;
        border-radius: 0;
        margin-right: 0;
        margin-bottom: 10px;
        position: static;
      }
      .main-panel {
        padding: 8px 2px;
        border-radius: 0;
      }
      .fab {
        right: 16px;
        bottom: 16px;
        width: 48px;
        height: 48px;
        font-size: 25px;
      }
      table {
        min-width: 400px;
      }
      .search-bar{
        width: 100%;
      }
    }
  </style>
</head>
<body>
<?php
// --- PHP: Fetch users from database ---
$servername = "localhost";
$username = "root";
$password = ""; // adjust if needed
$dbname = "university_portal";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$users = [
    "students" => [],
    "stuff" => []
];

$sql = "SELECT username, faculty, university_email, telephone, user_type, campus FROM users";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row['user_type'] === 'student') {
            $users['students'][] = [
                "name" => $row['username'],
                "faculty" => $row['faculty'],
                "email" => $row['university_email'],
                "telephone" => $row['telephone'],
                "campus" => $row['campus'],
                "username" => $row['username'] // Add username for deletion
            ];
        } elseif ( $row['user_type'] === 'stuff') {
            $users['stuff'][] = [
                "name" => $row['username'],
                "faculty" => $row['faculty'],
                "email" => $row['university_email'],
                "telephone" => $row['telephone'],
                "campus" => $row['campus'],
                "username" => $row['username']
            ];
        }
    }
}
$conn->close();
?>
  <div class="container">
    <div class="sidebar">
      <h2>Categories</h2>
      <button class="menu-btn" data-group="students" style="font-family: 'Poppins', sans-serif;"><i class="fas fa-user-graduate" ></i> Students</button>
      <button class="menu-btn" data-group="stuff" style="font-family: 'Poppins', sans-serif; fontsize: 20px;"><i class="fas fa-users"></i> Stuff</button>
    </div>
    <div class="main-panel">
      <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search...">
        <button id="searchBtn"><i class="fas fa-search"></i></button>
      </div>
      <div class="table-container">
        <h2 id="groupHeading" style="margin-bottom: 10px; color: #742c2c;">Students</h2>
        <table id="userTable">
          <thead>
            <tr>
              <th>Name</th>
              <th>Faculty</th>
              <th>Campus</th>
              <th>Telephone</th>
              <th>E-mail</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <!-- JS will populate this -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- Floating Action Button -->
  <button class="fab" id="addMemberBtn" title="Add New Member"><i class="fas fa-plus"></i></button>

  <!-- Add Member Modal -->
  <div id="addMemberModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.35); z-index:2000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:10px; padding:28px 24px 18px 24px; min-width:300px; max-width:90vw; box-shadow:0 4px 16px rgba(0,0,0,0.18); position:relative;">
      <button id="closeAddMemberModal" style="position:absolute; top:10px; right:12px; background:none; border:none; font-size:20px; color:#888; cursor:pointer;"><i class="fas fa-times"></i></button>
      <h3 id="addMemberModalHeader" style="margin-top:0; margin-bottom:18px; color:#222;">Add New Student</h3>
      <form id="addMemberForm" autocomplete="off">
        <input type="hidden" name="user_type" id="userTypeInput" value="student">
        <div style="margin-bottom:12px;">
          <label style="display:block; margin-bottom:4px; font-size:14px;">Username</label>
          <input type="text" name="name" required style="width:90%; padding:7px 10px; border-radius:5px; border:1px solid #ccc; font-size:15px;">
        </div>
        <div style="margin-bottom:12px;">
          <label style="display:block; margin-bottom:4px; font-size:14px;">Telephone</label>
          <input type="text" name="telephone" required style="width:90%; padding:7px 10px; border-radius:5px; border:1px solid #ccc; font-size:15px;">
        </div>
        <div style="margin-bottom:12px;">
          <label style="display:block; margin-bottom:4px; font-size:14px;">Faculty</label>
          <input type="text" name="faculty" required style="width:90%; padding:7px 10px; border-radius:5px; border:1px solid #ccc; font-size:15px;">
        </div>
        <div style="margin-bottom:12px;">
          <label style="display:block; margin-bottom:4px; font-size:14px;">Campus</label>
          <input type="text" name="campus" required style="width:90%; padding:7px 10px; border-radius:5px; border:1px solid #ccc; font-size:15px;">
        </div>
        <div style="margin-bottom:18px;">
          <label style="display:block; margin-bottom:4px; font-size:14px;">University_e-mail</label>
          <input type="email" name="email" required style="width:90%; padding:7px 10px; border-radius:5px; border:1px solid #ccc; font-size:15px;">
        </div>
        <div style="margin-bottom:18px;">
          <label style="display:block; margin-bottom:4px; font-size:14px;">password</label>
          <input type="password" name="password" required style="width:90%; padding:7px 10px; border-radius:5px; border:1px solid #ccc; font-size:15px;">
        </div>
        <button type="submit" style="background:#742c2c; color:#fff; border:none; border-radius:5px; padding:9px 22px; font-size:15px; cursor:pointer;">Add Member</button>
      </form>
    </div>
  </div>
  <script>
    // --- JS: Users data from PHP ---
    const users = <?php echo json_encode($users); ?>;

    let currentGroup = "students";
    let filteredUsers = users[currentGroup];

    // Render table rows and update heading
    function renderTable(data) {
      // Update heading
      const heading = document.getElementById("groupHeading");
      heading.textContent = currentGroup === "students" ? "Students" : "Stuff";

      const tbody = document.querySelector("#userTable tbody");
      tbody.innerHTML = "";
      data.forEach((user, idx) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${user.name}</td>
          <td>${user.faculty}</td>
          <td>${user.campus || ""}</td>
          <td>${user.telephone}</td>
          <td>${user.email}</td>
          <td>
            <button class="toggle-btn delete" data-row="${idx}" data-username="${user.username}" title="Delete User">
              <i class="fas fa-trash-alt"></i>
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    // Switch group (Students/Stuff)
    document.querySelectorAll(".menu-btn").forEach(btn => {
      btn.addEventListener("click", function() {
        document.querySelectorAll(".menu-btn").forEach(b => b.classList.remove("active"));
        this.classList.add("active");
        currentGroup = this.getAttribute("data-group");
        filteredUsers = users[currentGroup];
        document.getElementById("searchInput").value = "";
        renderTable(filteredUsers);
      });
    });

    // Search functionality
    document.getElementById("searchInput").addEventListener("input", function() {
      const val = this.value.toLowerCase();
      filteredUsers = users[currentGroup].filter(u =>
        u.name.toLowerCase().includes(val) ||
        u.faculty.toLowerCase().includes(val) ||
        u.email.toLowerCase().includes(val)
      );
      renderTable(filteredUsers);
    });

    // Toggle button functionality (Delete user)
    document.querySelector("#userTable tbody").addEventListener("click", function(e) {
      if (e.target.closest(".toggle-btn.delete")) {
        const btn = e.target.closest(".toggle-btn.delete");
        const rowIdx = btn.getAttribute("data-row");
        const username = btn.getAttribute("data-username");
        if (!confirm("Are you sure you want to delete this user?")) return;
        btn.disabled = true;
        // AJAX request to delete user
        fetch(window.location.pathname, {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `delete_user=1&username=${encodeURIComponent(username)}&user_type=${encodeURIComponent(currentGroup === "students" ? "student" : "stuff")}`
        })
        .then(res => {
          if (!res.ok) throw new Error("Delete failed");
          return res.text();
        })
        .then(resp => {
          // Remove from users and filteredUsers arrays
          users[currentGroup] = users[currentGroup].filter(u => u.username !== username);
          filteredUsers = filteredUsers.filter(u => u.username !== username);
          renderTable(filteredUsers);
        })
        .catch(err => {
          alert("Failed to delete user.");
          btn.disabled = false;
        });
      }
    });

    // Floating Action Button functionality
    document.getElementById("addMemberBtn").addEventListener("click", function() {
      // Set modal header based on current group
      document.getElementById("addMemberModalHeader").textContent =
        currentGroup === "students" ? "Add New Student" : "Add New Staff";
      // Set user_type hidden input
      document.getElementById("userTypeInput").value = currentGroup === "students" ? "student" : "stuff";
      document.getElementById("addMemberModal").style.display = "flex";
    });

    // Close modal functionality
    document.getElementById("closeAddMemberModal").addEventListener("click", function() {
      document.getElementById("addMemberModal").style.display = "none";
    });

    // Optional: Close modal when clicking outside the modal content
    document.getElementById("addMemberModal").addEventListener("click", function(e) {
      if (e.target === this) {
      this.style.display = "none";
      }
    });

    // Handle Add Member form submission (AJAX)
    document.getElementById("addMemberForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);
      formData.append("add_user", "1");
      fetch(window.location.pathname, {
        method: "POST",
        body: formData
      })
      .then(res => {
        if (!res.ok) throw new Error("Add failed");
        return res.text();
      })
      .then(resp => {
        // Add new user to users array and re-render
        const newUser = {
          name: form.name.value,
          faculty: form.faculty.value,
          campus: form.campus.value,
          email: form.email.value,
          username: form.name.value
        };
        users[currentGroup].push(newUser);
        filteredUsers = users[currentGroup];
        renderTable(filteredUsers);
        document.getElementById("addMemberModal").style.display = "none";
        form.reset();
      })
      .catch(err => {
        alert("Failed to add user.");
      });
    });

    // Initial render
    renderTable(filteredUsers);
  </script>
</body>
</html>
