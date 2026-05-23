<?php

include_once 'app/RepositorioUsuario.inc.php';

class ValidadorRegistro
{

    private $aviso_inicio;
    private $aviso_cierre;

    private $nombre;
    private $identificacion;
    private $correo;
    private $telefono;
    private $clave1;
    private $clave2;
    private $tipo_insumos;

    private $error_nombre;
    private $error_identificacion;
    private $error_correo;
    private $error_telefono;
    private $error_clave1;
    private $error_clave2;
    private $error_tipo_insumos;

    public function __construct($nombre, $identificacion, $correo, $telefono, $clave1, $clave2, $tipo_insumos, $conexion)
    {

        $this->aviso_inicio = '<div class="alert alert-danger" role="alert">';
        $this->aviso_cierre = '</div>';

        $this->nombre = $nombre;
        $this->identificacion = $identificacion;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->clave1 = $clave1;
        $this->clave2 = $clave2;
        $this->tipo_insumos = $tipo_insumos;

        $this->error_nombre = $this->validar_nombre($conexion, $nombre);
        $this->error_identificacion = $this->validar_identificacion($conexion, $identificacion);
        $this->error_correo = $this->validar_correo($conexion, $correo);
        $this->error_telefono = $this->validar_telefono($conexion, $telefono);
        $this->error_clave1 = $this->validar_clave1($clave1);
        $this->error_clave2 = $this->validar_clave2($clave1, $clave2);
        $this->error_tipo_insumos = $this->validar_tipo_insumos($conexion, $tipo_insumos);

        if ($this->error_clave1 == '' && $this->error_clave2 == '') {
            $this->clave1 = $clave1;
        }
    }

    protected function variable_iniciada($variable)
    {
        return isset($variable) && !empty($variable);
    }

    protected function validar_nombre($conexion, $nombre)
    {
        if (!$this->variable_iniciada($nombre)) {
            return "Por favor, introduce un nombre";
        }
        if (strlen($nombre) < 3) {
            return "El nombre debe tener al menos 3 caracteres";
        }
        return '';
    }

    protected function validar_identificacion($conexion, $identificacion)
    {
        if (!$this->variable_iniciada($identificacion)) {
            return "Por favor, introduce una identificación";
        }
        if (strlen($identificacion) < 6) {
            return "La identificación no es válida";
        }
        if (RepositorioUsuario::identificacion_existe($conexion, $identificacion)) {
            return "Esta identificación ya está en uso. Por favor, <a href='" . RUTA_LOGIN . "'>Inicie sesión</a>";
        }
        return '';
    }

    protected function validar_correo($conexion, $correo)
    {
        if (!$this->variable_iniciada($correo)) {
            return "Debe proporcionar un email.";
        }
        // Validación básica de email
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return "El email no es válido.";
        }

        if (RepositorioUsuario::correo_usuario_existe($conexion, $correo)) {
            return "Este correo ya está en uso. Por favor, <a href='" . RUTA_LOGIN . "'>Inicie sesión</a>";
        }

        return "";
    }

    protected function validar_telefono($conexion, $telefono)
    {
        if (!$this->variable_iniciada($telefono)) {
            return "Debe proporcionar un teléfono.";
        }
        // Validación básica de teléfono
        if (strlen($telefono) < 5) {
            return "El teléfono no es válido.";
        }
        return "";
    }

    protected function validar_clave1($clave1)
    {
        if (!$this->variable_iniciada($clave1)) {
            return "Debe proporcionar una contraseña.";
        }
        if (strlen($clave1) < 6) {
            return "La contraseña debe ser más larga que 6 caracteres.";
        }
        return "";
    }
    protected function validar_clave2($clave1, $clave2)
    {
        if (!$this->variable_iniciada($clave2)) {
            return "Debe repetir la contraseña.";
        }
        if ($clave1 != $clave2) {
            return "Las contraseñas no coinciden.";
        }
        return "";
    }

    protected function validar_tipo_insumos($tipo_insumos)
    {
        if (!$this->variable_iniciada($tipo_insumos)) {
            return "Por favor, selecciona un tipo de insumo.";
        }
        return "";
    }

    public function obtener_nombre()
    {
        return $this->nombre;
    }
    public function obtener_identificacion()
    {
        return $this->identificacion;
    }
    public function obtener_correo()
    {
        return $this->correo;
    }
    public function obtener_telefono()
    {
        return $this->telefono;
    }
    public function obtener_clave1()
    {
        return $this->clave1;
    }
    public function obtener_clave2()
    {
        return $this->clave2;
    }
    public function obtener_tipo_insumos()
    {
        return $this->tipo_insumos;
    }

    public function mostrar_nombre()
    {
        if ($this->nombre != '') {
            echo 'value="' . $this->nombre . '"';
        }
    }
    public function mostrar_identificacion()
    {
        if ($this->identificacion != '') {
            echo 'value="' . $this->identificacion . '"';
        }
    }
    public function mostrar_correo()
    {
        if ($this->correo != '') {
            echo 'value="' . $this->correo . '"';
        }
    }
    public function mostrar_telefono()
    {
        if ($this->telefono != '') {
            echo 'value="' . $this->telefono . '"';
        }
    }
    public function mostrar_tipo_insumos()
    {
        if ($this->tipo_insumos != '') {
            echo 'value="' . $this->tipo_insumos . '"';
        }
    }

    public function mostrar_error_nombre()
    {
        if ($this->error_nombre != '') {
            echo $this->aviso_inicio . $this->error_nombre . $this->aviso_cierre;
        }
    }
    public function mostrar_error_identificacion()
    {
        if ($this->error_identificacion != '') {
            echo $this->aviso_inicio . $this->error_identificacion . $this->aviso_cierre;
        }
    }
    public function mostrar_error_correo()
    {
        if ($this->error_correo != '') {
            echo $this->aviso_inicio . $this->error_correo . $this->aviso_cierre;
        }
    }
    public function mostrar_error_telefono()
    {
        if ($this->error_telefono != '') {
            echo $this->aviso_inicio . $this->error_telefono . $this->aviso_cierre;
        }
    }
    public function mostrar_error_clave1()
    {
        if ($this->error_clave1 != '') {
            echo $this->aviso_inicio . $this->error_clave1 . $this->aviso_cierre;
        }
    }
    public function mostrar_error_clave2()
    {
        if ($this->error_clave2 != '') {
            echo $this->aviso_inicio . $this->error_clave2 . $this->aviso_cierre;
        }
    }
    public function mostrar_error_tipo_insumos()
    {
        if ($this->error_tipo_insumos != '') {
            echo $this->aviso_inicio . $this->error_tipo_insumos . $this->aviso_cierre;
        }
    }

    public function registro_valido()
    {
        if ($this->error_nombre == '' && $this->error_identificacion == '' && $this->error_correo == '' && $this->error_telefono == '' && $this->error_clave1 == '' && $this->error_clave2 == '' && $this->error_tipo_insumos == '') {
            return true;
        } else {
            return false;
        }
    }
}
