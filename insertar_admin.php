<?php
// insert_admin.php

// 1. Conectamos a la base de datos (ajusta los datos según tu entorno local)
$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$base_de_datos = 'inventario';

$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// 2. Verificamos conexión
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

// 3. Definimos los datos del usuario Admin
$usuario_admin = 'admin';
$contrasena_plana = 'admin123';
$nombre = 'Administrador General';
$rol = 'Admin';

// 4. Encriptamos la contraseña usando password_hash()
$contrasena_segura = password_hash($contrasena_plana, PASSWORD_DEFAULT);

// 5. Preparamos e insertamos el usuario
$sql = "INSERT INTO usuarios (usuario, contrasena, nombre, rol) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $usuario_admin, $contrasena_segura, $nombre, $rol);

// 6. Ejecutamos y confirmamos resultado
if ($stmt->execute()) {
    echo "✅ Usuario Admin creado correctamente.";
} else {
    echo "❌ Error al crear usuario: " . $stmt->error;
}

// 7. Cerramos la conexión
$stmt->close();
$conn->close();
?>
