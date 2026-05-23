<?php
include_once 'app/ControlSesion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/RepositorioUsuario.inc.php';

if (!ControlSesion::es_admin()) {
    Redireccion::redirigir(SERVIDOR);
    exit();
}

Conexion::abrir_conexion();

// Obtener el usuario a editar
$usuario = null;
if (isset($_GET['id'])) {
    $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $_GET['id']);
    if (!$usuario) {
        $_SESSION['mensaje_error'] = 'Usuario no encontrado.';
        Redireccion::redirigir(RUTA_ADMIN_USUARIOS);
        exit();
    }
} else {
    Redireccion::redirigir(RUTA_ADMIN_USUARIOS);
    exit();
}

// Procesar envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $identificacion = trim($_POST['identificacion'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $rol = $_POST['rol'] ?? '';
    $tipo_insumos = trim($_POST['tipo_insumos'] ?? '');

    $roles_permitidos = ['admin', 'cliente'];

    if (!empty($nombre) && !empty($identificacion) && !empty($correo) && in_array($rol, $roles_permitidos)) {
        $resultado = RepositorioUsuario::actualizar_datos_admin(
            Conexion::obtener_conexion(),
            $usuario->obtener_id(),
            $nombre,
            $identificacion,
            $correo,
            $telefono,
            $rol,
            $tipo_insumos
        );

        if ($resultado) {
            $_SESSION['mensaje_exito'] = 'Usuario actualizado correctamente.';
        } else {
            $_SESSION['mensaje_error'] = 'Error al actualizar el usuario.';
        }

        Redireccion::redirigir(RUTA_ADMIN_USUARIOS);
        exit();
    } else {
        $_SESSION['mensaje_error'] = 'Por favor, completa todos los campos obligatorios.';
    }
}

include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>

<br><br>
<div class="container mt-5">
    <h2 class="text-center main-title mb-4">Editar Usuario</h2>

    <form method="POST" class="tarjeta-registro p-4 rounded-4 shadow-sm border mx-auto fondo-registro" style="max-width: 600px;">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre completo</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required
                value="<?= htmlspecialchars($usuario->obtener_nombre()) ?>">
        </div>

        <div class="mb-3">
            <label for="identificacion" class="form-label">Identificación</label>
            <input type="text" name="identificacion" id="identificacion" class="form-control" required
                value="<?= htmlspecialchars($usuario->obtener_identificacion()) ?>">
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" name="correo" id="correo" class="form-control" required
                value="<?= htmlspecialchars($usuario->obtener_correo()) ?>">
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control"
                value="<?= htmlspecialchars($usuario->obtener_telefono()) ?>">
        </div>

        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select name="rol" id="rol" class="form-select" required>
                <option value="cliente" <?= $usuario->obtener_rol() === 'cliente' ? 'selected' : '' ?>>cliente</option>
                <option value="admin" <?= $usuario->obtener_rol() === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tipo_insumos" class="form-label">Tipo de Insumos</label>
            <select class="form-select" name="tipo_insumos" id="tipo_insumos">
                <option value="" disabled <?= empty($usuario->obtener_tipo_insumos()) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($usuario->obtener_tipo_insumos()) ?: 'Seleccione un tipo' ?>
                </option>
                <option value="Agropecuarios" <?= $usuario->obtener_tipo_insumos() === 'Agropecuarios' ? 'selected' : '' ?>>Agropecuarios</option>
                <option value="Maquinaria en general" <?= $usuario->obtener_tipo_insumos() === 'Maquinaria en general' ? 'selected' : '' ?>>Maquinaria en general</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
    </form>
</div>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>