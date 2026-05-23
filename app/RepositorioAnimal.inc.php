<?php
include_once 'Animal.inc.php';
include_once 'RepositorioCaballo.inc.php'; // Asegúrate de incluir el repositorio de caballos

class RepositorioAnimal
{

    // Función para insertar un nuevo animal en la base de datos
    public static function insertar_animal($conexion, $animal)
    {
        $animal_insertado = false;
        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO animales (titulo, descripcion, categoria, raza, pureza, sexo, tipo_animal, edad, peso, precio, tipo_precio, telefono, correo, departamento, municipio, direccion, destacado, premium, imagenes, videos, sugerido, fecha_fin, latitud, longitud, id_usuario, soporte_pago, valor_comision)
                        VALUES (:titulo, :descripcion, :categoria, :raza, :pureza, :sexo, :tipo_animal, :edad, :peso, :precio, :tipo_precio, :telefono, :correo, :departamento, :municipio, :direccion, :destacado, :premium, :imagenes, :videos, :sugerido, :fecha_fin, :latitud, :longitud, :id_usuario, :soporte_pago, :valor_comision)";

                $sentencia = $conexion->prepare($sql);

                $titulo = $animal->obtener_titulo();
                $descripcion = $animal->obtener_descripcion();
                $categoria = $animal->obtener_categoria();
                $raza = $animal->obtener_raza();
                $pureza = $animal->obtener_pureza();
                $sexo = $animal->obtener_sexo();
                $tipo_animal = $animal->obtener_tipo_animal();
                $edad = $animal->obtener_edad();
                $peso = $animal->obtener_peso();
                $precio = $animal->obtener_precio();
                $tipo_precio = $animal->obtener_tipo_precio();
                $telefono = $animal->obtener_telefono();
                $correo = $animal->obtener_correo();
                $departamento = $animal->obtener_departamento();
                $municipio = $animal->obtener_municipio();
                $direccion = $animal->obtener_direccion();
                $destacado = $animal->esta_destacado();
                $premium = $animal->es_premium();
                $imagenes_json = json_encode($animal->obtener_imagenes());
                $videos_json = json_encode($animal->obtener_videos());
                $sugerido = $animal->es_sugerido();
                $fecha_fin = $animal->obtener_fecha_fin();
                $latitud = $animal->obtener_latitud();
                $longitud = $animal->obtener_longitud();
                $id_usuario = $animal->obtener_id_usuario();
                $soporte_pago = $animal->obtener_soporte_pago();
                $valor_comision = $animal->obtener_valor_comision();


                $sentencia->bindParam(':titulo', $titulo, PDO::PARAM_STR);
                $sentencia->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $sentencia->bindParam(':categoria', $categoria, PDO::PARAM_STR);
                $sentencia->bindParam(':raza', $raza, PDO::PARAM_STR);
                $sentencia->bindParam(':pureza', $pureza, PDO::PARAM_STR);
                $sentencia->bindParam(':sexo', $sexo, PDO::PARAM_STR);
                $sentencia->bindParam(':tipo_animal', $tipo_animal, PDO::PARAM_STR);
                $sentencia->bindParam(':edad', $edad, PDO::PARAM_STR);
                $sentencia->bindParam(':peso', $peso, PDO::PARAM_STR);
                $sentencia->bindParam(':precio', $precio, PDO::PARAM_STR);
                $sentencia->bindParam(':tipo_precio', $tipo_precio, PDO::PARAM_STR);
                $sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
                $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
                $sentencia->bindParam(':departamento', $departamento, PDO::PARAM_STR);
                $sentencia->bindParam(':municipio', $municipio, PDO::PARAM_STR);
                $sentencia->bindParam(':direccion', $direccion, PDO::PARAM_STR);
                $sentencia->bindParam(':destacado', $destacado, PDO::PARAM_INT);
                $sentencia->bindParam(':premium', $premium, PDO::PARAM_INT);
                $sentencia->bindParam(':imagenes', $imagenes_json, PDO::PARAM_STR);
                $sentencia->bindParam(':videos', $videos_json, PDO::PARAM_STR);
                $sentencia->bindParam(':sugerido', $sugerido, PDO::PARAM_INT);
                $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
                $sentencia->bindParam(':latitud', $latitud, PDO::PARAM_STR);
                $sentencia->bindParam(':longitud', $longitud, PDO::PARAM_STR);
                $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $sentencia->bindParam(':soporte_pago', $soporte_pago, PDO::PARAM_STR);
                $sentencia->bindParam(':valor_comision', $valor_comision, PDO::PARAM_STR);



                $animal_insertado = $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        if ($animal_insertado) {
            return $conexion->lastInsertId();
        }

        return false;
    }


