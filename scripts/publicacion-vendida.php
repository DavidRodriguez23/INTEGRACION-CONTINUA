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

if ($tipo === 'animal') {
    RepositorioAnimal::marcar_animal_vendido($conexion, $id, $_SESSION['id_usuario']);
} elseif ($tipo === 'caballo') {
    RepositorioCaballo::marcar_caballo_vendido($conexion, $id, $_SESSION['id_usuario']);
}

Redireccion::redirigir(RUTA_PERFIL . '?vendido=1');
exit();