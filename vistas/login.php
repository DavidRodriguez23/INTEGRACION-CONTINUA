<?php
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/ValidadorLogin.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/Redireccion.inc.php';


if (ControlSesion::sesion_iniciada()) {
    Redireccion::redirigir(SERVIDOR);
}

if (isset($_POST['login'])) {
    if (isset($_POST['correo']) && isset($_POST['clave'])) {
        Conexion::abrir_conexion();
        $correo = $_POST['correo'];
        $clave = $_POST['clave'];
        $validador = new ValidadorLogin($correo, $clave, Conexion::obtener_conexion());

        if ($validador->obtener_error() === '' && !is_null($validador->obtener_usuario())) {
            ControlSesion::iniciar_sesion(
                $validador->obtener_usuario()->obtener_id(),
                $validador->obtener_usuario()->obtener_nombre(),
                $validador->obtener_usuario()->obtener_rol()
            );
            Redireccion::redirigir(SERVIDOR);
        }

        Conexion::cerrar_conexion();
    }
}

$titulo = 'Iniciar sesión';

include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>

<br>
<br>
<div class="container mt-5 mb-5">
    <div class="card shadow tarjeta-registro animar-entrada" style="border: 2px solid #bfa76f;">
        <!-- Header con degradado -->
        <div class="card-header text-white text-center encabezado-degradado">
            <h3 class="titulo-encabezado mb-0">Iniciar sesión</h3>
            <p class="mt-1 mb-0 subtitulo-registro">
                Ingresa tus credenciales para acceder a tu cuenta.
            </p>
        </div>

        <!-- Formulario -->
        <form action="<?php echo RUTA_LOGIN ?>" method="post">
            <div class="card-body fondo-registro">
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold text-success">Correo electrónico</label>
                    <input type="email"
                        name="correo"
                        id="email"
                        class="form-control"
                        placeholder="Ingresa tu correo"
                        value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>"
                        autofocus
                        required>
                </div>

                <div class="mb-3">
                    <label for="clave" class="form-label fw-semibold text-success">Contraseña</label>
                    <input type="password"
                        name="clave"
                        id="clave"
                        class="form-control"
                        placeholder="Ingresa tu contraseña"
                        required>
                </div>

                <!-- Mostrar errores -->
                <?php
                if (isset($_POST['login']) && !empty($_POST['correo']) && !empty($_POST['clave'])) {
                    Conexion::abrir_conexion();
                    $validador = new ValidadorLogin($_POST['correo'], $_POST['clave'], Conexion::obtener_conexion());
                    $validador->mostrar_error();
                    Conexion::cerrar_conexion();
                }
                ?>

                <button type="submit" name="login" class="btn btn-danger btn-lg rounded-pill w-100 mt-3 shadow-sm">
                    Iniciar sesión
                </button>

                <div class="text-center mt-4">
                    <a href="<?php echo RUTA_RECUPERAR_CLAVE; ?>" class="text-decoration-none fw-semibold" style="color: #1e5826;">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>
