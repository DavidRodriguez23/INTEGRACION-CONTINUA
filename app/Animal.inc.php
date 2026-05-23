<?php

class Animal
{

    private $id;
    private $titulo;
    private $descripcion;
    private $categoria;
    private $raza;
    private $pureza;
    private $sexo;
    private $tipo_animal;
    private $edad;
    private $peso;
    private $precio;
    private $tipo_precio;
    private $telefono;
    private $correo;
    private $departamento;
    private $municipio;
    private $direccion;
    private $destacado;
    private $premium;
    private $imagenes;
    private $videos;
    private $sugerido;
    private $fecha_fin;
    private $latitud;
    private $longitud;
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
        $pureza,
        $sexo,
        $tipo_animal,
        $edad,
        $peso,
        $precio,
        $tipo_precio,
        $telefono,
        $correo,
        $departamento,
        $municipio,
        $direccion,
        $destacado,
        $premium,
        $imagenes,
        $videos,
        $sugerido,
        $fecha_fin,
        $latitud,
        $longitud,
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
        $this->pureza = $pureza;
        $this->sexo = $sexo;
        $this->tipo_animal = $tipo_animal;
        $this->edad = $edad;
        $this->peso = $peso;
        $this->precio = $precio;
        $this->tipo_precio = $tipo_precio;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->departamento = $departamento;
        $this->municipio = $municipio;
        $this->direccion = $direccion;
        $this->destacado = $destacado;
        $this->premium = $premium;
        $this->imagenes = $imagenes;
        $this->videos = $videos;
        $this->sugerido = $sugerido;
        $this->fecha_fin = $fecha_fin;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->id_usuario = $id_usuario;
        $this->vendido = $vendido;
        $this->soporte_pago = $soporte_pago;
        $this->valor_comision = $valor_comision;
    }

    public function obtener_id()
    {
        return $this->id;
    }
    public function obtener_titulo()
    {
        return $this->titulo;
    }
    public function obtener_descripcion()
    {
        return $this->descripcion;
    }
    public function obtener_categoria()
    {
        return $this->categoria;
    }
    public function obtener_raza()
    {
        return $this->raza;
    }
    public function obtener_pureza()
    {
        return $this->pureza;
    }
    public function obtener_sexo()
    {
        return $this->sexo;
    }
    public function obtener_tipo_animal()
    {
        return $this->tipo_animal;
    }
    public function obtener_edad()
    {
        return $this->edad;
    }
    public function obtener_peso()
    {
        return $this->peso;
    }
    public function obtener_precio()
    {
        return $this->precio;
    }
    public function obtener_tipo_precio()
    {
        return $this->tipo_precio;
    }
    public function obtener_telefono()
    {
        return $this->telefono;
    }
    public function obtener_correo()
    {
        return $this->correo;
    }
    public function obtener_departamento()
    {
        return $this->departamento;
    }
    public function obtener_municipio()
    {
        return $this->municipio;
    }
    public function obtener_direccion()
    {
        return $this->direccion;
    }
    public function esta_destacado()
    {
        return $this->destacado;
    }
    public function es_premium()
    {
        return $this->premium;
    }
    public function obtener_imagenes()
    {
        return $this->imagenes;
    }
    public function obtener_videos()
    {
        return $this->videos;
    }
    public function es_sugerido()
    {
        return $this->sugerido;
    }
    public function obtener_fecha_fin()
    {
        return $this->fecha_fin;
    }
    public function obtener_latitud()
    {
        return $this->latitud;
    }
    public function obtener_longitud()
    {
        return $this->longitud;
    }
    public function obtener_fecha_publicacion()
    {

        return $this->creado_en ?? null;
    }
    public function obtener_id_usuario()
    {
        return $this->id_usuario;
    }
    public function esta_vendido()
    {
        return $this->vendido;
    }
    public function obtener_soporte_pago() { return $this->soporte_pago; }
    public function obtener_valor_comision() { return $this->valor_comision; }

    public function cambiar_titulo($nuevo_titulo)
    {
        $this->titulo = $nuevo_titulo;
    }
    public function cambiar_descripcion($nueva_descripcion)
    {
        $this->descripcion = $nueva_descripcion;
    }
    public function cambiar_categoria($nueva_categoria)
    {
        $this->categoria = $nueva_categoria;
    }
    public function cambiar_raza($nueva_raza)
    {
        $this->raza = $nueva_raza;
    }
    public function cambiar_pureza($nueva_pureza)
    {
        $this->pureza = $nueva_pureza;
    }
    public function cambiar_sexo($nuevo_sexo)
    {
        $this->sexo = $nuevo_sexo;
    }
    public function cambiar_tipo_animal($nuevo_tipo_animal)
    {
        $this->tipo_animal = $nuevo_tipo_animal;
    }
    public function cambiar_edad($nueva_edad)
    {
        $this->edad = $nueva_edad;
    }
    public function cambiar_peso($nuevo_peso)
    {
        $this->peso = $nuevo_peso;
    }
    public function cambiar_precio($nuevo_precio)
    {
        $this->precio = $nuevo_precio;
    }
    public function cambiar_tipo_precio($nuevo_tipo_precio)
    {
        $this->tipo_precio = $nuevo_tipo_precio;
    }
    public function cambiar_telefono($nuevo_telefono)
    {
        $this->telefono = $nuevo_telefono;
    }
    public function cambiar_correo($nuevo_correo)
    {
        $this->correo = $nuevo_correo;
    }
    public function cambiar_departamento($nuevo_departamento)
    {
        $this->departamento = $nuevo_departamento;
    }
    public function cambiar_municipio($nuevo_municipio)
    {
        $this->municipio = $nuevo_municipio;
    }
    public function cambiar_direccion($nueva_direccion)
    {
        $this->direccion = $nueva_direccion;
    }
    public function cambiar_destacado($nuevo_destacado)
    {
        $this->destacado = $nuevo_destacado;
    }
    public function cambiar_premium($nuevo_premium)
    {
        $this->premium = $nuevo_premium;
    }
    public function cambiar_imagenes($nuevas_imagenes)
    {
        $this->imagenes = $nuevas_imagenes;
    }
    public function cambiar_videos($nuevos_videos)
    {
        $this->videos = $nuevos_videos;
    }
    public function cambiar_sugerido($nuevo_sugerido)
    {
        $this->sugerido = $nuevo_sugerido;
    }
    public function cambiar_fecha_fin($nueva_fecha_fin)
    {
        $this->fecha_fin = $nueva_fecha_fin;
    }
    public function cambiar_latitud($nueva_latitud)
    {
        $this->latitud = $nueva_latitud;
    }
    public function cambiar_longitud($nueva_longitud)
    {
        $this->longitud = $nueva_longitud;
    }
    public function cambiar_vendido($nuevo_vendido)
    {
        $this->vendido = $nuevo_vendido;
    }
    public function cambiar_soporte_pago($soporte_pago) { $this->soporte_pago = $soporte_pago; }
    public function cambiar_valor_comision($valor_comision) { $this->valor_comision = $valor_comision; }
}
