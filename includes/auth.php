<?php
// includes/auth.php

// Inicia sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Función para verificar si hay sesión activa
function verificarSesion() {
    if (!isset($_SESSION['usuario'])) {
        // Si no hay sesión, redirige al login
        header("Location: ../login.php");
        exit();
    }
}

// ✅ Función para verificar si el usuario tiene un rol específico
function verificarRol($rolesPermitidos = []) {
    if (!isset($_SESSION['rol'])) {
        header("Location: ../login.php");
        exit();
    }

    if (empty($rolesPermitidos)) {
        echo "⛔ No se han especificado roles permitidos.";
        exit();
    }

    // Si el rol del usuario no está en los permitidos, lo saca del sistema
    if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
        echo "⛔ Acceso denegado. No tienes permisos para esta sección.";
        exit();
    }
}

// ✅ Función para cerrar sesión
function cerrarSesion() {
    session_start();
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
}
?>

