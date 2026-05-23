<?php
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/Redireccion.inc.php';

$titulo = '¡Registro correcto!';

include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>
<br><br>
<div class="container my-5 animar-entrada">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card tarjeta-registro shadow-lg border-0 animate-fade-in">
                <div class="card-body text-center px-4">

                    <!-- Logo -->
                    <img src="<?php echo SERVIDOR . '/img/logo-ganandez.jpg'; ?>"
                        alt="Logo Ganández"
                        class="logo-registro img-fluid mb-4"
                        style="max-height: 100px; width: auto;">

                    <!-- Icono de éxito -->
                    <div class="icono-container mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    </div>

                    <!-- Título -->
                    <h4 class="text-success fw-bold mb-3" style="font-size: 1.5rem;">¡Registro exitoso!</h4>

                    <!-- Mensaje personalizado -->
                    <p class="mensaje-registro mb-4" style="font-size: 1.1rem; color: #6c757d;">
                        ¡Gracias por registrarte, <strong><?php echo htmlspecialchars($nombre); ?></strong>! Ahora puedes iniciar sesión y disfrutar de nuestros servicios.
                    </p>

                    <!-- Botón de inicio de sesión -->
                    <a href="<?php echo RUTA_LOGIN; ?>"
                        class="btn btn-danger btn-lg rounded-pill px-5 shadow-sm"
                        style="font-size: 1.2rem;">
                        Inicia sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'plantillas/documento-cierre.inc.php';
?>