    <?php
    $tarjetas_resultado = [];

    if (isset($_GET['tipoBusqueda']) && in_array($_GET['tipoBusqueda'], ['ganado', 'caballo'])) {
        Conexion::abrir_conexion();
        $conexion = Conexion::obtener_conexion();

        if ($_GET['tipoBusqueda'] === 'ganado') {
            $edadMinMeses = $_GET['edadGanadoMinMeses'] ?? '';
            $edadMaxMeses = $_GET['edadGanadoMaxMeses'] ?? '';
            $edadMinAnios = $_GET['edadGanadoMinAnios'] ?? '';
            $edadMaxAnios = $_GET['edadGanadoMaxAnios'] ?? '';

            // Filtros GANADO
            $categoria = $_GET['categoriaGanado'] ?? '';
            $raza = $_GET['razaGanado'] ?? '';
            $sexo = $_GET['sexoGanado'] ?? '';
            $edadMin = $_GET['edadGanadoMin'] ?? '';
            $edadMax = $_GET['edadGanadoMax'] ?? '';
            $pesoMin = $_GET['pesoMin'] ?? '';
            $pesoMax = $_GET['pesoMax'] ?? '';
            $precioMin = $_GET['precioMin'] ?? '';
            $precioMax = $_GET['precioMax'] ?? '';
            $departamento = $_GET['departamentoGanado'] ?? '';
            $municipio = $_GET['municipioGanado'] ?? '';
            $pureza = $_GET['pureza'] ?? '';

            $sql = "SELECT * FROM animales WHERE vendido = 0";
            $params = [];

            if ($categoria) {
                $sql .= " AND categoria = ?";
                $params[] = $categoria;
            }
            if ($raza) {
                $sql .= " AND raza LIKE ?";
                $params[] = "%$raza%";
            }
            if ($sexo) {
                $sql .= " AND sexo = ?";
                $params[] = $sexo;
            }
            if ($edadMin !== '') {
                $sql .= " AND edad >= ?";
                $params[] = $edadMin;
            }
            if ($edadMax !== '') {
                $sql .= " AND edad <= ?";
                $params[] = $edadMax;
            }
            if ($pesoMin !== '') {
                $sql .= " AND peso >= ?";
                $params[] = $pesoMin;
            }
            if ($pesoMax !== '') {
                $sql .= " AND peso <= ?";
                $params[] = $pesoMax;
            }
            if ($precioMin !== '') {
                $sql .= " AND precio >= ?";
                $params[] = $precioMin;
            }
            if ($precioMax !== '') {
                $sql .= " AND precio <= ?";
                $params[] = $precioMax;
            }
            if ($departamento) {
                $sql .= " AND departamento LIKE ?";
                $params[] = "%$departamento%";
            }
            if ($municipio) {
                $sql .= " AND municipio LIKE ?";
                $params[] = "%$municipio%";
            }
            if ($pureza) {
                $sql .= " AND pureza LIKE ?";
                $params[] = "%$pureza%";
            }

            $sql .= " ORDER BY id DESC LIMIT 100";
            $stmt = $conexion->prepare($sql);
            $stmt->execute($params);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Filtro de edad avanzado (meses/años)
            foreach ($resultados as $fila) {
                // Extraer valor y unidad de edad
                $edad_db = strtolower(trim($fila['edad']));
                $edad_valor = 0;
                if (strpos($edad_db, 'año') !== false) {
                    $edad_valor = (int)filter_var($edad_db, FILTER_SANITIZE_NUMBER_INT) * 12;
                } elseif (strpos($edad_db, 'mes') !== false) {
                    $edad_valor = (int)filter_var($edad_db, FILTER_SANITIZE_NUMBER_INT);
                }

                // Filtro por meses
                if ($edadMinMeses !== '' && $edad_valor < (int)$edadMinMeses) continue;
                if ($edadMaxMeses !== '' && $edad_valor > (int)$edadMaxMeses) continue;
                // Filtro por años (convertido a meses)
                if ($edadMinAnios !== '' && $edad_valor < ((int)$edadMinAnios * 12)) continue;
                if ($edadMaxAnios !== '' && $edad_valor > ((int)$edadMaxAnios * 12)) continue;

                // ...crea el objeto Animal como ya lo haces...
                $tarjetas_resultado[] = new Animal(
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
                    $fila['vendido']
                );
            }
        } else {
            // Filtros CABALLOS
            $categoria = $_GET['categoriaCaballo'] ?? '';
            $raza = $_GET['razaCaballo'] ?? '';
            $sexo = $_GET['sexoCaballo'] ?? '';
            $edadMin = $_GET['edadCaballoMin'] ?? '';
            $edadMax = $_GET['edadCaballoMax'] ?? '';
            $pesoMin = $_GET['pesoMin'] ?? '';
            $pesoMax = $_GET['pesoMax'] ?? '';
            $precioMin = $_GET['precioMin'] ?? '';
            $precioMax = $_GET['precioMax'] ?? '';
            $departamento = $_GET['departamentoCaballo'] ?? '';
            $municipio = $_GET['municipioCaballo'] ?? '';
            $caracteristicas = $_GET['caracteristicas'] ?? '';

            $sql = "SELECT * FROM caballos WHERE vendido = 0";

            $params = [];

            if ($categoria) {
                $sql .= " AND categoria = ?";
                $params[] = $categoria;
            }
            if ($raza) {
                $sql .= " AND raza LIKE ?";
                $params[] = "%$raza%";
            }
            if ($sexo) {
                $sql .= " AND sexo = ?";
                $params[] = $sexo;
            }
            if ($edadMin !== '') {
                $sql .= " AND edad >= ?";
                $params[] = $edadMin;
            }
            if ($edadMax !== '') {
                $sql .= " AND edad <= ?";
                $params[] = $edadMax;
            }
            if ($pesoMin !== '') {
                $sql .= " AND peso >= ?";
                $params[] = $pesoMin;
            }
            if ($pesoMax !== '') {
                $sql .= " AND peso <= ?";
                $params[] = $pesoMax;
            }
            if ($precioMin !== '') {
                $sql .= " AND precio >= ?";
                $params[] = $precioMin;
            }
            if ($precioMax !== '') {
                $sql .= " AND precio <= ?";
                $params[] = $precioMax;
            }
            if ($departamento) {
                $sql .= " AND departamento LIKE ?";
                $params[] = "%$departamento%";
            }
            if ($municipio) {
                $sql .= " AND municipio LIKE ?";
                $params[] = "%$municipio%";
            }
            if ($caracteristicas) {
                $sql .= " AND caracteristicas LIKE ?";
                $params[] = "%$caracteristicas%";
            }

            $sql .= " ORDER BY id DESC LIMIT 30";
            $stmt = $conexion->prepare($sql);
            $stmt->execute($params);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $edadMinMeses = $_GET['edadCaballoMinMeses'] ?? '';
            $edadMaxMeses = $_GET['edadCaballoMaxMeses'] ?? '';
            $edadMinAnios = $_GET['edadCaballoMinAnios'] ?? '';
            $edadMaxAnios = $_GET['edadCaballoMaxAnios'] ?? '';

            include_once 'app/Caballo.inc.php';
            foreach ($resultados as $fila) {
                // Extraer valor y unidad de edad
                $edad_db = strtolower(trim($fila['edad']));
                $edad_valor = 0;
                if (strpos($edad_db, 'año') !== false) {
                    $edad_valor = (int)filter_var($edad_db, FILTER_SANITIZE_NUMBER_INT) * 12;
                } elseif (strpos($edad_db, 'mes') !== false) {
                    $edad_valor = (int)filter_var($edad_db, FILTER_SANITIZE_NUMBER_INT);
                }

                // Filtro por meses
                if ($edadMinMeses !== '' && $edad_valor < (int)$edadMinMeses) continue;
                if ($edadMaxMeses !== '' && $edad_valor > (int)$edadMaxMeses) continue;
                // Filtro por años (convertido a meses)
                if ($edadMinAnios !== '' && $edad_valor < ((int)$edadMinAnios * 12)) continue;
                if ($edadMaxAnios !== '' && $edad_valor > ((int)$edadMaxAnios * 12)) continue;

                $tarjetas_resultado[] = new Caballo(
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
                    $fila['vendido']
                );
            }
        }
    }
    ?>