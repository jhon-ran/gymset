<?php
//Descomentar para servidor remoto
$host = '127.0.0.1:3306';
$dbname = 'u878617270_gymset';
$username = 'u878617270_gymbro';
$password = '6Q~ipXQZWz';

// Configuración de la base de datos local
//$host = 'localhost';
//$dbname = 'gymset';
//$username = 'usuario';
//$password = 'contraseña';

try {
    // Creación de la conexión PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar el modo de error de PDO para excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
