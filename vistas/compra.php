<?php
include_once 'app/ControlSesion.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/EscritorAnimal.inc.php';


include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>
<br><br>
<div class="container my-5 seccion-explora">
    <h1 class="text-center text-success mb-4 fw-bold titulo-seccion-explora">
        <i class="fas fa-search-location me-2"></i>
        Explora nuestras categorías y encuentra el ganado ideal para ti
    </h1>
    <p class="lead text-center subtitulo-seccion-explora">
        Accede a una amplia selección de ganado y caballos cuidadosamente clasificados. Filtra por raza, edad, ubicación y mucho más para encontrar exactamente lo que necesitas, de forma rápida y segura.
    </p>
</div>



<?php

Conexion::abrir_conexion();
RepositorioAnimal::eliminar_anuncios_vencidos(Conexion::obtener_conexion());
RepositorioCaballo::eliminar_anuncios_vencidos(Conexion::obtener_conexion());
?>
<!-- Filtros -->
<div class="text-center">
    <div class="container my-5">
        <div class="card tarjeta-registro fondo-registro animate-fade-in">
            <div class="encabezado-degradado">
                <h3 class="titulo-encabezado mb-0">
                    <i class="fas fa-filter me-2"></i>Filtro avanzado de búsqueda
                </h3>
            </div>
            <form id="filtro-avanzado-form" class="row g-3 pt-3" method="get" action="#resultados-busqueda">

                <!-- Tipo de búsqueda -->
                <div class="col-md-4">
                    <label for="tipoBusqueda" class="form-label fw-bold text-success">
                        <i class="fas fa-search me-1"></i> ¿Qué deseas buscar?
                    </label>
                    <select class="form-select" id="tipoBusqueda" name="tipoBusqueda" required>
                        <option value="">Selecciona una opción</option>
                        <option value="ganado" <?= (isset($_GET['tipoBusqueda']) && $_GET['tipoBusqueda'] == 'ganado') ? 'selected' : '' ?>>Ganado</option>
                        <option value="caballo" <?= (isset($_GET['tipoBusqueda']) && $_GET['tipoBusqueda'] == 'caballo') ? 'selected' : '' ?>>Caballos</option>
                    </select>
                </div>

                <!-- Filtros GANADO -->
                <div id="filtros-ganado" style="display:none;">
                    <hr class="mt-4">
                    <h5 class="text-success"><i class="fas fa-cow me-2"></i>Filtros para Ganado</h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" name="categoriaGanado">
                                <option value="">Todas</option>
                                <option value="Carne" <?= (isset($_GET['categoriaGanado']) && $_GET['categoriaGanado'] == 'Carne') ? 'selected' : '' ?>>Carne</option>
                                <option value="Leche" <?= (isset($_GET['categoriaGanado']) && $_GET['categoriaGanado'] == 'Leche') ? 'selected' : '' ?>>Leche</option>
                                <option value="Doble propósito" <?= (isset($_GET['categoriaGanado']) && $_GET['categoriaGanado'] == 'Doble propósito') ? 'selected' : '' ?>>Doble propósito</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Raza</label>
                            <input type="text" class="form-control" name="razaGanado" value="<?= $_GET['razaGanado'] ?? '' ?>" placeholder="Ej: Brahman">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sexo</label>
                            <select class="form-select" name="sexoGanado">
                                <option value="">Todos</option>
                                <option value="Macho" <?= (isset($_GET['sexoGanado']) && $_GET['sexoGanado'] == 'Macho') ? 'selected' : '' ?>>Macho</option>
                                <option value="Hembra" <?= (isset($_GET['sexoGanado']) && $_GET['sexoGanado'] == 'Hembra') ? 'selected' : '' ?>>Hembra</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Edad mínima (meses)</label>
                            <input type="number" class="form-control" name="edadGanadoMinMeses" min="0" value="<?= $_GET['edadGanadoMinMeses'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Edad máxima (meses)</label>
                            <input type="number" class="form-control" name="edadGanadoMaxMeses" min="0" value="<?= $_GET['edadGanadoMaxMeses'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Edad mínima (años)</label>
                            <input type="number" class="form-control" name="edadGanadoMinAnios" min="0" value="<?= $_GET['edadGanadoMinAnios'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Edad máxima (años)</label>
                            <input type="number" class="form-control" name="edadGanadoMaxAnios" min="0" value="<?= $_GET['edadGanadoMaxAnios'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-map-marker-alt me-1"></i>Departamento</label>
                            <input type="text" class="form-control" name="departamentoGanado" value="<?= $_GET['departamentoGanado'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Municipio</label>
                            <input type="text" class="form-control" name="municipioGanado" value="<?= $_GET['municipioGanado'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Peso mínimo (kg)</label>
                            <input type="number" class="form-control" name="pesoMin" min="0" value="<?= $_GET['pesoMin'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Peso máximo (kg)</label>
                            <input type="number" class="form-control" name="pesoMax" min="0" value="<?= $_GET['pesoMax'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-dollar-sign me-1"></i>Precio mínimo</label>
                            <input type="number" class="form-control" name="precioMin" min="0" value="<?= $_GET['precioMin'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-dollar-sign me-1"></i>Precio máximo</label>
                            <input type="number" class="form-control" name="precioMax" min="0" value="<?= $_GET['precioMax'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-dna me-1"></i>Pureza</label>
                            <input type="text" class="form-control" name="pureza" value="<?= $_GET['pureza'] ?? '' ?>">
                        </div>
                    </div>
                </div>

                <!-- Filtros CABALLOS -->
                <div id="filtros-caballo" style="display:none;">
                    <hr class="mt-4">
                    <h5 class="text-success"><i class="fas fa-horse me-2"></i>Filtros para Caballos</h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" name="categoriaCaballo">
                                <option value="">Todas</option>
                                <option value="Caballo Criollo Colombiano" <?= (isset($_GET['categoriaCaballo']) && $_GET['categoriaCaballo'] == 'Caballo Criollo Colombiano') ? 'selected' : '' ?>>Criollo Colombiano</option>
                                <option value="Caballo Percherón" <?= (isset($_GET['categoriaCaballo']) && $_GET['categoriaCaballo'] == 'Caballo Percherón') ? 'selected' : '' ?>>Percherón</option>
                                <option value="Caballo Árabe" <?= (isset($_GET['categoriaCaballo']) && $_GET['categoriaCaballo'] == 'Caballo Árabe') ? 'selected' : '' ?>>Árabe</option>
                                <option value="Cuarto de Milla" <?= (isset($_GET['categoriaCaballo']) && $_GET['categoriaCaballo'] == 'Cuarto de Milla') ? 'selected' : '' ?>>Cuarto de Milla</option>
                                <option value="Caballos Mulares" <?= (isset($_GET['categoriaCaballo']) && $_GET['categoriaCaballo'] == 'Caballos Mulares') ? 'selected' : '' ?>>Mulares</option>
                                <option value="Otros" <?= (isset($_GET['categoriaCaballo']) && $_GET['categoriaCaballo'] == 'Otros') ? 'selected' : '' ?>>Otros</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Aptitud/Raza</label>
                            <input type="text" class="form-control" name="razaCaballo" value="<?= $_GET['razaCaballo'] ?? '' ?>" placeholder="Ej: Trocha, Paso fino">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sexo</label>
                            <select class="form-select" name="sexoCaballo">
                                <option value="">Todos</option>
                                <option value="Macho" <?= ($_GET['sexoCaballo'] ?? '') === 'Macho' ? 'selected' : '' ?>>Macho</option>
                                <option value="Hembra" <?= ($_GET['sexoCaballo'] ?? '') === 'Hembra' ? 'selected' : '' ?>>Hembra</option>
                                <option value="Macho castrado" <?= ($_GET['sexoCaballo'] ?? '') === 'Macho castrado' ? 'selected' : '' ?>>Macho castrado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-map-marker-alt me-1"></i>Departamento</label>
                            <input type="text" class="form-control" name="departamentoCaballo" value="<?= $_GET['departamentoCaballo'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Municipio</label>
                            <input type="text" class="form-control" name="municipioCaballo" value="<?= $_GET['municipioCaballo'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Edad mínima (meses)</label>
                            <input type="number" class="form-control" name="edadCaballoMinMeses" min="0" value="<?= $_GET['edadCaballoMinMeses'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Edad máxima (meses)</label>
                            <input type="number" class="form-control" name="edadCaballoMaxMeses" min="0" value="<?= $_GET['edadCaballoMaxMeses'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Edad mínima (años)</label>
                            <input type="number" class="form-control" name="edadCaballoMinAnios" min="0" value="<?= $_GET['edadCaballoMinAnios'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Edad máxima (años)</label>
                            <input type="number" class="form-control" name="edadCaballoMaxAnios" min="0" value="<?= $_GET['edadCaballoMaxAnios'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-dollar-sign me-1"></i>Precio mínimo</label>
                            <input type="number" class="form-control" name="precioMin" min="0" value="<?= $_GET['precioMin'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-dollar-sign me-1"></i>Precio máximo</label>
                            <input type="number" class="form-control" name="precioMax" min="0" value="<?= $_GET['precioMax'] ?? '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fas fa-star me-1"></i>Características</label>
                            <input type="text" class="form-control" name="caracteristicas" value="<?= $_GET['caracteristicas'] ?? '' ?>" placeholder="Ej: Ágil, dócil">
                        </div>
                    </div>
                </div>

                <div class="col-12 text-end mt-4">
                    <button type="submit" class="btn btn-success btn-lg shadow-sm">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once 'app/FiltroAvanzado.inc.php'; ?>

    <?php
    echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
    ?>
    <?php if (!empty($tarjetas_resultado)): ?>
        <div class="container my-5" id="resultados-busqueda">
            <h2 class="text-center text-success mb-4"><i class="fas fa-search me-2"></i>Resultados de la búsqueda</h2>
            <div class="row">
                <?php foreach ($tarjetas_resultado as $animal): ?>
                    <?php EscritorAnimal::escribir_tarjeta_animal($animal); ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php elseif (isset($_GET['tipoBusqueda'])): ?>
        <div class="container my-5" id="resultados-busqueda">

            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                No se encontraron resultados para tu búsqueda.
            </div>
        </div>
    <?php endif; ?>

    <?php
    echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
    ?>
    <?php
    EscritorAnimal::escribir_carrusel_sugeridos();
    echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
    EscritorAnimal::escribir_carrusel_premium();
    echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
    EscritorAnimal::escribir_carrusel_destacados();

    EscritorAnimal::escribir_carrusel_ultimas_publicaciones();
    ?>
