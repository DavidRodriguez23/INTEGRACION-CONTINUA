<?php
include_once 'app/ControlSesion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/RepositorioAnimal.inc.php';
include_once 'app/RepositorioCaballo.inc.php';
include_once 'app/RepositorioUsuario.inc.php';

if (!ControlSesion::es_admin()) {
    Redireccion::redirigir(SERVIDOR);
    exit();
}



function fecha_espanol($fecha)
{
    $meses = [
        'January' => 'enero',
        'February' => 'febrero',
        'March' => 'marzo',
        'April' => 'abril',
        'May' => 'mayo',
        'June' => 'junio',
        'July' => 'julio',
        'August' => 'agosto',
        'September' => 'septiembre',
        'October' => 'octubre',
        'November' => 'noviembre',
        'December' => 'diciembre'
    ];

    $timestamp = strtotime($fecha);
    $dia = date('d', $timestamp);
    $mes_ingles = date('F', $timestamp);
    $anio = date('Y', $timestamp);
    $mes_espanol = $meses[$mes_ingles] ?? $mes_ingles;
    return "$dia de $mes_espanol de $anio";
}

Conexion::abrir_conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? 'animal';
    $id = $_POST['id'] ?? null;
    $id_usuario = $_POST['id_usuario'] ?? null;

    $id_usuario_para_funciones = null;

    if ($id && $id_usuario) {
        if (isset($_POST['marcar_vendido'])) {
            if ($tipo === 'animal') {
                $exito = RepositorioAnimal::marcar_animal_vendido(Conexion::obtener_conexion(), $id, $id_usuario_para_funciones);
            } else {
                $exito = RepositorioCaballo::marcar_caballo_vendido(Conexion::obtener_conexion(), $id, $id_usuario_para_funciones);
            }


            $_SESSION[$exito ? 'mensaje_exito' : 'mensaje_error'] = $exito
                ? ucfirst($tipo) . ' marcado como vendido.'
                : 'Error al marcar como vendido.';

            Redireccion::redirigir(RUTA_ADMIN_PUBLICACIONES);
            exit();
        }

        if (isset($_POST['marcar_disponible'])) {
            if ($tipo === 'animal') {
                $exito = RepositorioAnimal::marcar_animal_disponible(Conexion::obtener_conexion(), $id, $id_usuario_para_funciones);
            } else {
                $exito = RepositorioCaballo::marcar_caballo_disponible(Conexion::obtener_conexion(), $id, $id_usuario_para_funciones);
            }

            $_SESSION[$exito ? 'mensaje_exito' : 'mensaje_error'] = $exito
                ? ucfirst($tipo) . ' marcado como disponible.'
                : 'Error al cambiar a disponible.';

            Redireccion::redirigir(RUTA_ADMIN_PUBLICACIONES);
            exit();
        }

        if (isset($_POST['eliminar_animal'])) {
            if ($tipo === 'animal') {
                $exito = RepositorioAnimal::eliminar_animal_por_id(Conexion::obtener_conexion(), $id, $id_usuario_para_funciones);
            } else {
                $exito = RepositorioCaballo::eliminar_caballo_por_id(Conexion::obtener_conexion(), $id, $id_usuario_para_funciones);
            }

            $_SESSION[$exito ? 'mensaje_exito' : 'mensaje_error'] = $exito
                ? ucfirst($tipo) . ' eliminado correctamente.'
                : 'Error al eliminar.';

            Redireccion::redirigir(RUTA_ADMIN_PUBLICACIONES);
            exit();
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tipo = $_POST['tipo'] ?? 'animal';
        $id = $_POST['id'] ?? null;

        if ($id) {
            if (isset($_POST['editar_publicacion'])) {

                if ($tipo === 'animal') {
                    Redireccion::redirigir(RUTA_ADMIN_EDITAR_ANIMAL . "?id=$id");
                } else {
                    Redireccion::redirigir(RUTA_ADMIN_EDITAR_CABALLO . "?id=$id");
                }
                exit();
            }

            // ... Aquí sigue el resto de tus acciones (marcar vendido, disponible, eliminar)
        }
    }
}
// Obtener ambos listados y unirlos
$conexion = Conexion::obtener_conexion();
$animales = RepositorioAnimal::obtener_animales_normales($conexion);
$caballos = RepositorioCaballo::obtener_todos($conexion);

