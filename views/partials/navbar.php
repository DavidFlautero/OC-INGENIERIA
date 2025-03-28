<nav>
    <ul>
        <li><a href="<?= base_url; ?>">Inicio</a></li>
        <li><a href="<?= base_url; ?>producto/gestion">Productos</a></li>
        <li><a href="<?= base_url; ?>carrito/index">Carrito</a></li>
        <?php if (isset($_SESSION['user'])) : ?>
            <li><a href="<?= base_url; ?>usuario/logout">Cerrar Sesión</a></li>
        <?php else : ?>
            <li><a href="<?= base_url; ?>usuario/login">Iniciar Sesión</a></li>
            <li><a href="<?= base_url; ?>usuario/registro">Registrarse</a></li>
        <?php endif; ?>
    </ul>
</nav>