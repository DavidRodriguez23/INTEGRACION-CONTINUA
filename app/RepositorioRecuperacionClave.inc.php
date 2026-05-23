<?php

class RepositorioRecuperacionClave{

    public static function generar_peticion($conexion, $id_usuario, $url_secreta){
        $peticion_generada = false;

        if (isset($conexion)){
            try {
                $sql = "INSERT INTO recuperar_clave(usuario_id, url_secreta, fecha) VALUES (:usuario_id, :url_secreta, NOW())";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':usuario_id', $id_usuario, PDO::PARAM_INT);
                $sentencia->bindParam(':url_secreta', $url_secreta, PDO::PARAM_STR);
                $peticion_generada = $sentencia->execute();
            } catch (PDOException $ex) {
                echo 'Error: ' . $ex->getMessage();
            }
        }
        return $peticion_generada;
    }

    public static function url_secreta_existe($conexion, $url_secreta) {
        $existe = false;

        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM recuperar_clave WHERE url_secreta = :url_secreta";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':url_secreta', $url_secreta, PDO::PARAM_STR);
                $sentencia->execute();

                if ($sentencia->rowCount() > 0) {
                    $existe = true;
                }
            } catch (PDOException $ex) {
                echo 'Error: ' . $ex->getMessage();
            }
        }

        return $existe;
    }

    public static function obtener_id_usuario_url_secreta($conexion, $url_secreta) {
        $id_usuario = null;

        if (isset($conexion)) {
            try {
                $sql = "SELECT usuario_id FROM recuperar_clave WHERE url_secreta = :url_secreta";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':url_secreta', $url_secreta, PDO::PARAM_STR);
                $sentencia->execute();

                if ($sentencia->rowCount() > 0) {
                    $fila = $sentencia->fetch(PDO::FETCH_ASSOC);
                    $id_usuario = $fila['usuario_id'];
                }
            } catch (PDOException $ex) {
                echo 'Error: ' . $ex->getMessage();
            }
        }

        return $id_usuario;
    }

    public static function eliminar_peticion($conexion, $url_secreta) {
        $peticion_eliminada = false;

        if (isset($conexion)) {
            try {
                $sql = "DELETE FROM recuperar_clave WHERE url_secreta = :url_secreta";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':url_secreta', $url_secreta, PDO::PARAM_STR);
                $peticion_eliminada = $sentencia->execute();
            } catch (PDOException $ex) {
                echo 'Error: ' . $ex->getMessage();
            }
        }

        return $peticion_eliminada;
    }

}

?>