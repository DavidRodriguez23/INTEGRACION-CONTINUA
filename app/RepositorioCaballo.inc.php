<?php

include_once 'Caballo.inc.php';

class RepositorioCaballo
{

    // Insertar un nuevo caballo
    public static function insertar_caballo($conexion, $caballo)
    {
        $caballo_insertado = false;

        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO caballos (
                        titulo, descripcion, categoria, raza, sexo, edad, peso, precio, caracteristicas, imagenes, videos,
                        telefono, correo, departamento, municipio, direccion, latitud, longitud, destacado, premium, sugerido, fecha_publicacion, terminos, fecha_fin, id_usuario, soporte_pago, valor_comision
                    ) VALUES (
                        :titulo, :descripcion, :categoria, :raza, :sexo, :edad, :peso, :precio, :caracteristicas, :imagenes, :videos,
                        :telefono, :correo, :departamento, :municipio, :direccion, :latitud, :longitud, :destacado, :premium, :sugerido, NOW(), :terminos, :fecha_fin, :id_usuario, :soporte_pago, :valor_comision
                    )";

                $sentencia = $conexion->prepare($sql);

                // Variables intermedias
                $titulo = $caballo->obtener_titulo();
                $descripcion = $caballo->obtener_descripcion();
                $categoria = $caballo->obtener_categoria();
                $raza = $caballo->obtener_raza();
                $sexo = $caballo->obtener_sexo();
                $edad = $caballo->obtener_edad();
                $peso = $caballo->obtener_peso();
                $precio = $caballo->obtener_precio();
                $caracteristicas = $caballo->obtener_caracteristicas();
                $imagenes = $caballo->obtener_imagenes();
                $videos = $caballo->obtener_videos();
                $telefono = $caballo->obtener_telefono();
                $correo = $caballo->obtener_correo();
                $departamento = $caballo->obtener_departamento();
                $municipio = $caballo->obtener_municipio();
                $direccion = $caballo->obtener_direccion();
                $latitud = $caballo->obtener_latitud();
                $longitud = $caballo->obtener_longitud();
                $destacado = $caballo->obtener_destacado();
                $premium = $caballo->obtener_premium();
                $sugerido = $caballo->obtener_sugerido();
                $terminos = $caballo->obtener_terminos();
                $fecha_fin = $caballo->obtener_fecha_fin();
                $id_usuario = $caballo->obtener_id_usuario();
                $soporte_pago = $caballo->obtener_soporte_pago();
                $valor_comision = $caballo->obtener_valor_comision();

                // Binds
                $sentencia->bindParam(':titulo', $titulo, PDO::PARAM_STR);
                $sentencia->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $sentencia->bindParam(':categoria', $categoria, PDO::PARAM_STR);
                $sentencia->bindParam(':raza', $raza, PDO::PARAM_STR);
                $sentencia->bindParam(':sexo', $sexo, PDO::PARAM_STR);
                $sentencia->bindParam(':edad', $edad, PDO::PARAM_STR);
                $sentencia->bindParam(':peso', $peso, PDO::PARAM_INT);
                $sentencia->bindParam(':precio', $precio, PDO::PARAM_STR);
                $sentencia->bindParam(':caracteristicas', $caracteristicas, PDO::PARAM_STR);
                $sentencia->bindParam(':imagenes', $imagenes, PDO::PARAM_STR);
                $sentencia->bindParam(':videos', $videos, PDO::PARAM_STR);
                $sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
                $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
                $sentencia->bindParam(':departamento', $departamento, PDO::PARAM_STR);
                $sentencia->bindParam(':municipio', $municipio, PDO::PARAM_STR);
                $sentencia->bindParam(':direccion', $direccion, PDO::PARAM_STR);
                $sentencia->bindParam(':latitud', $latitud, PDO::PARAM_STR);
                $sentencia->bindParam(':longitud', $longitud, PDO::PARAM_STR);
                $sentencia->bindParam(':destacado', $destacado, PDO::PARAM_INT);
                $sentencia->bindParam(':premium', $premium, PDO::PARAM_INT);
                $sentencia->bindParam(':sugerido', $sugerido, PDO::PARAM_INT);
                $sentencia->bindParam(':terminos', $terminos, PDO::PARAM_INT);
                $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
                $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $sentencia->bindParam(':soporte_pago', $soporte_pago, PDO::PARAM_STR);
                $sentencia->bindParam(':valor_comision', $valor_comision, PDO::PARAM_STR);

                $caballo_insertado = $sentencia->execute();
            } catch (PDOException $ex) {
                // Manejo de errores
                throw new Exception('Error al insertar el caballo: ' . $ex->getMessage());
            }
        }
        if ($caballo_insertado) {
            return $conexion->lastInsertId();
        }

        return $caballo_insertado;
    }


    // Obtener todos los caballos
    public static function obtener_todos($conexion)
    {
        $caballos = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM caballos ORDER BY fecha_publicacion DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll();
                foreach ($resultados as $fila) {
                    $caballos[] = new Caballo(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['sexo'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['caracteristicas'],
                        $fila['imagenes'],
                        $fila['videos'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['latitud'],
                        $fila['longitud'],
                        $fila['destacado'],
                        $fila['premium'],
                        $fila['sugerido'],
                        $fila['fecha_publicacion'],
                        $fila['terminos'],
                        $fila['fecha_fin'],
                        $fila['id_usuario'],
                        $fila['vendido'],
                        $fila['soporte_pago'] ?? null,
                        $fila['valor_comision'] ?? null
                    );
                }
            } catch (PDOException $ex) {
                // Manejo de errores
            }
        }
        return $caballos;
    }

    // Obtener caballo por ID
    public static function obtener_caballo_por_id($conexion, $id)
    {
        $caballo = null;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM caballos WHERE id = :id LIMIT 1";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                $sentencia->execute();
                $fila = $sentencia->fetch();
                if ($fila) {
                    $caballo = new Caballo(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['sexo'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['caracteristicas'],
                        $fila['imagenes'],
                        $fila['videos'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['latitud'],
                        $fila['longitud'],
                        $fila['destacado'],
                        $fila['premium'],
                        $fila['sugerido'],
                        $fila['fecha_publicacion'],
                        $fila['terminos'],
                        $fila['fecha_fin'],
                        $fila['id_usuario'],
                        $fila['vendido'],
                        $fila['soporte_pago'] ?? null,
                        $fila['valor_comision'] ?? null
                    );
                }
            } catch (PDOException $ex) {
                // Manejo de errores
            }
        }
        return $caballo;
    }

    // Eliminar caballo
    public static function eliminar_caballo($conexion, $id)
    {
        $eliminado = false;
        if (isset($conexion)) {
            try {
                $sql = "DELETE FROM caballos WHERE id = :id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                $eliminado = $sentencia->execute();
            } catch (PDOException $ex) {
                // Manejo de errores
            }
        }
        return $eliminado;
    }

    // Obtener caballos sugeridos
    public static function obtener_caballos_sugeridos($conexion)
    {
        $caballos = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM caballos WHERE sugerido = 1 ORDER BY fecha_publicacion DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll();
                foreach ($resultados as $fila) {
                    $caballos[] = new Caballo(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['sexo'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['caracteristicas'],
                        $fila['imagenes'],
                        $fila['videos'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['latitud'],
                        $fila['longitud'],
                        $fila['destacado'],
                        $fila['premium'],
                        $fila['sugerido'],
                        $fila['fecha_publicacion'],
                        $fila['terminos'],
                        $fila['fecha_fin'],
                        $fila['id_usuario'],
                        $fila['vendido'],
                        $fila['soporte_pago'] ?? null,
                        $fila['valor_comision'] ?? null
                    );
                }
            } catch (PDOException $ex) {
                // Manejo de errores
            }
        }
        return $caballos;
    }

    // Obtener caballos premium
    public static function obtener_caballos_premium($conexion)
    {
        $caballos = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM caballos WHERE premium = 1 ORDER BY fecha_publicacion DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll();
                foreach ($resultados as $fila) {
                    $caballos[] = new Caballo(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['sexo'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['caracteristicas'],
                        $fila['imagenes'],
                        $fila['videos'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['latitud'],
                        $fila['longitud'],
                        $fila['destacado'],
                        $fila['premium'],
                        $fila['sugerido'],
                        $fila['fecha_publicacion'],
                        $fila['terminos'],
                        $fila['fecha_fin'],
                        $fila['id_usuario'],
                        $fila['vendido'],
                        $fila['soporte_pago'] ?? null,
                        $fila['valor_comision'] ?? null
                    );
                }
            } catch (PDOException $ex) {
                // Manejo de errores
            }
        }
        return $caballos;
    }

    // Obtener caballos destacados
    public static function obtener_caballos_destacados($conexion)
    {
        $caballos = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM caballos WHERE destacado = 1 ORDER BY fecha_publicacion DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll();
                foreach ($resultados as $fila) {
                    $caballos[] = new Caballo(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['sexo'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['caracteristicas'],
                        $fila['imagenes'],
                        $fila['videos'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['latitud'],
                        $fila['longitud'],
                        $fila['destacado'],
                        $fila['premium'],
                        $fila['sugerido'],
                        $fila['fecha_publicacion'],
                        $fila['terminos'],
                        $fila['fecha_fin'],
                        $fila['id_usuario'],
                        $fila['vendido'],
                        $fila['soporte_pago'] ?? null,
                        $fila['valor_comision'] ?? null
                    );
                }
            } catch (PDOException $ex) {
                // Manejo de errores
            }
        }
        return $caballos;
    }

    // Obtener caballos normales
    public static function obtener_caballos_normales($conexion)
    {
        $caballos = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM caballos ORDER BY fecha_publicacion DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll();
                foreach ($resultados as $fila) {
                    $caballos[] = new Caballo(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['sexo'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['caracteristicas'],
                        $fila['imagenes'],
                        $fila['videos'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['latitud'],
                        $fila['longitud'],
                        $fila['destacado'],
                        $fila['premium'],
                        $fila['sugerido'],
                        $fila['fecha_publicacion'],
                        $fila['terminos'],
                        $fila['fecha_fin'],
                        $fila['id_usuario'],
                        $fila['vendido'],
                        $fila['soporte_pago'] ?? null,
                        $fila['valor_comision'] ?? null
                    );
                }
            } catch (PDOException $ex) {
                // Manejo de errores
            }
        }
        return $caballos;
    }

    // Eliminar anuncios vencidos
    public static function eliminar_anuncios_vencidos($conexion)
    {
        if (isset($conexion)) {
            try {
                $sql = "DELETE FROM caballos WHERE fecha_fin IS NOT NULL AND fecha_fin < NOW() AND (vendido IS NULL OR vendido = 0)";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception('Error al eliminar anuncios vencidos de caballos: ' . $ex->getMessage());
            }
        }
    }

    // Obtener caballos por usuario
    public static function obtener_caballos_por_usuario($conexion, $id_usuario)
    {
        $caballos = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM caballos WHERE id_usuario = :id_usuario ORDER BY fecha_publicacion DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll();
                foreach ($resultados as $fila) {
                    $caballos[] = new Caballo(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['sexo'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['caracteristicas'],
                        $fila['imagenes'],
                        $fila['videos'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['latitud'],
                        $fila['longitud'],
                        $fila['destacado'],
                        $fila['premium'],
                        $fila['sugerido'],
                        $fila['fecha_publicacion'],
                        $fila['terminos'],
                        $fila['fecha_fin'],
                        $fila['id_usuario'],
                        $fila['vendido'],
                        $fila['soporte_pago'] ?? null,
                        $fila['valor_comision'] ?? null
                    );
                }
            } catch (PDOException $ex) {
                // Manejo de errores
            }
        }
        return $caballos;
    }

    public static function actualizar_contacto_y_edad($conexion, $id, $telefono, $correo, $edad)
    {
        $actualizado = false;
        if (isset($conexion)) {
            try {
                $sql = "UPDATE caballos SET telefono = :telefono, correo = :correo, edad = :edad WHERE id = :id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
                $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
                $sentencia->bindParam(':edad', $edad, PDO::PARAM_STR);
                $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                $actualizado = $sentencia->execute();
            } catch (PDOException $ex) {
                // Manejo de errores
            }
        }
        return $actualizado;
    }

    public static function actualizar_caballo_admin ($conexion, $caballo)
    {
        $actualizado = false;

        if (isset($conexion)) {
            try {
                $sql = "UPDATE caballos SET 
                        titulo = :titulo, 
                        descripcion = :descripcion, 
                        categoria = :categoria, 
                        raza = :raza, 
                        sexo = :sexo, 
                        edad = :edad, 
                        peso = :peso, 
                        precio = :precio, 
                        caracteristicas = :caracteristicas, 
                        imagenes = :imagenes, 
                        videos = :videos, 
                        telefono = :telefono, 
                        correo = :correo, 
                        departamento = :departamento, 
                        municipio = :municipio, 
                        direccion = :direccion, 
                        latitud = :latitud, 
                        longitud = :longitud, 
                        destacado = :destacado, 
                        premium = :premium, 
                        sugerido = :sugerido,
                        terminos = :terminos,
                        fecha_fin = :fecha_fin
                    WHERE id = :id";

                $sentencia = $conexion->prepare($sql);

                // Variables intermedias
                $titulo = $caballo->obtener_titulo();
                $descripcion = $caballo->obtener_descripcion();
                $categoria = $caballo->obtener_categoria();
                $raza = $caballo->obtener_raza();
                $sexo = $caballo->obtener_sexo();
                $edad = $caballo->obtener_edad();
                $peso = $caballo->obtener_peso();
                $precio = $caballo->obtener_precio();
                $caracteristicas = $caballo->obtener_caracteristicas();
                $imagenes = $caballo->obtener_imagenes();
                $videos = $caballo->obtener_videos();
                $telefono = $caballo->obtener_telefono();
                $correo = $caballo->obtener_correo();
                $departamento = $caballo->obtener_departamento();
                $municipio = $caballo->obtener_municipio();
                $direccion = $caballo->obtener_direccion();
                $latitud = $caballo->obtener_latitud();
                $longitud = $caballo->obtener_longitud();
                $destacado = $caballo->obtener_destacado();
                $premium = $caballo->obtener_premium();
                $sugerido = $caballo->obtener_sugerido();
                $terminos = $caballo->obtener_terminos();
                $fecha_fin = $caballo->obtener_fecha_fin();
                $id = $caballo->obtener_id();
                // Binds
                $sentencia->bindParam(':titulo', $titulo, PDO::PARAM_STR);
                $sentencia->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $sentencia->bindParam(':categoria', $categoria, PDO::PARAM_STR);
                $sentencia->bindParam(':raza', $raza, PDO::PARAM_STR);
                $sentencia->bindParam(':sexo', $sexo, PDO::PARAM_STR);
                $sentencia->bindParam(':edad', $edad, PDO::PARAM_STR);
                $sentencia->bindParam(':peso', $peso, PDO::PARAM_INT);
                $sentencia->bindParam(':precio', $precio, PDO::PARAM_STR);
                $sentencia->bindParam(':caracteristicas', $caracteristicas, PDO::PARAM_STR);
                $sentencia->bindParam(':imagenes', $imagenes, PDO::PARAM_STR);
                $sentencia->bindParam(':videos', $videos, PDO::PARAM_STR);
                $sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
                $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
                $sentencia->bindParam(':departamento', $departamento, PDO::PARAM_STR);
                $sentencia->bindParam(':municipio', $municipio, PDO::PARAM_STR);
                $sentencia->bindParam(':direccion', $direccion, PDO::PARAM_STR);
                $sentencia->bindParam(':latitud', $latitud, PDO::PARAM_STR);
                $sentencia->bindParam(':longitud', $longitud, PDO::PARAM_STR);
                $sentencia->bindParam(':destacado', $destacado, PDO::PARAM_INT);
                $sentencia->bindParam(':premium', $premium, PDO::PARAM_INT);
                $sentencia->bindParam(':sugerido', $sugerido, PDO::PARAM_INT);
                $sentencia->bindParam(':terminos', $terminos, PDO::PARAM_INT);
                $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
                $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                $actualizado = $sentencia->execute();
            } catch (PDOException $ex) {
                // Manejo de errores
                throw new Exception('Error al actualizar el caballo: ' . $ex->getMessage());
            }
        }
        return $actualizado;
    }

    // Eliminar caballo por ID y usuario
    public static function eliminar_caballo_por_id($conexion, $id, $id_usuario = null)
    {
        if (isset($conexion)) {
            try {
                if ($id_usuario === null) {
                    $sql = "DELETE FROM caballos WHERE id = :id";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                } else {
                    $sql = "DELETE FROM caballos WHERE id = :id AND id_usuario = :id_usuario";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                    $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                }

                return $sentencia->execute() && $sentencia->rowCount() > 0;
            } catch (PDOException $ex) {
                throw new Exception('Error al eliminar el caballo: ' . $ex->getMessage());
            }
        }
        return false;
    }


    // Marcar caballo como vendido
    public static function marcar_caballo_vendido($conexion, $id, $id_usuario = null)
    {
        if (isset($conexion)) {
            try {
                if ($id_usuario === null) {
                    $sql = "UPDATE caballos SET vendido = 1 WHERE id = :id";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                } else {
                    $sql = "UPDATE caballos SET vendido = 1 WHERE id = :id AND id_usuario = :id_usuario";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                    $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                }

                return $sentencia->execute() && $sentencia->rowCount() > 0;
            } catch (PDOException $ex) {
                throw new Exception('Error al marcar el caballo como vendido: ' . $ex->getMessage());
            }
        }
        return false;
    }


    public static function marcar_caballo_disponible($conexion, $id, $id_usuario = null)
    {
        if (isset($conexion)) {
            try {
                if ($id_usuario === null) {
                    $sql = "UPDATE caballos SET vendido = 0 WHERE id = :id";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                } else {
                    $sql = "UPDATE caballos SET vendido = 0 WHERE id = :id AND id_usuario = :id_usuario";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                    $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                }

                return $sentencia->execute() && $sentencia->rowCount() > 0;
            } catch (PDOException $ex) {
                throw new Exception('Error al marcar el caballo como disponible: ' . $ex->getMessage());
            }
        }
        return false;
    }

    public static function contar_caballos_vendidos($conexion)
    {
        $total = 0;
        if (isset($conexion)) {
            try {
                $sql = "SELECT COUNT(*) FROM caballos WHERE vendido = 1";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $total = $sentencia->fetchColumn();
            } catch (PDOException $ex) {
                // Manejo de errores
            }
        }
        return $total;
    }

    public static function contar_proximos_a_vencer($conexion, $dias = 7)
    {
        $total = 0;
        if (isset($conexion)) {
            try {
                $sql = "SELECT COUNT(*) FROM caballos WHERE fecha_fin IS NOT NULL AND fecha_fin <= DATE_ADD(NOW(), INTERVAL :dias DAY) AND (vendido IS NULL OR vendido = 0)";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':dias', $dias, PDO::PARAM_INT);
                $sentencia->execute();
                $total = $sentencia->fetchColumn();
            } catch (PDOException $ex) {
                // Manejo de errores
            }
        }
        return $total;
    }
}
