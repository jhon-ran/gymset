<?php
include('../includes/db.php');

// Recibir datos del formulario
$name = $_POST['name'];
$description = $_POST['description'];

try {
    // Insertar ejercicio en la base de datos
    $stmt = $conn->prepare("INSERT INTO exercises (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    header("Location: ../views/view_exercises.php?message=Ejercicio creado con éxito");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
