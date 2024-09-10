<?php
include('../includes/db.php');
session_start();


if (!isset($_GET['id'])) {
    die("ID de rutina no especificado.");
}

$routine_id = $_GET['id'];

// Obtener la rutina semanal
$stmt = $conn->prepare("SELECT * FROM weekly_routines WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $routine_id, 'user_id' => $_SESSION['user_id']]);
$weekly_routine = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$weekly_routine) {
    die("Rutina no encontrada o no tiene permisos para editarla.");
}

// Obtener las rutinas diarias asociadas
$stmt = $conn->prepare("SELECT * FROM daily_routines WHERE weekly_routine_id = :weekly_routine_id");
$stmt->execute(['weekly_routine_id' => $routine_id]);
$daily_routines = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener los ejercicios asociados a cada día
$stmt = $conn->prepare("
    SELECT re.id as routine_exercise_id, re.daily_routine_id, re.exercise_id, re.repetitions, re.weight, re.sets, e.name
    FROM routine_exercises re
    JOIN exercises e ON re.exercise_id = e.id
    WHERE re.daily_routine_id IN (SELECT id FROM daily_routines WHERE weekly_routine_id = :weekly_routine_id)
");
$stmt->execute(['weekly_routine_id' => $routine_id]);
$routine_exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los ejercicios disponibles
$stmt = $conn->prepare("SELECT * FROM exercises");
$stmt->execute();
$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include('../includes/header.php');?>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- Barra de navegación -->
    </nav>

    <div class="container mt-5">
        <h2>Editar Rutina Semanal</h2>
        <form action="../api/update_routine.php" method="POST">
            <input type="hidden" name="routine_id" value="<?php echo $routine_id; ?>">

            <!-- Información general de la rutina -->
            <div class="form-group">
                <label for="week_start_date">Fecha de inicio de la semana:</label>
                <input type="date" class="form-control" id="week_start_date" name="week_start_date" value="<?php echo $weekly_routine['week_start_date']; ?>" required>
            </div>

            <!-- Rutinas diarias y ejercicios asociados -->
            <?php foreach ($daily_routines as $daily): ?>
                <h4>Día <?php echo $daily['day_of_week']; ?></h4>
                <input type="hidden" name="daily_routine_ids[]" value="<?php echo $daily['id']; ?>">

                <div class="form-group">
                    <label>Ejercicios para el día <?php echo $daily['day_of_week']; ?>:</label>
                    <div id="exercise_list_day_<?php echo $daily['day_of_week']; ?>">
                        <?php foreach ($routine_exercises as $exercise): ?>
                            <?php if ($exercise['daily_routine_id'] == $daily['id']): ?>
                                <div class="exercise-group mb-3">
                                    <select name="exercises[<?php echo $daily['id']; ?>][]" class="form-control mb-2" required>
                                        <?php foreach ($exercises as $ex): ?>
                                            <option value="<?php echo $ex['id']; ?>" <?php echo ($ex['id'] == $exercise['exercise_id']) ? 'selected' : ''; ?>>
                                                <?php echo $ex['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="number" name="sets[<?php echo $daily['id']; ?>][]" class="form-control mb-2" value="<?php echo $exercise['sets']; ?>" placeholder="Sets" required>
                                    <input type="number" name="repetitions[<?php echo $daily['id']; ?>][]" class="form-control mb-2" value="<?php echo $exercise['repetitions']; ?>" placeholder="Repeticiones" required>
                                    <input type="number" step="0.1" name="weight[<?php echo $daily['id']; ?>][]" class="form-control mb-2" value="<?php echo $exercise['weight']; ?>" placeholder="Peso" required>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addExercise(<?php echo $daily['day_of_week']; ?>)">Agregar ejercicio</button>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script>
        function addExercise(dayOfWeek) {
            const container = document.getElementById('exercise_list_day_' + dayOfWeek);
            const exerciseSelect = `
                <div class="exercise-group mb-3">
                    <select name="exercises[${dayOfWeek}][]" class="form-control mb-2" required>
                        <?php foreach ($exercises as $ex): ?>
                            <option value="<?php echo $ex['id']; ?>"><?php echo $ex['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="sets[${dayOfWeek}][]" class="form-control mb-2" placeholder="Sets" required>
                    <input type="number" name="repetitions[${dayOfWeek}][]" class="form-control mb-2" placeholder="Repeticiones" required>
                    <input type="number" step="0.1" name="weight[${dayOfWeek}][]" class="form-control mb-2" placeholder="Peso" required>
                </div>`;
            container.insertAdjacentHTML('beforeend', exerciseSelect);
        }
    </script>
</body>