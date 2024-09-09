<?php include('includes/header.php'); ?>
<body>
  <!-- Barra de navegación -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Gestión de Ejercicio</a>
      
      <!-- Botón hamburguesa para pantallas pequeñas -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Contenido de la barra de navegación -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto"> <!-- Cambiado de 'ml-auto' a 'ms-auto' para Bootstrap 5 -->
          <li class="nav-item"><a class="nav-link" href="views/register.php">Registro</a></li>
          <li class="nav-item"><a class="nav-link" href="views/login.php">Iniciar Sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenido principal -->
  <div class="container">
    <h1>Bienvenido a la aplicación de gestión de rutinas de ejercicio</h1>
    <p>Registra tus rutinas, monitorea tu progreso y alcanza tus metas.</p>
  </div>

<?php include('includes/footer.php'); ?>
</body>
