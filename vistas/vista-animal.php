<?php
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/Animal.inc.php';
include_once 'app/RepositorioAnimal.inc.php';
include_once 'app/RepositorioCaballo.inc.php';
include_once 'app/EscritorAnimal.inc.php';
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';

if (!isset($animal) || !$animal instanceof Animal) {
    echo "<div class='alert alert-danger text-center m-5'>No se encontro la publicacion.</div>";
    include_once 'plantillas/documento-cierre.inc.php';
    return;
}

function json_decode_recursivo($json, $depth = 3) {
    $decoded = $json;
    while (is_string($decoded) && $depth-- > 0) {
        $decoded = json_decode($decoded, true);
    }
    return $decoded;
}

$imagenes_raw = json_decode_recursivo($animal->obtener_imagenes()) ?? [];
$imagenes = [];
foreach ((array)$imagenes_raw as $url) {
    $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) $imagenes[] = $url;
}

$precio_formateado = '$ ' . number_format($animal->obtener_precio(), 0, ',', '.');
$latitud  = $animal->obtener_latitud();
$longitud = $animal->obtener_longitud();
$tiene_mapa = !empty($latitud) && !empty($longitud);

$carrito = $_SESSION['carrito'] ?? [];
$en_carrito = false;
foreach ($carrito as $item) {
    if (isset($item['id']) && $item['id'] == $animal->obtener_id() && isset($item['tipo']) && $item['tipo'] === 'animal') {
        $en_carrito = true; break;
    }
}
?>

<div style="margin-top:70px;"></div>

