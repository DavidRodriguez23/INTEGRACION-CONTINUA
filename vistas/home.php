<?php
include_once 'app/ControlSesion.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/EscritorAnimal.inc.php';

include_once 'plantillas/navbar.inc.php';
include_once 'plantillas/documento-apertura.inc.php';

$usuario_activo = ControlSesion::usuario_activo();

?>

<div class="container-fluid text-center mt-5 pt-5">
    <!-- Sección de bienvenida -->
    <section class="bienvenida-section text-center py-5">
        <div class="container">
            <!-- Logotipo -->
            <img
                src="<?php echo SERVIDOR . '/img/Logo-ganandez.jpg'; ?>"
                alt="Logo Ganandez"
                class="mb-4"
                style="max-width: 150px;">

            <!-- Título principal (a todo el ancho) -->
            <div class="row">
                <div class="col-12">
                    <h1 class="display-4 font-weight-bold text-success">Bienvenido a GANADERÍA LIVESTOCK</h1>
                </div>
            </div>
            <!-- Texto y botones centrados -->
            <div class="row mt-5">
                <div class="col-12 col-md-6 offset-md-3 text-center">
                    <p class="lead text-dark">
                        En <span class="fw-bold text-success">GANADERÍA LIVESTOCK</span> te conectamos con miles de <span class="text-success fw-bold">compradores</span> y <span class="text-danger fw-bold">vendedores</span> de <span class="fw-bold text-info">ganado</span> y <span class="fw-bold text-primary">caballos</span> en toda Colombia. Una plataforma confiable, rápida y segura para hacer negocios agropecuarios sin complicaciones.
                    </p>
                    <?php
                    $sesion_iniciada = ControlSesion::sesion_iniciada();
                    $deshabilitar_botones = $sesion_iniciada && !$usuario_activo;
                    ?>

                    <?php if ($deshabilitar_botones): ?>
                        <div class="alert alert-warning text-center my-4">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Tu cuenta está <strong>inactiva</strong>. No puedes publicar anuncios hasta que sea activada.
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo RUTA_COMPRA; ?>" class="btn btn-danger btn-lg mx-2">Comprar</a>

                    <a href="<?= RUTA_VENTA ?>"
                        class="btn btn-outline-success btn-lg mx-2 <?= $deshabilitar_botones ? 'disabled' : '' ?>"
                        <?= $deshabilitar_botones ? 'tabindex="-1" aria-disabled="true" style="pointer-events:none;"' : '' ?>>
                        Vender Ganado
                    </a>

                    <a href="<?= RUTA_VENTA_CABALLO ?>"
                        class="btn btn-outline-primary btn-lg mx-2 <?= $deshabilitar_botones ? 'disabled' : '' ?>"
                        <?= $deshabilitar_botones ? 'tabindex="-1" aria-disabled="true" style="pointer-events:none;"' : '' ?>>
                        Vender Caballos
                    </a>


                </div>
            </div>
        </div>
    </section>

    <?php
    Conexion::abrir_conexion();
    RepositorioAnimal::eliminar_anuncios_vencidos(Conexion::obtener_conexion());
    RepositorioCaballo::eliminar_anuncios_vencidos(Conexion::obtener_conexion());

    EscritorAnimal::escribir_carrusel_sugeridos();
    ?>


    <!-- Interacción: Banner de confianza -->
    <div class="container my-4">
        <div class="alert alert-success shadow-sm text-center" style="font-size:1.15rem;">
            <i class="fas fa-shield-alt me-2"></i>
            <strong>¡Compra y vende con total seguridad!</strong> Todos los usuarios y publicaciones son verificados por nuestro equipo.
        </div>
    </div>

    <?php
    echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
    ?>
    <!-- Filtros -->
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
    <!-- Sección informativa para compradores y vendedores -->
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="p-4 bg-light rounded shadow-sm h-100">
                    <h3 class="text-success mb-3"><i class="fas fa-shopping-cart me-2"></i>¿Quieres Comprar?</h3>
                    <p class="mb-2">
                        Explora cientos de publicaciones de ganado de calidad, compara precios y contacta directamente con los vendedores de todo el país.
                    </p>
                    <a href="<?php echo RUTA_COMPRA; ?>" class="btn btn-danger btn-lg mt-2">Ver publicaciones</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 bg-light rounded shadow-sm h-100">
                    <h3 class="text-danger mb-3"><i class="fas fa-bullhorn me-2"></i>¿Quieres Vender?</h3>
                    <p class="mb-2">
                        Publica tu ganado en minutos, llega a miles de compradores potenciales y gestiona tus anuncios de forma sencilla y segura.
                    </p>
                    <?php if (ControlSesion::sesion_iniciada()) : ?>
                        <?php if (ControlSesion::usuario_activo()) : ?>
                            <a href="<?php echo RUTA_VENTA; ?>" class="btn btn-outline-success btn-lg mt-2">Publicar ahora</a>
                        <?php else : ?>
                            <a href="#" class="btn btn-secondary btn-lg mt-2 disabled" title="Tu cuenta está inactiva">Publicar ahora</a>
                            <div class="text-danger mt-2"><i class="fas fa-ban me-1"></i> Tu cuenta está inactiva. Contacta al soporte.</div>
                        <?php endif; ?>
                    <?php else : ?>
                        <a href="<?php echo RUTA_LOGIN; ?>" class="btn btn-outline-secondary btn-lg mt-2">Inicia sesión para publicar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
    EscritorAnimal::escribir_carrusel_premium();
    ?>

    <!-- Interacción: Llamado a la acción para soporte -->
    <div class="container my-4">
        <div class="alert alert-warning shadow-sm text-center" style="font-size:1.1rem;">
            <i class="fas fa-headset me-2"></i>
            ¿Tienes dudas? <a href="<?php echo RUTA_CONTACTENOS; ?>" class="fw-bold text-success">Contáctanos aquí</a> y recibe atención personalizada.
        </div>
    </div>

    <?php
    echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
    EscritorAnimal::escribir_carrusel_destacados();
    ?>

    <!-- Interacción: Banner de sostenibilidad -->
    <div class="container my-4">
        <div class="bg-success bg-opacity-10 border border-success rounded shadow-sm p-3 text-center">
            <i class="fas fa-leaf text-success me-2"></i>
            <span class="fw-bold text-success">Comprometidos con la ganadería sostenible y el bienestar animal.</span>
        </div>
    </div>

    <?php
    echo '<hr class="my-5" style="border-top: 2px solid #28a745;">';
    EscritorAnimal::escribir_carrusel_ultimas_publicaciones();
    ?>
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
        // Normaliza la ruta
        $img = preg_replace('#/+#','/',$img);
        if ($img && strpos($img, '/Ganandez/') !== 0 && strpos($img, 'http') !== 0) {
            $img = '/Ganandez' . (strpos($img, '/') === 0 ? $img : '/' . $img);
        }
        // echo "<!-- Ruta imagen mapa: $img -->";
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
                <div class="tarjeta-animal-img-wrapper d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                    <?php if ($es_caballo): ?>
                        <i class="fas fa-horse fa-5x text-primary"></i>
                    <?php else: ?>
                        <i class="fas fa-cow fa-5x text-success"></i>
                    <?php endif; ?>
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
    ?>