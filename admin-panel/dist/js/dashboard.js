// Configuración del gráfico de ventas mensuales
const salesChartOptions = {
    series: [
      {
        name: "Ventas",
        data: [30, 40, 35, 50, 49, 60, 70], // Datos de ejemplo
      },
    ],
    chart: {
      type: "line",
      height: 350,
      toolbar: {
        show: false, // Ocultar la barra de herramientas del gráfico
      },
    },
    colors: ["#0d6efd"], // Color de la línea del gráfico
    xaxis: {
      categories: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul"], // Etiquetas del eje X
    },
    stroke: {
      curve: "smooth", // Hacer que la línea sea suave
    },
    tooltip: {
      enabled: true, // Habilitar tooltips
    },
  };
  
  // Renderizar el gráfico de ventas
  const salesChart = new ApexCharts(
    document.querySelector("#revenue-chart"),
    salesChartOptions
  );
  salesChart.render();
  
  // Configuración del gráfico de reclamos por vendedor
  const reclamosChartOptions = {
    series: [
      {
        name: "Reclamos",
        data: [10, 15, 8, 12, 20, 18, 25], // Datos de ejemplo
      },
    ],
    chart: {
      type: "bar", // Tipo de gráfico de barras
      height: 350,
      toolbar: {
        show: false, // Ocultar la barra de herramientas del gráfico
      },
    },
    colors: ["#dc3545"], // Color de las barras
    xaxis: {
      categories: ["Vendedor 1", "Vendedor 2", "Vendedor 3", "Vendedor 4", "Vendedor 5"], // Etiquetas del eje X
    },
    plotOptions: {
      bar: {
        horizontal: false, // Barras verticales
        columnWidth: "55%", // Ancho de las barras
      },
    },
    tooltip: {
      enabled: true, // Habilitar tooltips
    },
  };
  
  // Renderizar el gráfico de reclamos
  const reclamosChart = new ApexCharts(
    document.querySelector("#reclamos-chart"),
    reclamosChartOptions
  );
  reclamosChart.render();
  
  // Eventos para las tarjetas de resumen
  document.querySelectorAll(".small-box").forEach((card) => {
    card.addEventListener("click", () => {
      const link = card.querySelector("a").getAttribute("href");
      if (link) {
        window.location.href = link; // Redirigir al hacer clic en la tarjeta
      }
    });
  });