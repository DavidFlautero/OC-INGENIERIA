// searchcar.js
$(document).ready(function () {
    // Búsqueda en vivo
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    if (searchInput && searchResults) {
        searchInput.addEventListener('input', function () {
            const query = searchInput.value.trim();

            if (query.length > 0) {
                // Realizar la solicitud AJAX
                fetch(`http://localhost/onlinetienda/config/search.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            // Mostrar los resultados con imágenes
                            searchResults.innerHTML = data.map(producto => `
                                <div class="search-result-item">
                                    <img src="http://localhost/onlineTienda/admin-panel/assets/uploads/imagenes_productos/${producto.imagen}" alt="${producto.nombre_producto}" class="mini-imagen">
                                    <span>${producto.nombre_producto}</span>
                                </div>
                            `).join('');
                            searchResults.style.display = 'block';
                        } else {
                            searchResults.innerHTML = '<div class="search-result-item">No se encontraron resultados.</div>';
                            searchResults.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        searchResults.innerHTML = '<div class="search-result-item">Error al cargar los resultados.</div>';
                        searchResults.style.display = 'block';
                    });
            } else {
                searchResults.style.display = 'none';
            }
        });

        // Cerrar los resultados al hacer clic fuera
        document.addEventListener('click', function (event) {
            if (!searchResults.contains(event.target)) {
                searchResults.style.display = 'none';
            }
        });
    } else {
        console.error('Los elementos de búsqueda no existen en el DOM.');
    }
});