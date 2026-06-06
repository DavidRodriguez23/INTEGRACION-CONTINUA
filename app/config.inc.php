<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

session_start();
//info base de datos
define('NOMBRE_SERVIDOR', 'db');
define('NOMBRE_USUARIO', 'ganandez_user'); //nombre usuario de la base de datos
define('PASSWORD', 'ganandez_pass');// password de la base de datos
define('NOMBRE_DB', 'IT');//nombre de la base de datos

//rutas de la web

define("SERVIDOR", "http://localhost:8080"); 
define("RUTA_REGISTRO", SERVIDOR. "/registro");
define("RUTA_REGISTRO_CORRECTO", SERVIDOR. "/registro-correcto");
define("RUTA_LOGIN", SERVIDOR. "/login");
define("RUTA_RECUPERAR_CLAVE", SERVIDOR. "/recuperar-clave");
define("RUTA_GENERAR_URL_SECRETA", SERVIDOR. "/generar-url-secreta");
define("RUTA_RECUPERACION_CLAVE", SERVIDOR. "/recuperacion-clave");
define("RUTA_CLAVE_RECUPERADA", SERVIDOR. "/clave-recuperada");
define("RUTA_LOGOUT", SERVIDOR. "/logout");
define("RUTA_VENTA", SERVIDOR. "/venta");
define("RUTA_PERFIL",SERVIDOR."/perfil");
define("RUTA_PREVIA_VENTA", SERVIDOR."/previa-venta");
define("RUTA_NOSOTROS", SERVIDOR."/nosotros");
define("RUTA_COMPRA", SERVIDOR."/compra");
define("RUTA_CONTACTENOS", SERVIDOR."/contactenos");
define("RUTA_VISTA_ANIMAL", SERVIDOR."/vista-animal");
define("RUTA_VENTA_CABALLO", SERVIDOR."/venta-caballo");
define("RUTA_PREVIA_VENTA_CABALLO", SERVIDOR."/previa-venta-caballo");
define("RUTA_ELIMINAR_PUBLICACION", SERVIDOR."/scripts/eliminar-publicacion");
define("RUTA_PUBLICACION_VENDIDA", SERVIDOR."/scripts/publicacion-vendida");
define("RUTA_EDITAR_ANIMAL_USUARIO", SERVIDOR."/editar-animal-usuario");
define("RUTA_ADMIN_PANEL", SERVIDOR."/admin-panel");
define("RUTA_ADMIN_USUARIOS", SERVIDOR."/admin-usuarios");
define("RUTA_ADMIN_EDITAR_USUARIO", SERVIDOR."/admin-editar-usuario");
define("RUTA_ADMIN_PUBLICACIONES", SERVIDOR."/admin-publicaciones");
define('RUTA_ADMIN_EDITAR_ANIMAL', SERVIDOR . '/admin-editar-animal.php');
define('RUTA_ADMIN_EDITAR_CABALLO', SERVIDOR . '/admin-editar-caballo.php');
define("RUTA_ADMIN_REPORTES", SERVIDOR."/admin-reportes");
define("RUTA_ADMIN_AJUSTES", SERVIDOR."/admin-ajustes");
define("RUTA_CARRITO", SERVIDOR."/vistas/carrito.php");



//Recursos

define("RUTA_CSS", SERVIDOR . "/css/");
define("DIRECTORIO_RAIZ", realpath(__DIR__)."/..");


?>
