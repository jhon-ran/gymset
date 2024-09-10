<?php 
include('../includes/db.php');
session_start();

// Obtener todos los ejercicios de la base de datos
$stmt = $conn->prepare("SELECT * FROM exercises");
$stmt->execute();
$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../includes/header.php');?>

<body>
<div class="container">
  <h2>Ejercicios Disponibles</h2>
  <a href="create_exercise.php" class="btn btn-success mb-3">Agregar Nuevo Ejercicio</a>
  
  <?php if ($exercises): ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($exercises as $exercise): ?>
      <tr>
        <td><?php echo htmlspecialchars($exercise['name']); ?></td>
        <td><?php echo htmlspecialchars($exercise['description']); ?></td>
        <td>
          <a href="edit_exercise.php?id=<?php echo htmlspecialchars($exercise['id']); ?>" class="btn btn-primary btn-sm">Editar</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p>No hay ejercicios disponibles.</p>
  <?php endif; ?>
</div>
<?php include('../includes/footer.php'); ?>
</body>
