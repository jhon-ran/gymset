<?php 
include('../includes/header.php'); 
include('../includes/db.php');
session_start();

$user_id = $_SESSION['user_id'];

// Obtener las rutinas semanales del usuario
$stmt = $conn->prepare("SELECT * FROM weekly_routines WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$weekly_routines = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener la rutina semanal y el día seleccionados, si existen
$selected_weekly_routine_id = $_GET['weekly_routine_id'] ?? $_POST['weekly_routine_id'] ?? null;
$selected_day_of_week = $_GET['day_of_week'] ?? $_POST['day_of_week'] ?? null;
?>

<body>
<div class="container">
  <h2>Ingresar Progreso Diario</h2>
  
  <!-- Seleccionar la rutina semanal -->
  <form method="GET" action="enter_progress.php">
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
    <button type="submit" class="btn btn-primary">Cargar Rutina</button>
  </form>

  <?php if ($selected_weekly_routine_id): 
    // Obtener los días de la rutina semanal seleccionada
    $stmt = $conn->prepare("SELECT * FROM daily_routines WHERE weekly_routine_id = :weekly_routine_id");
    $stmt->bindParam(':weekly_routine_id', $selected_weekly_routine_id);
    $stmt->execute();
    $daily_routines = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <!-- Seleccionar día de la semana -->
  <form method="GET" action="enter_progress.php">
    <input type="hidden" name="weekly_routine_id" value="<?php echo $selected_weekly_routine_id; ?>">
    <div class="form-group">
      <label for="day_of_week">Seleccionar Día:</label>
      <select class="form-control" name="day_of_week" id="day_of_week" required>
        <option value="">--Seleccione un día--</option>
        <?php foreach ($daily_routines as $daily_routine): ?>
          <option value="<?php echo $daily_routine['day_of_week']; ?>" <?php echo ($daily_routine['day_of_week'] == $selected_day_of_week) ? 'selected' : ''; ?>>
            Día <?php echo $daily_routine['day_of_week']; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Cargar Día</button>
  </form>

  <?php 
    if ($selected_day_of_week): 
      // Obtener los ejercicios del día seleccionado
      $stmt = $conn->prepare("SELECT re.id as routine_exercise_id, e.name 
                              FROM routine_exercises re 
                              JOIN exercises e ON re.exercise_id = e.id 
                              WHERE re.daily_routine_id = (SELECT id FROM daily_routines WHERE weekly_routine_id = :weekly_routine_id AND day_of_week = :day_of_week)");
      $stmt->bindParam(':weekly_routine_id', $selected_weekly_routine_id);
      $stmt->bindParam(':day_of_week', $selected_day_of_week);
      $stmt->execute();
      $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <!-- Formulario para ingresar progreso de ejercicios del día seleccionado -->
  <?php foreach ($exercises as $exercise): 
    // Obtener el progreso del ejercicio si ya existe
    $stmt = $conn->prepare("SELECT * FROM progress WHERE routine_exercise_id = :routine_exercise_id ORDER BY progress_date DESC LIMIT 1");
    $stmt->bindParam(':routine_exercise_id', $exercise['routine_exercise_id']);
    $stmt->execute();
    $progress = $stmt->fetch(PDO::FETCH_ASSOC);
  ?>
    <form action="../api/save_progress.php" method="post" class="form-inline">
      <input type="hidden" name="routine_exercise_id" value="<?php echo $exercise['routine_exercise_id']; ?>">
      <input type="hidden" name="weekly_routine_id" value="<?php echo $selected_weekly_routine_id; ?>">
      <input type="hidden" name="day_of_week" value="<?php echo $selected_day_of_week; ?>">
      <h4><?php echo htmlspecialchars($exercise['name']); ?></h4>
      
      <div class="form-group">
        <label for="sets_<?php echo $exercise['routine_exercise_id']; ?>">Sets realizados:</label>
        <input type="number" class="form-control" name="sets" id="sets_<?php echo $exercise['routine_exercise_id']; ?>" value="<?php echo $progress ? $progress['sets'] : ''; ?>" required>
      </div>

      <div class="form-group">
        <label for="repetitions_<?php echo $exercise['routine_exercise_id']; ?>">Repeticiones realizadas por set:</label>
        <input type="number" class="form-control" name="repetitions" id="repetitions_<?php echo $exercise['routine_exercise_id']; ?>" value="<?php echo $progress ? $progress['repetitions'] : ''; ?>" required>
      </div>

      <div class="form-group">
        <label for="weight_<?php echo $exercise['routine_exercise_id']; ?>">Peso utilizado (kg):</label>
        <input type="number" class="form-control" name="weight" id="weight_<?php echo $exercise['routine_exercise_id']; ?>" value="<?php echo $progress ? $progress['weight'] : ''; ?>" step="0.01" required>
      </div>

      <button type="submit" class="btn btn-primary">Guardar Progreso</button>
    </form>
    <hr>
  <?php endforeach; ?>
  <?php endif; ?>

  <?php endif; ?>
</div>
<?php include('../includes/footer.php'); ?>
</body>
