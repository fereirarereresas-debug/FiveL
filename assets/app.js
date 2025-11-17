// Troca de abas login/registro
const tabButtons = document.querySelectorAll(".tab-btn");
const forms = document.querySelectorAll(".form");

tabButtons.forEach((btn) => {
  btn.addEventListener("click", () => {
    const tab = btn.getAttribute("data-tab");

    tabButtons.forEach((b) => b.classList.remove("active"));
    btn.classList.add("active");

    forms.forEach((f) => f.classList.remove("active"));
    const form = document.getElementById(`form-${tab}`);
    if (form) form.classList.add("active");
  });
});

// Efeito de clique nos botões "Iniciar Spoofer"
function runSpoofer() {
  // Aqui você pode chamar sua lógica real (download, abrir loader, etc.)
  alert("Iniciando Five Spoofer...\n(Conectado ao KeyAuth)");
}

// Pequeno efeito 3D ao passar o mouse nos cards
document.querySelectorAll(".product-card").forEach((card) => {
  card.addEventListener("mousemove", (e) => {
    const rect = card.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width - 0.5) * 6;
    const y = ((e.clientY - rect.top) / rect.height - 0.5) * 6;
    card.style.transform = `translateY(-3px) rotateX(${-y}deg) rotateY(${x}deg)`;
  });

  card.addEventListener("mouseleave", () => {
    card.style.transform = "translateY(0) rotateX(0) rotateY(0)";
  });
});