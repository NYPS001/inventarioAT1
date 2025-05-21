<?php
// includes/db.php

// Configuración de conexión a la base de datos
$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$base_de_datos = 'inventarioat1';

// Crear conexión con MySQL usando MySQLi
$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// Verificar si hay error de conexión
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

// Opcional: establecer conjunto de caracteres
$conn->set_charset("utf8mb4");

// ✅ Conexión lista para ser utilizada
?>

