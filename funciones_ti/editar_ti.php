<?php
// editar_ti.php

require_once '../includes/db.php';
require_once '../includes/auth.php';

verificarSesion();

if (!tienePermiso(['TI', 'Admin'])) {
    header('Location: dashboard.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: ../inventario/inventario_ti.php');
    exit();
}

$id = intval($_GET['id']);
$errores = [];

// Obtener datos actuales
$query = "SELECT * FROM inventario_ti WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$registro = mysqli_fetch_assoc($resultado);

if (!$registro) {
    echo "Registro no encontrado.";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    if (empty($serie) || empty($categoria) || empty($marca) || empty($modelo) || empty($colaborador) || empty($fecha_alta) || empty($fecha_baja)) {
        $errores[] = "Completa todos los campos obligatorios.";
    }

    // Validar que N. de serie no esté repetido
    $query_check = "SELECT id FROM inventario_ti WHERE numero_serie = ? AND id != ?";
    $stmt_check = mysqli_prepare($conn, $query_check);
    mysqli_stmt_bind_param($stmt_check, "si", $serie, $id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $errores[] = "Ya existe un artículo con ese número de serie.";
    }
    mysqli_stmt_close($stmt_check);

    // Imagen nueva (opcional)
    $imagen_nombre = $registro['imagen'];
    if (!empty($_FILES['imagen']['name'])) {
        $imagen_nombre = time() . '_' . basename($_FILES['imagen']['name']);
        $ruta_destino = 'uploads/' . $imagen_nombre;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino);
    }

    // Actualizar si no hay errores
    if (empty($errores)) {
        $query_update = "UPDATE inventario_ti SET
            numero_serie = ?, categoria = ?, marca = ?, modelo = ?, colaborador = ?,
            fecha_alta = ?, mes_mantenimiento = ?, fecha_baja = ?,
            proximo_mantenimiento = ?, fecha_mantenimiento = ?, estado = ?, imagen = ?
            WHERE id = ?";

        $stmt_update = mysqli_prepare($conn, $query_update);
        mysqli_stmt_bind_param($stmt_update, "ssssssssssssi",
            $serie, $categoria, $marca, $modelo, $colaborador,
            $fecha_alta, $mes_mantenimiento, $fecha_baja,
            $proximo_mantenimiento, $fecha_mantenimiento, $estado, $imagen_nombre,
            $id
        );

        if (mysqli_stmt_execute($stmt_update)) {
            header('Location: ../inventario/inventario_ti.php');
            exit();
        } else {
            $errores[] = "Error al actualizar registro.";
        }

        mysqli_stmt_close($stmt_update);
    }
}



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar registro TI</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>
    <h2>Editar artículo - Área TI</h2>
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
        <input type="text" name="numero_serie" value="<?php echo htmlspecialchars($registro['numero_serie']); ?>" required><br>

        <label>Categoría*:</label><br>
        <input type="text" name="categoria" value="<?php echo htmlspecialchars($registro['categoria']); ?>" required><br>

        <label>Marca*:</label><br>
        <input type="text" name="marca" value="<?php echo htmlspecialchars($registro['marca']); ?>" required><br>

        <label>Modelo*:</label><br>
        <input type="text" name="modelo" value="<?php echo htmlspecialchars($registro['modelo']); ?>" required><br>

        <label>Colaborador*:</label><br>
        <input type="text" name="colaborador" value="<?php echo htmlspecialchars($registro['colaborador']); ?>" required><br>

        <label>Fecha de Alta*:</label><br>
        <input type="text" name="fecha_alta" value="<?php echo htmlspecialchars($registro['fecha_alta']); ?>" required><br>

        <label>Mes Mantenimiento:</label><br>
        <input type="text" name="mes_mantenimiento" value="<?php echo htmlspecialchars($registro['mes_mantenimiento']); ?>"><br>

        <label>Fecha de Baja*:</label><br>
        <input type="text" name="fecha_baja" value="<?php echo htmlspecialchars($registro['fecha_baja']); ?>" required><br>

        <label>Próximo Mantenimiento:</label><br>
        <input type="text" name="proximo_mantenimiento" value="<?php echo htmlspecialchars($registro['proximo_mantenimiento']); ?>"><br>

        <label>Fecha Mantenimiento:</label><br>
        <input type="text" name="fecha_mantenimiento" value="<?php echo htmlspecialchars($registro['fecha_mantenimiento']); ?>"><br>

        <label>Estado:</label><br>
        <input type="text" name="estado" value="<?php echo htmlspecialchars($registro['estado']); ?>"><br>

        <label>Imagen actual:</label><br>
        <?php if ($registro['imagen']): ?>
            <img src="uploads/<?php echo $registro['imagen']; ?>" alt="Imagen actual" width="150"><br>
        <?php endif; ?>
        <label>Cambiar imagen:</label><br>
        <input type="file" name="imagen" accept="image/*"><br><br>

        <button type="submit">Guardar cambios</button>
    </form>
</body>
</html>
