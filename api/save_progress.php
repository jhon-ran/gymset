<?php
include('../includes/db.php');
session_start();

$routine_exercise_id = $_POST['routine_exercise_id'];
$sets = $_POST['sets'];
$repetitions = $_POST['repetitions'];
$weight = $_POST['weight'];
$weekly_routine_id = $_POST['weekly_routine_id'];
$day_of_week = $_POST['day_of_week'];

try {
    // Insertar el progreso del ejercicio
    $stmt = $conn->prepare("INSERT INTO progress (routine_exercise_id, week_number, sets, repetitions, weight) 
                            VALUES (:routine_exercise_id, WEEK(NOW()), :sets, :repetitions, :weight)");
    $stmt->bindParam(':routine_exercise_id', $routine_exercise_id);
    $stmt->bindParam(':sets', $sets);
    $stmt->bindParam(':repetitions', $repetitions);
    $stmt->bindParam(':weight', $weight);
    $stmt->execute();

    // Redireccionar de vuelta a la página de ingresar progreso manteniendo la rutina y el día seleccionados
    header("Location: ../views/enter_progress.php?weekly_routine_id=$weekly_routine_id&day_of_week=$day_of_week&message=Progreso guardado con éxito");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
