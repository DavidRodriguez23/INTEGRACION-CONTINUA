<!-- perfil.php -->
<?php

include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/Redireccion.inc.php';

$titulo = 'Perfil de Usuario';

if (!ControlSesion::sesion_iniciada()) {
    Redireccion::redirigir(SERVIDOR);
}

Conexion::abrir_conexion();
$usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $_SESSION['id_usuario']);

if (!$usuario) {
    echo '<div class="alert alert-danger mt-4">No se pudo cargar la información del usuario.</div>';
    exit;
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



// -------------------------
// Subida de imagen de perfil
// -------------------------
$mensaje_swal = '';

if (isset($_POST['guardar_imagen']) && !empty($_FILES['archivo_subido']['tmp_name'])) {
    $directorio = DIRECTORIO_RAIZ . '/subidas/';
    $nombre_archivo = $usuario->obtener_id() . '.jpg';
    $ruta_objetivo = $directorio . $nombre_archivo;
    $tipo_imagen = strtolower(pathinfo($_FILES['archivo_subido']['name'], PATHINFO_EXTENSION));
    $es_valida = true;

    if (!getimagesize($_FILES['archivo_subido']['tmp_name'])) {
        $mensaje_swal = "Swal.fire({icon: 'error', title: 'Archivo inválido', text: 'El archivo no es una imagen válida.'});";
        $es_valida = false;
    }

    if ($_FILES['archivo_subido']['size'] > 1000000) {
        $mensaje_swal = "Swal.fire({icon: 'error', title: 'Archivo demasiado grande', text: 'La imagen debe pesar menos de 1MB.'});";
        $es_valida = false;
    }

    if (!in_array($tipo_imagen, ['jpg', 'jpeg', 'png'])) {
        $mensaje_swal = "Swal.fire({icon: 'error', title: 'Tipo de archivo no permitido', text: 'Solo se permiten imágenes JPG, JPEG o PNG.'});";
        $es_valida = false;
    }

    if ($es_valida) {
        if (move_uploaded_file($_FILES['archivo_subido']['tmp_name'], $ruta_objetivo)) {
            $mensaje_swal = "Swal.fire({icon: 'success', title: '¡Éxito!', text: 'Imagen actualizada correctamente.'});";
        } else {
            $mensaje_swal = "Swal.fire({icon: 'error', title: 'Error al subir', text: 'Hubo un problema al guardar la imagen.'});";
        }
    }
}

// Recuperar publicaciones del usuario (animales y caballos)
include_once 'app/RepositorioAnimal.inc.php';
include_once 'app/RepositorioCaballo.inc.php';

$publicaciones_animales = RepositorioAnimal::obtener_animales_por_usuario(Conexion::obtener_conexion(), $usuario->obtener_id());
$publicaciones_caballos = RepositorioCaballo::obtener_caballos_por_usuario(Conexion::obtener_conexion(), $usuario->obtener_id());

$publicaciones_totales = array_merge($publicaciones_animales, $publicaciones_caballos);

$publicaciones_activas = array_filter($publicaciones_totales, function ($pub) {
    return method_exists($pub, 'esta_vendido') ? !$pub->esta_vendido() : true;
});

$publicaciones_vendidas = array_filter($publicaciones_totales, function ($pub) {
    return method_exists($pub, 'esta_vendido') && $pub->esta_vendido();
});

// Ordenar ambos arrays por fecha descendente
usort($publicaciones_activas, function ($a, $b) {
    $fechaA = method_exists($a, 'obtener_creado_en') ? $a->obtener_creado_en() : (method_exists($a, 'obtener_fecha_publicacion') ? $a->obtener_fecha_publicacion() : '');
    $fechaB = method_exists($b, 'obtener_creado_en') ? $b->obtener_creado_en() : (method_exists($b, 'obtener_fecha_publicacion') ? $b->obtener_fecha_publicacion() : '');
    return strtotime($fechaB) <=> strtotime($fechaA);
});

usort($publicaciones_vendidas, function ($a, $b) {
    $fechaA = method_exists($a, 'obtener_creado_en') ? $a->obtener_creado_en() : (method_exists($a, 'obtener_fecha_publicacion') ? $a->obtener_fecha_publicacion() : '');
    $fechaB = method_exists($b, 'obtener_creado_en') ? $b->obtener_creado_en() : (method_exists($b, 'obtener_fecha_publicacion') ? $b->obtener_fecha_publicacion() : '');
    return strtotime($fechaB) <=> strtotime($fechaA);
});

?>

<?php include_once 'plantillas/documento-apertura.inc.php'; ?>
<?php include_once 'plantillas/navbar.inc.php'; ?>
<br><br><br>

<div class="container mt-4 mb-4 perfil">
    <div class="row justify-content-center">
        <!-- Foto de perfil -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-4 text-center h-100 rounded-4 border-success border-2">
                <h4 class="text-success fw-bold mb-4">Tu Foto de Perfil</h4>
                <?php
                $ruta_imagen = SERVIDOR . '/subidas/' . $usuario->obtener_id() . '.jpg';
                if (file_exists(DIRECTORIO_RAIZ . '/subidas/' . $usuario->obtener_id() . '.jpg')) {
                    echo '<img src="' . $ruta_imagen . '" class="img-fluid rounded-circle mb-4 border border-3 border-success mx-auto d-block shadow-sm" style="max-width: 180px; object-fit: cover;">';
                } else {
                    echo '<img src="' . SERVIDOR . '/subidas/Logo-ganandez.jpg" class="img-fluid rounded-circle mb-4 border border-3 border-secondary mx-auto d-block shadow-sm" style="max-width: 180px; object-fit: cover;">';
                }
                ?>
                <form method="post" enctype="multipart/form-data" class="d-flex flex-column align-items-center gap-3">
                    <label for="archivo_subido" class="btn btn-outline-success px-4 fw-semibold mb-0" style="cursor: pointer;">Seleccionar Imagen</label>
                    <input type="file" name="archivo_subido" id="archivo_subido" class="form-control d-none" accept="image/png, image/jpeg, image/jpg">
                    <small id="nombre-archivo" class="text-muted fst-italic"></small>
                    <button type="submit" name="guardar_imagen" class="btn btn-success px-5 fw-semibold shadow-sm">Guardar</button>
                </form>
            </div>
        </div>

        <!-- Datos del usuario -->
        <div class="col-md-6">
            <div class="card rounded-4 shadow border-0 animar-entrada">
                <div class="bg-success bg-gradient rounded-top p-3">
                    <h4 class="mb-0 text-white fw-bold text-center">Información del Usuario</h4>
                </div>
                <div class="p-4 fondo-registro bg-white rounded-bottom">
                    <p><strong>Nombre:</strong> <span class="text-secondary"><?= htmlspecialchars($usuario->obtener_nombre()) ?></span></p>
                    <p><strong>Identificación:</strong> <span class="text-secondary"><?= htmlspecialchars($usuario->obtener_identificacion()) ?></span></p>
                    <p><strong>Correo:</strong> <span class="text-secondary"><?= htmlspecialchars($usuario->obtener_correo()) ?></span></p>
                    <p><strong>Teléfono:</strong> <span class="text-secondary"><?= htmlspecialchars($usuario->obtener_telefono()) ?></span></p>
                    <p><strong>Fecha de registro:</strong> <span class="text-secondary"><?= fecha_espanol($usuario->obtener_fecha_registro()) ?></span></p>
                    <p><strong>Tipo de insumos:</strong> <span class="text-secondary"><?= htmlspecialchars($usuario->obtener_tipo_insumos()) ?></span></p>
                    <p><strong>Estado:</strong>
                        <?php if ($usuario->obtener_estado_usuario() == 1): ?>
                            <span class="badge bg-success text-white px-3 py-1 rounded-pill">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary text-white px-3 py-1 rounded-pill">Inactivo</span>
                        <?php endif; ?>
                    </p>
                    <!--<div class="d-flex justify-content-center mt-4">
                        <a href="editar_perfil.php" class="btn btn-outline-success fw-semibold px-4 shadow-sm">Editar Perfil</a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mensaje emergente SweetAlert2 -->
<?php if (!empty($mensaje_swal)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php echo $mensaje_swal; ?>
        });
    </script>
