<?php
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/RepositorioAjustes.inc.php';

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

// Obtener datos desde ajustes
$email_contacto = RepositorioAjustes::obtener_valor('email_contacto', $conexion);
$telefono = RepositorioAjustes::obtener_valor('telefono_contacto', $conexion);
$direccion = RepositorioAjustes::obtener_valor('direccion_contacto', $conexion);
$facebook = RepositorioAjustes::obtener_valor('facebook', $conexion);
$twitter = RepositorioAjustes::obtener_valor('twitter', $conexion);
$instagram = RepositorioAjustes::obtener_valor('instagram', $conexion);
?>

<br>
<section class="contacto-hero d-flex align-items-center justify-content-center">
    <div>
        <h1 class="fw-bold display-4">Contáctanos</h1>
        <p class="lead">Estamos aquí para ayudarte. ¡Hablemos!</p>
    </div>
</section>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card card-contacto shadow-lg">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-4 text-center">Información de contacto</h2>
                    <hr class="mb-4" style="border-top: 2px solid var(--dorado); width: 80px; margin: 0 auto;">

                    <div class="mb-4 text-center fs-5 contact-info">
                        <span class="me-4 d-block d-md-inline">
                            <i class="fas fa-envelope me-2"></i> <?php echo $email_contacto; ?>
                        </span>
                        <span class="me-4 d-block d-md-inline">
                            <i class="fas fa-phone me-2"></i> <?php echo $telefono; ?>
                        </span>
                        <span class="d-block mt-3">
                            <i class="fas fa-map-marker-alt me-2"></i> <?php echo $direccion; ?>
                        </span>
                    </div>

                    <div class="text-center fs-4 mt-4">
                        <?php if ($facebook): ?>
                            <a href="<?php echo $facebook; ?>" class="text-success me-3 contacto-icono-red"><i class="fab fa-facebook"></i></a>
                        <?php endif; ?>
                        <?php if ($twitter): ?>
                            <a href="<?php echo $twitter; ?>" class="text-success me-3 contacto-icono-red"><i class="fab fa-twitter"></i></a>
                        <?php endif; ?>
                        <?php if ($instagram): ?>
                            <a href="<?php echo $instagram; ?>" class="text-success contacto-icono-red"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>