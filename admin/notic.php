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
    $res = $db->query("SELECT id, title, meta, audience, image_path FROM notices ORDER BY id DESC LIMIT 50");
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
body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:#f2f2f2;
    height:100vh;
    overflow:hidden; /* prevents full page scroll */
}

/* Layout */
.dashboard-container{
    display:flex;
    height:100vh; /* lock full screen */
}

/* Sidebar */
.sidebar{
    width:220px;
    background:#fff;
    padding:20px;
    box-shadow:2px 0 6px rgba(0,0,0,0.08);
}

.menu-btn{
    display:block;
    width:100%;
    padding:10px;
    margin-bottom:8px;
    border:none;
    background:#eee;
    border-radius:6px;
    cursor:pointer;
    text-align:left;
}

.menu-btn.active{
    background:#742c2c;
    color:#fff;
}

/* Main */
.main-content{
    flex:1;
    padding:20px;
    display:flex;
    flex-direction:column;
    overflow:auto; 
  }

/* Grid */
.posts-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:18px;
    margin-top:15px;
}

/* Card */
.post-card{
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    position:relative;
}

.post-card img{
    width:100%;
    height:180px;
    object-fit:cover;
    display:block;
}

.post-card-content{
    padding:14px;
}

.post-title{
    font-weight:600;
    margin-bottom:6px;
}

.post-meta{
    color:#777;
    font-size:0.9rem;
    margin-bottom:8px;
}

.details-link{
    text-decoration:none;
    color:#742c2c;
    font-weight:500;
    font-size:0.9rem;
}

/* Delete button */
.delete-btn{
    position:absolute;
    top:10px;
    right:10px;
    background:rgba(255,255,255,0.9);
    border:none;
    border-radius:50%;
    padding:6px 8px;
    cursor:pointer;
    color:#b00000;
}

/* Add Button */
.add-button{
    position:fixed;
    right:20px;
    bottom:20px;
    width:55px;
    height:55px;
    border-radius:50%;
    background:#742c2c;
    color:#fff;
    border:none;
    font-size:1.6rem;
    cursor:pointer;
    box-shadow:0 4px 16px rgba(0,0,0,0.25);
    z-index:1000;
}

/* Modals */
#addModal,#detailsModal{
    display:none;
    position:fixed;
    top:0;left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
    justify-content:center;
    align-items:center;
    z-index:1000;
}

.modal-content{
    background:#fff;
    padding:22px;
    border-radius:12px;
    width:95%;
    max-width:420px;
    position:relative;
}

.modal-close{
    position:absolute;
    top:10px;
    right:15px;
    border:none;
    background:none;
    font-size:1.4rem;
    cursor:pointer;
}

