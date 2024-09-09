<?php 
include('../includes/header.php'); 
include('../includes/db.php'); 
session_start();
?>

<body>
<div class="container">
  <h2>Crear Nueva Rutina Semanal</h2>
  
  <!-- Formulario para crear la rutina semanal -->
  <form method="POST" action="../api/create_routine.php">
    <div class="form-group">
      <label for="week_start_date">Fecha de Inicio:</label>
      <input type="date" class="form-control" name="week_start_date" id="week_start_date" required>
    </div>

    <?php
    // Obtener todos los ejercicios disponibles
    $stmt = $conn->prepare("SELECT * FROM exercises");
    $stmt->execute();
    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- Repetir para cada día de la semana -->
    <?php for ($i = 0; $i < 7; $i++): ?>
      <h4>Día <?php echo $i + 1; ?>:</h4>
      <div class="form-group">
        <label for="is_rest_day_<?php echo $i; ?>">Día de Descanso:</label>
        <input type="checkbox" name="is_rest_day[<?php echo $i; ?>]" id="is_rest_day_<?php echo $i; ?>" value="1" onclick="toggleExercises(<?php echo $i; ?>)">
      </div>

      <div id="exercises_section_<?php echo $i; ?>">
        <div class="form-group">
          <label>Ejercicios:</label>
          <div class="exercise-checkboxes">
            <?php foreach ($exercises as $exercise): ?>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="exercises[<?php echo $i; ?>][]" value="<?php echo $exercise['id']; ?>" id="exercise_<?php echo $i; ?>_<?php echo $exercise['id']; ?>">
                <label class="form-check-label" for="exercise_<?php echo $i; ?>_<?php echo $exercise['id']; ?>">
                  <?php echo htmlspecialchars($exercise['name']); ?>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Campos para sets, repeticiones y peso por día -->
        <div id="exercise_details_<?php echo $i; ?>">
          <div class="form-group">
            <label for="sets_<?php echo $i; ?>">Sets (para el día):</label>
            <input type="number" class="form-control" name="sets[<?php echo $i; ?>]" id="sets_<?php echo $i; ?>" min="1" required>
          </div>

          <div class="form-group">
            <label for="reps_<?php echo $i; ?>">Repeticiones (para el día):</label>
            <input type="number" class="form-control" name="reps[<?php echo $i; ?>]" id="reps_<?php echo $i; ?>" min="1" required>
          </div>

          <div class="form-group">
            <label for="weight_<?php echo $i; ?>">Peso (para el día en kg):</label>
            <input type="number" class="form-control" name="weight[<?php echo $i; ?>]" id="weight_<?php echo $i; ?>" step="0.1" min="0" required>
          </div>
        </div>
      </div>
      <hr>
    <?php endfor; ?>

    <button type="submit" class="btn btn-primary">Crear Rutina Semanal</button>
  </form>
</div>

<script>
// Función para mostrar/ocultar ejercicios dependiendo si es día de descanso
function toggleExercises(dayIndex) {
  const checkbox = document.getElementById('is_rest_day_' + dayIndex);
  const exercisesSection = document.getElementById('exercises_section_' + dayIndex);
  const exerciseDetails = document.getElementById('exercise_details_' + dayIndex);
  const inputs = exerciseDetails.querySelectorAll('input');

  if (checkbox.checked) {
    exercisesSection.style.display = 'none';
    inputs.forEach(input => input.disabled = true); // Desactivar inputs cuando es día de descanso
  } else {
    exercisesSection.style.display = 'block';
    inputs.forEach(input => input.disabled = false); // Activar inputs cuando no es día de descanso
  }
}
</script>

<?php include('../includes/footer.php'); ?>
</body>
