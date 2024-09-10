<?php  
include('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener información del usuario
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php include('../includes/header.php');?>
<body>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mt-3">
    <h2>Bienvenido, <?php echo htmlspecialchars($user['name']); ?>!</h2>
    <a href="../api/logout.php" class="btn btn-danger">Cerrar sesión</a>
  </div>
  <p>Aquí puedes gestionar tus rutinas de ejercicio y revisar tu progreso.</p>

  <div class="row">
    <div class="col-md-6">
      <h4>Gestión de Rutinas</h4>
      <ul class="list-group">
        <li class="list-group-item"><a href="create_routine.php">Crear Nueva Rutina Semanal</a></li>
        <li class="list-group-item"><a href="enter_progress.php">Ingresar Progreso Diario</a></li>
        <li class="list-group-item"><a href="view_progress.php">Ver Progreso</a></li>
      </ul>
    </div>
    <div class="col-md-6">
      <h4>Gestión de Ejercicios</h4>
      <ul class="list-group">
        <li class="list-group-item"><a href="create_exercise.php">Agregar Nuevo Ejercicio</a></li>
        <li class="list-group-item"><a href="view_exercises.php">Ver y Editar Ejercicios</a></li>
      </ul>
    </div>
  </div>
</div>

<?php include('../includes/footer.php'); ?>
</body>
