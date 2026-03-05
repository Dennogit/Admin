<?php
/* ------------------------------------------------------------------
   Tiny demo “back‑end” – in real life you’d query MySQL instead.
   ------------------------------------------------------------------ */
$recentCount = 4;                  // badge on the bell
$notices = [
  [
    "id"=>1,
    "title"=>"Registrar",
    "subtitle"=>"Admissions",
    "image"=>"https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=480&q=80",
    "category"=>"students"
  ],
  [
    "id"=>2,
    "title"=>"Sports tutor",
    "subtitle"=>"League match",
    "image"=>"https://images.unsplash.com/photo-1604589090098-3e77c6cf0a6c?auto=format&fit=crop&w=480&q=80",
    "category"=>"students"
  ],
  [
    "id"=>3,
    "title"=>"Campus Grill",
    "subtitle"=>"BBQ Friday",
    "image"=>"https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=480&q=80",
    "category"=>"students"
  ],
  [
    "id"=>4,
    "title"=>"New staff IDs",
    "subtitle"=>"Collect at HR",
    "image"=>"https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=480&q=80",
    "category"=>"staff"
  ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notice Board</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!--  Google Poppins  -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

  <!--  Font Awesome (icons)  -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* ---------- Variables ---------- */
    :root{
      --blue:#2688ff;
      --grey:#e6e6e6;
      --bg:#c9f3f6;
      --sidebar-width:230px;
    }
    /* ---------- Reset & base ---------- */
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif}
    body{background:var(--bg);color:#333;min-height:100vh}
    img{display:block;width:100%}
    button{cursor:pointer}
    a{text-decoration:none;color:inherit}

    /* ---------- Layout ---------- */
    .wrapper{display:flex;gap:18px;padding:18px}
    aside.sidebar{
      width:var(--sidebar-width);min-width:200px;
      background:#fff;border-radius:12px;padding:26px 18px;
      box-shadow:0 3px 10px rgba(0,0,0,.05)
    }
    main{flex:1;position:relative}

    /* ---------- Sidebar ---------- */
    .sidebar h2{font-size:1.3rem;font-weight:700;margin-bottom:30px}
    .cat-btn{
      width:100%;padding:10px 12px;margin-bottom:14px;border:none;
      border-radius:6px;background:var(--grey);transition:.25s;font-size:16px
    }
    .cat-btn.active,.cat-btn:hover{background:var(--blue);color:#fff}

    /* ---------- Top bar ---------- */
    .top-bar{display:flex;gap:14px;align-items:center;margin-bottom:10px}
    .search-box{position:relative;flex:1;max-width:450px}
    .search-box input{
      width:100%;padding:11px 44px 11px 16px;border:none;border-radius:22px;
      background:var(--grey);outline:none
    }
    .search-box i{
      position:absolute;right:16px;top:50%;transform:translateY(-50%);color:#555
    }
    .bell{font-size:1.5rem;border:none;background:none;position:relative}
    .badge{
      position:absolute;top:-6px;right:-6px;background:#ff4d4f;
      color:#fff;border-radius:50%;font-size:.7rem;padding:2px 6px
    }

    /* ---------- Headings ---------- */
    h1.panel-title{font-size:1.6rem;font-weight:700;margin:6px 0 12px}
    .sec-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
    .sec-header h3{font-size:1.1rem;font-weight:600}

    /* ---------- Card grid ---------- */
    .cards{
      background:#ccc;border-radius:12px;padding:16px;
      display:grid;gap:16px;grid-template-columns:repeat(auto-fill,minmax(180px,1fr))
    }
    .card{
      background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,.06);transition:.25s
    }
    .card:hover{transform:translateY(-4px)}
    .card-info{padding:10px}
    .card-info h4{font-size:15px;font-weight:600}
    .card-info small{font-size:13px;color:#777}
    .card-info .view{display:block;margin-top:6px;font-size:12px;color:var(--blue);font-weight:500}

    /* ---------- Floating + button ---------- */
    .fab{
      position:absolute;right:26px;bottom:26px;width:52px;height:52px;
      border:none;border-radius:50%;background:var(--blue);color:#fff;
      font-size:1.7rem;display:flex;justify-content:center;align-items:center;
      box-shadow:0 4px 12px rgba(0,0,0,.18);transition:.3s
    }
    .fab:hover{transform:rotate(90deg)}

    /* ---------- Responsive ---------- */
    @media (max-width:800px){
      .wrapper{flex-direction:column}
      aside.sidebar{width:100%;display:flex;overflow:auto;gap:10px}
      .sidebar h2{display:none}
      .cat-btn{flex:1;white-space:nowrap;margin:0}
      main{padding-top:6px}
    }
  </style>
</head>
<body>
<div class="wrapper">

  <!-- ---------- Sidebar ---------- -->
  <aside class="sidebar">
    <h2>Categories</h2>
    <button class="cat-btn active" data-cat="students">Students</button>
    <button class="cat-btn" data-cat="staff">Staff</button>
  </aside>

  <!-- ---------- Main ---------- -->
  <main>
    <!-- Top bar -->
    <div class="top-bar">
      <div class="search-box">
        <input type="text" placeholder="Search notices" id="searchInput">
        <i class="fas fa-search"></i>
      </div>
      <button class="bell" id="bellBtn" title="Recent posts">
        <i class="far fa-bell"></i>
        <span class="badge"><?= $recentCount ?></span>
      </button>
    </div>

    <!-- Panel title -->
    <h1 class="panel-title" id="panelTitle">Students</h1>

    <!-- Section header -->
    <div class="sec-header">
      <h3>Recent posts</h3>
      <a href="#" class="view-all">View all <i class="fas fa-arrow-right"></i></a>
    </div>

    <!-- Card grid -->
    <div class="cards" id="cardWrap"></div>

    <!-- Floating + -->
    <button class="fab" id="addBtn"><i class="fas fa-plus"></i></button>
  </main>
</div>

<!-- ---------------- JavaScript ---------------- -->
<script>
  /* ---------- Data from PHP ---------- */
  const ALL_NOTICES = <?php echo json_encode($notices, JSON_HEX_TAG); ?>;
  let currentCat = 'students';

  /* ---------- DOM helpers ---------- */
  const $ = sel => document.querySelector(sel);
  const $$ = sel => [...document.querySelectorAll(sel)];

  /* ---------- Render cards ---------- */
  function renderCards(filter=''){
    const wrap = $('#cardWrap');
    wrap.innerHTML = '';

    ALL_NOTICES
      .filter(n => n.category === currentCat &&
                   (n.title.toLowerCase().includes(filter) || n.subtitle.toLowerCase().includes(filter)))
      .forEach(n=>{
        const card = document.createElement('div');
        card.className = 'card';
        card.innerHTML = `
          <img src="${n.image}" alt="${n.title}">
          <div class="card-info">
            <h4>${n.title}</h4>
            <small>${n.subtitle}</small>
            <span class="view">View details</span>
          </div>`;
        card.onclick = ()=>alert('Details for: '+n.title); // placeholder
        wrap.appendChild(card);
      });
  }

  /* ---------- Category buttons ---------- */
  $$('.cat-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{
      $$('.cat-btn').forEach(b=>b.classList.remove('active'));
      btn.classList.add('active');
      currentCat = btn.dataset.cat;
      $('#panelTitle').textContent = btn.textContent;
      renderCards($('#searchInput').value.toLowerCase());
    });
  });

  /* ---------- Search filter ---------- */
  $('#searchInput').addEventListener('input',e=>{
    renderCards(e.target.value.toLowerCase());
  });

  /* ---------- Floating add ---------- */
  $('#addBtn').onclick = ()=>alert('Add new notice (hook your modal here)');

  /* ---------- Bell ---------- */
  $('#bellBtn').onclick = ()=>alert('Show recent posts');

  /* ---------- Initial view ---------- */
  renderCards();
</script>
</body>
</html>
