<?php
include_once 'Conexion.inc.php';

class RepositorioAjustes {

    public static function obtener_valor($clave, $conexion) {
        $sql = "SELECT valor FROM ajustes WHERE clave = :clave LIMIT 1";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':clave', $clave, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetch();

        return $resultado ? $resultado['valor'] : '';
    }

    public static function guardar_valor($clave, $valor, $conexion) {
        // Inserta o actualiza
        $sql = "INSERT INTO ajustes (clave, valor) 
                VALUES (:clave, :valor) 
                ON DUPLICATE KEY UPDATE valor = :valor";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':clave', $clave, PDO::PARAM_STR);
        $sentencia->bindParam(':valor', $valor, PDO::PARAM_STR);
        return $sentencia->execute();
    }

    public static function estado_sitio($conexion) {
    $estado = self::obtener_valor('estado_sitio', $conexion);
    if ($estado === 'mantenimiento') {
        return true;
    }
    return false;
}

}
