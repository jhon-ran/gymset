<?php 
include('../includes/header.php'); 
include('../includes/db.php');
session_start();

$user_id = $_SESSION['user_id'];

// Obtener todos los ejercicios disponibles utilizando PDO
$stmt = $conn->prepare("SELECT * FROM exercises");
$stmt->execute();
$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC); 
?>

<body>
<div class="container">
  <h2>Crear Nueva Rutina Semanal</h2>
  <form action="../api/create_routine.php" method="post">
    <div class="form-group">
      <label for="week_start_date">Fecha de inicio de la semana:</label>
      <input type="date" class="form-control" id="week_start_date" name="week_start_date" required>
    </div>

    <!-- Bloque para seleccionar ejercicios por día -->
    <div class="form-group">
      <?php for ($i = 0; $i < 7; $i++): ?>
      <h3>Día <?php echo $i + 1; ?></h3>

      <!-- Seleccionar múltiples ejercicios -->
      <div class="form-group">
        <label for="exercises_<?php echo $i; ?>">Ejercicios:</label>
        <?php foreach ($exercises as $exercise): ?>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" 
                   name="exercises_<?php echo $i; ?>[]" 
                   value="<?php echo htmlspecialchars($exercise['id']); ?>" 
                   id="exercise_<?php echo $i . '_' . $exercise['id']; ?>">
            <label class="form-check-label" for="exercise_<?php echo $i . '_' . $exercise['id']; ?>">
              <?php echo htmlspecialchars($exercise['name']); ?>
            </label>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Inputs para sets y repeticiones del día -->
      <div class="form-group">
        <label for="sets_day_<?php echo $i; ?>">Sets para el día:</label>
        <input type="number" class="form-control" name="sets_day_<?php echo $i; ?>" required>
      </div>
      <div class="form-group">
        <label for="repetitions_day_<?php echo $i; ?>">Repeticiones por set para el día:</label>
        <input type="number" class="form-control" name="repetitions_day_<?php echo $i; ?>" required>
      </div>

      <hr>
      <?php endfor; ?>
    </div>

    <button type="submit" class="btn btn-primary">Crear Rutina</button>
  </form>
</div>

<?php include('../includes/footer.php'); ?>
</body>
