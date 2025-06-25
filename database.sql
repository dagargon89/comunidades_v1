CREATE DATABASE IF NOT EXISTS comunidades_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE comunidades_db;

-- ========= SISTEMA DE USUARIOS, ROLES Y ORGANIZACIONES (VERSIÓN FINAL) =========

-- Tabla para las organizaciones (SIMPLIFICADA)
CREATE TABLE organizaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL UNIQUE, -- Se hace único para no tener organizaciones duplicadas
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla para los roles del sistema
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla para los usuarios que accederán al sistema
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    organizacion_id INT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    apellido_paterno VARCHAR(100),
    apellido_materno VARCHAR(100),
    puesto VARCHAR(150),
    telefono VARCHAR(20),
    foto_perfil VARCHAR(255),
    activo BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organizacion_id) REFERENCES organizaciones(id) ON DELETE SET NULL
);

-- Tabla de vínculo para asignar roles a usuarios
CREATE TABLE usuario_roles (
    usuario_id INT NOT NULL,
    rol_id INT NOT NULL,
    PRIMARY KEY (usuario_id, rol_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE
);


-- ========= JERARQUÍA ESTRATÉGICA DEL PROYECTO =========

CREATE TABLE ejes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT
);

CREATE TABLE componentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eje_id INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    FOREIGN KEY (eje_id) REFERENCES ejes(id) ON DELETE CASCADE
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    componente_id INT NOT NULL,
    tipo_producto VARCHAR(100),
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (componente_id) REFERENCES componentes(id) ON DELETE CASCADE
);


-- ========= ACTIVIDADES Y METAS =========

CREATE TABLE actividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    tipo_actividad VARCHAR(100),
    fecha_inicio DATETIME,
    fecha_fin DATETIME,
    lugar VARCHAR(255),
    responsable_id INT,
    modalidad VARCHAR(50),
    estatus VARCHAR(50) DEFAULT 'Programada',
    meta TEXT,
    indicador TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    FOREIGN KEY (responsable_id) REFERENCES usuarios(id) ON DELETE SET NULL
);


-- ========= BENEFICIARIOS =========

CREATE TABLE beneficiarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100) NOT NULL,
    apellido_materno VARCHAR(100),
    fecha_nacimiento DATE,
    sexo VARCHAR(50),
    curp VARCHAR(18) UNIQUE,
    telefono VARCHAR(20),
    email VARCHAR(100),
    escolaridad VARCHAR(100),
    ocupacion VARCHAR(100),
    colonia VARCHAR(255),
    calle_numero VARCHAR(255),
    codigo_postal VARCHAR(10),
    municipio VARCHAR(100),
    estado VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- ========= TABLAS DE VINCULACIÓN =========

CREATE TABLE actividad_beneficiario (
    actividad_id INT NOT NULL,
    beneficiario_id INT NOT NULL,
    fecha_asistencia DATE NOT NULL,
    observaciones TEXT,
    PRIMARY KEY (actividad_id, beneficiario_id, fecha_asistencia),
    FOREIGN KEY (actividad_id) REFERENCES actividades(id) ON DELETE CASCADE,
    FOREIGN KEY (beneficiario_id) REFERENCES beneficiarios(id) ON DELETE CASCADE
);