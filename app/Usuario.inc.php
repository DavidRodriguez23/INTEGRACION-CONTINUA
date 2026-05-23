<?php

class Usuario {

    private $id;
    private $nombre;
    private $identificacion;
    private $correo;
    private $telefono;
    private $clave;
    private $estado_membresia;
    private $rol;
    private $estado_usuario;
    private $fecha_registro;
    private $fecha_inicio_membresia;
    private $fecha_fin_membresia;
    private $tipo_insumos;

    public function __construct($id, $nombre, $identificacion, $correo, $telefono, $clave, $estado_membresia, $rol, $estado_usuario, $fecha_registro, $fecha_inicio_membresia, $fecha_fin_membresia, $tipo_insumos) {

        $this->id = $id;
        $this->nombre = $nombre;
        $this->identificacion = $identificacion;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->clave = $clave;
        $this->estado_membresia = $estado_membresia;
        $this->rol = $rol;
        $this->estado_usuario = $estado_usuario;
        $this->fecha_registro = $fecha_registro;
        $this->fecha_inicio_membresia = $fecha_inicio_membresia;
        $this->fecha_fin_membresia = $fecha_fin_membresia;
        $this->tipo_insumos = $tipo_insumos;
    }

    public function obtener_id() {
        return $this->id;
    }
    public function obtener_nombre() {
        return $this->nombre;
    }
    public function obtener_identificacion() {
        return $this->identificacion;
    }
    public function obtener_correo() {
        return $this->correo;
    }
    public function obtener_telefono() {
        return $this->telefono;
    }
    public function obtener_clave() {
        return $this->clave;
    }
    public function obtener_estado_membresia() {
        return $this->estado_membresia;
    }
    public function obtener_rol() {
        return $this->rol;
    }
    public function obtener_estado_usuario() {
        return $this->estado_usuario;
    }
    public function obtener_fecha_registro() {
        return $this->fecha_registro;
    }
    public function obtener_fecha_inicio_membresia() {
        return $this->fecha_inicio_membresia;
    }
    public function obtener_fecha_fin_menbrecia() {
        return $this->fecha_fin_membresia;
    }
    public function obtener_tipo_insumos() {
        return $this->tipo_insumos;
    }

    public function cambiar_nombre($nombre) {
        $this->nombre = $nombre;
    }
    public function cambiar_identificacion($identificacion) {
        $this->identificacion = $identificacion;
    }
    public function cambiar_correo($correo) {
        $this->correo = $correo;
    }
    public function cambiar_telefono($telefono) {
        $this->telefono = $telefono;
    }
    public function cambiar_clave($clave) {
        $this->clave = $clave;
    }
    public function cambiar_estado_menbrecia($estado_membresia) {
        $this->estado_membresia = $estado_membresia;
    }
    public function cambiar_rol($rol) {
        $this->rol = $rol;
    }
    public function cambiar_estado_usuario($estado_usuario) {
        $this->estado_usuario = $estado_usuario;
    }
    public function cambiar_fecha_registro($fecha_registro) {
        $this->fecha_registro = $fecha_registro;
    }
    public function cambiar_fecha_inicio_menbrecia($fecha_inicio_membresia) {
        $this->fecha_inicio_membresia = $fecha_inicio_membresia;
    }
    public function cambiar_fecha_fin_menbrecia($fecha_fin_membresia) {
        $this->fecha_fin_membresia = $fecha_fin_membresia;
    }
    public function cambiar_tipo_insumos($tipo_insumos) {
        $this->tipo_insumos = $tipo_insumos;
    }

}
?>