<?php 
include('../includes/header.php'); 
include('../includes/db.php');
session_start();

if (!isset($_GET['id'])) {
    header("Location: view_exercises.php?error=No se ha especificado el ejercicio a editar");
    exit();
}

$exercise_id = $_GET['id'];

// Obtener el ejercicio desde la base de datos
$stmt = $conn->prepare("SELECT * FROM exercises WHERE id = :id");
$stmt->bindParam(':id', $exercise_id);
$stmt->execute();
$exercise = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exercise) {
    header("Location: view_exercises.php?error=Ejercicio no encontrado");
    exit();
}
?>

<body>
<div class="container">
  <h2>Editar Ejercicio</h2>
  <form action="../api/edit_exercise.php" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($exercise['id']); ?>">

    <div class="form-group">
      <label for="name">Nombre del Ejercicio:</label>
      <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($exercise['name']); ?>" required>
    </div>

    <div class="form-group">
      <label for="description">Descripción del Ejercicio:</label>
      <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($exercise['description']); ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar Ejercicio</button>
  </form>
</div>
<?php include('../includes/footer.php'); ?>
</body>
