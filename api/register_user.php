<?php
include('../includes/db.php');

// Recibir datos del formulario
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Hasheado de la contraseña
$initial_weight = $_POST['initial_weight'];

try {
    // Preparar la consulta SQL con PDO
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, initial_weight) VALUES (:name, :email, :password, :initial_weight)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':initial_weight', $initial_weight);
    $stmt->execute();

    // Redirigir al usuario al login después del registro
    header("Location: ../views/login.php?message=Usuario registrado con éxito");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
