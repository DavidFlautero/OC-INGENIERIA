<?php
class ErrorController {

    // Método para manejar errores (por ejemplo, error 404)
    public function index() {
        // Mostrar el mensaje de error
        echo "<div class='col-md-12 text-center'>";
        echo "<h1> Error 404 </h1>";
        echo "<img class='img-fluid error-oops' src='" . base_url . "img/OOPS.png'>";
        echo "</div>";

        // Redirigir al usuario a la página principal después de 3 segundos
        header("Refresh: 3; URL=" . base_url);
    }
}