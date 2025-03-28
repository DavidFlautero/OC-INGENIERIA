// Funciones específicas para la gestión de productos
document.addEventListener('DOMContentLoaded', function() {
  // Filtro por estado
  document.getElementById('filter-status')?.addEventListener('change', function() {
    const status = this.value;
    document.querySelectorAll('tbody tr').forEach(row => {
      row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
    });
  });

  // Confirmar eliminación
  window.confirmDelete = function(id) {
    if (confirm('¿Eliminar producto?')) {
      fetch(`../../controllers/ProductoController.php?action=eliminarProducto&id=${id}`, {
        method: 'DELETE'
      })
      .then(response => {
        if (response.ok) {
          document.querySelector(`tr[data-id="${id}"]`)?.remove();
          setTimeout(() => location.reload(), 1000); 
        } else {
          alert('Error al eliminar');
        }
      });
    }
  };
});