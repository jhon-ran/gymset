<?php
session_start(); // Inicia la sesión
session_destroy();  // Destruye todas las sesiones activas
header("Location: ../views/login.php"); // Redirige al usuario a la página de inicio de sesión
exit();
?>
