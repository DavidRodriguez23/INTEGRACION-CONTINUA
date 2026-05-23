<?php
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/animal.inc.php';
include_once 'app/usuario.inc.php';
include_once 'app/RepositorioAnimal.inc.php';

$titulo = 'Ingreso de ganado';

// Función para normalizar rutas web (remplaza '\' por '/' y recorta desde /usuarios/)
function ruta_web($ruta_absoluta)
{
    // Cambiar barras invertidas a normales
    $ruta_absoluta = str_replace('\\', '/', $ruta_absoluta);
    // Buscar posición de "/usuarios/" para recortar
    $pos = strpos($ruta_absoluta, '/usuarios/');
    if ($pos === false) {
        $pos = strpos($ruta_absoluta, 'usuarios/');
    }
    // Retornar ruta desde usuarios o la original si no se encontró
    return $pos !== false ? '/' . ltrim(substr($ruta_absoluta, $pos), '/') : $ruta_absoluta;
}

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
    $publicacion_id = uniqid('venta_', true);

    // Datos del animal enviados desde el formulario
    $titulo_animal = $_POST['titulo'] ?? '';
    $descripcion_animal = $_POST['descripcion'] ?? '';
    $categoria_animal = $_POST['categoria'] ?? '';
    $raza_animal = $_POST['raza'] ?? '';
    $pureza_animal = $_POST['pureza'] ?? '';
    $sexo_animal = $_POST['sexo'] ?? '';
    $tipo_animal = $_POST['tipo_animal'] ?? '';
    $edad_animal = $_POST['edad'] ?? '';
    $peso_animal = floatval($_POST['peso'] ?? 0);
    $precio_animal = floatval($_POST['precio'] ?? 0);
    $tipo_precio_animal = $_POST['tipo_precio'] ?? '';
    $telefono_animal = $_POST['telefono'] ?? '';
    $correo_animal = $_POST['correo'] ?? '';
    $departamento_animal = $_POST['departamento'] ?? '';
    $municipio_animal = $_POST['municipio'] ?? '';
    $direccion_animal = $_POST['direccion'] ?? '';
    $destacado_animal = (isset($_POST['destacado']) && $_POST['destacado'] === 'on') ? 1 : 0;
    $premium_animal = (isset($_POST['premium']) && $_POST['premium'] === 'on') ? 1 : 0;
    $sugerido = (isset($_POST['sugerido']) && $_POST['sugerido'] === 'on') ? 1 : 0;
    $latitud = floatval($_POST['latitud'] ?? 0.0);
    $longitud = floatval($_POST['longitud'] ?? 0.0);

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

    // Guardar imágenes base64
    if (!empty($_POST['fotos_tmp']) && !empty($_POST['fotos_type'])) {
        foreach ($_POST['fotos_tmp'] as $i => $base64) {
            $data = base64_decode($base64);
            $tipo = $_POST['fotos_type'][$i];
            $ext = explode('/', $tipo)[1];
            $nombre_archivo = uniqid('img_', true) . '.' . $ext;
            $ruta_final = $upload_dir . $nombre_archivo;
            $ruta_relativa = $upload_dir_relativo . $nombre_archivo;
            file_put_contents($ruta_final, $data);
            // Usar función ruta_web para normalizar
            $imagenes_guardadas[] = ruta_web($ruta_relativa);
        }
    }

    // Guardar videos base64
    if (!empty($_POST['videos_tmp']) && !empty($_POST['videos_type'])) {
        foreach ($_POST['videos_tmp'] as $i => $base64) {
            $data = base64_decode($base64);
            $tipo = $_POST['videos_type'][$i];
            $ext = explode('/', $tipo)[1];
            $nombre_archivo = uniqid('vid_', true) . '.' . $ext;
            $ruta_final = $upload_dir . $nombre_archivo;
            $ruta_relativa = $upload_dir_relativo . $nombre_archivo;
            file_put_contents($ruta_final, $data);
            // Normalizar ruta
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

    // Subir imágenes desde $_FILES
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

    // Subir videos desde $_FILES
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

    // Convertir rutas a JSON sin barras escapadas
    $imagenes_json = json_encode($imagenes_guardadas, JSON_UNESCAPED_SLASHES);
    $videos_json = json_encode($videos_guardados, JSON_UNESCAPED_SLASHES);

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
    if ($porcentaje > 0 && $precio_animal > 0) {
        $valor_comision = floor($precio_animal * $porcentaje);
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

    // Crear el objeto Animal
    $animal = new Animal(
        null,
        $titulo_animal,
        $descripcion_animal,
        $categoria_animal,
        $raza_animal,
        $pureza_animal,
        $sexo_animal,
        $tipo_animal,
        $edad_animal,
        $peso_animal,
        $precio_animal,
        $tipo_precio_animal,
        $telefono_animal,
        $correo_animal,
        $departamento_animal,
        $municipio_animal,
        $direccion_animal,
        $destacado_animal,
        $premium_animal,
        $imagenes_json,
        $videos_json,
        $sugerido,
        $fecha_fin,
        $latitud,
        $longitud,
        $usuario_id,
        0,
        $soporte_pago_url, // Aseguramos que se pase la ruta normalizada
        $valor_comision
    );

    // Insertar animal en base de datos
    $conexion->beginTransaction();
    try {
        $animal_insertado = RepositorioAnimal::insertar_animal($conexion, $animal);
        if (!$animal_insertado) {
            throw new Exception('No se pudo insertar el animal.');
        }
        $conexion->commit();
        $conexion->lastInsertId();

        $_SESSION['publicacion_exitosa'] = true;
        $_SESSION['publicacion_id'] = $animal_insertado;

        header("Location: " . RUTA_VENTA);
        exit();
    } catch (Exception $e) {
        $conexion->rollBack();
        echo "Error en la transacción: " . $e->getMessage();
    }
}

// Incluir el encabezado y el navbar
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';

?>



<br><br><br>
<div class="container text-center mt-4 mb-4">
    <h2 class="main-title">
        <i class="fas fa-cow text-success me-2"></i>
        Publica tu ganado
        <i class="fas fa-tractor text-danger ms-2"></i>
    </h2>
    <p class="main-subtitle">Completa el formulario para publicar tu animal</p>
</div>
<?php if ($mensaje_exito): ?>
    <div class="alert alert-success text-center animate-fade-in">
        <strong>¡Tu publicación fue realizada con éxito!</strong><br>
        ID de publicación: <code>animales - <?= htmlspecialchars($mensaje_id) ?></code>
    </div>
<?php endif; ?>

<div class="container mt-5">
    <div class="card tarjeta-registro animate-fade-in">
        <!-- Se eliminó la elección de planes -->
        <!-- Solo se muestra el formulario -->
        <form method="post" action="<?php echo RUTA_PREVIA_VENTA; ?>" id="formulario-publicacion" enctype="multipart/form-data">
            <?php include_once 'plantillas/form_venta_ganado.inc.php'; ?>
            <!-- El input oculto de plan puede eliminarse si ya no se usa en el backend -->
            <!-- <input type="hidden" name="plan" id="input-plan-seleccionado"> -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-danger btn-lg btn-custom animate-fade-in" name="publicar">
                    <i class="fas fa-upload me-2"></i> Publicar
                </button>
            </div>
        </form>
    </div>
</div>

        <!-- JS dinámico -->
        <script>
            const razasPorCategoria = {
                carne: ["Aberdeen Angus", "Limousine", "Bosmara", "Brahman", "Gyr", "Guzerat", "Nelore", "Big master", "Nelore pintado", "Brangus", "Simbrah", "Red sindhi", "Branford", "Herford", "Otros"],
                leche: ["Holstein", "Jersey", "Pardo Suizo", "Gyr Lechero", "Girolando (cruce entre Gyr y Holstein)", "Ayrshire", "Jerhol", "Gusolando", "Bramolando", "Otros"],
                doble: ["Simmental", "Romosinuano", "Blanco Orejinegro (BON)", "Sanmartinero", "Normando", "Girolando", "Pardo suizo", "Otros"]
            };

            document.getElementById('categoria')?.addEventListener('change', function() {
                const categoria = this.value;
                const razaSelect = document.getElementById('raza');
                razaSelect.innerHTML = '<option value="">Selecciona una raza</option>';

                if (razasPorCategoria[categoria]) {
                    razasPorCategoria[categoria].forEach(raza => {
                        const option = document.createElement('option');
                        option.value = raza.toLowerCase();
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
        </script>



        <?php include_once 'plantillas/documento-cierre.inc.php'; ?>