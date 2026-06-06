<?php
include_once 'app/ControlSesion.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/EscritorAnimal.inc.php';
include_once 'plantillas/navbar.inc.php';

$usuario_activo = ControlSesion::usuario_activo();
$sesion_iniciada = ControlSesion::sesion_iniciada();
$deshabilitar_botones = $sesion_iniciada && !$usuario_activo;
?>

<!-- HERO SECTION -->
<section class="hero-principal">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <div class="hero-tag">🐄 Colombia's #1 Livestock Marketplace</div>
        <h1 class="hero-principal h1">
          Compra y vende<br><span class="acento">ganado de calidad</span>
        </h1>
        <p class="hero-desc">
          En Ganadería Livestock conectamos compradores y vendedores de ganado y caballos en toda Colombia. Rápido, seguro y confiable.
        </p>

        <?php if ($deshabilitar_botones): ?>
          <div class="alert alert-warning mb-3">
            <i class="fas fa-exclamation-circle me-2"></i>
            Tu cuenta está <strong>inactiva</strong>. Contacta al soporte para activarla.
          </div>
        <?php endif; ?>

        <div class="hero-btns d-flex gap-3 flex-wrap">
          <a href="<?php echo RUTA_COMPRA; ?>" class="btn btn-dorado btn-lg px-4">
            <i class="fas fa-search me-2"></i>Ver publicaciones
          </a>
          <?php if ($sesion_iniciada && $usuario_activo): ?>
            <a href="<?php echo RUTA_VENTA; ?>" class="btn btn-outline-dorado btn-lg px-4" style="border:1.5px solid rgba(232,184,75,0.6);color:#fff;">
              <i class="fas fa-bullhorn me-2"></i>Publicar anuncio
            </a>
          <?php elseif (!$sesion_iniciada): ?>
            <a href="<?php echo RUTA_REGISTRO; ?>" class="btn btn-outline-dorado btn-lg px-4" style="border:1.5px solid rgba(232,184,75,0.6);color:#fff;">
              <i class="fas fa-user-plus me-2"></i>Crear cuenta gratis
            </a>
          <?php endif; ?>
        </div>

        <div class="hero-stats">
          <div class="hero-stat">
            <span class="hero-stat-num">12K+</span>
            <span class="hero-stat-label">Compradores activos</span>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-num">3.4K</span>
            <span class="hero-stat-label">Publicaciones al mes</span>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-num">32</span>
            <span class="hero-stat-label">Departamentos cubiertos</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- BARRA DE CONFIANZA -->
<div class="barra-confianza">
  <div class="barra-confianza-item">
    <div class="barra-confianza-icon"><i class="fas fa-check"></i></div>
    Usuarios verificados
  </div>
  <div class="barra-confianza-item">
    <div class="barra-confianza-icon"><i class="fas fa-lock"></i></div>
    Transacciones seguras
  </div>
  <div class="barra-confianza-item">
    <div class="barra-confianza-icon"><i class="fas fa-map-marker-alt"></i></div>
    Mapa de ubicaciones
  </div>
  <div class="barra-confianza-item">
    <div class="barra-confianza-icon"><i class="fas fa-bolt"></i></div>
    Publicación en minutos
  </div>
</div>

<!-- PUBLICACIONES SUGERIDAS -->
<div class="container seccion">
  <?php
  Conexion::abrir_conexion();
  RepositorioAnimal::eliminar_anuncios_vencidos(Conexion::obtener_conexion());
  RepositorioCaballo::eliminar_anuncios_vencidos(Conexion::obtener_conexion());
  EscritorAnimal::escribir_carrusel_sugeridos();
  ?>
</div>

