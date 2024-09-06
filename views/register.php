<?php include('../includes/header.php'); ?>
<body>
<div class="container">
  <h2>Registro de Usuario</h2>
  <form method="POST" action="../api/register_user.php">
    <div class="form-group">
      <label for="name">Nombre:</label>
      <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
      <label for="email">Correo Electrónico:</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
      <label for="password">Contraseña:</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Registrarse</button>
  </form>
</div>
<?php include('../includes/footer.php'); ?>
</body>
