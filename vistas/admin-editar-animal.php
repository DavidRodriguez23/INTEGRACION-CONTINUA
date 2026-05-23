<?php



include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/RepositorioAnimal.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/animal.inc.php';

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

$animal = RepositorioAnimal::obtener_animal_por_id(Conexion::obtener_conexion(), $id);
if (!$animal) {
    Redireccion::redirigir(RUTA_ADMIN_PUBLICACIONES);
    exit();
}

function normalizar_rutas_json(array $rutas): string
{
    return json_encode($rutas, JSON_UNESCAPED_SLASHES);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_animal'])) {
    $conexion = Conexion::obtener_conexion();

    $id_animal = $_POST['id_animal'];
    // Asegurarse de obtener el usuario_id de la sesión
    $usuario_id = $_SESSION['id_usuario'] ?? null;
    if (!$usuario_id) {
        // Manejar el caso donde el id_usuario no está en sesión,
        // por ejemplo, redireccionar o lanzar un error.
        error_log("Error: id_usuario no encontrado en la sesión al editar animal.");
        Redireccion::redirigir(RUTA_LOGIN); // O alguna página de error
        exit();
    }


    $stmt_user_name = $conexion->prepare("SELECT u.nombre FROM usuarios u JOIN animales a ON u.id = a.id_usuario WHERE a.id = ?");
    $stmt_user_name->execute([$id_animal]);
    $nombre_usuario_publicacion = $stmt_user_name->fetchColumn();

    if (!$nombre_usuario_publicacion) {

        error_log("Error: No se encontró el nombre de usuario del propietario original para el animal ID: " . $id_animal);
        $nombre_usuario_publicacion = $_SESSION['nombre_usuario'] ?? 'usuario_desconocido'; // Fallback
    }

    // Obtener campos del formulario
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $raza = $_POST['raza'] ?? '';
    $pureza = $_POST['pureza'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $tipo_animal = $_POST['tipo_animal'] ?? '';
    $edad = $_POST['edad'] ?? '';
    $peso = floatval($_POST['peso'] ?? 0);
    $precio = floatval($_POST['precio'] ?? 0);
    $tipo_precio = $_POST['tipo_precio'] ?? '';
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
    $fecha_fin = $_POST['fecha_fin'] ?? date('Y-m-d H:i:s');

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

    // Obtener imágenes y videos actuales desde el objeto $animal
    // ASUMIMOS que obtener_imagenes() y obtener_videos() ya devuelven un ARRAY o null/string a decodificar.
    $imagenes_actuales_db = $animal->obtener_imagenes();
    if (!is_array($imagenes_actuales_db)) {
        $imagenes_actuales_db = json_decode($imagenes_actuales_db, true);
        if (!is_array($imagenes_actuales_db)) {
            $imagenes_actuales_db = [];
        }
    }

    $videos_actuales_db = $animal->obtener_videos();
    if (!is_array($videos_actuales_db)) {
        $videos_actuales_db = json_decode($videos_actuales_db, true);
        if (!is_array($videos_actuales_db)) {
            $videos_actuales_db = [];
        }
    }

    // Determinar la carpeta de la publicación. Es crucial para mantener la consistencia de las rutas.
    $carpeta_publicacion = '';
    // Intentar obtener la carpeta de una imagen existente
    if (!empty($imagenes_actuales_db)) {
        // Remove custom escaping for path processing if present, then split
        $path_for_splitting = str_replace('\/', '/', $imagenes_actuales_db[0]);
        $partes_ruta = explode('/', $path_for_splitting);
        if (count($partes_ruta) >= 3) {
            $carpeta_publicacion = $partes_ruta[2]; // Captura 'venta_...'
        }
    }
    // If not found in images, try from an existing video
    if (empty($carpeta_publicacion) && !empty($videos_actuales_db)) {
        // Remove custom escaping for path processing if present, then split
        $path_for_splitting = str_replace('\/', '/', $videos_actuales_db[0]);
        $partes_ruta = explode('/', $path_for_splitting);
        if (count($partes_ruta) >= 3) {
            $carpeta_publicacion = $partes_ruta[2];
        }
    }
    // If still not found, generate a new one (this should only happen in very specific cases)
    if (empty($carpeta_publicacion)) {
        $carpeta_publicacion = 'venta_' . uniqid();
    }

    $ruta_base = $directorio_base . $nombre_usuario_publicacion . '/' . $carpeta_publicacion . '/';

    // Ensure trailing slash for base path
    if (substr($ruta_base, -1) !== '/') {
        $ruta_base .= '/';
    }
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/Ganandez/" . $ruta_base;
    $upload_dir_relativo = $ruta_base;

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
        chmod($upload_dir, 0777);
    }

    // --- Manejo de Imágenes ---

    // 1. Manejar eliminación de imágenes existentes
    if (!empty($_POST['borrar_images']) && is_array($_POST['borrar_images'])) {
        foreach ($_POST['borrar_images'] as $img_a_borrar) {
            // Clean the path for the file system (remove custom escaping)
            $path_limpio = str_replace('\/', '/', $img_a_borrar);
            $path = $_SERVER['DOCUMENT_ROOT'] . "/Ganandez/" . $path_limpio;
            if (file_exists($path)) {
                unlink($path);
            }
            // Remove from the current images array (important to use the DB array)
            $key = array_search($img_a_borrar, $imagenes_actuales_db);
            if ($key !== false) {
                unset($imagenes_actuales_db[$key]);
            }
        }
    }

    // 2. Subir nuevas imágenes (base64)
    if (!empty($_POST['fotos_tmp']) && !empty($_POST['fotos_type'])) {
        foreach ($_POST['fotos_tmp'] as $i => $base64) {
            $data = base64_decode($base64);
            $tipo = $_POST['fotos_type'][$i];
            $ext = explode('/', $tipo)[1];
            $nombre_archivo = uniqid('img_', true) . '.' . $ext;
            $ruta_final = $upload_dir . $nombre_archivo;
            $ruta_relativa = $upload_dir_relativo . $nombre_archivo;
            if (file_put_contents($ruta_final, $data)) {
                $imagenes_actuales_db[] = $ruta_relativa; // Add to the final images list
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
                    $imagenes_actuales_db[] = $ruta_relativa; // Add to the final images list
                }
            }
        }
    }

    // --- Manejo de Videos ---

    // 1. Manejar eliminación de videos existentes
    if (!empty($_POST['borrar_videos']) && is_array($_POST['borrar_videos'])) {
        foreach ($_POST['borrar_videos'] as $vid_a_borrar) {
            // Clean the path for the file system (remove custom escaping)
            $path_limpio = str_replace('\/', '/', $vid_a_borrar);
            $path = $_SERVER['DOCUMENT_ROOT'] . "/Ganandez/" . $path_limpio;
            if (file_exists($path)) {
                unlink($path);
            }
            // Remove from the current videos array (important to use the DB array)
            $key = array_search($vid_a_borrar, $videos_actuales_db);
            if ($key !== false) {
                unset($videos_actuales_db[$key]);
            }
        }
    }

    // 2. Subir nuevos videos (base64)
    if (!empty($_POST['videos_tmp']) && !empty($_POST['videos_type'])) {
        foreach ($_POST['videos_tmp'] as $i => $base64) {
            $data = base64_decode($base64);
            $tipo = $_POST['videos_type'][$i];
            $ext = explode('/', $tipo)[1];
            $nombre_archivo = uniqid('vid_', true) . '.' . $ext;
            $ruta_final = $upload_dir . $nombre_archivo;
            $ruta_relativa = $upload_dir_relativo . $nombre_archivo;
            if (file_put_contents($ruta_final, $data)) {
                $videos_actuales_db[] = $ruta_relativa; // Add to the final videos list
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
                    $videos_actuales_db[] = $ruta_relativa; // Add to the final videos list
                }
            }
        }
    }

    $imagenes_json = normalizar_rutas_json(array_values($imagenes_actuales_db));
    $videos_json = normalizar_rutas_json(array_values($videos_actuales_db));
    // Create updated Animal object
    $animal_actualizado = new Animal(
        $id_animal,
        $titulo,
        $descripcion,
        $categoria,
        $raza,
        $pureza,
        $sexo,
        $tipo_animal,
        $edad,
        $peso,
        $precio,
        $tipo_precio,
        $telefono,
        $correo,
        $departamento,
        $municipio,
        $direccion,
        $destacado,
        $premium,
        $imagenes_json,
        $videos_json,
        $sugerido,
        $fecha_fin,
        $latitud,
        $longitud,
        $usuario_id
    );

    try {
        $resultado = RepositorioAnimal::actualizar_animal_admin($conexion, $animal_actualizado);
        if ($resultado) {
            $_SESSION['mensaje_exito'] = "Animal editado correctamente.";
            Redireccion::redirigir(RUTA_ADMIN_PUBLICACIONES);
            exit();
        } else {
            throw new Exception("No se pudo actualizar el animal.");
        }
    } catch (Exception $e) {
        echo "Error al actualizar: " . $e->getMessage();
    }
}
?>

