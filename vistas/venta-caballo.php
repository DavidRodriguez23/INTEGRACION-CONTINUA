<?php


include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/Caballo.inc.php';
include_once 'app/usuario.inc.php';
include_once 'app/RepositorioCaballo.inc.php';

$titulo = 'Ingreso de ganado';

// Asegurarse de que el usuario esté autenticado
// Asegurarse de que el usuario esté autenticado y activo
if (!ControlSesion::sesion_iniciada() || !ControlSesion::usuario_activo()) {
    header("Location: " . RUTA_LOGIN);
    exit();
}


$mensaje_exito = false;
$mensaje_id = '';

if (isset($_SESSION['publicacion_exitosa']) && $_SESSION['publicacion_exitosa']) {
    $mensaje_exito = true;
    $mensaje_id = $_SESSION['publicacion_id'];

    // Limpiar para que no se repita al recargar otra vez
    unset($_SESSION['publicacion_exitosa']);
    unset($_SESSION['publicacion_id']);
}



if (isset($_POST['publicar'])) {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();
    // Obtener el plan seleccionado
    $plan_seleccionado = $_POST['plan'] ?? 'personal1'; // fallback por si se pierde
    $duraciones = [
        'personal1' => '+2 months',
        'personal2' => '+4 months',
        'personal3' => '+4 months',
        'ganaderos1' => '+4 months',
        'ganaderos2' => '+4 months',
    ];

    $duracion = $duraciones[$plan_seleccionado] ?? '+4 months'; // fallback adicional
    $hoy = new DateTime();
    $hoy->modify($duracion);
    $fecha_fin = $hoy->format('Y-m-d H:i:s');

    $usuario_id = $_SESSION['id_usuario'];
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $publicacion_id = uniqid('caballo_', true);

    // Datos del caballo enviados desde el formulario
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $raza = $_POST['raza'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $edad = $_POST['edad'] ?? '';
    $peso = $_POST['peso'] ?? 0;
    $precio = $_POST['precio'] ?? 0;
    $caracteristicas = isset($_POST['caracteristicas']) ? json_encode($_POST['caracteristicas']) : json_encode([]);
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $departamento = $_POST['departamento'] ?? '';
    $municipio = $_POST['municipio'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $latitud = isset($_POST['latitud']) ? floatval($_POST['latitud']) : 0.0;
    $longitud = isset($_POST['longitud']) ? floatval($_POST['longitud']) : 0.0;
    $destacado = (isset($_POST['destacado']) && $_POST['destacado'] === 'on') ? 1 : 0;
    $premium = (isset($_POST['premium']) && $_POST['premium'] === 'on') ? 1 : 0;
    $sugerido = (isset($_POST['sugerido']) && $_POST['sugerido'] === 'on') ? 1 : 0;
    $terminos = (isset($_POST['terminos']) && $_POST['terminos'] === 'on') ? 1 : 0;
    $fecha_fin = $fecha_fin;

    // Rutas
    $directorio_base = "usuarios/";
    $ruta_base = $directorio_base . "$nombre_usuario/$publicacion_id/";


    // Crear carpeta si no existe
    if (!file_exists($ruta_base)) {
        mkdir($ruta_base, 0777, true);
        chmod($ruta_base, 0777);
    }

    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/Ganandez/" . $ruta_base;
    $upload_dir_relativo = $ruta_base;

    $imagenes_guardadas = [];
    $videos_guardados = [];

    if (!empty($_POST['fotos_tmp']) && !empty($_POST['fotos_type'])) {
        foreach ($_POST['fotos_tmp'] as $i => $base64) {
            $data = base64_decode($base64);
            $tipo = $_POST['fotos_type'][$i];
            $ext = explode('/', $tipo)[1];
            $nombre_archivo = uniqid('img_', true) . '.' . $ext;
            $ruta_final = $upload_dir . $nombre_archivo;
            $ruta_relativa = $upload_dir_relativo . $nombre_archivo;
            file_put_contents($ruta_final, $data);
            $imagenes_guardadas[] = ruta_web($ruta_relativa);
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
            file_put_contents($ruta_final, $data);
            $videos_guardados[] = ruta_web($ruta_relativa);
        }
    }

    function es_valido_mime($file_tmp, $tipos_validos)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);
        return in_array($mime, $tipos_validos);
    }

    // Subir imágenes
    if (isset($_FILES['fotos']) && !empty($_FILES['fotos']['name'][0])) {
        foreach ($_FILES['fotos']['tmp_name'] as $index => $tmpPath) {
            if ($_FILES['fotos']['error'][$index] === UPLOAD_ERR_OK && es_valido_mime($tmpPath, $mime_imagenes)) {
                $ext = strtolower(pathinfo($_FILES['fotos']['name'][$index], PATHINFO_EXTENSION));
                $nombre_archivo = uniqid('img_', true) . '.' . $ext;
                $ruta_final = $upload_dir . $nombre_archivo;
                $ruta_relativa = $upload_dir_relativo . $nombre_archivo;

                if (move_uploaded_file($tmpPath, $ruta_final)) {
                    $imagenes_guardadas[] = ruta_web($ruta_relativa);
                }
            }
        }
    }

    // Subir videos
    if (isset($_FILES['videos']) && !empty($_FILES['videos']['name'][0])) {
        foreach ($_FILES['videos']['tmp_name'] as $index => $tmpPath) {
            if ($_FILES['videos']['error'][$index] === UPLOAD_ERR_OK && es_valido_mime($tmpPath, $mime_videos)) {
                $ext = strtolower(pathinfo($_FILES['videos']['name'][$index], PATHINFO_EXTENSION));
                $nombre_archivo = uniqid('vid_', true) . '.' . $ext;
                $ruta_final = $upload_dir . $nombre_archivo;
                $ruta_relativa = $upload_dir_relativo . $nombre_archivo;

                if (move_uploaded_file($tmpPath, $ruta_final)) {
                    $videos_guardados[] = ruta_web($ruta_relativa);
                }
            }
        }
    }

    // Convertir rutas a JSON (para que RepositorioAnimal las inserte)
    $imagenes_json = json_encode($imagenes_guardadas);
    $videos_json = json_encode($videos_guardados);



    // Calcular comisión y guardar soporte de pago
    $soporte_pago_url = null;
    $valor_comision = null;
    $tasas = [
        'personal2' => 0.03,
        'personal3' => 0.06,
        'ganaderos1' => 0.03,
        'ganaderos2' => 0.06
    ];
    $porcentaje = $tasas[$plan_seleccionado] ?? 0;
    if ($porcentaje > 0 && $precio > 0) {
        $valor_comision = floor($precio * $porcentaje);
        // Guardar soporte de pago (PDF)
        if (isset($_FILES['soporte_pago']) && $_FILES['soporte_pago']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['soporte_pago']['name'], PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $nombre_soporte = uniqid('soporte_', true) . '.pdf';
                $ruta_soporte = $upload_dir . $nombre_soporte;
                $ruta_soporte_rel = $upload_dir_relativo . $nombre_soporte;
                if (move_uploaded_file($_FILES['soporte_pago']['tmp_name'], $ruta_soporte)) {
                    $soporte_pago_url = '/Ganandez' . ruta_web($ruta_soporte_rel);
                }
            }
        } elseif (!empty($_POST['soporte_pago_tmp']) && !empty($_POST['soporte_pago_type'])) {
            // Si viene de la vista previa como base64
            $mime = $_POST['soporte_pago_type'];
            if ($mime === 'application/pdf') {
                $nombre_soporte = uniqid('soporte_', true) . '.pdf';
                $ruta_soporte = $upload_dir . $nombre_soporte;
                $ruta_soporte_rel = $upload_dir_relativo . $nombre_soporte;
                $data = base64_decode($_POST['soporte_pago_tmp']);
                if (file_put_contents($ruta_soporte, $data)) {
                    $soporte_pago_url = '/Ganandez' . ruta_web($ruta_soporte_rel);
                }
            }
        }
    }

    // Crear el objeto Caballo
    $caballo = new Caballo(
        null,
        $titulo,
        $descripcion,
        $categoria,
        $raza,
        $sexo,
        $edad,
        $peso,
        $precio,
        $caracteristicas,
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
        $fecha_fin,
        $usuario_id,
        0,
        $soporte_pago_url,
        $valor_comision
    );

    // Insertar en la base de datos
    $conexion->beginTransaction();
    try {
        $caballo_id = RepositorioCaballo::insertar_caballo($conexion, $caballo);
        if (!$caballo_id) {
            throw new Exception('No se pudo insertar el caballo.');
        }
        $conexion->commit();
        $conexion->lastInsertId();
        $_SESSION['publicacion_exitosa'] = true;
        $_SESSION['publicacion_id'] = $caballo_id;
        header("Location: " . RUTA_VENTA_CABALLO);
        exit();
    } catch (Exception $e) {
        $conexion->rollBack();
        echo "Error en la transacción: " . $e->getMessage();
    }
}

