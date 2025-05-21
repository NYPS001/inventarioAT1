<?php
// inventario_ti.php

require_once 'includes/db.php';
require_once 'includes/auth.php';

// Verificar que haya sesión
verificarSesion();

// Verificar que el usuario tenga permiso para esta área
if (!tienePermiso(['TI', 'Admin'])) {
    header('Location: dashboard.php');
    exit();
}

// Obtener filtros desde GET
$filtro_colaborador = $_GET['colaborador'] ?? '';
$filtro_serie = $_GET['serie'] ?? '';
$filtro_categoria = $_GET['categoria'] ?? '';
$filtro_modelo = $_GET['modelo'] ?? '';

// Construir la consulta con filtros
$sql = "SELECT * FROM inventario_ti WHERE 1=1";

if (!empty($filtro_colaborador)) {
    $sql .= " AND colaborador LIKE '%" . mysqli_real_escape_string($conn, $filtro_colaborador) . "%'";
}
if (!empty($filtro_serie)) {
    $sql .= " AND numero_serie LIKE '%" . mysqli_real_escape_string($conn, $filtro_serie) . "%'";
}
if (!empty($filtro_categoria)) {
    $sql .= " AND categoria LIKE '%" . mysqli_real_escape_string($conn, $filtro_categoria) . "%'";
}
if (!empty($filtro_modelo)) {
    $sql .= " AND modelo LIKE '%" . mysqli_real_escape_string($conn, $filtro_modelo) . "%'";
}

// Ejecutar consulta
$resultado = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario TI</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <h2>Inventario - Área TI</h2>
    <p><a href="dashboard.php">← Volver al Dashboard</a></p>

    <!-- Filtros -->
    <form method="get">
        <input type="text" name="colaborador" placeholder="Colaborador" value="<?php echo htmlspecialchars($filtro_colaborador); ?>">
        <input type="text" name="serie" placeholder="N. de Serie" value="<?php echo htmlspecialchars($filtro_serie); ?>">
        <input type="text" name="categoria" placeholder="Categoría" value="<?php echo htmlspecialchars($filtro_categoria); ?>">
        <input type="text" name="modelo" placeholder="Modelo" value="<?php echo htmlspecialchars($filtro_modelo); ?>">
        <button type="submit">Filtrar</button>
    </form>

    <!-- Botón para agregar nuevo -->
    <p><a href="agregar_ti.php" class="boton">+ Agregar nuevo</a></p>

    <!-- Tabla -->
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>N. de Serie</th>
                <th>Categoría</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Colaborador</th>
                <th>Fecha Alta</th>
                <th>Mes Mantenimiento</th>
                <th>Fecha Baja</th>
                <th>Próximo Mantenimiento</th>
                <th>Fecha Mantenimiento</th>
                <th>Estado</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['numero_serie']); ?></td>
                    <td><?php echo htmlspecialchars($fila['categoria']); ?></td>
                    <td><?php echo htmlspecialchars($fila['marca']); ?></td>
                    <td><?php echo htmlspecialchars($fila['modelo']); ?></td>
                    <td><?php echo htmlspecialchars($fila['colaborador']); ?></td>
                    <td><?php echo htmlspecialchars($fila['fecha_alta']); ?></td>
                    <td><?php echo htmlspecialchars($fila['mes_mantenimiento']); ?></td>
                    <td><?php echo htmlspecialchars($fila['fecha_baja']); ?></td>
                    <td><?php echo htmlspecialchars($fila['proximo_mantenimiento']); ?></td>
                    <td><?php echo htmlspecialchars($fila['fecha_mantenimiento']); ?></td>
                    <td><?php echo htmlspecialchars($fila['estado']); ?></td>
                    <td>
                        <?php if (!empty($fila['imagen'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($fila['imagen']); ?>" alt="Imagen" width="50">
                        <?php else: ?>
                            Sin imagen
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="editar_ti.php?id=<?php echo $fila['id']; ?>">Editar</a> |
                        <a href="eliminar_ti.php?id=<?php echo $fila['id']; ?>" onclick="return confirm('¿Eliminar este registro?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="13">No se encontraron registros</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
