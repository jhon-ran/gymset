<?php include('../includes/header.php'); ?>
<body>
<div class="container">
  <h2>Iniciar Sesión</h2>
  <form method="POST" action="../api/login_user.php">
    <!-- Campo de correo electrónico -->
    <div class="form-group">
      <label for="email">Correo Electrónico:</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>

    <!-- Campo de contraseña -->
    <div class="form-group">
      <label for="password">Contraseña:</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <!-- Botón para enviar el formulario de inicio de sesión -->
    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
  </form>

  <!-- Mensaje de error (si existe) -->
  <?php
  if (isset($_GET['error'])) {
    echo "<div class='alert alert-danger mt-3'>" . htmlspecialchars($_GET['error']) . "</div>";
  }
  ?>
</div>
<?php include('../includes/footer.php'); ?>
</body>
