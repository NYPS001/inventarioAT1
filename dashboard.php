<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<h2>Bienvenido, <?php echo $_SESSION['usuario']; ?> (Rol: <?php echo $_SESSION['rol']; ?>)</h2>
<a href="logout.php">Cerrar sesión</a>
