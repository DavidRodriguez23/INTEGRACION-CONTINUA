<?php

$titulo = 'Recuperación de contraseña';

include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>

<br><br>
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card tarjeta-registro animate-fade-in">
                <div class="card-header encabezado-degradado text-white text-center">
                    <h3 class="titulo-encabezado mb-0">Recuperación de contraseña</h3>
                </div>
                <div class="card-body fondo-registro">
                    <p class="text-muted">
                        Escribe el correo electrónico asociado a tu cuenta. Te enviaremos un enlace para restablecer tu clave.
                        Revisa tu carpeta de spam si no lo ves.
                    </p>
                    <form action="<?php echo RUTA_GENERAR_URL_SECRETA; ?>" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label text-success fw-semibold">Correo electrónico</label>
                            <input type="email" name="email" id="email" class="form-control" required autofocus placeholder="ejemplo@correo.com">
                        </div>
                        <button type="submit" name="enviar_email" class="btn btn-danger w-100">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'plantillas/documento-cierre.inc.php';
?>
