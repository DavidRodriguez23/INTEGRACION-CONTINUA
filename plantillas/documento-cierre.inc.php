<?php
include_once __DIR__ . '/../app/Conexion.inc.php';
include_once __DIR__ . '/../app/config.inc.php';
?>

<footer class="footer-principal">
  <div class="container">
    <div class="row g-4">

      <div class="col-lg-4 col-md-6">
        <div class="mb-3">
          <img src="<?php echo SERVIDOR; ?>/img/logo-vaca-sm.png" alt="Ganadería Livestock" height="52">
        </div>
        <p class="footer-desc">La plataforma líder de compraventa de ganado y caballos en Colombia. Conectamos ganaderos de todo el país de forma rápida, segura y confiable.</p>
        <div class="footer-social mt-3">
          <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>

      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-heading">Plataforma</div>
        <a class="footer-link" href="<?php echo SERVIDOR; ?>">Inicio</a>
        <a class="footer-link" href="<?php echo RUTA_COMPRA; ?>">Comprar</a>
        <a class="footer-link" href="<?php echo RUTA_VENTA; ?>">Vender ganado</a>
        <a class="footer-link" href="<?php echo RUTA_VENTA_CABALLO; ?>">Vender caballos</a>
      </div>

      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-heading">Nosotros</div>
        <a class="footer-link" href="<?php echo RUTA_NOSOTROS; ?>">¿Quiénes somos?</a>
        <a class="footer-link" href="<?php echo RUTA_CONTACTENOS; ?>">Contáctenos</a>
        <a class="footer-link" href="<?php echo RUTA_REGISTRO; ?>">Crear cuenta</a>
        <a class="footer-link" href="<?php echo RUTA_LOGIN; ?>">Iniciar sesión</a>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="footer-heading">Contacto</div>
        <p style="color: rgba(255,255,255,0.6); font-size: 0.88rem; line-height: 2.2; margin-bottom: 12px;">
          <i class="fas fa-envelope me-2" style="color: var(--dorado);"></i> contacto@ganaderialivestock.co<br>
          <i class="fas fa-phone me-2" style="color: var(--dorado);"></i> +57 300 000 0000<br>
          <i class="fas fa-map-marker-alt me-2" style="color: var(--dorado);"></i> Colombia
        </p>
        <div style="background: rgba(200,150,30,0.1); border: 1px solid rgba(200,150,30,0.25); border-radius: 6px; padding: 10px 14px;">
          <div style="font-size: 0.7rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--dorado); margin-bottom: 4px;">Cobertura del mapa</div>
          <span style="font-size: 0.82rem; color: rgba(255,255,255,0.6);">
            <i class="fas fa-map-marked-alt me-1"></i> 32 departamentos · Todo Colombia
          </span>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <div class="row align-items-center g-2">
        <div class="col-md-6">
          <span>&copy; <?= date('Y') ?> Ganadería Livestock &mdash; Todos los derechos reservados.</span>
        </div>
        <div class="col-md-6 text-md-end">
          <span style="color: rgba(255,255,255,0.3); font-size: 0.78rem;">
            <i class="fas fa-university me-1" style="color: var(--dorado); opacity: 0.6;"></i>
            Proyecto académico &mdash; Énfasis Profesional | Integración Continua &mdash; Grupo 13 &mdash; Politécnico Grancolombiano
          </span>
        </div>
      </div>
    </div>
  </div>
</footer>

<?php Conexion::cerrar_conexion(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>
