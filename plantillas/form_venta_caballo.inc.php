<div class="card-body fondo-registro">
    <!-- Planes personales -->
    <div class="card-header encabezado-degradado text-center">
        <h3 class="titulo-encabezado mb-2">
            <i class="fas fa-paw me-2"></i> Publicar caballo
        </h3>

        <p class="subtitulo-registro">Completa la información para publicar tu ejemplar</p>
    </div>
    <div class="card-body fondo-registro">
        <!-- Título -->
        <div class="mb-3">
            <label for="titulo" class="form-label text-success">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="50" placeholder="Ej. Novillo de engorde">
            <div class="d-flex justify-content-between">
                <small class="form-text text-muted">Máximo 50 caracteres.</small>
                <small id="titulo-counter" class="text-muted">50 restantes</small>
            </div>
        </div>

        <!-- Descripción -->
        <div class="mb-3">
            <label for="descripcion" class="form-label text-success">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required maxlength="300" placeholder="Describe tu animal..."></textarea>
            <div class="d-flex justify-content-between">
                <small class="form-text text-muted">Máximo 300 caracteres.</small>
                <small id="descripcion-counter" class="text-muted">300 restantes</small>
            </div>
        </div>


        <!-- Categoría -->
        <div class="mb-3">
            <label for="categoria" class="form-label text-success">Categoría</label>
            <select class="form-select" id="categoria" name="categoria" required>
                <option value="">Selecciona una categoría</option>
                <option value="Caballo Criollo Colombiano">Caballo Criollo Colombiano</option>
                <option value="Caballo Percherón">Caballo Percherón</option>
                <option value="Caballo Árabe">Caballo Árabe</option>
                <option value="Cuarto de Milla">Cuarto de Milla</option>
                <option value="Caballos Mulares">Caballos Mulares</option>
                <option value="Otros">Otros</option>
            </select>
            <small class="form-text text-muted">Elige la categoría que mejor describa tu animal (Carne, Leche, o Doble propósito).</small>
        </div>

        <!-- Raza -->
        <div class="mb-3">
            <label for="raza" class="form-label text-success">Raza</label>
            <select class="form-select" id="raza" name="raza" required>
                <option value="">Selecciona una raza</option>
            </select>
            <small class="form-text text-muted">Selecciona la raza del animal (si no está en la lista, puedes añadirla en la descripción).</small>
        </div>
        <!-- Sexo (radio) -->
        <div class="mb-3">
            <label class="form-label text-success">Sexo</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sexo" id="Macho castrado" value="Macho castrado" required>
                <label class="form-check-label" for="macho">Macho castrado</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sexo" id="Macho entero" value="Macho entero">
                <label class="form-check-label" for="hembra">Macho entero</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sexo" id="macho" value="macho" required>
                <label class="form-check-label" for="macho">Macho</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sexo" id="hembra" value="hembra">
                <label class="form-check-label" for="hembra">Hembra</label>
            </div>
            <small class="form-text text-muted">Selecciona el sexo del animal.</small>
        </div>
        <!-- Edad -->
        <div class="mb-3">
            <label for="edad" class="form-label text-success">Edad</label>
            <select class="form-select" id="edad" name="edad" required>
                <option value="">Selecciona la edad</option>
                <optgroup label="Meses">
                    <?php for ($i = 1; $i <= 12; $i++) {
                        $texto = $i . ' ' . ($i == 1 ? 'mes' : 'meses');
                        echo "<option value='{$texto}'>{$texto}</option>";
                    } ?>
                </optgroup>
                <optgroup label="Años">
                    <?php for ($i = 1; $i <= 6; $i++) {
                        $texto = $i . ' ' . ($i == 1 ? 'año' : 'años');
                        echo "<option value='{$texto}'>{$texto}</option>";
                    } ?>
                    <option value="Más de 6 años">Más de 6 años</option>
                </optgroup>
            </select>
            <small class="form-text text-muted">Indica la edad del animal en meses o años.</small>
        </div>

        <!-- Peso -->
        <div class="mb-3">
            <label for="peso" class="form-label text-success">Peso (kg)</label>
            <input type="number" class="form-control" id="peso" name="peso" required placeholder="Ej. 450">
            <small class="form-text text-muted">Introduce el peso del animal en kilogramos.</small>
        </div>

        <!-- Precio -->
        <div class="mb-3">
            <label for="precio" class="form-label text-success">Precio (COP)</label>
            <input type="text" class="form-control" id="precio" name="precio" required placeholder="Ej. 1200000">
            <small class="form-text text-muted">Indica el precio en COP para tu animal.</small>
        </div>

        <!-- Tipo de precio (radio) -->
        <div class="mb-3">
            <label class="form-label text-success">Características del caballo <span class="text-muted">(máx. 3)</span></label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="caracteristicas[]" id="caracteristica1" value="Manso">
                <label class="form-check-label" for="caracteristica1">Manso</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="caracteristicas[]" id="caracteristica2" value="Apto para niños">
                <label class="form-check-label" for="caracteristica2">Apto para niños</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="caracteristicas[]" id="caracteristica3" value="Buen paso">
                <label class="form-check-label" for="caracteristica3">Buen paso</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="caracteristicas[]" id="caracteristica4" value="Fácil de montar">
                <label class="form-check-label" for="caracteristica4">Fácil de montar</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="caracteristicas[]" id="caracteristica5" value="Ideal para trabajo">
                <label class="form-check-label" for="caracteristica5">Ideal para trabajo</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="caracteristicas[]" id="caracteristica6" value="Buen pedigree">
                <label class="form-check-label" for="caracteristica6">Buen pedigree</label>
            </div>
            <small class="form-text text-muted d-block mt-2">Marca hasta 3 características que describan mejor tu caballo.</small>
        </div>
        <!-- Campo para subir imágenes -->
        <div class="mb-3">
            <label for="fotos" class="form-label text-success">Fotos del animal</label>
            <input
                type="file"
                class="form-control"
                id="fotos"
                name="fotos[]"
                accept="image/jpeg,image/png,image/gif"
                multiple
                onchange="mostrarVistaPrevia(this)">
            <small id="ayuda-fotos" class="form-text text-muted">
                Puedes subir hasta 10 imágenes (JPG, PNG, GIF). Tamaño máximo por imagen: <strong>2MB</strong>.
            </small>

            <div id="preview-fotos" class="mt-3 d-flex flex-wrap gap-2"></div>
        </div>


        <!-- Campo para subir videos -->
        <div class="mb-3" id="campo-video">
            <label for="videos" class="form-label text-success">Video del animal</label>
            <input
                type="file"
                class="form-control"
                id="videos"
                name="videos[]"
                accept="video/mp4,video/avi,video/quicktime"
                onchange="mostrarVistaPreviaVideo(this)">
            <small id="ayuda-video" class="form-text text-muted">
                Solo se permite subir un video (MP4, AVI, MOV). Tamaño máximo: <strong>50MB</strong>.
            </small>

            <div id="preview-video" class="mt-3"></div>
        </div>
        <!-- Contacto -->
        <div class="mb-3">
            <label for="telefono" class="form-label text-success">Teléfono</label>
            <input type="tel" class="form-control" id="telefono" name="telefono" required>
            <small class="form-text text-muted">Escribe tu número de teléfono para que los compradores puedan contactarte.</small>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label text-success">Correo electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
            <small class="form-text text-muted">Introduce tu correo electrónico para que podamos enviarte notificaciones.</small>
        </div>


        <!-- Ubicación -->
        <div class="mb-3">
            <label for="departamento" class="form-label text-success">Departamento</label>
            <input type="text" class="form-control" id="departamento" name="departamento" required>
            <small class="form-text text-muted">Indica el departamento donde se encuentra el animal.</small>
        </div>
        <div class="mb-3">
            <label for="municipio" class="form-label text-success">Municipio</label>
            <input type="text" class="form-control" id="municipio" name="municipio" required>
            <small class="form-text text-muted">Escribe el municipio donde resides o donde se encuentra el animal.</small>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label text-success">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" required>
            <small class="form-text text-muted">Introduce la dirección exacta donde el animal se encuentra.</small>
        </div>
        <div class="mb-3">
            <label class="form-label text-success">Ubicación en el mapa</label>
            <div id="map" style="height: 400px;"></div>
            <small class="form-text text-muted">Puedes mover el marcador para indicar la ubicación exacta del animal.</small>
            <input type="hidden" name="latitud" id="latitud">
            <input type="hidden" name="longitud" id="longitud">
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="destacado" name="destacado">
            <label class="form-check-label" for="destacado">¿Deseas que tu publicación esté en <strong>DESTACADOS</strong>?</label>
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="premium" name="premium">
            <label class="form-check-label" for="premium">¿Deseas que tu publicación esté en <strong>PREMIUM</strong>?</label>
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="sugerido" name="sugerido">
            <label class="form-check-label" for="su">¿Deseas que tu publicación esté en <strong>SUGERIDOS</strong>?</label>
        </div>

        <!-- Términos -->
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="terminos" name="terminos" required>
            <label class="form-check-label text-success" for="terminos">
                Acepto los <a href="#" class="text-danger fw-bold">términos y condiciones</a>
            </label>
            <small class="form-text text-muted">Es importante que leas y aceptes los términos y condiciones para continuar.</small>
        </div>

        <!-- Comisión y datos bancarios -->
        <div id="info-comision" style="display:none;" class="alert alert-info mt-4 border border-warning shadow-lg p-4 rounded-4 bg-light animate-fade-in">
            <h5 class="mb-3 text-success fw-bold">
                <i class="fas fa-university me-2"></i>Información bancaria para la transferencia
            </h5>

            <ul class="mb-3 list-unstyled ps-2">
                <li><strong>Banco:</strong> Bancolombia</li>
                <li><strong>Número de cuenta:</strong> 1234567890</li>
                <li><strong>Tipo de cuenta:</strong> Ahorros</li>
                <li><strong>Titular:</strong> Ganandez S.A.S</li>
            </ul>

            <!-- Sección: Certificación Bancaria -->
            <div class="mb-4 border-start border-4 ps-3 border-success">
                <label class="form-label fw-semibold mb-2">
                    <i class="fas fa-file-pdf me-2 text-danger"></i>
                    Ver certificación bancaria oficial:
                </label>
                <a href="/documentos/CertificacionGanandez.pdf" target="_blank" class="btn btn-outline-success btn-sm shadow-sm">
                    <i class="fas fa-download me-1"></i> Descargar Certificación
                </a>
            </div>

            <!-- Comisión -->
            <div class="mb-4 p-3 bg-white border-start border-4 border-success shadow-sm rounded-3">
                <p class="mb-1 fs-5 text-dark fw-semibold">
                    Comisión a transferir:
                </p>
                <p class="fs-4 text-success fw-bold mb-0" id="comision-monto"></p>
                <small class="text-muted">Corresponde al <span id="comision-porcentaje"></span> del valor publicado.</small>
            </div>

            <!-- Subida de comprobante -->
            <div class="mb-4">
                <label for="soporte_pago" class="form-label fw-semibold">Adjunta el comprobante de pago (formato PDF):</label>
                <input type="file" class="form-control border border-success shadow-sm" name="soporte_pago" id="soporte_pago" accept="application/pdf">
            </div>

            <!-- Instrucciones -->
            <div class="alert alert-warning mt-4 border-2 border-warning rounded-3 premium-instrucciones">
                <h6 class="fw-bold text-danger mb-2"><i class="fas fa-info-circle me-2"></i>Proceso de Validación</h6>
                <ul class="mb-2">
                    <li>Una vez confirmada la transferencia, tu publicación será activada en la plataforma.</li>
                    <li>La validación puede tardar hasta <strong>24 horas hábiles</strong>.</li>
                    <li>Recibirás una notificación por correo electrónico cuando esté activa.</li>
                </ul>
                <span class="text-success fw-bold">Gracias por confiar en Ganandez.</span>
            </div>
        </div>


        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#edad').select2({
                    width: '100%',
                    dropdownAutoWidth: true,
                    placeholder: 'Selecciona la edad',
                    allowClear: true
                });
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const precioInput = document.getElementById("precio");
                const planRadios = document.querySelectorAll("input[name='plan']");
                const infoComision = document.getElementById("info-comision");
                const comisionMonto = document.getElementById("comision-monto");
                const comisionPorcentaje = document.getElementById("comision-porcentaje");
                const soportePago = document.getElementById("soporte_pago");

                const tasas = {
                    personal2: 0.03,
                    personal3: 0.06,
                    ganaderos1: 0.03,
                    ganaderos2: 0.06
                };

                function actualizarComision() {
                    let plan = "";
                    planRadios.forEach(radio => {
                        if (radio.checked) plan = radio.value;
                    });
                    let precio = parseFloat(precioInput.value.replace(/[^\d]/g, ''));
                    let porcentaje = tasas[plan] || 0;

                    if (!isNaN(precio) && precio > 0 && porcentaje > 0) {
                        let comision = Math.floor(precio * porcentaje);
                        comisionMonto.textContent = comision.toLocaleString('es-CO', {
                            style: 'currency',
                            currency: 'COP'
                        });
                        comisionPorcentaje.textContent = (porcentaje * 100) + "%";
                        infoComision.style.display = "block";
                        soportePago.required = true;
                    } else {
                        infoComision.style.display = "none";
                        soportePago.required = false;
                    }
                }

                planRadios.forEach(radio => {
                    radio.addEventListener("change", actualizarComision);
                });
                precioInput.addEventListener("input", actualizarComision);
            });
        </script>