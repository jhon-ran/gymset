<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $routine_id = $_POST['routine_id'];
    $week_start_date = $_POST['week_start_date'];
    $daily_routine_ids = $_POST['daily_routine_ids'];

    try {
        $conn->beginTransaction();

        // Actualizar la rutina semanal
        $stmt = $conn->prepare("UPDATE weekly_routines SET week_start_date = :week_start_date WHERE id = :routine_id");
        $stmt->execute(['week_start_date' => $week_start_date, 'routine_id' => $routine_id]);

        // Actualizar los ejercicios de cada día
        foreach ($daily_routine_ids as $daily_id) {
            if (isset($_POST['exercises'][$daily_id])) {
                $exercises = $_POST['exercises'][$daily_id];
                $sets = $_POST['sets'][$daily_id];
                $repetitions = $_POST['repetitions'][$daily_id];
                $weights = $_POST['weight'][$daily_id];

                foreach ($exercises as $index => $exercise_id) {
                    $set_count = $sets[$index];
                    $rep_count = $repetitions[$index];
                    $weight_value = $weights[$index];

                    // Actualizar o insertar cada ejercicio de la rutina
                    $stmt = $conn->prepare("UPDATE routine_exercises SET exercise_id = :exercise_id, sets = :sets, repetitions = :repetitions, weight = :weight WHERE daily_routine_id = :daily_routine_id AND exercise_id = :exercise_id");
                    $stmt->execute([
                        'exercise_id' => $exercise_id,
                        'sets' => $set_count,
                        'repetitions' => $rep_count,
                        'weight' => $weight_value,
                        'daily_routine_id' => $daily_id
                    ]);
                }
            }
        }

        $conn->commit();
        header("Location: ../views/manage_routines.php?message=success");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
