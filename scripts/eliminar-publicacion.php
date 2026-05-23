<?php
include_once '../app/config.inc.php';
include_once '../app/Conexion.inc.php';
include_once '../app/ControlSesion.inc.php';
include_once '../app/RepositorioAnimal.inc.php';
include_once '../app/RepositorioCaballo.inc.php';
include_once '../app/Redireccion.inc.php';

if (!ControlSesion::sesion_iniciada()) {
    Redireccion::redirigir(RUTA_LOGIN);
    exit();
}

$tipo = $_GET['tipo'] ?? '';
$id = $_GET['id'] ?? '';

if (!$tipo || !$id) {
    Redireccion::redirigir(RUTA_PERFIL);
    exit();
}

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

// Validar existencia y propiedad de la publicación
$esPropia = false;

if ($tipo === 'animal') {
    $animal = RepositorioAnimal::obtener_animal_por_id($conexion, $id);
    if ($animal && $animal->obtener_id_usuario() == $_SESSION['id_usuario']) {
        $esPropia = true;
        $eliminado = RepositorioAnimal::eliminar_animal_por_id($conexion, $id, $_SESSION['id_usuario']);
    }
} elseif ($tipo === 'caballo') {
    $caballo = RepositorioCaballo::obtener_caballo_por_id($conexion, $id);
    if ($caballo && $caballo->obtener_id_usuario() == $_SESSION['id_usuario']) {
        $esPropia = true;
        $eliminado = RepositorioCaballo::eliminar_caballo_por_id($conexion, $id, $_SESSION['id_usuario']);
    }
}

if ($esPropia && $eliminado) {
    Redireccion::redirigir(RUTA_PERFIL . '?eliminado=1');
} else {
    Redireccion::redirigir(RUTA_PERFIL . '?error=1');
}
exit();