<?php
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>
<br><br>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header encabezado-degradado text-center">
                    <h3 class="titulo-encabezado mb-2">
                        <i class="fas fa-paw me-2"></i> Editar publicación de animal (ID <?= $animal->obtener_id(); ?>)
                    </h3>
                    <p class="subtitulo-registro">Actualiza los detalles solicitados por el usuario.</p>
                </div>
                <div class="card-body fondo-registro">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_animal" value="<?= $animal->obtener_id(); ?>">

                        <div class="mb-3">
                            <label for="titulo" class="form-label text-success">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo"
                                value="<?= htmlspecialchars($animal->obtener_titulo()); ?>" required maxlength="50">
                            <div class="d-flex justify-content-between">
                                <small class="form-text text-muted">Máximo 50 caracteres.</small>
                                <small id="titulo-counter" class="text-muted"><?= 50 - mb_strlen($animal->obtener_titulo()); ?> restantes</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label text-success">Descripción</label>
                            <textarea class="form-control" name="descripcion" id="descripcion" rows="4" maxlength="300"><?= htmlspecialchars($animal->obtener_descripcion()); ?></textarea>
                            <div class="d-flex justify-content-between">
                                <small class="form-text text-muted">Máximo 300 caracteres.</small>
                                <small id="descripcion-counter" class="text-muted"><?= 300 - mb_strlen($animal->obtener_descripcion()); ?> restantes</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="categoria" class="form-label text-success">Categoría</label>
                            <select name="categoria" class="form-select" required>
                                <option value="carne" <?= $animal->obtener_categoria() === 'carne' ? 'selected' : '' ?>>Carne</option>
                                <option value="leche" <?= $animal->obtener_categoria() === 'leche' ? 'selected' : '' ?>>Leche</option>
                                <option value="doble" <?= $animal->obtener_categoria() === 'doble' ? 'selected' : '' ?>>Doble propósito</option>
                            </select>
                            <small class="form-text text-muted">Elige la categoría del animal.</small>
                        </div>

                        <div class="mb-3">
                            <label for="raza" class="form-label text-success">Raza</label>
                            <input type="text" class="form-control" id="raza" name="raza" required
                                value="<?= htmlspecialchars($animal->obtener_raza()); ?>">
                            <small class="form-text text-muted">Introduce la raza del animal.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-success">Pureza</label><br>
                            <div class="radio-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="pureza" id="purezaPuro" value="puro" <?= $animal->obtener_pureza() === 'puro' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="purezaPuro">Puro</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="pureza" id="purezaCruce" value="cruce" <?= $animal->obtener_pureza() === 'cruce' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="purezaCruce">Cruce</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Selecciona la pureza del animal.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-success">Sexo</label><br>
                            <div class="radio-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sexo" id="sexoMacho" value="macho" <?= $animal->obtener_sexo() === 'macho' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="sexoMacho">Macho</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sexo" id="sexoHembra" value="hembra" <?= $animal->obtener_sexo() === 'hembra' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="sexoHembra">Hembra</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Selecciona el sexo del animal.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-success">Tipo de animal</label><br>
                            <div class="radio-group">
                                <?php
                                $tipos = ['novillo', 'novilla', 'ternero', 'ternera', 'toro', 'vaca'];
                                foreach ($tipos as $tipo) {
                                    echo '<div class="form-check form-check-inline">';
                                    echo '<input class="form-check-input" type="radio" name="tipo_animal" id="tipo' . ucfirst($tipo) . '" value="' . $tipo . '" ' .
                                        ($animal->obtener_tipo_animal() === $tipo ? 'checked' : '') . '>';
                                    echo '<label class="form-check-label" for="tipo' . ucfirst($tipo) . '">' . ucfirst($tipo) . '</label>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                            <small class="form-text text-muted">Elige el tipo de animal.</small>
                        </div>

                        <div class="mb-3">
                            <label for="edad" class="form-label text-success">Edad</label>
                            <select class="form-select" id="edad" name="edad" required>
                                <option value="">Selecciona la edad</option>
                                <optgroup label="Meses">
                                    <?php for ($i = 1; $i <= 12; $i++) {
                                        $texto = $i . ' ' . ($i == 1 ? 'mes' : 'meses');
                                        $selected = $animal->obtener_edad() === $texto ? 'selected' : '';
                                        echo "<option value='{$texto}' {$selected}>{$texto}</option>";
                                    } ?>
                                </optgroup>
                                <optgroup label="Años">
                                    <?php for ($i = 1; $i <= 6; $i++) {
                                        $texto = $i . ' ' . ($i == 1 ? 'año' : 'años');
                                        $selected = $animal->obtener_edad() === $texto ? 'selected' : '';
                                        echo "<option value='{$texto}' {$selected}>{$texto}</option>";
                                    } ?>
                                    <option value="Más de 6 años" <?= $animal->obtener_edad() === 'Más de 6 años' ? 'selected' : '' ?>>Más de 6 años</option>
                                </optgroup>
                            </select>
                            <small class="form-text text-muted">Indica la edad del animal.</small>
                        </div>

                        <div class="mb-3">
                            <label for="peso" class="form-label text-success">Peso (kg)</label>
                            <input type="number" name="peso" class="form-control" value="<?= $animal->obtener_peso(); ?>" required>
                            <small class="form-text text-muted">Introduce el peso del animal en kilogramos.</small>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label text-success">Precio</label>
                            <input type="text" name="precio" class="form-control" value="<?= $animal->obtener_precio(); ?>" required>
                            <small class="form-text text-muted">Indica el precio del animal.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-success">Tipo de precio</label><br>
                            <div class="radio-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_precio" id="precioPeso" value="peso" <?= $animal->obtener_tipo_precio() === 'peso' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="precioPeso">Por peso</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_precio" id="precioAnimal" value="animal" <?= $animal->obtener_tipo_precio() === 'animal' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="precioAnimal">Por animal</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Define si el precio es por peso o por animal.</small>
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label text-success">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($animal->obtener_telefono() ?? ''); ?>">
                            <small class="form-text text-muted">Ingresa un número de teléfono de contacto.</small>
                        </div>

                        <div class="mb-3">
                            <label for="correo" class="form-label text-success">Correo</label>
                            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($animal->obtener_correo() ?? ''); ?>">
                            <small class="form-text text-muted">Introduce un correo electrónico de contacto.</small>
                        </div>

                        <div class="mb-3">
                            <label for="departamento" class="form-label text-success">Departamento</label>
                            <input type="text" name="departamento" class="form-control" value="<?= htmlspecialchars($animal->obtener_departamento()); ?>" required>
                            <small class="form-text text-muted">Departamento donde se encuentra el animal.</small>
                        </div>
                        <div class="mb-3">
                            <label for="municipio" class="form-label text-success">Municipio</label>
                            <input type="text" name="municipio" class="form-control" value="<?= htmlspecialchars($animal->obtener_municipio()); ?>" required>
                            <small class="form-text text-muted">Municipio donde se encuentra el animal.</small>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label text-success">Dirección</label>
                            <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($animal->obtener_direccion()); ?>" required>
                            <small class="form-text text-muted">Dirección aproximada del animal.</small>
                        </div>

                        <div class="mb-3">
                            <label for="latitud" class="form-label text-success">Latitud</label>
                            <input type="text" name="latitud" class="form-control" value="<?= htmlspecialchars($animal->obtener_latitud() ?? ''); ?>">
                            <small class="form-text text-muted">Opcional: Latitud de la ubicación.</small>
                        </div>
                        <div class="mb-3">
                            <label for="longitud" class="form-label text-success">Longitud</label>
                            <input type="text" name="longitud" class="form-control" value="<?= htmlspecialchars($animal->obtener_longitud() ?? ''); ?>">
                            <small class="form-text text-muted">Opcional: Longitud de la ubicación.</small>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="destacado" name="destacado" <?= $animal->esta_destacado() ? 'checked' : '' ?>>
                            <label class="form-check-label text-success" for="destacado">Destacado</label>
                            <small class="form-text text-muted d-block">Marca para destacar esta publicación.</small>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="premium" name="premium" <?= $animal->es_premium() ? 'checked' : '' ?>>
                            <label class="form-check-label text-success" for="premium">Premium</label>
                            <small class="form-text text-muted d-block">Marca para que esta publicación sea premium.</small>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="sugerido" name="sugerido" <?= $animal->es_sugerido() ? 'checked' : '' ?>>
                            <label class="form-check-label text-success" for="sugerido">Sugerido</label>
                            <small class="form-text text-muted d-block">Marca para sugerir esta publicación.</small>
                        </div>

                        <hr class="my-4">

                        <h5 class="text-secondary mb-3">Gestión de Imágenes</h5>
                        <div class="mb-4">
                            <label class="form-label d-block text-success">Imágenes actuales</label>
                            <div class="d-flex flex-wrap gap-3" id="current-images-preview">
                                <?php
                                $imagenes = $animal->obtener_imagenes();
                                if (!is_array($imagenes)) {
                                    $decoded_imagenes = json_decode($imagenes, true);
                                    $imagenes = is_array($decoded_imagenes) ? $decoded_imagenes : [];
                                }

                                if (empty($imagenes)) {
                                    echo "<p class='text-muted'>No hay imágenes cargadas para este animal.</p>";
                                } else {
                                    foreach ($imagenes as $idx => $img) {
                                        echo '<div class="position-relative me-2 mb-2 image-preview-item">';
                                        echo '<img src="/Ganandez/' . htmlspecialchars($img) . '" alt="Imagen" class="img-thumbnail imagen-plan" style="width: 100px; height: 100px; object-fit: cover;">';
                                        // Escaping for JS string literal and then for HTML attribute
                                        echo '<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="deleteExistingMedia(this, \'' . htmlspecialchars($img, ENT_QUOTES, 'UTF-8') . '\', \'image\')">&times;</button>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                            <div id="hidden_delete_images_inputs"></div>
                        </div>

                        <div class="mb-4 p-3 border rounded bg-light-green">
                            <label for="fotos" class="form-label d-block fs-5 text-success">
                                <i class="bi bi-cloud-upload me-2"></i> Añadir nuevas imágenes
                            </label>
                            <input type="file" class="form-control" id="fotos" accept="image/*" multiple onchange="mostrarVistaPrevia(this, 'new-images-preview')">
                            <small class="form-text text-muted">Puedes subir múltiples imágenes aquí. Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo por imagen: **2MB**.</small>
                            <div id="new-images-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                            <div id="fotos_tmp_inputs"></div>
                            <div id="fotos_type_inputs"></div>
                        </div>

                        <hr class="my-4">

                        <h5 class="text-secondary mb-3">Gestión de Videos</h5>
                        <div class="mb-4">
                            <label class="form-label d-block text-success">Videos actuales</label>
                            <div class="d-flex flex-wrap gap-3" id="current-videos-preview">
                                <?php
                                $videos = $animal->obtener_videos();
                                if (!is_array($videos)) {
                                    $decoded_videos = json_decode($videos, true);
                                    $videos = is_array($decoded_videos) ? $decoded_videos : [];
                                }

                                if (empty($videos)) {
                                    echo "<p class='text-muted'>No hay videos cargados para este animal.</p>";
                                } else {
                                    foreach ($videos as $idx => $vid) {
                                        echo '<div class="position-relative me-2 mb-2 video-preview-item">';
                                        echo '<video width="160" controls class="img-thumbnail imagen-plan" style="height: 100px; object-fit: cover;"><source src="/Ganandez/' . htmlspecialchars($vid) . '" type="video/mp4">Tu navegador no soporta video.</video>';
                                        // Escaping for JS string literal and then for HTML attribute
                                        echo '<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="deleteExistingMedia(this, \'' . htmlspecialchars($vid, ENT_QUOTES, 'UTF-8') . '\', \'video\')">&times;</button>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                            <div id="hidden_delete_videos_inputs"></div>
                        </div>

                        <div class="mb-4 p-3 border rounded bg-light-green">
                            <label for="videos" class="form-label d-block fs-5 text-success">
                                <i class="bi bi-film me-2"></i> Añadir nuevos videos
                            </label>
                            <input type="file" class="form-control" id="videos" accept="video/*" multiple onchange="mostrarVistaPreviaVideo(this, 'new-videos-preview')">
                            <small class="form-text text-muted">Puedes subir múltiples videos aquí. Formatos permitidos: MP4, WEBM, OGG. Tamaño máximo por video: **50MB**.</small>
                            <div id="new-videos-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                            <div id="videos_tmp_inputs"></div>
                            <div id="videos_type_inputs"></div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label text-success">Fecha fin de la publicación</label>
                            <input type="datetime-local" name="fecha_fin" class="form-control"
                                value="<?= date('Y-m-d\TH:i', strtotime($animal->obtener_fecha_fin())); ?>">
                            <small class="form-text text-muted">Establece la fecha de finalización de la publicación.</small>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-save"></i> Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos copiados y adaptados del formulario de caballos */
    .encabezado-degradado {
        background: linear-gradient(to right, #6cb245, #3a7d32);
        /* Degradado de verde */
        color: white;
        padding: 1.5rem;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .titulo-encabezado {
        font-size: 1.75rem;
        font-weight: bold;
    }

    .subtitulo-registro {
        font-size: 1rem;
        opacity: 0.9;
    }

    .fondo-registro {
        background-color: #f8f9fa;
        /* Color de fondo claro */
    }

    .card-header {
        border-bottom: none;
        /* Eliminar borde inferior por defecto */
    }

    .form-label.text-success {
        font-weight: bold;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .bg-light-green {
        background-color: #e6ffe6;
        /* Un verde muy claro para resaltar secciones */
    }

    .image-preview-item,
    .video-preview-item {
        position: relative;
        display: inline-block;
    }

    .image-preview-item img,
    .video-preview-item video {
        display: block;
        max-width: 150px;
        /* Ajusta el tamaño de la imagen/video según necesites */
        height: auto;
        border: 1px solid #ddd;
        padding: 5px;
        border-radius: 4px;
    }

    .image-preview-item .btn-danger,
    .video-preview-item .btn-danger {
        position: absolute;
        top: -5px;
        right: -5px;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .radio-group .form-check-inline {
        margin-right: 1.5rem;
        /* Espaciado entre radios */
    }
</style>

<script>
    // JavaScript para contadores de caracteres y gestión de archivos
    document.addEventListener('DOMContentLoaded', function() {
        // Character Counters
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
            updateCounter(tituloInput, tituloCounter, 50); // Initial update
        }

        if (descripcionTextarea && descripcionCounter) {
            descripcionTextarea.addEventListener('input', () => updateCounter(descripcionTextarea, descripcionCounter, 300));
            updateCounter(descripcionTextarea, descripcionCounter, 300); // Initial update
        }
    });

    // Function to display new image previews and prepare for base64 upload
    function mostrarVistaPrevia(input, previewContainerId) {
        const previewContainer = document.getElementById(previewContainerId);
        const fotosTmpInputs = document.getElementById('fotos_tmp_inputs');
        const fotosTypeInputs = document.getElementById('fotos_type_inputs');

        if (!previewContainer || !fotosTmpInputs || !fotosTypeInputs) return;

        previewContainer.innerHTML = ''; // Clear previous previews
        fotosTmpInputs.innerHTML = ''; // Clear previous hidden inputs
        fotosTypeInputs.innerHTML = ''; // Clear previous hidden inputs

        if (input.files && input.files.length > 0) {
            Array.from(input.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgDiv = document.createElement('div');
                        imgDiv.className = 'position-relative me-2 mb-2 image-preview-item';
                        imgDiv.innerHTML = `
                            <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="removeNewMediaPreview(this)">&times;</button>
                        `;
                        previewContainer.appendChild(imgDiv);

                        // Create hidden inputs for base64 data
                        const hiddenBase64 = document.createElement('input');
                        hiddenBase64.type = 'hidden';
                        hiddenBase64.name = 'fotos_tmp[]';
                        hiddenBase64.value = e.target.result.split(',')[1]; // Get base64 part
                        fotosTmpInputs.appendChild(hiddenBase64);

                        const hiddenType = document.createElement('input');
                        hiddenType.type = 'hidden';
                        hiddenType.name = 'fotos_type[]';
                        hiddenType.value = file.type;
                        fotosTypeInputs.appendChild(hiddenType);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    // Function to display new video previews and prepare for base64 upload
    function mostrarVistaPreviaVideo(input, previewContainerId) {
        const previewContainer = document.getElementById(previewContainerId);
        const videosTmpInputs = document.getElementById('videos_tmp_inputs');
        const videosTypeInputs = document.getElementById('videos_type_inputs');

        if (!previewContainer || !videosTmpInputs || !videosTypeInputs) return;

        previewContainer.innerHTML = ''; // Clear previous previews
        videosTmpInputs.innerHTML = ''; // Clear previous hidden inputs
        videosTypeInputs.innerHTML = ''; // Clear previous hidden inputs

        if (input.files && input.files.length > 0) {
            Array.from(input.files).forEach(file => {
                if (file.type.startsWith('video/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const videoDiv = document.createElement('div');
                        videoDiv.className = 'position-relative me-2 mb-2 video-preview-item';
                        videoDiv.innerHTML = `
                            <video src="${e.target.result}" controls class="img-thumbnail" style="width: 160px; height: 100px; object-fit: cover;"></video>
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="removeNewMediaPreview(this)">&times;</button>
                        `;
                        previewContainer.appendChild(videoDiv);

                        // Create hidden inputs for base64 data
                        const hiddenBase64 = document.createElement('input');
                        hiddenBase64.type = 'hidden';
                        hiddenBase64.name = 'videos_tmp[]';
                        hiddenBase64.value = e.target.result.split(',')[1]; // Get base64 part
                        videosTmpInputs.appendChild(hiddenBase64);

                        const hiddenType = document.createElement('input');
                        hiddenType.type = 'hidden';
                        hiddenType.name = 'videos_type[]';
                        hiddenType.value = file.type;
                        videosTypeInputs.appendChild(hiddenType);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    // Function to remove new media previews (not yet uploaded) with confirmation
    function removeNewMediaPreview(button) {
        if (!confirm("¿Estás seguro de que quieres eliminar este archivo?")) {
            return; // Si cancela, no hacer nada
        }

        const itemDiv = button.closest('.image-preview-item, .video-preview-item');
        if (itemDiv) {
            const parentContainer = itemDiv.parentElement;
            const inputType = parentContainer.id === 'new-images-preview' ? 'fotos' : 'videos';

            // Find the index of the removed item to also remove its corresponding hidden inputs
            const index = Array.from(parentContainer.children).indexOf(itemDiv);

            if (inputType === 'fotos') {
                const fotosTmpInputs = document.querySelectorAll('#fotos_tmp_inputs input');
                const fotosTypeInputs = document.querySelectorAll('#fotos_type_inputs input');
                if (fotosTmpInputs[index]) fotosTmpInputs[index].remove();
                if (fotosTypeInputs[index]) fotosTypeInputs[index].remove();
            } else if (inputType === 'videos') {
                const videosTmpInputs = document.querySelectorAll('#videos_tmp_inputs input');
                const videosTypeInputs = document.querySelectorAll('#videos_type_inputs input');
                if (videosTmpInputs[index]) videosTmpInputs[index].remove();
                if (videosTypeInputs[index]) videosTypeInputs[index].remove();
            }
            itemDiv.remove(); // Remove the preview itself
        }
    }

    // Function to mark existing media for deletion with confirmation
    function deleteExistingMedia(button, filePath, type) {
        if (!confirm("¿Estás seguro de que quieres eliminar este archivo existente?")) {
            return; // Si cancela, no hacer nada
        }

        const hiddenInputContainer = document.getElementById(`hidden_delete_${type}s_inputs`);

        if (!hiddenInputContainer) {
            console.error("❌ Contenedor no encontrado:", `hidden_delete_${type}s_inputs`);
            return;
        }

        console.log("✅ Agregando input hidden para:", filePath);

        const input = document.createElement("input");
        input.type = "hidden";
        input.name = `borrar_${type}s[]`;
        input.value = filePath;
        hiddenInputContainer.appendChild(input);

        const mediaItem = button.closest(".image-preview-item, .video-preview-item");
        if (mediaItem) {
            mediaItem.remove();
        }
    }
</script>