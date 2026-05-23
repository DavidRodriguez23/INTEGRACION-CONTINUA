<?php
include_once 'Conexion.inc.php';
include_once 'RepositorioAnimal.inc.php';
include_once 'RepositorioCaballo.inc.php';
include_once 'Animal.inc.php';
include_once 'Caballo.inc.php';

class EscritorAnimal
{
    // Carrusel de sugeridos
    public static function escribir_carrusel_sugeridos()
    {
        Conexion::abrir_conexion();
        $animales = RepositorioAnimal::obtener_animal_sugerido(Conexion::obtener_conexion());
        $caballos = RepositorioCaballo::obtener_caballos_sugeridos(Conexion::obtener_conexion());

        // Filtrar los que NO están vendidos
        $animales = array_filter($animales, function ($a) {
            return method_exists($a, 'esta_vendido') ? !$a->esta_vendido() : true;
        });

        $caballos = array_filter($caballos, function ($c) {
            return method_exists($c, 'esta_vendido') ? !$c->esta_vendido() : true;
        });

        $publicaciones = array_merge($animales, $caballos);

        usort($publicaciones, function ($a, $b) {
            $fechaA = strtotime($a->obtener_fecha_publicacion());
            $fechaB = strtotime($b->obtener_fecha_publicacion());
            return $fechaB <=> $fechaA;
        });

        self::escribir_carrusel($publicaciones, 'carousel-sugeridos', 'Publicaciones Sugeridas');
    }


    // Carrusel de premium
    public static function escribir_carrusel_premium()
    {
        Conexion::abrir_conexion();
        $animales = RepositorioAnimal::obtener_animal_premium(Conexion::obtener_conexion());
        $caballos = RepositorioCaballo::obtener_caballos_premium(Conexion::obtener_conexion());

        $animales = array_filter($animales, function ($a) {
            return method_exists($a, 'esta_vendido') ? !$a->esta_vendido() : true;
        });

        $caballos = array_filter($caballos, function ($c) {
            return method_exists($c, 'esta_vendido') ? !$c->esta_vendido() : true;
        });

        $publicaciones = array_merge($animales, $caballos);
        usort($publicaciones, function ($a, $b) {
            $fechaA = strtotime($a->obtener_fecha_publicacion());
            $fechaB = strtotime($b->obtener_fecha_publicacion());
            return $fechaB <=> $fechaA;
        });
        self::escribir_carrusel($publicaciones, 'carousel-premium', 'Publicaciones Premium');
    }

    // Carrusel de destacados
    public static function escribir_carrusel_destacados()
    {
        Conexion::abrir_conexion();
        $animales = RepositorioAnimal::obtener_animal_destacado(Conexion::obtener_conexion());
        $caballos = RepositorioCaballo::obtener_caballos_destacados(Conexion::obtener_conexion());
        $animales = array_filter($animales, function ($a) {
            return method_exists($a, 'esta_vendido') ? !$a->esta_vendido() : true;
        });

        $caballos = array_filter($caballos, function ($c) {
            return method_exists($c, 'esta_vendido') ? !$c->esta_vendido() : true;
        });

        $publicaciones = array_merge($animales, $caballos);
        usort($publicaciones, function ($a, $b) {
            $fechaA = strtotime($a->obtener_fecha_publicacion());
            $fechaB = strtotime($b->obtener_fecha_publicacion());
            return $fechaB <=> $fechaA;
        });
        self::escribir_carrusel($publicaciones, 'carousel-destacados', 'Publicaciones Destacadas');
    }

    public static function escribir_carrusel_ultimas_publicaciones()
    {
        Conexion::abrir_conexion();
        $animales = RepositorioAnimal::obtener_animales_normales(Conexion::obtener_conexion());
        $caballos = RepositorioCaballo::obtener_caballos_normales(Conexion::obtener_conexion());
        $animales = array_filter($animales, function ($a) {
            return method_exists($a, 'esta_vendido') ? !$a->esta_vendido() : true;
        });

        $caballos = array_filter($caballos, function ($c) {
            return method_exists($c, 'esta_vendido') ? !$c->esta_vendido() : true;
        });

        $publicaciones = array_merge($animales, $caballos);
        usort($publicaciones, function ($a, $b) {
            $fechaA = strtotime($a->obtener_fecha_publicacion());
            $fechaB = strtotime($b->obtener_fecha_publicacion());
            return $fechaB <=> $fechaA;
        });
        self::escribir_carrusel($publicaciones, 'carousel-ultimas-publicaciones', 'Últimas Publicaciones');
    }

