<?php
include_once 'app/ControlSesion.inc.php';
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/RepositorioAjustes.inc.php';

Conexion::abrir_conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $claves = ['email_contacto', 'telefono_contacto', 'direccion_contacto', 'facebook', 'twitter', 'instagram', 'estado_sitio'];

    foreach ($claves as $clave) {
        $valor = $_POST[$clave] ?? '';
        RepositorioAjustes::guardar_valor($clave, $valor, Conexion::obtener_conexion());
    }

    $mensaje = 'Ajustes guardados correctamente.';
}

// Obtener valores actuales
$email_contacto     = RepositorioAjustes::obtener_valor('email_contacto', Conexion::obtener_conexion());
$telefono_contacto  = RepositorioAjustes::obtener_valor('telefono_contacto', Conexion::obtener_conexion());
$direccion_contacto = RepositorioAjustes::obtener_valor('direccion_contacto', Conexion::obtener_conexion());
$facebook           = RepositorioAjustes::obtener_valor('facebook', Conexion::obtener_conexion());
$instagram          = RepositorioAjustes::obtener_valor('instagram', Conexion::obtener_conexion());
$twitter            = RepositorioAjustes::obtener_valor('twitter', Conexion::obtener_conexion());
$estado_sitio       = RepositorioAjustes::obtener_valor('estado_sitio', Conexion::obtener_conexion());

include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>


<br><br>
<section class="bienvenida-section py-5">
    <div class="container text-center">
        <h1 class="main-title animate-fade-in mb-3">Ajustes del Sistema</h1>
        <p class="main-subtitle">Configura parámetros generales, visuales y de contacto del sistema.</p>
    </div>
</section>

<div class="container py-5">
    <div class="admin-panel">
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <form method="POST" class="row g-4">

            <h4 class="mb-3 text-success"><i class="fas fa-sliders-h me-2"></i>Configuración General</h4>

            <div class="col-12">
                <p class="text-muted fst-italic">
                    Para modificar los ajustes de contacto y redes sociales, simplemente actualiza los campos correspondientes con la información deseada y haz clic en "Guardar Cambios". Asegúrate de ingresar URLs completas para los enlaces de redes sociales, incluyendo "https://". Si tienes dudas o necesitas ayuda, contacta al administrador del sistema.
                </p>
            </div>



            <h4 class="mt-4 mb-3 text-success"><i class="fas fa-envelope me-2"></i>Contacto y Redes</h4>

            <div class="col-md-6">
                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="email_contacto" value="<?= htmlspecialchars($email_contacto) ?>" placeholder="admin@ganandez.com">
            </div>

            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono_contacto" value="<?= htmlspecialchars($telefono_contacto) ?>" placeholder="+573142873700">
            </div>

            <div class="col-md-12">
                <label class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion_contacto" value="<?= htmlspecialchars($direccion_contacto) ?>" placeholder="Calle 123, Ciudad, País">
            </div>

            <div class="col-md-6">
                <label class="form-label">Facebook</label>
                <input type="url" class="form-control" name="facebook" id="facebook" value="<?= htmlspecialchars($facebook) ?>" placeholder="https://facebook.com/ganandez">
                <div id="facebook-preview" class="mt-2">
                    <?php if (!empty($facebook)): ?>
                        <a href="<?= htmlspecialchars($facebook) ?>" target="_blank" rel="noopener" class="text-primary fs-5">
                            <i class="fab fa-facebook me-2"></i>Ver enlace actual
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Instagram</label>
                <input type="url" class="form-control" name="instagram" id="instagram" value="<?= htmlspecialchars($instagram) ?>" placeholder="https://instagram.com/ganandez">
                <div id="instagram-preview" class="mt-2">
                    <?php if (!empty($instagram)): ?>
                        <a href="<?= htmlspecialchars($instagram) ?>" target="_blank" rel="noopener" class="text-danger fs-5">
                            <i class="fab fa-instagram me-2"></i>Ver enlace actual
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Twitter</label>
                <input type="url" class="form-control" name="twitter" id="twitter" value="<?= htmlspecialchars($twitter) ?>" placeholder="https://twitter.com/ganandez">
                <div id="twitter-preview" class="mt-2">
                    <?php if (!empty($twitter)): ?>
                        <a href="<?= htmlspecialchars($twitter) ?>" target="_blank" rel="noopener" class="text-info fs-5">
                            <i class="fab fa-twitter me-2"></i>Ver enlace actual
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 text-end mt-4">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save me-2"></i>Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const socialLinks = [{
                id: 'facebook',
                icon: 'facebook',
                class: 'text-primary'
            },
            {
                id: 'instagram',
                icon: 'instagram',
                class: 'text-danger'
            },
            {
                id: 'twitter',
                icon: 'twitter',
                class: 'text-info'
            }
        ];

        socialLinks.forEach(({
            id,
            icon,
            class: colorClass
        }) => {
            const input = document.getElementById(id);
            const preview = document.getElementById(id + '-preview');

            input.addEventListener('input', function() {
                const url = input.value.trim();
                if (url) {
                    preview.innerHTML = `
                    <a href="${url}" target="_blank" rel="noopener" class="${colorClass} fs-5">
                        <i class="fab fa-${icon} me-2"></i>Ver enlace actual
                    </a>`;
                } else {
                    preview.innerHTML = '';
                }
            });
        });
    });
</script>


<?php include_once 'plantillas/documento-cierre.inc.php'; ?>