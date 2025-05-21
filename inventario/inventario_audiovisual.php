<?php
// 1. Incluir verificación de sesión y conexión a la base de datos
include '../includes/auth.php';
include '../includes/db.php';

// Verificamos que el usuario haya iniciado sesión
verificarSesion();

// Solo Usuarios Audivisual y Admin pueden acceder a esta vista

if (!tienePermiso(['Audiovisual', 'Admin'])) {
    header('Location: acceso_denegado.php'); // Cambiar a una página adecuada
    exit();
}

// 3. Capturar filtros desde el formulario
$filtros = [];
$condiciones = [];

if (!empty($_GET['numero_serie'])) {
    $filtros['numero_serie'] = $_GET['numero_serie'];
    $condiciones[] = "numero_serie LIKE ?";
}
if (!empty($_GET['categoria'])) {
    $filtros['categoria'] = $_GET['categoria'];
    $condiciones[] = "categoria LIKE ?";
}
if (!empty($_GET['marca'])) {
    $filtros['marca'] = $_GET['marca'];
    $condiciones[] = "marca LIKE ?";
}
if (!empty($_GET['modelo'])) {
    $filtros['modelo'] = $_GET['modelo'];
    $condiciones[] = "modelo LIKE ?";
}

// 4. Construir la consulta SQL según los filtros
$sql = "SELECT * FROM inventario_audiovisual";
if ($condiciones) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}
$stmt = $conn->prepare($sql);

// 5. Vincular los valores de los filtros
$parametros = [];
foreach ($filtros as $valor) {
    $parametros[] = "%$valor%";
}
if ($parametros) {
    $stmt->execute($parametros);
} else {
    $stmt->execute();
}

// 6. Obtener los resultados
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario Audiovisual</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>
    <h2>Inventario Audiovisual</h2>

    <!-- 7. Formulario de filtros -->
    <form method="GET">
        <input type="text" name="numero_serie" placeholder="N. de Serie" value="<?= htmlspecialchars($_GET['num_serie'] ?? '') ?>">
        <input type="text" name="categoria" placeholder="Categoría" value="<?= htmlspecialchars($_GET['categoria'] ?? '') ?>">
        <input type="text" name="marca" placeholder="Marca" value="<?= htmlspecialchars($_GET['marca'] ?? '') ?>">
        <input type="text" name="modelo" placeholder="Modelo" value="<?= htmlspecialchars($_GET['modelo'] ?? '') ?>">
        <button type="submit">Filtrar</button>
    </form>

    <!-- 8. Botón para agregar nuevo -->
    <a href="agregar_audiovisual.php">+ Agregar Nuevo Registro</a>

    <!-- 9. Tabla de resultados -->
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>N. de Serie</th>
                <th>Categoría</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Estado</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado): ?>
                <?php foreach ($resultado as $fila): ?>
                    <tr>
                        <td style="color: <?= empty($fila['num_serie']) ? 'red' : 'black' ?>"><?= htmlspecialchars($fila['num_serie']) ?></td>
                        <td style="color: <?= empty($fila['categoria']) ? 'red' : 'black' ?>"><?= htmlspecialchars($fila['categoria']) ?></td>
                        <td style="color: <?= empty($fila['marca']) ? 'red' : 'black' ?>"><?= htmlspecialchars($fila['marca']) ?></td>
                        <td style="color: <?= empty($fila['modelo']) ? 'red' : 'black' ?>"><?= htmlspecialchars($fila['modelo']) ?></td>
                        <td><?= htmlspecialchars($fila['estado']) ?></td>
                        <td>
                            <?php if (!empty($fila['imagen'])): ?>
                                <img src="uploads/<?= htmlspecialchars($fila['imagen']) ?>" alt="Imagen" width="60">
                            <?php else: ?>
                                Sin imagen
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="editar_audiovisual.php?id=<?= $fila['id'] ?>">Editar</a> |
                            <a href="eliminar_audiovisual.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este registro?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No se encontraron registros.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