<?php endif; ?>

<script>
    document.getElementById('archivo_subido').addEventListener('change', function() {
        const archivo = this.files[0];
        const nombreArchivoSpan = document.getElementById('nombre-archivo');
        nombreArchivoSpan.textContent = archivo ? 'Archivo seleccionado: ' + archivo.name : '';
    });

    document.querySelector('label[for="archivo_subido"]').addEventListener('click', function() {
        document.getElementById('archivo_subido').click();
    });
</script>

<div class="container mt-5">
    <?php if (isset($_GET['eliminado']) && $_GET['eliminado'] == 1): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Publicación eliminada!',
                text: 'Tu publicación se eliminó correctamente.',
                confirmButtonColor: '#28a745'
            });
        </script>
    <?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo eliminar la publicación.',
                confirmButtonColor: '#d33'
            });
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['vendido']) && $_GET['vendido'] == 1): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Publicación marcada como vendida!',
                text: 'La publicación ha sido desactivada correctamente.',
                confirmButtonColor: '#28a745'
            });
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['editado']) && $_GET['editado'] == 1): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Cambios guardados!',
                text: 'La publicación se actualizó correctamente.',
                confirmButtonColor: '#28a745'
            });
        </script>
    <?php endif; ?>

    <h3 class="text-success mb-4 fw-bold text-center">Tus publicaciones</h3>

    <!-- Vista en tabla -->
    <div class="table-responsive mb-5 tarjeta-registro p-3 rounded-4 shadow-sm border">
        <table class="table tabla-personalizada align-middle table-hover text-center">
            <thead class="encabezado-degradado text-white" style="background: linear-gradient(90deg, #198754, #2ecc71);">
                <tr>
                    <th>Título</th>
                    <th><i class="fas fa-tag me-1"></i> Categoría</th>
                    <th><i class="fas fa-dollar-sign me-1"></i> Precio</th>
                    <th><i class="fas fa-calendar-alt me-1"></i> Fecha de fin</th>
                    <th><i class="fas fa-paw me-1"></i> Tipo</th>
                    <th><i class="fas fa-cogs me-1"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($publicaciones_activas && count($publicaciones_activas) > 0): ?>
                    <?php foreach ($publicaciones_activas as $pub): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($pub->obtener_titulo()) ?></td>
                            <td><?= htmlspecialchars($pub->obtener_categoria()) ?></td>
                            <td class="text-success fw-bold">
                                <?php
                                if (method_exists($pub, 'obtener_precio')) {
                                    echo number_format($pub->obtener_precio(), 0, ',', '.') . ' COP';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (method_exists($pub, 'obtener_fecha_fin') && $pub->obtener_fecha_fin() !== null) {
                                    echo '<i class="fas fa-calendar-alt text-success me-1" title="Fecha fin"></i> ' . htmlspecialchars(fecha_espanol($pub->obtener_fecha_fin()));
                                } else {
                                    echo '<span class="text-muted fst-italic" title="Sin fecha de finalización">—</span>';
                                }
                                ?>
                            </td>
                            <td><?= ($pub instanceof Animal) ? '<span class="badge bg-success">Ganado</span>' : '<span class="badge bg-info text-dark">Caballo</span>'; ?></td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                        id="dropdownMenuButton<?= $pub->obtener_id() ?>"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        style="border:none;">
                                        <i class="fas fa-ellipsis-v"></i>
                                        <span class="visually-hidden">Abrir menú de acciones</span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?= $pub->obtener_id() ?>">
                                        <li>
                                            <button class="dropdown-item text-danger" type="button" onclick="eliminarPublicacion('<?= ($pub instanceof Animal) ? 'animal' : 'caballo' ?>', <?= $pub->obtener_id() ?>)">
                                                <i class="fas fa-trash-alt me-2"></i>Eliminar publicación
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-success" type="button" onclick="publicacionVendida('<?= ($pub instanceof Animal) ? 'animal' : 'caballo' ?>', <?= $pub->obtener_id() ?>)">
                                                <i class="fas fa-check-circle me-2"></i>Marcar como vendida
                                            </button>
                                        </li>
                                        <li>
                                            <?php if ($pub instanceof Animal): ?>
                                                <a class="dropdown-item" href="<?= RUTA_EDITAR_ANIMAL_USUARIO ?>/animal/<?= $pub->obtener_id() ?>">
                                                    <i class="fas fa-edit me-2"></i>Editar publicación
                                                </a>
                                            <?php else: ?>
                                                <a class="dropdown-item" href="<?= RUTA_EDITAR_ANIMAL_USUARIO ?>/caballo/<?= $pub->obtener_id() ?>">
                                                    <i class="fas fa-edit me-2"></i>Editar publicación
                                                </a>
                                            <?php endif; ?>
                                        </li>
                                    </ul>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted fst-italic py-4">No tienes publicaciones aún.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Vista en tarjetas -->
    <div class="row g-4 text-center">
        <?php
        include_once 'app/EscritorAnimal.inc.php';
        ?>
        <?php if ($publicaciones_activas && count($publicaciones_activas) > 0): ?>
            <?php foreach ($publicaciones_activas as $pub): ?>
                <?php EscritorAnimal::escribir_tarjeta_animal($pub); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <div class="alert alert-info">No tienes publicaciones aún.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($publicaciones_vendidas)): ?>
    <hr class="my-5">
    <h3 class="text-secondary mb-4 fw-bold text-center">Tus publicaciones vendidas</h3>

    <div class="row g-4 text-center">
        <?php foreach ($publicaciones_vendidas as $pub): ?>
            <?php EscritorAnimal::escribir_tarjeta_animal($pub); ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
    function eliminarPublicacion(tipo, id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará la publicación permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= RUTA_ELIMINAR_PUBLICACION ?>.php?tipo=' + tipo + '&id=' + id;
            }
        });
    }

    function publicacionVendida(tipo, id) {
        Swal.fire({
            title: '¿Marcar como vendida?',
            text: "La publicación será marcada como vendida.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, marcar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= RUTA_PUBLICACION_VENDIDA ?>.php?tipo=' + tipo + '&id=' + id;
            }
        });
    }
</script>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>