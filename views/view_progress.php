<?php 
include('../includes/header.php'); 
include('../includes/db.php');
session_start();

$user_id = $_SESSION['user_id'];

// Obtener rutinas semanales del usuario
$stmt = $conn->prepare("SELECT * FROM weekly_routines WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$weekly_routines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
<div class="container">
  <h2>Progreso Semanal</h2>

  <?php foreach ($weekly_routines as $routine): ?>
    <h3>Semana de <?php echo $routine['week_start_date']; ?></h3>

    <?php
    // Obtener los días de la rutina semanal
    $stmt = $conn->prepare("SELECT * FROM daily_routines WHERE weekly_routine_id = :weekly_routine_id");
    $stmt->bindParam(':weekly_routine_id', $routine['id']);
    $stmt->execute();
    $daily_routines = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php foreach ($daily_routines as $daily_routine): ?>
      <h4>Día <?php echo $daily_routine['day_of_week']; ?></h4>

      <?php
      // Obtener el progreso de cada ejercicio del día
      $stmt = $conn->prepare("SELECT p.*, e.name 
                              FROM progress p 
                              JOIN routine_exercises re ON p.routine_exercise_id = re.id 
                              JOIN exercises e ON re.exercise_id = e.id 
                              WHERE re.daily_routine_id = :daily_routine_id");
      $stmt->bindParam(':daily_routine_id', $daily_routine['id']);
      $stmt->execute();
      $progress = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <?php if ($progress): ?>
        <ul>
          <?php foreach ($progress as $entry): ?>
            <li><?php echo htmlspecialchars($entry['name']); ?>: <?php echo htmlspecialchars($entry['repetitions']); ?> repeticiones, <?php echo htmlspecialchars($entry['weight']); ?> kg</li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>No se ha registrado progreso para este día.</p>
      <?php endif; ?>

    <?php endforeach; ?>
    <hr>
  <?php endforeach; ?>

</div>
<?php include('../includes/footer.php'); ?>
</body>
