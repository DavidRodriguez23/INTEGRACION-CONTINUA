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

<section class="hero-principal">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-12 col-lg-7">

        <div class="hero-tag">🐄 Portal número 1 en Colombia para compra/venta de ganado</div>

        <h1 style="font-family:'Playfair Display',Georgia,serif;font-size:clamp(1.8rem,3.2vw,2.8rem);font-weight:700;color:#fff;line-height:1.2;margin-bottom:16px;">
          Compra y vende <span style="color:#E8B84B;">ganado de calidad</span>
        </h1>

        <p style="color:rgba(255,255,255,0.65);font-size:1rem;max-width:480px;margin:0 auto 28px;line-height:1.7;">
          En Ganadería Livestock conectamos compradores y vendedores de ganado y caballos en toda Colombia. Rápido, seguro y confiable.
        </p>

        <?php if ($deshabilitar_botones): ?>
          <div class="alert alert-warning mb-3">
            <i class="fas fa-exclamation-circle me-2"></i>
            Tu cuenta está <strong>inactiva</strong>. Contacta al soporte para activarla.
          </div>
        <?php endif; ?>

        <div class="d-flex gap-3 flex-wrap justify-content-center mb-5">
          <a href="<?php echo RUTA_COMPRA; ?>" class="btn btn-dorado btn-lg px-4">
            <i class="fas fa-search me-2"></i>Ver publicaciones
          </a>
          <?php if ($sesion_iniciada && $usuario_activo): ?>
            <a href="<?php echo RUTA_VENTA; ?>" class="btn btn-lg px-4" style="border:1.5px solid rgba(232,184,75,0.5);color:#fff;background:transparent;">
              <i class="fas fa-bullhorn me-2"></i>Publicar anuncio
            </a>
          <?php elseif (!$sesion_iniciada): ?>
            <a href="<?php echo RUTA_REGISTRO; ?>" class="btn btn-lg px-4" style="border:1.5px solid rgba(232,184,75,0.5);color:#fff;background:transparent;">
              <i class="fas fa-user-plus me-2"></i>Crear cuenta gratis
            </a>
          <?php endif; ?>
        </div>

        <div class="d-flex justify-content-center gap-0" style="border-top:1px solid rgba(255,255,255,0.12);padding-top:24px;">
          <div style="padding:0 28px;border-right:1px solid rgba(255,255,255,0.12);">
            <span style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:#E8B84B;display:block;line-height:1;margin-bottom:4px;">12K+</span>
            <span style="font-size:0.72rem;color:rgba(255,255,255,0.45);">Compradores activos</span>
          </div>
          <div style="padding:0 28px;border-right:1px solid rgba(255,255,255,0.12);">
            <span style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:#E8B84B;display:block;line-height:1;margin-bottom:4px;">3.4K</span>
            <span style="font-size:0.72rem;color:rgba(255,255,255,0.45);">Publicaciones al mes</span>
          </div>
          <div style="padding:0 0 0 28px;">
            <span style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:#E8B84B;display:block;line-height:1;margin-bottom:4px;">32</span>
            <span style="font-size:0.72rem;color:rgba(255,255,255,0.45);">Departamentos cubiertos</span>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<div class="barra-confianza">
  <div class="barra-confianza-item"><div class="barra-confianza-icon"><i class="fas fa-check"></i></div>Usuarios verificados</div>
  <div class="barra-confianza-item"><div class="barra-confianza-icon"><i class="fas fa-lock"></i></div>Transacciones seguras</div>
  <div class="barra-confianza-item"><div class="barra-confianza-icon"><i class="fas fa-map-marker-alt"></i></div>Mapa de ubicaciones</div>
  <div class="barra-confianza-item"><div class="barra-confianza-icon"><i class="fas fa-bolt"></i></div>Publicación en minutos</div>
</div>

<div class="container seccion">
  <?php
  Conexion::abrir_conexion();
  RepositorioAnimal::eliminar_anuncios_vencidos(Conexion::obtener_conexion());
  RepositorioCaballo::eliminar_anuncios_vencidos(Conexion::obtener_conexion());
  EscritorAnimal::escribir_carrusel_sugeridos();
  ?>
</div>

