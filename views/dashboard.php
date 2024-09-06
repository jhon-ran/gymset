<?php 
include('../includes/header.php'); 
include('../includes/db.php');
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener la información del usuario
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<body>
<div class="container">
  <!-- Saludo de bienvenida -->
  <h2>Bienvenido, <?php echo htmlspecialchars($user['name']); ?>!</h2>
  <p>Desde aquí puedes gestionar tus rutinas de ejercicio y seguir tu progreso.</p>
  
  <!-- Opciones del Dashboard -->
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Crear Nueva Rutina</h5>
          <p class="card-text">Crea una nueva rutina semanal y asigna ejercicios.</p>
          <a href="create_routine.php" class="btn btn-primary">Crear Rutina</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Ver Progreso</h5>
          <p class="card-text">Consulta tu progreso semanal y ve tus estadísticas de rendimiento.</p>
          <a href="view_progress.php" class="btn btn-primary">Ver Progreso</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Cerrar Sesión</h5>
          <p class="card-text">Finaliza tu sesión de manera segura.</p>
          <a href="../api/logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include('../includes/footer.php'); ?>
</body>
