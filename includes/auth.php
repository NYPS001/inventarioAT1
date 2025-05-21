<?php
// ✳ Inicia la sesión PHP para manejar usuarios conectados
session_start();

// ✳ Función para verificar si el usuario está logueado
function verificar_sesion() {
    if (!isset($_SESSION['usuario_id'])) {
        // Si no hay sesión activa, redirige a login
        header("Location: login.php");
        exit();
    }
}

// ✳ Función para obtener el rol del usuario logueado
function obtener_rol() {
    return $_SESSION['usuario_rol'] ?? 'invitado';
}
?>