// Función para normalizar rutas web (igual que en venta.php)
function ruta_web($ruta_absoluta)
{
    $ruta_absoluta = str_replace('\\', '/', $ruta_absoluta);
    $pos = strpos($ruta_absoluta, '/usuarios/');
    if ($pos === false) {
        $pos = strpos($ruta_absoluta, 'usuarios/');
    }
    return $pos !== false ? '/' . ltrim(substr($ruta_absoluta, $pos), '/') : $ruta_absoluta;
}

// Incluir el encabezado y el navbar
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';

?>

<br><br><br>
<div class="container text-center mt-4 mb-4">
    <h2 class="main-title">
        <i class="fas fa-cow text-success me-2"></i>
        Selecciona tu plan Ganadero
        <i class="fas fa-tractor text-danger ms-2"></i>
    </h2>
    <p class="main-subtitle">Elige el plan que mejor se adapte a tus necesidades personales o de producción</p>
</div>
<?php if ($mensaje_exito): ?>
    <div class="alert alert-success text-center animate-fade-in">
        <strong>¡Tu publicación fue realizada con éxito!</strong><br>
        ID de publicación: <code>caballos - <?= htmlspecialchars($mensaje_id) ?></code>
    </div>

<?php endif; ?>



<div class="container mt-5">
    <div class="card tarjeta-registro animate-fade-in">
        <!-- Planes personales -->
        <details class="mb-4 border p-3 bg-white shadow-sm rounded">
            <summary class="form-label text-success h5 mb-3">Planes personales</summary>
            <div class="contenido-animado">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <label class="plan-opcion w-100">
                            <input type="radio" name="plan" value="personal1" required>
                            <img src="img/1.png" alt="Plan 1" class="imagen-plan">
                        </label>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <label class="plan-opcion w-100">
                            <input type="radio" name="plan" value="personal2">
                            <img src="img/2.png" alt="Plan 2" class="imagen-plan">
                        </label>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <label class="plan-opcion w-100">
                            <input type="radio" name="plan" value="personal3">
                            <img src="img/3.png" alt="Plan 3" class="imagen-plan">
                        </label>
                    </div>
                </div>
            </div>
        </details>

        <!-- Planes ganadería -->
        <details class="mb-4 border p-3 bg-white shadow-sm rounded">
            <summary class="form-label text-success h5 mb-3">Planes para ganadería</summary>
            <div class="contenido-animado">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="plan-opcion w-100">
                            <input type="radio" name="plan" value="ganaderos1">
                            <img src="img/4.png" alt="Ganadería 1" class="imagen-plan">
                        </label>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="plan-opcion w-100">
                            <input type="radio" name="plan" value="ganaderos2">
                            <img src="img/5.png" alt="Ganadería 2" class="imagen-plan">
                        </label>
                    </div>
                </div>
            </div>
        </details>
    </div>

    <!-- Formulario de publicación -->
    <form method="post" action="<?php echo RUTA_PREVIA_VENTA_CABALLO; ?>" id="formulario-publicacion" style="display: none;" enctype="multipart/form-data">
        <?php include_once 'plantillas/form_venta_caballo.inc.php'; ?>
        <input type="hidden" name="plan" id="input-plan-seleccionado">
        <div class="text-center mt-4">


            <button type="submit" class="btn btn-danger btn-lg btn-custom animate-fade-in" name="publicar">
                <i class="fas fa-upload me-2"></i> Publicar
            </button>
        </div>

        <!-- JS dinámico -->
        <script>
            // Razas/aptitudes por categoría para caballos
            const razasPorCategoriaCaballo = {
                "Caballo Criollo Colombiano": [
                    "Trocha",
                    "Galope",
                    "Paso fino",
                    "Trote",
                    "Vaquería",
                    "Pista"
                ],
                "Caballo Percherón": [
                    "Trabajo",
                    "Pista"
                ],
                "Caballo Árabe": [
                    "Pista",
                    "Exhibición"
                ],
                "Cuarto de Milla": [
                    "Carreras",
                    "Vaquería",
                    "Coleo"
                ],
                "Caballos Mulares": [ // <-- Corrige aquí
                    "Trabajo",
                    "Pista",
                    "Paso fino",
                    "Trocha",
                    "Trote",
                    "Galope"
                ],
                "Otros": [
                    "Otros"
                ]
            };

            document.getElementById('categoria').addEventListener('change', function() {
                const categoria = this.value;
                const razaSelect = document.getElementById('raza');
                razaSelect.innerHTML = '<option value="">Selecciona una raza o aptitud</option>';

                if (razasPorCategoriaCaballo[categoria]) {
                    razasPorCategoriaCaballo[categoria].forEach(function(raza) {
                        const option = document.createElement('option');
                        option.value = raza;
                        option.textContent = raza;
                        razaSelect.appendChild(option);
                    });
                }
            });

            const planRadios = document.querySelectorAll('input[name="plan"]');
            const formulario = document.getElementById('formulario-publicacion');
            const campoVideo = document.getElementById('campo-video');
            const inputFotos = document.getElementById('fotos');
            const ayudaFotos = document.getElementById('ayuda-fotos');
            let maxArchivos = 5; // Por defecto

            // Referencias a los tres checkboxes
            const destacadoCheckbox = document.getElementById('destacado');
            const premiumCheckbox = document.getElementById('premium');
            const sugeridoCheckbox = document.getElementById('sugerido');

            if (campoVideo) campoVideo.style.display = 'none';

            // Maneja el cambio de plan
            planRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    formulario.style.display = 'block';
                    document.getElementById('input-plan-seleccionado').value = this.value;



                    if (this.value === 'personal1') {
                        // Plan personal1: No acceso a ninguno de los campos
                        destacadoCheckbox.disabled = true;
                        premiumCheckbox.disabled = true;
                        sugeridoCheckbox.disabled = true;

                        // Limpiar valores
                        destacadoCheckbox.checked = false;
                        premiumCheckbox.checked = false;
                        sugeridoCheckbox.checked = false;

                        campoVideo.style.display = 'none';
                        maxArchivos = 1;
                    } else if (this.value === 'personal2' || this.value === 'ganaderos1') {
                        // Planes personal2 y ganaderos2: Solo acceso a DESTACADOS
                        destacadoCheckbox.disabled = false;
                        premiumCheckbox.disabled = true;
                        sugeridoCheckbox.disabled = true;

                        // Limpiar valores
                        premiumCheckbox.checked = false;
                        sugeridoCheckbox.checked = false;
                        campoVideo.style.display = 'block';
                        maxArchivos = 5;
                    } else if (this.value === 'personal3' || this.value === 'ganaderos2') {
                        // Planes personal3 y ganaderos3: Acceso a los tres campos
                        destacadoCheckbox.disabled = false;
                        premiumCheckbox.disabled = false;
                        sugeridoCheckbox.disabled = false;

                        campoVideo.style.display = 'block';
                        maxArchivos = 5;
                    }

                    // Limpiar selección previa de fotos
                    if (inputFotos) {
                        inputFotos.value = '';
                    }

                    if (ayudaFotos) {
                        ayudaFotos.textContent = `Puedes subir hasta ${maxArchivos} imagen${maxArchivos === 1 ? '' : 'es'} (formatos: JPG, PNG, GIF).`;
                    }

                    formulario.scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });


            // Vista previa de imágenes
            function mostrarVistaPrevia(input) {
                const preview = document.getElementById('preview-fotos');
                const archivos = input.files;
                const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
                const MAX_SIZE_MB = 2;

                preview.innerHTML = "";

                if (archivos.length > maxArchivos) {
                    ayudaFotos.textContent = `Máximo ${maxArchivos} imagen${maxArchivos === 1 ? '' : 'es'} permitida${maxArchivos === 1 ? '' : 's'}.`;
                    input.value = "";
                    return;
                }

                let error = false;
                Array.from(archivos).forEach(archivo => {
                    if (!tiposPermitidos.includes(archivo.type)) {
                        ayudaFotos.textContent = `Formato no permitido: ${archivo.name}`;
                        error = true;
                        return;
                    }

                    if (archivo.size > MAX_SIZE_MB * 1024 * 1024) {
                        ayudaFotos.textContent = `La imagen "${archivo.name}" supera el límite de ${MAX_SIZE_MB}MB.`;
                        error = true;
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail me-2 mb-2';
                        img.style.maxWidth = '200px';
                        img.style.maxHeight = '200px';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(archivo);
                });

                if (error) {
                    input.value = "";
                    preview.innerHTML = "";
                } else {
                    ayudaFotos.textContent = `${archivos.length} archivo${archivos.length > 1 ? 's' : ''} válido${archivos.length > 1 ? 's' : ''} seleccionado${archivos.length > 1 ? 's' : ''}.`;
                }
            }



            function mostrarVistaPreviaVideo(input) {
                const maxVideos = 1;
                const preview = document.getElementById('preview-video');
                const ayuda = document.getElementById('ayuda-video');
                const archivos = input.files;

                preview.innerHTML = "";

                if (archivos.length > maxVideos) {
                    ayuda.textContent = `Solo puedes subir ${maxVideos} video.`;
                    input.value = "";
                    return;
                }

                const tiposPermitidos = ['video/mp4', 'video/avi', 'video/quicktime'];
                const archivo = archivos[0];

                if (!tiposPermitidos.includes(archivo.type)) {
                    ayuda.textContent = `Formato no permitido: ${archivo.name}`;
                    input.value = "";
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const video = document.createElement('video');
                    video.src = e.target.result;
                    video.controls = true;
                    video.style.maxWidth = "100%";
                    video.style.maxHeight = "320px";
                    preview.appendChild(video);
                    ayuda.textContent = "Video válido cargado correctamente.";
                };
                reader.readAsDataURL(archivo);
            }

            document.getElementById('precio').addEventListener('input', function(e) {
                let input = e.target;
                let value = input.value.replace(/\D/g, ''); // Elimina todo lo que no sea número
                if (!value) {
                    input.value = '';
                    return;
                }
                // Convierte a número y aplica formato solo en la vista
                let formatted = '$' + parseInt(value, 10).toLocaleString('es-CO');
                input.value = formatted;
            });

            document.getElementById('formulario-publicacion').addEventListener('submit', function(e) {
                const precioInput = document.getElementById('precio');
                if (precioInput) {
                    // Limpiar el valor de precio antes de enviarlo
                    precioInput.value = precioInput.value.replace(/[^\d]/g, ''); // Eliminar caracteres no numéricos
                }
            });

            document.addEventListener("DOMContentLoaded", function() {
                const tituloInput = document.getElementById("titulo");
                const descripcionInput = document.getElementById("descripcion");
                const tituloCounter = document.getElementById("titulo-counter");
                const descripcionCounter = document.getElementById("descripcion-counter");

                const updateCounter = (input, counter, max) => {
                    const remaining = max - input.value.length;
                    counter.textContent = `${remaining} restantes`;
                };

                tituloInput.addEventListener("input", () => {
                    updateCounter(tituloInput, tituloCounter, 50);
                });

                descripcionInput.addEventListener("input", () => {
                    updateCounter(descripcionInput, descripcionCounter, 300);
                });

                // Inicializar
                updateCounter(tituloInput, tituloCounter, 50);
                updateCounter(descripcionInput, descripcionCounter, 300);
            });

            const MAX_SIZE_IMG_MB = 5; // Máximo 2MB por imagen
            const MAX_SIZE_VIDEO_MB = 50; // Máximo 10MB por video

            function mostrarVistaPreviaVideo(input) {
                const preview = document.getElementById('preview-video');
                preview.innerHTML = '';
                const file = input.files[0];

                if (file && file.size > MAX_SIZE_VIDEO_MB * 1024 * 1024) {
                    alert(`El video supera el límite de ${MAX_SIZE_VIDEO_MB}MB.`);
                    input.value = '';
                    return;
                }

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const video = document.createElement('video');
                        video.src = e.target.result;
                        video.controls = true;
                        video.style.maxWidth = '100%';
                        preview.appendChild(video);
                    };
                    reader.readAsDataURL(file);
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                const mapa = L.map('map').setView([4.5709, -74.2973], 6); // Coordenadas centradas en Colombia

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(mapa);

                var marcador = L.marker([4.5709, -74.2973], {
                    draggable: true
                }).addTo(mapa);

                // Guardar posición inicial
                document.getElementById('latitud').value = marcador.getLatLng().lat;
                document.getElementById('longitud').value = marcador.getLatLng().lng;

                // Actualizar al mover el marcador
                marcador.on('moveend', function(e) {
                    var pos = marcador.getLatLng();
                    document.getElementById('latitud').value = pos.lat;
                    document.getElementById('longitud').value = pos.lng;
                });
            });

            // Limitar selección a 3 características
            document.querySelectorAll('input[name="caracteristicas[]"]').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    let checked = document.querySelectorAll('input[name="caracteristicas[]"]:checked');
                    if (checked.length > 3) {
                        this.checked = false;
                        alert('Solo puedes seleccionar hasta 3 características.');
                    }
                });
            });
        </script>



        <?php include_once 'plantillas/documento-cierre.inc.php'; ?>