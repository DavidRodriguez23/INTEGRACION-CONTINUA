<?php
include_once 'app/config.inc.php';
include_once 'app/Conexion.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/Redireccion.inc.php';

include_once 'app/EscritorAnimal.inc.php';

include_once 'app/Usuario.inc.php';
include_once 'app/Animal.inc.php';
include_once 'app/Caballo.inc.php';

include_once 'app/RepositorioUsuario.inc.php';
include_once 'app/RepositorioAnimal.inc.php';
include_once 'app/RepositorioCaballo.inc.php';


$compenentes_url = parse_url($_SERVER['REQUEST_URI']); //$_SERVER['SERVER_NAME'] .

$ruta = $compenentes_url['path'];

$partes_ruta = explode("/", $ruta);
$partes_ruta = array_filter($partes_ruta);
$partes_ruta = array_slice($partes_ruta, 0);

$ruta_elegida = 'vistas/404.php';
if (isset($partes_ruta[0])) { //DOMINIO SE ENCUENTRA EL PROYECTO
     if (count($partes_ruta) == 1) {
        $ruta_elegida = 'vistas/home.php';
    } elseif (count($partes_ruta) == 2) {
        switch ($partes_ruta[1]) {
            case 'registro':
                $ruta_elegida = 'vistas/registro.php';
                break;
            case 'login':
                $ruta_elegida = 'vistas/login.php';
                break;
            case 'generar-url-secreta':
                $ruta_elegida = 'scripts/generar-url-secreta.php';
                break;
            case 'clave-recuperada':
                $ruta_elegida = 'vistas/clave-recuperada.php';
                break;
            case 'recuperar-clave':
                $ruta_elegida = 'vistas/recuperar-clave.php';
                break;
            case 'logout':
                $ruta_elegida = 'vistas/logout.php';
                break;
            case 'venta':
                $ruta_elegida = 'vistas/venta.php';
                break;
            case 'perfil':
                $ruta_elegida = 'vistas/perfil.php';
                break;
            case 'previa-venta':
                $ruta_elegida = 'vistas/previa-venta.php';
                break;
            case 'nosotros':
                $ruta_elegida = 'vistas/nosotros.php';
                break;
            case 'compra':
                $ruta_elegida = 'vistas/compra.php';
                break;
            case 'contactenos':
                $ruta_elegida = 'vistas/contactenos.php';
                break;
            case 'vista-animal':
                $ruta_elegida = 'vistas/vista-animal.php';
                break;
            case 'previa-venta-caballo':
                $ruta_elegida = 'vistas/previa-venta-caballo.php';
                break;
            case 'venta-caballo':
                $ruta_elegida = 'vistas/venta-caballo.php';
                break;
            case 'admin-panel':
                $ruta_elegida = 'vistas/admin-panel.php';
                break;
            case 'admin-usuarios':
                $ruta_elegida = 'vistas/admin-usuarios.php';
                break;
            case 'cambiar_estado':
                $ruta_elegida = 'scripts/cambiar_estado.php';
                break;
            case 'admin-editar-usuario':
                $ruta_elegida = 'vistas/admin-editar-usuario.php';
                break;
            case 'admin-publicaciones':
                $ruta_elegida = 'vistas/admin-publicaciones.php';
                break;
            case 'admin-reportes':
                $ruta_elegida = 'vistas/admin-reportes.php';
                break;
            case 'admin-ajustes':
                $ruta_elegida = 'vistas/admin-ajustes.php';
                break;
            case 'carrito':
                $ruta_elegida = 'vistas/carrito.php';
                break;
        }
    } elseif (count($partes_ruta) == 3) {
        if ($partes_ruta[1] == 'registro-correcto') {
            $nombre = $partes_ruta[2];
            $ruta_elegida = 'vistas/registro-correcto.php';
        }
        if ($partes_ruta[1] == 'recuperacion-clave') {
            $url_personal = $partes_ruta[2];
            $ruta_elegida = 'vistas/recuperacion-clave.php';
        }
        // Vista de animal
        if ($partes_ruta[1] === 'animal') {
            $id_animal = $partes_ruta[2];

            Conexion::abrir_conexion();
            $animal = RepositorioAnimal::obtener_animal_por_id(Conexion::obtener_conexion(), $id_animal);

            if ($animal) {
                include_once 'vistas/vista-animal.php'; // $animal ya disponible
            } else {
                echo "<div class='alert alert-danger text-center'>No se encontró el animal.</div>";
            }
            return;
        }
        // Vista de caballo
        if ($partes_ruta[1] === 'caballo') {
            $id_caballo = $partes_ruta[2];

            Conexion::abrir_conexion();
            $animal = RepositorioCaballo::obtener_caballo_por_id(Conexion::obtener_conexion(), $id_caballo);

            if ($animal) {
                include_once 'vistas/vista-caballo.php'; // $animal ya disponible
            } else {
                echo "<div class='alert alert-danger text-center'>No se encontró el caballo.</div>";
            }
            return;
        }
    } elseif (count($partes_ruta) == 4) {
        // /Ganandez/eliminar-publicacion/animal/123
        if ($partes_ruta[1] === 'eliminar-publicacion') {
            $_GET['tipo'] = $partes_ruta[2];
            $_GET['id'] = $partes_ruta[3];
            $ruta_elegida = 'scripts/eliminar-publicacion.php';
        } elseif ($partes_ruta[1] === 'publicacion-vendida') {
            $_GET['tipo'] = $partes_ruta[2];
            $_GET['id'] = $partes_ruta[3];
            $ruta_elegida = 'scripts/publicacion-vendida.php';
        }
        // Editar animal o caballo
        if ($partes_ruta[1] === 'editar-animal-usuario') {
            $_GET['tipo'] = $partes_ruta[2];
            $_GET['id'] = $partes_ruta[3];
            $ruta_elegida = 'vistas/editar-animal-usuario.php';
        }
        // Admin editar animal
        if ($partes_ruta[1] === 'admin-editar-animal') {
            $_GET['tipo'] = $partes_ruta[2];
            $_GET['id'] = $partes_ruta[3];
            $ruta_elegida = 'vistas/admin-editar-animal.php';
        }
        // Admin editar caballo
        if ($partes_ruta[1] === 'admin-editar-caballo') {
            $_GET['tipo'] = $partes_ruta[2];
            $_GET['id'] = $partes_ruta[3];
            $ruta_elegida = 'vistas/admin-editar-caballo.php';
        }
    }

    include_once $ruta_elegida;
}