    // Carrusel genérico
    private static function escribir_carrusel($animales, $carousel_id, $titulo)
    {
        if ($animales instanceof Animal) {
            $animales = [$animales];
        }
        if (empty($animales)) {
            echo "<div class='alert alert-info text-center'>No hay publicaciones para mostrar.</div>";
            return;
        }
        // Renderiza todas las tarjetas en un solo slide, el JS se encarga de agruparlas
?>
        <div class="container my-5">
            <h2 class="text-center text-success mb-4"><?= htmlspecialchars($titulo) ?></h2>
            <div id="<?= htmlspecialchars($carousel_id) ?>" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators"></div>
                <div class="carousel-inner"></div>
                <div class="carousel-controls-below d-flex justify-content-between mt-3 px-3" style="display:none;">
                    <button class="boton-carrusel" type="button" data-bs-target="#<?= htmlspecialchars($carousel_id) ?>" data-bs-slide="prev">
                        ⬅ Anterior
                    </button>
                    <button class="boton-carrusel" type="button" data-bs-target="#<?= htmlspecialchars($carousel_id) ?>" data-bs-slide="next">
                        Siguiente ➡
                    </button>
                </div>
                <div class="d-none" id="<?= htmlspecialchars($carousel_id) ?>-all-cards">
                    <?php foreach ($animales as $animal): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
                            <?php self::escribir_tarjeta_animal($animal, false); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <script>
            (function() {
                function getChunkSize() {
                    if (window.innerWidth >= 1200) return 4; // xl+
                    if (window.innerWidth >= 992) return 4; // lg
                    if (window.innerWidth >= 768) return 3; // md
                    if (window.innerWidth >= 576) return 2; // sm
                    return 1; // xs
                }

                function chunkArray(arr, size) {
                    var results = [];
                    for (var i = 0; i < arr.length; i += size) {
                        results.push(arr.slice(i, i + size));
                    }
                    return results;
                }

                function renderCarousel() {
                    var carouselId = "<?= htmlspecialchars($carousel_id) ?>";
                    var allCards = document.querySelectorAll("#" + carouselId + "-all-cards > div");
                    var chunkSize = getChunkSize();
                    var chunks = chunkArray(Array.from(allCards), chunkSize);

                    var carouselInner = document.querySelector("#" + carouselId + " .carousel-inner");
                    var indicators = document.querySelector("#" + carouselId + " .carousel-indicators");
                    var controls = document.querySelector("#" + carouselId + " .carousel-controls-below");

                    carouselInner.innerHTML = "";
                    indicators.innerHTML = "";

                    chunks.forEach(function(chunk, i) {
                        var item = document.createElement("div");
                        item.className = "carousel-item" + (i === 0 ? " active" : "");
                        var row = document.createElement("div");
                        row.className = "row justify-content-center";
                        chunk.forEach(function(card) {
                            row.appendChild(card.cloneNode(true));
                        });
                        item.appendChild(row);
                        carouselInner.appendChild(item);

                        var indicator = document.createElement("button");
                        indicator.type = "button";
                        indicator.setAttribute("data-bs-target", "#" + carouselId);
                        indicator.setAttribute("data-bs-slide-to", i);
                        indicator.setAttribute("aria-label", "Slide " + (i + 1));
                        if (i === 0) {
                            indicator.className = "active";
                            indicator.setAttribute("aria-current", "true");
                        }
                        indicators.appendChild(indicator);
                    });

                    // Mostrar controles solo si hay más de un slide
                    controls.style.display = (chunks.length > 1) ? "flex" : "none";
                    indicators.style.display = (chunks.length > 1) ? "block" : "none";
                }
                window.addEventListener("resize", renderCarousel);
                window.addEventListener("DOMContentLoaded", renderCarousel);
            })();
        </script>
    <?php
    }

