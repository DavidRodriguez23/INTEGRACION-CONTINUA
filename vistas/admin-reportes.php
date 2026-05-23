    <?php
    include_once 'app/config.inc.php';
    include_once 'app/Conexion.inc.php';
    include_once 'app/RepositorioCaballo.inc.php';
    include_once 'app/RepositorioAnimal.inc.php';
    include_once 'plantillas/documento-apertura.inc.php';
    include_once 'plantillas/navbar.inc.php';

    Conexion::abrir_conexion();

    function contar_categorias_animales_y_caballos($animales, $caballos)
    {
        $conteo_categorias = [];

        foreach (array_merge($animales, $caballos) as $item) {
            $categoria = strtolower(trim($item->obtener_categoria()));

            if (!empty($categoria)) {
                if (!isset($conteo_categorias[$categoria])) {
                    $conteo_categorias[$categoria] = 1;
                } else {
                    $conteo_categorias[$categoria]++;
                }
            }
        }

        ksort($conteo_categorias); // Orden alfabético
        return $conteo_categorias;
    }

    // Datos
    $total_caballos = RepositorioCaballo::obtener_caballos_normales(Conexion::obtener_conexion());
    $total_animales = RepositorioAnimal::obtener_animales_normales(Conexion::obtener_conexion());

    $vendidos = RepositorioAnimal::contar_animales_vendidos(Conexion::obtener_conexion(), 'vendido') +
        RepositorioCaballo::contar_caballos_vendidos(Conexion::obtener_conexion(), 'vendido');

    $proximos_vencer = RepositorioAnimal::contar_proximos_a_vencer(Conexion::obtener_conexion(), 7) +
        RepositorioCaballo::contar_proximos_a_vencer(Conexion::obtener_conexion(), 7);

    $categorias_top = RepositorioAnimal::obtener_animal_sugerido(Conexion::obtener_conexion(), 5);

    $conteo_categorias = contar_categorias_animales_y_caballos($total_animales, $total_caballos);
    ?>

<br><br>
    <div class="container mt-5 admin-panel">
        <h2><i class="bi bi-speedometer2 me-2"></i>Dashboard General</h2>
        <hr class="divider">

        <!-- Tarjetas estadísticas -->
        <div class="row text-center mb-4">
            <div class="col-md-4">
                <div class="card shadow tarjeta-estadistica">
                    <div class="card-body">
                        <h5 class="card-title">Publicaciones Totales</h5>
                        <p class="fs-3"><?= count($total_caballos) + count($total_animales) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow tarjeta-estadistica">
                    <div class="card-body">
                        <h5 class="card-title">Vendidos</h5>
                        <p class="fs-3"><?= $vendidos ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow tarjeta-estadistica">
                    <div class="card-body">
                        <h5 class="card-title">Próx. a Vencer (7 días)</h5>
                        <p class="fs-3"><?= $proximos_vencer ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categorías populares -->
        <div class="mt-5">
            <h4><i class="bi bi-tags-fill me-2"></i>Categorías Populares y Distribución por Categoría</h4>
            <div class="row mt-3">
                <!-- Tabla Categorías -->
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($conteo_categorias as $nombre_categoria => $cantidad): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= ucfirst(htmlspecialchars($nombre_categoria)) ?>
                                <span class="badge bg-primary rounded-pill"><?= $cantidad ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Gráfico -->
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <div style="max-width: 400px; width: 100%;">
                        <canvas id="graficoCategorias" style="width: 100%; height: auto;"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
        <script>
            const ctx = document.getElementById('graficoCategorias').getContext('2d');

            const categorias = <?= json_encode(array_keys($conteo_categorias)) ?>;
            const cantidades = <?= json_encode(array_values($conteo_categorias)) ?>;

            const colores = categorias.map((_, i) => `hsl(${i * 45 % 360}, 70%, 60%)`);

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: categorias,
                    datasets: [{
                        label: 'Cantidad por categoría',
                        data: cantidades,
                        backgroundColor: colores,
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    plugins: {
                        datalabels: {
                            color: '#fff',
                            formatter: (value, ctx) => {
                                let total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let porcentaje = (value / total * 100).toFixed(1);
                                return porcentaje + '%';
                            },
                            font: {
                                weight: 'bold',
                                size: 14
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.parsed || 0;
                                    return `${label}: ${value} animales`;
                                }
                            }
                        }
                    }
                }
            });
        </script>


        <?php include_once 'plantillas/documento-cierre.inc.php'; ?>