<div style="background:#F0E4C4;padding:48px 0;">
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
              <option value="ganado" <?= (isset($_GET['tipoBusqueda']) && $_GET['tipoBusqueda']=='ganado')?'selected':'' ?>>Ganado</option>
              <option value="caballo" <?= (isset($_GET['tipoBusqueda']) && $_GET['tipoBusqueda']=='caballo')?'selected':'' ?>>Caballos</option>
            </select>
          </div>

          <div id="filtros-ganado" style="display:none;" class="col-12">
            <div class="row g-3">
              <div class="col-md-3">
                <label class="form-label">Categoría</label>
                <select class="form-select" name="categoriaGanado">
                  <option value="">Todas</option>
                  <option value="Carne" <?= (isset($_GET['categoriaGanado']) && $_GET['categoriaGanado']=='Carne')?'selected':'' ?>>Carne</option>
                  <option value="Leche" <?= (isset($_GET['categoriaGanado']) && $_GET['categoriaGanado']=='Leche')?'selected':'' ?>>Leche</option>
                  <option value="Doble propósito" <?= (isset($_GET['categoriaGanado']) && $_GET['categoriaGanado']=='Doble propósito')?'selected':'' ?>>Doble propósito</option>
                </select>
              </div>
              <div class="col-md-3"><label class="form-label">Raza</label><input type="text" class="form-control" name="razaGanado" value="<?= $_GET['razaGanado'] ?? '' ?>" placeholder="Ej: Brahman"></div>
              <div class="col-md-2">
                <label class="form-label">Sexo</label>
                <select class="form-select" name="sexoGanado">
                  <option value="">Todos</option>
                  <option value="Macho" <?= (isset($_GET['sexoGanado']) && $_GET['sexoGanado']=='Macho')?'selected':'' ?>>Macho</option>
                  <option value="Hembra" <?= (isset($_GET['sexoGanado']) && $_GET['sexoGanado']=='Hembra')?'selected':'' ?>>Hembra</option>
                </select>
              </div>
              <div class="col-md-2"><label class="form-label">Departamento</label><input type="text" class="form-control" name="departamentoGanado" value="<?= $_GET['departamentoGanado'] ?? '' ?>"></div>
              <div class="col-md-2"><label class="form-label">Municipio</label><input type="text" class="form-control" name="municipioGanado" value="<?= $_GET['municipioGanado'] ?? '' ?>"></div>
              <div class="col-md-3"><label class="form-label">Precio mínimo</label><input type="number" class="form-control" name="precioMin" min="0" value="<?= $_GET['precioMin'] ?? '' ?>"></div>
              <div class="col-md-3"><label class="form-label">Precio máximo</label><input type="number" class="form-control" name="precioMax" min="0" value="<?= $_GET['precioMax'] ?? '' ?>"></div>
            </div>
          </div>

          <div id="filtros-caballo" style="display:none;" class="col-12">
            <div class="row g-3">
              <div class="col-md-3">
                <label class="form-label">Categoría</label>
                <select class="form-select" name="categoriaCaballo">
                  <option value="">Todas</option>
                  <option value="Caballo Criollo Colombiano" <?= (isset($_GET['categoriaCaballo']) && $_GET['categoriaCaballo']=='Caballo Criollo Colombiano')?'selected':'' ?>>Criollo Colombiano</option>
                  <option value="Caballo Percherón" <?= (isset($_GET['categoriaCaballo']) && $_GET['categoriaCaballo']=='Caballo Percherón')?'selected':'' ?>>Percherón</option>
                  <option value="Caballo Árabe" <?= (isset($_GET['categoriaCaballo']) && $_GET['categoriaCaballo']=='Caballo Árabe')?'selected':'' ?>>Árabe</option>
                  <option value="Cuarto de Milla" <?= (isset($_GET['categoriaCaballo']) && $_GET['categoriaCaballo']=='Cuarto de Milla')?'selected':'' ?>>Cuarto de Milla</option>
                  <option value="Caballos Mulares" <?= (isset($_GET['categoriaCaballo']) && $_GET['categoriaCaballo']=='Caballos Mulares')?'selected':'' ?>>Mulares</option>
                </select>
              </div>
              <div class="col-md-3"><label class="form-label">Aptitud/Raza</label><input type="text" class="form-control" name="razaCaballo" value="<?= $_GET['razaCaballo'] ?? '' ?>" placeholder="Ej: Trocha, Paso fino"></div>
              <div class="col-md-2">
                <label class="form-label">Sexo</label>
                <select class="form-select" name="sexoCaballo">
                  <option value="">Todos</option>
                  <option value="Macho" <?= ($_GET['sexoCaballo'] ?? '')==='Macho'?'selected':'' ?>>Macho</option>
                  <option value="Hembra" <?= ($_GET['sexoCaballo'] ?? '')==='Hembra'?'selected':'' ?>>Hembra</option>
                  <option value="Macho castrado" <?= ($_GET['sexoCaballo'] ?? '')==='Macho castrado'?'selected':'' ?>>Macho castrado</option>
                </select>
              </div>
              <div class="col-md-2"><label class="form-label">Departamento</label><input type="text" class="form-control" name="departamentoCaballo" value="<?= $_GET['departamentoCaballo'] ?? '' ?>"></div>
              <div class="col-md-2"><label class="form-label">Municipio</label><input type="text" class="form-control" name="municipioCaballo" value="<?= $_GET['municipioCaballo'] ?? '' ?>"></div>
              <div class="col-md-3"><label class="form-label">Precio mínimo</label><input type="number" class="form-control" name="precioMin" min="0" value="<?= $_GET['precioMin'] ?? '' ?>"></div>
              <div class="col-md-3"><label class="form-label">Precio máximo</label><input type="number" class="form-control" name="precioMax" min="0" value="<?= $_GET['precioMax'] ?? '' ?>"></div>
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

