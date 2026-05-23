<?php
include_once 'app/ControlSesion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/RepositorioUsuario.inc.php';

if (!ControlSesion::es_admin()) {
    header('Location: ' . SERVIDOR);
    exit();
}

Conexion::abrir_conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cambiar estado
    if (isset($_POST['cambiar_estado'])) {
        $usuario_id = $_POST['usuario_id'] ?? null;
        $nuevo_estado = $_POST['nuevo_estado'] ?? null;

        if ($usuario_id !== null && ($nuevo_estado === '0' || $nuevo_estado === '1')) {
            $resultado = RepositorioUsuario::actualizar_estado_usuario(Conexion::obtener_conexion(), $usuario_id, $nuevo_estado);

            if ($resultado) {
                $_SESSION['mensaje_exito'] = 'Estado del usuario actualizado correctamente.';
            } else {
                $_SESSION['mensaje_error'] = 'Error al actualizar el estado del usuario.';
            }

            Redireccion::redirigir(RUTA_ADMIN_USUARIOS);
            exit();
        }
    }

    // Cambiar rol
    if (isset($_POST['cambiar_rol'])) {
        $usuario_id = $_POST['usuario_id'] ?? null;
        $nuevo_rol = $_POST['nuevo_rol'] ?? null;

        $roles_permitidos = ['admin', 'cliente'];

        if ($usuario_id !== null && in_array($nuevo_rol, $roles_permitidos, true)) {
            $resultado = RepositorioUsuario::actualizar_rol_usuario(Conexion::obtener_conexion(), $usuario_id, $nuevo_rol);

            if ($resultado) {
                $_SESSION['mensaje_exito'] = 'Rol del usuario actualizado correctamente.';
            } else {
                $_SESSION['mensaje_error'] = 'Error al cambiar el rol del usuario.';
            }

            Redireccion::redirigir(RUTA_ADMIN_USUARIOS);
            exit();
        }
    }

    // Eliminar usuario
    if (isset($_POST['eliminar_usuario_definitivo'])) {
        $usuario_id = $_POST['usuario_id'] ?? null;

        if ($usuario_id !== null) {
            $resultado_publicaciones = RepositorioUsuario::eliminar_publicaciones_por_usuario(Conexion::obtener_conexion(), $usuario_id);
            $resultado_usuario = RepositorioUsuario::eliminar_usuario_por_id(Conexion::obtener_conexion(), $usuario_id);

            if ($resultado_publicaciones && $resultado_usuario) {
                $_SESSION['mensaje_exito'] = 'Usuario y todas sus publicaciones eliminados correctamente.';
            } else {
                $_SESSION['mensaje_error'] = 'Error al eliminar el usuario o sus publicaciones.';
            }

            Redireccion::redirigir(RUTA_ADMIN_USUARIOS);
            exit();
        }
    }
}
// Filtros
$filtro_id = $_GET['filtro_id'] ?? '';
$filtro_nombre = $_GET['filtro_nombre'] ?? '';
$filtro_correo = $_GET['filtro_correo'] ?? '';
$filtro_rol = $_GET['filtro_rol'] ?? '';
$filtro_estado = $_GET['filtro_estado'] ?? '';

// Paginación
$pagina_actual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$usuarios_por_pagina = 10;
$offset = ($pagina_actual - 1) * $usuarios_por_pagina;

// Obtener todos los usuarios y filtrar manualmente
Conexion::abrir_conexion();
$usuarios_completos = RepositorioUsuario::obtener_todos(Conexion::obtener_conexion());

$usuarios_filtrados = array_filter($usuarios_completos, function ($usuario) use ($filtro_nombre, $filtro_correo, $filtro_rol, $filtro_estado) {
    $nombre_valido = !$filtro_nombre || stripos($usuario->obtener_nombre(), $filtro_nombre) !== false;
    $correo_valido = !$filtro_correo || stripos($usuario->obtener_correo(), $filtro_correo) !== false;
    $rol_valido = !$filtro_rol || $usuario->obtener_rol() === $filtro_rol;
    $estado_valido = $filtro_estado === '' || $usuario->obtener_estado_usuario() == $filtro_estado;

    return $nombre_valido && $correo_valido && $rol_valido && $estado_valido;
});

// Paginación
$total_usuarios = count($usuarios_filtrados);
$total_paginas = ceil($total_usuarios / $usuarios_por_pagina);
$usuarios = array_slice($usuarios_filtrados, $offset, $usuarios_por_pagina);

include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>

