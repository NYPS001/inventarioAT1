<?php
// agregar_ti.php

require_once '../includes/db.php';
require_once '../includes/auth.php';

verificarSesion();

if (!tienePermiso(['TI', 'Admin'])) {
    header('Location: ../dashboard.php');
    exit();
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $serie = trim($_POST['numero_serie']);
    $categoria = trim($_POST['categoria']);
    $marca = trim($_POST['marca']);
    $modelo = trim($_POST['modelo']);
    $colaborador = trim($_POST['colaborador']);
    $fecha_alta = trim($_POST['fecha_alta']);
    $mes_mantenimiento = trim($_POST['mes_mantenimiento']);
    $fecha_baja = trim($_POST['fecha_baja']);
    $proximo_mantenimiento = trim($_POST['proximo_mantenimiento']);
    $fecha_mantenimiento = trim($_POST['fecha_mantenimiento']);
    $estado = trim($_POST['estado']);

    // Validaciones básicas
    if (empty($serie) || empty($categoria) || empty($marca) || empty($modelo) || empty($colaborador) || empty($fecha_alta) || empty($fecha_baja)) {
        $errores[] = "Todos los campos obligatorios deben ser completados.";
    }

    // Verificar que N. de Serie no exista
    $query_check = "SELECT id FROM inventario_ti WHERE numero_serie = ?";
    $stmt_check = mysqli_prepare($conn, $query_check);
    mysqli_stmt_bind_param($stmt_check, "s", $serie);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $errores[] = "El número de serie ya existe.";
    }
    mysqli_stmt_close($stmt_check);

    // Subida de imagen
    $imagen_nombre = '';
    if (!empty($_FILES['imagen']['name'])) {
        $imagen_nombre = time() . '_' . basename($_FILES['imagen']['name']);
        $ruta_destino = 'uploads/' . $imagen_nombre;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino);
    }

    // Si no hay errores, insertar en la base de datos
    if (empty($errores)) {
        $query_insert = "INSERT INTO inventario_ti (
            numero_serie, categoria, marca, modelo, colaborador,
            fecha_alta, mes_mantenimiento, fecha_baja,
            proximo_mantenimiento, fecha_mantenimiento, estado, imagen
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_insert = mysqli_prepare($conn, $query_insert);
        mysqli_stmt_bind_param($stmt_insert, "ssssssssssss",
            $serie, $categoria, $marca, $modelo, $colaborador,
            $fecha_alta, $mes_mantenimiento, $fecha_baja,
            $proximo_mantenimiento, $fecha_mantenimiento, $estado, $imagen_nombre
        );

        if (mysqli_stmt_execute($stmt_insert)) {
            header('Location: ../inventario/inventario_ti.php');
            exit();
        } else {
            $errores[] = "Error al insertar registro.";
        }

        mysqli_stmt_close($stmt_insert);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar registro TI</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>
    <h2>Agregar nuevo artículo - Área TI</h2>
    <p><a href="../inventario/inventario_ti.php">← Volver al inventario</a></p>

    <?php if (!empty($errores)): ?>
        <div class="errores">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>N. de Serie*:</label><br>
        <input type="text" name="numero_serie" required><br>

        <label>Categoría*:</label><br>
        <input type="text" name="categoria" required><br>

        <label>Marca*:</label><br>
        <input type="text" name="marca" required><br>

        <label>Modelo*:</label><br>
        <input type="text" name="modelo" required><br>

        <label>Colaborador*:</label><br>
        <input type="text" name="colaborador" required><br>

        <label>Fecha de Alta (DD/MM/AA)*:</label><br>
        <input type="text" name="fecha_alta" required><br>

        <label>Mes de Mantenimiento:</label><br>
        <input type="text" name="mes_mantenimiento"><br>

        <label>Fecha de Baja (DD/MM/AA)*:</label><br>
        <input type="text" name="fecha_baja" required><br>

        <label>Próximo Mantenimiento:</label><br>
        <input type="text" name="proximo_mantenimiento"><br>

        <label>Fecha Mantenimiento:</label><br>
        <input type="text" name="fecha_mantenimiento"><br>

        <label>Estado:</label><br>
        <input type="text" name="estado"><br>

        <label>Imagen:</label><br>
        <input type="file" name="imagen" accept="image/*"><br><br>

        <button type="submit">Guardar registro</button>
    </form>
</body>
</html>