    // Tarjeta individual de animal
    public static function escribir_tarjeta_animal($animal, $wrapCol = true)
    {
        $medios_raw = $animal->obtener_imagenes();
        $medios = [];

        if (is_string($medios_raw)) {
            $medios = json_decode($medios_raw, true);
            if (!is_array($medios)) {
                $medios = [$medios_raw];
            }
        } elseif (is_array($medios_raw)) {
            $medios = $medios_raw;
        }


        if (!function_exists('ruta_web')) {
            function ruta_web($ruta_absoluta)
            {
                $ruta_absoluta = str_replace('\\', '/', $ruta_absoluta);
                $pos = strpos($ruta_absoluta, '/usuarios/');
                if ($pos === false) {
                    $pos = strpos($ruta_absoluta, 'usuarios/');
                }
                return $pos !== false ? '/' . ltrim(substr($ruta_absoluta, $pos), '/') : $ruta_absoluta;
            }
        }

        $primer_medio_url = null;
        $es_video = false;
        $mime_type = '';

        if (!empty($medios)) {
            $primer_medio = $medios[0];
            $ext = strtolower(pathinfo($primer_medio, PATHINFO_EXTENSION));
            $video_exts = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'];

            if (in_array($ext, $video_exts)) {
                $es_video = true;
                $mime_map = [
                    'mp4' => 'video/mp4',
                    'webm' => 'video/webm',
                    'ogg' => 'video/ogg',
                    'mov' => 'video/quicktime',
                    'avi' => 'video/x-msvideo',
                    'mkv' => 'video/x-matroska'
                ];
                $mime_type = $mime_map[$ext] ?? 'video/mp4';
            }

            // Normaliza la ruta para mostrar
            $ruta = ruta_web($primer_medio);
            // Elimina doble slash accidental
            $ruta = preg_replace('#/+#','/',$ruta);
            // Solo anteponer /Ganandez si la ruta es relativa
            if (strpos($ruta, '/Ganandez/') !== 0 && strpos($ruta, 'http') !== 0) {
                $ruta = '/Ganandez' . (strpos($ruta, '/') === 0 ? $ruta : '/' . $ruta);
            }
            // Depuración: muestra la ruta generada en HTML (quitar en producción)
            // echo "<!-- Ruta imagen: $ruta -->";
            $primer_medio_url = SERVIDOR . $ruta;
        }

        if (!$primer_medio_url) {
            $primer_medio_url = SERVIDOR . '/img/default-animal.jpg';
        }

        // DEBUG: para verificar la ruta y tipo
        // echo "<!-- Medio: $primer_medio_url - Es video? " . ($es_video ? 'SI' : 'NO') . " -->";

        if ($animal instanceof Caballo) {
            $url = SERVIDOR . '/caballo/' . $animal->obtener_id();
            $icono = '<i class="fas fa-horse me-1"></i>';
            $tipoEtiqueta = '<span class="badge bg-primary ms-1">Caballo</span>';
        } else {
            $url = SERVIDOR . '/animal/' . $animal->obtener_id();
            $icono = '<i class="fas fa-cow me-1"></i>';
            $tipoEtiqueta = '<span class="badge bg-info text-dark ms-1">Ganado</span>';
        }

        $precio_formateado = '$ ' . number_format($animal->obtener_precio(), 0, ',', '.');

        if ($wrapCol) {
            echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">';
        }
    ?>

        <a href="<?= htmlspecialchars($url) ?>" class="enlace-tarjeta" style="text-decoration: none; color: inherit; width: 100%;">
            <div class="card tarjeta-animal mejorada shadow-sm animar-entrada">
                <div class="tarjeta-animal-img-wrapper d-flex align-items-center justify-content-center bg-light" style="height: 200px; overflow: hidden;">
                    <?php if ($animal instanceof Caballo): ?>
                        <i class="fas fa-horse fa-7x text-primary"></i>
                    <?php else: ?>
                        <i class="fas fa-cow fa-7x text-success"></i>
                    <?php endif; ?>
                </div>
                <div class="card-body tarjeta-animal-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="tarjeta-animal-title mb-2">
                            <?= htmlspecialchars($animal->obtener_titulo()) ?>
                            <?= $tipoEtiqueta ?>
                        </h5>
                        <div class="mb-2">
                            <span class="badge bg-success me-1"><i class="fas fa-tag me-1"></i><?= htmlspecialchars($animal->obtener_categoria()) ?></span>
                            <span class="badge bg-warning text-dark"><?= $icono ?><?= htmlspecialchars($animal->obtener_raza()) ?></span>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <p class="precio-animal mt-2 mb-0"><?= $precio_formateado ?></p>
                    </div>
                </div>
            </div>
        </a>

<?php
        if ($wrapCol) {
            echo '</div>';
        }
    }



    public static function mostrar_vista_animal($id_animal)
    {
        $animal = RepositorioAnimal::obtener_animal_por_id(Conexion::obtener_conexion(), $id_animal);
        include_once __DIR__ . '/../vistas/vista-animal.php';
    }
}
?>