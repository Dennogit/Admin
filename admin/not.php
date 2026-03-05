<?php
// just to show recent‑posts number in the bell badge:
$recentCount = 3;   // calculate this in real life
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF‑8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Notice Board</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="not.css">
</head>
<body>
<div class="wrapper">

    <!--‑‑‑  SIDEBAR  ‑‑‑-->
    <aside class="sidebar">
        <h2>Categories</h2>
        <button class="cat-btn active" data-cat="students">Students</button>
        <button class="cat-btn" data-cat="staff">Staff</button>
    </aside>

    <!--‑‑‑  MAIN  ‑‑‑-->
    <main class="main">

        <!-- top‑bar -->
        <header class="topbar">
            <div class="search">
                <input id="searchInput" type="text" placeholder="Search notices">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>

            <button id="bellBtn" class="bell" title="Recent posts">
                <i class="fa-regular fa-bell"></i>
                <span class="badge"><?= $recentCount ?></span>
            </button>
        </header>

        <h1 id="panelTitle">Students</h1>

        <!-- floating add button (moved outside header for fixed positioning) -->
        <button id="addBtn" class="fab" title="Add notice">
            <i class="fa-solid fa-plus"></i>
        </button>

        <!-- notice grid -->
        <section class="card-wrapper" id="cardWrapper" style="width: 800px; height: 100vh;">
            <!-- cards are injected by JS -->
        </section>

        

        
    </main>
    
</div>

<!--‑‑‑  MODALS  ‑‑‑-->

<!-- Add‑notice modal -->
<div class="modal" id="addModal">
    <div class="modal-content">
        <h2>Add notice</h2>
        <form id="addForm">
            <input required name="title" placeholder="Title">
            <textarea required name="body" placeholder="Description"></textarea>
            <select name="category">
                <option value="students">Students</option>
                <option value="staff">Staff</option>
            </select>
            <input name="image" type="url" placeholder="Image URL">
            <div class="modal-actions">
                <button type="button" class="btn-secondary" data-close>Add</button>
                <button type="submit" class="btn-primary">Post</button>
            </div>
        </form>
    </div>
</div>

<!-- Details / comments modal -->
<div class="modal" id="detailModal">
    <div class="modal-content" id="detailContent">
        <!-- filled by JS -->
    </div>
</div>

<!-- Recent‑posts slide‑in -->
<div class="drawer" id="recentDrawer">
    <h3>Recent posts</h3>
    <ul id="recentList"></ul>
</div>

<script src="app.js"></script>

<!-- Add this style block before </body> to position the button -->
<style>
#addBtn.fab {
    position: fixed;
    right: 32px;
    bottom: 32px;
    z-index: 1000;
    border-radius: 50%;
    width: 56px;
    height: 56px;
    background: #007bff;
    color: #fff;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    cursor: pointer;
    transition: background 0.2s;
}
#addBtn.fab:hover {
    background: #0056b3;
}
</style>
</body>
</html>
