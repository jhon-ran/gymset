<?php 
include('../includes/db.php');
session_start();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['exercise_id'])) {
    // Guardar progreso por ejercicio
    $daily_routine_id = $_POST['daily_routine_id'];
    $exercise_id = $_POST['exercise_id'];
    $actual_sets = $_POST['actual_sets'];
    $actual_repetitions = $_POST['actual_repetitions'];
    $actual_weight = $_POST['actual_weight'];
    $user_id = $_SESSION['user_id'];
    $day_of_week = $_POST['day_of_week'];

    // Comprobar si ya existe un progreso para este ejercicio
    $stmt = $conn->prepare("SELECT * FROM progress WHERE user_id = ? AND daily_routine_id = ? AND exercise_id = ? AND day_of_week = ?");
    $stmt->execute([$user_id, $daily_routine_id, $exercise_id, $day_of_week]);
    $existing_progress = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_progress) {
        // Actualizar el progreso existente
        $stmt = $conn->prepare("UPDATE progress SET actual_sets = ?, actual_repetitions = ?, actual_weight = ? WHERE user_id = ? AND daily_routine_id = ? AND exercise_id = ? AND day_of_week = ?");
        $stmt->execute([$actual_sets, $actual_repetitions, $actual_weight, $user_id, $daily_routine_id, $exercise_id, $day_of_week]);
    } else {
        // Insertar nuevo progreso
        $stmt = $conn->prepare("INSERT INTO progress (user_id, daily_routine_id, exercise_id, day_of_week, actual_sets, actual_repetitions, actual_weight, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $daily_routine_id, $exercise_id, $day_of_week, $actual_sets, $actual_repetitions, $actual_weight]);
    }

    // Responder a la solicitud AJAX
    echo json_encode(["message" => "Progreso guardado correctamente para el ejercicio " . htmlspecialchars($_POST['exercise_name']) . "."]);
    exit();
}

// Obtener todas las rutinas semanales creadas por el usuario
$stmt = $conn->prepare("SELECT * FROM weekly_routines WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$weekly_routines = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['weekly_routine_id']) && isset($_POST['day_of_week'])) {
    $weekly_routine_id = $_POST['weekly_routine_id'];
    $day_of_week = $_POST['day_of_week'];

    // Obtener la rutina diaria correspondiente a la rutina semanal y al día seleccionado
    $stmt = $conn->prepare("SELECT * FROM daily_routines WHERE weekly_routine_id = ? AND day_of_week = ?");
    $stmt->execute([$weekly_routine_id, $day_of_week]);
    $daily_routine = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($daily_routine) {
        // Obtener los ejercicios de la rutina diaria
        $stmt = $conn->prepare("SELECT re.*, e.name FROM routine_exercises re JOIN exercises e ON re.exercise_id = e.id WHERE re.daily_routine_id = ?");
        $stmt->execute([$daily_routine['id']]);
        $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cargar el progreso existente si hay alguno
        $progress = [];
        $stmt = $conn->prepare("SELECT * FROM progress WHERE daily_routine_id = ? AND day_of_week = ?");
        $stmt->execute([$daily_routine['id'], $day_of_week]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $progress[$row['exercise_id']] = $row;
        }
    } else {
        echo "Rutina diaria no encontrada.";
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-4">
    <h2>Ingresar Progreso Diario</h2>

    <?php if (isset($weekly_routine_id) && isset($day_of_week) && isset($daily_routine)): ?>
        <!-- Mostrar el formulario para ingresar el progreso del día seleccionado -->
        <?php foreach ($exercises as $exercise): ?>
            <form action="enter_progress.php" method="POST" class="mb-4" onsubmit="saveProgress(event, this);">
                <input type="hidden" name="daily_routine_id" value="<?php echo $daily_routine['id']; ?>">
                <input type="hidden" name="exercise_id" value="<?php echo $exercise['exercise_id']; ?>">
                <input type="hidden" name="day_of_week" value="<?php echo $day_of_week; ?>">
                <input type="hidden" name="exercise_name" value="<?php echo htmlspecialchars($exercise['name']); ?>">

                <h5><?php echo htmlspecialchars($exercise['name']); ?></h5>
                <p><strong>Sets Planeados:</strong> <?php echo htmlspecialchars($exercise['planned_sets']); ?></p>
                <p><strong>Repeticiones Planeadas:</strong> <?php echo htmlspecialchars($exercise['planned_repetitions']); ?></p>
                <p><strong>Peso Planeado:</strong> <?php echo htmlspecialchars($exercise['planned_weight']); ?></p>

                <div class="form-group">
                    <label for="sets_<?php echo $exercise['exercise_id']; ?>">Sets Realizados</label>
                    <input type="number" class="form-control" name="actual_sets" id="sets_<?php echo $exercise['exercise_id']; ?>" value="<?php echo isset($progress[$exercise['exercise_id']]) ? $progress[$exercise['exercise_id']]['actual_sets'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="repetitions_<?php echo $exercise['exercise_id']; ?>">Repeticiones Realizadas</label>
                    <input type="number" class="form-control" name="actual_repetitions" id="repetitions_<?php echo $exercise['exercise_id']; ?>" value="<?php echo isset($progress[$exercise['exercise_id']]) ? $progress[$exercise['exercise_id']]['actual_repetitions'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="weight_<?php echo $exercise['exercise_id']; ?>">Peso Usado</label>
                    <input type="number" step="0.01" class="form-control" name="actual_weight" id="weight_<?php echo $exercise['exercise_id']; ?>" value="<?php echo isset($progress[$exercise['exercise_id']]) ? $progress[$exercise['exercise_id']]['actual_weight'] : ''; ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Progreso</button>
                <div class="result-message" style="display: none;"></div>
            </form>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Formulario para seleccionar la rutina semanal y el día -->
        <form action="enter_progress.php" method="POST">
            <div class="form-group">
                <label for="weekly_routine_id">Selecciona una Rutina Semanal</label>
                <select name="weekly_routine_id" id="weekly_routine_id" class="form-control" required>
                    <?php foreach ($weekly_routines as $routine): ?>
                        <option value="<?php echo $routine['id']; ?>">
                            <?php echo htmlspecialchars($routine['week_start_date']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="day_of_week">Selecciona un Día</label>
                <select name="day_of_week" id="day_of_week" class="form-control" required>
                    <option value="1">Lunes</option>
                    <option value="2">Martes</option>
                    <option value="3">Miércoles</option>
                    <option value="4">Jueves</option>
                    <option value="5">Viernes</option>
                    <option value="6">Sábado</option>
                    <option value="7">Domingo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Seleccionar Rutina</button>
        </form>
    <?php endif; ?>
</div>

<script>
function saveProgress(event, form) {
    event.preventDefault();

    const formData = new FormData(form);
    const resultMessage = form.querySelector('.result-message');

    fetch('enter_progress.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        resultMessage.textContent = data.message;
        resultMessage.style.display = 'block';
        resultMessage.classList.add('alert', 'alert-success');
    })
    .catch(error => {
        resultMessage.textContent = 'Error al guardar el progreso.';
        resultMessage.style.display = 'block';
        resultMessage.classList.add('alert', 'alert-danger');
    });
}
</script>

<?php include('../includes/footer.php'); ?>
