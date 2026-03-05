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
  <title>Admin Settings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', Arial, sans-serif;
      background-color: #f0f0f0;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      width: 100%;
    }

    .settings-section {
      margin-bottom: 30px;
    }

    .settings-section h2 {
      font-size: 22px;
      border-bottom: 2px solid #ccc;
      padding-bottom: 5px;
    }

    .settings-section label {
      display: block;
      margin-top: 15px;
    }

    .settings-section input, .settings-section select {
      width: 90%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-family: 'Poppins', Arial, sans-serif;
    }

    .btn {
      display: inline-block;
      background-color: #742c2c;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 20px;
      text-decoration: none;
      font-family: 'Poppins', Arial, sans-serif;
    }

    .btn:hover {
      background-color: #5a1f1f;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-cogs"></i> Admin Settings</h1>

    <div class="settings-section">
      <h2>Profile Management</h2>
      <div style="display: flex; align-items: center; margin-top: 15px; margin-bottom: 15px;">
        <img src="https://via.placeholder.com/80" alt="Profile Photo" style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:2px solid #ccc; margin-right:20px;">
        <label style="margin:0;">
          <input type="file" accept="image/*" style="display:none;" id="profilePhotoInput" onchange="previewProfilePhoto(event)">
          <button type="button" class="btn" onclick="document.getElementById('profilePhotoInput').click();">
        <i class="fas fa-edit"></i> Edit Photo
          </button>
        </label>
      </div> 
     <script>
        function previewProfilePhoto(event) {
          const input = event.target;
          if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.querySelector('img[alt="Profile Photo"]').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
          }
        }
      </script>
      <label>Username
        <input type="text" placeholder="Admin Username" />
      </label>
      <label>Email <br>
        <input type="email" placeholder="Admin Email" />
      </label>
      <label>Telephone <br>
        <input type="text" placeholder="phone number" />
      </label>
      <label>Password <br>
        <input type="password" placeholder="New Password" />
      </label>
    </div>

    <div class="settings-section">
      <h2>Notification Settings</h2>
      <label>Email Notifications
        <select>
          <option>Enabled</option>
          <option>Disabled</option>
        </select>
      </label>
      <label>SMS Notifications
        <select>
          <option>Enabled</option>
          <option>Disabled</option>
        </select>
      </label>
    </div>

    <div class="settings-section">
      <h2>Security Settings</h2>
      <label>Two-Factor Authentication
        <select>
          <option>Enabled</option>
          <option>Disabled</option>
        </select>
      </label>
      <label>Auto Logout (minutes)
        <input type="number" placeholder="15" />
      </label>
    </div>
    <button class="btn" onclick="saveSettings()">Save Settings</button>

    <script>
      function saveSettings() {
        alert('Settings have been saved!');
      }
    </script>
  </div>
</body>
</html>
