CREATE DATABASE IF NOT EXISTS IT
    DEFAULT CHARACTER SET utf8;

USE IT;

CREATE TABLE usuarios (
    id INT NOT NULL UNIQUE AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    identificacion VARCHAR(50) NOT NULL,
    correo VARCHAR(50) NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    clave VARCHAR(255) NOT NULL,
    estado_membresia TINYINT DEFAULT 0 NOT NULL,
    rol VARCHAR(20) DEFAULT 'cliente' NOT NULL,
    estado_usuario TINYINT DEFAULT 1 NOT NULL,
    fecha_registro DATETIME NOT NULL,
    fecha_inicio_membresia DATETIME NOT NULL,
    fecha_fin_membresia DATETIME NOT NULL,
    tipo_insumos VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE recuperar_clave (
    id INT NOT NULL UNIQUE AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    url_secreta VARCHAR(255) NOT NULL,
    correo VARCHAR(255) NOT NULL UNIQUE,
    fecha DATETIME NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE animales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    titulo VARCHAR(255),
    descripcion TEXT,
    categoria VARCHAR(50),
    raza VARCHAR(50),
    pureza VARCHAR(50),
    sexo VARCHAR(50),
    tipo_animal VARCHAR(50),
    edad VARCHAR(50),
    peso DECIMAL(50,2),
    precio DECIMAL(20,2),
    tipo_precio VARCHAR(10),
    telefono VARCHAR(20),
    correo VARCHAR(100),
    departamento VARCHAR(100),
    municipio VARCHAR(100),
    direccion VARCHAR(255),
    destacado BOOLEAN,
    premium BOOLEAN,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    imagenes TEXT,
    videos TEXT,
    sugerido BOOLEAN,
    vendido TINYINT(1) DEFAULT 0,
    fecha_fin TIMESTAMP,
    latitud DECIMAL(11, 8),
    longitud DECIMAL(11, 8),
    soporte_pago TEXT NULL,
    valor_comision BIGINT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE caballos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    titulo VARCHAR(50) NOT NULL,
    descripcion VARCHAR(300) NOT NULL,
    categoria VARCHAR(250) NOT NULL,
    raza VARCHAR(50) NOT NULL,
    sexo VARCHAR(30) NOT NULL,
    edad VARCHAR(20) NOT NULL,
    peso INT NOT NULL,
    precio BIGINT NOT NULL,
    caracteristicas VARCHAR(255), 
    imagenes JSON,                
    videos JSON,                  
    telefono VARCHAR(30) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    departamento VARCHAR(50) NOT NULL,
    municipio VARCHAR(50) NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    latitud DECIMAL(10,7) NOT NULL,
    longitud DECIMAL(10,7) NOT NULL,
    destacado TINYINT(1) DEFAULT 0,
    premium TINYINT(1) DEFAULT 0,
    sugerido TINYINT(1) DEFAULT 0,
    vendido TINYINT(1) DEFAULT 0,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    terminos TINYINT(1) DEFAULT 0,
    fecha_fin TIMESTAMP,
    soporte_pago TEXT NULL,
    valor_comision BIGINT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE ajustes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(50) NOT NULL UNIQUE,
    valor TEXT NOT NULL
);
-- Inserta o actualiza
INSERT INTO ajustes (clave, valor) VALUES
    ('email_contacto', 'Contacto@gmail.com'),
    ('telefono_contacto', '3142873700'),
    ('direccion_contacto', 'Facatativá – Manzana I lote 1, barrio La Esperanza'),
    ('facebook', ''),
    ('twitter', ''),
    ('instagram', ''),
    ('estado_sitio', 'activo')
ON DUPLICATE KEY UPDATE valor = VALUES(valor);




