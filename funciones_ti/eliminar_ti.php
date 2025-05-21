<?php
// eliminar_ti.php

require_once '../includes/db.php';
require_once '../includes/auth.php';

// Verificamos que el usuario haya iniciado sesión
verificarSesion();

// Comprobamos que el usuario tenga permiso para eliminar en TI o sea Admin
if (!tienePermiso(['TI', 'Admin'])) {
    header('Location: ../dashboard.php');
    exit();
}

// Verificamos que se reciba un id válido por GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ../inventario/inventario_ti.php');
    exit();
}

$id = intval($_GET['id']);

// Antes de eliminar, obtenemos el nombre de la imagen para borrarla del servidor
$query = "SELECT imagen FROM inventario_ti WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$registro = mysqli_fetch_assoc($resultado);

if (!$registro) {
    // Si no existe el registro, redirigimos a la lista
    header('Location: ../inventario/inventario_ti.php');
    exit();
}

// Si hay imagen asociada, eliminarla físicamente
if (!empty($registro['imagen'])) {
    $rutaImagen = 'uploads/' . $registro['imagen'];
    if (file_exists($rutaImagen)) {
        unlink($rutaImagen); // Borra la imagen
    }
}

// Eliminamos el registro de la base de datos
$query_delete = "DELETE FROM inventario_ti WHERE id = ?";
$stmt_delete = mysqli_prepare($conn, $query_delete);
mysqli_stmt_bind_param($stmt_delete, "i", $id);
mysqli_stmt_execute($stmt_delete);

mysqli_stmt_close($stmt_delete);
mysqli_close($conn);

// Redirigimos a la lista
header('Location: ../inventario/inventario_ti.php');
exit();
?>