$lista_completa = array_merge($animales, $caballos);

// Parámetros de paginación
$pagina_actual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$registros_por_pagina = 10;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Filtro avanzado
$filtro_id = $_GET['filtro_id'] ?? '';
$filtro_dueno = $_GET['filtro_dueno'] ?? '';
$filtro_estado = $_GET['filtro_estado'] ?? '';
$filtro_estado = $_GET['filtro_estado'] ?? '';
$filtro_vencimiento = $_GET['filtro_vencimiento'] ?? '';


// Unir y filtrar animales
$conexion = Conexion::obtener_conexion();
$animales = RepositorioAnimal::obtener_animales_normales($conexion);
$caballos = RepositorioCaballo::obtener_todos($conexion);
$lista_completa = array_merge($animales, $caballos);

// Filtrar
$lista_filtrada = array_filter($lista_completa, function ($item) use ($filtro_id, $filtro_dueno, $filtro_estado, $filtro_vencimiento, $conexion) {
    $id_actual = $item->obtener_id();
    $estado_actual = $item->esta_vendido() ? 'vendido' : 'disponible';
    $usuario = RepositorioUsuario::obtener_usuario_por_id($conexion, $item->obtener_id_usuario());
    $nombre_dueno = $usuario ? $usuario->obtener_nombre() : '';

    $id_valido = !$filtro_id || stripos((string)$id_actual, $filtro_id) !== false;
    $dueno_valido = !$filtro_dueno || stripos($nombre_dueno, $filtro_dueno) !== false;
    $estado_valido = !$filtro_estado || $estado_actual === $filtro_estado;

    $vence_valido = true;
    if ($filtro_vencimiento === 'proximos') {
        $fecha_fin = strtotime($item->obtener_fecha_fin());
        $hoy = strtotime(date('Y-m-d'));
        $en_7_dias = strtotime('+7 days');
        $vence_valido = $fecha_fin >= $hoy && $fecha_fin <= $en_7_dias && !$item->esta_vendido();
    }

    return $id_valido && $dueno_valido && $estado_valido && $vence_valido;
});



// Paginación sobre la lista filtrada
$total_registros = count($lista_filtrada);
$total_paginas = ceil($total_registros / $registros_por_pagina);
$lista_paginada = array_slice($lista_filtrada, $offset, $registros_por_pagina);

