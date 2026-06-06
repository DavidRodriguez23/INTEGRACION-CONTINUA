<?php

class Conexion {
    private static $conexion;
    
    public static function abrir_conexion() {
        if (!isset(self::$conexion)){
            try {
                include_once 'config.inc.php';

                self::$conexion = new PDO(
                    'mysql:host=' . NOMBRE_SERVIDOR . ';dbname=' . NOMBRE_DB . ';charset=utf8mb4',
                    NOMBRE_USUARIO,
                    PASSWORD,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );

            } catch (PDOException $ex) {
                print "ERROR: ". $ex->getMessage() . "<br>";
                die();
            }
        }
    }

    public static function cerrar_conexion(){
        if (isset(self::$conexion)){
            self::$conexion = null;
        }
    }

    public static function obtener_conexion() {
        return self::$conexion;
    }

}
?>
