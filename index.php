<?php
// index.php

session_start();

// Si ya hay sesión activa, redirige al dashboard
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit();
}

// Si no hay sesión, envía al login
header("Location: login.php");
exit();

