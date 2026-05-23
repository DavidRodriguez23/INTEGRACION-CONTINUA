<?php
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/Caballo.inc.php';
include_once 'app/RepositorioCaballo.inc.php';
include_once 'app/RepositorioAnimal.inc.php';
include_once 'app/EscritorAnimal.inc.php';
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';

if (!isset($animal) || !$animal instanceof Caballo) {
    echo "<div class='alert alert-danger text-center'>No se encontró la publicación.</div>";
    include_once 'plantillas/documento-cierre.inc.php';
    return;
}

function ruta_web($ruta_absoluta)
{
    $ruta_absoluta = str_replace('\\', '/', $ruta_absoluta);
    $pos = strpos($ruta_absoluta, '/usuarios/');
    if ($pos === false) {
        $pos = strpos($ruta_absoluta, 'usuarios/');
    }
    return $pos !== false ? '/' . ltrim(substr($ruta_absoluta, $pos), '/') : $ruta_absoluta;
}

function obtener_mime_video($ext)
{
    $ext = strtolower($ext);
    $mimes = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg'];
    return $mimes[$ext] ?? 'video/mp4';
}

function json_decode_recursivo($json, $depth = 3)
{
    $decoded = $json;
    while (is_string($decoded) && $depth-- > 0) {
        $decoded = json_decode($decoded, true);
    }
    return $decoded;
}

$imagenes_raw = json_decode_recursivo($animal->obtener_imagenes()) ?? [];
$videos_raw = json_decode_recursivo($animal->obtener_videos()) ?? [];

$imagenes = [];
$videos = [];