include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>
<br><br>
<div class="container my-5 admin-panel">
    <h2 class="text-center mb-4">Gestión de Publicaciones</h2>

    <?php if (isset($_SESSION['mensaje_exito'])): ?>
        <div class="alert alert-success"><?= $_SESSION['mensaje_exito'];
                                            unset($_SESSION['mensaje_exito']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['mensaje_error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['mensaje_error'];
                                        unset($_SESSION['mensaje_error']); ?></div>
    <?php endif; ?>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="filtro_id" class="form-label">ID de publicación:</label>
            <input type="number" name="filtro_id" id="filtro_id" class="form-control" placeholder="Ej. 12" value="<?= htmlspecialchars($filtro_id) ?>">
        </div>

        <div class="col-md-4">
            <label for="filtro_dueno" class="form-label">Nombre del dueño:</label>
            <input type="text" name="filtro_dueno" id="filtro_dueno" class="form-control" placeholder="Ej. Juan Pérez" value="<?= htmlspecialchars($filtro_dueno) ?>">
        </div>

        <div class="col-md-3">
            <label for="filtro_estado" class="form-label">Estado:</label>
            <select name="filtro_estado" id="filtro_estado" class="form-select">
                <option value="">-- Todos --</option>
                <option value="disponible" <?= $filtro_estado === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                <option value="vendido" <?= $filtro_estado === 'vendido' ? 'selected' : '' ?>>Vendido</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="filtro_vencimiento" class="form-label">Vencimiento:</label>
            <select name="filtro_vencimiento" id="filtro_vencimiento" class="form-select">
                <option value="">-- Todos --</option>
                <option value="proximos" <?= isset($_GET['filtro_vencimiento']) && $_GET['filtro_vencimiento'] === 'proximos' ? 'selected' : '' ?>>Próx. a vencer (7 días)</option>
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100"><i class="fas fa-filter me-2"></i>Filtrar</button>
        </div>
    </form>



    <div class="table-responsive tarjeta-registro p-3 rounded-4 shadow-sm border">
        <table class="table tabla-personalizada align-middle table-hover text-center">
            <thead class="encabezado-degradado text-white" style="background: linear-gradient(90deg, #198754, #2ecc71);">
                <tr>
                    <th><i class="fas fa-id-badge me-1"></i> ID</th>
                    <th><i class="fas fa-heading me-1"></i> Título</th>
                    <th><i class="fas fa-dog me-1"></i> Tipo</th>
                    <th><i class="fas fa-user me-1"></i> Dueño</th>
                    <th><i class="fas fa-circle me-1"></i> Estado</th>
                    <th><i class="fas fa-calendar-alt me-1"></i> Fecha fin</th>
                    <th><i class="fas fa-file-invoice-dollar me-1"></i> Soporte pago</th>
                    <th><i class="fas fa-percent me-1"></i> Comisión</th>
                    <th><i class="fas fa-cogs me-1"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lista_completa)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted fst-italic py-4">No hay animales registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lista_paginada as $animal):
                        $usuario = RepositorioUsuario::obtener_usuario_por_id($conexion, $animal->obtener_id_usuario());
                        if (method_exists($animal, 'obtener_tipo_animal')) {
                            $tipo_mostrar = $animal->obtener_tipo_animal() ?: $animal->obtener_categoria();
                            $tipo_form = 'animal';
                        } else {
                            $tipo_mostrar = $animal->obtener_categoria();
                            $tipo_form = 'caballo';
                        }
                        // ...existing code...
                    ?>
                        <tr>
                            <td class="fw-semibold celda-id">
                                <?= $tipo_form . " - " . $animal->obtener_id(); ?>
                            </td>
                            <td><?= htmlspecialchars($animal->obtener_titulo()); ?></td>
                            <td><?= htmlspecialchars($tipo_mostrar); ?></td>
                            <td><?= htmlspecialchars($usuario ? $usuario->obtener_nombre() : 'N/A'); ?></td>
                            <td>
                                <?php if ($animal->esta_vendido()): ?>
                                    <span class="badge bg-secondary">Vendido</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Disponible</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($animal->esta_vendido()): ?>
                                    <span class="text-muted fst-italic">Publicación finalizada</span>
                                <?php else: ?>
                                    <?= fecha_espanol($animal->obtener_fecha_fin()); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (method_exists($animal, 'obtener_soporte_pago') && $animal->obtener_soporte_pago()): ?>
                                    <a href="<?= htmlspecialchars($animal->obtener_soporte_pago()); ?>"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-danger shadow-sm d-inline-flex align-items-center">
                                        <i class="fas fa-file-pdf me-2"></i> Ver comprobante
                                    </a>
                                <?php endif; ?>

                            </td>
                            <td>
                                <?php if (method_exists($animal, 'obtener_valor_comision') && $animal->obtener_valor_comision()): ?>
                                    <span class="text-success fw-semibold">
                                        $<?= number_format($animal->obtener_valor_comision(), 0, ',', '.'); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">No aplica</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                        id="dropdownPublicacion<?= $animal->obtener_id() ?>"
                                        data-bs-toggle="dropdown" aria-expanded="false" style="border: none;">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownPublicacion<?= $animal->obtener_id() ?>">
                                        <li>
                                            <a class="dropdown-item text-primary" href="<?= SERVIDOR ?>/admin-editar-<?= $tipo_form ?>/<?= $tipo_form ?>/<?= $animal->obtener_id(); ?>">
                                                <i class="fas fa-edit me-2"></i>Editar publicación
                                            </a>
                                        </li>


                                        <?php if (!$animal->esta_vendido()): ?>
                                            <li>
                                                <form id="form-vendido-<?= $tipo_form . '-' . $animal->obtener_id() ?>" method="POST" style="display:none;">
                                                    <input type="hidden" name="id" value="<?= $animal->obtener_id(); ?>">
                                                    <input type="hidden" name="id_usuario" value="<?= $animal->obtener_id_usuario(); ?>">
                                                    <input type="hidden" name="tipo" value="<?= $tipo_form; ?>">
                                                    <input type="hidden" name="marcar_vendido" value="1">
                                                </form>
                                                <button class="dropdown-item text-warning"
                                                    onclick="document.getElementById('form-vendido-<?= $tipo_form . '-' . $animal->obtener_id() ?>').submit();">
                                                    <i class="fas fa-check me-2"></i>Marcar como vendido
                                                </button>

                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <form id="form-disponible-<?= $tipo_form . '-' . $animal->obtener_id() ?>" method="POST" style="display:none;">
                                                    <input type="hidden" name="id" value="<?= $animal->obtener_id(); ?>">
                                                    <input type="hidden" name="id_usuario" value="<?= $animal->obtener_id_usuario(); ?>">
                                                    <input type="hidden" name="tipo" value="<?= $tipo_form; ?>">
                                                    <input type="hidden" name="marcar_disponible" value="1">
                                                </form>
                                                <button class="dropdown-item text-success"
                                                    onclick="document.getElementById('form-disponible-<?= $tipo_form . '-' . $animal->obtener_id() ?>').submit();">
                                                    <i class="fas fa-undo me-2"></i>Marcar como disponible
                                                </button>

                                            </li>
                                        <?php endif; ?>
                                        <li>
                                            <form id="form-eliminar-<?= $tipo_form . '-' . $animal->obtener_id() ?>" method="POST" style="display:none;">
                                                <input type="hidden" name="id" value="<?= $animal->obtener_id(); ?>">
                                                <input type="hidden" name="id_usuario" value="<?= $animal->obtener_id_usuario(); ?>">
                                                <input type="hidden" name="tipo" value="<?= $tipo_form; ?>">
                                                <input type="hidden" name="eliminar_animal" value="1">
                                            </form>
                                            <button class="dropdown-item text-danger"
                                                onclick="if(confirm('¿Eliminar este animal? Esta acción no se puede deshacer.')) document.getElementById('form-eliminar-<?= $tipo_form . '-' . $animal->obtener_id() ?>').submit();">
                                                <i class="fas fa-trash-alt me-2"></i>Eliminar publicación
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if ($total_paginas > 1): ?>
            <nav class="mt-5 animate-fade-in">
                <ul class="pagination pagination-rounded justify-content-center">

                    <!-- Botón anterior -->
                    <li class="page-item <?= $pagina_actual == 1 ? 'disabled' : '' ?>">
                        <a class="page-link"
                            href="?pagina=<?= $pagina_actual - 1 ?>&filtro_id=<?= urlencode($filtro_id) ?>&filtro_dueno=<?= urlencode($filtro_dueno) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>">
                            &laquo;
                        </a>
                    </li>

                    <?php
                    $max_paginas = 10;
                    $inicio = max(1, $pagina_actual - 4);
                    $fin = min($total_paginas, $inicio + $max_paginas - 1);

                    if ($inicio > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=1&filtro_id=<?= urlencode($filtro_id) ?>&filtro_dueno=<?= urlencode($filtro_dueno) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>">1</a>
                        </li>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>

                    <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                        <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                            <a class="page-link <?= $i == $pagina_actual ? 'bg-success text-white border-success' : '' ?>"
                                href="?pagina=<?= $i ?>&filtro_id=<?= urlencode($filtro_id) ?>&filtro_dueno=<?= urlencode($filtro_dueno) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($fin < $total_paginas): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?= $total_paginas ?>&filtro_id=<?= urlencode($filtro_id) ?>&filtro_dueno=<?= urlencode($filtro_dueno) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>"><?= $total_paginas ?></a>
                        </li>
                    <?php endif; ?>

                    <!-- Botón siguiente -->
                    <li class="page-item <?= $pagina_actual == $total_paginas ? 'disabled' : '' ?>">
                        <a class="page-link"
                            href="?pagina=<?= $pagina_actual + 1 ?>&filtro_id=<?= urlencode($filtro_id) ?>&filtro_dueno=<?= urlencode($filtro_dueno) ?>&filtro_estado=<?= urlencode($filtro_estado) ?>">
                            &raquo;
                        </a>
                    </li>

                </ul>
            </nav>
        <?php endif; ?>


    </div>


    <?php include_once 'plantillas/documento-cierre.inc.php'; ?>