<?php
include_once 'app/config.inc.php';
include_once 'app/ControlSesion.inc.php';

$titulo = 'Vista previa de tu publicación';

if (!ControlSesion::sesion_iniciada()) {
    header("Location: " . RUTA_LOGIN);
    exit();
}

// Recuperar datos del formulario anterior con POST
$datos = $_POST;


// Incluir el encabezado y navbar
include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>
<br><br>
<div class="container my-5 animate-fade-in">
    <div class="card tarjeta-registro">
        <div class="encabezado-degradado text-white">
            <h3 class="titulo-encabezado mb-0"><i class="fas fa-eye"></i> Vista Previa del Registro</h3>
            <p class="subtitulo-registro">Revisa cuidadosamente antes de publicar</p>
        </div>
        <div class="fondo-registro">
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-heading text-success"></i> Título</h5>
                    <p class="lead"><?= htmlspecialchars($datos['titulo'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-horse text-primary"></i> Categoría</h5>
                    <p class="lead"><?= htmlspecialchars($datos['categoria'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-dna text-success"></i> Raza / Aptitud</h5>
                    <p class="lead"><?= htmlspecialchars($datos['raza'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-venus-mars text-success"></i> Sexo</h5>
                    <p class="lead"><?= htmlspecialchars($datos['sexo'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-hourglass-half text-success"></i> Edad</h5>
                    <p class="lead"><?= htmlspecialchars($datos['edad'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-weight text-success"></i> Peso</h5>
                    <p class="lead"><?= htmlspecialchars($datos['peso'] ?? '') ?> kg</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-dollar-sign text-success"></i> Precio</h5>
                    <p class="lead"><?= htmlspecialchars($datos['precio'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-phone text-success"></i> Teléfono</h5>
                    <p class="lead"><?= htmlspecialchars($datos['telefono'] ?? '') ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-envelope text-success"></i> Correo</h5>
                    <p class="lead"><?= htmlspecialchars($datos['correo'] ?? '') ?></p>
                </div>
                <div class="col-md-12 mb-3">
                    <h5><i class="fas fa-map-marker-alt text-success"></i> Ubicación</h5>
                    <p class="lead"><?= htmlspecialchars($datos['departamento'] ?? '') ?>, <?= htmlspecialchars($datos['municipio'] ?? '') ?> - <?= htmlspecialchars($datos['direccion'] ?? '') ?></p>
                </div>
                <div class="col-md-12 mb-3">
                    <h5><i class="fas fa-align-left text-success"></i> Descripción</h5>
                    <p><?= nl2br(htmlspecialchars($datos['descripcion'] ?? '')) ?></p>
                </div>
                <div class="col-md-12 mb-3">
                    <h5><i class="fas fa-star text-success"></i> Características</h5>
                    <p class="lead">
                        <?php
                        if (!empty($datos['caracteristicas'])) {
                            if (is_array($datos['caracteristicas'])) {
                                echo implode(', ', array_map('htmlspecialchars', $datos['caracteristicas']));
                            } else {
                                echo htmlspecialchars($datos['caracteristicas']);
                            }
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </p>
                </div>
            </div>

            <div class="text-center">
                <form action="<?php echo RUTA_VENTA_CABALLO; ?>" method="POST" enctype="multipart/form-data">
                    <?php foreach ($datos as $clave => $valor): ?>
                        <?php if (is_array($valor)): ?>
                            <?php foreach ($valor as $v): ?>
                                <input type="hidden" name="<?= htmlspecialchars($clave) ?>[]" value="<?= htmlspecialchars($v) ?>">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <input type="hidden" name="<?= htmlspecialchars($clave) ?>" value="<?= htmlspecialchars($valor) ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <a href="javascript:history.back()" class="btn btn-outline-success mx-2">
                        <i class="fas fa-arrow-left"></i> Editar
                    </a>
                    <button type="submit" name="publicar" class="btn btn-primary mx-2">
                        <i class="fas fa-check-circle"></i> Confirmar y Publicar Caballo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'plantillas/documento-cierre.inc.php'; ?>