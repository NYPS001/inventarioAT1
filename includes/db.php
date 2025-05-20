<?php
$host = "localhost";
$usuario = "root";
$contrasena = ""; // En XAMPP no hay contraseña por defecto
$bd = "InventarioUsuarios";

$conexion = new mysqli($host, $usuario, $contrasena, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
