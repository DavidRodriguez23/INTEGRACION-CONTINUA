<?php
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/RepositorioCaballo.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/caballo.inc.php'; // Make sure this path is correct for the Caballo class

if (!ControlSesion::es_admin()) {
    Redireccion::redirigir(SERVIDOR);
    exit();
}

Conexion::abrir_conexion();

$id = $_GET['id'] ?? null;
if (!$id) {
    Redireccion::redirigir(RUTA_ADMIN_PUBLICACIONES);
    exit();
}

// Cambiado $animal a $caballo para consistencia con el formulario de edición
$caballo = RepositorioCaballo::obtener_caballo_por_id(Conexion::obtener_conexion(), $id);
if (!$caballo) {
    Redireccion::redirigir(RUTA_ADMIN_PUBLICACIONES);
    exit();
}

function normalizar_rutas_json(array $rutas): string
{
    return json_encode($rutas, JSON_UNESCAPED_SLASHES);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_caballo'])) {
    $conexion = Conexion::obtener_conexion();

    $id_caballo = $_POST['id_caballo'];

    $usuario_id = $_SESSION['id_usuario'] ?? null;
    if (!$usuario_id) {
        error_log("Error: id_usuario no encontrado en la sesión al editar caballo.");
        Redireccion::redirigir(RUTA_LOGIN);
        exit();
    }
    $stmt_user_name = $conexion->prepare("SELECT u.nombre FROM usuarios u JOIN caballos a ON u.id = a.id_usuario WHERE a.id = ?");
    $stmt_user_name->execute([$id_caballo]);
    $nombre_usuario_publicacion = $stmt_user_name->fetchColumn();

    if (!$nombre_usuario_publicacion) {
        error_log("Error: No se encontró el nombre de usuario del propietario original para el caballo ID: " . $id_caballo);
        $nombre_usuario_publicacion = $_SESSION['nombre_usuario'] ?? 'usuario_desconocido';
    }
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $raza = $_POST['raza'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $edad = $_POST['edad'] ?? '';
    $peso = floatval($_POST['peso'] ?? 0);
    $precio = floatval($_POST['precio'] ?? 0);
    $caracteristicas = $_POST['caracteristicas'] ?? [];
    $caracteristicas_json = json_encode($caracteristicas, JSON_UNESCAPED_SLASHES);
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $departamento = $_POST['departamento'] ?? '';
    $municipio = $_POST['municipio'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $destacado = isset($_POST['destacado']) ? 1 : 0;
    $premium = isset($_POST['premium']) ? 1 : 0;
    $sugerido = isset($_POST['sugerido']) ? 1 : 0;
    $latitud = floatval($_POST['latitud'] ?? 0);
    $longitud = floatval($_POST['longitud'] ?? 0);
    // Use the value from the form if present, otherwise keep the existing value, or default to current time
    $fecha_fin = $_POST['fecha_fin'];

    $mime_imagenes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $mime_videos = ['video/mp4', 'video/webm', 'video/ogg'];

    function es_valido_mime($file_tmp, $tipos_validos)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);
        return in_array($mime, $tipos_validos);
    }

    $directorio_base = "usuarios/";

    // Obtener imágenes y videos actuales desde el objeto $caballo
    $current_images = $caballo->obtener_imagenes();
    if (!is_array($current_images)) {
        $current_images = json_decode($current_images, true);
        $current_images = is_array($current_images) ? $current_images : [];
    }

    $imagenes_actuales_db = $current_images;


    foreach ($current_images as $img_path) {
        $img_url = '/Ganandez/' . ltrim($img_path, '/');
        echo '<div class="position-relative me-2 mb-2">';
        echo '<img src="' . htmlspecialchars($img_url) . '" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">';
        echo '<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="eliminarImagenExistente(this, \'' . htmlspecialchars($img_path, ENT_QUOTES, 'UTF-8') . '\')">&times;</button>';
        echo '</div>';
    }



    $videos_actuales_db = $caballo->obtener_videos();
    $current_videos = $caballo->obtener_videos();
    if (!is_array($current_videos)) {
        $current_videos = json_decode($current_videos, true);
        $current_videos = is_array($current_videos) ? $current_videos : [];
    }

    if (!empty($current_videos)) {
        $vid_url = '/Ganandez/' . ltrim($vid_path, '/');
        echo '<div class="position-relative">';
        echo '<video src="' . htmlspecialchars($vid_url) . '" controls class="img-thumbnail" style="width: 200px;"></video>';
        echo '<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="eliminarVideoExistente(this, \'' . htmlspecialchars($vid_path, ENT_QUOTES, 'UTF-8') . '\')">&times;</button>';
        echo '</div>';
    }


    // Determinar la carpeta de la publicación. Es crucial para mantener la consistencia de las rutas.
    $carpeta_publicacion = '';
    if (!empty($imagenes_actuales_db)) {
        $path_for_splitting = str_replace('\/', '/', $imagenes_actuales_db[0]);
        $partes_ruta = explode('/', $path_for_splitting);
        if (count($partes_ruta) >= 3) {
            $carpeta_publicacion = $partes_ruta[2];
        }
    }
    if (empty($carpeta_publicacion) && !empty($videos_actuales_db)) {
        $path_for_splitting = str_replace('\/', '/', $videos_actuales_db[0]);
        $partes_ruta = explode('/', $path_for_splitting);
        if (count($partes_ruta) >= 3) {
            $carpeta_publicacion = $partes_ruta[2];
        }
    }
    if (empty($carpeta_publicacion)) {
        $carpeta_publicacion = 'venta_' . uniqid();
    }

    $ruta_base = $directorio_base . $nombre_usuario_publicacion . '/' . $carpeta_publicacion . '/';

    if (substr($ruta_base, -1) !== '/') {
        $ruta_base .= '/';
    }
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/Ganandez/" . $ruta_base;
    $upload_dir_relativo = $ruta_base;

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
        chmod($upload_dir, 0777);
    }
    if (!empty($_POST['borrar_imagenes']) && is_array($_POST['borrar_imagenes'])) {
        foreach ($_POST['borrar_imagenes'] as $img_a_borrar) {
            $path_limpio = str_replace('\/', '/', $img_a_borrar);
            $path = $_SERVER['DOCUMENT_ROOT'] . "/Ganandez/" . $path_limpio;
            if (file_exists($path)) {
                unlink($path);
            }
            $key = array_search($img_a_borrar, $imagenes_actuales_db);
            if ($key !== false) {
                unset($imagenes_actuales_db[$key]);
            }
        }
    }
    if (!empty($_POST['fotos_tmp']) && !empty($_POST['fotos_type'])) {
        foreach ($_POST['fotos_tmp'] as $i => $base64) {
            $data = base64_decode($base64);
            $tipo = $_POST['fotos_type'][$i];
            $ext = explode('/', $tipo)[1];
            $nombre_archivo = uniqid('img_', true) . '.' . $ext;
            $ruta_final = $upload_dir . $nombre_archivo;
            $ruta_relativa = $upload_dir_relativo . $nombre_archivo;
            if (file_put_contents($ruta_final, $data)) {
                $imagenes_actuales_db[] = $ruta_relativa;
            }
        }
    }

    // 3. Subir nuevas imágenes (uploaded via input file)
    if (isset($_FILES['fotos']) && !empty($_FILES['fotos']['name'][0])) {
        foreach ($_FILES['fotos']['tmp_name'] as $index => $tmpPath) {
            if ($_FILES['fotos']['error'][$index] === UPLOAD_ERR_OK && es_valido_mime($tmpPath, $mime_imagenes)) {
                $ext = strtolower(pathinfo($_FILES['fotos']['name'][$index], PATHINFO_EXTENSION));
                $nombre_archivo = uniqid('img_', true) . '.' . $ext;
                $ruta_final = $upload_dir . $nombre_archivo;
                $ruta_relativa = $upload_dir_relativo . $nombre_archivo;
                if (move_uploaded_file($tmpPath, $ruta_final)) {
                    $imagenes_actuales_db[] = $ruta_relativa;
                }
            }
        }
    }

    if (!is_array($videos_actuales_db)) {
        $videos_actuales_db = json_decode($videos_actuales_db, true);
        if (!is_array($videos_actuales_db)) {
            $videos_actuales_db = [];
        }
    }

    if (!empty($_POST['borrar_videos']) && is_array($_POST['borrar_videos'])) {
        foreach ($_POST['borrar_videos'] as $vid_a_borrar) {
            $path_limpio = str_replace('\/', '/', $vid_a_borrar);
            $path = $_SERVER['DOCUMENT_ROOT'] . "/Ganandez/" . $path_limpio;
            if (file_exists($path)) {
                unlink($path);
            }
            $key = array_search($vid_a_borrar, $videos_actuales_db);
            if ($key !== false) {
                unset($videos_actuales_db[$key]);
            }
        }
    }


    if (!empty($_POST['videos_tmp']) && !empty($_POST['videos_type'])) {
        foreach ($_POST['videos_tmp'] as $i => $base64) {
            $data = base64_decode($base64);
            $tipo = $_POST['videos_type'][$i];
            $ext = explode('/', $tipo)[1];
            $nombre_archivo = uniqid('vid_', true) . '.' . $ext;
            $ruta_final = $upload_dir . $nombre_archivo;
            $ruta_relativa = $upload_dir_relativo . $nombre_archivo;
            if (file_put_contents($ruta_final, $data)) {
                $videos_actuales_db[] = $ruta_relativa;
            }
        }
    }

    // 3. Subir nuevos videos (uploaded via input file)
    if (isset($_FILES['videos']) && !empty($_FILES['videos']['name'][0])) {
        foreach ($_FILES['videos']['tmp_name'] as $index => $tmpPath) {
            if ($_FILES['videos']['error'][$index] === UPLOAD_ERR_OK && es_valido_mime($tmpPath, $mime_videos)) {
                $ext = strtolower(pathinfo($_FILES['videos']['name'][$index], PATHINFO_EXTENSION));
                $nombre_archivo = uniqid('vid_', true) . '.' . $ext;
                $ruta_final = $upload_dir . $nombre_archivo;
                $ruta_relativa = $upload_dir_relativo . $nombre_archivo;
                if (move_uploaded_file($tmpPath, $ruta_final)) {
                    $videos_actuales_db[] = $ruta_relativa;
                }
            }
        }
    }
    $imagenes_json = normalizar_rutas_json(array_values($imagenes_actuales_db));
    $videos_json = normalizar_rutas_json(array_values($videos_actuales_db));
    $fecha_publicacion = $caballo->obtener_fecha_publicacion();
    $terminos = $caballo->obtener_terminos();
    $vendido = $caballo->esta_vendido();

    $caballo_actualizado = new Caballo(
        $id_caballo,
        $titulo,
        $descripcion,
        $categoria,
        $raza,
        $sexo,
        $edad,
        $peso,
        $precio,
        $caracteristicas_json,
        $imagenes_json,
        $videos_json,
        $telefono,
        $correo,
        $departamento,
        $municipio,
        $direccion,
        $latitud,
        $longitud,
        $destacado,
        $premium,
        $sugerido,
        $fecha_publicacion,
        $terminos,
        date('Y-m-d H:i:s', strtotime($fecha_fin)),
        $usuario_id,
        $vendido
    );

    try {
        $resultado = RepositorioCaballo::actualizar_caballo_admin($conexion, $caballo_actualizado);
        if ($resultado) {
            $_SESSION['mensaje_exito'] = "Caballo editado correctamente.";
            Redireccion::redirigir(RUTA_ADMIN_PUBLICACIONES);
            exit();
        } else {
            throw new Exception("No se pudo actualizar el caballo.");
        }
    } catch (Exception $e) {
        echo "Error al actualizar: " . $e->getMessage();
    }
}
?>

