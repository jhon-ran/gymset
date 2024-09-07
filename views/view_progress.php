<?php 
include('../includes/header.php'); 
include('../includes/db.php');
session_start();

$user_id = $_SESSION['user_id'];

// Obtener las rutinas semanales del usuario utilizando PDO
$stmt = $conn->prepare("SELECT * FROM weekly_routines WHERE user_id = :user_id ORDER BY week_start_date DESC");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$weekly_routines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<body>
<div class="container">
  <h2>Progreso de Rutina</h2>

  <?php
  foreach ($weekly_routines as $weekly_routine) {
      echo "<h3>Semana del " . htmlspecialchars($weekly_routine['week_start_date']) . "</h3>";

      $weekly_routine_id = $weekly_routine['id'];

      // Obtener rutinas diarias para cada semana
      $stmt_daily = $conn->prepare("SELECT * FROM daily_routines WHERE weekly_routine_id = :weekly_routine_id");
      $stmt_daily->bindParam(':weekly_routine_id', $weekly_routine_id);
      $stmt_daily->execute();
      $daily_routines = $stmt_daily->fetchAll(PDO::FETCH_ASSOC);

      foreach ($daily_routines as $daily_routine) {
          $day_of_week = $daily_routine['day_of_week'];
          $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

          echo "<h4>" . $days[$day_of_week - 1] . "</h4>";

          $daily_routine_id = $daily_routine['id'];

          // Obtener ejercicios de cada rutina diaria
          $stmt_exercises = $conn->prepare("SELECT exercises.name, routine_exercises.repetitions, routine_exercises.weight 
                                            FROM routine_exercises 
                                            JOIN exercises ON routine_exercises.exercise_id = exercises.id 
                                            WHERE routine_exercises.daily_routine_id = :daily_routine_id");
          $stmt_exercises->bindParam(':daily_routine_id', $daily_routine_id);
          $stmt_exercises->execute();
          $exercises = $stmt_exercises->fetchAll(PDO::FETCH_ASSOC);

          if ($exercises) {
              echo "<table class='table table-striped'>";
              echo "<thead><tr><th>Ejercicio</th><th>Repeticiones</th><th>Peso (kg)</th></tr></thead>";
              echo "<tbody>";

              foreach ($exercises as $exercise) {
                  echo "<tr><td>" . htmlspecialchars($exercise['name']) . "</td><td>" . htmlspecialchars($exercise['repetitions']) . "</td><td>" . htmlspecialchars($exercise['weight']) . "</td></tr>";
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
