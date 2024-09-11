<?php
include('../includes/db.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $daily_routine_id = $_POST['daily_routine_id'];
    $day_of_week = $_POST['day_of_week'];
    $user_id = $_SESSION['user_id'];
    $actual_sets = $_POST['actual_sets'];
    $actual_repetitions = $_POST['actual_repetitions'];
    $actual_weight = $_POST['actual_weight'];

    try {
        $conn->beginTransaction();

        foreach ($actual_sets as $exercise_id => $sets) {
            $repetitions = $actual_repetitions[$exercise_id];
            $weight = $actual_weight[$exercise_id];

            // Insertar progreso diario
            $stmt = $conn->prepare("
                INSERT INTO progress (user_id, daily_routine_id, exercise_id, day_of_week, actual_sets, actual_repetitions, actual_weight)
                VALUES (:user_id, :daily_routine_id, :exercise_id, :day_of_week, :actual_sets, :actual_repetitions, :actual_weight)
            ");
            $stmt->execute([
                'user_id' => $user_id,
                'daily_routine_id' => $daily_routine_id,
                'exercise_id' => $exercise_id,
                'day_of_week' => $day_of_week,
                'actual_sets' => $sets,
                'actual_repetitions' => $repetitions,
                'actual_weight' => $weight
            ]);
        }

        $conn->commit();
        header("Location: ../views/view_progress.php?message=success");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>