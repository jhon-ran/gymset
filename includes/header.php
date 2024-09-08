<?php
//url para conexión en hosting
//$url_base = "/xpert/";
//url de base en localhost para concatenar en la navbar y evitar errores de redirección (dinámica)
//$url_base = "http://localhost/xpert/";

//si no existe la variable de sesión usuario_id, se redirige al login
/*if(!isset($_SESSION['usuario_id'])){
    header('Location:'.$url_base.'login.php');
    exit();
}
*/

//print_r($_SESSION['usuario_tipo'])
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Gymset</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <!-- estilo para personalizar -->
        <link rel="stylesheet" href="../../style.css">
        <!-- estilo para personalizar y que index.php puede acceder a él -->
        <link rel="stylesheet" href="style.css">
        <!-- font awesome -->
        <script src="https://kit.fontawesome.com/07ff07af43.js" crossorigin="anonymous"></script>
        <!-- cdn JQuery v.3.7.1-->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <!-- cdn DataTables v.1.12.1 -->
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" />
        <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
        <!-- cdn para Sweet Alert 2, alertas de acciones -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="dashboard.php">Inicio</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="create_routine.php">Crear Rutina Semanal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="view_progress.php">Ver Progreso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="enter_progress.php">Ingresar Progreso Diario</a> <!-- Nuevo enlace -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../api/logout.php">Cerrar Sesión</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header> 
    <main class="container">  