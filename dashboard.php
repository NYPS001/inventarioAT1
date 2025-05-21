
<?php
// ✳ Incluye el archivo que valida la sesión y el rol
require_once 'includes/auth.php';

// ✳ Llama a la función que protege esta página (solo usuarios logueados pueden acceder)
verificar_sesion();

// ✳ Recupera el rol del usuario desde la sesión
$rol = obtener_rol();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Inventario</title>
    <link rel="stylesheet" href="assets/css/estilos.css"> <!-- CSS opcional -->
</head>
<body>
    <h1>Bienvenido al sistema de inventario</h1>

    <!-- ✳ Mostramos el rol del usuario -->
    <p>Tu rol: <strong><?php echo ucfirst($rol); ?></strong></p>

    <!-- ✳ Menú básico según rol -->
    <nav>
        <ul>
            <?php if ($rol === 'admin') : ?>
                <li><a href="#">Gestionar usuarios</a></li>
                <li><a href="#">Ver todos los inventarios</a></li>
            <?php elseif ($rol === 'ti') : ?>
                <li><a href="#">Inventario TI</a></li>
            <?php elseif ($rol === 'audiovisual') : ?>
                <li><a href="#">Inventario Audiovisual</a></li>
            <?php elseif ($rol === 'inmuebles') : ?>
                <li><a href="#">Inventario de Inmuebles</a></li>
            <?php else : ?>
                <li>No tienes permisos para ver esta información.</li>
            <?php endif; ?>
        </ul>
    </nav>
</body>
</html>

