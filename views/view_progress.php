<?php  
include('../includes/db.php');
session_start();

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT p.*, e.name, re.planned_sets, re.planned_repetitions, re.planned_weight
    FROM progress p
    JOIN exercises e ON p.exercise_id = e.id
    JOIN routine_exercises re ON p.exercise_id = re.exercise_id AND p.daily_routine_id = re.daily_routine_id
    WHERE p.user_id = :user_id
    ORDER BY p.created_at DESC
");
$stmt->execute(['user_id' => $user_id]);
$progress_entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include('../includes/header.php');?>
<div class="container mt-5">
    <h2>Progreso de Ejercicio</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Ejercicio</th>
                <th>Sets Planeados</th>
                <th>Repeticiones Planeadas</th>
                <th>Peso Planeado (kg)</th>
                <th>Sets Realizados</th>
                <th>Repeticiones Realizadas</th>
                <th>Peso Realizado (kg)</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($progress_entries as $entry): ?>
                <tr>
                    <td><?php echo htmlspecialchars($entry['name']); ?></td>
                    <td><?php echo htmlspecialchars($entry['planned_sets']); ?></td>
                    <td><?php echo htmlspecialchars($entry['planned_repetitions']); ?></td>
                    <td><?php echo htmlspecialchars($entry['planned_weight']); ?></td>
                    <td><?php echo htmlspecialchars($entry['actual_sets']); ?></td>
                    <td><?php echo htmlspecialchars($entry['actual_repetitions']); ?></td>
                    <td><?php echo htmlspecialchars($entry['actual_weight']); ?></td>
                    <td><?php echo htmlspecialchars($entry['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>