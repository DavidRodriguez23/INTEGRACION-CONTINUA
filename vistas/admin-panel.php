<?php
include_once 'app/ControlSesion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';

if (!ControlSesion::es_admin()) {
    header('Location: ' . SERVIDOR);
    exit();
}
?>

<br><br>
<section class="bienvenida-section py-5 text-center">
    <div class="container">
        <h1 class="main-title animate-fade-in mb-3">
            <i class="fas fa-tools me-2"></i> Panel de Administración
        </h1>
        <hr class="divider mb-4">
        <p class="main-subtitle">Gestiona los usuarios, publicaciones, reportes y configuraciones generales del sistema.</p>

        <!-- Acciones rápidas -->
        <div class="mt-4 mb-5">
            <a href="<?php echo RUTA_ADMIN_AJUSTES; ?>" class="btn btn-outline-cafe mx-2">
                <i class="fas fa-cogs me-1"></i> Ajustes
            </a>
        </div>
    </div>
</section>

<div class="container py-4">
    <div class="row g-4">

        <!-- Usuarios -->
        <div class="col-md-6 col-lg-4">
            <a href="<?php echo RUTA_ADMIN_USUARIOS; ?>" class="enlace-tarjeta" aria-label="Gestionar usuarios">
                <div class="card tarjeta-animal mejorada h-100 text-center p-4">
                    <div class="admin-icon mb-3">
                        <i class="fas fa-users fa-3x text-success"></i>
                    </div>
                    <h4 class="tarjeta-animal-title">Usuarios</h4>
                    <p class="section-content">Ver, editar o eliminar usuarios registrados.</p>
                    <span class="btn btn-outline-success mt-auto">Gestionar</span>
                </div>
            </a>
        </div>

        <!-- Publicaciones -->
        <div class="col-md-6 col-lg-4">
            <a href="<?php echo RUTA_ADMIN_PUBLICACIONES; ?>" class="enlace-tarjeta" aria-label="Gestionar publicaciones">
                <div class="card tarjeta-animal mejorada h-100 text-center p-4">
                    <div class="admin-icon mb-3">
                        <i class="fas fa-clipboard-list fa-3x text-success"></i>
                    </div>
                    <h4 class="tarjeta-animal-title">Publicaciones</h4>
                    <p class="section-content">Revisar ventas y publicaciones de animales.</p>
                    <span class="btn btn-outline-success mt-auto">Gestionar</span>
                </div>
            </a>
        </div>

        <!-- Reportes -->
        <div class="col-md-6 col-lg-4">
            <a href="<?php echo RUTA_ADMIN_REPORTES; ?>" class="enlace-tarjeta" aria-label="Ver reportes del sistema">
                <div class="card tarjeta-animal mejorada h-100 text-center p-4">
                    <div class="admin-icon mb-3">
                        <i class="fas fa-chart-bar fa-3x text-success"></i>
                    </div>
                    <h4 class="tarjeta-animal-title">Reportes</h4>
                    <p class="section-content">Visualizar estadísticas del sistema.</p>
                    <span class="btn btn-outline-success mt-auto">Ver Reportes</span>
                </div>
            </a>
        </div>

    </div>
</div>

<!-- Footer institucional -->
<footer class="text-center mt-5">
    <small class="text-muted">
        © 2025 Sistema de Administración Ganandez — Todos los derechos reservados
    </small>
</footer>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>
