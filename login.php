<?php
session_start();
include("includes/db.php");

$usuario = $_POST['usuario'];
$contrasena = md5($_POST['contrasena']); // igual que en la BD

$sql = "SELECT * FROM inventario_usuarios WHERE nombre_usuario = '$usuario' AND contrasena = '$contrasena'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows == 1) {
    $fila = $resultado->fetch_assoc();
    $_SESSION['usuario'] = $fila['nombre_usuario'];
    $_SESSION['rol'] = $fila['rol'];
    header("Location: dashboard.php");
} else {
    echo "Usuario o contraseña incorrectos.";
}
?>
