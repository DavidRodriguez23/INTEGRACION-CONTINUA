<?php
include_once '../app/config.inc.php';
include_once '../app/Conexion.inc.php';
include_once '../app/RepositorioAnimal.inc.php';
include_once '../app/RepositorioCaballo.inc.php';
include_once '../app/Animal.inc.php';
include_once '../app/Caballo.inc.php';
include_once '../app/ControlSesion.inc.php';

session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    $id = $_POST['id'] ?? '';
    $key = $tipo . '_' . $id;

    if (isset($_POST['agregar'])) {
        $_SESSION['carrito'][$key] = ['tipo' => $tipo, 'id' => $id];
    } elseif (isset($_POST['quitar'])) {
        unset($_SESSION['carrito'][$key]);
    } elseif (isset($_POST['vaciar'])) {
        $_SESSION['carrito'] = [];
    }
    // Cambia la redirección después de agregar/quitar/vaciar para que siempre vaya a la ruta amigable
    header('Location: ' . RUTA_CARRITO);
    exit();
}

$carrito = $_SESSION['carrito'] ?? [];

include_once '../plantillas/documento-apertura.inc.php';
include_once '../plantillas/navbar.inc.php';
?>
<br><br><br>
<div class="container my-5">
    <h2 class="text-center mb-4"><i class="fas fa-shopping-cart text-success"></i> Carrito de Compras</h2>
    <?php if (empty($carrito)): ?>
        <div class="alert alert-info text-center">Tu carrito está vacío.</div>
    <?php else: ?>
        <form method="post" class="mb-3 text-end">
            <button type="submit" name="vaciar" class="btn btn-danger">
                <i class="fas fa-trash"></i> Vaciar carrito
            </button>
        </form>
        <div class="row">
            <?php
            Conexion::abrir_conexion();
            $conexion = Conexion::obtener_conexion();
            foreach ($carrito as $item) {
                $tipo = $item['tipo'];
                $id = $item['id'];
                // Validar que el ID sea numérico y no vacío
                if (!is_numeric($id) || empty($id)) {
                    continue;
                }
                if ($tipo === 'animal') {
                    $obj = RepositorioAnimal::obtener_animal_por_id($conexion, $id);
                    $url = SERVIDOR . '/animal/' . $id;
                    $icono = '<i class="fas fa-cow fa-3x text-success"></i>';
                } else {
                    $obj = RepositorioCaballo::obtener_caballo_por_id($conexion, $id);
                    $url = SERVIDOR . '/caballo/' . $id;
                    $icono = '<i class="fas fa-horse fa-3x text-primary"></i>';
                }
                if (!$obj) {
                    // Mostrar mensaje si no se encuentra el animal/caballo
                    echo '<div class="col-md-12 mb-4">';
                    echo '<div class="alert alert-danger text-center">';
                    echo $icono . '<br>No se encontró el ' . ($tipo === 'animal' ? 'animal' : 'caballo') . ' (ID: ' . htmlspecialchars($id) . '). ';
                    echo '<form method="post" class="d-inline">';
                    echo '<input type="hidden" name="tipo" value="' . htmlspecialchars($tipo) . '">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($id) . '">';
                    echo '<button type="submit" name="quitar" class="btn btn-warning btn-sm ms-2">';
                    echo '<i class="fas fa-times"></i> Quitar del carrito';
                    echo '</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    continue;
                }
                $titulo = htmlspecialchars($obj->obtener_titulo());
                $precio = '$ ' . number_format($obj->obtener_precio(), 0, ',', '.');
            ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <?= $icono ?>
                        <h5 class="card-title mt-3"><?= $titulo ?></h5>
                        <p class="card-text"><?= $precio ?></p>
                        <a href="<?= $url ?>" class="btn btn-outline-info btn-sm mb-2">Ver detalle</a>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="tipo" value="<?= $tipo ?>">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <button type="submit" name="quitar" class="btn btn-warning btn-sm">
                                <i class="fas fa-times"></i> Quitar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    <?php endif; ?>
</div>
<?php include_once '../plantillas/documento-cierre.inc.php'; ?>