.add-form{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.add-form input,
.add-form select{
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
}

.add-form button{
    padding:11px;
    border:none;
    border-radius:8px;
    background:#742c2c;
    color:#fff;
    font-weight:600;
    cursor:pointer;
}

/* Responsive */
@media(max-width:768px){
    .dashboard-container{
        flex-direction:column;
    }

    .sidebar{
        width:100%;
        display:flex;
        justify-content:space-around;
        box-shadow:none;
        border-bottom:1px solid #ddd;
    }

    .menu-btn{
        margin:0 5px;
        text-align:center;
        flex:1;
    }
}
/* Glassy sticky title */
.main-title {
    position: sticky;       /* sticks to the top of scroll container */
    top: 0;                 /* offset from top */
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

<div class="dashboard-container">

<div class="sidebar">
    <button class="menu-btn active" data-menu="Both">Both</button>
    <button class="menu-btn" data-menu="Students">Students</button>
    <button class="menu-btn" data-menu="Stuff">Stuff</button>
    
</div>

<div class="main-content">
<h2 class="main-title">Notices</h2>

<div>
  <div class="posts-grid" id="postsGrid">

    <?php foreach($notices as $post): ?>
    <div class="post-card"
        data-id="<?= htmlspecialchars($post['id']) ?>"
        data-audience="<?= htmlspecialchars($post['audience']) ?>"
        data-details="<?= htmlspecialchars($post['meta']) ?>">
        <!-- <?php echo $post['image_path']; ?> -->

        <?php
        $image = !empty($post['image_path']) ? $post['image_path'] : '';
        $fullPath = './' . $image;
        ?>

        <img src="<?= !empty($image) && file_exists($fullPath) 
        ? $fullPath 
        : 'https://via.placeholder.com/400x180'; ?>" 
        alt="Notice Image">

        <div class="post-card-content">
          <div class="post-title"><?= htmlspecialchars($post['title']); ?></div>
          <div class="post-meta"><?= htmlspecialchars($post['meta']); ?></div>
          <a href="#" class="details-link">View details <i class="fas fa-arrow-right"></i></a>
        </div>

        <button class="delete-btn"><i class="fas fa-trash"></i></button>
        </div>
          <?php endforeach; ?>

        </div>
      </div>
  </div>
</div>

<button class="add-button" id="openAdd"><i class="fas fa-plus"></i></button>

<!-- Details Modal -->
<div id="detailsModal">
<div class="modal-content">
<button class="modal-close" id="closeDetails">&times;</button>
<h3 id="detailTitle"></h3>
<p id="detailMeta" style="color:#777;"></p>
</div>
</div>

<!-- Add Modal -->
<div id="addModal">
<div class="modal-content">
<button class="modal-close" id="closeAdd">&times;</button>
<h3>Add New Notice</h3>

<form class="add-form" id="addForm" enctype="multipart/form-data">
<input type="text" name="title" placeholder="Title" required>
<input type="text" name="meta" placeholder="Meta" required>

<select name="audience" required>
<option value="Students">Students</option>
<option value="Stuff">Stuff</option>
<option value="Both">Both</option>
</select>

<input type="file" name="image" accept="image/*">
<button type="submit">Submit Notice</button>
</form>

</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

const buttons = document.querySelectorAll('.menu-btn');
const cards = document.querySelectorAll('.post-card');
const detailModal=document.getElementById('detailsModal');
const addModal=document.getElementById('addModal');

// FILTER
buttons.forEach(btn=>{
btn.addEventListener('click',()=>{
buttons.forEach(b=>b.classList.remove('active'));
btn.classList.add('active');

const filter=btn.dataset.menu;

cards.forEach(card=>{
const audience = card.dataset.audience;

if(filter === 'Both'){
    card.style.display = 'block';
} 
else if(filter === 'Students'){
    card.style.display =
        (audience === 'Students' || audience === 'Both')
        ? 'block' : 'none';
}
else if(filter === 'Stuff'){
    card.style.display =
        (audience === 'Stuff' || audience === 'Both')
        ? 'block' : 'none';
}
});
});
});

// DETAILS MODAL
document.querySelectorAll('.details-link').forEach(link=>{
link.onclick=e=>{
e.preventDefault();
const card=link.closest('.post-card');
document.getElementById('detailTitle').textContent=
card.querySelector('.post-title').textContent;
document.getElementById('detailMeta').textContent=
card.querySelector('.post-meta').textContent;
detailModal.style.display='flex';
};
});

document.getElementById('closeDetails').onclick=
()=>detailModal.style.display='none';

detailModal.onclick=e=>{
if(e.target===detailModal)
detailModal.style.display='none';
};

// ADD MODAL
document.getElementById('openAdd').onclick=
()=>addModal.style.display='flex';

document.getElementById('closeAdd').onclick=
()=>addModal.style.display='none';

addModal.onclick=e=>{
if(e.target===addModal)
addModal.style.display='none';
};

// SUBMIT
document.getElementById('addForm').onsubmit=async e=>{
e.preventDefault();
const formData=new FormData(e.target);

const res=await fetch('ad_notice.php',{
method:'POST',
body:formData
});

const result=await res.json();
if(result.status==='success') location.reload();
else alert(result.message||'Error submitting.');
};

// DELETE
document.querySelectorAll('.delete-btn').forEach(btn=>{
btn.onclick=async ()=>{
const card=btn.closest('.post-card');
const id=card.dataset.id;

if(confirm('Delete this notice?')){
const res=await fetch('delete_notice.php',{
method:'POST',
headers:{'Content-Type':'application/json'},
body:JSON.stringify({id:id})
});
const result=await res.json();
if(result.status==='success') card.remove();
else alert(result.message||'Delete failed.');
}
};
});

});
</script>

</body>
</html>