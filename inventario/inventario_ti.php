<?php
// inventario_ti.php

require_once '../includes/db.php';
require_once '../includes/auth.php';

// Verificamos que el usuario haya iniciado sesión
verificarSesion();

// Solo Usuarios TI y Admin pueden acceder a esta vista
if (!tienePermiso(['TI', 'Admin'])) {
    header('Location: ../dashboard.php');
    exit();
}

// Inicializamos variables para filtros y condiciones
$colaborador = $_GET['colaborador'] ?? '';
$num_serie = $_GET['num_serie'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$modelo = $_GET['modelo'] ?? '';

$where = [];
$params = [];
$tipos = '';

// Condiciones dinámicas para filtros
if (!empty($colaborador)) {
    $where[] = "colaborador LIKE ?";
    $params[] = "%$colaborador%";
    $tipos .= 's';
}
if (!empty($num_serie)) {
    $where[] = "num_serie LIKE ?";
    $params[] = "%$num_serie%";
    $tipos .= 's';
}
if (!empty($categoria)) {
    $where[] = "categoria LIKE ?";
    $params[] = "%$categoria%";
    $tipos .= 's';
}
if (!empty($modelo)) {
    $where[] = "modelo LIKE ?";
    $params[] = "%$modelo%";
    $tipos .= 's';
}

// Construimos la cláusula WHERE si hay condiciones
$where_sql = '';
if (count($where) > 0) {
    $where_sql = "WHERE " . implode(' AND ', $where);
}

// Consulta preparada con filtros
$sql = "SELECT id, num_serie, categoria, marca, modelo, colaborador, fecha_alta, mes_mantenimiento, fecha_baja, proximo_mantenimiento, fecha_mantenimiento, estado, imagen FROM inventario_ti $where_sql ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);

// Enlazamos parámetros si hay filtros
if (count($params) > 0) {
    mysqli_stmt_bind_param($stmt, $tipos, ...$params);
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario TI</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Marca campos vacíos en rojo */
        .campo-vacio {
            background-color: #f8d7da;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
        img {
            max-width: 80px;
            max-height: 60px;
        }
    </style>
</head>
<body>

<h1>Inventario TI</h1>

<!-- Botón para agregar nuevo registro -->
<p><a href="agregar_ti.php">+ Agregar Nuevo Registro</a> | <a href="dashboard.php">Volver al Dashboard</a></p>

<!-- Formulario de filtros -->
<form method="get" action="inventario_ti.php">
    <label>Colaborador:
        <input type="text" name="colaborador" value="<?= htmlspecialchars($colaborador) ?>">
    </label>
    <label>N. de Serie:
        <input type="text" name="num_serie" value="<?= htmlspecialchars($num_serie) ?>">
    </label>
    <label>Categoría:
        <input type="text" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
    </label>
    <label>Modelo:
        <input type="text" name="modelo" value="<?= htmlspecialchars($modelo) ?>">
    </label>
    <button type="submit">Filtrar</button>
    <a href="inventario_ti.php">Limpiar filtros</a>
</form>

<!-- Tabla de registros -->
<table>
    <thead>
        <tr>
            <th>ID</th>
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
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td class="<?= empty($row['num_serie']) ? 'campo-vacio' : '' ?>"><?= htmlspecialchars($row['num_serie']) ?></td>
                <td class="<?= empty($row['categoria']) ? 'campo-vacio' : '' ?>"><?= htmlspecialchars($row['categoria']) ?></td>
                <td class="<?= empty($row['marca']) ? 'campo-vacio' : '' ?>"><?= htmlspecialchars($row['marca']) ?></td>
                <td class="<?= empty($row['modelo']) ? 'campo-vacio' : '' ?>"><?= htmlspecialchars($row['modelo']) ?></td>
                <td class="<?= empty($row['colaborador']) ? 'campo-vacio' : '' ?>"><?= htmlspecialchars($row['colaborador']) ?></td>
                <td class="<?= empty($row['fecha_alta']) ? 'campo-vacio' : '' ?>"><?= htmlspecialchars($row['fecha_alta']) ?></td>
                <td><?= htmlspecialchars($row['mes_mantenimiento']) ?></td>
                <td><?= htmlspecialchars($row['fecha_baja']) ?></td>
                <td><?= htmlspecialchars($row['proximo_mantenimiento']) ?></td>
                <td><?= htmlspecialchars($row['fecha_mantenimiento']) ?></td>
                <td><?= htmlspecialchars($row['estado']) ?></td>
                <td>
                    <?php if (!empty($row['imagen'])): ?>
                        <img src="uploads/<?= htmlspecialchars($row['imagen']) ?>" alt="Imagen">
                    <?php else: ?>
                        No imagen
                    <?php endif; ?>
                </td>
                <td>
                    <a href="../funciones_ti/editar_ti.php?id=<?= $row['id'] ?>">Editar</a> |
                    <a href="../funciones_ti/eliminar_ti.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este registro?');">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
