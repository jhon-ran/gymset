<?php include('../includes/header.php'); ?>
<?php include('../includes/db.php'); ?>

<body>
<div class="container">
  <h2>Progreso de Rutina</h2>

  <?php
  session_start();
  $user_id = $_SESSION['user_id'];

  // Obtener las rutinas semanales del usuario
  $sql = "SELECT * FROM weekly_routines WHERE user_id = '$user_id' ORDER BY week_start_date DESC";
  $weekly_routines = $conn->query($sql);

  while ($weekly_routine = $weekly_routines->fetch_assoc()) {
      echo "<h3>Semana del " . $weekly_routine['week_start_date'] . "</h3>";

      $weekly_routine_id = $weekly_routine['id'];

      // Obtener rutinas diarias para cada semana
      $sql_daily = "SELECT * FROM daily_routines WHERE weekly_routine_id = '$weekly_routine_id'";
      $daily_routines = $conn->query($sql_daily);

      while ($daily_routine = $daily_routines->fetch_assoc()) {
          $day_of_week = $daily_routine['day_of_week'];
          $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

          echo "<h4>" . $days[$day_of_week - 1] . "</h4>";

          $daily_routine_id = $daily_routine['id'];

          // Obtener ejercicios de cada rutina diaria
          $sql_exercises = "SELECT exercises.name, routine_exercises.repetitions, routine_exercises.weight 
                            FROM routine_exercises 
                            JOIN exercises ON routine_exercises.exercise_id = exercises.id 
                            WHERE routine_exercises.daily_routine_id = '$daily_routine_id'";

          $exercises = $conn->query($sql_exercises);

          if ($exercises->num_rows > 0) {
              echo "<table class='table table-striped'>";
              echo "<thead><tr><th>Ejercicio</th><th>Repeticiones</th><th>Peso (kg)</th></tr></thead>";
              echo "<tbody>";

              while ($exercise = $exercises->fetch_assoc()) {
                  echo "<tr><td>" . $exercise['name'] . "</td><td>" . $exercise['repetitions'] . "</td><td>" . $exercise['weight'] . "</td></tr>";
              }

              echo "</tbody></table>";
          } else {
              echo "<p>No hay ejercicios registrados para este día.</p>";
          }
      }
  }
  ?>

</div>
<?php include('../includes/footer.php'); ?>
</body>
