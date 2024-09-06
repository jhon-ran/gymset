<?php include('../includes/header.php'); ?>
<?php include('../includes/db.php'); ?>

<body>
<div class="container">
  <h2>Crear Rutina Semanal</h2>
  <form method="POST" action="../api/create_routine.php">
    <!-- Selección de fecha de inicio de la rutina semanal -->
    <div class="form-group">
      <label for="week_start_date">Fecha de Inicio de la Semana:</label>
      <input type="date" class="form-control" id="week_start_date" name="week_start_date" required>
    </div>

    <!-- Selección de ejercicios para cada día -->
    <?php
    $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    $result = $conn->query("SELECT id, name FROM exercises");
    $exercises = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($days as $index => $day) {
        echo "<h3>$day</h3>";
        echo "<div class='form-group'>";
        echo "<label for='exercises_$index'>Ejercicios:</label>";
        echo "<select class='form-control' name='exercises_$index"."[]' multiple>";
        foreach ($exercises as $exercise) {
            echo "<option value='" . $exercise['id'] . "'>" . $exercise['name'] . "</option>";
        }
        echo "</select>";
        echo "</div>";
        
        echo "<div class='form-group'>";
        echo "<label for='repetitions_$index'>Repeticiones (por cada ejercicio seleccionado):</label>";
        echo "<input type='number' class='form-control' id='repetitions_$index' name='repetitions_$index' min='1' required>";
        echo "</div>";
        
        echo "<div class='form-group'>";
        echo "<label for='weight_$index'>Peso (kg por cada ejercicio seleccionado):</label>";
        echo "<input type='number' class='form-control' id='weight_$index' name='weight_$index' min='0' step='0.1' required>";
        echo "</div>";
    }
    ?>

    <!-- Botón para enviar el formulario -->
    <button type="submit" class="btn btn-primary">Guardar Rutina</button>
  </form>
</div>
<?php include('../includes/footer.php'); ?>
</body>
