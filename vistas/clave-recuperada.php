<?php
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/Redireccion.inc.php';


$titulo = 'Clave recuperada';

include_once 'plantillas/documento-apertura.inc.php';

?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card tarjeta-registro animate-fade-in text-center">
                <div class="card-header encabezado-degradado text-white">
                    <h3 class="titulo-encabezado mb-0">¡Clave restaurada con éxito!</h3>
                </div>
                <div class="card-body fondo-registro">
                    <p class="lead">Tu contraseña ha sido actualizada correctamente.</p>
                    <a href="<?php echo RUTA_LOGIN; ?>" class="btn btn-danger w-100 mt-3">Iniciar sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include_once 'plantillas/documento-cierre.inc.php';
?>