<br><br>
<div class="container my-5 admin-panel">
    <h2 class="text-center mb-4">Gestión de Usuarios</h2>

    <?php if (isset($_SESSION['mensaje_exito'])) : ?>
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['mensaje_exito'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        </div>
        <?php unset($_SESSION['mensaje_exito']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['mensaje_error'])) : ?>
        <div class="container mt-4">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['mensaje_error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        </div>
        <?php unset($_SESSION['mensaje_error']); ?>
    <?php endif; ?>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="filtro_nombre" class="form-control" value="<?= htmlspecialchars($filtro_nombre) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Correo:</label>
            <input type="text" name="filtro_correo" class="form-control" value="<?= htmlspecialchars($filtro_correo) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Rol:</label>
            <select name="filtro_rol" class="form-select">
                <option value="">-- Todos --</option>
                <option value="admin" <?= $filtro_rol === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="cliente" <?= $filtro_rol === 'cliente' ? 'selected' : '' ?>>cliente</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Estado:</label>
            <select name="filtro_estado" class="form-select">
                <option value="">-- Todos --</option>
                <option value="1" <?= $filtro_estado === '1' ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= $filtro_estado === '0' ? 'selected' : '' ?>>Inhabilitado</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-success"><i class="fas fa-filter me-2"></i>Filtrar</button>
        </div>
    </form>


    <div class="table-responsive mb-5 tarjeta-registro p-3 rounded-4 shadow-sm border">
        <table class="table tabla-personalizada align-middle table-hover text-center">
            <thead class="encabezado-degradado text-white" style="background: linear-gradient(90deg, #198754, #2ecc71);">
                <tr>
                    <th><i class="fas fa-id-badge me-1"></i> Usuario</th>
                    <th><i class="fas fa-user-tag me-1"></i> Rol</th>
                    <th><i class="fas fa-envelope me-1"></i> Correo</th>
                    <th><i class="fas fa-mobile-alt me-1"></i> Teléfono</th>
                    <th><i class="fas fa-circle me-1"></i> Estado</th>
                    <th><i class="fas fa-bullhorn me-1"></i> Publicaciones activas</th>
                    <th><i class="fas fa-cogs me-1"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)) : ?>
                    <?php foreach ($usuarios as $usuario) : ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($usuario->obtener_nombre()) ?></td>
                            <td>
                                <span class="badge <?= $usuario->obtener_rol() === 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                                    <?= ucfirst($usuario->obtener_rol()) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($usuario->obtener_correo()) ?></td>
                            <td><?= htmlspecialchars($usuario->obtener_telefono()) ?></td>
                            <td>
                                <?php if ($usuario->obtener_estado_usuario() == 1): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inhabilitado</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                // Asegúrate de implementar este método que cuenta las publicaciones activas del usuario
                                $totalPublicaciones = RepositorioUsuario::contar_publicaciones_activas_por_usuario(
                                    Conexion::obtener_conexion(),
                                    $usuario->obtener_id()
                                );
                                echo $totalPublicaciones;
                                ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                        id="dropdownUsuario<?= $usuario->obtener_id() ?>"
                                        data-bs-toggle="dropdown" aria-expanded="false" style="border:none;">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownUsuario<?= $usuario->obtener_id() ?>">
                                        <li>
                                            <a class="dropdown-item" href="<?= RUTA_ADMIN_EDITAR_USUARIO . '?id=' . $usuario->obtener_id(); ?>">
                                                <i class="fas fa-edit me-2"></i>Editar usuario
                                            </a>

                                        </li>
                                        <li>
                                            <form id="form-estado-<?= $usuario->obtener_id() ?>" method="POST" style="display:none;">
                                                <input type="hidden" name="usuario_id" value="<?= $usuario->obtener_id() ?>">
                                                <input type="hidden" name="cambiar_estado" value="1">
                                                <input type="hidden" name="nuevo_estado" id="nuevo_estado_<?= $usuario->obtener_id() ?>" value="">
                                            </form>

                                            <?php if ($usuario->obtener_estado_usuario() == 1): ?>
                                                <button class="dropdown-item text-warning" onclick="cambiarEstadoUsuario(<?= $usuario->obtener_id() ?>, 0)">
                                                    <i class="fas fa-user-slash me-2"></i>Inhabilitar cuenta
                                                </button>
                                            <?php else: ?>
                                                <button class="dropdown-item text-success" onclick="cambiarEstadoUsuario(<?= $usuario->obtener_id() ?>, 1)">
                                                    <i class="fas fa-user-check me-2"></i>Activar cuenta
                                                </button>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <form id="form-rol-<?= $usuario->obtener_id() ?>" method="POST" style="display:none;">
                                                <input type="hidden" name="usuario_id" value="<?= $usuario->obtener_id() ?>">
                                                <input type="hidden" name="cambiar_rol" value="1">
                                                <input type="hidden" name="nuevo_rol" id="nuevo_rol_<?= $usuario->obtener_id() ?>" value="">
                                            </form>

                                            <?php if ($usuario->obtener_rol() === 'admin'): ?>
                                                <button class="dropdown-item text-primary" onclick="cambiarRolUsuario(<?= $usuario->obtener_id() ?>, 'cliente')">
                                                    <i class="fas fa-user me-2"></i> Cambiar a cliente
                                                </button>
                                            <?php else: ?>
                                                <button class="dropdown-item text-danger" onclick="cambiarRolUsuario(<?= $usuario->obtener_id() ?>, 'admin')">
                                                    <i class="fas fa-user-shield me-2"></i> Cambiar a Admin
                                                </button>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <form id="form-eliminar-<?= $usuario->obtener_id() ?>" method="POST" style="display:none;">
                                                <input type="hidden" name="usuario_id" value="<?= $usuario->obtener_id() ?>">
                                                <input type="hidden" name="eliminar_usuario_definitivo" value="1">
                                            </form>
                                            <button class="dropdown-item text-danger" onclick="confirmarEliminacion(<?= $usuario->obtener_id() ?>)">
                                                <i class="fas fa-trash-alt me-2"></i>Eliminar usuario definitivamente
                                            </button>
                                        </li>

                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted fst-italic py-4">No hay usuarios registrados aún.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if ($total_paginas > 1): ?>
            <nav class="mt-5 animate-fade-in">
                <ul class="pagination justify-content-center pagination-rounded">

                    <!-- Botón Anterior -->
                    <li class="page-item <?= $pagina_actual == 1 ? 'disabled' : '' ?>">
                        <a class="page-link"
                            href="?pagina=<?= $pagina_actual - 1 ?>&filtro_id=<?= urlencode($filtro_id) ?>&filtro_nombre=<?= urlencode($filtro_nombre) ?>&filtro_correo=<?= urlencode($filtro_correo) ?>&filtro_rol=<?= urlencode($filtro_rol) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>">
                            &laquo;
                        </a>
                    </li>

                    <?php
                    $max_mostrar = 10;
                    $inicio = max(1, $pagina_actual - 4);
                    $fin = min($total_paginas, $inicio + $max_mostrar - 1);

                    if ($inicio > 1):
                    ?>
                        <li class="page-item"><a class="page-link" href="?pagina=1&filtro_id=<?= urlencode($filtro_id) ?>&filtro_nombre=<?= urlencode($filtro_nombre) ?>&filtro_correo=<?= urlencode($filtro_correo) ?>&filtro_rol=<?= urlencode($filtro_rol) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>">1</a></li>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>

                    <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                        <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                            <a class="page-link <?= $i == $pagina_actual ? 'bg-success text-white border-success' : '' ?>"
                                href="?pagina=<?= $i ?>&filtro_id=<?= urlencode($filtro_id) ?>&filtro_nombre=<?= urlencode($filtro_nombre) ?>&filtro_correo=<?= urlencode($filtro_correo) ?>&filtro_rol=<?= urlencode($filtro_rol) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($fin < $total_paginas): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        <li class="page-item"><a class="page-link"
                                href="?pagina=<?= $total_paginas ?>&filtro_id=<?= urlencode($filtro_id) ?>&filtro_nombre=<?= urlencode($filtro_nombre) ?>&filtro_correo=<?= urlencode($filtro_correo) ?>&filtro_rol=<?= urlencode($filtro_rol) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>"><?= $total_paginas ?></a></li>
                    <?php endif; ?>

                    <!-- Botón Siguiente -->
                    <li class="page-item <?= $pagina_actual == $total_paginas ? 'disabled' : '' ?>">
                        <a class="page-link"
                            href="?pagina=<?= $pagina_actual + 1 ?>&filtro_id=<?= urlencode($filtro_id) ?>&filtro_nombre=<?= urlencode($filtro_nombre) ?>&filtro_correo=<?= urlencode($filtro_correo) ?>&filtro_rol=<?= urlencode($filtro_rol) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>">
                            &raquo;
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>


    </div>

    <script>
        function cambiarEstadoUsuario(id, nuevoEstado) {
            const mensaje = nuevoEstado == 1 ?
                '¿Estás seguro de que deseas activar esta cuenta?' :
                '¿Estás seguro de que deseas inhabilitar esta cuenta?';

            if (confirm(mensaje)) {
                document.getElementById('nuevo_estado_' + id).value = nuevoEstado;
                document.getElementById('form-estado-' + id).submit();
            }
        }

        function cambiarRolUsuario(id, nuevoRol) {
            const mensaje = nuevoRol === 'admin' ?
                '¿Estás seguro de que deseas cambiar el rol a Administrador?' :
                '¿Estás seguro de que deseas cambiar el rol a Usuario?';

            if (confirm(mensaje)) {
                document.getElementById('nuevo_rol_' + id).value = nuevoRol;
                document.getElementById('form-rol-' + id).submit();
            }
        }

        function confirmarEliminacion(id) {
            const mensaje = '¿Seguro que quieres eliminar este usuario y todas sus publicaciones? Esta acción es irreversible.';
            if (confirm(mensaje)) {
                document.getElementById('form-eliminar-' + id).submit();
            }
        }
    </script>






    <?php include_once 'plantillas/documento-cierre.inc.php'; ?>