<?php
include_once __DIR__ . '/../app/Conexion.inc.php';
?>

<footer style="background: linear-gradient(135deg, #f0e9d2 60%, #bfa76f 100%); padding: 48px 0 36px 0; margin-top: 60px;">
    <div class="container">
        <div class="text-center small" style="font-size: 1.15rem; line-height: 2.2;">
            <!-- Puedes poner aquí datos de contacto fijos si lo deseas -->
            <span class="me-4 d-inline-block">
                <i class="fas fa-envelope text-success"></i> contacto@ganandez.com
            </span>
            <span class="me-4 d-inline-block">
                <i class="fas fa-phone text-success"></i> +57 300 000 0000
            </span>
            <span class="me-4 d-inline-block">
                <i class="fas fa-map-marker-alt text-success"></i> Colombia
            </span>
            <span class="me-3 d-inline-block">
                <a href="#" class="text-success" target="_blank" rel="noopener">
                    <i class="fab fa-facebook fa-lg"></i>
                </a>
            </span>
            <span class="me-3 d-inline-block">
                <a href="#" class="text-success" target="_blank" rel="noopener">
                    <i class="fab fa-twitter fa-lg"></i>
                </a>
            </span>
            <span class="d-inline-block">
                <a href="#" class="text-success" target="_blank" rel="noopener">
                    <i class="fab fa-instagram fa-lg"></i>
                </a>
            </span>
        </div>

        <div class="text-center text-muted mt-3" style="font-size: 1.05rem;">
            &copy; <?= date('Y') ?> Ganandez. Todos los derechos reservados.
        </div>
    </div>
</footer>

<?php Conexion::cerrar_conexion(); ?>
<!-- Bootstrap JS (necesario para los modales y otros componentes interactivos) -->

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-Q6Ea3cn7V1bIu/BuT9xCvMzqwF1Bk4r+dF9cHoMC9V4PPxu3WDp2Qe9lRJWc+jTO" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>