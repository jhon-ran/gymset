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
        $weekly_routine_id = $conn->lastInsertId();  // Cambiado a $conn->lastInsertId()

        // Iterar sobre los días de la rutina (1 al 7)
        for ($day_number = 1; $day_number <= 7; $day_number++) {
            $is_rest_day = isset($_POST['rest_days']) && in_array($day_number, $_POST['rest_days']) ? 1 : 0;

            // Inserta la rutina diaria con el indicador de descanso
            $stmt = $conn->prepare("INSERT INTO daily_routines (weekly_routine_id, day_of_week, is_rest_day) VALUES (?, ?, ?)");
            $stmt->execute([$weekly_routine_id, $day_number, $is_rest_day]);
            $daily_routine_id = $conn->lastInsertId();  // Cambiado a $conn->lastInsertId()

            // Si no es un día de descanso, insertar los ejercicios
            if (!$is_rest_day && !empty($_POST['exercises'][$day_number])) {
                foreach ($_POST['exercises'][$day_number] as $exercise_id) {
                    $planned_sets = $_POST['planned_sets'][$day_number][$exercise_id];
                    $planned_repetitions = $_POST['planned_repetitions'][$day_number][$exercise_id];
                    $planned_weight = $_POST['planned_weight'][$day_number][$exercise_id];

                    // Inserta los datos de los ejercicios planeados
                    $stmt = $conn->prepare("INSERT INTO routine_exercises (daily_routine_id, exercise_id, planned_sets, planned_repetitions, planned_weight) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$daily_routine_id, $exercise_id, $planned_sets, $planned_repetitions, $planned_weight]);
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
