// Configuración de gráficos
document.addEventListener("DOMContentLoaded", function () {
  const salesChartOptions = {
      series: [
          {
              name: "Ventas",
              data: [30, 40, 35, 50, 49, 60, 70],
          },
      ],
      chart: {
          type: "line",
          height: 350,
      },
      xaxis: {
          categories: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul"],
      },
  };

  const salesChart = new ApexCharts(
      document.querySelector("#revenue-chart"),
      salesChartOptions
  );
  salesChart.render();
});

// Función para eliminar un producto
function confirmDelete(productId) {
  if (confirm('¿Estás seguro de eliminar este producto?')) {
      fetch(`/onlinetienda/controllers/ProductoController.php?action=eliminarProducto&id=${productId}`)
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  location.reload(); // Recargar la página para ver los cambios
              } else {
                  alert(data.error || 'Error al eliminar el producto.');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('Error al conectar con el servidor.');
          });
  }
}

// Función para habilitar/deshabilitar la edición en línea
function toggleEdit(button) {
  const productId = button.getAttribute('data-id');
  const row = document.getElementById(`product-${productId}`);
  const isEditing = button.innerHTML.includes('Guardar');

  if (isEditing) {
      saveChanges(productId, row, button);
  } else {
      enableEditing(row, button);
  }
}

// Función para habilitar la edición
function enableEditing(row, button) {
  button.innerHTML = '<i class="fas fa-save"></i> Guardar';
  row.querySelectorAll('.editable').forEach(cell => {
      const field = cell.getAttribute('data-field');
      const value = cell.innerText;
      const input = document.createElement('input');
      input.type = 'text';
      input.value = value;
      input.className = 'form-control';
      cell.innerHTML = '';
      cell.appendChild(input);
  });
}

// Función para guardar los cambios
function saveChanges(productId, row, button) {
  const updates = {};

  row.querySelectorAll('.editable').forEach(cell => {
      const field = cell.getAttribute('data-field');
      const input = cell.querySelector('input');
      updates[field] = input.value;
  });

  fetch(`/onlinetienda/controllers/ProductoController.php?action=actualizarProducto`, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify({
          id_producto: productId,
          updates: updates,
      }),
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          disableEditing(row, button);
      } else {
          alert(data.error || 'Error al actualizar el producto.');
      }
  })
  .catch(error => {
      console.error('Error:', error);
      alert('Error al conectar con el servidor.');
  });
}

// Función para deshabilitar la edición
function disableEditing(row, button) {
  button.innerHTML = '<i class="fas fa-edit"></i> Editar';
  row.querySelectorAll('.editable').forEach(cell => {
      const input = cell.querySelector('input');
      cell.innerHTML = input.value;
  });
}