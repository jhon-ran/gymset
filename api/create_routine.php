<?php
include('../includes/db.php');
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $week_start_date = $_POST['week_start_date'];
    $user_id = 1; // Suponiendo que el usuario está autenticado y tenemos su ID; puedes ajustar esto según tu lógica de autenticación.
    
    try {
        // Inicia la transacción
        $conn->beginTransaction();

        // Inserta la rutina semanal
        $stmt = $conn->prepare("INSERT INTO weekly_routines (user_id, week_start_date) VALUES (?, ?)");
        $stmt->execute([$user_id, $week_start_date]);
        $weekly_routine_id = $conn->lastInsertId();

        // Verifica si $_POST contiene los días de descanso y los ejercicios
        if (!empty($_POST['exercises'])) {
            foreach ($_POST['exercises'] as $day => $exercise_ids) {
                if (!isset($_POST['rest_days']) || !in_array($day, $_POST['rest_days'])) {  // Verifica si no es un día de descanso
                    // Inserta la rutina diaria
                    $stmt = $conn->prepare("INSERT INTO daily_routines (weekly_routine_id, day_of_week) VALUES (?, ?)");
                    $stmt->execute([$weekly_routine_id, $day + 1]);
                    $daily_routine_id = $conn->lastInsertId();

                    // Inserta los ejercicios para ese día
                    foreach ($exercise_ids as $exercise_id) {
                        $planned_sets = $_POST['planned_sets'][$day][$exercise_id];
                        $planned_repetitions = $_POST['planned_repetitions'][$day][$exercise_id];
                        $planned_weight = $_POST['planned_weight'][$day][$exercise_id];

                        // Inserta los datos de los ejercicios planeados
                        $stmt = $conn->prepare("INSERT INTO routine_exercises (daily_routine_id, exercise_id, planned_sets, planned_repetitions, planned_weight) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$daily_routine_id, $exercise_id, $planned_sets, $planned_repetitions, $planned_weight]);
                    }
                } else {
                    // Inserta un registro para el día de descanso si se desea mantener consistencia
                    $stmt = $conn->prepare("INSERT INTO daily_routines (weekly_routine_id, day_of_week) VALUES (?, ?)");
                    $stmt->execute([$weekly_routine_id, $day + 1]);
                }
            }
        }

        // Confirma la transacción
        $conn->commit();
        header('Location: ../views/dashboard.php?message=Routine created successfully');
    } catch (Exception $e) {
        // Si ocurre un error, se revierte la transacción
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>