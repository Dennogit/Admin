<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Logins/login.php");
    exit;
}
require_once 'db.php';
$db = new mysqli('localhost', 'root', '', 'university_portal');
$notices = [];
if (!$db->connect_errno) {
    $res = $db->query("SELECT id, title, meta,  audience, image_path FROM notices ORDER BY id DESC LIMIT 50");
    while ($row = $res->fetch_assoc()) {
        $notices[] = $row;
    }
    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student E-Notice Board</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  /* Basic styling */
  body { margin:0; font-family:'Poppins',sans-serif; background:#f2f2f2; }
  .dashboard-container { display:flex; min-height:100vh; }
  .sidebar { background:#fff; padding:20px; width:220px; box-shadow:2px 0 6px rgba(0,0,0,0.1); }
  .sidebar .menu-btn { display:block;padding:10px;margin-bottom:8px;border:none;width:100%;text-align:left;border-radius:5px;cursor:pointer;background:#eee; }
  .sidebar .menu-btn.active { background:#742c2c;color:#fff; }
  .main-content { flex:1;padding:20px; position:relative; height:90vh; overflow: hidden; }
  .posts-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); gap:20px; overflow-y:auto; max-height:calc(100vh - 60px); }
  .post-card { background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05)}
  .post-card img { width:100%; height:150px; object-fit:cover; }
  .post-card-content { padding:12px; }
  .post-title { font-weight:600; margin-bottom:6px; }
  .post-meta { color:#777; margin-bottom:10px; }
  .details-link { display:inline-flex; align-items:center; gap:4px; color:#742c2c; font-weight:500; text-decoration:none; font-size:0.95rem; }
  .add-button { position:fixed; right:24px; bottom:24px; width:50px;height:50px; border-radius:50%; background:#742c2c;color:#fff;border:none;font-size:1.6rem;cursor:pointer;box-shadow:0 4px 16px rgba(0,0,0,0.2); }
  #addModal, #detailsModal { display:none; position:fixed; top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4); align-items:center; justify-content:center; z-index:1000; }
  .modal-content { background:#fff; padding:20px; border-radius:8px; width:90%; max-width:400px; position:relative; }
  .modal-close { position:absolute; top:10px; right:12px; background:none; border:none; font-size:1.2rem; cursor:pointer; color:#777; }
  /* Form styling */
  .add-form { display:flex; flex-direction:column; gap:12px; }
  .add-form input, .add-form textarea, .add-form select { width:100%; padding:8px 10px; border:1px solid #ccc; border-radius:4px; }
  .add-form button { padding:10px; background:linear-gradient(90deg,#742c2c 60%,#a85c5c 100%); color:#fff; border:none; border-radius:6px; font-size:1rem; font-weight:500; cursor:pointer; }

  /* Improved Modal Styling */
#addModal .modal-content {
  padding: 24px;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  background: #fff;
  animation: fadeIn 0.25s ease-in-out;
  font-family: 'Poppins', sans-serif;
}

#addModal h3 {
  margin-bottom: 20px;
  font-size: 1.4rem;
  color: #742c2c;
  text-align: center;
  border-bottom: 2px solid #eee;
  padding-bottom: 8px;
}

/* Form Fields */
.add-form input[type="text"],
.add-form textarea,
.add-form select,
.add-form input[type="file"] {
  width: 90%;
  padding: 10px 12px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 15px;
  background-color: #fafafa;
  transition: border-color 0.3s ease;
}

.add-form input:focus,
.add-form textarea:focus,
.add-form select:focus {
  border-color: #742c2c;
  outline: none;
}

/* File Input Appearance */
.add-form input[type="file"] {
  padding: 6px;
  font-size: 14px;
  background-color: #fff;
}

/* Submit Button */
.add-form button[type="submit"] {
  padding: 12px;
  background: linear-gradient(90deg, #742c2c, #a85c5c);
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.3s ease;
}

.add-form button[type="submit"]:hover {
  background: linear-gradient(90deg, #5d2222, #934a4a);
}

/* Close button in modal */
.modal-close {
  position: absolute;
  top: 12px;
  right: 16px;
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #999;
  transition: color 0.2s;
}

.modal-close:hover {
  color: #742c2c;
}

/* Responsive */
@media (max-width: 480px) {
  .modal-content {
    padding: 16px;
  }

  .add-form button[type="submit"] {
    font-size: 0.95rem;
  }
}

/* Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.96);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

</style>
</head>
<body>
<div class="dashboard-container">
  <div class="sidebar">
    <button class="menu-btn" data-menu="both">Both</button>
    <button class="menu-btn" data-menu="student">Students</button>
    <button class="menu-btn" data-menu="staff">Staff</button>
    
  </div>
  <div class="main-content">
    <h2>Notices</h2>
    <div class="posts-grid" id="postsGrid">
      <?php foreach($notices as $post): ?>
      
        <div class="post-card" data-audience="<?= htmlspecialchars($post['audience']) ?>" data-details="<?= htmlspecialchars($post['meta']) ?>" data-id="<?= htmlspecialchars($post['id']) ?>">

        <img src="<?php echo !empty($post['image_path']) ? htmlspecialchars($post['image_path']) : 'https://via.placeholder.com/400x140'; ?>">
        <div class="post-card-content">
          <div class="post-title"><?php echo htmlspecialchars($post['title']); ?></div>
          <div class="post-meta"><?php echo htmlspecialchars($post['meta']); ?></div>
          <a href="#" class="details-link">View details <i class="fas fa-arrow-right"></i></a>
        </div>

            <!-- 🗑 Delete icon -->
      <button class="delete-btn" title="Delete" style="float:right; background:none; border:none; color:#b00; cursor:pointer; font-size:1.2rem; margin_top: 10px;">
          <i class="fas fa-trash"></i>
        </button>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<button class="add-button" id="openAdd"><i class="fas fa-plus"></i></button>

<!-- Detail Modal -->
<div id="detailsModal"><div class="modal-content">
  <button class="modal-close" id="closeDetails">&times;</button>
  <h3 id="detailTitle"></h3>
  <p id="detailMeta" style="color:#777; margin-bottom:10px;"></p>
  <p id="detailDetails"></p>
</div></div>

<!-- Add-Notice Modal -->
<div id="addModal"><div class="modal-content">
  <button class="modal-close" id="closeAdd">&times;</button>
  <h3>Add New Notice</h3>
  <form class="add-form" id="addForm" method="post" action="ad_notice.php" enctype="multipart/form-data"> 
    <input type="text" id="newTitle" placeholder="Title" name="title" required >
    <input type="text" id="newMeta" placeholder="Meta" name="meta"required>
    <!-- <textarea id="newDetails" placeholder="Full details" name="details"required></textarea> -->
    <select id="newAudience" name="audience"required>
      <option value="Student">Students</option>
      <option value="Staff">Staff</option>
      <option value="Both">Both</option>
    </select>
    <input type="file" id="newImage" accept="image/*" name="image_path">
    <button type="submit">Submit Notice</button>
  </form>
</div></div>

<script>
    // Highlight "both" on page load
document.querySelector('.menu-btn[data-menu="both"]').click();

  // Filter logic
  // FILTER
const buttons = document.querySelectorAll('.menu-btn');
const cards = document.querySelectorAll('.post-card');

buttons.forEach(btn => {
  btn.addEventListener('click', () => {

    buttons.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const filter = btn.dataset.menu;

    cards.forEach(card => {

      const audience = card.dataset.audience;

      // FIXED LOGIC
      if (
          filter === 'both' || 
          audience === filter || 
          audience === 'both'
      ) {
          card.style.display = 'block';
      } else {
          card.style.display = 'none';
      }

    });

  });
});

  // Detail modal
  const detailModal = document.getElementById('detailsModal');
  document.querySelectorAll('.details-link').forEach((link,i) => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const card = link.closest('.post-card');
      document.getElementById('detailTitle').textContent = card.querySelector('.post-title').textContent;
      document.getElementById('detailMeta').textContent = card.querySelector('.post-meta').textContent;
      document.getElementById('detailDetails').textContent = card.dataset.details;

      detailModal.style.display = 'flex';
    });
  });
  document.getElementById('closeDetails').onclick = _=>detailModal.style.display='none';
  detailModal.addEventListener('click', e => { if(e.target===detailModal) detailModal.style.display='none'; });

  // Add modal
  const addModal = document.getElementById('addModal');
  document.getElementById('openAdd').onclick = _=>addModal.style.display='flex';
  document.getElementById('closeAdd').onclick = _=>addModal.style.display='none';
  addModal.addEventListener('click', e => { if(e.target===addModal) addModal.style.display='none'; });

  // Handle add form
  // document.getElementById('addForm').onsubmit = e => {
  //   e.preventDefault();
  //   const title = document.getElementById('newTitle').value;
  //   const meta = document.getElementById('newMeta').value;
  //   const details = document.getElementById('newDetails').value;
  //   const audience = document.getElementById('newAudience').value;
  //   const file = document.getElementById('newImage').files[0];
  //   const imgURL = file ? URL.createObjectURL(file) : 'https://via.placeholder.com/400x140';

  //   // Create new card
  //   const div = document.createElement('div');
  //   div.className = 'post-card';
  //   div.dataset.audience = audience;
  //   div.innerHTML = `
  //     <img src="${imgURL}">
  //     <div class="post-card-content">
  //       <div class="post-title">${title}</div>
  //       <div class="post-meta">${meta}</div>
  //       <a href="#" class="details-link">View details <i class="fas fa-arrow-right"></i></a>
  //     </div>
  //   `;
  //   document.getElementById('postsGrid').prepend(div);

  //   // Attach detail click
  //   div.querySelector('.details-link').onclick = e => {
  //     e.preventDefault();
  //     document.getElementById('detailTitle').textContent = title;
  //     document.getElementById('detailMeta').textContent = meta;
  //     document.getElementById('detailDetails').textContent = details;
  //     detailModal.style.display='flex';
  //   };

  //   addModal.style.display = 'none';
  //   e.target.reset();
  // };

  document.getElementById('addForm').onsubmit = async (e) => {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

  try {
    const response = await fetch('ad_notice.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();

    if (result.status === 'success') {
      // Reload page to get updated notice list
      location.reload();
    } else {
      alert(result.message || 'Failed to submit notice.');
    }
  } catch (err) {
    console.error(err);
    alert('An error occurred while submitting.');
  }
};


document.querySelectorAll('.delete-btn').forEach(button => {
  button.addEventListener('click', async (e) => {
    e.stopPropagation(); // Prevent triggering card click
    const card = e.target.closest('.post-card');
    const id = card.dataset.id;

    if (confirm("Are you sure you want to delete this notice?")) {
      try {
        const res = await fetch('delete_notice.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id })
        });

        const result = await res.json();
        if (result.status === 'success') {
          card.remove(); // Remove card from UI
        } else {
          alert(result.message || 'Failed to delete notice.');
        }
      } catch (err) {
        console.error(err);
        alert('An error occurred while deleting.');
      }
    }
  });
});


</script>
</body>
</html>