<?php include_once 'app/FiltroAvanzado.inc.php'; ?>

<?php if (!empty($tarjetas_resultado)): ?>
  <div class="container seccion" id="resultados-busqueda">
    <h2 class="seccion-titulo text-center">Resultados de búsqueda</h2>
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

<div class="container seccion">
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card-accion">
        <h3><i class="fas fa-shopping-cart me-2" style="color:var(--dorado);"></i>¿Quieres Comprar?</h3>
        <p>Explora cientos de publicaciones de ganado de calidad, compara precios y contacta directamente con vendedores de todo el país.</p>
        <a href="<?php echo RUTA_COMPRA; ?>" class="btn btn-lg mt-2" style="background:var(--tierra);color:#fff;border:none;">Ver publicaciones</a>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card-accion verde">
        <h3><i class="fas fa-bullhorn me-2" style="color:var(--verde);"></i>¿Quieres Vender?</h3>
        <p>Publica tu ganado en minutos, llega a miles de compradores potenciales y gestiona tus anuncios de forma sencilla.</p>
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

<div class="container"><?php EscritorAnimal::escribir_carrusel_premium(); ?></div>
<div class="container"><?php EscritorAnimal::escribir_carrusel_destacados(); ?></div>
<div class="container"><?php EscritorAnimal::escribir_carrusel_ultimas_publicaciones(); ?></div>

<div class="container seccion">
  <div class="text-center mb-4">
    <span class="seccion-label">Geolocalización</span>
    <h2 class="seccion-titulo"><i class="fas fa-map-marked-alt me-2"></i>Animales publicados en Colombia</h2>
    <p class="seccion-sub">Encuentra ganado cerca de ti en los 32 departamentos del país</p>
  </div>
  <div id="mapa-ubicaciones" style="height:500px;"></div>
</div>

<?php
$conexion = Conexion::obtener_conexion();
$s1 = $conexion->prepare("SELECT * FROM animales WHERE latitud IS NOT NULL AND longitud IS NOT NULL");
$s1->execute();
$ubicaciones = $s1->fetchAll(PDO::FETCH_ASSOC);
$s2 = $conexion->prepare("SELECT * FROM caballos WHERE latitud IS NOT NULL AND longitud IS NOT NULL");
$s2->execute();
$ubicaciones_caballos = $s2->fetchAll(PDO::FETCH_ASSOC);
$ubicaciones_totales = array_merge($ubicaciones, $ubicaciones_caballos);
?>

<script>
  var mapa = L.map('mapa-ubicaciones').setView([4.5709,-74.2973],5);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap'}).addTo(mapa);
  var iconoGanandez = L.icon({
    iconUrl:'<?php echo SERVIDOR."/img/logo-vaca-dorado-sm.png"; ?>',
    iconSize:[40,40],iconAnchor:[20,35],popupAnchor:[0,-60],className:'icono-redondo'
  });
</script>

<?php foreach ($ubicaciones_totales as $ubicacion):
  $es_caballo = array_key_exists('caracteristicas',$ubicacion);
  $animal = $es_caballo
    ? RepositorioCaballo::obtener_caballo_por_id($conexion,$ubicacion['id'])
    : RepositorioAnimal::obtener_animal_por_id($conexion,$ubicacion['id']);
  if (method_exists($animal,'esta_vendido') && $animal->esta_vendido()) continue;
  $url = $es_caballo ? SERVIDOR.'/caballo/'.$animal->obtener_id() : SERVIDOR.'/animal/'.$animal->obtener_id();
  $precio = '$ '.number_format($animal->obtener_precio(),0,',','.');
  $titulo = htmlspecialchars($animal->obtener_titulo());
?>
<script>
  L.marker([<?= $ubicacion['latitud'] ?>,<?= $ubicacion['longitud'] ?>],{icon:iconoGanandez})
    .addTo(mapa)
    .bindPopup('<a href="<?= $url ?>" style="text-decoration:none;color:inherit;"><div style="text-align:center;padding:8px;"><strong><?= $titulo ?></strong><br><span style="color:#C8961E;font-size:1.1rem;"><?= $precio ?></span></div></a>');
</script>
<?php endforeach; ?>

<script>
  function mostrarFiltros(){
    var t = document.getElementById('tipoBusqueda').value;
    document.getElementById('filtros-ganado').style.display = (t==='ganado') ? 'block' : 'none';
    document.getElementById('filtros-caballo').style.display = (t==='caballo') ? 'block' : 'none';
  }
  document.getElementById('tipoBusqueda').addEventListener('change', mostrarFiltros);
  window.addEventListener('DOMContentLoaded', mostrarFiltros);
</script>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>
