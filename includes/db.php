<?php
// includes/db.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gymset"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>