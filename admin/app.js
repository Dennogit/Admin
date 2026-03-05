/* MOCK data ------------------------------------------------------------ */
let notices = [
  {id:1, title:"Registrar Admissions", body:"March into your future with UMU…", category:"students", image:"https://i.imgur.com/LbQ2Gta.png"},
  {id:2, title:"League match", body:"Inter‑faculty football league, be there!", category:"students", image:"https://i.imgur.com/zG5SMTn.jpg"},
  {id:3, title:"Barbecue Friday", body:"Don’t miss our weekly nyama‑choma.", category:"students", image:"https://i.imgur.com/ZB200Hu.jpg"},
  {id:4, title:"New Staff IDs", body:"Collect your new cards at HR.", category:"staff", image:"https://i.imgur.com/ev90TxK.jpg"}
];

let currentCat = "students";

/* Helpers -------------------------------------------------------------- */
const qs = s => document.querySelector(s);
const qsa = s => [...document.querySelectorAll(s)];

function renderCards(){
  const wrapper = qs("#cardWrapper");
  wrapper.innerHTML = "";
  notices
    .filter(n => n.category === currentCat)
    .forEach(n=>{
      const div = document.createElement("div");
      div.className = "card";
      div.innerHTML = `
        <img src="${n.image}" alt="${n.title}">
        <div class="caption">
            <strong>${n.title}</strong>
            <p>${n.body.slice(0,60)}…</p>
        </div>`;
      div.onclick = ()=> openDetail(n);
      wrapper.appendChild(div);
    });
}

/* Category buttons ----------------------------------------------------- */
qsa(".cat-btn").forEach(btn=>{
  btn.onclick = ()=>{
    qsa(".cat-btn").forEach(b=>b.classList.remove("active"));
    btn.classList.add("active");
    currentCat = btn.dataset.cat;
    qs("#panelTitle").textContent = btn.textContent;
    renderCards();
  };
});

/* Search (client‑side filter) ------------------------------------------ */
qs("#searchInput").addEventListener("input", e=>{
  const q = e.target.value.toLowerCase();
  qsa(".card").forEach(card=>{
    const title = card.querySelector("strong").textContent.toLowerCase();
    card.style.display = title.includes(q) ? "" : "none";
  });
});

/* Add notice ----------------------------------------------------------- */
const addModal = qs("#addModal");
qs("#addBtn").onclick = ()=> addModal.classList.add("active");
addModal.addEventListener("click", e=>{
  if(e.target.dataset.close || e.target===addModal) addModal.classList.remove("active");
});

qs("#addForm").onsubmit = async ev=>{
  ev.preventDefault();
  const fd = new FormData(ev.target);
  const newNotice = Object.fromEntries(fd.entries());
  newNotice.id = Date.now();
  notices.unshift(newNotice);            // store locally
  renderCards();
  ev.target.reset();
  addModal.classList.remove("active");

  /* To persist on a server, uncomment:
  await fetch('api/add.php',{method:'POST',body:fd});
  */
};

/* Detail modal --------------------------------------------------------- */
const detailModal = qs("#detailModal");
function openDetail(n){
  const wrap = qs("#detailContent");
  wrap.innerHTML = `
    <img src="${n.image}" style="width:100%;border-radius:10px;margin-bottom:12px">
    <h2>${n.title}</h2>
    <p style="margin:10px 0 18px;">${n.body}</p>
    <h4>Comments</h4>
    <p style="color:#999;font-size:.85rem;">(hook your comment system here)</p>
    <div class="modal-actions">
        <button class="btn-secondary" data-close>Close</button>
    </div>`;
  detailModal.classList.add("active");
}
detailModal.onclick = e=>{
  if(e.target.dataset.close || e.target===detailModal) detailModal.classList.remove("active");
};

/* Recent posts drawer -------------------------------------------------- */
const drawer = qs("#recentDrawer");
qs("#bellBtn").onclick = ()=> drawer.classList.toggle("open");
function fillDrawer(){
  const list = qs("#recentList");
  list.innerHTML = notices.slice(0,5).map(n=>`<li>${n.title}</li>`).join("");
}
fillDrawer();

/* Initialise view ------------------------------------------------------ */
renderCards();
