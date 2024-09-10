<?php
include('../includes/db.php');
session_start();


if (isset($_GET['id'])) {
    $routine_id = $_GET['id'];

    try {
        // Iniciar una transacción para asegurar que todas las eliminaciones se completen juntas
        $conn->beginTransaction();

        // Eliminar todos los registros de progress asociados a los ejercicios de la rutina
        $stmt = $conn->prepare("
            DELETE progress 
            FROM progress 
            JOIN routine_exercises ON progress.routine_exercise_id = routine_exercises.id
            JOIN daily_routines ON routine_exercises.daily_routine_id = daily_routines.id
            WHERE daily_routines.weekly_routine_id = :routine_id
        ");
        $stmt->execute(['routine_id' => $routine_id]);

        // Eliminar todos los ejercicios de la rutina diaria asociados
        $stmt = $conn->prepare("
            DELETE routine_exercises 
            FROM routine_exercises 
            JOIN daily_routines ON routine_exercises.daily_routine_id = daily_routines.id
            WHERE daily_routines.weekly_routine_id = :routine_id
        ");
        $stmt->execute(['routine_id' => $routine_id]);

        // Eliminar todas las rutinas diarias asociadas a la rutina semanal
        $stmt = $conn->prepare("DELETE FROM daily_routines WHERE weekly_routine_id = :routine_id");
        $stmt->execute(['routine_id' => $routine_id]);

        // Eliminar la rutina semanal
        $stmt = $conn->prepare("DELETE FROM weekly_routines WHERE id = :routine_id");
        $stmt->execute(['routine_id' => $routine_id]);

        // Confirmar la transacción
        $conn->commit();

        // Redirigir de nuevo a la página de gestión de rutinas con un mensaje de éxito
        header("Location: ../views/manage_routines.php?message=success");
        exit();
    } catch (Exception $e) {
        // Si hay un error, revertir la transacción
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ID de rutina no especificado.";
}
?>
