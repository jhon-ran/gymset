<?php 

include('../includes/db.php');
session_start();
?>

<?php include('../includes/header.php');?>
<body>
<div class="container">
  <h2>Crear Nuevo Ejercicio</h2>
  <form action="../api/create_exercise.php" method="post">
    <div class="form-group">
      <label for="name">Nombre del Ejercicio:</label>
      <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="form-group">
      <label for="description">Descripción del Ejercicio:</label>
      <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Guardar Ejercicio</button>
  </form>
</div>
<?php include('../includes/footer.php'); ?>
</body>
