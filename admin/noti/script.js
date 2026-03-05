const notices = [
  {
    id: 1,
    title: "Registrar",
    subtitle: "Admissions",
    image: "images/admissions.jpg",
    category: "students"
  },
  {
    id: 2,
    title: "Sports tutor",
    subtitle: "League match",
    image: "images/football.jpg",
    category: "students"
  },
  {
    id: 3,
    title: "Grill Corner",
    subtitle: "BBQ Time",
    image: "images/bbq.jpg",
    category: "students"
  },
  {
    id: 4,
    title: "UMU Activities",
    subtitle: "Open Day",
    image: "images/open-day.jpg",
    category: "staff"
  }
];

const container = document.getElementById("noticesContainer");
const buttons = document.querySelectorAll(".category-btn");
const searchInput = document.getElementById("searchInput");

let currentCategory = "students";

function renderCards(filter = "") {
  container.innerHTML = "";
  const filtered = notices.filter(n => 
    n.category === currentCategory &&
    (n.title.toLowerCase().includes(filter) || n.subtitle.toLowerCase().includes(filter))
  );

  filtered.forEach(n => {
    const card = document.createElement("div");
    card.className = "card";
    card.innerHTML = `
      <img src="${n.image}" alt="${n.title}" />
      <div class="card-info">
        <h4>${n.title}</h4>
        <small>${n.subtitle}</small>
        <span class="view-details">View details</span>
      </div>
    `;
    container.appendChild(card);
  });
}

buttons.forEach(btn => {
  btn.addEventListener("click", () => {
    buttons.forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    currentCategory = btn.dataset.category;
    renderCards(searchInput.value.toLowerCase());
  });
});

searchInput.addEventListener("input", () => {
  renderCards(searchInput.value.toLowerCase());
});

renderCards();
