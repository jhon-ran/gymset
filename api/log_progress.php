<?php
include('../includes/db.php');
session_start();

$routine_exercise_id = $_POST['routine_exercise_id'];
$week_number = $_POST['week_number'];
$repetitions = $_POST['repetitions'];
$weight = $_POST['weight'];

$sql = "INSERT INTO progress (routine_exercise_id, week_number, repetitions, weight) 
        VALUES ('$routine_exercise_id', '$week_number', '$repetitions', '$weight')";

if ($conn->query($sql) === TRUE) {
    echo "Progreso registrado con éxito";
    header("Location: ../views/view_progress.php?message=Progreso registrado con éxito");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
