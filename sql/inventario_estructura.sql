-- --------------------------------------------------
-- Tabla de Usuarios
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    rol ENUM('TI', 'Audiovisual', 'Inmuebles', 'Admin') NOT NULL
);

-- --------------------------------------------------
-- Tabla de Inventario TI
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS inventario_ti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_serie VARCHAR(100) NOT NULL UNIQUE,
    categoria VARCHAR(50) NOT NULL,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(100) NOT NULL,
    colaborador VARCHAR(100) NOT NULL,
    fecha_alta DATE NOT NULL,
    mes_mantenimiento VARCHAR(20),
    fecha_baja DATE,
    proximo_mantenimiento DATE,
    fecha_mantenimiento DATE,
    estado_activo ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    imagen VARCHAR(255)  -- Ruta de imagen en carpeta del servidor
);

-- --------------------------------------------------
-- Tabla de Inventario Audiovisual
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS inventario_audiovisual (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_serie VARCHAR(100) NOT NULL UNIQUE,
    categoria VARCHAR(50) NOT NULL,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(100) NOT NULL,
    estado_activo ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    imagen VARCHAR(255)  -- Ruta de imagen en carpeta del servidor
);

-- --------------------------------------------------
-- Tabla de Inventario Inmuebles
-- --------------------------------------------------

CREATE TABLE IF NOT EXISTS inventario_inmuebles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_serie VARCHAR(100) NOT NULL UNIQUE,
    categoria VARCHAR(50) NOT NULL,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(100) NOT NULL,
    colaborador VARCHAR(100) NOT NULL,
    fecha_alta DATE NOT NULL,
    fecha_baja DATE,
    estado ENUM('Bueno', 'Regular', 'Malo') DEFAULT 'Bueno',
    imagen VARCHAR(255)  -- Ruta de imagen en carpeta del servidor
);
