<?php
// login.php

// Iniciar sesión
session_start();

// Incluir conexión a la base de datos
require_once 'includes/db.php';

// Inicializar variables de error
$error = '';

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $contrasena = $_POST['contrasena'];

    // Consulta preparada para buscar al usuario
    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $fila = $resultado->fetch_assoc();

        // Verificar la contraseña con password_verify
        if (password_verify($contrasena, $fila['contrasena'])) {
            // Guardar datos del usuario en la sesión
            $_SESSION['usuario'] = $fila['usuario'];
            $_SESSION['nombre'] = $fila['nombre'];
            $_SESSION['rol'] = $fila['rol'];

            // Redirigir al dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "⚠️ Contraseña incorrecta.";
        }
    } else {
        $error = "⚠️ Usuario no encontrado.";
    }

    $stmt->close();
}
?>

<!-- HTML del formulario de login -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Inventario</title>
    <link rel="stylesheet" href="assets/css/estilos.css"> <!-- Puedes agregar CSS personalizado -->
</head>
<body>
    <h2>Iniciar Sesión</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="usuario">Usuario:</label><br>
        <input type="text" name="usuario" id="usuario" required><br><br>

        <label for="contrasena">Contraseña:</label><br>
        <input type="password" name="contrasena" id="contrasena" required><br><br>

        <button type="submit">Ingresar</button>
    </form>
</body>
</html>
