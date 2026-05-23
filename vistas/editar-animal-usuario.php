<?php

include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/RepositorioAnimal.inc.php';
include_once 'app/RepositorioCaballo.inc.php';
include_once 'app/Redireccion.inc.php';

if (!ControlSesion::sesion_iniciada()) {
    Redireccion::redirigir(RUTA_LOGIN);
    exit();
}

$tipo = $_GET['tipo'] ?? '';
$id = $_GET['id'] ?? '';
$publicacion = null;

Conexion::abrir_conexion();
if ($tipo === 'animal') {
    $publicacion = RepositorioAnimal::obtener_animal_por_id(Conexion::obtener_conexion(), $id);
} elseif ($tipo === 'caballo') {
    $publicacion = RepositorioCaballo::obtener_caballo_por_id(Conexion::obtener_conexion(), $id);
}

if (!$publicacion) {
    echo "<div class='alert alert-danger text-center'>No se encontró la publicación.</div>";
    exit();
}

// Verificar si el usuario actual es el autor de la publicación
if ($publicacion->obtener_id_usuario() !== $_SESSION['id_usuario']) {
    echo "<div class='alert alert-danger text-center mt-4'>No tienes permiso para editar esta publicación.</div>";
    include_once 'plantillas/documento-cierre.inc.php';
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $edad = $_POST['edad'] ?? '';

    if ($tipo === 'animal') {
        RepositorioAnimal::actualizar_contacto_y_edad(Conexion::obtener_conexion(), $id, $telefono, $correo, $edad);
    } elseif ($tipo === 'caballo') {
        RepositorioCaballo::actualizar_contacto_y_edad(Conexion::obtener_conexion(), $id, $telefono, $correo, $edad);
    }

    Redireccion::redirigir(RUTA_PERFIL . '?editado=1');
    exit();
}

include_once 'plantillas/documento-apertura.inc.php';
include_once 'plantillas/navbar.inc.php';
?>
<br>
<br>
<div class="container mt-5 mb-5">
    <div class="card tarjeta-registro mx-auto" style="max-width: 600px;">
        <div class="encabezado-degradado">
            <h3 class="titulo-encabezado mb-1">Editar publicación</h3>
            <p class="subtitulo-registro mb-0">
                Modifica los datos de contacto y la edad del siguiente:
            </p>
        </div>
        <div class="fondo-registro">
            <h4 class="text-center mb-4 text-success"><?= htmlspecialchars($publicacion->obtener_titulo()) ?></h4>

            <form method="post">
                <div class="mb-3">
                    <label for="telefono" class="form-label">
                        <i class="bi bi-telephone"></i> Teléfono
                    </label>
                    <input type="text" class="form-control" id="telefono" name="telefono" required
                        value="<?= htmlspecialchars($publicacion->obtener_telefono()) ?>">
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">
                        <i class="bi bi-envelope"></i> Correo electrónico
                    </label>
                    <input type="email" class="form-control" id="correo" name="correo" required
                        value="<?= htmlspecialchars($publicacion->obtener_correo()) ?>">
                </div>
                <div class="mb-4">
                    <label for="edad" class="form-label">
                        <i class="bi bi-hourglass-split"></i> Edad
                    </label>
                    <select class="form-select" id="edad" name="edad" required>
                        <option value="">Selecciona la edad</option>
                        <optgroup label="Meses">
                            <?php
                            $edad_actual = $publicacion->obtener_edad();
                            for ($i = 1; $i <= 12; $i++) {
                                $texto = $i . ' ' . ($i === 1 ? 'mes' : 'meses');
                                $selected = ($edad_actual === $texto) ? 'selected' : '';
                                echo "<option value='$texto' $selected>$texto</option>";
                            }
                            ?>
                        </optgroup>
                        <optgroup label="Años">
                            <?php
                            for ($i = 1; $i <= 6; $i++) {
                                $texto = $i . ' ' . ($i === 1 ? 'año' : 'años');
                                $selected = ($edad_actual === $texto) ? 'selected' : '';
                                echo "<option value='$texto' $selected>$texto</option>";
                            }
                            $selected = ($edad_actual === 'Más de 6 años') ? 'selected' : '';
                            echo "<option value='Más de 6 años' $selected>Más de 6 años</option>";
                            ?>
                        </optgroup>
                    </select>
                </div>


                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success px-4">Guardar cambios</button>
                    <a href="<?= RUTA_PERFIL ?>" class="btn btn-outline-cafe">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#edad').select2({
            width: '100%',
            dropdownAutoWidth: true,
            placeholder: 'Selecciona la edad',
            allowClear: true
        });
    });
</script>


<?php include_once 'plantillas/documento-cierre.inc.php'; ?>