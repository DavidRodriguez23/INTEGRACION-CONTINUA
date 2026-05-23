<?php

include_once 'app/RepositorioRecuperacionClave.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/RepositorioUsuario.inc.php';

Conexion::abrir_conexion();

// Verificar si la URL secreta existe
if (RepositorioRecuperacionClave::url_secreta_existe(Conexion::obtener_conexion(), $url_personal)) {
    $id_usuario = RepositorioRecuperacionClave::obtener_id_usuario_url_secreta(Conexion::obtener_conexion(), $url_personal);
} else {
    echo '404';
}

if (isset($_POST['guardar-clave'])) {
    // Validar que las contraseñas coincidan
    if ($_POST['clave'] !== $_POST['clave2']) {
        echo 'Error: Las contraseñas no coinciden.';
        exit;
    }


    $clave_cifrada = password_hash($_POST['clave'], PASSWORD_DEFAULT);

    // Actualizar la contraseña en la base de datos
    $clave_actualizada = RepositorioUsuario::actualizar_clave(Conexion::obtener_conexion(), $id_usuario, $clave_cifrada) && RepositorioRecuperacionClave::eliminar_peticion(Conexion::obtener_conexion(), $url_personal);

    // Redirigir según el resultado
    if ($clave_actualizada) {
        Redireccion::redirigir(RUTA_CLAVE_RECUPERADA);
    } else {
        echo 'Error: No se pudo actualizar la contraseña.';
    }
}

Conexion::cerrar_conexion();

$titulo = 'Recuperación de clave';
include_once 'plantillas/documento-apertura.inc.php';
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card tarjeta-registro animate-fade-in">
                <div class="card-header encabezado-degradado text-white text-center">
                    <h3 class="titulo-encabezado mb-0">Recuperación de clave</h3>
                </div>
                <div class="card-body fondo-registro">
                    <form method="post" action="<?php echo htmlspecialchars(RUTA_RECUPERACION_CLAVE . "/" . $url_personal); ?>">
                        <div class="mb-3">
                            <label for="clave" class="form-label text-success fw-semibold">Nueva clave</label>
                            <input type="password" name="clave" id="clave" class="form-control" required placeholder="Escribe tu nueva clave">
                        </div>
                        <div class="mb-3">
                            <label for="clave2" class="form-label text-success fw-semibold">Repite tu clave</label>
                            <input type="password" name="clave2" id="clave2" class="form-control" required placeholder="Debe coincidir">
                        </div>
                        <button type="submit" name="guardar-clave" class="btn btn-danger w-100">Guardar contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include_once 'plantillas/documento-cierre.inc.php';
?>