<!-- BÚSQUEDA RÁPIDA -->
<div style="background: var(--crema-mid); padding: 48px 0;">
  <div class="container">
    <div class="text-center mb-4">
      <span class="seccion-label">Búsqueda rápida</span>
      <h2 class="seccion-titulo">Encuentra lo que necesitas</h2>
      <p class="seccion-sub">Filtra por tipo, ubicación y precio</p>
    </div>
    <div class="filtro-card">
      <div class="encabezado-degradado">
        <h5 class="titulo-encabezado mb-0"><i class="fas fa-filter me-2"></i>Filtro avanzado de búsqueda</h5>
      </div>
      <div class="p-4">
        <form id="filtro-avanzado-form" class="row g-3" method="get" action="#resultados-busqueda">
          <div class="col-md-4">
            <label class="form-label"><i class="fas fa-search me-1"></i>¿Qué deseas buscar?</label>
            <select class="form-select" id="tipoBusqueda" name="tipoBusqueda" required>
              <option value="">Selecciona una opción</option>
              <option value="ganado" <?= (isset($_GET['tipoBusqueda']) && $_GET['tipoBusqueda'] == 'ganado') ? 'selected' : '' ?>>Ganado</option>
              <option value="caballo" <?= (isset($_GET['tipoBusqueda']) && $_GET['tipoBusqueda'] == 'caballo') ? 'selected' : '' ?>>Caballos</option>
            </select>
          </div>

          <div id="filtros-ganado" style="display:none;" class="col-12">
            <div class="row g-3">
              <div class="col-md-3"><label class="form-label">Categoría</label>
                <select class="form-select" name="categoriaGanado">
                  <option value="">Todas</option>
                  <option value="Carne">Carne</option>
                  <option value="Leche">Leche</option>
                  <option value="Doble propósito">Doble propósito</option>
                </select>
              </div>
              <div class="col-md-3"><label class="form-label">Raza</label>
                <input type="text" class="form-control" name="razaGanado" value="<?= $_GET['razaGanado'] ?? '' ?>" placeholder="Ej: Brahman">
              </div>
              <div class="col-md-2"><label class="form-label">Sexo</label>
                <select class="form-select" name="sexoGanado">
                  <option value="">Todos</option>
                  <option value="Macho">Macho</option>
                  <option value="Hembra">Hembra</option>
                </select>
              </div>
              <div class="col-md-2"><label class="form-label">Departamento</label>
                <input type="text" class="form-control" name="departamentoGanado" value="<?= $_GET['departamentoGanado'] ?? '' ?>">
              </div>
              <div class="col-md-2"><label class="form-label">Municipio</label>
                <input type="text" class="form-control" name="municipioGanado" value="<?= $_GET['municipioGanado'] ?? '' ?>">
              </div>
              <div class="col-md-3"><label class="form-label">Precio mínimo</label>
                <input type="number" class="form-control" name="precioMin" min="0" value="<?= $_GET['precioMin'] ?? '' ?>">
              </div>
              <div class="col-md-3"><label class="form-label">Precio máximo</label>
                <input type="number" class="form-control" name="precioMax" min="0" value="<?= $_GET['precioMax'] ?? '' ?>">
              </div>
            </div>
          </div>

          <div id="filtros-caballo" style="display:none;" class="col-12">
            <div class="row g-3">
              <div class="col-md-3"><label class="form-label">Categoría</label>
                <select class="form-select" name="categoriaCaballo">
                  <option value="">Todas</option>
                  <option value="Caballo Criollo Colombiano">Criollo Colombiano</option>
                  <option value="Caballo Percherón">Percherón</option>
                  <option value="Caballo Árabe">Árabe</option>
                  <option value="Cuarto de Milla">Cuarto de Milla</option>
                  <option value="Caballos Mulares">Mulares</option>
                </select>
              </div>
              <div class="col-md-3"><label class="form-label">Aptitud/Raza</label>
                <input type="text" class="form-control" name="razaCaballo" value="<?= $_GET['razaCaballo'] ?? '' ?>" placeholder="Ej: Trocha, Paso fino">
              </div>
              <div class="col-md-2"><label class="form-label">Sexo</label>
                <select class="form-select" name="sexoCaballo">
                  <option value="">Todos</option>
                  <option value="Macho">Macho</option>
                  <option value="Hembra">Hembra</option>
                  <option value="Macho castrado">Macho castrado</option>
                </select>
              </div>
              <div class="col-md-2"><label class="form-label">Departamento</label>
                <input type="text" class="form-control" name="departamentoCaballo" value="<?= $_GET['departamentoCaballo'] ?? '' ?>">
              </div>
              <div class="col-md-2"><label class="form-label">Municipio</label>
                <input type="text" class="form-control" name="municipioCaballo" value="<?= $_GET['municipioCaballo'] ?? '' ?>">
              </div>
              <div class="col-md-3"><label class="form-label">Precio mínimo</label>
                <input type="number" class="form-control" name="precioMin" min="0" value="<?= $_GET['precioMin'] ?? '' ?>">
              </div>
              <div class="col-md-3"><label class="form-label">Precio máximo</label>
                <input type="number" class="form-control" name="precioMax" min="0" value="<?= $_GET['precioMax'] ?? '' ?>">
              </div>
            </div>
          </div>

          <div class="col-12 text-end">
            <button type="submit" class="btn btn-verde btn-lg px-4">
              <i class="fas fa-search me-2"></i>Buscar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- RESULTADOS -->
<?php include_once 'app/FiltroAvanzado.inc.php'; ?>
<?php if (!empty($tarjetas_resultado)): ?>
  <div class="container seccion" id="resultados-busqueda">
    <h2 class="seccion-titulo text-center"><i class="fas fa-search me-2"></i>Resultados de búsqueda</h2>
    <div class="row">
      <?php foreach ($tarjetas_resultado as $animal): ?>
        <?php EscritorAnimal::escribir_tarjeta_animal($animal); ?>
      <?php endforeach; ?>
    </div>
  </div>
<?php elseif (isset($_GET['tipoBusqueda'])): ?>
  <div class="container my-4" id="resultados-busqueda">
    <div class="alert alert-warning text-center"><i class="fas fa-exclamation-triangle me-2"></i>No se encontraron resultados.</div>
  </div>
<?php endif; ?>

