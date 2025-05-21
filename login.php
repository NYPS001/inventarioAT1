<?php
// ✳ Inicia la sesión para poder trabajar con variables de sesión
session_start();

// ✳ Incluimos el archivo de conexión a la base de datos
require_once 'includes/db.php';

// ✳ Inicializamos una variable para mostrar errores
$error = '';

// ✳ Verificamos si se ha enviado el formulario (por método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ✳ Obtenemos los datos del formulario
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    // ✳ Validamos que los campos no estén vacíos
    if ($usuario !== '' && $password !== '') {

        // ✳ Preparamos la consulta para buscar al usuario
        $sql = "SELECT * FROM inventario_usuarios WHERE usuario = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        // ✳ Encriptamos la contraseña con MD5 (⚠️ Temporal — luego usaremos password_hash())
        $password_md5 = md5($password);

        // ✳ Enlazamos los parámetros a la consulta
        $stmt->bind_param("ss", $usuario, $password_md5);

        // ✳ Ejecutamos la consulta
        $stmt->execute();

        // ✳ Obtenemos el resultado
        $resultado = $stmt->get_result();

        // ✳ Si se encontró un usuario válido
        if ($resultado->num_rows === 1) {
            $usuario_data = $resultado->fetch_assoc();

            // ✳ Guardamos los datos del usuario en la sesión
            $_SESSION['usuario_id'] = $usuario_data['id'];
            $_SESSION['usuario_nombre'] = $usuario_data['nombre'];
            $_SESSION['usuario_rol'] = $usuario_data['rol'];

            // ✳ Redirigimos al dashboard
            header("Location: dashboard.php");
            exit();

        } else {
            // ✳ Usuario o contraseña incorrectos
            $error = "Usuario o contraseña incorrectos.";
        }

        // ✳ Cerramos la consulta
        $stmt->close();

    } else {
        // ✳ Si faltan campos
        $error = "Por favor completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - Inventario</title>
    <link rel="stylesheet" href="assets/css/estilos.css"> <!-- ✳ Si usas CSS personalizado -->
    <style>
        /* ✳ Estilo básico en caso de no tener aún el CSS */
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f8f9fa;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            width: 300px;
            margin: auto;
            box-shadow: 0px 0px 10px #ccc;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #0d6efd;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #084298;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>Iniciar sesión</h2>

    <!-- ✳ Mostramos error si existe -->
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- ✳ Formulario de inicio de sesión -->
    <form method="POST" action="">
        <label for="usuario">Usuario:</label><br>
        <input type="text" name="usuario" id="usuario" required><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" name="password" id="password" required><br>

        <button type="submit">Ingresar</button>
    </form>

</body>
</html>

