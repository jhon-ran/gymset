<?php
include('../includes/db.php');
session_start();

$user_id = $_SESSION['user_id'];
$week_start_date = $_POST['week_start_date'];

// Insertar rutina semanal
$sql_weekly_routine = "INSERT INTO weekly_routines (user_id, week_start_date) VALUES ('$user_id', '$week_start_date')";
$conn->query($sql_weekly_routine);
$weekly_routine_id = $conn->insert_id;

// Insertar rutinas diarias y ejercicios
$days_of_week = 7;
for ($i = 0; $i < $days_of_week; $i++) {
    $day_of_week = $i + 1;
    
    // Insertar rutina diaria
    $sql_daily_routine = "INSERT INTO daily_routines (weekly_routine_id, day_of_week) VALUES ('$weekly_routine_id', '$day_of_week')";
    $conn->query($sql_daily_routine);
    $daily_routine_id = $conn->insert_id;

    // Obtener ejercicios seleccionados para el día
    $exercises = $_POST["exercises_$i"];
    $repetitions = $_POST["repetitions_$i"];
    $weight = $_POST["weight_$i"];

    // Insertar cada ejercicio en la rutina diaria
    foreach ($exercises as $exercise_id) {
        $sql_routine_exercise = "INSERT INTO routine_exercises (daily_routine_id, exercise_id, repetitions, weight) VALUES ('$daily_routine_id', '$exercise_id', '$repetitions', '$weight')";
        $conn->query($sql_routine_exercise);
    }
}

header("Location: ../views/view_progress.php?message=Rutina creada con éxito");
?>
