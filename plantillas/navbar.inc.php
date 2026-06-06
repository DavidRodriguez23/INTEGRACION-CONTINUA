<?php
include_once __DIR__ . '/../app/ControlSesion.inc.php';
include_once 'documento-apertura.inc.php';
include_once __DIR__ . '/../app/config.inc.php';

$usuario_activo = ControlSesion::usuario_activo();
?>

<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo SERVIDOR; ?>">
      <img src="<?php echo SERVIDOR . '/img/logo-vaca-dorado-sm.png'; ?>" alt="Ganadería Livestock" height="44">
      <span>GANADERÍA <span>LIVESTOCK</span></span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarGanaderos">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarGanaderos">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="<?php echo SERVIDOR; ?>">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_CONTACTENOS; ?>">Contáctenos</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_NOSOTROS; ?>">Nosotros</a></li>

        <?php if (!ControlSesion::sesion_iniciada()): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarSesion" role="button" data-bs-toggle="dropdown">
              Mi cuenta
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="<?php echo RUTA_LOGIN; ?>"><i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión</a></li>
              <li><a class="dropdown-item" href="<?php echo RUTA_REGISTRO; ?>"><i class="fas fa-user-check me-2"></i>Registrarse</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarUsuario" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-user-circle me-1"></i> Mi cuenta
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="<?php echo RUTA_PERFIL; ?>"><i class="fas fa-user me-2"></i>Perfil</a></li>
              <?php if (ControlSesion::es_admin()): ?>
                <li><a class="dropdown-item" href="<?php echo RUTA_ADMIN_PANEL; ?>"><i class="fas fa-cogs me-2"></i>Panel admin</a></li>
              <?php endif; ?>
              <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
              <li><a class="dropdown-item" href="<?php echo RUTA_LOGOUT; ?>"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>

      <div class="d-flex align-items-center gap-2 ms-lg-3 mt-2 mt-lg-0">
        <a href="<?php echo SERVIDOR . '/vistas/carrito.php'; ?>" class="btn btn-outline-dorado btn-sm position-relative">
          <i class="fas fa-shopping-cart me-1"></i>Carrito
          <?php
          $carrito_count = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;
          if ($carrito_count > 0):
          ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: var(--dorado); color: var(--tierra); font-size: 0.65rem;"><?= $carrito_count ?></span>
          <?php endif; ?>
        </a>

        <a href="<?php echo RUTA_COMPRA; ?>" class="btn btn-dorado btn-sm">Comprar</a>

        <?php if (ControlSesion::sesion_iniciada() && ControlSesion::usuario_activo()): ?>
          <a href="<?php echo RUTA_VENTA; ?>" class="btn btn-outline-dorado btn-sm">Vender ganado</a>
          <a href="<?php echo RUTA_VENTA_CABALLO; ?>" class="btn btn-outline-dorado btn-sm">Vender caballos</a>
        <?php elseif (ControlSesion::sesion_iniciada()): ?>
          <span class="btn btn-sm btn-secondary disabled">Vender ganado</span>
          <span class="btn btn-sm btn-secondary disabled">Vender caballos</span>
          <script>document.addEventListener('DOMContentLoaded',function(){alert('Tu cuenta está inactiva. Contacta a soporte para habilitarla.');});</script>
        <?php else: ?>
          <a href="<?php echo RUTA_LOGIN; ?>" class="btn btn-outline-dorado btn-sm">Vender ganado</a>
          <a href="<?php echo RUTA_LOGIN; ?>" class="btn btn-outline-dorado btn-sm">Vender caballos</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
