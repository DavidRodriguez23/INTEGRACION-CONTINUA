<div class="container mt-5 mb-5">
    <div class="card shadow" style="border: 2px solid #bfa76f;">

        <div class="card-body" style="background-color: #f0e9d2;">
            <div class="mb-3">
                <label for="nombre" class="form-label text-success">Nombre completo</label>
                <input type="text" class="form-control" name="nombre">
            </div>
            <div class="mb-3">
                <label for="identificacion" class="form-label text-success">Identificación</label>
                <input type="text" class="form-control" name="identificacion">
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label text-success">Correo electrónico</label>
                <input type="email" class="form-control" name="correo">
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label text-success">Teléfono</label>
                <input type="text" class="form-control" name="telefono">
            </div>
            <div class="mb-3">
                <label for="tipo_insumos" class="form-label text-success">Tipo de Insumos</label>
                <select class="form-control" name="tipo_insumos">
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="Agropecuarios">Agropecuarios</option>
                    <option value="Maquinaria en general">Maquinaria en general</option>
                </select>
            </div>
            <div class="mb-3 position-relative">
                <label for="clave1" class="form-label text-success">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="clave1" name="clave1">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('clave1', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3 position-relative">
                <label for="clave2" class="form-label text-success">Repite tu contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="clave2" name="clave2">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('clave2', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-danger" name="enviar">Registrar</button>
        </div>
    </div>
</div>