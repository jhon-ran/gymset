<?php 
include('../includes/header.php'); 
include('../includes/db.php');
session_start();

$user_id = $_SESSION['user_id'];

// Obtener todas las rutinas semanales del usuario
$stmt = $conn->prepare("SELECT * FROM weekly_routines WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$weekly_routines = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener la rutina semanal seleccionada
$selected_weekly_routine_id = $_GET['weekly_routine_id'] ?? null;
?>

<body>
<div class="container">
  <h2>Ver Progreso Semanal</h2>
  
  <!-- Selección de la rutina semanal -->
  <form method="GET" action="view_progress.php">
    <div class="form-group">
      <label for="weekly_routine_id">Seleccionar Rutina Semanal:</label>
      <select class="form-control" name="weekly_routine_id" id="weekly_routine_id" required>
        <option value="">--Seleccione una rutina--</option>
        <?php foreach ($weekly_routines as $routine): ?>
          <option value="<?php echo $routine['id']; ?>" <?php echo ($routine['id'] == $selected_weekly_routine_id) ? 'selected' : ''; ?>>
            Semana de <?php echo $routine['week_start_date']; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Cargar Progreso</button>
  </form>

  <?php if ($selected_weekly_routine_id): 
    // Obtener todos los días de la rutina semanal seleccionada, incluyendo días de descanso
    $stmt = $conn->prepare("SELECT * FROM daily_routines WHERE weekly_routine_id = :weekly_routine_id");
    $stmt->bindParam(':weekly_routine_id', $selected_weekly_routine_id);
    $stmt->execute();
    $daily_routines = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <?php foreach ($daily_routines as $daily_routine): ?>
    <h3>Día <?php echo $daily_routine['day_of_week']; ?> 
      <?php if ($daily_routine['is_rest_day']): ?>
        - Día de Descanso
      <?php endif; ?>
    </h3>

  <?php if (!$daily_routine['is_rest_day']): ?>
    <?php
    // Obtener los ejercicios de la rutina diaria
    $stmt = $conn->prepare("SELECT re.id as routine_exercise_id, e.name 
                            FROM routine_exercises re 
                            JOIN exercises e ON re.exercise_id = e.id 
                            WHERE re.daily_routine_id = :daily_routine_id");
    $stmt->bindParam(':daily_routine_id', $daily_routine['id']);
    $stmt->execute();
    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

      <table class="table">
        <thead>
          <tr>
            <th>Ejercicio</th>
            <th>Sets</th>
            <th>Repeticiones</th>
            <th>Peso (kg)</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($exercises as $exercise): 
            // Obtener el progreso del ejercicio
            $stmt = $conn->prepare("SELECT * FROM progress WHERE routine_exercise_id = :routine_exercise_id ORDER BY progress_date ASC");
            $stmt->bindParam(':routine_exercise_id', $exercise['routine_exercise_id']);
            $stmt->execute();
            $progresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
          ?>

          <?php foreach ($progresses as $progress): ?>
            <tr>
              <td><?php echo htmlspecialchars($exercise['name']); ?></td>
              <td><?php echo htmlspecialchars($progress['sets']); ?></td>
              <td><?php echo htmlspecialchars($progress['repetitions']); ?></td>
              <td><?php echo htmlspecialchars($progress['weight']); ?></td>
              <td><?php echo htmlspecialchars($progress['progress_date']); ?></td>
            </tr>
          <?php endforeach; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Este día es de descanso, no se requiere progreso.</p>
    <?php endif; ?>

  <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php include('../includes/footer.php'); ?>
</body>