<!-- COMPRAR / VENDER -->
<div class="container seccion">
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card-accion">
        <h3><i class="fas fa-shopping-cart me-2 text-dorado" style="color:var(--dorado);"></i>¿Quieres Comprar?</h3>
        <p>Explora cientos de publicaciones de ganado de calidad, compara precios y contacta directamente con los vendedores de todo el país.</p>
        <a href="<?php echo RUTA_COMPRA; ?>" class="btn btn-tierra btn-lg mt-2" style="background:var(--tierra);color:#fff;border:none;">Ver publicaciones</a>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card-accion verde">
        <h3><i class="fas fa-bullhorn me-2" style="color:var(--verde);"></i>¿Quieres Vender?</h3>
        <p>Publica tu ganado en minutos, llega a miles de compradores potenciales y gestiona tus anuncios de forma sencilla y segura.</p>
        <?php if ($sesion_iniciada && $usuario_activo): ?>
          <a href="<?php echo RUTA_VENTA; ?>" class="btn btn-verde btn-lg mt-2">Publicar ahora</a>
        <?php elseif ($sesion_iniciada): ?>
          <span class="btn btn-secondary btn-lg mt-2 disabled">Cuenta inactiva</span>
        <?php else: ?>
          <a href="<?php echo RUTA_LOGIN; ?>" class="btn btn-verde btn-lg mt-2">Inicia sesión para publicar</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- CARRUSELES -->
<div class="container">
  <?php EscritorAnimal::escribir_carrusel_premium(); ?>
</div>
<div class="container">
  <?php EscritorAnimal::escribir_carrusel_destacados(); ?>
</div>
<div class="container">
  <?php EscritorAnimal::escribir_carrusel_ultimas_publicaciones(); ?>
</div>

<!-- MAPA -->
<div class="container seccion">
  <div class="text-center mb-4">
    <span class="seccion-label">Geolocalización</span>
    <h2 class="seccion-titulo"><i class="fas fa-map-marked-alt me-2"></i>Animales publicados en Colombia</h2>
    <p class="seccion-sub">Encuentra ganado cerca de ti en los 32 departamentos del país</p>
  </div>
  <div id="mapa-ubicaciones" style="height:500px;" class="rounded shadow-sm"></div>
</div>

<?php
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$sentencia = $conexion->prepare("SELECT * FROM animales WHERE latitud IS NOT NULL AND longitud IS NOT NULL");
$sentencia->execute();
$ubicaciones = $sentencia->fetchAll(PDO::FETCH_ASSOC);
$sentencia_caballos = $conexion->prepare("SELECT * FROM caballos WHERE latitud IS NOT NULL AND longitud IS NOT NULL");
$sentencia_caballos->execute();
$ubicaciones_caballos = $sentencia_caballos->fetchAll(PDO::FETCH_ASSOC);
$ubicaciones_totales = array_merge($ubicaciones, $ubicaciones_caballos);
?>

<script>
  var mapa = L.map('mapa-ubicaciones').setView([4.5709, -74.2973], 5);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(mapa);
  var iconoGanandez = L.icon({
    iconUrl: '<?php echo SERVIDOR . "/img/logo-vaca-dorado-sm.png"; ?>',
    iconSize: [40, 40], iconAnchor: [20, 35], popupAnchor: [0, -60], className: 'icono-redondo'
  });
</script>

<?php foreach ($ubicaciones_totales as $ubicacion):
  $es_caballo = array_key_exists('precio', $ubicacion) && array_key_exists('caracteristicas', $ubicacion);
  $animal = $es_caballo
    ? RepositorioCaballo::obtener_caballo_por_id($conexion, $ubicacion['id'])
    : RepositorioAnimal::obtener_animal_por_id($conexion, $ubicacion['id']);
  if (method_exists($animal, 'esta_vendido') && $animal->esta_vendido()) continue;
  $url = $es_caballo ? SERVIDOR . '/caballo/' . $animal->obtener_id() : SERVIDOR . '/animal/' . $animal->obtener_id();
  $precio_formateado = '$ ' . number_format($animal->obtener_precio(), 0, ',', '.');
  $titulo = htmlspecialchars($animal->obtener_titulo());
  $categoria = htmlspecialchars($animal->obtener_categoria());
?>
  <script>
    L.marker([<?= $ubicacion['latitud'] ?>, <?= $ubicacion['longitud'] ?>], { icon: iconoGanandez })
      .addTo(mapa)
      .bindPopup('<a href="<?= $url ?>" style="text-decoration:none;color:inherit;"><div style="text-align:center;padding:8px;"><strong><?= $titulo ?></strong><br><span style="color:#C8961E;font-size:1.1rem;"><?= $precio_formateado ?></span><br><small><?= $categoria ?></small></div></a>');
  </script>
<?php endforeach; ?>

<script>
  function mostrarFiltros() {
    var tipo = document.getElementById('tipoBusqueda').value;
    document.getElementById('filtros-ganado').style.display = (tipo === 'ganado') ? 'block' : 'none';
    document.getElementById('filtros-caballo').style.display = (tipo === 'caballo') ? 'block' : 'none';
  }
  document.getElementById('tipoBusqueda').addEventListener('change', mostrarFiltros);
  window.addEventListener('DOMContentLoaded', mostrarFiltros);
</script>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>
