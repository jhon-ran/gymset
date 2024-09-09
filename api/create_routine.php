<?php
include('../includes/db.php');
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

// Validar datos enviados
$week_start_date = $_POST['week_start_date'];
$user_id = $_SESSION['user_id'];

try {
    $conn->beginTransaction();

    // Insertar en weekly_routines
    $stmt = $conn->prepare("INSERT INTO weekly_routines (user_id, week_start_date) VALUES (:user_id, :week_start_date)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':week_start_date', $week_start_date, PDO::PARAM_STR);
    $stmt->execute();
    $weekly_routine_id = $conn->lastInsertId();

    // Insertar en daily_routines y routine_exercises
    for ($i = 0; $i < 7; $i++) {
        // Verificar si es un día de descanso
        if (isset($_POST['is_rest_day'][$i])) {
            continue; // Saltar este día si es día de descanso
        }

        // Insertar en daily_routines
        $stmt = $conn->prepare("INSERT INTO daily_routines (weekly_routine_id, day_of_week) VALUES (:weekly_routine_id, :day_of_week)");
        $stmt->bindParam(':weekly_routine_id', $weekly_routine_id, PDO::PARAM_INT);
        
        $day_of_week = $i + 1;
        $stmt->bindValue(':day_of_week', $day_of_week, PDO::PARAM_INT); // Usar bindValue para valores calculados
        $stmt->execute();
        $daily_routine_id = $conn->lastInsertId();

        // Insertar los ejercicios seleccionados
        if (isset($_POST['exercises'][$i])) {
            foreach ($_POST['exercises'][$i] as $exercise_id) {
                $sets = $_POST['sets'][$i];
                $reps = $_POST['reps'][$i];
                $weight = $_POST['weight'][$i];

                $stmt = $conn->prepare("INSERT INTO routine_exercises (daily_routine_id, exercise_id, repetitions, sets, weight) VALUES (:daily_routine_id, :exercise_id, :repetitions, :sets, :weight)");
                $stmt->bindParam(':daily_routine_id', $daily_routine_id, PDO::PARAM_INT);
                $stmt->bindParam(':exercise_id', $exercise_id, PDO::PARAM_INT);
                $stmt->bindParam(':repetitions', $reps, PDO::PARAM_INT);
                $stmt->bindParam(':sets', $sets, PDO::PARAM_INT);
                $stmt->bindParam(':weight', $weight, PDO::PARAM_STR);
                $stmt->execute();
            }
        }
    }

    $conn->commit();
    header('Location: ../views/dashboard.php');
    exit();

} catch (Exception $e) {
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}
?>