foreach ($imagenes_raw as $media_url) {
    $ext = strtolower(pathinfo($media_url, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        $imagenes[] = $media_url;
    } elseif (in_array($ext, ['mp4', 'webm', 'ogg'])) {
        $videos[] = $media_url;
    }
}

foreach ($videos_raw as $media_url) {
    if (!in_array($media_url, $videos)) {
        $videos[] = $media_url;
    }
}

$precio_formateado = number_format($animal->obtener_precio(), 0, ',', '.') . ' COP';

// Obtener latitud y longitud del caballo
$latitud = $animal->obtener_latitud();
$longitud = $animal->obtener_longitud();
?>
<br><br>
<div class="container my-5">
    <div class="row">
        <!-- Contenido principal -->
        <div class="col-lg-8 animate-fade-in">
            <div class="card tarjeta-animal p-4">
                <div class="row g-0">
                    <div class="col-md-5 mb-4">
                        <!-- Título sección imágenes -->
                        <?php if (!empty($imagenes)): ?>
                            <h4 class="section-title mb-3">
                                <i class="bi bi-images me-2"></i>Imágenes
                            </h4>
                            <div id="carouselImagenes" class="carousel slide mb-4" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php foreach ($imagenes as $k => $img_url): ?>
                                        <div class="carousel-item<?= $k === 0 ? ' active' : '' ?>">
                                            <div class="lupa-container">
                                                <img src="<?= SERVIDOR . ruta_web($img_url) ?>"
                                                    class="d-block w-100 rounded img-lupa"
                                                    style="object-fit:cover; min-height:360px; max-height:480px; cursor: zoom-in;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalImagenCompleta"
                                                    data-img="<?= SERVIDOR . ruta_web($img_url) ?>"
                                                    data-index="<?= $k ?>">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselImagenes" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselImagenes" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                                <div class="carousel-indicators mt-3">
                                    <?php foreach ($imagenes as $k => $_): ?>
                                        <button type="button" data-bs-target="#carouselImagenes" data-bs-slide-to="<?= $k ?>" class="<?= $k === 0 ? 'active' : '' ?>" aria-current="<?= $k === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $k + 1 ?>"></button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <hr>
                        <?php endif; ?>

                        <!-- Título sección videos -->
                        <?php if (!empty($videos)): ?>
                            <h4 class="section-title mb-3">
                                <i class="bi bi-camera-video-fill me-2"></i>Videos
                            </h4>
                            <div id="carouselVideos" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php foreach ($videos as $k => $video_url): ?>
                                        <div class="carousel-item<?= $k === 0 ? ' active' : '' ?>">
                                            <video class="d-block w-100 rounded" style="object-fit:cover; min-height:360px; max-height:480px;" controls>
                                                <source src="<?= SERVIDOR . ruta_web($video_url) ?>" type="<?= obtener_mime_video(pathinfo($video_url, PATHINFO_EXTENSION)) ?>">
                                            </video>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselVideos" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselVideos" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-7 d-flex flex-column justify-content-between ps-md-4">
                        <h2 class="main-title text-center mb-4"><?= htmlspecialchars($animal->obtener_titulo()) ?></h2>

                        <div class="precio-animal precio-vista-animal">
                            <i class="bi bi-currency-dollar me-3"></i><strong><?= $precio_formateado ?></strong>
                        </div>

                        <!-- Sección Descripción -->
                        <?php if ($animal->obtener_descripcion()): ?>
                            <section class="mb-5">
                                <h4 class="section-title">
                                    <i class="bi bi-card-text me-3"></i>Descripción
                                </h4>
                                <p class="section-content"><?= nl2br(htmlspecialchars($animal->obtener_descripcion())) ?></p>
                                <hr>
                            </section>
                        <?php endif; ?>

                        <!-- Sección Datos adicionales -->
                        <section class="mb-5">
                            <h4 class="section-title mb-4">
                                <i class="bi bi-info-circle-fill me-3"></i>Detalles
                            </h4>
                            <ul class="list-group list-group-flush fs-4">
                                <li class="list-group-item">
                                    <i class="bi bi-tags-fill me-3 text-success"></i><strong>Categoría:</strong> <?= htmlspecialchars($animal->obtener_categoria()) ?>
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-award-fill me-3 text-warning"></i><strong>Raza / Aptitud:</strong> <?= htmlspecialchars($animal->obtener_raza()) ?>
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-gender-ambiguous me-3 text-danger"></i><strong>Sexo:</strong> <?= htmlspecialchars($animal->obtener_sexo()) ?>
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-hourglass-split me-3 text-info"></i><strong>Edad:</strong> <?= htmlspecialchars($animal->obtener_edad()) ?>
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-bar-chart-fill me-3 text-secondary"></i><strong>Peso:</strong> <?= htmlspecialchars($animal->obtener_peso()) ?> kg
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-star-fill me-3 text-warning"></i><strong>Características:</strong>
                                    <?php
                                    $carac = $animal->obtener_caracteristicas();
                                    if ($carac) {
                                        $caracArr = is_array($carac) ? $carac : json_decode($carac, true);
                                        if (is_array($caracArr)) {
                                            echo htmlspecialchars(implode(', ', $caracArr));
                                        } else {
                                            echo htmlspecialchars($carac);
                                        }
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </li>
                            </ul>
                        </section>
                        <hr>
                        <!-- Sección Ubicación y Contacto -->
                        <section>
                            <h4 class="section-title mb-4">
                                <i class="bi bi-geo-alt-fill me-3"></i>Ubicación & Contacto
                            </h4>
                            <p class="section-content fs-5 mb-3">
                                <i class="bi bi-flag-fill me-2" title="Departamento"></i> <?= htmlspecialchars($animal->obtener_departamento()) ?><br>
                                <i class="bi bi-building me-2" title="Municipio"></i> <?= htmlspecialchars($animal->obtener_municipio()) ?><br>
                                <i class="bi bi-geo me-2" title="Dirección"></i> <?= htmlspecialchars($animal->obtener_direccion()) ?>
                            </p>
                            <p class="section-content fs-5">
                                <i class="bi bi-telephone-fill me-2 text-success"></i><strong>Teléfono:</strong> <?= htmlspecialchars($animal->obtener_telefono()) ?><br>
                                <i class="bi bi-envelope-fill me-2 text-primary"></i><strong>Correo:</strong> <?= htmlspecialchars($animal->obtener_correo()) ?>
                            </p>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <!-- Otras publicaciones -->
        <div class="col-lg-4">
            <div class="seccion-explora text-center p-4">
                <h2 class="text-center mb-4 text-dark titulo-otras-publicaciones">
                    <i class="fas fa-layer-group me-2 text-warning"></i>Otras Publicaciones
                </h2>
                <h3 class="mb-4 text-muted small">
                    Descubre más animales publicados recientemente que podrían interesarte. ¡Explora y encuentra el ejemplar ideal para ti!
                </h3>
                <?php
                Conexion::abrir_conexion();
                RepositorioAnimal::eliminar_anuncios_vencidos(Conexion::obtener_conexion());
                RepositorioCaballo::eliminar_anuncios_vencidos(Conexion::obtener_conexion());

                // 1. Obtener publicaciones por grupo
                $grupos = [
                    array_merge(
                        RepositorioCaballo::obtener_caballos_sugeridos(Conexion::obtener_conexion()),
                        RepositorioAnimal::obtener_animal_sugerido(Conexion::obtener_conexion())
                    ),
                    array_merge(
                        RepositorioCaballo::obtener_caballos_premium(Conexion::obtener_conexion()),
                        RepositorioAnimal::obtener_animal_premium(Conexion::obtener_conexion())
                    ),
                    array_merge(
                        RepositorioCaballo::obtener_caballos_destacados(Conexion::obtener_conexion()),
                        RepositorioAnimal::obtener_animal_destacado(Conexion::obtener_conexion())
                    ),
                    array_merge(
                        RepositorioCaballo::obtener_caballos_normales(Conexion::obtener_conexion()),
                        RepositorioAnimal::obtener_animales_normales(Conexion::obtener_conexion())
                    )
                ];


                // 2. Inicializar variables
                $ids_vistos = [$animal->obtener_id()];
                $sugerencias = [];

                // 3. Procesar grupos en orden, mezclando y acumulando hasta 3 sugerencias únicas
                foreach ($grupos as $grupo) {
                    shuffle($grupo);
                    foreach ($grupo as $item) {
                        if ($item && !in_array($item->obtener_id(), $ids_vistos)) {
                            $sugerencias[] = $item;
                            $ids_vistos[] = $item->obtener_id();
                        }
                        if (count($sugerencias) >= 3) break 2; // Salir de ambos bucles
                    }
                }
                $cuantas = count($sugerencias);

                if ($cuantas === 1) {
                    echo "<div class='alert alert-warning small'>Solo encontramos una publicación relacionada en este momento.</div>";
                } elseif ($cuantas === 2) {
                    echo "<div class='alert alert-warning small'>Hemos encontrado dos publicaciones que podrían interesarte.</div>";
                }

                // 4. Mostrar sugerencias
                if (!empty($sugerencias)) {
                    // Filtrar sugerencias para excluir animales/caballos vendidos
                    $sugerencias = array_filter($sugerencias, function ($item) {
                        return method_exists($item, 'esta_vendido') ? !$item->esta_vendido() : true;
                    });

                    if (!empty($sugerencias)) {
                        $contador = 0;
                        foreach ($sugerencias as $sug) {
                            if ($contador == 0) {
                                EscritorAnimal::escribir_tarjeta_animal($sug, false);
                            } else {
                                echo '<div class="mt-4">';
                                EscritorAnimal::escribir_tarjeta_animal($sug, false);
                                echo '</div>';
                            }
                            $contador++;
                        }
                    } else {
                        echo "<div class='alert alert-info'>No hay otras publicaciones disponibles que no estén vendidas.</div>";
                    }
                } else {
                    echo "<div class='alert alert-info'>No hay otras publicaciones para mostrar.</div>";
                }

                ?>

            </div>
        </div>

    </div>
</div>
<!-- Modal para mostrar imagen completa con navegación -->
<div class="modal fade" id="modalImagenCompleta" tabindex="-1" aria-labelledby="modalImagenCompletaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="modal-content bg-dark border-0 position-relative">

            <!-- Botón cerrar -->
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4 fs-2 z-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>

            <!-- Flechas navegación -->
            <button id="prevImagenModal" class="btn btn-light position-absolute start-0 top-50 translate-middle-y z-3" style="font-size: 2rem;">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button id="nextImagenModal" class="btn btn-light position-absolute end-0 top-50 translate-middle-y z-3" style="font-size: 2rem;">
                <i class="bi bi-chevron-right"></i>
            </button>

            <!-- Imagen en grande -->
            <div class="modal-body p-0 d-flex justify-content-center align-items-center overflow-auto">
                <img src="" id="imagenCompletaModal" class="rounded shadow-lg"
                    style="width: auto; height: auto; min-width: 40%; min-height: 40%; max-width: none; max-height: none; object-fit: contain; background-color: #fff;"
                    alt="Imagen completa">
            </div>
        </div>
    </div>
</div>

<script>
    const imagenes = Array.from(document.querySelectorAll('.img-lupa'));
    const modalImg = document.getElementById('imagenCompletaModal');
    const modal = document.getElementById('modalImagenCompleta');

    let currentIndex = 0;

    // Función para mostrar imagen según el índice
    function mostrarImagen(index) {
        if (index < 0) {
            index = imagenes.length - 1;
        } else if (index >= imagenes.length) {
            index = 0;
        }

        currentIndex = index;
        const nuevaImg = imagenes[currentIndex];
        modalImg.src = nuevaImg.getAttribute('data-img');
    }

    // Evento click en cada imagen del carrusel
    imagenes.forEach((img, index) => {
        img.addEventListener('click', () => {
            currentIndex = index;
            mostrarImagen(currentIndex);
        });
    });

    // Botones de navegación
    document.getElementById('prevImagenModal').addEventListener('click', (e) => {
        e.stopPropagation(); // Evita conflictos con otros clics
        mostrarImagen(currentIndex - 1);
    });

    document.getElementById('nextImagenModal').addEventListener('click', (e) => {
        e.stopPropagation();
        mostrarImagen(currentIndex + 1);
    });

    // Flechas del teclado
    document.addEventListener('keydown', function(e) {
        if (!modal.classList.contains('show')) return;
        if (e.key === 'ArrowLeft') {
            mostrarImagen(currentIndex - 1);
        } else if (e.key === 'ArrowRight') {
            mostrarImagen(currentIndex + 1);
        }
    });
</script>

<!-- Sección del mapa de ubicación individual -->
<?php if ($latitud && $longitud): ?>
    <div class="container my-5">
        <h4 class="mb-3 text-success"><i class="fas fa-map-marker-alt me-2"></i>Ubicación en el mapa</h4>
        <div id="mapa-ubicacion-animal" style="height: 400px;" class="rounded shadow"></div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var mapaAnimal = L.map('mapa-ubicacion-animal').setView([<?= $latitud ?>, <?= $longitud ?>], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(mapaAnimal);

            var iconoGanandez = L.icon({
                iconUrl: '<?= SERVIDOR . "/img/Logo-ganandez.jpg"; ?>',
                iconSize: [50, 50],
                iconAnchor: [25, 40],
                popupAnchor: [0, -70],
                className: 'icono-redondo'
            });

            L.marker([<?= $latitud ?>, <?= $longitud ?>], {
                    icon: iconoGanandez
                })
                .addTo(mapaAnimal)
                .bindPopup(`<strong><?= htmlspecialchars($animal->obtener_titulo()) ?></strong>`)
                .openPopup();
        });
    </script>
<?php endif; ?>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>