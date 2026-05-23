<?php
include_once 'RepositorioUsuario.inc.php';

class ValidadorLogin {

    private $usuario;
    private $error;

    public function __construct($correo, $clave, $conexion){
        $this -> error = "";

        // Verificar si el email y la clave están definidos
        if (!$this -> variable_iniciada($correo) || !$this -> variable_iniciada($clave)) {
            $this -> usuario = null;
            $this -> error = "Debes introducir tu email y tu contraseña";
        } else {
            // Obtener usuario desde el repositorio
            $this -> usuario = RepositorioUsuario::obtener_usuario_por_correo($conexion, $correo);
            // Verificar si el usuario existe y la contraseña es correcta
            if (is_null($this -> usuario) || !password_verify($clave, $this -> usuario->obtener_clave())) {
                $this -> error = "Correo o contraseña incorrectos";
            }
        }
    }

    // Verifica si una variable está iniciada y no está vacía
    private function variable_iniciada($variable) {
        return isset($variable) && !empty($variable);
    }

    // Obtener el objeto usuario
    public function obtener_usuario(){
        return $this -> usuario;
    }

    // Obtener el mensaje de error
    public function obtener_error(){
        return $this -> error;
    }

    // Mostrar el mensaje de error en una alerta
    public function mostrar_error(){
        // Ahora muestra la alerta solo si hay error
        if ($this -> error !== "") {
            echo "<br><div class='alert alert-danger' role='alert'>";
            echo $this -> error;
            echo "</div><br>";
        }
    }
}


?>