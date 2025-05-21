<?php
// dashboard.php

// Incluir archivos necesarios
require_once 'includes/db.php';
require_once 'includes/auth.php';

echo '<pre>';
print_r($_SESSION);
echo '</pre>';

// Verificar que el usuario esté logueado
verificarSesion();

// Obtener los datos de sesión
$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema de Inventario</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <h2>Bienvenido, <?php echo htmlspecialchars($nombre); ?> (<?php echo htmlspecialchars($rol); ?>)</h2>

    <p><a href="logout.php">Cerrar sesión</a></p>

    <hr>

    <h3>Opciones disponibles:</h3>
    <ul>
        <?php if ($rol === 'Admin' || $rol === 'TI'): ?>
            <li><a href="inventario\inventario_ti.php">Inventario TI</a></li>
        <?php endif; ?>

        <?php if ($rol === 'Admin' || $rol === 'Audiovisual'): ?>
            <li><a href="inventario\inventario_audiovisual.php">Inventario Audiovisual</a></li>
        <?php endif; ?>

        <?php if ($rol === 'Admin' || $rol === 'Inmuebles'): ?>
            <li><a href="inventario\inventario_inmuebles.php">Inventario Inmuebles</a></li>
        <?php endif; ?>

        <?php if ($rol === 'Admin'): ?>
            <li><a href="inventario\admin_usuarios.php">Administrar Usuarios</a></li>
        <?php endif; ?>
    </ul>
</body>
</html>

