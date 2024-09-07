<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'gymset';
$username = 'usuario';
$password = 'contraseña';

try {
    // Creación de la conexión PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar el modo de error de PDO para excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