</div>
<?php
// Obtener ubicaciones
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

// Animales
$sentencia = $conexion->prepare("SELECT * FROM animales WHERE latitud IS NOT NULL AND longitud IS NOT NULL");
$sentencia->execute();
$ubicaciones = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Caballos
$sentencia_caballos = $conexion->prepare("SELECT * FROM caballos WHERE latitud IS NOT NULL AND longitud IS NOT NULL");
$sentencia_caballos->execute();
$ubicaciones_caballos = $sentencia_caballos->fetchAll(PDO::FETCH_ASSOC);

// Unir ambos arrays
$ubicaciones_totales = array_merge($ubicaciones, $ubicaciones_caballos);
?>
<hr class="my-5" style="border-top: 2px solid #28a745;">
<div class="container my-5">
    <h2 class="text-center text-success mb-4"><i class="fas fa-map-marked-alt me-2"></i> Ubicación de los animales publicados</h2>
    <div id="mapa-ubicaciones" style="height: 500px;" class="rounded shadow-sm"></div>
</div>

<script>
    var mapa = L.map('mapa-ubicaciones').setView([4.5709, -74.2973], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(mapa);

    var iconoGanandez = L.icon({
        iconUrl: '<?php echo SERVIDOR . "/img/Logo-ganandez.jpg"; ?>',
        iconSize: [50, 50],
        iconAnchor: [25, 40],
        popupAnchor: [0, -70],
        className: 'icono-redondo'
    });
</script>

<?php foreach ($ubicaciones_totales as $ubicacion):
    $es_caballo = array_key_exists('precio', $ubicacion) && array_key_exists('caracteristicas', $ubicacion);

    if ($es_caballo) {
        $animal = RepositorioCaballo::obtener_caballo_por_id($conexion, $ubicacion['id']);
    } else {
        $animal = RepositorioAnimal::obtener_animal_por_id($conexion, $ubicacion['id']);
    }

    // 🔒 FILTRAR vendidos correctamente usando esta_vendido()
    if (method_exists($animal, 'esta_vendido') && $animal->esta_vendido()) {
        continue;
    }

    $imagenes_raw = $animal->obtener_imagenes();

    if (is_string($imagenes_raw)) {
        $imagenes = json_decode($imagenes_raw, true);
        if (!is_array($imagenes)) {
            $imagenes = [];
        }
    } elseif (is_array($imagenes_raw)) {
        $imagenes = $imagenes_raw;
    } else {
        $imagenes = [];
    }

    $img = (!empty($imagenes) && is_array($imagenes)) ? $imagenes[0] : 'img/default-animal.jpg';
    $url = $es_caballo ? SERVIDOR . '/caballo/' . $animal->obtener_id() : SERVIDOR . '/animal/' . $animal->obtener_id();
    $precio_formateado = '$ ' . number_format($animal->obtener_precio(), 0, ',', '.');
    $categoria = htmlspecialchars($animal->obtener_categoria());
    $raza = htmlspecialchars($animal->obtener_raza());
    $titulo = htmlspecialchars($animal->obtener_titulo());
?>

    <script>
        L.marker([<?= $ubicacion['latitud'] ?>, <?= $ubicacion['longitud'] ?>], {
                icon: iconoGanandez
            }).addTo(mapa)
            .bindPopup(`
        <a href="<?= $url ?>" style="text-decoration: none; color: inherit; width: 100%;">
            <div class="card tarjeta-animal mejorada shadow-sm animar-entrada text-center" style="width: 16rem;">
                <div class="tarjeta-animal-img-wrapper">
                    <img src="<?= htmlspecialchars($img) ?>" class="card-img-top tarjeta-animal-img" alt="Imagen de la publicación">
                </div>
                <div class="card-body tarjeta-animal-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="tarjeta-animal-title mb-2"><?= $titulo ?></h5>
                        <div class="mb-2">
                            <span class="badge bg-success me-1"><i class="fas fa-tag me-1"></i><?= $categoria ?></span>
                            <span class="badge bg-warning text-dark"><i class="fas fa-cow me-1"></i><?= $raza ?></span>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <p class="precio-animal mt-2 mb-0"><?= $precio_formateado ?></p>
                    </div>
                </div>
            </div>
        </a>
    `);
    </script>

<?php endforeach; ?>

<script>
    // Mostrar/ocultar filtros según el tipo de búsqueda
    function mostrarFiltros() {
        var tipo = document.getElementById('tipoBusqueda').value;
        document.getElementById('filtros-ganado').style.display = (tipo === 'ganado') ? 'block' : 'none';
        document.getElementById('filtros-caballo').style.display = (tipo === 'caballo') ? 'block' : 'none';
    }
    document.getElementById('tipoBusqueda').addEventListener('change', mostrarFiltros);
    window.addEventListener('DOMContentLoaded', mostrarFiltros);
</script>
<?php
include_once 'plantillas/documento-cierre.inc.php';
