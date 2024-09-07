<?php
include('../includes/db.php');

// Recibir datos del formulario
$id = $_POST['id'];
$name = $_POST['name'];
$description = $_POST['description'];

try {
    // Actualizar el ejercicio en la base de datos
    $stmt = $conn->prepare("UPDATE exercises SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    header("Location: ../views/view_exercises.php?message=Ejercicio actualizado con éxito");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
