<?php
include_once __DIR__ . '/../app/ControlSesion.inc.php';
include_once 'documento-apertura.inc.php';
include_once __DIR__ . '/../app/config.inc.php';

$usuario_activo = ControlSesion::usuario_activo();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 text-light fw-bold" href="<?php echo SERVIDOR; ?>">
            <img src="<?php echo SERVIDOR . '/img/Logo-ganandez.jpg'; ?>" alt="Ganandez" height="60" class="d-inline-block align-text-top">
            <span class="fs-4">GANADERÍA LIVESTOCK</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarGanaderos" aria-controls="navbarGanaderos" aria-expanded="false" aria-label="Menú">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarGanaderos">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?php echo SERVIDOR; ?>">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_CONTACTENOS; ?>">Contactenos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_NOSOTROS; ?>">Nosotros</a></li>

                <?php if (!ControlSesion::sesion_iniciada()) { ?>
                    <!-- No ha iniciado sesión -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarSesion" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Forma parte de nosotros
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarSesion">
                            <li><a class="dropdown-item" href="<?php echo RUTA_LOGIN; ?>"><i class="fas fa-sign-in-alt me-1"></i>Iniciar sesión</a></li>
                            <li><a class="dropdown-item" href="<?php echo RUTA_REGISTRO; ?>"><i class="fas fa-user-check me-1"></i>Registrarse</a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <!-- Usuario autenticado -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUsuario" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Mi cuenta
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUsuario">
                            <li><a class="dropdown-item" href="<?php echo RUTA_PERFIL; ?>"><i class="fas fa-user me-1"></i>Perfil</a></li>
                            <?php if (ControlSesion::es_admin()) { ?>
                                <li><a class="dropdown-item" href="<?php echo RUTA_ADMIN_PANEL; ?>"><i class="fas fa-cogs me-1"></i>Panel de control</a></li>
                            <?php } ?>
                            <li><a class="dropdown-item" href="<?php echo RUTA_LOGOUT; ?>"><i class="fas fa-sign-out-alt me-1"></i>Cerrar sesión</a></li>
                        </ul>
                    </li>
                <?php } ?>
            </ul>

            <a href="<?php echo RUTA_COMPRA; ?>" class="nav-item btn btn-danger btn-lg mx-2">Comprar</a>

            <!-- Carrito de compras -->
            <a href="<?php echo SERVIDOR . '/vistas/carrito.php'; ?>" class="nav-item btn btn-outline-success btn-lg mx-2 position-relative">
                <i class="fas fa-shopping-cart"></i>
                <span class="d-none d-lg-inline">Carrito</span>
                <?php
                $carrito_count = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;
                if ($carrito_count > 0) {
                    echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' . $carrito_count . '</span>';
                }
                ?>
            </a>

            <?php if (ControlSesion::sesion_iniciada()) : ?>
                <?php if (ControlSesion::usuario_activo()) : ?>
                    <!-- Usuario con sesión activa -->
                    <a href="<?php echo RUTA_VENTA; ?>" class="nav-item btn btn-danger btn-lg mx-2">Vender Ganado</a>
                    <a href="<?php echo RUTA_VENTA_CABALLO; ?>" class="nav-item btn btn-danger btn-lg mx-2">Vender Caballos</a>
                <?php else : ?>
                    <!-- Usuario con sesión iniciada pero cuenta inactiva -->
                    <a href="#" class="nav-item btn btn-secondary btn-lg mx-2 disabled" title="Tu cuenta está inactiva">Vender Ganado</a>
                    <a href="#" class="nav-item btn btn-secondary btn-lg mx-2 disabled" title="Tu cuenta está inactiva">Vender Caballos</a>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            alert("Tu cuenta está inactiva. Por favor contacta con soporte para habilitarla.");
                        });
                    </script>
                <?php endif; ?>
            <?php else : ?>
                <!-- Usuario no ha iniciado sesión -->
                <a href="<?php echo RUTA_LOGIN; ?>" class="nav-item btn btn-danger btn-lg mx-2">Vender Ganado</a>
                <a href="<?php echo RUTA_LOGIN; ?>" class="nav-item btn btn-danger btn-lg mx-2">Vender Caballos</a>
            <?php endif; ?>

        </div>
    </div>
</nav>