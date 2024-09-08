<?php
include('../includes/db.php');
session_start();

$user_id = $_SESSION['user_id'];
$week_start_date = $_POST['week_start_date'];

try {
    // Comenzar una transacción
    $conn->beginTransaction();

    // Insertar la nueva rutina semanal
    $stmt = $conn->prepare("INSERT INTO weekly_routines (user_id, week_start_date) VALUES (:user_id, :week_start_date)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':week_start_date', $week_start_date);
    $stmt->execute();
    $weekly_routine_id = $conn->lastInsertId();

    // Iterar sobre los días de la semana
    for ($i = 0; $i < 7; $i++) {
        // Variable intermedia para el día de la semana
        $day_of_week = $i + 1; // Ahora esta es una variable, no una expresión

        // Insertar una rutina diaria para cada día de la semana
        $stmt = $conn->prepare("INSERT INTO daily_routines (weekly_routine_id, day_of_week) VALUES (:weekly_routine_id, :day_of_week)");
        $stmt->bindParam(':weekly_routine_id', $weekly_routine_id);
        $stmt->bindParam(':day_of_week', $day_of_week); // Usamos la variable intermedia aquí
        $stmt->execute();
        $daily_routine_id = $conn->lastInsertId();

        // Obtener los sets y repeticiones para este día
        $sets = $_POST['sets_day_' . $i];
        $repetitions = $_POST['repetitions_day_' . $i];

        // Procesar los ejercicios seleccionados para ese día
        if (isset($_POST['exercises_' . $i])) {
            foreach ($_POST['exercises_' . $i] as $exercise_id) {
                // Insertar los ejercicios en la rutina diaria
                $stmt = $conn->prepare("INSERT INTO routine_exercises (daily_routine_id, exercise_id, repetitions, weight) 
                                        VALUES (:daily_routine_id, :exercise_id, :repetitions, 0)");
                $stmt->bindParam(':daily_routine_id', $daily_routine_id);
                $stmt->bindParam(':exercise_id', $exercise_id);
                $stmt->bindParam(':repetitions', $repetitions);
                $stmt->execute();
            }
        }
    }

    // Confirmar la transacción
    $conn->commit();
    header("Location: ../views/dashboard.php?message=Rutina creada con éxito");
    exit();
} catch (PDOException $e) {
    // Revertir la transacción en caso de error
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}
?>

