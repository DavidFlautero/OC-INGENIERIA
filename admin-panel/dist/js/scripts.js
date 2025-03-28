// scripts.js - Scripts globales para el panel de administración

console.log("Scripts cargados correctamente.");

// Función para inicializar plugins o eventos globales
function initGlobalScripts() {
  console.log("Inicializando scripts globales...");

  // Ejemplo: Inicializar tooltips de Bootstrap
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );

  // Ejemplo: Manejar eventos globales
  document.querySelectorAll(".small-box").forEach((card) => {
    card.addEventListener("click", () => {
      const link = card.querySelector("a").getAttribute("href");
      if (link) {
        window.location.href = link; // Redirigir al hacer clic en la tarjeta
      }
    });
  });
}

// Llamar a la función de inicialización cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", initGlobalScripts);