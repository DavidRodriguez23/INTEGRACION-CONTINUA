<?php

class Caballo {
    private $id;
    private $titulo;
    private $descripcion;
    private $categoria;
    private $raza;
    private $sexo;
    private $edad;
    private $peso;
    private $precio;
    private $caracteristicas; 
    private $imagenes;        
    private $videos;          
    private $telefono;
    private $correo;
    private $departamento;
    private $municipio;
    private $direccion;
    private $latitud;
    private $longitud;
    private $destacado;
    private $premium;
    private $sugerido;
    private $fecha_publicacion;
    private $terminos;
    private $fecha_fin;
    private $id_usuario;
    private $vendido;
    private $soporte_pago;
    private $valor_comision;

    public function __construct(
        $id,
        $titulo,
        $descripcion,
        $categoria,
        $raza,
        $sexo,
        $edad,
        $peso,
        $precio,
        $caracteristicas,
        $imagenes,
        $videos,
        $telefono,
        $correo,
        $departamento,
        $municipio,
        $direccion,
        $latitud,
        $longitud,
        $destacado = 0,
        $premium = 0,
        $sugerido = 0,
        $fecha_publicacion,
        $terminos = 0,
        $fecha_fin,
        $id_usuario,
        $vendido = 0,
        $soporte_pago = null,
        $valor_comision = null
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->categoria = $categoria;
        $this->raza = $raza;
        $this->sexo = $sexo;
        $this->edad = $edad;
        $this->peso = $peso;
        $this->precio = $precio;
        $this->caracteristicas = $caracteristicas;
        $this->imagenes = $imagenes;
        $this->videos = $videos;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->departamento = $departamento;
        $this->municipio = $municipio;
        $this->direccion = $direccion;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->destacado = $destacado;
        $this->premium = $premium;
        $this->sugerido = $sugerido;
        $this->fecha_publicacion = $fecha_publicacion;
        $this->terminos = $terminos;
        $this->fecha_fin = $fecha_fin;
        $this->id_usuario = $id_usuario;
        $this->vendido = $vendido;
        $this->soporte_pago = $soporte_pago;
        $this->valor_comision = $valor_comision;
    }

    // Métodos getters
    public function obtener_id() { return $this->id; }
    public function obtener_titulo() { return $this->titulo; }
    public function obtener_descripcion() { return $this->descripcion; }
    public function obtener_categoria() { return $this->categoria; }
    public function obtener_raza() { return $this->raza; }
    public function obtener_sexo() { return $this->sexo; }
    public function obtener_edad() { return $this->edad; }
    public function obtener_peso() { return $this->peso; }
    public function obtener_precio() { return $this->precio; }
    public function obtener_caracteristicas() { return $this->caracteristicas; }
    public function obtener_imagenes() { return $this->imagenes; }
    public function obtener_videos() { return $this->videos; }
    public function obtener_telefono() { return $this->telefono; }
    public function obtener_correo() { return $this->correo; }
    public function obtener_departamento() { return $this->departamento; }
    public function obtener_municipio() { return $this->municipio; }
    public function obtener_direccion() { return $this->direccion; }
    public function obtener_latitud() { return $this->latitud; }
    public function obtener_longitud() { return $this->longitud; }
    public function obtener_destacado() { return $this->destacado; }
    public function obtener_premium() { return $this->premium; }
    public function obtener_sugerido() { return $this->sugerido; }
    public function obtener_fecha_publicacion() { return $this->fecha_publicacion; }
    public function obtener_terminos() { return $this->terminos; }
    public function obtener_fecha_fin() { return $this->fecha_fin; }
    public function obtener_id_usuario() { return $this->id_usuario; }
    public function esta_vendido() { return $this->vendido; }
    public function obtener_soporte_pago() { return $this->soporte_pago; }
    public function obtener_valor_comision() { return $this->valor_comision; }

    // Métodos setters
    public function cambiar_titulo($titulo) { $this->titulo = $titulo; }
    public function cambiar_descripcion($descripcion) { $this->descripcion = $descripcion; }
    public function cambiar_categoria($categoria) { $this->categoria = $categoria; }
    public function cambiar_raza($raza) { $this->raza = $raza; }
    public function cambiar_sexo($sexo) { $this->sexo = $sexo; }
    public function cambiar_edad($edad) { $this->edad = $edad; }
    public function cambiar_peso($peso) { $this->peso = $peso; }
    public function cambiar_precio($precio) { $this->precio = $precio; }
    public function cambiar_caracteristicas($caracteristicas) { $this->caracteristicas = $caracteristicas; }
    public function cambiar_imagenes($imagenes) { $this->imagenes = $imagenes; }
    public function cambiar_videos($videos) { $this->videos = $videos; }
    public function cambiar_telefono($telefono) { $this->telefono = $telefono; }
    public function cambiar_correo($correo) { $this->correo = $correo; }
    public function cambiar_departamento($departamento) { $this->departamento = $departamento; }
    public function cambiar_municipio($municipio) { $this->municipio = $municipio; }
    public function cambiar_direccion($direccion) { $this->direccion = $direccion; }
    public function cambiar_latitud($latitud) { $this->latitud = $latitud; }
    public function cambiar_longitud($longitud) { $this->longitud = $longitud; }
    public function cambiar_destacado($destacado) { $this->destacado = $destacado; }
    public function cambiar_premium($premium) { $this->premium = $premium; }
    public function cambiar_sugerido($sugerido) { $this->sugerido = $sugerido; }
    public function cambiar_fecha_publicacion($fecha_publicacion) { $this->fecha_publicacion = $fecha_publicacion; }
    public function cambiar_vendido($vendido) { $this->vendido = $vendido; }
    public function cambiar_soporte_pago($soporte_pago) { $this->soporte_pago = $soporte_pago; }
    public function cambiar_valor_comision($valor_comision) { $this->valor_comision = $valor_comision; }


}