<?php
include_once 'app/Usuario.inc.php';

class RepositorioUsuario
{

    public static function insertar_usuario($conexion, $usuario)
    {

        $usuario_insertado = false;

        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO usuarios (nombre, identificacion, correo, telefono, clave, estado_membresia, rol, estado_usuario, fecha_registro, fecha_inicio_membresia, fecha_fin_membresia, tipo_insumos)
                VALUES (:nombre, :identificacion, :correo, :telefono, :clave, 0, 'cliente', 1, NOW(), NOW(), NOW(), :tipo_insumos)";


                $sentencia = $conexion->prepare($sql);

                $nombre = $usuario->obtener_nombre();
                $identificacion = $usuario->obtener_identificacion();
                $correo = $usuario->obtener_correo();
                $telefono = $usuario->obtener_telefono();
                $clave = $usuario->obtener_clave();
                $tipo_insumos = $usuario->obtener_tipo_insumos();
                $sentencia->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $sentencia->bindParam(':identificacion', $identificacion, PDO::PARAM_STR);
                $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
                $sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
                $sentencia->bindParam(':clave', $clave, PDO::PARAM_STR);
                $sentencia->bindParam(':tipo_insumos', $tipo_insumos, PDO::PARAM_STR);

                $usuario_insertado = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $usuario_insertado;
    }