    // Función para obtener un animal por su ID
    public static function obtener_animal_por_id($conexion, $id)
    {
        $animal = null;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM animales WHERE id = :id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                $sentencia->execute();
                $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

                if ($resultado) {
                    $animal = new Animal(
                        $resultado['id'],
                        $resultado['titulo'],
                        $resultado['descripcion'],
                        $resultado['categoria'],
                        $resultado['raza'],
                        $resultado['pureza'],
                        $resultado['sexo'],
                        $resultado['tipo_animal'],
                        $resultado['edad'],
                        $resultado['peso'],
                        $resultado['precio'],
                        $resultado['tipo_precio'],
                        $resultado['telefono'],
                        $resultado['correo'],
                        $resultado['departamento'],
                        $resultado['municipio'],
                        $resultado['direccion'],
                        $resultado['destacado'],
                        $resultado['premium'],
                        json_decode($resultado['imagenes'], true),
                        json_decode($resultado['videos'], true),
                        $resultado['sugerido'],
                        $resultado['fecha_fin'],
                        $resultado['latitud'],
                        $resultado['longitud'],
                        $resultado['id_usuario'],
                        $resultado['vendido'],
                        $resultado['soporte_pago'] ?? null,
                        $resultado['valor_comision'] ?? null
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $animal;
    }
    // Ejemplo para obtener animales y caballos sugeridos
    public static function obtener_animal_sugerido($conexion)
    {
        $animales = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM animales WHERE sugerido = 1 ORDER BY creado_en DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll();
                foreach ($resultados as $fila) {
                    $animales[] = new Animal(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['pureza'],
                        $fila['sexo'],
                        $fila['tipo_animal'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['tipo_precio'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['destacado'],
                        $fila['premium'],
                        json_decode($fila['imagenes'], true),
                        json_decode($fila['videos'], true),
                        $fila['sugerido'],
                        $fila['fecha_fin'],
                        $fila['latitud'],
                        $fila['longitud'],
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
        return $animales;
    }

    public static function obtener_animal_premium($conexion)
    {
        $animales = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM animales WHERE premium = 1 ORDER BY creado_en DESC"; // Más recientes primero
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $fila) {
                    $animales[] = new Animal(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['pureza'],
                        $fila['sexo'],
                        $fila['tipo_animal'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['tipo_precio'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['destacado'],
                        $fila['premium'],
                        json_decode($fila['imagenes'], true),
                        json_decode($fila['videos'], true),
                        $fila['sugerido'],
                        $fila['fecha_fin'],
                        $fila['latitud'],
                        $fila['longitud'],
                        $fila['id_usuario'],
                        $fila['vendido'],
                        $fila['soporte_pago'] ?? null,
                        $fila['valor_comision'] ?? null
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $animales;
    }

    public static function obtener_animal_destacado($conexion)
    {
        $animales = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM animales WHERE destacado = 1 ORDER BY creado_en DESC"; // Más recientes primero
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $fila) {
                    $animales[] = new Animal(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['pureza'],
                        $fila['sexo'],
                        $fila['tipo_animal'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['tipo_precio'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['destacado'],
                        $fila['premium'],
                        json_decode($fila['imagenes'], true),
                        json_decode($fila['videos'], true),
                        $fila['sugerido'],
                        $fila['fecha_fin'],
                        $fila['latitud'],
                        $fila['longitud'],
                        $fila['id_usuario'],
                        $fila['vendido'],
                        $fila['soporte_pago'] ?? null,
                        $fila['valor_comision'] ?? null
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $animales;
    }

    public static function obtener_animales_normales($conexion)
    {
        $animales = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM animales ORDER BY creado_en DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $fila) {
                    $animales[] = new Animal(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['pureza'],
                        $fila['sexo'],
                        $fila['tipo_animal'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['tipo_precio'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['destacado'],
                        $fila['premium'],
                        json_decode($fila['imagenes'], true),
                        json_decode($fila['videos'], true),
                        $fila['sugerido'],
                        $fila['fecha_fin'],
                        $fila['latitud'],
                        $fila['longitud'],
                        $fila['id_usuario'],
                        $fila['vendido'],
                        $fila['soporte_pago'] ?? null,
                        $fila['valor_comision'] ?? null
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $animales;
    }

    public static function eliminar_anuncios_vencidos($conexion)
    {
        if (isset($conexion)) {
            try {
                // Solo elimina anuncios vencidos que NO estén marcados como vendidos
                $sql = "DELETE FROM animales WHERE fecha_fin IS NOT NULL AND fecha_fin < NOW() AND (vendido IS NULL OR vendido = 0)";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception('Error al eliminar anuncios vencidos: ' . $ex->getMessage());
            }
        }
    }

    public static function obtener_animales_por_usuario($conexion, $id_usuario)
    {
        $animales = [];
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM animales WHERE id_usuario = :id_usuario ORDER BY creado_en DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $sentencia->execute();
                $resultados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $fila) {
                    $animales[] = new Animal(
                        $fila['id'],
                        $fila['titulo'],
                        $fila['descripcion'],
                        $fila['categoria'],
                        $fila['raza'],
                        $fila['pureza'],
                        $fila['sexo'],
                        $fila['tipo_animal'],
                        $fila['edad'],
                        $fila['peso'],
                        $fila['precio'],
                        $fila['tipo_precio'],
                        $fila['telefono'],
                        $fila['correo'],
                        $fila['departamento'],
                        $fila['municipio'],
                        $fila['direccion'],
                        $fila['destacado'],
                        $fila['premium'],
                        json_decode($fila['imagenes'], true),
                        json_decode($fila['videos'], true),
                        $fila['sugerido'],
                        $fila['fecha_fin'],
                        $fila['latitud'],
                        $fila['longitud'],
                        $fila['id_usuario'],
                        $fila['vendido'],
                        $fila['soporte_pago'] ?? null,
                        $fila['valor_comision'] ?? null
                    );
                }
            } catch (PDOException $ex) {
                throw new Exception('Error al preparar la consulta: ' . $ex->getMessage());
            }
        }
        return $animales;
    }

    public static function actualizar_contacto_y_edad($conexion, $id, $telefono, $correo, $edad)
    {
        if (isset($conexion)) {
            try {
                $sql = "UPDATE animales SET telefono = :telefono, correo = :correo, edad = :edad WHERE id = :id";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
                $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
                $sentencia->bindParam(':edad', $edad, PDO::PARAM_STR);
                $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                return $sentencia->execute();
            } catch (PDOException $ex) {
                throw new Exception('Error al actualizar contacto y edad: ' . $ex->getMessage());
            }
        }
        return false;
    }


    public static function actualizar_animal_admin($conexion, $animal)
    {
        if (isset($conexion)) {
            try {
                $sql = "UPDATE animales SET 
                    titulo = :titulo, 
                    descripcion = :descripcion, 
                    categoria = :categoria, 
                    raza = :raza, 
                    pureza = :pureza, 
                    sexo = :sexo, 
                    tipo_animal = :tipo_animal, 
                    edad = :edad, 
                    peso = :peso, 
                    precio = :precio, 
                    tipo_precio = :tipo_precio, 
                    telefono = :telefono, 
                    correo = :correo, 
                    departamento = :departamento, 
                    municipio = :municipio, 
                    direccion = :direccion, 
                    destacado = :destacado, 
                    premium = :premium, 
                    imagenes = :imagenes, 
                    videos = :videos, 
                    sugerido = :sugerido, 
                    fecha_fin = :fecha_fin, 
                    latitud = :latitud, 
                    longitud = :longitud 
                    WHERE id = :id";

                $sentencia = $conexion->prepare($sql);

                // --- Store values in local variables before binding ---
                $titulo = $animal->obtener_titulo();
                $descripcion = $animal->obtener_descripcion();
                $categoria = $animal->obtener_categoria();
                $raza = $animal->obtener_raza();
                $pureza = $animal->obtener_pureza();
                $sexo = $animal->obtener_sexo();
                $tipo_animal = $animal->obtener_tipo_animal();
                $edad = $animal->obtener_edad();
                $peso = $animal->obtener_peso();
                $precio = $animal->obtener_precio();
                $tipo_precio = $animal->obtener_tipo_precio();
                $telefono = $animal->obtener_telefono();
                $correo = $animal->obtener_correo();
                $departamento = $animal->obtener_departamento();
                $municipio = $animal->obtener_municipio();
                $direccion = $animal->obtener_direccion();
                $destacado = $animal->esta_destacado();
                $premium = $animal->es_premium();
                $imagenes = $animal->obtener_imagenes();
                $videos = $animal->obtener_videos();
                $sugerido = $animal->es_sugerido();
                $fecha_fin = $animal->obtener_fecha_fin();
                $latitud = $animal->obtener_latitud();
                $longitud = $animal->obtener_longitud();
                $id = $animal->obtener_id(); // Don't forget the ID for the WHERE clause

                $sentencia->bindParam(':titulo', $titulo, PDO::PARAM_STR);
                $sentencia->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $sentencia->bindParam(':categoria', $categoria, PDO::PARAM_STR);
                $sentencia->bindParam(':raza', $raza, PDO::PARAM_STR);
                $sentencia->bindParam(':pureza', $pureza, PDO::PARAM_STR);
                $sentencia->bindParam(':sexo', $sexo, PDO::PARAM_STR);
                $sentencia->bindParam(':tipo_animal', $tipo_animal, PDO::PARAM_STR);
                $sentencia->bindParam(':edad', $edad, PDO::PARAM_STR);
                $sentencia->bindParam(':peso', $peso, PDO::PARAM_STR);
                $sentencia->bindParam(':precio', $precio, PDO::PARAM_STR);
                $sentencia->bindParam(':tipo_precio', $tipo_precio, PDO::PARAM_STR);
                $sentencia->bindParam(':telefono', $telefono, PDO::PARAM_STR);
                $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
                $sentencia->bindParam(':departamento', $departamento, PDO::PARAM_STR);
                $sentencia->bindParam(':municipio', $municipio, PDO::PARAM_STR);
                $sentencia->bindParam(':direccion', $direccion, PDO::PARAM_STR);
                $sentencia->bindParam(':destacado', $destacado, PDO::PARAM_INT);
                $sentencia->bindParam(':premium', $premium, PDO::PARAM_INT);
                $sentencia->bindParam(':imagenes', $imagenes, PDO::PARAM_STR);
                $sentencia->bindParam(':videos', $videos, PDO::PARAM_STR);
                $sentencia->bindParam(':sugerido', $sugerido, PDO::PARAM_INT);
                $sentencia->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
                $sentencia->bindParam(':latitud', $latitud, PDO::PARAM_STR);
                $sentencia->bindParam(':longitud', $longitud, PDO::PARAM_STR);
                $sentencia->bindParam(':id', $id, PDO::PARAM_INT);

                return $sentencia->execute();
            } catch (PDOException $ex) {
                print "Error en actualizar_animal_admin: " . $ex->getMessage();
                return false;
            }
        } else {
            return false;
        }
    }

    public static function eliminar_animal_por_id($conexion, $id, $id_usuario = null)
    {
        if (isset($conexion)) {
            try {
                if ($id_usuario === null) {
                    // Admin puede eliminar sin validar dueño
                    $sql = "DELETE FROM animales WHERE id = :id";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                } else {
                    // Usuario común: solo si es dueño
                    $sql = "DELETE FROM animales WHERE id = :id AND id_usuario = :id_usuario";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                    $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                }

                return $sentencia->execute() && $sentencia->rowCount() > 0;
            } catch (PDOException $ex) {
                throw new Exception('Error al eliminar el animal: ' . $ex->getMessage());
            }
        }
        return false;
    }

    public static function marcar_animal_vendido($conexion, $id, $id_usuario = null)
    {
        if (isset($conexion)) {
            try {
                if ($id_usuario === null) {
                    $sql = "UPDATE animales SET vendido = 1 WHERE id = :id";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                } else {
                    $sql = "UPDATE animales SET vendido = 1 WHERE id = :id AND id_usuario = :id_usuario";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                    $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                }

                return $sentencia->execute() && $sentencia->rowCount() > 0;
            } catch (PDOException $ex) {
                throw new Exception('Error al marcar el animal como vendido: ' . $ex->getMessage());
            }
        }
        return false;
    }

    public static function marcar_animal_disponible($conexion, $id, $id_usuario = null)
    {
        if (isset($conexion)) {
            try {
                if ($id_usuario === null) {
                    $sql = "UPDATE animales SET vendido = 0 WHERE id = :id";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                } else {
                    $sql = "UPDATE animales SET vendido = 0 WHERE id = :id AND id_usuario = :id_usuario";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
                    $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                }

                return $sentencia->execute() && $sentencia->rowCount() > 0;
            } catch (PDOException $ex) {
                throw new Exception('Error al marcar el animal como disponible: ' . $ex->getMessage());
            }
        }
        return false;
    }

    public static function contar_animales_vendidos($conexion)
    {
        if (isset($conexion)) {
            try {
                $sql = "SELECT COUNT(*) FROM animales WHERE vendido = 1";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();
                return $sentencia->fetchColumn();
            } catch (PDOException $ex) {
                throw new Exception('Error al contar animales vendidos: ' . $ex->getMessage());
            }
        }
        return 0;
    }

    public static function contar_proximos_a_vencer($conexion, $dias = 7)
    {
        if (isset($conexion)) {
            try {
                $sql = "SELECT COUNT(*) FROM animales WHERE fecha_fin IS NOT NULL AND fecha_fin BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL :dias DAY)";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':dias', $dias, PDO::PARAM_INT);
                $sentencia->execute();
                return $sentencia->fetchColumn();
            } catch (PDOException $ex) {
                throw new Exception('Error al contar próximos a vencer: ' . $ex->getMessage());
            }
        }
        return 0;
    }
}
