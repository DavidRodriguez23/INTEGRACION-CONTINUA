<?php
include_once 'app/Conexion.inc.php';
include_once 'app/Usuario.inc.php';
include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/ValidadorRegistro.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/config.inc.php';


if (isset($_POST['enviar'])) {
    Conexion::abrir_conexion();

    $tipo_insumos = isset($_POST['tipo_insumos']) ? $_POST['tipo_insumos'] : null;

    $validador = new ValidadorRegistro(
        $_POST['nombre'],
        $_POST['identificacion'],
        $_POST['correo'],
        $_POST['telefono'],
        $_POST['clave1'],
        $_POST['clave2'],
        $tipo_insumos,
        Conexion::obtener_conexion()
    );

    if ($validador->registro_valido()) {
        $usuario = new Usuario(
            null,
            $validador->obtener_nombre(),
            $validador->obtener_identificacion(),
            $validador->obtener_correo(),
            $validador->obtener_telefono(),
            password_hash($validador->obtener_clave1(), PASSWORD_DEFAULT),
            0,
            1,
            1,
            null,
            null,
            null,
            $validador->obtener_tipo_insumos()
        );

        $usuario_insertado = RepositorioUsuario::insertar_usuario(Conexion::obtener_conexion(), $usuario);
        Redireccion::redirigir(RUTA_REGISTRO_CORRECTO . '/' . $usuario->obtener_nombre());
    }

}

$titulo = 'Registro';

include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>

<br>
<br>
<div class="container mt-5 mb-5">
    <div class="card shadow animar-entrada tarjeta-registro">
        <div class="card-header text-white encabezado-degradado">
            <h3 class="titulo-encabezado mb-0 text-center">Registro de Usuario</h3>
            <p class="text-center mt-1 mb-0" style="font-size: 0.95rem; color: #f0e9d2;">
                Por favor, completa todos los campos obligatorios para crear tu cuenta.
            </p>
        </div>
        <form action="<?php echo RUTA_REGISTRO; ?>" method="POST">
            <div class="card-body" style="background-color: #f0e9d2;">
                <?php
                if (isset($_POST['enviar'])) {
                    include_once 'plantillas/registro_valido.inc.php';
                } else {
                    include_once 'plantillas/registro_vacio.inc.php';
                }
                ?>
            </div>
    </div>
    </form>


    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }
    </script>

    <?php include_once 'plantillas/documento-cierre.inc.php'; ?>