    public static function obtener_todos($conexion)
    {
        $usuarios = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM usuarios";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultado as $fila) {
                    $usuarios[] = new Usuario(
                        $fila['id'],
                        $fila['nombre'],
                        $fila['identificacion'],
                        $fila['correo'],
                        $fila['telefono'],
                        $fila['clave'],
                        $fila['estado_membresia'],
                        $fila['rol'],
                        $fila['estado_usuario'],
                        $fila['fecha_registro'],
                        $fila['fecha_inicio_membresia'],
                        $fila['fecha_fin_membresia'],
                        $fila['tipo_insumos']
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $usuarios;
    }

    public static function obtener_usuario_por_id($conexion, $id)
    {
        $usuario = null;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM usuarios WHERE id = :id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

                if ($resultado) {
                    $usuario = new Usuario(
                        $resultado['id'],
                        $resultado['nombre'],
                        $resultado['identificacion'],
                        $resultado['correo'],
                        $resultado['telefono'],
                        $resultado['clave'],
                        $resultado['estado_membresia'],
                        $resultado['rol'],
                        $resultado['estado_usuario'],
                        $resultado['fecha_registro'],
                        $resultado['fecha_inicio_membresia'],
                        $resultado['fecha_fin_membresia'],
                        $resultado['tipo_insumos']
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $usuario;
    }


    public static function obtener_usuario_por_correo($conexion, $correo)
    {
        $usuario = null;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM usuarios WHERE correo = :correo";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
                $sentencia->execute();
                $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

                if ($resultado) {
                    $usuario = new Usuario(
                        $resultado['id'],
                        $resultado['nombre'],
                        $resultado['identificacion'],
                        $resultado['correo'],
                        $resultado['telefono'],
                        $resultado['clave'],
                        $resultado['estado_membresia'],
                        $resultado['rol'],
                        $resultado['estado_usuario'],
                        $resultado['fecha_registro'],
                        $resultado['fecha_inicio_membresia'],
                        $resultado['fecha_fin_membresia'],
                        $resultado['tipo_insumos']
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $usuario;
    }

    public static function nombre_usuario_existe($conexion, $nombre_usuario)
    {
        $existe = false;
        if (isset($conexion)) {
            try {
                $sql = "SELECT COUNT(*) FROM usuarios WHERE nombre = :nombre_usuario";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
                $sentencia->execute();
                $resultado = $sentencia->fetchColumn();

                if ($resultado > 0) {
                    $existe = true;
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $existe;
    }

    public static function identificacion_existe($conexion, $identificacion)
    {
        $identificacion_existe = false;

        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM usuarios WHERE identificacion = :identificacion LIMIT 1";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':identificacion', $identificacion, PDO::PARAM_STR);
                $sentencia->execute();
                $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

                if ($resultado) {
                    $identificacion_existe = true;
                }
            } catch (PDOException $ex) {
                print "¡Error!: " . $ex->getMessage() . "<br/>";
            }
        }
        return $identificacion_existe;
    }

    public static function correo_usuario_existe($conexion, $correo_usuario)
    {
        $existe = false;
        if (isset($conexion)) {
            try {
                $sql = "SELECT COUNT(*) FROM usuarios WHERE correo = :correo_usuario";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':correo_usuario', $correo_usuario, PDO::PARAM_STR);
                $sentencia->execute();
                $resultado = $sentencia->fetchColumn();

                if ($resultado > 0) {
                    $existe = true;
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $existe;
    }

    public static function telefono_usuario_existe($conexion, $telefono_usuario)
    {
        $existe = false;
        if (isset($conexion)) {
            try {
                $sql = "SELECT COUNT(*) FROM usuarios WHERE telefono = :telefono_usuario";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':telefono_usuario', $telefono_usuario, PDO::PARAM_STR);
                $sentencia->execute();
                $resultado = $sentencia->fetchColumn();

                if ($resultado > 0) {
                    $existe = true;
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $existe;
    }

    public static function actualizar_clave($conexion, $id_usuario, $clave)
    {
        $clave_actualizada = false;

        if (isset($conexion)) {
            try {
                $sql = "UPDATE usuarios SET clave = :clave WHERE id = :id_usuario";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':clave', $clave, PDO::PARAM_STR);
                $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $clave_actualizada = $sentencia->execute();
            } catch (PDOException $ex) {
                print "¡Error!: " . $ex->getMessage() . "<br/>";
            }
        }
        return $clave_actualizada;
    }

    public static function contar_publicaciones_activas_por_usuario($conexion, $id_usuario)
{
    $cantidad_publicaciones = 0;

    if (isset($conexion)) {
        try {
            // Contar animales no vendidos
            $sql_animales = "SELECT COUNT(*) FROM animales WHERE id_usuario = :id_usuario AND vendido = 0";
            $sentencia_animales = $conexion->prepare($sql_animales);
            $sentencia_animales->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $sentencia_animales->execute();
            $cantidad_animales = $sentencia_animales->fetchColumn();

            // Contar caballos no vendidos
            $sql_caballos = "SELECT COUNT(*) FROM caballos WHERE id_usuario = :id_usuario AND vendido = 0";
            $sentencia_caballos = $conexion->prepare($sql_caballos);
            $sentencia_caballos->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $sentencia_caballos->execute();
            $cantidad_caballos = $sentencia_caballos->fetchColumn();

            // Sumar ambos conteos
            $cantidad_publicaciones = $cantidad_animales + $cantidad_caballos;

        } catch (PDOException $ex) {
            print "¡Error!: " . $ex->getMessage() . "<br/>";
        }
    }

    return $cantidad_publicaciones;
}


    public static function actualizar_estado_usuario($conexion, $id_usuario, $nuevo_estado)
    {
        $estado_actualizado = false;

        if (isset($conexion)) {
            try {
                $sql = "UPDATE usuarios SET estado_usuario = :nuevo_estado WHERE id = :id_usuario";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':nuevo_estado', $nuevo_estado, PDO::PARAM_INT);
                $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $estado_actualizado = $sentencia->execute();
            } catch (PDOException $ex) {
                print "¡Error!: " . $ex->getMessage() . "<br/>";
            }
        }
        return $estado_actualizado;
    }

    public static function actualizar_rol_usuario($conexion, $id_usuario, $nuevo_rol)
    {
        $rol_actualizado = false;

        if (isset($conexion)) {
            try {
                $sql = "UPDATE usuarios SET rol = :nuevo_rol WHERE id = :id_usuario";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':nuevo_rol', $nuevo_rol, PDO::PARAM_STR);
                $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $rol_actualizado = $sentencia->execute();
            } catch (PDOException $ex) {
                print "¡Error!: " . $ex->getMessage() . "<br/>";
            }
        }
        return $rol_actualizado;
    }

    public static function eliminar_publicaciones_por_usuario($conexion, $id_usuario)
    {
        $resultado_animales = false;
        $resultado_caballos = false;

        if (isset($conexion)) {
            try {
                // Eliminar animales
                $sql_animales = "DELETE FROM animales WHERE id_usuario = :id_usuario";
                $sentencia_animales = $conexion->prepare($sql_animales);
                $sentencia_animales->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $resultado_animales = $sentencia_animales->execute();

                // Eliminar caballos
                $sql_caballos = "DELETE FROM caballos WHERE id_usuario = :id_usuario";
                $sentencia_caballos = $conexion->prepare($sql_caballos);
                $sentencia_caballos->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $resultado_caballos = $sentencia_caballos->execute();

                // Aquí puedes agregar más tablas si es necesario
            } catch (PDOException $ex) {
                print "¡Error!: " . $ex->getMessage() . "<br/>";
            }
        }

        // Retorna true solo si ambas eliminaciones fueron exitosas
        return $resultado_animales && $resultado_caballos;
    }


    public static function eliminar_usuario_por_id($conexion, $id_usuario)
    {
        $usuario_eliminado = false;

        if (isset($conexion)) {
            try {
                $sql = "DELETE FROM usuarios WHERE id = :id_usuario";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $usuario_eliminado = $sentencia->execute();
            } catch (PDOException $ex) {
                print "¡Error!: " . $ex->getMessage() . "<br/>";
            }
        }
        return $usuario_eliminado;
    }

    public static function actualizar_datos_admin($conexion, $id_usuario, $nombre, $identificacion, $correo, $telefono, $rol, $tipo_insumos)
    {
        $datos_actualizados = false;

        if (isset($conexion)) {
            try {
                $sql = "UPDATE usuarios SET nombre = :nombre, identificacion = :identificacion, correo = :correo, telefono = :telefono, rol = :rol, tipo_insumos = :tipo_insumos WHERE id = :id_usuario";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $sentencia->bindParam(':identificacion', $identificacion, PDO::PARAM_STR);
                $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
                $sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
                $sentencia->bindParam(':rol', $rol, PDO::PARAM_STR);
                $sentencia->bindParam(':tipo_insumos', $tipo_insumos, PDO::PARAM_STR);
                $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $datos_actualizados = $sentencia->execute();
            } catch (PDOException $ex) {
                print "¡Error!: " . $ex->getMessage() . "<br/>";
            }
        }
        return $datos_actualizados;
    }
}