<?php include_once 'plantillas/documento-apertura.inc.php'; ?>
<?php include_once 'plantillas/navbar.inc.php'; ?>

<br><br>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header encabezado-degradado text-center">
                    <h3 class="titulo-encabezado mb-2">
                        <i class="fas fa-paw me-2"></i> Editar caballo
                    </h3>
                    <p class="subtitulo-registro">Modifica la información de tu ejemplar</p>
                </div>

                <div class="card-body fondo-registro">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <input type="hidden" name="id_caballo" value="<?= htmlspecialchars($caballo->obtener_id()); ?>">

                        <div class="mb-3">
                            <label for="titulo" class="form-label text-success">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="50"
                                placeholder="Ej. Novillo de engorde" value="<?= htmlspecialchars($caballo->obtener_titulo()); ?>">
                            <div class="d-flex justify-content-between">
                                <small class="form-text text-muted">Máximo 50 caracteres para el título del ejemplar.</small>
                                <small id="titulo-counter" class="text-muted"><?= 50 - mb_strlen($caballo->obtener_titulo()); ?> restantes</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label text-success">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required maxlength="300"
                                placeholder="Describe el animal..."><?= htmlspecialchars($caballo->obtener_descripcion()); ?></textarea>
                            <div class="d-flex justify-content-between">
                                <small class="form-text text-muted">Máximo 300 caracteres para la descripción del animal.</small>
                                <small id="descripcion-counter" class="text-muted"><?= 300 - mb_strlen($caballo->obtener_descripcion()); ?> restantes</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="categoria" class="form-label text-success">Categoría</label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="">Selecciona una categoría</option>
                                <option value="Caballo Criollo Colombiano" <?= $caballo->obtener_categoria() === 'Caballo Criollo Colombiano' ? 'selected' : '' ?>>Caballo Criollo Colombiano</option>
                                <option value="Caballo Percherón" <?= $caballo->obtener_categoria() === 'Caballo Percherón' ? 'selected' : '' ?>>Caballo Percherón</option>
                                <option value="Caballo Árabe" <?= $caballo->obtener_categoria() === 'Caballo Árabe' ? 'selected' : '' ?>>Caballo Árabe</option>
                                <option value="Cuarto de Milla" <?= $caballo->obtener_categoria() === 'Cuarto de Milla' ? 'selected' : '' ?>>Cuarto de Milla</option>
                                <option value="Caballos Mulares" <?= $caballo->obtener_categoria() === 'Caballos Mulares' ? 'selected' : '' ?>>Caballos Mulares</option>
                                <option value="Otros" <?= $caballo->obtener_categoria() === 'Otros' ? 'selected' : '' ?>>Otros</option>
                            </select>
                            <small class="form-text text-muted">Seleccione la categoría del animal.</small>
                        </div>

                        <div class="mb-3">
                            <label for="raza" class="form-label text-success">Raza</label>
                            <input
                                type="text"
                                class="form-control"
                                id="raza"
                                name="raza"
                                value="<?= htmlspecialchars($caballo->obtener_raza(), ENT_QUOTES, 'UTF-8'); ?>"
                                placeholder="Ingresa la raza del animal"
                                required>
                            <small class="form-text text-muted">Ingrese la raza del animal.</small>
                        </div>


                        <div class="mb-3">
                            <label class="form-label text-success">Sexo</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sexo" id="Macho_castrado" value="Macho castrado" required <?= $caballo->obtener_sexo() === 'Macho castrado' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="Macho_castrado">Macho castrado</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sexo" id="Macho_entero" value="Macho entero" <?= $caballo->obtener_sexo() === 'Macho entero' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="Macho_entero">Macho entero</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sexo" id="macho" value="macho" required <?= $caballo->obtener_sexo() === 'macho' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="macho">Macho</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sexo" id="hembra" value="hembra" <?= $caballo->obtener_sexo() === 'hembra' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="hembra">Hembra</label>
                            </div>
                            <small class="form-text text-muted">Seleccione el sexo del animal.</small>
                        </div>

                        <div class="mb-3">
                            <label for="edad" class="form-label text-success">Edad</label>
                            <select class="form-select" id="edad" name="edad" required>
                                <option value="">Selecciona la edad</option>
                                <optgroup label="Meses">
                                    <?php for ($i = 1; $i <= 12; $i++) {
                                        $texto = $i . ' ' . ($i == 1 ? 'mes' : 'meses');
                                        $selected = $caballo->obtener_edad() === $texto ? 'selected' : '';
                                        echo "<option value='{$texto}' {$selected}>{$texto}</option>";
                                    } ?>
                                </optgroup>
                                <optgroup label="Años">
                                    <?php for ($i = 1; $i <= 6; $i++) {
                                        $texto = $i . ' ' . ($i == 1 ? 'año' : 'años');
                                        $selected = $caballo->obtener_edad() === $texto ? 'selected' : '';
                                        echo "<option value='{$texto}' {$selected}>{$texto}</option>";
                                    } ?>
                                    <option value="Más de 6 años" <?= $caballo->obtener_edad() === 'Más de 6 años' ? 'selected' : '' ?>>Más de 6 años</option>
                                </optgroup>
                            </select>
                            <small class="form-text text-muted">Indique la edad del animal.</small>
                        </div>

                        <div class="mb-3">
                            <label for="peso" class="form-label text-success">Peso (kg)</label>
                            <input type="number" class="form-control" id="peso" name="peso" required placeholder="Ej. 450"
                                value="<?= htmlspecialchars($caballo->obtener_peso()); ?>">
                            <small class="form-text text-muted">Ingrese el peso del animal en kilogramos.</small>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label text-success">Precio (COP)</label>
                            <input type="text" class="form-control" id="precio" name="precio" required placeholder="Ej. 1200000"
                                value="<?= htmlspecialchars($caballo->obtener_precio()); ?>">
                            <small class="form-text text-muted">Indique el precio en COP para este animal.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-success">Características del caballo <span class="text-muted">(máx. 3)</span></label><br>
                            <?php
                            $caracteristicas_caballo = is_array($caballo->obtener_caracteristicas()) ? $caballo->obtener_caracteristicas() : json_decode($caballo->obtener_caracteristicas(), true);
                            if (!is_array($caracteristicas_caballo)) {
                                $caracteristicas_caballo = [];
                            }
                            $caracteristicas_disponibles = ["Manso", "Apto para niños", "Buen paso", "Fácil de montar", "Ideal para trabajo", "Buen pedigree"];
                            foreach ($caracteristicas_disponibles as $caracteristica) {
                                $checked = in_array($caracteristica, $caracteristicas_caballo) ? 'checked' : '';
                                $id_caracteristica = 'caracteristica' . str_replace(' ', '', $caracteristica);
                                echo '<div class="form-check form-check-inline">';
                                echo '<input class="form-check-input" type="checkbox" name="caracteristicas[]" id="' . $id_caracteristica . '" value="' . htmlspecialchars($caracteristica) . '" ' . $checked . '>';
                                echo '<label class="form-check-label" for="' . $id_caracteristica . '">' . htmlspecialchars($caracteristica) . '</label>';
                                echo '</div>';
                            }
                            ?>
                            <small class="form-text text-muted d-block mt-2">Seleccione hasta 3 características que describan mejor el caballo.</small>
                        </div>

                        <div class="mb-3">
                            <label for="fotos" class="form-label text-success">Fotos del animal</label>
                            <input type="file" class="form-control" id="fotos" name="fotos[]" accept="image/jpeg,image/png,image/gif,image/webp"
                                multiple onchange="mostrarVistaPrevia(this)">
                            <small id="ayuda-fotos" class="form-text text-muted">
                                Puede subir hasta 10 imágenes (JPG, PNG, GIF, WEBP). Tamaño máximo por imagen: <strong>2MB</strong>.
                            </small>

                            <div id="preview-fotos" class="mt-3 d-flex flex-wrap gap-2">
                                <?php
                                $current_images = is_array($caballo->obtener_imagenes()) ? $caballo->obtener_imagenes() : json_decode($caballo->obtener_imagenes(), true);
                                if (is_array($current_images)) {
                                    foreach ($current_images as $img_path) {
                                        $img_url = '/Ganandez/' . ltrim($img_path, '/');
                                        echo '<div class="position-relative me-2 mb-2">';
                                        echo '<img src="' . htmlspecialchars($img_url) . '" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">';
                                        echo '<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="eliminarImagenExistente(this, \'' . htmlspecialchars($img_path, ENT_QUOTES, 'UTF-8') . '\')">&times;</button>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                            <div id="hidden_delete_images_inputs"></div>
                        </div>

                        <div class="mb-3" id="campo-video">
                            <label for="videos" class="form-label text-success">Video del animal</label>
                            <input type="file" class="form-control" id="videos" name="videos[]"
                                accept="video/mp4,video/webm,video/ogg" onchange="mostrarVistaPreviaVideo(this)">
                            <small id="ayuda-video" class="form-text text-muted">
                                Solo se permite subir un video (MP4, WEBM, OGG). Tamaño máximo: <strong>50MB</strong>.
                            </small>


                            <div id="preview-video" class="mt-3">
                                <?php
                                $current_videos = is_array($caballo->obtener_videos()) ? $caballo->obtener_videos() : json_decode($caballo->obtener_videos(), true);
                                if (is_array($current_videos) && !empty($current_videos)) {
                                    foreach ($current_videos as $vid_path) {
                                        $vid_url = '/Ganandez/' . ltrim($vid_path, '/');
                                        echo '<div class="position-relative">';
                                        echo '<video src="' . htmlspecialchars($vid_url) . '" controls class="img-thumbnail" style="width: 200px;"></video>';
                                        echo '<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="eliminarVideoExistente(this, \'' . htmlspecialchars($vid_path, ENT_QUOTES, 'UTF-8') . '\')">&times;</button>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                            <div id="hidden_delete_videos_inputs"></div>
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label text-success">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" required
                                value="<?= htmlspecialchars($caballo->obtener_telefono()); ?>">
                            <small class="form-text text-muted">Número de teléfono de contacto para el ejemplar.</small>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label text-success">Correo electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" required
                                value="<?= htmlspecialchars($caballo->obtener_correo()); ?>">
                            <small class="form-text text-muted">Correo electrónico de contacto para el ejemplar.</small>
                        </div>

                        <div class="mb-3">
                            <label for="departamento" class="form-label text-success">Departamento</label>
                            <input type="text" class="form-control" id="departamento" name="departamento" required
                                value="<?= htmlspecialchars($caballo->obtener_departamento()); ?>">
                            <small class="form-text text-muted">Departamento donde se ubica el animal.</small>
                        </div>
                        <div class="mb-3">
                            <label for="municipio" class="form-label text-success">Municipio</label>
                            <input type="text" class="form-control" id="municipio" name="municipio" required
                                value="<?= htmlspecialchars($caballo->obtener_municipio()); ?>">
                            <small class="form-text text-muted">Municipio donde se ubica el animal.</small>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label text-success">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required
                                value="<?= htmlspecialchars($caballo->obtener_direccion()); ?>">
                            <small class="form-text text-muted">Dirección exacta de la ubicación del animal.</small>
                        </div>

                        <div class="mb-3">
                            <label for="latitud" class="form-label text-success">Latitud</label>
                            <input type="text" class="form-control" id="latitud" name="latitud" required
                                value="<?= htmlspecialchars($caballo->obtener_latitud()); ?>">
                            <small class="form-text text-muted">Coordenada de latitud de la ubicación del animal.</small>
                        </div>
                        <div class="mb-3">
                            <label for="longitud" class="form-label text-success">Longitud</label>
                            <input type="text" class="form-control" id="longitud" name="longitud" required
                                value="<?= htmlspecialchars($caballo->obtener_longitud()); ?>">
                            <small class="form-text text-muted">Coordenada de longitud de la ubicación del animal.</small>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="destacado" name="destacado" <?= $caballo->obtener_destacado() ? 'checked' : '' ?>>
                            <label class="form-check-label" for="destacado">Marcar esta publicación como <strong>DESTACADA</strong>.</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="premium" name="premium" <?= $caballo->obtener_premium() ? 'checked' : '' ?>>
                            <label class="form-check-label" for="premium">Marcar esta publicación como <strong>PREMIUM</strong>.</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="sugerido" name="sugerido" <?= $caballo->obtener_sugerido() ? 'checked' : '' ?>>
                            <label class="form-check-label" for="sugerido">Marcar esta publicación como <strong>SUGERIDA</strong>.</label>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label text-success">Fecha Fin</label>
                            <input type="datetime-local" name="fecha_fin" class="form-control"
                                value="<?= $caballo->obtener_fecha_fin() ? date('Y-m-d\TH:i', strtotime($caballo->obtener_fecha_fin())) : ''; ?>">
                            <small class="form-text text-muted">Fecha límite para la publicación del ejemplar.</small>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Function to handle removal of existing images (requires more JS for a complete implementation)
    document.addEventListener('DOMContentLoaded', function() {
        // Contadores (si se usan en caballo)
        const tituloInput = document.getElementById('titulo');
        const tituloCounter = document.getElementById('titulo-counter');
        const descripcionTextarea = document.getElementById('descripcion');
        const descripcionCounter = document.getElementById('descripcion-counter');

        function updateCounter(input, counter, maxLength) {
            const currentLength = input.value.length;
            const remaining = maxLength - currentLength;
            counter.textContent = `${remaining} restantes`;
        }

        if (tituloInput && tituloCounter) {
            tituloInput.addEventListener('input', () => updateCounter(tituloInput, tituloCounter, 50));
            updateCounter(tituloInput, tituloCounter, 50);
        }

        if (descripcionTextarea && descripcionCounter) {
            descripcionTextarea.addEventListener('input', () => updateCounter(descripcionTextarea, descripcionCounter, 300));
            updateCounter(descripcionTextarea, descripcionCounter, 300);
        }
    });

    // Mostrar imágenes nuevas
    function mostrarVistaPrevia(input) {
        const previewContainer = document.getElementById('preview-fotos');
        // No borrar todo el contenedor, sólo agregar imágenes nuevas

        if (input.files && input.files.length > 0) {
            Array.from(input.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgDiv = document.createElement('div');
                        imgDiv.className = 'position-relative me-2 mb-2';
                        imgDiv.innerHTML = `
                        <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                    `;
                        previewContainer.appendChild(imgDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }



    // Mostrar video nuevo
    function mostrarVistaPreviaVideo(input) {
        const previewContainer = document.getElementById('preview-video');
        previewContainer.innerHTML = '';

        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            if (file.type.startsWith('video/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const videoDiv = document.createElement('div');
                    videoDiv.className = 'position-relative';
                    videoDiv.innerHTML = `
                    <video src="${e.target.result}" controls class="img-thumbnail" style="width: 200px;"></video>
                `;
                    previewContainer.appendChild(videoDiv);
                };
                reader.readAsDataURL(file);
            }
        }
    }

    // Eliminar imagen existente del caballo
    function eliminarImagenExistente(button, imagePath) {
        if (confirm('¿Estás seguro de que quieres eliminar esta imagen?')) {
            button.closest('div').remove();
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'borrar_imagenes[]';
            hiddenInput.value = imagePath;
            document.getElementById('hidden_delete_images_inputs').appendChild(hiddenInput);
        }
    }

    // Eliminar video existente del caballo
    function eliminarVideoExistente(button, videoPath) {
        if (confirm('¿Estás seguro de que quieres eliminar este video?')) {
            button.closest('div').remove();
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'borrar_videos[]';
            hiddenInput.value = videoPath;
            document.getElementById('hidden_delete_videos_inputs').appendChild(hiddenInput);
        }
    }
</script>



<?php include_once 'plantillas/documento-cierre.inc.php'; ?>