<div class="container" style="padding:40px 0 60px;">

  <!-- Breadcrumb -->
  <nav style="margin-bottom:24px;">
    <span style="font-size:0.85rem;color:var(--tierra-mid);">
      <a href="<?= SERVIDOR ?>" style="color:var(--tierra-mid);">Inicio</a>
      <span style="margin:0 8px;">›</span>
      <a href="<?= RUTA_COMPRA ?>" style="color:var(--tierra-mid);">Comprar</a>
      <span style="margin:0 8px;">›</span>
      <span style="color:var(--tierra);"><?= htmlspecialchars($animal->obtener_titulo()) ?></span>
    </span>
  </nav>

  <div class="row g-4">

    <!-- COLUMNA IZQUIERDA: Imagen + mapa -->
    <div class="col-lg-7">

      <!-- Imagen principal -->
      <div style="background:var(--crema-mid);border-radius:var(--radio-lg);overflow:hidden;aspect-ratio:4/3;display:flex;align-items:center;justify-content:center;border:1px solid rgba(61,43,31,0.1);">
        <?php if (!empty($imagenes)): ?>
          <img src="<?= htmlspecialchars($imagenes[0]) ?>" alt="<?= htmlspecialchars($animal->obtener_titulo()) ?>"
               style="width:100%;height:100%;object-fit:cover;">
        <?php else: ?>
          <div style="text-align:center;color:var(--tierra-mid);">
            <i class="fas fa-cow" style="font-size:5rem;margin-bottom:12px;display:block;color:var(--tierra-light);"></i>
            <span style="font-size:0.9rem;">Sin imagen disponible</span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Galería adicional -->
      <?php if (count($imagenes) > 1): ?>
        <div style="display:flex;gap:8px;margin-top:10px;overflow-x:auto;">
          <?php foreach (array_slice($imagenes, 1) as $img): ?>
            <img src="<?= htmlspecialchars($img) ?>" style="width:80px;height:60px;object-fit:cover;border-radius:6px;border:2px solid var(--crema-dark);cursor:pointer;flex-shrink:0;">
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Mapa -->
      <?php if ($tiene_mapa): ?>
        <div style="margin-top:24px;">
          <h5 style="font-family:var(--fuente-titulo);color:var(--tierra);margin-bottom:12px;">
            <i class="fas fa-map-marker-alt me-2" style="color:var(--dorado);"></i>Ubicación
          </h5>
          <div id="mapa-animal" style="height:260px;border-radius:var(--radio-lg);border:1px solid rgba(61,43,31,0.12);"></div>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              var mapa = L.map('mapa-animal').setView([<?= $latitud ?>, <?= $longitud ?>], 12);
              L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution:'&copy; OpenStreetMap'}).addTo(mapa);
              L.marker([<?= $latitud ?>, <?= $longitud ?>]).addTo(mapa)
                .bindPopup('<strong><?= htmlspecialchars($animal->obtener_titulo()) ?></strong><br><?= htmlspecialchars($animal->obtener_municipio() . ', ' . $animal->obtener_departamento()) ?>').openPopup();
            });
          </script>
        </div>
      <?php endif; ?>
    </div>

    <!-- COLUMNA DERECHA: Info + acciones -->
    <div class="col-lg-5">
      <div style="position:sticky;top:84px;">

        <!-- Badges -->
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;">
          <span style="background:var(--dorado-pale);color:var(--dorado);font-size:0.72rem;font-weight:500;padding:4px 12px;border-radius:20px;letter-spacing:0.05em;text-transform:uppercase;">
            <?= htmlspecialchars($animal->obtener_categoria()) ?>
          </span>
          <span style="background:rgba(45,80,22,0.1);color:var(--verde);font-size:0.72rem;font-weight:500;padding:4px 12px;border-radius:20px;letter-spacing:0.05em;text-transform:uppercase;">
            <?= htmlspecialchars($animal->obtener_raza()) ?>
          </span>
          <?php if ($animal->obtener_premium()): ?>
            <span style="background:var(--tierra);color:#fff;font-size:0.72rem;font-weight:500;padding:4px 12px;border-radius:20px;">
              ⭐ Premium
            </span>
          <?php endif; ?>
        </div>

        <!-- Título -->
        <h1 style="font-family:var(--fuente-titulo);font-size:clamp(1.4rem,2.5vw,2rem);font-weight:700;color:var(--tierra);line-height:1.2;margin-bottom:16px;">
          <?= htmlspecialchars($animal->obtener_titulo()) ?>
        </h1>

        <!-- Precio -->
        <div style="background:var(--crema-mid);border-radius:var(--radio-lg);padding:16px 20px;margin-bottom:20px;border-left:4px solid var(--dorado);">
          <div style="font-size:0.75rem;color:var(--tierra-mid);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Precio</div>
          <div style="font-family:var(--fuente-titulo);font-size:2rem;font-weight:700;color:var(--verde);"><?= $precio_formateado ?></div>
          <?php if ($animal->obtener_tipo_precio()): ?>
            <div style="font-size:0.8rem;color:var(--tierra-mid);">por <?= htmlspecialchars($animal->obtener_tipo_precio()) ?></div>
          <?php endif; ?>
        </div>

        <!-- Detalles -->
        <div style="background:#fff;border:1px solid rgba(61,43,31,0.1);border-radius:var(--radio-lg);padding:16px 20px;margin-bottom:20px;">
          <h6 style="font-family:var(--fuente-titulo);color:var(--tierra);margin-bottom:12px;font-size:0.95rem;">Características</h6>
          <table style="width:100%;font-size:0.88rem;">
            <?php
            $detalles = [
              ['fas fa-paw',           'Raza',         $animal->obtener_raza()],
              ['fas fa-venus-mars',    'Sexo',         $animal->obtener_sexo()],
              ['fas fa-birthday-cake', 'Edad',         $animal->obtener_edad()],
              ['fas fa-weight',        'Peso',         $animal->obtener_peso() ? $animal->obtener_peso() . ' kg' : null],
              ['fas fa-dna',           'Pureza',       method_exists($animal,'obtener_pureza') ? $animal->obtener_pureza() : null],
              ['fas fa-map-marker-alt','Departamento', $animal->obtener_departamento()],
              ['fas fa-city',          'Municipio',    $animal->obtener_municipio()],
            ];
            foreach ($detalles as $d):
              if (!$d[2]) continue;
            ?>
              <tr style="border-bottom:1px solid rgba(61,43,31,0.06);">
                <td style="padding:7px 0;color:var(--tierra-mid);width:40%;">
                  <i class="<?= $d[0] ?> me-2" style="color:var(--dorado);width:16px;text-align:center;"></i><?= $d[1] ?>
                </td>
                <td style="padding:7px 0;color:var(--tierra);font-weight:500;"><?= htmlspecialchars($d[2]) ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>

        <!-- Descripción -->
        <?php if ($animal->obtener_descripcion()): ?>
          <div style="background:#fff;border:1px solid rgba(61,43,31,0.1);border-radius:var(--radio-lg);padding:16px 20px;margin-bottom:20px;">
            <h6 style="font-family:var(--fuente-titulo);color:var(--tierra);margin-bottom:8px;font-size:0.95rem;">Descripción</h6>
            <p style="font-size:0.9rem;color:var(--tierra-mid);line-height:1.7;margin:0;"><?= nl2br(htmlspecialchars($animal->obtener_descripcion())) ?></p>
          </div>
        <?php endif; ?>

        <!-- Contacto -->
        <div style="background:var(--crema-mid);border-radius:var(--radio-lg);padding:16px 20px;margin-bottom:20px;">
          <h6 style="font-family:var(--fuente-titulo);color:var(--tierra);margin-bottom:10px;font-size:0.95rem;">Contactar vendedor</h6>
          <?php if ($animal->obtener_telefono()): ?>
            <a href="https://wa.me/57<?= preg_replace('/[^0-9]/','',$animal->obtener_telefono()) ?>?text=Hola, me interesa: <?= urlencode($animal->obtener_titulo()) ?>"
               target="_blank"
               style="display:flex;align-items:center;gap:10px;background:#25D366;color:#fff;padding:10px 16px;border-radius:6px;text-decoration:none;font-weight:500;font-size:0.9rem;margin-bottom:8px;">
              <i class="fab fa-whatsapp" style="font-size:1.2rem;"></i>
              Contactar por WhatsApp
            </a>
          <?php endif; ?>
          <?php if ($animal->obtener_correo()): ?>
            <a href="mailto:<?= htmlspecialchars($animal->obtener_correo()) ?>?subject=Consulta sobre: <?= urlencode($animal->obtener_titulo()) ?>"
               style="display:flex;align-items:center;gap:10px;background:var(--tierra);color:#fff;padding:10px 16px;border-radius:6px;text-decoration:none;font-weight:500;font-size:0.9rem;">
              <i class="fas fa-envelope" style="font-size:1rem;"></i>
              Enviar correo
            </a>
          <?php endif; ?>
        </div>

        <!-- Botón carrito -->
        <?php if (!$animal->esta_vendido()): ?>
          <form method="post" action="<?= SERVIDOR ?>/vistas/carrito.php">
            <input type="hidden" name="id" value="<?= $animal->obtener_id() ?>">
            <input type="hidden" name="tipo" value="animal">
            <input type="hidden" name="accion" value="<?= $en_carrito ? 'eliminar' : 'agregar' ?>">
            <button type="submit" class="btn btn-lg w-100" style="<?= $en_carrito ? 'background:var(--tierra-mid);' : 'background:var(--dorado);' ?> color:<?= $en_carrito ? '#fff' : 'var(--tierra)' ?>;border:none;font-weight:600;padding:14px;">
              <i class="fas <?= $en_carrito ? 'fa-cart-arrow-down' : 'fa-cart-plus' ?> me-2"></i>
              <?= $en_carrito ? 'Quitar del carrito' : 'Agregar al carrito' ?>
            </button>
          </form>
        <?php else: ?>
          <div style="background:var(--crema-dark);text-align:center;padding:14px;border-radius:var(--radio-lg);color:var(--tierra-mid);font-weight:500;">
            <i class="fas fa-check-circle me-2" style="color:var(--verde);"></i>Este animal ya fue vendido
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>
